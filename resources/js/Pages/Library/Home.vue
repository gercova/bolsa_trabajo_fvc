<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';
import axios from 'axios';
import ReaderLayout from './Layout.vue';
import route from '@/route';

const props = defineProps({
    user: { type: Object, default: () => null },
    favoriteBooks: { type: Array, default: () => [] },
    recentlyReadBooks: { type: Array, default: () => [] },
    featuredBooks: { type: Array, default: () => [] },
    recentBooks: { type: Object, default: () => ({}) },
    studyPrograms: { type: Array, default: () => [] },
    selectedCareer: { type: [String, Number], default: 'all' },
    searchQuery: { type: String, default: '' },
    totalBooks: { type: Number, default: 0 },
});

// Reactive map for favorites state
const favoritesMap = reactive({});
props.favoriteBooks.forEach((b) => {
    favoritesMap[b.id] = true;
});
props.recentlyReadBooks.forEach((b) => {
    if (b.is_favorited) {
        favoritesMap[b.id] = true;
    }
});
props.featuredBooks.forEach((b) => {
    if (b.is_favorited) {
        favoritesMap[b.id] = true;
    }
});

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
</script>

<template>
    <Head title="Biblioteca Virtual - Inicio" />
    <ReaderLayout pageTitle="Inicio">
        <div class="space-y-10">
            <!-- Greeting Header -->
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Hola, {{ user?.names || 'Estudiante' }}.
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Bienvenido a la biblioteca digital institucional del IESTP Francisco Vigo Caballero.</p>
            </div>
            <!-- SECTION 1: Mi biblioteca (Favorites) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-bookmark-heart-fill text-blue-600"></i> Mi biblioteca
                        </h2>
                        <p class="text-xs text-slate-500">Aquí se listan los últimos libros agregados a tu biblioteca</p>
                    </div>
                    <Link
                        v-if="favoriteBooks.length > 0"
                        :href="route('biblioteca.favorites')"
                        class="text-xs font-bold text-slate-600 hover:text-blue-600 transition"
                    >
                        Ver más
                    </Link>
                </div>
                <div v-if="favoriteBooks.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    <div
                        v-for="book in favoriteBooks"
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
                                    <i class="bi bi-book text-3xl mb-1 text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 line-clamp-2">{{ book.title }}</span>
                                </div>

                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3 py-1.5 bg-white text-slate-900 font-bold text-xs rounded-lg shadow-sm flex items-center gap-1">
                                        <i class="bi bi-book-half text-blue-600"></i> Leer
                                    </span>
                                </div>
                            </div>
                        </Link>
                        <!-- Metadata -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i v-for="s in 5" :key="s" class="bi bi-star-fill text-[11px]"></i>
                            </div>
                            <Link :href="route('biblioteca.read', book.slug)" class="block">
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1 leading-snug group-hover:text-blue-600 transition" :title="book.title">
                                    {{ book.title }}
                                </h3>
                            </Link>
                            <p class="text-[11px] text-slate-500 line-clamp-1" :title="book.author">
                                {{ book.author }}
                            </p>
                        </div>
                        <!-- Action Strip -->
                        <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <Link
                                :href="route('biblioteca.read', book.slug)"
                                class="text-blue-600 hover:text-blue-800 font-bold text-[11px]"
                            >
                                Leer ahora
                            </Link>
                            <button
                                type="button"
                                @click="toggleFavorite(book.id)"
                                class="text-rose-500 hover:scale-110 transition p-1"
                                title="Quitar de favoritos"
                            >
                                <i class="bi" :class="favoritesMap[book.id] ? 'bi-heart-fill' : 'bi-heart text-slate-400'"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="py-10 text-center text-slate-400 text-xs font-medium bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                    Aún no has agregado libros a tu biblioteca
                </div>
            </div>
            <!-- SECTION 2: Leídos últimamente -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-clock-history text-teal-700"></i> Leídos últimamente
                        </h2>
                        <p class="text-xs text-slate-500">Aquí se listan los últimos 5 libros que has leído</p>
                    </div>
                    <Link
                        v-if="recentlyReadBooks.length > 0"
                        :href="route('biblioteca.search')"
                        class="text-xs font-bold text-slate-600 hover:text-blue-600 transition"
                    >
                        Ver más
                    </Link>
                </div>
                <div v-if="recentlyReadBooks.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    <div
                        v-for="book in recentlyReadBooks"
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
                                    <i class="bi bi-book text-3xl mb-1 text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-500 line-clamp-2">{{ book.title }}</span>
                                </div>

                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3 py-1.5 bg-white text-slate-900 font-bold text-xs rounded-lg shadow-sm flex items-center gap-1">
                                        <i class="bi bi-book-half text-blue-600"></i> Continuar lectura
                                    </span>
                                </div>
                            </div>
                        </Link>
                        <!-- Metadata -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i v-for="s in 5" :key="s" class="bi bi-star-fill text-[11px]"></i>
                            </div>
                            <Link :href="route('biblioteca.read', book.slug)" class="block">
                                <h3 class="text-xs font-bold text-slate-900 line-clamp-1 leading-snug group-hover:text-blue-600 transition" :title="book.title">
                                    {{ book.title }}
                                </h3>
                            </Link>
                            <p class="text-[11px] text-slate-500 line-clamp-1" :title="book.author">
                                {{ book.author }}
                            </p>
                        </div>
                        <!-- Action Strip -->
                        <div class="pt-2 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <Link
                                :href="route('biblioteca.read', book.slug)"
                                class="text-blue-600 hover:text-blue-800 font-bold text-[11px]"
                            >
                                Leer
                            </Link>
                            <button
                                type="button"
                                @click="toggleFavorite(book.id)"
                                class="transition p-1"
                                :class="favoritesMap[book.id] ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500'"
                                title="Favorito"
                            >
                                <i class="bi" :class="favoritesMap[book.id] ? 'bi-heart-fill' : 'bi-heart'"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="py-10 text-center text-slate-400 text-xs font-medium bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                    No has leído libros recientemente. Explora nuestro catálogo en la sección
                    <Link :href="route('biblioteca.search')" class="text-blue-600 font-bold underline ml-1">Buscar</Link>.
                </div>
            </div>
            <!-- SECTION 3: Materiales Destacados -->
            <div v-if="featuredBooks.length > 0" class="space-y-4 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-fire text-amber-500"></i> Materiales Más Consultados
                        </h2>
                        <p class="text-xs text-slate-500">Documentos con mayor número de lecturas en la institución</p>
                    </div>
                    <Link
                        :href="route('biblioteca.search')"
                        class="text-xs font-bold text-slate-600 hover:text-blue-600 transition"
                    >
                        Ver todo el catálogo
                    </Link>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div
                        v-for="book in featuredBooks"
                        :key="book.id"
                        class="group bg-white rounded-xl border border-slate-200/90 p-2.5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between"
                    >
                        <Link :href="route('biblioteca.read', book.slug)" class="block mb-2">
                            <div class="w-full aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 border border-slate-100 flex items-center justify-center relative">
                                <img
                                    v-if="book.cover_url"
                                    :src="book.cover_url"
                                    :alt="book.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    loading="lazy"
                                />
                                <div v-else class="p-2 text-center flex flex-col items-center justify-center h-full w-full bg-slate-50 text-slate-400">
                                    <i class="bi bi-book text-2xl mb-1 text-slate-300"></i>
                                    <span class="text-[9px] font-bold text-slate-500 line-clamp-2">{{ book.title }}</span>
                                </div>
                            </div>
                        </Link>
                        <div class="space-y-0.5">
                            <Link :href="route('biblioteca.read', book.slug)" class="block">
                                <h4 class="text-[11px] font-bold text-slate-900 line-clamp-1 hover:text-blue-600 transition" :title="book.title">
                                    {{ book.title }}
                                </h4>
                            </Link>
                            <p class="text-[10px] text-slate-400 line-clamp-1">{{ book.author }}</p>
                        </div>
                        <div class="pt-1.5 mt-1.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                            <span class="text-[10px] text-slate-400 font-semibold flex items-center gap-1">
                                <i class="bi bi-eye"></i> {{ Number(book.views_count || 0).toLocaleString() }}
                            </span>
                            <button
                                type="button"
                                @click="toggleFavorite(book.id)"
                                class="transition p-0.5"
                                :class="favoritesMap[book.id] ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500'"
                            >
                                <i class="bi" :class="favoritesMap[book.id] ? 'bi-heart-fill' : 'bi-heart'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ReaderLayout>
</template>
