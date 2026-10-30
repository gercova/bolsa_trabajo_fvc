<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    teacherDetails: {
        type: Array,
        default: () => [],
    },
    programs: {
        type: Array,
        default: () => [],
    },
    transversalTeachers: {
        type: Array,
        default: () => [],
    },
    unassignedTeachers: {
        type: Array,
        default: () => [],
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const selectedProgram = ref('all');
const searchQuery = ref('');
const activeTeacher = ref(null);
const showModal = ref(false);

const transversalList = computed(() => {
    return props.transversalTeachers?.length ? props.transversalTeachers : (props.unassignedTeachers || []);
});

const totalTeachersCount = computed(() => {
    const assignedIds = props.teacherDetails.map((d) => d.user_id).filter(Boolean);
    const transIds = transversalList.value.map((t) => t.id).filter(Boolean);
    return new Set([...assignedIds, ...transIds]).size;
});

const coordinators = computed(() => {
    return props.teacherDetails.filter((d) => d.is_coordinator && d.user);
});

const programMeta = {
    'Producción Agropecuaria': {
        icon: 'bi-tree-fill',
        badge: 'bg-emerald-50 text-emerald-800 border-emerald-100',
        tag: 'Producción & Campo',
        bar: 'bg-emerald-500',
        avatar: 'bg-emerald-600 text-white',
    },
    'Enfermería Técnica': {
        icon: 'bi-heart-pulse-fill',
        badge: 'bg-rose-50 text-rose-800 border-rose-100',
        tag: 'Ciencias de la Salud',
        bar: 'bg-rose-500',
        avatar: 'bg-rose-600 text-white',
    },
    'Administración de Redes y Comunicaciones': {
        icon: 'bi-router-fill',
        badge: 'bg-sky-50 text-sky-800 border-sky-100',
        tag: 'Soporte e Infraestructura TI',
        bar: 'bg-sky-500',
        avatar: 'bg-sky-600 text-white',
    },
    'Asistencia Administrativa': {
        icon: 'bi-briefcase-fill',
        badge: 'bg-amber-50 text-amber-800 border-amber-100',
        tag: 'Administración & Finanzas',
        bar: 'bg-amber-600',
        avatar: 'bg-amber-600 text-white',
    },
    'Manejo Forestal': {
        icon: 'bi-globe-americas',
        badge: 'bg-teal-50 text-teal-800 border-teal-100',
        tag: 'Recursos Naturales',
        bar: 'bg-teal-500',
        avatar: 'bg-teal-700 text-white',
    },
};

const defaultMeta = {
    icon: 'bi-mortarboard-fill',
    badge: 'bg-blue-50 text-blue-800 border-blue-100',
    tag: 'Educación Técnica',
    bar: 'bg-blue-500',
    avatar: 'bg-blue-700 text-white',
};

const getMeta = (progName) => {
    return programMeta[progName] || defaultMeta;
};

const getInitials = (name) => {
    if (!name) return 'PF';
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((p) => p[0].toUpperCase())
        .join('');
};

const matchesSearch = (name, specialty, position, programName) => {
    if (!searchQuery.value.trim()) return true;
    const q = searchQuery.value.toLowerCase().trim();
    const n = (name || '').toLowerCase();
    const s = (specialty || '').toLowerCase();
    const p = (position || '').toLowerCase();
    const pr = (programName || '').toLowerCase();
    return n.includes(q) || s.includes(q) || p.includes(q) || pr.includes(q);
};

const openTeacherModal = (name, role, program, specialty, email, photo, isCoordinator, cvUrl) => {
    activeTeacher.value = {
        name,
        role: role || 'Docente Institucional',
        program: program || 'IESTP Francisco Vigo Caballero',
        specialty: specialty || 'Educación Técnica Superior',
        email: email || '',
        photo: photo || '',
        isCoordinator: Boolean(isCoordinator),
        cvUrl: cvUrl || '',
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    activeTeacher.value = null;
};

const filteredProgramsWithTeachers = computed(() => {
    if (selectedProgram.value === 'transversal') return [];

    return props.programs
        .filter((prog) => {
            if (selectedProgram.value !== 'all' && selectedProgram.value !== prog.slug) {
                return false;
            }
            return true;
        })
        .map((prog) => {
            const details = props.teacherDetails.filter(
                (d) => d.program_id === prog.id && d.user && matchesSearch(d.user.names, d.specialty, d.user.job_position, prog.name)
            );
            return {
                program: prog,
                details,
            };
        })
        .filter((group) => group.details.length > 0);
});

const filteredTransversalTeachers = computed(() => {
    if (selectedProgram.value !== 'all' && selectedProgram.value !== 'transversal') {
        return [];
    }
    return transversalList.value.filter((t) =>
        matchesSearch(t.names, t.primary_role_detail?.specialty, t.job_position, 'Formación General y Transversal')
    );
});

const jsonLd = computed(() => {
    const schemaItemList = [];
    let pos = 1;

    props.teacherDetails.forEach((detail) => {
        if (detail.user) {
            schemaItemList.push({
                '@type': 'ListItem',
                'position': pos++,
                'item': {
                    '@type': 'Person',
                    'name': detail.user.names,
                    'jobTitle': detail.user.job_position || (detail.is_coordinator ? 'Coordinador Académico' : 'Docente'),
                    'email': detail.user.email || null,
                    'worksFor': {
                        '@type': 'EducationalOrganization',
                        'name': 'IESTP Francisco Vigo Caballero',
                    },
                    'knowsAbout': detail.specialty || (detail.program ? detail.program.name : 'Educación Técnica Superior'),
                },
            });
        }
    });

    transversalList.value.forEach((tTeacher) => {
        schemaItemList.push({
            '@type': 'ListItem',
            'position': pos++,
            'item': {
                '@type': 'Person',
                'name': tTeacher.names,
                'jobTitle': tTeacher.job_position || 'Docente de Formación Transversal',
                'email': tTeacher.email || null,
                'worksFor': {
                    '@type': 'EducationalOrganization',
                    'name': 'IESTP Francisco Vigo Caballero',
                },
                'knowsAbout': 'Formación General y Módulos Transversales',
            },
        });
    });

    return JSON.stringify({
        '@context': 'https://schema.org',
        '@graph': [
            {
                '@type': 'EducationalOrganization',
                'name': 'IESTP Francisco Vigo Caballero',
                'url': typeof window !== 'undefined' ? window.location.origin : '',
                'description': 'Plana docente y equipo de coordinadores académicos del IESTP Francisco Vigo Caballero en Uchiza.',
                'department': props.programs.map((program) => ({
                    '@type': 'AcademicDepartment',
                    'name': program.name,
                    'url': `${typeof window !== 'undefined' ? window.location.origin : ''}/programas-de-estudios/${program.slug}`,
                })),
            },
            {
                '@type': 'ItemList',
                'name': 'Plana Docente IESTP Francisco Vigo Caballero',
                'numberOfItems': schemaItemList.length,
                'itemListElement': schemaItemList,
            },
        ],
    });
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Plana Docente y Equipo Académico — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Conoce a la plana docente y equipo de coordinadores académicos del IESTP Francisco Vigo Caballero. Profesionales experimentados comprometidos con la educación técnica de excelencia en Uchiza." />
            <meta name="keywords" content="plana docente, profesores iestp, coordinadores academicos, educadores tecnicos, francisco vigo caballero, uchiza, docentes produccion agropecuaria, docentes enfermeria tecnica, docentes redes, docentes administracion, docentes manejo forestal, docentes transversales" />
            <meta name="robots" content="index, follow, max-image-preview:large" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Plana Docente y Equipo Académico — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Conoce a nuestros educadores y coordinadores de carrera altamente calificados, comprometidos con la excelencia en la educación técnica superior." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <div class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased pb-20">
            <!-- HERO SECTION -->
            <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
                    <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                        Plana Docente y <span class="text-sky-400">Equipo Académico</span>
                    </h1>

                    <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                        Profesionales capacitados y educadores experimentados en el sector productivo, dedicados a guiar la formación profesional técnica de nuestros estudiantes.
                    </p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                            <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ totalTeachersCount }}</p>
                            <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Docentes en Plana</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                            <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ coordinators.length }}</p>
                            <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Coordinadores de Carrera</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                            <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ programs.length }}</p>
                            <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Programas de Estudio</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                            <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ transversalList.length }}</p>
                            <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Formación Transversal</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FILTER CONTROLS BAR -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-4 border border-slate-200/80 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        <div class="md:col-span-5 relative">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Buscar por docente, especialidad o carrera..."
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all"
                            />
                        </div>

                        <div class="md:col-span-7 flex flex-wrap gap-2 items-center">
                            <button
                                type="button"
                                @click="selectedProgram = 'all'"
                                :class="selectedProgram === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer"
                            >
                                Todos ({{ totalTeachersCount }})
                            </button>
                            <button
                                v-for="prog in programs"
                                :key="prog.id"
                                type="button"
                                @click="selectedProgram = prog.slug"
                                :class="selectedProgram === prog.slug ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer"
                            >
                                {{ prog.name }}
                            </button>
                            <button
                                type="button"
                                @click="selectedProgram = 'transversal'"
                                :class="selectedProgram === 'transversal' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer"
                            >
                                Transversales ({{ transversalList.length }})
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MAIN CONTENT -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 space-y-16">

                <!-- SECTION 1: COORDINADORES DESTACADOS -->
                <div v-if="coordinators.length > 0 && selectedProgram === 'all' && !searchQuery.trim()" class="space-y-8">
                    <div class="border-b border-slate-200 pb-4 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Liderazgo de Carrera</span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">
                                Coordinadores Académicos
                            </h2>
                        </div>
                        <span class="text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 px-3 py-1.5 rounded-full">
                            {{ coordinators.length }} Carreras Profesionales
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="coord in coordinators"
                            :key="coord.id"
                            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between"
                        >
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <img
                                        v-if="coord.user?.photo_profile_url"
                                        :src="coord.user.photo_profile_url"
                                        :alt="coord.user?.names"
                                        class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm"
                                    />
                                    <div
                                        v-else
                                        class="w-16 h-16 rounded-2xl bg-amber-600 text-white font-extrabold text-xl flex items-center justify-center shrink-0 shadow-sm"
                                    >
                                        {{ getInitials(coord.user?.names) }}
                                    </div>
                                    <div class="space-y-1">
                                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-amber-900 border border-amber-200 uppercase tracking-wider">
                                            Coordinador
                                        </span>
                                        <h3 class="font-extrabold text-slate-900 text-base leading-snug">{{ coord.user?.names }}</h3>
                                        <p class="text-xs font-semibold text-blue-600">{{ coord.program?.name }}</p>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-500 leading-relaxed">
                                    {{ coord.specialty || 'Especialista a cargo de la gestión académica y formativa del programa.' }}
                                </p>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                <a
                                    v-if="coord.user?.email"
                                    :href="`mailto:${coord.user.email}`"
                                    class="text-xs text-slate-500 hover:text-blue-600 flex items-center gap-1.5 truncate max-w-[180px]"
                                >
                                    <i class="bi bi-envelope"></i>
                                    <span class="truncate">{{ coord.user.email }}</span>
                                </a>
                                <button
                                    type="button"
                                    @click="openTeacherModal(coord.user.names, coord.user.job_position, coord.program?.name, coord.specialty, coord.user.email, coord.user.photo_profile_url, true, coord.user.cv_file_url)"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition cursor-pointer"
                                >
                                    Ver Ficha
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DOCENTES POR PROGRAMA DE ESTUDIOS -->
                <div v-for="group in filteredProgramsWithTeachers" :key="group.program.id" class="space-y-8">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                                <i :class="`bi ${getMeta(group.program.name).icon}`" class="text-2xl"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Programa de Estudios</span>
                                <h2 class="text-2xl font-black text-slate-900">{{ group.program.name }}</h2>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                            {{ group.details.length }} Docentes
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="detail in group.details"
                            :key="detail.id"
                            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between"
                        >
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <img
                                        v-if="detail.user?.photo_profile_url"
                                        :src="detail.user.photo_profile_url"
                                        :alt="detail.user?.names"
                                        class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm"
                                    />
                                    <div
                                        v-else
                                        :class="`w-16 h-16 rounded-2xl ${getMeta(group.program.name).avatar} font-extrabold text-xl flex items-center justify-center shrink-0 shadow-sm`"
                                    >
                                        {{ getInitials(detail.user?.names) }}
                                    </div>
                                    <div class="space-y-1">
                                        <span v-if="detail.is_coordinator" class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-100 text-amber-900">
                                            Coordinador
                                        </span>
                                        <h3 class="font-extrabold text-slate-900 text-base leading-snug">{{ detail.user?.names }}</h3>
                                        <p class="text-xs font-semibold text-slate-500">{{ detail.user?.job_position || 'Docente de Especialidad' }}</p>
                                    </div>
                                </div>

                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Especialidad</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5 leading-snug">{{ detail.specialty || 'Especialista del Programa' }}</p>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                <a
                                    v-if="detail.user?.email"
                                    :href="`mailto:${detail.user.email}`"
                                    class="text-xs text-slate-500 hover:text-blue-600 flex items-center gap-1.5 truncate max-w-[160px]"
                                >
                                    <i class="bi bi-envelope"></i>
                                    <span class="truncate">{{ detail.user.email }}</span>
                                </a>
                                <span v-else class="text-xs text-slate-400">Docente</span>

                                <div class="flex items-center gap-2">
                                    <a
                                        v-if="detail.user?.cv_file_url"
                                        :href="detail.user.cv_file_url"
                                        target="_blank"
                                        download
                                        title="Descargar CV"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-slate-100 transition"
                                    >
                                        <i class="bi bi-file-earmark-person text-base"></i>
                                    </a>
                                    <button
                                        type="button"
                                        @click="openTeacherModal(detail.user.names, detail.user.job_position, group.program.name, detail.specialty, detail.user.email, detail.user.photo_profile_url, detail.is_coordinator, detail.user.cv_file_url)"
                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg transition cursor-pointer"
                                    >
                                        Ver Ficha
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: DOCENTES DE FORMACIÓN GENERAL Y TRANSVERSAL -->
                <div v-if="filteredTransversalTeachers.length > 0" class="space-y-8">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                                <i class="bi bi-diagram-3-fill text-2xl"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Área Transversal</span>
                                <h2 class="text-2xl font-black text-slate-900">Formación General y Módulos Transversales</h2>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700">
                            {{ filteredTransversalTeachers.length }} Docentes
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="teacher in filteredTransversalTeachers"
                            :key="teacher.id"
                            class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between"
                        >
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <img
                                        v-if="teacher.photo_profile_url"
                                        :src="teacher.photo_profile_url"
                                        :alt="teacher.names"
                                        class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm"
                                    />
                                    <div
                                        v-else
                                        class="w-16 h-16 rounded-2xl bg-indigo-700 text-white font-extrabold text-xl flex items-center justify-center shrink-0 shadow-sm"
                                    >
                                        {{ getInitials(teacher.names) }}
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-extrabold text-slate-900 text-base leading-snug">{{ teacher.names }}</h3>
                                        <p class="text-xs font-semibold text-indigo-700">{{ teacher.job_position || 'Docente de Formación Transversal' }}</p>
                                    </div>
                                </div>

                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Especialidad</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5 leading-snug">
                                        {{ teacher.primary_role_detail?.specialty || 'Habilidades Blandas, Comunicación, TIC & Empleabilidad' }}
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                <a
                                    v-if="teacher.email"
                                    :href="`mailto:${teacher.email}`"
                                    class="text-xs text-slate-500 hover:text-blue-600 flex items-center gap-1.5 truncate max-w-[160px]"
                                >
                                    <i class="bi bi-envelope"></i>
                                    <span class="truncate">{{ teacher.email }}</span>
                                </a>
                                <span v-else class="text-xs text-slate-400">Docente</span>

                                <div class="flex items-center gap-2">
                                    <a
                                        v-if="teacher.cv_file_url"
                                        :href="teacher.cv_file_url"
                                        target="_blank"
                                        download
                                        title="Descargar CV"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-slate-100 transition"
                                    >
                                        <i class="bi bi-file-earmark-person text-base"></i>
                                    </a>
                                    <button
                                        type="button"
                                        @click="openTeacherModal(teacher.names, teacher.job_position, 'Formación General y Transversal', teacher.primary_role_detail?.specialty, teacher.email, teacher.photo_profile_url, false, teacher.cv_file_url)"
                                        class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition cursor-pointer"
                                    >
                                        Ver Ficha
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Search Result -->
                <div
                    v-if="filteredProgramsWithTeachers.length === 0 && filteredTransversalTeachers.length === 0"
                    class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto space-y-4"
                >
                    <div class="w-16 h-16 mx-auto bg-slate-100 text-slate-400 rounded-full flex items-center justify-center">
                        <i class="bi bi-person-slash text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">No se encontraron docentes</h3>
                        <p class="text-xs text-slate-500 mt-1">Intente buscando con otros términos o seleccione otro programa.</p>
                    </div>
                    <button
                        type="button"
                        @click="searchQuery = ''; selectedProgram = 'all'"
                        class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition cursor-pointer"
                    >
                        Restablecer Filtros
                    </button>
                </div>
            </div>

            <!-- TEACHER DETAIL MODAL -->
            <div
                v-if="showModal && activeTeacher"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
            >
                <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl space-y-6 relative border border-slate-100">
                    <button
                        type="button"
                        @click="closeModal"
                        class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1 cursor-pointer"
                    >
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>

                    <div class="flex items-center gap-4">
                        <img
                            v-if="activeTeacher.photo"
                            :src="activeTeacher.photo"
                            :alt="activeTeacher.name"
                            class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm"
                        />
                        <div
                            v-else
                            class="w-20 h-20 rounded-2xl bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-sm"
                        >
                            {{ getInitials(activeTeacher.name) }}
                        </div>
                        <div>
                            <span v-if="activeTeacher.isCoordinator" class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-100 text-amber-900 mb-1">
                                Coordinador Académico
                            </span>
                            <h3 class="text-xl font-black text-slate-900 leading-tight">{{ activeTeacher.name }}</h3>
                            <p class="text-xs font-bold text-blue-600 mt-0.5">{{ activeTeacher.role }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ activeTeacher.program }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-slate-100 text-sm">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Especialidad Formativa</p>
                            <p class="text-xs font-bold text-slate-800 mt-1 leading-snug">{{ activeTeacher.specialty }}</p>
                        </div>

                        <div v-if="activeTeacher.email" class="flex items-center gap-3 text-xs">
                            <i class="bi bi-envelope text-blue-600 text-base"></i>
                            <a :href="`mailto:${activeTeacher.email}`" class="text-slate-700 hover:underline break-all">{{ activeTeacher.email }}</a>
                        </div>

                        <div v-if="activeTeacher.cvUrl" class="pt-2">
                            <a
                                :href="activeTeacher.cvUrl"
                                target="_blank"
                                download
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl transition"
                            >
                                <i class="bi bi-file-earmark-pdf text-base"></i> Descargar Currículum Vitae (CV)
                            </a>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="closeModal"
                        class="w-full py-3 bg-slate-950 hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition cursor-pointer"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
