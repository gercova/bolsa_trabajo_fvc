@extends('layouts.app')
@section('title', 'Licenciamiento Institucional — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO Primary Meta Tags --}}
    <meta name="description" content="Conoce las 5 fases del proceso de Licenciamiento Institucional del IESTP Francisco Vigo Caballero en Uchiza. Estado actual (P), cumplimiento de las 7 Condiciones Básicas de Calidad (CBC) según la Ley N° 30512 y RVM N° 276-2019-MINEDU.">
    <meta name="keywords" content="licenciamiento institucional, CBC, condiciones basicas de calidad, IESTP Francisco Vigo Caballero, MINEDU, Uchiza, san martin, educacion tecnologica superior, ley 30512, etapas de licenciamiento">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Proceso de Licenciamiento Institucional — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Seguimiento oficial de las fases del licenciamiento institucional y matriz de las 7 Condiciones Básicas de Calidad (CBC).">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Licenciamiento Institucional — IESTP Francisco Vigo Caballero">
    <meta name="twitter:description" content="Consulta las 5 fases y el estado del proceso de licenciamiento del IESTP Francisco Vigo Caballero.">
    <meta name="twitter:image" content="{{ asset('enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "EducationalOrganization",
                "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}",
                "alternateName": "IESTP FVC",
                "url": "{{ url('/') }}",
                "logo": "{{ asset('enterprise/favicons/logo-iestpfvc.png') }}",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
                    "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
                    "addressRegion": "San Martín",
                    "addressCountry": "PE"
                }
            },
            {
                "@type": "GovernmentService",
                "name": "Portal de Transparencia - Licenciamiento Institucional",
                "serviceType": "Supervisión de Condiciones Básicas de Calidad",
                "provider": {
                    "@type": "EducationalOrganization",
                    "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}"
                },
                "hasOfferCatalog": {
                    "@type": "OfferCatalog",
                    "name": "Fases del Proceso de Licenciamiento",
                    "itemListElement": [
                        @foreach ($phases as $index => $phase)
                        {
                            "@type": "ListItem",
                            "position": {{ $index + 1 }},
                            "name": "{{ $phase->phase_number }}. {{ $phase->title }}",
                            "description": "{{ $phase->subtitle ?? $phase->title }}"
                        }@if(!$loop->last),@endif
                        @endforeach
                    ]
                }
            },
            {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Inicio",
                    "item": "{{ route('inicio') }}"
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
                    "name": "Licenciamiento",
                    "item": "{{ route('licenciamiento') }}"
                }
            ]
            }
        ]
    }
    </script>
@endpush

@section('content')
<div x-data="licensingApp()" class="min-h-screen bg-slate-50">
    {{-- HERO SECTION: Modern Gradient, Badges, Global Progress         --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white overflow-hidden py-16 lg:py-24 border-b border-indigo-900/30">
        {{-- Ambient Glow Effects --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.18),transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(14,165,233,0.15),transparent_40%)]"></div>
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-6 max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-300 text-xs sm:text-sm font-semibold tracking-wide backdrop-blur-md">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                    </span>
                    Proceso Oficial MINEDU — Ley N° 30512 & RVM N° 276-2019-MINEDU
                </div>

                {{-- Hero Heading --}}
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-tight text-white">
                    Licenciamiento <span class="bg-gradient-to-r from-sky-400 via-indigo-300 to-purple-400 bg-clip-text text-transparent">Institucional</span>
                </h1>

                <p class="text-base sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                    Monitoreo en tiempo real de las <strong class="text-white font-semibold">5 fases oficiales</strong> del procedimiento de licenciamiento y la adecuación de las <strong class="text-white font-semibold">7 Condiciones Básicas de Calidad (CBC)</strong> del IESTP "Francisco Vigo Caballero".
                </p>

                {{-- Key Stats Row --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 pt-6 max-w-4xl mx-auto text-left">
                    {{-- Stat 1: Etapa Actual --}}
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 relative overflow-hidden group hover:border-amber-400/40 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Etapa Actual</span>
                            <span class="px-2 py-0.5 text-xs font-extrabold rounded-md bg-amber-500 text-slate-950 animate-pulse">(P)</span>
                        </div>
                        <p class="text-base sm:text-lg font-bold text-white mt-1 truncate">
                            Fase {{ $currentPhase->phase_number ?? 1 }}: {{ Str::limit($currentPhase->code ?? 'CBC-01', 12) }}
                        </p>
                        <p class="text-[11px] text-amber-300/90 truncate mt-0.5">En Proceso Activo</p>
                    </div>

                    {{-- Stat 2: Avance Global --}}
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 hover:border-sky-400/40 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Avance Global</span>
                            <i class="bi bi-graph-up-arrow text-sky-400 text-sm"></i>
                        </div>
                        <p class="text-xl sm:text-2xl font-black text-sky-400 mt-1">
                            {{ $globalProgress }}%
                        </p>
                        <div class="w-full bg-white/10 h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-sky-400 to-indigo-500 h-full rounded-full transition-all duration-1000" style="width: {{ $globalProgress }}%"></div>
                        </div>
                    </div>

                    {{-- Stat 3: Total Fases --}}
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 hover:border-indigo-400/40 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Fases Totales</span>
                            <i class="bi bi-layers-fill text-indigo-400 text-sm"></i>
                        </div>
                        <p class="text-xl sm:text-2xl font-black text-white mt-1">
                            5 <span class="text-xs font-normal text-slate-400">Etapas MINEDU</span>
                        </p>
                        <p class="text-[11px] text-slate-300 mt-0.5">1 En Curso &bull; 4 Siguientes</p>
                    </div>

                    {{-- Stat 4: Matriz CBC --}}
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 hover:border-emerald-400/40 transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Condiciones CBC</span>
                            <i class="bi bi-shield-check text-emerald-400 text-sm"></i>
                        </div>
                        <p class="text-xl sm:text-2xl font-black text-emerald-400 mt-1">
                            7 / 7 <span class="text-xs font-normal text-slate-400">CBCs</span>
                        </p>
                        <p class="text-[11px] text-emerald-300/90 truncate mt-0.5">En Adecuación 100%</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- SECTION 1: INTERACTIVE 5-PHASE ROADMAP / PIPELINE STEPPER     --}}
    <section class="py-14 sm:py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- Section Title --}}
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                    Ruta del Licenciamiento
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Fases del Proceso y Estado Actual <span class="text-amber-600 font-black">(P)</span>
                </h2>
                <p class="text-sm sm:text-base text-slate-600">
                    El proceso de licenciamiento institucional consta de 5 etapas consecutivas evaluadas por el Ministerio de Educación:
                </p>
            </div>

            {{-- Horizontal Timeline on Desktop / Stepper on Mobile --}}
            <div class="relative">
                {{-- Desktop Connecting Progress Bar --}}
                <div class="hidden lg:block absolute top-1/2 -translate-y-6 left-12 right-12 h-1 bg-slate-200 z-0">
                    <div class="h-full bg-gradient-to-r from-emerald-500 via-amber-500 to-slate-200 transition-all duration-700"
                        style="width: {{ $currentPhase ? min(100, (($currentPhase->phase_number - 0.5) / max(1, $totalPhases)) * 100) : 20 }}%"></div>
                </div>

                {{-- Steps Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 relative z-10">
                    @foreach ($phases as $phase)
                        @php
                            $isCurrent = $phase->is_current || ($currentPhase && $currentPhase->id === $phase->id);
                            $isCompleted = $phase->status === 'completed';
                            $isObserved = $phase->status === 'observed';
                            $isPending = $phase->status === 'pending' && !$isCurrent;
                        @endphp

                        <div @click="selectedPhaseId = {{ $phase->id }}"
                            class="cursor-pointer transition-all duration-300 rounded-3xl p-5 border flex flex-col justify-between text-left group relative
                                {{ $isCurrent 
                                    ? 'bg-amber-50/70 border-amber-400 shadow-lg shadow-amber-500/10 ring-2 ring-amber-400/40 -translate-y-1' 
                                    : ($isCompleted 
                                        ? 'bg-emerald-50/40 border-emerald-300 hover:shadow-md' 
                                        : 'bg-white border-slate-200 hover:border-indigo-300 hover:shadow-md') }}">
                            
                            {{-- Step Number & Status Tag --}}
                            <div class="flex items-center justify-between gap-2">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg transition-transform duration-300 group-hover:scale-105
                                    {{ $isCurrent 
                                        ? 'bg-gradient-to-br from-amber-500 to-amber-600 text-slate-950 shadow-md shadow-amber-500/30 ring-4 ring-amber-200' 
                                        : ($isCompleted 
                                            ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' 
                                            : 'bg-slate-100 text-slate-600 border border-slate-300') }}">
                                    @if ($isCompleted)
                                        <i class="bi bi-check2 text-2xl font-bold"></i>
                                    @else
                                        {{ $phase->phase_number }}
                                    @endif
                                </div>

                                {{-- Tag Badge --}}
                                <div>
                                    @if ($isCurrent)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-amber-500 text-slate-950 shadow-sm animate-pulse">
                                            <i class="bi bi-play-circle-fill"></i> (P) En Proceso
                                        </span>
                                    @elseif ($isCompleted)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="bi bi-check-circle-fill"></i> (C) Culminado
                                        </span>
                                    @elseif ($isObserved)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            (OBS) En Subsanación
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            (PTE) Pendiente
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Phase Title & Subtitle --}}
                            <div class="mt-4 space-y-1.5 flex-1">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    Fase {{ $phase->phase_number }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                                    {{ $phase->title }}
                                </h3>
                                @if ($phase->subtitle)
                                    <p class="text-xs text-slate-500 line-clamp-2">
                                        {{ $phase->subtitle }}
                                    </p>
                                @endif
                            </div>

                            {{-- Mini Progress & Indicator --}}
                            <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Avance</span>
                                <span class="font-bold {{ $isCurrent ? 'text-amber-700' : ($isCompleted ? 'text-emerald-700' : 'text-slate-600') }}">
                                    {{ $phase->progress_percentage }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    {{-- SECTION 2: CURRENT STAGE (P) SPOTLIGHT FOCUS CARD              --}}
    @if ($currentPhase)
    <section class="py-12 sm:py-16 bg-gradient-to-b from-slate-50 to-amber-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white rounded-3xl p-6 sm:p-10 lg:p-12 shadow-xl border border-amber-400/30 relative overflow-hidden">
                {{-- Decorative Glow --}}
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="bi bi-patch-check-fill text-9xl text-amber-400"></i>
                </div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    {{-- Left Details Column --}}
                    <div class="lg:col-span-8 space-y-5">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs font-extrabold uppercase tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                            Etapa en Ejecución Actual: Fase {{ $currentPhase->phase_number }} (P)
                        </div>

                        <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-snug">
                            {{ $currentPhase->title }}
                        </h2>

                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                            {{ $currentPhase->description ?? $currentPhase->subtitle }}
                        </p>

                        {{-- Metadata Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-white/10">
                            @if ($currentPhase->resolution_number)
                                <div class="flex items-start gap-2.5">
                                    <i class="bi bi-file-earmark-ruled text-amber-400 text-lg mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium">Resolución / Norma</p>
                                        <p class="text-xs sm:text-sm font-bold text-white">{{ $currentPhase->resolution_number }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($currentPhase->legal_basis)
                                <div class="flex items-start gap-2.5">
                                    <i class="bi bi-journal-bookmark text-sky-400 text-lg mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium">Marco Normativo</p>
                                        <p class="text-xs sm:text-sm font-bold text-white truncate max-w-[200px]" title="{{ $currentPhase->legal_basis }}">{{ $currentPhase->legal_basis }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($currentPhase->estimated_date)
                                <div class="flex items-start gap-2.5">
                                    <i class="bi bi-calendar-event text-emerald-400 text-lg mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium">Periodo Estimado</p>
                                        <p class="text-xs sm:text-sm font-bold text-white">{{ $currentPhase->estimated_date }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Progress Column --}}
                    <div class="lg:col-span-4 bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 flex flex-col items-center justify-center text-center space-y-4">
                        <div class="relative flex items-center justify-center">
                            {{-- Circular Progress SVG --}}
                            <svg class="w-32 h-32 -rotate-90">
                                <circle cx="64" cy="64" r="54" stroke="currentColor" stroke-width="10" class="text-white/10" fill="transparent" />
                                <circle cx="64" cy="64" r="54" stroke="currentColor" stroke-width="10" class="text-amber-400 transition-all duration-1000" fill="transparent"
                                    stroke-dasharray="339.29"
                                    stroke-dashoffset="{{ 339.29 - (339.29 * ($currentPhase->progress_percentage / 100)) }}"
                                    stroke-linecap="round" />
                            </svg>
                            <div class="absolute flex flex-col items-center justify-center text-center">
                                <span class="text-3xl font-black text-white">{{ $currentPhase->progress_percentage }}%</span>
                                <span class="text-[10px] uppercase font-bold text-amber-300">Completado</span>
                            </div>
                        </div>

                        <div class="text-center space-y-1">
                            <p class="text-xs font-bold text-white">Estado de la Etapa (P)</p>
                            <p class="text-xs text-slate-400">Avance consolidado de los medios de verificación y matrices CBC.</p>
                        </div>

                        @if ($currentPhase->file_path)
                            <a href="{{ Storage::url($currentPhase->file_path) }}" target="_blank"
                                class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20">
                                <i class="bi bi-file-earmark-arrow-down-fill text-sm"></i> Descargar Evidencias / Informe
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- SECTION 3: 7 CONDICIONES BÁSICAS DE CALIDAD (CBC) MATRIX       --}}
    @if ($cbcPhase && !empty($cbcPhase->milestones))
    <section class="py-16 sm:py-24 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-200 pb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-100">
                        Matriz de Evaluación Oficial
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-2">
                        Las 7 Condiciones Básicas de Calidad (CBC)
                    </h2>
                    <p class="text-sm text-slate-600 mt-1 max-w-2xl">
                        Estándares obligatorios exigidos por la Ley N° 30512 para asegurar la excelencia académica e infraestructura del IESTP.
                    </p>
                </div>
                <div class="text-xs font-bold px-4 py-2 bg-slate-100 text-slate-700 rounded-2xl border border-slate-200 flex items-center gap-2">
                    <i class="bi bi-shield-fill-check text-emerald-600 text-base"></i> RVM N° 276-2019-MINEDU
                </div>
            </div>

            {{-- 7 CBCs Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($cbcPhase->milestones as $cbc)
                    @php
                        $cbcProgress = $cbc['progress'] ?? 100;
                        $isCbcComplete = ($cbc['status'] ?? '') === 'completed' || $cbcProgress >= 100;
                    @endphp
                    <div class="bg-slate-50 hover:bg-white rounded-3xl p-6 border border-slate-200 hover:border-indigo-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div class="space-y-4">
                            {{-- CBC Header --}}
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-3 py-1 rounded-xl text-xs font-black bg-indigo-600 text-white shadow-sm">
                                    {{ $cbc['cbc_number'] ?? 'CBC' }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full {{ $isCbcComplete ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    <i class="bi {{ $isCbcComplete ? 'bi-check-circle-fill' : 'bi-hourglass-split' }}"></i>
                                    {{ $isCbcComplete ? 'Cumplido' : 'En Adecuación' }}
                                </span>
                            </div>

                            {{-- CBC Name --}}
                            <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                                {{ $cbc['name'] }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ $cbc['description'] }}
                            </p>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mt-6 pt-4 border-t border-slate-200/80 space-y-1.5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-500 font-medium">Nivel de Adecuación</span>
                                <span class="font-bold {{ $isCbcComplete ? 'text-emerald-600' : 'text-amber-600' }}">{{ $cbcProgress }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 {{ $isCbcComplete ? 'bg-emerald-500' : 'bg-gradient-to-r from-amber-400 to-amber-500' }}"
                                    style="width: {{ $cbcProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    {{-- SECTION 4: TABLE OF LICENSING PHASES & OFFICIAL DOCUMENTS      --}}
    <section class="py-16 sm:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Header --}}
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                    Transparencia y Trazabilidad
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Cuadro Detallado de Fases del Licenciamiento
                </h2>
                <p class="text-sm sm:text-base text-slate-600">
                    Consulte el detalle completo, estado de avance, periodos y marco legal de cada una de las 5 etapas del procedimiento:
                </p>
            </div>

            {{-- Table Container --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider font-bold">
                                <th class="py-4 px-6">N° / Código</th>
                                <th class="py-4 px-6">Fase del Proceso</th>
                                <th class="py-4 px-6">Estado / Etapa (P)</th>
                                <th class="py-4 px-6">Avance</th>
                                <th class="py-4 px-6">Periodo Estimado</th>
                                <th class="py-4 px-6 text-right">Documentación</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @foreach ($phases as $p)
                                @php
                                    $isRowCurrent = $p->is_current || ($currentPhase && $currentPhase->id === $p->id);
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ $isRowCurrent ? 'bg-amber-50/40 font-semibold' : '' }}">
                                    {{-- Number / Code --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="w-8 h-8 rounded-xl font-black text-xs flex items-center justify-center
                                                {{ $isRowCurrent ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                {{ $p->phase_number }}
                                            </span>
                                            <span class="text-xs text-slate-500 font-mono">{{ $p->code ?? "FASE-0{$p->phase_number}" }}</span>
                                        </div>
                                    </td>

                                    {{-- Title & Subtitle --}}
                                    <td class="py-4 px-6">
                                        <p class="font-bold text-slate-900">{{ $p->title }}</p>
                                        @if ($p->subtitle)
                                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $p->subtitle }}</p>
                                        @endif
                                    </td>

                                    {{-- Status & Tag (P) --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if ($isRowCurrent)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-500 text-slate-950 shadow-sm animate-pulse">
                                                <i class="bi bi-hourglass-split"></i> (P) Etapa Actual
                                            </span>
                                        @elseif ($p->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="bi bi-check-circle-fill"></i> (C) Culminado
                                            </span>
                                        @elseif ($p->status === 'observed')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                (OBS) En Subsanación
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                (PTE) Pendiente
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Progress Bar --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 bg-slate-200 h-2 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $p->status === 'completed' ? 'bg-emerald-500' : ($isRowCurrent ? 'bg-amber-500' : 'bg-slate-400') }}"
                                                    style="width: {{ $p->progress_percentage }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-slate-700">{{ $p->progress_percentage }}%</span>
                                        </div>
                                    </td>

                                    {{-- Estimated Date --}}
                                    <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-600">
                                        {{ $p->estimated_date ?? 'Por definir' }}
                                    </td>

                                    {{-- Actions / Documents --}}
                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        @if ($p->file_path)
                                            <a href="{{ Storage::url($p->file_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-bold transition-colors border border-indigo-200">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> Descargar
                                            </a>
                                        @elseif ($p->external_link)
                                            <a href="{{ $p->external_link }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                                                <i class="bi bi-box-arrow-up-right"></i> Enlace
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 italic">En trámite</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    {{-- SECTION 5: INSTITUTIONAL FAQ & LEGAL FRAMEWORK                 --}}
    <section class="py-16 sm:py-20 bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                    Preguntas Frecuentes
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    ¿Qué significa el Licenciamiento Institucional?
                </h2>
                <p class="text-sm text-slate-600">
                    Aspectos clave sobre el proceso de licenciamiento que lidera el IESTP Francisco Vigo Caballero.
                </p>
            </div>

            <div class="space-y-4" x-data="{ openFaq: 1 }">
                {{-- FAQ 1 --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-colors">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors">
                        <span class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-3">
                            <i class="bi bi-patch-question-fill text-indigo-600"></i> ¿Qué es el licenciamiento institucional para institutos tecnológicos?
                        </span>
                        <i class="bi text-slate-500 transition-transform duration-200" :class="openFaq === 1 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="p-5 text-sm text-slate-600 bg-white border-t border-slate-200 leading-relaxed space-y-2">
                        <p>
                            El Licenciamiento Institucional es el procedimiento obligatorio conducido por el <strong>Ministerio de Educación (MINEDU)</strong> para verificar que los Institutos de Educación Superior Tecnológicos Públicos y Privados cumplan con las <strong>Condiciones Básicas de Calidad (CBC)</strong> estipuladas en la Ley N° 30512 y su Reglamento.
                        </p>
                        <p>
                            Garantiza que la institución cuenta con infraestructura moderna, docentes calificados, planes de estudio pertinentes y sostenibilidad para formar profesionales técnicos de alto nivel.
                        </p>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-colors">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors">
                        <span class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-3">
                            <i class="bi bi-award-fill text-indigo-600"></i> ¿Por cuánto tiempo se otorga la Licencia Institucional?
                        </span>
                        <i class="bi text-slate-500 transition-transform duration-200" :class="openFaq === 2 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="p-5 text-sm text-slate-600 bg-white border-t border-slate-200 leading-relaxed">
                        <p>
                            La Licencia Institucional otorgada mediante Resolución Ministerial del MINEDU tiene una vigencia de <strong>6 años renovables</strong>, sujeta a evaluación periódica de la calidad educativa y el mantenimiento de las CBC.
                        </p>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-colors">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors">
                        <span class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-3">
                            <i class="bi bi-mortarboard-fill text-indigo-600"></i> ¿Qué beneficios obtienen los estudiantes y egresados?
                        </span>
                        <i class="bi text-slate-500 transition-transform duration-200" :class="openFaq === 3 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="p-5 text-sm text-slate-600 bg-white border-t border-slate-200 leading-relaxed">
                        <p>
                            Los estudiantes del IESTP Francisco Vigo Caballero acceden a títulos oficiales a <strong>Nombre de la Nación</strong> registrados en el padrón nacional del MINEDU, certificación modular progresiva, convalidación universitaria, acceso a becas PRONABEC y una bolsa de trabajo respaldada por convenios con el sector productivo regional y nacional.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('licensingApp', () => ({
            selectedPhaseId: {{ $currentPhase->id ?? ($phases->first()->id ?? 1) }},
            init() {
                // Initial interactive setup
            }
        }));
    });
</script>
@endpush
