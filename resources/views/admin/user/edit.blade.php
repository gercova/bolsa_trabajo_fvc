@extends('layouts.app')
@section('title', 'Crear Usuario - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="enterpriseApp()">
    @include('admin.components.aside')
    {{-- Encabezado --}}
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Actualizar datos de usuario {{ $user->names }}
                    </h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.partners.index') }}" class="hover:text-purple-600 transition">Usuarios</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Actualizar datos del usuario</span>
                </div>
            </div>
        </header>
        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                {{-- Grid de 2 columnas --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Columna Izquierda: Foto de Perfil y Estado --}}
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-300">
                            <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="bi bi-camera-fill text-purple-600 mr-2"></i>
                                Foto de Perfil
                            </h3>
                            {{-- Preview de imagen --}}
                            <div class="mb-4">
                                <div id="imagePreview" class="w-full aspect-square bg-white rounded-xl border-2 border-gray-200 flex items-center justify-center overflow-hidden">
                                    <img src="{{ Storage::url($user->photo_profile) }}" alt="{{ $user->names }}">
                                    {{-- <i class="bi bi-person-circle text-6xl text-gray-400"></i> --}}
                                </div>
                            </div>
                            {{-- Input de foto --}}
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-cloud-upload mr-1"></i>
                                    Seleccionar imagen
                                </label>
                                <input type="file" name="photo_profile" id="photo_profile" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition cursor-pointer">
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-info-circle"></i>
                                    Formatos: JPG, PNG, GIF, WEBP. Máx: 2MB
                                </p>
                                @error('photo_profile')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Estado del usuario --}}
                            <div class="border-t border-gray-200 pt-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-600 mr-1"></i>
                                        Usuario activo
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2 ml-8">Los usuarios inactivos no podrán iniciar sesión</p>
                            </div>
                        </div>
                    </div>
                    {{-- Columna Derecha: Datos del Usuario --}}
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- DNI --}}
                            <div>
                                <label for="dni" class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-person-vcard text-purple-600 mr-1"></i>
                                    DNI / Documento de Identidad
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-credit-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="dni" id="dni" value="{{ $user->dni ?? old('dni') }}" placeholder="Ej: 12345678" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('dni') border-red-500 @enderror">
                                </div>
                                @error('dni')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Nombres Completos --}}
                            <div>
                                <label for="names" class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-person-fill text-purple-600 mr-1"></i>
                                    Nombres Completos
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-person text-gray-400"></i>
                                    </div>
                                    <input type="text" name="names" id="names" value="{{ $user->names ?? old('names') }}" placeholder="Ej: Juan Pérez González" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('names') border-red-500 @enderror">
                                </div>
                                @error('names')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Email --}}
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-envelope-fill text-purple-600 mr-1"></i>
                                    Correo Electrónico
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ $user->email ?? old('email') }}" placeholder="Ej: usuario@correo.com" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('email') border-red-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Rol --}}
                            <div>
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-shield-fill text-purple-600 mr-1"></i>
                                    Rol de Usuario
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-tag text-gray-400"></i>
                                    </div>
                                    <select name="role" id="role" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition appearance-none @error('role') border-red-500 @enderror">
                                        <option value="">Seleccionar rol...</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Cargo Laboral --}}
                            <div>
                                <label for="job_position" class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-briefcase-fill text-purple-600 mr-1"></i>
                                    Cargo / Puesto
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" name="job_position" id="job_position" value="{{ $user->job_position ?? old('job_position') }}" placeholder="Ej: Desarrollador Senior" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('job_position') border-red-500 @enderror">
                                </div>
                                @error('job_position')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- CV File --}}
                            <div class="md:col-span-2">
                                <label for="cv_file" class="block mb-2 text-sm font-medium text-gray-700">
                                    <i class="bi bi-file-earmark-pdf-fill text-purple-600 mr-1"></i>
                                    Currículum Vitae (CV)
                                </label>
                                <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition cursor-pointer">
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-info-circle"></i>
                                    Formatos: PDF, DOC, DOCX. Máx: 5MB
                                </p>
                                @error('cv_file')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Botones de acción --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                            <i class="bi bi-x-circle mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-md hover:shadow-lg">
                            <i class="bi bi-save-fill mr-2"></i>
                            Guardar Usuario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Inicializador del layout base del dashboard
            Alpine.data('enterpriseApp', () => ({
                sidebarOpen: window.innerWidth >= 1024,
                toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; },
                init() {
                    window.addEventListener('resize', () => {
                        this.sidebarOpen = window.innerWidth >= 1024;
                    });
                }
            }));
        });
        // Preview de imagen
        document.getElementById('photo_profile').addEventListener('change', function(e) {
            const file      = e.target.files[0];
            const preview   = document.getElementById('imagePreview');
            
            if (file) {
                const reader    = new FileReader();
                reader.onload   = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<i class="bi bi-person-circle text-6xl text-gray-400"></i>';
            }
        });

        // Toggle visibilidad de contraseña
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon  = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        }

        // Validación del lado del cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const password  = document.getElementById('password').value;
            const confirm   = document.getElementById('password_confirmation').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .aspect-square { aspect-ratio: 1 / 1; }
        
        input:focus, select:focus, textarea:focus { outline: none; }
        
        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
@endpush
@endsection