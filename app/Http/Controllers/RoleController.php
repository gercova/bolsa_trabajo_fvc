<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Listar todos los roles del sistema (Index)
     */
    public function index(Request $request): View
    {
        $query = Role::withCount(['users', 'permissions'])->with('permissions');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('name', 'asc')->get();
        $permissions = Permission::all();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Mostrar formulario para crear nuevo rol
     */
    public function create(): View
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Guardar un nuevo rol en la base de datos
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique'   => 'Ya existe un rol registrado con este nombre.',
            'name.max'      => 'El nombre del rol no debe superar los 255 caracteres.',
        ]);

        try {
            $role = Role::create([
                'name'       => trim($request->name),
                'guard_name' => 'web',
            ]);

            if ($request->filled('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => "Rol '{$role->name}' creado con éxito.",
                    'redirect' => route('admin.roles.index'),
                    'role'     => $role,
                ], 201);
            }

            return redirect()->route('admin.roles.index')
                             ->with('success', "El rol '{$role->name}' ha sido registrado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error creando rol: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al crear el rol: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Ocurrió un error inesperado al guardar el rol.');
        }
    }

    /**
     * Mostrar formulario para editar un rol existente
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualizar los datos y permisos de un rol
     */
    public function update(Request $request, Role $role): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique'   => 'Ya existe un rol registrado con este nombre.',
            'name.max'      => 'El nombre del rol no debe superar los 255 caracteres.',
        ]);

        try {
            $role->update([
                'name' => trim($request->name),
            ]);

            $role->syncPermissions($request->permissions ?? []);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => "Rol '{$role->name}' actualizado correctamente.",
                    'redirect' => route('admin.roles.index'),
                ], 200);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', "El rol '{$role->name}' se ha actualizado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error actualizando rol: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al actualizar el rol: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Ocurrió un error inesperado al actualizar el rol.');
        }
    }

    /**
     * Eliminar un rol del sistema
     */
    public function destroy(Role $role): JsonResponse|RedirectResponse
    {
        // Proteger roles fundamentales del sistema
        $systemRoles = ['Administrador', 'Director', 'Admin'];
        if (in_array($role->name, $systemRoles)) {
            $msg = "El rol '{$role->name}' es un rol del sistema y no puede ser eliminado.";
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }

        try {
            $roleName = $role->name;
            $role->syncPermissions([]);
            $role->delete();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "El rol '{$roleName}' ha sido eliminado correctamente.",
                ], 200);
            }

            return redirect()->route('admin.roles.index')
                ->with('success', "El rol '{$roleName}' ha sido eliminado correctamente.");

        } catch (\Exception $e) {
            Log::error('Error eliminando rol: ' . $e->getMessage());

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo eliminar el rol.',
                ], 500);
            }

            return back()->with('error', 'No se pudo eliminar el rol.');
        }
    }
}
