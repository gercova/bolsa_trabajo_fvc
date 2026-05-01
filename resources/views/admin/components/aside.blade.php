<!-- Overlay para móvil -->
<div 
    x-show="sidebarOpen" 
    x-transition:enter="transition-opacity ease-linear duration-300" 
    x-transition:enter-start="opacity-0" 
    x-transition:enter-end="opacity-100" 
    x-transition:leave="transition-opacity ease-linear duration-300" 
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0" 
    class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-[40] lg:hidden" 
    @click="sidebarOpen = false" 
    x-cloak
></div>

<!-- Sidebar -->
<aside class="bg-slate-900 shadow-2xl transition-all duration-300 ease-in-out flex-shrink-0 fixed lg:relative inset-y-0 left-0 z-[45] lg:z-[40] top-[64px] lg:top-0" :class="sidebarOpen ? 'w-72 translate-x-0' : 'w-0 -translate-x-full lg:translate-x-0 lg:w-20'">
    <div class="flex flex-col h-full lg:h-[calc(100vh-64px)] lg:sticky lg:top-[64px] overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-700/50">
            <div class="flex items-center space-x-3 whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.300ms>
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                    <i class="bi bi-grid-1x2-fill text-sm text-white"></i>
                </div>
                <span class="text-white font-bold text-lg tracking-wide">Panel de Control</span>
            </div>
            <button @click="toggleSidebar()" class="text-slate-400 hover:text-white transition-colors p-1 rounded-md hover:bg-slate-800">
                <i class="bi text-xl" :class="sidebarOpen ? 'bi-chevron-left' : 'bi-list'"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('dashboard') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}"></div>
                <i class="bi bi-grid-1x2 text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Resumen General</span>
                @if(request()->routeIs('dashboard'))
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                @endif
            </a>

            <!-- Gestionar puestos -->
            <a href="{{ route('admin.trabajos') }}" 
               class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.trabajos*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.trabajos*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}"></div>
                <i class="bi bi-briefcase text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.trabajos*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Gestionar puestos</span>
                @if(request()->routeIs('admin.trabajos*'))
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                @endif
            </a>

            <!-- Gestionar convocatorias -->
            <a href="{{ route('admin.convocatorias-internas') }}" 
               class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.convocatorias-internas*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.convocatorias-internas*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}"></div>
                <i class="bi bi-megaphone text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.convocatorias-internas*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Gestionar convocatorias</span>
                @if(request()->routeIs('admin.convocatorias-internas*'))
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                @endif
            </a>

            <!-- Gestionar usuarios -->
            <a href="{{ route('admin.usuarios') }}" 
               class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.usuarios*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.usuarios*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}"></div>
                <i class="bi bi-people text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.usuarios*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Gestionar usuarios</span>
                @if(request()->routeIs('admin.usuarios*'))
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                @endif
            </a>

            <!-- Configurar Empresa -->
            <a href="{{ route('admin.enterprise.edit') }}" 
               class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.enterprise*') ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.enterprise*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}"></div>
                <i class="bi bi-building text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.enterprise*') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Configurar Empresa</span>
                @if(request()->routeIs('admin.enterprise*'))
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-white shadow-lg shadow-white/50"></div>
                @endif
            </a>

            <!-- Partners -->
            <a href="{{ route('admin.partners.index') }}" class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.partners*') ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.enterprise*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}"></div>
                {{--    --}}
                <i class="bi bi-buildings text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.partners*') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Partners</span>
                @if(request()->routeIs('admin.partners*'))
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-white shadow-lg shadow-white/50"></div>
                @endif
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/50" x-show="sidebarOpen" x-transition.opacity>
            <div class="bg-slate-800 rounded-xl p-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-person-circle text-2xl text-white"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? Auth::user()->names }}</p>
                    <p class="text-xs text-slate-400 truncate">Administrador</p>
                </div>
            </div>
        </div>
    </div>
</aside>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('enterpriseApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            },
            init() {
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        this.sidebarOpen = true;
                    } else {
                        this.sidebarOpen = false;
                    }
                });
            }
        }))
    })
</script>
@endpush