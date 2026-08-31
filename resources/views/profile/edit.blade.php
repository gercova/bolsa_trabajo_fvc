@extends('layouts.app')
@section('title', 'Perfil de Usuario - ' . ($user->names ?? 'Usuario') . ' | Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-slate-50 font-sans text-slate-900 min-h-[calc(100vh-64px)]" x-data="profileApp()">
    @include('admin.components.aside')

    {{-- Contenido Principal --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50 relative">

        {{-- Encabezado Sticky --}}
        <header class="bg-white/95 border-b border-slate-200 sticky top-[64px] lg:top-0 z-[30] shadow-xs backdrop-blur-md">
            <div class="px-4 sm:px-6 py-3.5 sm:py-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()" class="text-slate-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-xl transition-colors lg:hidden" aria-label="Abrir menú">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-display">
                                Perfil de Usuario
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                <i class="bi bi-circle-fill text-[6px] {{ $user->is_active ? 'text-emerald-500 animate-pulse' : 'text-red-500' }}"></i>
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 hidden sm:block mt-0.5">
                            Gestión de datos personales, credenciales y preferencias de <strong class="text-slate-700 font-semibold">{{ $user->names }}</strong>
                        </p>
                    </div>
                </div>

                {{-- Breadcrumbs & Quick info --}}
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-500 gap-2">
                    <a href="{{ route('admin.dashboard.index') }}" class="hover:text-purple-600 transition flex items-center gap-1">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <i class="bi bi-chevron-right text-[10px] text-slate-400"></i>
                    <a href="{{ route('admin.partners.index') }}" class="hover:text-purple-600 transition">Usuarios</a>
                    <i class="bi bi-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-purple-700 font-bold bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-100">
                        {{ Str::limit($user->names, 22) }}
                    </span>
                </div>
            </div>
        </header>

        {{-- Main Body --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden space-y-6 max-w-7xl mx-auto w-full">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 sm:p-5 flex items-start justify-between gap-3 shadow-xs animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-emerald-900">¡Operación Exitosa!</h4>
                            <p class="text-xs sm:text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.animate-fade-in').remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
            @endif

            @if(session('status') === 'password-updated')
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 sm:p-5 flex items-start justify-between gap-3 shadow-xs animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-emerald-900">Seguridad Actualizada</h4>
                            <p class="text-xs sm:text-sm text-emerald-700 mt-0.5">La contraseña de acceso se ha actualizado satisfactoriamente.</p>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.animate-fade-in').remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 sm:p-5 flex items-start gap-3 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="space-y-1 text-xs sm:text-sm">
                        <h4 class="font-bold text-red-900">Por favor corrige los siguientes errores:</h4>
                        <ul class="list-disc list-inside space-y-0.5 text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- User Hero Card Summary --}}
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-indigo-900/40 relative overflow-hidden">
                <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    
                    {{-- User Avatar Thumbnail --}}
                    <div class="relative group shrink-0">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl overflow-hidden bg-gradient-to-tr from-purple-600 to-indigo-500 p-1 shadow-2xl">
                            @if($user->photo_profile)
                                <img src="{{ Storage::url($user->photo_profile) }}" alt="{{ $user->names }}" class="w-full h-full object-cover rounded-[22px]">
                            @else
                                <div class="w-full h-full bg-slate-800 rounded-[22px] flex items-center justify-center text-3xl font-black text-purple-300">
                                    {{ strtoupper(substr($user->names, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-white text-xs shadow-md">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                    </div>

                    {{-- User Details Info --}}
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wider uppercase bg-purple-500/20 text-purple-300 border border-purple-400/30">
                                <i class="bi bi-shield-shaded mr-1"></i> Rol: {{ $user->role ?: 'Usuario' }}
                            </span>
                            @if($user->job_position)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/10">
                                    <i class="bi bi-briefcase-fill mr-1 text-indigo-400"></i> {{ $user->job_position }}
                                </span>
                            @endif
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight font-display">
                            {{ $user->names }}
                        </h2>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-5 gap-y-1.5 text-xs sm:text-sm text-slate-300 pt-1">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-person-vcard text-purple-400"></i>
                                DNI: <strong class="text-white font-mono">{{ $user->dni ?: '—' }}</strong>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-envelope text-indigo-400"></i>
                                {{ $user->email }}
                            </span>
                            @if($user->phone)
                                <span class="flex items-center gap-1.5">
                                    <i class="bi bi-telephone text-emerald-400"></i>
                                    {{ $user->phone }}
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- Main Form Card with Tabs --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

                {{-- Tab Navigation Bar --}}
                <div class="bg-slate-50 border-b border-slate-200 px-4 sm:px-6 pt-3 flex flex-wrap items-center gap-2">
                    <button 
                        type="button" 
                        @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'border-purple-600 text-purple-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-5 py-3 rounded-t-2xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="bi bi-person-gear text-base"></i>
                        <span>1. Información del Perfil</span>
                    </button>

                    <button 
                        type="button" 
                        @click="activeTab = 'password'" 
                        :class="activeTab === 'password' ? 'border-purple-600 text-purple-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-5 py-3 rounded-t-2xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="bi bi-shield-lock text-base"></i>
                        <span>2. Seguridad &amp; Contraseña</span>
                    </button>

                    <button 
                        type="button" 
                        @click="activeTab = 'account'" 
                        :class="activeTab === 'account' ? 'border-purple-600 text-purple-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-5 py-3 rounded-t-2xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="bi bi-info-circle text-base"></i>
                        <span>3. Detalles de Cuenta</span>
                    </button>
                </div>

                {{-- TAB 1: Información del Perfil (Profile Update Form) --}}
                <div x-show="activeTab === 'profile'" class="p-6 sm:p-8">
                    <form action="{{ route('admin.profile.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                            {{-- Left Column: Foto de Perfil & Documentos --}}
                            <div class="lg:col-span-1 space-y-6">
                                
                                {{-- Avatar Box --}}
                                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-4">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-camera-fill text-purple-600"></i>
                                        Fotografía de Perfil
                                    </h3>

                                    {{-- Image Preview Area --}}
                                    <div class="relative group w-full aspect-square max-w-[220px] mx-auto rounded-2xl overflow-hidden bg-slate-200 border-2 border-dashed border-purple-300 flex items-center justify-center shadow-inner">
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" alt="Vista previa" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!photoPreview">
                                            @if($user->photo_profile)
                                                <img src="{{ Storage::url($user->photo_profile) }}" alt="{{ $user->names }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="text-center p-4">
                                                    <i class="bi bi-person-bounding-box text-5xl text-slate-400"></i>
                                                    <p class="text-[11px] text-slate-500 mt-2">Sin fotografía asignada</p>
                                                </div>
                                            @endif
                                        </template>
                                    </div>

                                    {{-- File Input --}}
                                    <div>
                                        <label for="photo_profile" class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Cambiar Imagen de Perfil
                                        </label>
                                        <input 
                                            type="file" 
                                            name="photo_profile" 
                                            id="photo_profile" 
                                            accept="image/jpeg,image/png,image/jpg,image/webp" 
                                            @change="handlePhotoChange($event)"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition cursor-pointer border border-slate-200 rounded-xl bg-white p-1">
                                        <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                                            <i class="bi bi-info-circle"></i> JPG, PNG, WEBP. Máx. 2MB
                                        </p>
                                        @error('photo_profile')
                                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Curriculum Vitae Box --}}
                                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500"></i>
                                        Curriculum Vitae (CV)
                                    </h3>

                                    @if($user->cv_file)
                                        <div class="p-3 bg-white rounded-xl border border-slate-200 flex items-center justify-between gap-2 shadow-xs">
                                            <div class="flex items-center gap-2 overflow-hidden">
                                                <i class="bi bi-filetype-pdf text-2xl text-red-500 shrink-0"></i>
                                                <div class="truncate">
                                                    <span class="text-xs font-bold text-slate-800 block truncate">CV_Registrado.pdf</span>
                                                    <span class="text-[10px] text-emerald-600 font-semibold">Documento activo</span>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($user->cv_file) }}" target="_blank" class="px-2.5 py-1.5 bg-slate-100 hover:bg-purple-50 text-slate-700 hover:text-purple-700 rounded-lg text-xs font-bold transition shrink-0">
                                                <i class="bi bi-eye"></i> Ver
                                            </a>
                                        </div>
                                    @endif

                                    <div>
                                        <label for="cv_file" class="block text-xs font-bold text-slate-700 mb-1">
                                            {{ $user->cv_file ? 'Actualizar Archivo PDF' : 'Subir Documento CV' }}
                                        </label>
                                        <input 
                                            type="file" 
                                            name="cv_file" 
                                            id="cv_file" 
                                            accept="application/pdf"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer border border-slate-200 rounded-xl bg-white p-1">
                                        <p class="text-[10px] text-slate-400 mt-1">Solo documentos en formato PDF (Máx. 5MB).</p>
                                        @error('cv_file')
                                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            {{-- Right Column: User Data Grid --}}
                            <div class="lg:col-span-2 space-y-6">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                                    {{-- Nombres Completos --}}
                                    <div class="sm:col-span-2">
                                        <label for="names" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Nombres y Apellidos Completos <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-person-fill text-base text-purple-600"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="names" 
                                                id="names" 
                                                value="{{ old('names', $user->names) }}" 
                                                required 
                                                placeholder="Ej: Juan Carlos Pérez Gómez"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition @error('names') border-red-500 ring-2 ring-red-200 @enderror">
                                        </div>
                                        @error('names')
                                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- DNI / Documento --}}
                                    <div>
                                        <label for="dni" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            DNI / Documento de Identidad <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-person-vcard-fill text-base text-purple-600"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="dni" 
                                                id="dni" 
                                                value="{{ old('dni', $user->dni) }}" 
                                                required 
                                                maxlength="15"
                                                placeholder="Ej: 72839401"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition @error('dni') border-red-500 ring-2 ring-red-200 @enderror">
                                        </div>
                                        @error('dni')
                                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Correo Electrónico --}}
                                    <div>
                                        <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Correo Electrónico <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-envelope-fill text-base text-purple-600"></i>
                                            </div>
                                            <input 
                                                type="email" 
                                                name="email" 
                                                id="email" 
                                                value="{{ old('email', $user->email) }}" 
                                                required 
                                                placeholder="correo@institucional.edu.pe"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition @error('email') border-red-500 ring-2 ring-red-200 @enderror">
                                        </div>
                                        @error('email')
                                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Teléfono / Celular --}}
                                    <div>
                                        <label for="phone" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Teléfono / Celular
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-telephone-fill text-base text-indigo-600"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="phone" 
                                                id="phone" 
                                                value="{{ old('phone', $user->phone) }}" 
                                                placeholder="Ej: 987654321"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition @error('phone') border-red-500 @enderror">
                                        </div>
                                        @error('phone')
                                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Cargo / Ocupación --}}
                                    <div>
                                        <label for="job_position" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Cargo u Ocupación
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-briefcase-fill text-base text-indigo-600"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="job_position" 
                                                id="job_position" 
                                                value="{{ old('job_position', $user->job_position) }}" 
                                                placeholder="Ej: Docente / Administrador / Estudiante"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition">
                                        </div>
                                    </div>

                                    {{-- Fecha de Nacimiento --}}
                                    <div>
                                        <label for="birthdate" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Fecha de Nacimiento
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-calendar-date-fill text-base text-purple-600"></i>
                                            </div>
                                            <input 
                                                type="date" 
                                                name="birthdate" 
                                                id="birthdate" 
                                                value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}" 
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition">
                                        </div>
                                    </div>

                                    {{-- Género / Sexo --}}
                                    <div>
                                        <label for="sex" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Sexo / Género
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-gender-ambiguous text-base text-purple-600"></i>
                                            </div>
                                            <select 
                                                name="sex" 
                                                id="sex" 
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition">
                                                <option value="">Seleccione opción</option>
                                                <option value="M" {{ old('sex', $user->sex) === 'M' ? 'selected' : '' }}>Masculino</option>
                                                <option value="F" {{ old('sex', $user->sex) === 'F' ? 'selected' : '' }}>Femenino</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Lengua Materna --}}
                                    <div>
                                        <label for="mother_tongue" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Lengua Materna
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-translate text-base text-indigo-600"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="mother_tongue" 
                                                id="mother_tongue" 
                                                value="{{ old('mother_tongue', $user->mother_tongue ?? 'Español') }}" 
                                                placeholder="Ej: Español, Quechua"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition">
                                        </div>
                                    </div>

                                    {{-- Dirección --}}
                                    <div class="sm:col-span-2">
                                        <label for="address" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Dirección Domiciliaria
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="bi bi-geo-alt-fill text-base text-purple-600"></i>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="address" 
                                                id="address" 
                                                value="{{ old('address', $user->address) }}" 
                                                placeholder="Ej: Jr. San Martín N° 240, Uchiza"
                                                class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition">
                                        </div>
                                    </div>

                                </div>

                                {{-- Submit Actions Bar --}}
                                <div class="pt-6 border-t border-slate-200 flex flex-wrap items-center justify-end gap-3">
                                    <a href="{{ route('admin.partners.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition">
                                        Cancelar
                                    </a>
                                    <button 
                                        type="submit" 
                                        class="px-7 py-3 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-lg shadow-purple-600/30 hover:shadow-purple-600/50 transition-all flex items-center gap-2">
                                        <i class="bi bi-floppy-fill"></i>
                                        <span>Guardar Cambios del Perfil</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

                {{-- TAB 2: Seguridad y Actualización de Contraseña --}}
                <div x-show="activeTab === 'password'" style="display: none;" class="p-6 sm:p-8 max-w-2xl">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 font-display">Actualización de Contraseña</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Asegúrese de que su cuenta utilice una contraseña de alta seguridad con al menos 8 caracteres.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('PUT')

                            {{-- Current Password --}}
                            <div>
                                <label for="current_password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Contraseña Actual <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-shield-lock text-purple-600"></i>
                                    </div>
                                    <input 
                                        :type="showCurrentPass ? 'text' : 'password'" 
                                        name="current_password" 
                                        id="current_password" 
                                        required 
                                        class="w-full pl-10 pr-10 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition @error('current_password', 'updatePassword') border-red-500 @enderror">
                                    <button 
                                        type="button" 
                                        @click="showCurrentPass = !showCurrentPass" 
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                        <i class="bi" :class="showCurrentPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                    </button>
                                </div>
                                @error('current_password', 'updatePassword')
                                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div>
                                <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Nueva Contraseña <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-key-fill text-purple-600"></i>
                                    </div>
                                    <input 
                                        :type="showNewPass ? 'text' : 'password'" 
                                        name="password" 
                                        id="password" 
                                        required 
                                        class="w-full pl-10 pr-10 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition @error('password', 'updatePassword') border-red-500 @enderror">
                                    <button 
                                        type="button" 
                                        @click="showNewPass = !showNewPass" 
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                        <i class="bi" :class="showNewPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                    </button>
                                </div>
                                @error('password', 'updatePassword')
                                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Confirmar Nueva Contraseña <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-check2-all text-purple-600"></i>
                                    </div>
                                    <input 
                                        :type="showConfirmPass ? 'text' : 'password'" 
                                        name="password_confirmation" 
                                        id="password_confirmation" 
                                        required 
                                        class="w-full pl-10 pr-10 py-2.5 sm:py-3 bg-slate-50/50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition">
                                    <button 
                                        type="button" 
                                        @click="showConfirmPass = !showConfirmPass" 
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                        <i class="bi" :class="showConfirmPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                                <button 
                                    type="submit" 
                                    class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Actualizar Contraseña</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TAB 3: Detalles de Cuenta & Auditoría --}}
                <div x-show="activeTab === 'account'" style="display: none;" class="p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 font-display">Detalles del Sistema &amp; Auditoría</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Información técnica y registros de actividad de la cuenta.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ID de Usuario</span>
                            <p class="text-base font-black text-slate-900 font-mono mt-1">#{{ $user->id }}</p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Fecha de Registro</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : '—' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Última Actualización</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ $user->updated_at ? $user->updated_at->diffForHumans() : '—' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Verificación de Email</span>
                            <p class="text-sm font-bold {{ $user->email_verified_at ? 'text-emerald-700' : 'text-amber-600' }} mt-1 flex items-center gap-1.5">
                                <i class="bi {{ $user->email_verified_at ? 'bi-check-circle-fill' : 'bi-clock-history' }}"></i>
                                {{ $user->email_verified_at ? 'Verificado el ' . $user->email_verified_at->format('d/m/Y') : 'Pendiente de verificación' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rol Principal</span>
                            <p class="text-sm font-extrabold text-purple-700 mt-1">
                                {{ $user->role ?: 'Solicitante' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Estado de Acceso</span>
                            <p class="text-sm font-bold {{ $user->is_active ? 'text-emerald-700' : 'text-red-600' }} mt-1">
                                {{ $user->is_active ? 'Habilitado para inicio de sesión' : 'Bloqueado / Inactivo' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

@push('scripts')
<script>
    function profileApp() {
        return {
            sidebarOpen: window.innerWidth >= 1024,
            activeTab: 'profile',
            photoPreview: null,
            showCurrentPass: false,
            showNewPass: false,
            showConfirmPass: false,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            },
            handlePhotoChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.photoPreview = URL.createObjectURL(file);
                }
            },
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        };
    }
</script>
@endpush
@endsection