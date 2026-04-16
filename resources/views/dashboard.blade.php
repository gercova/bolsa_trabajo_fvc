@extends('layouts.app')
@section('title', 'Dashboard - Panel de Usuario')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    
    @include('admin.components.aside')

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