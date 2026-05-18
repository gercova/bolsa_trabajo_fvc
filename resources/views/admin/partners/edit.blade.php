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

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="partnerForm({
            company: '{{ $partner->company }}',
            image_url: '{{ $partner->image_url }}',
            is_active: {{ $partner->is_active ? 'true' : 'false' }}
        })">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Actualizar Partner
                    </h2>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Company --}}
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Empresa <span class="text-red-500">*</span></label>
                            <input type="text" id="company" x-model="form.company" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="Nombre de la empresa">
                            <p x-show="errors.company" x-text="errors.company" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        {{-- Image URL --}}
                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">URL de la imagen</label>
                            <input type="url" id="image_url" x-model="form.image_url" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="https://ejemplo.com/logo.png">
                            <p x-show="errors.image_url" x-text="errors.image_url" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        {{-- Active --}}
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="is_active" x-model="form.is_active" 
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Partner activo</label>
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
                                <span x-show="loading" class="flex items-center gap-1">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('partnerForm', (initialData) => ({
            form: { ...initialData },
            errors: {},
            loading: false,

            validate() {
                this.errors = {};
                if (!this.form.company.trim()) {
                    this.errors.company = 'El nombre de la empresa es obligatorio.';
                }
                if (this.form.image_url.trim() !== '') {
                    try {
                        new URL(this.form.image_url);
                    } catch (_) {
                        this.errors.image_url = 'Ingresa una URL válida (ej. https://...).';
                    }
                }
                return Object.keys(this.errors).length === 0;
            },

            async submitForm() {
                if (!this.validate()) return;

                this.loading = true;
                try {
                    const response = await fetch('{{ route("admin.partners.update", $partner) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ...this.form,
                            _method: 'PUT'  // Spoof method for Laravel
                        })
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                this.errors[key] = data.errors[key][0];
                            });
                            throw new Error('Errores de validación');
                        }
                        throw new Error('Error del servidor');
                    }

                    // Success
                    window.location.href = '{{ route("admin.partners.index") }}';
                } catch (error) {
                    if (error.message !== 'Error del servidor' && error.message !== 'Errores de validación') {
                        alert('Ocurrió un error inesperado.');
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush
@endsection