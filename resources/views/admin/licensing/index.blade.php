@extends('layouts.app')
@section('title', 'Gestión de Licenciamiento - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="licensingAdminApp()">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-patch-check text-purple-600"></i> Licenciamiento Institucional
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-shield-check mr-1"></i> Transparencia
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600 font-semibold">Licenciamiento</span>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Toast Notifications --}}
                <div class="fixed bottom-5 right-5 z-[100] space-y-3 w-full max-w-sm" x-cloak>
                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'"
                             class="text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between gap-3 border border-white/10">
                            <div class="flex items-center gap-2.5">
                                <i :class="toast.type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'" class="bi text-base"></i>
                                <p class="text-sm font-semibold tracking-wide" x-text="toast.message"></p>
                            </div>
                            <button type="button" @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-white/80 hover:text-white transition-colors p-1">
                                <i class="bi bi-x-lg text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Stats Cards Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Total Fases --}}
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 text-2xl font-bold">
                            <i class="bi bi-layers-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Fases</p>
                            <p class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                    </div>

                    {{-- Etapa Actual (P) --}}
                    <div class="bg-white p-5 rounded-2xl border border-amber-300 shadow-sm flex items-center gap-4 relative overflow-hidden">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-2xl font-bold">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider flex items-center gap-1.5">
                                Etapa Actual <span class="px-1.5 py-0.2 rounded bg-amber-500 text-white font-extrabold text-[10px]">(P)</span>
                            </p>
                            <p class="text-sm sm:text-base font-extrabold text-gray-900 truncate">
                                {{ $stats['current'] ? "Fase {$stats['current']->phase_number}: {$stats['current']->code}" : 'No definida' }}
                            </p>
                        </div>
                    </div>

                    {{-- Fases Culminadas --}}
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl font-bold">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Culminadas</p>
                            <p class="text-2xl font-black text-emerald-600">{{ $stats['completed'] }}</p>
                        </div>
                    </div>

                    {{-- Avance Global --}}
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-2xl font-bold">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Avance Promedio</p>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-black text-sky-600">{{ $stats['avg_progress'] }}%</span>
                                <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-sky-500 h-full rounded-full" style="width: {{ $stats['avg_progress'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions & Filter Bar --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.licensing.index') }}" method="GET"
                        class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                        {{-- Search Input --}}
                        <div class="relative w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por título, código o resolución..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        {{-- Status Filter --}}
                        <div class="w-full sm:w-auto">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Todos los Estados</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En Proceso (P)</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Culminado (C)</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente (PTE)</option>
                                <option value="observed" {{ request('status') === 'observed' ? 'selected' : '' }}>En Observación (OBS)</option>
                            </select>
                        </div>

                        {{-- Active Filter --}}
                        <div class="w-full sm:w-auto">
                            <select name="is_active" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Visibilidad</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Públicos / Activos</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Ocultos / Inactivos</option>
                            </select>
                        </div>

                        {{-- Clear Filters --}}
                        @if (request()->hasAny(['search', 'status', 'is_active']))
                            <a href="{{ route('admin.licensing.index') }}"
                                class="px-3.5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all flex items-center gap-1.5 w-full sm:w-auto justify-center">
                                <i class="bi bi-x-circle text-gray-500"></i> Limpiar
                            </a>
                        @endif
                    </form>

                    {{-- Actions: View Public & Create Button --}}
                    <div class="flex items-center gap-2.5 w-full md:w-auto">
                        <a href="{{ route('licenciamiento') }}" target="_blank"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors flex items-center gap-1.5">
                            <i class="bi bi-eye"></i> Ver Vista Pública
                        </a>

                        <a href="{{ route('admin.licensing.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all duration-200">
                            <i class="bi bi-plus-lg text-base"></i>
                            <span>Nueva Fase / Proceso</span>
                        </a>
                    </div>
                </div>

                {{-- Phases Table --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Fase / Código</th>
                                    <th class="px-6 py-4">Título y Marco Legal</th>
                                    <th class="px-6 py-4">Estado del Proceso</th>
                                    <th class="px-6 py-4 text-center">Etapa Actual (P)</th>
                                    <th class="px-6 py-4">Avance</th>
                                    <th class="px-6 py-4 text-center">Publicado</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                                @forelse ($phases as $phase)
                                    @php
                                        $badge = $phase->status_badge;
                                    @endphp
                                    <tr class="hover:bg-gray-50/60 transition-colors {{ $phase->is_current ? 'bg-amber-50/30' : '' }}">
                                        {{-- Phase Number & Code --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm
                                                    {{ $phase->is_current ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                                    {{ $phase->phase_number }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">Fase {{ $phase->phase_number }}</p>
                                                    <p class="text-xs text-gray-500 font-mono">{{ $phase->code ?? "FASE-0{$phase->phase_number}" }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Title & Legal Basis --}}
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900 max-w-md">{{ $phase->title }}</p>
                                            @if ($phase->resolution_number || $phase->estimated_date)
                                                <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                                    @if ($phase->resolution_number)
                                                        <span class="inline-flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded">
                                                            <i class="bi bi-file-earmark-text"></i> {{ $phase->resolution_number }}
                                                        </span>
                                                    @endif
                                                    @if ($phase->estimated_date)
                                                        <span>&bull; {{ $phase->estimated_date }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Status Badge --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $badge['bg_class'] }}">
                                                <i class="bi {{ $badge['icon'] }}"></i>
                                                {{ $badge['label'] }}
                                            </span>
                                        </td>

                                        {{-- Current Stage (P) Indicator / Action --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($phase->is_current)
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-amber-500 text-slate-950 shadow-sm animate-pulse">
                                                    <i class="bi bi-star-fill text-xs"></i> Actual (P)
                                                </span>
                                            @else
                                                <button type="button" @click="setCurrentStage({{ $phase->id }}, {{ $phase->phase_number }})"
                                                    title="Establecer como la Etapa Actual (P)"
                                                    class="text-xs px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-amber-100 text-gray-600 hover:text-amber-800 font-semibold transition-colors border border-gray-200">
                                                    Fijar (P)
                                                </button>
                                            @endif
                                        </td>

                                        {{-- Progress Bar --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-20 bg-gray-200 h-2 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full {{ $phase->status === 'completed' ? 'bg-emerald-500' : ($phase->is_current ? 'bg-amber-500' : 'bg-purple-500') }}"
                                                        style="width: {{ $phase->progress_percentage }}%"></div>
                                                </div>
                                                <span class="text-xs font-bold text-gray-700">{{ $phase->progress_percentage }}%</span>
                                            </div>
                                        </td>

                                        {{-- Toggle Active Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button type="button" @click="toggleStatus({{ $phase->id }})"
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                                :class="'{{ $phase->is_active }}' === '1' ? 'bg-purple-600' : 'bg-gray-200'">
                                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                    :class="'{{ $phase->is_active }}' === '1' ? 'translate-x-5' : 'translate-x-0'"></span>
                                            </button>
                                        </td>

                                        {{-- Action Buttons --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Edit Button --}}
                                                <a href="{{ route('admin.licensing.edit', $phase) }}"
                                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Editar Fase y Estado">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>

                                                {{-- Delete Button --}}
                                                <button type="button" @click="confirmDelete({{ $phase->id }}, '{{ addslashes($phase->title) }}')"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar Fase">
                                                    <i class="bi bi-trash text-base"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <div class="max-w-sm mx-auto space-y-3">
                                                <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto text-2xl">
                                                    <i class="bi bi-patch-question"></i>
                                                </div>
                                                <p class="text-base font-bold text-gray-700">No se encontraron fases registradas</p>
                                                <p class="text-xs text-gray-500">Comience agregando las fases del proceso de licenciamiento institucional.</p>
                                                <a href="{{ route('admin.licensing.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-semibold">
                                                    <i class="bi bi-plus-lg"></i> Registrar Primera Fase
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($phases->hasPages())
                        <div class="p-4 border-t border-gray-200">
                            {{ $phases->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>

        {{-- Delete Confirmation Modal --}}
        <div x-show="deleteModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-center" @click.away="deleteModalOpen = false">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">¿Eliminar Fase del Licenciamiento?</h3>
                <p class="text-xs sm:text-sm text-gray-600">
                    Está a punto de eliminar la fase <strong class="text-gray-900" x-text="deleteTargetTitle"></strong>. Esta acción no se puede deshacer.
                </p>

                <form :action="deleteActionUrl" method="POST" class="flex gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteModalOpen = false"
                        class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-xl transition-colors shadow-lg shadow-red-600/30">
                        Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('licensingAdminApp', () => ({
            deleteModalOpen: false,
            deleteTargetTitle: '',
            deleteActionUrl: '',
            toasts: [],

            addToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 4000);
            },

            confirmDelete(id, title) {
                this.deleteTargetTitle = title;
                this.deleteActionUrl = `{{ url('/admin-licenciamiento') }}/${id}`;
                this.deleteModalOpen = true;
            },

            async toggleStatus(id) {
                try {
                    const response = await fetch(`{{ url('/admin-licenciamiento/estado') }}/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.addToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 600);
                    }
                } catch (e) {
                    this.addToast('Error al actualizar el estado.', 'error');
                }
            },

            async setCurrentStage(id, phaseNum) {
                try {
                    const response = await fetch(`{{ url('/admin-licenciamiento/etapa-actual') }}/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.addToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 600);
                    }
                } catch (e) {
                    this.addToast('Error al establecer la etapa actual.', 'error');
                }
            }
        }));
    });
</script>
@endpush
