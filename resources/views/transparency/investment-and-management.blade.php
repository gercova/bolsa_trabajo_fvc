@extends('layouts.app')

@section('title', 'Inversión y Gestión — IESTP Francisco Vigo Caballero')
@section('meta_description', 'Portal de Transparencia del IESTP Francisco Vigo Caballero: Consulta los registros de inversión institucional, desembolsos por categoría y programa de estudios, organizados por mes y año para garantizar la transparencia en la gestión de recursos públicos.')
@section('meta_keywords', 'inversion institucional, gestion financiera, transparencia, desembolsos, gastos institucionales, IESTP Francisco Vigo Caballero, Uchiza, rendicion de cuentas, recursos publicos, educacion superior tecnologica')

@push('styles')
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Inversión y Gestión Institucional — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Consulta los registros de inversión, desembolsos y gestión de recursos institucionales del IESTP Francisco Vigo Caballero, organizados por mes, categoría y programa de estudios.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($enterprise->logo_path ?? 'enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
            "@type": "EducationalOrganization",
            "@id": "{{ url('/') }}#organization",
            "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}",
            "alternateName": "{{ $enterprise->trade_name ?? 'IESTP FVC' }}",
            "url": "{{ url('/') }}",
            "logo": "{{ asset($enterprise->logo_path ?? 'enterprise/favicons/logo-iestpfvc.png') }}",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
                "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
                "addressRegion": "San Martín",
                "addressCountry": "PE"
            }
        },
        {
            "@type": "BreadcrumbList",
            "@id": "{{ url()->current() }}#breadcrumb",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Inicio",
                    "item": "{{ url('/') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Transparencia",
                    "item": "{{ route('documentos-de-gestion') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "Inversión y Gestión",
                    "item": "{{ url()->current() }}"
                }
            ]
        },
        {
            "@type": "Dataset",
            "@id": "{{ url()->current() }}#dataset",
            "name": "Registros de Inversión y Gestión Institucional — IESTP Francisco Vigo Caballero",
            "description": "Datos de desembolsos, inversiones y gestión de recursos institucionales del IESTP Francisco Vigo Caballero, desagregados por mes, categoría y programa de estudios.",
            "license": "https://creativecommons.org/licenses/by/4.0/",
            "creator": {
                "@id": "{{ url('/') }}#organization"
            },
            "spatialCoverage": "Uchiza, San Martín, Perú",
            "variableMeasured": [
                "Desembolsos Mensuales",
                "Distribución por Categoría",
                "Inversión por Programa de Estudios"
            ]
        }
      ]
    }
    </script>

    <style>
        /* ── Transparency page custom styles ─────────────────── */
        .invest-hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #0c1a3a 100%);
        }
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.12);
        }
        .chart-container {
            position: relative;
        }
        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 1rem;
            border-radius: 9999px;
            font-size: 0.8125rem;
            font-weight: 600;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }
        .filter-pill:hover {
            transform: translateY(-1px);
        }
        .filter-pill.active {
            border-color: #6366f1;
            background: rgba(99,102,241,0.12);
            color: #818cf8;
        }
        .filter-pill.inactive {
            border-color: rgba(99,102,241,0.2);
            background: rgba(15,23,42,0.4);
            color: #94a3b8;
        }
        .filter-pill.inactive:hover {
            border-color: rgba(99,102,241,0.5);
            color: #c7d2fe;
        }
        .amount-positive { color: #1d4ed8; font-weight: 700; }
        .amount-neutral  { color: #64748b; font-weight: 600; }
        .table-row-hover:hover { background-color: rgba(99,102,241,0.04); }
        @keyframes fade-in-up {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.5s ease both; }
        .animate-delay-100  { animation-delay: 0.1s; }
        .animate-delay-200  { animation-delay: 0.2s; }
        .animate-delay-300  { animation-delay: 0.3s; }
    </style>
@endpush

@section('content')
<div class="bg-slate-50 min-h-screen">

    {{-- ===== HERO SECTION ===================================================================== --}}
    <section class="invest-hero-gradient relative text-white overflow-hidden py-16 lg:py-20 border-b border-indigo-900/30"
             aria-label="Encabezado de Inversión y Gestión">
        {{-- Decorative blobs --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(99,102,241,0.18),transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_70%,rgba(56,189,248,0.10),transparent_40%)]"></div>
        <div class="absolute inset-0 opacity-[0.07] bg-[radial-gradient(#fff_1px,transparent_1px)] bg-[size:28px_28px]"></div>
        {{-- Floating decorative circles --}}
        <div class="absolute top-8 right-12 w-40 h-40 rounded-full bg-indigo-600/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-8  w-56 h-56 rounded-full bg-sky-600/8  blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
            {{-- Eyebrow badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-bold uppercase tracking-widest animate-fade-in-up">
                <i class="bi bi-shield-check"></i>
                Portal de Transparencia
            </div>

            {{-- Main heading --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight animate-fade-in-up animate-delay-100">
                Inversión y
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-400 to-blue-400">
                    Gestión Institucional
                </span>
            </h1>

            <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed animate-fade-in-up animate-delay-200">
                Registros de desembolsos e inversión de recursos del
                <strong class="text-white">{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}</strong>,
                organizados por mes, categoría y programa de estudios en cumplimiento de la normativa de transparencia.
            </p>

            {{-- Breadcrumb --}}
            <nav aria-label="Miga de pan" class="flex items-center justify-center gap-2 text-xs text-slate-400 animate-fade-in-up animate-delay-300">
                <a href="{{ route('inicio') }}" class="hover:text-white transition-colors">Inicio</a>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <a href="{{ route('documentos-de-gestion') }}" class="hover:text-white transition-colors">Transparencia</a>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <span class="text-indigo-300 font-semibold">Inversión y Gestión</span>
            </nav>
        </div>
    </section>

    {{-- ===== MAIN BODY ======================================================================= --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 space-y-10">

        {{-- ── YEAR FILTER BAR ────────────────────────────────────────────── --}}
        <section aria-label="Filtrar por año">
            <form method="GET" action="{{ route('inversion-y-gestion') }}"
                  class="flex flex-wrap items-center gap-3">

                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <i class="bi bi-calendar3 mr-1"></i> Filtrar por año:
                </span>

                {{-- All years --}}
                <a href="{{ route('inversion-y-gestion') }}"
                   class="filter-pill {{ !$selectedYear ? 'active' : 'inactive' }}"
                   aria-current="{{ !$selectedYear ? 'page' : 'false' }}">
                    Todos
                </a>

                @foreach ($availableYears as $yr)
                    <a href="{{ route('inversion-y-gestion', ['year' => $yr]) }}"
                       class="filter-pill {{ $selectedYear == $yr ? 'active' : 'inactive' }}"
                       aria-current="{{ $selectedYear == $yr ? 'page' : 'false' }}">
                        {{ $yr }}
                    </a>
                @endforeach

                @if (empty($availableYears))
                    <span class="text-xs text-slate-400 italic">Sin datos disponibles aún</span>
                @endif
            </form>
        </section>

        @if ($totalRecords === 0)
            {{-- ── EMPTY STATE ────────────────────────────────────────────────── --}}
            <div class="flex flex-col items-center justify-center py-24 gap-5">
                <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-400 flex items-center justify-center text-4xl shadow-inner">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <div class="text-center">
                    <h2 class="text-xl font-bold text-slate-700">Sin datos disponibles</h2>
                    <p class="text-slate-500 text-sm mt-2">
                        @if ($selectedYear)
                            No hay registros para el año <strong>{{ $selectedYear }}</strong>.
                            <a href="{{ route('inversion-y-gestion') }}" class="text-indigo-600 font-semibold hover:underline">Ver todos los años</a>
                        @else
                            Aún no se han cargado registros de inversión institucional.
                        @endif
                    </p>
                </div>
            </div>
        @else

            {{-- ── KPI SUMMARY CARDS ──────────────────────────────────────────── --}}
            <section aria-label="Resumen de inversión" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">

                {{-- Total Registros --}}
                <article class="stat-card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-200 shrink-0">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Registros</p>
                        <p class="text-3xl font-black text-slate-800 mt-0.5">{{ number_format($totalRecords) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $selectedYear ? "año $selectedYear" : 'todos los períodos' }}</p>
                    </div>
                </article>

                {{-- Total Desembolsado --}}
                <article class="stat-card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-sky-200 shrink-0">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Desembolsado</p>
                        <p class="text-3xl font-black text-blue-700 mt-0.5">S/ {{ number_format($totalAmount, 2) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $selectedYear ? "año $selectedYear" : 'histórico acumulado' }}</p>
                    </div>
                </article>

                {{-- Categorías / Programas --}}
                <article class="stat-card bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center gap-5 sm:col-span-2 lg:col-span-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-emerald-200 shrink-0">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Categorías</p>
                        <p class="text-3xl font-black text-emerald-700 mt-0.5">{{ $categoryTotals->count() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">rubros de inversión registrados</p>
                    </div>
                </article>
            </section>

            {{-- ── CHARTS SECTION ─────────────────────────────────────────────── --}}
            @if ($monthlyTotals->isNotEmpty() || $categoryTotals->isNotEmpty())
                <section aria-label="Gráficos de inversión" class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                    {{-- Bar chart: monthly totals (wider) --}}
                    <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="text-base font-extrabold text-slate-800">Desembolsos Mensuales</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Total acumulado por mes en S/</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg">
                                <i class="bi bi-bar-chart-fill"></i>
                            </div>
                        </div>
                        <div class="chart-container" style="height:260px;">
                            <canvas id="monthlyChart" aria-label="Gráfico de barras con desembolsos mensuales"></canvas>
                        </div>
                    </div>

                    {{-- Doughnut chart: category distribution (narrower) --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="text-base font-extrabold text-slate-800">Por Categoría</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Distribución de inversión</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg">
                                <i class="bi bi-pie-chart-fill"></i>
                            </div>
                        </div>
                        <div class="chart-container" style="height:200px;">
                            <canvas id="categoryChart" aria-label="Gráfico de dona con distribución por categoría"></canvas>
                        </div>
                        {{-- Category legend --}}
                        <div class="mt-4 space-y-1.5 max-h-40 overflow-y-auto pr-1">
                            @php
                                $catColors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#84cc16','#ec4899'];
                                $ci = 0;
                            @endphp
                            @foreach ($categoryTotals as $cat => $total)
                                <div class="flex items-center justify-between gap-2 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-3 h-3 rounded-sm shrink-0"
                                              style="background:{{ $catColors[$ci % count($catColors)] }}"></span>
                                        <span class="text-slate-600 truncate">{{ $cat }}</span>
                                    </div>
                                    <span class="font-bold text-slate-700 shrink-0">S/ {{ number_format($total, 0) }}</span>
                                </div>
                                @php $ci++ @endphp
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ── DATA TABLE ─────────────────────────────────────────────────── --}}
            <section aria-label="Tabla de registros de inversión">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    {{-- Table header --}}
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                                <i class="bi bi-table text-indigo-500"></i>
                                Detalle de Registros
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $records->total() }} registros
                                @if ($selectedYear) del año <strong>{{ $selectedYear }}</strong> @endif
                            </p>
                        </div>
                        <div class="shrink-0 text-xs text-slate-400 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 flex items-center gap-1.5">
                            <i class="bi bi-lock-fill text-slate-400"></i>
                            Datos oficiales
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" role="table" aria-label="Registros de inversión institucional">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mes</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">N° B/V</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Cliente / Beneficiario</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Descripción</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Categoría</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Programa</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Monto (S/)</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden xl:table-cell">Motivo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($records as $record)
                                    <tr class="table-row-hover transition-colors">
                                        <td class="px-4 py-3 font-semibold text-slate-700 whitespace-nowrap">
                                            {{ $record->month ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">
                                            {{ $record->date ? $record->date->format('d/m/Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-600 whitespace-nowrap">
                                            {{ $record->receipt_number ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-700 max-w-[180px]">
                                            <span class="block truncate" title="{{ $record->client }}">
                                                {{ $record->client ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600 text-xs max-w-[220px] hidden md:table-cell">
                                            <span class="block truncate" title="{{ $record->description }}">
                                                {{ $record->description ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($record->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    {{ $record->category }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 hidden lg:table-cell max-w-[140px]">
                                            @if ($record->program_code)
                                                <span class="font-bold text-indigo-600">{{ $record->program_code }}</span>
                                            @endif
                                            @if ($record->program_name)
                                                <span class="block text-slate-500 truncate" title="{{ $record->program_name }}">
                                                    {{ Str::limit($record->program_name, 22) }}
                                                </span>
                                            @endif
                                            @if (!$record->program_code && !$record->program_name)
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap amount-positive">
                                            S/ {{ number_format((float)$record->amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-500 text-xs hidden xl:table-cell max-w-[120px]">
                                            <span class="block truncate" title="{{ $record->reason }}">
                                                {{ $record->reason ?? '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($records->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-xs text-slate-500">
                                Mostrando
                                <span class="font-bold text-slate-700">{{ $records->firstItem() }}</span>–<span class="font-bold text-slate-700">{{ $records->lastItem() }}</span>
                                de <span class="font-bold text-slate-700">{{ $records->total() }}</span> registros
                            </p>
                            {{ $records->links() }}
                        </div>
                    @endif
                </div>
            </section>

            {{-- ── TRANSPARENCY NOTE ───────────────────────────────────────────── --}}
            <aside class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div class="text-sm text-indigo-800 leading-relaxed">
                    <strong class="font-bold">Nota de Transparencia:</strong>
                    Los registros publicados en esta sección corresponden a los desembolsos e inversiones institucionales del
                    <strong>{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}</strong>
                    en cumplimiento de las disposiciones del Portal de Transparencia Estándar del Estado Peruano (D.S. 070-2013-PCM).
                    Para consultas adicionales, comuníquese con la Dirección Administrativa.
                </div>
            </aside>

        @endif {{-- end if $totalRecords > 0 --}}
    </div>
</div>
@endsection

@push('scripts')
@if ($totalRecords > 0 && ($monthlyTotals->isNotEmpty() || $categoryTotals->isNotEmpty()))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Chart.js global defaults ────────────────────────────────
    Chart.defaults.font.family = "'Inter', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#64748b';

    // ── Monthly bar chart ────────────────────────────────────────
    const monthlyLabels = @json($monthlyTotals->keys());
    const monthlyData   = @json($monthlyTotals->values());

    if (document.getElementById('monthlyChart') && monthlyLabels.length) {
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Desembolsado (S/)',
                    data: monthlyData,
                    backgroundColor: 'rgba(99,102,241,0.75)',
                    hoverBackgroundColor: 'rgba(99,102,241,1)',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' S/ ' + ctx.parsed.y.toLocaleString('es-PE', {minimumFractionDigits:2})
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(226,232,240,0.8)' },
                        ticks: {
                            callback: v => 'S/ ' + v.toLocaleString('es-PE'),
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // ── Category doughnut chart ──────────────────────────────────
    const catLabels = @json($categoryTotals->keys());
    const catData   = @json($categoryTotals->values());
    const catColors = [
        '#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444',
        '#8b5cf6','#06b6d4','#f97316','#84cc16','#ec4899'
    ];

    if (document.getElementById('categoryChart') && catLabels.length) {
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: catColors.slice(0, catLabels.length),
                    hoverOffset: 6,
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' S/ ' + ctx.parsed.toLocaleString('es-PE', {minimumFractionDigits:2})
                        }
                    }
                }
            }
        });
    }
});
</script>
@endif
@endpush
