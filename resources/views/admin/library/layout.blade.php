@extends('layouts.app')

@section('content')
<div class="flex w-full bg-slate-100/70 font-sans text-slate-800 min-h-[calc(100vh-64px)]"
    x-data="{ 
        adminLibrarySidebarOpen: true,
        showCreateModal: false,
        showEditModal: false,
        isSubmitting: false,
        sourceType: 'file', // 'file' or 'link'
        toggleSidebar() {
            this.adminLibrarySidebarOpen = !this.adminLibrarySidebarOpen;
        }
    }">

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- SIDEBAR: Admin Library Navigation (Screenshot 1: Dark Teal/Slate)   --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <aside 
        class="bg-[#0b4d57] text-white flex-shrink-0 transition-all duration-300 ease-in-out fixed lg:relative inset-y-0 left-0 z-40 top-[64px] lg:top-0 shadow-xl"
        :class="adminLibrarySidebarOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full lg:translate-x-0 lg:w-20'">
        
        <div class="flex flex-col h-full lg:h-[calc(100vh-64px)] lg:sticky lg:top-[64px] overflow-hidden">
            
            {{-- Institutional Brand Header in Sidebar --}}
            <div class="px-5 py-5 border-b border-teal-800/60 flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden" x-show="adminLibrarySidebarOpen" x-transition.opacity>
                    <div class="w-10 h-10 rounded-lg bg-white/10 p-1 flex-shrink-0 flex items-center justify-center border border-white/20">
                        <img src="{{ $enterprise->logo_path ?? asset('images/logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xs font-black tracking-wider text-white uppercase truncate">IESTP "FRANCISCO VIGO CABALLERO"</h2>
                        <span class="text-[10px] text-teal-200 uppercase font-semibold tracking-wider">Biblioteca Admin</span>
                    </div>
                </div>
                <button @click="toggleSidebar()" class="text-teal-200 hover:text-white p-1 rounded-lg hover:bg-white/10 transition lg:hidden">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            {{-- Navigation Items (Matching Screenshot 1) --}}
            <div class="px-3 py-4">
                <span class="text-[10px] font-black uppercase tracking-wider text-teal-200/60 px-3 block mb-2" x-show="adminLibrarySidebarOpen">
                    GENERAL
                </span>

                <nav class="space-y-1">
                    {{-- 1. Libros Asignados --}}
                    <a href="{{ route('admin.library.books.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors gap-3 {{ request()->routeIs('admin.library.index', 'admin.library.books.*') ? 'bg-teal-900/80 text-white font-bold shadow-sm border-l-4 border-teal-300' : 'text-teal-100/80 hover:bg-white/10 hover:text-white' }}"
                        title="Libros Asignados">
                        <i class="bi bi-journal-bookmark-fill text-base flex-shrink-0"></i>
                        <span class="truncate" x-show="adminLibrarySidebarOpen">Libros Asignados</span>
                    </a>

                    {{-- 2. Repositorio --}}
                    <a href="{{ route('admin.library.repository') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors gap-3 {{ request()->routeIs('admin.library.repository') ? 'bg-teal-900/80 text-white font-bold shadow-sm border-l-4 border-teal-300' : 'text-teal-100/80 hover:bg-white/10 hover:text-white' }}"
                        title="Repositorio">
                        <i class="bi bi-folder2-open text-base flex-shrink-0"></i>
                        <span class="truncate" x-show="adminLibrarySidebarOpen">Repositorio</span>
                    </a>

                    {{-- 3. Administradores --}}
                    <a href="{{ route('admin.library.administrators') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors gap-3 {{ request()->routeIs('admin.library.administrators') ? 'bg-teal-900/80 text-white font-bold shadow-sm border-l-4 border-teal-300' : 'text-teal-100/80 hover:bg-white/10 hover:text-white' }}"
                        title="Administradores">
                        <i class="bi bi-people-fill text-base flex-shrink-0"></i>
                        <span class="truncate" x-show="adminLibrarySidebarOpen">Administradores</span>
                    </a>

                    {{-- 4. Lectores (Monitoreo de Docentes / Estudiantes) --}}
                    <a href="{{ route('admin.library.readers') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors gap-3 {{ request()->routeIs('admin.library.readers') ? 'bg-teal-900/80 text-white font-bold shadow-sm border-l-4 border-teal-300' : 'text-teal-100/80 hover:bg-white/10 hover:text-white' }}"
                        title="Lectores">
                        <i class="bi bi-person-video2 text-base flex-shrink-0"></i>
                        <span class="truncate" x-show="adminLibrarySidebarOpen">Lectores</span>
                    </a>

                    {{-- 5. Reportes --}}
                    <a href="{{ route('admin.library.reports') }}"
                        class="flex items-center px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors gap-3 {{ request()->routeIs('admin.library.reports') ? 'bg-teal-900/80 text-white font-bold shadow-sm border-l-4 border-teal-300' : 'text-teal-100/80 hover:bg-white/10 hover:text-white' }}"
                        title="Reportes">
                        <i class="bi bi-flag-fill text-base flex-shrink-0"></i>
                        <span class="truncate" x-show="adminLibrarySidebarOpen">Reportes</span>
                    </a>

                    {{-- 6. Regresar al Panel Principal --}}
                    <div class="pt-4 border-t border-teal-800/60 mt-4">
                        <a href="{{ route('admin.dashboard.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium text-teal-200 hover:bg-white/10 hover:text-white transition-colors gap-3"
                            title="Panel Principal">
                            <i class="bi bi-arrow-left-circle text-base flex-shrink-0"></i>
                            <span class="truncate" x-show="adminLibrarySidebarOpen">Panel General</span>
                        </a>

                        <a href="{{ route('biblioteca.index') }}" target="_blank"
                            class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium text-teal-200 hover:bg-white/10 hover:text-white transition-colors gap-3"
                            title="Ver Portal de Lectura">
                            <i class="bi bi-box-arrow-up-right text-base flex-shrink-0"></i>
                            <span class="truncate" x-show="adminLibrarySidebarOpen">Portal Lectores</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </aside>

    {{-- Mobile Overlay --}}
    <div x-show="adminLibrarySidebarOpen" 
        @click="adminLibrarySidebarOpen = false" 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
        style="display: none;"></div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- MAIN WRAPPER                                                       --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        @yield('library_content')
    </div>

</div>
@endsection
