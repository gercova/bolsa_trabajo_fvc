<script setup>
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    members: {
        type: Array,
        default: () => [],
    },
    board: {
        type: Array,
        default: () => [],
    },
    secretaries: {
        type: Array,
        default: () => [],
    },
    periods: {
        type: Array,
        default: () => [],
    },
    selectedPeriod: {
        type: String,
        default: '2026-2027',
    },
});

const onPeriodChange = (event) => {
    const period = event.target.value;
    router.get(
        route('consejo-de-estudiantes'),
        { period },
        { preserveState: true, preserveScroll: true }
    );
};

const uniqueProgramsCount = computed(() => {
    const pIds = props.members.map((m) => m.study_program_id).filter(Boolean);
    return new Set(pIds).size;
});

const isPresident = (pos) => {
    if (!pos) return false;
    const p = pos.toLowerCase();
    return p.includes('presidente') && !p.includes('vice');
};

const getInitials = (name) => {
    if (!name) return 'CE';
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((n) => n[0].toUpperCase())
        .join('');
};

const jsonLd = computed(() => {
    const schemaMembers = props.members.map((m, idx) => ({
        '@type': 'ListItem',
        'position': idx + 1,
        'item': {
            '@type': 'Person',
            'name': m.user?.names || m.name,
            'jobTitle': `${m.position} — Consejo de Estudiantes (${m.academic_period})`,
            'email': m.user?.email || null,
            'telephone': m.user?.phone || null,
            'worksFor': {
                '@type': 'EducationalOrganization',
                'name': 'IESTP Francisco Vigo Caballero',
            },
        },
    }));

    return JSON.stringify({
        '@context': 'https://schema.org',
        '@graph': [
            {
                '@type': 'EducationalOrganization',
                'name': 'IESTP Francisco Vigo Caballero',
                'url': typeof window !== 'undefined' ? window.location.origin : '',
                'description': 'Consejo de Estudiantes del IESTP Francisco Vigo Caballero de Uchiza.',
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
                'name': `Consejo de Estudiantes ${props.selectedPeriod} — IESTP Francisco Vigo Caballero`,
                'numberOfItems': schemaMembers.length,
                'itemListElement': schemaMembers,
            },
        ],
    });
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>{{ `Consejo de Estudiantes (${selectedPeriod}) — IESTP Francisco Vigo Caballero` }}</title>
            <meta name="description" content="Conoce a los representantes electos del Consejo de Estudiantes del IESTP Francisco Vigo Caballero en Uchiza. Liderazgo, defensa de derechos y gestión estudiantil." />
            <meta name="keywords" content="consejo de estudiantes, consejo estudiantil, representantes de estudiantes, iestp francisco vigo caballero, uchiza, san martin, educacion tecnica, liderazgo estudiantil" />
            <meta name="robots" content="index, follow" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Consejo de Estudiantes — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Representantes estudiantiles del IESTP Francisco Vigo Caballero." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                    Consejo de <span class="text-sky-400">Estudiantes</span>
                </h1>

                <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                    Voz oficial, liderazgo democrático y representación activa de los alumnos del IESTP Francisco Vigo Caballero.
                </p>

                <!-- Metrics & Management Selector Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ members.length }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Representantes Electos</p>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ uniqueProgramsCount }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Programas de Estudio</p>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ secretaries.length }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Secretarías de Gestión</p>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300 flex flex-col justify-center">
                        <div v-if="periods.length > 1" class="w-full">
                            <select
                                :value="selectedPeriod"
                                @change="onPeriodChange"
                                class="w-full bg-slate-900/90 border border-slate-700 text-sky-400 font-extrabold text-sm sm:text-base rounded-xl px-3 py-2 text-center focus:ring-2 focus:ring-sky-400 cursor-pointer"
                            >
                                <option v-for="period in periods" :key="period" :value="period">
                                    Gestión {{ period }}
                                </option>
                            </select>
                        </div>
                        <p v-else class="text-2xl sm:text-3xl font-black text-sky-400">{{ selectedPeriod }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Período Institucional</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- JUNTA DIRECTIVA SECTION -->
        <section v-if="board.length > 0" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        Liderazgo Directivo
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Junta Directiva Principal
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                        Representantes que lideran el diálogo institucional, coordinan las asambleas generales y representan oficialmente al alumnado.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <div
                        v-for="leader in board"
                        :key="leader.id"
                        class="bg-white rounded-3xl border border-slate-100 shadow-md hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full group"
                    >
                        <!-- Header Banner & Avatar -->
                        <div class="h-48 relative overflow-hidden bg-slate-900 flex items-center justify-center p-6 border-b border-slate-800">
                            <!-- Position Tag -->
                            <div class="absolute top-4 left-4 z-20">
                                <span
                                    :class="isPresident(leader.position) ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-slate-100 text-slate-800 border-slate-200'"
                                    class="px-3.5 py-1.5 text-xs font-black rounded-lg uppercase tracking-wider shadow border"
                                >
                                    <i :class="isPresident(leader.position) ? 'bi-award-fill' : 'bi-shield-check'" class="bi mr-1.5"></i>
                                    {{ leader.position }}
                                </span>
                            </div>

                            <!-- Period Tag -->
                            <div class="absolute top-4 right-4 z-20">
                                <span class="px-3 py-1 text-xs font-bold text-slate-300 bg-white/10 rounded-lg backdrop-blur-md">
                                    Gestión {{ leader.academic_period }}
                                </span>
                            </div>

                            <!-- Avatar / Photo -->
                            <div class="relative z-10 flex flex-col items-center mt-4">
                                <img
                                    v-if="leader.user?.photo_profile_url"
                                    :src="leader.user.photo_profile_url"
                                    :alt="leader.user?.names || leader.name"
                                    class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white/20 shadow-xl group-hover:scale-105 transition-transform duration-500"
                                />
                                <div
                                    v-else
                                    class="w-24 h-24 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-black text-3xl ring-4 ring-white/20 shadow-sm"
                                >
                                    {{ getInitials(leader.user?.names || leader.name) }}
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-8 flex flex-col flex-grow space-y-6">
                            <div class="text-center">
                                <h3 class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-tight mb-2">
                                    {{ leader.user?.names || leader.name }}
                                </h3>
                                <div
                                    :class="isPresident(leader.position) ? 'bg-blue-600' : 'bg-sky-500'"
                                    class="w-16 h-1 rounded-full mx-auto"
                                ></div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2.5">
                                <div v-if="leader.study_program || leader.studyProgram" class="flex items-center gap-2.5 text-sm font-bold text-slate-800">
                                    <i class="bi bi-mortarboard-fill text-blue-600 text-base shrink-0"></i>
                                    <span>{{ leader.studyProgram?.name || leader.study_program?.name }}</span>
                                </div>

                                <div v-if="leader.user?.phone" class="flex items-center gap-2.5 text-sm font-bold text-slate-700">
                                    <i class="bi bi-telephone-fill text-blue-600 text-base shrink-0"></i>
                                    <span>Teléfono: {{ leader.user.phone }}</span>
                                </div>
                            </div>

                            <div v-if="leader.user?.email" class="pt-2">
                                <a
                                    :href="`mailto:${leader.user.email}`"
                                    class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-black text-white bg-slate-950 hover:bg-blue-600 rounded-xl transition duration-200 shadow-md hover:shadow-lg"
                                >
                                    <i class="bi bi-envelope-fill mr-2"></i> Contactar Representante
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECRETARÍAS DE GESTIÓN SECTION -->
        <section v-if="secretaries.length > 0" class="py-24 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-sky-100 text-sky-800 uppercase tracking-wider">
                        Comisiones de Trabajo
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Secretarías y Delegados
                    </h2>
                    <p class="text-lg text-slate-600 mt-4 leading-relaxed">
                        Equipos encargados del bienestar estudiantil, actividades académicas, deporte, cultura y defensa de los derechos de los alumnos.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="sec in secretaries"
                        :key="sec.id"
                        class="bg-slate-50 border border-slate-200 rounded-3xl p-6 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
                    >
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <img
                                    v-if="sec.user?.photo_profile_url"
                                    :src="sec.user.photo_profile_url"
                                    :alt="sec.user?.names || sec.name"
                                    class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm"
                                />
                                <div
                                    v-else
                                    class="w-16 h-16 rounded-2xl bg-sky-600 text-white font-extrabold text-xl flex items-center justify-center shrink-0 shadow-sm"
                                >
                                    {{ getInitials(sec.user?.names || sec.name) }}
                                </div>
                                <div class="space-y-1">
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-sky-100 text-sky-900 border border-sky-200 uppercase tracking-wider">
                                        {{ sec.position }}
                                    </span>
                                    <h4 class="font-extrabold text-slate-900 text-base leading-snug">{{ sec.user?.names || sec.name }}</h4>
                                    <p class="text-xs font-semibold text-slate-500">{{ sec.studyProgram?.name || sec.study_program?.name || 'Estudiante Representante' }}</p>
                                </div>
                            </div>
                        </div>

                        <div v-if="sec.user?.email" class="pt-4 mt-4 border-t border-slate-200">
                            <a
                                :href="`mailto:${sec.user.email}`"
                                class="text-xs text-slate-600 hover:text-blue-600 font-semibold flex items-center gap-1.5 truncate"
                            >
                                <i class="bi bi-envelope"></i>
                                <span class="truncate">{{ sec.user.email }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FUNCIONES Y ATRIBUCIONES -->
        <section class="py-20 bg-slate-900 text-white border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-sky-400">
                        Estatuto del Alumnado
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white mt-2">
                        Funciones del Consejo de Estudiantes
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base mt-3">
                        El Consejo Estudiantil canaliza inquietudes, impulsa la vida académica y garantiza una comunidad participativa.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-3">
                        <i class="bi bi-shield-check text-sky-400 text-3xl"></i>
                        <h3 class="font-bold text-white text-base">Defensa de Derechos</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Velar por el estricto cumplimiento de los derechos de los estudiantes y el reglamento académico institucional.
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-3">
                        <i class="bi bi-chat-dots text-sky-400 text-3xl"></i>
                        <h3 class="font-bold text-white text-base">Diálogo Institucional</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Representar la voz del alumnado ante la Dirección General, Unidad Académica y comisiones de calidad.
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-3">
                        <i class="bi bi-calendar-event text-sky-400 text-3xl"></i>
                        <h3 class="font-bold text-white text-base">Cultura y Deporte</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Fomentar la integración a través de ferias tecnológicas, olimpiadas deportivas y actividades artísticas.
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-3">
                        <i class="bi bi-people text-sky-400 text-3xl"></i>
                        <h3 class="font-bold text-white text-base">Bienestar Estudiantil</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Coordinar con la Unidad de Bienestar y Empleabilidad para soporte psicosocial, becas y tutorías.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
