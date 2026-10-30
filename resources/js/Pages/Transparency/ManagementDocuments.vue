<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed } from 'vue';

const page = usePage();

const props = defineProps({
    documents: {
        type: Array,
        default: () => [],
    },
    enterprise: {
        type: Object,
        default: () => null,
    },
});

const ent = computed(() => props.enterprise || page.props.enterprise || {});

// Search and filter state
const searchQuery = ref('');
const selectedCategory = ref('all');

// Expanded details accordion per card
const openDetails = ref({});
const toggleDetails = (id) => {
    openDetails.value[id] = !openDetails.value[id];
};

// Modal for previewing PDF
const showPdfModal = ref(false);
const pdfModalUrl = ref('');
const pdfModalTitle = ref('');

const openPdf = (url, title) => {
    pdfModalUrl.value = url;
    pdfModalTitle.value = title;
    showPdfModal.value = true;
};

const closePdfModal = () => {
    showPdfModal.value = false;
    pdfModalUrl.value = '';
    pdfModalTitle.value = '';
};

// Document URL helpers
const getDocFileUrl = (doc) => {
    if (doc.file_url) return doc.file_url;
    if (doc.file_path) {
        return doc.file_path.startsWith('http') ? doc.file_path : `/storage/${doc.file_path.replace(/^\//, '')}`;
    }
    return null;
};

const getResolutionFileUrl = (doc) => {
    if (doc.resolution_document_url) return doc.resolution_document_url;
    if (doc.resolution_document_path) {
        return doc.resolution_document_path.startsWith('http')
            ? doc.resolution_document_path
            : `/storage/${doc.resolution_document_path.replace(/^\//, '')}`;
    }
    return null;
};

const getValidityYear = (dateStr) => {
    if (!dateStr) return '';
    return String(dateStr).split('-')[0];
};

// Filtered documents
const filteredDocuments = computed(() => {
    const query = searchQuery.value.toLowerCase().trim();
    const cat = selectedCategory.value;

    return props.documents.filter((doc) => {
        const titleUpper = (doc.title || '').toUpperCase();

        // Category filter
        let categoryMatch = true;
        if (cat === 'PAT') {
            categoryMatch = titleUpper.includes('PAT') || titleUpper.includes('PLAN');
        } else if (cat === 'RI') {
            categoryMatch = titleUpper.includes('REGLAMENTO') || titleUpper.includes('RI');
        } else if (cat === 'MPP') {
            categoryMatch = titleUpper.includes('PUESTOS') || titleUpper.includes('MPP');
        }

        if (!categoryMatch) return false;
        if (!query) return true;

        return (
            (doc.title && doc.title.toLowerCase().includes(query)) ||
            (doc.description && doc.description.toLowerCase().includes(query)) ||
            (doc.details && doc.details.toLowerCase().includes(query))
        );
    });
});
</script>

<template>
    <Head>
        <title>Documentos de Gestión Institucional — IESTP Francisco Vigo Caballero</title>
        <meta
            name="description"
            content="Documentos de Gestión Institucional del IESTP Francisco Vigo Caballero de Uchiza. Accede al Reglamento Interno (RI), Plan Anual de Trabajo (PAT), Manual de Perfil de Puestos (MPP) y más."
        />
        <meta
            name="keywords"
            content="documentos de gestion, PAT 2026, reglamento interno, MPP, IESTP Francisco Vigo Caballero, transparencia institucional, uchiza, educacion superior"
        />
        <meta name="robots" content="index, follow" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Documentos de Gestión Institucional — IESTP Francisco Vigo Caballero" />
        <meta property="og:description" content="Consulta los instrumentos normativos y de gestión oficiales que rigen el IESTP Francisco Vigo Caballero." />
    </Head>

    <PublicLayout title="Documentos de Gestión">
        <!-- ═══ HERO SECTION ═══════════════════════════════════════════ -->
        <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                    Documentos de <span class="text-sky-400">Gestión</span>
                </h1>

                <p class="text-lg sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                    Instrumentos normativos, planeamiento estratégico y marcos de regulación oficial que orientan el funcionamiento institucional y académico de nuestra casa de estudios.
                </p>

                <!-- Institutional Badges Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-4xl mx-auto mt-10 pt-8 border-t border-white/10">
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center justify-center gap-3">
                        <i class="bi bi-shield-check text-2xl text-sky-400"></i>
                        <div class="text-left">
                            <p class="text-xs text-slate-400 font-semibold">Marco Legal</p>
                            <p class="text-sm font-bold text-white">Ley N° 30512</p>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center justify-center gap-3">
                        <i class="bi bi-eye text-2xl text-emerald-400"></i>
                        <div class="text-left">
                            <p class="text-xs text-slate-400 font-semibold">Acceso Público</p>
                            <p class="text-sm font-bold text-white">Transparencia 100%</p>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center justify-center gap-3">
                        <i class="bi bi-calendar-check text-2xl text-amber-400"></i>
                        <div class="text-left">
                            <p class="text-xs text-slate-400 font-semibold">Periodos Vigentes</p>
                            <p class="text-sm font-bold text-white">2026 — 2027</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ MAIN CONTENT SECTION ═══════════════════════════════════ -->
        <section class="py-16 sm:py-24 bg-slate-50 min-h-[600px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                <!-- Filter and Search Control Bar -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                                <i class="bi bi-folder2-open text-blue-600"></i> Catálogo de Documentos Oficiales
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Busque o filtre por título, objetivo o contenido específico.</p>
                        </div>
                        <div class="text-xs font-semibold px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                            {{ filteredDocuments.length }} Documentos Disponibles
                        </div>
                    </div>

                    <!-- Search Input & Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search Input -->
                        <div class="md:col-span-2 relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Buscar por título, palabra clave o descripción..."
                                class="w-full pl-11 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            />
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                            <button
                                v-if="searchQuery"
                                type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                                @click="searchQuery = ''"
                            >
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select
                                v-model="selectedCategory"
                                class="w-full py-3.5 px-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            >
                                <option value="all">Todos los Documentos</option>
                                <option value="PAT">Plan Anual de Trabajo (PAT)</option>
                                <option value="RI">Reglamento Interno (RI)</option>
                                <option value="MPP">Manual de Perfil de Puestos (MPP)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Documents Grid -->
                <div v-if="filteredDocuments.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div
                        v-for="doc in filteredDocuments"
                        :key="doc.id"
                        class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group"
                    >
                        <div class="p-6 sm:p-8 space-y-5">
                            <!-- Card Header: Icon & Badges -->
                            <div class="flex items-center justify-between gap-3">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300"
                                >
                                    <i
                                        v-if="(doc.title || '').toUpperCase().includes('PAT') || (doc.title || '').toUpperCase().includes('PLAN')"
                                        class="bi bi-journal-text text-2xl text-blue-600 group-hover:text-white transition-colors"
                                    ></i>
                                    <i
                                        v-else-if="(doc.title || '').toUpperCase().includes('REGLAMENTO') || (doc.title || '').toUpperCase().includes('RI')"
                                        class="bi bi-shield-lock-fill text-2xl text-indigo-600 group-hover:text-white transition-colors"
                                    ></i>
                                    <i
                                        v-else-if="(doc.title || '').toUpperCase().includes('PUESTOS') || (doc.title || '').toUpperCase().includes('MPP')"
                                        class="bi bi-person-lines-fill text-2xl text-emerald-600 group-hover:text-white transition-colors"
                                    ></i>
                                    <i
                                        v-else
                                        class="bi bi-file-earmark-pdf-fill text-2xl text-red-600 group-hover:text-white transition-colors"
                                    ></i>
                                </div>

                                <div class="flex flex-col items-end gap-1.5">
                                    <span
                                        v-if="doc.validity_period"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200"
                                    >
                                        <i class="bi bi-calendar3"></i>
                                        Vigencia {{ getValidityYear(doc.validity_period) }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <span
                                            v-if="getResolutionFileUrl(doc)"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-800 border border-red-200"
                                        >
                                            <i class="bi bi-file-earmark-check-fill"></i> Resolución
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700">
                                            Oficial
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Title -->
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900 leading-snug tracking-tight group-hover:text-blue-600 transition-colors">
                                    {{ doc.title }}
                                </h3>
                            </div>

                            <!-- Description -->
                            <p v-if="doc.description" class="text-sm text-slate-600 leading-relaxed font-normal">
                                {{ doc.description }}
                            </p>

                            <!-- Details Accordion Toggle -->
                            <div v-if="doc.details" class="pt-2">
                                <button
                                    type="button"
                                    class="w-full py-2 px-3 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-xl text-xs font-bold transition-all flex items-center justify-between border border-slate-200/80 cursor-pointer"
                                    @click="toggleDetails(doc.id)"
                                >
                                    <span class="flex items-center gap-1.5">
                                        <i class="bi bi-list-nested"></i> Estructura y Contenido
                                    </span>
                                    <i
                                        class="bi bi-chevron-down text-xs transition-transform duration-200"
                                        :class="{ 'rotate-180': openDetails[doc.id] }"
                                    ></i>
                                </button>

                                <div
                                    v-show="openDetails[doc.id]"
                                    class="mt-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200 text-xs text-slate-600 leading-relaxed space-y-2"
                                >
                                    <p class="font-bold text-slate-800 flex items-center gap-1">
                                        <i class="bi bi-info-circle-fill text-blue-500"></i> Detalle del documento:
                                    </p>
                                    <p class="whitespace-pre-line">{{ doc.details }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Footer -->
                        <div class="p-6 bg-slate-50/60 border-t border-slate-100 space-y-3">
                            <div v-if="getDocFileUrl(doc)" class="flex items-center gap-2.5">
                                <a
                                    :href="getDocFileUrl(doc)"
                                    target="_blank"
                                    class="flex-1 py-3 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl font-bold text-xs transition-all duration-300 flex items-center justify-center gap-2 shadow-sm hover:shadow-blue-600/30"
                                >
                                    <i class="bi bi-download text-sm"></i>
                                    <span>Descargar PDF</span>
                                </a>

                                <button
                                    type="button"
                                    class="py-3 px-4 bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 shadow-sm cursor-pointer"
                                    title="Previsualizar en pantalla"
                                    @click="openPdf(getDocFileUrl(doc), doc.title)"
                                >
                                    <i class="bi bi-eye text-sm text-blue-600"></i>
                                    <span class="hidden sm:inline">Ver</span>
                                </button>
                            </div>
                            <div v-else class="w-full py-3 px-4 bg-amber-50 border border-amber-200/80 rounded-xl text-center">
                                <p class="text-xs font-semibold text-amber-800 flex items-center justify-center gap-1.5">
                                    <i class="bi bi-check-circle-fill text-amber-600"></i>
                                    Aprobado y Registrado en Secretaría
                                </p>
                            </div>

                            <!-- Resolution Document Action Bar -->
                            <div
                                v-if="getResolutionFileUrl(doc)"
                                class="p-3 bg-red-50/80 border border-red-200/80 rounded-2xl flex items-center justify-between gap-2"
                            >
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600 text-sm shadow-xs">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-bold text-red-950 truncate">Resolución de Aprobación</p>
                                        <p class="text-[10px] text-red-700 font-medium">Documento de sustento legal</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <button
                                        type="button"
                                        class="px-2.5 py-1.5 bg-white hover:bg-red-100 text-red-700 border border-red-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1 shadow-xs cursor-pointer"
                                        title="Ver Resolución"
                                        @click="openPdf(getResolutionFileUrl(doc), `Resolución — ${doc.title}`)"
                                    >
                                        <i class="bi bi-eye"></i>
                                        <span>Ver</span>
                                    </button>
                                    <a
                                        :href="getResolutionFileUrl(doc)"
                                        download
                                        target="_blank"
                                        class="p-1.5 bg-white hover:bg-red-100 text-red-700 border border-red-200 rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center"
                                        title="Descargar Resolución PDF"
                                    >
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Search Results Notice -->
                <div v-else class="py-16 text-center bg-white rounded-3xl border border-slate-200">
                    <div class="max-w-md mx-auto space-y-4">
                        <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto text-2xl">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No se encontraron resultados</h3>
                        <p class="text-sm text-slate-500">
                            No hay documentos que coincidan con la búsqueda "<span class="font-bold">{{ searchQuery }}</span>".
                        </p>
                        <button
                            type="button"
                            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors inline-flex items-center gap-2 cursor-pointer"
                            @click="searchQuery = ''; selectedCategory = 'all'"
                        >
                            <i class="bi bi-x-lg"></i> Limpiar Búsqueda
                        </button>
                    </div>
                </div>

                <!-- Transparency Notice Banner -->
                <div class="bg-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-800 relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="space-y-2 text-center md:text-left">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-xs font-bold text-sky-300 uppercase tracking-wider">
                                Ley de Transparencia N° 27806
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black tracking-tight">¿Desea solicitar un documento público adicional?</h3>
                            <p class="text-sm text-slate-300 max-w-2xl">
                                De acuerdo con los lineamientos de acceso a la información pública, puede presentar su solicitud mediante nuestra Mesa de Partes Virtual o presencial.
                            </p>
                        </div>
                        <Link
                            href="/tramites/mesa-de-partes"
                            class="px-6 py-3.5 bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold text-sm rounded-2xl transition-all duration-300 shadow-lg shadow-sky-500/20 flex-shrink-0 flex items-center gap-2"
                        >
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Ir a Mesa de Partes</span>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ PDF READER MODAL ═══════════════════════════════════════ -->
        <teleport to="body">
            <div
                v-if="showPdfModal"
                class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
            >
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden border border-slate-200" @click.stop>
                    <!-- Modal Top Bar -->
                    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-file-earmark-pdf-fill text-red-400 text-lg"></i>
                            </div>
                            <h3 class="font-bold text-sm sm:text-base truncate">{{ pdfModalTitle }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <a
                                :href="pdfModalUrl"
                                download
                                target="_blank"
                                class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5"
                            >
                                <i class="bi bi-download"></i> Descargar
                            </a>
                            <button
                                type="button"
                                class="p-2 text-slate-400 hover:text-white rounded-lg transition-colors cursor-pointer"
                                @click="closePdfModal"
                            >
                                <i class="bi bi-x-lg text-base"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal iFrame Body -->
                    <div class="flex-1 bg-slate-100 relative">
                        <iframe :src="pdfModalUrl" class="w-full h-full border-none"></iframe>
                    </div>
                </div>
            </div>
        </teleport>
    </PublicLayout>
</template>
