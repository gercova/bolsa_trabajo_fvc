@extends('layouts.app')
@section('title', 'Cronogramas de Matrícula - Panel Administrativo')
@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .paginator-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1.5px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
            transition: all 0.15s;
        }

        .paginator-btn:hover:not(:disabled):not(.active) {
            border-color: #4f46e5;
            color: #4f46e5;
            background: #eef2ff;
        }

        .paginator-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
            font-weight: 800;
        }

        .paginator-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .type-pill-ordinaria {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .type-pill-extraordinaria {
            background: #ede9fe;
            color: #6d28d9;
            border: 1px solid #ddd6fe;
        }
    </style>
@endpush
@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">

            {{-- Sticky Header --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="toggleSidebar()"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                            Cronogramas de Matrícula
                        </h1>
                    </div>
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Inicio
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-indigo-600">Matrículas</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Flash --}}
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                            <div class="flex items-center">
                                <i class="bi bi-check-circle-fill text-green-500 text-xl flex-shrink-0"></i>
                                <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                                <button type="button" class="ml-auto text-green-500 hover:text-green-700"
                                    onclick="this.parentElement.parentElement.remove()">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Stats Bar --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @php
                            $totalActive = $schedules->where('is_active', true)->count();
                            $totalOrd = $schedules->where('enrollment_type', 'ordinaria')->count();
                            $totalExt = $schedules->where('enrollment_type', 'extraordinaria')->count();
                            $totalAll = $schedules->total();
                        @endphp
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
                            <div class="text-2xl font-black text-indigo-600">{{ $totalAll }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-0.5">Total Cronogramas</div>
                        </div>
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
                            <div class="text-2xl font-black text-emerald-600">{{ $totalActive }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-0.5">Activos</div>
                        </div>
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
                            <div class="text-2xl font-black text-blue-600">{{ $totalOrd }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-0.5">Ordinarios</div>
                        </div>
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
                            <div class="text-2xl font-black text-violet-600">{{ $totalExt }}</div>
                            <div class="text-xs text-gray-500 font-semibold mt-0.5">Extraordinarios</div>
                        </div>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                        {{-- Card Header --}}
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                                    <i class="bi bi-calendar2-check text-indigo-600 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-extrabold text-gray-800 tracking-tight">Cronogramas Registrados
                                    </h2>
                                    <p class="text-xs text-gray-500 mt-0.5">Gestiona los períodos de matrícula ordinaria y
                                        extraordinaria.</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.enrollments.create') }}"
                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition shadow-sm shrink-0">
                                <i class="bi bi-plus-lg"></i> Nuevo Cronograma
                            </a>
                        </div>

                        {{-- Search / Filters --}}
                        <form method="GET" action="{{ route('admin.enrollments.index') }}"
                            class="px-5 py-3 border-b border-gray-100 flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-1">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar período u observaciones..."
                                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 bg-slate-50">
                            </div>
                            <select name="type"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 bg-slate-50">
                                <option value="">Todos los tipos</option>
                                <option value="ordinaria" {{ request('type') === 'ordinaria' ? 'selected' : '' }}>Ordinaria
                                </option>
                                <option value="extraordinaria" {{ request('type') === 'extraordinaria' ? 'selected' : '' }}>
                                    Extraordinaria</option>
                            </select>
                            <select name="status"
                                class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 bg-slate-50">
                                <option value="">Todos los estados</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos
                                </option>
                            </select>
                            <button type="submit"
                                class="bg-indigo-600 text-white text-sm font-bold px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                                <i class="bi bi-funnel mr-1"></i> Filtrar
                            </button>
                            @if (request()->hasAny(['search', 'type', 'status']))
                                <a href="{{ route('admin.enrollments.index') }}"
                                    class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 px-3 py-2 rounded-xl border border-gray-200 hover:border-red-200 transition">
                                    <i class="bi bi-x-circle"></i> Limpiar
                                </a>
                            @endif
                        </form>

                        {{-- Table --}}
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm min-w-[700px]">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            Período / Tipo</th>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            Inicio</th>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            Cierre</th>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            Costo (S/)</th>
                                        <th
                                            class="px-5 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            Estado</th>
                                        <th
                                            class="px-5 py-3 text-right text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($schedules as $schedule)
                                        <tr class="hover:bg-indigo-50/30 transition-colors">
                                            <td class="px-5 py-4 text-gray-400 font-mono text-xs">{{ $schedule->id }}</td>
                                            <td class="px-5 py-4">
                                                <div class="font-extrabold text-gray-800">{{ $schedule->academic_period }}
                                                </div>
                                                <span
                                                    class="mt-1 inline-flex items-center text-xs font-bold px-2.5 py-0.5 rounded-full
                                                {{ $schedule->enrollment_type === 'ordinaria' ? 'type-pill-ordinaria' : 'type-pill-extraordinaria' }}">
                                                    {{ $schedule->type_label }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-gray-600 font-semibold">
                                                {{ $schedule->start_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-5 py-4 text-gray-600 font-semibold">
                                                {{ $schedule->end_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-5 py-4 font-extrabold text-gray-800">
                                                S/ {{ number_format($schedule->enrollment_fee, 2) }}
                                            </td>
                                            <td class="px-5 py-4">
                                                <form action="{{ route('admin.enrollments.toggle-status', $schedule) }}"
                                                    method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full transition
                                                    {{ $schedule->is_active
                                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full {{ $schedule->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        {{ $schedule->is_active ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.enrollments.edit', $schedule) }}"
                                                        class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 px-3 py-1.5 rounded-lg transition">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                    <form action="{{ route('admin.enrollments.destroy', $schedule) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('¿Eliminar el cronograma del período {{ $schedule->academic_period }}? Esta acción no se puede revertir.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-100 px-3 py-1.5 rounded-lg transition">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-5 py-16 text-center">
                                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                                    <i class="bi bi-calendar-x text-5xl"></i>
                                                    <p class="font-semibold text-base text-gray-500">No se encontraron
                                                        cronogramas de matrícula.</p>
                                                    <a href="{{ route('admin.enrollments.create') }}"
                                                        class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 mt-2">
                                                        <i class="bi bi-plus-circle"></i> Crear el primer cronograma
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginator --}}
                        @if ($schedules->hasPages())
                            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between gap-4">
                                <p class="text-xs text-gray-500">
                                    Mostrando
                                    <strong>{{ $schedules->firstItem() }}</strong>–<strong>{{ $schedules->lastItem() }}</strong>
                                    de <strong>{{ $schedules->total() }}</strong> cronogramas
                                </p>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <a href="{{ $schedules->previousPageUrl() }}"
                                        class="paginator-btn {{ $schedules->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                                        <i class="bi bi-chevron-left text-xs"></i>
                                    </a>
                                    @foreach ($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
                                        <a href="{{ $url }}"
                                            class="paginator-btn {{ $schedules->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                                    @endforeach
                                    <a href="{{ $schedules->nextPageUrl() }}"
                                        class="paginator-btn {{ !$schedules->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                                        <i class="bi bi-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection
