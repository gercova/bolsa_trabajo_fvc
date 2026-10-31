<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    links: {
        type: Object,
        required: true,
    },
    search: {
        type: String,
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
    totalLinks: {
        type: Number,
        default: 0,
    },
    activeLinks: {
        type: Number,
        default: 0,
    },
    inactiveLinks: {
        type: Number,
        default: 0,
    },
});

const page = usePage();

// Filters state
const searchQuery = ref(props.search || '');
const statusFilter = ref(props.status || '');

// Delete modal state
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const isDeleting = ref(false);

// Status toggling
const togglingId = ref(null);

const applyFilters = () => {
    router.get(
        route('admin.links.index'),
        {
            search: searchQuery.value || undefined,
            status: statusFilter.value !== '' ? statusFilter.value : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const clearFilters = () => {
    searchQuery.value = '';
    statusFilter.value = '';
    router.get(route('admin.links.index'), {}, { preserveScroll: true });
};

const openDeleteModal = (linkItem) => {
    itemToDelete.value = linkItem;
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
    router.delete(route('admin.links.destroy', itemToDelete.value.id), {
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

const toggleStatus = (linkItem) => {
    togglingId.value = linkItem.id;
    router.patch(
        route('admin.links.toggle-status', linkItem.id),
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                togglingId.value = null;
            },
        }
    );
};
</script>

<template>
    <Head title="Gestión de Enlaces Institucionales - Panel Administrativo" />

    <AdminLayout title="Gestión de Enlaces Institucionales">
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-box-arrow-up-right text-purple-600"></i>
                        Enlaces Institucionales
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Administre los accesos directos y plataformas institucionales externas visibles en el portal.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.links.create')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bi bi-plus-circle-fill text-base"></i>
                        <span>Nuevo Enlace</span>
                    </Link>
                </div>
            </div>

            <!-- Summary Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ totalLinks }}</p>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Enlaces</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ activeLinks }}</p>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Enlaces Activos</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold">
                        <i class="bi bi-eye-slash-fill"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ inactiveLinks }}</p>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Enlaces Inactivos</p>
                    </div>
                </div>
            </div>

            <!-- Filters & Search Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200/80 flex flex-col md:flex-row justify-between items-center gap-4">
                <form @submit.prevent="applyFilters" class="w-full flex flex-col sm:flex-row items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="relative w-full sm:max-w-md">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Buscar por nombre o dirección URL..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400"
                        />
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full sm:w-auto">
                        <select
                            v-model="statusFilter"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Todos los Estados</option>
                            <option value="active">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button
                            type="submit"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5"
                        >
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button
                            v-if="searchQuery || statusFilter !== ''"
                            type="button"
                            @click="clearFilters"
                            class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors"
                        >
                            Limpiar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[650px]">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="p-4 w-16 text-center">Icono</th>
                                <th class="p-4">Plataforma / Nombre</th>
                                <th class="p-4">Dirección URL Destino</th>
                                <th class="p-4 text-center w-28">Estado</th>
                                <th class="p-4 text-center w-36">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            <tr
                                v-for="linkItem in links.data"
                                :key="linkItem.id"
                                class="hover:bg-purple-50/20 transition-colors"
                            >
                                <!-- Icon -->
                                <td class="p-4 text-center">
                                    <div class="w-10 h-10 mx-auto rounded-xl bg-purple-100/80 border border-purple-200 text-purple-700 flex items-center justify-center text-lg shadow-sm">
                                        <i :class="['bi', linkItem.icon || 'bi-box-arrow-up-right']"></i>
                                    </div>
                                </td>

                                <!-- Name -->
                                <td class="p-4 font-bold text-gray-900">
                                    <div class="text-base font-extrabold text-blue-950">{{ linkItem.name }}</div>
                                    <span class="text-xs text-gray-400 font-mono">Clase: {{ linkItem.icon || 'bi-box-arrow-up-right' }}</span>
                                </td>

                                <!-- Link URL -->
                                <td class="p-4">
                                    <a
                                        :href="linkItem.link"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-medium transition-colors break-all"
                                    >
                                        <i class="bi bi-link-45deg text-sm"></i>
                                        <span>{{ linkItem.link }}</span>
                                        <i class="bi bi-box-arrow-up-right text-[10px] ml-1"></i>
                                    </a>
                                </td>

                                <!-- Status Toggle -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <button
                                        type="button"
                                        @click="toggleStatus(linkItem)"
                                        :disabled="togglingId === linkItem.id"
                                        :class="[
                                            'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm cursor-pointer disabled:opacity-50',
                                            linkItem.is_active
                                                ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200'
                                                : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200'
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'w-2 h-2 rounded-full',
                                                linkItem.is_active ? 'bg-emerald-500' : 'bg-rose-500'
                                            ]"
                                        ></span>
                                        <span>{{ linkItem.is_active ? 'Activo' : 'Inactivo' }}</span>
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <Link
                                            :href="route('admin.links.edit', linkItem.id)"
                                            class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                            title="Editar enlace"
                                        >
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>

                                        <button
                                            type="button"
                                            @click="openDeleteModal(linkItem)"
                                            class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer"
                                            title="Eliminar enlace"
                                        >
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="!links.data || links.data.length === 0">
                                <td colspan="5" class="p-12 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto space-y-3">
                                        <i class="bi bi-box-arrow-up-right text-4xl text-gray-300"></i>
                                        <p class="text-base font-bold text-gray-700">No se encontraron enlaces institucionales</p>
                                        <p class="text-xs text-gray-500">
                                            No hay enlaces externos registrados o ninguno coincide con los filtros aplicados.
                                        </p>
                                        <Link
                                            :href="route('admin.links.create')"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition"
                                        >
                                            Registrar Primer Enlace
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="links.links && links.links.length > 3" class="p-4 bg-gray-50 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs text-gray-500">
                        Mostrando del <span class="font-bold">{{ links.from || 0 }}</span> al <span class="font-bold">{{ links.to || 0 }}</span> de <span class="font-bold">{{ links.total || 0 }}</span> enlaces
                    </p>
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(linkItem, idx) in links.links" :key="idx">
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
                        <h3 class="text-lg font-extrabold text-gray-900">Eliminar Enlace Institucional</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            ¿Está seguro de que desea eliminar permanentemente «<strong class="text-gray-800">{{ itemToDelete?.name }}</strong>»? Esta acción no se puede deshacer.
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
