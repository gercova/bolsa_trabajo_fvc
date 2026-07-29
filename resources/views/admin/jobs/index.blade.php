@extends('layouts.app')
@section('title', 'Gestión de Bolsa de Trabajo - Panel Administrativo')

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
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Gestión de Bolsa de Trabajo
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Ofertas Laborales</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="jobsList(@json($jobs))">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700 p-1" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 p-1" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Header Actions & Summary --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Ofertas Laborales Registradas</h2>
                        <p class="text-sm text-gray-500">Administra las oportunidades de empleo y prácticas para estudiantes y egresados.</p>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.works.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2">
                            <i class="bi bi-plus-circle text-lg"></i>
                            <span>Publicar Nueva Oferta</span>
                        </a>
                    </div>
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i class="bi bi-briefcase-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="jobs.length">0</p>
                                <p class="text-xs text-gray-400 font-medium">Total Ofertas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="bi bi-check-circle-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="jobs.filter(j => j.is_active).length">0</p>
                                <p class="text-xs text-gray-400 font-medium">Activas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                <i class="bi bi-eye-slash-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="jobs.filter(j => !j.is_active).length">0</p>
                                <p class="text-xs text-gray-400 font-medium">Inactivas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-building text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="jobs.filter(j => j.source && j.source.toLowerCase().includes('interna')).length">0</p>
                                <p class="text-xs text-gray-400 font-medium">Convocatorias Internas</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters Bar --}}
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" x-model="search" placeholder="Buscar por título, empresa o ubicación..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                            <select x-model="statusFilter" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                <option value="">Todos los estados</option>
                                <option value="active">Activos</option>
                                <option value="inactive">Inactivos</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fuente</label>
                            <input type="text" x-model="sourceFilter" placeholder="Filtrar por fuente (ej: Interna, LinkedIn)..." class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">Oferta Laboral / Empresa</th>
                                    <th class="px-6 py-4">Ubicación</th>
                                    <th class="px-6 py-4">Fuente</th>
                                    <th class="px-6 py-4">Estado</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <template x-for="job in filteredJobs" :key="job.id">
                                    <tr class="hover:bg-purple-50/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 font-bold border border-purple-100">
                                                    <i class="bi bi-briefcase text-lg"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <a :href="getEditUrl(job.id)" class="font-bold text-gray-900 hover:text-purple-600 transition-colors line-clamp-1" x-text="job.title"></a>
                                                    <p class="text-xs text-purple-600 font-medium line-clamp-1" x-text="job.company"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            <span class="inline-flex items-center gap-1 text-xs font-medium bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                                                <i class="bi bi-geo-alt-fill text-gray-400"></i>
                                                <span x-text="job.location || 'No especificada'"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                                                  :class="job.source && job.source.toLowerCase().includes('interna') ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-gray-100 text-gray-700'">
                                                <i class="bi" :class="job.source && job.source.toLowerCase().includes('interna') ? 'bi-star-fill text-blue-500' : 'bi-globe'"></i>
                                                <span x-text="job.source || 'Bolsa Institucional'"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button type="button" @click="toggleStatus(job)" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all"
                                                    :class="job.is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                                                <span class="w-2 h-2 rounded-full" :class="job.is_active ? 'bg-emerald-500' : 'bg-gray-400'"></span>
                                                <span x-text="job.is_active ? 'Activo' : 'Inactivo'"></span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <template x-if="job.url && job.url !== '#'">
                                                    <a :href="job.url" target="_blank" class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Ver enlace de postulación">
                                                        <i class="bi bi-box-arrow-up-right text-base"></i>
                                                    </a>
                                                </template>
                                                <a :href="getEditUrl(job.id)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar oferta">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>
                                                <button type="button" @click="deleteJob(job)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar oferta">
                                                    <i class="bi bi-trash3 text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="filteredJobs.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <div class="w-16 h-16 mx-auto mb-3 bg-gray-50 rounded-2xl flex items-center justify-center">
                                                <i class="bi bi-briefcase-slash text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron ofertas laborales</p>
                                            <p class="text-xs text-gray-400 mt-1">Prueba cambiando los criterios de búsqueda o crea una nueva oferta.</p>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('jobsList', (initialJobs) => ({
            jobs: initialJobs || [],
            search: '',
            statusFilter: '',
            sourceFilter: '',

            get filteredJobs() {
                return this.jobs.filter(job => {
                    const matchesSearch = !this.search.trim() ||
                        (job.title && job.title.toLowerCase().includes(this.search.toLowerCase())) ||
                        (job.company && job.company.toLowerCase().includes(this.search.toLowerCase())) ||
                        (job.location && job.location.toLowerCase().includes(this.search.toLowerCase()));

                    const matchesStatus = !this.statusFilter ||
                        (this.statusFilter === 'active' && job.is_active) ||
                        (this.statusFilter === 'inactive' && !job.is_active);

                    const matchesSource = !this.sourceFilter.trim() ||
                        (job.source && job.source.toLowerCase().includes(this.sourceFilter.toLowerCase()));

                    return matchesSearch && matchesStatus && matchesSource;
                });
            },

            getEditUrl(id) {
                return `{{ url('/admin-trabajos') }}/${id}/editar-oferta`;
            },

            async toggleStatus(job) {
                try {
                    const response = await fetch(`{{ url('/admin-trabajos/estado') }}/${job.id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        job.is_active = data.is_active;
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        alert(data.message || 'Error al cambiar estado.');
                    }
                } catch (e) {
                    alert('Error de red al alternar el estado.');
                }
            },

            async deleteJob(job) {
                const result = await Swal.fire({
                    title: '¿Eliminar esta oferta laboral?',
                    text: `Se eliminará "${job.title}" de la base de datos. Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`{{ url('/admin-trabajos') }}/${job.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.jobs = this.jobs.filter(j => j.id !== job.id);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar el registro.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Ocurrió un error de red o servidor.', 'error');
                }
            }
        }));
    });
</script>
@endpush
