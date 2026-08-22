@extends('layouts.app')
@section('title', 'Editar Documento de Gestión - Panel Administrativo')

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')

        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-pencil-square text-purple-600"></i> Editar Documento de Gestión
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <a href="{{ route('admin.documents.index') }}" class="hover:text-purple-600 transition-colors flex items-center gap-1">
                            <i class="bi bi-folder-symlink"></i> Documentos de Gestión
                        </a>
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600 font-semibold">Editar</span>
                    </div>
                </div>
            </header>

            {{-- Form Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-4xl mx-auto space-y-6">

                    {{-- Back Link --}}
                    <div>
                        <a href="{{ route('admin.documents.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                            <i class="bi bi-arrow-left"></i> Volver a la lista de documentos
                        </a>
                    </div>

                    {{-- Card Container --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/40">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="bi bi-pencil text-purple-600"></i> Modificar Información
                            </h2>
                            <p class="text-xs text-gray-500 mt-1">Actualice los datos o reemplace el archivo cargado para este documento de gestión.</p>
                        </div>

                        <form action="{{ route('admin.documents.update', $managementDocument) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6"
                            x-data="fileUploader()">
                            @csrf
                            @method('PUT')

                            {{-- Title Field --}}
                            <div class="space-y-1.5">
                                <label for="title" class="block text-sm font-semibold text-gray-700">
                                    Título del Documento <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title', $managementDocument->title) }}" required
                                    placeholder="Ej. Reglamento Interno de Trabajo 2026"
                                    class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('title') border-red-500 bg-red-50/30 @else border-gray-300 @enderror">
                                @error('title')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Description Field --}}
                            <div class="space-y-1.5">
                                <label for="description" class="block text-sm font-semibold text-gray-700">
                                    Descripción Resumida
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    placeholder="Breve resumen del contenido o propósito del documento..."
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('description') border-red-500 bg-red-50/30 @enderror">{{ old('description', $managementDocument->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Details Field --}}
                            <div class="space-y-1.5">
                                <label for="details" class="block text-sm font-semibold text-gray-700">
                                    Detalles Adicionales / Observaciones
                                </label>
                                <textarea name="details" id="details" rows="2"
                                    placeholder="Notas adicionales, resolución de aprobación, o código de documento..."
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('details') border-red-500 bg-red-50/30 @enderror">{{ old('details', $managementDocument->details) }}</textarea>
                                @error('details')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Validity Period & Active Status Grid --}}
                            {{-- Validity Period Field --}}
                            <div class="space-y-1.5">
                                <label for="validity_period" class="block text-sm font-semibold text-gray-700">
                                    Fecha / Periodo de Vigencia
                                </label>
                                <div class="relative">
                                    <input type="date" name="validity_period" id="validity_period"
                                        value="{{ old('validity_period', $managementDocument->validity_period ? $managementDocument->validity_period->format('Y-m-d') : '') }}"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('validity_period') border-red-500 bg-red-50/30 @enderror">
                                </div>
                                @error('validity_period')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="pt-2">
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">Estado de Publicación</h4>
                                        <p class="text-xs text-gray-500">Determina si este registro es visible en el portal público de la institución.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $managementDocument->is_active) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>

                            {{-- File Field with Current Thumbnail and Replacement Preview --}}
                            <div class="space-y-3 pt-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Archivo Adjunto del Documento
                                </label>

                                @php
                                    $existingExt = strtolower(pathinfo($managementDocument->file_path, PATHINFO_EXTENSION));
                                    $existingUrl = Storage::url($managementDocument->file_path);
                                    $isExistingImage = in_array($existingExt, ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp

                                {{-- Current Uploaded File Preview --}}
                                <div class="bg-purple-50/60 border border-purple-200/80 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        @if ($isExistingImage)
                                            <div class="w-20 h-20 rounded-xl overflow-hidden border border-purple-200 bg-white flex-shrink-0 relative shadow-sm">
                                                <img src="{{ $existingUrl }}" alt="Vista Previa" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-16 h-16 rounded-xl bg-white border border-purple-200 flex items-center justify-center flex-shrink-0 shadow-sm">
                                                @if ($existingExt === 'pdf')
                                                    <i class="bi bi-file-earmark-pdf-fill text-red-600 text-3xl"></i>
                                                @elseif (in_array($existingExt, ['doc', 'docx']))
                                                    <i class="bi bi-file-earmark-word-fill text-blue-600 text-3xl"></i>
                                                @elseif (in_array($existingExt, ['xls', 'xlsx']))
                                                    <i class="bi bi-file-earmark-excel-fill text-emerald-600 text-3xl"></i>
                                                @else
                                                    <i class="bi bi-file-earmark-text-fill text-purple-600 text-3xl"></i>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="text-left overflow-hidden">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-purple-900 uppercase tracking-wide">Archivo Actual</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-200 text-purple-800 uppercase">{{ $existingExt ?: 'DOC' }}</span>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-800 truncate max-w-xs sm:max-w-md mt-0.5">
                                                {{ basename($managementDocument->file_path) }}
                                            </p>
                                        </div>
                                    </div>

                                    <a href="{{ $existingUrl }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-purple-700 bg-white border border-purple-200 hover:bg-purple-100 rounded-xl transition-all shadow-sm">
                                        <i class="bi bi-box-arrow-up-right"></i> Ver / Descargar
                                    </a>
                                </div>

                                {{-- Drag & Drop / File Picker for Replacement --}}
                                <div class="relative border-2 border-dashed rounded-2xl p-6 transition-all text-center cursor-pointer mt-3"
                                    :class="isDragging ? 'border-purple-500 bg-purple-50/50' : 'border-gray-300 hover:border-purple-400 bg-gray-50/30 hover:bg-gray-50'"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event)"
                                    @click="$refs.fileInput.click()">

                                    <input type="file" name="file_path" id="file_path" x-ref="fileInput"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp"
                                        class="hidden"
                                        @change="handleFileSelect($event)">

                                    {{-- State 1: No new file selected --}}
                                    <div x-show="!file" class="space-y-2 py-1">
                                        <div class="w-10 h-10 rounded-full bg-gray-200/80 text-gray-600 flex items-center justify-center mx-auto shadow-sm">
                                            <i class="bi bi-arrow-repeat text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-700">
                                                Seleccione un nuevo archivo para reemplazar el actual (opcional)
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                Si no desea reemplazar el archivo, deje este campo vacío.
                                            </p>
                                        </div>
                                    </div>

                                    {{-- State 2: New Selected File Thumbnail & Preview --}}
                                    <div x-show="file" x-cloak class="p-2" @click.stop>
                                        <div class="bg-white border border-emerald-300 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                                            
                                            {{-- Thumbnail View --}}
                                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                                {{-- Image Thumbnail Preview --}}
                                                <template x-if="isImage">
                                                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 flex-shrink-0 relative shadow-inner">
                                                        <img :src="imageUrl" alt="Preview" class="w-full h-full object-cover">
                                                    </div>
                                                </template>

                                                {{-- Document Icon Preview --}}
                                                <template x-if="!isImage">
                                                    <div class="w-16 h-16 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
                                                        <i class="text-3xl" :class="documentIconClass"></i>
                                                    </div>
                                                </template>

                                                {{-- File Details --}}
                                                <div class="text-left overflow-hidden">
                                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700">
                                                        <i class="bi bi-check-circle-fill text-xs"></i> Nuevo Archivo Seleccionado
                                                    </span>
                                                    <p class="text-sm font-bold text-gray-800 truncate max-w-xs sm:max-w-md mt-0.5" x-text="fileName"></p>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-100 text-emerald-800 uppercase" x-text="fileExtension"></span>
                                                        <span class="text-xs text-gray-500" x-text="fileSize"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Cancel Replacement Button --}}
                                            <button type="button" @click="clearFile()"
                                                class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex items-center gap-1.5 self-end sm:self-center">
                                                <i class="bi bi-x-circle"></i> Cancelar reemplazo
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @error('file_path')
                                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                                <a href="{{ route('admin.documents.index') }}"
                                    class="w-full sm:w-auto px-5 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm rounded-xl transition-all text-center">
                                    Cancelar
                                </a>
                                <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all">
                                    <i class="bi bi-save"></i> Guardar Cambios
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
            document.addEventListener('alpine:init', () => {
                Alpine.data('fileUploader', () => ({
                    file: null,
                    fileName: '',
                    fileSize: '',
                    fileExtension: '',
                    isImage: false,
                    imageUrl: '',
                    isDragging: false,
                    documentIconClass: 'bi bi-file-earmark-text text-emerald-600',

                    handleFileSelect(event) {
                        const files = event.target.files;
                        if (files.length > 0) {
                            this.processFile(files[0]);
                        }
                    },

                    handleDrop(event) {
                        this.isDragging = false;
                        const files = event.dataTransfer.files;
                        if (files.length > 0) {
                            this.$refs.fileInput.files = files;
                            this.processFile(files[0]);
                        }
                    },

                    processFile(file) {
                        this.file = file;
                        this.fileName = file.name;
                        this.fileSize = this.formatBytes(file.size);
                        
                        const ext = file.name.split('.').pop().toLowerCase();
                        this.fileExtension = ext;
                        
                        this.isImage = ['jpg', 'jpeg', 'png', 'webp'].includes(ext);

                        if (this.isImage) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.imageUrl = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            if (ext === 'pdf') {
                                this.documentIconClass = 'bi bi-file-earmark-pdf-fill text-red-600';
                            } else if (['doc', 'docx'].includes(ext)) {
                                this.documentIconClass = 'bi bi-file-earmark-word-fill text-blue-600';
                            } else if (['xls', 'xlsx'].includes(ext)) {
                                this.documentIconClass = 'bi bi-file-earmark-excel-fill text-emerald-600';
                            } else {
                                this.documentIconClass = 'bi bi-file-earmark-text-fill text-purple-600';
                            }
                        }
                    },

                    clearFile() {
                        this.file = null;
                        this.fileName = '';
                        this.fileSize = '';
                        this.fileExtension = '';
                        this.isImage = false;
                        this.imageUrl = '';
                        this.$refs.fileInput.value = '';
                    },

                    formatBytes(bytes) {
                        if (bytes === 0) return '0 Bytes';
                        const k = 1024;
                        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                    }
                }));
            });
        </script>
    @endpush
@endsection
