<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    links: {
        type: Array,
        default: () => [],
    },
});

// FAQ Accordion State
const activeFaq = ref(null);
const toggleFaq = (index) => {
    activeFaq.value = activeFaq.value === index ? null : index;
};

// Official MINEDU predefined cards fallback & enrichment
const enrichedLinks = computed(() => {
    if (!props.links || props.links.length === 0) {
        return [
            {
                id: 1,
                name: 'REGISTRA',
                subtitle: 'Sistema de Registro de Matrícula y Notas',
                description: 'Plataforma del MINEDU para la administración y validación oficial de nóminas de matrícula, actas de evaluación modular e historial académico de estudiantes de IESTP a nivel nacional.',
                link: 'https://registra.minedu.gob.pe',
                icon: 'bi-pencil-square',
                barColor: 'bg-blue-600',
                avatarColor: 'bg-blue-600',
                badgeClass: 'bg-blue-50 text-blue-800 border-blue-200',
                targetAudience: 'Estudiantes, Docentes y Secretaría Académica',
                domainHost: 'registra.minedu.gob.pe',
            },
            {
                id: 2,
                name: 'TITULA',
                subtitle: 'Registro Nacional de Títulos Profesionales',
                description: 'Portal oficial para la consulta pública, verificación y validación legal de títulos técnicos profesionales y certificados modulares emitidos a Nombre de la Nación.',
                link: 'https://titula.minedu.gob.pe',
                icon: 'bi-award',
                barColor: 'bg-emerald-600',
                avatarColor: 'bg-emerald-600',
                badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                targetAudience: 'Egresados, Empleadores y Público en General',
                domainHost: 'titula.minedu.gob.pe',
            },
            {
                id: 3,
                name: 'CONECTA',
                subtitle: 'Plataforma de Articulación y Comunidad Tecnológica',
                description: 'Red de interacción digital que vincula a estudiantes, docentes y graduados de Educación Superior Tecnológica con oportunidades formativas, recursos y redes de innovación.',
                link: 'https://conecta.minedu.gob.pe',
                icon: 'bi-people',
                barColor: 'bg-indigo-600',
                avatarColor: 'bg-indigo-600',
                badgeClass: 'bg-purple-50 text-purple-800 border-purple-200',
                targetAudience: 'Estudiantes, Egresados y Comunidad Académica',
                domainHost: 'conecta.minedu.gob.pe',
            },
            {
                id: 4,
                name: 'AVANZA',
                subtitle: 'Sistema de Trayectoria y Desarrollo Profesional',
                description: 'Herramienta de seguimiento del progreso laboral, desarrollo de competencias profesionales, formación continua y empleabilidad para técnicos del Perú.',
                link: 'https://avanza.minedu.gob.pe',
                icon: 'bi-graph-up-arrow',
                barColor: 'bg-amber-600',
                avatarColor: 'bg-amber-600',
                badgeClass: 'bg-amber-50 text-amber-800 border-amber-200',
                targetAudience: 'Egresados Técnicos y Orientadores de Carrera',
                domainHost: 'avanza.minedu.gob.pe',
            },
        ];
    }

    return props.links.map((link) => {
        const nameLower = (link.name || '').toLowerCase();
        let subtitle = 'Portal Oficial del Ministerio de Educación';
        let description = 'Enlace institucional oficial de consulta y trámite de la Educación Superior Tecnológica del Perú.';
        let barColor = 'bg-blue-700';
        let avatarColor = 'bg-blue-700';
        let badgeClass = 'bg-blue-50 text-blue-800 border-blue-200';
        let iconName = link.icon || 'bi-link-45deg';
        let targetAudience = 'Comunidad Institucional IESTP FVC';

        if (nameLower.includes('registra')) {
            subtitle = 'Sistema de Registro de Matrícula y Notas';
            description = 'Plataforma del MINEDU para la administración y validación oficial de nóminas de matrícula, actas de evaluación modular e historial académico de estudiantes.';
            barColor = 'bg-blue-600';
            avatarColor = 'bg-blue-600';
            badgeClass = 'bg-blue-50 text-blue-800 border-blue-200';
            iconName = 'bi-pencil-square';
            targetAudience = 'Estudiantes, Docentes y Secretaría Académica';
        } else if (nameLower.includes('titula')) {
            subtitle = 'Registro Nacional de Títulos Profesionales';
            description = 'Portal oficial para la consulta pública, verificación y validación legal de títulos técnicos profesionales y certificados modulares emitidos a Nombre de la Nación.';
            barColor = 'bg-emerald-600';
            avatarColor = 'bg-emerald-600';
            badgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
            iconName = 'bi-award';
            targetAudience = 'Egresados, Empleadores y Público en General';
        } else if (nameLower.includes('conecta')) {
            subtitle = 'Plataforma de Articulación y Comunidad Tecnológica';
            description = 'Red de interacción digital que vincula a estudiantes, docentes y graduados de Educación Superior Tecnológica con oportunidades formativas y recursos.';
            barColor = 'bg-indigo-600';
            avatarColor = 'bg-indigo-600';
            badgeClass = 'bg-purple-50 text-purple-800 border-purple-200';
            iconName = 'bi-people';
            targetAudience = 'Estudiantes, Egresados y Comunidad Académica';
        } else if (nameLower.includes('avanza')) {
            subtitle = 'Sistema de Trayectoria y Desarrollo Profesional';
            description = 'Herramienta de seguimiento del progreso laboral, desarrollo de competencias profesionales, formación continua y empleabilidad para técnicos del Perú.';
            barColor = 'bg-amber-600';
            avatarColor = 'bg-amber-600';
            badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
            iconName = 'bi-graph-up-arrow';
            targetAudience = 'Egresados Técnicos y Orientadores de Carrera';
        }

        let domainHost = 'minedu.gob.pe';
        try {
            if (link.link) {
                const parsed = new URL(link.link);
                domainHost = parsed.hostname;
            }
        } catch {
            // Keep fallback
        }

        return {
            ...link,
            subtitle,
            description,
            barColor,
            avatarColor,
            badgeClass,
            customIcon: link.icon || iconName,
            targetAudience,
            domainHost,
        };
    });
});

// JSON-LD Schema
const jsonLd = computed(() => {
    return JSON.stringify({
        '@context': 'https://schema.org',
        '@graph': [
            {
                '@type': 'WebPage',
                '@id': 'https://iestpfranciscovigocaballero.edu.pe/servicios/enlaces-institucionales#webpage',
                url: 'https://iestpfranciscovigocaballero.edu.pe/servicios/enlaces-institucionales',
                name: 'Enlaces Institucionales Oficiales MINEDU — IESTP Francisco Vigo Caballero',
                description: 'Acceso directo a las plataformas oficiales del Ministerio de Educación (MINEDU) para estudiantes, egresados y docentes de educación superior técnica.',
                inLanguage: 'es-PE',
                isPartOf: {
                    '@type': 'WebSite',
                    name: props.enterprise?.company_name || 'IESTP Francisco Vigo Caballero',
                },
            },
        ],
    });
});
</script>

<template>
    <PublicLayout>
        <Head>
            <title>Enlaces Institucionales Oficiales MINEDU — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Portales web oficiales del Ministerio de Educación (MINEDU) para Educación Superior Tecnológica: Registra, Titula, Conecta y Avanza del IESTP Francisco Vigo Caballero en Uchiza, San Martín." />
            <meta name="keywords" content="registra minedu, titula minedu, conecta minedu, avanza minedu, enlaces institucionales, iestp francisco vigo caballero, titulos tecnicos peru, educacion superior tecnologica, uchiza" />
            <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Enlaces Institucionales Oficiales MINEDU — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Acceso directo a las plataformas oficiales del Ministerio de Educación (MINEDU) para la verificación de títulos, matrícula y comunidad tecnológica." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section aria-label="Portada Enlaces Institucionales" class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <!-- Left: Title + Intro -->
                    <div class="lg:col-span-7 space-y-6">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-sky-400 text-xs sm:text-sm font-semibold tracking-wide">
                            <i class="bi bi-shield-check text-emerald-400" aria-hidden="true"></i>
                            Portales Oficiales MINEDU & Educación Superior
                        </span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] text-white font-sans">
                            Enlaces e Integración <br />
                            <span class="text-sky-400">Institucional Oficial</span>
                        </h1>
                        <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                            Accede de manera directa y segura a las plataformas nacionales del <strong class="text-white">Ministerio de Educación (MINEDU)</strong> para la consulta de matrícula, verificación de títulos a Nombre de la Nación y servicios académicos.
                        </p>
                        <!-- Quick Stats / Features -->
                        <div class="flex flex-wrap gap-4 pt-2">
                            <div class="flex items-center gap-2.5 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                                <i class="bi bi-award-fill text-amber-400 text-xl" aria-hidden="true"></i>
                                <div>
                                    <p class="text-sm font-extrabold text-white">Verificación Oficial</p>
                                    <p class="text-[11px] text-blue-200 font-medium">Títulos registrados ante MINEDU</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                                <i class="bi bi-pencil-square text-emerald-400 text-xl" aria-hidden="true"></i>
                                <div>
                                    <p class="text-sm font-extrabold text-white">Gestión Académica</p>
                                    <p class="text-[11px] text-blue-200 font-medium">Matrícula y actas de notas</p>
                                </div>
                            </div>
                        </div>
                        <!-- CTA Button -->
                        <div class="pt-2">
                            <a href="#plataformas" class="inline-flex items-center justify-center px-7 py-4 text-base font-extrabold text-blue-900 bg-white hover:bg-blue-50 rounded-2xl transition-all shadow-xl hover:shadow-2xl group">
                                <i class="bi bi-box-arrow-up-right mr-2 text-blue-700 group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                                Explorar Portales MINEDU
                            </a>
                        </div>
                    </div>
                    <!-- Right: Info Panel -->
                    <div class="lg:col-span-5 relative" aria-hidden="true">
                        <div class="relative bg-slate-800 border border-slate-700 p-8 rounded-3xl shadow-xl space-y-5">
                            <div class="flex items-center justify-between border-b border-white/10 pb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center text-xl shadow-inner text-white">
                                        <i class="bi bi-building-gear"></i>
                                    </div>
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wider text-blue-200 font-extrabold">Gobierno del Perú</p>
                                        <p class="text-lg font-extrabold text-white">Ministerio de Educación</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 rounded-full text-xs font-extrabold">
                                    IESTP FVC
                                </span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-pencil-square text-blue-300 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">REGISTRA MINEDU</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Nómina de matriculados y registro modular de estudiantes.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-award-fill text-emerald-400 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">TITULA MINEDU</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Verificación de títulos profesionales a Nombre de la Nación.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-people-fill text-purple-300 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">CONECTA MINEDU</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Plataforma de comunidad y recursos pedagógicos de IESTP.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10">
                                    <i class="bi bi-graph-up-arrow text-amber-300 text-lg mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-sm font-extrabold text-white">AVANZA MINEDU</p>
                                        <p class="text-xs text-blue-100 mt-0.5">Seguimiento de trayectoria formativa y desarrollo laboral.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PLATAFORMAS OFICIALES GRID -->
        <section id="plataformas" aria-label="Portales institucionales de MINEDU" class="py-16 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                        Plataformas del Ministerio de Educación
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-950 mt-4 font-sans">
                        Acceso a los Sistemas Institucionales
                    </h2>
                    <div class="w-16 h-1.5 bg-blue-600 mx-auto mt-4 rounded-full"></div>
                    <p class="text-lg text-slate-600 mt-6 leading-relaxed">
                        Haga clic en cualquiera de las tarjetas para acceder directamente al sitio oficial del MINEDU correspondiente a su trámite o consulta.
                    </p>
                </div>

                <!-- Dynamic Links Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <article
                        v-for="link in enrichedLinks"
                        :key="link.id"
                        class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative group"
                    >
                        <!-- Accent Bar -->
                        <div class="h-1.5 transition-all duration-300 group-hover:h-2" :class="link.barColor" role="presentation"></div>

                        <div class="p-7 sm:p-8 space-y-5 flex-1">
                            <!-- Top Badges -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold px-3 py-1 rounded-full border" :class="link.badgeClass">
                                    <i class="bi bi-building-check"></i> MINEDU Oficial
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1">
                                    <i class="bi bi-shield-lock-fill text-emerald-600"></i> Portal Seguro
                                </span>
                            </div>

                            <!-- Main Title & Avatar -->
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-sm shrink-0 transition-transform duration-300 group-hover:scale-110" :class="link.avatarColor">
                                    <i class="bi" :class="link.customIcon"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-extrabold text-2xl text-blue-950 leading-snug group-hover:text-blue-600 transition-colors">
                                        {{ link.name }}
                                    </h3>
                                    <p class="text-xs font-bold text-blue-600 mt-0.5">
                                        {{ link.subtitle }}
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ link.description }}
                            </p>

                            <!-- Metadata Pills -->
                            <div class="pt-1 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200/70">
                                    <i class="bi bi-person-badge text-blue-600"></i> {{ link.targetAudience }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-50 px-3 py-1 rounded-xl border border-slate-200/50 font-mono">
                                    <i class="bi bi-globe"></i> {{ link.domainHost }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer Action -->
                        <div class="px-7 sm:px-8 py-4 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between gap-4">
                            <span class="text-xs text-slate-500 font-medium hidden sm:inline-flex items-center gap-1">
                                <i class="bi bi-info-circle text-blue-500"></i> Redirección externa
                            </span>
                            <a
                                :href="link.link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 hover:brightness-110 text-white font-extrabold text-xs sm:text-sm rounded-xl transition-all shadow-sm gap-2"
                                :class="link.barColor"
                            >
                                <span>Ingresar a {{ link.name }} MINEDU</span>
                                <i class="bi bi-box-arrow-up-right text-xs"></i>
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- MÁS SERVICIOS DIGITALES -->
        <section aria-label="Servicios digitales institucionales" class="py-16 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                        Gestión Interna e Integrada
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-950 mt-4 font-sans">
                        Servicios Digitales del IESTP FVC
                    </h2>
                    <div class="w-16 h-1.5 bg-blue-600 mx-auto mt-4 rounded-full"></div>
                    <p class="text-lg text-slate-600 mt-6 leading-relaxed">
                        Complemente sus trámites accediendo a las herramientas virtuales del propio instituto.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-blue-700 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="bi bi-inbox-fill" aria-hidden="true"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-blue-950 mb-3">Mesa de Partes Virtual</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">
                                Ingrese solicitudes, trámites de título, constancias y documentos oficiales digitalmente sin salir de casa.
                            </p>
                        </div>
                        <Link :href="route('mesa-de-partes')" class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-all">
                            Ingresar a Mesa de Partes <i class="bi bi-arrow-right ml-1"></i>
                        </Link>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-blue-700 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="bi bi-book-half" aria-hidden="true"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-blue-950 mb-3">Biblioteca Virtual</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">
                                Acceso a catálogo bibliográfico digital, libros de especialidad técnica y repositorios para la investigación estudiantil.
                            </p>
                        </div>
                        <a href="https://iestpfranciscovigocaballero.bibliotecalatina.com/login" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-all">
                            Acceder a Biblioteca <i class="bi bi-box-arrow-up-right ml-1 text-[10px]"></i>
                        </a>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-slate-50/60 hover:bg-white border border-slate-200/80 hover:border-blue-200 p-8 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md group flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-blue-700 text-white rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="bi bi-journal-bookmark" aria-hidden="true"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-blue-950 mb-3">TUPA Institucional</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">
                                Consulte los requisitos, costos de tasa administrativa y plazos de atención para cada trámite académico y administrativo.
                            </p>
                        </div>
                        <Link :href="route('tupa')" class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-all">
                            Ver Reglamento TUPA <i class="bi bi-arrow-right ml-1"></i>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- PREGUNTAS FRECUENTES (FAQ) -->
        <section aria-label="Preguntas frecuentes sobre portales MINEDU" class="py-16 bg-slate-50 border-t border-slate-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="text-[11px] font-extrabold tracking-widest text-blue-700 uppercase bg-blue-100 px-4 py-1.5 rounded-full border border-blue-200/60">
                        Ayuda y Orientación
                    </span>
                    <h2 class="text-3xl font-extrabold text-blue-950 mt-4 font-sans">
                        Preguntas Frecuentes
                    </h2>
                </div>

                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                        <button
                            type="button"
                            @click="toggleFaq(1)"
                            class="w-full px-6 py-5 text-left font-extrabold text-blue-950 flex items-center justify-between gap-4 hover:bg-slate-50 transition"
                        >
                            <span class="text-base flex items-center gap-2">
                                <i class="bi bi-award text-emerald-600"></i>
                                ¿Cómo verifico mi título profesional en Titula MINEDU?
                            </span>
                            <i class="bi bi-chevron-down text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': activeFaq === 1 }"></i>
                        </button>
                        <div v-show="activeFaq === 1" class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                            Al ingresar al portal Titula MINEDU, puedes ingresar tu número de DNI o apellido paterno. El sistema mostrará la constancia de inscripción de tu título profesional técnico o certificación emitida por el instituto a Nombre de la Nación.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                        <button
                            type="button"
                            @click="toggleFaq(2)"
                            class="w-full px-6 py-5 text-left font-extrabold text-blue-950 flex items-center justify-between gap-4 hover:bg-slate-50 transition"
                        >
                            <span class="text-base flex items-center gap-2">
                                <i class="bi bi-pencil-square text-blue-600"></i>
                                ¿Quién realiza la inscripción de matrícula en Registra MINEDU?
                            </span>
                            <i class="bi bi-chevron-down text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': activeFaq === 2 }"></i>
                        </button>
                        <div v-show="activeFaq === 2" class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                            La Secretaría Académica del IESTP Francisco Vigo Caballero realiza la carga de las nóminas oficiales de matriculados y registros modulares. Los estudiantes pueden consultar y validar la conformidad de sus registros según los periodos académicos.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                        <button
                            type="button"
                            @click="toggleFaq(3)"
                            class="w-full px-6 py-5 text-left font-extrabold text-blue-950 flex items-center justify-between gap-4 hover:bg-slate-50 transition"
                        >
                            <span class="text-base flex items-center gap-2">
                                <i class="bi bi-people text-purple-600"></i>
                                ¿Qué beneficios encuentro en la plataforma Conecta MINEDU?
                            </span>
                            <i class="bi bi-chevron-down text-blue-600 transition-transform duration-200" :class="{ 'rotate-180': activeFaq === 3 }"></i>
                        </button>
                        <div v-show="activeFaq === 3" class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                            Conecta MINEDU brinda acceso a talleres virtuales de orientación profesional, repositorios de recursos educativos, noticias de convocatorias tecnológicas a nivel nacional y vinculación laboral para egresados de educación superior técnica.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA DE SOPORTE INSTITUCIONAL -->
        <section aria-label="Soporte y contacto" class="py-16 bg-slate-950 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-blue-900 rounded-3xl p-8 sm:p-12 border border-blue-800 shadow-md">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-4">
                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-blue-300 bg-blue-950/70 px-4 py-1.5 rounded-full border border-blue-400/30 inline-block">
                                Atención al Usuario
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-sans leading-tight">
                                ¿Necesitas asistencia con tu registro o título?
                            </h2>
                            <p class="text-blue-100 text-base leading-relaxed max-w-xl">
                                Si requieres constancias, verificación previa de actas o asistencia para acceder a los portales institucionales del MINEDU, comunícate con la Secretaría Académica de nuestro instituto.
                            </p>
                            <div class="flex flex-wrap gap-5 pt-1 text-sm text-blue-200">
                                <a v-if="enterprise?.email" :href="`mailto:${enterprise.email}`" class="flex items-center gap-2 hover:text-white transition">
                                    <i class="bi bi-envelope-fill text-blue-400 text-lg" aria-hidden="true"></i>
                                    <span>{{ enterprise.email }}</span>
                                </a>
                                <a v-if="enterprise?.phone_number_1" :href="`tel:${enterprise.phone_number_1}`" class="flex items-center gap-2 hover:text-white transition">
                                    <i class="bi bi-telephone-fill text-blue-400 text-lg" aria-hidden="true"></i>
                                    <span>{{ enterprise.phone_number_1 }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="lg:col-span-4 flex flex-col gap-4">
                            <a
                                v-if="enterprise?.whatsapp_link"
                                :href="enterprise.whatsapp_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm rounded-2xl transition-all shadow-lg hover:shadow-emerald-500/25"
                            >
                                <i class="bi bi-whatsapp mr-2 text-lg" aria-hidden="true"></i> Consultar por WhatsApp
                            </a>
                            <Link
                                :href="route('mesa-de-partes')"
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-extrabold text-sm rounded-2xl transition-all"
                            >
                                <i class="bi bi-file-earmark-arrow-up mr-2 text-lg text-blue-300" aria-hidden="true"></i>
                                Mesa de Partes Virtual
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
