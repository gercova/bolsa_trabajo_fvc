<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    cepreAdmissions: { type: Object, default: () => ({ data: [] }) },
    admisionAdmissions: { type: Object, default: () => ({ data: [] }) },
    cepreImage: { type: Object, default: () => null },
    admisionImage: { type: Object, default: () => null },
    filters: { type: Object, default: () => ({}) },
});

// Active tab between Admisión and CEPRE
const activeTab = ref('admision');

// Search & Filter state
const search = ref(props.filters?.search || '');
const typeFilter = ref(props.filters?.type || '');
const statusFilter = ref(props.filters?.status || '');

const applyFilters = () => {
    router.get(route('admin.exams.index'), {
        search: search.value || undefined,
        type: typeFilter.value || undefined,
        status: statusFilter.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    search.value = '';
    typeFilter.value = '';
    statusFilter.value = '';
    router.get(route('admin.exams.index'), {}, { preserveScroll: true });
};

// Toggle status
const toggleStatus = (id) => {
    router.patch(route('admin.exams.toggle-status', id), {}, {
        preserveScroll: true,
    });
};

// Delete modal
const showDeleteModal = ref(false);
const itemToDelete = ref(null);

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (!itemToDelete.value) return;
    router.delete(route('admin.exams.destroy', itemToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        },
    });
};

// Banner Images Forms
const cepreForm = useForm({
    image: null,
});
const ceprePreview = ref(null);

const onCepreFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        cepreForm.image = file;
        ceprePreview.value = URL.createObjectURL(file);
    }
};

const uploadCepreImage = () => {
    if (!cepreForm.image) return;
    cepreForm.post(route('admin.exams.update-cepre-image'), {
        preserveScroll: true,
        onSuccess: () => {
            ceprePreview.value = null;
            cepreForm.reset();
        },
    });
};

const admisionForm = useForm({
    image: null,
});
const admisionPreview = ref(null);

const onAdmisionFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        admisionForm.image = file;
        admisionPreview.value = URL.createObjectURL(file);
    }
};

const uploadAdmisionImage = () => {
    if (!admisionForm.image) return;
    admisionForm.post(route('admin.exams.update-admission-image'), {
        preserveScroll: true,
        onSuccess: () => {
            admisionPreview.value = null;
            admisionForm.reset();
        },
    });
};

// Helper for asset URLs
const storageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    return `/storage/${path.replace(/^\//, '')}`;
};
</script>

<template>
    <AdminLayout title="Gestión de Exámenes de Admisión / CEPRE"
                 subtitle="Administra los procesos de ingreso ordinario, extraordinario y ciclo preuniversitario"
                 icon="bi-journal-text"
                 :breadcrumbs="[{ label: 'Admisión / CEPRE' }]">
        <div class="space-y-6 max-w-7xl mx-auto">

            <!-- ══ TABS DE NAVEGACIÓN ══ -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <button @click="activeTab = 'admision'"
                            type="button"
                            class="px-5 py-3 rounded-t-2xl font-extrabold text-sm flex items-center gap-2 border-b-2 transition-all"
                            :class="activeTab === 'admision'
                                ? 'border-purple-600 text-purple-700 bg-white shadow-sm'
                                : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100'">
                        <i class="bi bi-mortarboard-fill text-purple-600"></i>
                        <span>Examen de Admisión</span>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-800 font-black">
                            {{ admisionAdmissions?.total || admisionAdmissions?.data?.length || 0 }}
                        </span>
                    </button>

                    <button @click="activeTab = 'cepre'"
                            type="button"
                            class="px-5 py-3 rounded-t-2xl font-extrabold text-sm flex items-center gap-2 border-b-2 transition-all"
                            :class="activeTab === 'cepre'
                                ? 'border-purple-600 text-purple-700 bg-white shadow-sm'
                                : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100'">
                        <i class="bi bi-book-fill text-indigo-600"></i>
                        <span>Ciclo CEPRE</span>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800 font-black">
                            {{ cepreAdmissions?.total || cepreAdmissions?.data?.length || 0 }}
                        </span>
                    </button>
                </div>

                <Link :href="route('admin.exams.create')"
                      class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-purple-500/20 transition-all">
                    <i class="bi bi-plus-circle-fill text-base"></i>
                    <span>Nuevo Examen / Ciclo</span>
                </Link>
            </div>

            <!-- ══ HERO BANNERS MANAGEMENT SECTION ══ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Banner Admisión -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                                <i class="bi bi-image"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Banner Examen Admisión</h3>
                                <p class="text-[11px] text-slate-400">Imagen de portada del portal público</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                            Página Pública
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-32 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 relative">
                            <img :src="admisionPreview || (admisionImage?.path ? storageUrl(admisionImage.path) : '/images/admission-default.jpg')"
                                 class="w-full h-full object-cover"
                                 alt="Admisión Banner" />
                        </div>
                        <form @submit.prevent="uploadAdmisionImage" class="flex-1 space-y-2">
                            <input type="file"
                                   id="admisionFileInput"
                                   accept="image/png,image/jpeg,image/webp"
                                   @change="onAdmisionFileChange"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer" />
                            <button v-if="admisionForm.image"
                                    type="submit"
                                    :disabled="admisionForm.processing"
                                    class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-lg transition disabled:opacity-50">
                                {{ admisionForm.processing ? 'Guardando...' : 'Guardar Imagen' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Banner CEPRE -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="bi bi-image"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Banner Ciclo CEPRE</h3>
                                <p class="text-[11px] text-slate-400">Imagen de portada del portal CEPRE</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Página Pública
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-32 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shrink-0 relative">
                            <img :src="ceprePreview || (cepreImage?.path ? storageUrl(cepreImage.path) : '/images/cepre-default.jpg')"
                                 class="w-full h-full object-cover"
                                 alt="CEPRE Banner" />
                        </div>
                        <form @submit.prevent="uploadCepreImage" class="flex-1 space-y-2">
                            <input type="file"
                                   id="cepreFileInput"
                                   accept="image/png,image/jpeg,image/webp"
                                   @change="onCepreFileChange"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            <button v-if="cepreForm.image"
                                    type="submit"
                                    :disabled="cepreForm.processing"
                                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-lg transition disabled:opacity-50">
                                {{ cepreForm.processing ? 'Guardando...' : 'Guardar Imagen' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ══ FILTERS TOOLBAR ══ -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto flex-1">
                    <div class="relative w-full sm:w-72">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input v-model="search"
                               @keyup.enter="applyFilters"
                               type="text"
                               placeholder="Buscar periodo o actividad..."
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" />
                    </div>

                    <select v-model="typeFilter"
                            @change="applyFilters"
                            class="w-full sm:w-auto px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-purple-500 transition">
                        <option value="">Todos los Tipos</option>
                        <option value="ordinario">Ordinario</option>
                        <option value="extraordinario">Extraordinario</option>
                    </select>

                    <select v-model="statusFilter"
                            @change="applyFilters"
                            class="w-full sm:w-auto px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-purple-500 transition">
                        <option value="">Todos los Estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>

                    <button @click="applyFilters"
                            type="button"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>

                    <button v-if="search || typeFilter || statusFilter"
                            @click="clearFilters"
                            type="button"
                            class="px-3 py-2 text-rose-600 hover:bg-rose-50 font-bold text-xs rounded-xl transition">
                        Limpiar
                    </button>
                </div>
            </div>

            <!-- ══ TAB 1: ADMISIÓN ORDINARIA / EXTRAORDINARIA ══ -->
            <div v-show="activeTab === 'admision'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                                <th class="p-4">Periodo & Proceso</th>
                                <th class="p-4">Actividad</th>
                                <th class="p-4 text-center">Fechas Clave</th>
                                <th class="p-4 text-center">Vacantes</th>
                                <th class="p-4 text-center">Costo</th>
                                <th class="p-4 text-center">Documentos</th>
                                <th class="p-4 text-center">Estado</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="item in admisionAdmissions.data" :key="item.id" class="hover:bg-slate-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ item.period }}</div>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase mt-1"
                                          :class="item.type === 'ordinario' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200'">
                                        {{ item.type }}
                                    </span>
                                </td>
                                <td class="p-4 font-semibold text-slate-800 max-w-xs truncate">
                                    {{ item.activity }}
                                </td>
                                <td class="p-4 text-center text-slate-600 space-y-0.5">
                                    <div><strong class="text-slate-800">Examen:</strong> {{ item.exam_date }}</div>
                                    <div class="text-[11px] text-slate-400">{{ item.inscription_start_date }} al {{ item.inscription_end_date }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="font-extrabold text-slate-900 text-sm">{{ item.total_vacancies }}</span>
                                    <span class="text-slate-400 text-[10px] block">plazas</span>
                                </td>
                                <td class="p-4 text-center font-mono font-bold text-slate-800">
                                    S/ {{ Number(item.price || 0).toFixed(2) }}
                                </td>
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a v-if="item.url_pdf"
                                       :href="storageUrl(item.url_pdf)"
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold hover:bg-red-100 transition"
                                       title="Ver Bases">
                                        <i class="bi bi-file-earmark-pdf"></i> Bases
                                    </a>
                                    <a v-if="item.results_url_pdf"
                                       :href="storageUrl(item.results_url_pdf)"
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 text-[11px] font-bold hover:bg-emerald-100 transition"
                                       title="Ver Resultados">
                                        <i class="bi bi-file-earmark-check"></i> Resultados
                                    </a>
                                    <span v-if="!item.url_pdf && !item.results_url_pdf" class="text-slate-300 text-xs">—</span>
                                </td>
                                <td class="p-4 text-center">
                                    <button @click="toggleStatus(item.id)"
                                            type="button"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold transition"
                                            :class="item.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        {{ item.is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <Link :href="route('admin.exams.edit', item.id)"
                                          class="p-2 rounded-xl text-slate-500 hover:text-purple-600 hover:bg-purple-50 transition inline-block"
                                          title="Editar examen">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </Link>
                                    <button @click="confirmDelete(item)"
                                            type="button"
                                            class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition inline-block"
                                            title="Eliminar examen">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!admisionAdmissions.data || admisionAdmissions.data.length === 0">
                                <td colspan="8" class="p-12 text-center text-slate-400">
                                    <i class="bi bi-inbox text-3xl block mb-2"></i>
                                    No se encontraron convocatorias de admisión ordinaria/extraordinaria.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="admisionAdmissions.links && admisionAdmissions.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        Mostrando {{ admisionAdmissions.from || 0 }} - {{ admisionAdmissions.to || 0 }} de {{ admisionAdmissions.total }} registros
                    </p>
                    <div class="flex items-center gap-1">
                        <Link v-for="(link, i) in admisionAdmissions.links" :key="i"
                              :href="link.url || '#'"
                              v-html="link.label"
                              class="px-3 py-1.5 rounded-lg text-xs font-bold transition"
                              :class="link.active ? 'bg-purple-600 text-white' : (link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none')" />
                    </div>
                </div>
            </div>

            <!-- ══ TAB 2: CICLO CEPRE ══ -->
            <div v-show="activeTab === 'cepre'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                                <th class="p-4">Periodo</th>
                                <th class="p-4">Actividad</th>
                                <th class="p-4 text-center">Fechas Clave</th>
                                <th class="p-4 text-center">Vacantes</th>
                                <th class="p-4 text-center">Costo</th>
                                <th class="p-4 text-center">Documentos</th>
                                <th class="p-4 text-center">Estado</th>
                                <th class="p-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="item in cepreAdmissions.data" :key="item.id" class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-slate-900 text-sm">
                                    {{ item.period }}
                                </td>
                                <td class="p-4 font-semibold text-slate-800 max-w-xs truncate">
                                    {{ item.activity }}
                                </td>
                                <td class="p-4 text-center text-slate-600 space-y-0.5">
                                    <div><strong class="text-slate-800">Examen:</strong> {{ item.exam_date }}</div>
                                    <div class="text-[11px] text-slate-400">{{ item.inscription_start_date }} al {{ item.inscription_end_date }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="font-extrabold text-slate-900 text-sm">{{ item.total_vacancies }}</span>
                                    <span class="text-slate-400 text-[10px] block">plazas</span>
                                </td>
                                <td class="p-4 text-center font-mono font-bold text-slate-800">
                                    S/ {{ Number(item.price || 0).toFixed(2) }}
                                </td>
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a v-if="item.url_pdf"
                                       :href="storageUrl(item.url_pdf)"
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold hover:bg-red-100 transition"
                                       title="Ver Bases CEPRE">
                                        <i class="bi bi-file-earmark-pdf"></i> Bases
                                    </a>
                                    <a v-if="item.results_url_pdf"
                                       :href="storageUrl(item.results_url_pdf)"
                                       target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 text-[11px] font-bold hover:bg-emerald-100 transition"
                                       title="Ver Resultados CEPRE">
                                        <i class="bi bi-file-earmark-check"></i> Resultados
                                    </a>
                                    <span v-if="!item.url_pdf && !item.results_url_pdf" class="text-slate-300 text-xs">—</span>
                                </td>
                                <td class="p-4 text-center">
                                    <button @click="toggleStatus(item.id)"
                                            type="button"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold transition"
                                            :class="item.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        {{ item.is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                    <Link :href="route('admin.exams.edit', item.id)"
                                          class="p-2 rounded-xl text-slate-500 hover:text-purple-600 hover:bg-purple-50 transition inline-block"
                                          title="Editar ciclo CEPRE">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </Link>
                                    <button @click="confirmDelete(item)"
                                            type="button"
                                            class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition inline-block"
                                            title="Eliminar ciclo CEPRE">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!cepreAdmissions.data || cepreAdmissions.data.length === 0">
                                <td colspan="8" class="p-12 text-center text-slate-400">
                                    <i class="bi bi-inbox text-3xl block mb-2"></i>
                                    No se encontraron convocatorias de CEPRE.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="cepreAdmissions.links && cepreAdmissions.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        Mostrando {{ cepreAdmissions.from || 0 }} - {{ cepreAdmissions.to || 0 }} de {{ cepreAdmissions.total }} registros
                    </p>
                    <div class="flex items-center gap-1">
                        <Link v-for="(link, i) in cepreAdmissions.links" :key="i"
                              :href="link.url || '#'"
                              v-html="link.label"
                              class="px-3 py-1.5 rounded-lg text-xs font-bold transition"
                              :class="link.active ? 'bg-purple-600 text-white' : (link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none')" />
                    </div>
                </div>
            </div>

            <!-- ══ CONFIRM DELETE MODAL ══ -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 space-y-5 animate-fade-in">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="text-center space-y-2">
                        <h3 class="text-base font-extrabold text-slate-900">¿Eliminar este examen / proceso?</h3>
                        <p class="text-xs text-slate-500">
                            Estás a punto de eliminar <strong>{{ itemToDelete?.period }} — {{ itemToDelete?.activity }}</strong>. Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button @click="showDeleteModal = false"
                                type="button"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                            Cancelar
                        </button>
                        <button @click="executeDelete"
                                type="button"
                                class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition">
                            Sí, Eliminar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>
