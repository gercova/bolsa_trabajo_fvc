<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const searchQuery = ref('');
const zoomLevel = ref(100);
const isFullscreen = ref(false);

const zoomIn = () => {
    if (zoomLevel.value < 140) zoomLevel.value += 10;
};

const zoomOut = () => {
    if (zoomLevel.value > 70) zoomLevel.value -= 10;
};

const resetZoom = () => {
    zoomLevel.value = 100;
};

const isMatch = (title) => {
    if (!searchQuery.value || !searchQuery.value.trim()) return false;
    return title.toLowerCase().includes(searchQuery.value.toLowerCase().trim());
};

const triggerPrint = () => {
    if (typeof window !== 'undefined') {
        window.print();
    }
};

const jsonLd = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'EducationalOrganization',
    'name': 'IESTP Francisco Vigo Caballero',
    'alternateName': 'IESTP FVC Uchiza',
    'url': typeof window !== 'undefined' ? window.location.origin : '',
    'description': 'Estructura organizacional e institucional del Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero de Uchiza.',
    'address': {
        '@type': 'PostalAddress',
        'streetAddress': props.enterprise?.address || 'Av. Ricardo Palma N° 1401',
        'addressLocality': props.enterprise?.city || 'Uchiza',
        'addressRegion': 'San Martín',
        'addressCountry': 'PE',
    },
    'hasOrganizationalUnit': [
        { '@type': 'OrganizationalUnit', 'name': 'Dirección General' },
        { '@type': 'OrganizationalUnit', 'name': 'Consejo Asesor' },
        { '@type': 'OrganizationalUnit', 'name': 'Concejo Estudiantil' },
        { '@type': 'OrganizationalUnit', 'name': 'Secretaría de Dirección' },
        { '@type': 'OrganizationalUnit', 'name': 'Unidad Académica' },
        { '@type': 'OrganizationalUnit', 'name': 'Secretaría Académica' },
        { '@type': 'OrganizationalUnit', 'name': 'Área de Calidad' },
        { '@type': 'OrganizationalUnit', 'name': 'Unidad de Investigación' },
        { '@type': 'OrganizationalUnit', 'name': 'Unidad de Bienestar y Empleabilidad' },
        { '@type': 'OrganizationalUnit', 'name': 'Unidad de Formación Continua' },
        { '@type': 'OrganizationalUnit', 'name': 'Administración' },
    ],
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Organigrama Institucional — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Estructura organizacional oficial del IESTP Francisco Vigo Caballero de Uchiza. Tres niveles jerárquicos: Dirección General, Unidades Académicas, Áreas Administrativas y Servicios de Bienestar." />
            <meta name="keywords" content="organigrama, estructura institucional, direccion general, unidad academica, secretaria academica, uchiza, iestp francisco vigo caballero, san martin, educacion superior" />
            <meta name="robots" content="index, follow, max-image-preview:large" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Organigrama Institucional — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Estructura organizacional e institucional del IESTP Francisco Vigo Caballero de Uchiza." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white py-16 lg:py-20 overflow-hidden border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

            <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
                <span class="inline-flex items-center gap-2 bg-slate-800 text-sky-400 text-xs sm:text-sm font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border border-slate-700">
                    <i class="bi bi-diagram-3 text-base"></i>
                    Estructura Orgánica Institucional
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                    Organigrama Institucional
                </h1>
                <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto font-medium">
                    Estructura organizacional oficial del <strong class="text-white">IESTP Francisco Vigo Caballero</strong>. Tres niveles jerárquicos articulados para garantizar la excelencia académica y administrativa en Uchiza.
                </p>
            </div>
        </section>

        <!-- MAIN INTERACTIVE CONTAINER -->
        <section class="py-10 bg-slate-100">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
                <!-- TOOLBAR CONTROLS -->
                <div class="no-print bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Quick Filter Input -->
                    <div class="relative w-full md:w-80">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Buscar área o cargo (ej. Docentes, Tesorero)..."
                            class="w-full pl-10 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        />
                        <button
                            v-if="searchQuery"
                            @click="searchQuery = ''"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                        >
                            <i class="bi bi-x-circle-fill text-sm"></i>
                        </button>
                    </div>

                    <!-- Legend Summary -->
                    <div class="hidden lg:flex items-center gap-4 text-xs font-bold text-slate-700">
                        <span class="flex items-center gap-1.5">
                            <span class="w-5 h-3 border-2 border-black bg-white inline-block"></span>
                            Órganos Directos
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-5 h-3 border-2 border-dashed border-black bg-white inline-block"></span>
                            Órganos de Apoyo
                        </span>
                    </div>

                    <!-- Action Controls (Zoom, Reset, Print) -->
                    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                        <div class="flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
                            <button
                                type="button"
                                @click="zoomOut"
                                title="Alejar (-)"
                                class="px-2.5 py-1 text-slate-700 hover:bg-white rounded-lg transition font-bold text-xs cursor-pointer"
                            >
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <span class="px-2 text-xs font-extrabold text-slate-700 min-w-[42px] text-center">{{ zoomLevel }}%</span>
                            <button
                                type="button"
                                @click="zoomIn"
                                title="Acercar (+)"
                                class="px-2.5 py-1 text-slate-700 hover:bg-white rounded-lg transition font-bold text-xs cursor-pointer"
                            >
                                <i class="bi bi-zoom-in"></i>
                            </button>
                            <button
                                type="button"
                                @click="resetZoom"
                                title="Restablecer Zoom"
                                class="px-2 py-1 text-slate-500 hover:text-slate-900 text-xs font-bold border-l border-slate-200 ml-1 pl-2 cursor-pointer"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>

                        <button
                            type="button"
                            @click="triggerPrint"
                            title="Imprimir / Exportar a PDF"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl font-bold text-xs transition flex items-center gap-1.5 shadow-sm cursor-pointer"
                        >
                            <i class="bi bi-printer"></i>
                            <span class="hidden sm:inline">Imprimir</span>
                        </button>
                    </div>
                </div>

                <!-- CHART VIEWPORT -->
                <div class="overflow-x-auto pb-4">
                    <div
                        class="min-w-[1050px] w-full border-[3px] border-slate-800 bg-white rounded-xl shadow-lg transition-transform duration-200 origin-top overflow-hidden"
                        :style="{ transform: `scale(${zoomLevel / 100})` }"
                    >
                        <!-- 1. PRIMER NIVEL (ORANGE / PEACH BAND) -->
                        <div class="bg-[#FCD5B5] border-b-2 border-slate-800 px-6 pt-4 pb-6 relative">
                            <div class="mb-4">
                                <span class="bg-[#FFFF00] text-black font-black text-[0.72rem] tracking-wider uppercase px-2.5 py-1 border-[1.5px] border-black shadow-[1px_1px_0px_#000]">
                                    PRIMER NIVEL
                                </span>
                            </div>

                            <div class="relative w-full max-w-4xl mx-auto">
                                <div class="flex items-center justify-between gap-4">
                                    <!-- Concejo Estudiantil -->
                                    <div class="w-44 flex flex-col items-center">
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-2 px-2 min-h-[42px] border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,0.18)] flex items-center justify-center uppercase transition-all"
                                            :class="{ 'outline-3 outline-blue-600 shadow-[0_0_16px_rgba(37,99,235,0.6)] animate-pulse': isMatch('Concejo Estudiantil') }"
                                        >
                                            CONCEJO ESTUDIANTIL
                                        </div>
                                    </div>

                                    <div class="flex-1 h-0.5 bg-black"></div>

                                    <!-- Dirección General -->
                                    <div class="w-48 flex flex-col items-center">
                                        <div
                                            class="w-full bg-white text-black font-black text-xs leading-tight text-center py-2 px-2 min-h-[48px] border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,0.18)] flex items-center justify-center uppercase transition-all"
                                            :class="{ 'outline-3 outline-blue-600 shadow-[0_0_16px_rgba(37,99,235,0.6)] animate-pulse': isMatch('Dirección General') }"
                                        >
                                            DIRECCIÓN GENERAL
                                        </div>
                                    </div>

                                    <div class="flex-1 h-0.5 bg-black"></div>

                                    <!-- Consejo Asesor -->
                                    <div class="w-44 flex flex-col items-center">
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-2 px-2 min-h-[42px] border-2 border-black shadow-[2px_2px_0px_rgba(0,0,0,0.18)] flex items-center justify-center uppercase transition-all"
                                            :class="{ 'outline-3 outline-blue-600 shadow-[0_0_16px_rgba(37,99,235,0.6)] animate-pulse': isMatch('Consejo Asesor') }"
                                        >
                                            CONSEJO ASESOR
                                        </div>
                                    </div>
                                </div>

                                <!-- Central Trunk Stem -->
                                <div class="w-full flex justify-center">
                                    <div class="w-0.5 bg-black h-8"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. SEGUNDO NIVEL (SKY BLUE BAND) -->
                        <div class="bg-[#B8D9F7] border-b-2 border-slate-800 px-4 pt-4 pb-8 relative">
                            <div class="mb-4">
                                <span class="bg-[#FFFF00] text-black font-black text-[0.72rem] tracking-wider uppercase px-2.5 py-1 border-[1.5px] border-black shadow-[1px_1px_0px_#000]">
                                    SEGUNDO NIVEL
                                </span>
                            </div>

                            <div class="flex flex-col items-center w-full">
                                <!-- Secretaría de Dirección -->
                                <div class="relative flex items-center justify-center">
                                    <div class="border-t-2 border-dashed border-black w-10"></div>
                                    <div
                                        class="w-48 bg-white text-black font-bold text-xs leading-tight text-center py-2 px-2 min-h-[42px] border-2 border-dashed border-black shadow-[2px_2px_0px_rgba(0,0,0,0.1)] flex items-center justify-center uppercase transition-all"
                                        :class="{ 'outline-3 outline-blue-600 shadow-[0_0_16px_rgba(37,99,235,0.6)] animate-pulse': isMatch('Secretaría de Dirección') }"
                                    >
                                        SECRETARÍA DE DIRECCIÓN
                                    </div>
                                    <div class="border-t-2 border-dashed border-black w-10"></div>
                                </div>

                                <!-- Trunk Stem to Distribution Line -->
                                <div class="w-0.5 bg-black h-7"></div>

                                <!-- Main Horizontal Line -->
                                <div class="w-full px-4">
                                    <div class="h-0.5 bg-black w-full"></div>
                                </div>

                                <!-- 8 Main Columns Grid -->
                                <div class="grid grid-cols-8 gap-2 w-full px-2 pt-0">
                                    <!-- Col 1: ÁREA ADMINISTRATIVA -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-dashed border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Área Administrativa') }"
                                        >
                                            ÁREA ADMINISTRATIVA
                                        </div>
                                    </div>

                                    <!-- Col 2: ÁREA DE PRODUCCIÓN -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-dashed border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Área de Producción') }"
                                        >
                                            ÁREA DE PRODUCCIÓN
                                        </div>
                                    </div>

                                    <!-- Col 3: SECRETARÍA ACADÉMICA -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Secretaría Académica') }"
                                        >
                                            SECRETARÍA ACADÉMICA
                                        </div>
                                    </div>

                                    <!-- Col 4: UNIDAD ACADÉMICA -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Unidad Académica') }"
                                        >
                                            UNIDAD ACADÉMICA
                                        </div>
                                    </div>

                                    <!-- Col 5: ÁREA DE CALIDAD -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Área de Calidad') }"
                                        >
                                            ÁREA DE CALIDAD
                                        </div>
                                    </div>

                                    <!-- Col 6: UNIDAD DE INVESTIGACIÓN -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Unidad de Investigación') }"
                                        >
                                            UNIDAD DE INVESTIGACIÓN
                                        </div>
                                    </div>

                                    <!-- Col 7: UNIDAD DE BIENESTAR Y EMPLEABILIDAD -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0.5 bg-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Bienestar') }"
                                        >
                                            UNIDAD DE BIENESTAR Y EMPLEABILIDAD
                                        </div>
                                    </div>

                                    <!-- Col 8: UNIDAD DE FORMACIÓN CONTINUA -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-0 border-l-2 border-dashed border-black h-[18px]"></div>
                                        <div
                                            class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-dashed border-black shadow flex items-center justify-center uppercase"
                                            :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Formación Continua') }"
                                        >
                                            UNIDAD DE FORMACIÓN CONTINUA
                                        </div>
                                    </div>
                                </div>

                                <!-- SECONDARY ROW IN LEVEL 2 -->
                                <div class="w-full px-2 mt-4 relative">
                                    <div class="grid grid-cols-8 gap-2 w-full">
                                        <!-- Col 1: ADMINISTRACIÓN -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-0.5 bg-black h-5"></div>
                                            <div
                                                class="w-full bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1.5 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Administración') }"
                                            >
                                                ADMINISTRACIÓN
                                            </div>
                                        </div>

                                        <!-- Col 2: IMAGEN INSTITUCIONAL -->
                                        <div class="flex items-center pt-5">
                                            <div class="border-t-2 border-dashed border-black flex-1"></div>
                                            <div
                                                class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 min-h-[42px] border-2 border-dashed border-black shadow flex items-center justify-center uppercase"
                                                :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Imagen Institucional') }"
                                            >
                                                IMAGEN INSTITUCIONAL
                                            </div>
                                        </div>
                                        <div></div>

                                        <!-- Col 4: SECRETARÍA DE UNIDAD ACADÉMICA -->
                                        <div class="flex flex-col items-center relative">
                                            <div class="w-0.5 bg-black h-5"></div>
                                            <div class="flex items-center w-full relative">
                                                <div class="w-full flex justify-center">
                                                    <div class="w-0.5 bg-black h-6"></div>
                                                </div>
                                                <div class="absolute left-1/2 flex items-center w-[140px]">
                                                    <div class="h-0.5 bg-black w-6"></div>
                                                    <div
                                                        class="w-[130px] bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 min-h-[42px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                        :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Secretaría de Unidad Académica') }"
                                                    >
                                                        SECRETARÍA DE UNIDAD ACADÉMICA
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div></div>
                                        <div></div>
                                        <div class="flex justify-center">
                                            <div class="w-0.5 bg-black h-[50px]"></div>
                                        </div>
                                        <div></div>
                                    </div>

                                    <!-- 5 ACADEMIC COORDINATORS BRANCH -->
                                    <div class="mt-4 flex flex-col items-center w-full">
                                        <div class="w-0.5 bg-black h-4"></div>
                                        <div class="h-0.5 bg-black w-[62%]"></div>
                                        <div class="flex items-start justify-center gap-2 mt-0 w-[65%]">
                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="w-0.5 bg-black h-3.5"></div>
                                                <div
                                                    class="w-full bg-white text-black font-extrabold text-[0.55rem] leading-tight text-center py-1.5 px-1 min-h-[46px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                    :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Producción Agropecuaria') }"
                                                >
                                                    COORDINADOR ACADÉMICO DE PRODUCCIÓN AGROPECUARIA
                                                </div>
                                            </div>

                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="w-0.5 bg-black h-3.5"></div>
                                                <div
                                                    class="w-full bg-white text-black font-extrabold text-[0.55rem] leading-tight text-center py-1.5 px-1 min-h-[46px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                    :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Enfermería') }"
                                                >
                                                    COORDINADOR ACADÉMICO DE ENFERMERÍA TÉCNICA
                                                </div>
                                            </div>

                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="w-0.5 bg-black h-3.5"></div>
                                                <div
                                                    class="w-full bg-white text-black font-extrabold text-[0.55rem] leading-tight text-center py-1.5 px-1 min-h-[46px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                    :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Redes') }"
                                                >
                                                    COORDINADOR ACADÉMICO DE ADMINISTRACIÓN DE REDES Y COMUNICACIÓN
                                                </div>
                                            </div>

                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="w-0.5 bg-black h-3.5"></div>
                                                <div
                                                    class="w-full bg-white text-black font-extrabold text-[0.55rem] leading-tight text-center py-1.5 px-1 min-h-[46px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                    :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Asistencia Administrativa') }"
                                                >
                                                    COORDINADOR ACADÉMICO DE ASISTENCIA ADMINISTRATIVA
                                                </div>
                                            </div>

                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="w-0.5 bg-black h-3.5"></div>
                                                <div
                                                    class="w-full bg-white text-black font-extrabold text-[0.55rem] leading-tight text-center py-1.5 px-1 min-h-[46px] border-2 border-black shadow flex items-center justify-center uppercase"
                                                    :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Manejo Forestal') }"
                                                >
                                                    COORDINADOR ACADÉMICO DE MANEJO FORESTAL
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. TERCER NIVEL (ROSE / PINK BAND) -->
                        <div class="bg-[#F8C8CE] px-4 pt-4 pb-10 relative">
                            <div class="mb-4">
                                <span class="bg-[#FFFF00] text-black font-black text-[0.72rem] tracking-wider uppercase px-2.5 py-1 border-[1.5px] border-black shadow-[1px_1px_0px_#000]">
                                    TERCER NIVEL
                                </span>
                            </div>

                            <div class="grid grid-cols-12 gap-2 w-full items-start">
                                <!-- LEFT SECTION: ADMIN DEPENDENCIES -->
                                <div class="col-span-4 relative pt-2">
                                    <div class="absolute left-1/2 top-0 bottom-4 w-0.5 bg-black -translate-x-1/2"></div>
                                    <div class="space-y-3 relative z-10">
                                        <div class="flex items-center justify-between">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Asistente Administrativo') }">
                                                    ASISTENTE ADMINISTRATIVO
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                            <div class="w-5/12 flex items-center pl-1">
                                                <div class="h-0.5 bg-black w-4"></div>
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Personal de Servicio') }">
                                                    PERSONAL DE SERVICIO
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Tesorero') }">
                                                    TESORERO
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                            <div class="w-5/12 flex items-center pl-1">
                                                <div class="h-0.5 bg-black w-4"></div>
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Personal de Servicio II') }">
                                                    PERSONAL DE SERVICIO II
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Patrimonio') }">
                                                    ÁREA DE PATRIMONIO
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                            <div class="w-5/12 flex items-center pl-1">
                                                <div class="h-0.5 bg-black w-4"></div>
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Personal de Servicio III') }">
                                                    PERSONAL DE SERVICIO III
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-start">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Abastecimiento') }">
                                                    ÁREA DE ABASTECIMIENTO
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-start">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Guardianía') }">
                                                    GUARDIANIA DIURNA
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-start">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Asistente de Campo') }">
                                                    ASISTENTE DE CAMPO
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-start">
                                            <div class="w-5/12 flex items-center pr-1">
                                                <div class="w-full bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1 px-1 border-2 border-dashed border-black shadow" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Personal de Campo') }">
                                                    PERSONAL DE CAMPO
                                                </div>
                                                <div class="border-t-2 border-dashed border-black w-4"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CENTER SECTION: ACADEMIC HIERARCHY -->
                                <div class="col-span-4 flex flex-col items-center pt-2">
                                    <div class="w-0.5 bg-black h-6"></div>
                                    <div
                                        class="w-44 bg-white text-black font-black text-xs leading-tight text-center py-2 px-2 min-h-[44px] border-2 border-black shadow flex items-center justify-center uppercase"
                                        :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Docentes') }"
                                    >
                                        DOCENTES
                                    </div>
                                    <div class="w-0.5 bg-black h-5"></div>
                                    <div class="h-0.5 bg-black w-3/4"></div>

                                    <div class="flex items-center justify-between w-full max-w-xs pt-0">
                                        <div class="flex flex-col items-center flex-1">
                                            <div class="w-0.5 bg-black h-3.5"></div>
                                            <div class="w-32 bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1 px-1 border-2 border-black shadow flex items-center justify-center uppercase" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Especialidad') }">
                                                ESPECIALIDAD
                                            </div>
                                            <div class="w-0.5 bg-black h-3.5"></div>
                                        </div>
                                        <div class="flex flex-col items-center flex-1">
                                            <div class="w-0.5 bg-black h-3.5"></div>
                                            <div class="w-32 bg-white text-black font-extrabold text-[0.62rem] leading-tight text-center py-1 px-1 border-2 border-black shadow flex items-center justify-center uppercase" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Empleabilidad') }">
                                                EMPLEABILIDAD
                                            </div>
                                            <div class="w-0.5 bg-black h-3.5"></div>
                                        </div>
                                    </div>

                                    <div class="h-0.5 bg-black w-3/4"></div>
                                    <div class="w-0.5 bg-black h-5"></div>

                                    <div
                                        class="w-44 bg-white text-black font-black text-xs leading-tight text-center py-2 px-2 min-h-[44px] border-2 border-black shadow flex items-center justify-center uppercase"
                                        :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Estudiantes') }"
                                    >
                                        ESTUDIANTES
                                    </div>
                                </div>

                                <!-- RIGHT SECTION: BIENESTAR SERVICES -->
                                <div class="col-span-4 relative pt-2">
                                    <div class="absolute left-6 top-0 bottom-12 w-0.5 bg-black"></div>
                                    <div class="space-y-4 pt-4 pl-6">
                                        <div class="flex items-center">
                                            <div class="border-t-2 border-dashed border-black w-6"></div>
                                            <div class="w-64 bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1.5 px-2 border-2 border-dashed border-black shadow uppercase" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Médico') || isMatch('Tópico') }">
                                                SERVICIO MÉDICO (TÓPICO)
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="border-t-2 border-dashed border-black w-6"></div>
                                            <div class="w-64 bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1.5 px-2 border-2 border-dashed border-black shadow uppercase" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Psicopedagógico') }">
                                                SERVICIO PSICOPEDAGOGICO
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="border-t-2 border-dashed border-black w-6"></div>
                                            <div class="w-64 bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1.5 px-2 border-2 border-dashed border-black shadow uppercase" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Bienestar Social') }">
                                                SERVICIO DE BIENESTAR SOCIAL (CONSEJERÍA)
                                            </div>
                                        </div>

                                        <div class="flex items-center">
                                            <div class="border-t-2 border-dashed border-black w-6"></div>
                                            <div class="w-64 bg-white text-black font-extrabold text-[0.58rem] leading-tight text-center py-1.5 px-2 border-2 border-dashed border-black shadow uppercase" :class="{ 'outline-3 outline-blue-600 animate-pulse': isMatch('Servicio de Empleabilidad') }">
                                                SERVICIO DE EMPLEABILIDAD
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-center text-xs text-slate-500 mt-4 font-semibold no-print">
                    <i class="bi bi-info-circle mr-1 text-blue-600"></i>
                    En teléfonos y tablets, deslice horizontalmente el esquema para explorar el organigrama completo.
                </p>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="py-16 bg-slate-900 text-white text-center relative overflow-hidden border-t border-slate-800">
            <div class="container mx-auto px-4 relative z-10 space-y-6">
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight max-w-3xl mx-auto leading-tight">
                    ¿Deseas conocer más sobre el IESTP Francisco Vigo Caballero?
                </h2>
                <p class="text-base text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                    Conoce a nuestra plana docente, explora nuestros programas de estudio o revisa la reseña histórica de la institución.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-2">
                    <Link
                        :href="route('historia')"
                        class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-3.5 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5 text-sm"
                    >
                        <i class="bi bi-clock-history text-blue-600 text-lg"></i>
                        Nuestra Historia
                    </Link>
                    <Link
                        :href="route('programas-de-estudio')"
                        class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-3.5 rounded-xl font-extrabold transition flex items-center justify-center gap-2 text-sm"
                    >
                        Programas de Estudio
                        <i class="bi bi-arrow-right text-lg"></i>
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
