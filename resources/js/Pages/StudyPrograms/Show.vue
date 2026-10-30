<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    program: {
        type: Object,
        required: true,
    },
});

const prog = computed(() => props.program);
const metaObj = computed(() => prog.value.meta || {});
const color = computed(() => prog.value.color_config || {
    key: 'blue',
    solid_bg: 'bg-blue-600',
    bar: 'bg-blue-600',
    text: 'text-blue-600',
    badge: 'bg-blue-100 text-blue-800 border-blue-200',
    btn_primary: 'bg-blue-600 hover:bg-blue-700 text-white',
});

const accent = computed(() => metaObj.value.accent || prog.value.accent || 'blue');
const icon = computed(() => metaObj.value.icon || prog.value.icon || 'bi-mortarboard-fill');
const badge = computed(() => metaObj.value.tag || prog.value.tag || 'Carrera Profesional Técnica');
const barColorClass = computed(() => color.value.bar || `bg-${accent.value}-500`);
const iconBgClass = computed(() => `bg-${accent.value}-50 text-${accent.value}-600 border-${accent.value}-100`);
const borderHoverClass = computed(() => `hover:border-${accent.value}-300`);
const badgeModuleClass = computed(() => `bg-${accent.value}-100 text-${accent.value}-800`);
const sidebarIconClass = computed(() => `text-${accent.value}-600`);

// Images
const imagesList = computed(() => prog.value.images || []);
const mainImage = computed(() => imagesList.value.find((i) => i.is_main) || imagesList.value[0]);
const carouselImages = computed(() => {
    const valid = imagesList.value.filter((img) => Boolean(img.path));
    if (valid.length === 0 && mainImage.value) {
        return [mainImage.value];
    }
    return valid;
});
const useCarousel = computed(() => Boolean(prog.value.enable_carousel) && carouselImages.value.length > 0);

// Carousel state
const currentSlide = ref(0);
const isPaused = ref(false);
let autoplayTimer = null;
const intervalDuration = 5500;

const nextSlide = () => {
    if (carouselImages.value.length <= 1) return;
    currentSlide.value = (currentSlide.value + 1) % carouselImages.value.length;
};

const prevSlide = () => {
    if (carouselImages.value.length <= 1) return;
    currentSlide.value = (currentSlide.value - 1 + carouselImages.value.length) % carouselImages.value.length;
};

const goToSlide = (idx) => {
    currentSlide.value = idx;
};

const startAutoplay = () => {
    stopAutoplay();
    if (carouselImages.value.length <= 1) return;
    autoplayTimer = setInterval(() => {
        if (!isPaused.value) {
            nextSlide();
        }
    }, intervalDuration);
};

const stopAutoplay = () => {
    if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = null;
    }
};

// Touch swipe
let touchStartX = 0;
const onTouchStart = (e) => {
    if (e.changedTouches?.length) {
        touchStartX = e.changedTouches[0].screenX;
    }
    isPaused.value = true;
};
const onTouchEnd = (e) => {
    if (e.changedTouches?.length) {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 40) {
            if (diff > 0) nextSlide();
            else prevSlide();
        }
    }
    isPaused.value = false;
};

// Lightbox modal state
const lightboxOpen = ref(false);
const activePhotoIndex = ref(0);

const openLightbox = (idx) => {
    activePhotoIndex.value = idx;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const nextPhoto = () => {
    if (imagesList.value.length === 0) return;
    activePhotoIndex.value = (activePhotoIndex.value + 1) % imagesList.value.length;
};

const prevPhoto = () => {
    if (imagesList.value.length === 0) return;
    activePhotoIndex.value = (activePhotoIndex.value - 1 + imagesList.value.length) % imagesList.value.length;
};

const handleKeyDown = (e) => {
    if (lightboxOpen.value) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextPhoto();
        if (e.key === 'ArrowLeft') prevPhoto();
    } else if (useCarousel.value) {
        if (e.key === 'ArrowRight') nextSlide();
        if (e.key === 'ArrowLeft') prevSlide();
    }
};

onMounted(() => {
    if (useCarousel.value && carouselImages.value.length > 1) {
        startAutoplay();
    }
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    stopAutoplay();
    window.removeEventListener('keydown', handleKeyDown);
});

// Helper for image URLs
const getImgUrl = (img) => {
    if (!img) return '';
    if (img.url) return img.url;
    if (img.path) {
        return img.path.startsWith('http') ? img.path : `/storage/${img.path.replace(/^\//, '')}`;
    }
    return '';
};

// Course schema
const courseSchema = computed(() => ({
    '@context': 'https://schema.org',
    '@type': 'Course',
    name: prog.value.name,
    description: (prog.value.description || '').replace(/<[^>]*>?/gm, '').trim(),
    provider: {
        '@type': 'EducationalOrganization',
        name: 'IESTP Francisco Vigo Caballero',
        sameAs: 'https://iestpvigocaballero.edu.pe',
    },
    educationalCredentialAwarded: `Profesional Técnico en ${prog.value.name}`,
}));
</script>

<template>
    <Head>
        <title>{{ prog.name }} — IESTP Francisco Vigo Caballero</title>
        <meta name="description" :content="prog.description ? prog.description.substring(0, 155) : prog.name" />
        <meta name="keywords" :content="`${prog.name}, carrera tecnica, IESTP Francisco Vigo Caballero, Uchiza, ${badge}`" />
        <meta name="robots" content="index, follow" />
        <meta property="og:type" content="website" />
        <meta property="og:title" :content="`${prog.name} — IESTP Francisco Vigo Caballero`" />
        <meta property="og:description" :content="prog.description ? prog.description.substring(0, 155) : prog.name" />
        <meta v-if="mainImage" property="og:image" :content="getImgUrl(mainImage)" />
    </Head>

    <PublicLayout :title="prog.name">
        <!-- ═══ HERO HEADER SECTION WITH CAROUSEL ═══════════════════════ -->
        <section
            class="relative bg-slate-950 text-white overflow-hidden select-none border-b border-slate-800"
            :aria-label="`Portada del Programa ${prog.name}`"
            @mouseenter="isPaused = true"
            @mouseleave="isPaused = false"
            @touchstart="onTouchStart"
            @touchend="onTouchEnd"
        >
            <!-- Background carousel or static -->
            <div v-if="useCarousel" class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                    v-for="(img, idx) in carouselImages"
                    :key="idx"
                    class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                    :class="currentSlide === idx ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                >
                    <div
                        class="absolute inset-0 bg-cover bg-center transition-transform duration-[8000ms] ease-out"
                        :class="currentSlide === idx ? 'scale-105' : 'scale-100'"
                        :style="{ backgroundImage: `url('${getImgUrl(img)}')` }"
                    ></div>
                    <div class="absolute inset-0 bg-slate-950/85"></div>
                </div>
            </div>
            <div v-else class="absolute inset-0 pointer-events-none">
                <div
                    v-if="mainImage"
                    class="absolute inset-0 bg-cover bg-center"
                    :style="{ backgroundImage: `url('${getImgUrl(mainImage)}')` }"
                ></div>
                <div class="absolute inset-0 bg-slate-950/90"></div>
            </div>

            <!-- Foreground Content Container -->
            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24">
                <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-12">
                    <!-- Text Column -->
                    <div class="lg:w-7/12 text-left space-y-5">
                        <div class="w-16 h-1 rounded-full" :class="color.bar || 'bg-blue-600'"></div>

                        <span
                            class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold px-4 py-2 rounded-full uppercase tracking-widest border"
                            :class="color.badge || 'bg-blue-100 text-blue-800 border-blue-200'"
                        >
                            <i class="bi text-sm" :class="icon"></i>
                            {{ badge }}
                        </span>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-tight text-white font-sans">
                            {{ prog.name }}
                        </h1>

                        <p v-if="prog.carousel_alt_text" class="text-base sm:text-lg text-slate-200 leading-relaxed font-sans max-w-2xl">
                            {{ prog.carousel_alt_text }}
                        </p>
                        <p v-else class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans max-w-2xl">
                            Prepárate para destacar en el campo laboral de manera rápida y efectiva con nuestro programa formativo integral, gratuito y con certificación oficial.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <Link
                                href="/admision-y-matricula/examen-de-admision"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 font-bold rounded-xl shadow-lg transition-transform hover:scale-105 text-xs sm:text-sm group"
                                :class="color.btn_primary || 'bg-blue-600 text-white'"
                            >
                                <i class="bi bi-pencil-square text-base group-hover:scale-110 transition-transform"></i>
                                <span>Postular a Admisión</span>
                            </Link>

                            <a
                                href="#plan-estudios"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-white font-bold rounded-xl transition-colors text-xs sm:text-sm"
                            >
                                <i class="bi bi-journal-bookmark-fill text-sky-400 text-base"></i>
                                <span>Plan de Estudios</span>
                            </a>
                        </div>

                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-6 border-t border-white/10 max-w-xl">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-400 uppercase font-semibold tracking-wider">Duración</p>
                                <p class="text-lg sm:text-xl font-bold mt-1" :class="color.text || 'text-blue-400'">3 Años</p>
                                <p class="text-xs sm:text-sm text-gray-400">6 Ciclos Académicos</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-400 uppercase font-semibold tracking-wider">Certificaciones</p>
                                <p class="text-lg sm:text-xl font-bold mt-1" :class="color.text || 'text-blue-400'">Modulares</p>
                                <p class="text-xs sm:text-sm text-gray-400">Anuales de Minedu</p>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <p class="text-xs sm:text-sm text-gray-400 uppercase font-semibold tracking-wider">Inversión</p>
                                <p class="text-lg sm:text-xl font-bold mt-1" :class="color.text || 'text-blue-400'">Gratuito</p>
                                <p class="text-xs sm:text-sm text-gray-400">Educación Pública</p>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Column: Logo / Main Image -->
                    <div class="lg:w-5/12 w-full flex justify-center lg:justify-end">
                        <div
                            class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl aspect-[16/10] w-full max-w-lg flex items-center justify-center group"
                            :class="color.solid_bg || 'bg-slate-900'"
                            style="aspect-ratio: 16/10;"
                        >
                            <img
                                v-if="prog.logo_url || prog.logo_path"
                                :src="prog.logo_url || getImgUrl({ path: prog.logo_path })"
                                :alt="`Logo ${prog.name}`"
                                class="max-h-[85%] max-w-[85%] object-contain drop-shadow-2xl group-hover:scale-105 transition-transform duration-500"
                            />
                            <div v-else-if="mainImage" class="w-full h-full relative">
                                <img
                                    :src="getImgUrl(mainImage)"
                                    :alt="prog.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
                                />
                                <div class="absolute inset-0 bg-slate-950/40"></div>
                            </div>
                            <i v-else class="bi text-9xl text-white/40 drop-shadow z-10" :class="icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <template v-if="useCarousel && carouselImages.length > 1">
                <button
                    type="button"
                    class="hidden sm:flex absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-white items-center justify-center transition-all duration-200 shadow-xl hover:scale-105 focus:outline-none cursor-pointer"
                    aria-label="Imagen anterior"
                    @click="prevSlide"
                >
                    <i class="bi bi-chevron-left text-xl sm:text-2xl"></i>
                </button>

                <button
                    type="button"
                    class="hidden sm:flex absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-30 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-white items-center justify-center transition-all duration-200 shadow-xl hover:scale-105 focus:outline-none cursor-pointer"
                    aria-label="Siguiente imagen"
                    @click="nextSlide"
                >
                    <i class="bi bi-chevron-right text-xl sm:text-2xl"></i>
                </button>

                <!-- Indicator Bar -->
                <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 z-30 pointer-events-none">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 sm:gap-2 bg-slate-900/90 border border-slate-800 px-3 py-1.5 rounded-full pointer-events-auto shadow-lg">
                            <button
                                v-for="(_, index) in carouselImages"
                                :key="index"
                                type="button"
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                :class="currentSlide === index ? `${color.solid_bg} w-7 shadow-md` : 'bg-slate-600 hover:bg-slate-500 w-2'"
                                :aria-label="`Ir a imagen ${index + 1}`"
                                @click="goToSlide(index)"
                            ></button>
                        </div>

                        <div class="hidden md:flex items-center gap-3 bg-slate-900/90 border border-slate-800 px-4 py-1.5 rounded-full text-xs font-semibold text-slate-300 pointer-events-auto shadow-lg">
                            <button
                                type="button"
                                class="hover:text-white transition-colors cursor-pointer"
                                :title="isPaused ? 'Reanudar carrusel' : 'Pausar carrusel'"
                                @click="isPaused = !isPaused"
                            >
                                <i class="bi text-sm" :class="isPaused ? 'bi-play-fill text-amber-400' : 'bi-pause-fill'"></i>
                            </button>
                            <span class="text-white font-bold">{{ currentSlide + 1 }} / {{ carouselImages.length }}</span>
                        </div>
                    </div>
                </div>
            </template>
        </section>

        <!-- ═══ MAIN LAYOUT GRID ═══════════════════════════════════════ -->
        <section class="py-16 bg-gray-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- LEFT/MAIN COLUMN (2 spans) -->
                    <div class="lg:col-span-2 space-y-12">
                        <!-- About card -->
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 rounded-full" :class="barColorClass"></span>
                                Sobre la Carrera Profesional
                            </h2>
                            <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                                <p class="text-base md:text-lg font-medium text-gray-800">
                                    {{ prog.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Competencies -->
                        <div v-if="prog.competencies && prog.competencies.length > 0" class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 rounded-full" :class="barColorClass"></span>
                                Perfil de Competencias Específicas
                            </h2>
                            <p class="text-gray-600 mb-8 text-base">
                                A lo largo de los 3 años de formación práctica, el estudiante del programa desarrollará capacidades técnicas en las siguientes áreas de especialización:
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div
                                    v-for="(comp, cIdx) in prog.competencies"
                                    :key="cIdx"
                                    class="flex gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 transition-colors"
                                    :class="borderHoverClass"
                                >
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border" :class="iconBgClass">
                                        <i class="bi text-lg" :class="comp.icon || 'bi-mortarboard-fill'"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-base mb-1">{{ comp.title }}</h3>
                                        <p class="text-sm text-gray-600 leading-relaxed">{{ comp.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Curriculum & Modular certifications / Itinerario Formativo -->
                        <div
                            v-if="(prog.modules && prog.modules.length > 0) || prog.training_itinerary_path"
                            id="plan-estudios"
                            class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-8 scroll-mt-24"
                        >
                            <div>
                                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                    <span class="w-2 h-8 rounded-full" :class="barColorClass"></span>
                                    Plan de Estudios e Itinerario Formativo
                                </h2>
                                <p class="text-gray-600 text-base">
                                    Nuestro plan curricular está estructurado modularmente para permitir una rápida inserción laboral. Cada año académico completado te acredita con un certificado oficial del Ministerio de Educación:
                                </p>
                            </div>

                            <!-- Modules list -->
                            <div v-if="prog.modules && prog.modules.length > 0" class="space-y-6">
                                <div
                                    v-for="(module, mIdx) in prog.modules"
                                    :key="mIdx"
                                    class="timeline-item flex gap-6 relative"
                                >
                                    <div
                                        class="timeline-bullet w-10 h-10 rounded-full text-white font-extrabold flex items-center justify-center shrink-0 relative z-10 shadow-sm"
                                        :class="color.solid_bg || 'bg-blue-600'"
                                    >
                                        {{ mIdx + 1 }}
                                    </div>
                                    <div class="flex-grow bg-gray-50 border border-gray-100 rounded-2xl p-6 relative">
                                        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                                            <span class="px-3 py-1 text-sm font-bold uppercase tracking-wider rounded-full" :class="badgeModuleClass">
                                                Módulo Informativo {{ mIdx + 1 }}
                                            </span>
                                            <span class="text-sm text-gray-500 font-medium flex items-center gap-1">
                                                <i class="bi bi-calendar3"></i> Año Académico {{ mIdx + 1 }}
                                            </span>
                                        </div>
                                        <h3 class="font-extrabold text-gray-900 text-lg mb-2">
                                            {{ module.module }}
                                        </h3>
                                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                                            Certificación otorgada al concluir satisfactoriamente las unidades didácticas teóricas y las correspondientes experiencias formativas en situaciones reales de trabajo.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Itinerario Formativo PDF Download Card -->
                            <div
                                v-if="prog.training_itinerary_path"
                                class="p-6 md:p-8 rounded-2xl bg-slate-900 text-white border border-slate-700/60 shadow-lg relative overflow-hidden"
                            >
                                <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-red-500/20 border border-red-400/30 text-red-400 flex items-center justify-center text-3xl shrink-0 shadow-inner">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                                <span class="text-xs text-slate-400 font-medium">Itinerario Formativo (PDF)</span>
                                            </div>
                                            <h3 class="text-lg md:text-xl font-black text-white tracking-tight">
                                                Itinerario Formativo y Malla Curricular
                                            </h3>
                                            <p class="text-xs md:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                                                Consulta el plan de estudios oficial, distribución de unidades didácticas, créditos y horas lectivas de {{ prog.name }}.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 w-full lg:w-auto shrink-0 flex-wrap sm:flex-nowrap">
                                        <a
                                            :href="prog.training_itinerary_url || `/storage/${prog.training_itinerary_path.replace(/^\//, '')}`"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs md:text-sm border border-white/20 transition-all duration-200 shadow-sm hover:scale-[1.02] active:scale-[0.98]"
                                        >
                                            <i class="bi bi-box-arrow-up-right text-sm"></i>
                                            <span>Ver Documento</span>
                                        </a>

                                        <a
                                            :href="prog.training_itinerary_url || `/storage/${prog.training_itinerary_path.replace(/^\//, '')}`"
                                            :download="`${prog.slug}-itinerario-formativo.pdf`"
                                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs md:text-sm transition-all duration-200 shadow-md hover:scale-[1.02] active:scale-[0.98]"
                                        >
                                            <i class="bi bi-download text-sm"></i>
                                            <span>Descargar PDF</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO ALBUM / GALLERY -->
                        <div v-if="imagesList.length > 0" class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 rounded-full" :class="barColorClass"></span>
                                Álbum de Fotos & Galería
                            </h2>
                            <p class="text-gray-600 mb-8 text-base">
                                Explora las actividades prácticas, laboratorios, talleres y el trabajo de campo que realizan nuestros estudiantes.
                            </p>

                            <!-- Grid layout -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div
                                    v-for="(img, idx) in imagesList"
                                    :key="idx"
                                    class="relative group rounded-2xl overflow-hidden cursor-pointer aspect-video border border-gray-100 hover:scale-105 transition-transform duration-300"
                                    @click="openLightbox(idx)"
                                >
                                    <img :src="getImgUrl(img)" :alt="`Galería ${prog.name}`" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-lg">
                                            <i class="bi bi-eye-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT/SIDEBAR COLUMN (1 span) -->
                    <div class="space-y-8">
                        <!-- Technical sheet -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                                <i class="bi bi-info-square text-lg" :class="sidebarIconClass"></i>
                                Ficha Técnica
                            </h3>

                            <div class="space-y-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-award text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Título Oficial</p>
                                        <p class="text-sm text-gray-700 font-semibold mt-0.5 leading-snug">
                                            Profesional Técnico a Nombre de la Nación
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-hourglass-split text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Duración de Carrera</p>
                                        <p class="text-sm text-gray-700 font-semibold mt-0.5">3 Años (6 Semestres)</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-clock text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Horario Regular</p>
                                        <p class="text-sm text-gray-700 font-semibold mt-0.5">Lunes a Viernes 7:30 am – 1:30 pm</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-cash-coin text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Pensión y Mensualidad</p>
                                        <p class="text-sm text-gray-700 font-semibold mt-0.5">Costo Social S/ 0 (Gratuito)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Career Prospects / Campo Laboral -->
                        <div v-if="prog.jobFields && prog.jobFields.length > 0" class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                                <i class="bi bi-briefcase text-lg" :class="sidebarIconClass"></i>
                                Campo Laboral
                            </h3>
                            <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                                Al egresar, estarás preparado para desempeñarte con éxito en:
                            </p>
                            <ul class="space-y-3">
                                <li
                                    v-for="(field, fIdx) in prog.jobFields"
                                    :key="fIdx"
                                    class="text-sm text-gray-700 flex items-start gap-2.5 leading-relaxed"
                                >
                                    <i class="bi bi-check2-circle shrink-0 mt-0.5 text-base" :class="sidebarIconClass"></i>
                                    <span>{{ field.description }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Admission Requirements -->
                        <div v-if="prog.requirements && prog.requirements.length > 0" class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                                <i class="bi bi-clipboard2-check text-lg" :class="sidebarIconClass"></i>
                                Requisitos de Matrícula
                            </h3>
                            <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                                Documentación mínima necesaria para el proceso de admisión:
                            </p>
                            <ul class="space-y-3">
                                <li
                                    v-for="(req, rIdx) in prog.requirements"
                                    :key="rIdx"
                                    class="text-sm text-gray-700 flex items-start gap-2.5 leading-relaxed font-semibold"
                                >
                                    <i class="bi bi-dot shrink-0 text-xl -mt-1" :class="sidebarIconClass"></i>
                                    <span>{{ req.description }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- CTA Box -->
                        <div
                            class="rounded-3xl p-6 text-white text-center shadow-lg relative overflow-hidden"
                            :class="color.solid_bg || 'bg-blue-800'"
                        >
                            <div class="relative z-10">
                                <h4 class="font-extrabold text-xl mb-3">¿Deseas iniciar tu inscripción?</h4>
                                <p class="text-sm text-white/90 leading-relaxed mb-6">
                                    Las vacantes son limitadas y no pagas mensualidad alguna durante toda la carrera técnica. ¡Asegura tu futuro!
                                </p>
                                <Link
                                    href="/admision-y-matricula/examen-de-admision"
                                    class="inline-block w-full bg-white text-slate-900 hover:bg-gray-100 py-3 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-md"
                                >
                                    Iniciar Inscripción
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ LIGHTBOX MODAL ═════════════════════════════════════════ -->
        <teleport to="body">
            <div
                v-if="lightboxOpen"
                class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/95 backdrop-blur-sm p-4 select-none"
            >
                <div class="absolute inset-0" @click="closeLightbox"></div>

                <div class="relative max-w-5xl w-full flex flex-col items-center justify-center z-10" @click.stop>
                    <div class="relative max-h-[80vh] w-full flex items-center justify-center overflow-hidden rounded-2xl">
                        <img
                            :src="getImgUrl(imagesList[activePhotoIndex])"
                            class="max-h-[80vh] max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300"
                        />

                        <!-- Prev / Next buttons -->
                        <button
                            type="button"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-sm border border-white/10 transition-colors cursor-pointer"
                            @click="prevPhoto"
                        >
                            <i class="bi bi-chevron-left text-xl"></i>
                        </button>
                        <button
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-sm border border-white/10 transition-colors cursor-pointer"
                            @click="nextPhoto"
                        >
                            <i class="bi bi-chevron-right text-xl"></i>
                        </button>
                    </div>

                    <!-- Footer -->
                    <div class="w-full flex items-center justify-between text-white mt-6 px-2">
                        <span class="text-base font-semibold tracking-wide">
                            {{ prog.name }} — Foto {{ activePhotoIndex + 1 }} de {{ imagesList.length }}
                        </span>
                        <button
                            type="button"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-base font-bold border border-white/10 transition-colors cursor-pointer"
                            @click="closeLightbox"
                        >
                            <i class="bi bi-x-lg"></i> Cerrar
                        </button>
                    </div>

                    <!-- Thumbnail dots -->
                    <div class="flex items-center justify-center gap-2 mt-4 flex-wrap">
                        <button
                            v-for="(_, idx) in imagesList"
                            :key="idx"
                            type="button"
                            class="w-3 h-3 rounded-full transition-all cursor-pointer"
                            :class="activePhotoIndex === idx ? `${barColorClass} scale-125` : 'bg-white/40 hover:bg-white/60'"
                            @click="activePhotoIndex = idx"
                        ></button>
                    </div>
                </div>
            </div>
        </teleport>
    </PublicLayout>
</template>
