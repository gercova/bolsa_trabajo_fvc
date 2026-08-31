<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Course::withCount(['modules', 'certificates', 'itineraries'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('is_active', (bool) $status);
            })
            ->orderBy('name', 'asc');

        $courses = $query->paginate(10)->appends($request->only(['search', 'status']));

        // Stat counters
        $totalCourses           = Course::count();
        $activeCourses          = Course::where('is_active', true)->count();
        $withModulesCount       = Course::has('modules')->count();
        $withCertificatesCount  = Course::has('certificates')->count();

        return view('admin.courses.index', compact(
            'courses',
            'totalCourses',
            'activeCourses',
            'withModulesCount',
            'withCertificatesCount',
            'search',
            'status'
        ));
    }

    /**
     * Store a newly created course.
     */
    public function store(CourseRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $course = Course::create($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El curso '{$course->name}' ha sido registrado exitosamente.",
                    'course'  => $course,
                ], 201);
            }

            return redirect()->route('admin.courses.index')
                ->with('success', "El curso '{$course->name}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando curso: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al registrar el curso: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al registrar el curso.');
        }
    }

    /**
     * Update the specified course.
     */
    public function update(CourseRequest $request, Course $course): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $course->update($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El curso '{$course->name}' ha sido actualizado exitosamente.",
                    'course'  => $course,
                ], 200);
            }

            return redirect()->route('admin.courses.index')
                ->with('success', "El curso '{$course->name}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando curso: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el curso: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar el curso.');
        }
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): RedirectResponse|JsonResponse
    {
        try {
            $name = $course->name;
            $course->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El curso '{$name}' ha sido eliminado correctamente.",
                ], 200);
            }

            return redirect()->route('admin.courses.index')
                ->with('success', "El curso '{$name}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando curso: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el curso.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el curso.');
        }
    }

    /**
     * Toggle the active status of a course.
     */
    public function toggleStatus(Course $course): JsonResponse|RedirectResponse
    {
        $course->is_active = !$course->is_active;
        $course->save();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $course->is_active,
                'message'   => 'Estado actualizado correctamente.',
            ]);
        }

        return back()->with('success', 'Estado del curso actualizado.');
    }
}
