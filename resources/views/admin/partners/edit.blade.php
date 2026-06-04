@extends('layouts.app')
@section('title', 'Editar Partner - Panel Administrativo')
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
                        Editar Partner
                    </h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.partners.index') }}" class="hover:text-purple-600 transition">Socios</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">{{ $partner->company }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="partnerForm()">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Actualizar Información del Partner
                    </h2>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Company --}}
                            <div class="md:col-span-2">
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Empresa / Partner <span class="text-red-500">*</span></label>
                                <input type="text" id="company" x-model="form.company"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="Ej. Microsoft Perú">
                                <p x-show="errors.company" x-text="errors.company" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            {{-- File Upload --}}
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Reemplazar Logo (Opcional)</label>
                                <input type="file" id="image" x-ref="imageInput" @change="previewFile" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer">
                                <p class="text-xs text-gray-500 mt-1">Sube una imagen solo si deseas cambiar la actual.</p>
                                <p x-show="errors.image" x-text="errors.image" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            {{-- Image Preview Area --}}
                            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center min-h-[140px]">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Vista previa actual</span>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="max-h-24 object-contain rounded p-1 bg-white shadow-sm transition-all">
                                </template>
                            </div>
                        </div>

                        {{-- Active Status --}}
                        <div class="flex items-center gap-3 bg-purple-50/50 p-4 rounded-lg border border-purple-100">
                            <input type="checkbox" id="is_active" x-model="form.is_active" 
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800 cursor-pointer">Partner activo en la plataforma</label>
                                <p class="text-xs text-gray-500">Si se desmarca, este socio no se mostrará públicamente.</p>
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
                                <span x-show="!loading">Actualizar Partner</span>
                                <span x-show="loading" class="flex items-center gap-1" x-cloak>
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    </svg>
                                    Actualizando...
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
        // Inicializador del layout base del dashboard
        Alpine.data('enterpriseApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; },
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        }));
        // Inicializador del formulario
        Alpine.data('partnerForm', () => ({
            form: {
                company: '{{ addslashes($partner->company) }}',
                is_active: {{ $partner->is_active ? 'true' : 'false' }}
            },
            // Cargamos la imagen actual concatenando la ruta pública
            imagePreview: '{{ $partner->image_url ? asset("storage/" . $partner->image_url) : "" }}',
            errors: {},
            loading: false,

            previewFile(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            validate() {
                this.errors = {};
                if (!this.form.company.trim()) this.errors.company = 'El nombre de la empresa es obligatorio.';
                return Object.keys(this.errors).length === 0;
            },

            async submitForm() {
                if (!this.validate()) return;
                this.loading = true;

                let formData = new FormData();
                formData.append('company', this.form.company);
                formData.append('is_active', this.form.is_active ? 1 : 0);
                
                // Spoofing de PUT porque FormData y PHP tienen peculiaridades en métodos puros
                formData.append('_method', 'PUT');

                const fileInput = this.$refs.imageInput;
                if (fileInput.files.length > 0) {
                    formData.append('image', fileInput.files[0]);
                }

                try {
                    // Enviamos por POST porque lleva _method=PUT adentro
                    const response = await fetch('{{ route("admin.partners.update", $partner) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                this.errors[key] = data.errors[key][0];
                            });
                            throw new Error('Errores de validación');
                        }
                        throw new Error('Error de servidor');
                    }

                    window.location.href = '{{ route("admin.partners.index") }}';
                } catch (error) {
                    if (!error.message.includes('validación')) {
                        alert('Ocurrió un error inesperado en la actualización.');
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush