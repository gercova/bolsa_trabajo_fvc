@extends('layouts.app')

@section('title', 'Plana Administrativa — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO & OpenGraph Meta Tags --}}
    <meta name="description"
        content="Conoce a la plana administrativa, cuerpo directivo y equipo de soporte del IESTP Francisco Vigo Caballero en Uchiza. Estructura organizacional, organigrama y cuadro de cargos institucionales.">
    <meta name="keywords"
        content="plana administrativa, equipo administrativo, dirección general, organigrama institucional, personal de apoyo, iestp francisco vigo caballero, uchiza, gestión académica">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- OpenGraph Meta Tags --}}
    <meta property="og:title" content="Plana Administrativa y Equipo de Gestión — IESTP Francisco Vigo Caballero">
    <meta property="og:description"
        content="Conoce a nuestro equipo directivo, administrativo y de soporte comprometido con el desarrollo institucional y la atención de calidad en Uchiza.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($enterprise->logo_path ?? 'img/logo.png') }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Plana Administrativa — IESTP Francisco Vigo Caballero">
    <meta name="twitter:description"
        content="Conoce al cuerpo administrativo y equipo de gestión institucional del IESTP Francisco Vigo Caballero.">
    <meta name="twitter:image" content="{{ asset($enterprise->logo_path ?? 'img/logo.png') }}">

    <style>
        .staff-card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .staff-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -15px rgba(30, 58, 138, 0.15);
        }

        .org-box-solid {
            background-color: #ffffff;
            border: 2px solid #1e293b;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            transition: all 0.25s ease;
        }

        .org-box-dashed {
            background-color: #f8fafc;
            border: 2px dashed #64748b;
            transition: all 0.25s ease;
        }

        .org-box-solid:hover,
        .org-box-dashed:hover {
            transform: translateY(-3px) scale(1.02);
            border-color: #2563eb;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- Structured Data JSON-LD --}}
    @php
        $schemaItemList = [];
        $pos = 1;
        foreach ($staffs as $staff) {
            $schemaItemList[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'item' => [
                    '@type' => 'Person',
                    'name' => $staff->names,
                    'jobTitle' => $staff->job_position ?? 'Personal Administrativo',
                    'email' => $staff->email ?? null,
                    'telephone' => $staff->phone ?? null,
                    'worksFor' => [
                        '@type' => 'EducationalOrganization',
                        'name' => 'IESTP Francisco Vigo Caballero',
                    ],
                ]
            ];
        }
    @endphp
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "EducationalOrganization",
                "@id": "{{ config('app.url') }}/#organization",
                "name": "IESTP Francisco Vigo Caballero",
                "url": "{{ config('app.url') }}",
                "description": "Plana administrativa y personal de gestión del IESTP Francisco Vigo Caballero de Uchiza.",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
                    "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
                    "addressRegion": "San Martín",
                    "addressCountry": "PE"
                }
            },
            {
                "@type": "ItemList",
                "name": "Plana Administrativa IESTP Francisco Vigo Caballero",
                "numberOfItems": {{ count($schemaItemList) }},
                "itemListElement": {!! json_encode($schemaItemList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            }
        ]
    }
    </script>
@endpush

@section('content')
<div x-data="{
    activeTab: 'directory',
    searchQuery: '',
    levelFilter: 'all',
    selectedStaff: null,
    modalOpen: false,

    openDetail(staff) {
        this.selectedStaff = staff;
        this.modalOpen = true;
    },

    closeDetail() {
        this.modalOpen = false;
        this.selectedStaff = null;
    }
}" class="min-h-screen bg-slate-50 text-slate-800 pb-20">

    {{-- ═══ HERO SECTION ═══════════════════════════════════════════════════════════════ --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white pt-14 pb-20 overflow-hidden">
        {{-- Background decorative shapes --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="flex items-center text-xs text-blue-200/80 mb-6 gap-2">
                <a href="{{ route('inicio') }}" class="hover:text-white transition-colors">Inicio</a>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <span class="text-blue-300 font-medium">Nosotros</span>
                <i class="bi bi-chevron-right text-[10px]"></i>
                <span class="text-white font-semibold">Plana Administrativa</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 backdrop-blur-md text-blue-300 text-xs font-semibold uppercase tracking-wider mb-4">
                        <i class="bi bi-building-gear text-amber-400"></i>
                        IESTP Francisco Vigo Caballero — Uchiza
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight font-sans leading-tight">
                        Plana Administrativa y <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-amber-300 to-amber-200 font-display italic">Equipo de Gestión</span>
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl font-normal leading-relaxed">
                        Conoce al personal directivo, administrativo y de servicios que impulsa la gestión institucional, garantizando una atención eficiente, transparente y de excelencia a nuestra comunidad educativa.
                    </p>
                </div>

                {{-- Metric Counters --}}
                <div class="lg:col-span-4 grid grid-cols-2 gap-4">
                    <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                        <span class="block text-3xl font-extrabold text-amber-400 font-display">36</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">Plazas Institucionales</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                        <span class="block text-3xl font-extrabold text-blue-400 font-display">3</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">Niveles Organizacionales</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                        <span class="block text-3xl font-extrabold text-emerald-400 font-display">{{ count($staffs) }}</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">Personal Registrado</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                        <span class="block text-3xl font-extrabold text-indigo-400 font-display">5</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">Programas Atendidos</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ MAIN NAVIGATION TABS ═══════════════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-2 border border-slate-200/80 flex flex-wrap sm:flex-nowrap gap-2">
            <button @click="activeTab = 'directory'"
                :class="activeTab === 'directory' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-people-fill text-base"></i>
                <span>Directorio de Personal</span>
            </button>
            <button @click="activeTab = 'organigram'"
                :class="activeTab === 'organigram' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-diagram-3-fill text-base"></i>
                <span>Estructura Organigrama</span>
            </button>
            <button @click="activeTab = 'cargos'"
                :class="activeTab === 'cargos' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-table text-base"></i>
                <span>Cuadro Resumen de Cargos</span>
            </button>
        </div>
    </section>

    {{-- ═══ TAB 1: STAFF DIRECTORY ═══════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'directory'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        {{-- Filter & Search Header --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                <input type="text" x-model="searchQuery" placeholder="Buscar por nombre, cargo o correo..."
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                <button x-show="searchQuery.length > 0" @click="searchQuery = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>

            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap mr-1">Filtrar:</span>
                <button @click="levelFilter = 'all'"
                    :class="levelFilter === 'all' ? 'bg-slate-900 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap">
                    Todos ({{ count($staffs) }})
                </button>
                <button @click="levelFilter = 'directivos'"
                    :class="levelFilter === 'directivos' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap">
                    Dirección & Jefaturas
                </button>
                <button @click="levelFilter = 'apoyo'"
                    :class="levelFilter === 'apoyo' ? 'bg-amber-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap">
                    Asistentes & Apoyo
                </button>
            </div>
        </div>

        {{-- Staff Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($staffs as $staff)
                @php
                    $job = strtolower($staff->job_position ?? '');
                    $isDirector = str_contains($job, 'director');
                    $isAdmin = str_contains($job, 'administrador');
                    $isSecretary = str_contains($job, 'secretaria');
                    $isSupport = str_contains($job, 'asistente') || str_contains($job, 'auxiliar') || str_contains($job, 'seguridad') || str_contains($job, 'servicio');

                    $category = $isDirector || $isAdmin ? 'directivos' : ($isSupport ? 'apoyo' : 'directivos');

                    $initials = collect(explode(' ', $staff->names))
                        ->map(fn($part) => mb_substr($part, 0, 1))
                        ->take(2)
                        ->implode('');
                @endphp

                <div x-show="(levelFilter === 'all' || levelFilter === '{{ $category }}') && ('{{ strtolower($staff->names) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($staff->job_position ?? '') }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($staff->email ?? '') }}'.includes(searchQuery.toLowerCase()))"
                    class="staff-card bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="relative">
                                @if($staff->photo_profile)
                                    <img src="{{ asset('storage/' . $staff->photo_profile) }}" alt="{{ $staff->names }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-md">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-800 text-white font-extrabold text-xl flex items-center justify-center shadow-md font-display">
                                        {{ strtoupper($initials) }}
                                    </div>
                                @endif
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Activo"></span>
                            </div>

                            @if($isDirector)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <i class="bi bi-star-fill text-amber-500 mr-1"></i> Alta Dirección
                                </span>
                            @elseif($isAdmin)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    <i class="bi bi-shield-check mr-1"></i> Jefatura
                                </span>
                            @elseif($isSecretary)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                    <i class="bi bi-file-earmark-text mr-1"></i> Secretaría
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="bi bi-person-gear mr-1"></i> Soporte / Apoyo
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 leading-snug font-sans">
                            {{ $staff->names }}
                        </h3>
                        <p class="text-sm font-semibold text-blue-600 mt-1">
                            {{ $staff->job_position ?? 'Personal Administrativo' }}
                        </p>

                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600">
                            @if($staff->dni)
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-card-heading text-slate-400"></i>
                                    <span><strong>DNI:</strong> {{ $staff->dni }}</span>
                                </div>
                            @endif
                            @if($staff->email)
                                <div class="flex items-center gap-2 truncate">
                                    <i class="bi bi-envelope text-slate-400"></i>
                                    <a href="mailto:{{ $staff->email }}" class="hover:text-blue-600 transition-colors truncate">
                                        {{ $staff->email }}
                                    </a>
                                </div>
                            @endif
                            @if($staff->phone)
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-telephone text-slate-400"></i>
                                    <a href="tel:{{ $staff->phone }}" class="hover:text-blue-600 transition-colors">
                                        {{ $staff->phone }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">IESTP FVC</span>
                        <button @click="openDetail({{ json_encode($staff) }})"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
                            <span>Ver Ficha Completa</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-sm">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="bi bi-person-exclamation"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No se encontraron registros</h3>
                    <p class="text-sm text-slate-500 mt-1">Actualmente no hay personal administrativo asignado que coincida con los criterios.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══ TAB 2: ORGANIGRAMA ESTRUCTURAL ═════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'organigram'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-slate-200 gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 font-sans">Estructura Organizacional Institucional</h2>
                    <p class="text-sm text-slate-600 mt-1">Niveles jerárquicos, áreas de gestión y dependencias operativas del IESTP Francisco Vigo Caballero.</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-900 border border-amber-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Nivel 1: Alta Dirección
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-900 border border-blue-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Nivel 2: Gestión & Unidades
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-900 border border-emerald-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Nivel 3: Servicios & Apoyo
                    </span>
                </div>
            </div>

            {{-- Visual Hierarchy Blocks --}}
            <div class="space-y-8">

                {{-- PRIMER NIVEL --}}
                <div class="bg-amber-500/10 border-2 border-amber-300/60 rounded-2xl p-6 relative">
                    <div class="absolute -top-3.5 left-6 bg-amber-500 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        Primer Nivel — Alta Dirección & Gobernanza
                    </div>
                    <div class="pt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="org-box-solid p-4 text-center rounded-xl bg-white border-2 border-amber-600">
                            <span class="text-xs font-extrabold text-amber-700 uppercase tracking-wide block">Dirección General</span>
                            <span class="text-sm font-bold text-slate-900 mt-1 block">Teodorico Ganoza Medina</span>
                            <span class="text-[11px] text-slate-500 block">Máxima Autoridad Institucional</span>
                        </div>
                        <div class="org-box-dashed p-4 text-center rounded-xl bg-white">
                            <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wide block">Consejo Asesor</span>
                            <span class="text-xs text-slate-500 mt-1 block">Órgano Consultivo e Institucional</span>
                        </div>
                        <div class="org-box-dashed p-4 text-center rounded-xl bg-white">
                            <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wide block">Concejo Estudiantil</span>
                            <span class="text-xs text-slate-500 mt-1 block">Representación de Estudiantes</span>
                        </div>
                    </div>
                </div>

                {{-- SEGUNDO NIVEL --}}
                <div class="bg-blue-500/10 border-2 border-blue-300/60 rounded-2xl p-6 relative">
                    <div class="absolute -top-3.5 left-6 bg-blue-600 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        Segundo Nivel — Gestión, Unidades & Coordinaciones Académicas
                    </div>
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Secretaría de Dirección</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Soporte Administrativo Directivo</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Área Administrativa</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Gestión de Recursos y Personal</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Unidad Académica</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Planificación & Calidad Curricular</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Secretaría Académica</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Registros y Certificaciones</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Área de Calidad</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Licenciamiento & Acreditación</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Unidad de Investigación</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Proyectos de Innovación</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Unidad de Bienestar & Empleabilidad</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Atención Estudiantil y Bolsa</span>
                        </div>
                        <div class="org-box-solid p-3.5 rounded-xl bg-white">
                            <span class="text-xs font-bold text-blue-900 block">Unidad de Formación Continua</span>
                            <span class="text-xs text-slate-500 block mt-0.5">Capacitaciones Especializadas</span>
                        </div>
                    </div>

                    {{-- Sub-bloque de coordinadores --}}
                    <div class="mt-6 pt-6 border-t border-blue-200/80">
                        <span class="text-xs font-extrabold text-blue-800 uppercase tracking-wider block mb-3">Coordinaciones de Programas de Estudio</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                            <div class="p-3 bg-white rounded-xl border border-slate-200 text-center">
                                <span class="text-[11px] font-bold text-slate-800 block">Coord. Producción Agropecuaria</span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-200 text-center">
                                <span class="text-[11px] font-bold text-slate-800 block">Coord. Enfermería Técnica</span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-200 text-center">
                                <span class="text-[11px] font-bold text-slate-800 block">Coord. Adm. Redes y Com.</span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-200 text-center">
                                <span class="text-[11px] font-bold text-slate-800 block">Coord. Asistencia Administrativa</span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-200 text-center">
                                <span class="text-[11px] font-bold text-slate-800 block">Coord. Manejo Forestal / RN</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TERCER NIVEL --}}
                <div class="bg-emerald-500/10 border-2 border-emerald-300/60 rounded-2xl p-6 relative">
                    <div class="absolute -top-3.5 left-6 bg-emerald-600 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        Tercer Nivel — Operación, Servicios & Soporte Institucional
                    </div>
                    <div class="pt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        {{-- Área de Administración --}}
                        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                            <h4 class="text-xs font-extrabold text-emerald-800 uppercase tracking-wide border-b pb-2 mb-3">Administración & Logística</h4>
                            <ul class="space-y-2 text-xs text-slate-700">
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Asistente Administrativo</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Tesorería y Caja</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Área de Patrimonio</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Área de Abastecimiento</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Guardianía Diurna / Nocturna</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Personal de Campo y Servicios</li>
                            </ul>
                        </div>

                        {{-- Área Docente & Estudiantes --}}
                        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                            <h4 class="text-xs font-extrabold text-emerald-800 uppercase tracking-wide border-b pb-2 mb-3">Cuerpo Docente & Estudiantes</h4>
                            <ul class="space-y-2 text-xs text-slate-700">
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Docentes de Módulos Profesionales</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Docentes Transversales (Empleabilidad)</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Estudiantes de Educación Superior</li>
                            </ul>
                        </div>

                        {{-- Servicios de Bienestar --}}
                        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                            <h4 class="text-xs font-extrabold text-emerald-800 uppercase tracking-wide border-b pb-2 mb-3">Servicios de Bienestar & Salud</h4>
                            <ul class="space-y-2 text-xs text-slate-700">
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Servicio Médico (Tópico Institucional)</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Servicio Psicopedagógico</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Bienestar Social y Consejería</li>
                                <li class="flex items-center gap-2"><i class="bi bi-dot text-emerald-600 text-lg"></i> Inserción Laboral y Empleabilidad</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ═══ TAB 3: CUADRO DE CARGOS ═════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'cargos'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-slate-200 gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 font-sans">Cuadro Institucional de Cargos y Personal</h2>
                    <p class="text-sm text-slate-600 mt-1">Resumen detallado de plazas institucionales, condición laboral y asignación por programa de estudios.</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-xs font-bold flex items-center gap-2">
                    <i class="bi bi-info-circle-fill text-blue-600 text-base"></i>
                    <span>Total General: 36 Plazas Cuadro PAP</span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-900 text-white font-semibold">
                        <tr>
                            <th class="py-3.5 px-4">Cargos Institucionales</th>
                            <th class="py-3.5 px-3 text-center">Total</th>
                            <th class="py-3.5 px-3">Situación Laboral</th>
                            <th class="py-3.5 px-3">Condición en el Cargo</th>
                            <th class="py-3.5 px-2 text-center bg-blue-950">P.A.</th>
                            <th class="py-3.5 px-2 text-center bg-blue-950">E.T.</th>
                            <th class="py-3.5 px-2 text-center bg-blue-950">A.R. y C.</th>
                            <th class="py-3.5 px-2 text-center bg-blue-950">A.A.</th>
                            <th class="py-3.5 px-2 text-center bg-blue-950">MA y RN</th>
                            <th class="py-3.5 px-3 text-center bg-slate-800">Pers. Adm.</th>
                            <th class="py-3.5 px-3 text-center bg-slate-800">Pers. Apoyo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        {{-- Rows matching Image 2 --}}
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-bold text-slate-900">Director General</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-semibold text-slate-800">Jefe de Unidad Académica</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-semibold text-slate-800">Secretaría Académica</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Coordinador de Producción Agropecuaria</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Coordinador de Enfermería Técnica</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Coordinador de Adm. de Redes y Comunicación</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Coordinador de Asistencia Administrativa</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Coordinador de Medio Ambiente y Rec. Naturales</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Jefe del Área Administrativa</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center font-bold text-blue-600">1</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Administrador</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Apoyo (Secretaría de Dirección)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="text-slate-400">—</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Servicio I (Tco. Campo / Prod.)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Servicio II (Conserje y Portería)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Guardianía (Nocturna)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Secretaría de Unidad Académica</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span></td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                            <td class="py-3 px-3 text-center">—</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Apoyo (Asistente Administrativo)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="text-slate-400">—</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Apoyo (Asistente Abastecimiento)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="text-slate-400">—</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Apoyo (Guardianía Diurna)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="text-slate-400">—</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                        </tr>
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-800">Personal de Apoyo (Personal de Campo)</td>
                            <td class="py-3 px-3 text-center font-bold">1</td>
                            <td class="py-3 px-3"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span></td>
                            <td class="py-3 px-3"><span class="text-slate-400">—</span></td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-2 text-center">—</td>
                            <td class="py-3 px-3 text-center">—</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">1</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-900 text-white font-bold">
                        <tr>
                            <td class="py-3.5 px-4 text-amber-400 font-extrabold uppercase">Total Plazas PAP</td>
                            <td class="py-3.5 px-3 text-center text-amber-400 font-extrabold text-sm">36</td>
                            <td class="py-3.5 px-3 font-normal text-xs text-slate-300" colspan="2">Consolidado Institucional de Personal</td>
                            <td class="py-3.5 px-2 text-center text-blue-300">5</td>
                            <td class="py-3.5 px-2 text-center text-blue-300">5</td>
                            <td class="py-3.5 px-2 text-center text-blue-300">5</td>
                            <td class="py-3.5 px-2 text-center text-blue-300">5</td>
                            <td class="py-3.5 px-2 text-center text-blue-300">5</td>
                            <td class="py-3.5 px-3 text-center text-emerald-300">5</td>
                            <td class="py-3.5 px-3 text-center text-emerald-300">5</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                <p><strong>Leyenda:</strong> P.A. (Producción Agropecuaria), E.T. (Enfermería Técnica), A.R. y C. (Adm. de Redes y Com.), A.A. (Asistencia Adm.), MA y RN (Medio Ambiente y Recursos Naturales).</p>
                <span class="font-semibold text-slate-600">Fuente: Cuadro PAP Aprobado — IESTP Francisco Vigo Caballero</span>
            </div>
        </div>

    </div>

    {{-- ═══ STAFF DETAIL MODAL ════════════════════════════════════════════════════════ --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click="closeDetail()" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-3xl shadow-2xl border border-slate-100 relative">

                <button @click="closeDetail()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>

                <template x-if="selectedStaff">
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-800 text-white font-extrabold text-xl flex items-center justify-center shadow-md font-display">
                                <span x-text="selectedStaff.names ? selectedStaff.names.substring(0,2).toUpperCase() : 'AP'"></span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 font-sans" x-text="selectedStaff.names"></h3>
                                <p class="text-sm font-semibold text-blue-600" x-text="selectedStaff.job_position || 'Personal Administrativo'"></p>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 mt-1">
                                    <i class="bi bi-check-circle-fill mr-1"></i> Personal Activo
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                            <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                <span class="font-semibold text-slate-500">Documento DNI:</span>
                                <span class="font-bold text-slate-800" x-text="selectedStaff.dni || 'No registrado'"></span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                <span class="font-semibold text-slate-500">Correo Institucional:</span>
                                <a :href="'mailto:' + selectedStaff.email" class="font-bold text-blue-600 hover:underline" x-text="selectedStaff.email || 'No registrado'"></a>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-slate-200/60">
                                <span class="font-semibold text-slate-500">Teléfono de Contacto:</span>
                                <span class="font-bold text-slate-800" x-text="selectedStaff.phone || 'No registrado'"></span>
                            </div>
                            <div class="flex justify-between items-center py-1">
                                <span class="font-semibold text-slate-500">Oficina / Área:</span>
                                <span class="font-bold text-slate-800">Sede Principal Uchiza</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                            <button @click="closeDetail()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                                Cerrar Ficha
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection
