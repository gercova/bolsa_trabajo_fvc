<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    programs: {
        type: Object,
        required: true,
    },
    modules: {
        type: Object,
        required: true,
    },
    competencies: {
        type: Object,
        required: true,
    },
    jobFields: {
        type: Object,
        required: true,
    },
    metas: {
        type: Object,
        required: true,
    },
    requirements: {
        type: Object,
        required: true,
    },
    activeTab: {
        type: String,
        default: 'programs',
    },
    allProgramsList: {
        type: Array,
        default: () => [],
    },
    search: {
        type: String,
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
    studyProgramId: {
        type: [String, Number],
        default: '',
    },
});

const currentTab = ref(props.activeTab || 'programs');

// Filters state
const searchQuery = ref(props.search || '');
const statusFilter = ref(props.status || '');
const programFilter = ref(props.studyProgramId || '');

const switchTab = (tab) => {
    currentTab.value = tab;
    searchQuery.value = '';
    statusFilter.value = '';
    programFilter.value = '';

    router.get(
        route('admin.programs.index'),
        { tab },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const applyFilters = () => {
    router.get(
        route('admin.programs.index'),
        {
            tab: currentTab.value,
            search: searchQuery.value || undefined,
            status: statusFilter.value || undefined,
            study_program_id: programFilter.value || undefined,
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
    programFilter.value = '';
    router.get(
        route('admin.programs.index'),
        { tab: currentTab.value },
        { preserveScroll: true }
    );
};

// Toggle status state
const togglingId = ref(null);
const toggleStatus = (item, type) => {
    togglingId.value = `${type}-${item.id}`;
    let url = '';

    if (type === 'program') {
        url = route('admin.programs.toggle-status', item.id);
    } else if (type === 'module') {
        url = route('admin.programs.modules.toggle-status', item.id);
    } else if (type === 'competency') {
        url = route('admin.programs.competencies.toggle-status', item.id);
    } else if (type === 'jobField') {
        url = route('admin.programs.job-fields.toggle-status', item.id);
    } else if (type === 'requirement') {
        url = route('admin.programs.requirements.toggle-status', item.id);
    }

    if (!url) return;

    router.patch(url, {}, {
        preserveScroll: true,
        onFinish: () => {
            togglingId.value = null;
        },
    });
};

// Delete confirmation modal state
const showDeleteModal = ref(false);
const isDeleting = ref(false);
const itemToDelete = ref(null);
const deleteTypeLabel = ref('');
const deleteTitle = ref('');
const deleteUrl = ref('');

const openDeleteModal = (item, type, title) => {
    itemToDelete.value = item;
    deleteTitle.value = title;

    if (type === 'program') {
        deleteTypeLabel.value = 'Programa de Estudio';
        deleteUrl.value = route('admin.programs.destroy', item.id);
    } else if (type === 'module') {
        deleteTypeLabel.value = 'Módulo de Certificación';
        deleteUrl.value = route('admin.programs.modules.destroy', item.id);
    } else if (type === 'competency') {
        deleteTypeLabel.value = 'Competencia';
        deleteUrl.value = route('admin.programs.competencies.destroy', item.id);
    } else if (type === 'jobField') {
        deleteTypeLabel.value = 'Campo Laboral';
        deleteUrl.value = route('admin.programs.job-fields.destroy', item.id);
    } else if (type === 'meta') {
        deleteTypeLabel.value = 'Metadatos de Estilo';
        deleteUrl.value = route('admin.programs.meta.destroy', item.id);
    } else if (type === 'requirement') {
        deleteTypeLabel.value = 'Requisito';
        deleteUrl.value = route('admin.programs.requirements.destroy', item.id);
    }

    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    if (!isDeleting.value) {
        showDeleteModal.value = false;
        itemToDelete.value = null;
        deleteTitle.value = '';
        deleteUrl.value = '';
    }
};

const confirmDelete = () => {
    if (!deleteUrl.value) return;

    isDeleting.value = true;
    router.delete(deleteUrl.value, {
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
    <Head title="Gestión de Programas de Estudio — Panel Administrativo" />

    <AdminLayout title="Gestión de Programas de Estudio">
        <div class="space-y-6">
            <!-- Navigation Sub-Tabs -->
            <div class="bg-white p-2 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-1 overflow-x-auto">
                <Link
                    :href="route('admin.programs.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold rounded-xl bg-purple-600 text-white shadow-xs shadow-purple-500/20 whitespace-nowrap"
                >
                    <i class="bi bi-mortarboard"></i> Programas de Estudio
                </Link>
                <Link
                    :href="route('admin.certificates.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition whitespace-nowrap"
                >
                    <i class="bi bi-patch-check"></i> Certificados
                </Link>
                <Link
                    :href="route('admin.courses.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition whitespace-nowrap"
                >
                    <i class="bi bi-journal-bookmark"></i> Cursos
                </Link>
                <Link
                    :href="route('admin.modules.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition whitespace-nowrap"
                >
                    <i class="bi bi-layers"></i> Módulos
                </Link>
                <Link
                    :href="route('admin.itineraries.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition whitespace-nowrap"
                >
                    <i class="bi bi-diagram-3"></i> Itinerarios
                </Link>
            </div>

            <!-- TAB NAVIGATION PANEL -->
            <div class="bg-white rounded-2xl p-2 border border-gray-200/80 shadow-xs flex flex-wrap lg:flex-nowrap gap-2 overflow-x-auto">
                <button
                    type="button"
                    @click="switchTab('programs')"
                    :class="[
                        'flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer',
                        currentTab === 'programs'
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
                            : 'text-gray-600 hover:bg-gray-100 font-medium'
                    ]"
                >
                    <i class="bi bi-mortarboard-fill text-base"></i>
                    <span>Programas</span>
                    <span
                        class="ml-1 px-2 py-0.5 text-xs rounded-full font-bold"
                        :class="currentTab === 'programs' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
                    >
                        {{ programs.total || 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    @click="switchTab('modules')"
                    :class="[
                        'flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer',
                        currentTab === 'modules'
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
                            : 'text-gray-600 hover:bg-gray-100 font-medium'
                    ]"
                >
                    <i class="bi bi-award-fill text-base"></i>
                    <span>Certificaciones</span>
                    <span
                        class="ml-1 px-2 py-0.5 text-xs rounded-full font-bold"
                        :class="currentTab === 'modules' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
                    >
                        {{ modules.total || 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    @click="switchTab('competencies')"
                    :class="[
                        'flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer',
                        currentTab === 'competencies'
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
                            : 'text-gray-600 hover:bg-gray-100 font-medium'
                    ]"
                >
                    <i class="bi bi-star-fill text-base"></i>
                    <span>Competencias</span>
                    <span
                        class="ml-1 px-2 py-0.5 text-xs rounded-full font-bold"
                        :class="currentTab === 'competencies' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
                    >
                        {{ competencies.total || 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    @click="switchTab('job_fields')"
                    :class="[
                        'flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer',
                        currentTab === 'job_fields'
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
                            : 'text-gray-600 hover:bg-gray-100 font-medium'
                    ]"
                >
                    <i class="bi bi-briefcase-fill text-base"></i>
                    <span>Campo Laboral</span>
                    <span
                        class="ml-1 px-2 py-0.5 text-xs rounded-full font-bold"
                        :class="currentTab === 'job_fields' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
                    >
                        {{ jobFields.total || 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    @click="switchTab('meta')"
                    :class="[
                        'flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer',
                        currentTab === 'meta'
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
                            : 'text-gray-600 hover:bg-gray-100 font-medium'
                    ]"
                >
                    <i class="bi bi-palette-fill text-base"></i>
                    <span>Metadatos</span>
                    <span
                        class="ml-1 px-2 py-0.5 text-xs rounded-full font-bold"
                        :class="currentTab === 'meta' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
                    >
                        {{ metas.total || 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    @click="switchTab('requirements')"
                    :class="[
                        'flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer',
                        currentTab === 'requirements'
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold'
                            : 'text-gray-600 hover:bg-gray-100 font-medium'
                    ]"
                >
                    <i class="bi bi-check2-square text-base"></i>
                    <span>Requisitos</span>
                    <span
                        class="ml-1 px-2 py-0.5 text-xs rounded-full font-bold"
                        :class="currentTab === 'requirements' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'"
                    >
                        {{ requirements.total || 0 }}
                    </span>
                </button>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 1: PROGRAMAS DE ESTUDIO                                               -->
            <!-- ========================================================================= -->
            <div v-if="currentTab === 'programs'" class="space-y-6">
                <!-- Header Actions Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Programas de Estudio Profesionales</h2>
                        <p class="text-sm text-gray-500">Administra las carreras técnicas y profesionales ofertadas por el instituto.</p>
                    </div>

                    <Link
                        :href="route('admin.programs.create')"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap"
                    >
                        <i class="bi bi-plus-circle text-lg"></i>
                        <span>Registrar Nuevo Programa</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Programa</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Nombre o palabra clave..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                            <select
                                v-model="statusFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los estados</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors cursor-pointer"
                            >
                                Filtrar
                            </button>
                            <button
                                type="button"
                                @click="clearFilters"
                                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors cursor-pointer"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Programs Table -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                    <th class="py-4 px-6 text-center w-20">Orden</th>
                                    <th class="py-4 px-6">Programa de Estudio</th>
                                    <th class="py-4 px-6">Identificador (Tag / Acento)</th>
                                    <th class="py-4 px-6">Detalles / Duración</th>
                                    <th class="py-4 px-6 text-center">Estado</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr v-for="program in programs.data" :key="program.id" class="hover:bg-purple-50/30 transition-colors">
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-gray-100 text-gray-700 border border-gray-200">
                                            #{{ program.order }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <img
                                                v-if="program.logo_url"
                                                :src="program.logo_url"
                                                :alt="program.name"
                                                class="w-10 h-10 object-contain rounded-xl border border-gray-200 bg-white p-1 flex-shrink-0"
                                            />
                                            <div
                                                v-else
                                                class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0"
                                            >
                                                <i :class="['bi', program.icon || 'bi-mortarboard-fill']"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900 leading-tight">{{ program.name }}</h3>
                                                <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ program.description }}</p>
                                                <a
                                                    v-if="program.training_itinerary_url"
                                                    :href="program.training_itinerary_url"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 hover:text-red-800 mt-1 bg-red-50 px-2 py-0.5 rounded-md border border-red-100"
                                                    title="Ver Itinerario Formativo"
                                                >
                                                    <i class="bi bi-file-earmark-pdf-fill"></i> Itinerario PDF
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                                <i :class="['bi', program.icon || 'bi-tag-fill']"></i> {{ program.tag || 'General' }}
                                            </span>
                                            <div class="text-[11px] text-gray-400 font-mono">Acento: {{ program.accent || 'blue' }}</div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6">
                                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">{{ program.details }}</p>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="toggleStatus(program, 'program')"
                                            :disabled="togglingId === `program-${program.id}`"
                                            :class="[
                                                'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs cursor-pointer disabled:opacity-50',
                                                program.is_active
                                                    ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 rounded-full',
                                                    program.is_active ? 'bg-emerald-500' : 'bg-gray-400'
                                                ]"
                                            ></span>
                                            <span>{{ program.is_active ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.programs.edit', program.id)"
                                                class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                                                title="Editar Programa"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </Link>

                                            <button
                                                type="button"
                                                @click="openDeleteModal(program, 'program', program.name)"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors cursor-pointer"
                                                title="Eliminar Programa"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!programs.data || programs.data.length === 0">
                                    <td colspan="6" class="py-12 px-6 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                <i class="bi bi-mortarboard text-3xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron programas</p>
                                            <p class="text-xs text-gray-500">Registra un nuevo programa de estudio profesional.</p>
                                            <Link
                                                :href="route('admin.programs.create')"
                                                class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                                            >
                                                Registrar Programa
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="programs.links && programs.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Mostrando {{ programs.from || 0 }} a {{ programs.to || 0 }} de {{ programs.total || 0 }} registros
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <template v-for="(linkItem, idx) in programs.links" :key="idx">
                                <Link
                                    v-if="linkItem.url"
                                    :href="linkItem.url"
                                    :class="[
                                        'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                        linkItem.active
                                            ? 'bg-purple-600 text-white shadow-xs'
                                            : 'text-gray-600 hover:bg-white border border-transparent hover:border-gray-200'
                                    ]"
                                    v-html="linkItem.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1.5 text-xs text-gray-400 select-none"
                                    v-html="linkItem.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 2: CERTIFICACIONES MODULARES                                          -->
            <!-- ========================================================================= -->
            <div v-if="currentTab === 'modules'" class="space-y-6">
                <!-- Header Actions Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Módulos de Certificación</h2>
                        <p class="text-sm text-gray-500">Administra las certificaciones modulares de cada carrera técnica profesional.</p>
                    </div>

                    <Link
                        :href="route('admin.programs.modules.create')"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap"
                    >
                        <i class="bi bi-award text-lg"></i>
                        <span>Registrar Nuevo Módulo</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Módulo</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Nombre del módulo..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                            <select
                                v-model="programFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los programas</option>
                                <option v-for="prog in allProgramsList" :key="prog.id" :value="prog.id">
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                            <select
                                v-model="statusFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los estados</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors cursor-pointer"
                            >
                                Filtrar
                            </button>
                            <button
                                type="button"
                                @click="clearFilters"
                                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors cursor-pointer"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modules Table -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                    <th class="py-4 px-6">Módulo Formativo</th>
                                    <th class="py-4 px-6">Programa de Estudio</th>
                                    <th class="py-4 px-6 text-center">Estado</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr v-for="moduleItem in modules.data" :key="moduleItem.id" class="hover:bg-purple-50/30 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2.5 bg-indigo-100 text-indigo-600 rounded-xl flex-shrink-0">
                                                <i class="bi bi-patch-check-fill text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900 leading-tight">{{ moduleItem.module }}</h3>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                            <i class="bi bi-mortarboard-fill"></i>
                                            {{ moduleItem.study_program ? moduleItem.study_program.name : 'Programa no hallado' }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="toggleStatus(moduleItem, 'module')"
                                            :disabled="togglingId === `module-${moduleItem.id}`"
                                            :class="[
                                                'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs cursor-pointer disabled:opacity-50',
                                                moduleItem.is_active
                                                    ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 rounded-full',
                                                    moduleItem.is_active ? 'bg-emerald-500' : 'bg-gray-400'
                                                ]"
                                            ></span>
                                            <span>{{ moduleItem.is_active ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.programs.modules.edit', moduleItem.id)"
                                                class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                                                title="Editar Módulo"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </Link>

                                            <button
                                                type="button"
                                                @click="openDeleteModal(moduleItem, 'module', moduleItem.module)"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors cursor-pointer"
                                                title="Eliminar Módulo"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!modules.data || modules.data.length === 0">
                                    <td colspan="4" class="py-12 px-6 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                <i class="bi bi-award text-3xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron módulos</p>
                                            <p class="text-xs text-gray-500">Registra un nuevo módulo formativo de certificación.</p>
                                            <Link
                                                :href="route('admin.programs.modules.create')"
                                                class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                                            >
                                                Registrar Módulo
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="modules.links && modules.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Mostrando {{ modules.from || 0 }} a {{ modules.to || 0 }} de {{ modules.total || 0 }} registros
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <template v-for="(linkItem, idx) in modules.links" :key="idx">
                                <Link
                                    v-if="linkItem.url"
                                    :href="linkItem.url"
                                    :class="[
                                        'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                        linkItem.active
                                            ? 'bg-purple-600 text-white shadow-xs'
                                            : 'text-gray-600 hover:bg-white border border-transparent hover:border-gray-200'
                                    ]"
                                    v-html="linkItem.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1.5 text-xs text-gray-400 select-none"
                                    v-html="linkItem.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: COMPETENCIAS DEL PROGRAMA -->
            <div v-if="currentTab === 'competencies'" class="space-y-6">
                <!-- Header Actions Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Competencias del Perfil Profesional</h2>
                        <p class="text-sm text-gray-500">Gestiona los logros de aprendizaje y habilidades del graduado.</p>
                    </div>

                    <Link
                        :href="route('admin.programs.competencies.create')"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap"
                    >
                        <i class="bi bi-star-fill text-lg"></i>
                        <span>Registrar Nueva Competencia</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Competencia</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Título o palabra clave..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                            <select
                                v-model="programFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los programas</option>
                                <option v-for="prog in allProgramsList" :key="prog.id" :value="prog.id">
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                            <select
                                v-model="statusFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los estados</option>
                                <option value="active">Activa</option>
                                <option value="inactive">Inactiva</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors cursor-pointer"
                            >
                                Filtrar
                            </button>
                            <button
                                type="button"
                                @click="clearFilters"
                                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors cursor-pointer"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Competencies Table -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                    <th class="py-4 px-6">Competencia</th>
                                    <th class="py-4 px-6">Programa de Estudio</th>
                                    <th class="py-4 px-6 text-center">Orden</th>
                                    <th class="py-4 px-6 text-center">Estado</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr v-for="comp in competencies.data" :key="comp.id" class="hover:bg-purple-50/30 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2.5 bg-amber-100 text-amber-700 rounded-xl flex-shrink-0 mt-0.5">
                                                <i :class="['bi', comp.icon || 'bi-star-fill']"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900 leading-tight">{{ comp.title }}</h3>
                                                <p class="text-xs text-gray-500 line-clamp-2 mt-0.5 leading-relaxed">{{ comp.description }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                            <i class="bi bi-mortarboard-fill"></i>
                                            {{ comp.study_program ? comp.study_program.name : 'Programa no hallado' }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded-md text-gray-700">#{{ comp.order }}</span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="toggleStatus(comp, 'competency')"
                                            :disabled="togglingId === `competency-${comp.id}`"
                                            :class="[
                                                'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs cursor-pointer disabled:opacity-50',
                                                comp.is_active
                                                    ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 rounded-full',
                                                    comp.is_active ? 'bg-emerald-500' : 'bg-gray-400'
                                                ]"
                                            ></span>
                                            <span>{{ comp.is_active ? 'Activa' : 'Inactiva' }}</span>
                                        </button>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.programs.competencies.edit', comp.id)"
                                                class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                                                title="Editar Competencia"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </Link>

                                            <button
                                                type="button"
                                                @click="openDeleteModal(comp, 'competency', comp.title)"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors cursor-pointer"
                                                title="Eliminar Competencia"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!competencies.data || competencies.data.length === 0">
                                    <td colspan="5" class="py-12 px-6 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                <i class="bi bi-star text-3xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron competencias</p>
                                            <p class="text-xs text-gray-500">Registra competencias profesionales para los programas de estudio.</p>
                                            <Link
                                                :href="route('admin.programs.competencies.create')"
                                                class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                                            >
                                                Registrar Competencia
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="competencies.links && competencies.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Mostrando {{ competencies.from || 0 }} a {{ competencies.to || 0 }} de {{ competencies.total || 0 }} registros
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <template v-for="(linkItem, idx) in competencies.links" :key="idx">
                                <Link
                                    v-if="linkItem.url"
                                    :href="linkItem.url"
                                    :class="[
                                        'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                        linkItem.active
                                            ? 'bg-purple-600 text-white shadow-xs'
                                            : 'text-gray-600 hover:bg-white border border-transparent hover:border-gray-200'
                                    ]"
                                    v-html="linkItem.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1.5 text-xs text-gray-400 select-none"
                                    v-html="linkItem.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 4: CAMPO LABORAL                                                      -->
            <!-- ========================================================================= -->
            <div v-if="currentTab === 'job_fields'" class="space-y-6">
                <!-- Header Actions Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Campo Laboral & Oportunidades</h2>
                        <p class="text-sm text-gray-500">Gestiona los nichos de empleabilidad para los egresados.</p>
                    </div>

                    <Link
                        :href="route('admin.programs.job-fields.create')"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap"
                    >
                        <i class="bi bi-briefcase text-lg"></i>
                        <span>Registrar Nuevo Campo Laboral</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Campo Laboral</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Palabra clave..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                            <select
                                v-model="programFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los programas</option>
                                <option v-for="prog in allProgramsList" :key="prog.id" :value="prog.id">
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                            <select
                                v-model="statusFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los estados</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors cursor-pointer"
                            >
                                Filtrar
                            </button>
                            <button
                                type="button"
                                @click="clearFilters"
                                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors cursor-pointer"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Job Fields Table -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                    <th class="py-4 px-6">Descripción del Campo Laboral</th>
                                    <th class="py-4 px-6">Programa de Estudio</th>
                                    <th class="py-4 px-6 text-center">Orden</th>
                                    <th class="py-4 px-6 text-center">Estado</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr v-for="job in jobFields.data" :key="job.id" class="hover:bg-purple-50/30 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl flex-shrink-0 mt-0.5">
                                                <i class="bi bi-building text-base"></i>
                                            </div>
                                            <p class="font-medium text-gray-900 leading-relaxed">{{ job.description }}</p>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                            <i class="bi bi-mortarboard-fill"></i>
                                            {{ job.study_program ? job.study_program.name : 'Programa no hallado' }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded-md text-gray-700">#{{ job.order }}</span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="toggleStatus(job, 'jobField')"
                                            :disabled="togglingId === `jobField-${job.id}`"
                                            :class="[
                                                'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs cursor-pointer disabled:opacity-50',
                                                job.is_active
                                                    ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 rounded-full',
                                                    job.is_active ? 'bg-emerald-500' : 'bg-gray-400'
                                                ]"
                                            ></span>
                                            <span>{{ job.is_active ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.programs.job-fields.edit', job.id)"
                                                class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                                                title="Editar Campo Laboral"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </Link>

                                            <button
                                                type="button"
                                                @click="openDeleteModal(job, 'jobField', job.description)"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors cursor-pointer"
                                                title="Eliminar Campo Laboral"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!jobFields.data || jobFields.data.length === 0">
                                    <td colspan="5" class="py-12 px-6 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                <i class="bi bi-briefcase text-3xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron registros</p>
                                            <p class="text-xs text-gray-500">Registra campos laborales para orientar a los postulantes.</p>
                                            <Link
                                                :href="route('admin.programs.job-fields.create')"
                                                class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                                            >
                                                Registrar Campo Laboral
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="jobFields.links && jobFields.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Mostrando {{ jobFields.from || 0 }} a {{ jobFields.to || 0 }} de {{ jobFields.total || 0 }} registros
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <template v-for="(linkItem, idx) in jobFields.links" :key="idx">
                                <Link
                                    v-if="linkItem.url"
                                    :href="linkItem.url"
                                    :class="[
                                        'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                        linkItem.active
                                            ? 'bg-purple-600 text-white shadow-xs'
                                            : 'text-gray-600 hover:bg-white border border-transparent hover:border-gray-200'
                                    ]"
                                    v-html="linkItem.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1.5 text-xs text-gray-400 select-none"
                                    v-html="linkItem.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 5: METADATOS DE PRESENTACIÓN                                          -->
            <!-- ========================================================================= -->
            <div v-if="currentTab === 'meta'" class="space-y-6">
                <!-- Header Actions Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Metadatos & Estilos de Presentación</h2>
                        <p class="text-sm text-gray-500">Personaliza la estética visual, insignias, colores de acento y gradientes del portal web.</p>
                    </div>

                    <Link
                        :href="route('admin.programs.meta.create')"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap"
                    >
                        <i class="bi bi-palette text-lg"></i>
                        <span>Registrar Metadatos</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar por Programa</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Nombre del programa..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Programa Específico</label>
                            <select
                                v-model="programFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los programas</option>
                                <option v-for="prog in allProgramsList" :key="prog.id" :value="prog.id">
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors cursor-pointer"
                            >
                                Filtrar
                            </button>
                            <button
                                type="button"
                                @click="clearFilters"
                                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors cursor-pointer"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Meta Table -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                    <th class="py-4 px-6">Programa</th>
                                    <th class="py-4 px-6">Ícono & Acento</th>
                                    <th class="py-4 px-6">Tag / Etiqueta</th>
                                    <th class="py-4 px-6">Estilos Configurados</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr v-for="metaItem in metas.data" :key="metaItem.id" class="hover:bg-purple-50/30 transition-colors">
                                    <td class="py-4 px-6 font-bold text-gray-900">
                                        {{ metaItem.study_program ? metaItem.study_program.name : 'N/A' }}
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg">
                                                <i :class="['bi', metaItem.icon || 'bi-mortarboard-fill']"></i>
                                            </div>
                                            <span class="font-semibold text-gray-700 capitalize text-xs">{{ metaItem.accent || 'blue' }}</span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-md text-xs font-semibold">
                                            {{ metaItem.tag || 'Sin tag' }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6">
                                        <div class="text-[11px] font-mono text-gray-500 space-y-0.5">
                                            <div>Barra: {{ metaItem.color_bar || 'Por defecto' }}</div>
                                            <div>Badge: {{ metaItem.bg_badge || 'Por defecto' }}</div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.programs.meta.edit', metaItem.id)"
                                                class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                                                title="Editar Metadatos"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </Link>

                                            <button
                                                type="button"
                                                @click="openDeleteModal(metaItem, 'meta', metaItem.study_program ? metaItem.study_program.name : 'Metadatos')"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors cursor-pointer"
                                                title="Eliminar Metadatos"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!metas.data || metas.data.length === 0">
                                    <td colspan="5" class="py-12 px-6 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                <i class="bi bi-palette text-3xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron metadatos</p>
                                            <p class="text-xs text-gray-500">Configura metadatos de estilo para personalizar la interfaz pública.</p>
                                            <Link
                                                :href="route('admin.programs.meta.create')"
                                                class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                                            >
                                                Registrar Metadatos
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="metas.links && metas.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Mostrando {{ metas.from || 0 }} a {{ metas.to || 0 }} de {{ metas.total || 0 }} registros
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <template v-for="(linkItem, idx) in metas.links" :key="idx">
                                <Link
                                    v-if="linkItem.url"
                                    :href="linkItem.url"
                                    :class="[
                                        'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                        linkItem.active
                                            ? 'bg-purple-600 text-white shadow-xs'
                                            : 'text-gray-600 hover:bg-white border border-transparent hover:border-gray-200'
                                    ]"
                                    v-html="linkItem.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1.5 text-xs text-gray-400 select-none"
                                    v-html="linkItem.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- TAB 6: REQUISITOS                                                         -->
            <!-- ========================================================================= -->
            <div v-if="currentTab === 'requirements'" class="space-y-6">
                <!-- Header Actions Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Requisitos de Admisión & Matrícula</h2>
                        <p class="text-sm text-gray-500">Administra los documentos exigidos a los postulantes de cada programa.</p>
                    </div>

                    <Link
                        :href="route('admin.programs.requirements.create')"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap"
                    >
                        <i class="bi bi-check2-square text-lg"></i>
                        <span>Registrar Nuevo Requisito</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Requisito</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Palabra clave..."
                                    class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                            <select
                                v-model="programFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los programas</option>
                                <option v-for="prog in allProgramsList" :key="prog.id" :value="prog.id">
                                    {{ prog.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                            <select
                                v-model="statusFilter"
                                class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="">Todos los estados</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="submit"
                                class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors cursor-pointer"
                            >
                                Filtrar
                            </button>
                            <button
                                type="button"
                                @click="clearFilters"
                                class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors cursor-pointer"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Requirements Table -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                    <th class="py-4 px-6">Requisito</th>
                                    <th class="py-4 px-6">Programa de Estudio</th>
                                    <th class="py-4 px-6 text-center">Orden</th>
                                    <th class="py-4 px-6 text-center">Estado</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <tr v-for="req in requirements.data" :key="req.id" class="hover:bg-purple-50/30 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg flex-shrink-0 mt-0.5">
                                                <i class="bi bi-file-earmark-check text-base"></i>
                                            </div>
                                            <p class="font-medium text-gray-900 leading-relaxed">{{ req.description }}</p>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                            <i class="bi bi-mortarboard-fill"></i>
                                            {{ req.study_program ? req.study_program.name : 'Programa no hallado' }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded-md text-gray-700">#{{ req.order }}</span>
                                    </td>

                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="toggleStatus(req, 'requirement')"
                                            :disabled="togglingId === `requirement-${req.id}`"
                                            :class="[
                                                'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs cursor-pointer disabled:opacity-50',
                                                req.is_active
                                                    ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 rounded-full',
                                                    req.is_active ? 'bg-emerald-500' : 'bg-gray-400'
                                                ]"
                                            ></span>
                                            <span>{{ req.is_active ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="route('admin.programs.requirements.edit', req.id)"
                                                class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors"
                                                title="Editar Requisito"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </Link>

                                            <button
                                                type="button"
                                                @click="openDeleteModal(req, 'requirement', req.description)"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors cursor-pointer"
                                                title="Eliminar Requisito"
                                            >
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!requirements.data || requirements.data.length === 0">
                                    <td colspan="5" class="py-12 px-6 text-center">
                                        <div class="max-w-xs mx-auto text-center space-y-3">
                                            <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                <i class="bi bi-check2-square text-3xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-700">No se encontraron requisitos</p>
                                            <p class="text-xs text-gray-500">Registra los documentos necesarios para la inscripción.</p>
                                            <Link
                                                :href="route('admin.programs.requirements.create')"
                                                class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors"
                                            >
                                                Registrar Requisito
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="requirements.links && requirements.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs text-gray-500">
                            Mostrando {{ requirements.from || 0 }} a {{ requirements.to || 0 }} de {{ requirements.total || 0 }} registros
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <template v-for="(linkItem, idx) in requirements.links" :key="idx">
                                <Link
                                    v-if="linkItem.url"
                                    :href="linkItem.url"
                                    :class="[
                                        'px-3 py-1.5 text-xs rounded-lg font-semibold transition',
                                        linkItem.active
                                            ? 'bg-purple-600 text-white shadow-xs'
                                            : 'text-gray-600 hover:bg-white border border-transparent hover:border-gray-200'
                                    ]"
                                    v-html="linkItem.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1.5 text-xs text-gray-400 select-none"
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
                class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4"
            >
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center space-y-4">
                    <div class="w-16 h-16 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">¿Eliminar {{ deleteTypeLabel }}?</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Esta acción eliminará permanentemente <strong class="text-gray-700">{{ deleteTitle }}</strong>.
                        </p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeDeleteModal"
                            :disabled="isDeleting"
                            class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors cursor-pointer disabled:opacity-50"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            @click="confirmDelete"
                            :disabled="isDeleting"
                            class="flex-1 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 transition-colors cursor-pointer inline-flex items-center justify-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="isDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                            <span>{{ isDeleting ? 'Eliminando...' : 'Sí, Eliminar' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
