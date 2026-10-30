<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const ent = computed(() => props.enterprise || {});

const principles = computed(() => {
    const raw = ent.value.principles || 'Calidad, Transparencia, Inclusión, Trabajo colaborativo, Interculturalidad, Innovación';
    return raw.split(',').map((p) => p.trim()).filter(Boolean);
});

const values = computed(() => {
    const raw = ent.value.values || 'Responsabilidad, Orden, Disciplina, Solidaridad, Honestidad, Respeto';
    return raw.split(',').map((v) => v.trim()).filter(Boolean);
});

const valueIcons = {
    'Responsabilidad': { icon: 'bi-shield-check', classes: 'text-emerald-600 bg-emerald-50 border-emerald-100' },
    'Orden': { icon: 'bi-layout-sidebar-inset', classes: 'text-blue-600 bg-blue-50 border-blue-100' },
    'Disciplina': { icon: 'bi-star-fill', classes: 'text-sky-600 bg-sky-50 border-sky-100' },
    'Solidaridad': { icon: 'bi-heart-fill', classes: 'text-rose-600 bg-rose-50 border-rose-100' },
    'Honestidad': { icon: 'bi-check-circle-fill', classes: 'text-indigo-600 bg-indigo-50 border-indigo-100' },
    'Respeto': { icon: 'bi-people-fill', classes: 'text-violet-600 bg-violet-50 border-violet-100' },
};

const principleIcons = {
    'Calidad': 'bi-award text-blue-600',
    'Transparencia': 'bi-eye text-cyan-600',
    'Inclusión': 'bi-people text-purple-600',
    'Trabajo colaborativo': 'bi-briefcase text-emerald-600',
    'Interculturalidad': 'bi-globe text-blue-600',
    'Innovación': 'bi-lightbulb text-rose-600',
};

const getValueStyle = (val) => {
    return valueIcons[val] || { icon: 'bi-patch-check-fill', classes: 'text-slate-500 bg-slate-50 border-slate-100' };
};

const getPrincipleIcon = (p) => {
    return principleIcons[p] || 'bi-arrow-right-circle text-blue-600';
};

const jsonLd = computed(() => {
    const desc = ent.value.description ? ent.value.description.replace(/\s+/g, ' ').trim() : '';
    return JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'CollegeOrUniversity',
        'name': 'IESTP Francisco Vigo Caballero',
        'description': desc,
        'url': typeof window !== 'undefined' ? window.location.origin : '',
        'address': {
            '@type': 'PostalAddress',
            'streetAddress': ent.value.address || 'Av. Ricardo Palma N° 1401',
            'addressLocality': ent.value.city || 'Uchiza',
            'addressCountry': 'PE',
        },
    });
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>¿Quiénes Somos? — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Conoce la misión, visión, valores y principios del IESTP Francisco Vigo Caballero de Uchiza. Formando profesionales técnicos competitivos y éticos para el desarrollo de la sociedad." />
            <meta name="keywords" content="quienes somos, mision, vision, valores, principios, IESTP Francisco Vigo Caballero, instituto uchiza, educacion superior publica" />
            <meta name="robots" content="index, follow" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="¿Quiénes Somos? — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Misión, visión, valores y principios de nuestra institución educativa." />
            <meta property="og:image" :content="ent.logo_path ? `/${ent.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white py-16 lg:py-20 overflow-hidden border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                    ¿Quiénes Somos?
                </h1>
                <p v-if="ent.phrase" class="text-sky-400 font-extrabold uppercase tracking-wider text-sm md:text-base mb-6">
                    "{{ ent.phrase }}"
                </p>
                <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                    {{ ent.description }}
                </p>
            </div>
        </section>

        <!-- MAIN GRID SECTION -->
        <section class="py-16 bg-slate-50/50">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 max-w-7xl mx-auto">

                    <!-- LEFT COLUMN: MISSION, VISION, VALUES, PRINCIPLES -->
                    <div class="lg:col-span-2 space-y-12">
                        <!-- Mission & Vision Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-4">Nuestra Misión</h3>
                                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                                        {{ ent.mission }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-4">Nuestra Visión</h3>
                                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                                        {{ ent.vision }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Values Section -->
                        <div v-if="values.length > 0" class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                                Nuestros Valores
                            </h3>
                            <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-8">
                                Los valores del IESTP "Francisco Vigo Caballero" rigen el comportamiento de nuestra comunidad y son el fundamento de la formación integral de nuestros estudiantes.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                <div
                                    v-for="val in values"
                                    :key="val"
                                    class="p-5 bg-white border border-slate-100 rounded-2xl flex flex-col items-center text-center hover:-translate-y-1 hover:border-blue-200 hover:shadow-md transition-all duration-300"
                                >
                                    <div :class="`w-12 h-12 rounded-xl flex items-center justify-center border ${getValueStyle(val).classes} mb-4`">
                                        <i :class="`bi ${getValueStyle(val).icon} text-lg`"></i>
                                    </div>
                                    <h4 class="font-extrabold text-slate-800 text-sm md:text-base">{{ val }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Principles Section -->
                        <div v-if="principles.length > 0" class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                                Principios Institucionales
                            </h3>
                            <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-8">
                                Orientamos nuestro servicio educativo bajo principios esenciales que garantizan la pertinencia, equidad e innovación académica.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div
                                    v-for="p in principles"
                                    :key="p"
                                    class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-4 cursor-default hover:bg-blue-600 hover:border-blue-600 group transition-all duration-300"
                                >
                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center shrink-0 group-hover:border-white">
                                        <i :class="`bi ${getPrincipleIcon(p)} text-lg`"></i>
                                    </div>
                                    <span class="text-xs md:text-sm font-bold text-slate-700 group-hover:text-white transition-colors">{{ p }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: SIDEBAR -->
                    <div class="space-y-8">
                        <!-- Identity Details Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                                <i class="bi bi-card-text text-blue-600 text-xl"></i>
                                Datos de la Institución
                            </h3>

                            <div class="space-y-4 text-sm">
                                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                                    <span class="text-slate-400 font-medium">Nombre Oficial</span>
                                    <span class="font-bold text-slate-800 text-right">{{ ent.company_name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                    <span class="text-slate-400 font-medium">R.U.C.</span>
                                    <span class="font-bold text-slate-800">{{ ent.ruc }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                    <span class="text-slate-400 font-medium">Sector Económico</span>
                                    <span class="font-bold text-slate-800">{{ ent.business_sector || 'Educación Superior Técnica' }}</span>
                                </div>
                                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                                    <span class="text-slate-400 font-medium">Director General</span>
                                    <span class="font-bold text-slate-800 text-right">{{ ent.legal_representative }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-slate-400 font-medium">Ciudad</span>
                                    <span class="font-bold text-slate-800">{{ ent.city }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                                <i class="bi bi-telephone text-blue-600 text-xl"></i>
                                Contacto y Redes
                            </h3>
                            <div class="space-y-4">
                                <div class="flex gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                                        <i class="bi bi-geo-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Dirección</p>
                                        <p class="text-slate-500 leading-relaxed">{{ ent.address }}, {{ ent.city }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                                        <i class="bi bi-envelope text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Correo Electrónico</p>
                                        <a :href="`mailto:${ent.email}`" class="text-slate-500 hover:text-blue-600 transition-colors break-all">{{ ent.email }}</a>
                                    </div>
                                </div>
                                <div class="flex gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                                        <i class="bi bi-telephone text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Teléfono</p>
                                        <p class="text-slate-500">{{ ent.phone_number_1 }}</p>
                                    </div>
                                </div>

                                <!-- Social Media Icons -->
                                <div class="flex gap-2 pt-2 border-t border-slate-50">
                                    <a
                                        v-if="ent.facebook_link"
                                        :href="ent.facebook_link"
                                        target="_blank"
                                        class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 border border-slate-100 flex items-center justify-center transition-colors"
                                    >
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a
                                        v-if="ent.whatsapp_link"
                                        :href="ent.whatsapp_link"
                                        target="_blank"
                                        class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 border border-slate-100 flex items-center justify-center transition-colors"
                                    >
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a
                                        v-if="ent.linkedin_link"
                                        :href="ent.linkedin_link"
                                        target="_blank"
                                        class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-700 border border-slate-100 flex items-center justify-center transition-colors"
                                    >
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                    <a
                                        v-if="ent.twitter_link"
                                        :href="ent.twitter_link"
                                        target="_blank"
                                        class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-900 border border-slate-100 flex items-center justify-center transition-colors"
                                    >
                                        <i class="bi bi-twitter-x"></i>
                                    </a>
                                    <a
                                        v-if="ent.instagram_link"
                                        :href="ent.instagram_link"
                                        target="_blank"
                                        class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-pink-50 text-slate-400 hover:text-pink-600 border border-slate-100 flex items-center justify-center transition-colors"
                                    >
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </PublicLayout>
</template>
