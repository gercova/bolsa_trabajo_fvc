<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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
        $isAdminOrStaff = $user && (
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
