<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItineraryRequest;
use App\Models\Course;
use App\Models\Itinerary;
use App\Models\Module;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItineraryController extends Controller
{
    /**
     * Display a listing of itineraries.
     */
    public function index(Request $request): View
    {
        $search   = $request->input('search');
        $courseId = $request->input('course_id');
        $moduleId = $request->input('module_id');
        $status   = $request->input('status');

        $query = Itinerary::with(['course', 'module'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('name', 'LIKE', "%{$search}%")
                        ->orWhereHas('course', function ($cq) use ($search) {
                            $cq->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('code', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('module', function ($mq) use ($search) {
                            $mq->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($moduleId, fn ($q) => $q->where('module_id', $moduleId))
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('is_active', (bool) $status);
            })
            ->orderBy('course_id')
            ->orderBy('module_id')
            ->orderBy('name', 'asc');

        $itineraries = $query->paginate(10)->appends($request->only(['search', 'course_id', 'module_id', 'status']));
        $courses     = Course::where('is_active', true)->with('modules')->orderBy('name')->get();
        $modules     = Module::where('is_active', true)->orderBy('name')->get();

        // Stat counters
        $totalItineraries  = Itinerary::count();
        $activeItineraries = Itinerary::where('is_active', true)->count();
        $coursesWithItin   = Course::has('itineraries')->count();

        return view('admin.itineraries.index', compact(
            'itineraries',
            'courses',
            'modules',
            'totalItineraries',
            'activeItineraries',
            'coursesWithItin',
            'search',
            'courseId',
            'moduleId',
            'status'
        ));
    }

    /**
     * Store a newly created itinerary.
     */
    public function store(ItineraryRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $itinerary = Itinerary::create($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'   => true,
                    'message'   => "El itinerario '{$itinerary->name}' ha sido registrado exitosamente.",
                    'itinerary' => $itinerary->load(['course', 'module']),
                ], 201);
            }

            return redirect()->route('admin.itineraries.index')
                ->with('success', "El itinerario '{$itinerary->name}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando itinerario: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al registrar el itinerario: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al registrar el itinerario.');
        }
    }

    /**
     * Update the specified itinerary.
     */
    public function update(ItineraryRequest $request, Itinerary $itinerary): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $itinerary->update($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'   => true,
                    'message'   => "El itinerario '{$itinerary->name}' ha sido actualizado exitosamente.",
                    'itinerary' => $itinerary->load(['course', 'module']),
                ], 200);
            }

            return redirect()->route('admin.itineraries.index')
                ->with('success', "El itinerario '{$itinerary->name}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando itinerario: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el itinerario: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar el itinerario.');
        }
    }

    /**
     * Remove the specified itinerary.
     */
    public function destroy(Itinerary $itinerary): RedirectResponse|JsonResponse
    {
        try {
            $name = $itinerary->name;
            $itinerary->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El itinerario '{$name}' ha sido eliminado correctamente.",
                ], 200);
            }

            return redirect()->route('admin.itineraries.index')
                ->with('success', "El itinerario '{$name}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando itinerario: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el itinerario.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el itinerario.');
        }
    }

    /**
     * Toggle the active status of an itinerary.
     */
    public function toggleStatus(Itinerary $itinerary): JsonResponse|RedirectResponse
    {
        $itinerary->is_active = !$itinerary->is_active;
        $itinerary->save();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $itinerary->is_active,
                'message'   => 'Estado actualizado correctamente.',
            ]);
        }

        return back()->with('success', 'Estado del itinerario actualizado.');
    }
}
