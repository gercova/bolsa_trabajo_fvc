@extends('layouts.app')

@section('title', 'Iniciar Sesión — Bolsa de Trabajo')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: var(--sand);">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl shadow-[rgba(15,23,42,.06)] p-8 md:p-10 border" style="border-color: var(--border);">

            {{-- Icono / Logo --}}
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-2xl flex items-center justify-center">
                    <img src="{{ asset($enterprise->logo_path) }}" alt="Logo {{ $enterprise->company_name }}" class="w-24 h-24 object-contain">
                </div>
            </div>

            {{-- Encabezado --}}
            <h2 class="text-center text-3xl font-bold mb-2" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; color: var(--ink);">
                Iniciar Sesión
            </h2>
            <p class="text-center text-sm mb-6" style="color: var(--ink-muted);">
                ¿No tienes una cuenta?
                <a href="{{ route('register') }}" class="font-medium underline decoration-1 underline-offset-2 transition-colors hover:text-[#0F172A]" style="color: var(--gold-vivid);">
                    Regístrate aquí
                </a>
            </p>

            {{-- Alertas de Estado / Sesión --}}
            @if (session('status'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-sm text-emerald-800">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-lg flex-shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Alerta de Bloqueo por Límite de Intentos (Rate Limit) --}}
            @if ($errors->has('email') && str_contains($errors->first('email'), 'intentos'))
                <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 text-sm text-red-800">
                    <i class="bi bi-shield-slash-fill text-red-600 text-lg flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold">Acceso bloqueado temporalmente</p>
                        <p class="text-xs text-red-700 mt-0.5">{{ $errors->first('email') }}</p>
                    </div>
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                @csrf

                {{-- Campo Honeypot de seguridad para detección de bots automatizados --}}
                <input type="text" name="_hp_security_check" style="position: absolute; left: -9999px; top: -9999px; opacity: 0; width: 0; height: 0; pointer-events: none;" tabindex="-1" autocomplete="off" aria-hidden="true">

                {{-- Correo --}}
                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none
                            @error('email') border-red-300 ring-2 ring-red-100 @else border-[#E2E0DA] focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309] @enderror"
                            style="background: var(--white); color: var(--ink);"
                            placeholder="correo@ejemplo.com">
                    </div>
                    @error('email')
                        @unless(str_contains($message, 'intentos'))
                            <p class="mt-1.5 text-sm text-red-500 flex items-center gap-1.5">
                                <i class="bi bi-exclamation-circle text-xs"></i>
                                {{ $message }}
                            </p>
                        @endunless
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password"
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none
                            @error('password') border-red-300 ring-2 ring-red-100 @else border-[#E2E0DA] focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309] @enderror"
                            style="background: var(--white); color: var(--ink);"
                            placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors"
                            aria-label="Alternar visibilidad de contraseña">
                            <i class="bi text-lg" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-500 flex items-center gap-1.5">
                            <i class="bi bi-exclamation-circle text-xs"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Recordarme + Olvidé contraseña --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember_me"
                            class="w-4 h-4 rounded border-gray-300 text-[#B45309] focus:ring-[#FEF3C7] focus:ring-offset-0">
                        <span class="text-sm" style="color: var(--ink-muted);">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium underline decoration-1 underline-offset-2 transition-colors hover:text-[#0F172A]" style="color: var(--gold-vivid);">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                {{-- Botón --}}
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-6 text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg cursor-pointer"
                    style="background: var(--ink); color: var(--white);">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Iniciar Sesión</span>
                </button>

                {{-- Indicador de Protección y Seguridad --}}
                <div class="pt-2 flex items-center justify-center gap-1.5 text-[11.5px] text-slate-500">
                    <i class="bi bi-shield-check text-emerald-600 text-sm"></i>
                    <span>Protección activa contra accesos no autorizados y fuerza bruta</span>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-xs" style="color: var(--ink-muted);">
            Al iniciar sesión aceptas nuestros
            <a href="#" class="underline decoration-1 underline-offset-2 transition-colors hover:text-[#0F172A]" style="color: var(--ink-muted);">Términos y Condiciones</a>
        </p>
    </div>
</div>
@endsection