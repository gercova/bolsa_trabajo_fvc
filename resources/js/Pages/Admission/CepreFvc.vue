<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';

const page = usePage();

const props = defineProps({
    exams:           { type: Array, default: () => [] },
    requirements:    { type: Array, default: () => [] },
    enterprise:      { type: Object, default: () => null },
    cepreImage:      { type: Object, default: () => null },
    projectedPeriod: { type: String, default: '2027-I' },
});

// Fallback to shared enterprise if not passed explicitly
const ent = computed(() => props.enterprise || page.props.enterprise || {});

// First exam with published results
const latestResultExam = computed(() => {
    return props.exams.find(e => Boolean(e.results_pdf_url || e.results_url_pdf));
});

// Date formatting helper
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const clean = String(dateStr).split('T')[0];
    const parts = clean.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
};

// Currency formatting helper
const formatCurrency = (val) => {
    return Number(val || 0).toLocaleString('es-PE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

// Document url helpers
const getPdfUrl = (exam) => {
    if (exam.pdf_url) return exam.pdf_url;
    if (exam.url_pdf) {
        return exam.url_pdf.startsWith('http') ? exam.url_pdf : `/storage/${exam.url_pdf.replace(/^\//, '')}`;
    }
    return '';
};

const getResultsPdfUrl = (exam) => {
    if (exam.results_pdf_url) return exam.results_pdf_url;
    if (exam.results_url_pdf) {
        return exam.results_url_pdf.startsWith('http') ? exam.results_url_pdf : `/storage/${exam.results_url_pdf.replace(/^\//, '')}`;
    }
    return '';
};

const getHeroImage = computed(() => {
    if (props.cepreImage?.url) return props.cepreImage.url;
    return '/images/cepre_hero_banner.png';
});
</script>

<template>
    <Head>
        <title>CEPRE-FVC — IESTP Francisco Vigo Caballero</title>
        <meta name="description" content="Asegura tu ingreso directo al IESTP Francisco Vigo Caballero con la preparación de la CEPRE-FVC en Uchiza. Consulta vacantes, costos y cronograma oficial." />
        <meta name="keywords" content="CEPRE FVC, centro preuniversitario Uchiza, preparacion ingreso directo, admision FVC, carreras tecnicas Uchiza" />
        <meta property="og:title" content="CEPRE-FVC — Tu ingreso directo al futuro profesional" />
        <meta property="og:description" content="Prepárate con nuestra plana docente especializada y accede a vacantes exclusivas por orden de mérito." />
    </Head>

    <PublicLayout title="CEPRE-FVC">
        <!-- ═══ 1. HERO SECTION ═══════════════════════════════════════ -->
        <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6">
                        <div class="w-16 h-1 bg-sky-500 rounded-full mb-2"></div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white font-sans">
                            Tu ingreso directo al <br />
                            <span class="text-sky-400">Futuro Profesional</span>
                        </h1>
                        <p class="text-lg sm:text-xl text-slate-300 max-w-2xl leading-relaxed">
                            Asegura tu ingreso directo al IESTP Francisco Vigo Caballero. Prepárate con nuestra plana docente especializada y accede a las vacantes exclusivas asignadas por orden de mérito.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="#active-processes"
                               class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-md">
                                <i class="bi bi-calendar-event mr-2 text-lg"></i>
                                Ver Convocatorias
                            </a>
                            <a href="#requirements"
                               class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition">
                                <i class="bi bi-journal-text mr-2 text-lg"></i>
                                Requisitos de Inscripción
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5 relative">
                        <div class="relative mx-auto max-w-md lg:max-w-none">
                            <img :src="getHeroImage"
                                 alt="CEPRE IESTP Francisco Vigo Caballero"
                                 class="relative rounded-2xl shadow-xl border border-slate-700 w-full object-cover aspect-square sm:aspect-video lg:aspect-square" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 2. VENTAJAS DEL PROCESO ═══════════════════════════════ -->
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
                    <div class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                        <div class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                            <i class="bi bi-award-fill text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-3">Ingreso Directo</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Accede directamente a una de las vacantes reservadas exclusivamente para alumnos del Centro de Preparación, según su orden de mérito.
                        </p>
                    </div>

                    <div class="bg-blue-50/50 hover:bg-blue-50 border border-blue-100 p-8 rounded-2xl transition shadow-sm hover:shadow-md">
                        <div class="w-14 h-14 bg-blue-500 text-white rounded-xl flex items-center justify-center mb-6 shadow-md shadow-blue-500/20">
                            <i class="bi bi-journal-check text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900 mb-3">Temario Especializado</h3>
                        <p class="text-gray-600 text-base leading-relaxed">
                            Clases dirigidas y enfocadas con contenidos curriculares actualizados alineados a la evaluación institucional.
                        </p>
                    </div>

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

        <!-- ═══ 3. CONVOCATORIAS ACTIVAS ═══════════════════════════════ -->
        <section id="active-processes" class="py-16 bg-blue-50/30 border-y border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-extrabold uppercase tracking-wider mb-3">
                        <i class="bi bi-calendar3"></i>
                        Cronograma Proyectado {{ projectedPeriod }}
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Convocatoria y Cronograma CEPRE</h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                        Revisa a continuación la programación del ciclo proyectado {{ projectedPeriod }}, costos académicos, duración, vacantes ofertadas y publicación de resultados.
                    </p>
                </div>

                <div v-if="exams && exams.length > 0" class="space-y-12">
                    <div v-for="exam in exams" :key="exam.id"
                         class="bg-white rounded-3xl shadow-xl border border-blue-100 overflow-hidden">
                        
                        <!-- Card Header -->
                        <div class="bg-blue-700 px-6 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <span class="inline-block text-xs font-bold tracking-widest text-blue-200 uppercase bg-blue-900/40 py-1 px-3 rounded-full mb-1">
                                    Período Proyectado
                                </span>
                                <h3 class="text-2xl font-extrabold text-white">{{ exam.activity }} - {{ exam.period }}</h3>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-100 text-blue-800 text-sm font-extrabold px-4 py-2 rounded-full flex items-center gap-1.5 shadow-sm">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                                    Convocatoria Abierta
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 sm:p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                                <!-- Col Left: Details & Fees -->
                                <div class="lg:col-span-7 space-y-6">
                                    <!-- Fees Grid -->
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                        <!-- Inscripción -->
                                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                            <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                <i class="bi bi-cash-coin text-sm"></i>
                                                <span class="text-[11px] font-bold uppercase tracking-wider">Inscripción</span>
                                            </div>
                                            <span class="text-xl font-black text-blue-900">S/ {{ formatCurrency(exam.price) }}</span>
                                        </div>

                                        <!-- Matrícula -->
                                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                            <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                <i class="bi bi-receipt text-sm"></i>
                                                <span class="text-[11px] font-bold uppercase tracking-wider">Matrícula</span>
                                            </div>
                                            <span class="text-xl font-black text-blue-900">S/ {{ formatCurrency(exam.tuition_fee) }}</span>
                                        </div>

                                        <!-- Mensualidad -->
                                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                            <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                <i class="bi bi-calendar-check text-sm"></i>
                                                <span class="text-[11px] font-bold uppercase tracking-wider">Mensualidad</span>
                                            </div>
                                            <span class="text-xl font-black text-blue-900">S/ {{ formatCurrency(exam.monthly_fee) }}</span>
                                        </div>

                                        <!-- Duración -->
                                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center">
                                            <div class="flex items-center gap-1.5 text-blue-700 mb-1">
                                                <i class="bi bi-hourglass-split text-sm"></i>
                                                <span class="text-[11px] font-bold uppercase tracking-wider">Duración</span>
                                            </div>
                                            <span class="text-sm font-black text-blue-900 leading-tight">
                                                {{ exam.duration || '3 Meses' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Fechas -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">
                                                <i class="bi bi-calendar-range text-blue-600 mr-1"></i> Período de Inscripción
                                            </span>
                                            <p class="text-sm font-extrabold text-slate-800">
                                                <template v-if="exam.inscription_start_date && exam.inscription_end_date">
                                                    {{ formatDate(exam.inscription_start_date) }} al {{ formatDate(exam.inscription_end_date) }}
                                                </template>
                                                <template v-else>
                                                    <span class="text-slate-400 font-normal">Por confirmar</span>
                                                </template>
                                            </p>
                                        </div>

                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">
                                                <i class="bi bi-calendar2-check text-red-600 mr-1"></i> Fecha de Evaluación Final
                                            </span>
                                            <p class="text-sm font-extrabold text-slate-800">
                                                <template v-if="exam.exam_date">
                                                    {{ formatDate(exam.exam_date) }}
                                                </template>
                                                <template v-else>
                                                    <span class="text-slate-400 font-normal">Por confirmar</span>
                                                </template>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Indicaciones para el Postulante -->
                                    <div v-if="exam.indications"
                                         class="p-5 bg-amber-50/70 border border-amber-200/80 rounded-2xl space-y-2">
                                        <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="bi bi-info-circle-fill text-amber-600 text-sm"></i>
                                            Indicaciones para el Postulante
                                        </h4>
                                        <p class="text-xs text-amber-900 leading-relaxed font-medium whitespace-pre-line">
                                            {{ exam.indications }}
                                        </p>
                                    </div>

                                    <!-- Documentos Adjuntos -->
                                    <div class="pt-2 flex flex-wrap gap-3">
                                        <a v-if="getPdfUrl(exam)"
                                           :href="getPdfUrl(exam)"
                                           target="_blank"
                                           class="inline-flex items-center px-5 py-3 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-xl transition shadow-xs">
                                            <i class="bi bi-file-earmark-pdf-fill mr-2 text-base text-red-500"></i>
                                            Descargar Bases y Prospecto (PDF)
                                        </a>

                                        <a v-if="getResultsPdfUrl(exam)"
                                           :href="getResultsPdfUrl(exam)"
                                           target="_blank"
                                           class="inline-flex items-center px-5 py-3 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-600 hover:text-white rounded-xl transition shadow-xs">
                                            <i class="bi bi-award-fill mr-2 text-base text-emerald-600"></i>
                                            Ver Resultados Oficiales (PDF)
                                        </a>
                                    </div>
                                </div>

                                <!-- Col Right: Vacantes -->
                                <div class="lg:col-span-5 bg-blue-50/30 border border-blue-100 p-6 rounded-2xl flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between border-b border-blue-100 pb-3 mb-4">
                                            <h4 class="text-sm font-bold text-blue-900 uppercase tracking-wider flex items-center gap-2">
                                                <i class="bi bi-pie-chart-fill text-blue-500"></i>
                                                Vacantes Ofertadas
                                            </h4>
                                            <span class="bg-blue-600 text-white text-xs font-extrabold px-3 py-1 rounded-full">
                                                Total: {{ exam.total_vacancies }}
                                            </span>
                                        </div>

                                        <div class="space-y-2.5">
                                            <template v-if="(exam.admission_detail || exam.admissionDetail || []).length > 0">
                                                <div v-for="detail in (exam.admission_detail || exam.admissionDetail || [])" :key="detail.id"
                                                     class="flex justify-between items-center bg-white p-3.5 rounded-xl border border-blue-50 shadow-xs hover:border-blue-200 transition-colors">
                                                    <span class="text-xs font-bold text-gray-700 leading-tight pr-2">
                                                        {{ detail.program?.name }}
                                                    </span>
                                                    <span class="flex-shrink-0 bg-blue-100 text-blue-900 text-xs font-black px-3 py-1 rounded-lg border border-blue-200">
                                                        {{ detail.vacancies }} Vac.
                                                    </span>
                                                </div>
                                            </template>
                                            <p v-else class="text-xs text-gray-500 italic text-center py-4">
                                                Vacantes en proceso de asignación.
                                            </p>
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
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-3xl border border-blue-100 shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-calendar-x-fill text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">No hay convocatorias vigentes</h3>
                    <p class="text-base text-gray-600 leading-relaxed mb-6">
                        Actualmente no contamos con procesos de admisión CEPRE activos para el ciclo {{ projectedPeriod }}. Estamos trabajando en la planificación académica de la próxima convocatoria.
                    </p>
                    <a href="#contact"
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-xl transition shadow-md">
                        <i class="bi bi-chat-left-text-fill mr-2"></i>
                        Consultar Próximas Fechas
                    </a>
                </div>
            </div>
        </section>

        <!-- ═══ 4. PUBLICACIÓN DE RESULTADOS ══════════════════════════ -->
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
                    <div v-if="latestResultExam && getResultsPdfUrl(latestResultExam)"
                         class="bg-emerald-800 text-white rounded-3xl p-8 sm:p-10 shadow-xl relative overflow-hidden border border-emerald-700">
                        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="space-y-4 text-center md:text-left">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/30 text-emerald-200 text-xs font-extrabold uppercase tracking-wider border border-emerald-400/30">
                                    <i class="bi bi-check-circle-fill"></i> Resultados Publicados
                                </span>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                                    Cuadro de Méritos y Resultados {{ latestResultExam.period }}
                                </h3>
                                <p class="text-sm text-emerald-100 max-w-xl leading-relaxed">
                                    El archivo oficial contiene la relación de ingresantes por orden de mérito y puntajes obtenidos en el proceso de evaluación CEPRE.
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row md:flex-col gap-3 w-full md:w-auto flex-shrink-0">
                                <a :href="getResultsPdfUrl(latestResultExam)" target="_blank"
                                   class="inline-flex items-center justify-center px-6 py-3.5 bg-white hover:bg-emerald-50 text-emerald-950 font-extrabold text-sm rounded-xl transition shadow-lg hover:shadow-xl gap-2">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-600 text-lg"></i>
                                    <span>Ver Resultados (PDF)</span>
                                </a>
                                <a :href="getResultsPdfUrl(latestResultExam)" download target="_blank"
                                   class="inline-flex items-center justify-center px-6 py-3 bg-emerald-700/60 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl border border-emerald-500/40 transition gap-2">
                                    <i class="bi bi-download"></i>
                                    <span>Descargar Documento</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div v-else class="bg-slate-50 border border-slate-200 rounded-3xl p-8 sm:p-12 text-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto text-2xl">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Resultados en Espera de Publicación</h3>
                        <p class="text-sm text-slate-600 max-w-lg mx-auto leading-relaxed">
                            Los resultados oficiales del ciclo CEPRE {{ projectedPeriod }} y el cuadro general de méritos serán publicados en este espacio una vez culminada la jornada de evaluación.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-200/70 text-slate-700 text-xs font-semibold">
                                <i class="bi bi-clock-history text-slate-500"></i>
                                Se publicará al concluir la fecha de examen
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 5. REQUISITOS DE INSCRIPCIÓN ══════════════════════════ -->
        <section id="requirements" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900">Requisitos de Inscripción</h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                        Para postular a través de nuestra modalidad CEPRE, asegúrate de cumplir y presentar la siguiente documentación reglamentaria:
                    </p>
                </div>

                <div v-if="requirements && requirements.length > 0"
                     class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <div v-for="req in requirements" :key="req.id"
                         class="flex items-start gap-4 bg-blue-50/30 p-6 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="bi bi-check2-square text-xl font-black"></i>
                        </div>
                        <div>
                            <p class="text-gray-700 text-base font-semibold leading-relaxed">
                                {{ req.requirement }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else class="bg-blue-50/50 p-8 rounded-2xl border border-blue-100 max-w-2xl mx-auto text-center">
                    <p class="text-lg text-blue-900 font-semibold">
                        Los requisitos detallados del proceso de admisión CEPRE se especifican en el prospecto de admisión correspondiente.
                    </p>
                </div>
            </div>
        </section>

        <!-- ═══ 6. CTA CONTACTO ═══════════════════════════════════════ -->
        <section id="contact" class="py-16 bg-blue-900 text-white relative overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
                <h2 class="text-3xl sm:text-4xl font-extrabold">¿Tienes alguna duda sobre el proceso?</h2>
                <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                    Nuestra Oficina de Admisiones está lista para brindarte toda la información que necesitas sobre pre-inscripciones, vacantes y formas de pago.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-6 pt-4">
                    <a v-if="ent.phone_number_1"
                       :href="`tel:${ent.phone_number_1}`"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-white text-blue-900 hover:bg-blue-50 rounded-xl transition shadow-lg">
                        <i class="bi bi-telephone-fill mr-2 text-xl text-blue-600"></i>
                        Llamar a Admisión: {{ ent.phone_number_1 }}
                    </a>

                    <a v-if="ent.whatsapp_link"
                       :href="ent.whatsapp_link"
                       target="_blank"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-green-500 hover:bg-green-600 text-white rounded-xl transition shadow-lg">
                        <i class="bi bi-whatsapp mr-2 text-xl"></i>
                        Escríbenos por WhatsApp
                    </a>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
