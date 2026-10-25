@extends('layouts.app')
@section('title', 'Reglamento Interno Institucional — Portal del Estudiante | IESTP Francisco Vigo Caballero')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .regulation-card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .regulation-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div id="dashboard-container" class="flex w-full bg-slate-50 font-sans text-slate-900 min-h-[calc(100vh-64px)]"
    x-data="studentRegulationsApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50 relative">

        {{-- ── Encabezado Sticky ── --}}
        <header class="bg-white/95 border-b border-slate-200 sticky top-[64px] lg:top-0 z-[30] shadow-xs backdrop-blur-md">
            <div class="px-4 sm:px-6 py-3.5 sm:py-4 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()" class="text-slate-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-xl transition-colors lg:hidden" aria-label="Abrir menú">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-display flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-base shadow-sm">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                Reglamento Interno Institucional
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                <i class="bi bi-journal-text text-xs text-purple-500"></i> Normativa
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 hidden sm:block mt-0.5">
                            Normas de convivencia, régimen académico, derechos y deberes de los estudiantes del IESTP FVC.
                        </p>
                    </div>
                </div>

                {{-- Breadcrumbs --}}
                <div class="hidden sm:flex items-center text-xs font-medium text-slate-500 gap-2">
                    <a href="{{ route('biblioteca.index') }}" class="hover:text-purple-600 transition flex items-center gap-1">
                        <i class="bi bi-mortarboard"></i> Portal Estudiante
                    </a>
                    <i class="bi bi-chevron-right text-[10px] text-slate-400"></i>
                    <span class="text-purple-700 font-bold bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-100">
                        Reglamento Interno
                    </span>
                </div>
            </div>
        </header>

        {{-- ── Main Body ── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden space-y-6 max-w-7xl mx-auto w-full">

            {{-- Flash Messages --}}
            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 sm:p-5 flex items-center gap-3 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900">Información del Documento</h4>
                        <p class="text-xs sm:text-sm text-blue-700 mt-0.5">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            {{-- ── Hero Banner ── --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 lg:p-10 shadow-lg border border-slate-800">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-purple-200 border border-white/15 backdrop-blur-xs">
                        <i class="bi bi-patch-check-fill text-purple-400"></i>
                        <span>Aprobado por Resolución Directoral Institucional</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight">
                        {{ $regulation->title ?? 'Reglamento Interno (RI) — Periodo Académico 2026-2027' }}
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
                        {{ $regulation->description ?? 'El presente Reglamento Interno regula la vida institucional, las actividades académicas, formativas y administrativas, garantizando la calidad educativa, los derechos fundamentales y la convivencia armónica de la comunidad educativa del IESTP Francisco Vigo Caballero conforme a la Ley N° 30512.' }}
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        @if ($regulation && $regulation->file_path)
                            <a href="{{ Storage::url($regulation->file_path) }}" target="_blank"
                                class="py-3 px-5 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md flex items-center gap-2">
                                <i class="bi bi-download text-base"></i>
                                <span>Descargar Documento Oficial (PDF)</span>
                            </a>
                        @else
                            <a href="{{ route('admin.regulations.download') }}"
                                class="py-3 px-5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs sm:text-sm font-bold transition-all border border-white/20 flex items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-base text-purple-400"></i>
                                <span>Solicitar Copia en Secretaría</span>
                            </a>
                        @endif

                        <a href="{{ route('documentos-de-gestion', ['category' => 'RI']) }}" target="_blank"
                            class="py-3 px-5 bg-white/10 hover:bg-white/20 text-slate-200 rounded-xl text-xs sm:text-sm font-bold transition-all border border-white/10 flex items-center gap-2">
                            <i class="bi bi-box-arrow-up-right text-xs"></i>
                            <span>Ver en Portal de Transparencia</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── 5 Core Pillars of Internal Regulations ── --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <h3 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                        <i class="bi bi-bookmarks-fill text-purple-600"></i>
                        Capítulos y Disposiciones Fundamentales para el Estudiante
                    </h3>
                    <span class="text-xs text-slate-500 font-medium hidden sm:inline">Ley N° 30512</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Card 1: Derechos --}}
                    <div class="regulation-card bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-xl flex-shrink-0">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">1. Derechos del Estudiante</h4>
                                <p class="text-[11px] text-slate-500">Capítulo IV · Deberes y Derechos</p>
                            </div>
                        </div>
                        <ul class="text-xs text-slate-600 space-y-2 list-disc list-inside leading-relaxed pl-1">
                            <li>Recibir formación integral de alta calidad científica, tecnológica y humanística.</li>
                            <li>Tener acceso a la biblioteca virtual, laboratorios, talleres y aulas especializadas.</li>
                            <li>Ser evaluado con justicia, equidad y conocer oportunamente los resultados académicos.</li>
                            <li>Expresar libremente sus opiniones dentro del marco del respeto y la tolerancia.</li>
                            <li>Participar en el Consejo Estudiantil según las disposiciones vigentes.</li>
                        </ul>
                    </div>

                    {{-- Card 2: Deberes --}}
                    <div class="regulation-card bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-xl flex-shrink-0">
                                <i class="bi bi-check2-square"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">2. Deberes y Obligaciones</h4>
                                <p class="text-[11px] text-slate-500">Capítulo IV · Compromisos Éticos</p>
                            </div>
                        </div>
                        <ul class="text-xs text-slate-600 space-y-2 list-disc list-inside leading-relaxed pl-1">
                            <li>Cumplir puntualmente con los horarios de clases, prácticas preprofesionales y evaluaciones.</li>
                            <li>Mantener probidad académica (prohibición estricta de plagio en exámenes y proyectos).</li>
                            <li>Cuidar el mobiliario, equipos, infraestructura y material bibliográfico del campus.</li>
                            <li>Tratar con respeto a docentes, autoridades, compañeros y personal administrativo.</li>
                            <li>Portar su carné o identificación estudiantil al ingresar al recinto institucional.</li>
                        </ul>
                    </div>

                    {{-- Card 3: Régimen Académico --}}
                    <div class="regulation-card bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-xl flex-shrink-0">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">3. Régimen Académico y Calificación</h4>
                                <p class="text-[11px] text-slate-500">Capítulo V · Evaluación Modular</p>
                            </div>
                        </div>
                        <ul class="text-xs text-slate-600 space-y-2 list-disc list-inside leading-relaxed pl-1">
                            <li>El sistema de evaluación es vigesimal: de 0 a 20 puntos.</li>
                            <li>La nota mínima aprobatoria para cualquier unidad didáctica o módulo es de trece (13).</li>
                            <li>La asistencia mínima a clases es del 70%; el 30% o más de inasistencias inhabilita la unidad.</li>
                            <li>El estudiante tiene derecho a un proceso de recuperación académica según el calendario.</li>
                        </ul>
                    </div>

                    {{-- Card 4: Titulación y Certificados --}}
                    <div class="regulation-card bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-xl flex-shrink-0">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">4. Certificación Modular y Grados</h4>
                                <p class="text-[11px] text-slate-500">Capítulo VII · Egresados y Títulos</p>
                            </div>
                        </div>
                        <ul class="text-xs text-slate-600 space-y-2 list-disc list-inside leading-relaxed pl-1">
                            <li>Al concluir satisfactoriamente cada módulo, se emite el certificado modular correspondiente.</li>
                            <li>Para obtener el Título Profesional Técnico, se requiere haber aprobado todos los módulos del programa.</li>
                            <li>Acreditar el cumplimiento de Experiencias Formativas en Situaciones Reales de Trabajo (EFSRT).</li>
                            <li>Acreditar el conocimiento de idioma extranjero y herramientas informáticas requeridas.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ── Institutional Assistance Card ── --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="space-y-1">
                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="bi bi-question-circle-fill text-purple-600"></i>
                        ¿Tienes dudas sobre la aplicación del Reglamento Interno?
                    </h4>
                    <p class="text-xs text-slate-500">
                        Comunícate con la Secretaría Académica o presenta tu consulta formal mediante la Mesa de Partes Virtual.
                    </p>
                </div>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('mesa-de-partes') }}"
                        class="py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5">
                        <i class="bi bi-inbox-fill"></i>
                        <span>Mesa de Partes Virtual</span>
                    </a>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studentRegulationsApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            },
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        }));
    });
</script>
@endpush
