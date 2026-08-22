<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExternalInstitutionalLinkValidate;
use App\Models\ExternalInstitutionalLink;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExternalInstitutionalLinkController extends Controller
{
    /**
     * Display a listing of external institutional links with search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = ExternalInstitutionalLink::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('link', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            if ($status === 'active' || $status === '1') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive' || $status === '0') {
                $query->where('is_active', false);
            }
        }

        // Summary Statistics
        $totalLinks    = ExternalInstitutionalLink::count();
        $activeLinks   = ExternalInstitutionalLink::where('is_active', true)->count();
        $inactiveLinks = ExternalInstitutionalLink::where('is_active', false)->count();

        // Paginated results sorted by newest first
        $links = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.external_links.index', compact(
            'links',
            'search',
            'status',
            'totalLinks',
            'activeLinks',
            'inactiveLinks'
        ));
    }

    /**
     * Show form for creating a new institutional link.
     */
    public function create(): View
    {
        return view('admin.external_links.create');
    }

    /**
     * Store a newly created institutional link.
     */
    public function store(ExternalInstitutionalLinkValidate $request): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;
            $validated['icon']      = !empty($validated['icon']) ? $validated['icon'] : 'bi-box-arrow-up-right';

            $link = ExternalInstitutionalLink::create($validated);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Enlace institucional creado con éxito.',
                    'redirect' => route('admin.links.index'),
                    'data'     => $link,
                ], 201);
            }

            return redirect()->route('admin.links.index')
                ->with('success', 'Enlace institucional creado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error creando enlace institucional: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el enlace: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al guardar el enlace institucional.');
        }
    }

    /**
     * Show form for editing an existing institutional link.
     */
    public function edit(ExternalInstitutionalLink $link): View
    {
        return view('admin.external_links.edit', compact('link'));
    }

    /**
     * Update an existing institutional link.
     */
    public function update(ExternalInstitutionalLinkValidate $request, ExternalInstitutionalLink $link): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;
            $validated['icon']      = !empty($validated['icon']) ? $validated['icon'] : 'bi-box-arrow-up-right';

            $link->update($validated);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Enlace institucional actualizado correctamente.',
                    'redirect' => route('admin.links.index'),
                    'data'     => $link->fresh(),
                ], 200);
            }

            return redirect()->route('admin.links.index')
                ->with('success', 'Enlace institucional actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando enlace institucional: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el enlace: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar el enlace institucional.');
        }
    }

    /**
     * Toggle active / inactive status of an institutional link.
     */
    public function toggleStatus(ExternalInstitutionalLink $link): JsonResponse|RedirectResponse
    {
        try {
            $link->update(['is_active' => !$link->is_active]);

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'is_active' => $link->is_active,
                    'message'   => 'Estado del enlace actualizado correctamente.',
                ]);
            }

            return back()->with('success', 'Estado del enlace actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error alternando estado de enlace institucional: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado.',
                ], 500);
            }

            return back()->with('error', 'No se pudo cambiar el estado del enlace.');
        }
    }

    /**
     * Remove an institutional link.
     */
    public function destroy(ExternalInstitutionalLink $link): JsonResponse|RedirectResponse
    {
        try {
            $link->delete();

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'El enlace institucional ha sido eliminado correctamente.',
                ], 200);
            }

            return redirect()->route('admin.links.index')
                ->with('success', 'El enlace institucional ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error eliminando enlace institucional: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el registro.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el enlace institucional.');
        }
    }
}
