<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordValidate;
use App\Http\Requests\UserValidate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UsersController extends Controller {

    public function __construct(){

    }

    public function index(Request $request): View {
        // Parámetros de búsqueda y filtros
        $search     = $request->input('search');
        $status     = $request->input('status');                // 'active', 'inactive', o null (todos)
        $role       = $request->input('role');                  // filtrar por rol
        $perPage    = $request->input('per_page', 10);          // items por página (10, 25, 50)
        $sortBy     = $request->input('sort_by', 'created_at'); // campo de ordenamiento
        $sortOrder  = $request->input('sort_order', 'desc');    // ascendente o descendente

        // Validar que per_page sea un valor permitido
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Validar campos de ordenamiento permitidos
        $allowedSortFields = ['names', 'email', 'dni', 'job_position', 'created_at', 'is_active'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        // Construir la consulta
        $users = User::query()
            // Búsqueda por texto
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('names', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('dni', 'LIKE', "%{$search}%")
                        ->orWhere('job_position', 'LIKE', "%{$search}%");
                });
            })
            // Filtrar por estado (activo/inactivo)
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            // Filtrar por rol usando Spatie
            ->when($role, function ($query) use ($role) {
                $query->role($role);
            })
            // Ordenamiento
            ->orderBy($sortBy, $sortOrder)
            // Paginación
            ->paginate($perPage)
            // Mantener los parámetros en los links de paginación
            ->appends($request->only(['search', 'status', 'role', 'per_page', 'sort_by', 'sort_order']));

        // Obtener todos los roles para el filtro
        $roles = Role::all();

        return view('admin.user.index', compact('users', 'search', 'status', 'role', 'perPage', 'sortBy', 'sortOrder', 'roles'));
    }

     /**
     * Muestra el formulario para crear un nuevo usuario
     */
    public function create(): View {
        $roles = Role::get();
        return view('admin.user.create', compact('roles'));
    }

    public function edit(User $user): View {
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos
     */
    public function store(UserValidate $request): RedirectResponse {
        $validated = $request->validated();

        // Manejar la subida de la foto de perfil
        $photoPath = null;
        if ($request->hasFile('photo_profile')) {
            $photoPath = $request->file('photo_profile')->store('profile-photos', 'public');
        }

        // Manejar la subida del CV
        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('cv-files', 'public');
        }

        // Crear el usuario
        $user = User::create([
            'dni'           => $validated['dni'],
            'names'         => $validated['names'],
            'email'         => $validated['email'],
            'role'          => $validated['role'],
            'job_position'  => $validated['job_position'],
            'photo_profile' => $photoPath,
            'cv_file'       => $cvPath,
            'password'      => Hash::make('#Pa$$w0rd$.'),
            'is_active'     => $request->boolean('is_active', true),
        ]);

        // Asignar rol con Spatie
        $user->assignRole($validated['role']);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado exitosamente.');
    }

    public function updatePassword(PasswordValidate $request, User $user): JsonResponse {
        $validated = $request->validated();
        $user->update(['password' => Hash::make($validated['password'])]);
        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada',
        ], 200);
    }

    public function update(UserValidate $request, User $user): RedirectResponse {
        $validated = $request->validated();

        // Manejar la subida de la foto de perfil
        if ($request->hasFile('photo_profile')) {
            // Eliminar foto anterior si existe
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $validated['photo_profile'] = $request->file('photo_profile')->store('profile-photos', 'public');
        } else {
            // Mantener la foto actual
            unset($validated['photo_profile']);
        }

        // Manejar la subida del CV
        if ($request->hasFile('cv_file')) {
            // Eliminar CV anterior si existe
            if ($user->cv_file) {
                Storage::disk('public')->delete($user->cv_file);
            }
            $validated['cv_file'] = $request->file('cv_file')->store('cv-files', 'public');
        } else {
            // Mantener el CV actual
            unset($validated['cv_file']);
        }

        // Manejar la contraseña (solo si se proporciona una nueva)
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Asegurar que is_active sea booleano
        $validated['is_active'] = $request->boolean('is_active');

        // Actualizar el usuario
        $user->update($validated);

        // Sincronizar rol con Spatie
        if (isset($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('admin.usuarios')->with('success', "Usuario {$user->names} actualizado exitosamente.");
    }

    public function toggleStatus(User $user): JsonResponse {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Estado del usuario actualizado.',
            'status'    => $user->is_active
        ]);
    }

    public function destroy(User $user): JsonResponse {
        // Verificar que no sea el último admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el único administrador.'
            ], 400);
        }

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente.'
        ]);
    }
}
