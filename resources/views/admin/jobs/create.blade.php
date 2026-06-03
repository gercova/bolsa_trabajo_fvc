@extends('layouts.app')
@section('title', 'Nueva Oferta Laboral - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Nueva Oferta Laboral</h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.trabajos') }}" class="hover:text-purple-600 transition">Trabajos</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Crear</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-8" x-data="jobForm()">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="bi bi-briefcase text-purple-600"></i> Detalles de la Convocatoria
                    </h2>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium mb-1" :class="errors.title ? 'text-red-600' : 'text-gray-700'">Título del Puesto <span class="text-red-500">*</span></label>
                                <input type="text" id="title" x-model="form.title" 
                                    class="w-full px-4 py-2 border rounded-lg transition-colors"
                                    :class="errors.title ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500'" 
                                    placeholder="Ej. Administrador de Redes, Técnico de Campo">
                                <p x-show="errors.title" x-text="errors.title" class="mt-1 text-sm text-red-600 font-medium" x-cloak></p>
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-medium mb-1" :class="errors.company ? 'text-red-600' : 'text-gray-700'">Empresa contratante <span class="text-red-500">*</span></label>
                                <input type="text" id="company" x-model="form.company" 
                                    class="w-full px-4 py-2 border rounded-lg transition-colors"
                                    :class="errors.company ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500'" 
                                    placeholder="Ej. Grano Dorado S.A.C.">
                                <p x-show="errors.company" x-text="errors.company" class="mt-1 text-sm text-red-600 font-medium" x-cloak></p>
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium mb-1" :class="errors.location ? 'text-red-600' : 'text-gray-700'">Ubicación <span class="text-red-500">*</span></label>
                                <input type="text" id="location" x-model="form.location" 
                                    class="w-full px-4 py-2 border rounded-lg transition-colors"
                                    :class="errors.location ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500'" 
                                    placeholder="Ej. Uchiza, San Martín">
                                <p x-show="errors.location" x-text="errors.location" class="mt-1 text-sm text-red-600 font-medium" x-cloak></p>
                            </div>

                            <div>
                                <label for="url" class="block text-sm font-medium mb-1" :class="errors.url ? 'text-red-600' : 'text-gray-700'">Enlace / URL de Postulación (Opcional)</label>
                                <input type="url" id="url" x-model="form.url" 
                                    class="w-full px-4 py-2 border rounded-lg transition-colors"
                                    :class="errors.url ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500'" 
                                    placeholder="https://ejemplo.com/empleo">
                                <p x-show="errors.url" x-text="errors.url" class="mt-1 text-sm text-red-600 font-medium" x-cloak></p>
                            </div>

                            <div>
                                <label for="source" class="block text-sm font-medium mb-1" :class="errors.source ? 'text-red-600' : 'text-gray-700'">Fuente del empleo (Opcional)</label>
                                <input type="text" id="source" x-model="form.source" 
                                    class="w-full px-4 py-2 border rounded-lg transition-colors"
                                    :class="errors.source ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500'" 
                                    placeholder="Ej. Bolsa Institucional, LinkedIn">
                                <p x-show="errors.source" x-text="errors.source" class="mt-1 text-sm text-red-600 font-medium" x-cloak></p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium mb-1" :class="errors.description ? 'text-red-600' : 'text-gray-700'">Descripción del Puesto <span class="text-red-500">*</span></label>
                                <textarea id="description" x-model="form.description" rows="5" 
                                    class="w-full px-4 py-2 border rounded-lg transition-colors"
                                    :class="errors.description ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500'" 
                                    placeholder="Detalla los requisitos, funciones y beneficios del puesto..."></textarea>
                                <p x-show="errors.description" x-text="errors.description" class="mt-1 text-sm text-red-600 font-medium" x-cloak></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 bg-purple-50/50 p-4 rounded-lg border border-purple-100">
                            <input type="checkbox" id="is_active" x-model="form.is_active" class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800 cursor-pointer">Publicar inmediatamente</label>
                                <p class="text-xs text-gray-500">Si se desmarca, la oferta se guardará como borrador invisible para los alumnos.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.trabajos') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancelar</a>
                            <button type="submit" :disabled="loading" class="px-5 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition disabled:opacity-60 flex items-center gap-2">
                                <i class="bi bi-save"></i>
                                <span x-show="!loading">Guardar Oferta</span>
                                <span x-show="loading" x-cloak>Guardando...</span>
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
    // Configuración global del Toast
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
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
                    const response = await fetch('{{ route("admin.trabajos.store") }}', {
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
                            // Mapea los errores
                            Object.keys(data.errors).forEach(key => {
                                this.errors[key] = data.errors[key][0];
                            });
                            
                            // Disparar Toast de error
                            Toast.fire({
                                icon: 'warning',
                                title: 'Revisa los campos marcados en rojo'
                            });
                            
                            throw new Error('Errores de validación detectados.');
                        }
                        
                        Toast.fire({
                            icon: 'error',
                            title: 'Ocurrió un error en el servidor.'
                        });
                        throw new Error('Error en el servidor central.');
                    }

                    // Toast de éxito (opcional, si quieres que se vea antes de redirigir)
                    Toast.fire({
                        icon: 'success',
                        title: 'Oferta laboral creada con éxito.'
                    });
                    
                    // Pequeña pausa para que se vea el toast antes de redirigir
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.trabajos") }}';
                    }, 1500);
                    
                } catch (error) {
                    // Solo mostramos alerta genérica si no es error de validación (ya manejado arriba)
                    if (Object.keys(this.errors).length === 0) {
                        console.error(error);
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endpush