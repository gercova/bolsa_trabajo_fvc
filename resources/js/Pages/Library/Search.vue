<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import axios from 'axios';
import ReaderLayout from './Layout.vue';
import route from '@/route';

const props = defineProps({
    books: { type: Object, required: true },
    studyPrograms: { type: Array, default: () => [] },
    selectedCareer: { type: [String, Number], default: 'all' },
    selectedCategory: { type: String, default: 'all' },
    searchQuery: { type: String, default: '' },
});

const queryText = ref(props.searchQuery || '');
const currentCareer = ref(props.selectedCareer || 'all');
const currentCategory = ref(props.selectedCategory || 'all');

const applyFilter = (key, val) => {
    if (key === 'career') currentCareer.value = val;
    if (key === 'category') currentCategory.value = val;
    if (key === 'q') queryText.value = val;

    router.get(
        route('biblioteca.search'),
        {
            career: currentCareer.value !== 'all' ? currentCareer.value : undefined,
            category: currentCategory.value !== 'all' ? currentCategory.value : undefined,
            q: queryText.value || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const clearFilters = () => {
    queryText.value = '';
    currentCareer.value = 'all';
    currentCategory.value = 'all';
    router.get(route('biblioteca.search'));
};

// Favorites state
const favoritesMap = reactive({});
if (props.books.data) {
    props.books.data.forEach((b) => {
        if (b.is_favorited) {
            favoritesMap[b.id] = true;
        }
    });
}

const toggleFavorite = async (bookId) => {
    const currentState = Boolean(favoritesMap[bookId]);
    favoritesMap[bookId] = !currentState;

    try {
        const res = await axios.post(`/biblioteca/favorito/${bookId}`, {}, {
            headers: {
                Accept: 'application/json',
            },
        });
        const json = res.data;
        if (json.success) {
            favoritesMap[bookId] = json.favorited;
        } else {
            favoritesMap[bookId] = currentState;
        }
    } catch {
        favoritesMap[bookId] = currentState;
    }
};

const categoriesList = [
    { key: 'all', label: 'Todos' },
    { key: 'Libro', label: 'Libros' },
    { key: 'Revista', label: 'Revistas' },
    { key: 'Paper', label: 'Papers' },
    { key: 'Tesis', label: 'Tesis' },
    { key: 'Documento', label: 'Documentos' },
];
</script>

<template>
    <Head title="Buscar en la Biblioteca Virtual" />
    <ReaderLayout pageTitle="Buscar">
        <div class="space-y-6">
            <!-- Search and Filter Toolbar -->
            <div class="space-y-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Buscar en el Catálogo</h1>
                    <p class="text-xs text-slate-500">Explore libros, revistas científicas, papers, tesis y documentos en formato PDF directo por título, autor o temática.</p>
                </div>

                <!-- Search Input with Submit & Clear Actions -->
                <div class="relative flex items-center">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                    <input
                        type="text"
                        v-model="queryText"
                        placeholder="Escriba un título, autor, temática, DOI o código ISBN..."
                        @keydown.enter="applyFilter('q', queryText)"
                        class="w-full pl-11 pr-28 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition text-slate-900"
                    />
                    <div class="absolute right-2 flex items-center gap-1">
                        <button
                            v-if="queryText.length > 0"
                            type="button"
                            @click="applyFilter('q', '')"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg transition"
                            title="Limpiar texto"
                        >
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                        <button
                            type="button"
                            @click="applyFilter('q', queryText)"
                            class="px-3.5 py-1.5 bg-[#0b4d57] hover:bg-[#093d45] text-white text-xs font-bold rounded-xl transition shadow-2xs"
                        >
                            Buscar
                        </button>
                    </div>
                </div>
                <!-- Career Filter Chips -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs no-scrollbar">
                    <button
                        type="button"
                        @click="applyFilter('career', 'all')"
                        :class="[
                            'px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition cursor-pointer',
                            currentCareer === 'all'
                                ? 'bg-[#0b4d57] text-white shadow-xs'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200',
                        ]"
                    >
                        Todas las carreras
                    </button>
                    <button
                        v-for="prog in studyPrograms"
                        :key="prog.id"
                        type="button"
                        @click="applyFilter('career', prog.id)"
                        :class="[
                            'px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition cursor-pointer',
                            String(currentCareer) === String(prog.id)
                                ? 'bg-[#0b4d57] text-white shadow-xs'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200',
                        ]"
                    >
                        {{ prog.name }}
                    </button>
                </div>
                <!-- Category Filter Pills -->
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <span class="text-slate-400 font-bold uppercase text-[10px] mr-1">Tipo:</span>
                    <button
                        v-for="cat in categoriesList"
                        :key="cat.key"
                        type="button"
                        @click="applyFilter('category', cat.key)"
                        :class="[
                            'px-2.5 py-1 rounded-lg text-xs font-semibold transition cursor-pointer',
                            currentCategory === cat.key
                                ? 'bg-blue-50 text-blue-700 border border-blue-200 font-bold'
                                : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100',
                        ]"
                    >
                        {{ cat.label }}
                    </button>
                </div>
            </div>

            <!-- Results Counter & Reset -->
            <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                <span>
                    Se encontraron <strong>{{ books.total || 0 }}</strong> documentos verificados
                </span>
                <button
                    v-if="queryText || currentCareer !== 'all' || currentCategory !== 'all'"
                    type="button"
                    @click="clearFilters"
                    class="text-blue-600 hover:underline font-bold inline-flex items-center gap-1 cursor-pointer"
                >
                    <i class="bi bi-arrow-counterclockwise"></i> Limpiar filtros
                </button>
            </div>

            <!-- Books Grid -->
            <div v-if="books.data && books.data.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <div
                    v-for="book in books.data"
                    :key="book.id"
                    class="group bg-white rounded-xl border border-slate-200/90 p-3 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative"
                >
                    <!-- Cover Image -->
                    <Link :href="route('biblioteca.read', book.slug)" class="block mb-2.5">
                        <div class="w-full aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center relative">
                            <img
                                v-if="book.cover_url"
                                :src="book.cover_url"
                                :alt="book.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                loading="lazy"
                            />
                            <div v-else class="p-3 text-center flex flex-col items-center justify-center h-full w-full bg-slate-50 text-slate-400">
                                <i class="bi bi-file-earmark-pdf text-3xl mb-1 text-rose-500/70"></i>
                                <span class="text-[10px] font-bold text-slate-600 line-clamp-3">{{ book.title }}</span>
                            </div>

                            <!-- Career tag -->
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                {{ book.study_program ? book.study_program.name.substring(0, 14) : 'General' }}
                            </span>

                            <!-- Category badge -->
                            <span class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-600/90 text-white backdrop-blur-xs">
                                {{ book.category || 'PDF' }}
                            </span>
                        </div>
                    </Link>
                    <!-- Metadata -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-0.5 text-amber-400 text-[11px]">
                                <i
                                    v-for="i in 5"
                                    :key="i"
                                    class="bi"
                                    :class="i <= Math.round(Number(book.rating || 5)) ? 'bi-star-fill' : 'bi-star text-slate-300'"
                                ></i>
                            </div>
                            <span v-if="book.publication_year" class="text-[10px] font-semibold text-slate-400">
                                {{ book.publication_year }}
                            </span>
                        </div>
                        <Link :href="route('biblioteca.read', book.slug)" class="block">
                            <h3 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-blue-600 transition" :title="book.title">
                                {{ book.title }}
                            </h3>
                        </Link>
                        <p class="text-[11px] text-slate-500 line-clamp-1" :title="book.author">
                            {{ book.author }}
                        </p>
                    </div>
                    <!-- Actions -->
                    <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                        <Link
                            :href="route('biblioteca.read', book.slug)"
                            class="text-blue-600 hover:text-blue-800 font-bold text-[11px] inline-flex items-center gap-1"
                        >
                            <i class="bi bi-file-earmark-pdf-fill"></i> Leer PDF
                        </Link>
                        <button
                            type="button"
                            @click="toggleFavorite(book.id)"
                            :class="favoritesMap[book.id] ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500'"
                            class="transition p-1"
                            title="Guardar en Favoritos"
                        >
                            <i class="bi" :class="favoritesMap[book.id] ? 'bi-heart-fill' : 'bi-heart'"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Empty State -->
            <div v-else class="py-16 text-center text-slate-400 space-y-3 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <i class="bi bi-search text-3xl block text-slate-300"></i>
                <p class="text-sm font-semibold text-slate-700">No se encontraron documentos con los filtros seleccionados.</p>
                <p class="text-xs text-slate-500">Pruebe a cambiar el término de búsqueda o seleccione otra carrera o categoría.</p>
                <div>
                    <button
                        type="button"
                        @click="clearFilters"
                        class="px-4 py-2 bg-[#0b4d57] text-white text-xs font-bold rounded-xl hover:bg-teal-900 transition"
                    >
                        Ver todo el catálogo
                    </button>
                </div>
            </div>
            <!-- Pagination -->
            <div v-if="books.links && books.links.length > 3" class="pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <span class="text-xs text-slate-500">
                    Página {{ books.current_page }} de {{ books.last_page }} ({{ books.total }} resultados)
                </span>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="(link, idx) in books.links"
                        :key="idx"
                        :href="link.url || '#'"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-semibold transition',
                            link.active ? 'bg-[#0b4d57] text-white font-bold' : 'text-slate-600 hover:bg-slate-100',
                            !link.url ? 'opacity-40 pointer-events-none' : '',
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </ReaderLayout>
</template>
