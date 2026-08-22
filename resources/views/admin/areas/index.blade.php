@extends('layouts.app')
@section('title', 'Áreas Institucionales - Panel Administrativo')
@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')

        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            {{-- Header --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-diagram-3 text-purple-600"></i> Áreas Institucionales
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Panel
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Áreas</span>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div
                            class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                            </div>
                            <button type="button" class="text-emerald-500 hover:text-emerald-700"
                                onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button type="button" class="text-red-500 hover:text-red-700"
                                onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Stat Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div
                            class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de
                                    Áreas</span>
                                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalAreas }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                        </div>

                        <div
                            class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Áreas
                                    Institucionales</span>
                                <h3 class="text-2xl font-black text-blue-700 mt-1">{{ $generalAreasCount }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>

                        <div
                            class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Por
                                    Programa</span>
                                <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ $withProgramCount }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                        </div>

                        <div
                            class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Con
                                    Responsable</span>
                                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $withLeaderCount }}</h3>
                            </div>
                            <div
                                class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Actions & Filter Bar --}}
                    <div
                        class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col lg:flex-row justify-between items-center gap-4">
                        <form action="{{ route('admin.areas.index') }}" method="GET"
                            class="w-full lg:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                            {{-- Search Input --}}
                            <div class="relative w-full sm:max-w-xs">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar por nombre o descripción..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>

                            {{-- Program Filter --}}
                            <div class="w-full sm:w-auto">
                                <select name="program_id" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Todos los Programas</option>
                                    <option value="general" {{ request('program_id') === 'general' ? 'selected' : '' }}>
                                        General / Institucional</option>
                                    @foreach ($programs as $prog)
                                        <option value="{{ $prog->id }}"
                                            {{ request('program_id') == $prog->id ? 'selected' : '' }}>
                                            {{ $prog->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Responsable Filter --}}
                            <div class="w-full sm:w-auto">
                                <select name="has_user" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Responsable</option>
                                    <option value="yes" {{ request('has_user') === 'yes' ? 'selected' : '' }}>Con
                                        responsable</option>
                                    <option value="no" {{ request('has_user') === 'no' ? 'selected' : '' }}>Sin
                                        responsable</option>
                                </select>
                            </div>

                            {{-- Clear Filters --}}
                            @if (request()->hasAny(['search', 'program_id', 'has_user']))
                                <a href="{{ route('admin.areas.index') }}"
                                    class="px-3.5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all flex items-center gap-1.5 w-full sm:w-auto justify-center">
                                    <i class="bi bi-x-circle text-gray-500"></i> Limpiar
                                </a>
                            @endif
                        </form>

                        {{-- Create Button --}}
                        <a href="{{ route('admin.areas.create') }}"
                            class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all duration-200 flex-shrink-0">
                            <i class="bi bi-plus-lg text-base"></i>
                            <span>Nueva Área</span>
                        </a>
                    </div>

                    {{-- Areas Table --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <th class="py-4 px-6">Área / Dependencia</th>
                                        <th class="py-4 px-6">Programa de Estudio</th>
                                        <th class="py-4 px-6">Responsable</th>
                                        <th class="py-4 px-6">Descripción</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse ($areas as $area)
                                        <tr class="hover:bg-gray-50/70 transition-colors">
                                            {{-- Name --}}
                                            <td class="py-4 px-6 font-semibold text-gray-900">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                                                        <i class="bi bi-diagram-3 text-base"></i>
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="block font-bold text-gray-800">{{ $area->name }}</span>
                                                        <span class="text-xs text-gray-400">ID:
                                                            #{{ $area->id }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Program --}}
                                            <td class="py-4 px-6">
                                                @if ($area->program)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                        <i class="bi bi-mortarboard text-xs"></i>
                                                        {{ $area->program->name }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                        <i class="bi bi-building text-xs"></i>
                                                        Institucional General
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Responsible User --}}
                                            <td class="py-4 px-6">
                                                @if ($area->user)
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                                            {{ strtoupper(substr($area->user->names, 0, 1)) }}
                                                        </div>
                                                        <div class="leading-tight">
                                                            <p class="font-medium text-gray-800 text-xs">
                                                                {{ $area->user->names }} {{ $area->user->last_name1 }}</p>
                                                            <p class="text-[11px] text-gray-400">{{ $area->user->email }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">No asignado</span>
                                                @endif
                                            </td>

                                            {{-- Description --}}
                                            <td class="py-4 px-6 max-w-xs">
                                                @if ($area->description)
                                                    <p class="text-xs text-gray-600 truncate"
                                                        title="{{ $area->description }}">
                                                        {{ $area->description }}
                                                    </p>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sin descripción</span>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="py-4 px-6 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="{{ route('admin.areas.edit', $area) }}"
                                                        class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                                        title="Editar Área">
                                                        <i class="bi bi-pencil-square text-base"></i>
                                                    </a>

                                                    <button type="button"
                                                        @click="confirmDelete('{{ route('admin.areas.destroy', $area) }}', '{{ addslashes($area->name) }}')"
                                                        class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                                        title="Eliminar Área">
                                                        <i class="bi bi-trash3 text-base"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 text-center">
                                                <div class="max-w-sm mx-auto space-y-3">
                                                    <div
                                                        class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto text-2xl">
                                                        <i class="bi bi-diagram-3"></i>
                                                    </div>
                                                    <h4 class="text-base font-bold text-gray-800">No se encontraron áreas
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        @if (request()->hasAny(['search', 'program_id', 'has_user']))
                                                            No hay resultados para los filtros seleccionados. Intenta
                                                            restablecer los filtros.
                                                        @else
                                                            Aún no has registrado ninguna área institucional. Haz clic en
                                                            "Nueva Área" para comenzar.
                                                        @endif
                                                    </p>
                                                    @if (request()->hasAny(['search', 'program_id', 'has_user']))
                                                        <a href="{{ route('admin.areas.index') }}"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-xs rounded-xl transition">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                                                            Filtros
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.areas.create') }}"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl transition shadow-sm">
                                                            <i class="bi bi-plus-lg"></i> Crear Primera Área
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($areas->hasPages())
                            <div
                                class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    Mostrando {{ $areas->firstItem() }} a {{ $areas->lastItem() }} de
                                    {{ $areas->total() }} registros
                                </div>
                                <div>
                                    {{ $areas->links() }}
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </main>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm"
                    @click="showDeleteModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 text-xl">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Eliminar Área</h3>
                            <p class="text-xs text-gray-500 mt-1">¿Estás seguro de que deseas eliminar el área <strong
                                    x-text="deleteName" class="text-gray-800"></strong>? Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" @click="showDeleteModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                            Cancelar
                        </button>

                        <form :action="deleteUrl" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-sm">
                                Sí, Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
