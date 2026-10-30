<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    programs: {
        type: Array,
        default: () => [],
    },
});

const copied = ref(false);

const copyGps = () => {
    if (typeof navigator !== 'undefined' && navigator.clipboard) {
        navigator.clipboard.writeText('-8.4490562, -76.4660333');
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2500);
    }
};

const mapQueryUrl = computed(() => {
    const address = props.enterprise?.address || 'Av. Ricardo Palma N° 1401';
    const city = props.enterprise?.city || 'Uchiza';
    return `https://maps.google.com/?q=${encodeURIComponent(`${address}, ${city}, Peru`)}`;
});

const facilities = [
    {
        title: 'Laboratorio de Cómputo e Infraestructura TI',
        category: 'Tecnología & Redes',
        description: 'Equipos de cómputo de alta gama, servidores rackeables para pruebas de redes, switch Cisco, y cableado estructurado certificado.',
        icon: 'bi-router-fill',
        accent: 'sky',
        image: '/img/campus/lab-computo.jpg',
    },
    {
        title: 'Gabinete de Prácticas de Enfermería Técnica',
        category: 'Ciencias de la Salud',
        description: 'Simuladores anatómicos clínicos, camillas hospitalarias, instrumental quirúrgico menor, tópicos de primeros auxilios y esterilización.',
        icon: 'bi-heart-pulse-fill',
        accent: 'rose',
        image: '/img/campus/lab-enfermeria.jpg',
    },
    {
        title: 'Módulo Productivo Agropecuario & Parcelas Demostrativas',
        category: 'Campo & Producción',
        description: 'Área experimental para cultivos tropicales (cacao, palma, café), viveros tecnificados, sistemas de riego y galpones pecuarios.',
        icon: 'bi-tree-fill',
        accent: 'emerald',
        image: '/img/campus/modulo-agropecuario.jpg',
    },
    {
        title: 'Laboratorio de Dasonomía y Vivero Forestal',
        category: 'Recursos Naturales',
        description: 'Espacio para germinación de especies nativas de la Amazonía, equipos de medición dasométrica y xiloteca institucional.',
        icon: 'bi-globe-americas',
        accent: 'teal',
        image: '/img/campus/vivero-forestal.jpg',
    },
    {
        title: 'Aula Tecnológica de Asistencia Administrativa',
        category: 'Gestión & Finanzas',
        description: 'Ambientes equipados con software ofimático y ERP, módulos de digitación, archivo documentario y atención al usuario.',
        icon: 'bi-briefcase-fill',
        accent: 'amber',
        image: '/img/campus/aula-administracion.jpg',
    },
    {
        title: 'Biblioteca Institucional & Sala de Lectura',
        category: 'Servicio Académico',
        description: 'Colección bibliográfica especializada en agronomía, salud, informática y administración, con acceso a terminales virtuales.',
        icon: 'bi-journal-bookmark-fill',
        accent: 'blue',
        image: '/img/campus/biblioteca.jpg',
    },
];

const jsonLd = computed(() => {
    const address = props.enterprise?.address || 'Av. Ricardo Palma N° 1401';
    const city = props.enterprise?.city || 'Uchiza';

    return JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'EducationalOrganization',
        'name': 'IESTP Francisco Vigo Caballero',
        'url': typeof window !== 'undefined' ? window.location.origin : '',
        'logo': props.enterprise?.logo_path ? `/${props.enterprise.logo_path}` : '/img/logo.png',
        'address': {
            '@type': 'PostalAddress',
            'streetAddress': address,
            'addressLocality': city,
            'addressRegion': 'San Martín',
            'addressCountry': 'PE',
        },
        'hasMap': `https://maps.google.com/?q=${encodeURIComponent(`${address}, ${city}, Peru`)}`,
        'location': {
            '@type': 'Place',
            'name': 'Sede Principal IESTP Francisco Vigo Caballero',
            'address': {
                '@type': 'PostalAddress',
                'streetAddress': address,
                'addressLocality': city,
                'addressRegion': 'San Martín',
                'addressCountry': 'PE',
            },
        },
    });
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Nuestros Locales e Infraestructura — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Conoce el campus e instalaciones del IESTP Francisco Vigo Caballero en Uchiza. Aulas modernas, laboratorios de computación, talleres agropecuarios, gabinetes de enfermería y áreas deportivas." />
            <meta name="keywords" content="locales IESTP Francisco Vigo Caballero, campus uchiza, infraestructura educativa, laboratorios tecnicos, gabinete enfermeria, talleres agropecuarios, instituto uchiza, san martin" />
            <meta name="robots" content="index, follow" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Nuestros Locales e Infraestructura — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Campus moderno con talleres y laboratorios especializados en Uchiza, San Martín." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
                <div class="inline-flex items-center gap-2 bg-slate-800 border border-slate-700 text-sky-400 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase">
                    <i class="bi bi-geo-alt-fill text-sky-400"></i>
                    Campus & Infraestructura Educativa
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                    Nuestros <span class="text-sky-400">Locales e Instalaciones</span>
                </h1>

                <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                    Infraestructura moderna, laboratorios equipados y ambientes de aprendizaje diseñados para la formación profesional técnica de excelencia en Uchiza.
                </p>

                <!-- Metrics Grid in Hero -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">Sede Central</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">{{ enterprise?.address || 'Av. Ricardo Palma N° 1401' }}</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">5 Talleres</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Laboratorios Especializados</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300 col-span-2 md:col-span-1">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">100%</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Equipado para Prácticas Reales</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAIN CAMPUS DISPLAY SECTION -->
        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-24">

                <!-- Main Campus Banner -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                        <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between space-y-6">
                            <div>
                                <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider mb-4">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Sede Principal Activa
                                </span>
                                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                                    Campus Institucional Uchiza
                                </h2>
                                <p class="text-slate-600 text-base sm:text-lg mt-3 leading-relaxed font-medium">
                                    Nuestro campus integra aulas pedagógicas, laboratorios tecnológicos de última generación, módulos agropecuarios y campos forestales experimentales en una ubicación estratégica de fácil acceso.
                                </p>
                            </div>

                            <div class="space-y-4 pt-4 border-t border-slate-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="bi bi-geo-alt-fill text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Dirección Oficial</p>
                                        <p class="text-sm font-bold text-slate-800">{{ enterprise?.address || 'Av. Ricardo Palma N° 1401' }}, {{ enterprise?.city || 'Uchiza' }}, San Martín, Perú</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="bi bi-clock-fill text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Horario de Atención Presencial</p>
                                        <p class="text-sm font-bold text-slate-800">Lunes a Viernes: 7:30 am – 1:30 pm (Mesa de Partes y Trámites)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-4 pt-2">
                                <a
                                    :href="mapQueryUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-md hover:shadow-lg text-sm"
                                >
                                    <i class="bi bi-map-fill"></i>
                                    Ver en Google Maps
                                </a>
                                <a
                                    v-if="enterprise?.phone_number_1"
                                    :href="`tel:${enterprise.phone_number_1}`"
                                    class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition text-sm"
                                >
                                    <i class="bi bi-telephone-fill"></i>
                                    {{ enterprise.phone_number_1 }}
                                </a>
                            </div>
                        </div>

                        <div class="lg:col-span-5 bg-slate-900 relative min-h-[320px] flex items-center justify-center overflow-hidden border-t lg:border-t-0 lg:border-l border-slate-800">
                            <div class="relative z-20 text-center p-8 space-y-4">
                                <div class="w-20 h-20 bg-blue-500/20 border border-blue-400/30 rounded-3xl flex items-center justify-center mx-auto shadow-2xl">
                                    <i class="bi bi-building-fill text-4xl text-sky-400"></i>
                                </div>
                                <h3 class="text-2xl font-black text-white">IESTP FVC</h3>
                                <p class="text-sm text-slate-300 font-medium max-w-xs mx-auto">
                                    Educación Técnica Superior Gratuita y de Calidad a Nombre de la Nación
                                </p>
                                <span class="inline-block bg-sky-400/20 border border-sky-400/40 text-sky-300 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">
                                    Licenciamiento en Proceso
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DEDICATED GOOGLE MAPS SECTION -->
                <div id="mapa-ubicacion" class="space-y-12">
                    <div class="text-center max-w-3xl mx-auto space-y-3">
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                            <i class="bi bi-geo-alt-fill text-blue-600"></i> Localización Georreferenciada
                        </span>
                        <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                            Ubicación y Mapa del Campus
                        </h2>
                        <p class="text-lg text-slate-600 leading-relaxed font-medium">
                            Encuentra la sede central del IESTP Francisco Vigo Caballero en Google Maps y traza la ruta más rápida hacia nuestras instalaciones.
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-4 sm:p-6 lg:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                            <!-- Interactive Google Maps Iframe -->
                            <div class="lg:col-span-8 flex flex-col rounded-2xl overflow-hidden border border-slate-900 shadow-md">
                                <div class="bg-slate-900 text-white px-5 py-3 flex items-center justify-between shrink-0">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                                        <span class="text-xs font-bold text-slate-300 ml-2 hidden sm:inline">Google Maps — IESTP Francisco Vigo Caballero</span>
                                    </div>
                                </div>

                                <div class="flex-1 w-full min-h-[420px] sm:min-h-[500px] bg-slate-100 relative [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                                    <div v-if="enterprise?.google_maps_iframe" v-html="enterprise.google_maps_iframe" class="w-full h-full"></div>
                                    <iframe
                                        v-else
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.886026543033!2d-76.46860822416807!3d-8.449056191591522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91af63dcf2a7d793%3A0x39afb5dd2aae7783!2sFRANCISCO%20VIGO%20CABALLERO!5e0!3m2!1ses!2spe!4v1740000000000!5m2!1ses!2spe"
                                        width="100%"
                                        height="100%"
                                        style="border:0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                    ></iframe>
                                </div>
                            </div>

                            <!-- Map Sidebar Information & Route Navigation -->
                            <div class="lg:col-span-4 flex flex-col justify-between space-y-5 bg-slate-50 p-6 sm:p-7 rounded-2xl border border-slate-200">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-black text-slate-900">Ubicación Institucional</h3>
                                            <p class="text-xs text-slate-500 font-medium">Uchiza, Región San Martín</p>
                                        </div>
                                    </div>

                                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
                                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Dirección</p>
                                        <p class="text-sm font-bold text-slate-900 leading-snug">
                                            {{ enterprise?.address || 'Av. Ricardo Palma N° 1401' }}
                                        </p>
                                        <p class="text-xs text-slate-500 font-medium">
                                            {{ enterprise?.city || 'Uchiza' }} – Tocache, San Martín
                                        </p>
                                    </div>

                                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Coordenadas GPS</p>
                                            <button
                                                type="button"
                                                @click="copyGps"
                                                class="text-[11px] font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 cursor-pointer"
                                            >
                                                <i class="bi bi-clipboard"></i>
                                                <span>{{ copied ? '¡Copiado!' : 'Copiar' }}</span>
                                            </button>
                                        </div>
                                        <p class="font-mono text-xs font-bold text-slate-800">-8.4490562, -76.4660333</p>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-200 space-y-3">
                                    <a
                                        :href="mapQueryUrl"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="w-full inline-flex items-center justify-center gap-2 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-md"
                                    >
                                        <i class="bi bi-cursor-fill"></i>
                                        Cómo llegar con Google Maps
                                    </a>

                                    <p class="text-[11px] text-slate-400 text-center font-medium">
                                        Fácil acceso en mototaxi y transporte local desde la Plaza Mayor de Uchiza.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SPECIALIZED LABS / WORKSHOPS -->
                <div class="space-y-12">
                    <div class="text-center max-w-3xl mx-auto space-y-3">
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                            Talleres & Laboratorios
                        </span>
                        <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                            Espacios Especializados de Práctica
                        </h2>
                        <p class="text-lg text-slate-600 leading-relaxed font-medium">
                            Ambientes pedagógicos acondicionados para el aprendizaje basado en proyectos y prácticas preprofesionales reales.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            v-for="fac in facilities"
                            :key="fac.title"
                            class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between space-y-6"
                        >
                            <div class="space-y-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-xs">
                                    <i :class="`bi ${fac.icon}`"></i>
                                </div>
                                <span class="inline-block text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    {{ fac.category }}
                                </span>
                                <h3 class="text-xl font-black text-slate-900 leading-snug">
                                    {{ fac.title }}
                                </h3>
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                    {{ fac.description }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                                <span class="flex items-center gap-1.5 text-emerald-600">
                                    <i class="bi bi-check-circle-fill"></i> Operativo 100%
                                </span>
                                <span>IESTP FVC</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="py-16 bg-slate-900 text-white text-center relative overflow-hidden border-t border-slate-800">
            <div class="container mx-auto px-4 relative z-10 space-y-6">
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight max-w-3xl mx-auto leading-tight">
                    ¿Quieres conocer nuestras instalaciones en persona?
                </h2>
                <p class="text-base text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                    Visita nuestro campus en Uchiza o contáctate con nosotros para coordinar visitas guiadas y recibir orientación vocacional.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-2">
                    <Link
                        :href="route('mesa-de-partes')"
                        class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-3.5 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5 text-sm"
                    >
                        <i class="bi bi-send-fill text-blue-600"></i>
                        Mesa de Partes Virtual
                    </Link>
                    <Link
                        :href="route('programas-de-estudio')"
                        class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-3.5 rounded-xl font-extrabold transition flex items-center justify-center gap-2 text-sm"
                    >
                        Ver Programas Formativos
                        <i class="bi bi-arrow-right text-lg"></i>
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
