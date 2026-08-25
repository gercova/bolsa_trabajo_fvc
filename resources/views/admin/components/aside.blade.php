<!-- Overlay para móvil -->
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-[40] lg:hidden"
    @click="sidebarOpen = false" x-cloak>
</div>

<style>
    /* Custom Aesthetic Scrollbar for Admin Sidebar */
    .sidebar-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        margin: 6px 0;
    }

    .sidebar-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.25);
        border-radius: 20px;
    }

    .sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.4);
    }

    .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #a855f7, #6366f1) !important;
    }

    /* Firefox scrollbar */
    .sidebar-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.25) transparent;
    }
</style>

<!-- Sidebar -->
<aside
    class="bg-slate-900 shadow-2xl transition-all duration-300 ease-in-out flex-shrink-0 fixed lg:relative inset-y-0 left-0 z-[45] lg:z-[40] top-[64px] lg:top-0"
    :class="sidebarOpen ? 'w-72 translate-x-0' : 'w-0 -translate-x-full lg:translate-x-0 lg:w-20'">
    <div class="flex flex-col h-full lg:h-[calc(100vh-64px)] lg:sticky lg:top-[64px] overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-700/50">
            <div class="flex items-center space-x-3 whitespace-nowrap" x-show="sidebarOpen"
                x-transition.opacity.duration.300ms>
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                    <i class="bi bi-grid-1x2-fill text-sm text-white"></i>
                </div>
                <span class="text-white font-bold text-lg tracking-wide">Panel de Control</span>
            </div>
            <button @click="toggleSidebar()"
                class="text-slate-400 hover:text-white transition-colors p-1 rounded-md hover:bg-slate-800">
                <i class="bi text-xl" :class="sidebarOpen ? 'bi-chevron-left' : 'bi-list'"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto sidebar-scrollbar custom-scrollbar">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard.index') }}"
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div
                    class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('dashboard') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                </div>
                <i
                    class="bi bi-grid-1x2 text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Resumen General</span>
                @if (request()->routeIs('dashboard.index'))
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                    </div>
                @endif
            </a>

            <!-- ── GRUPO: Gestionar Calendario ─────────────────────────── -->
            @php $calendarOpen = request()->routeIs('admin.exams.*', 'admin.enrollments.*', 'admin.scholarships.*'); @endphp
            <div x-data="{ open: {{ $calendarOpen ? 'true' : 'false' }} }">

                {{-- Trigger --}}
                <button @click="sidebarOpen ? open = !open : open = true"
                    class="w-full flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                        {{ $calendarOpen ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200
                            {{ $calendarOpen ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                    </div>
                    <i
                        class="bi bi-calendar3 text-xl transition-transform duration-200 group-hover:scale-110
                            {{ $calendarOpen ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    <span class="ml-4 font-medium whitespace-nowrap flex-1 text-left" x-show="sidebarOpen">Gestionar
                        Calendario</span>
                    <i class="bi text-xs ml-auto transition-transform duration-200"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" x-show="sidebarOpen"></i>
                </button>

                {{-- Children --}}
                <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1" x-cloak>

                    {{-- Gestionar Exámenes --}}
                    <a href="{{ route('admin.exams.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.exams.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-journal-text text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.exams.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Exámenes</span>
                        @if (request()->routeIs('admin.exams.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Cronograma de Matrículas --}}
                    <a href="{{ route('admin.enrollments.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.enrollments.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-calendar-check text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.enrollments.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Matrículas</span>
                        @if (request()->routeIs('admin.enrollments.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Gestionar Becas --}}
                    <a href="{{ route('admin.scholarships.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.scholarships.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-award text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.scholarships.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Becas</span>
                        @if (request()->routeIs('admin.scholarships.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>
                </div>
            </div>

            <!-- ── GRUPO: Gestionar Documentación ──────────────────────── -->
            @php $docsOpen = request()->routeIs('admin.tupa.*', 'admin.documents.*'); @endphp
            <div x-data="{ open: {{ $docsOpen ? 'true' : 'false' }} }">

                <button @click="sidebarOpen ? open = !open : open = true"
                    class="w-full flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                        {{ $docsOpen ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200
                            {{ $docsOpen ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                    </div>
                    <i
                        class="bi bi-folder2-open text-xl transition-transform duration-200 group-hover:scale-110
                            {{ $docsOpen ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    <span class="ml-4 font-medium whitespace-nowrap flex-1 text-left" x-show="sidebarOpen">Gestionar
                        Documentos</span>
                    <i class="bi text-xs ml-auto transition-transform duration-200"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" x-show="sidebarOpen"></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1" x-cloak>

                    {{-- Gestionar TUPA --}}
                    <a href="{{ route('admin.tupa.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.tupa.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-file-earmark-text text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.tupa.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">TUPA</span>
                        @if (request()->routeIs('admin.tupa.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Documentos de Gestión --}}
                    <a href="{{ route('admin.documents.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.documents.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-folder-symlink text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.documents.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Documentos</span>
                        @if (request()->routeIs('admin.documents.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>
                </div>
            </div>

            <!-- ── GRUPO: Gestionar Grupos ─────────────────────────────── -->
            @php $groupsOpen = request()->routeIs('admin.users.*', 'admin.teacher-roles.*', 'admin.student-council.*'); @endphp
            <div x-data="{ open: {{ $groupsOpen ? 'true' : 'false' }} }">

                <button @click="sidebarOpen ? open = !open : open = true"
                    class="w-full flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                        {{ $groupsOpen ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200
                            {{ $groupsOpen ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                    </div>
                    <i
                        class="bi bi-people-fill text-xl transition-transform duration-200 group-hover:scale-110
                            {{ $groupsOpen ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    <span class="ml-4 font-medium whitespace-nowrap flex-1 text-left" x-show="sidebarOpen">Gestionar
                        Grupos</span>
                    <i class="bi text-xs ml-auto transition-transform duration-200"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" x-show="sidebarOpen"></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1" x-cloak>

                    {{-- Gestionar Usuarios --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.users.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-people text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.users.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Usuarios</span>
                        @if (request()->routeIs('admin.users.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Plana Docente --}}
                    <a href="{{ route('admin.teacher-roles.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.teacher-roles.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-person-workspace text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.teacher-roles.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Plana Docente</span>
                        @if (request()->routeIs('admin.teacher-roles.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Consejo Estudiantil --}}
                    <a href="{{ route('admin.student-council.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.student-council.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-mortarboard text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.student-council.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Concejo Estudiantil</span>
                        @if (request()->routeIs('admin.student-council.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>
                </div>
            </div>

            <!-- ── GRUPO: Empresa ──────────────────────────────────────── -->
            @php $companyOpen = request()->routeIs('admin.enterprise.*', 'admin.partners.*', 'admin.links.*', 'admin.areas.*', 'admin.history.*'); @endphp
            <div x-data="{ open: {{ $companyOpen ? 'true' : 'false' }} }">
                <button @click="sidebarOpen ? open = !open : open = true"
                    class="w-full flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                        {{ $companyOpen ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200
                            {{ $companyOpen ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                    </div>
                    <i
                        class="bi bi-building text-xl transition-transform duration-200 group-hover:scale-110
                            {{ $companyOpen ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    <span class="ml-4 font-medium whitespace-nowrap flex-1 text-left"
                        x-show="sidebarOpen">Empresa</span>
                    <i class="bi text-xs ml-auto transition-transform duration-200"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" x-show="sidebarOpen"></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1" x-cloak>

                    {{-- Configurar Empresa --}}
                    <a href="{{ route('admin.enterprise.edit') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.enterprise.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-gear-wide-connected text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.enterprise.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Configurar Empresa</span>
                        @if (request()->routeIs('admin.enterprise.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Historia Institucional --}}
                    <a href="{{ route('admin.history.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.history.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-clock-history text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.history.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Historia Institucional</span>
                        @if (request()->routeIs('admin.history.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Gestionar Partners --}}
                    <a href="{{ route('admin.partners.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.partners.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-buildings text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.partners.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Gestionar Partners</span>
                        @if (request()->routeIs('admin.partners.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Enlaces Institucionales --}}
                    <a href="{{ route('admin.links.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.links.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-box-arrow-up-right text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.links.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Enlaces Institucionales</span>
                        @if (request()->routeIs('admin.links.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Áreas Institucionales --}}
                    <a href="{{ route('admin.areas.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.areas.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-diagram-3 text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.areas.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Áreas Institucionales</span>
                        @if (request()->routeIs('admin.areas.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Gestionar programas de estudio -->
            <a href="{{ route('admin.programs.index') }}"
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.programs.*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div
                    class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.programs.*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                </div>
                <i
                    class="bi bi-book text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.programs.*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Gestionar Programas</span>
                @if (request()->routeIs('admin.programs.*'))
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                    </div>
                @endif
            </a>

            <!-- Gestionar Blogs / Noticias -->
            <a href="{{ route('admin.blogs.index') }}"
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.blogs.*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div
                    class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.blogs.*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                </div>
                <i
                    class="bi bi-newspaper text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.blogs.*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Gestionar Blogs</span>
                @if (request()->routeIs('admin.blogs.*'))
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                    </div>
                @endif
            </a>

            <!-- Bolsa de Trabajo -->
            <a href="{{ route('admin.works.index') }}"
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.works.*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div
                    class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.works.*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                </div>
                <i
                    class="bi bi-briefcase text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.works.*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Bolsa de Trabajo</span>
                @if (request()->routeIs('admin.works.*'))
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                    </div>
                @endif
            </a>

            <!-- Gestionar Roles y Permisos -->
            <a href="{{ route('admin.roles.index') }}"
                class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('admin.roles*') ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <div
                    class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200 {{ request()->routeIs('admin.roles*') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                </div>
                <i
                    class="bi bi-shield-lock text-xl transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('admin.roles*') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Roles del sistema</span>
                @if (request()->routeIs('admin.roles*'))
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                    </div>
                @endif
            </a>

            <!-- ── GRUPO: Transparencia ─────────────────────────────────── -->
            @php $transparencyOpen = request()->routeIs('admin.claims.*', 'admin.statistics.*'); @endphp
            <div x-data="{ open: {{ $transparencyOpen ? 'true' : 'false' }} }">
                <button @click="sidebarOpen ? open = !open : open = true"
                    class="w-full flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                        {{ $transparencyOpen ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200
                            {{ $transparencyOpen ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75' }}">
                    </div>
                    <i
                        class="bi bi-shield-check text-xl transition-transform duration-200 group-hover:scale-110
                            {{ $transparencyOpen ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400' }}"></i>
                    <span class="ml-4 font-medium whitespace-nowrap flex-1 text-left"
                        x-show="sidebarOpen">Transparencia</span>
                    <i class="bi text-xs ml-auto transition-transform duration-200"
                        :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" x-show="sidebarOpen"></i>
                </button>

                <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1" x-cloak>

                    {{-- Reclamos --}}
                    <a href="{{ route('admin.claims.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.claims.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-bookmark-x text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.claims.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Reclamos</span>
                        @if (request()->routeIs('admin.claims.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>

                    {{-- Estadísticas --}}
                    <a href="{{ route('admin.statistics.index') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                            {{ request()->routeIs('admin.statistics.*') ? 'bg-purple-600/15 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                        <i
                            class="bi bi-bar-chart-line text-base group-hover:scale-110 transition-transform duration-200
                                {{ request()->routeIs('admin.statistics.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }}"></i>
                        <span class="ml-3 text-sm font-medium whitespace-nowrap">Estadísticas</span>
                        @if (request()->routeIs('admin.statistics.*'))
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50">
                            </div>
                        @endif
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-slate-700/50" x-show="sidebarOpen" x-transition.opacity>
            <div class="bg-slate-800 rounded-xl p-4 flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-person-circle text-2xl text-white"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? Auth::user()->names }}
                    </p>
                    <p class="text-xs text-slate-400 truncate">Administrador</p>
                </div>
            </div>
        </div>
    </div>
</aside>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Cambiamos 'enterpriseApp' por 'dashboardApp' para que coincida con el x-data de tus vistas
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
