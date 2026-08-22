@extends('layouts.app')

@section('title', 'Nuestros Locales e Infraestructura — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO Optimization Meta Tags --}}
    <meta name="description"
        content="Conoce el campus e instalaciones del IESTP Francisco Vigo Caballero en Uchiza. Aulas modernas, laboratorios de computación, talleres agropecuarios, gabinetes de enfermería y áreas deportivas.">
    <meta name="keywords"
        content="locales IESTP Francisco Vigo Caballero, campus uchiza, infraestructura educativa, laboratorios tecnicos, gabinete enfermeria, talleres agropecuarios, instituto uchiza, san martin">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Nuestros Locales e Infraestructura — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Campus moderno con talleres y laboratorios especializados en Uchiza, San Martín.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "IESTP Francisco Vigo Caballero",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('enterprise/favicons/logo-iestpfvc.png') }}",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
        "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
        "addressRegion": "San Martín",
        "addressCountry": "PE"
      },
      "hasMap": "https://maps.google.com/?q={{ urlencode(($enterprise->address ?? 'Av. Ricardo Palma N° 1401') . ', ' . ($enterprise->city ?? 'Uchiza') . ', Peru') }}",
      "location": {
        "@type": "Place",
        "name": "Sede Principal IESTP Francisco Vigo Caballero",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
          "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
          "addressRegion": "San Martín",
          "addressCountry": "PE"
        }
      }
    }
    </script>

    <style>
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.15);
        }
    </style>
@endpush

@section('content')
    {{-- ===== HERO SECTION ===== --}}
    <section
        class="relative bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-blue-900/30">
        {{-- Glow patterns --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(56,189,248,0.15),transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(59,130,246,0.12),transparent_40%)]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
            <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 text-sky-300 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase">
                <i class="bi bi-geo-alt-fill text-sky-400"></i>
                Campus & Infraestructura Educativa
            </div>

            <h1
                class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                Nuestros <span
                    class="text-sky-400 bg-gradient-to-r from-sky-400 to-blue-400 bg-clip-text text-transparent">Locales
                    e Instalaciones</span>
            </h1>

            <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                Infraestructura moderna, laboratorios equipados y ambientes de aprendizaje diseñados para la formación profesional técnica de excelencia en Uchiza.
            </p>

            {{-- Metrics Grid in Hero --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">Sede Central</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}</p>
                </div>
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">5 Talleres</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Laboratorios Especializados</p>
                </div>
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300 col-span-2 md:col-span-1">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">100%</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Equipado para Prácticas Reales</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MAIN CAMPUS DISPLAY SECTION ===== --}}
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Main Campus Banner --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden mb-20">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                    <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between space-y-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider mb-4">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Sede Principal Activa
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                                Campus Institucional Uchiza
                            </h2>
                            <p class="text-slate-600 text-base sm:text-lg mt-3 leading-relaxed font-medium">
                                Nuestro campus integra aulas pedagógicas, laboratorios tecnológicos de última generación, módulos agropecuarios y campos forestales experimentales en una ubicación estratégica de fácil acceso.
                            </p>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="bi bi-geo-alt-fill text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Dirección Oficial</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}, {{ $enterprise->city ?? 'Uchiza' }}, San Martín, Perú</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="bi bi-clock-fill text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Horario de Atención Presencial</p>
                                    <p class="text-sm font-bold text-slate-800">Lunes a Viernes: 7:30 am – 1:30 pm (Mesa de Partes y Trámites)</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 pt-2">
                            <a href="https://maps.google.com/?q={{ urlencode(($enterprise->address ?? 'Av. Ricardo Palma N° 1401') . ', ' . ($enterprise->city ?? 'Uchiza') . ', Peru') }}"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md hover:shadow-lg text-sm">
                                <i class="bi bi-map-fill"></i>
                                Ver en Google Maps
                            </a>
                            @if($enterprise->phone_number_1)
                                <a href="tel:{{ $enterprise->phone_number_1 }}"
                                   class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition text-sm">
                                    <i class="bi bi-telephone-fill"></i>
                                    {{ $enterprise->phone_number_1 }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-5 bg-slate-900 relative min-h-[320px] flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-slate-900/90 to-slate-950 z-10"></div>
                        <div class="relative z-20 text-center p-8 space-y-4">
                            <div class="w-20 h-20 bg-blue-500/20 border border-blue-400/30 rounded-3xl flex items-center justify-center mx-auto shadow-2xl">
                                <i class="bi bi-building-fill text-4xl text-sky-400"></i>
                            </div>
                            <h3 class="text-2xl font-black text-white">IESTP FVC</h3>
                            <p class="text-sm text-slate-300 font-medium max-w-xs mx-auto">
                                Educación Técnica Superior Gratuita y de Calidad a Nombre de la Nación
                            </p>
                            <span class="inline-block bg-sky-400/20 border border-sky-400/40 text-sky-300 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">
                                Licenciamiento en Proceso
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== DEDICATED GOOGLE MAPS SECTION ===== --}}
            <div id="mapa-ubicacion" class="mb-24" x-data="{ copied: false }">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        <i class="bi bi-geo-alt-fill text-blue-600"></i> Localización Georreferenciada
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Ubicación y Mapa del Campus
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed font-medium">
                        Encuentra la sede central del IESTP Francisco Vigo Caballero en Google Maps y traza la ruta más rápida hacia nuestras instalaciones.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-4 sm:p-6 lg:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                        
                        {{-- Interactive Google Maps Iframe Frame --}}
                        <div class="lg:col-span-8 flex flex-col rounded-2xl overflow-hidden border border-slate-900 shadow-md">
                            {{-- Header Bar --}}
                            <div class="bg-slate-900 text-white px-5 py-3 flex items-center justify-between shrink-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                                    <span class="text-xs font-bold text-slate-300 ml-2 hidden sm:inline">Google Maps — IESTP Francisco Vigo Caballero</span>
                                </div>
                            </div>

                            {{-- Dynamic Iframe Embed Container --}}
                            <div class="flex-1 w-full min-h-[420px] sm:min-h-[500px] bg-slate-100 relative [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                                @if(!empty($enterprise->google_maps_iframe))
                                    {!! $enterprise->google_maps_iframe !!}
                                @else
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.886026543033!2d-76.46860822416807!3d-8.449056191591522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91af63dcf2a7d793%3A0x39afb5dd2aae7783!2sFRANCISCO%20VIGO%20CABALLERO!5e0!3m2!1ses!2spe!4v1740000000000!5m2!1ses!2spe" 
                                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                @endif
                            </div>
                        </div>
                        {{-- Map Sidebar Information & Route Navigation --}}
                        <div class="lg:col-span-4 flex flex-col justify-between space-y-5 bg-slate-50 p-6 sm:p-7 rounded-2xl border border-slate-200">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900">Ubicación Institucional</h3>
                                        <p class="text-xs text-slate-500 font-medium">Uchiza, Región San Martín</p>
                                    </div>
                                </div>

                                {{-- Direction Detail --}}
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Dirección</p>
                                    <p class="text-sm font-bold text-slate-900 leading-snug">
                                        {{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium">
                                        {{ $enterprise->city ?? 'Uchiza' }} – Tocache, San Martín
                                    </p>
                                </div>

                                {{-- GPS Coordinates --}}
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Coordenadas GPS</p>
                                        <button type="button" 
                                                @click="navigator.clipboard.writeText('-8.4490562, -76.4660333'); copied = true; setTimeout(() => copied = false, 2500)"
                                                class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                                            <i class="bi" :class="copied ? 'bi-check-lg text-emerald-600' : 'bi-clipboard'"></i>
                                            <span x-text="copied ? '¡Copiado!' : 'Copiar'"></span>
                                        </button>
                                    </div>
                                    <p class="font-mono text-xs font-bold text-slate-800">
                                        -8.4490562, -76.4660333
                                    </p>
                                </div>

                                {{-- Access routes --}}
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2 text-xs">
                                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">¿Cómo Llegar?</p>
                                    <div class="flex items-start gap-2 text-slate-600 font-medium">
                                        <i class="bi bi-bicycle text-blue-600 shrink-0 text-sm mt-0.5"></i>
                                        <span><strong>Urbano:</strong> A 3-5 minutos en mototaxi desde la Plaza Mayor de Uchiza.</span>
                                    </div>
                                    <div class="flex items-start gap-2 text-slate-600 font-medium">
                                        <i class="bi bi-bus-front text-blue-600 shrink-0 text-sm mt-0.5"></i>
                                        <span><strong>Interprovincial:</strong> Vía terrestre directa desde Tocache, Tingo María y Juanjuí.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Navigation Buttons --}}
                            <div class="space-y-2.5 pt-2">
                                <a href="https://www.google.com/maps/place/FRANCISCO+VIGO+CABALLERO/@-8.4505844,-76.465598,16z/data=!4m6!3m5!1s0x91af63dcf2a7d793:0x39afb5dd2aae7783!8m2!3d-8.4490562!4d-76.4660333!16s%2Fg%2F11hzzr2wh2?entry=ttu&g_ep=EgoyMDI2MDgxOS4wIKXMDSoASAFQAw%3D%3D"
                                   target="_blank" rel="noopener noreferrer"
                                   class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold py-3.5 px-4 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-xs sm:text-sm text-center">
                                    <i class="bi bi-cursor-fill text-sky-200"></i>
                                    Abrir en Google Maps (Ruta GPS)
                                </a>

                                @if($enterprise->phone_number_1)
                                    <a href="tel:{{ $enterprise->phone_number_1 }}"
                                       class="w-full bg-white hover:bg-slate-100 text-slate-700 font-bold py-2.5 px-4 rounded-xl transition border border-slate-300 flex items-center justify-center gap-2 text-xs text-center">
                                        <i class="bi bi-telephone-fill text-blue-600"></i>
                                        Llamar: {{ $enterprise->phone_number_1 }}
                                    </a>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Facilities Grid --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Ambientes de Aprendizaje
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                    Instalaciones Especializadas
                </h2>
                <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                    Nuestros ambientes están implementados para la formación teórico-práctica en cada uno de los 5 programas de estudio.
                </p>
            </div>

            @php
                $facilities = [
                    [
                        'title' => 'Aulas Pedagógicas & Tecnológicas',
                        'desc' => 'Salones iluminados y climatizados equipados con proyectores multimedia y conectividad para sesiones interactivas.',
                        'icon' => 'bi-easel-fill',
                        'badge' => 'Formación Teórica',
                        'color' => 'blue',
                        'bg_icon' => 'bg-blue-100 text-blue-600',
                    ],
                    [
                        'title' => 'Laboratorio de Computación y Redes',
                        'desc' => 'Equipos informáticos con software especializado para Administración de Redes, diseño y gestión empresarial.',
                        'icon' => 'bi-pc-display-horizontal',
                        'badge' => 'Tecnología & TI',
                        'color' => 'sky',
                        'bg_icon' => 'bg-sky-100 text-sky-600',
                    ],
                    [
                        'title' => 'Gabinete de Enfermería Técnica',
                        'desc' => 'Módulos de simulación clínica con camas hospitalarias, maquetas anatómicas y material biomédico para prácticas de salud.',
                        'icon' => 'bi-heart-pulse-fill',
                        'badge' => 'Ciencias de la Salud',
                        'color' => 'rose',
                        'bg_icon' => 'bg-rose-100 text-rose-600',
                    ],
                    [
                        'title' => 'Campos de Práctica Agropecuaria',
                        'desc' => 'Vivero institucional, parcelas demostrativas y áreas de crianza para el aprendizaje vivencial de la carrera agropecuaria.',
                        'icon' => 'bi-tree-fill',
                        'badge' => 'Campo & Producción',
                        'color' => 'emerald',
                        'bg_icon' => 'bg-emerald-100 text-emerald-600',
                    ],
                    [
                        'title' => 'Vivero y Taller Forestal',
                        'desc' => 'Área de propagación botánica, herbario e instrumental topográfico para la especialidad de Manejo Forestal.',
                        'icon' => 'bi-globe-americas',
                        'badge' => 'Recursos Naturales',
                        'color' => 'teal',
                        'bg_icon' => 'bg-teal-100 text-teal-600',
                    ],
                    [
                        'title' => 'Biblioteca & Centro de Documentación',
                        'desc' => 'Espacio de lectura silenciosa con acervo bibliográfico físico y acceso directo a la Biblioteca Virtual Latina.',
                        'icon' => 'bi-book-half',
                        'badge' => 'Investigación',
                        'color' => 'indigo',
                        'bg_icon' => 'bg-indigo-100 text-indigo-600',
                    ],
                    [
                        'title' => 'Auditorio Institucional',
                        'desc' => 'Ambiente para conferencias, ferias de proyectos tecnológicos, ponencias magistrales y eventos protocolares.',
                        'icon' => 'bi-person-workspace',
                        'badge' => 'Eventos & Ponencias',
                        'color' => 'amber',
                        'bg_icon' => 'bg-amber-100 text-amber-600',
                    ],
                    [
                        'title' => 'Losa Multideportiva',
                        'desc' => 'Cancha polideportiva para disciplinas de futsal, vóley y actividades de integración de la comunidad estudiantil.',
                        'icon' => 'bi-trophy-fill',
                        'badge' => 'Deportes & Recreación',
                        'color' => 'cyan',
                        'bg_icon' => 'bg-cyan-100 text-cyan-600',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($facilities as $f)
                    <div class="group bg-white rounded-3xl border border-slate-100 shadow-md hover-card p-6 flex flex-col justify-between h-full">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $f['bg_icon'] }} shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="bi {{ $f['icon'] }} text-2xl"></i>
                                </div>
                                <span class="text-[11px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                                    {{ $f['badge'] }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                                {{ $f['title'] }}
                            </h3>
                            <p class="text-slate-600 text-sm leading-relaxed font-medium">
                                {{ $f['desc'] }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-blue-600">
                            <i class="bi bi-check-circle-fill text-emerald-500"></i>
                            Disponible para estudiantes
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ===== PROGRAM LINKING SECTION ===== --}}
    @if($programs->isNotEmpty())
        <section class="py-20 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-sky-100 text-sky-800 uppercase tracking-wider">
                        Integración Académica
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                        Especialidades en Nuestras Instalaciones
                    </h2>
                    <p class="text-base sm:text-lg text-slate-600 mt-3 font-medium">
                        Cada ambiente responde directamente a la formación práctica de nuestras 5 carreras profesionales técnicas.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    @foreach($programs as $p)
                        <a href="/programas-de-estudios/{{ $p->id }}"
                           class="p-5 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 text-center group flex flex-col items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-white text-blue-600 flex items-center justify-center shadow-sm mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                                <i class="bi bi-mortarboard-fill text-lg"></i>
                            </div>
                            <p class="font-bold text-sm leading-snug mb-2 group-hover:text-white">{{ $p->name }}</p>
                            <span class="text-xs font-semibold text-blue-600 group-hover:text-white/80 flex items-center gap-1">
                                Ver carrera <i class="bi bi-arrow-right"></i>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== FAQ SECTION ===== --}}
    <section class="py-24 bg-slate-50" x-data="{ activeFaq: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Información al Visitante
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                    Preguntas Frecuentes sobre el Campus
                </h2>
                <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                    Aclaramos tus dudas sobre la ubicación, accesos y horarios de nuestras instalaciones.
                </p>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden transition-all duration-300"
                    :class="activeFaq === 1 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 1 ? null : 1">
                        <span>¿Dónde está ubicado el campus principal del IESTP FVC?</span>
                        <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                            :class="activeFaq === 1 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq1"
                        :style="activeFaq === 1 ? 'max-height: ' + $refs.faq1.scrollHeight + 'px' : ''">
                        <div
                            class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                            La sede central del Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero está situada en la <strong>{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}</strong>, en la ciudad de <strong>{{ $enterprise->city ?? 'Uchiza' }}</strong>, Provincia de Tocache, Región San Martín. Es una zona de fácil acceso urbano en mototaxi o transporte público.
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden transition-all duration-300"
                    :class="activeFaq === 2 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 2 ? null : 2">
                        <span>¿Los laboratorios y talleres están abiertos para los estudiantes fuera de horario de clases?</span>
                        <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                            :class="activeFaq === 2 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq2"
                        :style="activeFaq === 2 ? 'max-height: ' + $refs.faq2.scrollHeight + 'px' : ''">
                        <div
                            class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                            Sí. Los laboratorios informáticos, la biblioteca institucional y los gabinetes están disponibles para prácticas libres y trabajos de investigación en horarios coordinados con los docentes encargados de cada área.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden transition-all duration-300"
                    :class="activeFaq === 3 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 3 ? null : 3">
                        <span>¿Cuál es el horario para la atención presencial en Mesa de Partes?</span>
                        <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                            :class="activeFaq === 3 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq3"
                        :style="activeFaq === 3 ? 'max-height: ' + $refs.faq3.scrollHeight + 'px' : ''">
                        <div
                            class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                            La oficina de atención al público y Mesa de Partes atiende presencialmente de lunes a viernes en el horario de 7:30 am a 1:30 pm. También puedes realizar trámites virtuales mediante nuestra <a href="{{ route('mesa-de-partes') }}" class="text-blue-600 font-bold hover:underline">Mesa de Partes Digital</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section
        class="py-24 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white text-center relative overflow-hidden border-t border-blue-900/30">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.1),transparent_40%)]"></div>
        <div class="container mx-auto px-4 relative z-10 space-y-8">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight max-w-4xl mx-auto leading-tight">
                ¿Deseas conocer nuestro campus presencialmente?
            </h2>
            <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                Visítanos en Uchiza o contáctate con nosotros para brindarte la mejor orientación vocacional. ¡Te esperamos!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <a href="{{ route('examen-de-admision') }}"
                    class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-4.5 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5">
                    <i class="bi bi-check-circle-fill text-blue-600 text-lg"></i>
                    Admisión 2026
                </a>
                <a href="{{ route('mesa-de-partes') }}"
                    class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-4.5 rounded-xl font-extrabold transition flex items-center justify-center gap-2">
                    <i class="bi bi-inbox text-lg"></i>
                    Mesa de Partes Virtual
                </a>
            </div>
        </div>
    </section>
@endsection