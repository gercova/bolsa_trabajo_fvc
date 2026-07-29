@extends('layouts.app')
@section('title', 'Registrar TUPA - Panel Administrativo')
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
                        Registrar Nuevo TUPA
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.tupa.index') }}" class="hover:text-purple-600">TUPA</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Crear Registro</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="tupaForm()">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.tupa.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <form method="POST" action="{{ route('admin.tupa.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Título --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Título del Documento TUPA <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Ej: Texto Único de Procedimientos Administrativos - TUPA 2026" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Descripción / Resumen Informativo <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="4" placeholder="Ingrese una descripción general del contenido, resolución de aprobación o marco legal del TUPA..." required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Archivo PDF --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Archivo Documento PDF del TUPA <span class="text-red-500">*</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-200 hover:border-purple-500 rounded-2xl p-6 text-center bg-gray-50/50 transition-colors relative cursor-pointer group" @click="$refs.fileInput.click()">
                                    <input type="file" ref="fileInput" name="file_path" accept="application/pdf" class="hidden" @change="handleFileChange($event)" required>
                                    
                                    <template x-if="!fileName">
                                        <div class="space-y-3">
                                            <div class="w-12 h-12 mx-auto bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i class="bi bi-cloud-arrow-up text-2xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">Haz clic o arrastra el archivo PDF aquí</p>
                                                <p class="text-xs text-gray-500 mt-1">Formato admitido: PDF (Máx. 20MB)</p>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="fileName">
                                        <div class="flex items-center justify-between bg-purple-50 p-4 rounded-xl text-left border border-purple-100">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2.5 bg-purple-600 text-white rounded-xl">
                                                    <i class="bi bi-file-earmark-pdf text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900 truncate max-w-md" x-text="fileName"></p>
                                                    <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                                </div>
                                            </div>
                                            <span class="text-xs font-semibold text-purple-700 bg-purple-200 px-3 py-1 rounded-full">Listo para subir</span>
                                        </div>
                                    </template>
                                </div>
                                @error('file_path')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha Inicio Vigencia --}}
                            <div class="space-y-1.5">
                                <label for="effective_start_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Fecha Inicio de Vigencia <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="effective_start_date" name="effective_start_date" value="{{ old('effective_start_date', date('Y-01-01')) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('effective_start_date') border-red-500 @enderror">
                                @error('effective_start_date')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha Fin Vigencia --}}
                            <div class="space-y-1.5">
                                <label for="effective_end_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Fecha Fin de Vigencia (Opcional)
                                </label>
                                <input type="date" id="effective_end_date" name="effective_end_date" value="{{ old('effective_end_date') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('effective_end_date') border-red-500 @enderror">
                                <p class="text-[11px] text-gray-400">Dejar en blanco si la vigencia es indefinida.</p>
                                @error('effective_end_date')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2 pt-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    <span class="ml-3 text-sm font-semibold text-gray-700">Publicar como Registro Activo</span>
                                </label>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.tupa.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all">
                                Guardar TUPA
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
    function tupaForm() {
        return {
            fileName: '',
            fileSize: '',

            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                }
            }
        }
    }
</script>
@endpush
@endsection
