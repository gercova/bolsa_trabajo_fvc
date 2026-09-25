@extends('layouts.app')
@section('title', 'Gestión de Bolsa de Trabajo - Panel Administrativo')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="{ sidebarOpen: true }">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-briefcase text-purple-600"></i> Gestión de Bolsa de Trabajo
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Panel
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Ofertas Laborales</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700 p-1" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-xl"></i>
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-rose-500 hover:text-rose-700 p-1" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Header Actions & Summary --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Ofertas Laborales y Convocatorias</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Administra las oportunidades de empleo e inserción laboral publicadas en el portal.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        {{-- Clear all table button --}}
                        <button id="btn-clear-table" type="button" onclick="confirmClearAll()"
                            @if(($totalJobs ?? 0) === 0) disabled @endif
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-sm rounded-xl transition-all gap-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed hover:shadow cursor-pointer"
                            title="Eliminar permanentemente todos los registros de la tabla">
                            <i class="bi bi-trash3-fill text-base text-rose-600"></i>
                            <span>Vaciar Tabla</span>
                        </button>

                        {{-- Auto-Fetch button --}}
                        <button id="btn-fetch-jobs" type="button" onclick="runAutoFetch()"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-500/20 hover:from-emerald-700 hover:to-teal-700 transition-all gap-2">
                            <i class="bi bi-cloud-download-fill text-lg"></i>
                            <span>Buscar Automáticamente</span>
                        </button>

                        <a href="{{ route('admin.works.create') }}" 
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 hover:from-purple-700 hover:to-indigo-700 transition-all gap-2">
                            <i class="bi bi-plus-circle-fill text-lg"></i>
                            <span>Nueva Oferta</span>
                        </a>
                    </div>
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                                <i class="bi bi-briefcase-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900">{{ $totalJobs ?? 0 }}</p>
                                <p class="text-xs text-gray-500 font-medium">Total Ofertas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="bi bi-check-circle-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900">{{ $activeJobs ?? 0 }}</p>
                                <p class="text-xs text-gray-500 font-medium">Activas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                                <i class="bi bi-eye-slash-fill text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900">{{ $inactiveJobs ?? 0 }}</p>
                                <p class="text-xs text-gray-500 font-medium">Inactivas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                <i class="bi bi-building-check text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xl font-black text-gray-900">{{ $internalJobs ?? 0 }}</p>
                                <p class="text-xs text-gray-500 font-medium">Convocatorias Internas</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search & Filters Bar --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
                    <form action="{{ route('admin.works.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 items-center">
                        
                        {{-- Search Input --}}
                        <div class="md:col-span-5 relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar por título, empresa o ubicación..." 
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        {{-- Status Filter --}}
                        <div class="md:col-span-3">
                            <select name="status" onchange="this.form.submit()" 
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Todos los Estados</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        {{-- Source Filter --}}
                        <div class="md:col-span-2">
                            <input type="text" name="source" value="{{ request('source') }}" 
                                placeholder="Fuente (ej. Interna)" 
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                        </div>

                        {{-- Actions --}}
                        <div class="md:col-span-2 flex items-center gap-2">
                            <button type="submit" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                            @if(request('search') || request('status') !== null || request('source'))
                                <a href="{{ route('admin.works.index') }}" class="px-3 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-semibold rounded-xl transition-colors" title="Limpiar Filtros">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>

                    </form>
                </div>

                {{-- Bulk Action Floating Bar --}}
                <div id="bulk-action-bar" class="hidden bg-slate-900 text-white p-4 rounded-2xl shadow-xl border border-slate-800 flex flex-wrap items-center justify-between gap-4 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-600/30 border border-purple-500/30 text-purple-300 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-check2-square"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white flex items-center gap-2">
                                <span id="selected-count-badge" class="px-2.5 py-0.5 rounded-full bg-purple-600 text-white text-xs font-black">0</span>
                                <span id="selected-count-label">ofertas seleccionadas</span>
                            </p>
                            <p class="text-xs text-slate-400" id="selection-scope-desc">Registros marcados para eliminación</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button type="button" onclick="deselectAll()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition cursor-pointer">
                            <i class="bi bi-x-circle mr-1"></i> Desmarcar todo
                        </button>
                        <button type="button" onclick="confirmBulkDelete()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-rose-600/30 hover:scale-105 active:scale-95 cursor-pointer">
                            <i class="bi bi-trash-fill text-sm"></i>
                            <span>Eliminar Seleccionados</span>
                        </button>
                    </div>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Pagination Scope Banner (Gmail-style across all pages) --}}
                    @if($jobs->total() > $jobs->count())
                        <div id="select-all-pages-banner" class="hidden bg-purple-50 border-b border-purple-200 px-6 py-3 text-center text-xs text-purple-900 font-semibold transition-all">
                            <span id="banner-msg-partial">
                                Has seleccionado los <strong>{{ $jobs->count() }}</strong> registros de esta página.
                            </span>
                            <button type="button" id="btn-select-all-scope" onclick="selectAllMatchingAcrossPages()" class="font-bold underline text-purple-700 hover:text-purple-950 ml-1.5 cursor-pointer">
                                ¿Deseas seleccionar las {{ $jobs->total() }} ofertas laborales de todas las páginas?
                            </button>
                            <span id="banner-msg-all" class="hidden">
                                <i class="bi bi-check-all text-purple-700 text-sm mr-1"></i>
                                Se han seleccionado <strong>todas las {{ $jobs->total() }} ofertas laborales</strong> coincidentes de todas las páginas.
                                <button type="button" onclick="deselectAll()" class="font-bold underline text-purple-700 hover:text-purple-950 ml-2 cursor-pointer">
                                    Desmarcar todo
                                </button>
                            </span>
                        </div>
                    @endif

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[750px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4 w-12 text-center">
                                        <input type="checkbox" id="selectAllPageCheckbox" onchange="toggleSelectAllPage(this)"
                                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 transition cursor-pointer"
                                            title="Seleccionar todas las ofertas de esta página">
                                    </th>
                                    <th class="p-4">Puesto & Empresa</th>
                                    <th class="p-4">Ubicación</th>
                                    <th class="p-4">Fuente / Convocatoria</th>
                                    <th class="p-4 text-center w-28">Estado</th>
                                    <th class="p-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                                @forelse($jobs as $job)
                                    <tr class="hover:bg-purple-50/30 transition-colors" data-job-id="{{ $job->id }}">
                                        {{-- Checkbox --}}
                                        <td class="p-4 text-center">
                                            <input type="checkbox" class="job-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 transition cursor-pointer"
                                                value="{{ $job->id }}"
                                                onchange="onRowCheckboxChange(this)">
                                        </td>
                                        {{-- Job Title & Company --}}
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-purple-100 border border-purple-200 text-purple-700 flex items-center justify-center text-lg font-bold shrink-0 shadow-sm">
                                                    <i class="bi bi-briefcase"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('admin.works.edit', $job) }}" class="font-bold text-gray-900 hover:text-purple-600 transition-colors line-clamp-1">
                                                        {{ $job->title }}
                                                    </a>
                                                    <p class="text-xs text-purple-600 font-semibold line-clamp-1 mt-0.5">
                                                        {{ $job->company }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Location --}}
                                        <td class="p-4 text-gray-600">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200">
                                                <i class="bi bi-geo-alt-fill text-emerald-600"></i>
                                                <span>{{ $job->location ?? 'No especificada' }}</span>
                                            </span>
                                        </td>

                                        {{-- Source --}}
                                        <td class="p-4">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold border {{ Str::contains(mb_strtolower($job->source), 'interna') ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                                <i class="bi {{ Str::contains(mb_strtolower($job->source), 'interna') ? 'bi-star-fill text-blue-500' : 'bi-globe' }}"></i>
                                                <span>{{ $job->source ?? 'Bolsa Institucional' }}</span>
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="p-4 text-center">
                                            <form action="{{ route('admin.works.toggle-status', $job) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm {{ $job->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $job->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                    {{ $job->is_active ? 'Activa' : 'Inactiva' }}
                                                </button>
                                            </form>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if(!empty($job->url) && $job->url !== '#')
                                                    <a href="{{ $job->url }}" target="_blank" rel="noopener noreferrer" 
                                                       class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                                                       title="Ver enlace externo">
                                                        <i class="bi bi-box-arrow-up-right text-base"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('admin.works.edit', $job) }}" 
                                                   class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors" 
                                                   title="Editar oferta">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>

                                                <form action="{{ route('admin.works.destroy', $job) }}" method="POST" 
                                                      onsubmit="return confirm('¿Está seguro de eliminar la oferta laboral «{{ $job->title }}»?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" 
                                                        title="Eliminar oferta">
                                                        <i class="bi bi-trash text-base"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-12 text-center text-gray-500">
                                            <div class="max-w-sm mx-auto space-y-3">
                                                <i class="bi bi-briefcase text-4xl text-gray-300"></i>
                                                <p class="text-base font-bold text-gray-700">No se encontraron ofertas laborales</p>
                                                <p class="text-xs text-gray-500">No hay convocatorias registradas o ninguna coincide con los filtros aplicados.</p>
                                                <a href="{{ route('admin.works.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition">
                                                    Publicar Primera Oferta
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Server-Side Pagination --}}
                    @if($jobs->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/**
 * Auto-fetch: calls POST /admin-trabajos/buscar-automatico
 * Shows a live SweetAlert2 progress dialog while the server scrapes
 * Computrabajo PE and Bumeran PE for the 5 study-program keyword groups.
 */
async function runAutoFetch() {
    const btn = document.getElementById('btn-fetch-jobs');

    // Confirm before starting (can take ~60–90 s)
    const confirm = await Swal.fire({
        title: '¿Iniciar búsqueda automática?',
        html: `
            <p class="text-sm text-gray-600 mt-1">Se realizará una búsqueda en <strong>Computrabajo Perú</strong> y <strong>Bumeran Perú</strong> para las 5 especialidades técnicas del instituto.</p>
            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 text-left">
                <i class="bi bi-info-circle-fill mr-1"></i>
                Este proceso puede tardar <strong>60 – 120 segundos</strong> dependiendo de la red. Las ofertas encontradas se guardarán automáticamente.
            </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-cloud-download mr-1"></i> Sí, buscar ahora',
        cancelButtonText: 'Cancelar',
        width: '36rem',
    });

    if (!confirm.isConfirmed) return;

    // Show live progress dialog
    Swal.fire({
        title: 'Buscando ofertas laborales…',
        html: `
            <div class="space-y-4 text-sm text-gray-700">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin h-6 w-6 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="fetch-status">Conectando a los portales de empleo…</span>
                </div>

                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div id="fetch-bar" class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: 5%"></div>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-emerald-50 p-2 rounded-lg border border-emerald-200">
                        <p class="text-xl font-black text-emerald-700" id="count-saved">—</p>
                        <p class="text-[10px] text-gray-500 font-semibold uppercase">Nuevas</p>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg border border-blue-200">
                        <p class="text-xl font-black text-blue-700" id="count-updated">—</p>
                        <p class="text-[10px] text-gray-500 font-semibold uppercase">Actualizadas</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg border border-gray-200">
                        <p class="text-xl font-black text-gray-700" id="count-skipped">—</p>
                        <p class="text-[10px] text-gray-500 font-semibold uppercase">Sin cambios</p>
                    </div>
                </div>

                <div id="fetch-sources" class="text-left text-xs text-gray-500 space-y-1 hidden">
                    <p class="font-bold text-gray-600 text-xs">Portales consultados:</p>
                    <div class="flex gap-3">
                        <span class="flex items-center gap-1"><span id="dot-computrabajo" class="w-2 h-2 bg-gray-300 rounded-full inline-block"></span> Computrabajo PE</span>
                        <span class="flex items-center gap-1"><span id="dot-bumeran" class="w-2 h-2 bg-gray-300 rounded-full inline-block"></span> Bumeran PE</span>
                    </div>
                </div>
            </div>`,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        width: '36rem',
        didOpen: () => {
            // Animate progress bar slowly while waiting
            let progress = 5;
            const bar = document.getElementById('fetch-bar');
            const status = document.getElementById('fetch-status');
            const sources = document.getElementById('fetch-sources');
            const dotC = document.getElementById('dot-computrabajo');
            const dotB = document.getElementById('dot-bumeran');

            sources.classList.remove('hidden');

            const ticker = setInterval(() => {
                progress = Math.min(progress + (Math.random() * 4), 85);
                if (bar) bar.style.width = progress + '%';

                if (progress > 15) { dotC.className = 'w-2 h-2 bg-amber-400 rounded-full inline-block animate-pulse'; }
                if (progress > 45) {
                    dotC.className = 'w-2 h-2 bg-emerald-500 rounded-full inline-block';
                    dotB.className = 'w-2 h-2 bg-amber-400 rounded-full inline-block animate-pulse';
                    status.textContent = 'Consultando Bumeran Perú…';
                }
                if (progress > 70) {
                    dotB.className = 'w-2 h-2 bg-emerald-500 rounded-full inline-block';
                    status.textContent = 'Procesando y guardando resultados…';
                }
            }, 1800);

            // Disable the trigger button
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            // Start the actual fetch request
            fetch('{{ route("admin.works.fetch-jobs") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                clearInterval(ticker);
                bar.style.width = '100%';

                // Populate counters
                document.getElementById('count-saved').textContent    = data.saved    ?? 0;
                document.getElementById('count-updated').textContent  = data.updated  ?? 0;
                document.getElementById('count-skipped').textContent  = data.skipped  ?? 0;

                const hasResults = (data.saved ?? 0) + (data.updated ?? 0) > 0;
                const errHtml    = data.errors?.length
                    ? `<div class="mt-3 p-2 bg-amber-50 border border-amber-200 rounded text-xs text-amber-800 text-left"><b>Advertencias (${data.errors.length}):</b><br>${data.errors.slice(0,3).join('<br>')}</div>`
                    : '';

                setTimeout(() => {
                    Swal.fire({
                        title: hasResults ? '¡Búsqueda completada! ✅' : 'Búsqueda finalizada',
                        html: `<p class="text-sm text-gray-700">${data.message ?? ''}</p>${errHtml}`,
                        icon: hasResults ? 'success' : 'info',
                        confirmButtonColor: '#7c3aed',
                        confirmButtonText: 'Ver Resultados',
                        width: '34rem',
                    }).then(() => { window.location.reload(); });
                }, 400);
            })
            .catch(err => {
                clearInterval(ticker);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo completar la búsqueda automática. Verifique su conexión a internet o intente nuevamente.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                });
                console.error('fetchJobs error:', err);
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        }
    });
}

/**
 * =====================================================================
 * BULK ACTIONS & CLEAR TABLE MANAGEMENT WITH PAGINATION SUPPORT
 * =====================================================================
 */
const TOTAL_MATCHING_JOBS = {{ (int) $jobs->total() }};
const PAGE_JOBS_COUNT     = {{ (int) $jobs->count() }};
const TOTAL_ALL_JOBS      = {{ (int) ($totalJobs ?? 0) }};

const CURRENT_SEARCH = @json(request('search', ''));
const CURRENT_STATUS = @json(request('status', ''));
const CURRENT_SOURCE = @json(request('source', ''));

const BULK_DELETE_URL = '{{ route("admin.works.bulk-delete") }}';
const CLEAR_ALL_URL   = '{{ route("admin.works.clear-all") }}';

const STORAGE_KEY_IDS         = 'fvc_selected_job_offers';
const STORAGE_KEY_SELECT_ALL  = 'fvc_selected_job_offers_all_pages';

function getStoredIds() {
    try {
        const stored = sessionStorage.getItem(STORAGE_KEY_IDS);
        return stored ? JSON.parse(stored) : [];
    } catch (e) {
        return [];
    }
}

function setStoredIds(ids) {
    sessionStorage.setItem(STORAGE_KEY_IDS, JSON.stringify(ids));
}

function isSelectAllAcrossPagesActive() {
    return sessionStorage.getItem(STORAGE_KEY_SELECT_ALL) === 'true';
}

function setSelectAllAcrossPagesActive(value) {
    if (value) {
        sessionStorage.setItem(STORAGE_KEY_SELECT_ALL, 'true');
    } else {
        sessionStorage.removeItem(STORAGE_KEY_SELECT_ALL);
    }
}

function clearStoredState() {
    sessionStorage.removeItem(STORAGE_KEY_IDS);
    sessionStorage.removeItem(STORAGE_KEY_SELECT_ALL);
}

/**
 * Synchronizes the UI elements (master checkbox, row checkboxes,
 * floating bulk action bar, and multi-page banner) with state.
 */
function syncUI() {
    const rowCheckboxes  = Array.from(document.querySelectorAll('.job-checkbox'));
    const masterCheckbox = document.getElementById('selectAllPageCheckbox');
    const bulkActionBar  = document.getElementById('bulk-action-bar');
    const badge          = document.getElementById('selected-count-badge');
    const label          = document.getElementById('selected-count-label');
    const scopeDesc      = document.getElementById('selection-scope-desc');
    const banner         = document.getElementById('select-all-pages-banner');
    const bannerPartial  = document.getElementById('banner-msg-partial');
    const bannerAll      = document.getElementById('banner-msg-all');

    if (rowCheckboxes.length === 0) {
        if (bulkActionBar) bulkActionBar.classList.add('hidden');
        if (banner) banner.classList.add('hidden');
        return;
    }

    const selectAllPages = isSelectAllAcrossPagesActive();
    const storedIds      = getStoredIds();

    if (selectAllPages) {
        // All matching records across all pages are selected
        rowCheckboxes.forEach(cb => { cb.checked = true; });
        if (masterCheckbox) {
            masterCheckbox.checked = true;
            masterCheckbox.indeterminate = false;
        }

        if (bulkActionBar) {
            bulkActionBar.classList.remove('hidden');
            if (badge) badge.textContent = TOTAL_MATCHING_JOBS;
            if (label) label.textContent = 'todas las ofertas seleccionadas';
            if (scopeDesc) scopeDesc.textContent = `Se eliminarán todas las ${TOTAL_MATCHING_JOBS} ofertas coincidentes de todas las páginas`;
        }

        if (banner) {
            banner.classList.remove('hidden');
            if (bannerPartial) bannerPartial.classList.add('hidden');
            if (bannerAll) bannerAll.classList.remove('hidden');
        }
    } else {
        // Individual selections (handles pagination persistence via storedIds)
        let checkedOnPageCount = 0;
        rowCheckboxes.forEach(cb => {
            const valNum = parseInt(cb.value, 10);
            const isChecked = storedIds.includes(valNum) || storedIds.includes(cb.value);
            cb.checked = isChecked;
            if (isChecked) checkedOnPageCount++;
        });

        const totalSelectedCount = storedIds.length;

        if (masterCheckbox) {
            if (checkedOnPageCount === 0) {
                masterCheckbox.checked = false;
                masterCheckbox.indeterminate = false;
            } else if (checkedOnPageCount === rowCheckboxes.length) {
                masterCheckbox.checked = true;
                masterCheckbox.indeterminate = false;
            } else {
                masterCheckbox.checked = false;
                masterCheckbox.indeterminate = true;
            }
        }

        if (totalSelectedCount > 0) {
            if (bulkActionBar) {
                bulkActionBar.classList.remove('hidden');
                if (badge) badge.textContent = totalSelectedCount;
                if (label) label.textContent = totalSelectedCount === 1 ? 'oferta seleccionada' : 'ofertas seleccionadas';
                if (scopeDesc) {
                    if (totalSelectedCount > checkedOnPageCount) {
                        scopeDesc.textContent = `${checkedOnPageCount} en esta página, ${totalSelectedCount - checkedOnPageCount} en otras páginas`;
                    } else {
                        scopeDesc.textContent = 'Registros marcados para eliminación';
                    }
                }
            }
        } else {
            if (bulkActionBar) bulkActionBar.classList.add('hidden');
        }

        // Show banner only if all items on current page are checked AND total matching records exceed page count
        if (banner) {
            if (checkedOnPageCount === rowCheckboxes.length && rowCheckboxes.length > 0 && TOTAL_MATCHING_JOBS > rowCheckboxes.length) {
                banner.classList.remove('hidden');
                if (bannerPartial) bannerPartial.classList.remove('hidden');
                if (bannerAll) bannerAll.classList.add('hidden');
            } else {
                banner.classList.add('hidden');
            }
        }
    }
}

/**
 * Master checkbox toggle for the current page.
 */
function toggleSelectAllPage(master) {
    const rowCheckboxes = Array.from(document.querySelectorAll('.job-checkbox'));
    let stored = getStoredIds();

    if (master.checked) {
        rowCheckboxes.forEach(cb => {
            const id = parseInt(cb.value, 10);
            if (!stored.includes(id)) {
                stored.push(id);
            }
        });
        setStoredIds(stored);
        setSelectAllAcrossPagesActive(false);
    } else {
        const pageIds = rowCheckboxes.map(cb => parseInt(cb.value, 10));
        stored = stored.filter(id => !pageIds.includes(id));
        setStoredIds(stored);
        setSelectAllAcrossPagesActive(false);
    }

    syncUI();
}

/**
 * Handle individual row checkbox toggle.
 */
function onRowCheckboxChange(cb) {
    let stored = getStoredIds();
    const id = parseInt(cb.value, 10);

    if (isSelectAllAcrossPagesActive()) {
        // Disengage all-pages mode and fall back to explicitly checked items
        setSelectAllAcrossPagesActive(false);
        const rowCheckboxes = Array.from(document.querySelectorAll('.job-checkbox'));
        stored = rowCheckboxes.filter(c => c.checked).map(c => parseInt(c.value, 10));
        setStoredIds(stored);
    } else {
        if (cb.checked) {
            if (!stored.includes(id)) stored.push(id);
        } else {
            stored = stored.filter(item => item !== id);
        }
        setStoredIds(stored);
    }

    syncUI();
}

/**
 * Select all matching records across all pages.
 */
function selectAllMatchingAcrossPages() {
    setSelectAllAcrossPagesActive(true);
    syncUI();
}

/**
 * Deselect all selected items.
 */
function deselectAll() {
    clearStoredState();
    syncUI();
}

/**
 * Execute mass deletion with SweetAlert2 confirmation.
 */
async function confirmBulkDelete() {
    const selectAllAcrossPages = isSelectAllAcrossPagesActive();
    const storedIds = getStoredIds();
    const count = selectAllAcrossPages ? TOTAL_MATCHING_JOBS : storedIds.length;

    if (count === 0) {
        Swal.fire({
            title: 'Sin selección',
            text: 'Por favor selecciona al menos una oferta laboral para eliminar.',
            icon: 'info',
            confirmButtonColor: '#7c3aed',
        });
        return;
    }

    const titleText = selectAllAcrossPages
        ? `¿Eliminar las ${count} ofertas coincidentes?`
        : (count === 1 ? '¿Eliminar 1 oferta laboral?' : `¿Eliminar ${count} ofertas laborales?`);

    const htmlText = selectAllAcrossPages
        ? `<div class="text-left text-sm text-gray-700 space-y-2">
            <p>Se eliminarán permanentemente las <b>${count}</b> ofertas laborales de todas las páginas que coinciden con los filtros aplicados.</p>
            <p class="text-xs text-rose-600 font-semibold"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Esta acción no se puede deshacer.</p>
           </div>`
        : `<div class="text-left text-sm text-gray-700 space-y-2">
            <p>Se eliminarán permanentemente las <b>${count}</b> ofertas seleccionadas en la tabla.</p>
            <p class="text-xs text-rose-600 font-semibold"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Esta acción no se puede deshacer.</p>
           </div>`;

    const result = await Swal.fire({
        title: titleText,
        html: htmlText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash-fill mr-1"></i> Sí, eliminar ofertas',
        cancelButtonText: 'Cancelar',
        focusCancel: true,
        width: '32rem',
    });

    if (!result.isConfirmed) return;

    Swal.fire({
        title: 'Eliminando ofertas...',
        html: 'Por favor espera mientras se procesa la eliminación masiva.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const payload = selectAllAcrossPages ? {
        select_all: true,
        search: CURRENT_SEARCH,
        status: CURRENT_STATUS,
        source: CURRENT_SOURCE,
    } : {
        ids: storedIds,
    };

    try {
        const response = await fetch(BULK_DELETE_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            clearStoredState();
            await Swal.fire({
                title: '¡Eliminado exitosamente!',
                text: data.message || 'Las ofertas seleccionadas fueron eliminadas.',
                icon: 'success',
                confirmButtonColor: '#7c3aed',
            });
            window.location.reload();
        } else {
            throw new Error(data.message || 'No se pudo completar la eliminación masiva.');
        }
    } catch (err) {
        console.error('bulkDelete error:', err);
        Swal.fire({
            title: 'Error',
            text: err.message || 'Ocurrió un error al intentar eliminar las ofertas.',
            icon: 'error',
            confirmButtonColor: '#e11d48',
        });
    }
}

/**
 * Execute complete table clear with SweetAlert2 confirmation.
 */
async function confirmClearAll() {
    if (TOTAL_ALL_JOBS === 0) {
        Swal.fire({
            title: 'Tabla vacía',
            text: 'La tabla de ofertas laborales ya se encuentra vacía.',
            icon: 'info',
            confirmButtonColor: '#7c3aed',
        });
        return;
    }

    const result = await Swal.fire({
        title: '¿VACIAR TODA LA TABLA?',
        html: `
            <div class="text-left text-sm text-gray-700 space-y-3">
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs">
                    <p class="font-bold flex items-center gap-1.5 text-rose-700">
                        <i class="bi bi-exclamation-octagon-fill text-base"></i> ACCIÓN IRREVERSIBLE
                    </p>
                    <p class="mt-1">
                        Estás a punto de eliminar definitivamente <strong>TODOS los registros (${TOTAL_ALL_JOBS} ofertas laborales)</strong> de la base de datos.
                    </p>
                </div>
                <p class="text-xs text-gray-500">
                    Se restablecerá la tabla de ofertas laborales por completo. Esta operación no se puede deshacer.
                </p>
            </div>
        `,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash3-fill mr-1"></i> Sí, vaciar tabla completa',
        cancelButtonText: 'Cancelar',
        focusCancel: true,
        width: '32rem',
    });

    if (!result.isConfirmed) return;

    Swal.fire({
        title: 'Vaciando tabla...',
        html: 'Eliminando todos los registros de ofertas laborales...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const response = await fetch(CLEAR_ALL_URL, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        const data = await response.json();

        if (response.ok && data.success) {
            clearStoredState();
            await Swal.fire({
                title: '¡Tabla vaciada!',
                text: data.message || 'Se han eliminado todos los registros de la tabla.',
                icon: 'success',
                confirmButtonColor: '#7c3aed',
            });
            window.location.reload();
        } else {
            throw new Error(data.message || 'No se pudo vaciar la tabla de ofertas.');
        }
    } catch (err) {
        console.error('clearAll error:', err);
        Swal.fire({
            title: 'Error',
            text: err.message || 'Ocurrió un error al intentar vaciar la tabla.',
            icon: 'error',
            confirmButtonColor: '#dc2626',
        });
    }
}

// Initialize state sync on page load
document.addEventListener('DOMContentLoaded', () => {
    syncUI();
});
</script>
@endpush
