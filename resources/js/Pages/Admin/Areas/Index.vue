<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    areas: {
        type: Object,
        required: true,
    },
    programs: {
        type: Array,
        default: () => [],
    },
    totalAreas: {
        type: Number,
        default: 0,
    },
    withProgramCount: {
        type: Number,
        default: 0,
    },
    withLeaderCount: {
        type: Number,
        default: 0,
    },
    generalAreasCount: {
        type: Number,
        default: 0,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

// Filters
const search = ref(props.filters?.search || '');
const programId = ref(props.filters?.program_id || '');
const hasUser = ref(props.filters?.has_user || '');

// Delete modal
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const isDeleting = ref(false);

const applyFilters = () => {
    router.get(
        route('admin.areas.index'),
        {
            search: search.value || undefined,
            program_id: programId.value !== '' ? programId.value : undefined,
            has_user: hasUser.value !== '' ? hasUser.value : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const clearFilters = () => {
    search.value = '';
    programId.value = '';
    hasUser.value = '';
    router.get(route('admin.areas.index'), {}, { preserveScroll: true });
};

const openDeleteModal = (area) => {
    itemToDelete.value = area;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        itemToDelete.value = null;
    }
};

const confirmDelete = () => {
    if (!itemToDelete.value) return;

    isDeleting.value = true;
    router.delete(route('admin.areas.destroy', itemToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <Head title="Áreas Institucionales - Panel Administrativo" />

    <AdminLayout title="Áreas Institucionales">
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-diagram-3 text-purple-600"></i>
                        Áreas Institucionales
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Gestione las dependencias, jefaturas y áreas académicas o administrativas de la institución.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.areas.create')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bi bi-plus-lg text-base"></i>
                        <span>Nueva Área</span>
                    </Link>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de Áreas</span>
                        <h3 class="text-2xl font-black text-slate-800 mt-1">{{ totalAreas }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Institucionales</span>
                        <h3 class="text-2xl font-black text-blue-700 mt-1">{{ generalAreasCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-building"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Por Programa</span>
                        <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ withProgramCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Con Responsable</span>
                        <h3 class="text-2xl font-black text-amber-600 mt-1">{{ withLeaderCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200/80 flex flex-col lg:flex-row justify-between items-center gap-4">
                <form @submit.prevent="applyFilters" class="w-full flex flex-col sm:flex-row items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="relative w-full sm:max-w-xs">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar por nombre o descripción..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400"
                        />
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Program Filter -->
                    <div class="w-full sm:w-auto">
                        <select
                            v-model="programId"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Todos los Programas</option>
                            <option value="general">Institucional General</option>
                            <option v-for="prog in programs" :key="prog.id" :value="prog.id">
                                {{ prog.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Responsable Filter -->
                    <div class="w-full sm:w-auto">
                        <select
                            v-model="hasUser"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Todos los Responsables</option>
                            <option value="yes">Con responsable</option>
                            <option value="no">Sin responsable</option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button
                            type="submit"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5"
                        >
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button
                            v-if="search || programId !== '' || hasUser !== ''"
                            type="button"
                            @click="clearFilters"
                            class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors"
                        >
                            Limpiar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Areas Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="py-4 px-6">Área / Dependencia</th>
                                <th class="py-4 px-6">Programa de Estudio</th>
                                <th class="py-4 px-6">Responsable</th>
                                <th class="py-4 px-6">Descripción</th>
                                <th class="py-4 px-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr
                                v-for="area in areas.data"
                                :key="area.id"
                                class="hover:bg-purple-50/20 transition-colors"
                            >
                                <!-- Name -->
                                <td class="py-4 px-6 font-semibold text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                            <i class="bi bi-diagram-3 text-base"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-gray-800">{{ area.name }}</span>
                                            <span class="text-xs text-gray-400">ID: #{{ area.id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Program -->
                                <td class="py-4 px-6">
                                    <span
                                        v-if="area.program"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200"
                                    >
                                        <i class="bi bi-mortarboard text-xs"></i>
                                        {{ area.program.name }}
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200"
                                    >
                                        <i class="bi bi-building text-xs"></i>
                                        Institucional General
                                    </span>
                                </td>

                                <!-- Responsible User -->
                                <td class="py-4 px-6">
                                    <div v-if="area.user" class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ (area.user.names || 'U').charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="leading-tight">
                                            <p class="font-medium text-gray-800 text-xs">
                                                {{ area.user.names }} {{ area.user.last_name1 || '' }}
                                            </p>
                                            <p class="text-[11px] text-gray-400">{{ area.user.email }}</p>
                                        </div>
                                    </div>
                                    <span v-else class="text-xs text-gray-400 italic">No asignado</span>
                                </td>

                                <!-- Description -->
                                <td class="py-4 px-6 max-w-xs">
                                    <p
                                        v-if="area.description"
                                        class="text-xs text-gray-600 truncate"
                                        :title="area.description"
                                    >
                                        {{ area.description }}
                                    </p>
                                    <span v-else class="text-xs text-gray-400 italic">Sin descripción</span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('admin.areas.edit', area.id)"
                                            class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                            title="Editar Área"
                                        >
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>

                                        <button
                                            type="button"
                                            @click="openDeleteModal(area)"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                            title="Eliminar Área"
                                        >
                                            <i class="bi bi-trash3 text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="!areas.data || areas.data.length === 0">
                                <td colspan="5" class="py-12 text-center">
                                    <div class="max-w-sm mx-auto space-y-3">
                                        <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto text-2xl">
                                            <i class="bi bi-diagram-3"></i>
                                        </div>
                                        <h4 class="text-base font-bold text-gray-800">No se encontraron áreas</h4>
                                        <p class="text-xs text-gray-500">
                                            <template v-if="search || programId !== '' || hasUser !== ''">
                                                No hay resultados para los filtros seleccionados. Intente restablecer los filtros.
                                            </template>
                                            <template v-else>
                                                Aún no ha registrado ninguna área institucional. Haga clic en "Nueva Área" para comenzar.
                                            </template>
                                        </p>
                                        <button
                                            v-if="search || programId !== '' || hasUser !== ''"
                                            type="button"
                                            @click="clearFilters"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-xs rounded-xl transition cursor-pointer"
                                        >
                                            <i class="bi bi-arrow-counterclockwise"></i> Restablecer Filtros
                                        </button>
                                        <Link
                                            v-else
                                            :href="route('admin.areas.create')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl transition shadow-sm"
                                        >
                                            <i class="bi bi-plus-lg"></i> Crear Primera Área
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="areas.links && areas.links.length > 3" class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                    <div class="text-xs text-gray-500">
                        Mostrando {{ areas.from || 0 }} a {{ areas.to || 0 }} de {{ areas.total || 0 }} registros
                    </div>
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(linkItem, idx) in areas.links" :key="idx">
                            <Link
                                v-if="linkItem.url"
                                :href="linkItem.url"
                                :class="[
                                    'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                    linkItem.active
                                        ? 'bg-purple-600 text-white shadow-sm'
                                        : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
                                ]"
                                v-html="linkItem.label"
                                preserve-scroll
                            />
                            <span
                                v-else
                                class="px-3 py-1.5 text-xs rounded-lg font-medium text-gray-400 bg-gray-100 border border-gray-200"
                                v-html="linkItem.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        >
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform transition-all animate-in fade-in zoom-in-95">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Eliminar Área</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            ¿Está seguro de que desea eliminar permanentemente el área «<strong class="text-gray-800">{{ itemToDelete?.name }}</strong>»? Esta acción no se puede deshacer.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button
                        type="button"
                        @click="closeDeleteModal"
                        :disabled="isDeleting"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="confirmDelete"
                        :disabled="isDeleting"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 transition flex items-center gap-2 shadow-md shadow-rose-600/20 disabled:opacity-50"
                    >
                        <i v-if="isDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-trash"></i>
                        <span>{{ isDeleting ? 'Eliminando...' : 'Sí, Eliminar' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
