@extends('layouts.app')
@section('title', 'Gestión de Trabajos - Panel Administrativo')

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
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Bolsa de Trabajo</h1>
                </div>
                <a href="{{ route('admin.trabajos.create') }}" class="px-4 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition flex items-center gap-2 shadow-sm text-sm">
                    <i class="bi bi-plus-circle"></i> Nueva Oferta
                </a>
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-8" x-data="jobsList({{ json_encode($jobs) }})">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="relative w-full sm:w-96">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" x-model="search" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm" 
                               placeholder="Buscar por título, empresa o ubicación...">
                    </div>
                    <div class="text-sm text-gray-500 font-medium">
                        Mostrando <span class="text-purple-600" x-text="filteredJobs.length"></span> ofertas
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">Oferta / Empresa</th>
                                    <th class="px-6 py-4">Ubicación</th>
                                    <th class="px-6 py-4">Fuente</th>
                                    <th class="px-6 py-4">Estado</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <template x-for="job in filteredJobs" :key="job.id">
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900" x-text="job.title"></div>
                                            <div class="text-xs text-gray-500" x-text="job.company"></div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            <i class="bi bi-geo-alt text-gray-400 mr-1"></i>
                                            <span x-text="job.location"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-md" x-text="job.source || 'No especificada'"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <template x-if="job.is_active">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Activo
                                                </span>
                                            </template>
                                            <template x-if="!job.is_active">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Inactivo
                                                </span>
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a :href="`{{ route('admin.trabajos') }}/${job.id}/editar-oferta`" 
                                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Editar">
                                                <i class="bi bi-pencil-square text-lg"></i>
                                            </a>
                                            <button @click="deleteJob(job.id)" 
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                                <i class="bi bi-trash3 text-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-if="filteredJobs.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <i class="bi bi-briefcase-slash text-4xl block mb-2"></i>
                                            <p class="text-base font-medium">No se encontraron ofertas de trabajo</p>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
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
        Alpine.data('dashboardApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; },
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        }));

        Alpine.data('jobsList', (initialJobs) => ({
            jobs: initialJobs,
            search: '',

            get filteredJobs() {
                if (this.search.trim() === '') return this.jobs;
                return this.jobs.filter(job => {
                    return job.title.toLowerCase().includes(this.search.toLowerCase()) ||
                        job.company.toLowerCase().includes(this.search.toLowerCase()) ||
                        job.location.toLowerCase().includes(this.search.toLowerCase());
                });
            },

            async deleteJob(id) {
                if (!confirm('¿Estás completamente seguro de eliminar esta oferta laboral? Esta acción no se puede deshacer.')) return;

                try {
                    const response = await fetch(`{{ route('admin.trabajos') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Filtramos el array localmente para removerlo del DOM instantáneamente sin recargar
                        this.jobs = this.jobs.filter(job => job.id !== id);
                    } else {
                        alert(data.message || 'Error al intentar eliminar el registro.');
                    }
                } catch (error) {
                    alert('Ocurrió un error de red o de servidor al procesar la eliminación.');
                }
            }
        }));
    });
</script>
@endpush