@extends('layouts.app')

@section('title', 'Becas y Créditos Educativos — IESTP Francisco Vigo Caballero')
@section('meta_description', 'Descubre el programa integral de becas, exoneraciones de pago por ley y opciones de
    crédito educativo en el IESTP Francisco Vigo Caballero en Uchiza. Información sobre Primeros Puestos, Beca 18 PRONABEC,
    Deportistas, Discapacidad y Convenios.')
@section('meta_keywords', 'becas, creditos educativos, beca 18, pronabec, iestp francisco vigo caballero, uchiza,
    exoneracion pago, primeros puestos, san martin, educacion superior tecnica')
@section('canonical_url', route('becas-y-creditos'))

@push('scripts')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ItemPage",
            "name": "Becas y Créditos Educativos — IESTP Francisco Vigo Caballero",
            "description": "Información oficial sobre becas institucionales, exoneraciones de ley y programas de financiamiento como Beca 18 PRONABEC.",
            "url": "{{ route('becas-y-creditos') }}",
            "provider": {
                "@type": "EducationalOrganization",
                "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}",
                "url": "{{ url('/') }}",
                "address": {
                "@type": "PostalAddress",
                "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
                "addressRegion": "San Martín",
                "addressCountry": "PE"
                }
        }
        }
    </script>
@endpush

@section('content')
    {{-- ═══ HERO SECTION ════════════════════════════════════════════════════ --}}
    <section
        class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 text-white overflow-hidden py-16 lg:py-24">
        {{-- Ambient Light Accents --}}
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-700/30 via-transparent to-transparent pointer-events-none">
        </div>
        <div class="absolute -bottom-24 -left-20 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                {{-- Col Left: Title & Actions --}}
                <div class="lg:col-span-7 space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs sm:text-sm font-semibold tracking-wide backdrop-blur-sm">
                        <i class="bi bi-patch-check-fill text-blue-300" aria-hidden="true"></i>
                        <span>Inclusión y Apoyo Estudiantil</span>
                    </div>

                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white font-sans">
                        Becas, Beneficios y <br>
                        <span class="text-blue-200">Créditos Educativos</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                        Garantizamos una educación técnica superior inclusiva. Accede a exoneraciones de pago por mérito
                        académico, reservas por ley y acompañamiento en convenios nacionales como <strong
                            class="text-white font-semibold">Beca 18 - PRONABEC</strong>.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#modalidades-becas"
                            class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-blue-900 bg-white hover:bg-blue-50 rounded-xl transition shadow-lg hover:shadow-xl group">
                            <i class="bi bi-award-fill mr-2 text-lg text-blue-700 group-hover:scale-110 transition-transform"
                                aria-hidden="true"></i>
                            Becas Institucionales
                        </a>
                        {{-- <a href="#becas-externas"
                            class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-white border-2 border-blue-400/40 hover:bg-white/10 rounded-xl transition backdrop-blur-sm">
                            <i class="bi bi-mortarboard-fill mr-2 text-lg text-blue-200" aria-hidden="true"></i>
                            Beca 18 PRONABEC
                        </a> --}}
                    </div>
                </div>

                {{-- Col Right: Visual Graphic Card --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-3xl blur-2xl opacity-30">
                        </div>

                        <div
                            class="relative bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl shadow-2xl text-white space-y-6">
                            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                                        <i class="bi bi-mortarboard" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wider text-blue-200 font-bold">Oportunidades
                                            FVC</p>
                                        <h2 class="text-xl font-bold text-white">Apoyo Educativo</h2>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 rounded-full text-xs font-bold">
                                    Convocatoria 2026
                                </span>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-xl border border-white/10">
                                    <i class="bi bi-check-circle-fill text-emerald-400 text-lg mt-0.5"
                                        aria-hidden="true"></i>
                                    <div>
                                        <p class="text-sm font-bold text-white">Exoneración por Mérito</p>
                                        <p class="text-xs text-blue-100">Primeros puestos de secundaria y CEPRE-FVC.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-xl border border-white/10">
                                    <i class="bi bi-shield-check text-blue-300 text-lg mt-0.5" aria-hidden="true"></i>
                                    <div>
                                        <p class="text-sm font-bold text-white">Becas e Integración por Ley</p>
                                        <p class="text-xs text-blue-100">Discapacidad (CONADIS), VÍCTIMAS y FF.AA.</p>
                                    </div>
                                </div>
                                {{-- <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-xl border border-white/10">
                                    <i class="bi bi-star-fill text-amber-300 text-lg mt-0.5" aria-hidden="true"></i>
                                    <div>
                                        <p class="text-sm font-bold text-white">Postulación Beca 18</p>
                                        <p class="text-xs text-blue-100">Asesoría gratuita para postular a PRONABEC.</p>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="pt-2 flex items-center justify-between text-xs text-blue-200">
                                <span class="flex items-center gap-1"><i class="bi bi-geo-alt text-blue-300"></i> Uchiza,
                                    San Martín</span>
                                <span class="flex items-center gap-1"><i class="bi bi-clock-history text-blue-300"></i>
                                    Atención presencial</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- ═══ RESUMEN DE IMPACTO / STAT CARDS ════════════════════════════════ --}}
    <section class="py-12 bg-white relative -mt-6 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Card 1: Total Vacantes --}}
                <div
                    class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                    <div
                        class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-blue-600/20">
                        <i class="bi bi-people-fill text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-2xl font-black text-blue-900 mb-1">{{ $scholarships->sum('vacancies') }} Plazas</h3>
                    <p class="text-sm font-bold text-blue-700">Vacantes Disponibles</p>
                    <p class="text-xs text-gray-600 mt-1">Plazas totales reservadas en modalidades preferenciales de becas.</p>
                </div>

                {{-- Card 2: Primeros Puestos --}}
                <div
                    class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                    <div
                        class="w-12 h-12 bg-emerald-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-emerald-600/20">
                        <i class="bi bi-trophy-fill text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-2xl font-black text-emerald-900 mb-1">100% y 50%</h3>
                    <p class="text-sm font-bold text-emerald-700">Primeros Puestos</p>
                    <p class="text-xs text-gray-600 mt-1">100% de descuento para el 1° puesto y 50% para el 2° puesto escolar.</p>
                </div>

                {{-- Card 3: Fuerzas Armadas --}}
                <div
                    class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                    <div
                        class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-indigo-600/20">
                        <i class="bi bi-shield-fill-check text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-2xl font-black text-indigo-900 mb-1">50% Descuento</h3>
                    <p class="text-sm font-bold text-indigo-700">Servicio Militar (FF.AA.)</p>
                    <p class="text-xs text-gray-600 mt-1">Beneficio arancelario para personal del SMV y licenciados.</p>
                </div>

                {{-- Card 4: Inclusión y Leyes --}}
                <div
                    class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                    <div
                        class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-blue-600/20">
                        <i class="bi bi-patch-check-fill text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-2xl font-black text-blue-900 mb-1">100% y 5% Ley</h3>
                    <p class="text-sm font-bold text-blue-700">Inclusión y Reparaciones</p>
                    <p class="text-xs text-gray-600 mt-1">Exoneración total PIR y reserva legal del 5% CONADIS.</p>
                </div>

            </div>
        </div>
    </section>


    {{-- ═══ MODALIDADES DE BECAS INSTITUCIONALES (FROM DATABASE) ════════════ --}}
    <section id="modalidades-becas" class="py-16 bg-slate-50 border-y border-blue-100/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeModal: null }">

            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-100/80 px-3.5 py-1.5 rounded-full inline-flex items-center gap-1.5">
                    <i class="bi bi-award-fill text-blue-600"></i>
                    Modalidades Institucionales • {{ $scholarships->sum('vacancies') }} Plazas Disponibles
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-4 font-sans">
                    Becas, Descuentos y Exoneraciones
                </h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Conoce las modalidades de ingreso preferencial, vacantes disponibles y el porcentaje de descuento arancelario establecido por norma institucional y legislación nacional en favor de nuestros postulantes.
                </p>
            </div>

            @if ($scholarships->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($scholarships as $scholarship)
                        <div
                            class="bg-white rounded-3xl border border-blue-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                            <div class="p-8">
                                {{-- Card Header: Icon & Badges --}}
                                <div class="flex items-center justify-between mb-6">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-700/20 group-hover:scale-110 transition-transform">
                                        <i class="bi {{ $scholarship->icon ?? 'bi-award' }} text-2xl"
                                            aria-hidden="true"></i>
                                    </div>
                                    <div class="flex flex-col items-end gap-1.5">
                                        <span
                                            class="bg-blue-50 text-blue-700 text-xs font-extrabold px-3 py-0.5 rounded-full border border-blue-200">
                                            Modalidad #{{ $loop->iteration }}
                                        </span>
                                        <span
                                            class="bg-emerald-50 text-emerald-800 text-xs font-black px-3 py-0.5 rounded-full border border-emerald-200 flex items-center gap-1 shadow-xs">
                                            <i class="bi bi-people-fill text-emerald-600"></i>
                                            {{ $scholarship->vacancies ?? 0 }} Plazas
                                        </span>
                                    </div>
                                </div>

                                {{-- Title --}}
                                <h3
                                    class="text-xl font-bold text-blue-900 mb-3 group-hover:text-blue-600 transition-colors">
                                    {{ $scholarship->name }}
                                </h3>

                                {{-- Discount Highlight Box --}}
                                @if ($scholarship->discount_details || $scholarship->discount_percentage > 0)
                                    <div class="mb-4 p-3.5 bg-gradient-to-r from-emerald-50/80 to-teal-50/80 rounded-2xl border border-emerald-200/80 text-emerald-950">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[11px] font-black px-2.5 py-0.5 rounded-md shadow-xs">
                                                <i class="bi bi-tag-fill text-[10px]"></i>
                                                @if ($scholarship->discount_percentage > 0)
                                                    {{ number_format($scholarship->discount_percentage, 0) }}% Beneficio
                                                @else
                                                    Descuento
                                                @endif
                                            </span>
                                            <span class="text-xs font-extrabold text-emerald-900 uppercase tracking-wide">
                                                Beneficio Arancelario
                                            </span>
                                        </div>
                                        <p class="text-xs font-semibold text-emerald-900 leading-snug">
                                            {{ $scholarship->discount_details ?? 'Exoneración arancelaria preferencial de acuerdo al reglamento institucional.' }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Description --}}
                                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                                    {{ $scholarship->description ?? 'Modalidad especial de beca y exoneración de acuerdo a los requerimientos de evaluación e ingreso institucional del IESTP Francisco Vigo Caballero.' }}
                                </p>

                                {{-- Requisitos destacados según la modalidad --}}
                                <div class="border-t border-gray-100 pt-4 space-y-2 text-xs text-gray-700">
                                    <p
                                        class="font-bold text-blue-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                        <i class="bi bi-file-earmark-check-fill text-blue-500"></i> Requisitos principales:
                                    </p>

                                    @if ($scholarship->requirements)
                                        @foreach(explode("\n", $scholarship->requirements) as $reqLine)
                                            @if(trim($reqLine))
                                                <div class="flex items-start gap-2">
                                                    <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                                    <span>{{ trim($reqLine) }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @elseif (Str::contains(mb_strtoupper($scholarship->name), 'PRIMEROS'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Certificado de estudios con acreditación de 1° o 2° puesto escolar.</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Acta de sesión de promoción escolar emitida por la I.E. de origen.</span>
                                        </div>
                                    @elseif(Str::contains(mb_strtoupper($scholarship->name), 'DEPORTISTAS'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Credencial vigente emitida por el IPD o Federación Nacional.</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Carta de compromiso de representación institucional en torneos.</span>
                                        </div>
                                    @elseif(Str::contains(mb_strtoupper($scholarship->name), 'TERRORISMO'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Acreditación en el Registro Único de Víctimas (RUV - Ley 28592).</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Certificado acreditativo expedido por el Consejo de Reparaciones.</span>
                                        </div>
                                    @elseif(Str::contains(mb_strtoupper($scholarship->name), 'PRE-INSTITUTO'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Constancia de nota final y orden de mérito de la CEPRE-FVC.</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Asistencia mínima del 85% a clases preparatorias.</span>
                                        </div>
                                    @elseif(Str::contains(mb_strtoupper($scholarship->name), 'DISCAPACITADOS'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Carné oficial emitido por CONADIS (Ley N° 29973).</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Certificado médico oficial de discapacidad emitido por MINSA o
                                                EsSalud.</span>
                                        </div>
                                    @elseif(Str::contains(mb_strtoupper($scholarship->name), 'TITULADOS'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Copia autenticada de Título Profesional o Grado de Bachiller.</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Certificado oficial de estudios de nivel superior para
                                                convalidación.</span>
                                        </div>
                                    @elseif(Str::contains(mb_strtoupper($scholarship->name), 'FUERZAS ARMADAS'))
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Libreta Militar o Constancia de Licenciado del Servicio Militar
                                                Voluntario.</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Documento oficial de acreditación emitido por las FF.AA.</span>
                                        </div>
                                    @else
                                        <div class="flex items-start gap-2">
                                            <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                            <span>Documento de Identidad (DNI) vigente y solicitud dirigida al
                                                Director.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Footer --}}
                            <div class="px-8 py-4 bg-slate-50 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-blue-800 flex items-center gap-1">
                                    <i class="bi bi-check-circle-fill text-emerald-600"></i>
                                    {{ $scholarship->vacancies ?? 0 }} Vacantes
                                </span>
                                <button @click="activeModal = {{ $scholarship->id }}"
                                    class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-transform">
                                    Ver Ficha y Requisitos <i class="bi bi-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Modal de detalle por Beca --}}
                        <div x-show="activeModal === {{ $scholarship->id }}"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
                            style="display: none;" @keydown.escape.window="activeModal = null">

                            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative space-y-6"
                                @click.away="activeModal = null">
                                <button @click="activeModal = null"
                                    class="absolute top-5 right-5 text-gray-400 hover:text-gray-700 w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                                    <i class="bi bi-x-lg text-lg"></i>
                                </button>

                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold shadow-md shadow-blue-600/20">
                                        <i class="bi {{ $scholarship->icon ?? 'bi-award' }}"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600">Ficha de Modalidad</span>
                                            <span class="bg-blue-100 text-blue-900 text-[10px] font-black px-2 py-0.5 rounded-full border border-blue-200">
                                                {{ $scholarship->vacancies ?? 0 }} Plazas
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-blue-900">{{ $scholarship->name }}</h3>
                                    </div>
                                </div>

                                <div class="space-y-4 text-sm text-gray-700">
                                    {{-- Descuento / Beneficio en Modal --}}
                                    @if ($scholarship->discount_details || $scholarship->discount_percentage > 0)
                                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-emerald-950">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[10px] font-black px-2 py-0.5 rounded shadow-xs">
                                                    <i class="bi bi-tag-fill"></i>
                                                    @if ($scholarship->discount_percentage > 0)
                                                        {{ number_format($scholarship->discount_percentage, 0) }}% DESCUENTO
                                                    @else
                                                        BENEFICIO
                                                    @endif
                                                </span>
                                                <span class="text-xs font-extrabold text-emerald-900 uppercase">Cuadro de Descuentos</span>
                                            </div>
                                            <p class="text-xs font-semibold text-emerald-900 leading-snug">
                                                {{ $scholarship->discount_details }}
                                            </p>
                                        </div>
                                    @endif

                                    <p class="leading-relaxed bg-blue-50/60 p-4 rounded-2xl border border-blue-100 text-xs sm:text-sm">
                                        {{ $scholarship->description }}
                                    </p>

                                    <div>
                                        <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                                            <i class="bi bi-folder-check text-blue-600"></i> Documentación obligatoria a presentar:
                                        </h4>
                                        <ul class="space-y-2 pl-1">
                                            @if ($scholarship->requirements)
                                                @foreach(explode("\n", $scholarship->requirements) as $reqLine)
                                                    @if(trim($reqLine))
                                                        <li class="flex items-start gap-2 text-xs text-gray-700">
                                                            <i class="bi bi-check-circle-fill text-blue-500 text-sm mt-0.5"></i>
                                                            <span>{{ trim($reqLine) }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @else
                                                <li class="flex items-start gap-2 text-xs text-gray-700">
                                                    <i class="bi bi-check-circle-fill text-blue-500 text-sm mt-0.5"></i>
                                                    <span>Ficha de inscripción debidamente completada y firmada.</span>
                                                </li>
                                                <li class="flex items-start gap-2 text-xs text-gray-700">
                                                    <i class="bi bi-check-circle-fill text-blue-500 text-sm mt-0.5"></i>
                                                    <span>Copia simple de DNI vigente.</span>
                                                </li>
                                                <li class="flex items-start gap-2 text-xs text-gray-700">
                                                    <i class="bi bi-check-circle-fill text-blue-500 text-sm mt-0.5"></i>
                                                    <span>Documento sustentatorio oficial de la modalidad elegida.</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div
                                        class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-900 text-xs flex items-start gap-2">
                                        <i class="bi bi-exclamation-triangle-fill text-amber-500 text-base"></i>
                                        <span>La presentación de documentos se realiza de manera presencial en la Secretaría
                                            Académica o mediante la Mesa de Partes Virtual durante las fechas habilitadas en
                                            la convocatoria.</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                    <a href="{{ route('mesa-de-partes') }}"
                                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-md">
                                        Presentar Expediente
                                    </a>
                                    <button @click="activeModal = null"
                                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                                        Cerrar
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-3xl border border-blue-100 p-8 max-w-xl mx-auto shadow-sm">
                    <i class="bi bi-info-circle text-4xl text-blue-500 mb-4 inline-block"></i>
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Información en actualización</h3>
                    <p class="text-gray-600 text-sm">Próximamente se publicarán las modalidades de becas vigentes para el
                        presente período académico.</p>
                </div>
            @endif

        </div>
    </section>


    {{-- ═══ PROGRAMAS EXTERNOS & BECA 18 PRONABEC ════════════════════════════ --}}
    {{-- <section id="becas-externas" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                class="bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-900 text-white rounded-3xl overflow-hidden shadow-2xl border border-blue-800">
                <div class="grid grid-cols-1 lg:grid-cols-12 items-center">
                    
                    <div class="lg:col-span-7 p-8 sm:p-12 space-y-6">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-bold uppercase tracking-wider">
                            <i class="bi bi-star-fill text-amber-400"></i> Programa de Becas del Estado
                        </div>

                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight font-sans">
                            Estudia gratis tu carrera técnica con <span class="text-amber-300">Beca 18 - PRONABEC</span>
                        </h2>

                        <p class="text-blue-100 text-base sm:text-lg leading-relaxed">
                            El IESTP Francisco Vigo Caballero es una institución pública elegible dentro de las
                            convocatorias nacionales de PRONABEC. Si eres preseleccionado en Beca 18, puedes cursar
                            cualquiera de nuestras carreras profesionales técnicas financiadas al 100% por el Estado
                            Peruano.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm pt-2">
                            <div class="bg-white/10 p-4 rounded-2xl border border-white/10">
                                <p class="font-bold text-white mb-1 flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-emerald-400"></i> Cobertura del 100%
                                </p>
                                <p class="text-xs text-blue-200">Cubre costo total de matrícula, titulación y manutención
                                    mensual.</p>
                            </div>
                            <div class="bg-white/10 p-4 rounded-2xl border border-white/10">
                                <p class="font-bold text-white mb-1 flex items-center gap-2">
                                    <i class="bi bi-laptop text-amber-300"></i> Equipamiento y Materiales
                                </p>
                                <p class="text-xs text-blue-200">Asignación para laptop, útiles de estudio y transporte.
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 flex flex-wrap gap-4">
                            <a href="https://www.pronabec.gob.pe/beca-18/" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center px-6 py-3.5 bg-amber-400 hover:bg-amber-300 text-blue-950 font-extrabold text-sm rounded-xl transition shadow-lg hover:shadow-amber-400/20">
                                Portal Oficial PRONABEC <i class="bi bi-box-arrow-up-right ml-2"></i>
                            </a>
                            <a href="#contacto-asesoria"
                                class="inline-flex items-center px-6 py-3.5 border-2 border-white/30 hover:bg-white/10 text-white font-bold text-sm rounded-xl transition">
                                Solicitar Asesoría Institucional
                            </a>
                        </div>
                    </div>

                    <div
                        class="lg:col-span-5 bg-gradient-to-t from-blue-900 to-transparent p-8 sm:p-12 flex flex-col justify-center items-center text-center border-t lg:border-t-0 lg:border-l border-white/10">
                        <div
                            class="w-24 h-24 bg-white/10 text-amber-300 rounded-3xl flex items-center justify-center text-5xl mb-6 shadow-inner border border-white/20">
                            <i class="bi bi-award-fill"></i>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">¿Necesitas orientación para tu expediente?</h3>
                        <p class="text-xs text-blue-200 max-w-sm mb-6">
                            Nuestro equipo de Bienestar Estudiantil te ayuda a validar si cumples los requisitos de
                            vulnerabilidad (SISFOH) y rendimiento académico exigidos por PRONABEC.
                        </p>

                        <a href="#contacto-asesoria"
                            class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-blue-50 text-blue-950 font-bold text-xs rounded-xl shadow-md transition">
                            Contactar con un Asesor
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section> --}}

    {{-- ═══ PASOS PARA POSTULAR (STEP BY STEP) ══════════════════════════════ --}}
    <section id="proceso-postulacion" class="py-16 bg-blue-50/40 border-t border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-100 px-3.5 py-1.5 rounded-full">
                    Guía Práctica
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-4 font-sans">
                    ¿Cómo solicitar tu Beca o Exoneración?
                </h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Sigue estos 4 pasos para formalizar tu postulación y acceder a los beneficios arancelarios del IESTP
                    Francisco Vigo Caballero.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- Step 1 --}}
                <div
                    class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Verifica tu Modalidad</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Revisa las bases de admisión y confirma si cumples las condiciones de Primeros Puestos, CONADIS,
                        RUV, Deportista o FF.AA.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div
                    class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Reúne tus Documentos</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Organiza tu DNI, certificado de estudios secundarios y los comprobantes oficiales requeridos según
                        tu modalidad elegida.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div
                    class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Ingresa tu Expediente</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Presenta tu solicitud a través de la Mesa de Partes Virtual o acude presencialmente a la Secretaría
                        Académica.
                    </p>
                </div>

                {{-- Step 4 --}}
                <div
                    class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Evaluación y Adjudicación</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        La Comisión Evaluadora valida tu documentación y formaliza la adjudicación del beneficio en la
                        nómina oficial.
                    </p>
                </div>

            </div>

        </div>
    </section>


    {{-- ═══ PREGUNTAS FRECUENTES (FAQ ACCORDION) ════════════════════════════ --}}
    <section id="preguntas-frecuentes" class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16">
                <span
                    class="text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-100 px-3.5 py-1.5 rounded-full">
                    Resuelve tus dudas
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-4 font-sans">
                    Preguntas Frecuentes
                </h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="space-y-4" x-data="{ openFaq: 1 }">

                {{-- FAQ 1 --}}
                <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                    <button @click="openFaq = (openFaq === 1 ? null : 1)"
                        class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                        <span>¿Las becas y exoneraciones aplican para todas las carreras profesionales técnicas?</span>
                        <i class="bi text-xl text-blue-600"
                            :class="openFaq === 1 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 1" x-collapse
                        class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                        Sí, los beneficios contemplados por Ley y las exoneraciones de admisión por mérito institucional
                        aplican equitativamente para los 5 programas de estudio dictados en el IESTP Francisco Vigo
                        Caballero, sujeto a las vacantes aprobadas para cada modalidad.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                    <button @click="openFaq = (openFaq === 2 ? null : 2)"
                        class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                        <span>¿Puedo postular a Beca 18 si ya estoy matriculado en el instituto?</span>
                        <i class="bi text-xl text-blue-600"
                            :class="openFaq === 2 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 2" x-collapse
                        class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                        Las bases de Beca 18 de PRONABEC distinguen entre modalidades para ingresantes y para estudiantes en
                        continuidad de estudios. Te sugerimos consultar la convocatoria anual vigente de PRONABEC o
                        acercarte a nuestra oficina de Bienestar Estudiantil para evaluar tu caso.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                    <button @click="openFaq = (openFaq === 3 ? null : 3)"
                        class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                        <span>¿Es necesario actualizar la acreditación de CONADIS o RUV cada ciclo?</span>
                        <i class="bi text-xl text-blue-600"
                            :class="openFaq === 3 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 3" x-collapse
                        class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                        No. Una vez acreditada la condición legal de beneficiario (carné de CONADIS permanente o inclusión
                        en el Registro Único de Víctimas RUV), el beneficio se registra formalmente en tu expediente único
                        de estudiante.
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                    <button @click="openFaq = (openFaq === 4 ? null : 4)"
                        class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                        <span>¿Cómo se realiza el trámite si soy beneficiario del Servicio Militar Voluntario?</span>
                        <i class="bi text-xl text-blue-600"
                            :class="openFaq === 4 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                    </button>
                    <div x-show="openFaq === 4" x-collapse
                        class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                        Debes adjuntar tu Libreta Militar o Constancia de Licenciado emitida por el Comando de las Fuerzas
                        Armadas (Ejército, Marina o Fuerza Aérea) en tu carpeta de admisión para otorgarte la bonificación
                        del 10% adicional y descuentos correspondientes.
                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- ═══ SECCIÓN DE CONTACTO Y ASESORÍA DIRECTA ══════════════════════════ --}}
    <section id="contacto-asesoria" class="py-16 bg-slate-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div
                class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-3xl p-8 sm:p-12 border border-blue-700 shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                    <div class="lg:col-span-8 space-y-4">
                        <span
                            class="text-xs font-extrabold uppercase tracking-widest text-blue-300 bg-blue-950/60 px-3 py-1 rounded-full border border-blue-400/30">
                            Atención Personalizada
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-sans">
                            ¿Tienes dudas sobre los requisitos o postulaciones?
                        </h2>
                        <p class="text-blue-100 text-base leading-relaxed">
                            Nuestra <strong class="text-white">Unidad de Bienestar Estudiantil y Secretaría
                                Académica</strong> se encuentra a tu disposición para asesorarte paso a paso en el armado de
                            tu expediente.
                        </p>

                        <div class="flex flex-wrap gap-6 pt-2 text-sm text-blue-200">
                            @if (!empty($enterprise->address))
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-blue-400 text-lg"></i>
                                    <span>{{ $enterprise->address }}, {{ $enterprise->city ?? 'Uchiza' }}</span>
                                </div>
                            @endif
                            @if (!empty($enterprise->phone_number_1))
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-telephone-fill text-blue-400 text-lg"></i>
                                    <a href="tel:{{ $enterprise->phone_number_1 }}"
                                        class="hover:text-white transition-colors">
                                        {{ $enterprise->phone_number_1 }}
                                    </a>
                                </div>
                            @endif
                            @if (!empty($enterprise->email))
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-envelope-fill text-blue-400 text-lg"></i>
                                    <a href="mailto:{{ $enterprise->email }}" class="hover:text-white transition-colors">
                                        {{ $enterprise->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4">
                        @if (!empty($enterprise->whatsapp_link))
                            <a href="{{ $enterprise->whatsapp_link }}" target="_blank" rel="noopener noreferrer"
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm rounded-xl transition shadow-lg hover:shadow-emerald-500/20">
                                <i class="bi bi-whatsapp mr-2 text-lg"></i> Consulta por WhatsApp
                            </a>
                        @endif

                        <a href="{{ route('mesa-de-partes') }}"
                            class="w-full inline-flex items-center justify-center px-6 py-4 bg-white hover:bg-blue-50 text-blue-900 font-extrabold text-sm rounded-xl transition shadow-lg">
                            <i class="bi bi-file-earmark-arrow-up mr-2 text-lg text-blue-700"></i> Ir a Mesa de Partes
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section>

@endsection
