@extends('layouts.app')
@section('title', 'Programas de Estudio — IESTP Francisco Vigo Caballero')
@push('styles')
    <style>
        /* Sutiles animaciones y mejoras de visualización */
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    {{-- ===== HERO SECTION ===== --}}
    <section class="hero-gradient text-white py-24 relative overflow-hidden">
        {{-- Efectos decorativos de fondo --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]">
        </div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <span
                class="inline-block bg-purple-500/20 text-purple-300 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-4 border border-purple-500/30">
                Formación Técnica Profesional
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                Nuestros Programas de Estudio
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto mb-8 font-normal leading-relaxed">
                Descubre carreras técnicas diseñadas para responder a las demandas del mercado moderno. Prepárate con
                educación práctica, profesores expertos y certificaciones oficiales.
            </p>

            {{-- Métricas destacadas en el Hero --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-12 pt-8 border-t border-white/10">
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-purple-400">3 años</p>
                    <p class="text-xs md:text-sm text-gray-400 mt-1">Duración por carrera</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-purple-400">100%</p>
                    <p class="text-xs md:text-sm text-gray-400 mt-1">Título a nombre de la Nación</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-purple-400">Modular</p>
                    <p class="text-xs md:text-sm text-gray-400 mt-1">Certificaciones Anuales</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-purple-400">Gratuito</p>
                    <p class="text-xs md:text-sm text-gray-400 mt-1">Instituto Público</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PROGRAMAS DE ESTUDIO ===== --}}
    @if ($programs->isNotEmpty())
        @php
            // Mapeo dinámico de estilos, íconos y colores por programa para una presentación premium y variada
            $programMeta = [
                'Producción Agropecuaria' => [
                    'icon' => 'bi-tree-fill',
                    'color' => 'emerald',
                    'gradient' => 'from-emerald-600 to-teal-700',
                    'bg_light' => 'bg-emerald-50',
                    'border' => 'border-emerald-100',
                    'text' => 'text-emerald-700',
                    'tag' => 'Sector Primario & Agro',
                ],
                'Enfermería Técnica' => [
                    'icon' => 'bi-heart-pulse-fill',
                    'color' => 'rose',
                    'gradient' => 'from-rose-600 to-red-700',
                    'bg_light' => 'bg-rose-50',
                    'border' => 'border-rose-100',
                    'text' => 'text-rose-700',
                    'tag' => 'Ciencias de la Salud',
                ],
                'Administración de Redes y Comunicaciones' => [
                    'icon' => 'bi-router-fill',
                    'color' => 'blue',
                    'gradient' => 'from-blue-600 to-indigo-700',
                    'bg_light' => 'bg-blue-50',
                    'border' => 'border-blue-100',
                    'text' => 'text-blue-700',
                    'tag' => 'Tecnología & Redes',
                ],
                'Asistencia Administrativa' => [
                    'icon' => 'bi-briefcase-fill',
                    'color' => 'purple',
                    'gradient' => 'from-purple-600 to-indigo-700',
                    'bg_light' => 'bg-purple-50',
                    'border' => 'border-purple-100',
                    'text' => 'text-purple-700',
                    'tag' => 'Gestión Corporativa',
                ],
                'Manejo Forestal' => [
                    'icon' => 'bi-globe-americas',
                    'color' => 'teal',
                    'gradient' => 'from-teal-600 to-emerald-700',
                    'bg_light' => 'bg-teal-50',
                    'border' => 'border-teal-100',
                    'text' => 'text-teal-700',
                    'tag' => 'Conservación & Bosques',
                ],
            ];
        @endphp

        <section class="py-20 bg-gray-50 border-b border-gray-100">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <span
                        class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-3">
                        Oferta Vigente
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Explora Nuestras Especialidades
                    </h2>
                    <p class="text-gray-600 mt-4 max-w-2xl mx-auto text-base">
                        Diseñadas bajo el enfoque de competencias laborales para garantizar que obtengas los conocimientos
                        prácticos requeridos en la industria.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($programs as $program)
                        @php
                            $meta = $programMeta[$program->name] ?? [
                                'icon' => 'bi-mortarboard-fill',
                                'color' => 'purple',
                                'gradient' => 'from-purple-600 to-indigo-700',
                                'bg_light' => 'bg-purple-50',
                                'border' => 'border-purple-100',
                                'text' => 'text-purple-700',
                                'tag' => 'Educación Técnica',
                            ];
                        @endphp
                        <div
                            class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover-card overflow-hidden flex flex-col h-full">
                            {{-- Header con gradiente e ícono/logo --}}
                            <div class="h-40 bg-gradient-to-br {{ $meta['gradient'] }} flex items-center justify-center p-6 relative overflow-hidden">
                                {{-- Círculos decorativos traslúcidos --}}
                                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white/10 rounded-full blur-sm"></div>
                                <div class="absolute -top-6 -left-6 w-20 h-20 bg-white/10 rounded-full blur-sm"></div>

                                @if ($program->logo_path)
                                    <img src="{{ Storage::url($program->logo_path) }}" alt="{{ $program->name }}"
                                        class="h-24 w-24 object-contain drop-shadow-xl z-10 transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="z-10 text-center">
                                        <i class="bi {{ $meta['icon'] }} text-6xl text-white/90 drop-shadow-md"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Cuerpo de la tarjeta --}}
                            <div class="p-6 md:p-8 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-md {{ $meta['bg_light'] }} {{ $meta['text'] }}">
                                        {{ $meta['tag'] }}
                                    </span>
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <i class="bi bi-clock"></i> 3 Años
                                    </span>
                                </div>

                                <h3
                                    class="font-extrabold text-gray-900 text-xl leading-tight mb-3 group-hover:text-purple-700 transition-colors">
                                    {{ $program->name }}
                                </h3>

                                @if ($program->description)
                                    <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-4 flex-grow">
                                        {!! Str::limit($program->description, 225, '...') !!}
                                    </p>
                                @else
                                    <p class="text-gray-400 text-sm italic mb-6 flex-grow">Sin descripción disponible.</p>
                                @endif

                                {{-- Detalles formateados (Duración / Título) --}}
                                @if ($program->details)
                                    <div class="space-y-2 mb-6 pt-4 border-t border-gray-100">
                                        @foreach (explode("\n", $program->details) as $detailLine)
                                            @if (trim($detailLine))
                                                <div class="flex items-start gap-2 text-xs text-gray-600">
                                                    <i class="bi bi-info-circle-fill text-purple-500 shrink-0 mt-0.5"></i>
                                                    <span>{{ $detailLine }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Certificaciones Modulares --}}
                                @if ($program->modules->isNotEmpty())
                                    <div class="mt-auto bg-gray-50 p-4 rounded-2xl border border-gray-100/80 mb-6">
                                        <h4
                                            class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                            <i class="bi bi-patch-check text-purple-600 text-sm"></i>
                                            Certificaciones Modulares
                                        </h4>
                                        <ul class="space-y-1.5">
                                            @foreach ($program->modules as $module)
                                                <li class="text-xs text-gray-600 flex items-start gap-2">
                                                    <i
                                                        class="bi bi-chevron-right text-purple-500 shrink-0 mt-1 text-[10px]"></i>
                                                    <span class="leading-tight">{{ $module->module }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Botón de Acción --}}
                                <a href="/programas-de-estudios/{{ $program->slug }}"
                                    class="w-full bg-slate-900 text-white py-3 rounded-xl hover:bg-slate-800 transition-colors flex items-center justify-center gap-2 font-bold text-sm tracking-wide shadow-sm group-hover:shadow-md">
                                    Ver plan de estudios completo
                                    <i
                                        class="bi bi-arrow-right text-sm transition-transform duration-300 group-hover:translate-x-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== POR QUÉ ESTUDIAR CON NOSOTROS ===== --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span
                    class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-3">
                    Nuestras Ventajas
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    ¿Por qué elegir el IESTP FVC?
                </h2>
                <p class="text-gray-600 mt-4 max-w-xl mx-auto text-base">
                    Nuestra metodología está orientada a la inserción laboral rápida mediante formación práctica de alto
                    nivel.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6">
                        <i class="bi bi-file-earmark-badge text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-3">Título Oficial</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Recibe tu Título de Técnico Profesional a Nombre de la Nación al culminar satisfactoriamente tus 3
                        años de formación académica.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6">
                        <i class="bi bi-award text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-3">Certificación Modular</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Obtén un certificado oficial cada año al concluir un módulo técnico, permitiéndote buscar trabajo
                        especializado antes de graduarte.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6">
                        <i class="bi bi-briefcase text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-3">Bolsa de Trabajo</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Accede a convocatorias y ofertas laborales exclusivas mediante convenios directos con empresas y
                        fundaciones de la región.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6">
                        <i class="bi bi-people text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-3">Educación Gratuita</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Al ser una institución pública de educación superior técnica, tienes derecho a una enseñanza
                        gratuita de calidad sin mensualidades.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PROCESO DE ADMISIÓN ===== --}}
    <section class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span
                    class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-3">
                    Paso a Paso
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    ¿Cómo formar parte del IESTP FVC?
                </h2>
                <p class="text-gray-600 mt-4 max-w-xl mx-auto text-base">
                    Sigue nuestro sencillo proceso de admisión para iniciar tu camino hacia el éxito profesional.
                </p>
            </div>

            <div class="relative max-w-5xl mx-auto">
                {{-- Línea conectora horizontal (solo en pantallas grandes) --}}
                <div class="absolute top-1/2 left-0 w-full h-1 bg-purple-100 -translate-y-1/2 z-0 hidden lg:block"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
                    {{-- Paso 1 --}}
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-600 text-white font-extrabold flex items-center justify-center mx-auto mb-4 text-lg">
                            1
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-2">Requisitos</h3>
                        <p class="text-gray-500 text-xs leading-relaxed">
                            Prepara tu certificado de secundaria, copia de DNI y partida de nacimiento.
                        </p>
                    </div>

                    {{-- Paso 2 --}}
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-600 text-white font-extrabold flex items-center justify-center mx-auto mb-4 text-lg">
                            2
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-2">Inscripción</h3>
                        <p class="text-gray-500 text-xs leading-relaxed">
                            Regístrate en línea o acude a nuestras oficinas para inscribirte en el examen de admisión.
                        </p>
                    </div>

                    {{-- Paso 3 --}}
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-600 text-white font-extrabold flex items-center justify-center mx-auto mb-4 text-lg">
                            3
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-2">Examen</h3>
                        <p class="text-gray-500 text-xs leading-relaxed">
                            Rinde la evaluación de conocimientos y aptitud en la fecha establecida por la institución.
                        </p>
                    </div>

                    {{-- Paso 4 --}}
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 text-center hover:shadow-md transition-shadow">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-600 text-white font-extrabold flex items-center justify-center mx-auto mb-4 text-lg">
                            4
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-2">Matrícula</h3>
                        <p class="text-gray-500 text-xs leading-relaxed">
                            Si alcanzaste una vacante, formaliza tu matrícula y estarás listo para iniciar clases.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('examen-de-admision') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow-sm">
                    <i class="bi bi-info-circle"></i>
                    Más detalles sobre la Admisión
                </a>
            </div>
        </div>
    </section>

    {{-- ===== PREGUNTAS FRECUENTES ===== --}}
    <section class="py-20 bg-white" x-data="{ activeFaq: null }">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="text-center mb-16">
                <span
                    class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-3">
                    Dudas comunes
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Preguntas Frecuentes
                </h2>
                <p class="text-gray-600 mt-4 text-base">
                    Encuentra respuestas rápidas a las consultas más habituales sobre los programas de estudio.
                </p>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 1 ? 'border-purple-300 shadow-sm bg-purple-50/10' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-gray-900 flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 1 ? null : 1">
                        <span>¿Cuánto tiempo duran las carreras y cuáles son los horarios?</span>
                        <i class="bi transition-transform duration-300 text-purple-600"
                            :class="activeFaq === 1 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq1"
                        :style="activeFaq === 1 ? 'max-height: ' + $refs.faq1.scrollHeight + 'px' : ''">
                        <div class="p-6 pt-0 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                            Todos nuestros programas tienen una duración de 3 años estructurados en 6 períodos académicos
                            (ciclos semestrales). Los horarios de clase suelen ser diurnos, de lunes a viernes de 7:30 am a
                            1:30 pm.
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 2 ? 'border-purple-300 shadow-sm bg-purple-50/10' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-gray-900 flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 2 ? null : 2">
                        <span>¿Qué ventajas ofrece la certificación modular?</span>
                        <i class="bi transition-transform duration-300 text-purple-600"
                            :class="activeFaq === 2 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq2"
                        :style="activeFaq === 2 ? 'max-height: ' + $refs.faq2.scrollHeight + 'px' : ''">
                        <div class="p-6 pt-0 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                            La certificación modular te otorga un diploma técnico a nombre del Ministerio de Educación al
                            culminar y aprobar satisfactoriamente cada año académico. Esto valida que cuentas con
                            competencias específicas para trabajar en puestos intermedios de tu carrera sin necesidad de
                            haber concluido los 3 años completos.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 3 ? 'border-purple-300 shadow-sm bg-purple-50/10' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-gray-900 flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 3 ? null : 3">
                        <span>¿Hay algún costo mensual o pago por pensiones?</span>
                        <i class="bi transition-transform duration-300 text-purple-600"
                            :class="activeFaq === 3 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq3"
                        :style="activeFaq === 3 ? 'max-height: ' + $refs.faq3.scrollHeight + 'px' : ''">
                        <div class="p-6 pt-0 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                            No. Al ser un Instituto de Educación Superior Tecnológico Público, la educación es 100% gratuita
                            y no existe cobro de pensiones mensuales. Únicamente se realiza un pago anual de costo social
                            correspondiente al derecho de matrícula y/o carnet de estudiante.
                        </div>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300"
                    :class="activeFaq === 4 ? 'border-purple-300 shadow-sm bg-purple-50/10' : ''">
                    <button
                        class="w-full text-left p-6 font-bold text-gray-900 flex items-center justify-between gap-4 focus:outline-none"
                        @click="activeFaq = activeFaq === 4 ? null : 4">
                        <span>¿Qué requisitos son obligatorios para la matrícula?</span>
                        <i class="bi transition-transform duration-300 text-purple-600"
                            :class="activeFaq === 4 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                    </button>
                    <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq4"
                        :style="activeFaq === 4 ? 'max-height: ' + $refs.faq4.scrollHeight + 'px' : ''">
                        <div class="p-6 pt-0 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                            Para matricularte es indispensable contar con el Certificado de Estudios de Educación Secundaria
                            Completa visado, copia simple del Documento Nacional de Identidad (DNI) vigente, partida de
                            nacimiento original, fotos tamaño carnet a color y la constancia de haber ingresado en el
                            proceso de admisión.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CALL TO ACTION ===== --}}
    <section class="py-20 hero-gradient text-white text-center relative overflow-hidden">
        <div
            class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <h2 class="text-3xl md:text-5xl font-black mb-6 tracking-tight">
                ¿Listo para construir tu futuro profesional?
            </h2>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto mb-10 leading-relaxed font-normal">
                Las inscripciones para el próximo examen de admisión ya están abiertas. Elige tu carrera técnica y asegura
                tu vacante hoy mismo.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('examen-de-admision') }}"
                    class="bg-white text-slate-900 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition-colors shadow-md flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    Postular ahora
                </a>
                <a href="{{ route('cepre-fvc') }}"
                    class="bg-purple-600/30 text-white border border-purple-500/40 px-8 py-4 rounded-xl font-bold hover:bg-purple-600/50 transition-colors flex items-center justify-center gap-2">
                    CEPRE FVC
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
@endsection
