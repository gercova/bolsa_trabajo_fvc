@extends('layouts.app')
@section('title', 'Editar Rol - Panel Administrativo')

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
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">Editar Rol y Permisos</h1>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.roles.index') }}" class="hover:text-purple-600 transition">Roles</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Rol</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="roleEditForm()">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $role->is_active ?? true ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $role->name }}
                    </span>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-3 pb-6 border-b border-gray-100 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Actualizar Rol: {{ $role->name }}</h2>
                            <p class="text-xs text-gray-500">Modifica el nombre del rol o ajusta los permisos asignados.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" @submit.prevent="submitForm" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nombre del Rol --}}
                        <div class="space-y-1">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider" :class="errors.name ? 'text-red-600' : 'text-gray-700'">
                                Nombre del Rol <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" x-model="form.name" 
                                class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all"
                                :class="errors.name ? 'border-red-500 focus:ring-2 focus:ring-red-500 bg-red-50/50' : 'border-gray-200 focus:bg-white focus:ring-2 focus:ring-purple-500'">
                            <p x-show="errors.name" x-text="errors.name" class="text-xs text-red-600 font-semibold mt-1" x-cloak></p>
                        </div>

                        {{-- Permisos del Sistema --}}
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800">Permisos Asignados al Rol</h3>
                                    <p class="text-xs text-gray-500">Marca o desmarca los privilegios de este rol.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="selectAll()" class="text-xs font-bold text-purple-600 hover:text-purple-700 hover:underline">
                                        Seleccionar todos
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" @click="unselectAll()" class="text-xs font-bold text-gray-500 hover:text-gray-700 hover:underline">
                                        Desmarcar todos
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                                @foreach($permissions as $perm)
                                    <label class="p-3 bg-gray-50 hover:bg-purple-50/50 border border-gray-200 hover:border-purple-200 rounded-xl cursor-pointer transition-all flex items-start gap-3 group">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" x-model="form.permissions"
                                            class="mt-0.5 h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 group-hover:text-purple-700 transition-colors">{{ $perm->name }}</p>
                                            <p class="text-[11px] text-gray-500">Permite acceder al módulo {{ str_replace('gestionar-', '', $perm->name) }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" :disabled="loading" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2 disabled:opacity-60">
                                <i class="bi bi-check-lg text-lg" x-show="!loading"></i>
                                <span x-show="!loading">Guardar Cambios</span>
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

    const roleDataServer = @json($role);
    const rolePermissionsDataServer = @json($rolePermissions);
    const allPerms = @json($permissions->pluck('name'));

    function roleEditForm() {
        return {
            form: {
                name: roleDataServer.name || '',
                permissions: rolePermissionsDataServer || []
            },
            errors: {},
            loading: false,

            selectAll() {
                this.form.permissions = [...allPerms];
            },

            unselectAll() {
                this.form.permissions = [];
            },

            async submitForm() {
                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch('{{ url("/admin-roles") }}/' + roleDataServer.id, {
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
                            title: data.message || 'Ocurrió un error al actualizar.'
                        });
                        return;
                    }

                    Toast.fire({
                        icon: 'success',
                        title: 'Rol y permisos actualizados correctamente'
                    });

                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("admin.roles.index") }}';
                    }, 1200);

                } catch (e) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error de red o comunicación.'
                    });
                } finally {
                    this.loading = false;
                }
            }
        };
    }
</script>
@endpush
