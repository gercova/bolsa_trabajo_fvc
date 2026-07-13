@extends('layouts.app')
@section('title', 'Matrículas — IESTP Francisco Vigo Caballero')
@push('styles')
    {{-- SEO Optimization Meta Tags --}}
    <meta name="description"
        content="Información oficial sobre el proceso de matrícula ordinaria y extraordinaria del IESTP Francisco Vigo Caballero. Consulta calendarios, requisitos de admisión y cronogramas académicos por áreas.">
    <meta name="keywords"
        content="matricula, fvc, instituto francisco vigo caballero, matriculas 2026, cronograma de matricula, educacion superior tecnica, trujillo">
    <meta name="robots" content="index, follow">
@endpush

@section('content')
    {{-- Hero Section (Centered & Premium Glassmorphic Design) --}}
    <section
        class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 text-white overflow-hidden py-20 lg:py-32">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-500/20 via-transparent to-transparent">
        </div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-4xl mx-auto">
                Matrícula Académica y <span class="text-blue-300">Registro de Cursos</span>
            </h1>
            <p class="text-xl sm:text-2xl text-blue-100/90 max-w-3xl mx-auto leading-relaxed font-medium">
                Accede a los cronogramas oficiales, requisitos reglamentarios y canales de atención de matrícula para
                estudiantes nuevos y regulares de nuestro instituto.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-6">
                <a href="#cronograma" id="btn-hero-cronograma"
                    class="inline-flex items-center justify-center px-8 py-4.5 text-lg font-black text-blue-950 bg-white hover:bg-blue-50 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="bi bi-clock-history mr-2.5 text-xl"></i>
                    Ver Cronogramas de Matrícula
                </a>
                <a href="#guia-pasos" id="btn-hero-guia"
                    class="inline-flex items-center justify-center px-8 py-4.5 text-lg font-black text-white border-2 border-blue-400/30 hover:bg-white/10 rounded-xl transition-all">
                    <i class="bi bi-compass mr-2.5 text-xl"></i>
                    Guía Paso a Paso
                </a>
            </div>
        </div>
    </section>

    {{-- Guía Paso a Paso (Unique Step Concept Layout) --}}
    <section id="guia-pasos" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-blue-600 text-base font-extrabold uppercase tracking-widest">¿Cómo matricularte?</span>
                <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Paso a Paso del Proceso</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Paso 1 --}}
                <div
                    class="relative bg-blue-50/40 p-8 rounded-2xl border border-blue-100 flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        <div class="text-5xl font-black text-blue-200 mb-4">01</div>
                        <h3 class="text-xl font-extrabold text-blue-950 mb-3">Pago de Tasas</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Efectúa el abono por derecho de matrícula y/o seguro estudiantil según corresponda en la cuenta
                            bancaria de la institución.
                        </p>
                    </div>
                </div>
                {{-- Paso 2 --}}
                <div
                    class="relative bg-blue-50/40 p-8 rounded-2xl border border-blue-100 flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        <div class="text-5xl font-black text-blue-200 mb-4">02</div>
                        <h3 class="text-xl font-extrabold text-blue-950 mb-3">Expediente Requisitos</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Organiza y digitaliza los documentos de inscripción (DNI, fotos, certificados) solicitados de
                            forma obligatoria.
                        </p>
                    </div>
                </div>
                {{-- Paso 3 --}}
                <div
                    class="relative bg-blue-50/40 p-8 rounded-2xl border border-blue-100 flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        <div class="text-5xl font-black text-blue-200 mb-4">03</div>
                        <h3 class="text-xl font-extrabold text-blue-950 mb-3">Ficha de Registro</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Completa el formulario de matrícula especificando el programa de estudios e ingresa el voucher
                            correspondiente.
                        </p>
                    </div>
                </div>
                {{-- Paso 4 --}}
                <div
                    class="relative bg-blue-50/40 p-8 rounded-2xl border border-blue-100 flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        <div class="text-5xl font-black text-blue-200 mb-4">04</div>
                        <h3 class="text-xl font-extrabold text-blue-950 mb-3">Conformidad</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Recibe la constancia de matrícula firmada digitalmente y el acceso a tus horarios y aulas
                            virtuales institucionales.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cronograma en Línea de Tiempo (Unique Timeline Layout) --}}
    <section id="cronograma" class="py-20 bg-blue-50/30 border-y border-blue-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-blue-600 text-base font-extrabold uppercase tracking-widest">Calendarios</span>
                <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Fechas Programadas</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Cronogramas oficiales vigentes coordinados por la jefatura y secretaría académica del instituto.
                </p>
            </div>

            @if ($enrollments->count() > 0)
                <div class="relative border-l-4 border-blue-200 ml-4 md:ml-32 space-y-12">
                    @foreach ($enrollments as $index => $item)
                        <div class="relative pl-8 md:pl-12 group">
                            {{-- Timeline dot --}}
                            <div
                                class="absolute -left-[14px] top-1.5 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow group-hover:scale-110 transition-transform">
                            </div>

                            {{-- Timeline Card --}}
                            <div
                                class="bg-white rounded-2xl border border-blue-100 shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                <div
                                    class="bg-gradient-to-r from-blue-800 to-blue-700 px-6 py-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="bg-blue-900/60 text-blue-200 text-sm font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                            {{ $item->type }}
                                        </span>
                                        @if ($item->period)
                                            <span class="text-blue-100 text-sm font-bold">
                                                Período: {{ $item->period }}
                                            </span>
                                        @endif
                                    </div>
                                    <span
                                        class="bg-emerald-500/25 border border-emerald-400/30 text-emerald-200 text-sm font-extrabold px-3 py-1 rounded-full flex items-center gap-1.5 self-start sm:self-auto">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Semanas de Registro
                                    </span>
                                </div>

                                <div class="p-6 sm:p-8 space-y-6">
                                    <div
                                        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                        <div class="space-y-2">
                                            <span
                                                class="text-blue-600 text-sm font-extrabold uppercase tracking-widest block">Actividad</span>
                                            <h3 class="text-2xl font-black text-blue-950 leading-tight">
                                                {{ $item->activity }}
                                            </h3>
                                        </div>

                                        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                                            <div
                                                class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center gap-3">
                                                <i class="bi bi-calendar3 text-blue-600 text-2xl"></i>
                                                <div>
                                                    <span
                                                        class="text-sm font-bold text-blue-800 uppercase tracking-wider block">Inicio</span>
                                                    <span class="text-base font-extrabold text-gray-700">
                                                        {{ \Carbon\Carbon::parse($item->inscription_start_date)->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center gap-3">
                                                <i class="bi bi-calendar3-range text-blue-600 text-2xl"></i>
                                                <div>
                                                    <span
                                                        class="text-sm font-bold text-blue-800 uppercase tracking-wider block">Límite</span>
                                                    <span class="text-base font-extrabold text-gray-700">
                                                        {{ \Carbon\Carbon::parse($item->inscription_end_date)->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="border-t border-gray-100 pt-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        @if ($item->area)
                                            <div
                                                class="flex items-center gap-3 bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl">
                                                <i class="bi bi-building text-blue-600 text-xl"></i>
                                                <div>
                                                    <span
                                                        class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Área
                                                        a Cargo</span>
                                                    <span
                                                        class="text-sm font-bold text-gray-700">{{ $item->area->name }}</span>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                                            @if ($item->url_pdf)
                                                <a href="{{ asset('storage/' . $item->url_pdf) }}" target="_blank"
                                                    id="btn-pdf-{{ $item->id }}"
                                                    class="inline-flex items-center justify-center px-5 py-3 text-base font-bold text-blue-700 border-2 border-blue-500 hover:bg-blue-500 hover:text-white rounded-xl transition">
                                                    <i class="bi bi-file-pdf mr-2 text-lg"></i>
                                                    Bases y Formularios (PDF)
                                                </a>
                                            @endif
                                            @if ($enterprise->whatsapp_link)
                                                <a href="{{ $enterprise->whatsapp_link }}" target="_blank"
                                                    id="btn-whats-{{ $item->id }}"
                                                    class="inline-flex items-center justify-center px-5 py-3 text-base font-bold bg-green-500 hover:bg-green-600 text-white rounded-xl transition shadow">
                                                    <i class="bi bi-whatsapp mr-2 text-lg"></i>
                                                    Consultar Área
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($item->admissionDetail->count() > 0)
                                        <div class="border-t border-gray-100 pt-6">
                                            <span
                                                class="text-blue-800 text-sm font-extrabold uppercase tracking-wider block mb-3">Vacantes/Cupos
                                                Máximos</span>
                                            <div class="flex flex-wrap gap-3">
                                                @foreach ($item->admissionDetail as $detail)
                                                    @if ($detail->program)
                                                        <span
                                                            class="bg-blue-50 text-blue-900 border border-blue-100 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                            {{ $detail->program->name }}: <strong
                                                                class="font-extrabold ml-1">{{ $detail->vacancies }}</strong>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-blue-100 shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div
                        class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-calendar-x text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">No hay cronogramas activos</h3>
                    <p class="text-base text-gray-600 leading-relaxed mb-6">
                        Actualmente no contamos con cronogramas de matrícula activos para el período escolar vigente. Ponte
                        en contacto con secretaría académica.
                    </p>
                    <a href="#contact" id="btn-cron-contact"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-xl transition shadow">
                        <i class="bi bi-headset mr-2 text-lg"></i>
                        Contactar Soporte
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Requisitos de Matrícula (Cards Layout) --}}
    <section id="requirements" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-blue-600 text-base font-extrabold uppercase tracking-widest">Documentación</span>
                <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Requisitos de Matrícula</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                    Asegúrate de preparar y enviar la documentación correspondiente a fin de validar satisfactoriamente tu
                    matrícula académica.
                </p>
            </div>

            @if ($requirements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach ($requirements as $index => $req)
                        <div
                            class="bg-blue-50/20 p-6.5 rounded-2xl border border-blue-100 flex gap-4 transition hover:bg-blue-50/50 hover:shadow-sm">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center">
                                {{ sprintf('%02d', $index + 1) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-blue-950 mb-1">Requisito Obligatorio</h4>
                                <p class="text-gray-700 text-base leading-relaxed font-semibold">
                                    {{ $req->requirement }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-blue-50/50 p-8 rounded-2xl border border-blue-100 max-w-2xl mx-auto text-center">
                    <p class="text-lg text-blue-900 font-semibold">
                        Los documentos requeridos se detallan en las bases o instructivos de cada cronograma.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Contacto --}}
    <section id="contact" class="py-20 bg-blue-900 text-white relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-stops))] from-blue-700/40 via-transparent to-transparent">
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
            <h2 class="text-3xl sm:text-5xl font-black">¿Dificultades en tu proceso de matrícula?</h2>
            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed font-medium">
                Ponte en contacto directo con nuestra Mesa de Ayuda Académica para orientarte en pagos, validaciones e
                inconvenientes con la plataforma.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 pt-4">
                @if ($enterprise->phone_number_1)
                    <a href="tel:{{ $enterprise->phone_number_1 }}" id="contact-tel-link"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4.5 text-base font-bold bg-white text-blue-900 hover:bg-blue-50 rounded-xl transition shadow">
                        <i class="bi bi-telephone-fill mr-2 text-xl text-blue-600"></i>
                        Llamar a Admisión: {{ $enterprise->phone_number_1 }}
                    </a>
                @endif
                @if ($enterprise->whatsapp_link)
                    <a href="{{ $enterprise->whatsapp_link }}" target="_blank" id="contact-whats-link"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4.5 text-base font-bold bg-green-500 hover:bg-green-600 text-white rounded-xl transition shadow">
                        <i class="bi bi-whatsapp mr-2 text-xl"></i>
                        Escríbenos por WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
