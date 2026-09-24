@extends('layouts.app')

@section('content')
<div class="flex w-full bg-white font-sans text-slate-800 min-h-[calc(100vh-64px)]"
    x-data="{
        readerSidebarOpen: true,
        toggleReaderSidebar() {
            this.readerSidebarOpen = !this.readerSidebarOpen;
        }
    }">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- SIDEBAR: Reader Navigation (Matching Screenshots 2 & 3)             --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <aside 
        class="bg-white border-r border-slate-200/80 flex-shrink-0 transition-all duration-300 ease-in-out fixed lg:relative inset-y-0 left-0 z-40 top-[64px] lg:top-0"
        :class="readerSidebarOpen ? 'w-60 translate-x-0' : 'w-0 -translate-x-full lg:translate-x-0 lg:w-20'">
        
        <div class="flex flex-col h-full lg:h-[calc(100vh-64px)] lg:sticky lg:top-[64px] overflow-hidden p-4">
            
            {{-- Institutional Logo & Label (Matching Screenshot 3) --}}
            <div class="flex items-center gap-3 pb-4">
                <div class="w-10 h-10 rounded-lg p-0.5 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ $enterprise->logo_path ?? asset('images/logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <div class="min-w-0" x-show="readerSidebarOpen" x-transition.opacity>
                    <h2 class="text-xs font-bold text-slate-800 uppercase leading-snug">IESTP FRANCISCO VIGO CABALLERO</h2>
                    <span class="text-[11px] text-slate-400 font-medium">General</span>
                </div>
            </div>

            {{-- Subtle Divider Line (Screenshot 3) --}}
            <div class="h-px bg-slate-200/70 w-full mb-4"></div>

            {{-- Navigation Menu (Matching Screenshot 3) --}}
            <nav class="space-y-1.5 flex-1">
                
                {{-- 1. Inicio (House Icon) --}}
                <a href="{{ route('biblioteca.index') }}"
                    class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden {{ request()->routeIs('biblioteca.index') ? 'bg-blue-50/80 text-blue-900 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    title="Inicio">
                    @if(request()->routeIs('biblioteca.index'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-blue-600 rounded-r-md"></div>
                    @endif
                    <i class="bi bi-house-door-fill text-lg mr-3 flex-shrink-0 {{ request()->routeIs('biblioteca.index') ? 'text-blue-700' : 'text-slate-500 group-hover:text-slate-700' }}"></i>
                    <span class="truncate" x-show="readerSidebarOpen">Inicio</span>
                </a>

                {{-- 2. Buscar (Search Icon) --}}
                <a href="{{ route('biblioteca.search') }}"
                    class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden {{ request()->routeIs('biblioteca.search') ? 'bg-blue-50/80 text-blue-900 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    title="Buscar">
                    @if(request()->routeIs('biblioteca.search'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-blue-600 rounded-r-md"></div>
                    @endif
                    <i class="bi bi-search text-lg mr-3 flex-shrink-0 {{ request()->routeIs('biblioteca.search') ? 'text-blue-700' : 'text-slate-500 group-hover:text-slate-700' }}"></i>
                    <span class="truncate" x-show="readerSidebarOpen">Buscar</span>
                </a>

                {{-- 3. Mi Biblioteca (Screenshot 3 Active State) --}}
                <a href="{{ route('biblioteca.favorites') }}"
                    class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden {{ request()->routeIs('biblioteca.favorites') ? 'bg-blue-50/80 text-blue-900 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                    title="Mi Biblioteca">
                    @if(request()->routeIs('biblioteca.favorites'))
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-blue-600 rounded-r-md"></div>
                    @endif
                    <i class="bi bi-bookmark-heart-fill text-lg mr-3 flex-shrink-0 {{ request()->routeIs('biblioteca.favorites') ? 'text-blue-700' : 'text-slate-500 group-hover:text-slate-700' }}"></i>
                    <span class="truncate" x-show="readerSidebarOpen">Mi Biblioteca</span>
                </a>

            </nav>

            {{-- Optional Admin Shortcut (if user is admin) --}}
            @if(auth()->user() && in_array(auth()->user()->role, ['Admin', 'Administrador', 'Director']))
                <div class="pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.library.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-teal-800 hover:bg-teal-50 transition gap-2">
                        <i class="bi bi-sliders text-sm"></i>
                        <span x-show="readerSidebarOpen">Panel Administrativo</span>
                    </a>
                </div>
            @endif

        </div>
    </aside>

    {{-- Mobile Overlay --}}
    <div x-show="readerSidebarOpen" 
        @click="readerSidebarOpen = false" 
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-30 lg:hidden"
        style="display: none;"></div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- MAIN READER CONTENT AREA                                           --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 bg-white">
        
        {{-- Top Subbar: Breadcrumb / Mobile toggle --}}
        <div class="px-6 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" @click="toggleReaderSidebar()"
                    class="p-1.5 text-slate-500 hover:text-slate-800 rounded-lg hover:bg-slate-100 lg:hidden">
                    <i class="bi bi-list text-xl"></i>
                </button>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <i class="bi bi-book-half text-blue-600"></i>
                    <span>Biblioteca Virtual</span>
                    <span>/</span>
                    <span class="text-slate-800 font-bold">@yield('page_title', 'Inicio')</span>
                </div>
            </div>

            {{-- Top Right User Profile Badge (Screenshot 2: Avatar circle + Email) --}}
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-rose-500 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                    {{ strtoupper(substr(auth()->user()->names ?? 'U', 0, 2)) }}
                </div>
                <div class="hidden sm:block text-right leading-tight">
                    <span class="text-xs font-bold text-slate-800 block">{{ auth()->user()->names }}</span>
                    <span class="text-[11px] text-slate-400 block">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>

        {{-- Dynamic View Body --}}
        <main class="flex-1 p-6 sm:p-8 max-w-7xl w-full mx-auto relative">
            @yield('reader_content')
        </main>

    </div>

    {{-- Floating Question Mark Help Icon (Matching Screenshot 2 bottom right) --}}
    <div class="fixed bottom-5 right-5 z-20">
        <a href="{{ route('mesa-de-partes') }}" 
            title="Ayuda / Consultas sobre la Biblioteca"
            class="w-10 h-10 rounded-full bg-slate-400 hover:bg-slate-600 text-white flex items-center justify-center shadow-md font-bold text-base transition">
            <i class="bi bi-question-lg"></i>
        </a>
    </div>

</div>
@endsection
