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
                        <img src="{{ asset('images/admission_hero_banner.png') }}"
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
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Modalidades de Admisión</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Contamos con modalidades diferenciadas para brindarte oportunidades de acceso acordes a tu trayectoria
                    académica y talento.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- Ordinario --}}
                <div
                    class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                    <div
                        class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                        <i class="bi bi-journal-text text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Admisión Ordinaria</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Dirigida a todos los egresados de Educación Básica Regular (EBR) y Educación Básica Alternativa
                        (EBA) que deseen rendir el examen general de conocimientos y aptitudes académicas.
                    </p>
                </div>
                {{-- Extraordinario --}}
                <div
                    class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                    <div
                        class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                        <i class="bi bi-star-fill text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Admisión Extraordinaria</h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Exclusiva para los primeros puestos de nivel secundario, deportistas destacados, personas con
                        discapacidad, personal de servicio militar voluntario y beneficiarios de programas sociales.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Convocatorias Activas --}}
    <section id="active-processes" class="py-16 bg-blue-50/30 border-y border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Convocatorias Vigentes</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Entérate de las fechas límite, precios de derecho de examen, temarios y la asignación de vacantes
                    disponibles para este período.
                </p>
            </div>

            @if ($exams->count() > 0)
                <div class="space-y-12">
                    @foreach ($exams as $exam)
                        <div class="bg-white rounded-3xl shadow-xl border border-blue-100 overflow-hidden">
                            <div
                                class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <span
                                        class="inline-block text-xs font-bold tracking-widest text-blue-200 uppercase bg-blue-900/40 py-1 px-3 rounded-full mb-1">
                                        Período Académico
                                    </span>
                                    <h3 class="text-2xl font-extrabold text-white">{{ $exam->activity }} - {{ $exam->period }}</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="bg-blue-100 text-blue-800 text-sm font-extrabold px-4 py-2 rounded-full flex items-center gap-1.5 shadow-sm">
                                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                                        Inscripciones Abiertas
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                                    {{-- Detalles Principales --}}
                                    <div class="lg:col-span-7 space-y-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                            <div
                                                class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex flex-col justify-center">
                                                <span
                                                    class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-1">Costo
                                                    Examen</span>
                                                <span class="text-2xl font-black text-blue-900">S/
                                                    {{ number_format($exam->price, 2) }}</span>
                                            </div>
                                            <div
                                                class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex flex-col justify-center col-span-2">
                                                <span
                                                    class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-1">Fechas
                                                    de Inscripción</span>
                                                <span class="text-base font-bold text-gray-700 flex items-center gap-2">
                                                    <i class="bi bi-calendar-range text-blue-500"></i>
                                                    {{ \Carbon\Carbon::parse($exam->inscription_start_date)->format('d/m/Y') }}
                                                    al
                                                    {{ \Carbon\Carbon::parse($exam->inscription_end_date)->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="border-t border-gray-100 pt-6">
                                            <h4 class="text-lg font-bold text-blue-900 mb-2 flex items-center gap-2">
                                                <i class="bi bi-calendar2-check text-blue-500"></i>
                                                Día de la Evaluación
                                            </h4>
                                            <p class="text-base text-gray-700 font-semibold pl-7">
                                                El examen de admisión general se realizará el día:
                                                <span
                                                    class="text-blue-700 font-bold underline">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}</span>
                                            </p>
                                        </div>


                                        @if ($exam->url_pdf)
                                            <div class="pt-4">
                                                <a href="{{ asset('storage/' . $exam->url_pdf) }}" target="_blank"
                                                    class="inline-flex items-center px-6 py-3.5 text-base font-bold text-blue-700 border-2 border-blue-500 hover:bg-blue-500 hover:text-white rounded-xl transition shadow-sm">
                                                    <i class="bi bi-file-earmark-pdf-fill mr-2 text-xl"></i>
                                                    Descargar Prospecto de Admisión (PDF)
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Distribución de Vacantes --}}
                                    <div class="lg:col-span-5 bg-blue-50/30 border border-blue-100 p-6 rounded-2xl">
                                        <div class="flex items-center justify-between border-b border-blue-100 pb-3 mb-4">
                                            <h4
                                                class="text-lg font-bold text-blue-900 uppercase tracking-wider flex items-center gap-2">
                                                <i class="bi bi-pie-chart-fill text-blue-500"></i>
                                                Distribución de Vacantes
                                            </h4>
                                            <span
                                                class="bg-blue-600 text-white text-sm font-extrabold px-3 py-1 rounded-full">
                                                Total: {{ $exam->total_vacancies }}
                                            </span>
                                        </div>

                                        <div class="space-y-3">
                                            @foreach ($exam->admissionDetail as $detail)
                                                @if ($detail->program)
                                                    <div
                                                        class="flex justify-between items-center bg-white p-4 rounded-xl border border-blue-50 shadow-sm hover:border-blue-200 transition-colors">
                                                        <span class="text-sm font-bold text-gray-700 leading-tight pr-2">
                                                            {{ $detail->program->name }}
                                                        </span>
                                                        <span
                                                            class="flex-shrink-0 bg-blue-100 text-blue-900 text-sm font-black px-3.5 py-1.5 rounded-lg border border-blue-200">
                                                            {{ $detail->vacancies }} Vac.
                                                        </span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
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
                        Actualmente no contamos con procesos de admisión general abiertos en el sistema. Estamos trabajando
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
