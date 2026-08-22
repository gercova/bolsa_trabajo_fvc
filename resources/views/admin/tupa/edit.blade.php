@extends('layouts.app')
@section('title', 'Editar TUPA - Panel Administrativo')
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
                        Editar Registro TUPA
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.tupa.index') }}" class="hover:text-purple-600">TUPA</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Registro</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="tupaEditForm('{{ $tupa->url }}', '{{ addslashes($tupa->title) }}')">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.tupa.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $tupa->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $tupa->is_active ? 'Publicado Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
                    
                    {{-- Banner Informativo superior --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-earmark-pdf-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Actualización de Documentación TUPA</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Puedes actualizar la información del registro y cargar un nuevo documento PDF que reemplazará el archivo actual de forma segura.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.tupa.update', $tupa) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Título --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Título del Documento TUPA <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-card-heading absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="title" name="title" value="{{ old('title', $tupa->title) }}" placeholder="Ej: Texto Único de Procedimientos Administrativos - TUPA 2026" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('title') border-red-500 @enderror">
                                </div>
                                @error('title')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Descripción / Resumen Informativo <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="4" placeholder="Ingrese una descripción general del contenido, resolución de aprobación o marco legal del TUPA..." required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description', $tupa->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ===== SECCIÓN MEJORADA DE CARGA DE ARCHIVO PDF ===== --}}
                            <div class="md:col-span-2 space-y-4 pt-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                        Gestión del Archivo Documento PDF
                                    </label>
                                    <span class="text-xs text-gray-400 font-medium">Solo archivos .PDF (Máx. 20MB)</span>
                                </div>

                                {{-- Tarjeta de Archivo Actual (si existe) --}}
                                @if($tupa->url)
                                    <div class="p-4 bg-slate-900 text-white rounded-2xl shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-slate-800">
                                        <div class="flex items-center gap-3.5 min-w-0">
                                            <div class="w-12 h-12 rounded-xl bg-purple-500/20 border border-purple-400/30 text-purple-300 flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-file-earmark-pdf text-2xl"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-purple-500/30 text-purple-200 border border-purple-400/30">Archivo PDF Actual</span>
                                                </div>
                                                <p class="text-sm font-bold text-white truncate mt-1 max-w-sm sm:max-w-md">{{ basename($tupa->file_path) }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                            <button type="button" @click="openPdfModal(existingUrl, 'PDF Actual: ' + '{{ addslashes($tupa->title) }}')" class="px-3 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                                                <i class="bi bi-eye-fill"></i> Previsualizar
                                            </button>
                                            <a href="{{ $tupa->url }}" target="_blank" download class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                                                <i class="bi bi-download"></i> Descargar
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                {{-- Zona interactiva Drag and Drop para nuevo PDF --}}
                                <div class="relative">
                                    <div class="border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-300 relative cursor-pointer group"
                                         :class="{
                                             'border-purple-500 bg-purple-50/50 shadow-md ring-4 ring-purple-100': isDragging,
                                             'border-emerald-400 bg-emerald-50/30': fileName && !fileError,
                                             'border-red-400 bg-red-50/30': fileError,
                                             'border-gray-200 hover:border-purple-400 bg-gray-50/40 hover:bg-purple-50/20': !isDragging && !fileName
                                         }"
                                         @dragover.prevent="isDragging = true"
                                         @dragleave.prevent="isDragging = false"
                                         @drop.prevent="handleFileDrop($event)"
                                         @click="document.getElementById('tupaFileInput').click()">

                                        <input type="file" id="tupaFileInput" name="file_path" accept="application/pdf" class="hidden" @change="handleFileChange($event)">

                                        {{-- Estado por defecto / Sin nuevo archivo --}}
                                        <template x-if="!fileName">
                                            <div class="space-y-3 py-2">
                                                <div class="w-14 h-14 mx-auto bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                                    <i class="bi bi-cloud-arrow-up-fill text-3xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-extrabold text-gray-800">
                                                        <span class="text-purple-600 underline">Haz clic para seleccionar</span> o arrastra un nuevo archivo PDF aquí
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">Opcional. Si no seleccionas ninguno, se conservará el documento PDF actual.</p>
                                                </div>
                                                <div class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                                                    <i class="bi bi-filetype-pdf text-red-500"></i> Documento Formato PDF (Máx. 20MB)
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Estado cuando se ha seleccionado un nuevo archivo --}}
                                        <template x-if="fileName">
                                            <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-white rounded-xl border border-emerald-200 shadow-sm gap-4 text-left">
                                                <div class="flex items-center gap-3.5 min-w-0 w-full sm:w-auto">
                                                    <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                                                        <i class="bi bi-file-earmark-check-fill text-2xl"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">Nuevo PDF Seleccionado</span>
                                                        <p class="text-sm font-bold text-gray-900 truncate mt-0.5 max-w-xs sm:max-w-md" x-text="fileName"></p>
                                                        <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 w-full sm:w-auto justify-end" @click.stop>
                                                    <button type="button" @click="openPdfModal(newPdfPreviewUrl, 'Vista Previa Nuevo PDF: ' + fileName)" class="px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5">
                                                        <i class="bi bi-eye"></i> Previsualizar
                                                    </button>

                                                    <button type="button" @click="clearNewFile()" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-xs font-bold transition-colors" title="Cancelar selección">
                                                        <i class="bi bi-trash3 text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>

                                    </div>

                                    {{-- Mensaje de Error de Validación Frontend --}}
                                    <template x-if="fileError">
                                        <p class="text-xs text-red-500 font-semibold mt-1 flex items-center gap-1">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span x-text="fileError"></span>
                                        </p>
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
                                <div class="relative">
                                    <i class="bi bi-calendar-event absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="date" id="effective_start_date" name="effective_start_date" value="{{ old('effective_start_date', $tupa->effective_start_date ? $tupa->effective_start_date->format('Y-m-d') : '') }}" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('effective_start_date') border-red-500 @enderror">
                                </div>
                                @error('effective_start_date')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha Fin Vigencia --}}
                            <div class="space-y-1.5">
                                <label for="effective_end_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Fecha Fin de Vigencia (Opcional)
                                </label>
                                <div class="relative">
                                    <i class="bi bi-calendar-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="date" id="effective_end_date" name="effective_end_date" value="{{ old('effective_end_date', $tupa->effective_end_date ? $tupa->effective_end_date->format('Y-m-d') : '') }}" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('effective_end_date') border-red-500 @enderror">
                                </div>
                                <p class="text-[11px] text-gray-400">Dejar en blanco si la vigencia es indefinida.</p>
                                @error('effective_end_date')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2 pt-2">
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">Estado de Publicación</h4>
                                        <p class="text-xs text-gray-500">Determina si este registro es visible en el portal público de la institución.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tupa->is_active) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.tupa.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2">
                                <i class="bi bi-check-lg text-lg"></i>
                                <span>Guardar Cambios</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- PDF Reader Modal para Previsualizar --}}
            <div x-show="showPdfModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
                <div @click.away="showPdfModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-900 text-white">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-file-earmark-pdf text-red-400 text-xl"></i>
                            <h3 class="font-bold text-sm sm:text-base truncate max-w-md" x-text="pdfModalTitle"></h3>
                        </div>
                        <button type="button" @click="showPdfModal = false" class="text-gray-400 hover:text-white p-1 rounded-lg">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                    <div class="flex-1 bg-gray-100 relative">
                        <iframe :src="pdfModalUrl" class="w-full h-full border-none"></iframe>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

@push('scripts')
<script>
    function tupaEditForm(existingUrl, documentTitle) {
        return {
            existingUrl: existingUrl,
            documentTitle: documentTitle,
            fileName: '',
            fileSize: '',
            fileError: '',
            newPdfPreviewUrl: '',
            isDragging: false,
            showPdfModal: false,
            pdfModalUrl: '',
            pdfModalTitle: '',

            handleFileChange(event) {
                const file = event.target.files[0];
                this.processSelectedFile(file);
            },

            handleFileDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file) {
                    const input = document.getElementById('tupaFileInput');
                    if (input) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        input.files = dt.files;
                    }
                    this.processSelectedFile(file);
                }
            },

            processSelectedFile(file) {
                this.fileError = '';
                if (!file) return;

                if (file.type !== 'application/pdf') {
                    this.fileError = 'El archivo seleccionado debe ser un documento PDF válido.';
                    this.clearNewFile();
                    return;
                }

                if (file.size > 20 * 1024 * 1024) {
                    this.fileError = 'El archivo pesa más de 20MB. Por favor seleccione un PDF más ligero.';
                    this.clearNewFile();
                    return;
                }

                this.fileName = file.name;
                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                this.newPdfPreviewUrl = URL.createObjectURL(file);
            },

            clearNewFile() {
                this.fileName = '';
                this.fileSize = '';
                this.newPdfPreviewUrl = '';
                const input = document.getElementById('tupaFileInput');
                if (input) input.value = '';
            },

            openPdfModal(url, title) {
                this.pdfModalUrl = url;
                this.pdfModalTitle = title;
                this.showPdfModal = true;
            }
        }
    }
</script>
@endpush
@endsection
