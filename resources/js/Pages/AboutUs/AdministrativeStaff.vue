<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    staffs: {
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
const levelFilter = ref('all');
const selectedStaff = ref(null);
const modalOpen = ref(false);

const openDetail = (staff) => {
    selectedStaff.value = staff;
    modalOpen.value = true;
};

const closeDetail = () => {
    modalOpen.value = false;
    selectedStaff.value = null;
};

const getInitials = (name) => {
    if (!name) return 'AD';
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((p) => p[0].toUpperCase())
        .join('');
};

const isDirectivo = (pos) => {
    if (!pos) return false;
    const p = pos.toLowerCase();
    return p.includes('director') || p.includes('administrador') || p.includes('jefe') || p.includes('jefatura');
};

const filteredStaffs = computed(() => {
    return props.staffs.filter((s) => {
        // Filter by level
        if (levelFilter.value === 'directivos' && !isDirectivo(s.job_position)) return false;
        if (levelFilter.value === 'apoyo' && isDirectivo(s.job_position)) return false;

        // Search query
        if (!searchQuery.value.trim()) return true;
        const q = searchQuery.value.toLowerCase().trim();
        const n = (s.names || '').toLowerCase();
        const p = (s.job_position || '').toLowerCase();
        const e = (s.email || '').toLowerCase();
        return n.includes(q) || p.includes(q) || e.includes(q);
    });
});

const officialPositions = [
    { code: 'CAP-01', position: 'Director General', level: 'Directivo', dependency: 'Dirección Regional de Educación', function: 'Conducción institucional y representación legal del IESTP.' },
    { code: 'CAP-02', position: 'Secretaria de Dirección', level: 'Apoyo', dependency: 'Dirección General', function: 'Gestión documentaria y agenda de la dirección institucional.' },
    { code: 'CAP-03', position: 'Administrador Institucional', level: 'Jefatura', dependency: 'Dirección General', function: 'Gestión presupuestal, financiera y de recursos humanos.' },
    { code: 'CAP-04', position: 'Jefe de Unidad Académica', level: 'Jefatura', dependency: 'Dirección General', function: 'Planificación, ejecución y supervisión de los planes curriculares.' },
    { code: 'CAP-05', position: 'Secretaría Académica', level: 'Apoyo', dependency: 'Unidad Académica', function: 'Control de matrículas, actas, notas y emisión de certificados.' },
    { code: 'CAP-06', position: 'Jefe de Área de Calidad', level: 'Jefatura', dependency: 'Dirección General', function: 'Monitoreo de CBC, licenciamiento y aseguramiento de la calidad.' },
    { code: 'CAP-07', position: 'Jefe de Unidad de Investigación', level: 'Jefatura', dependency: 'Dirección General', function: 'Promoción de la innovación tecnológica e investigación aplicada.' },
    { code: 'CAP-08', position: 'Jefe de Bienestar y Empleabilidad', level: 'Jefatura', dependency: 'Dirección General', function: 'Bolsa de trabajo, seguimiento a egresados y servicios al estudiante.' },
    { code: 'CAP-09', position: 'Responsable de Abastecimiento', level: 'Apoyo', dependency: 'Administración', function: 'Adquisiciones, contrataciones y suministros institucionales.' },
    { code: 'CAP-10', position: 'Responsable de Patrimonio', level: 'Apoyo', dependency: 'Administración', function: 'Control inventarial de bienes muebles e inmuebles.' },
    { code: 'CAP-11', position: 'Tesorero', level: 'Apoyo', dependency: 'Administración', function: 'Recaudación de tasas educativas TUPA y flujo de caja.' },
    { code: 'CAP-12', position: 'Asistente de Campo', level: 'Técnico', dependency: 'Unidad Académica / Agropecuaria', function: 'Mantenimiento de módulos productivos y parcelas demostrativas.' },
    { code: 'CAP-13', position: 'Guardianía y Seguridad', level: 'Servicio', dependency: 'Administración', function: 'Custodia diurna y nocturna de las instalaciones del campus.' },
    { code: 'CAP-14', position: 'Personal de Limpieza y Mantenimiento', level: 'Servicio', dependency: 'Administración', function: 'Higiene y mantenimiento preventivo de aulas y laboratorios.' },
];

const jsonLd = computed(() => {
    const schemaItemList = props.staffs.map((staff, idx) => ({
        '@type': 'ListItem',
        'position': idx + 1,
        'item': {
            '@type': 'Person',
            'name': staff.names,
            'jobTitle': staff.job_position || 'Personal Administrativo',
            'email': staff.email || null,
            'telephone': staff.phone || null,
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
                'description': 'Plana administrativa y personal de gestión del IESTP Francisco Vigo Caballero de Uchiza.',
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
                'name': 'Plana Administrativa IESTP Francisco Vigo Caballero',
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
            <title>Plana Administrativa — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Conoce a la plana administrativa, cuerpo directivo y equipo de soporte del IESTP Francisco Vigo Caballero en Uchiza. Estructura organizacional, organigrama y cuadro de cargos institucionales." />
            <meta name="keywords" content="plana administrativa, equipo administrativo, dirección general, organigrama institucional, personal de apoyo, iestp francisco vigo caballero, uchiza, gestión académica" />
            <meta name="robots" content="index, follow, max-image-preview:large" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Plana Administrativa y Equipo de Gestión — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Conoce a nuestro equipo directivo, administrativo y de soporte comprometido con el desarrollo institucional y la atención de calidad en Uchiza." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <div class="min-h-screen bg-slate-50 text-slate-800 pb-20">
            <!-- HERO SECTION -->
            <section class="relative bg-slate-900 text-white pt-14 pb-20 overflow-hidden border-b border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight font-sans leading-tight">
                                Plana Administrativa y Equipo de Gestión
                            </h1>
                            <p class="mt-4 text-base sm:text-lg text-slate-300 max-w-2xl font-normal leading-relaxed">
                                Conoce al personal directivo, administrativo y de servicios que impulsa la gestión institucional, garantizando una atención eficiente, transparente y de excelencia a nuestra comunidad educativa.
                            </p>
                        </div>

                        <!-- Metric Counters -->
                        <div class="lg:col-span-4 grid grid-cols-2 gap-4">
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                                <span class="block text-3xl font-extrabold text-amber-400">36</span>
                                <span class="text-xs text-slate-300 font-medium mt-1 block">Plazas Institucionales</span>
                            </div>
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                                <span class="block text-3xl font-extrabold text-blue-400">3</span>
                                <span class="text-xs text-slate-300 font-medium mt-1 block">Niveles Organizacionales</span>
                            </div>
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                                <span class="block text-3xl font-extrabold text-emerald-400">{{ staffs.length }}</span>
                                <span class="text-xs text-slate-300 font-medium mt-1 block">Personal Registrado</span>
                            </div>
                            <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-4 text-center hover:bg-white/10 transition-colors">
                                <span class="block text-3xl font-extrabold text-indigo-400">5</span>
                                <span class="text-xs text-slate-300 font-medium mt-1 block">Programas Atendidos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MAIN NAVIGATION TABS -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-20">
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-2 border border-slate-200/80 flex flex-wrap sm:flex-nowrap gap-2">
                    <button
                        type="button"
                        @click="activeTab = 'directory'"
                        :class="activeTab === 'directory' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                        class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-people-fill text-base"></i>
                        <span>Directorio de Personal</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'organigram'"
                        :class="activeTab === 'organigram' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                        class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-diagram-3-fill text-base"></i>
                        <span>Estructura Organigrama</span>
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'cargos'"
                        :class="activeTab === 'cargos' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                        class="flex-1 py-3 px-4 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-table text-base"></i>
                        <span>Cuadro Resumen de Cargos</span>
                    </button>
                </div>
            </section>

            <!-- TAB 1: STAFF DIRECTORY -->
            <div v-show="activeTab === 'directory'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
                <!-- Filter & Search Header -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="relative w-full md:w-96">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Buscar por nombre, cargo o correo..."
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                        />
                        <button v-if="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap mr-1">Filtrar:</span>
                        <button
                            type="button"
                            @click="levelFilter = 'all'"
                            :class="levelFilter === 'all' ? 'bg-slate-900 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap cursor-pointer"
                        >
                            Todos ({{ staffs.length }})
                        </button>
                        <button
                            type="button"
                            @click="levelFilter = 'directivos'"
                            :class="levelFilter === 'directivos' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap cursor-pointer"
                        >
                            Dirección & Jefaturas
                        </button>
                        <button
                            type="button"
                            @click="levelFilter = 'apoyo'"
                            :class="levelFilter === 'apoyo' ? 'bg-amber-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap cursor-pointer"
                        >
                            Asistentes & Apoyo
                        </button>
                    </div>
                </div>

                <!-- Cards Grid -->
                <div v-if="filteredStaffs.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="staff in filteredStaffs"
                        :key="staff.id"
                        class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between"
                    >
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <img
                                    v-if="staff.photo_profile_url"
                                    :src="staff.photo_profile_url"
                                    :alt="staff.names"
                                    class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm"
                                />
                                <div
                                    v-else
                                    :class="`w-16 h-16 rounded-2xl ${isDirectivo(staff.job_position) ? 'bg-blue-600' : 'bg-slate-800'} text-white font-extrabold text-xl flex items-center justify-center shrink-0 shadow-sm`"
                                >
                                    {{ getInitials(staff.names) }}
                                </div>
                                <div class="space-y-1">
                                    <span
                                        :class="isDirectivo(staff.job_position) ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200'"
                                        class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider border"
                                    >
                                        {{ isDirectivo(staff.job_position) ? 'Jefatura / Dirección' : 'Apoyo / Gestión' }}
                                    </span>
                                    <h3 class="font-extrabold text-slate-900 text-base leading-snug">{{ staff.names }}</h3>
                                    <p class="text-xs font-semibold text-slate-600">{{ staff.job_position || 'Personal Administrativo' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                            <a
                                v-if="staff.email"
                                :href="`mailto:${staff.email}`"
                                class="text-xs text-slate-500 hover:text-blue-600 flex items-center gap-1.5 truncate max-w-[180px]"
                            >
                                <i class="bi bi-envelope"></i>
                                <span class="truncate">{{ staff.email }}</span>
                            </a>
                            <span v-else class="text-xs text-slate-400">Oficina Administrativa</span>

                            <button
                                type="button"
                                @click="openDetail(staff)"
                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition cursor-pointer"
                            >
                                Detalles
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Search -->
                <div
                    v-else
                    class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto space-y-4"
                >
                    <div class="w-16 h-16 mx-auto bg-slate-100 text-slate-400 rounded-full flex items-center justify-center">
                        <i class="bi bi-search text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">No se encontraron registros</h3>
                        <p class="text-xs text-slate-500 mt-1">Intente buscando con otros términos o modifique el filtro.</p>
                    </div>
                    <button
                        type="button"
                        @click="searchQuery = ''; levelFilter = 'all'"
                        class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition cursor-pointer"
                    >
                        Restablecer Filtros
                    </button>
                </div>
            </div>

            <!-- TAB 2: ORGANIGRAMA -->
            <div v-show="activeTab === 'organigram'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
                <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-8 text-center">
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">
                            Organigrama Funcional
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">
                            Estructura de la Plana Administrativa
                        </h3>
                    </div>

                    <div class="flex justify-center">
                        <div class="bg-slate-900 text-white rounded-2xl p-5 max-w-sm w-full text-center shadow-md">
                            <span class="text-[10px] font-black uppercase text-amber-400 tracking-wider">Nivel Directivo</span>
                            <h4 class="font-black text-lg mt-1">Dirección General</h4>
                            <p class="text-xs text-slate-300">Conducción y Política Institucional</p>
                        </div>
                    </div>

                    <div class="w-0.5 h-8 bg-slate-300 mx-auto"></div>

                    <div class="flex justify-center">
                        <div class="bg-blue-600 text-white rounded-2xl p-5 max-w-sm w-full text-center shadow-md">
                            <span class="text-[10px] font-black uppercase text-blue-200 tracking-wider">Gestión Central</span>
                            <h4 class="font-black text-lg mt-1">Administración</h4>
                            <p class="text-xs text-blue-100">Supervisión Operativa y Financiera</p>
                        </div>
                    </div>

                    <div class="w-0.5 h-8 bg-slate-300 mx-auto"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center">
                            <i class="bi bi-cash-stack text-blue-600 text-2xl"></i>
                            <h5 class="text-sm font-extrabold text-slate-900 mt-2">Tesorería</h5>
                            <p class="text-xs text-slate-500 mt-1">Recaudación y gestión de pagos</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center">
                            <i class="bi bi-box-seam text-blue-600 text-2xl"></i>
                            <h5 class="text-sm font-extrabold text-slate-900 mt-2">Abastecimiento</h5>
                            <p class="text-xs text-slate-500 mt-1">Compras y suministros</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center">
                            <i class="bi bi-building text-blue-600 text-2xl"></i>
                            <h5 class="text-sm font-extrabold text-slate-900 mt-2">Patrimonio</h5>
                            <p class="text-xs text-slate-500 mt-1">Control de bienes del campus</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center">
                            <i class="bi bi-shield-check text-blue-600 text-2xl"></i>
                            <h5 class="text-sm font-extrabold text-slate-900 mt-2">Servicios y Seguridad</h5>
                            <p class="text-xs text-slate-500 mt-1">Custodia y mantenimiento</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CUADRO DE CARGOS -->
            <div v-show="activeTab === 'cargos'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">Cuadro de Asignación de Personal (CAP)</h3>
                        <p class="text-sm text-slate-500 mt-1">Cargos formales aprobados para la gestión operativa y académica de la institución.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-extrabold text-slate-600">
                                <tr>
                                    <th class="py-3 px-4">Código</th>
                                    <th class="py-3 px-4">Cargo / Posición</th>
                                    <th class="py-3 px-4">Nivel</th>
                                    <th class="py-3 px-4">Dependencia Orgánica</th>
                                    <th class="py-3 px-4">Misión / Función Principal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                <tr v-for="pos in officialPositions" :key="pos.code" class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">{{ pos.code }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ pos.position }}</td>
                                    <td class="py-3 px-4">
                                        <span
                                            :class="pos.level === 'Directivo' ? 'bg-amber-50 text-amber-800 border-amber-200' : (pos.level === 'Jefatura' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200')"
                                            class="px-2 py-0.5 rounded text-[10px] font-bold border"
                                        >
                                            {{ pos.level }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">{{ pos.dependency }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ pos.function }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- STAFF DETAIL MODAL -->
            <div
                v-if="modalOpen && selectedStaff"
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
                            v-if="selectedStaff.photo_profile_url"
                            :src="selectedStaff.photo_profile_url"
                            :alt="selectedStaff.names"
                            class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shadow-sm"
                        />
                        <div
                            v-else
                            class="w-20 h-20 rounded-2xl bg-slate-900 text-white font-extrabold text-2xl flex items-center justify-center shadow-sm"
                        >
                            {{ getInitials(selectedStaff.names) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 leading-tight">{{ selectedStaff.names }}</h3>
                            <p class="text-xs font-bold text-blue-600 mt-1">{{ selectedStaff.job_position || 'Personal Administrativo' }}</p>
                            <span class="inline-block text-[10px] font-semibold text-slate-500 uppercase mt-0.5">IESTP Francisco Vigo Caballero</span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-slate-100 text-sm">
                        <div v-if="selectedStaff.email" class="flex items-center gap-3">
                            <i class="bi bi-envelope text-blue-600 text-base"></i>
                            <a :href="`mailto:${selectedStaff.email}`" class="text-slate-700 hover:underline break-all">{{ selectedStaff.email }}</a>
                        </div>
                        <div v-if="selectedStaff.phone" class="flex items-center gap-3">
                            <i class="bi bi-telephone text-blue-600 text-base"></i>
                            <a :href="`tel:${selectedStaff.phone}`" class="text-slate-700">{{ selectedStaff.phone }}</a>
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
