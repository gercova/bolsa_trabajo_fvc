@extends('layouts.app')
@section('title', 'Editar Programa de Estudio - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Editar Programa de Estudio
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.programs.index') }}" class="hover:text-purple-600">Programas</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Programa</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8" 
                    x-data="studyProgramEditForm('{{ old('icon', $program->icon ?? 'bi-mortarboard-fill') }}', '{{ $program->logo_path ? asset('storage/' . $program->logo_path) : '' }}', '{{ $program->logo_path ? basename($program->logo_path) : '' }}')">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Modificar: {{ $program->name }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Actualiza el perfil profesional, logo, detalles o metadatos de presentación de este programa de estudio.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nombre --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Nombre del Programa de Estudio <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-book absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="name" name="name" value="{{ old('name', $program->name) }}" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- FILE INPUT CON PREVISUALIZACIÓN Y LOGO ACTUAL --}}
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Logo del Programa (Formatos: PNG, JPG, JPEG, GIF, WEBP, SVG)
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                    {{-- Drag and Drop Upload Area --}}
                                    <div class="md:col-span-2 relative border-2 border-dashed border-gray-200 hover:border-purple-400 bg-gray-50/50 hover:bg-purple-50/20 rounded-2xl p-6 text-center transition-all duration-200 group">
                                        <input type="file" id="logo_path" name="logo_path" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml" 
                                            @change="handleLogoChange($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="space-y-2 pointer-events-none">
                                            <div class="w-12 h-12 mx-auto rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">
                                                    Haz clic o arrastra una nueva imagen para cambiar el logo
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP, GIF o SVG (Máx. 4MB)</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Live Thumbnail Display Box --}}
                                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-gray-200 min-h-[150px]">
                                        <template x-if="logoPreview">
                                            <div class="text-center space-y-3 relative w-full">
                                                <div class="relative inline-block group">
                                                    <img :src="logoPreview" alt="Previsualización Logo" class="w-24 h-24 object-contain rounded-xl bg-white p-2 border border-gray-200 shadow-sm mx-auto">
                                                    <button type="button" @click="resetLogo()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors" title="Restablecer / Deshacer cambio">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-600 truncate max-w-[200px] mx-auto font-medium" x-text="fileName"></div>
                                                <span class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full"
                                                      :class="isNewFile ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800'">
                                                    <span x-text="isNewFile ? 'Nuevo Logo Seleccionado' : 'Logo Actual Registrado'"></span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="!logoPreview">
                                            <div class="text-center space-y-2 text-gray-400">
                                                <i class="bi bi-image text-3xl opacity-50"></i>
                                                <p class="text-xs font-medium">Sin logo registrado</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                @error('logo_path')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción / Perfil --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Perfil del Egresado / Descripción <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description', $program->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Detalles / Duración y Título --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="details" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Detalles (Duración, Períodos y Título) <span class="text-red-500">*</span>
                                </label>
                                <textarea id="details" name="details" rows="3" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('details') border-red-500 @enderror">{{ old('details', $program->details) }}</textarea>
                                @error('details')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ícono --}}
                            <div class="space-y-1.5">
                                <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Ícono Principal (Bootstrap Icons)
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                        <i :class="'bi ' + selectedIcon"></i>
                                    </div>
                                    <input type="text" id="icon" name="icon" x-model="selectedIcon" value="{{ old('icon', $program->icon) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            {{-- Color de Acento --}}
                            <div class="space-y-1.5">
                                <label for="accent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Color de Acento
                                </label>
                                <select id="accent" name="accent" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="blue" {{ old('accent', $program->accent) == 'blue' ? 'selected' : '' }}>Azul (Blue)</option>
                                    <option value="emerald" {{ old('accent', $program->accent) == 'emerald' ? 'selected' : '' }}>Esmeralda (Emerald)</option>
                                    <option value="rose" {{ old('accent', $program->accent) == 'rose' ? 'selected' : '' }}>Rosa (Rose)</option>
                                    <option value="sky" {{ old('accent', $program->accent) == 'sky' ? 'selected' : '' }}>Cielo (Sky)</option>
                                    <option value="teal" {{ old('accent', $program->accent) == 'teal' ? 'selected' : '' }}>Verde Azulado (Teal)</option>
                                    <option value="indigo" {{ old('accent', $program->accent) == 'indigo' ? 'selected' : '' }}>Índigo (Indigo)</option>
                                </select>
                            </div>

                            {{-- Tag Informativo --}}
                            <div class="space-y-1.5">
                                <label for="tag" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Etiqueta / Tag
                                </label>
                                <input type="text" id="tag" name="tag" value="{{ old('tag', $program->tag) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Badge visual CSS --}}
                            <div class="space-y-1.5">
                                <label for="bg_badge" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Estilo CSS de Badge
                                </label>
                                <input type="text" id="bg_badge" name="bg_badge" value="{{ old('bg_badge', $program->bg_badge) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2 pt-2">
                                <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $program->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Programa Activo</span>
                                        <span class="text-xs text-gray-500 block">Los programas activos se muestran públicamente en el portal institucional.</span>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Botones de Acción --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.programs.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Actualizar Programa</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    function studyProgramEditForm(defaultIcon, existingLogoUrl, originalFileName) {
        return {
            selectedIcon: defaultIcon || 'bi-mortarboard-fill',
            logoPreview: existingLogoUrl || null,
            originalLogoUrl: existingLogoUrl || null,
            originalName: originalFileName || '',
            fileName: originalFileName || '',
            isNewFile: false,

            handleLogoChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.isNewFile = true;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            resetLogo() {
                this.logoPreview = this.originalLogoUrl;
                this.fileName = this.originalName;
                this.isNewFile = false;
                const input = document.getElementById('logo_path');
                if (input) input.value = '';
            }
        }
    }
</script>
@endpush
@endsection
