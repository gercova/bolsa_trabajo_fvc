<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    phases: {
        type: Array,
        default: () => [],
    },
    currentPhase: {
        type: Object,
        default: null,
    },
    cbcPhase: {
        type: Object,
        default: null,
    },
    totalPhases: {
        type: Number,
        default: 5,
    },
    completedPhases: {
        type: Number,
        default: 0,
    },
    globalProgress: {
        type: Number,
        default: 0,
    },
});

// Interactive phase selection
const selectedPhaseId = ref(props.currentPhase?.id ?? (props.phases[0]?.id ?? 1));

const selectedPhase = computed(() => {
    return props.phases.find((p) => p.id === selectedPhaseId.value) || props.currentPhase || props.phases[0] || {};
});

// Calculate stepper progress bar percentage
const stepperWidth = computed(() => {
    if (!props.currentPhase || props.totalPhases === 0) return 20;
    return Math.min(100, Math.max(10, ((props.currentPhase.phase_number - 0.5) / props.totalPhases) * 100));
});

// Circular gauge stroke calculations
const radius = 54;
const circumference = 2 * Math.PI * radius; // 339.29
const activeGaugeOffset = computed(() => {
    const pct = selectedPhase.value?.progress_percentage ?? 0;
    return circumference - (circumference * pct) / 100;
});

// 7 CBCs fallback definition if milestones array is empty
const defaultCbcs = [
    { cbc_number: 'CBC I', name: 'Gestión Institucional', description: 'Instrumentos de gestión articulados, estructura organizativa y personería jurídica institucional.', progress: 100, status: 'completed' },
    { cbc_number: 'CBC II', name: 'Gestión Académica y Programas de Estudio', description: 'Planes de estudio pertinentes, enfoque por competencias y créditos adaptados al Catálogo Nacional de la Oferta Formativa.', progress: 100, status: 'completed' },
    { cbc_number: 'CBC III', name: 'Infraestructura, Equipamiento y Mobiliario', description: 'Ambientes pedagógicos, talleres especializados, conectividad, laboratorios y servicios básicos óptimos.', progress: 100, status: 'completed' },
    { cbc_number: 'CBC IV', name: 'Plana Docente Calificada', description: 'Docentes idóneos con experiencia laboral técnica y pedagógica, asegurando un porcentaje mayoritario de dedicación continua.', progress: 100, status: 'completed' },
    { cbc_number: 'CBC V', name: 'Previsión Económica y Financiera', description: 'Presupuesto institucional y sostenibilidad operativa garantizada para la formación profesional.', progress: 100, status: 'completed' },
    { cbc_number: 'CBC VI', name: 'Servicios de Apoyo al Estudiante', description: 'Bienestar estudiantil, tópico de salud, apoyo psicopedagógico, biblioteca física y virtual, y actividades extracurriculares.', progress: 100, status: 'completed' },
    { cbc_number: 'CBC VII', name: 'Bolsa de Trabajo y Seguimiento al Egresado', description: 'Mecanismos formales de inserción laboral, convenios de empleabilidad y vinculación con el sector productivo regional.', progress: 100, status: 'completed' },
];

const cbcList = computed(() => {
    if (props.cbcPhase && Array.isArray(props.cbcPhase.milestones) && props.cbcPhase.milestones.length > 0) {
        return props.cbcPhase.milestones;
    }
    return defaultCbcs;
});

// FAQ Accordion State
const openFaq = ref(1);
const toggleFaq = (index) => {
    openFaq.value = openFaq.value === index ? null : index;
};

// SEO Meta Data
const pageTitle = 'Licenciamiento Institucional — IESTP Francisco Vigo Caballero';
const pageDescription = 'Conoce las 5 fases del proceso de Licenciamiento Institucional del IESTP Francisco Vigo Caballero en Uchiza. Estado actual (P), cumplimiento de las 7 Condiciones Básicas de Calidad (CBC) según la Ley N° 30512 y RVM N° 276-2019-MINEDU.';
</script>

<template>
    <PublicLayout>
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="pageDescription" />
            <meta name="keywords" content="licenciamiento institucional, CBC, condiciones basicas de calidad, IESTP Francisco Vigo Caballero, MINEDU, Uchiza, san martin, educacion tecnologica superior, ley 30512, etapas de licenciamiento" />
            <meta property="og:type" content="website" />
            <meta property="og:title" :content="pageTitle" />
            <meta property="og:description" :content="pageDescription" />
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" :content="pageTitle" />
            <meta name="twitter:description" :content="pageDescription" />
        </Head>

        <div class="min-h-screen bg-slate-50">
            <!-- HERO SECTION -->
            <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center space-y-6 max-w-4xl mx-auto">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-300 text-xs sm:text-sm font-semibold tracking-wide backdrop-blur-md">
                            <span class="flex h-2.5 w-2.5 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                            </span>
                            Proceso Oficial MINEDU — Ley N° 30512 &amp; RVM N° 276-2019-MINEDU
                        </div>

                        <!-- Hero Heading -->
                        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-tight text-white">
                            Licenciamiento <span class="text-sky-400">Institucional</span>
                        </h1>

                        <p class="text-base sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                            Monitoreo en tiempo real de las <strong class="text-white font-semibold">5 fases oficiales</strong> del procedimiento de licenciamiento y la adecuación de las <strong class="text-white font-semibold">7 Condiciones Básicas de Calidad (CBC)</strong> del IESTP "Francisco Vigo Caballero".
                        </p>

                        <!-- Key Stats Row -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 pt-6 max-w-4xl mx-auto text-left">
                            <!-- Stat 1: Etapa Actual -->
                            <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 relative overflow-hidden group hover:border-amber-400/40 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Etapa Actual</span>
                                    <span class="px-2 py-0.5 text-xs font-extrabold rounded-md bg-amber-500 text-slate-950 animate-pulse">(P)</span>
                                </div>
                                <p class="text-base sm:text-lg font-bold text-white mt-1 truncate">
                                    Fase {{ currentPhase?.phase_number ?? 1 }}: {{ currentPhase?.code || 'CBC-01' }}
                                </p>
                                <p class="text-[11px] text-amber-300/90 truncate mt-0.5">En Proceso Activo</p>
                            </div>

                            <!-- Stat 2: Avance Global -->
                            <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 hover:border-sky-400/40 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Avance Global</span>
                                    <i class="bi bi-graph-up-arrow text-sky-400 text-sm"></i>
                                </div>
                                <p class="text-xl sm:text-2xl font-black text-sky-400 mt-1">
                                    {{ globalProgress }}%
                                </p>
                                <div class="w-full bg-white/10 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="bg-sky-500 h-full rounded-full transition-all duration-1000" :style="{ width: `${globalProgress}%` }"></div>
                                </div>
                            </div>

                            <!-- Stat 3: Total Fases -->
                            <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 hover:border-indigo-400/40 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Fases Totales</span>
                                    <i class="bi bi-layers-fill text-indigo-400 text-sm"></i>
                                </div>
                                <p class="text-xl sm:text-2xl font-black text-white mt-1">
                                    {{ totalPhases || 5 }} <span class="text-xs font-normal text-slate-400">Etapas MINEDU</span>
                                </p>
                                <p class="text-[11px] text-slate-300 mt-0.5">{{ completedPhases }} Culminadas &bull; {{ (totalPhases || 5) - completedPhases }} Restantes</p>
                            </div>

                            <!-- Stat 4: Matriz CBC -->
                            <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 hover:border-emerald-400/40 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Condiciones CBC</span>
                                    <i class="bi bi-shield-check text-emerald-400 text-sm"></i>
                                </div>
                                <p class="text-xl sm:text-2xl font-black text-emerald-400 mt-1">
                                    7 / 7 <span class="text-xs font-normal text-slate-400">CBCs</span>
                                </p>
                                <p class="text-[11px] text-emerald-300/90 truncate mt-0.5">En Adecuación 100%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 1: 5-PHASE ROADMAP STEPPER -->
            <section class="py-14 sm:py-20 bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    <div class="text-center max-w-3xl mx-auto space-y-3">
                        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            Ruta del Licenciamiento
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            Fases del Proceso y Estado Actual <span class="text-amber-600 font-black">(P)</span>
                        </h2>
                        <p class="text-sm sm:text-base text-slate-600">
                            El proceso de licenciamiento institucional consta de 5 etapas consecutivas evaluadas por el Ministerio de Educación. Haz clic en una fase para visualizar sus detalles:
                        </p>
                    </div>

                    <!-- Stepper Timeline -->
                    <div class="relative">
                        <!-- Desktop Connecting Line -->
                        <div class="hidden lg:block absolute top-1/2 -translate-y-6 left-12 right-12 h-1 bg-slate-200 z-0">
                            <div class="h-full bg-emerald-500 transition-all duration-700" :style="{ width: `${stepperWidth}%` }"></div>
                        </div>

                        <!-- Steps Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 relative z-10">
                            <div
                                v-for="phase in phases"
                                :key="phase.id"
                                @click="selectedPhaseId = phase.id"
                                class="cursor-pointer transition-all duration-300 rounded-3xl p-5 border flex flex-col justify-between text-left group relative"
                                :class="[
                                    selectedPhaseId === phase.id
                                        ? 'ring-2 ring-indigo-500 shadow-xl bg-indigo-50/40 border-indigo-300 -translate-y-1'
                                        : phase.is_current || (currentPhase && currentPhase.id === phase.id)
                                        ? 'bg-amber-50/70 border-amber-400 shadow-md shadow-amber-500/10'
                                        : phase.status === 'completed'
                                        ? 'bg-emerald-50/40 border-emerald-300 hover:shadow-md'
                                        : 'bg-white border-slate-200 hover:border-indigo-300 hover:shadow-md'
                                ]"
                            >
                                <!-- Step Number & Tag Badge -->
                                <div class="flex items-center justify-between gap-2">
                                    <div
                                        class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg transition-transform duration-300 group-hover:scale-105"
                                        :class="[
                                            phase.is_current || (currentPhase && currentPhase.id === phase.id)
                                                ? 'bg-amber-500 text-slate-950 shadow-md ring-4 ring-amber-200'
                                                : phase.status === 'completed'
                                                ? 'bg-emerald-600 text-white shadow-md'
                                                : 'bg-slate-100 text-slate-600 border border-slate-300'
                                        ]"
                                    >
                                        <i v-if="phase.status === 'completed'" class="bi bi-check2 text-2xl font-bold"></i>
                                        <span v-else>{{ phase.phase_number }}</span>
                                    </div>

                                    <div>
                                        <span
                                            v-if="phase.is_current || (currentPhase && currentPhase.id === phase.id)"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-amber-500 text-slate-950 shadow-sm animate-pulse"
                                        >
                                            <i class="bi bi-play-circle-fill"></i> (P) En Proceso
                                        </span>
                                        <span
                                            v-else-if="phase.status === 'completed'"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200"
                                        >
                                            <i class="bi bi-check-circle-fill"></i> (C) Culminado
                                        </span>
                                        <span
                                            v-else-if="phase.status === 'observed'"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200"
                                        >
                                            (OBS) En Subsanación
                                        </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200"
                                        >
                                            (PTE) Pendiente
                                        </span>
                                    </div>
                                </div>

                                <!-- Phase Title & Subtitle -->
                                <div class="mt-4 space-y-1.5 flex-1">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                        Fase {{ phase.phase_number }}
                                    </span>
                                    <h3 class="text-sm font-bold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                                        {{ phase.title }}
                                    </h3>
                                    <p v-if="phase.subtitle" class="text-xs text-slate-500 line-clamp-2">
                                        {{ phase.subtitle }}
                                    </p>
                                </div>

                                <!-- Mini Progress & Indicator -->
                                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Avance</span>
                                    <span
                                        class="font-bold"
                                        :class="phase.status === 'completed' ? 'text-emerald-700' : (phase.is_current ? 'text-amber-700' : 'text-slate-600')"
                                    >
                                        {{ phase.progress_percentage }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: SELECTED / CURRENT STAGE SPOTLIGHT CARD -->
            <section class="py-12 sm:py-16 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-10 lg:p-12 shadow-xl border border-slate-800 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-10">
                            <i class="bi bi-patch-check-fill text-9xl text-amber-400"></i>
                        </div>

                        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <!-- Left Column: Phase Details -->
                            <div class="lg:col-span-8 space-y-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs font-extrabold uppercase tracking-wide">
                                    <span class="w-2 h-2 rounded-full bg-amber-400" :class="{ 'animate-ping': selectedPhase.is_current }"></span>
                                    Detalle de la Fase {{ selectedPhase.phase_number }}: {{ selectedPhase.code || 'ETAPA' }}
                                    <span v-if="selectedPhase.is_current" class="text-amber-200">(Etapa en Ejecución Actual)</span>
                                </div>

                                <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-snug">
                                    {{ selectedPhase.title }}
                                </h2>

                                <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                                    {{ selectedPhase.description || selectedPhase.subtitle }}
                                </p>

                                <!-- Metadata Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-white/10">
                                    <div v-if="selectedPhase.resolution_number" class="flex items-start gap-2.5">
                                        <i class="bi bi-file-earmark-ruled text-amber-400 text-lg mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-400 font-medium">Resolución / Norma</p>
                                            <p class="text-xs sm:text-sm font-bold text-white">{{ selectedPhase.resolution_number }}</p>
                                        </div>
                                    </div>

                                    <div v-if="selectedPhase.legal_basis" class="flex items-start gap-2.5">
                                        <i class="bi bi-journal-bookmark text-sky-400 text-lg mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-400 font-medium">Marco Normativo</p>
                                            <p class="text-xs sm:text-sm font-bold text-white truncate max-w-[200px]" :title="selectedPhase.legal_basis">
                                                {{ selectedPhase.legal_basis }}
                                            </p>
                                        </div>
                                    </div>

                                    <div v-if="selectedPhase.estimated_date" class="flex items-start gap-2.5">
                                        <i class="bi bi-calendar-event text-emerald-400 text-lg mt-0.5"></i>
                                        <div>
                                            <p class="text-xs text-slate-400 font-medium">Periodo Estimado</p>
                                            <p class="text-xs sm:text-sm font-bold text-white">{{ selectedPhase.estimated_date }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Circular Progress Gauge & Evidence -->
                            <div class="lg:col-span-4 bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/10 flex flex-col items-center justify-center text-center space-y-4">
                                <div class="relative flex items-center justify-center">
                                    <svg class="w-32 h-32 -rotate-90">
                                        <circle cx="64" cy="64" :r="radius" stroke="currentColor" stroke-width="10" class="text-white/10" fill="transparent" />
                                        <circle
                                            cx="64"
                                            cy="64"
                                            :r="radius"
                                            stroke="currentColor"
                                            stroke-width="10"
                                            class="text-amber-400 transition-all duration-1000"
                                            fill="transparent"
                                            :stroke-dasharray="circumference"
                                            :stroke-dashoffset="activeGaugeOffset"
                                            stroke-linecap="round"
                                        />
                                    </svg>
                                    <div class="absolute flex flex-col items-center justify-center text-center">
                                        <span class="text-3xl font-black text-white">{{ selectedPhase.progress_percentage ?? 0 }}%</span>
                                        <span class="text-[10px] uppercase font-bold text-amber-300">Avance</span>
                                    </div>
                                </div>

                                <div class="text-center space-y-1">
                                    <p class="text-xs font-bold text-white">Estado de la Fase</p>
                                    <p class="text-xs text-slate-400">Avance consolidado de los medios de verificación y matrices CBC.</p>
                                </div>

                                <a
                                    v-if="selectedPhase.file_url"
                                    :href="selectedPhase.file_url"
                                    target="_blank"
                                    class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20"
                                >
                                    <i class="bi bi-file-earmark-arrow-down-fill text-sm"></i> Descargar Evidencias / Informe
                                </a>
                                <a
                                    v-else-if="selectedPhase.external_link"
                                    :href="selectedPhase.external_link"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/20"
                                >
                                    <i class="bi bi-box-arrow-up-right text-sm"></i> Ver Enlace Oficial
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 3: 7 CONDICIONES BÁSICAS DE CALIDAD (CBC) MATRIX -->
            <section class="py-16 sm:py-24 bg-white border-y border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-200 pb-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-100">
                                Matriz de Evaluación Oficial
                            </span>
                            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-2">
                                Las 7 Condiciones Básicas de Calidad (CBC)
                            </h2>
                            <p class="text-sm text-slate-600 mt-1 max-w-2xl">
                                Estándares obligatorios exigidos por la Ley N° 30512 para asegurar la excelencia académica e infraestructura del IESTP.
                            </p>
                        </div>
                        <div class="text-xs font-bold px-4 py-2 bg-slate-100 text-slate-700 rounded-2xl border border-slate-200 flex items-center gap-2">
                            <i class="bi bi-shield-fill-check text-emerald-600 text-base"></i> RVM N° 276-2019-MINEDU
                        </div>
                    </div>

                    <!-- CBCs Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="(cbc, index) in cbcList"
                            :key="index"
                            class="bg-slate-50 hover:bg-white rounded-3xl p-6 border border-slate-200 hover:border-indigo-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                        >
                            <div class="space-y-4">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-3 py-1 rounded-xl text-xs font-black bg-indigo-600 text-white shadow-sm">
                                        {{ cbc.cbc_number || `CBC ${index + 1}` }}
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full"
                                        :class="cbc.status === 'completed' || (cbc.progress ?? 100) >= 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                                    >
                                        <i class="bi" :class="cbc.status === 'completed' || (cbc.progress ?? 100) >= 100 ? 'bi-check-circle-fill' : 'bi-hourglass-split'"></i>
                                        {{ cbc.status === 'completed' || (cbc.progress ?? 100) >= 100 ? 'Cumplido' : 'En Adecuación' }}
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                                    {{ cbc.name }}
                                </h3>

                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    {{ cbc.description }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-200/80 space-y-1.5">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500 font-medium">Nivel de Adecuación</span>
                                    <span class="font-bold" :class="cbc.status === 'completed' || (cbc.progress ?? 100) >= 100 ? 'text-emerald-600' : 'text-amber-600'">
                                        {{ cbc.progress ?? 100 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                    <div
                                        class="h-full rounded-full transition-all duration-700"
                                        :class="cbc.status === 'completed' || (cbc.progress ?? 100) >= 100 ? 'bg-emerald-500' : 'bg-amber-500'"
                                        :style="{ width: `${cbc.progress ?? 100}%` }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 4: TABLE OF LICENSING PHASES -->
            <section class="py-16 sm:py-24 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                    <div class="text-center max-w-3xl mx-auto space-y-3">
                        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            Transparencia y Trazabilidad
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            Cuadro Detallado de Fases del Licenciamiento
                        </h2>
                        <p class="text-sm sm:text-base text-slate-600">
                            Consulte el detalle completo, estado de avance, periodos y marco legal de cada una de las 5 etapas del procedimiento:
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider font-bold">
                                        <th class="py-4 px-6">N° / Código</th>
                                        <th class="py-4 px-6">Fase del Proceso</th>
                                        <th class="py-4 px-6">Estado / Etapa (P)</th>
                                        <th class="py-4 px-6">Avance</th>
                                        <th class="py-4 px-6">Periodo Estimado</th>
                                        <th class="py-4 px-6 text-right">Documentación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-sm">
                                    <tr
                                        v-for="p in phases"
                                        :key="p.id"
                                        class="hover:bg-slate-50/80 transition-colors"
                                        :class="{ 'bg-amber-50/40 font-semibold': p.is_current || (currentPhase && currentPhase.id === p.id) }"
                                    >
                                        <!-- Number / Code -->
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="w-8 h-8 rounded-xl font-black text-xs flex items-center justify-center"
                                                    :class="p.is_current || (currentPhase && currentPhase.id === p.id) ? 'bg-amber-500 text-slate-950' : 'bg-slate-100 text-slate-700 border border-slate-200'"
                                                >
                                                    {{ p.phase_number }}
                                                </span>
                                                <span class="text-xs text-slate-500 font-mono">{{ p.code || `FASE-0${p.phase_number}` }}</span>
                                            </div>
                                        </td>

                                        <!-- Title & Subtitle -->
                                        <td class="py-4 px-6">
                                            <p class="font-bold text-slate-900">{{ p.title }}</p>
                                            <p v-if="p.subtitle" class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ p.subtitle }}</p>
                                        </td>

                                        <!-- Status Tag -->
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span
                                                v-if="p.is_current || (currentPhase && currentPhase.id === p.id)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-500 text-slate-950 shadow-sm animate-pulse"
                                            >
                                                <i class="bi bi-hourglass-split"></i> (P) Etapa Actual
                                            </span>
                                            <span
                                                v-else-if="p.status === 'completed'"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200"
                                            >
                                                <i class="bi bi-check-circle-fill"></i> (C) Culminado
                                            </span>
                                            <span
                                                v-else-if="p.status === 'observed'"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200"
                                            >
                                                (OBS) En Subsanación
                                            </span>
                                            <span
                                                v-else
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200"
                                            >
                                                (PTE) Pendiente
                                            </span>
                                        </td>

                                        <!-- Progress Bar -->
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-24 bg-slate-200 h-2 rounded-full overflow-hidden">
                                                    <div
                                                        class="h-full rounded-full"
                                                        :class="p.status === 'completed' ? 'bg-emerald-500' : (p.is_current ? 'bg-amber-500' : 'bg-slate-400')"
                                                        :style="{ width: `${p.progress_percentage}%` }"
                                                    ></div>
                                                </div>
                                                <span class="text-xs font-bold text-slate-700">{{ p.progress_percentage }}%</span>
                                            </div>
                                        </td>

                                        <!-- Estimated Date -->
                                        <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-600">
                                            {{ p.estimated_date || 'Por definir' }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-4 px-6 whitespace-nowrap text-right">
                                            <a
                                                v-if="p.file_url"
                                                :href="p.file_url"
                                                target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-bold transition-colors border border-indigo-200"
                                            >
                                                <i class="bi bi-file-earmark-pdf-fill"></i> Descargar
                                            </a>
                                            <a
                                                v-else-if="p.external_link"
                                                :href="p.external_link"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors"
                                            >
                                                <i class="bi bi-box-arrow-up-right"></i> Enlace
                                            </a>
                                            <span v-else class="text-xs text-slate-400 italic">En trámite</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 5: INSTITUTIONAL FAQ -->
            <section class="py-16 sm:py-20 bg-white border-t border-slate-200">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    <div class="text-center space-y-3">
                        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            Preguntas Frecuentes
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                            ¿Qué significa el Licenciamiento Institucional?
                        </h2>
                        <p class="text-sm text-slate-600">
                            Aspectos clave sobre el proceso de licenciamiento que lidera el IESTP Francisco Vigo Caballero.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <!-- FAQ 1 -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden transition-colors">
                            <button
                                @click="toggleFaq(1)"
                                class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors"
                            >
                                <span class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-3">
                                    <i class="bi bi-patch-question-fill text-indigo-600"></i> ¿Qué es el licenciamiento institucional para institutos tecnológicos?
                                </span>
                                <i class="bi text-slate-500 transition-transform duration-200" :class="openFaq === 1 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>
                            <div v-show="openFaq === 1" class="p-5 text-sm text-slate-600 bg-white border-t border-slate-200 leading-relaxed space-y-2">
                                <p>
                                    El Licenciamiento Institucional es el procedimiento obligatorio conducido por el <strong>Ministerio de Educación (MINEDU)</strong> para verificar que los Institutos de Educación Superior Tecnológicos Públicos y Privados cumplan con las <strong>Condiciones Básicas de Calidad (CBC)</strong> estipuladas en la Ley N° 30512 y su Reglamento.
                                </p>
                                <p>
                                    Garantiza que la institución cuenta con infraestructura moderna, docentes calificados, planes de estudio pertinentes y sostenibilidad para formar profesionales técnicos de alto nivel.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden transition-colors">
                            <button
                                @click="toggleFaq(2)"
                                class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors"
                            >
                                <span class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-3">
                                    <i class="bi bi-award-fill text-indigo-600"></i> ¿Por cuánto tiempo se otorga la Licencia Institucional?
                                </span>
                                <i class="bi text-slate-500 transition-transform duration-200" :class="openFaq === 2 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>
                            <div v-show="openFaq === 2" class="p-5 text-sm text-slate-600 bg-white border-t border-slate-200 leading-relaxed">
                                <p>
                                    La Licencia Institucional otorgada mediante Resolución Ministerial del MINEDU tiene una vigencia de <strong>6 años renovables</strong>, sujeta a evaluación periódica de la calidad educativa y el mantenimiento de las CBC.
                                </p>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden transition-colors">
                            <button
                                @click="toggleFaq(3)"
                                class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors"
                            >
                                <span class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-3">
                                    <i class="bi bi-mortarboard-fill text-indigo-600"></i> ¿Qué beneficios obtienen los estudiantes y egresados?
                                </span>
                                <i class="bi text-slate-500 transition-transform duration-200" :class="openFaq === 3 ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>
                            <div v-show="openFaq === 3" class="p-5 text-sm text-slate-600 bg-white border-t border-slate-200 leading-relaxed">
                                <p>
                                    Los estudiantes del IESTP Francisco Vigo Caballero acceden a títulos oficiales a <strong>Nombre de la Nación</strong> registrados en el padrón nacional del MINEDU, certificación modular progresiva, convalidación universitaria, acceso a becas PRONABEC y una bolsa de trabajo respaldada por convenios con el sector productivo regional y nacional.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
