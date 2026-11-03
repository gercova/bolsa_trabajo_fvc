<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LibraryAccessLog;
use App\Models\StudyProgram;
use App\Services\AcademicLibraryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibraryReaderController extends Controller
{
    /**
     * Portal principal de la Biblioteca Virtual (/biblioteca).
     */
    public function index(Request $request): InertiaResponse
    {
        $selectedCareer = $request->query('career', 'all');
        $searchQuery = trim((string) $request->query('q', ''));
        $userId = auth()->id();
        $baseQuery = Book::with('studyProgram')
            ->where('is_active', true)
            ->career($selectedCareer)
            ->search($searchQuery);

        $this->attachFavoriteFlag($baseQuery, $userId);

        $featuredBooks = (clone $baseQuery)
            ->orderByDesc('views_count')
            ->latest('id')
            ->take(12)
            ->get();

        $recentBooks = (clone $baseQuery)
            ->latest('id')
            ->paginate(18)
            ->withQueryString();

        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();
        $totalBooks = Book::where('is_active', true)->count();
        $user = auth()->user();
        $favoriteBooks = $user
            ? $user->favoriteBooks()->with('studyProgram')->latest()->take(10)->get()
            : collect();

        $recentlyReadBookIds = $user
            ? LibraryAccessLog::where('user_id', $user->id)
                ->recent()
                ->pluck('book_id')
                ->unique()
                ->take(5)
            : collect();

        if ($recentlyReadBookIds->isNotEmpty()) {
            $recentReadQuery = Book::with('studyProgram')->whereIn('id', $recentlyReadBookIds);
            $this->attachFavoriteFlag($recentReadQuery, $userId);
            $recentlyReadBooks = $recentReadQuery->get();
        } else {
            $recentlyReadBooks = collect();
        }

        return Inertia::render('Library/Home', [
            'user' => $user,
            'favoriteBooks' => $favoriteBooks,
            'recentlyReadBooks' => $recentlyReadBooks,
            'featuredBooks' => $featuredBooks,
            'recentBooks' => $recentBooks,
            'studyPrograms' => $studyPrograms,
            'selectedCareer' => $selectedCareer,
            'searchQuery' => $searchQuery,
            'totalBooks' => $totalBooks,
        ]);
    }

    /**
     * Buscador avanzado del catálogo (/biblioteca/buscar).
     */
    public function search(Request $request): InertiaResponse
    {
        $selectedCareer = $request->query('career', 'all');
        $selectedCategory = $request->query('category', 'all');
        $searchQuery = trim((string) $request->query('q', ''));
        $userId = auth()->id();
        $query = Book::with('studyProgram')
            ->where('is_active', true)
            ->career($selectedCareer)
            ->category($selectedCategory)
            ->search($searchQuery)
            ->latest('id');

        $this->attachFavoriteFlag($query, $userId);

        $books = $query->paginate(24)->withQueryString();
        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Library/Search', [
            'books' => $books,
            'studyPrograms' => $studyPrograms,
            'selectedCareer' => $selectedCareer,
            'selectedCategory' => $selectedCategory,
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Colección personal de favoritos del lector (/biblioteca/mi-biblioteca).
     */
    public function myLibrary(Request $request): InertiaResponse
    {
        $userId = auth()->id();
        $selectedCareer = $request->query('career', 'all');
        $searchQuery = trim((string) $request->query('q', ''));

        $query = Book::with('studyProgram')
            ->where('is_active', true)
            ->whereExists(function ($sub) use ($userId) {
                $sub->select(DB::raw(1))
                    ->from('book_favorites')
                    ->whereColumn('book_favorites.book_id', 'books.id')
                    ->where('book_favorites.user_id', $userId);
            })
            ->career($selectedCareer)
            ->search($searchQuery)
            ->latest('id');

        $this->attachFavoriteFlag($query, $userId);

        $books = $query->paginate(24)->withQueryString();
        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Library/Favorites', [
            'books' => $books,
            'studyPrograms' => $studyPrograms,
            'selectedCareer' => $selectedCareer,
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Agregar o quitar un libro de favoritos (/biblioteca/favorito/{book}).
     */
    public function toggleFavorite(Request $request, Book $book): JsonResponse
    {
        $userId = auth()->id();
        if (! $userId) {
            return response()->json(['success' => false, 'message' => 'No autenticado.'], 401);
        }

        $exists = DB::table('book_favorites')
            ->where('user_id', $userId)
            ->where('book_id', $book->id)
            ->exists();

        if ($exists) {
            DB::table('book_favorites')
                ->where('user_id', $userId)
                ->where('book_id', $book->id)
                ->delete();
            $favorited = false;
        } else {
            DB::table('book_favorites')->insert([
                'user_id' => $userId,
                'book_id' => $book->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $favorited = true;
        }

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
        ]);
    }

    /**
     * Display the interactive document reader view (/biblioteca/leer/{book:slug}).
     * Automatically heals legacy landing-page URLs into direct PDF links and logs reader activity.
     */
    public function read(Request $request, Book $book, AcademicLibraryService $academicService): InertiaResponse
    {
        $user = $request->user();

        if (! $book->is_active && (! $user || ! in_array($user->role, ['Admin', 'Administrador', 'Director', 'Docente', 'Bibliotecario']))) {
            abort(404, 'El documento solicitado no se encuentra disponible actualmente.');
        }

        $book->loadMissing('studyProgram');

        // Auto-reparación en caliente si aún no tiene copia local
        if (empty($book->file_path) && ! empty($book->external_url)) {
            $currentUrl = trim((string) $book->external_url);
            $normalizedBinary = $this->toDirectBinaryPdfUrl($currentUrl, $academicService);

            if (! $academicService->isDirectDocumentOrPdfUrl($normalizedBinary, false)) {
                $resolvedUrl = $academicService->resolveDirectDocumentLink($currentUrl);
                if ($resolvedUrl !== '' && $resolvedUrl !== $currentUrl) {
                    $normalizedBinary = $this->toDirectBinaryPdfUrl($resolvedUrl, $academicService);
                }
            }

            if ($normalizedBinary !== '' && $normalizedBinary !== $currentUrl) {
                $book->update(['external_url' => Str::limit($normalizedBinary, 495)]);
                $book->refresh();
            }
        }

        // Registrar visualización en LibraryAccessLog
        $this->recordReaderAccess($request, $book);

        // Todas las URLs apuntan al stream interno para que el usuario nunca tenga que salir de la app
        $streamUrl = route('biblioteca.stream', $book->slug);

        $relatedBooks = Book::with('studyProgram')
            ->where('is_active', true)
            ->where('id', '!=', $book->id)
            ->when($book->study_program_id, fn ($q) => $q->where('study_program_id', $book->study_program_id))
            ->latest('id')
            ->take(6)
            ->get();

        $isFavorite = false;
        if ($user) {
            $isFavorite = DB::table('book_favorites')
                ->where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->exists();
        }

        return Inertia::render('Library/Reader', [
            'book' => $book,
            'streamUrl' => $streamUrl,
            'directPdfUrl' => $streamUrl, // Siempre interno para evitar bloqueos y salidas de la app
            'relatedBooks' => $relatedBooks,
            'isFavorite' => $isFavorite,
        ]);
    }

    /**
     * Stream the PDF binary directly inline (/biblioteca/stream/{book:slug}).
     * Serves local files or proxies external repository PDFs stripping X-Frame-Options
     * and converting HTML galley wrappers into raw PDF streams.
     */
    public function stream(Request $request, Book $book, AcademicLibraryService $academicService): BinaryFileResponse|StreamedResponse|RedirectResponse|Response
    {
        $user = $request->user();

        if (! $book->is_active && (! $user || ! in_array($user->role, ['Admin', 'Administrador', 'Director', 'Docente', 'Bibliotecario']))) {
            abort(404);
        }

        $safeFilename = (Str::slug(Str::limit($book->title, 80)) ?: 'documento-academico-'.$book->id).'.pdf';
        $dispositionType = $request->boolean('download') ? 'attachment' : 'inline';

        // ── CASO 1: Archivo PDF ya disponible en el disco local ──
        if (! empty($book->file_path) && Storage::disk('public')->exists($book->file_path)) {
            $fullPath = Storage::disk('public')->path($book->file_path);

            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => $dispositionType.'; filename="'.$safeFilename.'"',
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'public, max-age=86400',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN',
            ]);
        }

        // ── CASO 2: Recurso Académico Externo ──
        if (empty($book->external_url)) {
            abort(404, 'El documento no cuenta con un archivo o enlace asociado.');
        }

        $targetUrl = trim((string) $book->external_url);

        if (! $academicService->isDirectDocumentOrPdfUrl($targetUrl, false)) {
            $resolved = $academicService->resolveDirectDocumentLink($targetUrl);
            if ($resolved !== '' && $resolved !== $targetUrl) {
                $targetUrl = $resolved;
                $book->update(['external_url' => Str::limit($resolved, 495)]);
            }
        }

        $binaryCandidateUrl = $this->toDirectBinaryPdfUrl($targetUrl, $academicService);

        // Si el cliente no pide un Range específico, intentamos descargar y cachear en disco local (hasta 45 MB)
        // para que el visor PDF de Chrome/Brave tenga soporte completo de Range/Byte-Serving local inmediato.
        if (! $request->hasHeader('Range')) {
            $cachedPath = $this->downloadAndPersistLocalPdf($book, $binaryCandidateUrl, $academicService);
            if ($cachedPath && Storage::disk('public')->exists($cachedPath)) {
                $fullPath = Storage::disk('public')->path($cachedPath);

                return response()->file($fullPath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => $dispositionType.'; filename="'.$safeFilename.'"',
                    'Accept-Ranges' => 'bytes',
                    'Cache-Control' => 'public, max-age=86400',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'SAMEORIGIN',
                ]);
            }
        }

        // ── CASO 3: Streaming Proxy en vivo (si es petición Range o archivo muy grande) ──
        try {
            $upstreamResponse = $this->fetchUpstreamPdfResponse($binaryCandidateUrl, $request);

            if ($upstreamResponse && ($upstreamResponse->successful() || $upstreamResponse->status() === 206)) {
                $psrResponse = $upstreamResponse->toPsrResponse();
                $psrBody = $psrResponse->getBody();
                $firstBytes = $psrBody->read(1024);
                $contentType = strtolower((string) $upstreamResponse->header('Content-Type'));

                // Si devuelve un contenedor HTML en lugar de %PDF-, extraer el enlace interno al PDF y hacer un segundo salto
                if (! str_contains($firstBytes, '%PDF-') && (str_contains($contentType, 'text/html') || str_starts_with(ltrim($firstBytes), '<'))) {
                    $htmlSnippet = $firstBytes.$psrBody->getContents();
                    $psrBody->close();

                    $effectiveUri = (string) ($upstreamResponse->effectiveUri() ?: $binaryCandidateUrl);
                    $extractedPdf = $academicService->extractDirectDocumentLinkFromHtml($htmlSnippet, $effectiveUri);

                    if ($extractedPdf) {
                        $secondHopUrl = $this->toDirectBinaryPdfUrl($extractedPdf, $academicService);
                        if ($secondHopUrl !== $binaryCandidateUrl) {
                            $book->update(['external_url' => Str::limit($secondHopUrl, 495)]);
                            $upstreamResponse = $this->fetchUpstreamPdfResponse($secondHopUrl, $request);
                            if ($upstreamResponse && ($upstreamResponse->successful() || $upstreamResponse->status() === 206)) {
                                $psrResponse = $upstreamResponse->toPsrResponse();
                                $psrBody = $psrResponse->getBody();
                                $firstBytes = $psrBody->read(1024);
                                $contentType = strtolower((string) $upstreamResponse->header('Content-Type'));
                            }
                        }
                    }
                }

                $pdfPos = strpos($firstBytes, '%PDF-');
                $isPdfBinary = ($pdfPos !== false) || (str_contains($contentType, 'application/pdf') && ! str_starts_with(ltrim($firstBytes), '<'));

                if ($isPdfBinary) {
                    if ($pdfPos !== false && $pdfPos > 0) {
                        $firstBytes = substr($firstBytes, $pdfPos);
                    }

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => $dispositionType.'; filename="'.$safeFilename.'"',
                        'Cache-Control' => 'public, max-age=3600',
                        'X-Content-Type-Options' => 'nosniff',
                        'X-Frame-Options' => 'SAMEORIGIN',
                    ];

                    if ($contentLength = $upstreamResponse->header('Content-Length')) {
                        $headers['Content-Length'] = $contentLength;
                    }
                    if ($contentRange = $upstreamResponse->header('Content-Range')) {
                        $headers['Content-Range'] = $contentRange;
                    }
                    if ($acceptRanges = $upstreamResponse->header('Accept-Ranges')) {
                        $headers['Accept-Ranges'] = $acceptRanges;
                    }

                    $statusCode = $upstreamResponse->status() === 206 ? 206 : 200;

                    return response()->stream(function () use ($firstBytes, $psrBody): void {
                        if ($firstBytes !== '') {
                            echo $firstBytes;
                        }
                        while (! $psrBody->eof()) {
                            echo $psrBody->read(65536);
                            if (ob_get_level() > 0) {
                                ob_flush();
                            }
                            flush();
                        }
                        $psrBody->close();
                    }, $statusCode, $headers);
                }
            }
        } catch (\Throwable $e) {
            Log::info("Stream proxy fallback note for book [{$book->id} - {$binaryCandidateUrl}]: ".$e->getMessage());
        }

        // ── CASO 4: Si el servidor externo bloquea o el streaming falla, generar Copia Virtual y descargarla in-app ──
        try {
            $virtualCopy = $academicService->generateAndStoreVirtualCopy($book);
            if (! empty($virtualCopy['file_path']) && Storage::disk('public')->exists($virtualCopy['file_path'])) {
                $fullPath = Storage::disk('public')->path($virtualCopy['file_path']);

                return response()->file($fullPath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => $dispositionType.'; filename="'.$safeFilename.'"',
                    'Accept-Ranges' => 'bytes',
                    'Cache-Control' => 'public, max-age=86400',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'SAMEORIGIN',
                ]);
            }
        } catch (\Throwable $ve) {
            Log::error("Error al generar copia virtual para libro [{$book->id}]: ".$ve->getMessage());
        }

        if ($dispositionType === 'attachment') {
            return redirect()->away($binaryCandidateUrl);
        }

        return response()->view('library.external-fallback', [
            'book' => $book,
            'targetUrl' => $binaryCandidateUrl,
        ], 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control' => 'no-cache, private',
        ]);
    }

    /**
     * Descarga y almacena localmente el PDF remoto en el primer acceso para garantizar
     * lectura instantánea in-app sin bloqueos ni dependencia futura del servidor externo.
     */
    private function downloadAndPersistLocalPdf(Book $book, string $candidateUrl, AcademicLibraryService $academicService): ?string
    {
        try {
            $parsed = parse_url($candidateUrl);
            $referer = isset($parsed['scheme'], $parsed['host'])
                ? "{$parsed['scheme']}://{$parsed['host']}/"
                : 'https://scholar.google.com/';

            $response = Http::connectTimeout(6)
                ->timeout(25)
                ->withHeaders(array_merge(AcademicLibraryService::SCRAPE_HEADERS, [
                    'Accept' => 'application/pdf,application/octet-stream;q=0.9,*/*;q=0.8',
                    'Referer' => $referer,
                ]))
                ->withOptions([
                    'verify' => false,
                    'allow_redirects' => ['max' => 8, 'referer' => true],
                ])
                ->get($candidateUrl);

            if (! $response->successful()) {
                return null;
            }

            $body = $response->body();
            $contentType = strtolower((string) $response->header('Content-Type'));

            // Si el primer salto devolvió una página puente HTML (SciELO / OJS / PMC), extraer el PDF real
            if (! str_contains(substr($body, 0, 1024), '%PDF-') && (str_contains($contentType, 'text/html') || str_starts_with(ltrim($body), '<'))) {
                $effectiveUri = (string) ($response->effectiveUri() ?: $candidateUrl);
                $extracted = $academicService->extractDirectDocumentLinkFromHtml($body, $effectiveUri);

                if ($extracted) {
                    $secondHop = $this->toDirectBinaryPdfUrl($extracted, $academicService);
                    if ($secondHop !== $candidateUrl) {
                        $response = Http::connectTimeout(6)
                            ->timeout(25)
                            ->withHeaders(array_merge(AcademicLibraryService::SCRAPE_HEADERS, [
                                'Accept' => 'application/pdf,application/octet-stream;q=0.9,*/*;q=0.8',
                                'Referer' => $effectiveUri,
                            ]))
                            ->withOptions(['verify' => false, 'allow_redirects' => ['max' => 8]])
                            ->get($secondHop);

                        if (! $response->successful()) {
                            return null;
                        }
                        $body = $response->body();
                    }
                }
            }

            $pdfPos = strpos($body, '%PDF-');
            if ($pdfPos === false || strlen($body) < 1024) {
                // Intento de bypass mediante resolución DOI en repositorios alternativos Open Access
                $doiCandidate = null;
                if (! empty($book->isbn) && str_starts_with($book->isbn, '10.')) {
                    $doiCandidate = $book->isbn;
                } elseif (preg_match('~10\.\d{4,9}/[-._;()/:A-Za-z0-9]+~', (string) $book->external_url, $dm)) {
                    $doiCandidate = $dm[0];
                }

                if ($doiCandidate) {
                    $doiResolvedPdf = $academicService->resolveDoiToOpenAccessPdf($doiCandidate);
                    if ($doiResolvedPdf && $doiResolvedPdf !== $candidateUrl) {
                        $response = Http::connectTimeout(6)
                            ->timeout(25)
                            ->withHeaders(array_merge(AcademicLibraryService::SCRAPE_HEADERS, [
                                'Accept' => 'application/pdf,application/octet-stream;q=0.9,*/*;q=0.8',
                                'Referer' => 'https://scholar.google.com/',
                            ]))
                            ->withOptions(['verify' => false, 'allow_redirects' => ['max' => 8]])
                            ->get($doiResolvedPdf);

                        if ($response->successful()) {
                            $body = $response->body();
                            $pdfPos = strpos($body, '%PDF-');
                        }
                    }
                }
            }

            if ($pdfPos === false || strlen($body) < 1024) {
                return null;
            }

            if ($pdfPos > 0 && $pdfPos < 1024) {
                $body = substr($body, $pdfPos);
            }

            $safeSlug = Str::slug(Str::limit($book->slug ?: $book->title, 80)) ?: 'book-'.$book->id;
            $relativePath = 'library/books/book_'.$safeSlug.'_'.$book->id.'.pdf';

            Storage::disk('public')->put($relativePath, $body);

            $book->update([
                'file_path' => $relativePath,
                'file_size' => strlen($body),
            ]);

            return $relativePath;
        } catch (\Throwable $e) {
            Log::info("Auto-cache local omitido para libro [{$book->id}]: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Convert viewer wrapper URLs (like OJS /article/view/ID/GALLEY, PDF.js viewers, or Google Drive view)
     * into raw binary stream URLs (/article/download/ID/GALLEY) so the proxy fetches pure %PDF- bytes.
     */
    private function toDirectBinaryPdfUrl(string $url, ?AcademicLibraryService $academicService = null): string
    {
        $url = trim($url);

        // 1. Desempaquetar visores PDF.js (?file=...)
        if ($academicService && preg_match('/[?&](?:file|pdf|doc|url)=(https?%3A%2F%2F[^&#]+|https?:\/\/[^&#]+|%2F[^&#]+\.pdf)/i', $url, $viewerMatch)) {
            $url = $academicService->makeAbsoluteUrl(rawurldecode($viewerMatch[1]), $url);
        }

        // 2. Europe PMC: ASEGURAR que SIEMPRE apunte al renderizador binario fcgi (NUNCA a /articles/PMC...?pdf=render que es HTML)
        if (preg_match('~europepmc\.org/(?:articles|article/PMC)/?(PMC\d+|\d+)~i', $url, $epmc)) {
            $pmcId = str_starts_with(strtoupper($epmc[1]), 'PMC') ? strtoupper($epmc[1]) : 'PMC'.$epmc[1];

            return "https://europepmc.org/backend/ptpmcrender.fcgi?accid={$pmcId}&blobtype=pdf";
        }
        if (preg_match('~europepmc\.org/backend/ptpmcrender\.fcgi\?accid=(PMC\d+)~i', $url, $epmcBackend)) {
            return "https://europepmc.org/backend/ptpmcrender.fcgi?accid={$epmcBackend[1]}&blobtype=pdf";
        }

        // 3. NCBI PMC -> usar mirror binario directo de Europe PMC (evita el challenge PoW en JS de ncbi.nlm.nih.gov)
        if (preg_match('~ncbi\.nlm\.nih\.gov/pmc/articles/(PMC\d+)~i', $url, $ncbiMatch)) {
            return "https://europepmc.org/backend/ptpmcrender.fcgi?accid={$ncbiMatch[1]}&blobtype=pdf";
        }

        // 4. OJS: /article/view/{articleId}/{galleyId} -> /article/download/{articleId}/{galleyId}
        if (preg_match('~(/article/)(?:view|viewFile)/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', $url)) {
            return (string) preg_replace('~(/article/)(?:view|viewFile)/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', '$1download/$2', $url);
        }

        // 5. Redalyc visor /html/ o articulo.oa -> PDF directo
        if (preg_match('/redalyc\.org\/articulo\.oa\?id=(\d{7,})/i', $url, $rMatch)) {
            $oaId = $rMatch[1];
            $journalPrefix = substr($oaId, 0, -3);

            return "https://www.redalyc.org/pdf/{$journalPrefix}/{$oaId}.pdf";
        }

        // 6. Dialnet servlet -> descarga directa PDF
        if (preg_match('/dialnet\.unirioja\.es\/servlet\/(articulo|tesis|libro)\?codigo=(\d+)/i', $url, $dMatch)) {
            return "https://dialnet.unirioja.es/descarga/{$dMatch[1]}/{$dMatch[2]}.pdf";
        }

        // 7. Google Drive sharing link -> descarga binaria
        if (preg_match('~drive\.google\.com/file/d/([a-zA-Z0-9_-]+)~i', $url, $gdMatch)) {
            return "https://drive.google.com/uc?export=download&id={$gdMatch[1]}";
        }

        return $url;
    }

    /**
     * Execute an upstream HTTP request with streaming enabled and academic headers.
     */
    private function fetchUpstreamPdfResponse(string $url, Request $request): ?ClientResponse
    {
        $parsed = parse_url($url);
        $referer = isset($parsed['scheme'], $parsed['host'])
            ? "{$parsed['scheme']}://{$parsed['host']}/"
            : $url;

        $headers = array_merge(AcademicLibraryService::SCRAPE_HEADERS, [
            'Accept' => 'application/pdf,application/octet-stream;q=0.9,text/html;q=0.8,*/*;q=0.7',
            'Referer' => $referer,
        ]);

        if ($request->hasHeader('Range')) {
            $headers['Range'] = $request->header('Range');
        }

        return Http::timeout(30)
            ->connectTimeout(8)
            ->withHeaders($headers)
            ->withOptions([
                'stream' => true,
                'verify' => false,
                'allow_redirects' => [
                    'max' => 10,
                    'strict' => false,
                    'referer' => true,
                    'track_redirects' => true,
                ],
            ])
            ->get($url);
    }

    /**
     * Attach is_favorited boolean subquery to avoid N+1 queries in catalog views.
     */
    private function attachFavoriteFlag(Builder $query, ?int $userId): void
    {
        if ($userId) {
            $query->addSelect([
                'books.*',
                'is_favorited' => DB::table('book_favorites')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('book_favorites.book_id', 'books.id')
                    ->where('book_favorites.user_id', $userId),
            ]);
        }
    }

    /**
     * Increment book view counter and log access session in LibraryAccessLog.
     */
    private function recordReaderAccess(Request $request, Book $book): void
    {
        try {
            $userId = auth()->id();

            $recentlyLogged = LibraryAccessLog::where('book_id', $book->id)
                ->when($userId, fn ($q) => $q->where('user_id', $userId))
                ->where('created_at', '>=', now()->subMinutes(15))
                ->exists();

            if (! $recentlyLogged) {
                $book->increment('views_count');

                LibraryAccessLog::create([
                    'user_id' => $userId,
                    'book_id' => $book->id,
                    'access_type' => 'view',
                    'ip_address' => $request->ip(),
                    'user_agent' => Str::limit((string) $request->userAgent(), 250),
                    'accessed_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo registrar el acceso de lectura: '.$e->getMessage());
        }
    }
}
