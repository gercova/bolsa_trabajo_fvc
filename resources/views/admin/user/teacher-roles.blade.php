@extends('layouts.app')
@section('title', 'Plana Docente — Panel Administrativo')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }

    .pagination { display: flex; gap: 4px; }
    .pagination .page-item .page-link { padding: 8px 12px; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
    .pagination .page-item.active .page-link { background-color: #7c3aed; color: white; }
    .pagination .page-item .page-link:hover { background-color: #f3f4f6; }
    .pagination .page-item.active .page-link:hover { background-color: #6d28d9; }
</style>
@endpush

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
    x-data="enterpriseApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="teacherRolesApp()">

        {{-- ── Page Header ── --}}
        <header
            class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()"
                        class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight leading-none">
                            Plana Docente
                        </h1>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Asignaciones de docentes a programas de estudio</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.users.index') }}" class="hover:text-purple-600 transition-colors">
                        <i class="bi bi-house-door mr-1"></i> Usuarios
                    </a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Plana Docente</span>
                </div>
            </div>

            {{-- Sub-nav: tabs linking back to the regular users list and this view --}}
            <div class="px-4 sm:px-6 border-t border-gray-100 bg-gray-50/60 flex items-center gap-1 overflow-x-auto">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-people"></i>
                    Todos los Usuarios
                </a>
                <a href="{{ route('admin.teacher-roles.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-purple-600 text-purple-700 whitespace-nowrap">
                    <i class="bi bi-person-workspace"></i>
                    Plana Docente
                </a>
            </div>
        </header>

        {{-- ── Main Content ── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-circle-fill text-red-500 text-xl"></i>
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                {{-- ── Stats Cards ── --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @php
                        $totalActive       = $details->total();
                        $totalCoordinators = \App\Models\UserRoleDetail::where('is_coordinator', true)->count();
                        $totalPrograms     = \App\Models\StudyProgram::count();
                        $totalTeachers     = \App\Models\UserRoleDetail::distinct('user_id')->count('user_id');
                    @endphp
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-2xl font-black text-purple-700">{{ $totalTeachers }}</p>
                        <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Docentes Asignados</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-2xl font-black text-blue-700">{{ $totalCoordinators }}</p>
                        <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Coordinadores</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-2xl font-black text-emerald-700">{{ $totalPrograms }}</p>
                        <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Programas</p>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-2xl font-black text-orange-600">{{ \App\Models\UserRoleDetail::where('is_active', true)->count() }}</p>
                        <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-wider">Asignaciones Activas</p>
                    </div>
                </div>

                {{-- ── Search & Filter Bar ── --}}
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-200 space-y-4">
                    <form action="{{ route('admin.teacher-roles.index') }}" method="GET" class="w-full">
                        <div class="flex flex-col sm:flex-row gap-3">
                            {{-- Text search --}}
                            <div class="flex-1 relative">
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Buscar por nombre, DNI o puesto..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>

                            {{-- Program filter --}}
                            <div class="relative">
                                <select name="program_id"
                                    class="appearance-none w-full sm:w-52 pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm bg-white"
                                    onchange="this.form.submit()">
                                    <option value="">Todos los programas</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ $programId == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>

                            {{-- Only coordinators toggle --}}
                            <label class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 text-sm font-medium text-gray-700 select-none">
                                <input type="checkbox" name="only_coordinators" value="1"
                                    {{ $onlyCoord ? 'checked' : '' }} onchange="this.form.submit()"
                                    class="w-4 h-4 accent-purple-600 rounded">
                                Solo coordinadores
                            </label>

                            {{-- Submit --}}
                            <button type="submit"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm transition">
                                <i class="bi bi-funnel mr-1"></i> Filtrar
                            </button>
                        </div>
                    </form>

                    <div class="flex items-center justify-between gap-3 pt-1 flex-wrap">
                        <p class="text-sm text-gray-500">
                            <span class="font-bold text-gray-900">{{ $details->total() }}</span> asignaciones encontradas
                            @if($search || $programId || $onlyCoord)
                                — <a href="{{ route('admin.teacher-roles.index') }}"
                                    class="text-purple-600 hover:underline">Limpiar filtros</a>
                            @endif
                        </p>
                        {{-- "Nueva Asignación" button --}}
                        <button @click="openCreateModal()"
                            class="inline-flex items-center gap-2 bg-purple-600 text-white px-5 py-2.5 rounded-lg hover:bg-purple-700 transition shadow-sm font-semibold text-sm">
                            <i class="bi bi-plus-lg"></i> Nueva Asignación
                        </button>
                    </div>
                </div>

                {{-- ── Table ── --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[820px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4">Docente</th>
                                    <th class="p-4">Programa de Estudio</th>
                                    <th class="p-4">Especialidad</th>
                                    <th class="p-4 text-center">Coordinador</th>
                                    <th class="p-4 text-center">Estado</th>
                                    <th class="p-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($details as $detail)
                                    <tr class="hover:bg-gray-50/60 transition-colors group">
                                        {{-- Teacher --}}
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm flex-shrink-0">
                                                    @if($detail->user?->photo_profile)
                                                        <img src="{{ Storage::url($detail->user->photo_profile) }}"
                                                            alt="{{ $detail->user->names }}"
                                                            class="w-9 h-9 rounded-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($detail->user?->names ?? '?', 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $detail->user?->names ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-400">{{ $detail->user?->job_position ?? '—' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Programme --}}
                                        <td class="p-4">
                                            @if($detail->program)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 text-xs font-bold border border-blue-100">
                                                    <i class="bi bi-mortarboard"></i>
                                                    {{ $detail->program->name }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Sin programa asignado</span>
                                            @endif
                                        </td>

                                        {{-- Specialty --}}
                                        <td class="p-4">
                                            <span class="text-sm text-gray-700">{{ $detail->specialty ?? '—' }}</span>
                                        </td>

                                        {{-- Coordinator badge --}}
                                        <td class="p-4 text-center">
                                            @if($detail->is_coordinator)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    <i class="bi bi-star-fill text-amber-500"></i> Coordinador
                                                </span>
                                            @else
                                                <span class="text-gray-300 text-xs">—</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td class="p-4 text-center">
                                            @if($detail->is_active)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="p-4 text-center">
                                            <div class="relative inline-block" x-data="{ open: false, posTop: 0, posLeft: 0 }">
                                                <button x-ref="trigger" type="button"
                                                    @click="open = !open; if(open) { const r = $refs.trigger.getBoundingClientRect(); posTop = r.top + r.height + 6; posLeft = r.left + r.width - 224; }"
                                                    @scroll.window="open = false"
                                                    class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors opacity-0 group-hover:opacity-100">
                                                    <i class="bi bi-three-dots-vertical text-lg"></i>
                                                </button>

                                                <template x-teleport="body">
                                                    <div x-show="open" @click.outside="open = false"
                                                        @keydown.escape.window="open = false"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 scale-95"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-150"
                                                        x-transition:leave-start="opacity-100 scale-100"
                                                        x-transition:leave-end="opacity-0 scale-95"
                                                        :style="`top: ${posTop}px; left: ${posLeft}px`"
                                                        class="fixed z-[100] w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1"
                                                        x-cloak>
                                                        {{-- Edit --}}
                                                        <button type="button"
                                                            @click="openEditModal({{ $detail->id }}, {{ $detail->user_id }}, {{ $detail->program_id ?? 'null' }}, '{{ addslashes($detail->specialty ?? '') }}', {{ $detail->is_coordinator ? 'true' : 'false' }}, {{ $detail->is_active ? 'true' : 'false' }}); open = false"
                                                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 flex items-center transition-colors">
                                                            <i class="bi bi-pencil-square mr-2.5 text-purple-500"></i> Editar
                                                        </button>

                                                        <div class="my-1 border-t border-gray-100"></div>

                                                        {{-- Toggle status --}}
                                                        <form action="{{ route('admin.teacher-roles.toggle-status', $detail) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 flex items-center transition-colors">
                                                                <i class="bi {{ $detail->is_active ? 'bi-eye-slash text-red-500' : 'bi-eye text-green-500' }} mr-2.5"></i>
                                                                {{ $detail->is_active ? 'Desactivar' : 'Activar' }}
                                                            </button>
                                                        </form>

                                                        <div class="my-1 border-t border-gray-100"></div>

                                                        {{-- Delete --}}
                                                        <form action="{{ route('admin.teacher-roles.destroy', $detail) }}" method="POST" class="m-0"
                                                            onsubmit="return confirm('¿Eliminar esta asignación? Esta acción no se puede deshacer.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center transition-colors">
                                                                <i class="bi bi-trash mr-2.5"></i> Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-14 text-center text-gray-500">
                                            <div class="flex flex-col items-center gap-3">
                                                <i class="bi bi-person-workspace text-5xl text-gray-200"></i>
                                                <p class="font-semibold text-gray-800">No se encontraron asignaciones</p>
                                                <p class="text-sm">
                                                    @if($search || $programId || $onlyCoord)
                                                        Ajusta los filtros de búsqueda
                                                    @else
                                                        Comienza asignando un docente a un programa
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($details->hasPages())
                        <div class="p-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <p class="text-sm text-gray-600">
                                Página {{ $details->currentPage() }} de {{ $details->lastPage() }}
                            </p>
                            {{ $details->links() }}
                        </div>
                    @endif
                </div>

                {{-- ═══════════════════════════════════════════════════════════════ --}}
                {{-- PROGRAMMES OVERVIEW SECTION --}}
                {{-- ═══════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-extrabold text-gray-800 mb-5 flex items-center gap-2.5">
                        <span class="w-1.5 h-7 bg-purple-600 rounded-full"></span>
                        Resumen por Programa de Estudio
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($programs as $prog)
                            @php
                                $progDetails    = \App\Models\UserRoleDetail::with('user')
                                                    ->where('program_id', $prog->id)
                                                    ->where('is_active', true)
                                                    ->get();
                                $coordinator    = $progDetails->firstWhere('is_coordinator', true);
                                $teacherCount   = $progDetails->count();
                            @endphp
                            <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md transition duration-200">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $prog->name }}</h3>
                                    <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-xs font-bold
                                        {{ $teacherCount > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $teacherCount }} doc.
                                    </span>
                                </div>
                                @if($coordinator)
                                    <div class="flex items-center gap-2 mb-3 p-2.5 bg-amber-50 rounded-lg border border-amber-100">
                                        <i class="bi bi-star-fill text-amber-500 text-xs"></i>
                                        <div>
                                            <p class="text-xs font-bold text-amber-900 leading-none">Coordinador</p>
                                            <p class="text-xs text-amber-700 mt-0.5">{{ $coordinator->user?->names ?? '—' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3 p-2.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <p class="text-xs text-gray-400 italic">Sin coordinador asignado</p>
                                    </div>
                                @endif
                                @if($teacherCount > 0)
                                    <ul class="space-y-1.5">
                                        @foreach($progDetails->take(4) as $d)
                                            <li class="flex items-center gap-2 text-xs text-gray-700">
                                                <span class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-[10px] flex-shrink-0">
                                                    {{ strtoupper(substr($d->user?->names ?? '?', 0, 1)) }}
                                                </span>
                                                {{ $d->user?->names ?? 'N/A' }}
                                                @if($d->is_coordinator)
                                                    <i class="bi bi-star-fill text-amber-400 text-[10px]"></i>
                                                @endif
                                            </li>
                                        @endforeach
                                        @if($teacherCount > 4)
                                            <li class="text-xs text-gray-400 pl-7">
                                                + {{ $teacherCount - 4 }} más
                                            </li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-xs text-gray-400 italic">Sin docentes activos</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>{{-- /.max-w-7xl --}}
        </main>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- CREATE / EDIT MODAL (inside teacherRolesApp scope) --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}    
    <div x-show="showModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-modal="true" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            {{-- Backdrop --}}
            <div x-show="showModal" @click="closeModal()"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            {{-- Panel --}}
            <div x-show="showModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl z-10">

                {{-- Header --}}
                <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="bi bi-person-workspace text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900" x-text="isEditing ? 'Editar Asignación' : 'Nueva Asignación'"></h3>
                            <p class="text-xs text-gray-400">Asigna un docente a un programa de estudio</p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form :action="isEditing ? `/admin-docentes-roles/${editId}` : '{{ route('admin.teacher-roles.store') }}'"
                      method="POST" class="px-6 py-5 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" x-bind:value="isEditing ? 'PUT' : 'POST'">

                    {{-- Teacher select --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Docente <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" x-model="form.user_id" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white">
                            <option value="">— Seleccionar docente —</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->names }}
                                    @if($teacher->job_position) — {{ $teacher->job_position }} @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">
                            Solo se listan usuarios con el rol de <strong>Docente</strong>.
                            <a href="{{ route('admin.users.index', ['role' => 'Docente']) }}"
                               class="text-purple-600 hover:underline" target="_blank">Gestionar usuarios →</a>
                        </p>
                    </div>

                    {{-- Programme select --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Programa de Estudio
                        </label>
                        <select name="program_id" x-model="form.program_id"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white">
                            <option value="">— Sin programa (personal general) —</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Specialty --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Especialidad / Área de Docencia
                        </label>
                        <input type="text" name="specialty" x-model="form.specialty"
                            placeholder="Ej: Matemáticas, Producción Animal, Redes..."
                            maxlength="255"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                    </div>

                    {{-- Toggles row --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Is coordinator --}}
                        <label class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100 cursor-pointer hover:bg-amber-100/60 transition">
                            <input type="checkbox" name="is_coordinator" value="1" x-model="form.is_coordinator"
                                class="w-4 h-4 accent-amber-500 rounded mt-0.5">
                            <div>
                                <p class="text-sm font-semibold text-amber-900">Coordinador de Programa</p>
                                <p class="text-xs text-amber-700 mt-0.5">Este docente es coordinador del programa seleccionado.</p>
                            </div>
                        </label>

                        {{-- Is active --}}
                        <label class="flex items-start gap-3 p-4 bg-green-50 rounded-xl border border-green-100 cursor-pointer hover:bg-green-100/60 transition">
                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active"
                                class="w-4 h-4 accent-green-500 rounded mt-0.5">
                            <div>
                                <p class="text-sm font-semibold text-green-900">Asignación Activa</p>
                                <p class="text-xs text-green-700 mt-0.5">Visible en la vista pública de la plana docente.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Footer actions --}}
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <button type="button" @click="closeModal()"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition shadow-sm">
                            <i class="bi bi-save mr-1.5"></i>
                            <span x-text="isEditing ? 'Guardar cambios' : 'Crear asignación'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>{{-- /.modal (x-show="showModal") --}}
    </div>{{-- /.flex-1 (x-data="teacherRolesApp()") --}}

</div>{{-- /#dashboard-container (x-data="enterpriseApp()") --}}
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    if (!Alpine.data('enterpriseApp')) {
        Alpine.data('enterpriseApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
        }));
    }

    Alpine.data('teacherRolesApp', () => ({
        showModal:  false,
        isEditing:  false,
        editId:     null,
        form: {
            user_id:        '',
            program_id:     '',
            specialty:      '',
            is_coordinator: false,
            is_active:      true,
        },

        openCreateModal() {
            this.isEditing  = false;
            this.editId     = null;
            this.form = { user_id: '', program_id: '', specialty: '', is_coordinator: false, is_active: true };
            this.showModal  = true;
        },

        openEditModal(id, userId, programId, specialty, isCoordinator, isActive) {
            this.isEditing          = true;
            this.editId             = id;
            this.form.user_id       = String(userId);
            this.form.program_id    = programId ? String(programId) : '';
            this.form.specialty     = specialty;
            this.form.is_coordinator = isCoordinator;
            this.form.is_active     = isActive;
            this.showModal          = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }));
});
</script>
@endpush
