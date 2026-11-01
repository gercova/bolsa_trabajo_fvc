<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ReaderLayout from './Layout.vue';
import route from '@/route';

const props = defineProps({
    books: { type: Object, required: true },
    studyPrograms: { type: Array, default: () => [] },
    selectedCareer: { type: [String, Number], default: 'all' },
    searchQuery: { type: String, default: '' },
});

const currentCareer = ref(props.selectedCareer || 'all');
const removedBookIds = ref([]);

const filterCareer = (careerId) => {
    currentCareer.value = careerId;
    router.get(
        route('biblioteca.favorites'),
        {
            career: careerId !== 'all' ? careerId : undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const removeFavorite = async (bookId) => {
    removedBookIds.value.push(bookId);

    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(`/biblioteca/favorito/${bookId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                Accept: 'application/json',
            },
        });
        const json = await res.json();
        if (!json.success || json.favorited) {
            removedBookIds.value = removedBookIds.value.filter((id) => id !== bookId);
        }
    } catch {
        removedBookIds.value = removedBookIds.value.filter((id) => id !== bookId);
    }
};
</script>

<template>
    <Head title="Mi Biblioteca - Biblioteca Virtual" />

    <ReaderLayout pageTitle="Mi Biblioteca">
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-bookmark-heart-fill text-blue-600"></i> Mi Biblioteca
                    </h1>
                    <p class="text-xs text-slate-500">Tus libros y documentos favoritos guardados para consulta y lectura rápida.</p>
                </div>

                <!-- Career Filter -->
                <div>
                    <select
                        v-model="currentCareer"
                        @change="filterCareer(currentCareer)"
                        class="text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    >
                        <option value="all">Todas las carreras</option>
                        <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.id">
                            {{ prog.name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Books Grid -->
            <div v-if="books.data && books.data.some((b) => !removedBookIds.includes(b.id))" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                <template v-for="book in books.data" :key="book.id">
                    <div
                        v-if="!removedBookIds.includes(book.id)"
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

                                <!-- Career tag -->
                                <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                    {{ book.study_program ? book.study_program.name.substring(0, 12) : 'General' }}
                                </span>
                            </div>
                        </Link>

                        <!-- Metadata -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <i v-for="s in 5" :key="s" class="bi bi-star-fill text-[11px]"></i>
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
                                <i class="bi bi-eye"></i> Leer
                            </Link>
                            <button
                                type="button"
                                @click="removeFavorite(book.id)"
                                class="text-rose-500 hover:text-slate-400 transition p-1 cursor-pointer"
                                title="Quitar de mi biblioteca"
                            >
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div v-else class="py-16 text-center text-slate-400 space-y-3 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl">
                    <i class="bi bi-bookmark-x"></i>
                </div>
                <p class="text-sm font-semibold text-slate-700">Aún no has agregado libros a tu biblioteca.</p>
                <p class="text-xs text-slate-500">Puedes guardar libros en tus favoritos desde el catálogo de búsqueda haciendo clic en el icono de corazón.</p>
                <div>
                    <Link
                        :href="route('biblioteca.search')"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs inline-flex items-center gap-1.5 transition"
                    >
                        <i class="bi bi-search"></i> Explorar Catálogo
                    </Link>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="books.links && books.links.length > 3" class="pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <span class="text-xs text-slate-500">
                    Página {{ books.current_page }} de {{ books.last_page }} ({{ books.total }} favoritos)
                </span>
                <div class="flex items-center gap-1">
                    <Link
                        v-for="(link, idx) in books.links"
                        :key="idx"
                        :href="link.url || '#'"
                        :class="[
                            'px-3 py-1.5 rounded-lg text-xs font-semibold transition',
                            link.active ? 'bg-blue-600 text-white font-bold' : 'text-slate-600 hover:bg-slate-100',
                            !link.url ? 'opacity-40 pointer-events-none' : '',
                        ]"
                        v-html="link.label"
                    />
                </div>
            </div>

        </div>
    </ReaderLayout>
</template>
