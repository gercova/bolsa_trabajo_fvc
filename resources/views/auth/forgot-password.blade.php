@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
        <div>
            <div class="flex justify-center">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-key text-4xl text-purple-600"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Recuperar Contraseña
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                ¿Olvidaste tu contraseña? No hay problema. Solo dinos tu dirección de correo electrónico y te enviaremos un enlace para restablecerla.
            </p>
        </div>
        
        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Correo Electrónico
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" required
                           class="appearance-none rounded-lg relative block w-full pl-10 pr-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                           placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="bi bi-envelope-paper"></i>
                    </span>
                    Enviar enlace de recuperación
                </button>
            </div>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-purple-600 hover:text-purple-500">
                    <i class="bi bi-arrow-left mr-1"></i>
                    Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>
@endsection