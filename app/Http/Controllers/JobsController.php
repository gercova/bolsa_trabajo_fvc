<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Http\Requests\JobValidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    // Listar las ofertas (Index)
    public function index(): View {
        // Traemos todos para que Alpine gestione el filtro en tiempo real en el cliente
        $jobs = JobOffer::orderBy('created_at', 'desc')->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    // Convocatorias internas (reutiliza o personaliza según tu lógica)
    public function internalCalls(): View 
    {
        $jobs = JobOffer::where('source', 'Interna')->orderBy('created_at', 'desc')->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    // Mostrar formulario de creación
    public function create(): View {
        return view('admin.jobs.create');
    }

    // Guardar nueva oferta
    public function store(JobValidate $request): JsonResponse {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

            $job = JobOffer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Oferta laboral creada con éxito.',
                'data'    => $job
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar la oferta.'
            ], 500);
        }
    }

    // Mostrar formulario de edición
    public function edit(JobOffer $offer): View {
        return view('admin.jobs.edit', compact('offer'));
    }

    // Actualizar oferta existente
    public function update(JobValidate $request, JobOffer $offer): JsonResponse {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

            $offer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Oferta laboral actualizada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar la oferta.'
            ], 500);
        }
    }

    // Eliminar de forma asíncrona (AJAX)
    public function destroy(JobOffer $offer): JsonResponse {
        try {
            $offer->delete();
            return response()->json([
                'success' => true,
                'message' => 'La oferta ha sido eliminada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el registro.'
            ], 500);
        }
    }
}