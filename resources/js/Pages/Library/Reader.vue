<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import route from '@/route';

const props = defineProps({
    book: { type: Object, required: true },
    streamUrl: { type: String, required: true },
    directPdfUrl: { type: String, default: '' },
    relatedBooks: { type: Array, default: () => [] },
    isFavorite: { type: Boolean, default: false },
});

const isFavorited           = ref(props.isFavorite);
const isFullscreen          = ref(false);
const isLoading             = ref(true);
const showRelatedDrawer     = ref(false);
const isExternalProtected   = ref(false);
const activeExternalUrl     = ref(props.directPdfUrl || '');

const handleMessage = (event) => {
    if (event.data?.type === 'LIBRARY_READER_EXTERNAL_PROTECTED') {
        isLoading.value = false;
        isExternalProtected.value = true;
        if (event.data.targetUrl) {
            activeExternalUrl.value = event.data.targetUrl;
        }
    }
};

onMounted(() => {
    window.addEventListener('message', handleMessage);
});

onUnmounted(() => {
    window.removeEventListener('message', handleMessage);
});

const baseStreamUrl     = computed(() => props.streamUrl || route('biblioteca.stream', props.book.slug));
const viewerUrl         = computed(() => `${baseStreamUrl.value}#toolbar=1&navpanes=0&scrollbar=1&view=FitH`);
const downloadStreamUrl = computed(() => `${baseStreamUrl.value}?download=1`);

const reloadIframe  = () => {
    isLoading.value = true;
    const iframe    = document.getElementById('libraryViewerIframe');
    if (iframe) {
        const curSrc    = iframe.src;
        iframe.src      = '';
        setTimeout(() => {
            iframe.src = curSrc;
        }, 80);
    }
};

const toggleFullscreen = () => {
    const el = document.getElementById('readerContainer') || document.documentElement;
    if (!document.fullscreenElement) {
        el.requestFullscreen().then(() => {
            isFullscreen.value = true;
        }).catch(() => {});
    } else {
        document.exitFullscreen().then(() => {
            isFullscreen.value = false;
        }).catch(() => {});
    }
};

const toggleFavorite    = async () => {
    const prev          = isFavorited.value;
    isFavorited.value   = !prev;

    try {
        const res   = await axios.post(`/biblioteca/favorito/${props.book.id}`, {}, {
            headers: {
                Accept: 'application/json',
            },
        });
        const json = res.data;
        if (json.success) {
            isFavorited.value = json.favorited;
        } else {
            isFavorited.value = prev;
        }
    } catch {
        isFavorited.value = prev;
    }
};
</script>
<template>
    <Head :title="`Leyendo: ${book.title} - Biblioteca Virtual`" />
    <div class="h-screen w-full flex flex-col bg-slate-900 font-sans text-white overflow-hidden">
        <!-- TOP TOOLBAR -->
        <header class="bg-slate-950/95 backdrop-blur-md text-white border-b border-slate-800 px-4 sm:px-6 py-2.5 flex items-center justify-between z-20 shrink-0">
            <!-- Left: Back & Title -->
            <div class="flex items-center gap-3.5 min-w-0">
                <Link
                    :href="route('biblioteca.index')"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition flex items-center gap-1.5 text-xs font-bold"
                    title="Volver a la Biblioteca"
                >
                    <i class="bi bi-arrow-left text-base"></i>
                    <span class="hidden sm:inline">Biblioteca</span>
                </Link>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                            {{ book.category }}
                        </span>
                        <span
                            v-if="book.is_external || book.external_url"
                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30 inline-flex items-center gap-1"
                        >
                            <i class="bi bi-file-earmark-pdf-fill text-[9px]"></i>
                            <span>{{ (book.publisher || 'PDF Académico Directo').substring(0, 28) }}</span>
                        </span>
                        <h1 class="text-xs sm:text-sm font-bold text-white truncate max-w-sm sm:max-w-xl" :title="book.title">
                            {{ book.title }}
                        </h1>
                    </div>
                    <p class="text-[11px] text-slate-400 truncate mt-0.5">
                        {{ book.author }}
                        <template v-if="book.study_program">
                            · <span class="text-teal-300 font-medium">{{ book.study_program.name }}</span>
                        </template>
                    </p>
                </div>
            </div>
            <!-- Right: Actions -->
            <div class="flex items-center gap-2 shrink-0">
                <!-- Favorite Button -->
                <button
                    type="button"
                    @click="toggleFavorite"
                    :class="[
                        'px-3 py-1.5 rounded-xl border text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer',
                        isFavorited
                            ? 'bg-rose-500/20 border-rose-500/40 text-rose-400'
                            : 'bg-white/10 border-white/10 text-slate-300 hover:text-white',
                    ]"
                    title="Guardar en Mi Biblioteca"
                >
                    <i class="bi" :class="isFavorited ? 'bi-heart-fill text-rose-500' : 'bi-heart'"></i>
                    <span class="hidden sm:inline">{{ isFavorited ? 'En Mi Biblioteca' : 'Favorito' }}</span>
                </button>
                <!-- Reload Iframe -->
                <button
                    type="button"
                    @click="reloadIframe"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs cursor-pointer"
                    title="Recargar Documento PDF"
                >
                    <i class="bi bi-arrow-clockwise text-sm"></i>
                </button>
                <!-- Fullscreen Toggle -->
                <button
                    type="button"
                    @click="toggleFullscreen"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs cursor-pointer"
                    title="Pantalla Completa"
                >
                    <i class="bi" :class="isFullscreen ? 'bi-fullscreen-exit' : 'bi-arrows-fullscreen'"></i>
                </button>
                <!-- Download Direct PDF -->
                <a
                    :href="downloadStreamUrl"
                    class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-semibold transition flex items-center gap-1.5"
                    title="Descargar archivo PDF"
                >
                    <i class="bi bi-download"></i>
                    <span class="hidden sm:inline">Descargar PDF</span>
                </a>
                <!-- Open Direct PDF / Official Viewer in New Tab -->
                <a
                    v-if="activeExternalUrl"
                    :href="activeExternalUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    :class="[
                        'px-3 py-1.5 rounded-xl text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-xs',
                        isExternalProtected
                            ? 'bg-gradient-to-r from-teal-500 to-sky-500 hover:from-teal-400 hover:to-sky-400 ring-2 ring-teal-400/40'
                            : 'bg-teal-600/90 hover:bg-teal-500'
                    ]"
                    :title="isExternalProtected ? 'Abrir visor oficial en pestaña nueva' : 'Abrir PDF directo en pestaña nueva'"
                >
                    <i class="bi bi-box-arrow-up-right text-xs"></i>
                    <span class="hidden sm:inline">{{ isExternalProtected ? 'Abrir en Visor' : 'Abrir PDF' }}</span>
                </a>
                <!-- Related Books Drawer Button -->
                <button
                    v-if="relatedBooks.length > 0"
                    type="button"
                    @click="showRelatedDrawer = !showRelatedDrawer"
                    class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition text-xs cursor-pointer"
                    title="Documentos Relacionados"
                >
                    <i class="bi bi-collection text-sm"></i>
                </button>
            </div>
        </header>
        <!-- IFRAME VIEWER CONTAINER -->
        <main class="flex-1 w-full bg-slate-900 relative overflow-hidden flex flex-col" id="readerContainer">
            <!-- Sub-bar Status Header -->
            <div class="bg-slate-800/90 border-b border-slate-700/80 px-4 py-1.5 text-[11px] text-slate-300 flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-2 truncate">
                    <i v-if="isExternalProtected" class="bi bi-shield-lock-fill text-amber-400"></i>
                    <i v-else class="bi bi-shield-check text-emerald-400"></i>
                    <span class="truncate">
                        <template v-if="isExternalProtected">
                            Documento en Repositorio Oficial:
                            <strong class="text-teal-300">{{ book.publisher || 'Repositorio Académico Protegido' }}</strong>
                        </template>
                        <template v-else>
                            Lector de Documento Directo (PDF):
                            <strong>{{ book.publisher || 'Repositorio Académico Verificado' }}</strong>
                        </template>
                        <template v-if="book.publication_year">
                            ({{ book.publication_year }})
                        </template>
                    </span>
                </div>
                <div class="flex items-center gap-3 shrink-0 text-[10px]">
                    <template v-if="isExternalProtected">
                        <a
                            :href="activeExternalUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-teal-300 hover:text-teal-200 font-semibold flex items-center gap-1 transition"
                        >
                            <span>Abrir visor oficial en nueva pestaña</span>
                            <i class="bi bi-box-arrow-up-right text-[9px]"></i>
                        </a>
                    </template>
                    <span v-else class="text-slate-400 hidden sm:inline">Visualización limpia sin páginas externas</span>
                </div>
            </div>
            <!-- Iframe & Loading Viewport -->
            <div class="flex-1 w-full h-full relative bg-slate-900">
                <!-- Loading State -->
                <transition
                    enter-active-class="transition duration-200"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition duration-300"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="isLoading"
                        class="absolute inset-0 bg-slate-900/85 backdrop-blur-xs z-10 flex flex-col items-center justify-center text-slate-300 space-y-3"
                    >
                        <div class="w-8 h-8 border-2 border-teal-400 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-xs font-semibold tracking-wide">Cargando documento PDF directo...</p>
                    </div>
                </transition>

                <iframe
                    :src="viewerUrl"
                    id="libraryViewerIframe"
                    @load="isLoading = false"
                    class="w-full h-full border-0 bg-slate-800"
                    allow="fullscreen; clipboard-write"
                    allowfullscreen
                    :title="book.title"
                ></iframe>
            </div>
            <!-- Related Books Drawer -->
            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform translate-x-full"
                enter-to-class="transform translate-x-0"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-x-0"
                leave-to-class="transform translate-x-full"
            >
                <aside
                    v-if="showRelatedDrawer"
                    class="absolute top-0 right-0 bottom-0 w-80 bg-slate-950/95 border-l border-slate-800 p-5 shadow-2xl z-30 flex flex-col overflow-y-auto"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-teal-400 flex items-center gap-2">
                            <i class="bi bi-collection-fill"></i> Documentos Relacionados
                        </h3>
                        <button
                            type="button"
                            @click="showRelatedDrawer = false"
                            class="p-1 text-slate-400 hover:text-white"
                        >
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="space-y-3 flex-1 overflow-y-auto">
                        <div
                            v-for="rb in relatedBooks"
                            :key="rb.id"
                            class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 transition flex gap-3 items-center group"
                        >
                            <div class="w-10 h-14 bg-slate-800 rounded overflow-hidden shrink-0 border border-slate-700">
                                <img v-if="rb.cover_url" :src="rb.cover_url" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center text-slate-600">
                                    <i class="bi bi-book"></i>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <Link
                                    :href="route('biblioteca.read', rb.slug)"
                                    class="text-xs font-bold text-slate-200 group-hover:text-teal-300 transition line-clamp-2 leading-snug block"
                                >
                                    {{ rb.title }}
                                </Link>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ rb.author }}</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </transition>
        </main>
    </div>
</template>
