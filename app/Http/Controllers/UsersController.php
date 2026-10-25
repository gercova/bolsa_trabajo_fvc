<?php

namespace App\Http\Controllers;

use App\Exports\UserTemplateExport;
use App\Http\Requests\PasswordValidate;
use App\Http\Requests\UserImportRequest;
use App\Http\Requests\UserValidate;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UsersController extends Controller
{
    public function __construct() {}

    public function index(Request $request): View
    {
        // Parámetros de búsqueda y filtros
        $search = $request->input('search');
        $status = $request->input('status');                // 'active', 'inactive', o null (todos)
        $role = $request->input('role');                  // filtrar por rol
        $jobPosition = $request->input('job_position');          // filtrar por puesto
        $perPage = $request->input('per_page', 10);          // items por página (10, 25, 50)
        $sortBy = $request->input('sort_by', 'created_at'); // campo de ordenamiento
        $sortOrder = $request->input('sort_order', 'desc');    // ascendente o descendente

        // Validar que per_page sea un valor permitido
        if (! in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Validar campos de ordenamiento permitidos
        $allowedSortFields = ['names', 'email', 'dni', 'job_position', 'created_at', 'is_active'];
        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        // Construir la consulta reutilizable con los filtros
        $users = $this->buildFilteredQuery($request)
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->appends($request->only(['search', 'status', 'role', 'job_position', 'per_page', 'sort_by', 'sort_order']));

        // Obtener todos los roles para el filtro
        $roles = Role::all();

        // Obtener lista de puestos distintos registrados para el filtro
        $jobPositions = User::query()
            ->whereNotNull('job_position')
            ->where('job_position', '!=', '')
            ->distinct()
            ->orderBy('job_position')
            ->pluck('job_position');

        return view('admin.user.index', compact(
            'users',
            'search',
            'status',
            'role',
            'jobPosition',
            'jobPositions',
            'perPage',
            'sortBy',
            'sortOrder',
            'roles'
        ));
    }

    /**
     * Construye la consulta filtrada según los parámetros de la solicitud.
     */
    protected function buildFilteredQuery(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $role = $request->input('role');
        $jobPosition = $request->input('job_position');
        $createdBefore = $request->input('created_before');

        return User::query()
            // Búsqueda por texto (nombres, email, DNI, puesto)
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
                $query->where('is_active', $status === 'active' || $status === '1');
            })
            // Filtrar por rol (Spatie y columna role)
            ->when($role, function ($query) use ($role) {
                $query->where(function ($q) use ($role) {
                    $q->where('role', $role)
                        ->orWhereHas('roles', fn ($r) => $r->where('name', $role));
                });
            })
            // Filtrar por puesto de trabajo
            ->when($jobPosition, function ($query) use ($jobPosition) {
                $query->where('job_position', $jobPosition);
            })
            // Filtrar por fecha de creación (registrados antes de)
            ->when($createdBefore, function ($query) use ($createdBefore) {
                $query->whereDate('created_at', '<=', $createdBefore);
            });
    }

    /**
     * Muestra el formulario para crear un nuevo usuario
     */
    public function create(): View
    {
        $roles = Role::get();

        return view('admin.user.create', compact('roles'));
    }

    public function edit(User $user): View
    {
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos
     */
    public function store(UserValidate $request): RedirectResponse
    {
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
        $password = ! empty($validated['password']) ? $validated['password'] : $validated['dni'];

        // Crear el usuario
        $user = User::create([
            'dni' => $validated['dni'],
            'names' => $validated['names'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'job_position' => $validated['job_position'] ?? null,
            'photo_profile' => $photoPath,
            'cv_file' => $cvPath,
            'password' => Hash::make($password),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Asignar rol con Spatie
        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function updatePassword(PasswordValidate $request, User $user): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $user->update(['password' => Hash::make($validated['password'])]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contraseña de '.($user->names ?? 'usuario').' actualizada exitosamente.',
            ], 200);
        }

        return redirect()->route('admin.users.index')->with('success', 'Contraseña de '.($user->names ?? 'usuario').' actualizada exitosamente.');
    }

    public function update(UserValidate $request, User $user): RedirectResponse
    {
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
        if (! empty($validated['password'])) {
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

        return redirect()
            ->route('admin.users.index')->with('success', "Usuario {$user->names} actualizado exitosamente.");
    }

    public function toggleStatus(User $user): JsonResponse|RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado del usuario actualizado.',
                'status' => $user->is_active,
            ]);
        }

        $statusMessage = $user->is_active ? 'activado' : 'desactivado';

        return redirect()->back()->with('success', "Usuario {$user->names} {$statusMessage} correctamente.");
    }

    public function destroy(User $user): JsonResponse|RedirectResponse
    {
        // Prevenir auto-eliminación
        if (auth()->id() && $user->id === auth()->id()) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propia cuenta en sesión activa.',
                ], 400);
            }

            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta en sesión activa.');
        }

        // Verificar que no sea el último admin
        if ($this->isUserAdmin($user) && $this->getTotalAdminCount() <= 1) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el único administrador del sistema.',
                ], 400);
            }

            return redirect()->back()->with('error', 'No se puede eliminar el único administrador del sistema.');
        }

        // Eliminar archivos físicos asociados
        if ($user->photo_profile) {
            Storage::disk('public')->delete($user->photo_profile);
        }
        if ($user->cv_file) {
            Storage::disk('public')->delete($user->cv_file);
        }

        $user->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente.',
            ]);
        }

        return redirect()->back()->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Calcula la cantidad de usuarios que coinciden con los filtros para la previsualización del modal de eliminación masiva.
     */
    public function countBulkDelete(Request $request): JsonResponse
    {
        $query = $this->buildFilteredQuery($request);
        $currentUserId = auth()->id();
        $totalMatching = (clone $query)->count();
        $queryWithoutSelf = (clone $query)->when($currentUserId, fn ($q) => $q->where('id', '!=', $currentUserId));
        $count = $queryWithoutSelf->count();

        // Contar administradores afectados
        $adminCountInFilter = (clone $queryWithoutSelf)
            ->where(function ($q) {
                $q->whereRaw('LOWER(role) IN (?, ?)', ['admin', 'administrador'])
                    ->orWhereHas('roles', fn ($r) => $r->whereRaw('LOWER(name) IN (?, ?)', ['admin', 'administrador']));
            })
            ->count();

        $totalAdmins = $this->getTotalAdminCount();

        return response()->json([
            'success' => true,
            'count' => $count,
            'total_matching' => $totalMatching,
            'includes_self' => $totalMatching > $count,
            'admin_count_in_filter' => $adminCountInFilter,
            'total_admins' => $totalAdmins,
            'will_delete_all_admins' => $totalAdmins > 0 && $adminCountInFilter >= $totalAdmins,
        ]);
    }

    /**
     * Elimina usuarios de forma masiva (por selección de IDs o por criterios de filtrado).
     */
    public function bulkDelete(Request $request): JsonResponse|RedirectResponse
    {
        $byFilters = $request->boolean('by_filters') || $request->boolean('select_all');
        $currentUserId = auth()->id();
        $excludedSelf = false;
        $protectedAdminCount = 0;
        if ($byFilters) {
            // Validar que se haya especificado al menos un filtro o confirmación explícita
            $hasFilter = $request->filled('role')
                || $request->filled('job_position')
                || ($request->input('status') !== null && $request->input('status') !== '')
                || $request->filled('search')
                || $request->filled('created_before');

            if (! $hasFilter && ! $request->boolean('confirm_all')) {
                $msg = 'Debe especificar al menos un filtro (rol, puesto, estado o búsqueda) para la eliminación masiva.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->back()->with('error', $msg);
            }

            $query = $this->buildFilteredQuery($request);

            // Excluir la cuenta propia en sesión activa
            if ($currentUserId) {
                $hasSelf = (clone $query)->where('id', $currentUserId)->exists();
                if ($hasSelf) {
                    $excludedSelf = true;
                    $query->where('id', '!=', $currentUserId);
                }
            }

            $usersToDelete = $query->get();

            if ($usersToDelete->isEmpty()) {
                $msg = 'No se encontraron usuarios para eliminar con los filtros seleccionados.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 404);
                }

                return redirect()->back()->with('info', $msg);
            }

            // Protección de administradores: verificar si dejaría al sistema sin administradores
            $totalAdmins = $this->getTotalAdminCount();
            $adminUsersInList = $usersToDelete->filter(fn ($u) => $this->isUserAdmin($u));
            if ($totalAdmins > 0 && $adminUsersInList->count() >= $totalAdmins) {
                // Proteger a todos los administradores en el grupo para no dejar sin acceso
                $usersToDelete = $usersToDelete->reject(fn ($u) => $this->isUserAdmin($u));
                $protectedAdminCount = $adminUsersInList->count();
            }
        } else {
            // Modo por selección explícita de IDs
            $ids = $request->input('ids', []);
            if (empty($ids) || ! is_array($ids)) {
                $msg = 'Debe seleccionar al menos un usuario para eliminar.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->back()->with('error', $msg);
            }

            $validIds = array_values(array_filter(array_map('intval', $ids)));
            if (empty($validIds)) {
                $msg = 'Identificadores de usuario no válidos.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->back()->with('error', $msg);
            }

            // Excluir cuenta activa propia
            if ($currentUserId && in_array($currentUserId, $validIds, true)) {
                $validIds = array_values(array_diff($validIds, [$currentUserId]));
                $excludedSelf = true;
            }

            if (empty($validIds)) {
                $msg = 'No se puede eliminar la cuenta propia que se encuentra en sesión activa.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return redirect()->back()->with('error', $msg);
            }

            $usersToDelete = User::whereIn('id', $validIds)->get();

            // Verificación de protección de administradores
            $totalAdmins = $this->getTotalAdminCount();
            $adminUsersInList = $usersToDelete->filter(fn ($u) => $this->isUserAdmin($u));
            if ($totalAdmins > 0 && $adminUsersInList->count() >= $totalAdmins) {
                $msg = 'No se puede eliminar a todos los administradores del sistema. Debe permanecer al menos un administrador.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }

                return redirect()->back()->with('error', $msg);
            }
        }

        if ($usersToDelete->isEmpty()) {
            $msg = 'No hay usuarios disponibles para eliminar (fueron protegidos por seguridad).';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 404);
            }

            return redirect()->back()->with('info', $msg);
        }

        // Ejecutar eliminación en transacción y limpiar archivos multimedia
        $deletedCount = DB::transaction(function () use ($usersToDelete) {
            $count = 0;
            foreach ($usersToDelete as $user) {
                if ($user->photo_profile) {
                    Storage::disk('public')->delete($user->photo_profile);
                }
                if ($user->cv_file) {
                    Storage::disk('public')->delete($user->cv_file);
                }
                $user->delete();
                $count++;
            }

            return $count;
        });

        $message = $deletedCount === 1
            ? 'Se ha eliminado 1 usuario exitosamente.'
            : "Se han eliminado exitosamente {$deletedCount} usuarios.";

        $notices = [];
        if ($excludedSelf) {
            $notices[] = 'Su propia cuenta de usuario fue protegida y no se eliminó.';
        }
        if ($protectedAdminCount > 0) {
            $notices[] = "{$protectedAdminCount} cuenta(s) de administrador fueron protegidas para garantizar el acceso al sistema.";
        }

        if (! empty($notices)) {
            $message .= ' ('.implode(' ', $notices).')';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'excluded_self' => $excludedSelf,
                'protected_admin_count' => $protectedAdminCount,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Determina si un usuario tiene rol de administrador.
     */
    protected function isUserAdmin(User $user): bool
    {
        $roleName = strtolower((string) $user->role);
        if (in_array($roleName, ['admin', 'administrador'])) {
            return true;
        }

        return $user->hasAnyRole(['admin', 'Admin', 'administrador', 'Administrador']);
    }

    /**
     * Retorna el total de administradores registrados en la plataforma.
     */
    protected function getTotalAdminCount(): int
    {
        return User::query()
            ->where(function ($q) {
                $q->whereRaw('LOWER(role) IN (?, ?)', ['admin', 'administrador'])
                    ->orWhereHas('roles', fn ($r) => $r->whereRaw('LOWER(name) IN (?, ?)', ['admin', 'administrador']));
            })
            ->count();
    }

    /**
     * Download the template guide for bulk user import (.xlsx or .csv).
     */
    public function downloadTemplate(Request $request): BinaryFileResponse|StreamedResponse
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));

        if (in_array($format, ['xlsx', 'excel', 'xls'], true)) {
            $ext = $format === 'xls' ? 'xls' : 'xlsx';
            $excelType = $format === 'xls' ? \Maatwebsite\Excel\Excel::XLS : \Maatwebsite\Excel\Excel::XLSX;
            $filename = 'plantilla_usuarios_'.date('Y-m-d').'.'.$ext;

            return Excel::download(new UserTemplateExport, $filename, $excelType);
        }

        $filename = 'plantilla_usuarios_'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM so Excel opens accents correctly
            fwrite($out, "\xEF\xBB\xBF");
            // Header line
            fputcsv($out, [
                'N°',
                'DNI',
                'Nombres',
                'Apellido Paterno',
                'Apellido Materno',
                'job_position',
                'email',
                'Rol',
                'Teléfono',
            ]);

            // Sample rows
            fputcsv($out, [
                '1',
                '71234567',
                'Juan Carlos',
                'Pérez',
                'Gómez',
                'student/graduate',
                '', // Auto-generated: jperezg@iestpfvc.edu.pe
                'Estudiante',
                '987654321',
            ]);

            fputcsv($out, [
                '2',
                '45678901',
                'María Elena',
                'Ramos',
                'Castillo',
                'teaching/administrative staff',
                '', // Auto-generated: mramosc@iestpfvc.edu.pe
                'Docente',
                '976543210',
            ]);

            fputcsv($out, [
                '3',
                '78901234',
                'Luis Alberto',
                'Vásquez',
                'Torres',
                'student/graduate',
                'lvasquezt_personal@gmail.com', // Keeps provided email
                'Estudiante',
                '965432109',
            ]);

            fputcsv($out, [
                '4',
                '41239876',
                'Rosa Aurora',
                'Mendoza',
                'Flores',
                'teaching/administrative staff',
                '', // Auto-generated: rmendozaf@iestpfvc.edu.pe
                'Administrativo',
                '954321098',
            ]);

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Process bulk import of user records via Excel (.xlsx, .xls) or CSV (.csv).
     */
    public function import(UserImportRequest $request): RedirectResponse
    {
        $prevErrorHandler = set_error_handler(
            function (int $errno, string $errstr, string $errfile) use (&$prevErrorHandler): bool {
                if (str_contains($errstr, 'iconv') && str_contains($errstr, 'multibyte')) {
                    return true;
                }
                if ($prevErrorHandler !== null) {
                    return (bool) call_user_func($prevErrorHandler, $errno, $errstr, $errfile);
                }

                return false;
            }
        );

        try {
            $importer = new UserImport;
            Excel::import($importer, $request->file('file'));

            $parts = [];
            if ($importer->createdCount > 0) {
                $parts[] = "{$importer->createdCount} usuario(s) nuevo(s) registrado(s)";
            }
            if ($importer->updatedCount > 0) {
                $parts[] = "{$importer->updatedCount} usuario(s) actualizado(s)";
            }
            if ($importer->skippedCount > 0) {
                $parts[] = "{$importer->skippedCount} fila(s) omitida(s)";
            }

            $msg = ! empty($parts)
                ? 'Importación completada con éxito: '.implode(', ', $parts).'.'
                : 'Proceso de importación finalizado sin registros procesados.';

            $redirect = redirect()->route('admin.users.index')->with('success', $msg);

            if (! empty($importer->errors)) {
                $redirect = $redirect->with('import_errors', $importer->errors);
            }

            return $redirect;
        } catch (\Throwable $e) {
            Log::error('Error importando usuarios: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->route('admin.users.index')->with('error', 'Error al procesar el archivo de usuarios: '.$e->getMessage());
        } finally {
            restore_error_handler();
        }
    }
}
