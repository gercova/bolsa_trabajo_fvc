<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Course::withCount(['modules', 'certificates', 'itineraries'])
            ->with('studyProgram')
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
        $studyPrograms = StudyProgram::where('is_active', true)->orderBy('name')->get();

        // Stat counters
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();
        $withModulesCount = Course::has('modules')->count();
        $withCertificatesCount = Course::has('certificates')->count();

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
            'studyPrograms' => $studyPrograms,
            'totalCourses' => $totalCourses,
            'activeCourses' => $activeCourses,
            'withModulesCount' => $withModulesCount,
            'withCertificatesCount' => $withCertificatesCount,
            'search' => $search ?? '',
            'status' => $status ?? '',
        ]);
    }

    /**
     * Store a newly created course.
     */
    public function store(CourseRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $course = Course::create($data);

            return redirect()->route('admin.courses.index')
                ->with('success', "El curso '{$course->name}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando curso: '.$e->getMessage());

            return back()->withInput()->with('error', 'Error al registrar el curso: '.$e->getMessage());
        }
    }

    /**
     * Update the specified course.
     */
    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active');

            $course->update($data);

            return redirect()->route('admin.courses.index')
                ->with('success', "El curso '{$course->name}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando curso: '.$e->getMessage());

            return back()->withInput()->with('error', 'Error al actualizar el curso: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): RedirectResponse
    {
        try {
            $name = $course->name;
            $course->delete();

            return redirect()->route('admin.courses.index')
                ->with('success', "El curso '{$name}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando curso: '.$e->getMessage());

            return back()->with('error', 'No se pudo eliminar el curso.');
        }
    }

    /**
     * Toggle the active status of a course.
     */
    public function toggleStatus(Course $course): RedirectResponse
    {
        $course->is_active = ! $course->is_active;
        $course->save();

        return back()->with('success', 'Estado del curso actualizado.');
    }
}
