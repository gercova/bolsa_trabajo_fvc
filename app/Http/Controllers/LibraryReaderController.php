<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookFavorite;
use App\Models\LibraryAccessLog;
use App\Models\StudyProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryReaderController extends Controller
{
    /**
     * Home view of the Virtual Library for Students and Teachers (Screenshot 2).
     */
    public function index(): View
    {
        $user = auth()->user();

        // 1. My Library / Favorites (up to 5 recent)
        $favoriteBooks = $user->favoriteBooks()
            ->with('studyProgram')
            ->where('is_active', true)
            ->latest('book_favorites.created_at')
            ->take(5)
            ->get();

        // 2. Recently Read Books by this user (up to 5 recent)
        $recentBookIds = LibraryAccessLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->pluck('book_id')
            ->unique()
            ->take(5);

        $recentlyReadBooks = Book::with('studyProgram')
            ->whereIn('id', $recentBookIds)
            ->where('is_active', true)
            ->withExists(['favorites as is_favorited' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->get()
            ->sortBy(function ($book) use ($recentBookIds) {
                return array_search($book->id, $recentBookIds->toArray());
            });

        return view('library.home', compact('user', 'favoriteBooks', 'recentlyReadBooks'));
    }

    /**
     * Search and explore catalog view ("Buscar").
     */
    public function search(Request $request): View
    {
        $user = auth()->user();
        $selectedCareer   = $request->query('career', 'all');
        $selectedCategory = $request->query('category', 'all');
        $searchQuery      = $request->query('q', '');

        $query = Book::with('studyProgram')
            ->where('is_active', true)
            ->career($selectedCareer)
            ->category($selectedCategory)
            ->search($searchQuery)
            ->withExists(['favorites as is_favorited' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest('id');

        $books = $query->paginate(20)->withQueryString();
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
     * "Mi Biblioteca" view: All favorited books for the user (Screenshot 3).
     */
    public function myLibrary(Request $request): View
    {
        $user = auth()->user();
        $selectedCareer = $request->query('career', 'all');

        $query = $user->favoriteBooks()
            ->with('studyProgram')
            ->where('is_active', true)
            ->career($selectedCareer)
            ->latest('book_favorites.created_at');

        $books = $query->paginate(20)->withQueryString();
        $studyPrograms = StudyProgram::select('id', 'name')->orderBy('name')->get();

        return view('library.favorites', compact('books', 'studyPrograms', 'selectedCareer'));
    }

    /**
     * Open reading interface for a book or document.
     */
    public function read(Book $book, Request $request): View|RedirectResponse
    {
        if (!$book->is_active) {
            abort(404, 'El documento solicitado no se encuentra disponible.');
        }

        $user = auth()->user();

        // Increment views count and log access
        $book->increment('views_count');

        LibraryAccessLog::create([
            'user_id'     => $user?->id,
            'book_id'     => $book->id,
            'access_type' => $book->is_external ? 'external_link' : 'view',
            'ip_address'  => $request->ip(),
            'user_agent'  => Str::limit($request->userAgent() ?? '', 250),
            'created_at'  => now(),
        ]);

        // If external resource, redirect to external URL
        if ($book->is_external && $book->external_url) {
            return redirect()->away($book->external_url);
        }

        $isFavorited = $user ? $user->favoriteBooks()->where('book_id', $book->id)->exists() : false;

        return view('library.reader', compact('book', 'isFavorited'));
    }

    /**
     * Stream PDF file safely with range request support to protect server memory.
     */
    public function stream(Book $book): BinaryFileResponse|RedirectResponse
    {
        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            if ($book->external_url) {
                return redirect()->away($book->external_url);
            }
            abort(404, 'Archivo PDF no disponible en el almacenamiento.');
        }

        $fullPath = Storage::disk('public')->path($book->file_path);

        return response()->file($fullPath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addslashes($book->slug . '.pdf') . '"',
            'Cache-Control'       => 'public, max-age=86400',
        ]);
    }

    /**
     * Toggle a book in user's favorites ("Mi Biblioteca").
     */
    public function toggleFavorite(Book $book): JsonResponse
    {
        $user = auth()->user();

        $existing = BookFavorite::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
            $message = 'Eliminado de tu biblioteca';
        } else {
            BookFavorite::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
            $favorited = true;
            $message = 'Agregado a tu biblioteca';
        }

        return response()->json([
            'success'   => true,
            'favorited' => $favorited,
            'message'   => $message,
        ]);
    }
}
