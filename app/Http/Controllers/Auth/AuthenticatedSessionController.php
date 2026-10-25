<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        Log::info('Inicio de sesión exitoso', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $user = Auth::user();

        // Garantizar acceso y permisos específicos para el rol Estudiante / Student
        $isStudent = $user && (
            in_array(strtolower((string) $user->role), ['estudiante', 'student']) ||
            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Estudiante', 'Student', 'estudiante', 'student']))
        );

        if ($isStudent) {
            $studentRoleName = ($user->role === 'Student' || (method_exists($user, 'hasRole') && $user->hasRole('Student')))
                ? 'Student'
                : 'Estudiante';

            // Permisos iniciales exclusivos para estudiantes:
            // 1. Actualizar información personal
            // 2. Acceso a la biblioteca virtual
            // 3. Presentar solicitudes administrativas
            // 4. Ver sus certificados
            // 5. Consultar el reglamento interno
            $allowedPermissions = [
                // Actualizar información personal
                'actualizar-informacion-personal',
                'actualizar-perfil',
                'update-personal-information',
                // Acceso a la biblioteca virtual
                'acceder-biblioteca-virtual',
                'acceder-biblioteca',
                'access-virtual-library',
                // Presentar solicitudes administrativas
                'presentar-solicitudes-administrativas',
                'enviar-solicitudes-administrativas',
                'submit-administrative-requests',
                // Ver sus certificados
                'ver-certificados',
                'consultar-certificados',
                'view-certificates',
                // Consultar el reglamento interno
                'consultar-reglamento-interno',
                'ver-reglamento-interno',
                'consult-internal-regulations',
            ];

            // 1. Asegurar existencia de permisos sin colisiones de clave única
            $existingPermissions = Permission::whereIn('name', $allowedPermissions)
                ->where('guard_name', 'web')
                ->pluck('name')
                ->toArray();

            $missingPermissions = array_diff($allowedPermissions, $existingPermissions);
            foreach ($missingPermissions as $permissionName) {
                try {
                    Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                } catch (\Throwable) {
                    // Ignorar si ya fue insertado por otra petición concurrente
                }
            }

            // Refrescar caché de permisos de Spatie
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            // 2. Asegurar existencia y sincronización de roles de estudiante
            $rolesToSync = array_unique(array_filter([
                $studentRoleName,
                'Estudiante',
                'Student',
            ]));

            $existingRoles = Role::whereIn('name', $rolesToSync)
                ->where('guard_name', 'web')
                ->get()
                ->keyBy('name');

            foreach ($rolesToSync as $roleName) {
                $role = $existingRoles->get($roleName);
                if (! $role) {
                    try {
                        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
                    } catch (\Throwable) {
                        $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
                    }
                }
                if ($role) {
                    $role->syncPermissions($allowedPermissions);
                }
            }

            $studentRole = $existingRoles->get($studentRoleName)
                ?? Role::where('name', $studentRoleName)->where('guard_name', 'web')->first();

            if ($studentRole && method_exists($user, 'hasRole') && ! $user->hasRole($studentRoleName)) {
                $user->assignRole($studentRole);
            }

            // Remover posibles roles administrativos residuales para mantener permisos estrictos de estudiante
            $adminRoles = [
                'Admin', 'Administrador', 'Director', 'Docente', 'Administrativo',
                'Coordinador de Área', 'Coordinador de Calidad', 'Coordinador de Empleabilidad',
                'Coordinador de Educación Continua', 'Coordinador de Investigación',
            ];
            foreach ($adminRoles as $adminRole) {
                if (method_exists($user, 'hasRole') && $user->hasRole($adminRole)) {
                    $user->removeRole($adminRole);
                }
            }

            $user->syncPermissions($allowedPermissions);

            if (session()->has('url.intended') && str_contains(session('url.intended'), 'admin')) {
                session()->forget('url.intended');
            }
        }

        $isAdminOrStaff = $user && ! $isStudent && (
            in_array($user->role, [
                'Admin', 'Administrador', 'Director', 'Docente', 'Administrativo',
                'Coordinador de Área', 'Coordinador de Calidad', 'Coordinador de Empleabilidad',
                'Coordinador de Educación Continua', 'Coordinador de Investigación',
            ]) ||
            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole([
                'Admin', 'Administrador', 'Director', 'Docente', 'Administrativo',
                'Coordinador de Área', 'Coordinador de Calidad', 'Coordinador de Empleabilidad',
                'Coordinador de Educación Continua', 'Coordinador de Investigación',
            ]))
        );

        $defaultRoute = $isAdminOrStaff
            ? route('admin.dashboard.index', absolute: false)
            : route('biblioteca.index', absolute: false);

        return redirect()->intended($defaultRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        $email = Auth::user()?->email;

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('Cierre de sesión', [
            'user_id' => $userId,
            'email' => $email,
            'ip' => $request->ip(),
        ]);

        return redirect('/');
    }
}
