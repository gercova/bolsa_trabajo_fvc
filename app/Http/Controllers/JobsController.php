<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Http\Requests\JobValidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobsController extends Controller
{
    /**
     * Listar todas las ofertas laborales (Index)
     */
    public function index(Request $request): View {
        $query = JobOffer::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('source')) {
            $query->where('source', 'like', "%{$request->input('source')}%");
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();

        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Convocatorias internas
     */
    public function internalCalls(Request $request): View {
        $query = JobOffer::where('source', 'like', '%Interna%');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): View {
        return view('admin.jobs.create');
    }

    /**
     * Guardar nueva oferta laboral
     */
    public function store(JobValidate $request): JsonResponse|RedirectResponse {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active');
            $validated['url']       = !empty($validated['url']) ? $validated['url'] : '#';
            $validated['source']    = !empty($validated['source']) ? $validated['source'] : 'Bolsa Institucional';

            $job = JobOffer::create($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Oferta laboral creada con éxito.',
                    'redirect' => route('admin.works.index'),
                    'data'     => $job
                ], 201);
            }

            return redirect()->route('admin.works.index')
                             ->with('success', 'Oferta laboral creada con éxito.');
        } catch (\Exception $e) {
            Log::error('Error creando oferta laboral: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la oferta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al guardar la oferta laboral.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(JobOffer $offer): View {
        return view('admin.jobs.edit', compact('offer'));
    }

    /**
     * Actualizar oferta laboral
     */
    public function update(JobValidate $request, JobOffer $offer): JsonResponse|RedirectResponse {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->boolean('is_active');
            $validated['url']       = !empty($validated['url']) ? $validated['url'] : '#';
            $validated['source']    = !empty($validated['source']) ? $validated['source'] : 'Bolsa Institucional';

            $offer->update($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Oferta laboral actualizada correctamente.',
                    'redirect' => route('admin.works.index')
                ], 200);
            }

            return redirect()->route('admin.works.index')
                             ->with('success', 'Oferta laboral actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error actualizando oferta laboral: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la oferta: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar la oferta laboral.');
        }
    }

    /**
     * Alternar estado activo / inactivo
     */
    public function toggleStatus(JobOffer $offer): JsonResponse|RedirectResponse {
        try {
            $offer->update(['is_active' => !$offer->is_active]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success'   => true,
                    'is_active' => $offer->is_active,
                    'message'   => 'Estado de la oferta actualizado correctamente.'
                ]);
            }

            return back()->with('success', 'Estado de la oferta actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error alternando estado de oferta: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado.'
                ], 500);
            }

            return back()->with('error', 'No se pudo cambiar el estado de la oferta.');
        }
    }

    /**
     * Eliminar oferta laboral
     */
    public function destroy(JobOffer $offer): JsonResponse|RedirectResponse {
        try {
            $offer->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'La oferta ha sido eliminada correctamente.'
                ], 200);
            }

            return redirect()->route('admin.works.index')
                             ->with('success', 'La oferta ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error eliminando oferta laboral: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el registro.'
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar la oferta laboral.');
        }
    }
}