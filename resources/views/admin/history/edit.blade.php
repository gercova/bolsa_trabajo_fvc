@extends('layouts.app')
@section('title', 'Editar Hito Histórico - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Editar Hito Histórico
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.history.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">
                        <i class="bi bi-arrow-left"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Form Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto">
                
                @if ($errors->any())
                    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm">
                        <div class="flex items-center gap-2 text-rose-800 font-bold text-sm mb-1">
                            <i class="bi bi-exclamation-octagon-fill text-rose-600"></i>
                            Por favor corrige los siguientes errores:
                        </div>
                        <ul class="list-disc list-inside text-xs text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.history.update', $history) }}" method="POST" enctype="multipart/form-data" 
                      x-data="historyEditForm('{{ $history->image_url ?? '' }}')" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8 space-y-6">
                        
                        {{-- Título --}}
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-800 mb-1.5">
                                Título de la Etapa o Hito <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $history->title) }}" required
                                   placeholder="Ej: Etapa 1: Creación y funcionamiento (1991 - 2000)"
                                   class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                        </div>

                        {{-- Período (Años) & Orden --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            {{-- Año de Inicio --}}
                            <div>
                                <label for="start_year" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    Año de Inicio
                                </label>
                                <div class="relative">
                                    <input type="number" name="start_year" id="start_year" value="{{ old('start_year', $history->start_year) }}" min="1900" max="2100"
                                           placeholder="Ej: 1991"
                                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                    <i class="bi bi-calendar-event absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            {{-- Año de Fin --}}
                            <div>
                                <label for="end_year" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    Año de Fin
                                    <span class="text-xs font-normal text-gray-400 block sm:inline">(opcional)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="end_year" id="end_year" value="{{ old('end_year', $history->end_year) }}" min="1900" max="2100"
                                           placeholder="Ej: 2000 (o vacío para Presente)"
                                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                    <i class="bi bi-calendar-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            {{-- Orden --}}
                            <div>
                                <label for="order" class="block text-sm font-bold text-gray-800 mb-1.5">
                                    Orden de Presentación
                                </label>
                                <div class="relative">
                                    <input type="number" name="order" id="order" value="{{ old('order', $history->order) }}" min="0" max="255"
                                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                    <i class="bi bi-sort-numeric-down absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Foto / Imagen Referencial con Preview y Miniatura --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">
                                Fotografía o Imagen Representativa
                                <span class="text-xs font-normal text-gray-500">(Formatos: JPG, PNG, WEBP, máx 5MB)</span>
                            </label>

                            <input type="hidden" name="remove_image" :value="removeImage ? '1' : '0'">

                            <div class="mt-2 flex flex-col sm:flex-row items-start gap-6">
                                {{-- Zona de Subida --}}
                                <div class="flex-1 w-full">
                                    <div class="border-2 border-dashed border-gray-300 hover:border-purple-500 rounded-2xl p-6 text-center transition-colors cursor-pointer bg-gray-50/50 relative group"
                                         @dragover.prevent="" @drop.prevent="handleDrop($event)">
                                        <input type="file" name="image" id="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                               @change="fileChosen($event)">
                                        
                                        <div class="space-y-2">
                                            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mx-auto transition-transform group-hover:scale-110">
                                                <i class="bi bi-cloud-arrow-up-fill text-2xl"></i>
                                            </div>
                                            <p class="text-sm font-bold text-gray-700">Haz clic para cambiar imagen o arrastra una nueva</p>
                                            <p class="text-xs text-gray-500">Dejar vacío para conservar la foto actual</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Miniatura Preview --}}
                                <div class="w-full sm:w-44 flex flex-col items-center justify-center">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Vista Previa</span>
                                    <div class="w-40 h-28 rounded-xl border border-gray-200 bg-gray-100 overflow-hidden flex items-center justify-center relative shadow-sm">
                                        <template x-if="imageUrl && !removeImage">
                                            <div class="relative w-full h-full group">
                                                <img :src="imageUrl" alt="Preview" class="w-full h-full object-cover">
                                                <button type="button" @click="markRemoveImage()" title="Eliminar imagen" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow hover:bg-red-700">
                                                    <i class="bi bi-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <template x-if="!imageUrl || removeImage">
                                            <div class="text-center text-gray-400 p-2">
                                                <i class="bi bi-image text-3xl"></i>
                                                <p class="text-[10px] mt-1" x-text="removeImage ? 'Imagen eliminada' : 'Sin imagen'"></p>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <template x-if="removeImage">
                                        <button type="button" @click="restoreImage()" class="text-[11px] text-purple-600 hover:text-purple-800 font-semibold mt-1">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restaurar original
                                        </button>
                                    </template>
                                    <template x-if="imageFileName">
                                        <p class="text-[11px] text-gray-600 mt-1 truncate max-w-[160px]" x-text="imageFileName"></p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Descripción Narrativa --}}
                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-800 mb-1.5">
                                Reseña Histórica / Descripción Completa <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="8" required
                                      placeholder="Escribe la reseña detallada de esta etapa o hito histórico. Puedes separar párrafos con saltos de línea..."
                                      class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-sans leading-relaxed">{{ old('description', $history->description) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Los saltos de línea se formatearán automáticamente como párrafos en la página pública.</p>
                        </div>

                        {{-- Estado Activo --}}
                        <div class="pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $history->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                <span class="ml-3 text-sm font-bold text-gray-800">Hito Activo (Visible al público)</span>
                            </label>
                        </div>

                    </div>

                    {{-- Footer Actions --}}
                    <div class="p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-end items-center gap-3">
                        <a href="{{ route('admin.history.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 transition text-center shadow-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                            <i class="bi bi-check-lg text-lg"></i>
                            <span>Actualizar Hito Histórico</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('historyEditForm', (initialImageUrl) => ({
            imageUrl: initialImageUrl,
            originalImageUrl: initialImageUrl,
            imageFileName: '',
            removeImage: false,

            fileChosen(event) {
                const file = event.target.files[0];
                if (file) {
                    this.removeImage = false;
                    this.imageFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            handleDrop(event) {
                const file = event.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const input = document.getElementById('image');
                    input.files = event.dataTransfer.files;
                    this.removeImage = false;
                    this.imageFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            markRemoveImage() {
                this.removeImage = true;
                this.imageFileName = '';
                const input = document.getElementById('image');
                if (input) input.value = '';
            },

            restoreImage() {
                this.removeImage = false;
                this.imageUrl = this.originalImageUrl;
            }
        }));
    });
</script>
@endpush
