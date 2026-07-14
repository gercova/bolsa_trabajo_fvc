@extends('layouts.app')

@section('title', 'Programas de Estudio y Carreras — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO Optimization Meta Tags --}}
    <meta name="description"
        content="Conoce las carreras profesionales técnicas gratuitas del IESTP Francisco Vigo Caballero. Prepárate en 3 años con certificaciones modulares anuales y título oficial a nombre de la Nación.">
    <meta name="keywords"
        content="carreras tecnicas, programas de estudio, enfermeria tecnica, manejo forestal, asistencia administrativa, produccion agropecuaria, administracion de redes, institutos tecnologicos, educacion publica gratuita">
    <meta name="robots" content="index, follow">

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
        class="relative bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white overflow-hidden py-15 lg:py-20 border-b border-blue-900/30">
        {{-- Elegant glow patterns --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(56,189,248,0.15),transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(59,130,246,0.12),transparent_40%)]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
            <h1
                class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                Nuestros <span
                    class="text-sky-400 bg-gradient-to-r from-sky-400 to-blue-400 bg-clip-text text-transparent">Programas
                    de Estudio</span>
            </h1>
            <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                Descubre carreras profesionales acreditadas diseñadas para responder con éxito a las demandas del mercado
                laboral actual.
            </p>

            {{-- Metrics Grid in Hero --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">3 años</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Duración Académica</p>
                </div>
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">100%</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Título a Nombre de la Nación</p>
                </div>
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">Modular</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Certificaciones Anuales</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PROGRAM LIST SECTION ===== --}}
    @if ($programs->isNotEmpty())
        @php
            $programMeta = [
                'Producción Agropecuaria' => [
                    'icon' => 'bi-tree-fill',
                    'accent' => 'emerald',
                    'bg_badge' => 'bg-emerald-50 text-emerald-800 border-emerald-100',
                    'tag' => 'Producción & Campo',
                    'color_bar' => 'bg-emerald-500',
                ],
                'Enfermería Técnica' => [
                    'icon' => 'bi-heart-pulse-fill',
                    'accent' => 'rose',
                    'bg_badge' => 'bg-rose-50 text-rose-800 border-rose-100',
                    'tag' => 'Ciencias de la Salud',
                    'color_bar' => 'bg-rose-500',
                ],
                'Administración de Redes y Comunicaciones' => [
                    'icon' => 'bi-router-fill',
                    'accent' => 'sky',
                    'bg_badge' => 'bg-sky-50 text-sky-800 border-sky-100',
                    'tag' => 'Soporte e Infraestructura TI',
                    'color_bar' => 'bg-sky-500',
                ],
                'Asistencia Administrativa' => [
                    'icon' => 'bi-briefcase-fill',
                    'accent' => 'blue',
                    'bg_badge' => 'bg-blue-50 text-blue-800 border-blue-100',
                    'tag' => 'Administración & Finanzas',
                    'color_bar' => 'bg-blue-600',
                ],
                'Manejo Forestal' => [
                    'icon' => 'bi-globe-americas',
                    'accent' => 'teal',
                    'bg_badge' => 'bg-teal-50 text-teal-800 border-teal-100',
                    'tag' => 'Recursos Naturales',
                    'color_bar' => 'bg-teal-500',
                ],
            ];
        @endphp

        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span
                        class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        Especialidades
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Explora Carreras Profesionales
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                        Nuestras mallas curriculares están orientadas al desarrollo de competencias prácticas, preparándote
                        directamente para incorporarte al empleo técnico.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($programs as $program)
                        @php
                            $meta = $programMeta[$program->name] ?? [
                                'icon' => 'bi-mortarboard-fill',
                                'accent' => 'blue',
                                'bg_badge' => 'bg-blue-50 text-blue-800 border-blue-100',
                                'tag' => 'Educación Técnica',
                                'color_bar' => 'bg-blue-500',
                            ];

                            $mainImage =
                                $program->images->first(fn($img) => $img->is_main) ?? $program->images->first();
                            $imagePath = $mainImage ? $mainImage->path : null;
                        @endphp

                        <div
                            class="group bg-white rounded-3xl border border-slate-100 shadow-md hover-card overflow-hidden flex flex-col h-full">
                            {{-- Header image / gradient --}}
                            <div class="h-60 relative overflow-hidden bg-slate-900 flex items-center justify-center">
                                @if ($imagePath)
                                    <img src="{{ Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : asset('storage/' . $imagePath) }}"
                                        alt="{{ $program->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent">
                                    </div>
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 to-slate-950"></div>
                                    <i class="bi {{ $meta['icon'] }} text-7xl text-white/40 drop-shadow z-10"></i>
                                @endif

                                {{-- Floated Category tag --}}
                                <div class="absolute top-4 left-4 z-20">
                                    <span
                                        class="px-3.5 py-1.5 text-xs font-black rounded-lg uppercase tracking-wider shadow border {{ $meta['bg_badge'] }}">
                                        {{ $meta['tag'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-8 flex flex-col flex-grow space-y-6">
                                <div>
                                    <h3
                                        class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-tight mb-2">
                                        {{ $program->name }}
                                    </h3>
                                    <div class="w-16 h-1 {{ $meta['color_bar'] }} rounded-full"></div>
                                </div>

                                @if ($program->description)
                                    <p class="text-base text-slate-600 leading-relaxed line-clamp-4 flex-grow font-medium">
                                        {!! Str::limit(strip_tags($program->description), 120, '...') !!}
                                    </p>
                                @else
                                    <p class="text-slate-400 text-base italic flex-grow">Descripción detallada no disponible
                                        en este momento.</p>
                                @endif

                                {{-- Details --}}
                                @if ($program->details)
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                                        @foreach (explode("\n", $program->details) as $detailLine)
                                            @if (trim($detailLine))
                                                <div class="flex items-start gap-2.5 text-sm font-bold text-slate-700">
                                                    <i
                                                        class="bi bi-info-circle text-blue-600 shrink-0 mt-0.5 text-base"></i>
                                                    <span>{{ $detailLine }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Modules list --}}
                                @if ($program->modules->isNotEmpty())
                                    <div class="space-y-3">
                                        <h4
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                            <i class="bi bi-patch-check-fill text-blue-600 text-sm"></i>
                                            Módulos de Certificación
                                        </h4>
                                        <div class="space-y-2">
                                            @foreach ($program->modules->take(3) as $idx => $module)
                                                <div
                                                    class="flex items-start gap-3 bg-slate-50/50 p-3 rounded-lg border border-slate-100 hover:bg-white hover:shadow-sm transition-all">
                                                    <span
                                                        class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-600 text-white font-extrabold text-[11px] flex items-center justify-center mt-0.5">
                                                        {{ $idx + 1 }}
                                                    </span>
                                                    <span class="text-sm font-bold text-slate-700 leading-snug">
                                                        {{ $module->module }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Action Button --}}
                                <div class="pt-4">
                                    <a href="/programas-de-estudios/{{ $program->slug }}"
                                        class="w-full inline-flex items-center justify-center px-6 py-3.5 text-base font-black text-white bg-slate-950 hover:bg-blue-600 rounded-xl transition duration-200 shadow-md hover:shadow-lg">
                                        Plan de Estudios Completo
                                        <i class="bi bi-arrow-right ml-2 text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== WHY STUDY HERE SECTION ===== --}}
    <section class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Ventajas
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                    ¿Por qué elegir el IESTP Francisco Vigo Caballero?
                </h2>
                <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                    Ofrecemos una educación técnica integral, práctica y con respaldo oficial para impulsar tu inserción
                    laboral.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Point 1 --}}
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-mortarboard text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Título Oficial</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Obtén tu Título Profesional Técnico a Nombre de la Nación con valor oficial para todo el
                            territorio nacional.
                        </p>
                    </div>
                </div>

                {{-- Point 2 --}}
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-award text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Certificación Modular</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Convalida módulos y recibe certificaciones anuales intermedias que te permiten acceder a empleos
                            mientras estudias.
                        </p>
                    </div>
                </div>

                {{-- Point 3 --}}
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-briefcase text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Bolsa de Empleo</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Acceso directo a ofertas laborales y convenios con empresas líderes en la región para tus
                            prácticas y empleo formal.
                        </p>
                    </div>
                </div>

                {{-- Point 4 --}}
                <div
                    class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-cash-coin text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Sin Mensualidades</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Como institución de educación pública nacional, gozarás de educación superior técnica de calidad
                            y gratuita.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ADMISSION ROADMAP SECTION ===== --}}
    <section class="py-24 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Paso a Paso
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                    ¿Cómo formar parte de nuestra institución?
                </h2>
                <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                    Sigue esta secuencia de pasos para completar exitosamente tu postulación e ingresar al período lectivo.
                </p>
            </div>

            <div class="relative max-w-5xl mx-auto">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-blue-200 -translate-y-1/2 z-0 hidden lg:block"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
                    {{-- Step 1 --}}
                    <div class="bg-white p-6.5 rounded-2xl border border-slate-150 text-center hover:shadow-md transition">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-600 text-white font-black flex items-center justify-center mx-auto mb-4 text-lg">
                            1
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Requisitos</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Reúne tu DNI, certificado de secundaria completa y partida de nacimiento.
                        </p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="bg-white p-6.5 rounded-2xl border border-slate-150 text-center hover:shadow-md transition">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-600 text-white font-black flex items-center justify-center mx-auto mb-4 text-lg">
                            2
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Inscripción</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Inscríbete de manera digital en la web o acudiendo a las oficinas del instituto.
                        </p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="bg-white p-6.5 rounded-2xl border border-slate-150 text-center hover:shadow-md transition">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-600 text-white font-black flex items-center justify-center mx-auto mb-4 text-lg">
                            3
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Examen</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Rinde el examen de admisión (conocimientos y aptitud) en las fechas oficiales.
                        </p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="bg-white p-6.5 rounded-2xl border border-slate-150 text-center hover:shadow-md transition">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-600 text-white font-black flex items-center justify-center mx-auto mb-4 text-lg">
                            4
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Matrícula</h3>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Tras alcanzar una vacante, formaliza tu matrícula y estarás listo para estudiar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-14">
                <a href="{{ route('examen-de-admision') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-xl transition shadow hover:shadow-md">
                    <i class="bi bi-info-circle text-lg"></i>
                    Información sobre Admisiones
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FAQ SECTION ===== --}}
    <section class="py-24 bg-white" x-data="{ activeFaq: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Ayuda
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                    Preguntas Frecuentes
                </h2>
                <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                    Resolvemos de forma inmediata las dudas comunes sobre los estudios en el IESTP.
                </p>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 1 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 1 ? null : 1">
                        <span>¿Cuánto tiempo duran las carreras y en qué horarios se dictan?</span>
                        <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                            :class="activeFaq === 1 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq1"
                        :style="activeFaq === 1 ? 'max-height: ' + $refs.faq1.scrollHeight + 'px' : ''">
                        <div
                            class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                            Todos nuestros programas tienen una duración de 3 años, estructurados en 6 períodos académicos
                            semestrales. Las clases se dictan de lunes a viernes en horario diurno (7:30 am – 1:30 pm).
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 2 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 2 ? null : 2">
                        <span>¿En qué consiste la certificación modular?</span>
                        <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                            :class="activeFaq === 2 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq2"
                        :style="activeFaq === 2 ? 'max-height: ' + $refs.faq2.scrollHeight + 'px' : ''">
                        <div
                            class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                            Al culminar y aprobar los cursos correspondientes a cada año de estudios, el instituto te
                            entrega una certificación modular oficial a nombre de la Nación. Esto te califica para
                            incorporarte al mercado laboral en puestos asociados antes de culminar la carrera completa.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 3 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 3 ? null : 3">
                        <span>¿La educación realmente no tiene costo mensual?</span>
                        <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                            :class="activeFaq === 3 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq3"
                        :style="activeFaq === 3 ? 'max-height: ' + $refs.faq3.scrollHeight + 'px' : ''">
                        <div
                            class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                            Sí. Al ser un Instituto de Educación Superior Tecnológico Público, la educación es gratuita y no
                            existen cobros de pensiones o mensualidades. Se realiza únicamente el pago por derecho de
                            matrícula y carnet estudiantil al inicio del periodo anual.
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
                ¿Listo para iniciar tu desarrollo profesional?
            </h2>
            <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                Asegura tu vacante y prepárate con profesores expertos y talleres equipados. ¡Las inscripciones de admisión
                están abiertas!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                <a href="{{ route('examen-de-admision') }}"
                    class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-4.5 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5">
                    <i class="bi bi-check-circle-fill text-blue-600 text-lg"></i>
                    Inscribirse Ahora
                </a>
                <a href="{{ route('cepre-fvc') }}"
                    class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-4.5 rounded-xl font-extrabold transition flex items-center justify-center gap-2">
                    CEPRE FVC
                    <i class="bi bi-arrow-right text-lg"></i>
                </a>
            </div>
        </div>
    </section>
@endsection
