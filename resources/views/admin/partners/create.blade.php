@extends('layouts.app')
@section('title', 'Nuevo Partner - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="enterpriseApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Nuevo Partner
                    </h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.partners.index') }}" class="hover:text-purple-600 transition">Socios</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Crear</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="partnerForm({
            name: '',
            slug: '',
            description: '',
            image_url: '',
            link: '',
            is_active: true
        })">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="bi bi-building-add text-purple-600"></i> Información General del Partner
                    </h2>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Partner <span class="text-red-500">*</span></label>
                                <input type="text" id="name" x-model="form.name" @input="updateSlug"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="Ej. Microsoft Perú">
                                <p x-show="errors.name" x-text="errors.name" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            {{-- Slug --}}
                            {{-- <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (URL amigable) <span class="text-red-500">*</span></label>
                                <input type="text" id="slug" x-model="form.slug" @input="slugManual = true"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="ej-microsoft-peru">
                                <p x-show="errors.slug" x-text="errors.slug" class="mt-1 text-sm text-red-600"></p>
                            </div> --}}
                        </div>

                        {{-- Description --}}
                        {{-- <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción / Detalles del Convenio <span class="text-red-500">*</span></label>
                            <textarea id="description" x-model="form.description" rows="4"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                      placeholder="Escribe detalles importantes sobre la alianza institucional..."></textarea>
                            <p x-show="errors.description" x-text="errors.description" class="mt-1 text-sm text-red-600"></p>
                        </div> --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Link Web --}}
                            {{-- <div>
                                <label for="link" class="block text-sm font-medium text-gray-700 mb-1">Enlace Web Institucional <span class="text-red-500">*</span></label>
                                <input type="url" id="link" x-model="form.link" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="https://microsoft.com">
                                <p x-show="errors.link" x-text="errors.link" class="mt-1 text-sm text-red-600"></p>
                            </div> --}}

                            {{-- Image URL --}}
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Ruta o URL del Logo/Imagen <span class="text-red-500">*</span></label>
                                <input type="text" id="image_url" x-model="form.image_url" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="storage/partners/logo.png o https://...">
                                <p x-show="errors.image_url" x-text="errors.image_url" class="mt-1 text-sm text-red-600"></p>
                            </div>
                        </div>

                        {{-- Image Preview Area --}}
                        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center min-h-[140px]">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Vista previa del Logo</span>
                            <template x-if="form.image_url">
                                <img :src="form.image_url.startsWith('http') ? form.image_url : '/' + form.image_url" 
                                     @@error="$el.src='https://placehold.co/200x100?text=No+Encontrado'"
                                     class="max-h-24 object-contain rounded p-1 bg-white shadow-sm transition-all">
                            </template>
                            <template x-if="!form.image_url">
                                <div class="text-center text-gray-400">
                                    <i class="bi bi-image text-3xl mb-1 block"></i>
                                    <p class="text-xs">Inserta una ruta de imagen para visualizarla</p>
                                </div>
                            </template>
                        </div>

                        {{-- Active Status --}}
                        <div class="flex items-center gap-3 bg-purple-50/50 p-4 rounded-lg border border-purple-100">
                            <input type="checkbox" id="is_active" x-model="form.is_active" 
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800 cursor-pointer">Partner activo en la plataforma</label>
                                <p class="text-xs text-gray-500">Si se desmarca, este socio no se mostrará públicamente en el sitio del instituto.</p>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.partners.index') }}" 
                               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    :disabled="loading"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-2">
                                <i class="bi bi-save"></i>
                                <span x-show="!loading">Guardar Partner</span>
                                <span x-show="loading" class="flex items-center gap-1" x-cloak>
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Guardando...
                                </span>
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
        Alpine.data('partnerForm', (initialData) => ({
            form: { ...initialData },
            errors: {},
            loading: false,
            slugManual: false,

            updateSlug() {
                if (!this.slugManual) {
                    this.form.slug = this.form.name
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }
            },

            validate() {
                this.errors = {};
                if (!this.form.name.trim()) this.errors.name = 'El nombre del partner es obligatorio.';
                if (!this.form.slug.trim()) this.errors.slug = 'El slug es obligatorio para estructurar la navegación.';
                if (!this.form.description.trim()) this.errors.description = 'Ingresa una descripción o detalles del convenio.';
                if (!this.form.image_url.trim()) this.errors.image_url = 'La ruta o URL de la imagen del logo es requerida.';
                
                if (!this.form.link.trim()) {
                    this.errors.link = 'El enlace web institucional es obligatorio.';
                } else {
                    try { new URL(this.form.link); } catch (_) { this.errors.link = 'Ingresa una URL completa y válida (ej. https://...).'; }
                }

                return Object.keys(this.errors).length === 0;
            },

            async submitForm() {
                if (!this.validate()) return;

                this.loading = true;
                try {
                    const response = await fetch('{{ route("admin.partners.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                this.errors[key] = data.errors[key][0];
                            });
                            throw new Error('Errores de validación del servidor');
                        }
                        throw new Error('Error de servidor');
                    }

                    window.location.href = '{{ route("admin.partners.index") }}';
                } catch (error) {
                    if (!error.message.includes('validación')) {
                        alert('Ocurrió un error inesperado al procesar la solicitud.');
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush