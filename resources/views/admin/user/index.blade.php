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

                <!-- Errores / Observaciones de Importación Masiva -->
                @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-sm animate-fade-in" x-data="{ openDetails: false }">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle-fill text-amber-500 text-xl flex-shrink-0 mt-0.5"></i>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-amber-800">
                                Observaciones de Importación ({{ count(session('import_errors')) }} fila(s) con alertas o ignoradas)
                            </h3>
                            <p class="text-xs text-amber-700 mt-0.5">
                                Algunas filas no pudieron importarse debido a formato de DNI, puesto/rol inválido o correo duplicado.
                            </p>
                            <div class="mt-2">
                                <button type="button" @click="openDetails = !openDetails" class="text-xs font-bold text-amber-800 underline hover:text-amber-900 flex items-center gap-1">
                                    <span x-show="!openDetails">Ver detalle de observaciones ({{ count(session('import_errors')) }})</span>
                                    <span x-show="openDetails" x-cloak>Ocultar detalle de observaciones</span>
                                    <i class="bi bi-chevron-down text-[10px]" :class="openDetails ? 'rotate-180' : ''"></i>
                                </button>
                                <ul x-show="openDetails" x-cloak class="mt-2 text-xs text-amber-900 bg-white/80 p-3 rounded-lg border border-amber-200 divide-y divide-amber-100 max-h-48 overflow-y-auto space-y-1">
                                    @foreach(session('import_errors') as $error)
                                        <li class="pt-1 first:pt-0 flex items-start gap-1.5">
                                            <i class="bi bi-dot text-amber-500 text-sm -mt-0.5"></i>
                                            <span>{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="ml-auto text-amber-500 hover:text-amber-700 p-1" onclick="this.closest('.bg-amber-50').remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <i class="bi bi-x-circle-fill text-red-500 text-xl flex-shrink-0"></i>
                        <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
                        <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Panel de búsqueda y filtros -->
                <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
                    <!-- Barra de búsqueda principal -->
                    <form action="{{ route('admin.users.index') }}" method="GET" id="searchForm" class="w-full">
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
                                    onclick="window.location.href='{{ route('admin.users.index') }}'"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <i class="bi bi-x-circle"></i>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Filtro por estado -->
                            <div class="relative">
                                <select name="status" class="appearance-none w-full sm:w-40 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white" onchange="this.form.submit()">
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
                                        onclick="window.location.href='{{ route('admin.users.index') }}'"
                                        class="ml-2 text-purple-600 hover:text-purple-800 underline"
                                    >
                                        Limpiar filtros
                                    </button>
                                @endif
                            @else
                                No se encontraron usuarios
                                <button 
                                    onclick="window.location.href='{{ route('admin.users.index') }}'"
                                    class="ml-2 text-purple-600 hover:text-purple-800 underline"
                                >
                                    Limpiar filtros
                                </button>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            <button type="button" 
                                    @click="openImportModal()" 
                                    class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-purple-50 hover:border-purple-300 hover:text-purple-700 transition flex items-center justify-center gap-2 shadow-sm font-medium text-sm">
                                <i class="bi bi-file-earmark-arrow-up text-purple-600"></i> Importar Excel / CSV
                            </button>
                            <a href="{{ route('admin.users.create') }}" 
                               class="w-full sm:w-auto bg-purple-600 text-white px-5 py-2.5 rounded-lg hover:bg-purple-700 transition flex items-center justify-center gap-2 shadow-sm font-medium text-sm">
                                <i class="bi bi-plus-lg"></i> Nuevo Usuario
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4">
                                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'names', 'sort_order' => request('sort_by') === 'names' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Usuario
                                            @if(request('sort_by') === 'names')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4">
                                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'dni', 'sort_order' => request('sort_by') === 'dni' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Documento (DNI)
                                            @if(request('sort_by') === 'dni')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4">
                                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'job_position', 'sort_order' => request('sort_by') === 'job_position' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
                                           class="flex items-center gap-1 hover:text-purple-600 transition-colors">
                                            Puesto / Rol
                                            @if(request('sort_by') === 'job_position')
                                                <i class="bi bi-caret-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}-fill text-xs"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-4">
                                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'is_active', 'sort_order' => request('sort_by') === 'is_active' && request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}"
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
                                                        <a href="{{ route('admin.users.edit', $user) }}" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 flex items-center transition-colors" role="menuitem">
                                                            <i class="bi bi-pencil-square mr-2.5 text-purple-500"></i> Actualizar Datos
                                                        </a>

                                                        <!-- Botón Cambiar Contraseña -->
                                                        <button type="button" @click="openPwdModal({{ $user->id }}, '{{ addslashes($user->names) }}'); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center transition-colors" role="menuitem">
                                                            <i class="bi bi-key mr-2.5 text-blue-500"></i> Cambiar Contraseña
                                                        </button>

                                                        <!-- Separador -->
                                                        <div class="my-1 border-t border-gray-100"></div>

                                                        <!-- Botón Activar/Desactivar -->
                                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="m-0">
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
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
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

                <!-- Modal de Importación Masiva de Usuarios -->
                <div x-show="importModalOpen" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

                    <div @click.outside="importModalOpen = false"
                        @keydown.escape.window="importModalOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden flex flex-col max-h-[92vh]">

                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-700 via-indigo-700 to-purple-800 text-white shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-md text-white flex items-center justify-center text-2xl shadow-inner border border-white/20">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-extrabold tracking-tight">Importación Masiva de Usuarios</h3>
                                        <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-purple-400/30 text-purple-100 border border-purple-300/30">
                                            XLSX • XLS • CSV
                                        </span>
                                    </div>
                                    <p class="text-xs text-purple-100/90">Estudiantes, egresados, docentes y administrativos con reglas institucionales</p>
                                </div>
                            </div>
                            <button type="button" @click="importModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition-colors">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        {{-- Navigation Tabs --}}
                        <div class="flex items-center gap-2 px-6 pt-3 border-b border-gray-200 bg-gray-50/80 shrink-0">
                            <button type="button" @click="importTab = 'upload'"
                                class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                                :class="importTab === 'upload' ? 'border-purple-600 text-purple-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                                <i class="bi bi-cloud-arrow-up text-sm"></i>
                                1. Cargar Archivo y Plantillas
                            </button>
                            <button type="button" @click="importTab = 'guide'"
                                class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                                :class="importTab === 'guide' ? 'border-purple-600 text-purple-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                                <i class="bi bi-card-checklist text-sm"></i>
                                2. Instrucciones y Reglas Oficiales
                                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                    Normativa
                                </span>
                            </button>
                            <button type="button" @click="importTab = 'examples'"
                                class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                                :class="importTab === 'examples' ? 'border-purple-600 text-purple-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                                <i class="bi bi-table text-sm"></i>
                                3. Ejemplos de Registros
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="p-6 space-y-5 overflow-y-auto flex-1 bg-white">

                            {{-- ══ TAB 1: UPLOAD & DOWNLOAD TEMPLATES ════════════════ --}}
                            <div x-show="importTab === 'upload'" class="space-y-5">

                                {{-- Download Template Banner --}}
                                <div class="relative overflow-hidden p-5 bg-gradient-to-br from-purple-50/90 via-indigo-50/50 to-blue-50/60 border border-purple-100 rounded-2xl shadow-sm">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-wider text-purple-700 bg-purple-100/80 px-2.5 py-0.5 rounded-full border border-purple-200">
                                                    <i class="bi bi-file-earmark-check"></i> Plantilla Oficial Formateada
                                                </span>
                                                <span class="text-xs text-gray-500 font-medium">Formatos: <strong>.xlsx</strong> o <strong>.csv</strong></span>
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-900">¿Primera vez o deseas evitar errores de importación?</h4>
                                            <p class="text-xs text-gray-600 max-w-xl">
                                                Descarga nuestra plantilla oficial con cabeceras validadas, ejemplos ilustrativos y hoja de especificaciones. Todos los usuarios importados se configuran con <strong>DNI [1]</strong>, <strong>job_position</strong> normado, correo institucional automático y contraseña inicial predeterminada.
                                            </p>
                                        </div>

                                        {{-- Download Action Buttons --}}
                                        <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                                            <a href="{{ route('admin.users.template', ['format' => 'xlsx']) }}"
                                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition hover:shadow flex-1 sm:flex-none">
                                                <i class="bi bi-file-earmark-excel-fill text-base"></i>
                                                <span>Descargar Excel (.xlsx)</span>
                                                <span class="text-[10px] bg-purple-500/50 px-1.5 py-0.5 rounded-md font-semibold text-purple-100">Recomendado</span>
                                            </a>
                                            <a href="{{ route('admin.users.template', ['format' => 'csv']) }}"
                                                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold shadow-sm transition flex-1 sm:flex-none">
                                                <i class="bi bi-filetype-csv text-base text-gray-500"></i>
                                                <span>Descargar CSV (.csv)</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Specification Highlights Cards --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    <div class="p-3 bg-purple-50/60 rounded-xl border border-purple-100">
                                        <div class="flex items-center gap-2 text-purple-700 font-bold text-xs mb-1">
                                            <i class="bi bi-card-text"></i>
                                            <span>Documento</span>
                                        </div>
                                        <p class="text-[11px] text-gray-600">
                                            Siempre <strong>DNI [1]</strong>. Se validan exactamente 8 dígitos numéricos peruanos.
                                        </p>
                                    </div>

                                    <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100">
                                        <div class="flex items-center gap-2 text-indigo-700 font-bold text-xs mb-1">
                                            <i class="bi bi-briefcase"></i>
                                            <span>Puesto (job_position)</span>
                                        </div>
                                        <p class="text-[11px] text-gray-600">
                                            Debe ser estrictamente <strong>student/graduate</strong> o <strong>teaching/administrative staff</strong>.
                                        </p>
                                    </div>

                                    <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100">
                                        <div class="flex items-center gap-2 text-emerald-700 font-bold text-xs mb-1">
                                            <i class="bi bi-envelope-at"></i>
                                            <span>Correo Institucional</span>
                                        </div>
                                        <p class="text-[11px] text-gray-600">
                                            Si se omite: <code>[inicial][paterno][inicial_mat]@iestpfvc.edu.pe</code> automático.
                                        </p>
                                    </div>

                                    <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-100">
                                        <div class="flex items-center gap-2 text-amber-700 font-bold text-xs mb-1">
                                            <i class="bi bi-key"></i>
                                            <span>Contraseña</span>
                                        </div>
                                        <p class="text-[11px] text-gray-600">
                                            Se asigna fija y segura a <strong><code>P4$$w0rd*</code></strong> para todos los importados.
                                        </p>
                                    </div>
                                </div>

                                {{-- Upload Form --}}
                                <form id="import-users-form"
                                    action="{{ route('admin.users.import') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    @submit="isImporting = true"
                                    class="space-y-4">
                                    @csrf

                                    {{-- Drag & Drop Zone --}}
                                    <div class="relative">
                                        <label for="users-import-file"
                                            class="flex flex-col items-center justify-center w-full border-2 border-dashed rounded-2xl cursor-pointer transition-all p-6"
                                            :class="fileName ? 'border-purple-500 bg-purple-50/40' : 'border-gray-300 bg-gray-50/50 hover:border-purple-400 hover:bg-purple-50/20'"
                                            @dragover.prevent
                                            @drop.prevent="
                                                const f = $event.dataTransfer.files[0];
                                                if (f) { fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                                            ">
                                            <div class="flex flex-col items-center justify-center text-center space-y-2">
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl transition"
                                                    :class="fileName ? 'bg-purple-600 text-white' : 'bg-purple-100 text-purple-600'">
                                                    <i :class="fileName ? 'bi bi-file-earmark-check' : 'bi bi-cloud-arrow-up'"></i>
                                                </div>
                                                <div class="space-y-0.5">
                                                    <p class="text-sm font-bold text-gray-800" x-show="!fileName">
                                                        Arrastra tu archivo aquí o <span class="text-purple-600 hover:underline">explora tus carpetas</span>
                                                    </p>
                                                    <p class="text-sm font-extrabold text-purple-800" x-show="fileName" x-cloak>
                                                        Archivo seleccionado: <span x-text="fileName" class="font-mono text-xs bg-purple-100 px-2 py-0.5 rounded"></span>
                                                    </p>
                                                    <p class="text-[11px] text-gray-500">
                                                        Formatos compatibles: <strong>.xlsx</strong>, <strong>.xls</strong>, <strong>.csv</strong> (Máx. 10 MB)
                                                    </p>
                                                </div>
                                            </div>
                                            <input id="users-import-file"
                                                x-ref="fileInput"
                                                type="file"
                                                name="file"
                                                accept=".xlsx,.xls,.csv"
                                                class="hidden"
                                                @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                                required>
                                        </label>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex items-center justify-between pt-2">
                                        <button type="button" @click="importTab = 'guide'" class="text-xs text-purple-600 hover:text-purple-800 font-semibold flex items-center gap-1">
                                            <i class="bi bi-question-circle"></i> Ver especificaciones y reglas
                                        </button>
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="importModalOpen = false" class="px-4 py-2 text-xs font-bold text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition">
                                                Cancelar
                                            </button>
                                            <button type="submit"
                                                :disabled="!fileName || isImporting"
                                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span x-show="!isImporting" class="flex items-center gap-1.5">
                                                    <i class="bi bi-upload"></i>
                                                    <span>Procesar Importación</span>
                                                </span>
                                                <span x-show="isImporting" x-cloak class="flex items-center gap-1.5">
                                                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                    </svg>
                                                    <span>Procesando registros...</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            {{-- ══ TAB 2: INSTRUCTIONS & RULES ════════════════════════ --}}
                            <div x-show="importTab === 'guide'" x-cloak class="space-y-4">
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                        <i class="bi bi-info-circle text-purple-600"></i>
                                        Reglas y Especificaciones del Proceso de Importación
                                    </h4>
                                    <ul class="text-xs text-gray-700 space-y-2 list-disc list-inside">
                                        <li><strong>Tipo de Documento:</strong> Siempre se asigna <code>document_type_id = 1</code> (DNI). No es necesario especificar otro tipo de documento.</li>
                                        <li><strong>DNI:</strong> Debe contener exactamente 8 dígitos numéricos válidos. Si el usuario ya existe con ese DNI, se actualizarán sus datos y se mantendrá o actualizará su estado.</li>
                                        <li><strong>Puesto / Rol (job_position):</strong> Solo se admiten los valores normados:
                                            <div class="mt-1 ml-4 space-y-1">
                                                <span class="inline-block px-2 py-0.5 rounded bg-purple-100 text-purple-800 font-mono text-[11px] font-bold">student/graduate</span> (para alumnos regulares y egresados).
                                                <br>
                                                <span class="inline-block px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 font-mono text-[11px] font-bold">teaching/administrative staff</span> (para plana docente y personal administrativo).
                                            </div>
                                        </li>
                                        <li><strong>Correo Electrónico (email):</strong> Opcional en el archivo. Si la celda está vacía, el sistema autogenera el correo con la fórmula:
                                            <div class="mt-1 ml-4 p-2 bg-purple-50 rounded border border-purple-200 font-mono text-[11px] text-purple-900">
                                                [1ª letra del nombre] + [apellido paterno completo] + [1ª letra del apellido materno] + @iestpfvc.edu.pe
                                            </div>
                                            <span class="text-gray-500 text-[11px] ml-4 block">Ejemplo: Juan Pérez Gómez &rarr; <code>jperezg@iestpfvc.edu.pe</code>. Si existe homonimia, se agrega sufijo correlativo.</span>
                                        </li>
                                        <li><strong>Contraseña (password):</strong> Por seguridad y estándar institucional, se asigna automáticamente la contraseña inicial <strong><code>P4$$w0rd*</code></strong>. No requiere columna de clave en el archivo.</li>
                                        <li><strong>Rol en el Sistema (role):</strong> Puede indicar <code>Estudiante</code>, <code>Docente</code>, <code>Administrativo</code> o <code>Egresado</code>. Si se omite, se asigna acorde a la condición (Estudiante para student/graduate, Docente para personal).</li>
                                    </ul>
                                </div>

                                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                                    <table class="w-full text-left text-xs border-collapse">
                                        <thead class="bg-gray-100 text-gray-700 font-bold border-b border-gray-200">
                                            <tr>
                                                <th class="p-3">Columna</th>
                                                <th class="p-3">Campo Interno</th>
                                                <th class="p-3">Obligatorio</th>
                                                <th class="p-3">Regla / Formato</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-gray-600">
                                            <tr>
                                                <td class="p-3 font-semibold text-gray-900">A (o correlativo)</td>
                                                <td class="p-3 font-mono text-[11px]">N°</td>
                                                <td class="p-3 text-gray-400">Opcional</td>
                                                <td class="p-3">Número de orden</td>
                                            </tr>
                                            <tr class="bg-purple-50/30">
                                                <td class="p-3 font-semibold text-gray-900">B</td>
                                                <td class="p-3 font-mono text-[11px] text-purple-700">DNI</td>
                                                <td class="p-3 text-emerald-600 font-bold">Sí</td>
                                                <td class="p-3 font-medium text-gray-900">8 dígitos numéricos. Se guarda con document_type_id = 1.</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold text-gray-900">C</td>
                                                <td class="p-3 font-mono text-[11px]">Nombres</td>
                                                <td class="p-3 text-emerald-600 font-bold">Sí</td>
                                                <td class="p-3">Nombres del usuario</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold text-gray-900">D</td>
                                                <td class="p-3 font-mono text-[11px]">Apellido Paterno</td>
                                                <td class="p-3 text-emerald-600 font-bold">Sí</td>
                                                <td class="p-3">Primer apellido</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold text-gray-900">E</td>
                                                <td class="p-3 font-mono text-[11px]">Apellido Materno</td>
                                                <td class="p-3 text-gray-400">Opcional</td>
                                                <td class="p-3">Segundo apellido</td>
                                            </tr>
                                            <tr class="bg-indigo-50/30">
                                                <td class="p-3 font-semibold text-gray-900">F</td>
                                                <td class="p-3 font-mono text-[11px] text-indigo-700">job_position</td>
                                                <td class="p-3 text-emerald-600 font-bold">Sí</td>
                                                <td class="p-3 font-medium text-gray-900"><strong>student/graduate</strong> o <strong>teaching/administrative staff</strong></td>
                                            </tr>
                                            <tr class="bg-emerald-50/30">
                                                <td class="p-3 font-semibold text-gray-900">G</td>
                                                <td class="p-3 font-mono text-[11px] text-emerald-700">email</td>
                                                <td class="p-3 text-gray-400">Opcional</td>
                                                <td class="p-3">Si está vacío, autogenerado a <code>@iestpfvc.edu.pe</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold text-gray-900">H</td>
                                                <td class="p-3 font-mono text-[11px]">Rol</td>
                                                <td class="p-3 text-gray-400">Opcional</td>
                                                <td class="p-3">Estudiante, Docente, Administrativo, Egresado</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-semibold text-gray-900">I</td>
                                                <td class="p-3 font-mono text-[11px]">Teléfono</td>
                                                <td class="p-3 text-gray-400">Opcional</td>
                                                <td class="p-3">Número de contacto (9 dígitos)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- ══ TAB 3: REAL-WORLD EXAMPLES ═════════════════════════ --}}
                            <div x-show="importTab === 'examples'" x-cloak class="space-y-4">
                                <p class="text-xs text-gray-600">
                                    A continuación se muestran ejemplos reales de cómo estructurar los datos antes de guardarlos en formato <strong>.xlsx</strong> o <strong>.csv</strong>:
                                </p>

                                <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                                    <table class="w-full text-left text-xs border-collapse">
                                        <thead class="bg-purple-700 text-white font-semibold">
                                            <tr>
                                                <th class="p-2.5">DNI</th>
                                                <th class="p-2.5">Nombres</th>
                                                <th class="p-2.5">Ap. Paterno</th>
                                                <th class="p-2.5">Ap. Materno</th>
                                                <th class="p-2.5">job_position</th>
                                                <th class="p-2.5">email</th>
                                                <th class="p-2.5">Rol</th>
                                                <th class="p-2.5">Resultado de Correo y Clave</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-gray-700">
                                            <tr class="hover:bg-purple-50/20">
                                                <td class="p-2.5 font-mono font-bold text-purple-900">71234567</td>
                                                <td class="p-2.5 font-medium">Juan Carlos</td>
                                                <td class="p-2.5">Pérez</td>
                                                <td class="p-2.5">Gómez</td>
                                                <td class="p-2.5"><span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 font-mono text-[10px] font-bold">student/graduate</span></td>
                                                <td class="p-2.5 text-gray-400 italic">(vacío)</td>
                                                <td class="p-2.5">Estudiante</td>
                                                <td class="p-2.5">
                                                    <span class="font-mono text-[11px] text-emerald-700 font-bold">jperezg@iestpfvc.edu.pe</span>
                                                    <br><span class="text-[10px] text-gray-500">Clave: P4$$w0rd*</span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-indigo-50/20">
                                                <td class="p-2.5 font-mono font-bold text-indigo-900">45678901</td>
                                                <td class="p-2.5 font-medium">María Elena</td>
                                                <td class="p-2.5">Ramos</td>
                                                <td class="p-2.5">Castillo</td>
                                                <td class="p-2.5"><span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 font-mono text-[10px] font-bold">teaching/administrative staff</span></td>
                                                <td class="p-2.5 text-gray-400 italic">(vacío)</td>
                                                <td class="p-2.5">Docente</td>
                                                <td class="p-2.5">
                                                    <span class="font-mono text-[11px] text-emerald-700 font-bold">mramosc@iestpfvc.edu.pe</span>
                                                    <br><span class="text-[10px] text-gray-500">Clave: P4$$w0rd*</span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-purple-50/20">
                                                <td class="p-2.5 font-mono font-bold text-purple-900">78901234</td>
                                                <td class="p-2.5 font-medium">Luis Alberto</td>
                                                <td class="p-2.5">Vásquez</td>
                                                <td class="p-2.5">Torres</td>
                                                <td class="p-2.5"><span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 font-mono text-[10px] font-bold">student/graduate</span></td>
                                                <td class="p-2.5 font-mono text-[11px] text-blue-700">lvasquezt_personal@gmail.com</td>
                                                <td class="p-2.5">Estudiante</td>
                                                <td class="p-2.5">
                                                    <span class="font-mono text-[11px] text-blue-800 font-bold">lvasquezt_personal@gmail.com</span>
                                                    <br><span class="text-[10px] text-gray-500">Clave: P4$$w0rd*</span>
                                                </td>
                                            </tr>
                                            <tr class="hover:bg-indigo-50/20">
                                                <td class="p-2.5 font-mono font-bold text-indigo-900">41239876</td>
                                                <td class="p-2.5 font-medium">Rosa Aurora</td>
                                                <td class="p-2.5">Mendoza</td>
                                                <td class="p-2.5">Flores</td>
                                                <td class="p-2.5"><span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 font-mono text-[10px] font-bold">teaching/administrative staff</span></td>
                                                <td class="p-2.5 text-gray-400 italic">(vacío)</td>
                                                <td class="p-2.5">Administrativo</td>
                                                <td class="p-2.5">
                                                    <span class="font-mono text-[11px] text-emerald-700 font-bold">rmendozaf@iestpfvc.edu.pe</span>
                                                    <br><span class="text-[10px] text-gray-500">Clave: P4$$w0rd*</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

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
            
            importModalOpen: false,
            importTab: 'upload',
            fileName: '',
            isImporting: false,

            openImportModal() {
                this.importModalOpen = true;
                this.importTab = 'upload';
                this.fileName = '';
                this.isImporting = false;
            },
            
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