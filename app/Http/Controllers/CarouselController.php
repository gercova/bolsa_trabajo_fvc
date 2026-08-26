<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionalCarouselRequest;
use App\Models\Image;
use App\Models\InstitutionalCarousel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    /**
     * Listado de diapositivas del carrusel con filtros y paginación.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $carousels = InstitutionalCarousel::with('image')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('highlight_text', 'LIKE', "%{$search}%")
                      ->orWhere('tag', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active' || $status === '1');
            })
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.carousel.index', compact('carousels', 'search', 'status'));
    }

    /**
     * Formulario para crear una nueva diapositiva.
     */
    public function create(): View
    {
        $nextOrder = (InstitutionalCarousel::max('order') ?? 0) + 1;
        return view('admin.carousel.create', compact('nextOrder'));
    }

    /**
     * Almacenar la nueva diapositiva e insertar su imagen en el modelo Image.php.
     */
    public function store(InstitutionalCarouselRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        $validated['order']     = $validated['order'] ?? ((InstitutionalCarousel::max('order') ?? 0) + 1);

        unset($validated['image']);

        $carousel = InstitutionalCarousel::create($validated);

        // Guardar la imagen en el modelo Image.php
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('carousel', 'public');

            $carousel->image()->create([
                'path'           => $path,
                'is_main'        => true,
                'imageable_type' => InstitutionalCarousel::class,
                'imageable_id'   => $carousel->id,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Diapositiva del carrusel registrada correctamente.',
                'data'    => $carousel->load('image'),
            ], 201);
        }

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Diapositiva del carrusel registrada exitosamente.');
    }

    /**
     * Formulario de edición de diapositiva.
     */
    public function edit(InstitutionalCarousel $carousel): View
    {
        $carousel->load('image');
        return view('admin.carousel.edit', compact('carousel'));
    }

    /**
     * Actualizar la diapositiva y su imagen en Image.php.
     */
    public function update(InstitutionalCarouselRequest $request, InstitutionalCarousel $carousel): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');
        $validated['order']     = $validated['order'] ?? $carousel->order;

        unset($validated['image']);

        // Gestión de la imagen asociada en el modelo Image.php
        if ($request->hasFile('image')) {
            if ($carousel->image) {
                // Eliminar archivo previo si está en storage y no es estático por defecto
                if (!str_starts_with($carousel->image->path, 'images/')) {
                    Storage::disk('public')->delete($carousel->image->path);
                }

                $path = $request->file('image')->store('carousel', 'public');
                $carousel->image->update([
                    'path'    => $path,
                    'is_main' => true,
                ]);
            } else {
                $path = $request->file('image')->store('carousel', 'public');
                $carousel->image()->create([
                    'path'           => $path,
                    'is_main'        => true,
                    'imageable_type' => InstitutionalCarousel::class,
                    'imageable_id'   => $carousel->id,
                ]);
            }
        }

        $carousel->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Diapositiva del carrusel actualizada correctamente.',
                'data'    => $carousel->fresh(['image']),
            ]);
        }

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Diapositiva del carrusel actualizada exitosamente.');
    }

    /**
     * Alternar estado activo / inactivo de la diapositiva.
     */
    public function toggleStatus(InstitutionalCarousel $carousel): RedirectResponse|JsonResponse
    {
        $carousel->update([
            'is_active' => !$carousel->is_active,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Estado de la diapositiva actualizado.',
                'is_active' => $carousel->is_active,
            ]);
        }

        return back()->with('success', 'Estado de la diapositiva actualizado.');
    }

    /**
     * Eliminar la diapositiva y su imagen asociada.
     */
    public function destroy(InstitutionalCarousel $carousel): RedirectResponse|JsonResponse
    {
        if ($carousel->image) {
            if (!str_starts_with($carousel->image->path, 'images/')) {
                Storage::disk('public')->delete($carousel->image->path);
            }
            $carousel->image->delete();
        }

        $carousel->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Diapositiva del carrusel eliminada correctamente.',
            ]);
        }

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Diapositiva del carrusel eliminada exitosamente.');
    }
}
