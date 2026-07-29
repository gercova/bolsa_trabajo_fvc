@extends('layouts.app')

{{-- ═══ SEO ═══════════════════════════════════════════════════════════════ --}}
@section('title', 'IESTP Francisco Vigo Caballero — Formación Técnica Superior en Uchiza')
@section('meta_description', 'El IESTP Francisco Vigo Caballero ofrece 5 carreras técnicas a Nombre de la Nación en Uchiza, San Martín. Admisión, bolsa de trabajo, programas de estudio y más.')
@section('meta_keywords', 'IESTP Francisco Vigo Caballero, instituto técnico Uchiza, carreras técnicas San Martín, admisión 2025, bolsa de trabajo, CEPRE FVC, formación técnica Perú')
@section('canonical_url', url('/'))

@push('styles')
<style>
    /* ── Hero background blobs ──────────────────────────────── */
    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        opacity: .18;
        animation: blob-float 8s ease-in-out infinite alternate;
    }
    .blob-1 { width: 520px; height: 520px; background: #38bdf8; top: -120px; right: -80px; animation-delay: 0s; }
    .blob-2 { width: 380px; height: 380px; background: #6366f1; bottom: 0px;  left: -60px;  animation-delay: 2s; }
    .blob-3 { width: 260px; height: 260px; background: #22d3ee; top: 40%;    right: 28%;   animation-delay: 4s; }
    @keyframes blob-float {
        from { transform: translateY(0) scale(1); }
        to   { transform: translateY(-30px) scale(1.06); }
    }

    /* ── Counter animation ──────────────────────────────────── */
    .stat-number { font-variant-numeric: tabular-nums; }

    /* ── Infinite carousel ──────────────────────────────────── */
    @keyframes slide-left {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .carousel-track {
        display: flex;
        width: max-content;
        animation: slide-left 35s linear infinite;
    }
    .carousel-track:hover { animation-play-state: paused; }

    /* ── Card hover lift ────────────────────────────────────── */
    .card-lift {
        transition: transform .28s ease, box-shadow .28s ease;
    }
    .card-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(14,165,233,.18);
    }

    /* ── Line clamp ──────────────────────────────────────────── */
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    /* ── Fade-in on scroll ──────────────────────────────────── */
    .reveal { opacity: 0; transform: translateY(28px); transition: opacity .65s ease, transform .65s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ── Section label pill ─────────────────────────────────── */
    .section-pill {
        display:inline-block;
        padding: .2rem .9rem;
        border-radius: 9999px;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    /* ── Quick-access icon ring ─────────────────────────────── */
    .qa-icon {
        width: 3.25rem; height: 3.25rem;
        border-radius: .875rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
        transition: transform .22s ease;
    }
    .qa-card:hover .qa-icon { transform: scale(1.12) rotate(-4deg); }
</style>
@endpush

@section('content')

{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  1. HERO — Split layout con gradiente azul profundo                  ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-900 text-white overflow-hidden min-h-[88vh] flex items-center"
         aria-label="Portada principal">
    {{-- Blobs decorativos --}}
    <div class="blob blob-1" aria-hidden="true"></div>
    <div class="blob blob-2" aria-hidden="true"></div>
    <div class="blob blob-3" aria-hidden="true"></div>

    {{-- Grid de puntos sutil --}}
    <div class="absolute inset-0 opacity-[.04]"
         style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:28px 28px;"
         aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-14 items-center">

            {{-- ── Columna de texto ─────────────────────────── --}}
            <div class="lg:col-span-6 xl:col-span-7 space-y-7">
                <div class="inline-flex items-center gap-2 bg-sky-500/20 border border-sky-400/30 text-sky-300 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase">
                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                    Institución Pública — Uchiza, San Martín
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1]">
                    Tu futuro profesional<br>
                    empieza aquí, en el
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-300">
                        IESTP FVC
                    </span>
                </h1>

                <p class="text-lg text-blue-100/90 max-w-xl leading-relaxed">
                    5 carreras técnicas a Nombre de la Nación, bolsa de trabajo activa, admisión CEPRE y mucho más. Formamos profesionales que impulsan el desarrollo de nuestra región.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('programas-de-estudio') }}"
                       id="hero-cta-programas"
                       class="inline-flex items-center justify-center gap-2 px-7 py-4 bg-sky-400 hover:bg-sky-300 text-blue-950 font-bold rounded-xl transition-all shadow-xl shadow-sky-500/30 text-base">
                        <i class="bi bi-mortarboard-fill"></i>
                        Ver Programas de Estudio
                    </a>
                    <a href="{{ route('bolsa-de-trabajo') }}"
                       id="hero-cta-bolsa"
                       class="inline-flex items-center justify-center gap-2 px-7 py-4 border-2 border-blue-400/40 hover:bg-white/10 text-white font-bold rounded-xl transition-all text-base">
                        <i class="bi bi-briefcase-fill"></i>
                        Bolsa de Trabajo
                    </a>
                </div>

                {{-- Indicadores sociales rápidos --}}
                <div class="flex items-center gap-6 pt-3 text-sm text-blue-200">
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-patch-check-fill text-cyan-400"></i>
                        En proceso de licenciamiento
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-award-fill text-sky-400"></i>
                        +20 años de trayectoria
                    </div>
                </div>
            </div>

            {{-- ── Columna visual — cards flotantes ────────── --}}
            <div class="lg:col-span-6 xl:col-span-5 relative hidden lg:flex items-center justify-center">
                <div class="relative w-full max-w-sm mx-auto">
                    {{-- Card central principal --}}
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6 shadow-2xl">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-12 h-12 bg-sky-400/20 border border-sky-300/30 rounded-xl flex items-center justify-center">
                                <i class="bi bi-building-fill-up text-2xl text-sky-300"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">IESTP Francisco Vigo Caballero</p>
                                <p class="text-blue-200 text-xs">Uchiza · San Martín · Perú</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-800/50 rounded-xl p-3 text-center">
                                <p class="text-2xl font-black text-sky-300">{{ $programs->count() }}</p>
                                <p class="text-[11px] text-blue-200 font-medium mt-0.5">Carreras técnicas</p>
                            </div>
                            <div class="bg-blue-800/50 rounded-xl p-3 text-center">
                                <p class="text-2xl font-black text-cyan-300">{{ $jobOffers->count() }}+</p>
                                <p class="text-[11px] text-blue-200 font-medium mt-0.5">Empleos activos</p>
                            </div>
                            <div class="bg-blue-800/50 rounded-xl p-3 text-center">
                                <p class="text-2xl font-black text-indigo-300">{{ $partners->count() }}+</p>
                                <p class="text-[11px] text-blue-200 font-medium mt-0.5">Empresas aliadas</p>
                            </div>
                            <div class="bg-blue-800/50 rounded-xl p-3 text-center">
                                <p class="text-2xl font-black text-blue-300">{{ $users->where('role','usuario')->count() }}+</p>
                                <p class="text-[11px] text-blue-200 font-medium mt-0.5">Estudiantes</p>
                            </div>
                        </div>
                    </div>

                    {{-- Badge flotante superior derecho --}}
                    <div class="absolute -top-5 -right-5 bg-cyan-400 text-blue-950 text-xs font-black px-3 py-2 rounded-xl shadow-lg rotate-3 leading-tight">
                        <i class="bi bi-star-fill"></i> Admisión<br>2026 Abierta
                    </div>
                    {{-- Badge inferior izquierdo --}}
                    <div class="absolute -bottom-4 -left-4 bg-indigo-500 text-white text-xs font-bold px-3 py-2 rounded-xl shadow-lg -rotate-2">
                        <i class="bi bi-briefcase-fill mr-1"></i>Bolsa activa
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Ola inferior --}}
    <div class="absolute bottom-0 left-0 right-0 overflow-hidden leading-none" aria-hidden="true">
        <svg viewBox="0 0 1440 64" preserveAspectRatio="none" class="w-full h-16 fill-white">
            <path d="M0,32 C360,64 1080,0 1440,32 L1440,64 L0,64 Z"/>
        </svg>
    </div>
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  2. STATS BAR — Contadores animados                                  ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="bg-white py-14" aria-label="Estadísticas institucionales">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">

            @php
                $stats = [
                    ['icon'=>'bi-briefcase-fill',  'color'=>'blue',   'value'=>$jobOffers->count(), 'label'=>'Ofertas de empleo activas', 'suffix'=>'+'],
                    ['icon'=>'bi-building',         'color'=>'sky',    'value'=>$partners->count(),  'label'=>'Empresas aliadas',         'suffix'=>'+'],
                    ['icon'=>'bi-people-fill',       'color'=>'indigo', 'value'=>$users->where('role','usuario')->count(), 'label'=>'Estudiantes registrados', 'suffix'=>'+'],
                    ['icon'=>'bi-mortarboard-fill',  'color'=>'cyan',   'value'=>$programs->count(), 'label'=>'Programas de estudio',    'suffix'=>''],
                ];
            @endphp

            @foreach($stats as $i => $stat)
                <div class="reveal text-center group" style="transition-delay:{{ $i * 80 }}ms">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl flex items-center justify-center
                        {{ $stat['color']==='blue'   ? 'bg-blue-100 text-blue-600'   : '' }}
                        {{ $stat['color']==='sky'    ? 'bg-sky-100 text-sky-600'     : '' }}
                        {{ $stat['color']==='indigo' ? 'bg-indigo-100 text-indigo-600' : '' }}
                        {{ $stat['color']==='cyan'   ? 'bg-cyan-100 text-cyan-600'   : '' }}
                        group-hover:scale-110 transition-transform shadow-sm">
                        <i class="bi {{ $stat['icon'] }} text-2xl"></i>
                    </div>
                    <p class="stat-number text-4xl font-black text-gray-900"
                       data-target="{{ $stat['value'] }}">0</p>
                    <p class="text-sm text-gray-500 font-medium mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  3. ACCESOS RÁPIDOS — Grid inspirado en el menú de navegación        ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="bg-gradient-to-b from-sky-50 to-white py-20" aria-label="Accesos rápidos a secciones">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 reveal">
            <span class="section-pill bg-blue-100 text-blue-700 mb-3">Navega el instituto</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">Todo lo que necesitas, en un solo lugar</h2>
            <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Accede rápidamente a cualquier sección del portal institucional.</p>
        </div>

        @php
            $quickAccess = [
                // Admisión y Matrícula
                ['href'=>route('cepre-fvc'),          'icon'=>'bi-book-fill',            'color'=>'blue',   'label'=>'CEPRE FVC',              'desc'=>'Preparación para ingreso directo'],
                ['href'=>route('examen-de-admision'), 'icon'=>'bi-pencil-square',         'color'=>'sky',    'label'=>'Examen de Admisión',     'desc'=>'Procesos de admisión vigentes'],
                ['href'=>route('matriculas'),          'icon'=>'bi-clipboard-check-fill', 'color'=>'cyan',   'label'=>'Matrículas',             'desc'=>'Información de matrículas'],
                ['href'=>route('becas-y-creditos'),   'icon'=>'bi-award-fill',            'color'=>'indigo', 'label'=>'Becas y Créditos',       'desc'=>'Beneficios de financiamiento'],
                // Programas
                ['href'=>route('programas-de-estudio'),'icon'=>'bi-mortarboard-fill',    'color'=>'blue',   'label'=>'Programas de Estudio',   'desc'=>'5 carreras técnicas disponibles'],
                // Transparencia
                ['href'=>route('documentos-de-gestion'),'icon'=>'bi-folder2-open',       'color'=>'sky',    'label'=>'Documentos de Gestión',  'desc'=>'Acceso a documentos oficiales'],
                ['href'=>route('estadisticas'),        'icon'=>'bi-bar-chart-fill',       'color'=>'cyan',   'label'=>'Estadísticas',           'desc'=>'Datos e indicadores institucionales'],
                ['href'=>route('licenciamiento'),      'icon'=>'bi-patch-check-fill',     'color'=>'indigo', 'label'=>'Licenciamiento',         'desc'=>'Estado del proceso de licencia'],
                // Trámites
                ['href'=>route('mesa-de-partes'),      'icon'=>'bi-inbox-fill',           'color'=>'blue',   'label'=>'Mesa de Partes',         'desc'=>'Ingresa documentos y solicitudes'],
                ['href'=>route('tupa'),                'icon'=>'bi-file-earmark-ruled',   'color'=>'sky',    'label'=>'TUPA',                   'desc'=>'Texto Único de Procedimientos'],
                // Nosotros
                ['href'=>route('quienes-somos'),       'icon'=>'bi-people-fill',          'color'=>'cyan',   'label'=>'¿Quiénes somos?',        'desc'=>'Misión, visión e identidad'],
                // Servicios
                ['href'=>route('bolsa-de-trabajo'),    'icon'=>'bi-briefcase-fill',       'color'=>'indigo', 'label'=>'Bolsa de Trabajo',       'desc'=>'Ofertas de empleo vigentes'],
            ];
            $colorMap = [
                'blue'   => ['qa'=>'bg-blue-50 border-blue-100   hover:border-blue-300',  'ic'=>'bg-blue-600   text-white',  'lbl'=>'text-blue-900',   'sub'=>'text-blue-700',   'arr'=>'text-blue-500'],
                'sky'    => ['qa'=>'bg-sky-50  border-sky-100    hover:border-sky-300',   'ic'=>'bg-sky-500    text-white',  'lbl'=>'text-sky-900',    'sub'=>'text-sky-700',    'arr'=>'text-sky-500'],
                'cyan'   => ['qa'=>'bg-cyan-50 border-cyan-100   hover:border-cyan-300',  'ic'=>'bg-cyan-600   text-white',  'lbl'=>'text-cyan-900',   'sub'=>'text-cyan-700',   'arr'=>'text-cyan-500'],
                'indigo' => ['qa'=>'bg-indigo-50 border-indigo-100 hover:border-indigo-300','ic'=>'bg-indigo-600 text-white','lbl'=>'text-indigo-900', 'sub'=>'text-indigo-700', 'arr'=>'text-indigo-500'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($quickAccess as $i => $qa)
                @php $c = $colorMap[$qa['color']]; @endphp
                <a href="{{ $qa['href'] }}"
                   id="qa-link-{{ $i }}"
                   class="qa-card reveal flex items-center gap-4 p-4 bg-white border rounded-2xl transition-all duration-200 {{ $c['qa'] }} shadow-sm hover:shadow-md group"
                   style="transition-delay:{{ ($i % 4) * 60 }}ms">
                    <span class="qa-icon {{ $c['ic'] }} shadow-sm">
                        <i class="bi {{ $qa['icon'] }}"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="font-bold text-sm {{ $c['lbl'] }} leading-tight">{{ $qa['label'] }}</p>
                        <p class="text-xs {{ $c['sub'] }} mt-0.5 line-clamp-2">{{ $qa['desc'] }}</p>
                    </div>
                    <i class="bi bi-arrow-right-short text-lg {{ $c['arr'] }} ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
                </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  4. PROGRAMAS DE ESTUDIO                                             ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
@if($programs->isNotEmpty())
<section class="py-20 bg-white" aria-label="Programas de estudio">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 reveal">
            <span class="section-pill bg-sky-100 text-sky-700 mb-3">Oferta Académica</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">Nuestros Programas de Estudio</h2>
            <div class="w-20 h-1.5 bg-sky-500 mx-auto mt-4 rounded-full"></div>
            <p class="text-gray-500 mt-5 max-w-xl mx-auto leading-relaxed">
                Formación técnica de calidad orientada al mundo laboral. Elige la carrera que impulsará tu desarrollo profesional.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-{{ min($programs->count(), 4) }} gap-6">
            @foreach($programs as $i => $program)
                <a href="/programas-de-estudios/{{ $program->id }}"
                   class="card-lift reveal group bg-white rounded-2xl border border-sky-100 shadow-sm overflow-hidden flex flex-col"
                   style="transition-delay:{{ ($i % 4) * 70 }}ms">

                    {{-- Header con gradiente --}}
                    <div class="h-36 bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center p-4 relative overflow-hidden">
                        <div class="absolute -bottom-6 -right-6 w-28 h-28 bg-white/10 rounded-full"></div>
                        <div class="absolute -top-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        @if($program->logo_path)
                            <img src="{{ Storage::url($program->logo_path) }}" alt="{{ $program->name }}"
                                 class="h-20 w-20 object-contain drop-shadow-lg z-10" loading="lazy">
                        @else
                            <i class="bi bi-mortarboard-fill text-5xl text-white/90 z-10 relative"></i>
                        @endif
                    </div>

                    {{-- Cuerpo --}}
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="font-bold text-blue-900 text-base leading-snug mb-2 group-hover:text-sky-600 transition-colors">
                            {{ $program->name }}
                        </h3>
                        @if($program->description)
                            <p class="text-gray-500 text-sm line-clamp-3 flex-grow">{{ $program->description }}</p>
                        @else
                            <p class="text-gray-400 text-sm italic flex-grow">Sin descripción disponible.</p>
                        @endif
                        <div class="mt-4 pt-4 border-t border-sky-50 flex items-center justify-between">
                            <span class="text-xs text-sky-600 font-semibold uppercase tracking-wide">Ver programa</span>
                            <i class="bi bi-arrow-right text-sky-500 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12 reveal">
            <a href="{{ route('programas-de-estudio') }}"
               id="btn-ver-programas"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-blue-600/25 hover:shadow-xl">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Ver todos los programas
            </a>
        </div>
    </div>
</section>
@endif


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  5. ¿POR QUÉ ELEGIRNOS? — 3 propuestas de valor                     ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="py-20 bg-gradient-to-b from-sky-50 to-white" aria-label="Propuesta de valor institucional">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 reveal">
            <span class="section-pill bg-cyan-100 text-cyan-800 mb-3">Nuestra propuesta</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">¿Por qué elegir el IESTP FVC?</h2>
            <div class="w-20 h-1.5 bg-cyan-500 mx-auto mt-4 rounded-full"></div>
        </div>

        @php
            $values = [
                ['icon'=>'bi-award-fill',      'color'=>'blue',   'title'=>'Carreras a Nombre de la Nación',    'desc'=>'Nuestros títulos son emitidos por el Minedu, con validez oficial en todo el territorio peruano y reconocidos por empleadores a nivel nacional.'],
                ['icon'=>'bi-lightning-charge-fill','color'=>'sky','title'=>'Formación práctica e innovadora',  'desc'=>'Talleres equipados, laboratorios actualizados y docentes con experiencia en el sector productivo para garantizar una educación de calidad.'],
                ['icon'=>'bi-briefcase-fill',  'color'=>'cyan',   'title'=>'Inserción laboral garantizada',      'desc'=>'Nuestra bolsa de trabajo activa conecta a nuestros egresados con las mejores empresas e instituciones de la región y del país.'],
                ['icon'=>'bi-people-fill',     'color'=>'indigo', 'title'=>'Convenios con empresas líderes',    'desc'=>'Contamos con alianzas estratégicas con empresas del sector público y privado que garantizan prácticas pre-profesionales de calidad.'],
                ['icon'=>'bi-patch-check-fill','color'=>'blue',   'title'=>'En proceso de licenciamiento',      'desc'=>'Cumplimos rigurosamente con las condiciones básicas de calidad exigidas por el Ministerio de Educación del Perú.'],
                ['icon'=>'bi-geo-alt-fill',    'color'=>'sky',    'title'=>'Accesible para toda la región',     'desc'=>'Ubicados en Uchiza, servimos a toda la provincia de Tocache y regiones vecinas con horarios flexibles y costos accesibles.'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($values as $i => $v)
                @php
                    $vc = [
                        'blue'   => ['card'=>'bg-blue-50/60   border-blue-100   hover:border-blue-300',   'ic'=>'bg-blue-600   text-white   shadow-blue-600/20',   'h'=>'text-blue-900'],
                        'sky'    => ['card'=>'bg-sky-50/60    border-sky-100    hover:border-sky-300',    'ic'=>'bg-sky-500    text-white   shadow-sky-500/20',    'h'=>'text-sky-900'],
                        'cyan'   => ['card'=>'bg-cyan-50/60   border-cyan-100   hover:border-cyan-300',   'ic'=>'bg-cyan-600   text-white   shadow-cyan-600/20',   'h'=>'text-cyan-900'],
                        'indigo' => ['card'=>'bg-indigo-50/60 border-indigo-100 hover:border-indigo-300', 'ic'=>'bg-indigo-600 text-white   shadow-indigo-600/20', 'h'=>'text-indigo-900'],
                    ][$v['color']];
                @endphp
                <div class="reveal card-lift border p-7 rounded-2xl transition-all {{ $vc['card'] }}"
                     style="transition-delay:{{ ($i % 3) * 80 }}ms">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg {{ $vc['ic'] }}">
                        <i class="bi {{ $v['icon'] }} text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold mb-2 {{ $vc['h'] }}">{{ $v['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $v['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  6. BOLSA DE TRABAJO — Últimas ofertas                               ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="py-20 bg-white" aria-label="Ofertas de empleo recientes">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 reveal">
            <span class="section-pill bg-indigo-100 text-indigo-700 mb-3">Empleabilidad</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">Últimas Ofertas de Empleo</h2>
            <div class="w-20 h-1.5 bg-indigo-500 mx-auto mt-4 rounded-full"></div>
            <p class="text-gray-500 mt-5 max-w-xl mx-auto">
                Conectamos a nuestros estudiantes y egresados con las mejores oportunidades laborales de la región.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jobOffers->take(6) as $i => $offer)
                <div class="reveal card-lift bg-white rounded-2xl border border-indigo-50 shadow-sm overflow-hidden flex flex-col relative"
                     style="transition-delay:{{ ($i % 3) * 70 }}ms">

                    {{-- Stripe superior por color --}}
                    <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                    <div class="p-6 flex flex-col flex-grow">
                        @if($offer->source)
                            <span class="inline-block self-start mb-3 bg-indigo-50 text-indigo-600 text-[11px] font-bold px-3 py-1 rounded-full border border-indigo-100">
                                {{ $offer->source }}
                            </span>
                        @endif

                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-11 h-11 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="bi bi-laptop text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-blue-900 text-sm leading-tight line-clamp-2">{{ $offer->title }}</h3>
                                <p class="text-indigo-600 font-semibold text-xs mt-0.5">{{ $offer->company }}</p>
                            </div>
                        </div>

                        <p class="text-gray-500 text-sm line-clamp-3 flex-grow">
                            {{ $offer->description ?? 'No hay una descripción detallada. Haz clic en "Ver detalles" para consultar la fuente.' }}
                        </p>

                        <div class="pt-4 mt-4 border-t border-indigo-50 flex items-center justify-between">
                            <p class="text-gray-500 flex items-center gap-1.5 text-xs">
                                <i class="bi bi-geo-alt-fill text-indigo-400"></i>
                                {{ $offer->location ?? 'Ubicación no especificada' }}
                            </p>
                        </div>

                        <a href="{{ $offer->url ?? '#' }}"
                           target="{{ $offer->url ? '_blank' : '_self' }}"
                           rel="noopener noreferrer"
                           class="mt-4 w-full bg-blue-900 hover:bg-blue-800 text-white py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 font-semibold text-sm">
                            Ver detalles
                            <i class="bi bi-box-arrow-up-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-indigo-50/50 rounded-2xl border border-dashed border-indigo-200 reveal">
                    <i class="bi bi-inbox text-5xl text-indigo-300 block mb-4"></i>
                    <h3 class="text-lg font-bold text-indigo-900">No hay ofertas disponibles ahora</h3>
                    <p class="text-indigo-600 mt-1 text-sm">Vuelve pronto, actualizamos constantemente nuestras oportunidades.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12 reveal">
            <a href="{{ route('bolsa-de-trabajo') }}"
               id="btn-ver-ofertas"
               class="inline-flex items-center gap-2 border-2 border-indigo-500 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold px-8 py-3.5 rounded-xl transition-all">
                <i class="bi bi-collection-fill"></i>
                Ver todas las ofertas @if($jobOffers->count() > 6)({{ $jobOffers->count() }})@endif
            </a>
        </div>
    </div>
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  7. BLOG / NOTICIAS — Últimas publicaciones                          ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="py-20 bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 relative overflow-hidden"
         aria-label="Blog institucional y noticias">
    {{-- Decoración fondo --}}
    <div class="absolute inset-0 opacity-[.05]"
         style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:32px 32px;"
         aria-hidden="true"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-14">
            <div class="reveal">
                <span class="section-pill bg-sky-500/20 text-sky-300 border border-sky-400/30 mb-3">Blog Institucional</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Noticias y Novedades</h2>
                <div class="w-20 h-1.5 bg-sky-400 mt-4 rounded-full"></div>
            </div>
            {{-- Aquí puedes agregar un enlace a /blog cuando esté disponible --}}
        </div>

        @if($blogs->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($blogs as $i => $post)
                    <article class="reveal card-lift bg-white/8 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden flex flex-col hover:bg-white/12 hover:border-sky-400/30 transition-all"
                             style="transition-delay:{{ $i * 80 }}ms">

                        {{-- Imagen del post o placeholder --}}
                        @php $cover = $post->coverImage(); @endphp
                        @if($cover)
                            <div class="h-44 overflow-hidden">
                                <img src="{{ $cover }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="h-44 bg-gradient-to-br from-blue-800 to-indigo-800 flex items-center justify-center">
                                <i class="bi bi-newspaper text-5xl text-white/30"></i>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-sky-400 bg-sky-400/10 border border-sky-400/20 px-2.5 py-1 rounded-full">
                                    Publicación
                                </span>
                                @if($post->created_at)
                                    <time class="text-xs text-blue-300/70" datetime="{{ $post->created_at->toISOString() }}">
                                        {{ $post->created_at->locale('es')->isoFormat('D MMM YYYY') }}
                                    </time>
                                @endif
                            </div>

                            <h3 class="font-bold text-white text-base leading-snug mb-3 line-clamp-2">
                                {{ $post->title }}
                            </h3>

                            <p class="text-blue-200/70 text-sm leading-relaxed line-clamp-3 flex-grow">
                                {{ $post->excerpt(160) }}
                            </p>

                            <div class="mt-5 pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-xs text-blue-300/60 flex items-center gap-1.5">
                                    <i class="bi bi-clock"></i>
                                    {{ $post->created_at?->diffForHumans() ?? '—' }}
                                </span>
                                <a href="/blog/{{ $post->slug }}"
                                   class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1 transition-colors">
                                    Leer más <i class="bi bi-arrow-right text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

        @else
            {{-- Estado vacío premium --}}
            <div class="reveal text-center py-16 bg-white/5 border border-dashed border-sky-400/20 rounded-2xl">
                <div class="w-16 h-16 mx-auto mb-4 bg-sky-500/15 border border-sky-400/20 rounded-2xl flex items-center justify-center">
                    <i class="bi bi-newspaper text-3xl text-sky-400"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Próximamente — Blog Institucional</h3>
                <p class="text-blue-300/60 mt-2 text-sm max-w-md mx-auto">
                    Aquí publicaremos noticias, eventos y novedades del IESTP Francisco Vigo Caballero. ¡Vuelve pronto!
                </p>
                <div class="mt-6 flex items-center justify-center gap-4 text-xs text-blue-400/60">
                    <span class="flex items-center gap-1.5"><i class="bi bi-calendar3"></i> Eventos</span>
                    <span class="flex items-center gap-1.5"><i class="bi bi-megaphone"></i> Noticias</span>
                    <span class="flex items-center gap-1.5"><i class="bi bi-trophy"></i> Logros</span>
                </div>
            </div>
        @endif
    </div>
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  8. ALIADOS / PARTNERS — Carrusel infinito                           ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="py-16 bg-white border-t border-sky-100 overflow-hidden" aria-label="Empresas e instituciones aliadas">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 text-center reveal">
        <span class="section-pill bg-blue-100 text-blue-700 mb-3">Nuestros Aliados</span>
        <h2 class="text-3xl font-extrabold text-blue-900 mt-2">
            Convenios con empresas e instituciones que confían en nosotros
        </h2>
        <p class="text-gray-500 mt-3 text-sm">Aliados estratégicos en la formación y el empleo de nuestros estudiantes.</p>
    </div>

    @if($partners->isNotEmpty())
        <div class="relative w-full max-w-[1400px] mx-auto overflow-hidden flex items-center">
            <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
            <div class="carousel-track gap-6 px-4">
                @for($i = 0; $i < 2; $i++)
                    @foreach($partners as $partner)
                        <div class="w-44 h-28 shrink-0 bg-white border border-sky-100 rounded-2xl flex items-center justify-center p-4 shadow-sm hover:shadow-md hover:border-sky-300 transition-all cursor-default">
                            @if($partner->image_url)
                                <img src="{{ Storage::url($partner->image_url) }}"
                                     alt="{{ $partner->company }}"
                                     class="w-full h-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300"
                                     loading="lazy">
                            @else
                                <span class="text-gray-400 font-bold text-center text-sm px-2">{{ $partner->company }}</span>
                            @endif
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    @else
        <div class="text-center text-gray-400 py-8 text-sm">
            Próximamente estaremos añadiendo a nuestros aliados estratégicos.
        </div>
    @endif
</section>


{{-- ╔══════════════════════════════════════════════════════════════════════╗
     ║  9. CTA FINAL — Llamada a la acción                                  ║
     ╚══════════════════════════════════════════════════════════════════════╝ --}}
<section class="relative py-24 bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-600 overflow-hidden"
         aria-label="Llamada a la acción">
    <div class="absolute inset-0 opacity-10"
         style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:24px 24px;"
         aria-hidden="true"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 bg-blue-900/20 rounded-full blur-3xl" aria-hidden="true"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 text-white px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
            <i class="bi bi-mortarboard-fill"></i>
            Admisión 2026 Abierta
        </div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
            ¿Listo para comenzar tu carrera técnica<br class="hidden sm:block"> y transformar tu futuro?
        </h2>
        <p class="text-white/85 text-lg mt-5 max-w-2xl mx-auto leading-relaxed">
            Únete al IESTP Francisco Vigo Caballero, la institución tecnológica de referencia en Uchiza. Formación de calidad, docentes expertos y conexión directa con el mundo laboral.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
            <a href="{{ route('cepre-fvc') }}"
               id="cta-final-cepre"
               class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white hover:bg-blue-50 text-blue-700 font-extrabold rounded-xl transition-all shadow-xl text-base">
                <i class="bi bi-book-fill"></i>
                Proceso CEPRE FVC
            </a>
            <a href="{{ route('examen-de-admision') }}"
               id="cta-final-admision"
               class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-transparent border-2 border-white/70 hover:bg-white/15 text-white font-bold rounded-xl transition-all text-base">
                <i class="bi bi-pencil-square"></i>
                Examen de Admisión
            </a>
        </div>
    </div>
</section>

@endsection


@push('scripts')
<script>
/* ── JSON-LD WebPage + BreadcrumbList ──────────────────────────── */
(function () {
    const ld = {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebPage",
                "@id": "{{ url('/') }}",
                "url": "{{ url('/') }}",
                "name": "IESTP Francisco Vigo Caballero — Formación Técnica Superior en Uchiza",
                "description": "Portal oficial del IESTP Francisco Vigo Caballero en Uchiza, San Martín, Perú.",
                "inLanguage": "es-PE",
                "isPartOf": { "@id": "{{ url('/') }}#website" }
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    { "@type": "ListItem", "position": 1, "name": "Inicio", "item": "{{ url('/') }}" }
                ]
            }
        ]
    };
    const s = document.createElement('script');
    s.type  = 'application/ld+json';
    s.text  = JSON.stringify(ld);
    document.head.appendChild(s);
})();

/* ── Reveal on scroll (IntersectionObserver) ─────────────────── */
const revealEls = document.querySelectorAll('.reveal');
if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    revealEls.forEach(el => io.observe(el));
} else {
    revealEls.forEach(el => el.classList.add('visible'));
}

/* ── Stat counter animation ──────────────────────────────────── */
function animateCounter(el) {
    const target = parseInt(el.dataset.target || '0', 10);
    const suffix = el.closest('.reveal')?.querySelector('p')?.dataset?.suffix || '';
    const duration = 1400;
    const start    = performance.now();
    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const ease     = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(ease * target) + (progress >= 1 ? '+' : '');
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

const statEls = document.querySelectorAll('.stat-number');
if ('IntersectionObserver' in window) {
    const sio = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { animateCounter(e.target); sio.unobserve(e.target); }
        });
    }, { threshold: 0.5 });
    statEls.forEach(el => sio.observe(el));
} else {
    statEls.forEach(el => { el.textContent = el.dataset.target + '+'; });
}
</script>
@endpush
