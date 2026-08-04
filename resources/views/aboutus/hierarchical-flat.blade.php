@extends('layouts.app')

@section('title', 'Plana Jerárquica — IESTP Francisco Vigo Caballero')
@section('meta_description', 'Conoce la plana jerárquica y el equipo directivo del IESTP Francisco Vigo Caballero en
    Uchiza: Dirección General, Jefaturas de Unidad, Área de Calidad, Bienestar, Investigación y Coordinadores Académicos de
    Carrera.')
@section('meta_keywords', 'plana jerarquica, equipo directivo, dirección general, jefe unidad académica, área de
    calidad, bienestar y empleabilidad, coordinadores academicos, iestp francisco vigo caballero, uchiza, san martín')
@section('meta_robots', 'index, follow, max-image-preview:large')
@section('canonical_url', url('/nosotros/plana-jerarquica'))

@push('styles')
    {{-- Structured Data JSON-LD --}}
    @php
        $schemaPersons = [];
        $pos = 1;
        if ($director) {
            $schemaPersons[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'item' => [
                    '@type' => 'Person',
                    'name' => $director->names,
                    'jobTitle' => $director->job_position ?? 'Director General',
                    'email' => $director->email ?? null,
                    'telephone' => $director->phone ?? null,
                    'worksFor' => ['@type' => 'EducationalOrganization', 'name' => 'IESTP Francisco Vigo Caballero'],
                ],
            ];
        }
        foreach ($managementStaff as $s) {
            $schemaPersons[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'item' => [
                    '@type' => 'Person',
                    'name' => $s->names,
                    'jobTitle' => $s->job_position ?? 'Jefe de Unidad',
                    'email' => $s->email ?? null,
                    'telephone' => $s->phone ?? null,
                    'worksFor' => ['@type' => 'EducationalOrganization', 'name' => 'IESTP Francisco Vigo Caballero'],
                ],
            ];
        }
        foreach ($coordinators as $c) {
            if (!$c->user) {
                continue;
            }
            $schemaPersons[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'item' => [
                    '@type' => 'Person',
                    'name' => $c->user->names,
                    'jobTitle' => 'Coordinador Académico — ' . ($c->program->name ?? 'Programa de Estudios'),
                    'email' => $c->user->email ?? null,
                    'telephone' => $c->user->phone ?? null,
                    'worksFor' => ['@type' => 'EducationalOrganization', 'name' => 'IESTP Francisco Vigo Caballero'],
                ],
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
                "description": "Plana jerárquica y equipo directivo del IESTP Francisco Vigo Caballero de Uchiza.",
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
                "name": "Plana Jerárquica IESTP Francisco Vigo Caballero",
                "numberOfItems": {{ count($schemaPersons) }},
                "itemListElement": {!! json_encode($schemaPersons, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            }
        ]
    }
    </script>

    <style>
        .leader-card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .leader-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -12px rgba(30, 64, 175, 0.15);
        }

        .org-node {
            border: 1.5px solid #cbd5e1;
            transition: all 0.2s ease;
        }

        .org-node:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 20px -4px rgba(37, 99, 235, 0.2);
        }

        .org-node-dashed {
            border: 1.5px dashed #94a3b8;
        }

        .org-connector {
            border-left: 2px solid #94a3b8;
            border-bottom: 2px solid #94a3b8;
        }

        .hierarchy-step {
            transition: all 0.25s ease;
        }

        .hierarchy-step:hover {
            transform: scale(1.01);
        }

        [x-cloak] {
            display: none !important;
        }

        /* Organigrama connectors */
        .org-level-line::before {
            content: '';
            display: block;
            width: 2px;
            height: 24px;
            background: #94a3b8;
            margin: 0 auto;
        }

        .org-branch-container {
            position: relative;
        }

        .org-branch-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 24px;
            background: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div x-data="{
        activeTab: 'directory',
        searchQuery: '',
        categoryFilter: 'all',
        selectedLeader: null,
        modalOpen: false,
    
        openDetail(leader) {
            this.selectedLeader = leader;
            this.modalOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeDetail() {
            this.modalOpen = false;
            this.selectedLeader = null;
            document.body.classList.remove('overflow-hidden');
        }
    }" class="min-h-screen bg-slate-50 text-slate-800 pb-20">

        {{-- ═══ HERO SECTION ═══════════════════════════════════════════════════════════════ --}}
        <section
            class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white pt-14 pb-24 overflow-hidden"
            aria-label="Presentación de la Plana Jerárquica">
            {{-- Decorative Background --}}
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px);">
            </div>
            <div class="absolute -top-28 -right-28 w-96 h-96 bg-amber-500/15 rounded-full blur-3xl pointer-events-none">
            </div>
            <div class="absolute -bottom-28 -left-28 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-900/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    {{-- Title & Description --}}
                    <div class="lg:col-span-7">
                        <h1
                            class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight font-sans">
                            Plana Jerárquica y Equipo Directivo
                        </h1>
                        <p class="mt-5 text-base sm:text-lg text-slate-300 max-w-2xl font-normal leading-relaxed">
                            Las autoridades institucionales, jefes de unidad, área de calidad, bienestar, investigación y
                            coordinadores académicos de carrera que lideran el desarrollo educativo y el licenciamiento del
                            IESTP Francisco Vigo Caballero.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="#directorio" @click.prevent="activeTab = 'directory'"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5">
                                <i class="bi bi-people-fill"></i> Ver Directorio
                            </a>
                            <a href="#organigrama" @click.prevent="activeTab = 'organigrama'"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold text-sm rounded-xl transition-all duration-200 backdrop-blur-md">
                                <i class="bi bi-diagram-3"></i> Ver Organigrama
                            </a>
                        </div>
                    </div>

                    {{-- Metric Counters --}}
                    <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                        <div
                            class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                            <span
                                class="block text-3xl font-extrabold text-amber-400 font-display group-hover:scale-110 transition-transform duration-200">1</span>
                            <span class="text-xs text-slate-300 font-medium mt-1.5 block">Dirección General</span>
                            <div class="mt-2 w-6 h-0.5 bg-amber-500/50 mx-auto rounded-full"></div>
                        </div>
                        <div
                            class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                            <span
                                class="block text-3xl font-extrabold text-blue-400 font-display group-hover:scale-110 transition-transform duration-200">{{ count($managementStaff) }}</span>
                            <span class="text-xs text-slate-300 font-medium mt-1.5 block">Jefaturas y Unidades</span>
                            <div class="mt-2 w-6 h-0.5 bg-blue-500/50 mx-auto rounded-full"></div>
                        </div>
                        <div
                            class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                            <span
                                class="block text-3xl font-extrabold text-emerald-400 font-display group-hover:scale-110 transition-transform duration-200">{{ $coordinators->count() }}</span>
                            <span class="text-xs text-slate-300 font-medium mt-1.5 block">Coordinaciones de Carrera</span>
                            <div class="mt-2 w-6 h-0.5 bg-emerald-500/50 mx-auto rounded-full"></div>
                        </div>
                        <div
                            class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                            <span
                                class="block text-3xl font-extrabold text-indigo-400 font-display group-hover:scale-110 transition-transform duration-200">3</span>
                            <span class="text-xs text-slate-300 font-medium mt-1.5 block">Niveles Jerárquicos</span>
                            <div class="mt-2 w-6 h-0.5 bg-indigo-500/50 mx-auto rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══ TAB NAVIGATION ════════════════════════════════════════════════════════════ --}}
        <section id="directorio" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
            <div
                class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-1.5 border border-slate-200/80 flex flex-wrap sm:flex-nowrap gap-1.5">
                <button id="tab-directory" @click="activeTab = 'directory'"
                    :class="activeTab === 'directory' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/25' :
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                    class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="bi bi-people-fill text-base"></i>
                    <span class="hidden sm:inline">Directorio</span> Directivo
                </button>
                <button id="tab-organigrama" @click="activeTab = 'organigrama'"
                    :class="activeTab === 'organigrama' ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/25' :
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                    class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="bi bi-diagram-3-fill text-base"></i>
                    <span class="hidden sm:inline">Organigrama</span> Estructural
                </button>
                <button id="tab-cargos" @click="activeTab = 'cargos'"
                    :class="activeTab === 'cargos' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/25' :
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                    class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="bi bi-table text-base"></i>
                    <span class="hidden sm:inline">Cuadro</span> de Cargos
                </button>
            </div>
        </section>

        {{-- ═══ TAB 1: DIRECTORIO DIRECTIVO ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'directory'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

            {{-- Search & Filter Bar --}}
            <div
                class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-80">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" x-model="searchQuery" id="search-hierarchical"
                        placeholder="Buscar por nombre o cargo..."
                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all">
                    <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 flex-wrap">
                    <span
                        class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1 whitespace-nowrap">Nivel:</span>
                    <button @click="categoryFilter = 'all'"
                        :class="categoryFilter === 'all' ? 'bg-slate-900 text-white' :
                            'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap">
                        Todos
                    </button>
                    <button @click="categoryFilter = 'direccion'"
                        :class="categoryFilter === 'direccion' ? 'bg-amber-600 text-white' :
                            'bg-amber-50 text-amber-800 hover:bg-amber-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap">
                        <i class="bi bi-star-fill mr-1"></i>Alta Dirección
                    </button>
                    <button @click="categoryFilter = 'jefaturas'"
                        :class="categoryFilter === 'jefaturas' ? 'bg-blue-700 text-white' :
                            'bg-blue-50 text-blue-800 hover:bg-blue-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap">
                        <i class="bi bi-shield-check mr-1"></i>Jefaturas y Unidades
                    </button>
                    <button @click="categoryFilter = 'coordinadores'"
                        :class="categoryFilter === 'coordinadores' ? 'bg-emerald-600 text-white' :
                            'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap">
                        <i class="bi bi-mortarboard-fill mr-1"></i>Coordinaciones
                    </button>
                </div>
            </div>
            {{-- ── 1. ALTA DIRECCIÓN (Director General) ── --}}
            @if ($director)
                @php
                    $directorNames = strtolower($director->names);
                    $directorPosition = strtolower($director->job_position ?? 'Director General');
                @endphp
                <div x-show="(categoryFilter === 'all' || categoryFilter === 'direccion') && ('{{ $directorNames }}'.includes(searchQuery.toLowerCase()) || '{{ $directorPosition }}'.includes(searchQuery.toLowerCase()) || searchQuery === '')"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="relative rounded-3xl border-2 border-amber-400/60 p-6 sm:p-8 mb-10 overflow-hidden shadow-md"
                    style="background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(30,27,75,0.04) 100%);">
                    <div class="absolute inset-0 opacity-5"
                        style="background-image: radial-gradient(#f59e0b 1px, transparent 1px); background-size: 20px 20px;">
                    </div>
                    <div
                        class="absolute -top-16 -right-16 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl pointer-events-none">
                    </div>

                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 relative">
                        {{-- Avatar --}}
                        <div class="relative flex-shrink-0">
                            @if ($director->photo_profile)
                                <img src="{{ asset('storage/' . $director->photo_profile) }}"
                                    alt="Foto de {{ $director->names }}"
                                    class="w-28 h-28 rounded-2xl object-cover border-4 border-white shadow-xl">
                            @else
                                @php
                                    $dInitials = collect(explode(' ', $director->names))
                                        ->map(fn($p) => mb_substr($p, 0, 1))
                                        ->take(2)
                                        ->implode('');
                                @endphp
                                <div
                                    class="w-28 h-28 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 text-white font-extrabold text-4xl flex items-center justify-center shadow-xl font-display">
                                    {{ strtoupper($dInitials) }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 text-center md:text-left">
                            <div
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[11px] font-extrabold uppercase tracking-widest mb-3">
                                <i class="bi bi-award-fill text-amber-600"></i> PRIMER NIVEL · Máxima Autoridad
                                Institucional
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-sans leading-tight">
                                {{ $director->names }}
                            </h2>
                            <p class="text-base font-bold text-amber-700 mt-1">
                                {{ $director->job_position ?? 'Director General' }}
                            </p>
                            <p class="text-sm text-slate-600 mt-2 max-w-2xl leading-relaxed">
                                Máxima autoridad del IESTP Francisco Vigo Caballero. Responsable de la conducción
                                institucional, representación legal, planificación estratégica y licenciamiento ante el
                                MINEDU.
                            </p>

                            <div
                                class="mt-4 pt-4 border-t border-amber-200/60 flex flex-wrap items-center justify-center md:justify-start gap-3 text-xs">
                                @if ($director->dni)
                                    <span
                                        class="inline-flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-amber-200 shadow-sm text-slate-700">
                                        <i class="bi bi-card-heading text-amber-600"></i>
                                        <strong>DNI:</strong> {{ $director->dni }}
                                    </span>
                                @endif
                                @if ($director->email)
                                    <a href="mailto:{{ $director->email }}"
                                        class="inline-flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-amber-200 shadow-sm text-blue-700 hover:text-blue-900 font-medium transition-colors">
                                        <i class="bi bi-envelope text-amber-600"></i> {{ $director->email }}
                                    </a>
                                @endif
                                @if ($director->phone)
                                    <a href="tel:{{ $director->phone }}"
                                        class="inline-flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-amber-200 shadow-sm text-slate-700 font-medium hover:text-slate-900 transition-colors">
                                        <i class="bi bi-telephone text-amber-600"></i> {{ $director->phone }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex-shrink-0">
                            <button @click="openDetail({{ json_encode($director) }})"
                                class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="bi bi-person-badge-fill"></i>
                                Ver Ficha
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── 2. JEFATURAS, UNIDADES Y SECRETARÍA DE DIRECCIÓN ── --}}
            <div x-show="categoryFilter === 'all' || categoryFilter === 'jefaturas'" class="mb-10"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-xl bg-blue-700 flex items-center justify-center shadow-sm">
                        <i class="bi bi-shield-check text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 font-sans leading-tight">Segundo Nivel · Jefaturas de
                            Unidad, Área y Secretaría</h2>
                        <p class="text-xs text-slate-500">Gestión institucional, calidad, bienestar, investigación y
                            formación continua</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($managementStaff as $staff)
                        @php
                            $initials = collect(explode(' ', $staff->names))
                                ->map(fn($p) => mb_substr($p, 0, 1))
                                ->take(2)
                                ->implode('');
                            $sNames = strtolower($staff->names);
                            $sPos = strtolower($staff->job_position ?? '');
                        @endphp
                        <div x-show="'{{ $sNames }}'.includes(searchQuery.toLowerCase()) || '{{ $sPos }}'.includes(searchQuery.toLowerCase()) || searchQuery === ''"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100"
                            class="leader-card bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:border-blue-300">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-800 text-white font-extrabold text-base flex items-center justify-center shadow-md font-display flex-shrink-0">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200 whitespace-nowrap">
                                        <i class="bi bi-shield-fill mr-0.5 text-blue-600"></i> 2do Nivel
                                    </span>
                                </div>

                                <h3 class="text-sm font-bold text-slate-900 leading-snug font-sans">{{ $staff->names }}
                                </h3>
                                <p class="text-xs font-semibold text-blue-700 mt-1">
                                    {{ $staff->job_position ?? 'Jefe de Unidad' }}</p>

                                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                                    @if ($staff->dni)
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-card-heading text-slate-400 w-4 text-center"></i>
                                            <span><strong>DNI:</strong> {{ $staff->dni }}</span>
                                        </div>
                                    @endif
                                    @if ($staff->email)
                                        <div class="flex items-center gap-2 min-w-0">
                                            <i class="bi bi-envelope text-slate-400 w-4 text-center flex-shrink-0"></i>
                                            <a href="mailto:{{ $staff->email }}"
                                                class="hover:text-blue-600 transition-colors truncate">{{ $staff->email }}</a>
                                        </div>
                                    @endif
                                    @if ($staff->phone)
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-telephone text-slate-400 w-4 text-center"></i>
                                            <a href="tel:{{ $staff->phone }}"
                                                class="hover:text-blue-600 transition-colors">{{ $staff->phone }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Plaza
                                    PAP</span>
                                <button @click="openDetail({{ json_encode($staff) }})"
                                    class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1">
                                    Ver Ficha <i class="bi bi-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 text-slate-400 text-sm">
                            <i class="bi bi-inbox text-3xl block mb-2"></i>
                            No hay jefaturas registradas en el sistema.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ── 3. COORDINADORES ACADÉMICOS DE CARRERA ── --}}
            <div x-show="categoryFilter === 'all' || categoryFilter === 'coordinadores'" class="mb-10"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-600 flex items-center justify-center shadow-sm">
                        <i class="bi bi-mortarboard-fill text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 font-sans leading-tight">Segundo Nivel · Coordinadores
                            Académicos de Programa de Estudios</h2>
                        <p class="text-xs text-slate-500">Responsables de la supervisión académica por carrera técnica</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($coordinators as $coord)
                        @php
                            $user = $coord->user;
                            $program = $coord->program;
                            if (!$user) {
                                continue;
                            }

                            $initials = collect(explode(' ', $user->names))
                                ->map(fn($p) => mb_substr($p, 0, 1))
                                ->take(2)
                                ->implode('');
                            $cNames = strtolower($user->names);
                            $cProg = strtolower($program?->name ?? '');
                        @endphp
                        <div x-show="'{{ $cNames }}'.includes(searchQuery.toLowerCase()) || '{{ $cProg }}'.includes(searchQuery.toLowerCase()) || searchQuery === ''"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100"
                            class="leader-card bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:border-emerald-300">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-800 text-white font-extrabold text-base flex items-center justify-center shadow-md font-display flex-shrink-0">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 whitespace-nowrap">
                                        <i class="bi bi-mortarboard-fill mr-0.5 text-emerald-600"></i> Coord. Acad.
                                    </span>
                                </div>

                                <h3 class="text-sm font-bold text-slate-900 leading-snug font-sans">{{ $user->names }}
                                </h3>
                                <p class="text-xs font-semibold text-emerald-700 mt-1">
                                    Coordinador(a) Académico(a)
                                </p>
                                @if ($program)
                                    <div
                                        class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-semibold">
                                        <i class="bi bi-journal-bookmark text-emerald-600"></i>
                                        {{ $program->name }}
                                    </div>
                                @endif
                                @if ($coord->specialty)
                                    <div
                                        class="mt-1.5 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-medium">
                                        <i class="bi bi-book text-slate-400"></i>
                                        {{ $coord->specialty }}
                                    </div>
                                @endif

                                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                                    @if ($user->dni)
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-card-heading text-slate-400 w-4 text-center"></i>
                                            <span><strong>DNI:</strong> {{ $user->dni }}</span>
                                        </div>
                                    @endif
                                    @if ($user->email)
                                        <div class="flex items-center gap-2 min-w-0">
                                            <i class="bi bi-envelope text-slate-400 w-4 text-center flex-shrink-0"></i>
                                            <a href="mailto:{{ $user->email }}"
                                                class="hover:text-emerald-600 transition-colors truncate">{{ $user->email }}</a>
                                        </div>
                                    @endif
                                    @if ($user->phone)
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-telephone text-slate-400 w-4 text-center"></i>
                                            <a href="tel:{{ $user->phone }}"
                                                class="hover:text-emerald-600 transition-colors">{{ $user->phone }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Prog.
                                    Estudios</span>
                                <button @click="openDetail({{ json_encode($user) }})"
                                    class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors inline-flex items-center gap-1">
                                    Ver Ficha <i class="bi bi-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 text-slate-400 text-sm">
                            <i class="bi bi-inbox text-3xl block mb-2"></i>
                            No hay coordinadores académicos registrados.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════════════════════════════════════ --}}
        {{-- ═══ TAB 2: ORGANIGRAMA ESTRUCTURAL ═══════════════════════════════════════════ --}}
        {{-- ════════════════════════════════════════════════════════════════════════════════ --}}
        <div id="organigrama" x-show="activeTab === 'organigrama'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-slate-200 gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 font-sans">Organigrama Institucional</h2>
                        <p class="text-sm text-slate-500 mt-1">Estructura jerárquica y línea de autoridad del IESTP
                            Francisco Vigo Caballero — Uchiza</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-[11px] font-semibold">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-800 border border-amber-200 rounded-lg">
                            <span class="w-3 h-3 border border-amber-600 rounded-sm bg-amber-50 inline-block"></span> Línea
                            sólida = dependencia directa
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg">
                            <span class="w-3 h-3 border border-dashed border-slate-500 rounded-sm inline-block"></span>
                            Línea punteada = dependencia funcional
                        </span>
                    </div>
                </div>

                {{-- Organigrama Visual --}}
                <div class="overflow-x-auto pb-4">
                    <div class="min-w-[820px]">

                        {{-- ── PRIMER NIVEL ── --}}
                        <div class="relative mb-1">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-extrabold uppercase tracking-widest"
                                style="background: #fef08a; color: #713f12; border: 1px solid #ca8a04;">
                                PRIMER NIVEL — Alta Dirección
                            </div>
                        </div>
                        <div class="bg-amber-50/50 border border-amber-200 rounded-2xl p-5 mb-2">
                            <div class="flex items-start justify-center gap-6">
                                {{-- Dirección General (center) --}}
                                <div class="flex flex-col items-center gap-2">
                                    <div class="org-node bg-white rounded-xl px-5 py-3 text-center shadow-sm font-bold text-sm text-slate-900 border-amber-400"
                                        style="border-width: 2px; border-style: solid; border-color: #f59e0b; min-width: 160px;">
                                        <i class="bi bi-award-fill text-amber-500 text-base block mb-1"></i>
                                        DIRECCIÓN GENERAL
                                        @if ($director)
                                            <p class="text-[11px] font-normal text-slate-500 mt-0.5">
                                                {{ $director->names }}</p>
                                        @endif
                                    </div>
                                    {{-- Consejo Asesor connector --}}
                                    <div class="relative w-full flex justify-end pr-0 mt-1">
                                    </div>
                                </div>
                                {{-- Consejo Asesor (right) --}}
                                <div class="flex flex-col items-center gap-2 mt-4">
                                    <div class="org-node-dashed bg-white rounded-xl px-4 py-2.5 text-center text-xs text-slate-700 font-semibold"
                                        style="min-width: 130px;">
                                        CONSEJO ASESOR
                                    </div>
                                </div>
                            </div>

                            {{-- Concejo Estudiantil (below, left-aligned) --}}
                            <div class="flex justify-start mt-3 ml-8">
                                <div class="org-node-dashed bg-white rounded-xl px-4 py-2.5 text-xs text-slate-700 font-semibold text-center"
                                    style="min-width: 130px;">
                                    CONCEJO ESTUDIANTIL
                                </div>
                            </div>
                        </div>

                        {{-- Connector --}}
                        <div class="flex justify-center py-1">
                            <div class="w-0.5 h-6 bg-slate-400"></div>
                        </div>

                        {{-- ── SEGUNDO NIVEL ── --}}
                        <div class="relative mb-1">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-extrabold uppercase tracking-widest"
                                style="background: #bfdbfe; color: #1e3a8a; border: 1px solid #3b82f6;">
                                SEGUNDO NIVEL — Gestión y Unidades
                            </div>
                        </div>
                        <div class="bg-blue-50/40 border border-blue-200 rounded-2xl p-5 mb-2">
                            {{-- Secretaría de Dirección (top center) --}}
                            <div class="flex justify-center mb-4">
                                <div class="org-node bg-white rounded-xl px-5 py-3 text-center shadow-sm text-sm text-slate-900 border-blue-500"
                                    style="border-width: 1.5px; border-style: solid; min-width: 170px;">
                                    <i class="bi bi-building text-blue-600 text-sm block mb-1"></i>
                                    <span class="font-bold text-xs">SECRETARÍA DE DIRECCIÓN</span>
                                    @php $secDir = $managementStaff->first(fn($s) => str_contains(strtolower($s->job_position ?? ''), 'secretar')); @endphp
                                    @if ($secDir)
                                        <p class="text-[11px] text-slate-500 mt-0.5 font-normal">{{ $secDir->names }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Connector from Secretaría down --}}
                            <div class="flex justify-center mb-3">
                                <div class="w-0.5 h-4 bg-slate-400"></div>
                            </div>

                            {{-- Row of major units --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2 mb-4">
                                <div
                                    class="org-node-dashed bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors">
                                    ÁREA<br>ADMINISTRATIVA
                                </div>
                                <div
                                    class="org-node-dashed bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors">
                                    ÁREA DE<br>PRODUCCIÓN
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors border-blue-400"
                                    style="border-width: 1.5px; border-style: solid;">
                                    SECRETARIA<br>ACADÉMICA
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors border-blue-400"
                                    style="border-width: 1.5px; border-style: solid;">
                                    UNIDAD<br>ACADÉMICA
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors border-blue-400"
                                    style="border-width: 1.5px; border-style: solid;">
                                    ÁREA DE<br>CALIDAD
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors border-blue-400"
                                    style="border-width: 1.5px; border-style: solid;">
                                    UNIDAD DE<br>INVESTIGACIÓN
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors border-blue-400"
                                    style="border-width: 1.5px; border-style: solid;">
                                    UNIDAD DE BIENESTAR Y EMPLEABILIDAD
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-slate-700 hover:bg-blue-50 transition-colors border-blue-400"
                                    style="border-width: 1.5px; border-style: solid;">
                                    UNIDAD DE FORMACIÓN CONTINUA
                                </div>
                            </div>

                            {{-- Sub-units row --}}
                            <div class="grid grid-cols-3 gap-3 mt-3 max-w-2xl mx-auto">
                                <div
                                    class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-600 font-medium">
                                    IMAGEN INSTITUCIONAL
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-800 font-bold border-blue-300"
                                    style="border-width: 1.5px; border-style: solid;">
                                    @php $admin = $managementStaff->first(fn($s) => str_contains(strtolower($s->job_position ?? ''), 'administrador')); @endphp
                                    ADMINISTRACIÓN
                                    @if ($admin)
                                        <p class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $admin->names }}</p>
                                    @endif
                                </div>
                                <div class="org-node bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold border-blue-300"
                                    style="border-width: 1.5px; border-style: solid;">
                                    SECRETARIA DE UNIDAD ACADÉMICA
                                </div>
                            </div>

                            {{-- Connector to coordinadores --}}
                            <div class="flex justify-center mt-3">
                                <div class="w-0.5 h-4 bg-slate-400"></div>
                            </div>

                            {{-- Coordinadores Académicos row --}}
                            <div class="mt-1">
                                <p class="text-center text-[11px] font-bold text-blue-700 uppercase tracking-wider mb-2">
                                    Coordinadores Académicos de Programa de Estudios</p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                                    @forelse($coordinators as $coord)
                                        @if ($coord->user && $coord->program)
                                            <div class="org-node bg-blue-50 rounded-lg px-2 py-2.5 text-center text-[11px] font-semibold text-blue-900 hover:bg-blue-100 transition-colors border-blue-300"
                                                style="border-width: 1.5px; border-style: solid;">
                                                <i class="bi bi-mortarboard-fill text-blue-600 block mb-1"></i>
                                                COORD.<br>{{ strtoupper($coord->program->name) }}
                                                <p class="text-[10px] text-slate-500 font-normal mt-0.5 leading-tight">
                                                    {{ $coord->user->names }}</p>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="col-span-5 text-center text-xs text-slate-400 py-3">Sin coordinadores
                                            registrados</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Connector --}}
                        <div class="flex justify-center py-1">
                            <div class="w-0.5 h-6 bg-slate-400"></div>
                        </div>

                        {{-- ── TERCER NIVEL ── --}}
                        <div class="relative mb-1">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-extrabold uppercase tracking-widest"
                                style="background: #fecaca; color: #7f1d1d; border: 1px solid #f87171;">
                                TERCER NIVEL — Operación, Servicios y Soporte
                            </div>
                        </div>
                        <div class="bg-rose-50/40 border border-rose-200 rounded-2xl p-5">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                                {{-- Área Administrativa operativa --}}
                                <div class="space-y-2">
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        ASISTENTE ADMINISTRATIVO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        TESORERO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        ÁREA DE PATRIMONIO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        ÁREA DE ABASTECIMIENTO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        GUARDIANÍA DIURNA</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        ASISTENTE DE CAMPO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        PERSONAL DE CAMPO</div>
                                </div>
                                {{-- Personal de Servicio --}}
                                <div class="space-y-2">
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        PERSONAL DE SERVICIO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        PERSONAL DE SERVICIO II</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        PERSONAL DE SERVICIO III</div>
                                </div>
                                {{-- Docentes --}}
                                <div class="space-y-2">
                                    <div class="org-node bg-white rounded-lg px-2 py-3 text-center text-[11px] text-slate-900 font-bold border-slate-400"
                                        style="border-width: 1.5px; border-style: solid;">DOCENTES</div>
                                    <div class="ml-2 space-y-1.5">
                                        <div
                                            class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-600 font-semibold">
                                            ESPECIALIDAD</div>
                                        <div
                                            class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-600 font-semibold">
                                            EMPLEABILIDAD</div>
                                        <div
                                            class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-600 font-semibold">
                                            ESTUDIANTES</div>
                                    </div>
                                </div>
                                {{-- Spacer col --}}
                                <div class="hidden lg:block"></div>
                                {{-- Bienestar Servicios --}}
                                <div class="space-y-2">
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        SERVICIO MÉDICO (TÓPICO)</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        SERVICIO PSICOPEDAGÓGICO</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        SERVICIO DE BIENESTAR SOCIAL (CONSEJERÍA)</div>
                                    <div
                                        class="org-node-dashed bg-white rounded-lg px-2 py-2 text-center text-[11px] text-slate-700 font-semibold">
                                        SERVICIO DE EMPLEABILIDAD</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════════════════════ --}}
        {{-- ═══ TAB 3: CUADRO DE CARGOS DIRECTIVOS ═══════════════════════════════════════ --}}
        {{-- ════════════════════════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'cargos'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-slate-200 gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 font-sans">Cuadro de Cargos Jerárquicos</h2>
                        <p class="text-sm text-slate-500 mt-1">Distribución oficial de plazas directivas, jefaturas y
                            coordinaciones del IESTP Francisco Vigo Caballero — Cuadro PAP aprobado.</p>
                    </div>
                    <span
                        class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-900 font-bold text-xs rounded-xl whitespace-nowrap flex items-center gap-2">
                        <i class="bi bi-file-earmark-check-fill text-amber-600"></i>
                        Cuadro PAP Aprobado
                    </span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-700" id="tabla-cargos-jerarquicos">
                        <thead class="bg-slate-900 text-white">
                            <tr>
                                <th class="py-3.5 px-4 font-semibold rounded-tl-2xl">#</th>
                                <th class="py-3.5 px-4 font-semibold">Cargo Jerárquico / Directivo</th>
                                <th class="py-3.5 px-3 text-center font-semibold">Nivel</th>
                                <th class="py-3.5 px-3 font-semibold">Situación Laboral</th>
                                <th class="py-3.5 px-3 font-semibold">Condición en Cargo</th>
                                <th class="py-3.5 px-3 font-semibold rounded-tr-2xl">Área / Programa Asignado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr class="hover:bg-amber-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-amber-600">01</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-extrabold text-slate-900">Director General</span>
                                    @if ($director)
                                        <span
                                            class="block text-[11px] text-slate-500 font-normal">{{ $director->names }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[11px]">1°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 font-semibold text-slate-800">Dirección General Institucional</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">02</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800">Secretaría de Dirección General</span>
                                    @php $sd = $managementStaff->first(fn($s) => str_contains(strtolower($s->job_position ?? ''), 'secretar')); @endphp
                                    @if ($sd)
                                        <span
                                            class="block text-[11px] text-slate-500 font-normal">{{ $sd->names }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Secretaría — Dirección General</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">03</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800">Administrador del IESTP</span>
                                    @php $adm = $managementStaff->first(fn($s) => str_contains(strtolower($s->job_position ?? ''), 'administrador')); @endphp
                                    @if ($adm)
                                        <span
                                            class="block text-[11px] text-slate-500 font-normal">{{ $adm->names }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Nombrado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Área Administrativa General</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">04</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">Jefe de Unidad Académica</td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Unidad Académica</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">05</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">Secretaria Académica</td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Secretaría Académica</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">06</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">Jefe del Área de Calidad Institucional
                                </td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Área de Calidad</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">07</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">Jefe de Unidad de Investigación</td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Unidad de Investigación</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">08</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">Jefe de Unidad de Bienestar y
                                    Empleabilidad</td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Unidad de Bienestar y Empleabilidad</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-blue-600">09</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">Jefe de Unidad de Formación Continua
                                </td>
                                <td class="py-3.5 px-3 text-center"><span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[11px]">2°</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                </td>
                                <td class="py-3.5 px-3"><span
                                        class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700">Unidad de Formación Continua</td>
                            </tr>

                            {{-- Coordinadores Académicos --}}
                            @php $coordNum = 10; @endphp
                            @foreach ($coordinators as $coord)
                                @if ($coord->user && $coord->program)
                                    <tr class="hover:bg-emerald-50/40 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-emerald-600">
                                            {{ str_pad($coordNum++, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="font-semibold text-slate-800">Coordinador(a) Académico(a)</span>
                                            <span
                                                class="block text-[11px] text-slate-500 font-normal">{{ $coord->user->names }}</span>
                                        </td>
                                        <td class="py-3.5 px-3 text-center"><span
                                                class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">2°</span>
                                        </td>
                                        <td class="py-3.5 px-3"><span
                                                class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-semibold">Contratado</span>
                                        </td>
                                        <td class="py-3.5 px-3"><span
                                                class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Encargado</span>
                                        </td>
                                        <td class="py-3.5 px-3 text-slate-700">{{ $coord->program->name }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-900 text-white">
                            <tr>
                                <td class="py-3.5 px-4 text-amber-400 font-extrabold uppercase text-xs rounded-bl-2xl"
                                    colspan="2">
                                    Total Plazas Jerárquicas y Coordinaciones
                                </td>
                                <td class="py-3.5 px-3 text-center text-amber-400 font-extrabold">
                                    {{ 9 + $coordinators->count() }}</td>
                                <td class="py-3.5 px-3 text-xs text-slate-300 font-normal rounded-br-2xl" colspan="3">
                                    Cuadro PAP — Plana Jerárquica IESTP Francisco Vigo Caballero · Uchiza
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══ DETAIL MODAL ════════════════════════════════════════════════════════════════ --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title-jerarquica" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                {{-- Overlay --}}
                <div x-show="modalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="closeDetail()"
                    class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity">
                </div>

                {{-- Modal Panel --}}
                <div x-show="modalOpen" x-transition:enter="ease-out duration-250"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-10">

                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 p-6 text-white">
                        <button @click="closeDetail()" id="modal-close-jerarquica"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition-colors">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>

                        <template x-if="selectedLeader">
                            <div class="flex items-center gap-4 pr-10">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-700 text-white font-extrabold text-xl flex items-center justify-center shadow-lg flex-shrink-0 font-display">
                                    <span
                                        x-text="selectedLeader.names ? selectedLeader.names.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase() : 'PJ'"></span>
                                </div>
                                <div>
                                    <span
                                        class="text-[11px] font-bold uppercase tracking-widest text-amber-300/80 block mb-0.5">Ficha
                                        Directiva</span>
                                    <h3 class="text-lg font-extrabold text-white leading-tight font-sans"
                                        x-text="selectedLeader.names" id="modal-title-jerarquica"></h3>
                                    <p class="text-sm text-amber-300 font-semibold mt-0.5"
                                        x-text="selectedLeader.job_position || 'Personal Jerárquico'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Modal Body --}}
                    <template x-if="selectedLeader">
                        <div class="p-6 space-y-4">
                            <div class="bg-slate-50 rounded-2xl border border-slate-100 p-4 space-y-3 text-xs">
                                <div class="flex items-center justify-between gap-3 py-1 border-b border-slate-200/60">
                                    <span class="font-semibold text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-card-heading text-slate-400"></i> DNI
                                    </span>
                                    <span class="font-bold text-slate-800 font-mono"
                                        x-text="selectedLeader.dni || 'No registrado'"></span>
                                </div>
                                <div class="flex items-center justify-between gap-3 py-1 border-b border-slate-200/60">
                                    <span class="font-semibold text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-envelope text-slate-400"></i> Correo
                                    </span>
                                    <a :href="'mailto:' + selectedLeader.email"
                                        class="font-bold text-blue-600 hover:underline truncate max-w-[200px]"
                                        x-text="selectedLeader.email || 'No registrado'"></a>
                                </div>
                                <div class="flex items-center justify-between gap-3 py-1 border-b border-slate-200/60">
                                    <span class="font-semibold text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-telephone text-slate-400"></i> Teléfono
                                    </span>
                                    <a :href="'tel:' + selectedLeader.phone"
                                        class="font-bold text-slate-800 hover:text-blue-600 transition-colors"
                                        x-text="selectedLeader.phone || 'No registrado'"></a>
                                </div>
                                <div class="flex items-center justify-between gap-3 py-1">
                                    <span class="font-semibold text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-geo-alt text-slate-400"></i> Sede
                                    </span>
                                    <span class="font-bold text-slate-800">IESTP FVC · Uchiza</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 justify-between">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-900 border border-amber-200 rounded-xl text-[11px] font-bold">
                                    <i class="bi bi-star-fill text-amber-600 text-xs"></i> Cargo Jerárquico Institucional
                                </span>
                                <button @click="closeDetail()"
                                    class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
@endsection
