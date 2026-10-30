<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed } from 'vue';

const page = usePage();

const props = defineProps({
    enterprise: { type: Object, default: () => null },
    totalMatriculas: { type: Number, default: 0 },
    totalAdmisiones: { type: Number, default: 0 },
    totalTitulos: { type: Number, default: 0 },
    totalProgramas: { type: Number, default: 0 },
    totalPeriodos: { type: Number, default: 0 },
    totalAniosTitulacion: { type: Number, default: 0 },
    totalMalesMatricula: { type: Number, default: 0 },
    totalFemalesMatricula: { type: Number, default: 0 },
    totalMalesTitulos: { type: Number, default: 0 },
    totalFemalesTitulos: { type: Number, default: 0 },
    enrollmentByPeriod: { type: Object, default: () => ({}) },
    enrollmentPeriodSummary: { type: Array, default: () => [] },
    enrollmentProgramSummary: { type: Array, default: () => [] },
    admissionByPeriod: { type: Object, default: () => ({}) },
    admissionPeriodSummary: { type: Array, default: () => [] },
    degreesByYear: { type: Object, default: () => ({}) },
    degreesYearSummary: { type: Array, default: () => [] },
    degreesProgramSummary: { type: Array, default: () => [] },
    degreesFamilySummary: { type: Array, default: () => [] },
});

const ent = computed(() => props.enterprise || page.props.enterprise || {});

// State
const activeTab = ref('general');
const searchQuery = ref('');
const selectedPeriod = ref('all');
const selectedYear = ref('all');
const selectedAdmissionPeriod = ref('all');

// Formatting helpers
const formatNumber = (val) => {
    return Number(val || 0).toLocaleString('es-PE');
};

const calcPct = (val, total) => {
    if (!total || total === 0) return 0;
    return Math.round(((Number(val) || 0) / total) * 1000) / 10;
};

// Demographic Gender Percentages
const pctMalesMat = computed(() => calcPct(props.totalMalesMatricula, props.totalMatriculas));
const pctFemalesMat = computed(() => calcPct(props.totalFemalesMatricula, props.totalMatriculas));

const pctMalesTit = computed(() => calcPct(props.totalMalesTitulos, props.totalTitulos));
const pctFemalesTit = computed(() => calcPct(props.totalFemalesTitulos, props.totalTitulos));

// Print handler
const printStatistics = () => {
    window.print();
};

// Filter matches helper
const matchesSearch = (text) => {
    if (!searchQuery.value) return true;
    return String(text || '').toLowerCase().includes(searchQuery.value.toLowerCase().trim());
};
</script>

<template>
    <Head>
        <title>Estadísticas Institucionales — IESTP Francisco Vigo Caballero</title>
        <meta
            name="description"
            content="Portal de Transparencia del IESTP Francisco Vigo Caballero: Estadísticas oficiales y consolidadas de matrícula académica, procesos de admisión y grados y títulos registrados por año, periodo y programa de estudios."
        />
        <meta
            name="keywords"
            content="estadisticas institucionales, matricula academica, admision, grados y titulos, egresados, postulantes, estudiantes, IESTP Francisco Vigo Caballero, Uchiza, transparencia educativa, MINEDU, educacion superior tecnologica"
        />
        <meta name="robots" content="index, follow" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Estadísticas Institucionales — IESTP Francisco Vigo Caballero" />
        <meta property="og:description" content="Consulta los reportes y resúmenes estadísticos oficiales de matrícula, admisión y grados y títulos registrados en el IESTP Francisco Vigo Caballero." />
    </Head>

    <PublicLayout title="Estadísticas Institucionales">
        <div class="bg-slate-50 min-h-screen">
            <!-- ═══ HERO SECTION ═══════════════════════════════════════════ -->
            <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-20 border-b border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight text-white max-w-5xl mx-auto">
                        Estadísticas <span class="text-sky-400">Institucionales</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                        Información oficial consolidada de matrícula estudiantil, procesos de admisión y registro de grados y títulos del
                        <strong class="text-white font-semibold">{{ ent.company_name || 'IESTP Francisco Vigo Caballero' }}</strong>.
                    </p>

                    <!-- Highlights Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-5xl mx-auto mt-8 pt-6 border-t border-white/10 text-left">
                        <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Matrículas</p>
                                <p class="text-lg sm:text-xl font-black text-white">{{ formatNumber(totalMatriculas) }}</p>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Títulos Emitidos</p>
                                <p class="text-lg sm:text-xl font-black text-white">{{ formatNumber(totalTitulos) }}</p>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Programas</p>
                                <p class="text-lg sm:text-xl font-black text-white">{{ formatNumber(totalProgramas) }}</p>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-calendar2-range-fill"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Periodos / Años</p>
                                <p class="text-lg sm:text-xl font-black text-white">{{ totalPeriodos }} / {{ totalAniosTitulacion }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══ MAIN STATISTICAL CONTENT ════════════════════════════════ -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">
                <!-- Navigation Tabs -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 sm:p-3">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <nav class="flex flex-wrap gap-1.5 sm:gap-2" aria-label="Secciones Estadísticas">
                            <button
                                type="button"
                                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 cursor-pointer"
                                :class="activeTab === 'general' ? 'bg-blue-700 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                                @click="activeTab = 'general'"
                            >
                                <i class="bi bi-pie-chart-fill"></i>
                                <span>Resumen Consolidado</span>
                            </button>

                            <button
                                type="button"
                                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 cursor-pointer"
                                :class="activeTab === 'matricula' ? 'bg-blue-700 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                                @click="activeTab = 'matricula'"
                            >
                                <i class="bi bi-people-fill"></i>
                                <span>Matrícula por Periodo</span>
                                <span
                                    class="text-[10px] px-1.5 py-0.5 rounded-md"
                                    :class="activeTab === 'matricula' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                >
                                    {{ formatNumber(totalMatriculas) }}
                                </span>
                            </button>

                            <button
                                type="button"
                                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 cursor-pointer"
                                :class="activeTab === 'admision' ? 'bg-blue-700 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                                @click="activeTab = 'admision'"
                            >
                                <i class="bi bi-door-open-fill"></i>
                                <span>Admisión e Ingresantes</span>
                                <span
                                    v-if="totalAdmisiones > 0"
                                    class="text-[10px] px-1.5 py-0.5 rounded-md"
                                    :class="activeTab === 'admision' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                >
                                    {{ formatNumber(totalAdmisiones) }}
                                </span>
                            </button>

                            <button
                                type="button"
                                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-2 cursor-pointer"
                                :class="activeTab === 'titulos' ? 'bg-blue-700 text-white shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold'"
                                @click="activeTab = 'titulos'"
                            >
                                <i class="bi bi-award-fill"></i>
                                <span>Grados y Títulos</span>
                                <span
                                    class="text-[10px] px-1.5 py-0.5 rounded-md"
                                    :class="activeTab === 'titulos' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                >
                                    {{ formatNumber(totalTitulos) }}
                                </span>
                            </button>
                        </nav>

                        <!-- Search Filter & Print -->
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <div class="relative flex-1 sm:w-64">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Buscar programa..."
                                    class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-slate-50"
                                />
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <button
                                    v-if="searchQuery"
                                    type="button"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer"
                                    @click="searchQuery = ''"
                                >
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>

                            <button
                                type="button"
                                title="Imprimir resumen estadístico"
                                class="p-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 rounded-xl transition text-xs sm:text-sm font-semibold flex items-center gap-1.5 shrink-0 cursor-pointer"
                                @click="printStatistics"
                            >
                                <i class="bi bi-printer-fill"></i>
                                <span class="hidden sm:inline">Imprimir</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ══ TAB 1: RESUMEN CONSOLIDADO GENERAL ═══════════════════ -->
                <section v-show="activeTab === 'general'" class="space-y-8">
                    <!-- Demographic Gender Indicators Bar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Matrícula Gender Card -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                                        <i class="bi bi-gender-ambiguous"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Distribución de Género en Matrícula</h3>
                                        <p class="text-xs text-slate-500">Total histórico consolidado</p>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-bold text-slate-700">{{ formatNumber(totalMatriculas) }} reg.</span>
                            </div>

                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex">
                                <div class="bg-blue-600 h-full" :style="{ width: `${pctMalesMat}%` }" :title="`Varones: ${pctMalesMat}%`"></div>
                                <div class="bg-pink-500 h-full" :style="{ width: `${pctFemalesMat}%` }" :title="`Mujeres: ${pctFemalesMat}%`"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-1 text-center">
                                <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-100">
                                    <p class="text-xs font-semibold text-blue-700 flex items-center justify-center gap-1">
                                        <i class="bi bi-gender-male"></i> Varones: {{ formatNumber(totalMalesMatricula) }}
                                    </p>
                                    <p class="text-xs font-black text-blue-900 mt-0.5">{{ pctMalesMat }}%</p>
                                </div>
                                <div class="bg-pink-50/70 p-2.5 rounded-xl border border-pink-100">
                                    <p class="text-xs font-semibold text-pink-700 flex items-center justify-center gap-1">
                                        <i class="bi bi-gender-female"></i> Mujeres: {{ formatNumber(totalFemalesMatricula) }}
                                    </p>
                                    <p class="text-xs font-black text-pink-900 mt-0.5">{{ pctFemalesMat }}%</p>
                                </div>
                            </div>
                        </div>

                        <!-- Titulación Gender Card -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                                        <i class="bi bi-award"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Distribución de Género en Titulación</h3>
                                        <p class="text-xs text-slate-500">Total histórico de graduados titulados</p>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-bold text-slate-700">{{ formatNumber(totalTitulos) }} reg.</span>
                            </div>

                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex">
                                <div class="bg-blue-600 h-full" :style="{ width: `${pctMalesTit}%` }" :title="`Varones: ${pctMalesTit}%`"></div>
                                <div class="bg-pink-500 h-full" :style="{ width: `${pctFemalesTit}%` }" :title="`Mujeres: ${pctFemalesTit}%`"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-1 text-center">
                                <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-100">
                                    <p class="text-xs font-semibold text-blue-700 flex items-center justify-center gap-1">
                                        <i class="bi bi-gender-male"></i> Varones: {{ formatNumber(totalMalesTitulos) }}
                                    </p>
                                    <p class="text-xs font-black text-blue-900 mt-0.5">{{ pctMalesTit }}%</p>
                                </div>
                                <div class="bg-pink-50/70 p-2.5 rounded-xl border border-pink-100">
                                    <p class="text-xs font-semibold text-pink-700 flex items-center justify-center gap-1">
                                        <i class="bi bi-gender-female"></i> Mujeres: {{ formatNumber(totalFemalesTitulos) }}
                                    </p>
                                    <p class="text-xs font-black text-pink-900 mt-0.5">{{ pctFemalesTit }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla 1: Resumen Histórico de Matrícula por Programa de Estudios -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                                    <i class="bi bi-table text-blue-600"></i> Tabla 1: Resumen Histórico de Matrícula por Programa de Estudios
                                </h2>
                                <p class="text-xs text-slate-500 mt-0.5">Consolidado general de estudiantes matriculados según registros oficiales</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg self-start sm:self-auto">
                                {{ enrollmentProgramSummary.length }} Programas Registrados
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                        <th scope="col" class="py-3.5 px-4">#</th>
                                        <th scope="col" class="py-3.5 px-4">Programa de Estudios</th>
                                        <th scope="col" class="py-3.5 px-4 text-center">Periodos</th>
                                        <th scope="col" class="py-3.5 px-4 text-right">Varones</th>
                                        <th scope="col" class="py-3.5 px-4 text-right">Mujeres</th>
                                        <th scope="col" class="py-3.5 px-4 text-right">Total Matriculados</th>
                                        <th scope="col" class="py-3.5 px-4 text-right">% Participación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(prog, idx) in enrollmentProgramSummary"
                                        :key="idx"
                                        v-show="matchesSearch(prog.study_program)"
                                        class="hover:bg-slate-50/80 transition-colors"
                                    >
                                        <td class="py-3 px-4 text-slate-400 font-mono">{{ idx + 1 }}</td>
                                        <td class="py-3 px-4">
                                            <span class="font-bold text-slate-900">{{ prog.study_program }}</span>
                                            <span v-if="prog.last_period" class="block text-[11px] text-slate-500">
                                                Último periodo: {{ prog.last_period }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                                {{ prog.periods_count }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono text-blue-700 font-medium">
                                            {{ formatNumber(prog.males) }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono text-pink-700 font-medium">
                                            {{ formatNumber(prog.females) }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                            {{ formatNumber(prog.total) }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="inline-flex items-center gap-1.5">
                                                <div class="w-12 h-2 bg-slate-100 rounded-full overflow-hidden hidden sm:block">
                                                    <div class="bg-blue-600 h-full rounded-full" :style="{ width: `${calcPct(prog.total, totalMatriculas)}%` }"></div>
                                                </div>
                                                <span class="font-bold text-slate-700 text-xs">{{ calcPct(prog.total, totalMatriculas) }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                        <td class="py-3.5 px-4" colspan="2">TOTAL GENERAL CONSOLIDADO</td>
                                        <td class="py-3.5 px-4 text-center font-mono">{{ totalPeriodos }}</td>
                                        <td class="py-3.5 px-4 text-right font-mono text-blue-800">{{ formatNumber(totalMalesMatricula) }}</td>
                                        <td class="py-3.5 px-4 text-right font-mono text-pink-800">{{ formatNumber(totalFemalesMatricula) }}</td>
                                        <td class="py-3.5 px-4 text-right font-mono text-slate-950 text-sm sm:text-base">{{ formatNumber(totalMatriculas) }}</td>
                                        <td class="py-3.5 px-4 text-right font-mono">100.0%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- 2 Columns: Tabla 2 & Tabla 3 -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Evolución por Periodo Académico -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                                <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                    <i class="bi bi-calendar3 text-indigo-600"></i> Tabla 2: Matrícula por Periodo Académico
                                </h2>
                                <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded-md">
                                    {{ enrollmentPeriodSummary.length }} Periodos
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                            <th scope="col" class="py-3 px-4">Periodo</th>
                                            <th scope="col" class="py-3 px-4 text-center">Prog.</th>
                                            <th scope="col" class="py-3 px-4 text-right">Varones</th>
                                            <th scope="col" class="py-3 px-4 text-right">Mujeres</th>
                                            <th scope="col" class="py-3 px-4 text-right">Total</th>
                                            <th scope="col" class="py-3 px-4 text-right">%</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr v-for="ep in enrollmentPeriodSummary" :key="ep.academic_period" class="hover:bg-slate-50 transition-colors">
                                            <td class="py-2.5 px-4 font-bold text-slate-900 font-mono">{{ ep.academic_period }}</td>
                                            <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ ep.programs_count }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-700">{{ formatNumber(ep.males) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-700">{{ formatNumber(ep.females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900">{{ formatNumber(ep.total) }}</td>
                                            <td class="py-2.5 px-4 text-right text-xs font-semibold text-slate-600">{{ calcPct(ep.total, totalMatriculas) }}%</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-100 font-bold text-slate-900 border-t border-slate-200 text-xs">
                                            <td class="py-2.5 px-4" colspan="2">TOTAL</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-800">{{ formatNumber(totalMalesMatricula) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-800">{{ formatNumber(totalFemalesMatricula) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono">{{ formatNumber(totalMatriculas) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono">100%</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Evolución Anual de Grados y Títulos -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                                <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                    <i class="bi bi-award-fill text-purple-600"></i> Tabla 3: Títulos Otorgados por Año
                                </h2>
                                <span class="text-xs font-semibold px-2 py-0.5 bg-purple-100 text-purple-800 rounded-md">
                                    {{ degreesYearSummary.length }} Años Registrados
                                </span>
                            </div>

                            <div class="overflow-x-auto max-h-[380px]">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <thead class="sticky top-0 z-10">
                                        <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                            <th scope="col" class="py-3 px-4">Año</th>
                                            <th scope="col" class="py-3 px-4 text-center">Prog.</th>
                                            <th scope="col" class="py-3 px-4 text-right">Varones</th>
                                            <th scope="col" class="py-3 px-4 text-right">Mujeres</th>
                                            <th scope="col" class="py-3 px-4 text-right">Total</th>
                                            <th scope="col" class="py-3 px-4 text-right">%</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr v-for="dy in degreesYearSummary" :key="dy.year" class="hover:bg-slate-50 transition-colors">
                                            <td class="py-2.5 px-4 font-bold text-purple-900 font-mono">{{ dy.year }}</td>
                                            <td class="py-2.5 px-4 text-center text-slate-600 font-mono">{{ dy.programs_count }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-700">{{ formatNumber(dy.males) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-700">{{ formatNumber(dy.females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900">{{ formatNumber(dy.total) }}</td>
                                            <td class="py-2.5 px-4 text-right text-xs font-semibold text-slate-600">{{ calcPct(dy.total, totalTitulos) }}%</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="sticky bottom-0 bg-slate-100">
                                        <tr class="font-bold text-slate-900 border-t border-slate-200 text-xs">
                                            <td class="py-2.5 px-4" colspan="2">TOTAL HISTÓRICO</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-800">{{ formatNumber(totalMalesTitulos) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-800">{{ formatNumber(totalFemalesTitulos) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono">{{ formatNumber(totalTitulos) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono">100%</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla 4: Grados y Títulos por Familia Productiva -->
                    <div v-if="degreesFamilySummary && degreesFamilySummary.length > 0" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                            <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-diagram-3-fill text-emerald-600"></i> Tabla 4: Grados y Títulos por Familia Productiva
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded-md">
                                Clasificación MINEDU
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                        <th scope="col" class="py-3 px-4">#</th>
                                        <th scope="col" class="py-3 px-4">Familia Productiva</th>
                                        <th scope="col" class="py-3 px-4 text-right">Varones</th>
                                        <th scope="col" class="py-3 px-4 text-right">Mujeres</th>
                                        <th scope="col" class="py-3 px-4 text-right">Total Títulos</th>
                                        <th scope="col" class="py-3 px-4 text-right">% Participación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="(fam, idx) in degreesFamilySummary" :key="idx" class="hover:bg-slate-50 transition-colors">
                                        <td class="py-2.5 px-4 font-mono text-slate-400">{{ idx + 1 }}</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ fam.productive_family }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-blue-700">{{ formatNumber(fam.males) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono text-pink-700">{{ formatNumber(fam.females) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-900">{{ formatNumber(fam.total) }}</td>
                                        <td class="py-2.5 px-4 text-right font-mono font-semibold text-slate-700">{{ calcPct(fam.total, totalTitulos) }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- ══ TAB 2: MATRÍCULA POR PERIODO ACADÉMICO ═══════════════ -->
                <section v-show="activeTab === 'matricula'" class="space-y-8">
                    <!-- Period Filter bar -->
                    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-filter"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-extrabold text-slate-900">Filtrar por Periodo Académico</h2>
                                <p class="text-xs text-slate-500">Seleccione un periodo para ver el desglose por programa y ciclo</p>
                            </div>
                        </div>

                        <select
                            v-model="selectedPeriod"
                            class="text-xs sm:text-sm border border-slate-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-semibold text-slate-800"
                        >
                            <option value="all">Ver Todos los Periodos</option>
                            <option v-for="per in Object.keys(enrollmentByPeriod)" :key="per" :value="per">
                                Periodo {{ per }}
                            </option>
                        </select>
                    </div>

                    <!-- Period Tables -->
                    <template v-for="(periodRows, periodName) in enrollmentByPeriod" :key="periodName">
                        <div
                            v-show="selectedPeriod === 'all' || selectedPeriod === periodName"
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0"
                        >
                            <!-- Period Header Banner -->
                            <div class="px-6 py-4 border-b border-slate-200 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-blue-500 text-white font-mono font-black text-xs sm:text-sm rounded-lg shadow-sm">
                                        {{ periodName }}
                                    </span>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-extrabold text-white">Matrícula Académica — Periodo {{ periodName }}</h3>
                                        <p class="text-xs text-slate-300">{{ periodRows.length }} Programas con estudiantes matriculados</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-mono">
                                    <span class="text-blue-300">
                                        <i class="bi bi-gender-male"></i>
                                        {{ formatNumber(periodRows.reduce((acc, r) => acc + Number(r.males || 0), 0)) }} Varones
                                    </span>
                                    <span class="text-pink-300">
                                        <i class="bi bi-gender-female"></i>
                                        {{ formatNumber(periodRows.reduce((acc, r) => acc + Number(r.females || 0), 0)) }} Mujeres
                                    </span>
                                    <span class="px-2.5 py-1 bg-white/10 rounded-lg text-white font-bold">
                                        Total: {{ formatNumber(periodRows.reduce((acc, r) => acc + Number(r.total || 0), 0)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Table of Programs & Cycles -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider text-[11px] font-extrabold border-b border-slate-200">
                                            <th scope="col" class="py-3 px-4">#</th>
                                            <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                            <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo I</th>
                                            <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo II</th>
                                            <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo III</th>
                                            <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo IV</th>
                                            <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo V</th>
                                            <th scope="col" class="py-3 px-2 text-center text-slate-600">Ciclo VI</th>
                                            <th scope="col" class="py-3 px-3 text-right text-blue-700">Varones</th>
                                            <th scope="col" class="py-3 px-3 text-right text-pink-700">Mujeres</th>
                                            <th scope="col" class="py-3 px-4 text-right text-slate-900 font-black">Total</th>
                                            <th scope="col" class="py-3 px-4 text-right">% Periodo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="(row, idx) in periodRows"
                                            :key="idx"
                                            v-show="matchesSearch(row.study_program)"
                                            class="hover:bg-slate-50 transition-colors"
                                        >
                                            <td class="py-2.5 px-4 font-mono text-slate-400">{{ idx + 1 }}</td>
                                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ row.study_program }}</td>
                                            <td class="py-2.5 px-2 text-center font-mono" :class="row.cycle_i > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300'">
                                                {{ row.cycle_i || '—' }}
                                            </td>
                                            <td class="py-2.5 px-2 text-center font-mono" :class="row.cycle_ii > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300'">
                                                {{ row.cycle_ii || '—' }}
                                            </td>
                                            <td class="py-2.5 px-2 text-center font-mono" :class="row.cycle_iii > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300'">
                                                {{ row.cycle_iii || '—' }}
                                            </td>
                                            <td class="py-2.5 px-2 text-center font-mono" :class="row.cycle_iv > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300'">
                                                {{ row.cycle_iv || '—' }}
                                            </td>
                                            <td class="py-2.5 px-2 text-center font-mono" :class="row.cycle_v > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300'">
                                                {{ row.cycle_v || '—' }}
                                            </td>
                                            <td class="py-2.5 px-2 text-center font-mono" :class="row.cycle_vi > 0 ? 'text-slate-800 font-semibold' : 'text-slate-300'">
                                                {{ row.cycle_vi || '—' }}
                                            </td>
                                            <td class="py-2.5 px-3 text-right font-mono text-blue-700 font-semibold">{{ formatNumber(row.males) }}</td>
                                            <td class="py-2.5 px-3 text-right font-mono text-pink-700 font-semibold">{{ formatNumber(row.females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-extrabold text-slate-900 bg-slate-50/50">{{ formatNumber(row.total) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-xs font-bold text-slate-600">
                                                {{ calcPct(row.total, periodRows.reduce((acc, r) => acc + Number(r.total || 0), 0)) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </section>

                <!-- ══ TAB 3: ADMISIÓN E INGRESANTES ═════════════════════════ -->
                <section v-show="activeTab === 'admision'" class="space-y-8">
                    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-door-open-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-extrabold text-slate-900">Procesos de Admisión e Ingresantes</h2>
                                <p class="text-xs text-slate-500">Resumen estadístico de postulantes e ingresantes por programa y periodo</p>
                            </div>
                        </div>

                        <select
                            v-if="Object.keys(admissionByPeriod).length > 0"
                            v-model="selectedAdmissionPeriod"
                            class="text-xs sm:text-sm border border-slate-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white font-semibold text-slate-800"
                        >
                            <option value="all">Ver Todos los Procesos</option>
                            <option v-for="per in Object.keys(admissionByPeriod)" :key="per" :value="per">
                                Admisión {{ per }}
                            </option>
                        </select>
                    </div>

                    <template v-if="Object.keys(admissionByPeriod).length > 0">
                        <div
                            v-for="(periodRows, periodName) in admissionByPeriod"
                            :key="periodName"
                            v-show="selectedAdmissionPeriod === 'all' || selectedAdmissionPeriod === periodName"
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0"
                        >
                            <div class="px-6 py-4 border-b border-slate-200 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-indigo-500 text-white font-mono font-black text-xs sm:text-sm rounded-lg shadow-sm">
                                        {{ periodName }}
                                    </span>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-extrabold text-white">Proceso de Admisión — Periodo {{ periodName }}</h3>
                                        <p class="text-xs text-indigo-200">Postulantes e ingresantes por programa de estudios</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-mono">
                                    <span class="text-blue-300">
                                        <i class="bi bi-gender-male"></i>
                                        {{ formatNumber(periodRows.reduce((acc, r) => acc + Number(r.males || 0), 0)) }} Varones
                                    </span>
                                    <span class="text-pink-300">
                                        <i class="bi bi-gender-female"></i>
                                        {{ formatNumber(periodRows.reduce((acc, r) => acc + Number(r.females || 0), 0)) }} Mujeres
                                    </span>
                                    <span class="px-2.5 py-1 bg-white/10 rounded-lg text-white font-bold">
                                        Total: {{ formatNumber(periodRows.reduce((acc, r) => acc + Number(r.total || 0), 0)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                    <thead>
                                        <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider text-[11px] font-extrabold border-b border-slate-200">
                                            <th scope="col" class="py-3 px-4">#</th>
                                            <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                            <th scope="col" class="py-3 px-4 text-right text-blue-700">Varones</th>
                                            <th scope="col" class="py-3 px-4 text-right text-pink-700">Mujeres</th>
                                            <th scope="col" class="py-3 px-4 text-right text-slate-900 font-black">Total Ingresantes</th>
                                            <th scope="col" class="py-3 px-4 text-right">% Proceso</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="(row, idx) in periodRows"
                                            :key="idx"
                                            v-show="matchesSearch(row.study_program)"
                                            class="hover:bg-slate-50 transition-colors"
                                        >
                                            <td class="py-2.5 px-4 font-mono text-slate-400">{{ idx + 1 }}</td>
                                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ row.study_program }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-blue-700 font-semibold">{{ formatNumber(row.males) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-pink-700 font-semibold">{{ formatNumber(row.females) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono font-extrabold text-slate-900 bg-slate-50/50">{{ formatNumber(row.total) }}</td>
                                            <td class="py-2.5 px-4 text-right font-mono text-xs font-bold text-slate-600">
                                                {{ calcPct(row.total, periodRows.reduce((acc, r) => acc + Number(r.total || 0), 0)) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    <div v-else class="bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200 shadow-sm">
                        <i class="bi bi-door-open text-5xl mb-3 block text-slate-300"></i>
                        <p class="font-bold text-slate-700 text-base">No hay registros de admisión cargados actualmente.</p>
                        <p class="text-xs text-slate-500 mt-1">Los reportes se actualizarán al culminar cada proceso de examen ordinario o extraordinario.</p>
                    </div>
                </section>

                <!-- ══ TAB 4: GRADOS Y TÍTULOS ═══════════════════════════════ -->
                <section v-show="activeTab === 'titulos'" class="space-y-8">
                    <!-- Graduation Year Filter -->
                    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-extrabold text-slate-900">Grados y Títulos Registrados ante el MINEDU</h2>
                                <p class="text-xs text-slate-500">Desglose de egresados titulados por año de emisión y programa de estudios</p>
                            </div>
                        </div>

                        <select
                            v-model="selectedYear"
                            class="text-xs sm:text-sm border border-slate-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-semibold text-slate-800"
                        >
                            <option value="all">Ver Todos los Años ({{ Object.keys(degreesByYear).length }})</option>
                            <option v-for="yr in Object.keys(degreesByYear)" :key="yr" :value="yr">
                                Año {{ yr }}
                            </option>
                        </select>
                    </div>

                    <!-- Tabla Consolidada Histórica -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
                            <h2 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-trophy-fill text-purple-600"></i> Tabla: Consolidado Histórico de Títulos por Programa de Estudios
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 bg-purple-100 text-purple-800 rounded-md">
                                Total: {{ formatNumber(totalTitulos) }} Títulos
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-900 text-slate-200 uppercase tracking-wider text-[11px] font-extrabold">
                                        <th scope="col" class="py-3 px-4">#</th>
                                        <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                        <th scope="col" class="py-3 px-4">Nivel Formativo</th>
                                        <th scope="col" class="py-3 px-4 text-center">Periodo Histórico</th>
                                        <th scope="col" class="py-3 px-4 text-right text-blue-300">Varones</th>
                                        <th scope="col" class="py-3 px-4 text-right text-pink-300">Mujeres</th>
                                        <th scope="col" class="py-3 px-4 text-right text-white font-bold">Total Títulos</th>
                                        <th scope="col" class="py-3 px-4 text-right">% Histórico</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(dp, idx) in degreesProgramSummary"
                                        :key="idx"
                                        v-show="matchesSearch(dp.study_program)"
                                        class="hover:bg-slate-50 transition-colors"
                                    >
                                        <td class="py-3 px-4 font-mono text-slate-400">{{ idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-slate-900">{{ dp.study_program }}</td>
                                        <td class="py-3 px-4 text-slate-600 text-xs">{{ dp.formative_level || 'PROFESIONAL TÉCNICO' }}</td>
                                        <td class="py-3 px-4 text-center font-mono text-xs text-slate-600">
                                            {{ dp.first_year || '—' }} – {{ dp.last_year || '—' }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-mono text-blue-700 font-semibold">{{ formatNumber(dp.males) }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-pink-700 font-semibold">{{ formatNumber(dp.females) }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-extrabold text-slate-900">{{ formatNumber(dp.total) }}</td>
                                        <td class="py-3 px-4 text-right font-mono font-bold text-purple-700">{{ calcPct(dp.total, totalTitulos) }}%</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100 font-black text-slate-900 border-t-2 border-slate-300 text-xs sm:text-sm">
                                        <td class="py-3 px-4" colspan="3">TOTAL HISTÓRICO DE TÍTULOS OTORGADOS</td>
                                        <td class="py-3 px-4 text-center font-mono">{{ totalAniosTitulacion }} Años</td>
                                        <td class="py-3 px-4 text-right font-mono text-blue-800">{{ formatNumber(totalMalesTitulos) }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-pink-800">{{ formatNumber(totalFemalesTitulos) }}</td>
                                        <td class="py-3 px-4 text-right font-mono text-slate-950 text-sm sm:text-base">{{ formatNumber(totalTitulos) }}</td>
                                        <td class="py-3 px-4 text-right font-mono">100.0%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Annual Detailed Tables -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-calendar-check text-purple-600"></i> Desglose Anual Detallado
                        </h3>

                        <template v-for="(yearRows, yearName) in degreesByYear" :key="yearName">
                            <div
                                v-show="selectedYear === 'all' || selectedYear === String(yearName)"
                                class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0"
                            >
                                <div class="px-6 py-3.5 border-b border-slate-200 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-purple-500 text-white font-mono font-black text-xs sm:text-sm rounded-lg shadow-sm">
                                            Año {{ yearName }}
                                        </span>
                                        <span class="text-xs sm:text-sm font-extrabold text-white">Títulos y Grados Registrados en {{ yearName }}</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs font-mono">
                                        <span class="text-blue-300">
                                            <i class="bi bi-gender-male"></i>
                                            {{ formatNumber(yearRows.reduce((acc, r) => acc + Number(r.males || 0), 0)) }} Varones
                                        </span>
                                        <span class="text-pink-300">
                                            <i class="bi bi-gender-female"></i>
                                            {{ formatNumber(yearRows.reduce((acc, r) => acc + Number(r.females || 0), 0)) }} Mujeres
                                        </span>
                                        <span class="px-2.5 py-1 bg-white/10 rounded-lg text-white font-bold">
                                            Total: {{ formatNumber(yearRows.reduce((acc, r) => acc + Number(r.total || 0), 0)) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                                        <thead>
                                            <tr class="bg-slate-100 text-slate-700 uppercase tracking-wider text-[11px] font-extrabold border-b border-slate-200">
                                                <th scope="col" class="py-3 px-4">#</th>
                                                <th scope="col" class="py-3 px-4">Programa de Estudios</th>
                                                <th scope="col" class="py-3 px-4">Nivel Formativo</th>
                                                <th scope="col" class="py-3 px-4 text-right text-blue-700">Varones</th>
                                                <th scope="col" class="py-3 px-4 text-right text-pink-700">Mujeres</th>
                                                <th scope="col" class="py-3 px-4 text-right text-slate-900 font-black">Total Año</th>
                                                <th scope="col" class="py-3 px-4 text-right">% Año</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <tr
                                                v-for="(row, idx) in yearRows"
                                                :key="idx"
                                                v-show="matchesSearch(row.study_program)"
                                                class="hover:bg-slate-50 transition-colors"
                                            >
                                                <td class="py-2.5 px-4 font-mono text-slate-400">{{ idx + 1 }}</td>
                                                <td class="py-2.5 px-4 font-bold text-slate-900">{{ row.study_program }}</td>
                                                <td class="py-2.5 px-4 text-xs text-slate-600">{{ row.formative_level || 'PROFESIONAL TÉCNICO' }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-blue-700 font-semibold">{{ formatNumber(row.males) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-pink-700 font-semibold">{{ formatNumber(row.females) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono font-extrabold text-slate-900 bg-slate-50/50">{{ formatNumber(row.total) }}</td>
                                                <td class="py-2.5 px-4 text-right font-mono text-xs font-bold text-slate-600">
                                                    {{ calcPct(row.total, yearRows.reduce((acc, r) => acc + Number(r.total || 0), 0)) }}%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <!-- Institutional Footnote -->
                <div class="bg-slate-100 rounded-2xl p-5 sm:p-6 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs text-slate-600">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-info-circle-fill text-blue-600 text-xl shrink-0"></i>
                        <div>
                            <p class="font-bold text-slate-800">Nota Institucional de Transparencia:</p>
                            <p>Los datos consignados provienen de las nóminas oficiales de matrícula, actas de examen de admisión y el Sistema de Grados y Títulos (TITULA) del Ministerio de Educación (MINEDU).</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2">
                        <Link
                            href="/transparencia/documentos-de-gestion"
                            class="px-4 py-2 bg-white hover:bg-slate-200 text-slate-800 rounded-xl border border-slate-300 font-bold transition flex items-center gap-1.5"
                        >
                            <i class="bi bi-folder-check"></i> Documentos de Gestión
                        </Link>
                    </div>
                </div>
            </main>
        </div>
    </PublicLayout>
</template>
