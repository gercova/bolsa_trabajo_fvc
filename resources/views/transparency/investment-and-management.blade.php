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
            gap: 0.5rem;
            padding: 0.45rem 1.15rem;
            border-radius: 9999px;
            font-size: 0.8125rem;
            font-weight: 700;
            border: 1.5px solid transparent;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
        }
        .filter-pill:hover {
            transform: translateY(-1.5px);
        }
        .filter-pill.active {
            border-color: #2563eb;
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
        .filter-pill.inactive {
            border-color: #cbd5e1;
            background: #ffffff;
            color: #334155;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
        .filter-pill.inactive:hover {
            border-color: #93c5fd;
            background: #f0f7ff;
            color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
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
    <section class="bg-slate-900 relative text-white overflow-hidden py-16 lg:py-20 border-b border-slate-800"
             aria-label="Encabezado de Inversión y Gestión">
        <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
            {{-- Eyebrow badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-sky-400 text-xs font-bold uppercase tracking-widest">
                <i class="bi bi-shield-check"></i>
                Portal de Transparencia
            </div>

            {{-- Main heading --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Inversión y
                <span class="text-sky-400">
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
        <section aria-label="Filtrar por año" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
            <form method="GET" action="{{ route('inversion-y-gestion') }}"
                  class="flex flex-wrap items-center gap-2.5 sm:gap-3">

                <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5 mr-1">
                    <i class="bi bi-calendar3 text-blue-600 text-sm"></i> Período anual:
                </span>

                {{-- All years --}}
                <a href="{{ route('inversion-y-gestion') }}"
                   class="filter-pill {{ !$selectedYear ? 'active' : 'inactive' }}"
                   aria-current="{{ !$selectedYear ? 'page' : 'false' }}">
                    <i class="bi bi-collection-fill text-xs {{ !$selectedYear ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Todos los Años</span>
                </a>

                @foreach ($availableYears as $yr)
                    <a href="{{ route('inversion-y-gestion', ['year' => $yr]) }}"
                       class="filter-pill {{ $selectedYear == $yr ? 'active' : 'inactive' }}"
                       aria-current="{{ $selectedYear == $yr ? 'page' : 'false' }}">
                        <i class="bi bi-calendar-check-fill text-xs {{ $selectedYear == $yr ? 'text-white' : 'text-blue-500' }}"></i>
                        <span>Año {{ $yr }}</span>
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
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl shadow-sm shrink-0">
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
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl shadow-sm shrink-0">
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
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-2xl shadow-sm shrink-0">
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

            {{-- ── DATA TABLE GROUPED BY CATEGORY (COLUMN F) ─────────────────── --}}
            <section aria-label="Tabla de registros de inversión agrupados por categoría"
                     x-data="{
                         openCategories: [],
                         searchCategory: '',
                         viewMode: 'grouped',
                         allCats: {{ json_encode($categoryGroups->pluck('category')) }},
                         toggleCategory(cat) {
                             if (this.openCategories.includes(cat)) {
                                 this.openCategories = this.openCategories.filter(c => c !== cat);
                             } else {
                                 this.openCategories.push(cat);
                             }
                         },
                         isOpen(cat) {
                             return this.openCategories.includes(cat);
                         },
                         expandAll() {
                             this.openCategories = [...this.allCats];
                         },
                         collapseAll() {
                             this.openCategories = [];
                         }
                     }">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    {{-- Table header & Toolbar --}}
                    <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-slate-50 to-white">
                        <div>
                            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                                    <i class="bi bi-folder-fill"></i>
                                </span>
                                Detalle de Registros por Categoría
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Datos agrupados por <strong>Categoría (Columna F)</strong>:
                                <span class="font-bold text-slate-700">{{ $categoryGroups->count() }} rubros</span>
                                con un total de <span class="font-bold text-blue-700">{{ number_format($totalRecords) }} operaciones registradas</span>
                                @if ($selectedYear) para el año <strong>{{ $selectedYear }}</strong> @endif.
                            </p>
                        </div>

                        {{-- Toolbar: Search, View Mode, Expand/Collapse --}}
                        <div class="flex flex-wrap items-center gap-2.5">
                            {{-- Category Search Input --}}
                            <div class="relative">
                                <input type="text" x-model="searchCategory"
                                       placeholder="Buscar categoría..."
                                       class="pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all w-44 sm:w-52">
                                <i class="bi bi-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            </div>

                            {{-- Expand / Collapse buttons --}}
                            <div class="inline-flex rounded-xl shadow-xs border border-slate-200 overflow-hidden bg-white">
                                <button type="button" @click="expandAll()"
                                        class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition border-r border-slate-200"
                                        title="Expandir todas las categorías">
                                    <i class="bi bi-arrows-expand mr-1"></i> Expandir
                                </button>
                                <button type="button" @click="collapseAll()"
                                        class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition"
                                        title="Colapsar todas las categorías">
                                    <i class="bi bi-arrows-collapse mr-1"></i> Colapsar
                                </button>
                            </div>

                            {{-- View Mode Toggle --}}
                            <div class="inline-flex rounded-xl p-0.5 bg-slate-100 border border-slate-200 text-xs font-bold">
                                <button type="button" @click="viewMode = 'grouped'"
                                        :class="viewMode === 'grouped' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                        class="px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                                    <i class="bi bi-diagram-3-fill"></i> Agrupado
                                </button>
                                <button type="button" @click="viewMode = 'flat'"
                                        :class="viewMode === 'flat' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                        class="px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                                    <i class="bi bi-list-ul"></i> Lista Plana
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ══ GROUPED BY CATEGORY VIEW ══════════════════════════════════════ --}}
                    <div x-show="viewMode === 'grouped'" class="divide-y divide-slate-100">
                        @forelse ($categoryGroups as $catGroup)
                            @php
                                $catName    = $catGroup->category;
                                $subRecords = $recordsByCategory[$catName] ?? collect();
                                $pctOfTotal = $totalAmount > 0 ? ($catGroup->total_amount / $totalAmount) * 100 : 0;
                                $programs   = $subRecords->pluck('program_code')->filter()->unique()->values();
                            @endphp

                            <div x-show="!searchCategory || '{{ strtolower(addslashes($catName)) }}'.includes(searchCategory.toLowerCase())"
                                 class="transition-colors border-b border-slate-100 last:border-b-0">
                                
                                {{-- Category Summary Header Row --}}
                                <div @click="toggleCategory('{{ addslashes($catName) }}')"
                                     class="p-4 sm:px-6 sm:py-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50/80 transition-all select-none"
                                     :class="isOpen('{{ addslashes($catName) }}') ? 'bg-blue-50/40 border-l-4 border-l-blue-600' : 'border-l-4 border-l-transparent'">

                                    {{-- Left: Category Name & Programs --}}
                                    <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100/70 border border-blue-200/60 text-blue-700 flex items-center justify-center text-lg font-bold shrink-0 shadow-2xs">
                                            <i class="bi bi-tag-fill"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-sm sm:text-base font-black text-slate-800 tracking-tight">
                                                    {{ $catName }}
                                                </h3>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                    {{ $catGroup->count }} {{ $catGroup->count === 1 ? 'registro' : 'registros' }}
                                                </span>
                                            </div>

                                            {{-- Study programs preview --}}
                                            @if($programs->isNotEmpty())
                                                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                                    <span class="text-[11px] font-semibold text-slate-400">Programas:</span>
                                                    @foreach($programs as $pCode)
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                                            {{ $pCode }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right: Amount, Progress & Action --}}
                                    <div class="flex items-center justify-between lg:justify-end gap-5 shrink-0 pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                                        {{-- Percent of Total --}}
                                        <div class="hidden sm:block text-right w-28">
                                            <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 mb-1">
                                                <span>Participación</span>
                                                <span class="text-slate-700">{{ number_format($pctOfTotal, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500"
                                                     style="width: {{ min(100, max(4, $pctOfTotal)) }}%"></div>
                                            </div>
                                        </div>

                                        {{-- Total Amount --}}
                                        <div class="text-left sm:text-right">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Recaudado</span>
                                            <span class="text-base sm:text-lg font-black text-blue-700 tracking-tight">
                                                S/ {{ number_format((float)$catGroup->total_amount, 2) }}
                                            </span>
                                        </div>

                                        {{-- Toggle Accordion Button --}}
                                        <button type="button"
                                                class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-all flex items-center gap-1.5"
                                                :class="isOpen('{{ addslashes($catName) }}')
                                                    ? 'bg-blue-600 text-white border-blue-600 shadow-xs'
                                                    : 'bg-white text-slate-600 border-slate-200 hover:border-blue-400 hover:text-blue-600'">
                                            <span x-text="isOpen('{{ addslashes($catName) }}') ? 'Ocultar' : 'Ver detalle'"></span>
                                            <i class="bi text-xs" :class="isOpen('{{ addslashes($catName) }}') ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Category Detail Drawer (Accordion Table) --}}
                                <div x-show="isOpen('{{ addslashes($catName) }}')"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="bg-slate-50/70 border-t border-slate-200/80 px-3 sm:px-6 py-4">

                                    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                                        <div class="overflow-x-auto custom-scrollbar">
                                            <table class="min-w-full text-xs text-left">
                                                <thead>
                                                    <tr class="bg-slate-100/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200 text-[11px]">
                                                        <th class="py-2.5 px-3">Mes / Período</th>
                                                        <th class="py-2.5 px-3">N° B/V</th>
                                                        <th class="py-2.5 px-3">Cliente / Beneficiario</th>
                                                        <th class="py-2.5 px-3">Descripción</th>
                                                        <th class="py-2.5 px-3">Programa</th>
                                                        <th class="py-2.5 px-3 text-right">Monto (S/)</th>
                                                        <th class="py-2.5 px-3">Motivo</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                                    @foreach ($subRecords as $rec)
                                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                                            <td class="py-2.5 px-3 font-semibold text-slate-800 whitespace-nowrap">
                                                                <span class="inline-flex items-center gap-1">
                                                                    <i class="bi bi-calendar-event text-slate-400"></i>
                                                                    {{ $rec->month ?? ($rec->date ? $rec->date->format('M Y') : '—') }}
                                                                </span>
                                                            </td>
                                                            <td class="py-2.5 px-3 font-mono font-bold text-slate-600 whitespace-nowrap">
                                                                {{ $rec->receipt_number ?? '—' }}
                                                            </td>
                                                            <td class="py-2.5 px-3 font-semibold text-slate-800 max-w-[200px]">
                                                                <span class="block truncate" title="{{ $rec->client }}">
                                                                    {{ $rec->client ?? '—' }}
                                                                </span>
                                                            </td>
                                                            <td class="py-2.5 px-3 text-slate-600 max-w-[240px]">
                                                                <span class="block truncate" title="{{ $rec->description }}">
                                                                    {{ $rec->description ?? '—' }}
                                                                </span>
                                                            </td>
                                                            <td class="py-2.5 px-3 whitespace-nowrap">
                                                                @if ($rec->program_code)
                                                                    <span class="font-extrabold text-blue-700">{{ $rec->program_code }}</span>
                                                                    @if ($rec->program_name)
                                                                        <span class="text-slate-400 text-[10px] ml-1">({{ Str::limit($rec->program_name, 18) }})</span>
                                                                    @endif
                                                                @elseif ($rec->program_name)
                                                                    <span class="text-slate-600">{{ Str::limit($rec->program_name, 22) }}</span>
                                                                @else
                                                                    <span class="text-slate-300">—</span>
                                                                @endif
                                                            </td>
                                                            <td class="py-2.5 px-3 text-right font-black text-blue-700 whitespace-nowrap">
                                                                S/ {{ number_format((float)$rec->amount, 2) }}
                                                            </td>
                                                            <td class="py-2.5 px-3 text-slate-500 text-[11px] max-w-[140px]">
                                                                <span class="block truncate" title="{{ $rec->reason }}">
                                                                    {{ $rec->reason ?? '—' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-blue-50/60 font-bold border-t border-blue-100 text-xs">
                                                        <td colspan="5" class="py-2.5 px-3 text-right text-slate-600 uppercase tracking-wider">
                                                            Subtotal {{ $catName }}:
                                                        </td>
                                                        <td class="py-2.5 px-3 text-right font-black text-blue-800 text-sm whitespace-nowrap">
                                                            S/ {{ number_format((float)$catGroup->total_amount, 2) }}
                                                        </td>
                                                        <td class="py-2.5 px-3 text-slate-500 font-semibold text-[11px]">
                                                            {{ $subRecords->count() }} comprobantes
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-500 text-sm">
                                No se encontraron categorías con registros disponibles.
                            </div>
                        @endforelse
                    </div>

                    {{-- ══ FLAT RAW LIST VIEW (OPTIONAL ALTERNATIVE) ════════════════════ --}}
                    <div x-show="viewMode === 'flat'" class="overflow-x-auto">
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
