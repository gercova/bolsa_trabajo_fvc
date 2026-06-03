@extends('layouts.app')
@section('title', 'Editar Oferta Laboral - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Editar Oferta Laboral</h1>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-8" x-data="jobEditForm()">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Actualizar Información del Puesto
                    </h2>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título del Puesto <span class="text-red-500">*</span></label>
                                <input type="text" id="title" x-model="form.title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p x-show="errors.title" x-text="errors.title" class="mt-1 text-sm text-red-600" x-cloak></p>
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Empresa contratante <span class="text-red-500">*</span></label>
                                <input type="text" id="company" x-model="form.company" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p x-show="errors.company" x-text="errors.company" class="mt-1 text-sm text-red-600" x-cloak></p>
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Ubicación <span class="text-red-500">*</span></label>
                                <input type="text" id="location" x-model="form.location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p x-show="errors.location" x-text="errors.location" class="mt-1 text-sm text-red-600" x-cloak></p>
                            </div>

                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-1">Enlace / URL de Postulación</label>
                                <input type="url" id="url" x-model="form.url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p x-show="errors.url" x-text="errors.url" class="mt-1 text-sm text-red-600" x-cloak></p>
                            </div>

                            <div>
                                <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Fuente del empleo</label>
                                <input type="text" id="source" x-model="form.source" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <p x-show="errors.source" x-text="errors.source" class="mt-1 text-sm text-red-600" x-cloak></p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Puesto <span class="text-red-500">*</span></label>
                                <textarea id="description" x-model="form.description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                                <p x-show="errors.description" x-text="errors.description" class="mt-1 text-sm text-red-600" x-cloak></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 bg-purple-50/50 p-4 rounded-lg border border-purple-100">
                            <input type="checkbox" id="is_active" x-model="form.is_active" class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800 cursor-pointer">Oferta activa en la plataforma</label>
                                <p class="text-xs text-gray-500">Si se oculta, no figurará en la sección pública de ofertas.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.trabajos') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancelar</a>
                            <button type="submit" :disabled="loading" class="px-5 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition disabled:opacity-60 flex items-center gap-2">
                                <i class="bi bi-save"></i>
                                <span x-show="!loading">Actualizar Oferta</span>
                                <span x-show="loading" x-cloak>Actualizando...</span>
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
        Alpine.data('jobEditForm', () => ({
            form: {
                title: '{{ addslashes($offer->title) }}',
                company: '{{ addslashes($offer->company) }}',
                location: '{{ addslashes($offer->location) }}',
                url: '{{ $offer->url ?? "" }}',
                source: '{{ addslashes($offer->source ?? "") }}',
                description: `{{ addslashes($offer->description) }}`,
                is_active: {{ $offer->is_active ? 'true' : 'false' }}
            },
            errors: {},
            loading: false,

            async submitForm() {
                this.loading = true;
                this.errors = {};

                try {
                    // Al ser un JSON payload síncrono, mandamos PUT sin problemas nativamente
                    const response = await fetch('{{ route("admin.trabajos.update", $offer->id) }}', {
                        method: 'PUT',
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
                            throw new Error('Errores de validación.');
                        }
                        throw new Error('Error de comunicación con el servidor.');
                    }

                    window.location.href = '{{ route("admin.trabajos") }}';
                } catch (error) {
                    if (!this.errors || Object.keys(this.errors).length === 0) {
                        alert('Ocurrió un error inesperado al actualizar la oferta.');
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush