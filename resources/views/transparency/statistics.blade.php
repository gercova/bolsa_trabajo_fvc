@extends('layouts.app')

@section('title', 'Estadísticas Institucionales — IESTP Francisco Vigo Caballero')
@section('meta_description', 'Portal de Transparencia del IESTP Francisco Vigo Caballero: Estadísticas oficiales y consolidadas de matrícula académica, procesos de admisión y grados y títulos registrados por año, periodo y programa de estudios.')
@section('meta_keywords', 'estadisticas institucionales, matricula academica, admision, grados y titulos, egresados, postulantes, estudiantes, IESTP Francisco Vigo Caballero, Uchiza, transparencia educativa, MINEDU, educacion superior tecnologica')

@push('styles')
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Estadísticas Institucionales — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Consulta los reportes y resúmenes estadísticos oficiales de matrícula, admisión y grados y títulos registrados en el IESTP Francisco Vigo Caballero.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($enterprise->logo_path ?? 'enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data for SEO and Transparency --}}
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
              "name": "Estadísticas Institucionales",
              "item": "{{ url()->current() }}"
            }
          ]
        },
        {
          "@type": "Dataset",
          "@id": "{{ url()->current() }}#dataset",
          "name": "Estadísticas de Matrícula, Admisión y Titulación — IESTP Francisco Vigo Caballero",
          "description": "Conjunto de datos estadísticos consolidados y desagregados por año, periodo académico y programa de estudios del IESTP Francisco Vigo Caballero.",
          "license": "https://creativecommons.org/licenses/by/4.0/",
          "creator": {
            "@id": "{{ url('/') }}#organization"
          },
          "spatialCoverage": "Uchiza, San Martín, Perú",
          "temporalCoverage": "1997/2026",
          "variableMeasured": [
            "Estudiantes Matriculados",
            "Postulantes e Ingresantes",
            "Grados y Títulos Registrados",
            "Distribución por Género",
            "Distribución por Ciclo Académico"
          ]
        }
      ]
    }
    </script>
@endpush

@section('content')
    <div x-data="{
        activeTab: 'general',
        searchQuery: '',
        selectedPeriod: 'all',
        selectedYear: 'all',
        selectedAdmissionPeriod: 'all'
    }" class="bg-slate-50 min-h-screen">

        {{-- ===== HERO SECTION ===== --}}
        <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white overflow-hidden py-16 lg:py-20 border-b border-blue-900/30">
            {{-- Decorative Background Highlights --}}
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(56,189,248,0.15),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_70%,rgba(99,102,241,0.12),transparent_40%)]"></div>
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
                {{-- Breadcrumbs --}}
                <nav class="flex items-center justify-center gap-2 text-xs sm:text-sm text-slate-400 font-medium" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}" class="hover:text-sky-400 transition-colors">Inicio</a>
                    <i class="bi bi-chevron-right text-xs text-slate-600"></i>
                    <span class="text-slate-400">Transparencia</span>
                    <i class="bi bi-chevron-right text-xs text-slate-600"></i>
                    <span class="text-sky-400 font-semibold">Estadísticas</span>
                </nav>

                {{-- Hero Heading --}}
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight text-white max-w-5xl mx-auto">
                    Estadísticas <span class="text-sky-400 bg-gradient-to-r from-sky-400 via-blue-400 to-indigo-400 bg-clip-text text-transparent">Institucionales</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                    Información oficial consolidada de matrícula estudiantil, procesos de admisión y registro de grados y títulos del <strong class="text-white font-semibold">{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}</strong>.
                </p>

                {{-- Highlights Badges --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-5xl mx-auto mt-8 pt-6 border-t border-white/10 text-left">
                    <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Matrículas</p>
                            <p class="text-lg sm:text-xl font-black text-white">{{ number_format($totalMatriculas) }}</p>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Títulos Emitidos</p>
                            <p class="text-lg sm:text-xl font-black text-white">{{ number_format($totalTitulos) }}</p>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Programas</p>
                            <p class="text-lg sm:text-xl font-black text-white">{{ number_format($totalProgramas) }}</p>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-calendar2-range-fill"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Periodos / Años</p>
                            <p class="text-lg sm:text-xl font-black text-white">{{ $totalPeriodos }} / {{ $totalAniosTitulacion }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== MAIN STATISTICAL CONTENT ===== --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">

            {{-- ═══ NAVIGATION TABS ═══════════════════════════════════════ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 sm:p-3">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <nav class="flex flex-wrap gap-1.5 sm:gap-2" aria-label="Secciones Estadísticas">
                        <button type="button" @click="activeTab = 'general'"
                            :class="activeTab === 'general' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                            class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-pie-chart-fill"></i>
                            <span>Resumen Consolidado</span>
                        </button>

                        <button type="button" @click="activeTab = 'matricula'"
                            :class="activeTab === 'matricula' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                            class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-people-fill"></i>
                            <span>Matrícula por Periodo</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md"
                                :class="activeTab === 'matricula' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                                {{ number_format($totalMatriculas) }}
                            </span>
                        </button>

                        <button type="button" @click="activeTab = 'admision'"
                            :class="activeTab === 'admision' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                            class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-door-open-fill"></i>
                            <span>Admisión e Ingresantes</span>
                            @if ($totalAdmisiones > 0)
                                <span class="text-[10px] px-1.5 py-0.5 rounded-md"
                                    :class="activeTab === 'admision' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                                    {{ number_format($totalAdmisiones) }}
                                </span>
                            @endif
                        </button>

                        <button type="button" @click="activeTab = 'titulos'"
                            :class="activeTab === 'titulos' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                            class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-award-fill"></i>
                            <span>Grados y Títulos</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md"
                                :class="activeTab === 'titulos' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                                {{ number_format($totalTitulos) }}
                            </span>
                        </button>
                    </nav>

                    {{-- Search Filter & Print --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-64">
                            <input type="text" x-model="searchQuery"
                                placeholder="Buscar programa..."
                                class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <button type="button" x-show="searchQuery" @click="searchQuery = ''"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>

                        <button type="button" onclick="window.print()"
                            title="Imprimir resumen estadístico"
                            class="p-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 rounded-xl transition text-xs sm:text-sm font-semibold flex items-center gap-1.5 shrink-0">
                            <i class="bi bi-printer-fill"></i>
                            <span class="hidden sm:inline">Imprimir</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- TAB 1: RESUMEN CONSOLIDADO GENERAL                            --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <section x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">

                {{-- Demographic Gender Indicators Bar --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Matrícula Gender Card --}}
                    @php
                        $pctMalesMat = $totalMatriculas > 0 ? round(($totalMalesMatricula / $totalMatriculas) * 100, 1) : 0;
                        $pctFemalesMat = $totalMatriculas > 0 ? round(($totalFemalesMatricula / $totalMatriculas) * 100, 1) : 0;
                    @endphp
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                                    <i class="bi bi-gender-ambiguous"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Distribución de Género en Matrícula</h3>
                                    <p class="text-xs text-slate-500">Total histórico consolidado</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ number_format($totalMatriculas) }} reg.</span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex">
                            <div class="bg-blue-600 h-full" style="width: {{ $pctMalesMat }}%" title="Varones: {{ $pctMalesMat }}%"></div>
                            <div class="bg-pink-500 h-full" style="width: {{ $pctFemalesMat }}%" title="Mujeres: {{ $pctFemalesMat }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-1 text-center">
                            <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-100">
                                <p class="text-xs font-semibold text-blue-700 flex items-center justify-center gap-1">
                                    <i class="bi bi-gender-male"></i> Varones: {{ number_format($totalMalesMatricula) }}
                                </p>
                                <p class="text-xs font-black text-blue-900 mt-0.5">{{ $pctMalesMat }}%</p>
                            </div>
                            <div class="bg-pink-50/70 p-2.5 rounded-xl border border-pink-100">
                                <p class="text-xs font-semibold text-pink-700 flex items-center justify-center gap-1">
                                    <i class="bi bi-gender-female"></i> Mujeres: {{ number_format($totalFemalesMatricula) }}
                                </p>
                                <p class="text-xs font-black text-pink-900 mt-0.5">{{ $pctFemalesMat }}%</p>
                            </div>
                        </div>
                    </div>

                    {{-- Titulación Gender Card --}}
                    @php
                        $pctMalesTit = $totalTitulos > 0 ? round(($totalMalesTitulos / $totalTitulos) * 100, 1) : 0;
                        $pctFemalesTit = $totalTitulos > 0 ? round(($totalFemalesTitulos / $totalTitulos) * 100, 1) : 0;
                    @endphp
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                                    <i class="bi bi-award"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Distribución de Género en Titulación</h3>
                                    <p class="text-xs text-slate-500">Total histórico de graduados titulados</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ number_format($totalTitulos) }} reg.</span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex">
                            <div class="bg-blue-600 h-full" style="width: {{ $pctMalesTit }}%" title="Varones: {{ $pctMalesTit }}%"></div>
                            <div class="bg-pink-500 h-full" style="width: {{ $pctFemalesTit }}%" title="Mujeres: {{ $pctFemalesTit }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-1 text-center">
                            <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-100">
                                <p class="text-xs font-semibold text-blue-700 flex items-center justify-center gap-1">
                                    <i class="bi bi-gender-male"></i> Varones: {{ number_format($totalMalesTitulos) }}
                                </p>
                                <p class="text-xs font-black text-blue-900 mt-0.5">{{ $pctMalesTit }}%</p>
                            </div>
                            <div class="bg-pink-50/70 p-2.5 rounded-xl border border-pink-100">
                                <p class="text-xs font-semibold text-pink-700 flex items-center justify-center gap-1">
                                    <i class="bi bi-gender-female"></i> Mujeres: {{ number_format($totalFemalesTitulos) }}
                                </p>
                                <p class="text-xs font-black text-pink-900 mt-0.5">{{ $pctFemalesTit }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabla 1.1: Resumen Consolidado por Programa de Estudios --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-table text-blue-600"></i> Tabla 1: Resumen Histórico de Matrícula por Programa de Estudios
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Consolidado general de estudiantes matriculados según registros oficiales</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg self-start sm:self-auto">
                            {{ $enrollmentProgramSummary->count() }} Programas Registrados
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <caption class="sr-only">Resumen Histórico de Matrícula por Programa de Estudios</caption>
                            <thead>
                                <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                    <th scope="col" class="py-3.5 px-4">#</th>
                                    <th scope="col" class="py-3.5 px-4">Programa de Estudios</th>
                                    <th scope="col" class="py-3.5 px-4 text-center">Periodos</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Varones</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Mujeres</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">Total Matriculados</th>
                                    <th scope="col" class="py-3.5 px-4 text-right">% Participación</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($enrollmentProgramSummary as $idx => $prog)
                                    @php
                                        $pct = $totalMatriculas > 0 ? round(($prog->total / $totalMatriculas) * 100, 1) : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors"
                                        x-show="!searchQuery || '{{ strtolower($prog->study_program) }}'.includes(searchQuery.toLowerCase())">
                                        <td class="py-3 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-slate-900">{{ $prog->study_program }}</span>
                                            @if ($prog->last_period)
                                                <span class="block text-[11px] text-slate-500">Último periodo: {{ $prog->last_period }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                                {{ $prog->periods_count }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono text-blue-700 font-medium">
                                            {{ number_format($prog->males) }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono text-pink-700 font-medium">
                                            {{ number_format($prog->females) }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                            {{ number_format($prog->total) }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="inline-flex items-center gap-1.5">
                                                <div class="w-12 h-2 bg-slate-100 rounded-full overflow-hidden hidden sm:block">
                                                    <div class="bg-blue-600 h-full rounded-full" style="width: {{ $pct }}%"></div>
                                                </div>
                                                <span class="font-bold text-slate-700 text-xs">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400">No hay registros de matrícula disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                    <td class="py-3.5 px-4" colspan="2">TOTAL GENERAL CONSOLIDADO</td>
                                    <td class="py-3.5 px-4 text-center font-mono">{{ $totalPeriodos }}</td>
                                    <td class="py-3.5 px-4 text-right font-mono text-blue-800">{{ number_format($totalMalesMatricula) }}</td>
                                    <td class="py-3.5 px-4 text-right font-mono text-pink-800">{{ number_format($totalFemalesMatricula) }}</td>
                                    <td class="py-3.5 px-4 text-right font-mono text-slate-950 text-sm sm:text-base">{{ number_format($totalMatriculas) }}</td>
                                    <td class="py-3.5 px-4 text-right font-mono">100.0%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Tabla 1.2: Resumen Consolidado por Periodos Académicos --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Evolución por Periodo Académico --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                            <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-calendar3 text-indigo-600"></i> Tabla 2: Matrícula por Periodo Académico
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded-md">
                                {{ $enrollmentPeriodSummary->count() }} Periodos
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <caption class="sr-only">Evolución de Matrícula por Periodo</caption>
                                <thead>
                                    <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                        <th scope="col" class="py-3 px-4">Periodo</th>
                                        <th scope="col" class="py-3 px-4 text-center">Prog.</th>
                                        <th scope="col" class="py-3 px-4 text-right">Varones</th>
                                        <th scope="col" class="py-3 px-4 text-right">Mujeres</th>
                                        <th scope="col" class="py-3 px-4 text-right">Total</th>
                                        <th scope="col" class="py-3 px-4 text-right">%</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($enrollmentPeriodSummary as $ep)
                                        @php
                                            $pct = $totalMatriculas > 0 ? round(($ep->total / $totalMatriculas) * 100, 1) : 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-2.5 px-4 font-bold text-slate-900 font-mono">{{ $ep->academic_period }}</td>
                                            <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ $ep->programs_count }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-700">{{ number_format($ep->males) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-700">{{ number_format($ep->females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900">{{ number_format($ep->total) }}</td>
                                            <td class="py-2.5 px-4 text-right text-xs font-semibold text-slate-600">{{ $pct }}%</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="py-6 text-center text-slate-400">Sin datos registrados</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100 font-bold text-slate-900 border-t border-slate-200 text-xs">
                                        <td class="py-2.5 px-4" colspan="2">TOTAL</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-blue-800">{{ number_format($totalMalesMatricula) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-pink-800">{{ number_format($totalFemalesMatricula) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono">{{ number_format($totalMatriculas) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Evolución Anual de Grados y Títulos --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                            <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-award-fill text-purple-600"></i> Tabla 3: Títulos Otorgados por Año
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 bg-purple-100 text-purple-800 rounded-md">
                                {{ $degreesYearSummary->count() }} Años Registrados
                            </span>
                        </div>

                        <div class="overflow-x-auto max-h-[380px]">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <caption class="sr-only">Evolución Anual de Grados y Títulos Otorgados</caption>
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                        <th scope="col" class="py-3 px-4">Año</th>
                                        <th scope="col" class="py-3 px-4 text-center">Prog.</th>
                                        <th scope="col" class="py-3 px-4 text-right">Varones</th>
                                        <th scope="col" class="py-3 px-4 text-right">Mujeres</th>
                                        <th scope="col" class="py-3 px-4 text-right">Total</th>
                                        <th scope="col" class="py-3 px-4 text-right">%</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($degreesYearSummary as $dy)
                                        @php
                                            $pct = $totalTitulos > 0 ? round(($dy->total / $totalTitulos) * 100, 1) : 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-2.5 px-4 font-bold text-purple-900 font-mono">{{ $dy->year }}</td>
                                            <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ $dy->programs_count }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-700">{{ number_format($dy->males) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-700">{{ number_format($dy->females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900">{{ number_format($dy->total) }}</td>
                                            <td class="py-2.5 px-4 text-right text-xs font-semibold text-slate-600">{{ $pct }}%</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="py-6 text-center text-slate-400">Sin datos registrados</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="sticky bottom-0 bg-slate-100">
                                    <tr class="font-bold text-slate-900 border-t border-slate-200 text-xs">
                                        <td class="py-2.5 px-4" colspan="2">TOTAL HISTÓRICO</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-blue-800">{{ number_format($totalMalesTitulos) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-pink-800">{{ number_format($totalFemalesTitulos) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono">{{ number_format($totalTitulos) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Tabla 1.3: Grados y Títulos por Familia Productiva --}}
                @if ($degreesFamilySummary->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                            <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-diagram-3-fill text-emerald-600"></i> Tabla 4: Grados y Títulos por Familia Productiva
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-md">
                                Clasificación MINEDU
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <caption class="sr-only">Títulos por Familia Productiva</caption>
                                <thead>
                                    <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                        <th scope="col" class="py-3 px-4">#</th>
                                        <th scope="col" class="py-3 px-4">Familia Productiva</th>
                                        <th scope="col" class="py-3 px-4 text-right">Varones</th>
                                        <th scope="col" class="py-3 px-4 text-right">Mujeres</th>
                                        <th scope="col" class="py-3 px-4 text-right">Total Títulos</th>
                                        <th scope="col" class="py-3 px-4 text-right">% Participación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($degreesFamilySummary as $idx => $fam)
                                        @php
                                            $pct = $totalTitulos > 0 ? round(($fam->total / $totalTitulos) * 100, 1) : 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-2.5 px-4 font-mono text-slate-400">{{ $idx + 1 }}</td>
                                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ $fam->productive_family }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-700">{{ number_format($fam->males) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-700">{{ number_format($fam->females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900">{{ number_format($fam->total) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-semibold text-slate-700">{{ $pct }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </section>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- TAB 2: MATRÍCULA POR PERIODO ACADÉMICO (StudentRecord)        --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <section x-show="activeTab === 'matricula'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">

                {{-- Filter bar for Period --}}
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-filter"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Filtrar por Periodo Académico</h2>
                            <p class="text-xs text-slate-500">Seleccione un periodo para ver el desglose por programa y ciclo</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <select x-model="selectedPeriod"
                            class="text-xs sm:text-sm border border-slate-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-semibold text-slate-800">
                            <option value="all">Ver Todos los Periodos</option>
                            @foreach ($enrollmentByPeriod->keys() as $per)
                                <option value="{{ $per }}">Periodo {{ $per }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Detailed Tables per Period --}}
                @forelse ($enrollmentByPeriod as $periodName => $periodRows)
                    @php
                        $periodTotal = $periodRows->sum('total');
                        $periodMales = $periodRows->sum('males');
                        $periodFemales = $periodRows->sum('females');
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0"
                        x-show="selectedPeriod === 'all' || selectedPeriod === '{{ $periodName }}'">

                        {{-- Period Header Banner --}}
                        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-900 via-slate-800 to-blue-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-blue-500 text-white font-mono font-black text-xs sm:text-sm rounded-lg shadow-sm">
                                    {{ $periodName }}
                                </span>
                                <div>
                                    <h3 class="text-sm sm:text-base font-extrabold text-white">Matrícula Académica — Periodo {{ $periodName }}</h3>
                                    <p class="text-xs text-slate-300">{{ $periodRows->count() }} Programas con estudiantes matriculados</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-mono">
                                <span class="text-blue-300"><i class="bi bi-gender-male"></i> {{ number_format($periodMales) }} Varones</span>
                                <span class="text-pink-300"><i class="bi bi-gender-female"></i> {{ number_format($periodFemales) }} Mujeres</span>
                                <span class="px-2.5 py-1 bg-white/10 rounded-lg text-white font-bold">Total: {{ number_format($periodTotal) }}</span>
                            </div>
                        </div>

                        {{-- Table of Programs & Cycles --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <caption class="sr-only">Matrícula Periodo {{ $periodName }}</caption>
                                <thead>
                                    <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider text-[11px] font-extrabold border-b border-slate-200">
                                        <th scope="col" class="py-3 px-4">#</th>
                                        <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                        <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo I</th>
                                        <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo II</th>
                                        <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo III</th>
                                        <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo IV</th>
                                        <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo V</th>
                                        <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo VI</th>
                                        <th scope="col" class="py-3 px-3 text-right text-blue-700">Varones</th>
                                        <th scope="col" class="py-3 px-3 text-right text-pink-700">Mujeres</th>
                                        <th scope="col" class="py-3 px-4 text-right text-slate-900 font-black">Total</th>
                                        <th scope="col" class="py-3 px-4 text-right">% Periodo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($periodRows as $idx => $row)
                                        @php
                                            $rowPct = $periodTotal > 0 ? round(($row->total / $periodTotal) * 100, 1) : 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors"
                                            x-show="!searchQuery || '{{ strtolower($row->study_program) }}'.includes(searchQuery.toLowerCase())">
                                            <td class="py-2.5 px-4 font-mono text-slate-400">{{ $idx + 1 }}</td>
                                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ $row->study_program }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono {{ $row->cycle_i > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300' }}">{{ $row->cycle_i ?: '—' }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono {{ $row->cycle_ii > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300' }}">{{ $row->cycle_ii ?: '—' }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono {{ $row->cycle_iii > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300' }}">{{ $row->cycle_iii ?: '—' }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono {{ $row->cycle_iv > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300' }}">{{ $row->cycle_iv ?: '—' }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono {{ $row->cycle_v > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300' }}">{{ $row->cycle_v ?: '—' }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono {{ $row->cycle_vi > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300' }}">{{ $row->cycle_vi ?: '—' }}</td>
                                            <td class="py-2.5 px-3 text-right font-mono text-blue-700 font-semibold">{{ number_format($row->males) }}</td>
                                            <td class="py-2.5 px-3 text-right font-mono text-pink-700 font-semibold">{{ number_format($row->females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-extrabold text-slate-900 bg-slate-50/50">{{ number_format($row->total) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-xs font-bold text-slate-600">{{ $rowPct }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                        <td class="py-3 px-4" colspan="2">TOTAL PERIODO {{ $periodName }}</td>
                                        <td class="py-3 px-2 text-center font-mono">{{ number_format($periodRows->sum('cycle_i')) }}</td>
                                        <td class="py-3 px-2 text-center font-mono">{{ number_format($periodRows->sum('cycle_ii')) }}</td>
                                        <td class="py-3 px-2 text-center font-mono">{{ number_format($periodRows->sum('cycle_iii')) }}</td>
                                        <td class="py-3 px-2 text-center font-mono">{{ number_format($periodRows->sum('cycle_iv')) }}</td>
                                        <td class="py-3 px-2 text-center font-mono">{{ number_format($periodRows->sum('cycle_v')) }}</td>
                                        <td class="py-3 px-2 text-center font-mono">{{ number_format($periodRows->sum('cycle_vi')) }}</td>
                                        <td class="py-3 px-3 text-right font-mono text-blue-800">{{ number_format($periodMales) }}</td>
                                        <td class="py-3 px-3 text-right font-mono text-pink-800">{{ number_format($periodFemales) }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-slate-950 text-sm sm:text-base">{{ number_format($periodTotal) }}</td>
                                        <td class="py-3 px-4 text-right font-mono">100.0%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200 shadow-sm">
                        <i class="bi bi-people text-5xl mb-3 block text-slate-300"></i>
                        <p class="font-bold text-slate-700 text-base">No se encontraron registros de matrícula.</p>
                        <p class="text-xs text-slate-500 mt-1">Los datos se cargarán conforme se importen las nóminas institucionales.</p>
                    </div>
                @endforelse
            </section>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- TAB 3: ADMISIÓN E INGRESANTES (StudentRecord - ADMISION)      --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <section x-show="activeTab === 'admision'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">

                {{-- Filter bar for Admission Period --}}
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Procesos de Admisión e Ingresantes</h2>
                            <p class="text-xs text-slate-500">Resumen estadístico de postulantes e ingresantes por programa y periodo</p>
                        </div>
                    </div>

                    @if ($admissionByPeriod->isNotEmpty())
                        <div class="flex items-center gap-3">
                            <select x-model="selectedAdmissionPeriod"
                                class="text-xs sm:text-sm border border-slate-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white font-semibold text-slate-800">
                                <option value="all">Ver Todos los Procesos</option>
                                @foreach ($admissionByPeriod->keys() as $per)
                                    <option value="{{ $per }}">Admisión {{ $per }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                @if ($admissionByPeriod->isNotEmpty())
                    @foreach ($admissionByPeriod as $periodName => $periodRows)
                        @php
                            $periodTotal = $periodRows->sum('total');
                            $periodMales = $periodRows->sum('males');
                            $periodFemales = $periodRows->sum('females');
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0"
                            x-show="selectedAdmissionPeriod === 'all' || selectedAdmissionPeriod === '{{ $periodName }}'">

                            {{-- Header --}}
                            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-indigo-500 text-white font-mono font-black text-xs sm:text-sm rounded-lg shadow-sm">
                                        {{ $periodName }}
                                    </span>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-extrabold text-white">Proceso de Admisión — Periodo {{ $periodName }}</h3>
                                        <p class="text-xs text-indigo-200">Postulantes e ingresantes por programa de estudios</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-mono">
                                    <span class="text-blue-300"><i class="bi bi-gender-male"></i> {{ number_format($periodMales) }} Varones</span>
                                    <span class="text-pink-300"><i class="bi bi-gender-female"></i> {{ number_format($periodFemales) }} Mujeres</span>
                                    <span class="px-2.5 py-1 bg-white/10 rounded-lg text-white font-bold">Total: {{ number_format($periodTotal) }}</span>
                                </div>
                            </div>

                            {{-- Table --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <caption class="sr-only">Admisión Periodo {{ $periodName }}</caption>
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider text-[11px] font-extrabold border-b border-slate-200">
                                            <th scope="col" class="py-3 px-4">#</th>
                                            <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                            <th scope="col" class="py-3 px-4 text-right text-blue-700">Varones</th>
                                            <th scope="col" class="py-3 px-4 text-right text-pink-700">Mujeres</th>
                                            <th scope="col" class="py-3 px-4 text-right text-slate-900 font-black">Total Ingresantes</th>
                                            <th scope="col" class="py-3 px-4 text-right">% Proceso</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($periodRows as $idx => $row)
                                            @php
                                                $rowPct = $periodTotal > 0 ? round(($row->total / $periodTotal) * 100, 1) : 0;
                                            @endphp
                                            <tr class="hover:bg-slate-50 transition-colors"
                                                x-show="!searchQuery || '{{ strtolower($row->study_program) }}'.includes(searchQuery.toLowerCase())">
                                                <td class="py-2.5 px-4 font-mono text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="py-2.5 px-4 font-bold text-slate-900">{{ $row->study_program }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-blue-700 font-semibold">{{ number_format($row->males) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-pink-700 font-semibold">{{ number_format($row->females) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono font-extrabold text-slate-900 bg-slate-50/50">{{ number_format($row->total) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-xs font-bold text-slate-600">{{ $rowPct }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                            <td class="py-3 px-4" colspan="2">TOTAL ADMISIÓN {{ $periodName }}</td>
                                            <td class="py-3 px-4 text-right font-mono text-blue-800">{{ number_format($periodMales) }}</td>
                                            <td class="py-3 px-4 text-right font-mono text-pink-800">{{ number_format($periodFemales) }}</td>
                                            <td class="py-3 px-4 text-right font-mono text-slate-950 text-sm sm:text-base">{{ number_format($periodTotal) }}</td>
                                            <td class="py-3 px-4 text-right font-mono">100.0%</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200 shadow-sm">
                        <i class="bi bi-door-open text-5xl mb-3 block text-slate-300"></i>
                        <p class="font-bold text-slate-700 text-base">No hay registros de admisión cargados actualmente.</p>
                        <p class="text-xs text-slate-500 mt-1">Los reportes se actualizarán al culminar cada proceso de examen ordinario o extraordinario.</p>
                    </div>
                @endif
            </section>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- TAB 4: GRADOS Y TÍTULOS (DegreeRecord)                        --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <section x-show="activeTab === 'titulos'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">

                {{-- Filter bar for Graduation Year --}}
                <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Grados y Títulos Registrados ante el MINEDU</h2>
                            <p class="text-xs text-slate-500">Desglose de egresados titulados por año de emisión y programa de estudios</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <select x-model="selectedYear"
                            class="text-xs sm:text-sm border border-slate-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-semibold text-slate-800">
                            <option value="all">Ver Todos los Años ({{ $degreesByYear->count() }})</option>
                            @foreach ($degreesByYear->keys() as $yr)
                                <option value="{{ $yr }}">Año {{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tabla Consolidada Histórica de Titulación por Programa --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                        <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-trophy-fill text-purple-600"></i> Tabla: Consolidado Histórico de Títulos por Programa de Estudios
                        </h2>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-purple-100 text-purple-800 rounded-md">
                            Total: {{ number_format($totalTitulos) }} Títulos
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <caption class="sr-only">Consolidado de Títulos por Programa</caption>
                            <thead>
                                <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                    <th scope="col" class="py-3 px-4">#</th>
                                    <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                    <th scope="col" class="py-3 px-4">Nivel Formativo</th>
                                    <th scope="col" class="py-3 px-4 text-center">Periodo Histórico</th>
                                    <th scope="col" class="py-3 px-4 text-right text-blue-300">Varones</th>
                                    <th scope="col" class="py-3 px-4 text-right text-pink-300">Mujeres</th>
                                    <th scope="col" class="py-3 px-4 text-right text-white font-bold">Total Títulos</th>
                                    <th scope="col" class="py-3 px-4 text-right">% Histórico</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($degreesProgramSummary as $idx => $dp)
                                    @php
                                        $pct = $totalTitulos > 0 ? round(($dp->total / $totalTitulos) * 100, 1) : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors"
                                        x-show="!searchQuery || '{{ strtolower($dp->study_program) }}'.includes(searchQuery.toLowerCase())">
                                        <td class="py-3 px-4 font-mono text-slate-400">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-slate-900">{{ $dp->study_program }}</td>
                                        <td class="py-3 px-4 text-slate-600 text-xs">{{ $dp->formative_level ?? 'PROFESIONAL TÉCNICO' }}</td>
                                        <td class="py-3 px-4 text-center font-mono text-xs text-slate-600">
                                            {{ $dp->first_year ?? '—' }} – {{ $dp->last_year ?? '—' }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono text-blue-700 font-semibold">{{ number_format($dp->males) }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-pink-700 font-semibold">{{ number_format($dp->females) }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-extrabold text-slate-900">{{ number_format($dp->total) }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-purple-700">{{ $pct }}%</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="py-8 text-center text-slate-400">Sin títulos registrados</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                    <td class="py-3 px-4" colspan="3">TOTAL HISTÓRICO DE TÍTULOS OTORGADOS</td>
                                    <td class="py-3 px-4 text-center font-mono">{{ $totalAniosTitulacion }} Años</td>
                                    <td class="py-3 px-4 text-right font-mono text-blue-800">{{ number_format($totalMalesTitulos) }}</td>
                                    <td class="py-3 px-4 text-right font-mono text-pink-800">{{ number_format($totalFemalesTitulos) }}</td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-950 text-sm sm:text-base">{{ number_format($totalTitulos) }}</td>
                                    <td class="py-3 px-4 text-right font-mono">100.0%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Annual Detailed Tables per Year --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-calendar-check text-purple-600"></i> Desglose Anual Detallado
                    </h3>

                    @foreach ($degreesByYear as $yearName => $yearRows)
                        @php
                            $yearTotal = $yearRows->sum('total');
                            $yearMales = $yearRows->sum('males');
                            $yearFemales = $yearRows->sum('females');
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0"
                            x-show="selectedYear === 'all' || selectedYear === '{{ $yearName }}'">

                            {{-- Year Header Banner --}}
                            <div class="px-6 py-3.5 border-b border-slate-200 bg-gradient-to-r from-slate-900 via-purple-950 to-indigo-950 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-purple-500 text-white font-mono font-black text-xs sm:text-sm rounded-lg shadow-sm">
                                        Año {{ $yearName }}
                                    </span>
                                    <span class="text-xs sm:text-sm font-extrabold text-white">Títulos y Grados Registrados en {{ $yearName }}</span>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-mono">
                                    <span class="text-blue-300"><i class="bi bi-gender-male"></i> {{ number_format($yearMales) }} Varones</span>
                                    <span class="text-pink-300"><i class="bi bi-gender-female"></i> {{ number_format($yearFemales) }} Mujeres</span>
                                    <span class="px-2.5 py-1 bg-white/10 rounded-lg text-white font-bold">Total: {{ number_format($yearTotal) }}</span>
                                </div>
                            </div>

                            {{-- Table --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <caption class="sr-only">Títulos Año {{ $yearName }}</caption>
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider text-[11px] font-extrabold border-b border-slate-200">
                                            <th scope="col" class="py-3 px-4">#</th>
                                            <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                            <th scope="col" class="py-3 px-4">Nivel Formativo</th>
                                            <th scope="col" class="py-3 px-4 text-right text-blue-700">Varones</th>
                                            <th scope="col" class="py-3 px-4 text-right text-pink-700">Mujeres</th>
                                            <th scope="col" class="py-3 px-4 text-right text-slate-900 font-black">Total Año</th>
                                            <th scope="col" class="py-3 px-4 text-right">% Año</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($yearRows as $idx => $row)
                                            @php
                                                $rowPct = $yearTotal > 0 ? round(($row->total / $yearTotal) * 100, 1) : 0;
                                            @endphp
                                            <tr class="hover:bg-slate-50 transition-colors"
                                                x-show="!searchQuery || '{{ strtolower($row->study_program) }}'.includes(searchQuery.toLowerCase())">
                                                <td class="py-2.5 px-4 font-mono text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="py-2.5 px-4 font-bold text-slate-900">{{ $row->study_program }}</td>
                                                <td class="py-2.5 px-4 text-xs text-slate-600">{{ $row->formative_level ?? 'PROFESIONAL TÉCNICO' }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-blue-700 font-semibold">{{ number_format($row->males) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-pink-700 font-semibold">{{ number_format($row->females) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono font-extrabold text-slate-900 bg-slate-50/50">{{ number_format($row->total) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-xs font-bold text-slate-600">{{ $rowPct }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                            <td class="py-2.5 px-4" colspan="3">TOTAL AÑO {{ $yearName }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-800">{{ number_format($yearMales) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-800">{{ number_format($yearFemales) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-slate-950">{{ number_format($yearTotal) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono">100.0%</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ═══ INSTITUTIONAL FOOTNOTE / ACCESSIBILITY INFO ════════════ --}}
            <div class="bg-slate-100 rounded-2xl p-5 sm:p-6 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-slate-600">
                <div class="flex items-center gap-3">
                    <i class="bi bi-info-circle-fill text-blue-600 text-xl shrink-0"></i>
                    <div>
                        <p class="font-bold text-slate-800">Nota Institucional de Transparencia:</p>
                        <p>Los datos consignados provienen de las nóminas oficiales de matrícula, actas de examen de admisión y el Sistema de Grados y Títulos (TITULA) del Ministerio de Educación (MINEDU).</p>
                    </div>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('documentos-de-gestion') }}"
                        class="px-4 py-2 bg-white hover:bg-slate-200 text-slate-800 rounded-xl border border-slate-300 font-bold transition flex items-center gap-1.5">
                        <i class="bi bi-folder-check"></i> Documentos de Gestión
                    </a>
                </div>
            </div>

        </main>
    </div>
@endsection
