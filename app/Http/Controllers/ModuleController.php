<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules.
     */
    public function index(Request $request): View
    {
        $search   = $request->input('search');
        $courseId = $request->input('course_id');
        $status   = $request->input('status');

        $query = Module::with('course')
            ->withCount(['itineraries', 'certificateDetails'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('credits', 'LIKE', "%{$search}%")
                        ->orWhereHas('course', function ($cq) use ($search) {
                            $cq->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('code', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('is_active', (bool) $status);
            })
            ->orderBy('course_id')
            ->orderBy('name', 'asc');

        $modules = $query->paginate(10)->appends($request->only(['search', 'course_id', 'status']));
        $courses = Course::where('is_active', true)->orderBy('name')->get();

        // Stat counters
        $totalModules   = Module::count();
        $activeModules  = Module::where('is_active', true)->count();
        $coursesCount   = Course::has('modules')->count();

        return view('admin.modules.index', compact(
            'modules',
            'courses',
            'totalModules',
            'activeModules',
            'coursesCount',
            'search',
            'courseId',
            'status'
        ));
    }

    /**
     * Store a newly created module.
     */
    public function store(ModuleRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $module = Module::create($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El módulo '{$module->name}' ha sido registrado exitosamente.",
                    'module'  => $module->load('course'),
                ], 201);
            }

            return redirect()->route('admin.modules.index')
                ->with('success', "El módulo '{$module->name}' ha sido registrado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error registrando módulo: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al registrar el módulo: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al registrar el módulo.');
        }
    }

    /**
     * Update the specified module.
     */
    public function update(ModuleRequest $request, Module $module): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->boolean('is_active', true);

            $module->update($data);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El módulo '{$module->name}' ha sido actualizado exitosamente.",
                    'module'  => $module->load('course'),
                ], 200);
            }

            return redirect()->route('admin.modules.index')
                ->with('success', "El módulo '{$module->name}' ha sido actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando módulo: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el módulo: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Error al actualizar el módulo.');
        }
    }

    /**
     * Remove the specified module.
     */
    public function destroy(Module $module): RedirectResponse|JsonResponse
    {
        try {
            $name = $module->name;
            $module->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El módulo '{$name}' ha sido eliminado correctamente.",
                ], 200);
            }

            return redirect()->route('admin.modules.index')
                ->with('success', "El módulo '{$name}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando módulo: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el módulo.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el módulo.');
        }
    }

    /**
     * Toggle the active status of a module.
     */
    public function toggleStatus(Module $module): JsonResponse|RedirectResponse
    {
        $module->is_active = !$module->is_active;
        $module->save();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $module->is_active,
                'message'   => 'Estado actualizado correctamente.',
            ]);
        }

        return back()->with('success', 'Estado del módulo actualizado.');
    }

    /**
     * Get modules by course (for dynamic dropdowns).
     */
    public function byCourse(Course $course): JsonResponse
    {
        $modules = $course->modules()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'credits']);
        return response()->json($modules);
    }
}
