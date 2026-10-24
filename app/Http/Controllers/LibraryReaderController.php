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
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibraryReaderController extends Controller
{
    /**
     * Portal principal de la Biblioteca Virtual (/biblioteca).
     */
    public function index(Request $request): View
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
                ->where('access_type', 'view')
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

        return view('library.home', compact(
            'user',
            'favoriteBooks',
            'recentlyReadBooks',
            'featuredBooks',
            'recentBooks',
            'studyPrograms',
            'selectedCareer',
            'searchQuery',
            'totalBooks'
        ));
    }

    /**
     * Buscador avanzado del catálogo (/biblioteca/buscar).
     */
    public function search(Request $request): View
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

        return view('library.search', compact(
            'books',
            'studyPrograms',
            'selectedCareer',
            'selectedCategory',
            'searchQuery'
        ));
    }

    /**
     * Colección personal de favoritos del lector (/biblioteca/mi-biblioteca).
     */
    public function myLibrary(Request $request): View
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

        return view('library.favorites', compact(
            'books',
            'studyPrograms',
            'selectedCareer',
            'searchQuery'
        ));
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
    public function read(Request $request, Book $book, AcademicLibraryService $academicService): View
    {
        $user = $request->user();

        // 1. Verificar que el documento esté activo (salvo administradores/docentes/bibliotecarios)
        if (! $book->is_active && (! $user || ! in_array($user->role, ['Admin', 'Administrador', 'Director', 'Docente', 'Bibliotecario']))) {
            abort(404, 'El documento solicitado no se encuentra disponible actualmente.');
        }

        $book->loadMissing('studyProgram');

        // 2. Auto-reparación en caliente: si es un recurso externo y su URL aún apunta a una página web entera,
        // resolvemos y actualizamos en la BD el enlace directo al PDF antes de renderizar el lector.
        if (empty($book->file_path) && ! empty($book->external_url)) {
            $currentUrl = trim((string) $book->external_url);

            if (! $academicService->isDirectDocumentOrPdfUrl($currentUrl, false)) {
                $resolvedUrl = $academicService->resolveDirectDocumentLink($currentUrl);

                if ($resolvedUrl !== '' && $resolvedUrl !== $currentUrl && $academicService->isDirectDocumentOrPdfUrl($resolvedUrl, true)) {
                    $book->update(['external_url' => Str::limit($resolvedUrl, 495)]);
                    $book->refresh();
                }
            }
        }

        // 3. Registrar visualización y trazabilidad en LibraryAccessLog (con ventana anti-spam de 15 min)
        $this->recordReaderAccess($request, $book);

        // 4. Construir las URLs para el visor embebido
        $streamUrl = route('biblioteca.stream', $book->slug);

        // URL directa de respaldo al binario PDF para abrir en pestaña nueva
        $directPdfUrl = ! empty($book->file_path)
            ? $streamUrl
            : $this->toDirectBinaryPdfUrl((string) $book->external_url, $academicService);

        // 5. Libros o documentos relacionados de la misma carrera
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

        return view('library.reader', compact(
            'book',
            'streamUrl',
            'directPdfUrl',
            'relatedBooks',
            'isFavorite'
        ));
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

        $safeFilename = Str::slug($book->title ?: 'documento-academico').'.pdf';
        $dispositionType = $request->boolean('download') ? 'attachment' : 'inline';

        // ── CASO 1: Archivo PDF almacenado localmente en el servidor ──
        if (! empty($book->file_path) && Storage::disk('public')->exists($book->file_path)) {
            $fullPath = Storage::disk('public')->path($book->file_path);

            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => $dispositionType.'; filename="'.$safeFilename.'"',
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'public, max-age=86400',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        // ── CASO 2: Recurso Académico Externo (SciELO, Redalyc, Google Scholar, DOAJ, DSpace, OJS) ──
        if (empty($book->external_url)) {
            abort(404, 'El documento no cuenta con un archivo o enlace asociado.');
        }

        $targetUrl = trim((string) $book->external_url);

        // Si aún no es un enlace directo, resolverlo con AcademicLibraryService
        if (! $academicService->isDirectDocumentOrPdfUrl($targetUrl, false)) {
            $resolved = $academicService->resolveDirectDocumentLink($targetUrl);
            if ($resolved !== '' && $resolved !== $targetUrl) {
                $targetUrl = $resolved;
                if ($academicService->isDirectDocumentOrPdfUrl($resolved, false)) {
                    $book->update(['external_url' => Str::limit($resolved, 495)]);
                }
            }
        }

        // Transformar rutas de visor HTML de OJS (/article/view/ID/GALLEY) a descarga binaria (/article/download/ID/GALLEY)
        $binaryCandidateUrl = $this->toDirectBinaryPdfUrl($targetUrl, $academicService);

        try {
            $upstreamResponse = $this->fetchUpstreamPdfResponse($binaryCandidateUrl, $request);

            if ($upstreamResponse && ($upstreamResponse->successful() || $upstreamResponse->status() === 206)) {
                $psrResponse = $upstreamResponse->toPsrResponse();
                $psrBody = $psrResponse->getBody();
                $firstBytes = $psrBody->read(5);
                $contentType = strtolower((string) $upstreamResponse->header('Content-Type'));

                // Si los primeros bytes NO son %PDF- (ej. es una página HTML intermedia de OJS, SciELO o DSpace),
                // leemos el HTML, extraemos la URL real del binario PDF y ejecutamos un segundo salto.
                if ($firstBytes !== '%PDF-' && (str_contains($contentType, 'text/html') || str_starts_with(ltrim($firstBytes), '<'))) {
                    $htmlSnippet = $firstBytes.$psrBody->getContents();
                    $psrBody->close();

                    $effectiveUri = (string) ($upstreamResponse->effectiveUri() ?: $binaryCandidateUrl);
                    $extractedPdf = $academicService->extractDirectDocumentLinkFromHtml($htmlSnippet, $effectiveUri);

                    if ($extractedPdf) {
                        if ($academicService->isMoreDirectDocumentUrl($extractedPdf, $book->external_url)) {
                            $book->update(['external_url' => Str::limit($extractedPdf, 495)]);
                        }

                        $secondHopUrl = $this->toDirectBinaryPdfUrl($extractedPdf, $academicService);
                        if ($secondHopUrl !== $binaryCandidateUrl) {
                            $upstreamResponse = $this->fetchUpstreamPdfResponse($secondHopUrl, $request);
                            if ($upstreamResponse && ($upstreamResponse->successful() || $upstreamResponse->status() === 206)) {
                                $psrResponse = $upstreamResponse->toPsrResponse();
                                $psrBody = $psrResponse->getBody();
                                $firstBytes = $psrBody->read(5);
                                $contentType = strtolower((string) $upstreamResponse->header('Content-Type'));
                            }
                        }
                    }
                }

                $isPdfBinary = ($firstBytes === '%PDF-') || (str_contains($contentType, 'application/pdf') && ! str_starts_with(ltrim($firstBytes), '<'));

                if ($isPdfBinary) {
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
                            echo $psrBody->read(65536); // Chunks de 64 KB
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

        // ── FALLBACK SEGURO: Redirigir al URL binario directo si el repositorio remoto bloquea conexiones proxy ──
        return redirect()->away($binaryCandidateUrl);
    }

    /**
     * Convert viewer wrapper URLs (like OJS /article/view/ID/GALLEY, PDF.js viewers, or Google Drive view)
     * into raw binary stream URLs (/article/download/ID/GALLEY) so the proxy fetches pure %PDF- bytes.
     */
    private function toDirectBinaryPdfUrl(string $url, ?AcademicLibraryService $academicService = null): string
    {
        $url = trim($url);

        // Desempaquetar visores PDF.js (?file=...)
        if ($academicService && preg_match('/[?&](?:file|pdf|doc|url)=(https?%3A%2F%2F[^&#]+|https?:\/\/[^&#]+|%2F[^&#]+\.pdf)/i', $url, $viewerMatch)) {
            $url = $academicService->makeAbsoluteUrl(rawurldecode($viewerMatch[1]), $url);
        }

        // OJS: /article/view/{articleId}/{galleyId} -> /article/download/{articleId}/{galleyId}
        if (preg_match('~(/article/)(?:view|viewFile)/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', $url)) {
            return (string) preg_replace('~(/article/)(?:view|viewFile)/([^/?#]+/[^/?#]+)(?:/[^/?#]+)?~i', '$1download/$2', $url);
        }

        // Google Drive sharing link -> direct binary download
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

        return Http::timeout(25)
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
