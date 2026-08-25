@extends('layouts.app')
@section('title', 'Examen de Admisión — IESTP Francisco Vigo Caballero')
@section('content')
    {{-- Hero Section --}}
    <section
        class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 text-white overflow-hidden py-16 lg:py-24">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-700/30 via-transparent to-transparent">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white">
                        Construye tu éxito <br>
                        <span class="text-blue-200">Profesional Técnico</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                        Inicia tu formación profesional tecnológica de 3 años. Elige tu carrera, inscríbete al examen de
                        admisión y obtén tu Título a Nombre de la Nación.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#active-processes"
                            class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-blue-900 bg-white hover:bg-blue-50 rounded-xl transition shadow-lg hover:shadow-xl">
                            <i class="bi bi-calendar-event mr-2 text-lg"></i>
                            Convocatorias Vigentes
                        </a>
                        <a href="#modalidades"
                            class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-white border-2 border-blue-400/40 hover:bg-white/10 rounded-xl transition">
                            <i class="bi bi-shield-check mr-2 text-lg"></i>
                            Modalidades de Ingreso
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-3xl blur-2xl opacity-30">
                        </div>
                        <img src="{{ $admisionImage ? $admisionImage->url : asset('images/admission_hero_banner.png') }}"
                            alt="Examen de Admisión IESTP Francisco Vigo Caballero"
                            class="relative rounded-2xl shadow-2xl border-4 border-white/10 w-full object-cover aspect-square sm:aspect-video lg:aspect-square">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- Modalidades de Ingreso --}}
    <section id="modalidades" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-extrabold uppercase tracking-wider mb-3">
                    <i class="bi bi-shield-check"></i>
                    Opciones de Postulación
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Modalidades de Admisión</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Contamos con modalidades diferenciadas para brindarte oportunidades de acceso acordes a tu trayectoria
                    académica y talento.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- 1. Extraordinario (Primero) --}}
                <div
                    class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-200/30 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
                    <div
                        class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-indigo-600/20">
                        <i class="bi bi-star-fill text-2xl"></i>
                    </div>
                    <div class="inline-block text-[11px] font-bold tracking-widest text-indigo-700 uppercase bg-indigo-100 py-1 px-3 rounded-full mb-2">
                        Modalidad Especial
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">1. Admisión Extraordinaria</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Exclusiva para los primeros puestos de nivel secundario, deportistas destacados acreditados, personas con
                        discapacidad, personal de servicio militar voluntario y beneficiarios de programas sociales (PIR).
                    </p>
                </div>

                {{-- 2. Ordinario (Segundo) --}}
                <div
                    class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-200/30 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
                    <div
                        class="w-14 h-14 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-600/20">
                        <i class="bi bi-journal-text text-2xl"></i>
                    </div>
                    <div class="inline-block text-[11px] font-bold tracking-widest text-blue-700 uppercase bg-blue-100 py-1 px-3 rounded-full mb-2">
                        Modalidad General
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">2. Admisión Ordinaria</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Dirigida a todos los egresados de Educación Básica Regular (EBR) y Educación Básica Alternativa
                        (EBA) que deseen rendir la prueba general de conocimientos y aptitudes académicas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Convocatorias Activas y Cronograma Proyectado --}}
    <section id="active-processes" class="py-16 bg-blue-50/30 border-y border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-extrabold uppercase tracking-wider mb-3">
                    <i class="bi bi-calendar3"></i>
                    Cronograma Proyectado {{ $projectedPeriod ?? '2027-I' }}
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Convocatorias y Vacantes de Admisión</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Consulta el cronograma oficial del ciclo proyectado {{ $projectedPeriod ?? '2027-I' }}, fechas de inscripción, costos de derecho de examen y el cuadro general de vacantes disponibles.
                </p>
            </div>

            {{-- Tarjetas Resumen de Vacantes con Descuento CEPRE --}}
            <div class="max-w-5xl mx-auto mb-14">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-blue-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-gray-100 pb-6 mb-6">
                        <div>
                            <span class="text-xs font-extrabold text-blue-600 uppercase tracking-wider block mb-1">
                                <i class="bi bi-pie-chart-fill mr-1"></i> Balance de Vacantes {{ $projectedPeriod ?? '2027-I' }}
                            </span>
                            <h3 class="text-xl sm:text-2xl font-black text-gray-900">
                                Disponibilidad Total de Vacantes
                            </h3>
                        </div>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold shadow-xs">
                            <i class="bi bi-check-circle-fill text-emerald-600"></i>
                            Vacantes Netas Calculadas
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        {{-- 1. Vacantes Totales Brutas --}}
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="bi bi-buildings text-slate-500"></i> Vacantes Ofertadas
                            </span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-slate-800">{{ $totalGrossVacancies ?? $exams->sum('total_vacancies') }}</span>
                                <span class="text-xs font-semibold text-slate-500">Plazas</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-2">Capacidad institucional en los exámenes de admisión convocados.</p>
                        </div>

                        {{-- 2. Vacantes Reservadas CEPRE --}}
                        <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 flex flex-col justify-between">
                            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="bi bi-mortarboard-fill text-amber-600"></i> Descuento CEPRE
                            </span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-amber-900">-{{ $cepreTotalVacancies ?? 0 }}</span>
                                <span class="text-xs font-semibold text-amber-700">Plazas</span>
                            </div>
                            <p class="text-[11px] text-amber-800 mt-2">Vacantes asignadas para ingreso directo por orden de mérito CEPRE-FVC.</p>
                        </div>

                        {{-- 3. Vacantes Disponibles Netas --}}
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-lg flex flex-col justify-between">
                            <span class="text-xs font-bold text-emerald-100 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="bi bi-check-all text-white"></i> Vacantes Disponibles
                            </span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-black text-white">{{ $totalAvailableVacancies ?? 0 }}</span>
                                <span class="text-xs font-bold text-emerald-200">Plazas Netas</span>
                            </div>
                            <p class="text-[11px] text-emerald-100 mt-2">Disponibles a concursar en el proceso de admisión general.</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($exams->count() > 0)
                <div class="space-y-12">
                    @foreach ($exams as $exam)
                        <div class="bg-white rounded-3xl shadow-xl border border-blue-100 overflow-hidden">
                            <div
                                class="bg-gradient-to-r {{ $exam->type === 'extraordinario' ? 'from-indigo-800 via-indigo-700 to-purple-800' : 'from-blue-700 to-blue-600' }} px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="inline-block text-[10px] font-extrabold tracking-widest uppercase bg-white/20 text-white py-0.5 px-2.5 rounded-full">
                                            {{ $exam->type === 'extraordinario' ? 'Admisión Extraordinaria' : 'Admisión Ordinaria' }}
                                        </span>
                                        <span class="text-xs font-bold text-blue-100 uppercase">
                                            Período Proyectado: {{ $exam->period }}
                                        </span>
                                    </div>
                                    <h3 class="text-2xl font-extrabold text-white">{{ $exam->activity }}</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="bg-white text-blue-900 text-xs font-extrabold px-4 py-2 rounded-full flex items-center gap-1.5 shadow-sm">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $exam->type === 'extraordinario' ? 'bg-indigo-600' : 'bg-blue-600' }} animate-pulse"></span>
                                        Convocatoria Abierta
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                                    {{-- Detalles Principales --}}
                                    <div class="lg:col-span-7 space-y-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                            {{-- Costo Examen --}}
                                            <div
                                                class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                                <div class="flex items-center gap-1 text-blue-700 mb-1">
                                                    <i class="bi bi-cash-coin text-sm"></i>
                                                    <span class="text-xs font-bold uppercase tracking-wider">Derecho Examen</span>
                                                </div>
                                                <span class="text-2xl font-black text-blue-900">
                                                    {{ $exam->price > 0 ? 'S/ ' . number_format($exam->price, 2) : 'Gratuito' }}
                                                </span>
                                            </div>

                                            {{-- Fechas de Inscripción --}}
                                            <div
                                                class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center col-span-2">
                                                <div class="flex items-center gap-1 text-blue-700 mb-1">
                                                    <i class="bi bi-calendar-range text-sm"></i>
                                                    <span class="text-xs font-bold uppercase tracking-wider">Inscripciones</span>
                                                </div>
                                                <span class="text-sm sm:text-base font-extrabold text-gray-800">
                                                    @if ($exam->inscription_start_date && $exam->inscription_end_date)
                                                        {{ \Carbon\Carbon::parse($exam->inscription_start_date)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($exam->inscription_end_date)->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-gray-400 font-normal">Según cronograma institucional</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Día de la Evaluación --}}
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">
                                                <i class="bi bi-calendar2-check text-blue-600 mr-1"></i> Día de la Evaluación
                                            </span>
                                            <p class="text-base text-gray-800 font-bold">
                                                @if ($exam->exam_date)
                                                    La evaluación se llevará a cabo el día:
                                                    <span class="text-blue-700 underline font-black">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="text-gray-500 font-normal">Fecha por confirmar mediante resolución directoral.</span>
                                                @endif
                                            </p>
                                        </div>

                                        {{-- Indicaciones para el Postulante --}}
                                        @if ($exam->indications)
                                            <div class="p-5 bg-amber-50/70 border border-amber-200 rounded-2xl space-y-2">
                                                <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                                                    <i class="bi bi-info-circle-fill text-amber-600 text-sm"></i>
                                                    Indicaciones e Instrucciones para el Postulante
                                                </h4>
                                                <p class="text-xs text-amber-900 leading-relaxed font-medium whitespace-pre-line">
                                                    {{ $exam->indications }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- Documentos Adjuntos --}}
                                        <div class="pt-2 flex flex-wrap gap-3">
                                            @if ($exam->url_pdf)
                                                <a href="{{ Storage::url($exam->url_pdf) }}" target="_blank"
                                                    class="inline-flex items-center px-5 py-3 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-xl transition shadow-xs">
                                                    <i class="bi bi-file-earmark-pdf-fill mr-2 text-base text-red-500"></i>
                                                    Descargar Prospecto / Bases (PDF)
                                                </a>
                                            @endif

                                            @if ($exam->results_url_pdf)
                                                <a href="{{ Storage::url($exam->results_url_pdf) }}" target="_blank"
                                                    class="inline-flex items-center px-5 py-3 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-600 hover:text-white rounded-xl transition shadow-xs">
                                                    <i class="bi bi-award-fill mr-2 text-base text-emerald-600"></i>
                                                    Ver Resultados de la Evaluación (PDF)
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Distribución de Vacantes por Programa --}}
                                    <div class="lg:col-span-5 bg-blue-50/30 border border-blue-100 p-6 rounded-2xl flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between border-b border-blue-100 pb-3 mb-4">
                                                <h4
                                                    class="text-sm font-bold text-blue-900 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="bi bi-pie-chart-fill text-blue-500"></i>
                                                    Vacantes Ofertadas
                                                </h4>
                                                <span
                                                    class="bg-blue-600 text-white text-xs font-extrabold px-3 py-1 rounded-full">
                                                    Total: {{ $exam->total_vacancies }}
                                                </span>
                                            </div>

                                            <div class="space-y-2.5">
                                                @forelse ($exam->admissionDetail as $detail)
                                                    @if ($detail->program)
                                                        @php
                                                            $cepreDeduction = ($exam->type === 'ordinario') ? ($cepreVacanciesByProgram[$detail->program_id] ?? 0) : 0;
                                                            $netProgramVacancies = max(0, $detail->vacancies - $cepreDeduction);
                                                        @endphp
                                                        <div
                                                            class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-blue-50 shadow-xs hover:border-blue-200 transition-colors">
                                                            <div class="pr-2">
                                                                <span class="text-xs font-bold text-gray-800 leading-tight block">
                                                                    {{ $detail->program->name }}
                                                                </span>
                                                                @if ($cepreDeduction > 0)
                                                                    <span class="text-[10px] text-amber-700 font-semibold flex items-center gap-1 mt-0.5">
                                                                        <i class="bi bi-info-circle"></i> Ofertadas: {{ $detail->vacancies }} (-{{ $cepreDeduction }} CEPRE)
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="flex flex-col items-end flex-shrink-0">
                                                                <span
                                                                    class="bg-blue-100 text-blue-900 text-xs font-black px-3 py-1 rounded-lg border border-blue-200">
                                                                    {{ $exam->type === 'ordinario' && $cepreDeduction > 0 ? $netProgramVacancies : $detail->vacancies }} Vac.
                                                                </span>
                                                                @if ($exam->type === 'ordinario' && $cepreDeduction > 0)
                                                                    <span class="text-[9px] font-bold text-emerald-700 uppercase mt-0.5">Disponibles</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @empty
                                                    <p class="text-xs text-gray-500 italic text-center py-4">Vacantes en proceso de asignación.</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        @if ($exam->type === 'ordinario' && ($cepreTotalVacancies ?? 0) > 0)
                                            <div class="mt-4 pt-3 border-t border-blue-100 text-[11px] text-gray-500 text-center">
                                                <i class="bi bi-shield-check text-blue-600 mr-1"></i>
                                                Vacantes netas con deducción de las plazas cubiertas por CEPRE-FVC
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-blue-100 shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div
                        class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-calendar-x-fill text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">No hay convocatorias vigentes</h3>
                    <p class="text-base text-gray-600 leading-relaxed mb-6">
                        Actualmente no contamos con procesos de admisión general abiertos para el período {{ $projectedPeriod ?? '2027-I' }}. Estamos trabajando
                        en la planificación académica de la próxima convocatoria.
                    </p>
                    <a href="#contact"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-xl transition shadow-md">
                        <i class="bi bi-chat-left-text-fill mr-2"></i>
                        Consultar Próximas Fechas
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Sección de Publicación de Resultados Anteriores (PDF) - Solo del Último Examen --}}
    <section id="past-results" class="py-16 bg-white border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-800 border border-slate-200 text-xs font-extrabold uppercase tracking-wider mb-3">
                    <i class="bi bi-award-fill text-blue-600"></i>
                    Histórico de Evaluaciones
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Resultados del Último Examen de Admisión</h2>
                <div class="w-20 h-1.5 bg-blue-600 mx-auto mt-4 rounded-full"></div>
                <p class="text-base text-gray-600 mt-4 leading-relaxed">
                    Consulta la relación oficial de ingresantes, puntajes y cuadro de méritos correspondiente a la última evaluación de admisión efectuada en la institución.
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                @if ($lastExamResults && $lastExamResults->results_url_pdf)
                    <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="space-y-4 text-center md:text-left">
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-extrabold uppercase tracking-wider border border-emerald-400/30">
                                        <i class="bi bi-check-circle-fill"></i> Resultados Oficiales
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold">
                                        Período {{ $lastExamResults->period }}
                                    </span>
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                                    {{ $lastExamResults->activity }} — {{ $lastExamResults->period }}
                                </h3>
                                <p class="text-sm text-blue-100 max-w-xl leading-relaxed">
                                    Documento oficial con el cuadro general de méritos, puntajes obtenidos y la nómina de ingresantes por programa de estudio del último proceso de admisión.
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row md:flex-col gap-3 w-full md:w-auto flex-shrink-0">
                                <a href="{{ Storage::url($lastExamResults->results_url_pdf) }}" target="_blank"
                                    class="inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-blue-50 text-blue-950 font-extrabold text-sm rounded-xl transition shadow-lg hover:shadow-xl gap-2">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-600 text-lg"></i>
                                    <span>Ver Resultados (PDF)</span>
                                </a>
                                <a href="{{ Storage::url($lastExamResults->results_url_pdf) }}" download target="_blank"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-800/80 hover:bg-blue-800 text-white font-bold text-xs rounded-xl border border-blue-500/40 transition gap-2">
                                    <i class="bi bi-download"></i>
                                    <span>Descargar Documento</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-slate-50 border border-slate-200 rounded-3xl p-8 sm:p-12 text-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto text-2xl">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Resultados del Último Examen en Archivo</h3>
                        <p class="text-sm text-slate-600 max-w-lg mx-auto leading-relaxed">
                            Los resultados oficiales y el cuadro de méritos del último proceso de admisión estarán disponibles en este apartado para libre consulta pública.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-200/70 text-slate-700 text-xs font-semibold">
                                <i class="bi bi-clock-history text-slate-500"></i>
                                Consulta en Secretaría Académica
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Requisitos de Inscripción --}}
    <section id="requirements" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Requisitos de Inscripción</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Prepara tu expediente de postulación con los siguientes documentos exigidos por el Ministerio de
                    Educación:
                </p>
            </div>

            @if ($requirements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    @foreach ($requirements as $req)
                        <div
                            class="flex items-start gap-4 bg-blue-50/30 p-6 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="bi bi-check2-square text-xl font-black"></i>
                            </div>
                            <div>
                                <p class="text-gray-700 text-base font-semibold leading-relaxed">
                                    {{ $req->requirement }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-blue-50/50 p-8 rounded-2xl border border-blue-100 max-w-2xl mx-auto text-center">
                    <p class="text-lg text-blue-900 font-semibold">
                        Los requisitos detallados del proceso se especifican en el prospecto de admisión correspondiente.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Contacto --}}
    <section id="contact" class="py-16 bg-blue-900 text-white relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-stops))] from-blue-700/40 via-transparent to-transparent">
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
            <h2 class="text-3xl sm:text-4xl font-extrabold">¿Tienes alguna duda sobre el proceso?</h2>
            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Nuestra Oficina de Admisiones está lista para brindarte toda la información que necesitas sobre
                pre-inscripciones, vacantes y formas de pago.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 pt-4">
                @if ($enterprise->phone_number_1)
                    <a href="tel:{{ $enterprise->phone_number_1 }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-white text-blue-900 hover:bg-blue-50 rounded-xl transition shadow-lg">
                        <i class="bi bi-telephone-fill mr-2 text-xl text-blue-600"></i>
                        Llamar a Admisión: {{ $enterprise->phone_number_1 }}
                    </a>
                @endif
                @if ($enterprise->whatsapp_link)
                    <a href="{{ $enterprise->whatsapp_link }}" target="_blank"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-green-500 hover:bg-green-600 text-white rounded-xl transition shadow-lg">
                        <i class="bi bi-whatsapp mr-2 text-xl"></i>
                        Escríbenos por WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
