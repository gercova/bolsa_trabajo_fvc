<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    itineraries: {
        type: Object,
        required: true,
    },
    courses: {
        type: Array,
        default: () => [],
    },
    modules: {
        type: Array,
        default: () => [],
    },
    totalItineraries: {
        type: Number,
        default: 0,
    },
    activeItineraries: {
        type: Number,
        default: 0,
    },
    coursesWithItin: {
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
    moduleId: {
        type: [String, Number],
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
});

// Search and Filter form
const filterSearch = ref(props.search);
const filterCourseId = ref(props.courseId ? String(props.courseId) : '');
const filterStatus = ref(props.status !== null && props.status !== undefined ? String(props.status) : '');

const applyFilters = () => {
    router.get(
        route('admin.itineraries.index'),
        {
            search: filterSearch.value || undefined,
            course_id: filterCourseId.value || undefined,
            status: filterStatus.value !== '' ? filterStatus.value : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const clearFilters = () => {
    filterSearch.value = '';
    filterCourseId.value = '';
    filterStatus.value = '';
    router.get(route('admin.itineraries.index'), {}, { preserveState: true });
};

// Modal State
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingItineraryId = ref(null);

const form = useForm({
    course_id: '',
    module_id: '',
    name: '',
    is_active: true,
});

// Filter modules for selected course in modal
const filteredModules = computed(() => {
    if (!form.course_id) return [];
    const selectedCourse = props.courses.find(c => String(c.id) === String(form.course_id));
    if (selectedCourse && selectedCourse.modules && selectedCourse.modules.length > 0) {
        return selectedCourse.modules;
    }
    return props.modules.filter(m => String(m.course_id) === String(form.course_id));
});

const onCourseChange = () => {
    // If current selected module doesn't belong to newly selected course, reset module_id
    const validModule = filteredModules.value.some(m => String(m.id) === String(form.module_id));
    if (!validModule) {
        form.module_id = '';
    }
};

const openCreateModal = () => {
    isEditing.value = false;
    editingItineraryId.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    if (filterCourseId.value) {
        form.course_id = filterCourseId.value;
    }
    isModalOpen.value = true;
};

const openEditModal = (itin) => {
    isEditing.value = true;
    editingItineraryId.value = itin.id;
    form.clearErrors();
    form.course_id = itin.course_id ? String(itin.course_id) : '';
    form.module_id = itin.module_id ? String(itin.module_id) : '';
    form.name = itin.name || '';
    form.is_active = Boolean(itin.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('admin.itineraries.update', editingItineraryId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.itineraries.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

// Delete Modal State
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);
const isDeleting = ref(false);

const confirmDelete = (itin) => {
    itemToDelete.value = itin;
    isDeleteModalOpen.value = true;
};

const cancelDelete = () => {
    isDeleteModalOpen.value = false;
    itemToDelete.value = null;
};

const executeDelete = () => {
    if (!itemToDelete.value) return;
    isDeleting.value = true;
    router.delete(route('admin.itineraries.destroy', itemToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value = false;
            isDeleteModalOpen.value = false;
            itemToDelete.value = null;
        },
    });
};

// Toggle status
const togglingId = ref(null);
const toggleStatus = (itin) => {
    togglingId.value = itin.id;
    router.patch(
        route('admin.itineraries.toggle-status', itin.id),
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
    <AdminLayout title="Gestión de Itinerarios">
        <Head title="Gestión de Itinerarios — Panel Administrativo" />

        <div class="space-y-6">
            <!-- ── Top Header / Navigation Tabs ── -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight leading-none flex items-center gap-2">
                                Itinerarios Formativos
                            </h1>
                            <p class="text-xs text-gray-500 font-medium mt-1">
                                Gestión de itinerarios, temas y secuencias formativas por módulo y curso
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="openCreateModal"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Nuevo Itinerario</span>
                        </button>
                    </div>
                </div>

                <!-- Sub-Navigation Tabs -->
                <div class="px-6 bg-gray-50/60 flex items-center gap-1 overflow-x-auto text-xs sm:text-sm font-semibold">
                    <Link
                        :href="route('admin.programs.index')"
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap"
                    >
                        <i class="bi bi-mortarboard"></i> Programas de Estudio
                    </Link>
                    <Link
                        :href="route('admin.certificates.index')"
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap"
                    >
                        <i class="bi bi-patch-check"></i> Certificados
                    </Link>
                    <Link
                        :href="route('admin.courses.index')"
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap"
                    >
                        <i class="bi bi-journal-bookmark"></i> Cursos
                    </Link>
                    <Link
                        :href="route('admin.modules.index')"
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap"
                    >
                        <i class="bi bi-layers"></i> Módulos
                    </Link>
                    <Link
                        :href="route('admin.itineraries.index')"
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-purple-600 text-purple-700 whitespace-nowrap"
                    >
                        <i class="bi bi-diagram-3"></i> Itinerarios
                    </Link>
                </div>
            </div>

            <!-- ── Stat Cards ── -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Itinerarios</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ Number(totalItineraries).toLocaleString() }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Itinerarios Activos</p>
                        <h3 class="text-2xl font-black text-emerald-700">{{ Number(activeItineraries).toLocaleString() }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cursos con Itinerario</p>
                        <h3 class="text-2xl font-black text-blue-700">{{ Number(coursesWithItin).toLocaleString() }}</h3>
                    </div>
                </div>
            </div>

            <!-- ── Filters Bar ── -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs space-y-4">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                    <!-- Search -->
                    <div class="lg:col-span-4 relative">
                        <input
                            v-model="filterSearch"
                            type="text"
                            placeholder="Buscar por nombre, módulo o curso..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium"
                        />
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Course Filter -->
                    <div class="lg:col-span-3">
                        <select
                            v-model="filterCourseId"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium text-gray-700 truncate"
                        >
                            <option value="">Curso: Todos</option>
                            <option v-for="c in courses" :key="c.id" :value="String(c.id)">
                                {{ c.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="lg:col-span-2">
                        <select
                            v-model="filterStatus"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium text-gray-700"
                        >
                            <option value="">Estado: Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-3 flex items-center gap-2 justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1"
                        >
                            <i class="bi bi-funnel-fill"></i> Filtrar
                        </button>
                        <button
                            v-if="filterSearch || filterCourseId || filterStatus !== ''"
                            type="button"
                            @click="clearFilters"
                            class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition-all"
                            title="Limpiar Filtros"
                        >
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </form>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-500">
                        Mostrando {{ itineraries.total || itineraries.data?.length || 0 }} itinerarios registrados
                    </span>
                </div>
            </div>

            <!-- ── Table / List ── -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div v-if="!itineraries.data || itineraries.data.length === 0" class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                    <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-400 flex items-center justify-center text-3xl">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-gray-700">Sin itinerarios registrados</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span v-if="filterSearch || filterCourseId || filterStatus !== ''">
                                No hay itinerarios con los filtros aplicados.
                                <button type="button" @click="clearFilters" class="text-purple-600 font-semibold hover:underline">
                                    Limpiar filtros
                                </button>
                            </span>
                            <span v-else>
                                Empieza registrando el primer itinerario con el botón <strong>Nuevo Itinerario</strong>.
                            </span>
                        </p>
                    </div>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Curso</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Módulo</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Itinerario / Contenido</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="itin in itineraries.data" :key="itin.id" class="hover:bg-gray-50/80 transition-colors">
                                <!-- Course -->
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-journal-bookmark text-purple-600"></i>
                                        <span class="font-semibold text-gray-800 text-xs truncate max-w-[220px]" :title="itin.course?.name">
                                            {{ itin.course?.name || '—' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Module -->
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-gray-700 text-xs truncate max-w-[220px]" :title="itin.module?.name">
                                        {{ itin.module?.name || '—' }}
                                    </div>
                                </td>

                                <!-- Name -->
                                <td class="px-5 py-3">
                                    <div class="font-bold text-gray-900 text-sm">
                                        {{ itin.name }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <button
                                        type="button"
                                        @click="toggleStatus(itin)"
                                        :disabled="togglingId === itin.id"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all cursor-pointer disabled:opacity-50"
                                        :class="itin.is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        title="Clic para cambiar estado"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" :class="itin.is_active ? 'bg-emerald-500' : 'bg-gray-400'"></span>
                                        <span v-if="togglingId === itin.id">
                                            <i class="bi bi-arrow-repeat animate-spin"></i>
                                        </span>
                                        <span v-else>
                                            {{ itin.is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            type="button"
                                            @click="openEditModal(itin)"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Editar Itinerario"
                                        >
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button
                                            type="button"
                                            @click="confirmDelete(itin)"
                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Eliminar Itinerario"
                                        >
                                            <i class="bi bi-trash3 text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    v-if="itineraries.links && itineraries.links.length > 3"
                    class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3"
                >
                    <p class="text-xs text-gray-500">
                        Mostrando
                        <strong>{{ itineraries.from || 1 }}</strong>–<strong>{{ itineraries.to || itineraries.data.length }}</strong>
                        de <strong>{{ itineraries.total }}</strong> itinerarios
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in itineraries.links"
                            :key="i"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors"
                            :class="[
                                link.active
                                    ? 'bg-purple-600 text-white'
                                    : link.url
                                        ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                        : 'bg-gray-50 text-gray-400 cursor-not-allowed'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ MODAL CREATE / EDIT ══════════════════════════════════════════ -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
        >
            <div
                @click.stop
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden animate-scale-up"
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i :class="isEditing ? 'bi bi-pencil-square' : 'bi bi-diagram-3-fill'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">
                                {{ isEditing ? 'Editar Itinerario' : 'Nuevo Itinerario' }}
                            </h3>
                            <p class="text-xs text-gray-500">Asigne el itinerario al curso y módulo correspondiente</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg transition-colors"
                    >
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    <!-- Validation Errors Alert -->
                    <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border-l-4 border-red-500 p-3 rounded-xl">
                        <p class="text-xs font-bold text-red-800 mb-1">Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                            <li v-for="(err, f) in form.errors" :key="f">{{ err }}</li>
                        </ul>
                    </div>

                    <!-- Course Select -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Curso <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.course_id"
                            @change="onCourseChange"
                            required
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium"
                            :class="{ 'border-red-500': form.errors.course_id }"
                        >
                            <option value="">-- Seleccione curso --</option>
                            <option v-for="c in courses" :key="c.id" :value="String(c.id)">
                                {{ c.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.course_id" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.course_id }}</p>
                    </div>

                    <!-- Module Select -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Módulo <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.module_id"
                            required
                            :disabled="!form.course_id"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium disabled:bg-gray-100 disabled:cursor-not-allowed"
                            :class="{ 'border-red-500': form.errors.module_id }"
                        >
                            <option value="">-- Seleccione módulo --</option>
                            <option v-for="mod in filteredModules" :key="mod.id" :value="String(mod.id)">
                                {{ mod.name }}
                            </option>
                        </select>
                        <p v-if="!form.course_id" class="text-xs text-gray-400 mt-1">Seleccione primero un curso para ver sus módulos</p>
                        <p v-else-if="filteredModules.length === 0" class="text-xs text-amber-600 mt-1">Este curso no tiene módulos registrados</p>
                        <p v-if="form.errors.module_id" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.module_id }}</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Nombre del Itinerario / Tema <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            maxlength="255"
                            placeholder="Ej: Sesión 1: Arquitectura básica y componentes de placa madre"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium"
                            :class="{ 'border-red-500': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.name }}</p>
                    </div>

                    <!-- Active Toggle -->
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Itinerario Activo</h4>
                            <p class="text-xs text-gray-500">Determina si la sesión o tema del itinerario está habilitado en el plan de estudios.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="sr-only peer"
                            />
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <span>{{ isEditing ? 'Guardar Cambios' : 'Registrar Itinerario' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══ MODAL CONFIRM DELETE ═════════════════════════════════════════ -->
        <div
            v-if="isDeleteModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
        >
            <div
                @click.stop
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md p-6 text-center animate-scale-up"
            >
                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2">¿Eliminar Itinerario?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    ¿Está seguro de eliminar el itinerario «<strong>{{ itemToDelete?.name }}</strong>»? Esta acción no se puede deshacer.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button
                        type="button"
                        @click="cancelDelete"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors flex-1"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="executeDelete"
                        :disabled="isDeleting"
                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 flex-1 disabled:opacity-50"
                    >
                        <i v-if="isDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>Eliminar</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.2s ease-out;
}
.animate-scale-up {
    animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes scaleUp {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}
</style>
