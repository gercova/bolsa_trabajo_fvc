@extends('layouts.app')
@section('title', 'CEPRE-FVC — IESTP Francisco Vigo Caballero')
@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 text-white overflow-hidden py-16 lg:py-24">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-700/30 via-transparent to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white">
                        Tu ingreso directo al <br>
                        <span class="text-blue-200">Futuro Profesional</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                        Asegura tu ingreso directo al IESTP Francisco Vigo Caballero. Prepárate con nuestra plana docente especializada y accede a las vacantes exclusivas asignadas por orden de mérito.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#active-processes" class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-blue-900 bg-white hover:bg-blue-50 rounded-xl transition shadow-lg hover:shadow-xl">
                            <i class="bi bi-calendar-event mr-2 text-lg"></i>
                            Ver Convocatorias
                        </a>
                        <a href="#requirements" class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-white border-2 border-blue-400/40 hover:bg-white/10 rounded-xl transition">
                            <i class="bi bi-journal-text mr-2 text-lg"></i>
                            Requisitos de Inscripción
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-3xl blur-2xl opacity-30"></div>
                        <img src="{{ $cepreImage ? $cepreImage->url : asset('images/cepre_hero_banner.png') }}"
                            alt="CEPRE IESTP Francisco Vigo Caballero" 
                            class="relative rounded-2xl shadow-2xl border-4 border-white/10 w-full object-cover aspect-square sm:aspect-video lg:aspect-square">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Ventajas del Proceso --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">¿Por qué estudiar en la CEPRE-FVC?</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Nuestra preparación integral está diseñada para afianzar tus competencias y darte la mayor posibilidad de ingresar de forma directa a la carrera técnica de tu elección.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Ventaja 1 --}}
                <div class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                    <div class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                        <i class="bi bi-award-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Ingreso Directo</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Accede directamente a una de las vacantes reservadas exclusivamente para alumnos del Centro de Preparación, según su orden de mérito.
                    </p>
                </div>
                {{-- Ventaja 2 --}}
                <div class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                    <div class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                        <i class="bi bi-journal-check text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Temario Especializado</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Clases dirigidas y enfocadas con contenidos curriculares actualizados alineados a la evaluación institucional.
                    </p>
                </div>
                {{-- Ventaja 3 --}}
                <div class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                    <div class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Plana Docente Calificada</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Aprende con catedráticos experimentados y con amplia trayectoria en la preparación pre-institucional técnica superior.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Convocatorias Activas --}}
    <section id="active-processes" class="py-16 bg-blue-50/30 border-y border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-extrabold uppercase tracking-wider mb-3">
                    <i class="bi bi-calendar3"></i>
                    Cronograma Proyectado {{ $projectedPeriod ?? '2027-I' }}
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Convocatoria y Cronograma CEPRE</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Revisa a continuación la programación del ciclo proyectado {{ $projectedPeriod ?? '2027-I' }}, costos académicos, duración, vacantes ofertadas y publicación de resultados.
                </p>
            </div>

            @if ($exams->count() > 0)
                <div class="space-y-12">
                    @foreach ($exams as $exam)
                        <div class="bg-white rounded-3xl shadow-xl border border-blue-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <span class="inline-block text-xs font-bold tracking-widest text-blue-200 uppercase bg-blue-900/40 py-1 px-3 rounded-full mb-1">
                                        Período Proyectado
                                    </span>
                                    <h3 class="text-2xl font-extrabold text-white">{{ $exam->activity }} - {{ $exam->period }}</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-extrabold px-4 py-2 rounded-full flex items-center gap-1.5 shadow-sm">
                                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                                        Convocatoria Abierta
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                                    {{-- Detalles Principales --}}
                                    <div class="lg:col-span-7 space-y-6">
                                        {{-- Costos y Duración Grid --}}
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                            {{-- Costo Inscripción --}}
                                            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                                <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                    <i class="bi bi-cash-coin text-sm"></i>
                                                    <span class="text-[11px] font-bold uppercase tracking-wider">Inscripción</span>
                                                </div>
                                                <span class="text-xl font-black text-blue-900">S/ {{ number_format($exam->price, 2) }}</span>
                                            </div>

                                            {{-- Costo Matrícula --}}
                                            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                                <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                    <i class="bi bi-receipt text-sm"></i>
                                                    <span class="text-[11px] font-bold uppercase tracking-wider">Matrícula</span>
                                                </div>
                                                <span class="text-xl font-black text-blue-900">S/ {{ number_format($exam->tuition_fee ?? 0, 2) }}</span>
                                            </div>

                                            {{-- Mensualidad --}}
                                            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                                <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                    <i class="bi bi-calendar-check text-sm"></i>
                                                    <span class="text-[11px] font-bold uppercase tracking-wider">Mensualidad</span>
                                                </div>
                                                <span class="text-xl font-black text-blue-900">S/ {{ number_format($exam->monthly_fee ?? 0, 2) }}</span>
                                            </div>

                                            {{-- Duración --}}
                                            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                                <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                    <i class="bi bi-hourglass-split text-sm"></i>
                                                    <span class="text-[11px] font-bold uppercase tracking-wider">Duración</span>
                                                </div>
                                                <span class="text-sm font-black text-blue-900 leading-tight">
                                                    {{ $exam->duration ?: '3 Meses' }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Fechas de Inscripción y Examen --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">
                                                    <i class="bi bi-calendar-range text-blue-600 mr-1"></i> Período de Inscripción
                                                </span>
                                                <p class="text-sm font-extrabold text-slate-800">
                                                    @if ($exam->inscription_start_date && $exam->inscription_end_date)
                                                        {{ \Carbon\Carbon::parse($exam->inscription_start_date)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($exam->inscription_end_date)->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-slate-400 font-normal">Por confirmar</span>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">
                                                    <i class="bi bi-calendar2-check text-red-600 mr-1"></i> Fecha de Evaluación Final
                                                </span>
                                                <p class="text-sm font-extrabold text-slate-800">
                                                    @if ($exam->exam_date)
                                                        {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-slate-400 font-normal">Por confirmar</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Indicaciones para el Postulante --}}
                                        @if ($exam->indications)
                                            <div class="p-5 bg-amber-50/70 border border-amber-200/80 rounded-2xl space-y-2">
                                                <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                                                    <i class="bi bi-info-circle-fill text-amber-600 text-sm"></i>
                                                    Indicaciones para el Postulante
                                                </h4>
                                                <p class="text-xs text-amber-900 leading-relaxed font-medium whitespace-pre-line">
                                                    {{ $exam->indications }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- Documentos Adjuntos (Bases y Resultados) --}}
                                        <div class="pt-2 flex flex-wrap gap-3">
                                            @if ($exam->url_pdf)
                                                <a href="{{ Storage::url($exam->url_pdf) }}" target="_blank"
                                                    class="inline-flex items-center px-5 py-3 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-xl transition shadow-xs">
                                                    <i class="bi bi-file-earmark-pdf-fill mr-2 text-base text-red-500"></i>
                                                    Descargar Bases y Prospecto (PDF)
                                                </a>
                                            @endif

                                            @if ($exam->results_url_pdf)
                                                <a href="{{ Storage::url($exam->results_url_pdf) }}" target="_blank"
                                                    class="inline-flex items-center px-5 py-3 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-600 hover:text-white rounded-xl transition shadow-xs">
                                                    <i class="bi bi-award-fill mr-2 text-base text-emerald-600"></i>
                                                    Ver Resultados Oficiales (PDF)
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Distribución de Vacantes --}}
                                    <div class="lg:col-span-5 bg-blue-50/30 border border-blue-100 p-6 rounded-2xl flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between border-b border-blue-100 pb-3 mb-4">
                                                <h4 class="text-sm font-bold text-blue-900 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="bi bi-pie-chart-fill text-blue-500"></i>
                                                    Vacantes Ofertadas
                                                </h4>
                                                <span class="bg-blue-600 text-white text-xs font-extrabold px-3 py-1 rounded-full">
                                                    Total: {{ $exam->total_vacancies }}
                                                </span>
                                            </div>

                                            <div class="space-y-2.5">
                                                @forelse ($exam->admissionDetail as $detail)
                                                    @if ($detail->program)
                                                        <div class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-blue-50 shadow-xs hover:border-blue-200 transition-colors">
                                                            <span class="text-xs font-bold text-gray-700 leading-tight pr-2">
                                                                {{ $detail->program->name }}
                                                            </span>
                                                            <span class="flex-shrink-0 bg-blue-100 text-blue-900 text-xs font-black px-3 py-1 rounded-lg border border-blue-200">
                                                                {{ $detail->vacancies }} Vac.
                                                            </span>
                                                        </div>
                                                    @endif
                                                @empty
                                                    <p class="text-xs text-gray-500 italic text-center py-4">Vacantes en proceso de asignación.</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-blue-100 text-[11px] text-gray-500 text-center">
                                            <i class="bi bi-shield-check text-blue-600 mr-1"></i>
                                            Vacantes exclusivas por orden de mérito CEPRE
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-blue-100 shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-calendar-x-fill text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">No hay convocatorias vigentes</h3>
                    <p class="text-base text-gray-600 leading-relaxed mb-6">
                        Actualmente no contamos con procesos de admisión CEPRE activos para el ciclo {{ $projectedPeriod ?? '2027-I' }}. Estamos trabajando en la planificación académica de la próxima convocatoria.
                    </p>
                    <a href="#contact" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-xl transition shadow-md">
                        <i class="bi bi-chat-left-text-fill mr-2"></i>
                        Consultar Próximas Fechas
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Sección de Publicación de Resultados (PDF) --}}
    @php
        $latestResultExam = $exams->first(fn($e) => !empty($e->results_url_pdf));
    @endphp
    <section id="results" class="py-16 bg-white border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold uppercase tracking-wider mb-3">
                    <i class="bi bi-award-fill"></i>
                    Evaluación y Méritos
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Publicación de Resultados CEPRE</h2>
                <div class="w-20 h-1.5 bg-emerald-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-base text-gray-600 mt-4 leading-relaxed">
                    Consulta la relación oficial de ingresantes y cuadro de méritos del Centro Preuniversitario CEPRE-FVC.
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                @if ($latestResultExam && $latestResultExam->results_url_pdf)
                    <div class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 text-white rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="space-y-4 text-center md:text-left">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/30 text-emerald-200 text-xs font-extrabold uppercase tracking-wider border border-emerald-400/30">
                                    <i class="bi bi-check-circle-fill"></i> Resultados Publicados
                                </span>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                                    Cuadro de Méritos y Resultados {{ $latestResultExam->period }}
                                </h3>
                                <p class="text-sm text-emerald-100 max-w-xl leading-relaxed">
                                    El archivo oficial contiene la relación de ingresantes por orden de mérito y puntajes obtenidos en el proceso de evaluación CEPRE.
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row md:flex-col gap-3 w-full md:w-auto flex-shrink-0">
                                <a href="{{ Storage::url($latestResultExam->results_url_pdf) }}" target="_blank"
                                    class="inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-emerald-50 text-emerald-950 font-extrabold text-sm rounded-xl transition shadow-lg hover:shadow-xl gap-2">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-600 text-lg"></i>
                                    <span>Ver Resultados (PDF)</span>
                                </a>
                                <a href="{{ Storage::url($latestResultExam->results_url_pdf) }}" download target="_blank"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-emerald-700/60 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl border border-emerald-500/40 transition gap-2">
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
                        <h3 class="text-xl font-bold text-slate-800">Resultados en Espera de Publicación</h3>
                        <p class="text-sm text-slate-600 max-w-lg mx-auto leading-relaxed">
                            Los resultados oficiales del ciclo CEPRE {{ $projectedPeriod ?? '2027-I' }} y el cuadro general de méritos serán publicados en este espacio una vez culminada la jornada de evaluación.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-200/70 text-slate-700 text-xs font-semibold">
                                <i class="bi bi-clock-history text-slate-500"></i>
                                Se publicará al concluir la fecha de examen
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
                    Para postular a través de nuestra modalidad CEPRE, asegúrate de cumplir y presentar la siguiente documentación reglamentaria:
                </p>
            </div>

            @if ($requirements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    @foreach ($requirements as $req)
                        <div class="flex items-start gap-4 bg-blue-50/30 p-6 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
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
                        Los requisitos detallados del proceso de admisión CEPRE se especifican en el prospecto de admisión correspondiente.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Contacto --}}
    <section id="contact" class="py-16 bg-blue-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-stops))] from-blue-700/40 via-transparent to-transparent"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
            <h2 class="text-3xl sm:text-4xl font-extrabold">¿Tienes alguna duda sobre el proceso?</h2>
            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Nuestra Oficina de Admisiones está lista para brindarte toda la información que necesitas sobre pre-inscripciones, vacantes y formas de pago.
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
