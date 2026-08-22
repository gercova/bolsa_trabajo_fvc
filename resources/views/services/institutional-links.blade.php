@extends('layouts.app')

{{-- ════ SEO FULL SUITE ════════════════════════════════════════════════════ --}}
@section('title', 'Enlaces Institucionales Oficiales MINEDU — IESTP Francisco Vigo Caballero')
@section('meta_description', 'Portales web oficiales del Ministerio de Educación (MINEDU) para Educación Superior
    Tecnológica: Registra, Titula, Conecta y Avanza del IESTP Francisco Vigo Caballero en Uchiza, San Martín.')
@section('meta_keywords', 'registra minedu, titula minedu, conecta minedu, avanza minedu, enlaces institucionales, iestp
    francisco vigo caballero, titulos tecnicos peru, educacion superior tecnologica, uchiza')
@section('meta_robots', 'index, follow, max-snippet:-1, max-image-preview:large')
@section('canonical_url', route('enlaces-institucionales'))
@section('og_image', url(isset($enterprise) && $enterprise->logo_path ? $enterprise->logo_path : '/img/og-default.png'))

@push('styles')
    <style>
        /* ─── Link Card Styling ────────────────────────────────────────────────── */
        .link-card {
            transition: transform .28s cubic-bezier(.34, 1.56, .64, 1), box-shadow .28s ease;
            will-change: transform, box-shadow;
        }

        .link-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px -12px rgba(30, 58, 138, .14), 0 8px 20px -4px rgba(30, 58, 138, .08);
        }

        .link-card .card-accent-bar {
            height: 4px;
            border-radius: 12px 12px 0 0;
            transition: height .2s ease;
        }

        .link-card:hover .card-accent-bar {
            height: 6px;
        }

        .link-card .avatar-icon {
            transition: transform .25s cubic-bezier(.34, 1.56, .64, 1);
        }

        .link-card:hover .avatar-icon {
            transform: scale(1.12) rotate(-3deg);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
@push('scripts')
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebPage",
                "@id": "{{ route('enlaces-institucionales') }}#webpage",
                "url": "{{ route('enlaces-institucionales') }}",
                "name": "Enlaces Institucionales Oficiales MINEDU — IESTP Francisco Vigo Caballero",
                "description": "Acceso directo a las plataformas oficiales del Ministerio de Educación (MINEDU) para estudiantes, egresados y docentes de educación superior técnica.",
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
                        {"@type":"ListItem","position":3,"name":"Enlaces Institucionales","item":"{{ route('enlaces-institucionales') }}"}
                    ]
                }
                },
                {
                "@type": "ItemList",
                "name": "Plataformas Oficiales del Ministerio de Educación (MINEDU)",
                "description": "Enlaces oficiales del MINEDU para verificación de títulos, matrícula y comunidad tecnológica.",
                "url": "{{ route('enlaces-institucionales') }}",
                "numberOfItems": {{ $links->count() }},
                "itemListElement": [
                        @foreach($links as $idx => $linkItem)
                        {
                            "@type": "ListItem",
                            "position": {{ $idx + 1 }},
                            "item": {
                                "@type": "WebSite",
                                "@id": "{{ route('enlaces-institucionales') }}#link-{{ $linkItem->id }}",
                                "name": "{{ addslashes($linkItem->name) }} MINEDU",
                                "url": "{{ $linkItem->link }}",
                                "publisher": {
                                "@type": "GovernmentOrganization",
                                "name": "Ministerio de Educación del Perú (MINEDU)"
                            }
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
    <section aria-label="Portada Enlaces Institucionales"
        class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white overflow-hidden py-16 lg:py-24">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(96,165,250,0.25),transparent_55%)]">
            </div>
            <div class="absolute -bottom-32 -left-24 w-[28rem] h-[28rem] bg-blue-500/15 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-10 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                {{-- Left: Title + Intro --}}
                <div class="lg:col-span-7 space-y-6">
                    <p
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-400/15 border border-blue-300/25 text-blue-100 text-xs sm:text-sm font-semibold tracking-wide backdrop-blur-sm">
                        <i class="bi bi-shield-check text-emerald-400" aria-hidden="true"></i>
                        Portales Oficiales MINEDU & Educación Superior
                    </p>

                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-white font-sans">
                        Enlaces e Integración <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-blue-50">Institucional
                            Oficial</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                        Accede de manera directa y segura a las plataformas nacionales del <strong
                            class="text-white">Ministerio de Educación (MINEDU)</strong> para la consulta de matrícula,
                        verificación de títulos a Nombre de la Nación y servicios académicos.
                    </p>

                    {{-- Quick Stats / Features --}}
                    <div class="flex flex-wrap gap-4 pt-2">
                        <div
                            class="flex items-center gap-2.5 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                            <i class="bi bi-award-fill text-amber-400 text-xl" aria-hidden="true"></i>
                            <div>
                                <p class="text-sm font-extrabold text-white">Verificación Oficial</p>
                                <p class="text-[11px] text-blue-200 font-medium">Títulos registrados ante MINEDU</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2.5 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                            <i class="bi bi-pencil-square text-emerald-400 text-xl" aria-hidden="true"></i>
                            <div>
                                <p class="text-sm font-extrabold text-white">Gestión Académica</p>
                                <p class="text-[11px] text-blue-200 font-medium">Matrícula y actas de notas</p>
                            </div>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <div class="pt-2">
                        <a href="#plataformas"
                            class="inline-flex items-center justify-center px-7 py-4 text-base font-extrabold text-blue-900 bg-white hover:bg-blue-50 rounded-2xl transition-all shadow-xl hover:shadow-2xl group">
                            <i class="bi bi-box-arrow-up-right mr-2 text-blue-700 group-hover:scale-110 transition-transform"
                                aria-hidden="true"></i>
                            Explorar Portales MINEDU
                        </a>
                    </div>
                </div>

                {{-- Right: Visual Info Graphic Panel --}}
                <div class="lg:col-span-5 relative" aria-hidden="true">
                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-400/30 to-indigo-500/20 rounded-3xl blur-2xl">
                    </div>
                    <div
                        class="relative bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl space-y-5">

                        <div class="flex items-center justify-between border-b border-white/10 pb-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center text-xl shadow-inner text-white">
                                    <i class="bi bi-building-gear"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wider text-blue-200 font-extrabold">Gobierno
                                        del Perú</p>
                                    <p class="text-lg font-extrabold text-white">Ministerio de Educación</p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 rounded-full text-xs font-extrabold">
                                IESTP FVC
                            </span>
                        </div>

                        @foreach ([['icon' => 'bi-pencil-square', 'color' => 'text-blue-300', 'title' => 'REGISTRA MINEDU', 'desc' => 'Nómina de matriculados y registro modular de estudiantes.'], ['icon' => 'bi-award-fill', 'color' => 'text-emerald-400', 'title' => 'TITULA MINEDU', 'desc' => 'Verificación de títulos profesionales a Nombre de la Nación.'], ['icon' => 'bi-people-fill', 'color' => 'text-purple-300', 'title' => 'CONECTA MINEDU', 'desc' => 'Plataforma de comunidad y recursos pedagógicos de IESTP.'], ['icon' => 'bi-graph-up-arrow', 'color' => 'text-amber-300', 'title' => 'AVANZA MINEDU', 'desc' => 'Seguimiento de trayectoria formativa y desarrollo laboral.']] as $item)
                            <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
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


    {{-- ═══ PLATAFORMAS OFICIALES GRID ═════════════════════════════════════ --}}
    <section id="plataformas" aria-label="Portales institucionales de MINEDU" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-14">
                <span
                    class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                    Plataformas del Ministerio de Educación
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-950 mt-4 font-sans">
                    Acceso a los Sistemas Institucionales
                </h2>
                <div class="w-16 h-1.5 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-slate-600 mt-6 leading-relaxed">
                    Haga clic en cualquiera de las tarjetas para acceder directamente al sitio oficial del MINEDU
                    correspondiente a su trámite o consulta.
                </p>
            </div>

            {{-- Dynamic Links Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($links as $link)
                    @php
                        $nameLower = mb_strtolower($link->name ?? '');

                        // Contextual enrichment mapping based on seeder names
                        if (str_contains($nameLower, 'registra')) {
                            $subtitle = 'Sistema de Registro de Matrícula y Notas';
                            $description =
                                'Plataforma del MINEDU para la administración y validación oficial de nóminas de matrícula, actas de evaluación modular e historial académico de estudiantes de IESTP a nivel nacional.';
                            $barGradient = 'from-blue-600 to-indigo-700';
                            $avatarGradient = 'from-blue-600 to-indigo-800';
                            $badgeClass = 'bg-blue-50 text-blue-800 border-blue-200';
                            $iconName = 'bi-pencil-square';
                            $targetAudience = 'Estudiantes, Docentes y Secretaría Académica';
                        } elseif (str_contains($nameLower, 'titula')) {
                            $subtitle = 'Registro Nacional de Títulos Profesionales';
                            $description =
                                'Portal oficial para la consulta pública, verificación y validación legal de títulos técnicos profesionales y certificados modulares emitidos a Nombre de la Nación.';
                            $barGradient = 'from-emerald-600 to-teal-700';
                            $avatarGradient = 'from-emerald-600 to-teal-800';
                            $badgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                            $iconName = 'bi-award';
                            $targetAudience = 'Egresados, Empleadores y Público en General';
                        } elseif (str_contains($nameLower, 'conecta')) {
                            $subtitle = 'Plataforma de Articulación y Comunidad Tecnológica';
                            $description =
                                'Red de interacción digital que vincula a estudiantes, docentes y graduados de Educación Superior Tecnológica con oportunidades formativas, recursos y redes de innovación.';
                            $barGradient = 'from-purple-600 to-indigo-700';
                            $avatarGradient = 'from-purple-600 to-indigo-800';
                            $badgeClass = 'bg-purple-50 text-purple-800 border-purple-200';
                            $iconName = 'bi-people';
                            $targetAudience = 'Estudiantes, Egresados y Comunidad Académica';
                        } elseif (str_contains($nameLower, 'avanza')) {
                            $subtitle = 'Sistema de Trayectoria y Desarrollo Profesional';
                            $description =
                                'Herramienta de seguimiento del progreso laboral, desarrollo de competencias profesionales, formación continua y empleabilidad para técnicos del Perú.';
                            $barGradient = 'from-amber-500 to-orange-600';
                            $avatarGradient = 'from-amber-500 to-orange-700';
                            $badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                            $iconName = 'bi-graph-up-arrow';
                            $targetAudience = 'Egresados Técnicos y Orientadores de Carrera';
                        } else {
                            $subtitle = 'Portal Oficial del Ministerio de Educación';
                            $description =
                                'Enlace institucional oficial de consulta y trámite de la Educación Superior Tecnológica del Perú.';
                            $barGradient = 'from-blue-600 to-blue-800';
                            $avatarGradient = 'from-blue-600 to-blue-900';
                            $badgeClass = 'bg-blue-50 text-blue-800 border-blue-200';
                            $iconName = $link->icon ?: 'bi-link-45deg';
                            $targetAudience = 'Comunidad Institucional IESTP FVC';
                        }

                        $customIcon = $link->icon ?: $iconName;
                        $domainHost = parse_url($link->link, PHP_URL_HOST) ?: 'minedu.gob.pe';
                    @endphp

                    {{-- Platform Card --}}
                    <article
                        class="link-card bg-white rounded-3xl border border-slate-200/80 overflow-hidden flex flex-col justify-between shadow-sm relative group">
                        {{-- Accent Bar --}}
                        <div class="card-accent-bar bg-gradient-to-r {{ $barGradient }}" role="presentation"></div>
                        <div class="p-7 sm:p-8 space-y-5 flex-1">
                            {{-- Top Badges --}}
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 {{ $badgeClass }} text-[11px] font-extrabold px-3 py-1 rounded-full border">
                                    <i class="bi bi-building-check"></i> MINEDU Oficial
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1">
                                    <i class="bi bi-shield-lock-fill text-emerald-600"></i> Portal Seguro
                                </span>
                            </div>

                            {{-- Main Title & Avatar --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="avatar-icon w-14 h-14 bg-gradient-to-br {{ $avatarGradient }} text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-md shrink-0">
                                    <i class="bi {{ $customIcon }}"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="font-extrabold text-2xl text-blue-950 leading-snug group-hover:text-blue-600 transition-colors">
                                        {{ $link->name }}
                                    </h3>
                                    <p class="text-xs font-bold text-blue-600 mt-0.5">
                                        {{ $subtitle }}
                                    </p>
                                </div>
                            </div>

                            {{-- Description --}}
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ $description }}
                            </p>

                            {{-- Metadata Pills --}}
                            <div class="pt-1 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200/70">
                                    <i class="bi bi-person-badge text-blue-600"></i> {{ $targetAudience }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-50 px-3 py-1 rounded-xl border border-slate-200/50 font-mono">
                                    <i class="bi bi-globe"></i> {{ $domainHost }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Footer Action --}}
                        <div
                            class="px-7 sm:px-8 py-4 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-500 font-medium hidden sm:inline-flex items-center gap-1">
                                <i class="bi bi-info-circle text-blue-500"></i> Redirección externa
                            </span>

                            <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r {{ $barGradient }} hover:brightness-110 text-white font-extrabold text-xs sm:text-sm rounded-xl transition-all shadow-md shadow-blue-900/10 gap-2">
                                <span>Ingresar a {{ $link->name }} MINEDU</span>
                                <i class="bi bi-box-arrow-up-right text-xs"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-16 bg-white rounded-3xl border border-blue-100 text-center shadow-sm">
                        <div
                            class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-950 mb-2">No hay enlaces disponibles en este momento</h3>
                        <p class="text-slate-500 text-sm max-w-sm mx-auto">
                            Actualmente no se registraron enlaces externos activos. Por favor consulte más tarde.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══ MÁS SERVICIOS DIGITALES ═════════════════════════════════════════ --}}
    <section aria-label="Servicios digitales institucionales" class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-14">
                <span
                    class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                    Gestión Interna e Integrada
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-950 mt-4 font-sans">
                    Servicios Digitales del IESTP FVC
                </h2>
                <div class="w-16 h-1.5 bg-gradient-to-r from-blue-600 to-blue-400 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-slate-600 mt-6 leading-relaxed">
                    Complemente sus trámites accediendo a las herramientas virtuales del propio instituto.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Card 1 --}}
                <div
                    class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-md shadow-blue-600/20 group-hover:scale-110 transition-transform">
                        <i class="bi bi-inbox-fill" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-blue-950 mb-3">Mesa de Partes Virtual</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Ingrese solicitudes, solicitudes de tramitación de título, constancias y documentos oficiales
                        digitalmente sin salir de casa.
                    </p>
                    <a href="{{ route('mesa-de-partes') }}"
                        class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-all">
                        Ingresar a Mesa de Partes <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>

                {{-- Card 2 --}}
                <div
                    class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-md shadow-blue-600/20 group-hover:scale-110 transition-transform">
                        <i class="bi bi-book-half" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-blue-950 mb-3">Biblioteca Virtual</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Acceso a catálogo bibliográfico digital, libros de especialidad técnica y repositorios para la
                        investigación estudiantil.
                    </p>
                    <a href="https://iestpfranciscovigocaballero.bibliotecalatina.com/login" target="_blank"
                        rel="noopener"
                        class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-all">
                        Acceder a Biblioteca <i class="bi bi-box-arrow-up-right ml-1 text-[10px]"></i>
                    </a>
                </div>

                {{-- Card 3 --}}
                <div
                    class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-md shadow-blue-600/20 group-hover:scale-110 transition-transform">
                        <i class="bi bi-journal-bookmark" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-blue-950 mb-3">TUPA Institucional</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Consulte los requisitos, costos de tasa administrativa y plazos de atención para cada trámite
                        académico y administrativo.
                    </p>
                    <a href="{{ route('tupa') }}"
                        class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-all">
                        Ver Reglamento TUPA <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ PREGUNTAS FRECUENTES (FAQ) ═══════════════════════════════════════ --}}
    <section aria-label="Preguntas frecuentes sobre portales MINEDU" class="py-16 bg-slate-50 border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeFaq: null }">
            <div class="text-center mb-12">
                <span
                    class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                    Ayuda y Orientación
                </span>
                <h2 class="text-3xl font-extrabold text-blue-950 mt-4 font-sans">
                    Preguntas Frecuentes
                </h2>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                    <button @click="activeFaq = activeFaq === 1 ? null : 1"
                        class="w-full px-6 py-5 text-left font-extrabold text-blue-950 flex items-center justify-between gap-4 hover:bg-slate-50 transition">
                        <span class="text-base flex items-center gap-2">
                            <i class="bi bi-award text-emerald-600"></i>
                            ¿Cómo verifico mi título profesional en Titula MINEDU?
                        </span>
                        <i class="bi bi-chevron-down text-blue-600 transition-transform duration-200"
                            :class="activeFaq === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse x-cloak
                        class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Al ingresar al portal Titula MINEDU, puedes ingresar tu número de DNI o apellido paterno. El sistema
                        mostrará la constancia de inscripción de tu título profesional técnico o certificación emitida por
                        el instituto a Nombre de la Nación.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                    <button @click="activeFaq = activeFaq === 2 ? null : 2"
                        class="w-full px-6 py-5 text-left font-extrabold text-blue-950 flex items-center justify-between gap-4 hover:bg-slate-50 transition">
                        <span class="text-base flex items-center gap-2">
                            <i class="bi bi-pencil-square text-blue-600"></i>
                            ¿Quién realiza la inscripción de matrícula en Registra MINEDU?
                        </span>
                        <i class="bi bi-chevron-down text-blue-600 transition-transform duration-200"
                            :class="activeFaq === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse x-cloak
                        class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        La Secretaría Académica del IESTP Francisco Vigo Caballero realiza la carga de las nóminas oficiales
                        de matriculados y registros modulares. Los estudiantes pueden consultar y validar la conformidad de
                        sus registros según los periodos académicos.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                    <button @click="activeFaq = activeFaq === 3 ? null : 3"
                        class="w-full px-6 py-5 text-left font-extrabold text-blue-950 flex items-center justify-between gap-4 hover:bg-slate-50 transition">
                        <span class="text-base flex items-center gap-2">
                            <i class="bi bi-people text-purple-600"></i>
                            ¿Qué beneficios encuentro en la plataforma Conecta MINEDU?
                        </span>
                        <i class="bi bi-chevron-down text-blue-600 transition-transform duration-200"
                            :class="activeFaq === 3 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse x-cloak
                        class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Conecta MINEDU brinda acceso a talleres virtuales de orientación profesional, repositorios de
                        recursos educativos, noticias de convocatorias tecnológicas a nivel nacional y vinculación laboral
                        para egresados de educación superior técnica.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ CTA DE SOPORTE INSTITUCIONAL ════════════════════════════════════ --}}
    <section aria-label="Soporte y contacto" class="py-16 bg-slate-950 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div
                class="bg-gradient-to-r from-blue-950 via-blue-900 to-blue-800 rounded-3xl p-8 sm:p-12 border border-blue-700/40 shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                    <div class="lg:col-span-8 space-y-4">
                        <p
                            class="text-[11px] font-extrabold uppercase tracking-widest text-blue-300 bg-blue-950/70 px-4 py-1.5 rounded-full border border-blue-400/30 inline-block">
                            Atención al Usuario
                        </p>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-sans leading-tight">
                            ¿Necesitas asistencia con tu registro o título?
                        </h2>
                        <p class="text-blue-100 text-base leading-relaxed max-w-xl">
                            Si requieres constancias, verificación previa de actas o asistencia para acceder a los portales
                            institucionales del MINEDU, comunícate con la Secretaría Académica de nuestro instituto.
                        </p>
                        <div class="flex flex-wrap gap-5 pt-1 text-sm text-blue-200">
                            @if (!empty($enterprise->email))
                                <a href="mailto:{{ $enterprise->email }}"
                                    class="flex items-center gap-2 hover:text-white transition">
                                    <i class="bi bi-envelope-fill text-blue-400 text-lg" aria-hidden="true"></i>
                                    <span>{{ $enterprise->email }}</span>
                                </a>
                            @endif
                            @if (!empty($enterprise->phone_number_1))
                                <a href="tel:{{ $enterprise->phone_number_1 }}"
                                    class="flex items-center gap-2 hover:text-white transition">
                                    <i class="bi bi-telephone-fill text-blue-400 text-lg" aria-hidden="true"></i>
                                    <span>{{ $enterprise->phone_number_1 }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-4">
                        @if (!empty($enterprise->whatsapp_link))
                            <a href="{{ $enterprise->whatsapp_link }}" target="_blank" rel="noopener noreferrer"
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm rounded-2xl transition-all shadow-lg hover:shadow-emerald-500/25">
                                <i class="bi bi-whatsapp mr-2 text-lg" aria-hidden="true"></i> Consultar por WhatsApp
                            </a>
                        @endif
                        <a href="{{ route('mesa-de-partes') }}"
                            class="w-full inline-flex items-center justify-center px-6 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-extrabold text-sm rounded-2xl transition-all">
                            <i class="bi bi-file-earmark-arrow-up mr-2 text-lg text-blue-300" aria-hidden="true"></i>
                            Mesa de Partes Virtual
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
