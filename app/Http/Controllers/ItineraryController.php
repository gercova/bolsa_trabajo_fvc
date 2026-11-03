<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItineraryRequest;
use App\Models\Course;
use App\Models\Itinerary;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ItineraryController extends Controller
{
    /**
     * Display a listing of itineraries.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $courseId = $request->input('course_id');
        $moduleId = $request->input('module_id');
        $status = $request->input('status');

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
        $courses = Course::where('is_active', true)->with('modules')->orderBy('name')->get();
        $modules = Module::where('is_active', true)->orderBy('name')->get();

        // Stat counters
        $totalItineraries = Itinerary::count();
        $activeItineraries = Itinerary::where('is_active', true)->count();
        $coursesWithItin = Course::has('itineraries')->count();

        return Inertia::render('Admin/Itineraries/Index', [
            'itineraries' => $itineraries,
            'courses' => $courses,
            'modules' => $modules,
            'totalItineraries' => $totalItineraries,
            'activeItineraries' => $activeItineraries,
            'coursesWithItin' => $coursesWithItin,
            'search' => $search ?? '',
            'courseId' => $courseId ?? '',
            'moduleId' => $moduleId ?? '',
            'status' => $status ?? '',
        ]);
    }

    /**
     * Store a newly created itinerary.
     */
    public function store(ItineraryRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);
            $itinerary = Itinerary::create($data);

            return redirect()->route('admin.itineraries.index')
                ->with('success', "El itinerario '{$itinerary->name}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando itinerario: '.$e->getMessage());

            return back()->withInput()->with('error', 'Error al registrar el itinerario.');
        }
    }

    /**
     * Update the specified itinerary.
     */
    public function update(ItineraryRequest $request, Itinerary $itinerary): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active');
            $itinerary->update($data);

            return redirect()->route('admin.itineraries.index')
                ->with('success', "El itinerario '{$itinerary->name}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando itinerario: '.$e->getMessage());

            return back()->withInput()->with('error', 'Error al actualizar el itinerario.');
        }
    }

    /**
     * Remove the specified itinerary.
     */
    public function destroy(Itinerary $itinerary): RedirectResponse
    {
        try {
            $name = $itinerary->name;
            $itinerary->delete();

            return redirect()->route('admin.itineraries.index')
                ->with('success', "El itinerario '{$name}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando itinerario: '.$e->getMessage());

            return back()->with('error', 'No se pudo eliminar el itinerario.');
        }
    }

    /**
     * Toggle the active status of an itinerary.
     */
    public function toggleStatus(Itinerary $itinerary): RedirectResponse
    {
        $itinerary->is_active = ! $itinerary->is_active;
        $itinerary->save();

        return back()->with('success', 'Estado del itinerario actualizado.');
    }
}
