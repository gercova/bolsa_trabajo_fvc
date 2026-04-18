@extends('layouts.app')
@section('title', 'Gestión de Socios - Panel Administrativo')
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
                        Gestión de Socios
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Socios</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="userManagement()">
            <div class="max-w-7xl mx-auto space-y-6">
                
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                        <div class="flex items-center">
                            <i class="bi bi-check-circle-fill text-green-500 text-xl flex-shrink-0"></i>
                            <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                            <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.remove()">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <form action="" method="GET" class="w-full sm:w-1/2 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombres, correo o teléfono..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </form>
                    <button class="w-full sm:w-auto bg-purple-600 text-white px-5 py-2 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2 shadow-sm font-medium">
                        <i class="bi bi-plus-lg"></i> Nuevo Usuario
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4">Partner</th>
                                    
                                    <th class="p-4">Estado</th>
                                    <th class="p-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($partners as $partner)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold flex-shrink-0">
                                                {{ strtoupper(substr($partner->company, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $partner->company }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @if($partner->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span> Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" class="text-gray-400 hover:text-purple-600 p-2 rounded-full hover:bg-purple-50 transition-colors">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            
                                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 z-10 py-1" x-cloak>
                                                 
                                                <button @click="openPwdModal({{ $partner->id }}, '{{ $partner->names }}'); open = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 flex items-center">
                                                    <i class="bi bi-key mr-2"></i> Actualizar Clave
                                                </button>

                                                <form action="#" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 flex items-center">
                                                        <i class="bi {{ $partner->is_active ? 'bi-person-dash' : 'bi-person-check' }} mr-2"></i> 
                                                        {{ $partner->is_active ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>

                                                <div class="border-t border-gray-100 my-1"></div>

                                                <form action="#" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de eliminar este ítem?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                        <i class="bi bi-trash mr-2"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                                            <p>No se encontraron socios registrados.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($partners->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $partners->appends(['search' => request('search')])->links() }}
                    </div>
                    @endif
                </div>

                <div x-show="showModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal"  x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="bi bi-shield-lock text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Actualizar Contraseña
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Estableciendo nueva clave para <span class="font-bold text-purple-700" x-text="selectedUserName"></span>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <form :action="'/admin/usuarios/' + selectedUserId + '/password'" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="px-4 py-5 sm:p-6 space-y-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                                        <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm px-3 py-2 border">
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm px-3 py-2 border">
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Guardar Contraseña
                                    </button>
                                    <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Asegúrate de que enterpriseApp exista en caso de que no esté cargado desde aside
        if (!Alpine.data('enterpriseApp')) {
            Alpine.data('enterpriseApp', () => ({
                sidebarOpen: window.innerWidth >= 1024,
                toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
            }));
        }

        Alpine.data('userManagement', () => ({
            showModal: false,
            selectedUserId: null,
            selectedUserName: '',
            
            openPwdModal(id, name) {
                this.selectedUserId = id;
                this.selectedUserName = name;
                this.showModal = true;
            }
        }));
    })
</script>
@endpush

@endsection