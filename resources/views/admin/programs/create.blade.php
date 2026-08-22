@extends('layouts.app')
@section('title', 'Crear Programa de Estudio - Panel Administrativo')
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
                        Registrar Programa de Estudio
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.programs.index') }}" class="hover:text-purple-600">Programas</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Nuevo Programa</span>
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
                    x-data="studyProgramCreateForm('{{ old('icon', 'bi-mortarboard-fill') }}')">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-mortarboard-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Nuevo Programa de Estudio</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Ingresa los datos del programa, logo oficial, perfil del egresado y metadatos visuales.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nombre --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Nombre del Programa de Estudio <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-book absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Enfermería Técnica, Producción Agropecuaria" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- FILE INPUT CON PREVISUALIZACIÓN DE MINIATURA / LOGO --}}
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
                                                    Haz clic o arrastra la imagen aquí
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
                                                    <button type="button" @click="removeLogo()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors" title="Quitar imagen">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-600 truncate max-w-[200px] mx-auto font-medium" x-text="fileName"></div>
                                                <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">
                                                    Previsualización
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="!logoPreview">
                                            <div class="text-center space-y-2 text-gray-400">
                                                <i class="bi bi-image text-3xl opacity-50"></i>
                                                <p class="text-xs font-medium">Sin logo seleccionado</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                @error('logo_path')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- FILE INPUT CON PREVISUALIZACIÓN DE ITINERARIO FORMATIVO (PDF) --}}
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Documento del Itinerario Formativo (Formato: PDF)
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                    {{-- Drag and Drop Upload Area --}}
                                    <div class="md:col-span-2 relative border-2 border-dashed border-gray-200 hover:border-indigo-400 bg-gray-50/50 hover:bg-indigo-50/20 rounded-2xl p-6 text-center transition-all duration-200 group">
                                        <input type="file" id="training_itinerary_path" name="training_itinerary_path" accept="application/pdf,.pdf" 
                                            @change="handlePdfChange($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="space-y-2 pointer-events-none">
                                            <div class="w-12 h-12 mx-auto rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">
                                                    Haz clic o arrastra el documento PDF aquí
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Documento del plan de estudios / itinerario en formato PDF (Máx. 20MB)</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Live Document Status Box --}}
                                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-gray-200 min-h-[150px]">
                                        <template x-if="pdfFileName">
                                            <div class="text-center space-y-3 relative w-full">
                                                <div class="relative inline-block group">
                                                    <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 border border-red-100 flex items-center justify-center text-3xl shadow-sm mx-auto">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    </div>
                                                    <button type="button" @click="removePdf()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors" title="Quitar documento">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-700 truncate max-w-[200px] mx-auto font-bold" x-text="pdfFileName"></div>
                                                <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">
                                                    PDF Seleccionado
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="!pdfFileName">
                                            <div class="text-center space-y-2 text-gray-400">
                                                <i class="bi bi-file-earmark-arrow-up text-3xl opacity-50"></i>
                                                <p class="text-xs font-medium">Sin itinerario PDF</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                @error('training_itinerary_path')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Perfil del Egresado / Descripción <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="4" placeholder="Ingrese el perfil profesional y descripción del programa de estudio..." required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Detalles / Duración y Título --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="details" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Detalles (Duración, Períodos y Título) <span class="text-red-500">*</span>
                                </label>
                                <textarea id="details" name="details" rows="3" placeholder="Ej: Duración: 3 años (06 períodos lectivos)&#10;Título: Profesional Técnico en ..." required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('details') border-red-500 @enderror">{{ old('details') }}</textarea>
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
                                    <input type="text" id="icon" name="icon" x-model="selectedIcon" placeholder="Ej: bi-heart-pulse-fill" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            {{-- Color de Acento --}}
                            <div class="space-y-1.5">
                                <label for="accent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Color de Acento
                                </label>
                                <select id="accent" name="accent" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="blue" {{ old('accent') == 'blue' ? 'selected' : '' }}>Azul (Blue)</option>
                                    <option value="emerald" {{ old('accent') == 'emerald' ? 'selected' : '' }}>Esmeralda (Emerald)</option>
                                    <option value="rose" {{ old('accent') == 'rose' ? 'selected' : '' }}>Rosa (Rose)</option>
                                    <option value="sky" {{ old('accent') == 'sky' ? 'selected' : '' }}>Cielo (Sky)</option>
                                    <option value="teal" {{ old('accent') == 'teal' ? 'selected' : '' }}>Verde Azulado (Teal)</option>
                                    <option value="indigo" {{ old('accent') == 'indigo' ? 'selected' : '' }}>Índigo (Indigo)</option>
                                </select>
                            </div>

                            {{-- Tag Informativo --}}
                            <div class="space-y-1.5">
                                <label for="tag" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Etiqueta / Tag
                                </label>
                                <input type="text" id="tag" name="tag" value="{{ old('tag') }}" placeholder="Ej: Ciencias de la Salud, Producción & Campo" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Orden Secuencial --}}
                            <div class="space-y-1.5">
                                <label for="order" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Orden de Presentación <span class="text-gray-400 font-normal">(Secuencial)</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-sort-numeric-down absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="number" id="order" name="order" value="{{ old('order', $nextOrder ?? 1) }}" min="0" max="255" placeholder="Ej: 1, 2, 3..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('order') border-red-500 @enderror">
                                </div>
                                @error('order')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Badge visual CSS --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="bg_badge" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Estilo CSS de Badge
                                </label>
                                <input type="text" id="bg_badge" name="bg_badge" value="{{ old('bg_badge') }}" placeholder="Ej: bg-rose-50 text-rose-800 border-rose-100" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2 pt-2">
                                <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="sr-only peer">
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
                                <span>Guardar Programa</span>
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
    function studyProgramCreateForm(defaultIcon) {
        return {
            selectedIcon: defaultIcon || 'bi-mortarboard-fill',
            logoPreview: null,
            fileName: '',
            pdfFileName: '',

            handleLogoChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            removeLogo() {
                this.logoPreview = null;
                this.fileName = '';
                const input = document.getElementById('logo_path');
                if (input) input.value = '';
            },

            handlePdfChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.pdfFileName = file.name;
                }
            },

            removePdf() {
                this.pdfFileName = '';
                const input = document.getElementById('training_itinerary_path');
                if (input) input.value = '';
            }
        }
    }
</script>
@endpush
@endsection
