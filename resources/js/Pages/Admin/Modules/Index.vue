<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    modules: {
        type: Object,
        required: true,
    },
    courses: {
        type: Array,
        default: () => [],
    },
    totalModules: {
        type: Number,
        default: 0,
    },
    activeModules: {
        type: Number,
        default: 0,
    },
    coursesCount: {
        type: Number,
        default: 0,
    },
    search: {
        type: String,
        default: '',
    },
    courseId: {
        type: [String, Number],
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
});

// Filter state
const searchQuery = ref(props.search || '');
const selectedCourse = ref(props.courseId ?? '');
const statusFilter = ref(props.status ?? '');

const applyFilters = () => {
    router.get(
        route('admin.modules.index'),
        {
            search: searchQuery.value || undefined,
            course_id: selectedCourse.value !== '' ? selectedCourse.value : undefined,
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
    selectedCourse.value = '';
    statusFilter.value = '';
    router.get(route('admin.modules.index'), {}, { preserveScroll: true });
};

// Toggle status
const togglingId = ref(null);
const toggleStatus = (moduleItem) => {
    togglingId.value = moduleItem.id;
    router.patch(
        route('admin.modules.toggle-status', moduleItem.id),
        {},
        {
            preserveScroll: true,
            onFinish: () => {
                togglingId.value = null;
            },
        }
    );
};

// Modal state (Create & Edit)
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingModuleId = ref(null);

const form = useForm({
    course_id: '',
    name: '',
    credits: '',
    is_active: true,
});

const openCreateModal = () => {
    isEditing.value = false;
    editingModuleId.value = null;
    form.reset();
    form.clearErrors();
    form.course_id = selectedCourse.value ? selectedCourse.value : (props.courses[0]?.id || '');
    form.is_active = true;
    isModalOpen.value = true;
};

const openEditModal = (moduleItem) => {
    isEditing.value = true;
    editingModuleId.value = moduleItem.id;
    form.clearErrors();
    form.course_id = moduleItem.course_id || '';
    form.name = moduleItem.name || '';
    form.credits = moduleItem.credits || '';
    form.is_active = Boolean(moduleItem.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    if (!form.processing) {
        isModalOpen.value = false;
    }
};

const submitModule = () => {
    if (isEditing.value) {
        form.put(route('admin.modules.update', editingModuleId.value), {
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = false;
            },
        });
    } else {
        form.post(route('admin.modules.store'), {
            preserveScroll: true,
            onSuccess: () => {
                isModalOpen.value = false;
            },
        });
    }
};

// Delete Modal
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const isDeleting = ref(false);

const openDeleteModal = (moduleItem) => {
    itemToDelete.value = moduleItem;
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
    router.delete(route('admin.modules.destroy', itemToDelete.value.id), {
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
    <Head title="Gestión de Módulos — Panel Administrativo" />

    <AdminLayout title="Gestión de Módulos">
        <div class="space-y-6">
            <!-- Navigation Sub-Tabs -->
            <div class="bg-white p-2 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-1 overflow-x-auto">
                <Link
                    :href="route('admin.programs.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition whitespace-nowrap"
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
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-bold rounded-xl bg-purple-600 text-white shadow-xs shadow-purple-500/20 whitespace-nowrap"
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

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-layers text-purple-600"></i>
                        Módulos Académicos
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Gestión de los módulos temáticos y unidades de aprendizaje vinculados a los cursos.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="openCreateModal"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer"
                    >
                        <i class="bi bi-plus-circle-fill text-base"></i>
                        <span>Nuevo Módulo</span>
                    </button>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-layers-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Módulos</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ totalModules }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Módulos Activos</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ activeModules }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cursos con Módulos</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ coursesCount }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-200/80 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form @submit.prevent="applyFilters" class="w-full flex flex-col sm:flex-row items-center gap-3 flex-1">
                    <div class="relative w-full sm:max-w-xs">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Buscar por módulo o créditos..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400"
                        />
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <div class="w-full sm:w-auto">
                        <select
                            v-model="selectedCourse"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Todos los Cursos</option>
                            <option v-for="c in courses" :key="c.id" :value="c.id">
                                {{ c.name }}
                            </option>
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <select
                            v-model="statusFilter"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Todos los Estados</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button
                            type="submit"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer"
                        >
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button
                            v-if="searchQuery || selectedCourse !== '' || statusFilter !== ''"
                            type="button"
                            @click="clearFilters"
                            class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors cursor-pointer"
                        >
                            Limpiar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Modules Table -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[750px]">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="p-4">Módulo</th>
                                <th class="p-4">Curso Vinculado</th>
                                <th class="p-4 text-center">Créditos</th>
                                <th class="p-4 text-center">Itinerarios</th>
                                <th class="p-4 text-center">Calificaciones</th>
                                <th class="p-4 text-center">Estado</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr
                                v-for="moduleItem in modules.data"
                                :key="moduleItem.id"
                                class="hover:bg-purple-50/20 transition-colors"
                            >
                                <td class="p-4">
                                    <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                        <i class="bi bi-layers text-purple-600"></i>
                                        <span>{{ moduleItem.name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">ID: #{{ moduleItem.id }}</span>
                                </td>

                                <td class="p-4">
                                    <span
                                        v-if="moduleItem.course"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200"
                                    >
                                        <i class="bi bi-journal-bookmark text-xs"></i>
                                        {{ moduleItem.course.name }}
                                    </span>
                                    <span v-else class="text-xs text-gray-400 italic">Sin curso</span>
                                </td>

                                <td class="p-4 text-center font-mono text-xs font-bold text-gray-700">
                                    {{ moduleItem.credits || '—' }}
                                </td>

                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="bi bi-diagram-3 text-[10px]"></i>
                                        {{ moduleItem.itineraries_count || 0 }}
                                    </span>
                                </td>

                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="bi bi-check2-circle text-[10px]"></i>
                                        {{ moduleItem.certificate_details_count || 0 }}
                                    </span>
                                </td>

                                <td class="p-4 text-center whitespace-nowrap">
                                    <button
                                        type="button"
                                        @click="toggleStatus(moduleItem)"
                                        :disabled="togglingId === moduleItem.id"
                                        :class="[
                                            'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs cursor-pointer disabled:opacity-50',
                                            moduleItem.is_active
                                                ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200'
                                                : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200'
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'w-2 h-2 rounded-full',
                                                moduleItem.is_active ? 'bg-emerald-500' : 'bg-rose-500'
                                            ]"
                                        ></span>
                                        <span>{{ moduleItem.is_active ? 'Activo' : 'Inactivo' }}</span>
                                    </button>
                                </td>

                                <td class="p-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            type="button"
                                            @click="openEditModal(moduleItem)"
                                            class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors cursor-pointer"
                                            title="Editar Módulo"
                                        >
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>

                                        <button
                                            type="button"
                                            @click="openDeleteModal(moduleItem)"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                            title="Eliminar Módulo"
                                        >
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty state -->
                            <tr v-if="!modules.data || modules.data.length === 0">
                                <td colspan="7" class="p-12 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto space-y-3">
                                        <i class="bi bi-layers text-4xl text-gray-300"></i>
                                        <p class="text-base font-bold text-gray-700">No se encontraron módulos</p>
                                        <p class="text-xs text-gray-500">
                                            No hay módulos disponibles para los criterios seleccionados.
                                        </p>
                                        <button
                                            type="button"
                                            @click="openCreateModal"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition cursor-pointer"
                                        >
                                            Registrar Primer Módulo
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="modules.links && modules.links.length > 3" class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
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

        <!-- Create / Edit Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        >
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 transform transition-all animate-in fade-in zoom-in-95">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                        <i :class="['bi text-purple-600', isEditing ? 'bi-pencil-square' : 'bi-plus-circle']"></i>
                        <span>{{ isEditing ? 'Editar Módulo' : 'Registrar Nuevo Módulo' }}</span>
                    </h3>
                    <button
                        type="button"
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg"
                    >
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitModule" class="mt-4 space-y-4">
                    <!-- Course selection -->
                    <div>
                        <label for="module_course" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Curso Asociado <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="module_course"
                            v-model="form.course_id"
                            required
                            :class="[
                                'w-full px-3 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700',
                                form.errors.course_id ? 'border-rose-500 bg-rose-50/20' : 'border-gray-300'
                            ]"
                        >
                            <option value="" disabled>Seleccione un curso...</option>
                            <option v-for="c in courses" :key="c.id" :value="c.id">
                                {{ c.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.course_id" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.course_id }}</p>
                    </div>

                    <!-- Module Name -->
                    <div>
                        <label for="module_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Nombre del Módulo <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="module_name"
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Ej. Módulo I: Fundamentos de Gestión Documental..."
                            :class="[
                                'w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400',
                                form.errors.name ? 'border-rose-500 bg-rose-50/20' : 'border-gray-300'
                            ]"
                        />
                        <p v-if="form.errors.name" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.name }}</p>
                    </div>

                    <!-- Credits -->
                    <div>
                        <label for="module_credits" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Créditos / Horas
                        </label>
                        <input
                            id="module_credits"
                            v-model="form.credits"
                            type="text"
                            placeholder="Ej. 4 créditos, 60 horas..."
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400"
                        />
                        <p v-if="form.errors.credits" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.credits }}</p>
                    </div>

                    <!-- Active state -->
                    <div>
                        <label class="inline-flex items-center gap-2.5 cursor-pointer">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="w-4 h-4 text-purple-600 rounded-md border-gray-300 focus:ring-purple-500"
                            />
                            <span class="text-xs font-bold text-gray-800">Módulo activo para calificaciones</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            :disabled="form.processing"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-md shadow-purple-500/20 transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-check-lg"></i>
                            <span>{{ form.processing ? 'Guardando...' : (isEditing ? 'Actualizar Módulo' : 'Guardar Módulo') }}</span>
                        </button>
                    </div>
                </form>
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
                        <h3 class="text-lg font-extrabold text-gray-900">Eliminar Módulo</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            ¿Está seguro de que desea eliminar el módulo «<strong class="text-gray-800">{{ itemToDelete?.name }}</strong>»? Esta acción no se puede deshacer.
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
