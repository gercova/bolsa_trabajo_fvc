@extends('layouts.app')

{{-- ═══ SEO ═══════════════════════════════════════════════════════════════ --}}
@section('title', 'IESTP Francisco Vigo Caballero — Formación Técnica Superior en Uchiza')
@section('meta_description',
    'El IESTP Francisco Vigo Caballero ofrece 5 carreras técnicas a Nombre de la Nación en
    Uchiza, San Martín. Admisión, bolsa de trabajo, programas de estudio y más.')
@section('meta_keywords',
    'IESTP Francisco Vigo Caballero, instituto técnico Uchiza, carreras técnicas San Martín,
    admisión 2025, bolsa de trabajo, CEPRE FVC, formación técnica Perú')
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

        .blob-1 {
            width: 520px;
            height: 520px;
            background: #38bdf8;
            top: -120px;
            right: -80px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 380px;
            height: 380px;
            background: #6366f1;
            bottom: 0px;
            left: -60px;
            animation-delay: 2s;
        }

        .blob-3 {
            width: 260px;
            height: 260px;
            background: #22d3ee;
            top: 40%;
            right: 28%;
            animation-delay: 4s;
        }

        @keyframes blob-float {
            from {
                transform: translateY(0) scale(1);
            }

            to {
                transform: translateY(-30px) scale(1.06);
            }
        }

        /* ── Counter animation ──────────────────────────────────── */
        .stat-number {
            font-variant-numeric: tabular-nums;
        }

        /* ── Infinite carousel ──────────────────────────────────── */
        @keyframes slide-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .carousel-track {
            display: flex;
            width: max-content;
            animation: slide-left 35s linear infinite;
        }

        .carousel-track:hover {
            animation-play-state: paused;
        }

        /* ── Card hover lift ────────────────────────────────────── */
        .card-lift {
            transition: transform .28s ease, box-shadow .28s ease;
        }

        .card-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(14, 165, 233, .18);
        }

        /* ── Line clamp ──────────────────────────────────────────── */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── Fade-in on scroll ──────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Section label pill ─────────────────────────────────── */
        .section-pill {
            display: inline-block;
            padding: .2rem .9rem;
            border-radius: 9999px;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* ── Quick-access icon ring ─────────────────────────────── */
        .qa-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: .875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            transition: transform .22s ease;
        }

        .qa-card:hover .qa-icon {
            transform: scale(1.12) rotate(-4deg);
        }
    </style>
@endpush
@section('content')
    {{-- ═══ HERO CAROUSEL — UNSM STYLE ════════════════════════════════════ --}}
    <section class="relative bg-slate-950 text-white overflow-hidden select-none" aria-label="Carrusel de Portada Institucional"
        x-data="heroCarousel()"
        x-init="init()"
        @mouseenter="pauseTimer()"
        @mouseleave="resumeTimer()"
        @keydown.right.window="nextSlide()"
        @keydown.left.window="prevSlide()">

        {{-- Carousel Slides Container --}}
        <div class="relative w-full h-[540px] sm:h-[600px] lg:h-[660px] xl:h-[700px] overflow-hidden">
            
            {{-- SLIDE 1: Admisión & Formación --}}
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                :class="currentSlide === 0 ? 'opacity-100 z-20 pointer-events-auto' : 'opacity-0 z-10 pointer-events-none'">
                {{-- Background Photo with Ken Burns slow zoom --}}
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[8000ms] ease-out"
                    :class="currentSlide === 0 ? 'scale-105' : 'scale-100'"
                    style="background-image: url('{{ asset('images/slider_admision.jpg') }}');">
                </div>

                {{-- Multi-layered Dark Vignette & Gradient Overlays --}}
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 via-55% to-slate-950/30"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                {{-- Content --}}
                <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
                    <div class="max-w-3xl space-y-6 pt-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-bold tracking-wide backdrop-blur-md">
                            <i class="bi bi-mortarboard-fill text-amber-400"></i>
                            <span>Admisión 2026-I • Modalidades Abiertas</span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.12] text-white font-display">
                            Tu futuro profesional empieza aquí, en el <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-sky-300 to-cyan-300">
                                IESTP Francisco Vigo Caballero
                            </span>
                        </h1>

                        <p class="text-base sm:text-lg lg:text-xl text-slate-200 leading-relaxed font-sans max-w-2xl">
                            Estudia una de nuestras 5 carreras técnicas a Nombre de la Nación en Uchiza. Formación con alta demanda laboral, plana docente calificada y modernos ambientes.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3.5 pt-2">
                            <a href="{{ route('examen-de-admision') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all text-sm sm:text-base group">
                                <i class="bi bi-pencil-square text-lg group-hover:scale-110 transition-transform"></i>
                                <span>Examen de Admisión</span>
                            </a>
                            <a href="{{ route('programas-de-estudio') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-bold rounded-xl backdrop-blur-md transition-all text-sm sm:text-base">
                                <i class="bi bi-grid-3x3-gap-fill text-sky-300"></i>
                                <span>Ver 5 Carreras</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLIDE 2: Redes y Telecomunicaciones / Tecnología --}}
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                :class="currentSlide === 1 ? 'opacity-100 z-20 pointer-events-auto' : 'opacity-0 z-10 pointer-events-none'">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[8000ms] ease-out"
                    :class="currentSlide === 1 ? 'scale-105' : 'scale-100'"
                    style="background-image: url('{{ asset('images/slider_tecnologia.jpg') }}');">
                </div>

                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 via-55% to-slate-950/30"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
                    <div class="max-w-3xl space-y-6 pt-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/20 border border-sky-400/40 text-sky-300 text-xs sm:text-sm font-bold tracking-wide backdrop-blur-md">
                            <i class="bi bi-cpu-fill text-sky-400"></i>
                            <span>Innovación & Transformación Digital</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.12] text-white font-display">
                            Laboratorios modernos de computación, <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-indigo-300">
                                redes y telecomunicaciones
                            </span>
                        </h2>

                        <p class="text-base sm:text-lg lg:text-xl text-slate-200 leading-relaxed font-sans max-w-2xl">
                            Capacítate en arquitectura de redes, ciberseguridad, ensamblaje de servidores y desarrollo de software con talleres prácticos desde el primer ciclo.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3.5 pt-2">
                            <a href="{{ route('programas-de-estudio') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-sky-500/25 transition-all text-sm sm:text-base group">
                                <i class="bi bi-hdd-network-fill text-lg group-hover:scale-110 transition-transform"></i>
                                <span>Programa de Redes</span>
                            </a>
                            <a href="{{ route('bolsa-de-trabajo') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-bold rounded-xl backdrop-blur-md transition-all text-sm sm:text-base">
                                <i class="bi bi-briefcase-fill text-sky-300"></i>
                                <span>Bolsa de Empleo</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLIDE 3: Enfermería Técnica & Salud --}}
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                :class="currentSlide === 2 ? 'opacity-100 z-20 pointer-events-auto' : 'opacity-0 z-10 pointer-events-none'">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[8000ms] ease-out"
                    :class="currentSlide === 2 ? 'scale-105' : 'scale-100'"
                    style="background-image: url('{{ asset('images/slider_enfermeria.jpg') }}');">
                </div>

                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 via-55% to-slate-950/30"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
                    <div class="max-w-3xl space-y-6 pt-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/20 border border-rose-400/40 text-rose-300 text-xs sm:text-sm font-bold tracking-wide backdrop-blur-md">
                            <i class="bi bi-heart-pulse-fill text-rose-400"></i>
                            <span>Vocación de Servicio & Salud Comunitaria</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.12] text-white font-display">
                            Enfermería Técnica con <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-300 via-pink-300 to-amber-300">
                                simulación clínica integral
                            </span>
                        </h2>

                        <p class="text-base sm:text-lg lg:text-xl text-slate-200 leading-relaxed font-sans max-w-2xl">
                            Desarrolla competencias asistenciales con docentes médicos y licenciados. Convenios con hospitales y centros de salud para tus prácticas preprofesionales.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3.5 pt-2">
                            <a href="{{ route('programas-de-estudio') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white font-bold rounded-xl shadow-lg shadow-rose-500/25 transition-all text-sm sm:text-base group">
                                <i class="bi bi-heart-pulse text-lg group-hover:scale-110 transition-transform"></i>
                                <span>Enfermería Técnica</span>
                            </a>
                            <a href="{{ route('becas-y-creditos') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-bold rounded-xl backdrop-blur-md transition-all text-sm sm:text-base">
                                <i class="bi bi-award-fill text-rose-300"></i>
                                <span>Becas PRONABEC</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLIDE 4: Producción Agropecuaria & Manejo Forestal --}}
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                :class="currentSlide === 3 ? 'opacity-100 z-20 pointer-events-auto' : 'opacity-0 z-10 pointer-events-none'">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[8000ms] ease-out"
                    :class="currentSlide === 3 ? 'scale-105' : 'scale-100'"
                    style="background-image: url('{{ asset('images/slider_agroforestal.jpg') }}');">
                </div>

                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 via-55% to-slate-950/30"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="relative z-30 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
                    <div class="max-w-3xl space-y-6 pt-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-xs sm:text-sm font-bold tracking-wide backdrop-blur-md">
                            <i class="bi bi-tree-fill text-emerald-400"></i>
                            <span>Desarrollo Agroforestal & Sostenibilidad</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.12] text-white font-display">
                            Liderazgo en el agro y la <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-300 to-lime-300">
                                conservación de los bosques
                            </span>
                        </h2>

                        <p class="text-base sm:text-lg lg:text-xl text-slate-200 leading-relaxed font-sans max-w-2xl">
                            Aprende en parcelas demostrativas, viveros tecnificados y módulos de producción pecuaria en Uchiza. Formando técnicos para la productividad del Alto Huallaga.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3.5 pt-2">
                            <a href="{{ route('programas-de-estudio') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 transition-all text-sm sm:text-base group">
                                <i class="bi bi-tree text-lg group-hover:scale-110 transition-transform"></i>
                                <span>Ver Carreras del Agro</span>
                            </a>
                            <a href="{{ route('cepre-fvc') }}"
                                class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-white/10 hover:bg-white/20 border border-white/25 text-white font-bold rounded-xl backdrop-blur-md transition-all text-sm sm:text-base">
                                <i class="bi bi-book-fill text-emerald-300"></i>
                                <span>Ingreso Directo CEPRE</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Carousel Navigation Controls (Left / Right Floating Arrows) --}}
        <button type="button" @click="prevSlide()"
            class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-slate-900/60 hover:bg-white/25 border border-white/20 text-white flex items-center justify-center backdrop-blur-md transition-all duration-200 shadow-xl hover:scale-110 focus:outline-none cursor-pointer"
            aria-label="Diapositiva anterior">
            <i class="bi bi-chevron-left text-xl sm:text-2xl"></i>
        </button>

        <button type="button" @click="nextSlide()"
            class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-slate-900/60 hover:bg-white/25 border border-white/20 text-white flex items-center justify-center backdrop-blur-md transition-all duration-200 shadow-xl hover:scale-110 focus:outline-none cursor-pointer"
            aria-label="Siguiente diapositiva">
            <i class="bi bi-chevron-right text-xl sm:text-2xl"></i>
        </button>

        {{-- Bottom Slide Indicator Bar (UNSM Portal Style) --}}
        <div class="absolute bottom-6 sm:bottom-8 left-0 right-0 z-30 pointer-events-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                {{-- Slide Pills / Indicators --}}
                <div class="flex items-center gap-2 sm:gap-3 bg-slate-900/80 border border-white/20 px-3 py-1.5 rounded-full backdrop-blur-md pointer-events-auto">
                    <template x-for="(slide, index) in [
                        { label: 'Admisión 2026' },
                        { label: 'Redes & TI' },
                        { label: 'Enfermería' },
                        { label: 'Agroforestal' }
                    ]" :key="index">
                        <button type="button" @click="goToSlide(index)"
                            class="px-3 py-1 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                            :class="currentSlide === index ? 'bg-white text-slate-950 shadow-md scale-105' : 'text-slate-300 hover:text-white hover:bg-white/10'">
                            <span class="w-1.5 h-1.5 rounded-full" :class="currentSlide === index ? 'bg-blue-600' : 'bg-slate-500'"></span>
                            <span class="hidden sm:inline" x-text="slide.label"></span>
                            <span class="sm:hidden" x-text="index + 1"></span>
                        </button>
                    </template>
                </div>

                {{-- Play/Pause & Counter indicator --}}
                <div class="hidden md:flex items-center gap-3 bg-slate-900/80 border border-white/20 px-4 py-1.5 rounded-full backdrop-blur-md text-xs font-semibold text-slate-300 pointer-events-auto">
                    <button type="button" @click="isPaused = !isPaused" class="hover:text-white transition-colors cursor-pointer" :title="isPaused ? 'Reanudar carrusel' : 'Pausar carrusel'">
                        <i class="bi" :class="isPaused ? 'bi-play-fill text-amber-400 text-sm' : 'bi-pause-fill text-sm'"></i>
                    </button>
                    <span class="text-white font-bold" x-text="(currentSlide + 1) + ' / ' + totalSlides"></span>
                </div>
            </div>
        </div>

    </section>

    {{-- ═══ INSTITUTIONAL QUICK ACCESS BAR (UNSM STYLE) ════════════════════ --}}
    <section class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white border-y border-blue-700/40 py-4 shadow-md relative z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('examen-de-admision') }}"
                    class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-sky-500/20 text-sky-300 border border-sky-400/30 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <p class="text-xs text-blue-200 font-medium">Proceso 2026-I</p>
                        <p class="text-xs sm:text-sm font-bold text-white group-hover:text-sky-200 transition-colors">Admisión Ordinaria</p>
                    </div>
                </a>

                <a href="{{ route('mesa-de-partes') }}"
                    class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-cyan-500/20 text-cyan-300 border border-cyan-400/30 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs text-cyan-200 font-medium">Trámite Documentario</p>
                        <p class="text-xs sm:text-sm font-bold text-white group-hover:text-cyan-200 transition-colors">Mesa de Partes</p>
                    </div>
                </a>

                <a href="{{ route('bolsa-de-trabajo') }}"
                    class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-200 font-medium">Oportunidades Laborales</p>
                        <p class="text-xs sm:text-sm font-bold text-white group-hover:text-indigo-200 transition-colors">Bolsa de Trabajo</p>
                    </div>
                </a>

                <a href="{{ route('documentos-de-gestion') }}"
                    class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-400/30 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-file-earmark-ruled-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs text-amber-200 font-medium">Transparencia</p>
                        <p class="text-xs sm:text-sm font-bold text-white group-hover:text-amber-200 transition-colors">Documentos & TUPA</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- 4. PROGRAMAS DE ESTUDIO --}}
    @if ($programs->isNotEmpty())
        <section class="py-20 bg-white" aria-label="Programas de estudio">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14 reveal">
                    <span class="section-pill bg-sky-100 text-sky-700 mb-3">Oferta Académica</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">Nuestros Programas de Estudio</h2>
                    <div class="w-20 h-1.5 bg-sky-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-gray-500 mt-5 max-w-xl mx-auto leading-relaxed">
                        Formación técnica de calidad orientada al mundo laboral. Elige la carrera que impulsará tu
                        desarrollo profesional.
                    </p>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-{{ min($programs->count(), 4) }} gap-6">
                    @foreach ($programs as $i => $program)
                        <a href="{{ route('programas-de-estudio.detalle', $program->slug) }}"
                            class="card-lift reveal group bg-white rounded-2xl border border-sky-100 shadow-sm overflow-hidden flex flex-col"
                            style="transition-delay:{{ ($i % 4) * 70 }}ms">
                            {{-- Header con gradiente --}}
                            <div
                                class="h-36 bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center p-4 relative overflow-hidden">
                                <div class="absolute -bottom-6 -right-6 w-28 h-28 bg-white/10 rounded-full"></div>
                                <div class="absolute -top-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
                                @if ($program->logo_path)
                                    <img src="{{ Storage::url($program->logo_path) }}" alt="{{ $program->name }}"
                                        class="h-20 w-20 object-contain drop-shadow-lg z-10" loading="lazy">
                                @else
                                    <i class="bi bi-mortarboard-fill text-5xl text-white/90 z-10 relative"></i>
                                @endif
                            </div>
                            {{-- Cuerpo --}}
                            <div class="p-5 flex flex-col flex-grow">
                                <h3
                                    class="font-bold text-blue-900 text-base leading-snug mb-2 group-hover:text-sky-600 transition-colors">
                                    {{ $program->name }}
                                </h3>
                                @if ($program->description)
                                    <p class="text-gray-500 text-sm line-clamp-3 flex-grow">{{ $program->description }}
                                    </p>
                                @else
                                    <p class="text-gray-400 text-sm italic flex-grow">Sin descripción disponible.</p>
                                @endif
                                <div class="mt-4 pt-4 border-t border-sky-50 flex items-center justify-between">
                                    <span class="text-xs text-sky-600 font-semibold uppercase tracking-wide">Ver
                                        programa</span>
                                    <i
                                        class="bi bi-arrow-right text-sky-500 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-12 reveal">
                    <a href="{{ route('programas-de-estudio') }}" id="btn-ver-programas"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-blue-600/25 hover:shadow-xl">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        Ver todos los programas
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- 3. ACCESOS RÁPIDOS — Grid inspirado en el menú de navegación --}}
    <section class="bg-gradient-to-b from-sky-50 to-white py-20" aria-label="Accesos rápidos a secciones">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14 reveal">
                <span class="section-pill bg-blue-100 text-blue-700 mb-3">Navega el instituto</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">Todo lo que necesitas, en un solo lugar
                </h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Accede rápidamente a cualquier sección del portal
                    institucional.</p>
            </div>

            @php
                $quickAccess = [
                    // Admisión y Matrícula
                    [
                        'href' => route('cepre-fvc'),
                        'icon' => 'bi-book-fill',
                        'color' => 'blue',
                        'label' => 'CEPRE FVC',
                        'desc' => 'Preparación para ingreso directo',
                    ],
                    [
                        'href' => route('examen-de-admision'),
                        'icon' => 'bi-pencil-square',
                        'color' => 'sky',
                        'label' => 'Examen de Admisión',
                        'desc' => 'Procesos de admisión vigentes',
                    ],
                    [
                        'href' => route('matriculas'),
                        'icon' => 'bi-clipboard-check-fill',
                        'color' => 'cyan',
                        'label' => 'Matrículas',
                        'desc' => 'Información de matrículas',
                    ],
                    [
                        'href' => route('becas-y-creditos'),
                        'icon' => 'bi-award-fill',
                        'color' => 'indigo',
                        'label' => 'Becas y Créditos',
                        'desc' => 'Beneficios de financiamiento',
                    ],
                    // Programas
                    [
                        'href' => route('programas-de-estudio'),
                        'icon' => 'bi-mortarboard-fill',
                        'color' => 'blue',
                        'label' => 'Programas de Estudio',
                        'desc' => '5 carreras técnicas disponibles',
                    ],
                    // Transparencia
                    [
                        'href' => route('documentos-de-gestion'),
                        'icon' => 'bi-folder2-open',
                        'color' => 'sky',
                        'label' => 'Documentos de Gestión',
                        'desc' => 'Acceso a documentos oficiales',
                    ],
                    [
                        'href' => route('estadisticas'),
                        'icon' => 'bi-bar-chart-fill',
                        'color' => 'cyan',
                        'label' => 'Estadísticas',
                        'desc' => 'Datos e indicadores institucionales',
                    ],
                    [
                        'href' => route('licenciamiento'),
                        'icon' => 'bi-patch-check-fill',
                        'color' => 'indigo',
                        'label' => 'Licenciamiento',
                        'desc' => 'Estado del proceso de licencia',
                    ],
                    // Trámites
                    [
                        'href' => route('mesa-de-partes'),
                        'icon' => 'bi-inbox-fill',
                        'color' => 'blue',
                        'label' => 'Mesa de Partes',
                        'desc' => 'Ingresa documentos y solicitudes',
                    ],
                    [
                        'href' => route('tupa'),
                        'icon' => 'bi-file-earmark-ruled',
                        'color' => 'sky',
                        'label' => 'TUPA',
                        'desc' => 'Texto Único de Procedimientos',
                    ],
                    // Nosotros
                    [
                        'href' => route('quienes-somos'),
                        'icon' => 'bi-people-fill',
                        'color' => 'cyan',
                        'label' => '¿Quiénes somos?',
                        'desc' => 'Misión, visión e identidad',
                    ],
                    // Servicios
                    [
                        'href' => route('bolsa-de-trabajo'),
                        'icon' => 'bi-briefcase-fill',
                        'color' => 'indigo',
                        'label' => 'Bolsa de Trabajo',
                        'desc' => 'Ofertas de empleo vigentes',
                    ],
                ];
                $colorMap = [
                    'blue' => [
                        'qa' => 'bg-blue-50 border-blue-100   hover:border-blue-300',
                        'ic' => 'bg-blue-600   text-white',
                        'lbl' => 'text-blue-900',
                        'sub' => 'text-blue-700',
                        'arr' => 'text-blue-500',
                    ],
                    'sky' => [
                        'qa' => 'bg-sky-50  border-sky-100    hover:border-sky-300',
                        'ic' => 'bg-sky-500    text-white',
                        'lbl' => 'text-sky-900',
                        'sub' => 'text-sky-700',
                        'arr' => 'text-sky-500',
                    ],
                    'cyan' => [
                        'qa' => 'bg-cyan-50 border-cyan-100   hover:border-cyan-300',
                        'ic' => 'bg-cyan-600   text-white',
                        'lbl' => 'text-cyan-900',
                        'sub' => 'text-cyan-700',
                        'arr' => 'text-cyan-500',
                    ],
                    'indigo' => [
                        'qa' => 'bg-indigo-50 border-indigo-100 hover:border-indigo-300',
                        'ic' => 'bg-indigo-600 text-white',
                        'lbl' => 'text-indigo-900',
                        'sub' => 'text-indigo-700',
                        'arr' => 'text-indigo-500',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($quickAccess as $i => $qa)
                    @php $c = $colorMap[$qa['color']]; @endphp
                    <a href="{{ $qa['href'] }}" id="qa-link-{{ $i }}"
                        class="qa-card reveal flex items-center gap-4 p-4 bg-white border rounded-2xl transition-all duration-200 {{ $c['qa'] }} shadow-sm hover:shadow-md group"
                        style="transition-delay:{{ ($i % 4) * 60 }}ms">
                        <span class="qa-icon {{ $c['ic'] }} shadow-sm">
                            <i class="bi {{ $qa['icon'] }}"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="font-bold text-sm {{ $c['lbl'] }} leading-tight">{{ $qa['label'] }}</p>
                            <p class="text-xs {{ $c['sub'] }} mt-0.5 line-clamp-2">{{ $qa['desc'] }}</p>
                        </div>
                        <i
                            class="bi bi-arrow-right-short text-lg {{ $c['arr'] }} ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. ¿POR QUÉ ELEGIRNOS? — 3 propuestas de valor --}}
    <section class="py-20 bg-gradient-to-b from-sky-50 to-white" aria-label="Propuesta de valor institucional">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="section-pill bg-cyan-100 text-cyan-800 mb-3">Nuestra propuesta</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-2">¿Por qué elegir el IESTP FVC?</h2>
                <div class="w-20 h-1.5 bg-cyan-500 mx-auto mt-4 rounded-full"></div>
            </div>

            @php
                $values = [
                    [
                        'icon' => 'bi-award-fill',
                        'color' => 'blue',
                        'title' => 'Carreras a Nombre de la Nación',
                        'desc' =>
                            'Nuestros títulos son emitidos por el Minedu, con validez oficial en todo el territorio peruano y reconocidos por empleadores a nivel nacional.',
                    ],
                    [
                        'icon' => 'bi-lightning-charge-fill',
                        'color' => 'sky',
                        'title' => 'Formación práctica e innovadora',
                        'desc' =>
                            'Talleres equipados, laboratorios actualizados y docentes con experiencia en el sector productivo para garantizar una educación de calidad.',
                    ],
                    [
                        'icon' => 'bi-briefcase-fill',
                        'color' => 'cyan',
                        'title' => 'Inserción laboral garantizada',
                        'desc' =>
                            'Nuestra bolsa de trabajo activa conecta a nuestros egresados con las mejores empresas e instituciones de la región y del país.',
                    ],
                    [
                        'icon' => 'bi-people-fill',
                        'color' => 'indigo',
                        'title' => 'Convenios con empresas líderes',
                        'desc' =>
                            'Contamos con alianzas estratégicas con empresas del sector público y privado que garantizan prácticas pre-profesionales de calidad.',
                    ],
                    [
                        'icon' => 'bi-patch-check-fill',
                        'color' => 'blue',
                        'title' => 'En proceso de licenciamiento',
                        'desc' =>
                            'Cumplimos rigurosamente con las condiciones básicas de calidad exigidas por el Ministerio de Educación del Perú.',
                    ],
                    [
                        'icon' => 'bi-geo-alt-fill',
                        'color' => 'sky',
                        'title' => 'Accesible para toda la región',
                        'desc' =>
                            'Ubicados en Uchiza, servimos a toda la provincia de Tocache y regiones vecinas con horarios flexibles y costos accesibles.',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($values as $i => $v)
                    @php
                        $vc = [
                            'blue' => [
                                'card' => 'bg-blue-50/60   border-blue-100   hover:border-blue-300',
                                'ic' => 'bg-blue-600   text-white   shadow-blue-600/20',
                                'h' => 'text-blue-900',
                            ],
                            'sky' => [
                                'card' => 'bg-sky-50/60    border-sky-100    hover:border-sky-300',
                                'ic' => 'bg-sky-500    text-white   shadow-sky-500/20',
                                'h' => 'text-sky-900',
                            ],
                            'cyan' => [
                                'card' => 'bg-cyan-50/60   border-cyan-100   hover:border-cyan-300',
                                'ic' => 'bg-cyan-600   text-white   shadow-cyan-600/20',
                                'h' => 'text-cyan-900',
                            ],
                            'indigo' => [
                                'card' => 'bg-indigo-50/60 border-indigo-100 hover:border-indigo-300',
                                'ic' => 'bg-indigo-600 text-white   shadow-indigo-600/20',
                                'h' => 'text-indigo-900',
                            ],
                        ][$v['color']];
                    @endphp
                    <div class="reveal card-lift border p-7 rounded-2xl transition-all {{ $vc['card'] }}"
                        style="transition-delay:{{ ($i % 3) * 80 }}ms">
                        <div
                            class="w-14 h-14 rounded-xl flex items-center justify-center mb-5 shadow-lg {{ $vc['ic'] }}">
                            <i class="bi {{ $v['icon'] }} text-2xl"></i>
                        </div>
                        <h3 class="text-base font-bold mb-2 {{ $vc['h'] }}">{{ $v['title'] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $v['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 7. BLOG / NOTICIAS — Últimas publicaciones --}}
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
                    <span class="section-pill bg-sky-500/20 text-sky-300 border border-sky-400/30 mb-3">Blog
                        Institucional</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Noticias y Novedades</h2>
                    <div class="w-20 h-1.5 bg-sky-400 mt-4 rounded-full"></div>
                </div>
                {{-- Aquí puedes agregar un enlace a /blog cuando esté disponible --}}
            </div>

            @if ($blogs->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($blogs as $i => $post)
                        <article
                            class="reveal card-lift bg-white/8 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden flex flex-col hover:bg-white/12 hover:border-sky-400/30 transition-all"
                            style="transition-delay:{{ $i * 80 }}ms">
                            {{-- Imagen del post o placeholder --}}
                            @php $cover = $post->coverImage(); @endphp
                            @if ($cover)
                                <div class="h-44 overflow-hidden">
                                    <img src="{{ $cover }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy">
                                </div>
                            @else
                                <div
                                    class="h-44 bg-gradient-to-br from-blue-800 to-indigo-800 flex items-center justify-center">
                                    <i class="bi bi-newspaper text-5xl text-white/30"></i>
                                </div>
                            @endif

                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-sky-400 bg-sky-400/10 border border-sky-400/20 px-2.5 py-1 rounded-full">
                                        Publicación
                                    </span>
                                    @if ($post->created_at)
                                        <time class="text-xs text-blue-300/70"
                                            datetime="{{ $post->created_at->toISOString() }}">
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
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-sky-500/15 border border-sky-400/20 rounded-2xl flex items-center justify-center">
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

    {{-- 8. ALIADOS / PARTNERS — Carrusel infinito --}}
    <section class="py-16 bg-white border-t border-sky-100 overflow-hidden" aria-label="Empresas e instituciones aliadas">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 text-center reveal">
            <span class="section-pill bg-blue-100 text-blue-700 mb-3">Nuestros Aliados</span>
            <h2 class="text-3xl font-extrabold text-blue-900 mt-2">
                Convenios con empresas e instituciones que confían en nosotros
            </h2>
            <p class="text-gray-500 mt-3 text-sm">Aliados estratégicos en la formación y el empleo de nuestros estudiantes.
            </p>
        </div>

        @if ($partners->isNotEmpty())
            <div class="relative w-full max-w-[1400px] mx-auto overflow-hidden flex items-center">
                <div
                    class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none">
                </div>
                <div
                    class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none">
                </div>
                <div class="carousel-track gap-6 px-4">
                    @for ($i = 0; $i < 2; $i++)
                        @foreach ($partners as $partner)
                            <div
                                class="w-44 h-28 shrink-0 bg-white border border-sky-100 rounded-2xl flex items-center justify-center p-4 shadow-sm hover:shadow-md hover:border-sky-300 transition-all cursor-default">
                                @if ($partner->image_url)
                                    <img src="{{ Storage::url($partner->image_url) }}" alt="{{ $partner->company }}"
                                        class="w-full h-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300"
                                        loading="lazy">
                                @else
                                    <span
                                        class="text-gray-400 font-bold text-center text-sm px-2">{{ $partner->company }}</span>
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

    {{-- 9. CTA FINAL — Llamada a la acción --}}
    <section class="relative py-24 bg-gradient-to-r from-cyan-500 via-sky-500 to-blue-600 overflow-hidden"
        aria-label="Llamada a la acción">
        <div class="absolute inset-0 opacity-10"
            style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:24px 24px;"
            aria-hidden="true"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 bg-blue-900/20 rounded-full blur-3xl" aria-hidden="true"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            <div
                class="inline-flex items-center gap-2 bg-white/20 border border-white/30 text-white px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
                <i class="bi bi-mortarboard-fill"></i>
                Admisión 2026 Abierta
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
                ¿Listo para comenzar tu carrera técnica<br class="hidden sm:block"> y transformar tu futuro?
            </h2>
            <p class="text-white/85 text-lg mt-5 max-w-2xl mx-auto leading-relaxed">
                Únete al IESTP Francisco Vigo Caballero, la institución tecnológica de referencia en Uchiza. Formación de
                calidad, docentes expertos y conexión directa con el mundo laboral.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
                <a href="{{ route('cepre-fvc') }}" id="cta-final-cepre"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white hover:bg-blue-50 text-blue-700 font-extrabold rounded-xl transition-all shadow-xl text-base">
                    <i class="bi bi-book-fill"></i>
                    Proceso CEPRE FVC
                </a>
                <a href="{{ route('examen-de-admision') }}" id="cta-final-admision"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-transparent border-2 border-white/70 hover:bg-white/15 text-white font-bold rounded-xl transition-all text-base">
                    <i class="bi bi-pencil-square"></i>
                    Examen de Admisión
                </a>
            </div>
        </div>
    </section>

    {{-- 10. CONTADOR DE VISITAS — Visitor Counter Section at End of View --}}
    @php
        $totalVisits = $totalVisits ?? \App\Models\VisitorCounter::getTotalVisits();
        $visitDigits = $visitDigits ?? \App\Models\VisitorCounter::getPaddedDigits($totalVisits, 6);
    @endphp
    <section class="relative py-16 bg-slate-950 text-white overflow-hidden border-t border-sky-900/40"
        aria-label="Contador de visitas al portal institucional" id="visitor-counter-section">
        {{-- Background glowing mesh pattern --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none"
            style="background-image:radial-gradient(circle, rgba(56, 189, 248, 0.2) 1px, transparent 1px); background-size:32px 32px;"
            aria-hidden="true"></div>
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[650px] h-[300px] bg-gradient-to-r from-sky-500/20 via-blue-600/20 to-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            {{-- Pulsing Live Radar Badge --}}
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-400/30 text-sky-300 text-xs sm:text-sm font-bold tracking-wide backdrop-blur-md mb-4 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span>Contador Oficial • Visitas al Portal Web</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white font-display tracking-tight">
                Impacto y Presencia Digital del <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-300">IESTP FVC</span>
            </h2>
            <p class="text-slate-300/80 text-sm sm:text-base mt-2.5 max-w-xl mx-auto leading-relaxed">
                Monitoreo permanente de consultas y accesos a nuestra plataforma educativa institucional.
            </p>

            {{-- Main Glass Counter Board --}}
            <div class="mt-8 bg-gradient-to-b from-slate-900/90 to-slate-950/90 border border-sky-500/30 rounded-3xl p-6 sm:p-8 md:p-10 shadow-2xl shadow-sky-950/80 backdrop-blur-xl relative overflow-hidden group">
                {{-- Decorative corner accents --}}
                <div class="absolute top-0 left-0 w-28 h-28 bg-sky-500/10 rounded-br-full blur-xl pointer-events-none"></div>
                <div class="absolute bottom-0 right-0 w-28 h-28 bg-blue-500/10 rounded-tl-full blur-xl pointer-events-none"></div>

                <p class="text-xs sm:text-sm font-semibold uppercase tracking-widest text-sky-400/90 mb-4 flex items-center justify-center gap-2">
                    <i class="bi bi-eye-fill text-sky-400"></i>
                    <span>Total Acumulado de Visitas</span>
                </p>

                {{-- Digital Digit Plate Counter (Flip / Odometer Style) --}}
                <div class="flex items-center justify-center gap-1.5 sm:gap-2.5 md:gap-3 flex-wrap py-2" id="visitor-digits-container" aria-label="Contador: {{ $totalVisits }} visitas">
                    @foreach ($visitDigits as $index => $digit)
                        <div class="relative flex flex-col items-center justify-center w-10 h-14 sm:w-14 sm:h-20 md:w-16 md:h-24 bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 border-2 border-sky-500/40 rounded-xl sm:rounded-2xl shadow-lg shadow-black/60 overflow-hidden transform hover:-translate-y-1 transition-all duration-300 select-none">
                            {{-- Top Gloss Reflection --}}
                            <div class="absolute top-0 inset-x-0 h-1/2 bg-white/5 border-b border-white/10 pointer-events-none"></div>
                            {{-- Center split line for odometer look --}}
                            <div class="absolute inset-x-0 top-1/2 h-[1px] bg-sky-950/80 shadow-sm pointer-events-none"></div>
                            
                            {{-- Digit Number --}}
                            <span class="visitor-digit relative z-10 font-mono font-black text-2xl sm:text-4xl md:text-5xl text-transparent bg-clip-text bg-gradient-to-b from-white via-sky-100 to-cyan-300 tabular-nums drop-shadow-[0_2px_10px_rgba(56,189,248,0.4)]"
                                data-target-digit="{{ $digit }}">
                                {{ $digit }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Formatted readable total subtitle --}}
                <div class="mt-5 flex items-center justify-center gap-2 text-slate-300 text-xs sm:text-sm font-medium">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Registrando <strong class="text-white font-bold" id="visitor-total-formatted">{{ number_format($totalVisits, 0, '', ',') }}</strong> visitas totales al portal</span>
                </div>

                {{-- Micro-indicator Badges Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mt-8 pt-6 border-t border-slate-800/80 text-left">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.03] border border-white/5 hover:border-sky-500/20 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-sky-500/20 text-sky-300 border border-sky-400/30 flex items-center justify-center text-base shrink-0">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-medium leading-none">Conteo Global</p>
                            <p class="text-xs sm:text-sm font-bold text-white mt-1">Todas las vistas</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.03] border border-white/5 hover:border-sky-500/20 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center justify-center text-base shrink-0">
                            <i class="bi bi-activity"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-medium leading-none">Monitoreo Activo</p>
                            <p class="text-xs sm:text-sm font-bold text-white mt-1">24/7 en tiempo real</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.03] border border-white/5 hover:border-sky-500/20 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-blue-500/20 text-blue-300 border border-blue-400/30 flex items-center justify-center text-base shrink-0">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-medium leading-none">Transparencia</p>
                            <p class="text-xs sm:text-sm font-bold text-white mt-1">Portal Oficial FVC</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        /* JSON-LD WebPage + BreadcrumbList */
        (function() {
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
                        "isPartOf": {
                            "@id": "{{ url('/') }}#website"
                        }
                    }, {
                        "@type": "BreadcrumbList",
                        "itemListElement": [{
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Inicio",
                            "item": "{{ url('/') }}"
                        }]
                    }
                ]
            };
            const s = document.createElement('script');
            s.type = 'application/ld+json';
            s.text = JSON.stringify(ld);
            document.head.appendChild(s);
        })();

        /* ── Reveal on scroll (IntersectionObserver) ─────────────────── */
        const revealEls = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visible');
                        io.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.12
            });
            revealEls.forEach(el => io.observe(el));
        } else {
            revealEls.forEach(el => el.classList.add('visible'));
        }

        /* ── Stat counter animation ──────────────────────────────────── */
        function animateCounter(el) {
            const target = parseInt(el.dataset.target || '0', 10);
            const suffix = el.closest('.reveal')?.querySelector('p')?.dataset?.suffix || '';
            const duration = 1400;
            const start = performance.now();
            const step = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(ease * target) + (progress >= 1 ? '+' : '');
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        }

        const statEls = document.querySelectorAll('.stat-number');
        if ('IntersectionObserver' in window) {
            const sio = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        animateCounter(e.target);
                        sio.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.5
            });
            statEls.forEach(el => sio.observe(el));
        } else {
            statEls.forEach(el => {
                el.textContent = el.dataset.target + '+';
            });
        }

        /* ── Visitor Counter Digit Animation (Odometer / Scramble) ───── */
        (function() {
            const visitorSection = document.getElementById('visitor-counter-section');
            const digitEls = document.querySelectorAll('.visitor-digit');
            if (!visitorSection || !digitEls.length) return;

            let animated = false;
            const animateDigits = () => {
                if (animated) return;
                animated = true;

                digitEls.forEach((el, i) => {
                    const target = el.getAttribute('data-target-digit') || el.textContent.trim();
                    const duration = 1000 + (i * 120);
                    const startTime = performance.now();

                    const tick = (now) => {
                        const elapsed = now - startTime;
                        if (elapsed < duration) {
                            el.textContent = Math.floor(Math.random() * 10);
                            requestAnimationFrame(tick);
                        } else {
                            el.textContent = target;
                        }
                    };
                    requestAnimationFrame(tick);
                });
            };

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        animateDigits();
                        observer.disconnect();
                    }
                }, { threshold: 0.2 });
                observer.observe(visitorSection);
            } else {
                animateDigits();
            }
        })();

        /* ── Hero Carousel Controller (Alpine.js) ───────────────────── */
        function heroCarousel() {
            return {
                currentSlide: 0,
                totalSlides: 4,
                autoplayInterval: null,
                intervalDuration: 6000,
                isPaused: false,

                init() {
                    this.startAutoplay();
                },

                startAutoplay() {
                    this.clearAutoplay();
                    this.autoplayInterval = setInterval(() => {
                        if (!this.isPaused) {
                            this.nextSlide();
                        }
                    }, this.intervalDuration);
                },

                clearAutoplay() {
                    if (this.autoplayInterval) {
                        clearInterval(this.autoplayInterval);
                        this.autoplayInterval = null;
                    }
                },

                pauseTimer() {
                    this.isPaused = true;
                },

                resumeTimer() {
                    this.isPaused = false;
                },

                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },

                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },

                goToSlide(index) {
                    this.currentSlide = index;
                    this.startAutoplay();
                }
            };
        }
    </script>
@endpush
