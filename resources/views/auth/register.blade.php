@extends('layouts.app')

@section('title', 'Crear cuenta — Bolsa de Trabajo')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: var(--sand);">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl shadow-[rgba(15,23,42,.06)] p-8 md:p-10 border" style="border-color: var(--border);">
            
            {{-- Icono --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background-color: var(--ink);">
                    <i class="bi bi-person-plus text-3xl" style="color: var(--gold-vivid);"></i>
                </div>
            </div>

            {{-- Encabezado --}}
            <h2 class="text-center text-3xl font-bold mb-2" style="font-family: 'DM Serif Display', serif; color: var(--ink);">
                Crear una cuenta
            </h2>
            <p class="text-center text-sm mb-8" style="color: var(--ink-muted);">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="font-medium underline decoration-1 underline-offset-2 transition-colors hover:text-[#0F172A]" style="color: var(--gold-vivid);">
                    Inicia sesión aquí
                </a>
            </p>

            {{-- Formulario --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- DNI --}}
                <div>
                    <label for="dni" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">DNI</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-person-vcard text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="dni" name="dni" type="text" value="{{ old('dni') }}" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none
                            @error('dni') border-red-300 ring-2 ring-red-100 @else border-[#E2E0DA] focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309] @enderror"
                            style="background: var(--white); color: var(--ink);" 
                            placeholder="99999999">
                    </div>
                    @error('dni')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Nombre completo --}}
                <div>
                    <label for="name" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Nombre completo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-person text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none
                            @error('name') border-red-300 ring-2 ring-red-100 @else border-[#E2E0DA] focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309] @enderror"
                            style="background: var(--white); color: var(--ink);" 
                            placeholder="Juan Pérez">
                    </div>
                    @error('name')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Correo --}}
                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none
                            @error('email') border-red-300 ring-2 ring-red-100 @else border-[#E2E0DA] focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309] @enderror"
                            style="background: var(--white); color: var(--ink);" 
                            placeholder="correo@ejemplo.com">
                    </div>
                    @error('email')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none
                            @error('password') border-red-300 ring-2 ring-red-100 @else border-[#E2E0DA] focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309] @enderror"
                            style="background: var(--white); color: var(--ink);" 
                            placeholder="••••••••">
                    </div>
                    @error('password')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1.5" style="color: var(--ink);">Confirmar Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="bi bi-lock-fill text-lg" style="color: var(--ink-muted);"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#E2E0DA] text-sm transition-all duration-200 outline-none focus:ring-2 focus:ring-[#FEF3C7] focus:border-[#B45309]"
                            style="background: var(--white); color: var(--ink);" 
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Botón --}}
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-6 text-sm font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                    style="background: var(--ink); color: var(--white);">
                    <i class="bi bi-person-plus"></i>
                    Crear cuenta
                </button>
            </form>
        </div>

        <p class="text-center mt-6 text-xs" style="color: var(--ink-muted);">
            Al registrarte aceptas nuestros 
            <a href="#" class="underline decoration-1 underline-offset-2 transition-colors hover:text-[#0F172A]" style="color: var(--ink-muted);">Términos y Condiciones</a>
        </p>
    </div>
</div>
@endsection