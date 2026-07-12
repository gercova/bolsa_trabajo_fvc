@extends('layouts.app')
@section('title', 'Editar Programa de Estudio - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="enterpriseApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.programs.index') }}" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Editar Programa: {{ $program->name }}
                    </h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.programs.index') }}" class="hover:text-purple-600 transition">Programas</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="{ logoPreview: '{{ $program->logo_path ? Storage::url($program->logo_path) : '' }}' }">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-base font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-purple-600 rounded-full"></span>
                        Modificar Información del Programa de Estudio
                    </h2>

                    <form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Nombre del Programa <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $program->name) }}" required
                                    class="w-full px-4 py-2.5 border @error('name') border-red-500 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-xs focus:outline-none" 
                                    placeholder="Ej. Producción Agropecuaria">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Descripción <span class="text-red-500">*</span></label>
                                <textarea id="description" name="description" rows="5" required
                                    class="w-full px-4 py-2.5 border @error('description') border-red-500 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-xs focus:outline-none"
                                    placeholder="Describe las competencias, perfil del egresado y objetivos del programa...">{{ old('description', $program->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Details --}}
                            <div class="md:col-span-2">
                                <label for="details" class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Detalles / Requisitos <span class="text-red-500">*</span></label>
                                <textarea id="details" name="details" rows="3" required
                                    class="w-full px-4 py-2.5 border @error('details') border-red-500 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-xs focus:outline-none"
                                    placeholder="Ej. Duración: 3 años (06 períodos lectivos)&#10;Título: Profesional Técnico en Producción Agropecuaria">{{ old('details', $program->details) }}</textarea>
                                @error('details')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Logo Upload --}}
                            <div>
                                <label for="logo_path" class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Logo del Programa</label>
                                <input type="file" id="logo_path" name="logo_path" accept="image/png, image/jpeg, image/jpg, image/webp" 
                                    @change="logoPreview = URL.createObjectURL($event.target.files[0])"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
                                <p class="text-[10px] text-gray-400 mt-1.5">Formatos permitidos: PNG, JPG, JPEG, WEBP. Leave empty to keep current logo.</p>
                                @error('logo_path')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Logo Preview --}}
                            <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center min-h-[120px]">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">Vista previa del Logo</span>
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="max-h-20 object-contain rounded-lg p-1 bg-white shadow-sm border border-gray-100 transition-all">
                                </template>
                                <template x-if="!logoPreview">
                                    <div class="text-center text-gray-400">
                                        <i class="bi bi-image text-2xl mb-1 block"></i>
                                        <p class="text-[10px]">No se ha seleccionado archivo</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Active Switch --}}
                        <div class="flex items-center gap-3 bg-purple-50/30 p-4 rounded-xl border border-purple-100/50">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $program->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                class="h-4.5 w-4.5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                            <div>
                                <label for="is_active" class="text-xs font-bold text-gray-800 cursor-pointer">Programa de estudio activo</label>
                                <p class="text-[10px] text-gray-400">Si está inactivo, no se mostrará en las secciones públicas ni en los filtros.</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.programs.index') }}" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-purple-600 rounded-xl hover:bg-purple-700 transition flex items-center gap-1.5 shadow-sm">
                                <i class="bi bi-floppy"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.data('enterpriseApp')) {
            Alpine.data('enterpriseApp', () => ({
                sidebarOpen: window.innerWidth >= 1024,
                toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
            }));
        }
    });
</script>
@endpush
