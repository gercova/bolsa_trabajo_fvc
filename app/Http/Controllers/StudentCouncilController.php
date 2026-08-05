<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCouncilRequest;
use App\Models\StudentCouncil;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentCouncilController extends Controller
{
    /**
     * Display the student council management list in admin dashboard.
     */
    public function index(Request $request): View
    {
        $search    = $request->input('search');
        $period    = $request->input('period');
        $programId = $request->input('program_id');

        $query = StudentCouncil::with(['user', 'studyProgram'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('position', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('academic_period', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('names', 'LIKE', "%{$search}%")
                                ->orWhere('dni', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($period, fn($q) => $q->where('academic_period', $period))
            ->when($programId, fn($q) => $q->where('study_program_id', $programId))
            ->orderBy('academic_period', 'desc')
            ->orderBy('id', 'asc');

        $councils = $query->paginate(12)->appends($request->only(['search', 'period', 'program_id']));

        $users      = User::where('is_active', true)->orderBy('names')->get();
        $programs   = StudyProgram::where('is_active', true)->orderBy('name')->get();
        $periods    = StudentCouncil::select('academic_period')
            ->distinct()
            ->orderBy('academic_period', 'desc')
            ->pluck('academic_period');

        return view('admin.student-council.index', compact(
            'councils',
            'users',
            'programs',
            'periods',
            'search',
            'period',
            'programId'
        ));
    }

    /**
     * Store a new student council member.
     */
    public function store(StudentCouncilRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        try {
            $user = User::find($validated['user_id']);

            if (empty($validated['name']) && $user) {
                $validated['name'] = $user->names;
            }

            $validated['is_active'] = $request->boolean('is_active', true);

            StudentCouncil::create($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Miembro del Consejo creado correctamente.'], 201);
            }

            return redirect()->route('admin.student-council.index')
                ->with('success', 'Miembro del Consejo de Estudiantes guardado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar miembro del Consejo de Estudiantes: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ocurrió un error al guardar el registro.'], 500);
            }

            return back()->withInput()->with('error', 'Ocurrió un error al guardar el registro.');
        }
    }

    /**
     * Update an existing student council member.
     */
    public function update(StudentCouncilRequest $request, StudentCouncil $studentCouncil): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        try {
            $user = User::find($validated['user_id']);

            if (empty($validated['name']) && $user) {
                $validated['name'] = $user->names;
            }

            $validated['is_active'] = $request->boolean('is_active', true);

            $studentCouncil->update($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Registro del Consejo actualizado correctamente.']);
            }

            return redirect()->route('admin.student-council.index')
                ->with('success', 'Miembro del Consejo actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar miembro del Consejo de Estudiantes: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ocurrió un error al actualizar el registro.'], 500);
            }

            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el registro.');
        }
    }

    /**
     * Toggle the active status of a council record.
     */
    public function toggleStatus(StudentCouncil $studentCouncil): JsonResponse
    {
        $studentCouncil->update(['is_active' => !$studentCouncil->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del miembro actualizado.',
            'status'  => $studentCouncil->is_active,
        ]);
    }

    /**
     * Delete a student council member record.
     */
    public function destroy(StudentCouncil $studentCouncil): JsonResponse|RedirectResponse
    {
        try {
            $studentCouncil->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Miembro eliminado del Consejo correctamente.']);
            }

            return redirect()->route('admin.student-council.index')
                ->with('success', 'Registro del Consejo eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar miembro del Consejo: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'No se pudo eliminar el registro.'], 500);
            }

            return back()->with('error', 'No se pudo eliminar el registro.');
        }
    }
}
