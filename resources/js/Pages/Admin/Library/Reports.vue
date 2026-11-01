<script setup>
import { Head } from '@inertiajs/vue3';
import LibraryLayout from './Layout.vue';

const props = defineProps({
    totalBooks: { type: Number, default: 0 },
    totalViews: { type: Number, default: 0 },
    totalFavorites: { type: Number, default: 0 },
    totalCategories: { type: Number, default: 0 },
    booksByCategory: { type: Array, default: () => [] },
    booksByProgram: { type: Array, default: () => [] },
});

const maxCategory = Math.max(1, ...(props.booksByCategory.map((c) => Number(c.total || 0))));
const maxProgram = Math.max(1, ...(props.booksByProgram.map((p) => Number(p.total || 0))));
</script>

<template>
    <Head title="Reportes - Biblioteca" />

    <LibraryLayout>
        <div class="p-5 sm:p-7 space-y-6">

            <!-- Header -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-flag-fill text-teal-800"></i> Reportes y Métricas del Acervo Bibliográfico
                    </h1>
                    <p class="text-xs text-slate-500">Resumen cuantitativo de títulos, colecciones, lecturas y favoritos en la institución.</p>
                </div>
            </div>

            <!-- Metrics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-800 flex items-center justify-center text-xl mb-3 border border-teal-100">
                        <i class="bi bi-book"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Títulos Totales</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ Number(totalBooks).toLocaleString() }}</span>
                    <span class="text-[11px] text-slate-500">Libros y documentos registrados</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-xl mb-3 border border-indigo-100">
                        <i class="bi bi-eye"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Lecturas Acumuladas</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ Number(totalViews).toLocaleString() }}</span>
                    <span class="text-[11px] text-slate-500">Visualizaciones totales</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl mb-3 border border-rose-100">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total en "Mi Biblioteca"</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ Number(totalFavorites).toLocaleString() }}</span>
                    <span class="text-[11px] text-slate-500">Libros guardados como favoritos</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl mb-3 border border-amber-100">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Categorías</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ Number(totalCategories).toLocaleString() }}</span>
                    <span class="text-[11px] text-slate-500">Formatos disponibles</span>
                </div>
            </div>

            <!-- Detailed Breakdowns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- By Category -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-pie-chart-fill text-teal-700"></i> Distribución por Tipo de Contenido
                    </h3>
                    <div class="space-y-3">
                        <div v-for="cat in booksByCategory" :key="cat.category" class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-700">{{ cat.category }}</span>
                                <span class="font-bold text-slate-900">{{ Number(cat.total).toLocaleString() }} títulos</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div
                                    class="h-full bg-teal-700 rounded-full transition-all duration-500"
                                    :style="{ width: `${Math.round((cat.total / maxCategory) * 100)}%` }"
                                ></div>
                            </div>
                        </div>

                        <p v-if="!booksByCategory || booksByCategory.length === 0" class="text-xs text-slate-400 text-center py-4">
                            No hay información de categorías registrada.
                        </p>
                    </div>
                </div>

                <!-- By Program -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-grid-3x3-gap-fill text-indigo-700"></i> Títulos Asignados por Carrera
                    </h3>
                    <div class="space-y-3">
                        <div v-for="prog in booksByProgram" :key="prog.program_name" class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-700 truncate mr-2">{{ prog.program_name }}</span>
                                <span class="font-bold text-slate-900 shrink-0">{{ Number(prog.total).toLocaleString() }} títulos</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div
                                    class="h-full bg-indigo-600 rounded-full transition-all duration-500"
                                    :style="{ width: `${Math.round((prog.total / maxProgram) * 100)}%` }"
                                ></div>
                            </div>
                        </div>

                        <p v-if="!booksByProgram || booksByProgram.length === 0" class="text-xs text-slate-400 text-center py-4">
                            No hay asignaciones de programas de estudio registradas.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </LibraryLayout>
</template>
