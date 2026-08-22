@extends('layouts.app')
@section('title', 'Gestión de Roles y Permisos - Panel Administrativo')

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
                        Gestión de Roles y Permisos
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Roles del Sistema</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="rolesList()">
            <div class="max-w-7xl mx-auto space-y-6">

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

                {{-- Header Actions Bar --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Listado de Roles Registrados</h2>
                        <p class="text-sm text-gray-500">Administra los roles institucionales (Director, Docentes, Administradores, Coordinadores) y asigna privilegios.</p>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('admin.roles.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2">
                            <i class="bi bi-shield-plus text-lg"></i>
                            <span>Crear Nuevo Rol</span>
                        </a>
                    </div>
                </div>

                {{-- Summary Stat Cards --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i class="bi bi-shield-lock-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="roles.length">0</p>
                                <p class="text-xs text-gray-400 font-medium">Total Roles</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-key-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900">{{ $permissions->count() }}</p>
                                <p class="text-xs text-gray-400 font-medium">Permisos del Sistema</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <i class="bi bi-person-badge-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="roles.filter(r => r.name.toLowerCase().includes('coordinador')).length">0</p>
                                <p class="text-xs text-gray-400 font-medium">Coordinaciones</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="bi bi-people-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900" x-text="roles.reduce((acc, r) => acc + (r.users_count || 0), 0)">0</p>
                                <p class="text-xs text-gray-400 font-medium">Usuarios Asignados</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search & View Toggle Filter Bar --}}
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-80">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" x-model="search" placeholder="Buscar por nombre de rol..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        </div>

                        <select x-model="categoryFilter" class="w-full sm:w-48 py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            <option value="">Todas las categorías</option>
                            <option value="coordinador">Coordinaciones</option>
                            <option value="director">Dirección</option>
                            <option value="docente">Docencia</option>
                            <option value="admin">Administración</option>
                        </select>
                    </div>

                    {{-- View Switcher Buttons --}}
                    <div class="flex items-center bg-gray-100 p-1 rounded-xl shrink-0 self-end sm:self-center">
                        <button type="button" @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5">
                            <i class="bi bi-grid-fill"></i> Tarjetas
                        </button>
                        <button type="button" @click="viewMode = 'table'"
                                :class="viewMode === 'table' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5">
                            <i class="bi bi-table"></i> Tabla
                        </button>
                    </div>
                </div>

                {{-- ===== MODE 1: GRID CARDS VIEW ===== --}}
                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="role in filteredRoles" :key="role.id">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 font-bold"
                                             :class="getRoleBadgeStyle(role.name).icon">
                                            <i class="bi" :class="getRoleBadgeStyle(role.name).biIcon"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-gray-900 text-base leading-tight truncate" x-text="role.name"></h3>
                                            <span class="inline-block text-[11px] font-semibold text-gray-400 mt-0.5" x-text="`${role.users_count || 0} usuarios asignados`"></span>
                                        </div>
                                    </div>

                                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full border shrink-0"
                                          :class="getRoleBadgeStyle(role.name).badge"
                                          x-text="getRoleBadgeStyle(role.name).category">
                                    </span>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-gray-50">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Permisos Asignados:</p>
                                        <button type="button" @click="openPermissionsModal(role)" class="text-[11px] font-semibold text-purple-600 hover:underline flex items-center gap-1">
                                            <i class="bi bi-eye"></i> Ver todos (<span x-text="role.permissions ? role.permissions.length : (role.permissions_count || 0)"></span>)
                                        </button>
                                    </div>

                                    <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto custom-scrollbar pt-1">
                                        <template x-if="role.permissions && role.permissions.length > 0">
                                            <template x-for="p in role.permissions.slice(0, 4)" :key="p.id">
                                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100 flex items-center gap-1">
                                                    <i class="bi bi-check-circle-fill text-blue-500 text-[10px]"></i>
                                                    <span x-text="p.name"></span>
                                                </span>
                                            </template>
                                        </template>

                                        <template x-if="role.permissions && role.permissions.length > 4">
                                            <span @click="openPermissionsModal(role)" class="text-[11px] font-bold px-2 py-0.5 rounded-lg bg-purple-50 text-purple-700 border border-purple-100 cursor-pointer hover:bg-purple-100">
                                                +<span x-text="role.permissions.length - 4"></span> más...
                                            </span>
                                        </template>

                                        <template x-if="!role.permissions || role.permissions.length === 0">
                                            <span class="text-xs font-medium text-gray-400 italic">Sin permisos asignados</span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <a :href="getEditUrl(role.id)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl text-xs font-bold transition-colors">
                                    <i class="bi bi-pencil-square"></i> Editar Rol
                                </a>

                                <template x-if="!['Director','Administrador','Admin'].includes(role.name)">
                                    <button type="button" @click="deleteRole(role)" class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Eliminar rol">
                                        <i class="bi bi-trash3 text-base"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- ===== MODE 2: TABLE VIEW ===== --}}
                <div x-show="viewMode === 'table'" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-500">
                                    <th class="px-6 py-4">Nombre del Rol</th>
                                    <th class="px-6 py-4">Categoría</th>
                                    <th class="px-6 py-4">Permisos Asignados</th>
                                    <th class="px-6 py-4">Usuarios</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <template x-for="role in filteredRoles" :key="role.id">
                                    <tr class="hover:bg-purple-50/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 font-bold"
                                                     :class="getRoleBadgeStyle(role.name).icon">
                                                    <i class="bi" :class="getRoleBadgeStyle(role.name).biIcon"></i>
                                                </div>
                                                <div class="font-bold text-gray-900" x-text="role.name"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-full border"
                                                  :class="getRoleBadgeStyle(role.name).badge"
                                                  x-text="getRoleBadgeStyle(role.name).category">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button type="button" @click="openPermissionsModal(role)" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-bold border border-blue-100 transition-colors">
                                                <i class="bi bi-key-fill text-blue-500"></i>
                                                <span x-text="`${role.permissions ? role.permissions.length : (role.permissions_count || 0)} permisos`"></span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700">
                                                <i class="bi bi-people-fill text-gray-400"></i>
                                                <span x-text="`${role.users_count || 0} usuarios`"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button type="button" @click="openPermissionsModal(role)" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Ver detalles de permisos">
                                                    <i class="bi bi-eye text-base"></i>
                                                </button>
                                                <a :href="getEditUrl(role.id)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar rol">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>
                                                <template x-if="!['Director','Administrador','Admin'].includes(role.name)">
                                                    <button type="button" @click="deleteRole(role)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar rol">
                                                        <i class="bi bi-trash3 text-base"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="filteredRoles.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <i class="bi bi-shield-x text-4xl block mb-2 text-gray-300"></i>
                                            <p class="text-base font-bold text-gray-700">No se encontraron roles</p>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modal de Detalle de Permisos --}}
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
                    <div @click.away="showModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-900 text-white">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-shield-lock text-purple-400 text-xl"></i>
                                <h3 class="font-bold text-base" x-text="selectedRole ? `Permisos del Rol: ${selectedRole.name}` : 'Detalles de Permisos'"></h3>
                            </div>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-white p-1 rounded-lg">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto space-y-4">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lista completa de accesos concedidos:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <template x-if="selectedRole && selectedRole.permissions && selectedRole.permissions.length > 0">
                                    <template x-for="p in selectedRole.permissions" :key="p.id">
                                        <div class="p-3 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center gap-2 text-xs font-semibold text-purple-900">
                                            <i class="bi bi-check-circle-fill text-purple-600"></i>
                                            <span x-text="p.name"></span>
                                        </div>
                                    </template>
                                </template>

                                <template x-if="!selectedRole || !selectedRole.permissions || selectedRole.permissions.length === 0">
                                    <div class="col-span-full py-8 text-center text-gray-400">
                                        <i class="bi bi-key-slash text-3xl block mb-2"></i>
                                        <p class="text-sm font-medium">Este rol no tiene permisos asignados.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                            <button type="button" @click="showModal = false" class="px-5 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-colors">
                                Cerrar
                            </button>
                        </div>
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
    const rolesDataServer = @json($roles);

    function rolesList() {
        return {
            roles: rolesDataServer || [],
            search: '',
            categoryFilter: '',
            viewMode: 'grid', // 'grid' | 'table'
            showModal: false,
            selectedRole: null,

            get filteredRoles() {
                return this.roles.filter(r => {
                    const matchesSearch = !this.search.trim() || r.name.toLowerCase().includes(this.search.toLowerCase());
                    const matchesCat = !this.categoryFilter || r.name.toLowerCase().includes(this.categoryFilter.toLowerCase());
                    return matchesSearch && matchesCat;
                });
            },

            getRoleBadgeStyle(name) {
                const lower = (name || '').toLowerCase();
                if (lower.includes('director')) {
                    return { category: 'Dirección', badge: 'bg-purple-100 text-purple-800 border-purple-200', icon: 'bg-purple-100 text-purple-600', biIcon: 'bi-person-badge-fill' };
                }
                if (lower.includes('coordinador')) {
                    return { category: 'Coordinación', badge: 'bg-blue-100 text-blue-800 border-blue-200', icon: 'bg-blue-100 text-blue-600', biIcon: 'bi-diagram-3-fill' };
                }
                if (lower.includes('docente') || lower.includes('teacher')) {
                    return { category: 'Docencia', badge: 'bg-sky-100 text-sky-800 border-sky-200', icon: 'bg-sky-100 text-sky-600', biIcon: 'bi-mortarboard-fill' };
                }
                if (lower.includes('admin')) {
                    return { category: 'Administración', badge: 'bg-indigo-100 text-indigo-800 border-indigo-200', icon: 'bg-indigo-100 text-indigo-600', biIcon: 'bi-gear-fill' };
                }
                return { category: 'Personalizado', badge: 'bg-gray-100 text-gray-800 border-gray-200', icon: 'bg-gray-100 text-gray-600', biIcon: 'bi-shield-check' };
            },

            openPermissionsModal(role) {
                this.selectedRole = role;
                this.showModal = true;
            },

            getEditUrl(id) {
                return '{{ url("/admin-roles") }}/' + id + '/editar';
            },

            async deleteRole(role) {
                const result = await Swal.fire({
                    title: '¿Eliminar el rol "' + role.name + '"?',
                    text: 'Esta acción desvinculará los permisos asignados a este rol. No se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch('{{ url("/admin-roles") }}/' + role.id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.roles = this.roles.filter(r => r.id !== role.id);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar el rol.', 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Ocurrió un error de servidor.', 'error');
                }
            }
        };
    }
</script>
@endpush
