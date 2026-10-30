<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed, onMounted, nextTick } from 'vue';

const page = usePage();

const props = defineProps({
    records: { type: Object, default: () => ({ data: [], links: [] }) },
    categoryGroups: { type: Array, default: () => [] },
    recordsByCategory: { type: Object, default: () => ({}) },
    totalRecords: { type: Number, default: 0 },
    totalAmount: { type: Number, default: 0 },
    monthlyTotals: { type: Object, default: () => ({}) },
    categoryTotals: { type: Object, default: () => ({}) },
    availableYears: { type: Array, default: () => [] },
    selectedYear: { type: [Number, String], default: null },
});

const ent = computed(() => page.props.enterprise || {});

// State
const viewMode = ref('grouped');
const searchCategory = ref('');
const openCategories = ref([]);

const toggleCategory = (cat) => {
    if (openCategories.value.includes(cat)) {
        openCategories.value = openCategories.value.filter((c) => c !== cat);
    } else {
        openCategories.value.push(cat);
    }
};

const isCatOpen = (cat) => openCategories.value.includes(cat);

const expandAll = () => {
    openCategories.value = props.categoryGroups.map((g) => g.category);
};

const collapseAll = () => {
    openCategories.value = [];
};

// Formatting helpers
const formatCurrency = (val) => {
    return Number(val || 0).toLocaleString('es-PE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatNumber = (val) => {
    return Number(val || 0).toLocaleString('es-PE');
};

const formatDate = (dateStr) => {
    if (!dateStr) return '—';
    const clean = String(dateStr).split('T')[0];
    const parts = clean.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
};

// Colors for category charts
const catColors = [
    '#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444',
    '#8b5cf6', '#06b6d4', '#f97316', '#84cc16', '#ec4899',
];

// Helper to get programs list from a category's records
const getCategoryPrograms = (subRecords) => {
    if (!subRecords || !Array.isArray(subRecords)) return [];
    const progs = subRecords.map((r) => r.program_code).filter(Boolean);
    return [...new Set(progs)];
};

// Chart.js dynamic initialization
const monthlyChartCanvas = ref(null);
const categoryChartCanvas = ref(null);
let monthlyChartInstance = null;
let categoryChartInstance = null;

const loadScript = (src) => {
    return new Promise((resolve, reject) => {
        if (window.Chart) return resolve();
        const script = document.createElement('script');
        script.src = src;
        script.onload = () => resolve();
        script.onerror = () => reject();
        document.head.appendChild(script);
    });
};

const renderCharts = () => {
    if (!window.Chart) return;

    // Monthly Bar Chart
    const mLabels = Object.keys(props.monthlyTotals || {});
    const mValues = Object.values(props.monthlyTotals || {});

    if (monthlyChartCanvas.value && mLabels.length > 0) {
        if (monthlyChartInstance) monthlyChartInstance.destroy();
        monthlyChartInstance = new window.Chart(monthlyChartCanvas.value, {
            type: 'bar',
            data: {
                labels: mLabels,
                datasets: [
                    {
                        label: 'Desembolsado (S/)',
                        data: mValues,
                        backgroundColor: 'rgba(99,102,241,0.75)',
                        hoverBackgroundColor: 'rgba(99,102,241,1)',
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' S/ ' + ctx.parsed.y.toLocaleString('es-PE', { minimumFractionDigits: 2 }),
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' },
                    },
                    y: {
                        grid: { color: 'rgba(226,232,240,0.8)' },
                        ticks: {
                            callback: (v) => 'S/ ' + v.toLocaleString('es-PE'),
                            font: { size: 11 },
                            color: '#64748b',
                        },
                    },
                },
            },
        });
    }

    // Category Doughnut Chart
    const cLabels = Object.keys(props.categoryTotals || {});
    const cValues = Object.values(props.categoryTotals || {});

    if (categoryChartCanvas.value && cLabels.length > 0) {
        if (categoryChartInstance) categoryChartInstance.destroy();
        categoryChartInstance = new window.Chart(categoryChartCanvas.value, {
            type: 'doughnut',
            data: {
                labels: cLabels,
                datasets: [
                    {
                        data: cValues,
                        backgroundColor: catColors.slice(0, cLabels.length),
                        hoverOffset: 6,
                        borderWidth: 2,
                        borderColor: '#fff',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' S/ ' + ctx.parsed.toLocaleString('es-PE', { minimumFractionDigits: 2 }),
                        },
                    },
                },
            },
        });
    }
};

onMounted(async () => {
    if (props.totalRecords > 0) {
        try {
            await loadScript('https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js');
            await nextTick();
            renderCharts();
        } catch (e) {
            console.error('Error loading Chart.js', e);
        }
    }
});
</script>

<template>
    <Head>
        <title>Inversión y Gestión — IESTP Francisco Vigo Caballero</title>
        <meta
            name="description"
            content="Portal de Transparencia del IESTP Francisco Vigo Caballero: Consulta los registros de inversión institucional, desembolsos por categoría y programa de estudios, organizados por mes y año para garantizar la transparencia en la gestión de recursos públicos."
        />
        <meta
            name="keywords"
            content="inversion institucional, gestion financiera, transparencia, desembolsos, gastos institucionales, IESTP Francisco Vigo Caballero, Uchiza, rendicion de cuentas, recursos publicos, educacion superior tecnologica"
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Inversión y Gestión Institucional — IESTP Francisco Vigo Caballero" />
        <meta property="og:description" content="Consulta los registros de inversión, desembolsos y gestión de recursos institucionales del IESTP Francisco Vigo Caballero, organizados por mes, categoría y programa de estudios." />
    </Head>

    <PublicLayout title="Inversión y Gestión">
        <div class="bg-slate-50 min-h-screen">
            <!-- ═══ HERO SECTION ═══════════════════════════════════════════ -->
            <section class="bg-slate-900 relative text-white overflow-hidden py-16 lg:py-20 border-b border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-sky-400 text-xs font-bold uppercase tracking-widest">
                        <i class="bi bi-shield-check"></i>
                        Portal de Transparencia
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                        Inversión y <span class="text-sky-400">Gestión Institucional</span>
                    </h1>

                    <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
                        Registros de desembolsos e inversión de recursos del
                        <strong class="text-white">{{ ent.company_name || 'IESTP Francisco Vigo Caballero' }}</strong>,
                        organizados por mes, categoría y programa de estudios en cumplimiento de la normativa de transparencia.
                    </p>

                    <!-- Breadcrumb -->
                    <nav aria-label="Miga de pan" class="flex items-center justify-center gap-2 text-xs text-slate-400">
                        <Link href="/" class="hover:text-white transition-colors">Inicio</Link>
                        <i class="bi bi-chevron-right text-[10px]"></i>
                        <Link href="/transparencia/documentos-de-gestion" class="hover:text-white transition-colors">Transparencia</Link>
                        <i class="bi bi-chevron-right text-[10px]"></i>
                        <span class="text-indigo-300 font-semibold">Inversión y Gestión</span>
                    </nav>
                </div>
            </section>

            <!-- ═══ MAIN BODY ═══════════════════════════════════════════════ -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 space-y-10">
                <!-- YEAR FILTER BAR -->
                <section aria-label="Filtrar por año" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5 mr-1">
                            <i class="bi bi-calendar3 text-blue-600 text-sm"></i> Período anual:
                        </span>

                        <!-- All years pill -->
                        <Link
                            href="/transparencia/inversion-y-gestion"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold transition-all shadow-xs"
                            :class="!selectedYear ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-300 hover:border-blue-300 hover:bg-blue-50/50'"
                        >
                            <i class="bi bi-collection-fill text-xs" :class="!selectedYear ? 'text-white' : 'text-slate-400'"></i>
                            <span>Todos los Años</span>
                        </Link>

                        <!-- Year pills -->
                        <Link
                            v-for="yr in availableYears"
                            :key="yr"
                            :href="`/transparencia/inversion-y-gestion?year=${yr}`"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold transition-all shadow-xs"
                            :class="String(selectedYear) === String(yr) ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-300 hover:border-blue-300 hover:bg-blue-50/50'"
                        >
                            <i class="bi bi-calendar-check-fill text-xs" :class="String(selectedYear) === String(yr) ? 'text-white' : 'text-blue-500'"></i>
                            <span>Año {{ yr }}</span>
                        </Link>

                        <span v-if="availableYears.length === 0" class="text-xs text-slate-400 italic">
                            Sin datos disponibles aún
                        </span>
                    </div>
                </section>

                <!-- EMPTY STATE -->
                <div v-if="totalRecords === 0" class="flex flex-col items-center justify-center py-24 gap-5">
                    <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-400 flex items-center justify-center text-4xl shadow-inner">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <div class="text-center">
                        <h2 class="text-xl font-bold text-slate-700">Sin datos disponibles</h2>
                        <p class="text-slate-500 text-sm mt-2">
                            <template v-if="selectedYear">
                                No hay registros para el año <strong>{{ selectedYear }}</strong>.
                                <Link href="/transparencia/inversion-y-gestion" class="text-indigo-600 font-semibold hover:underline">
                                    Ver todos los años
                                </Link>
                            </template>
                            <template v-else>
                                Aún no se han cargado registros de inversión institucional.
                            </template>
                        </p>
                    </div>
                </div>

                <!-- MAIN DATA WHEN RECORDS EXIST -->
                <template v-else>
                    <!-- KPI SUMMARY CARDS -->
                    <section aria-label="Resumen de inversión" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                        <!-- Total Registros -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center gap-5 hover:-translate-y-1 transition-transform">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl shadow-sm shrink-0">
                                <i class="bi bi-receipt-cutoff"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Registros</p>
                                <p class="text-3xl font-black text-slate-800 mt-0.5">{{ formatNumber(totalRecords) }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ selectedYear ? `año ${selectedYear}` : 'todos los períodos' }}</p>
                            </div>
                        </div>

                        <!-- Total Desembolsado -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center gap-5 hover:-translate-y-1 transition-transform">
                            <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl shadow-sm shrink-0">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Desembolsado</p>
                                <p class="text-3xl font-black text-blue-700 mt-0.5">S/ {{ formatCurrency(totalAmount) }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ selectedYear ? `año ${selectedYear}` : 'histórico acumulado' }}</p>
                            </div>
                        </div>

                        <!-- Categorías -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex items-center gap-5 sm:col-span-2 lg:col-span-1 hover:-translate-y-1 transition-transform">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-2xl shadow-sm shrink-0">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Categorías</p>
                                <p class="text-3xl font-black text-emerald-700 mt-0.5">{{ Object.keys(categoryTotals).length }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">rubros de inversión registrados</p>
                            </div>
                        </div>
                    </section>

                    <!-- CHARTS SECTION -->
                    <section
                        v-if="Object.keys(monthlyTotals).length > 0 || Object.keys(categoryTotals).length > 0"
                        aria-label="Gráficos de inversión"
                        class="grid grid-cols-1 lg:grid-cols-5 gap-6"
                    >
                        <!-- Bar chart: monthly totals -->
                        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-800">Desembolsos Mensuales</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Total acumulado por mes en S/</p>
                                </div>
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg">
                                    <i class="bi bi-bar-chart-fill"></i>
                                </div>
                            </div>
                            <div class="relative" style="height: 260px;">
                                <canvas ref="monthlyChartCanvas" aria-label="Gráfico de barras con desembolsos mensuales"></canvas>
                            </div>
                        </div>

                        <!-- Doughnut chart: category distribution -->
                        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-5">
                                <div>
                                    <h2 class="text-base font-extrabold text-slate-800">Por Categoría</h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Distribución de inversión</p>
                                </div>
                                <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg">
                                    <i class="bi bi-pie-chart-fill"></i>
                                </div>
                            </div>
                            <div class="relative" style="height: 200px;">
                                <canvas ref="categoryChartCanvas" aria-label="Gráfico de dona con distribución por categoría"></canvas>
                            </div>

                            <!-- Legend -->
                            <div class="mt-4 space-y-1.5 max-h-40 overflow-y-auto pr-1">
                                <div
                                    v-for="(cat, cIdx) in Object.keys(categoryTotals)"
                                    :key="cat"
                                    class="flex items-center justify-between gap-2 text-xs"
                                >
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span
                                            class="w-3 h-3 rounded-sm shrink-0"
                                            :style="{ background: catColors[cIdx % catColors.length] }"
                                        ></span>
                                        <span class="text-slate-600 truncate">{{ cat }}</span>
                                    </div>
                                    <span class="font-bold text-slate-700 shrink-0">S/ {{ formatNumber(categoryTotals[cat]) }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- DATA TABLE GROUPED BY CATEGORY -->
                    <section aria-label="Tabla de registros de inversión agrupados por categoría">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                            <!-- Table header & Toolbar -->
                            <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-slate-50 to-white">
                                <div>
                                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2.5">
                                        <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                                            <i class="bi bi-folder-fill"></i>
                                        </span>
                                        Detalle de Registros por Categoría
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Datos agrupados por <strong>Categoría</strong>:
                                        <span class="font-bold text-slate-700">{{ categoryGroups.length }} rubros</span>
                                        con un total de <span class="font-bold text-blue-700">{{ formatNumber(totalRecords) }} operaciones</span>
                                        <template v-if="selectedYear">para el año <strong>{{ selectedYear }}</strong></template>.
                                    </p>
                                </div>

                                <!-- Toolbar: Search, View Mode, Expand/Collapse -->
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <div class="relative">
                                        <input
                                            v-model="searchCategory"
                                            type="text"
                                            placeholder="Buscar categoría..."
                                            class="pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all w-44 sm:w-52"
                                        />
                                        <i class="bi bi-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    </div>

                                    <div class="inline-flex rounded-xl shadow-xs border border-slate-200 overflow-hidden bg-white">
                                        <button
                                            type="button"
                                            class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition border-r border-slate-200 cursor-pointer"
                                            title="Expandir todas las categorías"
                                            @click="expandAll"
                                        >
                                            <i class="bi bi-arrows-expand mr-1"></i> Expandir
                                        </button>
                                        <button
                                            type="button"
                                            class="px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-50 transition cursor-pointer"
                                            title="Colapsar todas las categorías"
                                            @click="collapseAll"
                                        >
                                            <i class="bi bi-arrows-collapse mr-1"></i> Colapsar
                                        </button>
                                    </div>

                                    <!-- View Mode Toggle -->
                                    <div class="inline-flex rounded-xl p-0.5 bg-slate-100 border border-slate-200 text-xs font-bold">
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-lg transition flex items-center gap-1 cursor-pointer"
                                            :class="viewMode === 'grouped' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                            @click="viewMode = 'grouped'"
                                        >
                                            <i class="bi bi-diagram-3-fill"></i> Agrupado
                                        </button>
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-lg transition flex items-center gap-1 cursor-pointer"
                                            :class="viewMode === 'flat' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                            @click="viewMode = 'flat'"
                                        >
                                            <i class="bi bi-list-ul"></i> Lista Plana
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- ══ GROUPED BY CATEGORY VIEW ═════════════════════════════ -->
                            <div v-show="viewMode === 'grouped'" class="divide-y divide-slate-100">
                                <template v-for="catGroup in categoryGroups" :key="catGroup.category">
                                    <div
                                        v-show="!searchCategory || catGroup.category.toLowerCase().includes(searchCategory.toLowerCase().trim())"
                                        class="transition-colors border-b border-slate-100 last:border-b-0"
                                    >
                                        <!-- Category Summary Header Row -->
                                        <div
                                            class="p-4 sm:px-6 sm:py-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50/80 transition-all select-none"
                                            :class="isCatOpen(catGroup.category) ? 'bg-blue-50/40 border-l-4 border-l-blue-600' : 'border-l-4 border-l-transparent'"
                                            @click="toggleCategory(catGroup.category)"
                                        >
                                            <!-- Left: Category Name & Programs -->
                                            <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                                                <div class="w-10 h-10 rounded-xl bg-blue-100/70 border border-blue-200/60 text-blue-700 flex items-center justify-center text-lg font-bold shrink-0 shadow-2xs">
                                                    <i class="bi bi-tag-fill"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        <h3 class="text-sm sm:text-base font-black text-slate-800 tracking-tight">
                                                            {{ catGroup.category }}
                                                        </h3>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                            {{ catGroup.count }} {{ catGroup.count === 1 ? 'registro' : 'registros' }}
                                                        </span>
                                                    </div>

                                                    <!-- Programs preview -->
                                                    <div
                                                        v-if="getCategoryPrograms(recordsByCategory[catGroup.category]).length > 0"
                                                        class="flex items-center gap-1.5 mt-1 flex-wrap"
                                                    >
                                                        <span class="text-[11px] font-semibold text-slate-400">Programas:</span>
                                                        <span
                                                            v-for="pCode in getCategoryPrograms(recordsByCategory[catGroup.category])"
                                                            :key="pCode"
                                                            class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200"
                                                        >
                                                            {{ pCode }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right: Amount, Progress & Action -->
                                            <div class="flex items-center justify-between lg:justify-end gap-5 shrink-0 pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                                                <!-- Percent of Total -->
                                                <div class="hidden sm:block text-right w-28">
                                                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 mb-1">
                                                        <span>Participación</span>
                                                        <span class="text-slate-700">
                                                            {{ totalAmount > 0 ? (Math.round((catGroup.total_amount / totalAmount) * 1000) / 10) : 0 }}%
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                        <div
                                                            class="bg-blue-600 h-1.5 rounded-full transition-all duration-500"
                                                            :style="{ width: `${totalAmount > 0 ? Math.min(100, Math.max(4, (catGroup.total_amount / totalAmount) * 100)) : 0}%` }"
                                                        ></div>
                                                    </div>
                                                </div>

                                                <!-- Total Amount -->
                                                <div class="text-left sm:text-right">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Recaudado</span>
                                                    <span class="text-base sm:text-lg font-black text-blue-700 tracking-tight">
                                                        S/ {{ formatCurrency(catGroup.total_amount) }}
                                                    </span>
                                                </div>

                                                <!-- Toggle Button -->
                                                <button
                                                    type="button"
                                                    class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-all flex items-center gap-1.5 cursor-pointer"
                                                    :class="isCatOpen(catGroup.category) ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:border-blue-400 hover:text-blue-600'"
                                                >
                                                    <span>{{ isCatOpen(catGroup.category) ? 'Ocultar' : 'Ver detalle' }}</span>
                                                    <i class="bi text-xs" :class="isCatOpen(catGroup.category) ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Detail Drawer Table -->
                                        <div
                                            v-show="isCatOpen(catGroup.category)"
                                            class="bg-slate-50/70 border-t border-slate-200/80 px-3 sm:px-6 py-4"
                                        >
                                            <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full text-xs text-left">
                                                        <thead>
                                                            <tr class="bg-slate-100/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200 text-[11px]">
                                                                <th class="py-2.5 px-3">Mes / Período</th>
                                                                <th class="py-2.5 px-3">N° B/V</th>
                                                                <th class="py-2.5 px-3">Cliente / Beneficiario</th>
                                                                <th class="py-2.5 px-3">Descripción</th>
                                                                <th class="py-2.5 px-3">Programa</th>
                                                                <th class="py-2.5 px-3 text-right">Monto (S/)</th>
                                                                <th class="py-2.5 px-3">Motivo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100 text-slate-700">
                                                            <tr
                                                                v-for="rec in (recordsByCategory[catGroup.category] || [])"
                                                                :key="rec.id"
                                                                class="hover:bg-blue-50/30 transition-colors"
                                                            >
                                                                <td class="py-2.5 px-3 font-semibold text-slate-800 whitespace-nowrap">
                                                                    <span class="inline-flex items-center gap-1">
                                                                        <i class="bi bi-calendar-event text-slate-400"></i>
                                                                        {{ rec.month || (rec.date ? formatDate(rec.date) : '—') }}
                                                                    </span>
                                                                </td>
                                                                <td class="py-2.5 px-3 font-mono font-bold text-slate-600 whitespace-nowrap">
                                                                    {{ rec.receipt_number || '—' }}
                                                                </td>
                                                                <td class="py-2.5 px-3 font-semibold text-slate-800 max-w-[200px]">
                                                                    <span class="block truncate" :title="rec.client">
                                                                        {{ rec.client || '—' }}
                                                                    </span>
                                                                </td>
                                                                <td class="py-2.5 px-3 text-slate-600 max-w-[240px]">
                                                                    <span class="block truncate" :title="rec.description">
                                                                        {{ rec.description || '—' }}
                                                                    </span>
                                                                </td>
                                                                <td class="py-2.5 px-3 whitespace-nowrap">
                                                                    <template v-if="rec.program_code">
                                                                        <span class="font-extrabold text-blue-700">{{ rec.program_code }}</span>
                                                                        <span v-if="rec.program_name" class="text-slate-400 text-[10px] ml-1">
                                                                            ({{ rec.program_name.substring(0, 18) }})
                                                                        </span>
                                                                    </template>
                                                                    <span v-else-if="rec.program_name" class="text-slate-600">
                                                                        {{ rec.program_name.substring(0, 22) }}
                                                                    </span>
                                                                    <span v-else class="text-slate-300">—</span>
                                                                </td>
                                                                <td class="py-2.5 px-3 text-right font-black text-blue-700 whitespace-nowrap">
                                                                    S/ {{ formatCurrency(rec.amount) }}
                                                                </td>
                                                                <td class="py-2.5 px-3 text-slate-500 text-[11px] max-w-[140px]">
                                                                    <span class="block truncate" :title="rec.reason">
                                                                        {{ rec.reason || '—' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="bg-blue-50/60 font-bold border-t border-blue-100 text-xs">
                                                                <td colspan="5" class="py-2.5 px-3 text-right text-slate-600 uppercase tracking-wider">
                                                                    Subtotal {{ catGroup.category }}:
                                                                </td>
                                                                <td class="py-2.5 px-3 text-right font-black text-blue-800 text-sm whitespace-nowrap">
                                                                    S/ {{ formatCurrency(catGroup.total_amount) }}
                                                                </td>
                                                                <td class="py-2.5 px-3 text-slate-500 font-semibold text-[11px]">
                                                                    {{ (recordsByCategory[catGroup.category] || []).length }} comprobantes
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- ══ FLAT RAW LIST VIEW ═══════════════════════════════════ -->
                            <div v-show="viewMode === 'flat'" class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-200">
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mes</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fecha</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">N° B/V</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Cliente / Beneficiario</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Descripción</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Categoría</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Programa</th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Monto (S/)</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider hidden xl:table-cell">Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr v-for="rec in records.data" :key="rec.id" class="hover:bg-indigo-50/30 transition-colors">
                                            <td class="px-4 py-3 font-semibold text-slate-700 whitespace-nowrap">{{ rec.month || '—' }}</td>
                                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap text-xs">{{ formatDate(rec.date) }}</td>
                                            <td class="px-4 py-3 font-mono text-xs text-slate-600 whitespace-nowrap">{{ rec.receipt_number || '—' }}</td>
                                            <td class="px-4 py-3 text-slate-700 max-w-[180px]">
                                                <span class="block truncate" :title="rec.client">{{ rec.client || '—' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600 text-xs max-w-[220px] hidden md:table-cell">
                                                <span class="block truncate" :title="rec.description">{{ rec.description || '—' }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span v-if="rec.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    {{ rec.category }}
                                                </span>
                                                <span v-else class="text-slate-400 text-xs">—</span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-slate-600 hidden lg:table-cell max-w-[140px]">
                                                <span v-if="rec.program_code" class="font-bold text-indigo-600">{{ rec.program_code }}</span>
                                                <span v-if="rec.program_name" class="block text-slate-500 truncate" :title="rec.program_name">
                                                    {{ rec.program_name.substring(0, 22) }}
                                                </span>
                                                <span v-if="!rec.program_code && !rec.program_name" class="text-slate-400">—</span>
                                            </td>
                                            <td class="px-4 py-3 text-right whitespace-nowrap font-bold text-blue-700">
                                                S/ {{ formatCurrency(rec.amount) }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-500 text-xs hidden xl:table-cell max-w-[120px]">
                                                <span class="block truncate" :title="rec.reason">{{ rec.reason || '—' }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Pagination Links -->
                                <div v-if="records.links && records.links.length > 3" class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-xs text-slate-500">
                                        Mostrando
                                        <span class="font-bold text-slate-700">{{ records.from }}</span>–<span class="font-bold text-slate-700">{{ records.to }}</span>
                                        de <span class="font-bold text-slate-700">{{ records.total }}</span> registros
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <template v-for="(link, lIdx) in records.links" :key="lIdx">
                                            <Link
                                                v-if="link.url"
                                                :href="link.url"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors"
                                                :class="link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                                                v-html="link.label"
                                            ></Link>
                                            <span
                                                v-else
                                                class="px-3 py-1.5 rounded-lg text-xs text-slate-400 border border-slate-100"
                                                v-html="link.label"
                                            ></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- TRANSPARENCY NOTE -->
                    <aside class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div class="text-sm text-indigo-800 leading-relaxed">
                            <strong class="font-bold">Nota de Transparencia:</strong>
                            Los registros publicados en esta sección corresponden a los desembolsos e inversiones institucionales del
                            <strong>{{ ent.company_name || 'IESTP Francisco Vigo Caballero' }}</strong>
                            en cumplimiento de las disposiciones del Portal de Transparencia Estándar del Estado Peruano (D.S. 070-2013-PCM).
                            Para consultas adicionales, comuníquese con la Dirección Administrativa.
                        </div>
                    </aside>
                </template>
            </div>
        </div>
    </PublicLayout>
</template>
