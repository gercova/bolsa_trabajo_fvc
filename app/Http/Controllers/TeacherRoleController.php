<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserRoleDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class TeacherRoleController extends Controller
{
    /**
     * Display the teacher-roles management section (sub-view of user management).
     */
    public function index(Request $request): View
    {
        $search     = $request->input('search');
        $programId  = $request->input('program_id');
        $onlyCoord  = $request->boolean('only_coordinators');

        $query = UserRoleDetail::with(['user', 'program'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('names', 'LIKE', "%{$search}%")
                      ->orWhere('dni', 'LIKE', "%{$search}%")
                      ->orWhere('job_position', 'LIKE', "%{$search}%");
                });
            })
            ->when($programId, fn($q) => $q->where('program_id', $programId))
            ->when($onlyCoord,  fn($q) => $q->where('is_coordinator', true))
            ->orderByDesc('is_coordinator')
            ->orderBy('created_at');

        $details  = $query->paginate(15)->appends($request->only(['search', 'program_id', 'only_coordinators']));
        $programs = StudyProgram::orderBy('name')->get();
        $teachers = User::whereHas('roles', fn($q) => $q->where('name', 'Docente'))
                        ->orWhere('role', 'Docente')
                        ->where('is_active', true)
                        ->orderBy('names')
                        ->get();
        $roles    = Role::orderBy('name')->get();

        return view('admin.user.teacher-roles', compact(
            'details', 'programs', 'teachers', 'roles',
            'search', 'programId', 'onlyCoord'
        ));
    }

    /**
     * Store a new teacher-role detail record.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'program_id'     => 'nullable|exists:study_programs,id',
            'is_coordinator' => 'boolean',
            'specialty'      => 'nullable|string|max:255',
            'is_active'      => 'boolean',
        ], [
            'user_id.required' => 'Debe seleccionar un usuario.',
            'user_id.exists'   => 'El usuario seleccionado no existe.',
            'program_id.exists' => 'El programa seleccionado no existe.',
        ]);

        // Check for duplicate assignment
        $exists = UserRoleDetail::where('user_id', $validated['user_id'])
                                ->where('program_id', $validated['program_id'] ?? null)
                                ->exists();

        if ($exists) {
            $msg = 'Este docente ya está asignado a ese programa.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withInput()->with('error', $msg);
        }

        try {
            UserRoleDetail::create([
                'user_id'        => $validated['user_id'],
                'program_id'     => $validated['program_id'] ?? null,
                'is_coordinator' => $request->boolean('is_coordinator'),
                'specialty'      => $validated['specialty'] ?? null,
                'is_active'      => $request->boolean('is_active', true),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Asignación creada correctamente.'], 201);
            }

            return redirect()->route('admin.teacher-roles.index')
                             ->with('success', 'Asignación de docente guardada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error creando asignación de docente: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withInput()->with('error', 'Ocurrió un error al guardar la asignación.');
        }
    }

    /**
     * Update an existing teacher-role detail record.
     */
    public function update(Request $request, UserRoleDetail $teacherRole): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'program_id'     => 'nullable|exists:study_programs,id',
            'is_coordinator' => 'boolean',
            'specialty'      => 'nullable|string|max:255',
            'is_active'      => 'boolean',
        ]);

        // Check duplicate only if user or programme changed
        if (
            $teacherRole->user_id    != $validated['user_id'] ||
            $teacherRole->program_id != ($validated['program_id'] ?? null)
        ) {
            $exists = UserRoleDetail::where('user_id', $validated['user_id'])
                                    ->where('program_id', $validated['program_id'] ?? null)
                                    ->where('id', '!=', $teacherRole->id)
                                    ->exists();

            if ($exists) {
                $msg = 'Este docente ya está asignado a ese programa.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return back()->withInput()->with('error', $msg);
            }
        }

        try {
            $teacherRole->update([
                'user_id'        => $validated['user_id'],
                'program_id'     => $validated['program_id'] ?? null,
                'is_coordinator' => $request->boolean('is_coordinator'),
                'specialty'      => $validated['specialty'] ?? null,
                'is_active'      => $request->boolean('is_active', true),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Asignación actualizada correctamente.']);
            }

            return redirect()->route('admin.teacher-roles.index')
                             ->with('success', 'Asignación actualizada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error actualizando asignación de docente: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withInput()->with('error', 'Ocurrió un error al actualizar la asignación.');
        }
    }

    /**
     * Toggle the is_active status of a teacher-role detail.
     */
    public function toggleStatus(UserRoleDetail $teacherRole): JsonResponse
    {
        $teacherRole->update(['is_active' => !$teacherRole->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado.',
            'status'  => $teacherRole->is_active,
        ]);
    }

    /**
     * Delete a teacher-role detail record.
     */
    public function destroy(UserRoleDetail $teacherRole): JsonResponse|RedirectResponse
    {
        try {
            $teacherRole->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Asignación eliminada correctamente.']);
            }

            return redirect()->route('admin.teacher-roles.index')
                             ->with('success', 'Asignación eliminada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error eliminando asignación: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'No se pudo eliminar la asignación.'], 500);
            }

            return back()->with('error', 'No se pudo eliminar la asignación.');
        }
    }
}
