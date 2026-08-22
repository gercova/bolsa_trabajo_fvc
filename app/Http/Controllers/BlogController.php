<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogValidate;
use App\Models\Blog;
use App\Models\Image;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs with search, filter, stats, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Blog::with('images');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            if ($status === 'published' || $status === '1') {
                $query->where('is_published', true);
            } elseif ($status === 'draft' || $status === '0') {
                $query->where('is_published', false);
            }
        }

        // Summary Statistics
        $totalBlogs     = Blog::count();
        $publishedBlogs = Blog::where('is_published', true)->count();
        $draftBlogs     = Blog::where('is_published', false)->count();

        // Paginated results sorted by newest first
        $blogs = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.blogs.index', compact(
            'blogs',
            'search',
            'status',
            'totalBlogs',
            'publishedBlogs',
            'draftBlogs'
        ));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create(): View
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog post in storage.
     */
    public function store(BlogValidate $request): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Generate unique slug
            $baseSlug = Str::slug($validated['title']);
            $slug     = $baseSlug;
            $count    = 1;
            while (Blog::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $blog = Blog::create([
                'title'        => $validated['title'],
                'slug'         => $slug,
                'content'      => $validated['content'],
                'details'      => $validated['details'] ?? null,
                'is_published' => $request->has('is_published') ? (bool) $request->input('is_published') : false,
            ]);

            // Handle image upload if provided
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $path = $request->file('image')->store('blogs', 'public');

                $blog->images()->create([
                    'path'    => $path,
                    'is_main' => true,
                ]);
            }

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Publicación creada con éxito.',
                    'redirect' => route('admin.blogs.index'),
                    'data'     => $blog->load('images'),
                ], 201);
            }

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Publicación creada con éxito.');
        } catch (\Exception $e) {
            Log::error('Error creando blog: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la publicación: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al guardar la publicación de blog.');
        }
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(Blog $blog): View
    {
        $blog->load('images');
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog post in storage.
     */
    public function update(BlogValidate $request, Blog $blog): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Update slug if title changed
            if ($validated['title'] !== $blog->title) {
                $baseSlug = Str::slug($validated['title']);
                $slug     = $baseSlug;
                $count    = 1;
                while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                    $slug = $baseSlug . '-' . $count;
                    $count++;
                }
                $blog->slug = $slug;
            }

            $blog->title        = $validated['title'];
            $blog->content      = $validated['content'];
            $blog->details      = $validated['details'] ?? null;
            $blog->is_published = $request->has('is_published') ? (bool) $request->input('is_published') : false;
            $blog->save();

            // Handle image update if a new image was uploaded
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Remove existing main images if replacing
                foreach ($blog->images as $existingImage) {
                    if ($existingImage->path && Storage::disk('public')->exists($existingImage->path)) {
                        Storage::disk('public')->delete($existingImage->path);
                    }
                    $existingImage->delete();
                }

                $path = $request->file('image')->store('blogs', 'public');
                $blog->images()->create([
                    'path'    => $path,
                    'is_main' => true,
                ]);
            }

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Publicación actualizada correctamente.',
                    'redirect' => route('admin.blogs.index'),
                    'data'     => $blog->load('images'),
                ], 200);
            }

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Publicación actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando blog: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la publicación: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar la publicación.');
        }
    }

    /**
     * Toggle the published status of the blog.
     */
    public function toggleStatus(Blog $blog): JsonResponse|RedirectResponse
    {
        try {
            $blog->update(['is_published' => !$blog->is_published]);

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success'      => true,
                    'is_published' => $blog->is_published,
                    'message'      => 'Estado de publicación actualizado correctamente.',
                ]);
            }

            return back()->with('success', 'Estado de publicación actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error alternando estado del blog: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado.',
                ], 500);
            }

            return back()->with('error', 'No se pudo cambiar el estado de publicación.');
        }
    }

    /**
     * Remove the specified blog post from storage.
     */
    public function destroy(Blog $blog): JsonResponse|RedirectResponse
    {
        try {
            // Delete associated image files
            foreach ($blog->images as $img) {
                if ($img->path && Storage::disk('public')->exists($img->path)) {
                    Storage::disk('public')->delete($img->path);
                }
                $img->delete();
            }

            $blog->delete();

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'La publicación de blog ha sido eliminada correctamente.',
                ], 200);
            }

            return redirect()->route('admin.blogs.index')
                ->with('success', 'La publicación de blog ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error eliminando blog: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el registro.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar la publicación.');
        }
    }
}
