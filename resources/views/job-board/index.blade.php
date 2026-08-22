@extends('layouts.app')

{{-- ════ SEO FULL SUITE ════════════════════════════════════════════════════ --}}
@section('title', 'Bolsa de Trabajo Técnico en Perú — IESTP Francisco Vigo Caballero · Uchiza')
@section('meta_description', 'Encuentra ofertas de empleo técnico en Administración de Redes, Enfermería, Producción Agropecuaria, Manejo Forestal y Asistencia Administrativa en Perú. Bolsa de Trabajo institucional del IESTP Francisco Vigo Caballero — Uchiza, San Martín.')
@section('meta_keywords', 'bolsa de trabajo tecnico peru, empleo uchiza, practicas profesionales san martin, tecnico redes comunicaciones, asistente administrativo, enfermeria tecnica, manejo forestal, produccion agropecuaria, iestp francisco vigo caballero, insert laboral uchiza')
@section('meta_robots', 'index, follow, max-snippet:-1, max-image-preview:large')
@section('canonical_url', $jobs->currentPage() > 1 ? route('bolsa-de-trabajo') . '?page=' . $jobs->currentPage() : route('bolsa-de-trabajo'))
@section('og_image', url(isset($enterprise) && $enterprise->logo_path ? $enterprise->logo_path : '/img/og-default.png'))

@push('styles')
    <style>
        /* ─── Job Card Enhancements ──────────────────────────────────────────────── */
        .job-card {
            transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s ease;
            will-change: transform, box-shadow;
        }
        .job-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px -12px rgba(30,58,138,.14), 0 8px 20px -4px rgba(30,58,138,.08);
        }
        .job-card .card-accent-bar {
            height: 4px;
            border-radius: 12px 12px 0 0;
            transition: height .2s ease;
        }
        .job-card:hover .card-accent-bar {
            height: 6px;
        }
        .job-card .avatar-letter {
            transition: transform .25s cubic-bezier(.34,1.56,.64,1);
        }
        .job-card:hover .avatar-letter {
            transform: scale(1.12) rotate(-3deg);
        }

        /* ─── New Badge Pulse ────────────────────────────────────────────────────── */
        @keyframes newPulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(16,185,129,.35); }
            60%      { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
        }
        .badge-new { animation: newPulse 2s infinite; }

        /* ─── Custom pagination override ─────────────────────────────────────────── */
        nav[aria-label="pagination"] .flex {
            flex-wrap: wrap;
            gap: .35rem;
            justify-content: center;
        }
        nav[aria-label="pagination"] span,
        nav[aria-label="pagination"] a {
            min-width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .75rem;
            font-size: .8rem;
            font-weight: 700;
            transition: all .18s ease;
            padding: 0 .6rem;
        }
        nav[aria-label="pagination"] a {
            color: #1e40af;
            border: 1.5px solid #bfdbfe;
            background: #fff;
        }
        nav[aria-label="pagination"] a:hover {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37,99,235,.3);
        }
        nav[aria-label="pagination"] span[aria-current="page"] span {
            background: linear-gradient(135deg,#1d4ed8,#3b82f6);
            color: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(37,99,235,.35);
        }
        nav[aria-label="pagination"] span.text-gray-300 span,
        nav[aria-label="pagination"] span[aria-disabled] span {
            background: #f8fafc;
            color: #94a3b8;
            border: 1.5px solid #e2e8f0;
            cursor: not-allowed;
        }
        [x-cloak] { display: none !important; }
    </style>
@endpush

@push('scripts')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "WebPage",
                    "@id": "{{ route('bolsa-de-trabajo') }}#webpage",
                    "url": "{{ route('bolsa-de-trabajo') }}",
                    "name": "Bolsa de Trabajo Técnico en Perú — IESTP Francisco Vigo Caballero",
                    "description": "Ofertas de empleo y prácticas profesionales técnicas en Perú. Carreras en Redes, Enfermería, Agropecuaria, Forestal y Asistencia Administrativa.",
                    "inLanguage": "es-PE",
                    "isPartOf": {
                        "@type": "WebSite",
                        "@id": "{{ url('/') }}#website",
                        "url": "{{ url('/') }}",
                        "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}"
                    },
                    "breadcrumb": {
                        "@type": "BreadcrumbList",
                        "itemListElement": [
                            {"@type":"ListItem","position":1,"name":"Inicio","item":"{{ route('inicio') }}"},
                            {"@type":"ListItem","position":2,"name":"Servicios","item":"{{ url('/servicios') }}"},
                            {"@type":"ListItem","position":3,"name":"Bolsa de Trabajo","item":"{{ route('bolsa-de-trabajo') }}"}
                        ]
                    }
                },
                {
                "@type": "ItemList",
                "name": "Ofertas Laborales Técnicas en Perú — IESTP FVC",
                "description": "Vacantes y prácticas profesionales para técnicos peruanos en cinco especialidades.",
                "url": "{{ route('bolsa-de-trabajo') }}",
                "numberOfItems": {{ $jobs->total() }},
                "itemListElement": [
                    @foreach($jobs as $idx => $job)
                    {
                        "@type": "ListItem",
                        "position": {{ ($jobs->currentPage() - 1) * $jobs->perPage() + $idx + 1 }},
                        "item": {
                            "@type": "JobPosting",
                            "@id": "{{ route('bolsa-de-trabajo') }}#job-{{ $job->id }}",
                            "title": "{{ addslashes($job->title) }}",
                            "description": "{{ addslashes(Str::limit(strip_tags($job->description), 250)) }}",
                            "datePosted": "{{ $job->created_at->toIso8601String() }}",
                            "validThrough": "{{ $job->created_at->addDays(30)->toIso8601String() }}",
                            "employmentType": "FULL_TIME",
                            "hiringOrganization": {
                                "@type": "Organization",
                                "name": "{{ addslashes($job->company) }}",
                                "sameAs": "{{ $job->url }}"
                            },
                            "jobLocation": {
                                "@type": "Place",
                                "address": {
                                    "@type": "PostalAddress",
                                    "addressLocality": "{{ addslashes($job->location ?? 'Perú') }}",
                                    "addressCountry": "PE"
                                }
                            },
                            "url": "{{ $job->url }}"
                        }
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            }
            ]
        }
    </script>
@endpush

@section('content')
{{-- ═══ HERO ════════════════════════════════════════════════════════════ --}}
<section aria-label="Portada Bolsa de Trabajo" class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white overflow-hidden py-16 lg:py-24">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(96,165,250,0.25),transparent_55%)]"></div>
        <div class="absolute -bottom-32 -left-24 w-[28rem] h-[28rem] bg-blue-500/15 rounded-full blur-3xl"></div>
        <div class="absolute top-10 right-10 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {{-- Left: H1 + CTA --}}
            <div class="lg:col-span-7 space-y-6">
                <p class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-400/15 border border-blue-300/25 text-blue-100 text-xs sm:text-sm font-semibold tracking-wide backdrop-blur-sm">
                    <i class="bi bi-briefcase-fill text-blue-300" aria-hidden="true"></i>
                    Portal de Empleo Técnico · Perú
                </p>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-white font-sans">
                    Bolsa de Trabajo<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-blue-50">e Inserción Laboral</span>
                </h1>

                <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                    Conectamos a los egresados técnicos del <strong class="text-white">IESTP Francisco Vigo Caballero</strong> con empresas líderes de la región San Martín y todo el Perú.
                </p>

                {{-- Quick Stats --}}
                <div class="flex flex-wrap gap-4 pt-2">
                    <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-2xl px-4 py-2.5 backdrop-blur-sm">
                        <i class="bi bi-briefcase-fill text-blue-300 text-lg" aria-hidden="true"></i>
                        <div>
                            <p class="text-lg font-black text-white leading-none">{{ $jobs->total() }}</p>
                            <p class="text-[11px] text-blue-200 font-medium">Vacantes activas</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-2xl px-4 py-2.5 backdrop-blur-sm">
                        <i class="bi bi-geo-alt-fill text-emerald-400 text-lg" aria-hidden="true"></i>
                        <div>
                            <p class="text-lg font-black text-white leading-none">{{ $locations->count() }}</p>
                            <p class="text-[11px] text-blue-200 font-medium">Ubicaciones</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 border border-white/15 rounded-2xl px-4 py-2.5 backdrop-blur-sm">
                        <i class="bi bi-globe text-amber-400 text-lg" aria-hidden="true"></i>
                        <div>
                            <p class="text-lg font-black text-white leading-none">{{ $sources->count() }}</p>
                            <p class="text-[11px] text-blue-200 font-medium">Fuentes/portales</p>
                        </div>
                    </div>
                </div>

                {{-- CTAs --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <a href="#ofertas"
                       class="inline-flex items-center justify-center px-7 py-4 text-base font-extrabold text-blue-900 bg-white hover:bg-blue-50 rounded-2xl transition-all shadow-xl hover:shadow-2xl group">
                        <i class="bi bi-search mr-2 text-blue-700 group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        Explorar Vacantes
                    </a>
                    <a href="#publicar-oferta"
                       class="inline-flex items-center justify-center px-7 py-4 text-base font-extrabold text-white border-2 border-blue-300/30 hover:bg-white/10 rounded-2xl transition-all backdrop-blur-sm">
                        <i class="bi bi-building mr-2 text-blue-200" aria-hidden="true"></i>
                        Publicar Vacante
                    </a>
                </div>
            </div>

            {{-- Right: Info Panel --}}
            <div class="lg:col-span-5 relative" aria-hidden="true">
                <div class="absolute -inset-4 bg-gradient-to-r from-blue-400/30 to-indigo-500/20 rounded-3xl blur-2xl"></div>
                <div class="relative bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl space-y-5">

                    <div class="flex items-center justify-between border-b border-white/10 pb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-blue-200 font-extrabold">Portal Laboral FVC</p>
                                <p class="text-lg font-extrabold text-white">Conexión Profesional</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 rounded-full text-xs font-extrabold">
                            {{ $jobs->total() }} vacantes
                        </span>
                    </div>

                    @foreach([
                        ['icon'=>'bi-patch-check-fill','color'=>'text-emerald-400','title'=>'Convocatorias Validadas','desc'=>'Ofertas de portales líderes y empresas aliadas del instituto.'],
                        ['icon'=>'bi-mortarboard-fill','color'=>'text-blue-300','title'=>'5 Especialidades Técnicas','desc'=>'Redes, Administrativa, Enfermería, Forestal y Agropecuaria.'],
                        ['icon'=>'bi-arrow-repeat','color'=>'text-amber-300','title'=>'Actualización Automática','desc'=>'Búsqueda activa en Computrabajo PE y Bumeran PE diariamente.'],
                    ] as $item)
                        <div class="flex items-start gap-3 bg-white/5 p-4 rounded-2xl border border-white/10">
                            <i class="bi {{ $item['icon'] }} {{ $item['color'] }} text-lg mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-sm font-extrabold text-white">{{ $item['title'] }}</p>
                                <p class="text-xs text-blue-100 mt-0.5">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══ FILTROS ═════════════════════════════════════════════════════════ --}}
<section id="ofertas" aria-label="Filtros de búsqueda laboral" class="py-10 bg-gradient-to-b from-slate-100 to-slate-50 -mt-6 relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-2xl shadow-blue-900/5 border border-blue-100/60 p-6 sm:p-8">
            <h2 class="text-xl sm:text-2xl font-extrabold text-blue-950 mb-6 flex items-center gap-2.5">
                <span class="w-9 h-9 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-lg shrink-0">
                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                </span>
                Buscar Ofertas Laborales Técnicas
            </h2>

            <form action="{{ route('bolsa-de-trabajo') }}#ofertas" method="GET"
                  role="search" aria-label="Formulario de búsqueda de empleo"
                  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

                {{-- Keyword --}}
                <div class="lg:col-span-5 relative">
                    <label for="search" class="block text-xs font-bold text-gray-600 mb-1.5">Cargo o Empresa</label>
                    <input type="text" id="search" name="search" value="{{ $search }}"
                        autocomplete="off"
                        placeholder="Ej. Redes, Enfermería, Contable, Agrónomo..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500/25 focus:border-blue-600 transition-all text-sm font-medium placeholder-gray-400">
                    <i class="bi bi-search absolute left-3.5 top-[2.15rem] text-gray-400 text-sm" aria-hidden="true"></i>
                </div>

                {{-- Location --}}
                <div class="lg:col-span-3">
                    <label for="location" class="block text-xs font-bold text-gray-600 mb-1.5">Ubicación</label>
                    <select id="location" name="location"
                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500/25 focus:border-blue-600 transition-all text-sm bg-white text-gray-700 font-medium">
                        <option value="">Todas las Ubicaciones</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ $selectedLocation === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Source --}}
                <div class="lg:col-span-2">
                    <label for="source" class="block text-xs font-bold text-gray-600 mb-1.5">Fuente / Portal</label>
                    <select id="source" name="source"
                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500/25 focus:border-blue-600 transition-all text-sm bg-white text-gray-700 font-medium">
                        <option value="">Todos los Portales</option>
                        @foreach($sources as $src)
                            <option value="{{ $src }}" {{ $selectedSource === $src ? 'selected' : '' }}>{{ $src }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="lg:col-span-2 flex flex-col justify-end gap-2">
                    <label class="block text-xs font-bold text-transparent mb-1.5 select-none">Acción</label>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-extrabold px-4 py-3 rounded-2xl transition-all shadow-md hover:shadow-blue-500/30 flex items-center justify-center gap-1.5 text-sm">
                            <i class="bi bi-search" aria-hidden="true"></i> Buscar
                        </button>
                        @if($search || $selectedLocation || $selectedSource)
                            <a href="{{ route('bolsa-de-trabajo') }}#ofertas"
                               aria-label="Limpiar filtros"
                               class="p-3 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl transition-all flex items-center justify-center border border-rose-200/80">
                                <i class="bi bi-x-lg text-sm" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </form>
        </div>

    </div>
</section>


{{-- ═══ OFERTAS GRID ════════════════════════════════════════════════════ --}}
<section aria-label="Listado de ofertas laborales" class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Results Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-100 px-3.5 py-1.5 rounded-full border border-blue-200/60">
                    Convocatorias Vigentes
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-blue-950 mt-2.5 font-sans">
                    @if($search || $selectedLocation || $selectedSource)
                        Resultados de Búsqueda
                        <span class="text-blue-600">({{ $jobs->total() }})</span>
                    @else
                        Ofertas Laborales Disponibles
                        <span class="text-blue-600">({{ $jobs->total() }})</span>
                    @endif
                </h2>
            </div>

            @if($search || $selectedLocation || $selectedSource)
                <div class="text-xs text-gray-700 bg-white px-4 py-2.5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-2">
                    <i class="bi bi-funnel-fill text-blue-600" aria-hidden="true"></i>
                    <span>Filtrando: <strong>{{ implode(' · ', array_filter([$search, $selectedLocation, $selectedSource])) }}</strong></span>
                </div>
            @endif
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-7" x-data="{ activeModal: null }">
            @forelse($jobs as $job)
                @php
                    $sl   = mb_strtolower($job->source ?? '');
                    $isNew = $job->created_at->greaterThan(now()->subDays(3));

                    // Palette per source
                    if (str_contains($sl, 'computrabajo')) {
                        $bar   = 'from-amber-500 to-orange-500';
                        $badge = 'bg-amber-50 text-amber-800 border-amber-200';
                        $bIcon = 'bi-globe2 text-amber-600';
                        $avt   = 'from-amber-500 to-orange-600';
                        $ring  = 'focus-visible:ring-amber-400';
                    } elseif (str_contains($sl, 'bumeran')) {
                        $bar   = 'from-violet-500 to-purple-600';
                        $badge = 'bg-violet-50 text-violet-800 border-violet-200';
                        $bIcon = 'bi-briefcase-fill text-violet-600';
                        $avt   = 'from-violet-600 to-indigo-700';
                        $ring  = 'focus-visible:ring-violet-400';
                    } elseif (str_contains($sl, 'convenio')) {
                        $bar   = 'from-emerald-500 to-teal-600';
                        $badge = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                        $bIcon = 'bi-award-fill text-emerald-600';
                        $avt   = 'from-emerald-600 to-teal-700';
                        $ring  = 'focus-visible:ring-emerald-400';
                    } elseif (str_contains($sl, 'convocatoria') || str_contains($sl, 'publica')) {
                        $bar   = 'from-rose-500 to-pink-600';
                        $badge = 'bg-rose-50 text-rose-800 border-rose-200';
                        $bIcon = 'bi-building text-rose-600';
                        $avt   = 'from-rose-600 to-pink-700';
                        $ring  = 'focus-visible:ring-rose-400';
                    } else {
                        $bar   = 'from-blue-600 to-blue-800';
                        $badge = 'bg-blue-50 text-blue-800 border-blue-200';
                        $bIcon = 'bi-star-fill text-blue-600';
                        $avt   = 'from-blue-600 to-blue-900';
                        $ring  = 'focus-visible:ring-blue-400';
                    }

                    $initial = mb_strtoupper(mb_substr(preg_replace('/^(empresa|confidencial|s\.a|s\.a\.c|e\.i\.r\.l)\b/i', '', trim($job->company)), 0, 1));
                    if (!$initial) $initial = 'E';
                @endphp

                <article class="job-card bg-white rounded-3xl border border-slate-200/80 overflow-hidden flex flex-col focus-within:ring-2 {{ $ring }}"
                         itemscope itemtype="https://schema.org/JobPosting"
                         aria-label="Oferta: {{ $job->title }}">
                    <meta itemprop="datePosted" content="{{ $job->created_at->toIso8601String() }}">
                    <meta itemprop="validThrough" content="{{ $job->created_at->addDays(30)->toIso8601String() }}">
                    <meta itemprop="employmentType" content="FULL_TIME">

                    {{-- Gradient Accent Bar --}}
                    <div class="card-accent-bar bg-gradient-to-r {{ $bar }}" role="presentation"></div>

                    <div class="p-6 sm:p-7 flex flex-col gap-4 flex-1">

                        {{-- Top Row: Source Badge + Time + "New" --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 {{ $badge }} text-[11px] font-extrabold px-2.5 py-1 rounded-full border truncate max-w-[60%]">
                                <i class="bi {{ $bIcon }} shrink-0" aria-hidden="true"></i>
                                <span class="truncate">{{ $job->source }}</span>
                            </span>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($isNew)
                                    <span class="badge-new inline-flex items-center gap-1 text-[10px] font-extrabold text-emerald-700 bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded-full">
                                        <i class="bi bi-stars" aria-hidden="true"></i> Nuevo
                                    </span>
                                @endif
                                <time datetime="{{ $job->created_at->toIso8601String() }}"
                                      class="text-[11px] font-semibold text-gray-400 flex items-center gap-1">
                                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                                    {{ $job->created_at->diffForHumans() }}
                                </time>
                            </div>
                        </div>

                        {{-- Company Avatar + Title --}}
                        <div class="flex items-start gap-3.5">
                            <div class="avatar-letter w-13 h-13 w-[3.25rem] h-[3.25rem] bg-gradient-to-br {{ $avt }} text-white rounded-2xl flex items-center justify-center text-xl font-black shadow-md shrink-0 select-none"
                                 aria-hidden="true">
                                {{ $initial }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-extrabold text-base sm:text-[1.05rem] text-blue-950 leading-snug line-clamp-2"
                                    itemprop="title"
                                    title="{{ $job->title }}">
                                    {{ $job->title }}
                                </h3>
                                <p class="text-[13px] font-semibold text-slate-600 flex items-center gap-1.5 mt-1 truncate"
                                   itemprop="hiringOrganization" itemscope itemtype="https://schema.org/Organization">
                                    <i class="bi bi-building text-blue-500 shrink-0" aria-hidden="true"></i>
                                    <span itemprop="name" class="truncate">{{ $job->company }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Location + Verified Pills --}}
                        <div class="flex flex-wrap gap-1.5"
                             itemprop="jobLocation" itemscope itemtype="https://schema.org/Place">
                            @if($job->location)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200/70 px-2.5 py-1 rounded-xl"
                                      itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <i class="bi bi-geo-alt-fill text-emerald-600" aria-hidden="true"></i>
                                    <span itemprop="addressLocality">{{ $job->location }}</span>
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200/70 px-2.5 py-1 rounded-xl">
                                <i class="bi bi-shield-check text-blue-600" aria-hidden="true"></i> Verificada
                            </span>
                        </div>

                        {{-- Description excerpt --}}
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed line-clamp-3 flex-1"
                           itemprop="description">
                            {{ $job->description }}
                        </p>

                    </div>

                    {{-- Card Footer --}}
                    <div class="px-6 sm:px-7 py-4 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button
                            @click="activeModal = {{ $job->id }}"
                            class="inline-flex items-center gap-1 text-xs font-extrabold text-blue-700 hover:text-blue-900 transition-all hover:gap-2"
                            aria-label="Ver detalles de {{ $job->title }}">
                            Ver Detalles <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </button>

                        <a href="{{ $job->url }}" target="_blank" rel="noopener noreferrer"
                           itemprop="url"
                           class="px-4 py-2 bg-gradient-to-r {{ $bar }} hover:brightness-110 text-white text-[13px] font-extrabold rounded-xl transition-all shadow-md flex items-center gap-1.5">
                            Postular <i class="bi bi-box-arrow-up-right text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>

                    {{-- Inline Modal --}}
                    <div x-show="activeModal === {{ $job->id }}"
                         x-transition:enter="transition ease-out duration-250"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-180"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
                         style="display:none;"
                         role="dialog"
                         aria-modal="true"
                         aria-labelledby="modal-title-{{ $job->id }}"
                         @keydown.escape.window="activeModal = null">

                        <div class="bg-white rounded-3xl w-full max-w-xl max-h-[88vh] overflow-y-auto shadow-2xl border border-slate-100 flex flex-col"
                             @click.outside="activeModal = null">

                            {{-- Modal Accent Bar --}}
                            <div class="h-1.5 bg-gradient-to-r {{ $bar }} rounded-t-3xl flex-shrink-0"></div>

                            <div class="p-6 sm:p-8 space-y-5">
                                {{-- Header --}}
                                <div class="flex items-start gap-4 pr-8">
                                    <div class="w-14 h-14 bg-gradient-to-br {{ $avt }} text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-md shrink-0">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center gap-1 {{ $badge }} text-[11px] font-extrabold px-2.5 py-1 rounded-full border">
                                            <i class="bi {{ $bIcon }}" aria-hidden="true"></i> {{ $job->source }}
                                        </span>
                                        <h3 id="modal-title-{{ $job->id }}" class="text-xl font-extrabold text-blue-950 mt-2 leading-snug">{{ $job->title }}</h3>
                                        <p class="text-sm font-semibold text-slate-600 flex flex-wrap items-center gap-2 mt-1">
                                            <span class="flex items-center gap-1"><i class="bi bi-building text-blue-500" aria-hidden="true"></i> {{ $job->company }}</span>
                                            @if($job->location)
                                                <span class="text-slate-400">·</span>
                                                <span class="flex items-center gap-1 text-emerald-700"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $job->location }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <button @click="activeModal = null"
                                        class="absolute top-5 right-5 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition"
                                        aria-label="Cerrar modal">
                                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                                </button>

                                {{-- Description --}}
                                <div>
                                    <h4 class="font-extrabold text-blue-900 mb-2 flex items-center gap-2 text-sm">
                                        <i class="bi bi-card-text text-blue-600" aria-hidden="true"></i>
                                        Descripción del Puesto y Requisitos:
                                    </h4>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs sm:text-[13px] leading-relaxed whitespace-pre-line text-slate-700">
                                        {{ $job->description }}
                                    </div>
                                </div>

                                {{-- Info Notice --}}
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-2xl text-blue-900 text-xs flex items-start gap-2.5">
                                    <i class="bi bi-info-circle-fill text-blue-600 text-base shrink-0 mt-0.5" aria-hidden="true"></i>
                                    <span>Al hacer clic en «Ir a la Convocatoria», serás redirigido a la plataforma de postulación oficial de la empresa. El IESTP FVC no es responsable de los procesos de selección externos.</span>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                                    <a href="{{ $job->url }}" target="_blank" rel="noopener noreferrer"
                                       class="px-6 py-3 bg-gradient-to-r {{ $bar }} hover:brightness-110 text-white font-extrabold text-xs rounded-xl transition shadow-md flex items-center gap-2">
                                        Ir a la Convocatoria Oficial <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                    </a>
                                    <button @click="activeModal = null"
                                            class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs rounded-xl transition">
                                        Cerrar
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </article>
            @empty
                <div class="col-span-full py-20 bg-white rounded-3xl border border-blue-100 text-center shadow-sm" role="status">
                    <div class="w-16 h-16 bg-blue-50 text-blue-400 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                        <i class="bi bi-search" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-blue-950 mb-2">No se encontraron ofertas laborales</h3>
                    <p class="text-slate-500 text-sm max-w-sm mx-auto mb-6">
                        No hay vacantes que coincidan con los filtros seleccionados. Prueba con otros términos o limpia los filtros.
                    </p>
                    <a href="{{ route('bolsa-de-trabajo') }}#ofertas"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-extrabold text-sm rounded-2xl hover:bg-blue-700 transition shadow-md">
                        <i class="bi bi-arrow-repeat" aria-hidden="true"></i> Ver Todas las Ofertas
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ═ Enhanced Pagination ═════════════════════════════════════════ --}}
        @if($jobs->hasPages())
            <div class="mt-14 pt-8 border-t border-slate-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-5">

                    {{-- Range Info --}}
                    <p class="text-sm text-slate-500 font-medium text-center sm:text-left" aria-live="polite">
                        Mostrando
                        <span class="font-extrabold text-blue-700">{{ $jobs->firstItem() }}</span>
                        –
                        <span class="font-extrabold text-blue-700">{{ $jobs->lastItem() }}</span>
                        de
                        <span class="font-extrabold text-slate-800">{{ $jobs->total() }}</span>
                        ofertas laborales
                        @if($jobs->lastPage() > 1)
                            <span class="text-slate-400">· Página {{ $jobs->currentPage() }} de {{ $jobs->lastPage() }}</span>
                        @endif
                    </p>

                    {{-- Navigation Links --}}
                    <nav aria-label="pagination" class="flex-shrink-0">
                        {{ $jobs->links() }}
                    </nav>

                </div>
            </div>
        @endif

    </div>
</section>


{{-- ═══ EMPLEABILIDAD ═══════════════════════════════════════════════════ --}}
<section aria-label="Servicios de empleabilidad" class="py-16 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                Servicios al Estudiante
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-950 mt-4 font-sans">
                Potencia tu Empleabilidad Profesional
            </h2>
            <div class="w-16 h-1.5 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto mt-4 rounded-full"></div>
            <p class="text-lg text-slate-600 mt-6 leading-relaxed">
                Brindamos herramientas y asesoría personalizada a egresados y estudiantes para ingresar con éxito al mercado laboral técnico.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['icon'=>'bi-file-earmark-person','title'=>'Elaboración de CV Técnico','desc'=>'Aprende a estructurar tu CV destacando competencias tecnológicas, prácticas de campo y certificaciones modulares para postulaciones en Perú.'],
                ['icon'=>'bi-people','title'=>'Preparación de Entrevistas','desc'=>'Talleres de entrevistas laborales presenciales y virtuales, comunicación efectiva y resolución de evaluaciones de selección por competencias.'],
                ['icon'=>'bi-building-check','title'=>'Red de Empresas Aliadas','desc'=>'Convenios con empresas agroindustriales, centros de salud, entidades financieras, municipalidades y firmas tecnológicas de la región.'],
            ] as $svc)
                <div class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-md shadow-blue-600/20 group-hover:scale-110 transition-transform">
                        <i class="bi {{ $svc['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-blue-950 mb-3">{{ $svc['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $svc['desc'] }}</p>
                </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ═══ CTA EMPRESAS ════════════════════════════════════════════════════ --}}
<section id="publicar-oferta" aria-label="Publicar vacante — Para empresas" class="py-16 bg-slate-950 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="bg-gradient-to-r from-blue-950 via-blue-900 to-blue-800 rounded-3xl p-8 sm:p-12 border border-blue-700/40 shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

                <div class="lg:col-span-8 space-y-4">
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-blue-300 bg-blue-950/70 px-4 py-1.5 rounded-full border border-blue-400/30 inline-block">
                        Convenios Empresariales
                    </p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-sans leading-tight">
                        ¿Su empresa necesita talento<br class="hidden sm:block"> técnico calificado?
                    </h2>
                    <p class="text-blue-100 text-base leading-relaxed max-w-xl">
                        Publica vacantes de empleo o prácticas pre-profesionales en nuestra Bolsa Institucional y conecta con egresados titulados de los 5 programas técnicos del IESTP Francisco Vigo Caballero.
                    </p>
                    <div class="flex flex-wrap gap-5 pt-1 text-sm text-blue-200">
                        @if(!empty($enterprise->email))
                            <a href="mailto:{{ $enterprise->email }}" class="flex items-center gap-2 hover:text-white transition">
                                <i class="bi bi-envelope-fill text-blue-400 text-lg" aria-hidden="true"></i>
                                <span>{{ $enterprise->email }}</span>
                            </a>
                        @endif
                        @if(!empty($enterprise->phone_number_1))
                            <a href="tel:{{ $enterprise->phone_number_1 }}" class="flex items-center gap-2 hover:text-white transition">
                                <i class="bi bi-telephone-fill text-blue-400 text-lg" aria-hidden="true"></i>
                                <span>{{ $enterprise->phone_number_1 }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col gap-4">
                    @if(!empty($enterprise->whatsapp_link))
                        <a href="{{ $enterprise->whatsapp_link }}" target="_blank" rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm rounded-2xl transition-all shadow-lg hover:shadow-emerald-500/25">
                            <i class="bi bi-whatsapp mr-2 text-lg" aria-hidden="true"></i> Contactar por WhatsApp
                        </a>
                    @endif
                    <a href="{{ route('mesa-de-partes') }}"
                       class="w-full inline-flex items-center justify-center px-6 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-extrabold text-sm rounded-2xl transition-all">
                        <i class="bi bi-file-earmark-arrow-up mr-2 text-lg text-blue-300" aria-hidden="true"></i>
                        Enviar Vacante — Mesa de Partes
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection
