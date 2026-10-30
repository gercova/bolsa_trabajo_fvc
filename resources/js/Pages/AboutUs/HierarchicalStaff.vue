<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    director: {
        type: Object,
        default: null,
    },
    managementStaff: {
        type: Array,
        default: () => [],
    },
    coordinators: {
        type: Array,
        default: () => [],
    },
    allHierarchical: {
        type: Array,
        default: () => [],
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const activeTab = ref('directory');
const searchQuery = ref('');
const categoryFilter = ref('all');
const selectedLeader = ref(null);
const modalOpen = ref(false);

const openDetail = (leader) => {
    selectedLeader.value = leader;
    modalOpen.value = true;
};

const closeDetail = () => {
    modalOpen.value = false;
    selectedLeader.value = null;
};

const getInitials = (name) => {
    if (!name) return 'IE';
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((n) => n[0].toUpperCase())
        .join('');
};

const matchesSearch = (name, position) => {
    if (!searchQuery.value.trim()) return true;
    const q = searchQuery.value.toLowerCase().trim();
    const n = (name || '').toLowerCase();
    const p = (position || '').toLowerCase();
    return n.includes(q) || p.includes(q);
};

const showDirector = computed(() => {
    if (!props.director) return false;
    if (categoryFilter.value !== 'all' && categoryFilter.value !== 'direccion') return false;
    return matchesSearch(props.director.names, props.director.job_position || 'Director General');
});

const filteredManagementStaff = computed(() => {
    if (categoryFilter.value !== 'all' && categoryFilter.value !== 'jefaturas') return [];
    return props.managementStaff.filter((staff) => matchesSearch(staff.names, staff.job_position));
});

const filteredCoordinators = computed(() => {
    if (categoryFilter.value !== 'all' && categoryFilter.value !== 'coordinadores') return [];
    return props.coordinators.filter((c) => {
        const userName = c.user?.names || '';
        const progName = c.program?.name || '';
        const pos = `Coordinador Académico ${progName}`;
        return matchesSearch(userName, pos);
    });
});

const jsonLd = computed(() => {
    const persons = [];
    let pos = 1;
    if (props.director) {
        persons.push({
            '@type': 'ListItem',
            'position': pos++,
            'item': {
                '@type': 'Person',
                'name': props.director.names,
                'jobTitle': props.director.job_position || 'Director General',
                'email': props.director.email || null,
                'telephone': props.director.phone || null,
            },
        });
    }
    props.managementStaff.forEach((s) => {
        persons.push({
            '@type': 'ListItem',
            'position': pos++,
            'item': {
                '@type': 'Person',
                'name': s.names,
                'jobTitle': s.job_position || 'Jefe de Unidad',
                'email': s.email || null,
                'telephone': s.phone || null,
            },
        });
    });
    props.coordinators.forEach((c) => {
        if (c.user) {
            persons.push({
                '@type': 'ListItem',
                'position': pos++,
                'item': {
                    '@type': 'Person',
                    'name': c.user.names,
                    'jobTitle': 'Coordinador Académico — ' + (c.program?.name || 'Programa de Estudios'),
                    'email': c.user.email || null,
                    'telephone': c.user.phone || null,
                },
            });
        }
    });

    return JSON.stringify({
        '@context': 'https://schema.org',
        '@graph': [
            {
                '@type': 'EducationalOrganization',
                'name': 'IESTP Francisco Vigo Caballero',
                'url': typeof window !== 'undefined' ? window.location.origin : '',
                'description': 'Plana jerárquica y equipo directivo del IESTP Francisco Vigo Caballero de Uchiza.',
                'address': {
                    '@type': 'PostalAddress',
                    'streetAddress': props.enterprise?.address || 'Av. Ricardo Palma N° 1401',
                    'addressLocality': props.enterprise?.city || 'Uchiza',
                    'addressRegion': 'San Martín',
                    'addressCountry': 'PE',
                },
            },
            {
                '@type': 'ItemList',
                'name': 'Plana Jerárquica IESTP Francisco Vigo Caballero',
                'numberOfItems': persons.length,
                'itemListElement': persons,
            },
        ],
    });
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Plana Jerárquica — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Conoce la plana jerárquica y el equipo directivo del IESTP Francisco Vigo Caballero en Uchiza: Dirección General, Jefaturas de Unidad, Área de Calidad, Bienestar, Investigación y Coordinadores Académicos de Carrera." />
            <meta name="keywords" content="plana jerarquica, equipo directivo, dirección general, jefe unidad académica, área de calidad, bienestar y empleabilidad, coordinadores academicos, iestp francisco vigo caballero, uchiza, san martín" />
            <meta name="robots" content="index, follow, max-image-preview:large" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Plana Jerárquica — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Conoce la plana jerárquica y el equipo directivo del IESTP Francisco Vigo Caballero en Uchiza." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <div class="min-h-screen bg-slate-50 text-slate-800 pb-20">
            <!-- HERO SECTION -->
            <section class="relative bg-slate-900 text-white pt-14 pb-24 overflow-hidden border-b border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-7">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight">
                                Plana Jerárquica y Equipo Directivo
                            </h1>
                            <p class="mt-5 text-base sm:text-lg text-slate-300 max-w-2xl font-normal leading-relaxed">
                                Las autoridades institucionales, jefes de unidad, área de calidad, bienestar, investigación y coordinadores académicos de carrera que lideran el desarrollo educativo y el licenciamiento del IESTP Francisco Vigo Caballero.
                            </p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <button
                                    type="button"
                                    @click="activeTab = 'directory'"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer"
                                >
                                    <i class="bi bi-people-fill"></i> Ver Directorio
                                </button>
                                <button
                                    type="button"
                                    @click="activeTab = 'organigrama'"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-semibold text-sm rounded-xl transition-all duration-200 backdrop-blur-md cursor-pointer"
                                >
                                    <i class="bi bi-diagram-3"></i> Ver Organigrama
                                </button>
                            </div>
                        </div>

                        <!-- Metric Counters -->
                        <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                                <span class="block text-3xl font-extrabold text-amber-400 group-hover:scale-110 transition-transform duration-200">1</span>
                                <span class="text-xs text-slate-300 font-medium mt-1.5 block">Dirección General</span>
                                <div class="mt-2 w-6 h-0.5 bg-amber-500/50 mx-auto rounded-full"></div>
                            </div>
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                                <span class="block text-3xl font-extrabold text-blue-400 group-hover:scale-110 transition-transform duration-200">{{ managementStaff.length }}</span>
                                <span class="text-xs text-slate-300 font-medium mt-1.5 block">Jefaturas y Unidades</span>
                                <div class="mt-2 w-6 h-0.5 bg-blue-500/50 mx-auto rounded-full"></div>
                            </div>
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                                <span class="block text-3xl font-extrabold text-emerald-400 group-hover:scale-110 transition-transform duration-200">{{ coordinators.length }}</span>
                                <span class="text-xs text-slate-300 font-medium mt-1.5 block">Coordinaciones de Carrera</span>
                                <div class="mt-2 w-6 h-0.5 bg-emerald-500/50 mx-auto rounded-full"></div>
                            </div>
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-5 text-center hover:bg-white/10 transition-all duration-200 group">
                                <span class="block text-3xl font-extrabold text-indigo-400 group-hover:scale-110 transition-transform duration-200">3</span>
                                <span class="text-xs text-slate-300 font-medium mt-1.5 block">Niveles Jerárquicos</span>
                                <div class="mt-2 w-6 h-0.5 bg-indigo-500/50 mx-auto rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TAB NAVIGATION -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-1.5 border border-slate-200/80 flex flex-wrap sm:flex-nowrap gap-1.5">
                    <button
                        type="button"
                        @click="activeTab = 'directory'"
                        :class="activeTab === 'directory' ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/25' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                        class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-people-fill text-base"></i>
                        <span>Directorio Directivo</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'organigrama'"
                        :class="activeTab === 'organigrama' ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/25' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                        class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-diagram-3-fill text-base"></i>
                        <span>Organigrama Estructural</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'cargos'"
                        :class="activeTab === 'cargos' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/25' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                        class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-table text-base"></i>
                        <span>Cuadro de Cargos</span>
                    </button>
                </div>
            </section>

            <!-- TAB 1: DIRECTORIO DIRECTIVO -->
            <div v-show="activeTab === 'directory'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-10">
                <!-- Search & Filter Bar -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="relative w-full md:w-80">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Buscar por nombre o cargo..."
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all"
                        />
                        <button v-if="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 flex-wrap">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1 whitespace-nowrap">Nivel:</span>
                        <button
                            type="button"
                            @click="categoryFilter = 'all'"
                            :class="categoryFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                        >
                            Todos
                        </button>
                        <button
                            type="button"
                            @click="categoryFilter = 'direccion'"
                            :class="categoryFilter === 'direccion' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                        >
                            <i class="bi bi-star-fill mr-1"></i>Alta Dirección
                        </button>
                        <button
                            type="button"
                            @click="categoryFilter = 'jefaturas'"
                            :class="categoryFilter === 'jefaturas' ? 'bg-blue-700 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                        >
                            <i class="bi bi-shield-check mr-1"></i>Jefaturas y Unidades
                        </button>
                        <button
                            type="button"
                            @click="categoryFilter = 'coordinadores'"
                            :class="categoryFilter === 'coordinadores' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                        >
                            <i class="bi bi-mortarboard-fill mr-1"></i>Coordinaciones
                        </button>
                    </div>
                </div>

                <!-- 1. ALTA DIRECCIÓN -->
                <div v-if="showDirector" class="relative rounded-3xl border-2 border-amber-400/60 p-6 sm:p-8 overflow-hidden shadow-sm bg-amber-50/40">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 relative">
                        <div class="relative flex-shrink-0">
                            <img
                                v-if="director.photo_profile_url"
                                :src="director.photo_profile_url"
                                :alt="director.names"
                                class="w-28 h-28 rounded-2xl object-cover border-4 border-white shadow-xl"
                            />
                            <div
                                v-else
                                class="w-28 h-28 rounded-2xl bg-amber-600 text-white font-extrabold text-4xl flex items-center justify-center shadow-md"
                            >
                                {{ getInitials(director.names) }}
                            </div>
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[11px] font-extrabold uppercase tracking-widest mb-3">
                                <i class="bi bi-award-fill text-amber-600"></i> PRIMER NIVEL · Máxima Autoridad Institucional
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                                {{ director.names }}
                            </h2>
                            <p class="text-base font-bold text-amber-700 mt-1">
                                {{ director.job_position || 'Director General' }}
                            </p>
                            <p class="text-sm text-slate-600 mt-2 max-w-2xl leading-relaxed">
                                Máxima autoridad del IESTP Francisco Vigo Caballero. Responsable de la conducción institucional, representación legal, planificación estratégica y licenciamiento ante el MINEDU.
                            </p>

                            <div class="mt-4 pt-4 border-t border-amber-200/60 flex flex-wrap items-center justify-center md:justify-start gap-3 text-xs">
                                <a
                                    v-if="director.email"
                                    :href="`mailto:${director.email}`"
                                    class="inline-flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-amber-200 shadow-sm text-blue-700 hover:text-blue-900 font-medium transition-colors"
                                >
                                    <i class="bi bi-envelope text-amber-600"></i> {{ director.email }}
                                </a>
                                <a
                                    v-if="director.phone"
                                    :href="`tel:${director.phone}`"
                                    class="inline-flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-amber-200 shadow-sm text-slate-700 font-medium hover:text-slate-900 transition-colors"
                                >
                                    <i class="bi bi-telephone text-amber-600"></i> {{ director.phone }}
                                </a>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <button
                                type="button"
                                @click="openDetail(director)"
                                class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer"
                            >
                                <i class="bi bi-person-badge mr-1"></i> Ver Ficha
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. JEFATURAS Y UNIDADES DE GESTIÓN -->
                <div v-if="filteredManagementStaff.length > 0" class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                        <span class="w-2.5 h-7 bg-blue-600 rounded-full"></span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                            Jefaturas y Unidades de Gestión
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="staff in filteredManagementStaff"
                            :key="staff.id"
                            class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
                        >
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <img
                                        v-if="staff.photo_profile_url"
                                        :src="staff.photo_profile_url"
                                        :alt="staff.names"
                                        class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm"
                                    />
                                    <div
                                        v-else
                                        class="w-16 h-16 rounded-xl bg-blue-600 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-sm"
                                    >
                                        {{ getInitials(staff.names) }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-base leading-tight">{{ staff.names }}</h4>
                                        <p class="text-xs font-semibold text-blue-600 mt-1">{{ staff.job_position || 'Jefatura de Unidad' }}</p>
                                    </div>
                                </div>

                                <div class="space-y-1.5 text-xs text-slate-600 pt-2 border-t border-slate-100">
                                    <p v-if="staff.email" class="flex items-center gap-2 truncate">
                                        <i class="bi bi-envelope text-blue-500"></i>
                                        <a :href="`mailto:${staff.email}`" class="hover:underline truncate">{{ staff.email }}</a>
                                    </p>
                                    <p v-if="staff.phone" class="flex items-center gap-2">
                                        <i class="bi bi-telephone text-blue-500"></i>
                                        <span>{{ staff.phone }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                                <button
                                    type="button"
                                    @click="openDetail(staff)"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1 cursor-pointer"
                                >
                                    Detalles <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. COORDINADORES ACADÉMICOS DE CARRERA -->
                <div v-if="filteredCoordinators.length > 0" class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                        <span class="w-2.5 h-7 bg-emerald-600 rounded-full"></span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                            Coordinaciones Académicas de Carrera
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="coord in filteredCoordinators"
                            :key="coord.id"
                            class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
                        >
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <img
                                        v-if="coord.user?.photo_profile_url"
                                        :src="coord.user.photo_profile_url"
                                        :alt="coord.user?.names"
                                        class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm"
                                    />
                                    <div
                                        v-else
                                        class="w-16 h-16 rounded-xl bg-emerald-600 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-sm"
                                    >
                                        {{ getInitials(coord.user?.names) }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-base leading-tight">{{ coord.user?.names }}</h4>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1">
                                            {{ coord.program?.name || 'Programa de Estudios' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-1.5 text-xs text-slate-600 pt-2 border-t border-slate-100">
                                    <p v-if="coord.user?.email" class="flex items-center gap-2 truncate">
                                        <i class="bi bi-envelope text-emerald-500"></i>
                                        <a :href="`mailto:${coord.user.email}`" class="hover:underline truncate">{{ coord.user.email }}</a>
                                    </p>
                                    <p v-if="coord.user?.phone" class="flex items-center gap-2">
                                        <i class="bi bi-telephone text-emerald-500"></i>
                                        <span>{{ coord.user.phone }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                                <button
                                    type="button"
                                    @click="openDetail(coord.user)"
                                    class="text-xs text-emerald-600 hover:text-emerald-800 font-bold flex items-center gap-1 cursor-pointer"
                                >
                                    Detalles <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ORGANIGRAMA ESTRUCTURAL -->
            <div v-show="activeTab === 'organigrama'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
                <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-8 text-center">
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">
                            Estructura de Mando
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">
                            Flujo Jerárquico Directivo
                        </h3>
                    </div>

                    <!-- Level 1: Director -->
                    <div class="flex justify-center">
                        <div class="bg-amber-50 border-2 border-amber-400 rounded-2xl p-6 max-w-sm w-full text-center shadow-sm">
                            <span class="text-[10px] font-black uppercase text-amber-700 tracking-wider">Alta Dirección</span>
                            <h4 class="font-extrabold text-slate-900 text-lg mt-1">{{ director?.names || 'Director General' }}</h4>
                            <p class="text-xs font-bold text-amber-800">{{ director?.job_position || 'Director General' }}</p>
                        </div>
                    </div>

                    <div class="w-0.5 h-8 bg-slate-300 mx-auto"></div>

                    <!-- Level 2: Jefaturas -->
                    <div>
                        <p class="text-xs font-black uppercase text-slate-400 tracking-wider mb-4">Segundo Nivel: Jefaturas de Unidad y Áreas</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
                            <div
                                v-for="s in managementStaff"
                                :key="s.id"
                                class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center shadow-xs"
                            >
                                <h5 class="text-xs font-bold text-slate-900 leading-snug">{{ s.names }}</h5>
                                <p class="text-[10px] text-blue-700 font-semibold mt-1">{{ s.job_position }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-0.5 h-8 bg-slate-300 mx-auto"></div>

                    <!-- Level 3: Coordinaciones -->
                    <div>
                        <p class="text-xs font-black uppercase text-slate-400 tracking-wider mb-4">Tercer Nivel: Coordinaciones de Carrera</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
                            <div
                                v-for="c in coordinators"
                                :key="c.id"
                                class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center shadow-xs"
                            >
                                <h5 class="text-xs font-bold text-slate-900 leading-snug">{{ c.user?.names }}</h5>
                                <p class="text-[10px] text-emerald-800 font-semibold mt-0.5">{{ c.program?.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CUADRO DE CARGOS -->
            <div v-show="activeTab === 'cargos'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6 overflow-hidden">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">Cuadro de Cargos Institucionales</h3>
                        <p class="text-sm text-slate-500 mt-1">Estructura nominal de cargos directivos, de jefatura y coordinaciones académicas.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-extrabold text-slate-600">
                                <tr>
                                    <th class="py-3 px-4">Cargo / Función</th>
                                    <th class="py-3 px-4">Titular Asignado</th>
                                    <th class="py-3 px-4">Dependencia Orgánica</th>
                                    <th class="py-3 px-4 text-right">Contacto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                <tr v-if="director" class="hover:bg-amber-50/40 transition">
                                    <td class="py-3 px-4 font-bold text-amber-900">
                                        <i class="bi bi-star-fill text-amber-500 mr-1.5"></i> Dirección General
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">{{ director.names }}</td>
                                    <td class="py-3 px-4 text-slate-500">Primer Nivel Jerárquico</td>
                                    <td class="py-3 px-4 text-right text-blue-600">{{ director.email || '—' }}</td>
                                </tr>
                                <tr v-for="s in managementStaff" :key="s.id" class="hover:bg-blue-50/40 transition">
                                    <td class="py-3 px-4 font-bold text-blue-900">
                                        <i class="bi bi-shield-check text-blue-500 mr-1.5"></i> {{ s.job_position }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">{{ s.names }}</td>
                                    <td class="py-3 px-4 text-slate-500">Segundo Nivel Jerárquico</td>
                                    <td class="py-3 px-4 text-right text-blue-600">{{ s.email || '—' }}</td>
                                </tr>
                                <tr v-for="c in coordinators" :key="c.id" class="hover:bg-emerald-50/40 transition">
                                    <td class="py-3 px-4 font-bold text-emerald-900">
                                        <i class="bi bi-mortarboard-fill text-emerald-500 mr-1.5"></i> Coordinación: {{ c.program?.name }}
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900">{{ c.user?.names }}</td>
                                    <td class="py-3 px-4 text-slate-500">Unidad Académica</td>
                                    <td class="py-3 px-4 text-right text-emerald-600">{{ c.user?.email || '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- LEADER DETAIL MODAL -->
            <div
                v-if="modalOpen && selectedLeader"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
            >
                <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl space-y-6 relative border border-slate-100">
                    <button
                        type="button"
                        @click="closeDetail"
                        class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1 cursor-pointer"
                    >
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>

                    <div class="flex items-center gap-4">
                        <img
                            v-if="selectedLeader.photo_profile_url"
                            :src="selectedLeader.photo_profile_url"
                            :alt="selectedLeader.names"
                            class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm"
                        />
                        <div
                            v-else
                            class="w-20 h-20 rounded-2xl bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-sm"
                        >
                            {{ getInitials(selectedLeader.names) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 leading-tight">{{ selectedLeader.names }}</h3>
                            <p class="text-xs font-bold text-blue-600 mt-1">{{ selectedLeader.job_position || 'Personal Directivo' }}</p>
                            <span class="inline-block text-[10px] font-semibold text-slate-500 uppercase mt-0.5">IESTP Francisco Vigo Caballero</span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-slate-100 text-sm">
                        <div v-if="selectedLeader.email" class="flex items-center gap-3">
                            <i class="bi bi-envelope text-blue-600 text-base"></i>
                            <a :href="`mailto:${selectedLeader.email}`" class="text-slate-700 hover:underline break-all">{{ selectedLeader.email }}</a>
                        </div>
                        <div v-if="selectedLeader.phone" class="flex items-center gap-3">
                            <i class="bi bi-telephone text-blue-600 text-base"></i>
                            <a :href="`tel:${selectedLeader.phone}`" class="text-slate-700">{{ selectedLeader.phone }}</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="bi bi-geo-alt text-blue-600 text-base"></i>
                            <span class="text-slate-600">Sede Principal — Uchiza, San Martín</span>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="closeDetail"
                        class="w-full py-3 bg-slate-950 hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition cursor-pointer"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
