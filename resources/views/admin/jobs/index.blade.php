@extends('layouts.app')
@section('title', 'Dashboard - Panel de Usuario')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-[40] lg:hidden" @click="sidebarOpen = false" x-cloak></div>

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
    
                <a href="{{ route('dashboard') }}" 
                @click="if(window.innerWidth < 1024) sidebarOpen = false" 
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-md transition-transform duration-200 {{ request()->routeIs('dashboard') ? 'scale-y-100' : 'scale-y-0' }}"></div>
                    
                    <i class="bi bi-grid-1x2 text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                        Resumen General
                    </span>
                </a>

                <a href="{{ route('admin.trabajos') }}" 
                @click="if(window.innerWidth < 1024) sidebarOpen = false" 
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.trabajos') ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-md transition-transform duration-200 {{ request()->routeIs('admin.trabajos') ? 'scale-y-100' : 'scale-y-0' }}"></div>
                        
                    <i class="bi bi-briefcase text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.trabajos') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }}"></i> 
                    
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                        Gestionar puestos
                    </span>
                </a>

                <a href="{{ route('admin.convocatorias-internas') }}" 
                @click="if(window.innerWidth < 1024) sidebarOpen = false" 
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.convocatorias-internas') ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-md transition-transform duration-200 {{ request()->routeIs('admin.convocatorias-internas') ? 'scale-y-100' : 'scale-y-0' }}"></div>
                        
                    <i class="bi bi-megaphone text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.convocatorias-internas') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                        Gestionar convocatorias
                    </span>
                </a>

                <a href="{{ route('admin.usuarios') }}" 
                @click="if(window.innerWidth < 1024) sidebarOpen = false" 
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.usuarios') ? 'bg-purple-600 text-white shadow-lg shadow-purple-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-white rounded-r-md transition-transform duration-200 {{ request()->routeIs('admin.usuarios') ? 'scale-y-100' : 'scale-y-0' }}"></div>
                        
                    <i class="bi bi-people text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.usuarios') ? 'text-white' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    
                    <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                        Gestionar usuarios
                    </span>
                </a>

</nav>

            <div class="p-4 border-t border-slate-700/50" x-show="sidebarOpen" x-transition.opacity>
                <div class="bg-slate-800 rounded-xl p-4 flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-person-circle text-2xl text-purple-600"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->names }}</p>
                        <p class="text-xs text-slate-400 truncate">En línea</p>
                    </div>
                </div>
            </div>
            
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
        
        {{-- <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight" x-text="pageTitle"></h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600" x-text="pageTitle"></span>
                </div>
            </div>
        </header> --}}

        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                        @yield('dashboard_title', 'Panel de Control')
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">
                        @yield('dashboard_title', 'Panel de Control')
                    </span>
                </div>
            </div>
        </header>

        {{-- <main class="flex-1 p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6 min-h-[1200px]">
                
                <div x-show="currentTab === 'dashboard'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak> 
                    @include('dashboard.partials.dashboard-content') 
                </div>
                
                <div x-show="currentTab === 'profile'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak> 
                    @include('dashboard.partials.profile-content') 
                </div>

                <div x-show="currentTab === 'applications'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak> 
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-briefcase text-4xl text-purple-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Mis Postulaciones</h3>
                        <p class="text-gray-500">Aún no tienes postulaciones activas. ¡Explora las ofertas disponibles!</p>
                    </div>
                </div>
            </div>
        </main> --}}

        <main class="flex-1 p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">
                @yield('dashboard_content')
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    /* Ajustes para el navbar principal de tu app.blade */
    #main-content { 
        padding-top: 64px !important; 
    }
    
    footer { 
        margin-top: 0 !important; 
    }

    /* Scrollbar elegante para el sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardApp', () => ({
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
@endsection