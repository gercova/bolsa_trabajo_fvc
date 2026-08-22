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

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[750px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4">Puesto & Empresa</th>
                                    <th class="p-4">Ubicación</th>
                                    <th class="p-4">Fuente / Convocatoria</th>
                                    <th class="p-4 text-center w-28">Estado</th>
                                    <th class="p-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                                @forelse($jobs as $job)
                                    <tr class="hover:bg-purple-50/30 transition-colors">
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
                                        <td colspan="5" class="p-12 text-center text-gray-500">
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
</script>
@endpush
