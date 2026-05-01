@extends('layouts.app')
@section('title', 'Crear Usuario - Panel Administrativo')
@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Encabezado --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="bi bi-person-plus-fill text-purple-600 mr-3"></i>
                    Crear Nuevo Usuario
                </h1>
                <p class="text-gray-600 mt-2 ml-11">Completa el formulario para agregar un nuevo usuario al sistema</p>
            </div>
            <a href="{{ route('admin.usuarios') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center space-x-2">
                <i class="bi bi-arrow-left"></i>
                <span>Volver al listado</span>
            </a>
        </div>
    </div>

    {{-- Formulario --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <form action="{{ route('admin.usuarios.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
            @csrf
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
                                <i class="bi bi-person-circle text-6xl text-gray-400"></i>
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
                                <input type="text" name="dni" id="dni" value="{{ old('dni') }}" placeholder="Ej: 12345678" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('dni') border-red-500 @enderror">
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
                                <input type="text" name="names" id="names" value="{{ old('names') }}" placeholder="Ej: Juan Pérez González" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('names') border-red-500 @enderror">
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
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Ej: usuario@correo.com" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('email') border-red-500 @enderror">
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
                                <input type="text" name="job_position" id="job_position" value="{{ old('job_position') }}" placeholder="Ej: Desarrollador Senior" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('job_position') border-red-500 @enderror">
                            </div>
                            @error('job_position')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        {{-- <div class="md:col-span-2">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                                <i class="bi bi-lock-fill text-purple-600 mr-1"></i>
                                Contraseña
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-key text-gray-400"></i>
                                    </div>
                                    <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres" class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('password') border-red-500 @enderror">
                                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <i class="bi bi-eye-fill" id="password-icon"></i>
                                    </button>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="bi bi-key-fill text-gray-400"></i>
                                    </div>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmar contraseña" class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <i class="bi bi-eye-fill" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}

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
                    <a href="{{ route('admin.usuarios') }}" class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
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

@push('scripts')
<script>
    // Preview de imagen
    document.getElementById('photo_profile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
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
        const icon = document.getElementById(fieldId + '-icon');
        
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
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
        }
    });
</script>
@endpush

@push('styles')
<style>
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
    
    input:focus, select:focus, textarea:focus {
        outline: none;
    }
    
    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>
@endpush
@endsection