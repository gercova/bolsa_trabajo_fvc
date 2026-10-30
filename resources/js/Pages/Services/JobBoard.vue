<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    jobs: {
        type: Object,
        default: () => ({ data: [], links: [] }),
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    locations: {
        type: Array,
        default: () => [],
    },
    sources: {
        type: Array,
        default: () => [],
    },
    search: {
        type: String,
        default: '',
    },
    selectedLocation: {
        type: String,
        default: '',
    },
    selectedSource: {
        type: String,
        default: '',
    },
    graduates: {
        type: Array,
        default: () => [],
    },
    studyPrograms: {
        type: Array,
        default: () => [],
    },
});

// Tab state: 'ofertas' | 'estudiantes'
const activeTab = ref('ofertas');

// Search and filter forms for Jobs
const formSearch = ref(props.search || '');
const formLocation = ref(props.selectedLocation || '');
const formSource = ref(props.selectedSource || '');

const submitJobSearch = () => {
    router.get(
        route('bolsa-de-trabajo'),
        {
            search: formSearch.value || undefined,
            location: formLocation.value || undefined,
            source: formSource.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const resetJobFilters = () => {
    formSearch.value = '';
    formLocation.value = '';
    formSource.value = '';
    submitJobSearch();
};

// Selected Job for modal preview
const selectedJob = ref(null);
const openJobModal = (job) => {
    selectedJob.value = job;
};
const closeJobModal = () => {
    selectedJob.value = null;
};

// Search and filter for Graduates / Students
const studentSearch = ref('');
const selectedProgramFilter = ref('ALL');

const filteredGraduates = computed(() => {
    let list = props.graduates || [];

    if (selectedProgramFilter.value !== 'ALL') {
        const targetProgramId = Number(selectedProgramFilter.value);
        list = list.filter((user) => {
            const progId = user.primary_role_detail?.program_id || user.primaryRoleDetail?.program_id;
            return Number(progId) === targetProgramId;
        });
    }

    if (studentSearch.value.trim() !== '') {
        const query = studentSearch.value.toLowerCase().trim();
        list = list.filter((user) => {
            const names = (user.names || '').toLowerCase();
            const dni = (user.dni || '').toLowerCase();
            const programName = (user.primary_role_detail?.program?.name || user.primaryRoleDetail?.program?.name || '').toLowerCase();
            const position = (user.job_position || '').toLowerCase();
            return names.includes(query) || dni.includes(query) || programName.includes(query) || position.includes(query);
        });
    }

    return list;
});

// Selected Student for modal preview
const selectedStudent = ref(null);
const openStudentModal = (student) => {
    selectedStudent.value = student;
};
const closeStudentModal = () => {
    selectedStudent.value = null;
};

// Post vacancy modal
const showPublishModal = ref(false);

// Helper for company initial avatar
const getCompanyInitial = (company) => {
    return (company || 'E').trim().charAt(0).toUpperCase();
};

// JSON-LD Schema
const jsonLd = computed(() => {
    const jobPostings = (props.jobs?.data || []).slice(0, 10).map((job, idx) => ({
        '@type': 'ListItem',
        position: idx + 1,
        item: {
            '@type': 'JobPosting',
            title: job.title,
            description: job.description ? job.description.replace(/<[^>]*>?/gm, '').slice(0, 200) : '',
            hiringOrganization: {
                '@type': 'Organization',
                name: job.company || 'Empresa Aliada',
            },
            jobLocation: {
                '@type': 'Place',
                address: {
                    '@type': 'PostalAddress',
                    addressLocality: job.location || 'Perú',
                    addressCountry: 'PE',
                },
            },
        },
    }));

    return JSON.stringify({
        '@context': 'https://schema.org',
        '@graph': [
            {
                '@type': 'WebPage',
                name: 'Bolsa de Trabajo Técnico en Perú — IESTP Francisco Vigo Caballero',
                description: 'Encuentra ofertas laborales técnicas y talento calificado en Redes, Enfermería, Agropecuaria, Forestal y Asistencia Administrativa.',
                url: 'https://iestpfranciscovigocaballero.edu.pe/servicios/bolsa-de-trabajo',
            },
            {
                '@type': 'ItemList',
                name: 'Ofertas Laborales Técnicas — IESTP FVC',
                itemListElement: jobPostings,
            },
        ],
    });
});
</script>

<template>
    <PublicLayout>
        <Head>
            <title>Bolsa de Trabajo Técnico en Perú — IESTP Francisco Vigo Caballero · Uchiza</title>
            <meta name="description" content="Encuentra ofertas de empleo técnico en Administración de Redes, Enfermería, Producción Agropecuaria, Manejo Forestal y Asistencia Administrativa en Perú. Bolsa de Trabajo institucional del IESTP Francisco Vigo Caballero." />
            <meta name="keywords" content="bolsa de trabajo tecnico peru, empleo uchiza, practicas profesionales san martin, tecnico redes comunicaciones, asistente administrativo, enfermeria tecnica, manejo forestal, produccion agropecuaria, iestp francisco vigo caballero" />
            <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Bolsa de Trabajo Técnico en Perú — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Ofertas de empleo y bolsa de talentos técnicos calificados del IESTP Francisco Vigo Caballero de Uchiza." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section aria-label="Portada Bolsa de Trabajo" class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <!-- Left: Title + Intro -->
                    <div class="lg:col-span-7 space-y-6">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-sky-400 text-xs sm:text-sm font-semibold tracking-wide">
                            <i class="bi bi-briefcase-fill text-sky-400" aria-hidden="true"></i>
                            Oportunidades Laborales & Inserción Profesional
                        </span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] text-white font-sans">
                            Bolsa de Trabajo y <br />
                            <span class="text-sky-400">Talento Técnico FVC</span>
                        </h1>
                        <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                            Conectamos a nuestros estudiantes, egresados y titulados con empresas líderes e instituciones a nivel nacional para empleos formales y prácticas profesionales.
                        </p>
                        <!-- CTA Buttons -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="#ofertas" class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-extrabold text-blue-900 bg-white hover:bg-blue-50 rounded-2xl transition-all shadow-xl hover:shadow-2xl gap-2">
                                <i class="bi bi-search"></i>
                                <span>Ver Convocatorias</span>
                            </a>
                            <button
                                type="button"
                                @click="showPublishModal = true"
                                class="inline-flex items-center justify-center px-6 py-3.5 text-sm font-extrabold text-white bg-blue-600 hover:bg-blue-700 rounded-2xl transition-all shadow-lg hover:shadow-blue-500/25 gap-2"
                            >
                                <i class="bi bi-building"></i>
                                <span>Publicar Vacante</span>
                            </button>
                        </div>
                    </div>

                    <!-- Right: Info Panel -->
                    <div class="lg:col-span-5 relative" aria-hidden="true">
                        <div class="relative bg-slate-800/90 border border-slate-700 p-8 rounded-3xl shadow-2xl space-y-5">
                            <div class="flex items-center justify-between border-b border-white/10 pb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center text-xl shadow-inner text-white">
                                        <i class="bi bi-person-workspace"></i>
                                    </div>
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-blue-200 font-extrabold">Portal Laboral FVC</p>
                                        <p class="text-lg font-extrabold text-white">Conexión Profesional</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 rounded-full text-xs font-extrabold">
                                    {{ jobs.total || jobs.data?.length || 0 }} vacantes
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-patch-check-fill text-emerald-400 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">Convocatorias Validadas</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Ofertas de portales líderes y empresas aliadas del instituto.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-mortarboard-fill text-blue-300 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">5 Especialidades Técnicas</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Redes, Administrativa, Enfermería, Forestal y Agropecuaria.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-arrow-repeat text-amber-300 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">Actualización Constante</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Búsqueda activa y vinculación directa con el mercado laboral.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB NAVIGATION -->
        <section aria-label="Navegación de pestañas laborales" class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex gap-3 py-4" role="tablist">
                    <button
                        type="button"
                        @click="activeTab = 'ofertas'"
                        class="px-5 py-3 rounded-2xl text-sm font-extrabold transition-all flex items-center gap-2"
                        :class="activeTab === 'ofertas' ? 'bg-blue-700 text-white shadow-md' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200'"
                        role="tab"
                        :aria-selected="activeTab === 'ofertas'"
                    >
                        <i class="bi bi-briefcase-fill"></i>
                        <span>Ofertas Laborales</span>
                        <span class="text-xs px-2 py-0.5 rounded-full" :class="activeTab === 'ofertas' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                            {{ jobs.total || jobs.data?.length || 0 }}
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'estudiantes'"
                        class="px-5 py-3 rounded-2xl text-sm font-extrabold transition-all flex items-center gap-2"
                        :class="activeTab === 'estudiantes' ? 'bg-blue-700 text-white shadow-md' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200'"
                        role="tab"
                        :aria-selected="activeTab === 'estudiantes'"
                    >
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>Perfiles de Egresados y Estudiantes</span>
                        <span class="text-xs px-2 py-0.5 rounded-full" :class="activeTab === 'estudiantes' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                            {{ graduates.length }}
                        </span>
                    </button>
                </nav>
            </div>
        </section>

        <!-- TAB CONTENT 1: OFERTAS LABORALES -->
        <div v-show="activeTab === 'ofertas'" id="ofertas">
            <!-- FILTERS SECTION -->
            <section aria-label="Filtros de búsqueda laboral" class="py-10 bg-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-3xl shadow-xl border border-slate-200/80 p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2.5">
                                <span class="w-9 h-9 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-lg shrink-0">
                                    <i class="bi bi-funnel-fill" aria-hidden="true"></i>
                                </span>
                                Buscar Ofertas Laborales Técnicas
                            </h2>
                            <button
                                v-if="formSearch || formLocation || formSource"
                                type="button"
                                @click="resetJobFilters"
                                class="text-xs font-bold text-red-600 hover:text-red-800 flex items-center gap-1 py-1 px-3 rounded-lg bg-red-50 hover:bg-red-100 transition"
                            >
                                <i class="bi bi-x-circle"></i>
                                Limpiar Filtros
                            </button>
                        </div>

                        <form @submit.prevent="submitJobSearch" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                            <!-- Keyword Search -->
                            <div class="lg:col-span-5 relative">
                                <label for="job-search" class="block text-xs font-bold text-slate-700 mb-1.5">Cargo o Empresa</label>
                                <div class="relative">
                                    <input
                                        id="job-search"
                                        v-model="formSearch"
                                        type="text"
                                        autocomplete="off"
                                        placeholder="Ej. Redes, Enfermería, Contabilidad, Agrónomo..."
                                        class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/25 focus:border-blue-600 transition-all text-sm font-medium placeholder-slate-400"
                                    />
                                    <i class="bi bi-search absolute left-3.5 top-3.5 text-slate-400 text-sm" aria-hidden="true"></i>
                                </div>
                            </div>

                            <!-- Location Select -->
                            <div class="lg:col-span-3">
                                <label for="job-location" class="block text-xs font-bold text-slate-700 mb-1.5">Ubicación</label>
                                <select
                                    id="job-location"
                                    v-model="formLocation"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/25 focus:border-blue-600 transition-all text-sm bg-white text-slate-700 font-medium"
                                >
                                    <option value="">Todas las Ubicaciones</option>
                                    <option v-for="loc in locations" :key="loc" :value="loc">{{ loc }}</option>
                                </select>
                            </div>

                            <!-- Source Select -->
                            <div class="lg:col-span-2">
                                <label for="job-source" class="block text-xs font-bold text-slate-700 mb-1.5">Portal / Fuente</label>
                                <select
                                    id="job-source"
                                    v-model="formSource"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/25 focus:border-blue-600 transition-all text-sm bg-white text-slate-700 font-medium"
                                >
                                    <option value="">Todos los Portales</option>
                                    <option v-for="src in sources" :key="src" :value="src">{{ src }}</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="lg:col-span-2 flex flex-col justify-end">
                                <button
                                    type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-extrabold px-4 py-3 rounded-2xl transition-all shadow-md hover:shadow-blue-500/30 flex items-center justify-center gap-2 text-sm"
                                >
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- JOBS LISTING GRID -->
            <section aria-label="Listado de ofertas laborales" class="py-12 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">
                                Vacantes Disponibles
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Mostrando {{ jobs.from || 1 }} - {{ jobs.to || (jobs.data?.length || 0) }} de {{ jobs.total || (jobs.data?.length || 0) }} ofertas encontradas
                            </p>
                        </div>
                    </div>

                    <!-- Job Cards Grid -->
                    <div v-if="jobs.data && jobs.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <article
                            v-for="job in jobs.data"
                            :key="job.id"
                            class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative group"
                        >
                            <!-- Top Card Info -->
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-xl flex items-center justify-center shadow-sm shrink-0">
                                            {{ getCompanyInitial(job.company) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-500 truncate">{{ job.company || 'Empresa Confidencial' }}</p>
                                            <h4 class="text-base font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                                {{ job.title }}
                                            </h4>
                                        </div>
                                    </div>
                                    <span v-if="job.source" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 shrink-0">
                                        {{ job.source }}
                                    </span>
                                </div>

                                <!-- Location and Details -->
                                <div class="flex flex-wrap items-center gap-2 mb-4 text-xs font-semibold text-slate-600">
                                    <span class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-lg">
                                        <i class="bi bi-geo-alt-fill text-red-500"></i>
                                        {{ job.location || 'Perú' }}
                                    </span>
                                    <span v-if="job.created_at" class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-lg text-slate-500">
                                        <i class="bi bi-clock"></i>
                                        {{ String(job.created_at).slice(0, 10) }}
                                    </span>
                                </div>

                                <!-- Description snippet -->
                                <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 mb-6 leading-relaxed">
                                    {{ (job.description || '').replace(/<[^>]*>?/gm, '') }}
                                </p>
                            </div>

                            <!-- Card Bottom Action -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                <button
                                    type="button"
                                    @click="openJobModal(job)"
                                    class="text-xs font-bold text-blue-700 hover:text-blue-900 flex items-center gap-1 py-1.5 px-3 rounded-lg hover:bg-blue-50 transition"
                                >
                                    <i class="bi bi-eye"></i>
                                    <span>Ver Detalles</span>
                                </button>
                                <a
                                    v-if="job.url"
                                    :href="job.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all"
                                >
                                    <span>Postular</span>
                                    <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="py-16 bg-white rounded-3xl border border-slate-200 text-center shadow-sm max-w-xl mx-auto">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-2">No se encontraron ofertas</h4>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                            Intenta ajustar los criterios de búsqueda, cambiar la ubicación o limpiar los filtros seleccionados.
                        </p>
                        <button
                            type="button"
                            @click="resetJobFilters"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-blue-700 transition"
                        >
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Restablecer Filtros</span>
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div v-if="jobs.links && jobs.links.length > 3" class="mt-12 flex justify-center flex-wrap gap-1.5">
                        <template v-for="(link, idx) in jobs.links" :key="idx">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all"
                                :class="link.active ? 'bg-blue-700 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-blue-50 border border-slate-200'"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </section>
        </div>

        <!-- TAB CONTENT 2: PERFILES ESTUDIANTILES (BOLSA DE TALENTO) -->
        <div v-show="activeTab === 'estudiantes'" id="estudiantes">
            <section aria-label="Bolsa de egresados y estudiantes" class="py-12 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Intro & Search Header -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-8">
                        <div class="max-w-3xl">
                            <span class="text-[11px] font-extrabold tracking-widest text-emerald-700 uppercase bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200">
                                Talento Calificado IESTP FVC
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-3">
                                Directorio de Egresados y Estudiantes
                            </h2>
                            <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                                Encuentra técnicos profesionales capacitados listos para integrarse a tu organización con competencias certificadas en las especialidades del instituto.
                            </p>
                        </div>

                        <!-- Controls -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <div class="md:col-span-8 relative">
                                <input
                                    v-model="studentSearch"
                                    type="text"
                                    placeholder="Buscar por nombre, especialidad o cargo..."
                                    class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition-all text-sm font-medium"
                                />
                                <i class="bi bi-search absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                            </div>

                            <div class="md:col-span-4">
                                <select
                                    v-model="selectedProgramFilter"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition-all text-sm bg-white font-medium"
                                >
                                    <option value="ALL">Todas las Carreras</option>
                                    <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.id">
                                        {{ prog.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Students Grid -->
                    <div v-if="filteredGraduates.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <article
                            v-for="student in filteredGraduates"
                            :key="student.id"
                            class="bg-white rounded-3xl border border-slate-200/80 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative group"
                        >
                            <div>
                                <div class="flex items-center gap-4 mb-4">
                                    <img
                                        v-if="student.photo_profile_url"
                                        :src="student.photo_profile_url"
                                        :alt="student.names"
                                        class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm shrink-0"
                                    />
                                    <div v-else class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white font-black text-xl flex items-center justify-center shadow-sm shrink-0">
                                        {{ getCompanyInitial(student.names) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-extrabold text-base text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-1">
                                            {{ student.names }}
                                        </h4>
                                        <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ student.role || 'Estudiante' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-2 mb-4 text-xs text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-mortarboard text-emerald-600"></i>
                                        <span class="font-semibold line-clamp-1">
                                            {{ student.primary_role_detail?.program?.name || student.primaryRoleDetail?.program?.name || 'Formación Técnica Profesional' }}
                                        </span>
                                    </div>
                                    <div v-if="student.job_position" class="flex items-center gap-2 text-slate-500">
                                        <i class="bi bi-briefcase text-slate-400"></i>
                                        <span class="line-clamp-1">{{ student.job_position }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                <button
                                    type="button"
                                    @click="openStudentModal(student)"
                                    class="text-xs font-bold text-emerald-700 hover:text-emerald-900 flex items-center gap-1 py-1.5 px-3 rounded-lg hover:bg-emerald-50 transition"
                                >
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span>Ver Perfil</span>
                                </button>
                                <a
                                    v-if="student.cv_file_url"
                                    :href="student.cv_file_url"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition"
                                >
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                    <span>CV</span>
                                </a>
                            </div>
                        </article>
                    </div>

                    <!-- Empty Students -->
                    <div v-else class="py-16 bg-white rounded-3xl border border-slate-200 text-center shadow-sm max-w-xl mx-auto">
                        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-2">No se encontraron perfiles</h4>
                        <p class="text-slate-500 text-sm max-w-md mx-auto">
                            No hay candidatos que coincidan con los criterios de búsqueda seleccionados.
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <!-- JOB DETAIL MODAL -->
        <div v-if="selectedJob" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto relative">
                <button
                    type="button"
                    @click="closeJobModal"
                    class="absolute top-6 right-6 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"
                >
                    <i class="bi bi-x-lg text-lg"></i>
                </button>

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white font-black text-2xl flex items-center justify-center shadow-sm shrink-0">
                        {{ getCompanyInitial(selectedJob.company) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <span v-if="selectedJob.source" class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-200 mb-1">
                            {{ selectedJob.source }}
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
                            {{ selectedJob.title }}
                        </h3>
                        <p class="text-sm font-bold text-slate-600 mt-1">
                            {{ selectedJob.company || 'Empresa Confidencial' }} · {{ selectedJob.location || 'Perú' }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mb-6">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">Descripción del Empleo</h4>
                    <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ (selectedJob.description || '').replace(/<[^>]*>?/gm, '') }}
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="closeJobModal"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition"
                    >
                        Cerrar
                    </button>
                    <a
                        v-if="selectedJob.url"
                        :href="selectedJob.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md transition"
                    >
                        <span>Postular en Portal Oficial</span>
                        <i class="bi bi-box-arrow-up-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- STUDENT DETAIL MODAL -->
        <div v-if="selectedStudent" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto relative">
                <button
                    type="button"
                    @click="closeStudentModal"
                    class="absolute top-6 right-6 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"
                >
                    <i class="bi bi-x-lg text-lg"></i>
                </button>

                <div class="text-center mb-6">
                    <img
                        v-if="selectedStudent.photo_profile_url"
                        :src="selectedStudent.photo_profile_url"
                        :alt="selectedStudent.names"
                        class="w-24 h-24 rounded-3xl object-cover mx-auto mb-4 border-2 border-emerald-500 shadow-md"
                    />
                    <div v-else class="w-24 h-24 rounded-3xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white font-black text-3xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        {{ getCompanyInitial(selectedStudent.names) }}
                    </div>
                    <h3 class="text-xl font-black text-slate-900">{{ selectedStudent.names }}</h3>
                    <p class="text-xs font-bold text-emerald-700 mt-1 uppercase tracking-wide">
                        {{ selectedStudent.primary_role_detail?.program?.name || selectedStudent.primaryRoleDetail?.program?.name || 'Profesional Técnico' }}
                    </p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ selectedStudent.role || 'Egresado' }}
                    </span>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 space-y-3 mb-6 text-xs text-slate-700">
                    <div v-if="selectedStudent.email" class="flex items-center gap-2">
                        <i class="bi bi-envelope-fill text-slate-400"></i>
                        <span>{{ selectedStudent.email }}</span>
                    </div>
                    <div v-if="selectedStudent.phone" class="flex items-center gap-2">
                        <i class="bi bi-telephone-fill text-slate-400"></i>
                        <span>{{ selectedStudent.phone }}</span>
                    </div>
                    <div v-if="selectedStudent.address" class="flex items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-slate-400"></i>
                        <span>{{ selectedStudent.address }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="closeStudentModal"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition"
                    >
                        Cerrar
                    </button>
                    <a
                        v-if="selectedStudent.cv_file_url"
                        :href="selectedStudent.cv_file_url"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition"
                    >
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Descargar CV Completo</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- PUBLISH VACANCY MODAL FOR EMPLOYERS -->
        <div v-if="showPublishModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative">
                <button
                    type="button"
                    @click="showPublishModal = false"
                    class="absolute top-6 right-6 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"
                >
                    <i class="bi bi-x-lg text-lg"></i>
                </button>

                <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center text-2xl mb-4">
                    <i class="bi bi-building-add"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900">Publicar Oferta Laboral</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    ¿Representas a una empresa o institución y buscas profesionales técnicos de alto rendimiento? Envíanos el perfil de la vacante para su difusión institucional.
                </p>

                <div class="mt-6 space-y-3">
                    <a
                        v-if="enterprise?.whatsapp_link"
                        :href="enterprise.whatsapp_link"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-full flex items-center justify-center gap-2 py-3.5 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition"
                    >
                        <i class="bi bi-whatsapp text-lg"></i>
                        <span>Enviar Convocatoria por WhatsApp</span>
                    </a>
                    <Link
                        :href="route('mesa-de-partes')"
                        class="w-full flex items-center justify-center gap-2 py-3.5 px-5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-sm rounded-xl transition"
                    >
                        <i class="bi bi-inbox-fill text-blue-600"></i>
                        <span>Remitir Vía Mesa de Partes Virtual</span>
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
