@extends('layouts.app')
@section('title', 'Nueva Oferta Laboral - Panel Administrativo')

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
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">Nueva Oferta Laboral</h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.works.index') }}" class="hover:text-purple-600 transition">Trabajos</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Crear Oferta</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="jobForm()">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.works.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-3 pb-6 border-b border-gray-100 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                            <i class="bi bi-briefcase text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Detalles de la Convocatoria Laboral</h2>
                            <p class="text-xs text-gray-500">Completa los campos obligatorios para registrar la oportunidad en el portal.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.works.store') }}" method="POST" @submit.prevent="submitForm" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Título del puesto --}}
                            <div class="md:col-span-2 space-y-1">
                                <label for="title" class="block text-xs font-bold uppercase tracking-wider" :class="errors.title ? 'text-red-600' : 'text-gray-700'">
                                    Título del Puesto <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" x-model="form.title" 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                    :class="errors.title ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent'" 
                                    placeholder="Ej: Administrador de Redes, Técnico Agrónomo, Asistente Contable">
                                <p x-show="errors.title" x-text="errors.title" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                            </div>

                            {{-- Empresa --}}
                            <div class="space-y-1">
                                <label for="company" class="block text-xs font-bold uppercase tracking-wider" :class="errors.company ? 'text-red-600' : 'text-gray-700'">
                                    Empresa / Institución contratante <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="company" name="company" x-model="form.company" 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                    :class="errors.company ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent'" 
                                    placeholder="Ej: Palma del Espino S.A., Municipalidad Distrital de Uchiza">
                                <p x-show="errors.company" x-text="errors.company" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                            </div>

                            {{-- Ubicación --}}
                            <div class="space-y-1">
                                <label for="location" class="block text-xs font-bold uppercase tracking-wider" :class="errors.location ? 'text-red-600' : 'text-gray-700'">
                                    Ubicación <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="location" name="location" x-model="form.location" 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                    :class="errors.location ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent'" 
                                    placeholder="Ej: Uchiza, Tocache, San Martín">
                                <p x-show="errors.location" x-text="errors.location" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                            </div>

                            {{-- URL Postulación --}}
                            <div class="space-y-1">
                                <label for="url" class="block text-xs font-bold uppercase tracking-wider" :class="errors.url ? 'text-red-600' : 'text-gray-700'">
                                    Enlace / URL de Postulación <span class="text-gray-400 font-normal">(Opcional)</span>
                                </label>
                                <input type="url" id="url" name="url" x-model="form.url" 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                    :class="errors.url ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent'" 
                                    placeholder="https://ejemplo.com/postular">
                                <p x-show="errors.url" x-text="errors.url" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                            </div>

                            {{-- Fuente --}}
                            <div class="space-y-1">
                                <label for="source" class="block text-xs font-bold uppercase tracking-wider" :class="errors.source ? 'text-red-600' : 'text-gray-700'">
                                    Fuente del Empleo <span class="text-gray-400 font-normal">(Opcional)</span>
                                </label>
                                <input type="text" id="source" name="source" x-model="form.source" 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                    :class="errors.source ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent'" 
                                    placeholder="Ej: Convocatoria Interna FVC, LinkedIn, Computrabajo">
                                <p x-show="errors.source" x-text="errors.source" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2 space-y-1">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider" :class="errors.description ? 'text-red-600' : 'text-gray-700'">
                                    Descripción del Puesto y Requisitos <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" x-model="form.description" rows="5" 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                    :class="errors.description ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent'" 
                                    placeholder="Describe las funciones principales, perfil del candidato, horario y beneficios del puesto..."></textarea>
                                <p x-show="errors.description" x-text="errors.description" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                            </div>
                        </div>

                        {{-- Publicar toggle --}}
                        <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">Publicar Oferta Inmediatamente</h4>
                                <p class="text-xs text-gray-500">Si está marcado, la oferta estará visible públicamente en el portal.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_active" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.works.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" :disabled="loading" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2 disabled:opacity-60">
                                <i class="bi bi-check-lg text-lg" x-show="!loading"></i>
                                <span x-show="!loading">Guardar Oferta</span>
                                <span x-show="loading" x-cloak class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true
    });

    document.addEventListener('alpine:init', () => {
        Alpine.data('jobForm', () => ({
            form: {
                title: '',
                company: '',
                location: '',
                url: '',
                source: '',
                description: '',
                is_active: true
            },
            errors: {},
            loading: false,

            async submitForm() {
                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch('{{ route("admin.works.store") }}', {
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
                        if (response.status === 422 && data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                this.errors[key] = data.errors[key][0];
                            });
                            Toast.fire({
                                icon: 'warning',
                                title: 'Por favor corrige los errores resaltados'
                            });
                            return;
                        }
                        
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Ocurrió un error en el servidor.'
                        });
                        return;
                    }

                    Toast.fire({
                        icon: 'success',
                        title: 'Oferta laboral registrada correctamente'
                    });

                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("admin.works.index") }}';
                    }, 1200);

                } catch (error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error de red o comunicación con el servidor.'
                    });
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush