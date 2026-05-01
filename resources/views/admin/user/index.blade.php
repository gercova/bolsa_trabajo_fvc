@extends('layouts.app')
@section('title', 'Gestión de Usuarios - Panel Administrativo')
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
                        Gestión de Usuarios
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Usuarios</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="userManagement()">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Mensaje de éxito -->
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

                <!-- Panel de búsqueda y filtros -->
                <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
                    <!-- Barra de búsqueda principal -->
                    <form action="{{ route('admin.usuarios') }}" method="GET" id="searchForm" class="w-full">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Buscar por nombre, email, DNI o puesto..." 
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm"
                                >
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                @if(request('search'))
                                <button 
                                    type="button" 
                                    onclick="window.location.href='{{ route('admin.usuarios') }}'"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <i class="bi bi-x-circle"></i>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Filtro por estado -->
                            <div class="relative">
                                <select 
                                    name="status" 
                                    class="appearance-none w-full sm:w-40 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white"
                                    onchange="this.form.submit()"
                                >
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                            
                            <!-- Filtro por rol -->
                            <div class="relative">
                                <select 
                                    name="role" 
                                    class="appearance-none w-full sm:w-40 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white"
                                    onchange="this.form.submit()"
                                >
                                    <option value="">Todos los roles</option>
                                    @foreach($roles as $roleItem)
                                    <option value="{{ $roleItem->name }}" {{ request('role') === $roleItem->name ? 'selected' : '' }}>
                                        {{ ucfirst($roleItem->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                            
                            <!-- Items por página -->
                            <div class="relative">
                                <select 
                                    name="per_page" 
                                    class="appearance-none w-full sm:w-32 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white"
                                    onchange="this.form.submit()"
                                >
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 por pág</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 por pág</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 por pág</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 por pág</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <!-- Campos ocultos para mantener el ordenamiento -->
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                    </form>

                    <!-- Resultados y botón de nuevo usuario -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-2">
                        <div class="text-sm text-gray-600">
                            @if($users->total() > 0)
                                Mostrando <span class="font-semibold text-gray-900">{{ $users->firstItem() }}</span> 
                                a <span class="font-semibold text-gray-900">{{ $users->lastItem() }}</span> 
                                de <span class="font-semibold text-gray-900">{{ $users->total() }}</span> usuarios
                                @if(request('search') || request('status') || request('role'))
                                    <button 
                                        onclick="window.location.href='{{ route('admin.usuarios') }}'"
                                        class="ml-2 text-purple-600 hover:text-purple-800 underline"
                                    >
                                        Limpiar filtros
                                    </button>
                                @endif
                            @else
                                No se encontraron usuarios
                                <button 
                                    onclick="window.location.href='{{ route('admin.usuarios') }}'"
                                    class="ml-2 text-purple-600 hover:text-purple-800 underline"
                                >
                                    Limpiar filtros
                                </button>
                            @endif
                        </div>
                        <a href="{{ route('admin.usuarios.create') }}" 
                           class="w-full sm:w-auto bg-purple-600 text-white px-5 py-2.5 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2 shadow-sm font-medium text-sm">
                            <i class="bi bi-plus-lg"></i> Nuevo Usuario
                        </a>
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4">
                                        <a href="{{ route('admin.usuarios', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'names', 'sort_order' => request('sort_by') === 'names' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Usuario
                                            @if(request('sort_by') === 'names')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4">
                                        <a href="{{ route('admin.usuarios', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'dni', 'sort_order' => request('sort_by') === 'dni' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Documento (DNI)
                                            @if(request('sort_by') === 'dni')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4">
                                        <a href="{{ route('admin.usuarios', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'job_position', 'sort_order' => request('sort_by') === 'job_position' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Puesto / Rol
                                            @if(request('sort_by') === 'job_position')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4">
                                        <a href="{{ route('admin.usuarios', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'is_active', 'sort_order' => request('sort_by') === 'is_active' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Estado
                                            @if(request('sort_by') === 'is_active')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($users as $user)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="p-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold flex-shrink-0">
                                                @if($user->photo_profile)
                                                    <img src="{{ Storage::url($user->photo_profile) }}" alt="{{ $user->names }}" class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($user->names, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $user->names }}</p>
                                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-sm text-gray-700 font-mono">{{ $user->dni ?? 'N/A' }}</span>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm text-gray-900 font-medium">{{ $user->job_position ?? 'Sin Puesto' }}</p>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($user->getRoleNames() as $roleName)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ ucfirst($roleName) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <div class="relative inline-block" x-data="{ open: false, posTop: 0, posLeft: 0 }">
                                            <button 
                                                x-ref="trigger" 
                                                type="button" 
                                                @click="open = !open; if(open) { const r = $refs.trigger.getBoundingClientRect(); posTop = r.top + r.height + 6; posLeft = r.left + r.width - 224; }" 
                                                @scroll.window="open = false" 
                                                class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 opacity-0 group-hover:opacity-100 transition-opacity" 
                                                :aria-expanded="open" 
                                                aria-haspopup="true"
                                            >
                                                <i class="bi bi-three-dots-vertical text-lg"></i>
                                            </button>

                                            <template x-teleport="body">
                                                <div 
                                                    x-show="open" 
                                                    @click.outside="open = false" 
                                                    @keydown.escape.window="open = false" 
                                                    x-transition:enter="transition ease-out duration-200" 
                                                    x-transition:enter-start="opacity-0 scale-95" 
                                                    x-transition:enter-end="opacity-100 scale-100" 
                                                    x-transition:leave="transition ease-in duration-150" 
                                                    x-transition:leave-start="opacity-100 scale-100" 
                                                    x-transition:leave-end="opacity-0 scale-95" 
                                                    :style="`top: ${posTop}px; left: ${posLeft}px`" 
                                                    class="fixed z-[100] w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1 ring-1 ring-black/5" 
                                                    role="menu" 
                                                    aria-orientation="vertical" 
                                                    x-cloak
                                                >
                                                    <div class="py-1">
                                                        <!-- Botón Actualizar - NUEVO -->
                                                        <a href="{{ route('admin.usuarios.edit', $user) }}" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 flex items-center transition-colors" role="menuitem">
                                                            <i class="bi bi-pencil-square mr-2.5 text-purple-500"></i> Actualizar Datos
                                                        </a>

                                                        <!-- Botón Cambiar Contraseña -->
                                                        <button type="button" @click="openPwdModal({{ $user->id }}, '{{ addslashes($user->names) }}'); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center transition-colors" role="menuitem">
                                                            <i class="bi bi-key mr-2.5 text-blue-500"></i> Cambiar Contraseña
                                                        </button>

                                                        <!-- Separador -->
                                                        <div class="my-1 border-t border-gray-100"></div>

                                                        <!-- Botón Activar/Desactivar -->
                                                        <form action="{{ route('admin.usuarios.toggle-status', $user) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 flex items-center transition-colors" role="menuitem">
                                                                <i class="bi {{ $user->is_active ? 'bi-person-dash text-red-500' : 'bi-person-check text-green-500' }} mr-2.5"></i> 
                                                                {{ $user->is_active ? 'Desactivar Usuario' : 'Activar Usuario' }}
                                                            </button>
                                                        </form>

                                                        <!-- Separador -->
                                                        <div class="my-1 border-t border-gray-100"></div>

                                                        <!-- Botón Eliminar -->
                                                        <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center transition-colors" role="menuitem">
                                                                <i class="bi bi-trash mr-2.5"></i> Eliminar Usuario
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inbox text-5xl mb-4 text-gray-300"></i>
                                            <p class="text-lg font-medium text-gray-900 mb-1">No se encontraron usuarios</p>
                                            <p class="text-sm">
                                                @if(request('search') || request('status') || request('role'))
                                                    Intenta ajustar los filtros de búsqueda
                                                @else
                                                    Comienza creando tu primer usuario
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación mejorada -->
                    @if($users->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                Página {{ $users->currentPage() }} de {{ $users->lastPage() }}
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Modal de cambio de contraseña (sin cambios) -->
                <div x-show="showModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <!-- ... (mantén el modal como está) ... -->
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showModal = false"></div>

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
        
        /* Estilos de paginación personalizados */
        .pagination {
            display: flex;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .pagination .page-item.active .page-link {
            background-color: #7c3aed;
            color: white;
        }
        .pagination .page-item .page-link:hover {
            background-color: #f3f4f6;
        }
        .pagination .page-item.active .page-link:hover {
            background-color: #6d28d9;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
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