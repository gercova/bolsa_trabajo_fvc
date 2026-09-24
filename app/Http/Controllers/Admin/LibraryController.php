<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\LibraryAccessLog;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LibraryController extends Controller
{
    /**
     * Display the "Libros Asignados" view (matching Screenshot 1).
     */
    public function index(Request $request): View
    {
        $selectedCareer = $request->query('career', 'all');
        $searchQuery    = $request->query('q', '');
        $viewMode       = $request->query('view', 'grid'); // grid or list

        $query = Book::with('studyProgram')
            ->career($selectedCareer)
            ->search($searchQuery)
            ->latest('id');

        $totalCount = (clone $query)->count();
        $books = $query->paginate(24)->withQueryString();

        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return view('admin.library.assigned-books', compact(
            'books',
            'studyPrograms',
            'selectedCareer',
            'searchQuery',
            'viewMode',
            'totalCount'
        ));
    }

    /**
     * Display the "Repositorio" table view for complete CRUD management.
     */
    public function repository(Request $request): View
    {
        $selectedCareer = $request->query('career', 'all');
        $selectedCategory = $request->query('category', 'all');
        $searchQuery    = $request->query('q', '');

        $query = Book::with('studyProgram', 'creator')
            ->career($selectedCareer)
            ->category($selectedCategory)
            ->search($searchQuery)
            ->latest('id');

        $books = $query->paginate(15)->withQueryString();
        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return view('admin.library.repository', compact(
            'books',
            'studyPrograms',
            'selectedCareer',
            'selectedCategory',
            'searchQuery'
        ));
    }

    /**
     * Store a newly created book / document in the library.
     */
    public function store(BookRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['is_active']  = $request->has('is_active') ? true : false;
        $validated['rating']     = $validated['rating'] ?? 5.0;

        // Cover image upload
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverName = 'cover_' . Str::slug($validated['title']) . '_' . time() . '.' . $coverFile->getClientOriginalExtension();
            $coverPath = $coverFile->storeAs('library/covers', $coverName, 'public');
            $validated['cover_image'] = $coverPath;
        }

        // PDF file upload (direct storage, low memory usage)
        if ($request->hasFile('file')) {
            $pdfFile = $request->file('file');
            $pdfName = 'book_' . Str::slug($validated['title']) . '_' . time() . '.' . $pdfFile->getClientOriginalExtension();
            $pdfPath = $pdfFile->storeAs('library/books', $pdfName, 'public');
            $validated['file_path'] = $pdfPath;
            $validated['file_size'] = $pdfFile->getSize();
        }

        $book = Book::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido agregado a la biblioteca exitosamente.',
                'data'    => $book->load('studyProgram'),
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
            'data'    => [
                'id'               => $book->id,
                'title'            => $book->title,
                'author'           => $book->author,
                'study_program_id' => $book->study_program_id,
                'category'         => $book->category,
                'description'      => $book->description,
                'publisher'        => $book->publisher,
                'publication_year' => $book->publication_year,
                'edition'          => $book->edition,
                'pages'            => $book->pages,
                'isbn'             => $book->isbn,
                'language'         => $book->language,
                'external_url'     => $book->external_url,
                'rating'           => $book->rating,
                'is_active'        => $book->is_active,
                'cover_url'        => $book->cover_url,
                'file_url'         => $book->file_url,
                'file_size'        => $book->formatted_file_size,
            ],
        ]);
    }

    /**
     * Update an existing book / document.
     */
    public function update(BookRequest $request, Book $book): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // New cover image
        if ($request->hasFile('cover')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $coverFile = $request->file('cover');
            $coverName = 'cover_' . Str::slug($validated['title']) . '_' . time() . '.' . $coverFile->getClientOriginalExtension();
            $coverPath = $coverFile->storeAs('library/covers', $coverName, 'public');
            $validated['cover_image'] = $coverPath;
        }

        // New PDF file replacement
        if ($request->hasFile('file')) {
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
            }
            $pdfFile = $request->file('file');
            $pdfName = 'book_' . Str::slug($validated['title']) . '_' . time() . '.' . $pdfFile->getClientOriginalExtension();
            $pdfPath = $pdfFile->storeAs('library/books', $pdfName, 'public');
            $validated['file_path'] = $pdfPath;
            $validated['file_size'] = $pdfFile->getSize();
        }

        $book->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido actualizado correctamente.',
                'data'    => $book->load('studyProgram'),
            ]);
        }

        return redirect()->back()->with('success', 'La información del documento fue actualizada exitosamente.');
    }

    /**
     * Toggle active / inactive status of a book.
     */
    public function toggleStatus(Book $book): RedirectResponse|JsonResponse
    {
        $book->update(['is_active' => !$book->is_active]);

        $status = $book->is_active ? 'activado' : 'desactivado';

        if (request()->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => "El documento fue {$status} exitosamente.",
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
    public function readers(Request $request): View
    {
        $roleFilter   = $request->query('role', 'all');
        $careerFilter = $request->query('career', 'all');
        $search       = $request->query('q', '');

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

        // 3. Most Read Content Ranking
        $mostReadBooks = Book::with('studyProgram')
            ->withCount('accessLogs')
            ->orderByDesc('access_logs_count')
            ->take(5)
            ->get();

        // 4. Readers Activity Table
        $readersQuery = User::select('users.id', 'users.names', 'users.email', 'users.role', 'users.dni')
            ->withCount('libraryAccessLogs')
            ->having('library_access_logs_count', '>', 0);

        if ($roleFilter && $roleFilter !== 'all') {
            $readersQuery->where('users.role', $roleFilter);
        }

        if (!empty($search)) {
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
            ->recent()
            ->take(15)
            ->get();

        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return view('admin.library.readers', compact(
            'totalUniqueReaders',
            'totalAccessSessions',
            'activeTeachersCount',
            'activeStudentsCount',
            'readsByCareer',
            'mostReadBooks',
            'readers',
            'recentLogs',
            'studyPrograms',
            'roleFilter',
            'careerFilter',
            'search'
        ));
    }

    /**
     * Display Administrators list.
     */
    public function administrators(): View
    {
        $admins = User::whereIn('role', ['Admin', 'Administrador', 'Director'])
            ->select('id', 'names', 'email', 'role', 'phone', 'is_active', 'created_at')
            ->orderBy('names')
            ->get();

        return view('admin.library.administrators', compact('admins'));
    }

    /**
     * Display Reports and analytics.
     */
    public function reports(): View
    {
        $totalBooks       = Book::count();
        $totalViews       = Book::sum('views_count');
        $totalFavorites   = DB::table('book_favorites')->count();
        $totalCategories  = Book::distinct('category')->count('category');

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

        return view('admin.library.reports', compact(
            'totalBooks',
            'totalViews',
            'totalFavorites',
            'totalCategories',
            'booksByCategory',
            'booksByProgram'
        ));
    }
}
