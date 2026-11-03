<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\LibraryAccessLog;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\AcademicLibraryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LibraryController extends Controller
{
    /**
     * Display the "Libros Asignados" view.
     */
    public function index(Request $request): Response
    {
        $selectedCareer = $request->query('career', 'all');
        $searchQuery = $request->query('q', '');
        $viewMode = $request->query('view', 'grid');
        $query = Book::with('studyProgram')
            ->career($selectedCareer)
            ->search($searchQuery)
            ->latest('id');

        $totalCount = (clone $query)->count();
        $books = $query->paginate(24)->withQueryString();
        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/Library/AssignedBooks', [
            'books' => $books,
            'studyPrograms' => $studyPrograms,
            'selectedCareer' => $selectedCareer,
            'searchQuery' => $searchQuery,
            'viewMode' => $viewMode,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Display the "Repositorio" table view for complete CRUD management.
     */
    public function repository(Request $request): Response
    {
        $selectedCareer = $request->query('career', 'all');
        $selectedCategory = $request->query('category', 'all');
        $searchQuery = $request->query('q', '');
        $query = Book::with('studyProgram', 'creator')
            ->career($selectedCareer)
            ->category($selectedCategory)
            ->search($searchQuery)
            ->latest('id');

        $books = $query->paginate(15)->withQueryString();
        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/Library/Repository', [
            'books' => $books,
            'studyPrograms' => $studyPrograms,
            'selectedCareer' => $selectedCareer,
            'selectedCategory' => $selectedCategory,
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Search and bulk-import academic resources (journals, books, papers, theses, articles)
     * organized by study program from verifiable open-access sources.
     */
    public function fetchAcademicResources(Request $request, AcademicLibraryService $service): JsonResponse
    {
        $user = auth()->user();
        if (! $user || ! in_array($user->role, ['Admin', 'Administrador', 'Director', 'Docente', 'Bibliotecario'])) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para realizar importaciones masivas en el repositorio.',
            ], 403);
        }

        set_time_limit(180);

        try {
            $options = [
                'study_program_id' => $request->input('study_program_id'),
                'category' => $request->input('category'),
                'sources' => $request->input('sources', ['scielo', 'google_scholar', 'redalyc', 'scopus', 'openalex', 'crossref', 'doaj', 'europe_pmc', 'open_library', 'semanticscholar']),
                'custom_query' => $request->input('custom_query'),
                'limit' => (int) $request->input('limit', 10),
                'created_by' => auth()->id(),
                'strict_pdf_only' => true,
            ];

            $result = $service->fetchAndImport($options);

            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Error en búsqueda masiva de recursos académicos: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar la búsqueda académica: '.$e->getMessage(),
                'saved' => 0,
                'updated' => 0,
                'skipped' => 0,
                'total' => 0,
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * Store a newly created book / document in the library.
     */
    public function store(BookRequest $request, AcademicLibraryService $academicService): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['rating'] = $validated['rating'] ?? 5.0;

        if (! empty($validated['external_url'])) {
            if (! $academicService->isValidAndReachableUrl($validated['external_url'])) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El enlace académico no es válido o no se encuentra accesible en el servidor remoto.',
                        'errors' => ['external_url' => ['El enlace académico no es accesible o no existe en el servidor remoto.']],
                    ], 422);
                }

                return back()->withErrors(['external_url' => 'El enlace académico no es accesible o no existe en el servidor remoto.'])->withInput();
            }

            $validated['external_url'] = Str::limit(
                $academicService->resolveDirectDocumentLink((string) $validated['external_url']),
                495
            );
        }

        // Cover image upload
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverName = 'cover_'.Str::slug($validated['title']).'_'.time().'.'.$coverFile->getClientOriginalExtension();
            $coverPath = $coverFile->storeAs('library/covers', $coverName, 'public');
            $validated['cover_image'] = $coverPath;
        }

        // PDF file upload manual O descarga automática desde external_url
        if ($request->hasFile('file')) {
            $pdfFile = $request->file('file');
            $pdfName = 'book_'.Str::slug($validated['title']).'_'.time().'.'.$pdfFile->getClientOriginalExtension();
            $pdfPath = $pdfFile->storeAs('library/books', $pdfName, 'public');
            $validated['file_path'] = $pdfPath;
            $validated['file_size'] = $pdfFile->getSize();
        } elseif (! empty($validated['external_url'])) {
            $cached = $academicService->downloadAndCachePdf($validated['external_url'], $validated['title']);
            if ($cached) {
                $validated['file_path'] = $cached['file_path'];
                $validated['file_size'] = $cached['file_size'];
            }
        }

        $book = Book::create($validated);

        // Si el repositorio externo bloquea la descarga física, crear y almacenar copia virtual de inmediato
        if (empty($book->file_path) && ! empty($book->external_url)) {
            $academicService->generateAndStoreVirtualCopy($book);
            $book->refresh();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido agregado a la biblioteca exitosamente.',
                'data' => $book->load('studyProgram'),
            ], 201);
        }

        return redirect()->back()->with('success', 'El libro o documento fue agregado exitosamente al catálogo de la biblioteca.');
    }

    /**
     * Fetch book details for editing (JSON).
     */
    public function edit(Book $book): JsonResponse
    {
        $book->load('studyProgram');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'study_program_id' => $book->study_program_id,
                'category' => $book->category,
                'description' => $book->description,
                'publisher' => $book->publisher,
                'publication_year' => $book->publication_year,
                'edition' => $book->edition,
                'pages' => $book->pages,
                'isbn' => $book->isbn,
                'language' => $book->language,
                'external_url' => $book->external_url,
                'rating' => $book->rating,
                'is_active' => $book->is_active,
                'cover_url' => $book->cover_url,
                'file_url' => $book->file_url,
                'file_size' => $book->formatted_file_size,
            ],
        ]);
    }

    /**
     * Update an existing book / document.
     */
    public function update(BookRequest $request, Book $book, AcademicLibraryService $academicService): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $urlChanged = ! empty($validated['external_url']) && $validated['external_url'] !== $book->external_url;
        if ($urlChanged) {
            if (! $academicService->isValidAndReachableUrl($validated['external_url'])) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El enlace académico no es válido o no se encuentra accesible en el servidor remoto.',
                        'errors' => ['external_url' => ['El enlace académico no es accesible o no existe en el servidor remoto.']],
                    ], 422);
                }

                return back()->withErrors(['external_url' => 'El enlace académico no es accesible o no existe en el servidor remoto.'])->withInput();
            }

            $validated['external_url'] = Str::limit(
                $academicService->resolveDirectDocumentLink((string) $validated['external_url']),
                495
            );
        }

        if ($request->hasFile('cover')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $coverFile = $request->file('cover');
            $coverName = 'cover_'.Str::slug($validated['title']).'_'.time().'.'.$coverFile->getClientOriginalExtension();
            $coverPath = $coverFile->storeAs('library/covers', $coverName, 'public');
            $validated['cover_image'] = $coverPath;
        }

        if ($request->hasFile('file')) {
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
            }
            $pdfFile = $request->file('file');
            $pdfName = 'book_'.Str::slug($validated['title']).'_'.time().'.'.$pdfFile->getClientOriginalExtension();
            $pdfPath = $pdfFile->storeAs('library/books', $pdfName, 'public');
            $validated['file_path'] = $pdfPath;
            $validated['file_size'] = $pdfFile->getSize();
        } elseif (! empty($validated['external_url']) && ($urlChanged || empty($book->file_path) || ! Storage::disk('public')->exists($book->file_path))) {
            $cached = $academicService->downloadAndCachePdf($validated['external_url'], $validated['title']);
            if ($cached) {
                $validated['file_path'] = $cached['file_path'];
                $validated['file_size'] = $cached['file_size'];
            }
        }

        $book->update($validated);

        // Si no tiene archivo físico local y el repositorio externo bloquea descarga, crear copia virtual
        if (empty($book->file_path) && ! empty($book->external_url)) {
            $academicService->generateAndStoreVirtualCopy($book);
            $book->refresh();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido actualizado correctamente.',
                'data' => $book->load('studyProgram'),
            ]);
        }

        return redirect()->back()->with('success', 'La información del documento fue actualizada exitosamente.');
    }

    public function streamDocument(Request $request, Book $book, AcademicLibraryService $academicService): SymfonyResponse
    {
        $isDownload = $request->boolean('download', false);
        $dispositionType = $isDownload ? 'attachment' : 'inline';
        $safeFilename = (Str::slug(Str::limit($book->title, 80)) ?: 'documento-'.$book->id).'.pdf';

        // 1. Si el PDF ya existe en el almacenamiento local, servirlo inmediatamente (soporta byte-range nativo)
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            $fullPath = Storage::disk('public')->path($book->file_path);

            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "{$dispositionType}; filename=\"{$safeFilename}\"",
                'X-Frame-Options' => 'SAMEORIGIN',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        if (empty($book->external_url)) {
            abort(404, 'El documento no cuenta con un archivo o enlace asociado.');
        }

        // 2. Intentar descargar y cachear en disco automáticamente (Auto-Healing para libros ya importados)
        $cached = $academicService->downloadAndCachePdf($book->external_url, $book->slug ?: $book->title);
        if ($cached && Storage::disk('public')->exists($cached['file_path'])) {
            $book->update([
                'file_path' => $cached['file_path'],
                'file_size' => $cached['file_size'],
            ]);

            $fullPath = Storage::disk('public')->path($cached['file_path']);

            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "{$dispositionType}; filename=\"{$safeFilename}\"",
                'X-Frame-Options' => 'SAMEORIGIN',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        // 3. Fallback de Streaming Proxy en vivo (si el archivo no se pudo guardar en disco)
        $resolvedUrl = $academicService->resolveDirectDocumentLink($book->external_url);
        if ($resolvedUrl !== $book->external_url) {
            $book->update(['external_url' => Str::limit($resolvedUrl, 495)]);
        }

        try {
            $parsedHost = parse_url($resolvedUrl, PHP_URL_HOST) ?: '';
            $parsedScheme = parse_url($resolvedUrl, PHP_URL_SCHEME) ?: 'https';

            $upstream = Http::connectTimeout(8)
                ->timeout(45)
                ->withHeaders(array_merge(AcademicLibraryService::SCRAPE_HEADERS, [
                    'Accept' => 'application/pdf,application/octet-stream;q=0.9,*/*;q=0.8',
                    'Referer' => $parsedHost ? "{$parsedScheme}://{$parsedHost}/" : 'https://scholar.google.com/',
                ]))
                ->withOptions([
                    'stream' => true,
                    'allow_redirects' => ['max' => 8, 'referer' => true],
                    'verify' => false,
                ])
                ->get($resolvedUrl);

            if ($upstream->successful()) {
                $psrStream = $upstream->toPsrResponse()->getBody();

                return response()->stream(function () use ($psrStream) {
                    while (! $psrStream->eof()) {
                        echo $psrStream->read(65536);
                        if (ob_get_level() > 0) {
                            ob_flush();
                        }
                        flush();
                    }
                }, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "{$dispositionType}; filename=\"{$safeFilename}\"",
                    'X-Frame-Options' => 'SAMEORIGIN',
                    'Access-Control-Allow-Origin' => '*',
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning("Error en streamDocument proxy para libro ID {$book->id}: ".$e->getMessage());
        }

        // 4. Si el servidor externo bloquea o el streaming falla, generar Copia Virtual y descargarla in-app
        try {
            $virtualCopy = $academicService->generateAndStoreVirtualCopy($book);
            if (! empty($virtualCopy['file_path']) && Storage::disk('public')->exists($virtualCopy['file_path'])) {
                $fullPath = Storage::disk('public')->path($virtualCopy['file_path']);

                return response()->file($fullPath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "{$dispositionType}; filename=\"{$safeFilename}\"",
                    'X-Frame-Options' => 'SAMEORIGIN',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        } catch (\Throwable $ve) {
            Log::error('Error en generación de copia virtual en streamDocument: '.$ve->getMessage());
        }

        abort(502, 'No fue posible obtener el flujo del documento desde el servidor académico de origen.');
    }

    /**
     * Genera o regenera manualmente la copia virtual institucional de un documento.
     */
    public function generateVirtualCopy(Book $book, AcademicLibraryService $academicService): JsonResponse
    {
        try {
            $result = $academicService->generateAndStoreVirtualCopy($book);
            $book->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Copia virtual institucional generada y descargada exitosamente en la aplicación.',
                'file_url' => $book->file_url,
                'file_path' => $result['file_path'],
                'file_size' => $result['file_size'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar la copia virtual institucional: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle active / inactive status of a book.
     */
    public function toggleStatus(Book $book): RedirectResponse|JsonResponse
    {
        $book->update(['is_active' => ! $book->is_active]);
        $status = $book->is_active ? 'activado' : 'desactivado';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "El documento fue {$status} exitosamente.",
                'is_active' => $book->is_active,
            ]);
        }

        return redirect()->back()->with('success', "El documento fue {$status} en el portal de la biblioteca.");
    }

    /**
     * Delete a book and its physical assets safely.
     */
    public function destroy(Book $book): RedirectResponse|JsonResponse
    {
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }

        $book->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'El libro o documento fue eliminado de la biblioteca.',
            ]);
        }

        return redirect()->back()->with('success', 'El documento y sus archivos fueron eliminados correctamente.');
    }

    /**
     * Dedicated Reader Monitoring Dashboard ("Lectores").
     */
    public function readers(Request $request): Response
    {
        $roleFilter = $request->query('role', 'all');
        $careerFilter = $request->query('career', 'all');
        $search = trim((string) $request->query('q', ''));

        // 1. Core KPIs
        $totalUniqueReaders = LibraryAccessLog::whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $totalAccessSessions = LibraryAccessLog::count();
        $activeTeachersCount = LibraryAccessLog::join('users', 'library_access_logs.user_id', '=', 'users.id')
            ->where('users.role', 'Docente')
            ->distinct('users.id')
            ->count('users.id');

        $activeStudentsCount = LibraryAccessLog::join('users', 'library_access_logs.user_id', '=', 'users.id')
            ->where('users.role', 'Estudiante')
            ->distinct('users.id')
            ->count('users.id');

        // 2. Access Distribution by Study Program
        $readsByCareer = DB::table('library_access_logs')
            ->join('books', 'library_access_logs.book_id', '=', 'books.id')
            ->leftJoin('study_programs', 'books.study_program_id', '=', 'study_programs.id')
            ->select(
                DB::raw('COALESCE(study_programs.name, "General / Transversal") as program_name'),
                DB::raw('COUNT(library_access_logs.id) as total_reads')
            )
            ->groupBy('program_name')
            ->orderByDesc('total_reads')
            ->get();

        // 3. Most Read Content Ranking (filtrado por carrera si se selecciona una)
        $mostReadBooks = Book::with('studyProgram')
            ->when($careerFilter !== 'all', fn ($q) => $q->where('study_program_id', $careerFilter))
            ->withCount('accessLogs')
            ->orderByDesc('access_logs_count')
            ->take(5)
            ->get();

        // 4. Readers Activity Table
        $readersQuery = User::select('users.id', 'users.names', 'users.email', 'users.role', 'users.dni')
            ->withCount(['libraryAccessLogs' => function ($q) use ($careerFilter) {
                if ($careerFilter && $careerFilter !== 'all') {
                    $q->whereHas('book', fn ($bq) => $bq->where('study_program_id', $careerFilter));
                }
            }])
            ->having('library_access_logs_count', '>', 0);

        if ($roleFilter && $roleFilter !== 'all') {
            $readersQuery->where('users.role', $roleFilter);
        }

        if ($search !== '') {
            $readersQuery->where(function ($q) use ($search) {
                $q->where('users.names', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.dni', 'like', "%{$search}%");
            });
        }

        $readers = $readersQuery->orderByDesc('library_access_logs_count')
            ->paginate(15, ['*'], 'readers_page')
            ->withQueryString();

        // 5. Recent Reading Activity Stream
        $recentLogs = LibraryAccessLog::with(['user', 'book.studyProgram'])
            ->when($careerFilter && $careerFilter !== 'all', function ($q) use ($careerFilter) {
                $q->whereHas('book', fn ($bq) => $bq->where('study_program_id', $careerFilter));
            })
            ->recent()
            ->take(15)
            ->get();

        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Admin/Library/Readers', [
            'totalUniqueReaders' => $totalUniqueReaders,
            'totalAccessSessions' => $totalAccessSessions,
            'activeTeachersCount' => $activeTeachersCount,
            'activeStudentsCount' => $activeStudentsCount,
            'readsByCareer' => $readsByCareer,
            'mostReadBooks' => $mostReadBooks,
            'readers' => $readers,
            'recentLogs' => $recentLogs,
            'studyPrograms' => $studyPrograms,
            'roleFilter' => $roleFilter,
            'careerFilter' => $careerFilter,
            'search' => $search,
        ]);
    }

    /**
     * Display Administrators list.
     */
    public function administrators(): Response
    {
        $admins = User::whereIn('role', ['Admin', 'Administrador', 'Director'])
            ->select('id', 'names', 'email', 'role', 'phone', 'is_active', 'created_at')
            ->orderBy('names')
            ->get();

        return Inertia::render('Admin/Library/Administrators', [
            'admins' => $admins,
        ]);
    }

    /**
     * Display Reports and analytics.
     */
    public function reports(): Response
    {
        $totalBooks = Book::count();
        $totalViews = Book::sum('views_count');
        $totalFavorites = DB::table('book_favorites')->count();
        $totalCategories = Book::distinct('category')->count('category');
        $booksByCategory = Book::select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->get();

        $booksByProgram = DB::table('books')
            ->leftJoin('study_programs', 'books.study_program_id', '=', 'study_programs.id')
            ->select(
                DB::raw('COALESCE(study_programs.name, "General / Todas") as program_name'),
                DB::raw('COUNT(books.id) as total')
            )
            ->groupBy('program_name')
            ->orderByDesc('total')
            ->get();

        return Inertia::render('Admin/Library/Reports', [
            'totalBooks' => $totalBooks,
            'totalViews' => $totalViews,
            'totalFavorites' => $totalFavorites,
            'totalCategories' => $totalCategories,
            'booksByCategory' => $booksByCategory,
            'booksByProgram' => $booksByProgram,
        ]);
    }
}
