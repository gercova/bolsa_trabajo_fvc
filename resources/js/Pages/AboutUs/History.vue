<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    histories: {
        type: Array,
        default: () => [],
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const sortedHistories = computed(() => {
    return [...props.histories].sort((a, b) => (a.start_year || 0) - (b.start_year || 0));
});

const getParagraphs = (desc) => {
    if (!desc) return [];
    return desc.split('\n').map((p) => p.trim()).filter(Boolean);
};

const jsonLd = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'EducationalOrganization',
    'name': 'IESTP Francisco Vigo Caballero',
    'description': 'Historia y trayectoria institucional del IESTP Francisco Vigo Caballero de Uchiza.',
    'url': typeof window !== 'undefined' ? window.location.origin : '',
    'address': {
        '@type': 'PostalAddress',
        'streetAddress': props.enterprise?.address || 'Av. Ricardo Palma N° 1401',
        'addressLocality': props.enterprise?.city || 'Uchiza',
        'addressCountry': 'PE',
    },
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Reseña Histórica — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Conoce la reseña histórica y trayectoria del IESTP Francisco Vigo Caballero de Uchiza. Desde su creación en 1991, hitos clave y su evolución académica e infraestructura." />
            <meta name="keywords" content="historia, reseña historica, hitos, trayectoria, IESTP Francisco Vigo Caballero, Uchiza, fundacion, directores" />
            <meta name="robots" content="index, follow" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Reseña Histórica — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="La historia y los hitos más importantes de nuestra trayectoria institucional en Uchiza." />
            <meta property="og:image" :content="enterprise?.favicon_path ? `/${enterprise.favicon_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white py-16 lg:py-20 overflow-hidden border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
                <span class="inline-flex items-center gap-2 bg-slate-800 text-sky-400 text-sm font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border border-slate-700">
                    <i class="bi bi-clock-history text-base"></i>
                    Nuestra Trayectoria
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                    Reseña Histórica
                </h1>
                <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                    Conoce la trayectoria de nuestra institución educativa, desde sus humildes comienzos ad honorem en 1991, hasta consolidarse como pilar del licenciamiento y desarrollo tecnológico en Uchiza.
                </p>
            </div>
        </section>

        <!-- MAIN GRID SECTION -->
        <section class="py-16 bg-slate-50/50">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 max-w-7xl mx-auto">

                    <!-- LEFT COLUMN: TIMELINE -->
                    <div class="lg:col-span-2 space-y-12">
                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                            <h2 class="text-2xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                                Nuestra Trayectoria en el Tiempo
                            </h2>
                            <p class="text-sm md:text-base text-slate-500 leading-relaxed">
                                A través de décadas de dedicación, el IESTP "Francisco Vigo Caballero" ha evolucionado respondiendo a las necesidades productivas de la región San Martín, formando técnicos profesionales competentes y éticos.
                            </p>
                        </div>

                        <!-- Vertical Timeline List -->
                        <div class="relative border-l-2 border-slate-300 ml-4 pl-6 sm:pl-10 space-y-12">
                            <div
                                v-for="history in sortedHistories"
                                :key="history.id"
                                class="relative group"
                            >
                                <!-- Dot Indicator -->
                                <div class="absolute -left-[31px] sm:-left-[47px] top-6 w-4 h-4 rounded-full bg-blue-600 border-4 border-white ring-4 ring-blue-100 z-10"></div>

                                <!-- Card -->
                                <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm hover:shadow-md hover:translate-x-1.5 transition-all duration-300">
                                    <span class="inline-block px-3 py-1 text-sm font-extrabold tracking-wider rounded-full bg-blue-50 text-blue-800 border border-blue-200 mb-4">
                                        {{ history.end_year ? `${history.start_year} - ${history.end_year}` : `${history.start_year} - Presente` }}
                                    </span>

                                    <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-4 leading-tight">
                                        {{ history.title }}
                                    </h3>

                                    <div v-if="history.image_url" class="mb-6 rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50 relative group/img">
                                        <img
                                            :src="history.image_url"
                                            :alt="history.title"
                                            class="w-full max-h-80 object-cover object-center group-hover/img:scale-105 transition-transform duration-500"
                                        />
                                        <div class="absolute bottom-0 inset-x-0 bg-slate-950/80 p-4 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-between text-white">
                                            <span class="text-xs font-semibold drop-shadow-md">Registro Fotográfico Referencial</span>
                                            <a
                                                :href="history.image_url"
                                                target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold hover:bg-white hover:text-slate-900 transition"
                                            >
                                                <i class="bi bi-arrows-fullscreen text-[10px]"></i> Ver completa
                                            </a>
                                        </div>
                                    </div>

                                    <div class="prose max-w-none text-slate-600 text-sm md:text-base leading-relaxed space-y-4">
                                        <p v-for="(p, pIdx) in getParagraphs(history.description)" :key="pIdx">
                                            {{ p }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: SIDEBAR -->
                    <div class="space-y-8">
                        <!-- Milestones Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                                <i class="bi bi-bookmark-star text-blue-600 text-xl"></i>
                                Hitos Históricos Clave
                            </h3>

                            <div class="relative border-l border-slate-200 pl-4 space-y-5 ml-2">
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Julio 1990</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">Primera asamblea promotora de directores en Uchiza.</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Enero 1991</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">Se crea oficialmente mediante Decreto Ejecutivo Regional Nº 05-91-CR-SMLL.</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Noviembre 1994</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">R.M. Nº 0868-94-ED otorga el reconocimiento y funcionamiento definitivo.</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Marzo 1997</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">Se crea la especialidad de Secretariado Ejecutivo con R.D. Nº 0091-97-ED.</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Mayo 2000</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">Apertura del programa de Laboratorio Clínico con R.D. Nº 483-2000-ED.</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Abril 2006</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">Revalidación oficial de carreras con R.D. Nº 0298-2006-ED.</p>
                                </div>
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Abril 2010</p>
                                    <p class="text-sm text-slate-700 font-semibold mt-0.5 leading-snug">Creación de Medio Ambiente y Recursos Naturales con R.D. Nº 0341-2010-ED.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Historic Leaders -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                                <i class="bi bi-people text-blue-600 text-xl"></i>
                                Gestores y Directores Destacados
                            </h3>

                            <ul class="space-y-4">
                                <li class="flex gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 font-bold text-sm">
                                        LH
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Lic. Luis Viviano Hilario Vega</p>
                                        <p class="text-xs text-slate-500">Jefe de Coordinación Educativa y Director (1998 - 2001)</p>
                                    </div>
                                </li>
                                <li class="flex gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 font-bold text-sm">
                                        DD
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Econ. Demetrio Díaz Guevara</p>
                                        <p class="text-xs text-slate-500">Ex-Alcalde de Uchiza e impulsor de la creación del IESTP</p>
                                    </div>
                                </li>
                                <li class="flex gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 font-bold text-sm">
                                        EC
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Ing. Edison Calvo Trujillo</p>
                                        <p class="text-xs text-slate-500">Primer Director del Instituto (1991)</p>
                                    </div>
                                </li>
                                <li class="flex gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 font-bold text-sm">
                                        MG
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Ing. Manuel Adolfo Guerra Meléndez</p>
                                        <p class="text-xs text-slate-500">Director Revalidador y Gestor de Terrenos (2005 - 2010)</p>
                                    </div>
                                </li>
                                <li class="flex gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 font-bold text-sm">
                                        TG
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Mg. Teodorico Ganoza Medina</p>
                                        <p class="text-xs text-slate-500">Director General Actual (2026 - Presente)</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </PublicLayout>
</template>
