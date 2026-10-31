<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    scholarships: { type: Object, required: true },
    search: { type: String, default: '' },
    status: { type: String, default: '' },
    beneficiaries: { type: Object, required: true },
    beneficiarySearch: { type: String, default: '' },
    beneficiaryPeriod: { type: String, default: '' },
    beneficiaryStatus: { type: String, default: '' },
    allScholarships: { type: Array, default: () => [] },
    periods: { type: Array, default: () => [] },
    totalModalities: { type: Number, default: 0 },
    totalBeneficiaries: { type: Number, default: 0 },
    activeTab: { type: String, default: 'modalities' },
});

const currentTab = ref(props.activeTab || 'modalities');

// ── Modalities Filters ──────────────────────────────────────
const modSearch = ref(props.search || '');
const modStatus = ref(props.status || '');

const filterModalities = () => {
    router.get(route('admin.scholarships.index'), {
        tab: 'modalities',
        search: modSearch.value || undefined,
        status: modStatus.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearModFilters = () => {
    modSearch.value = '';
    modStatus.value = '';
    router.get(route('admin.scholarships.index'), { tab: 'modalities' });
};

// Toggle status modality
const togglingModId = ref(null);
const toggleModStatus = (scholarship) => {
    togglingModId.value = scholarship.id;
    router.patch(route('admin.scholarships.toggle-status', scholarship.id), {}, {
        preserveScroll: true,
        onFinish: () => { togglingModId.value = null; },
    });
};

// Delete modality modal
const deletingMod = ref(null);
const confirmDeleteMod = (scholarship) => {
    deletingMod.value = scholarship;
};
const executeDeleteMod = () => {
    if (!deletingMod.value) return;
    router.delete(route('admin.scholarships.destroy', deletingMod.value.id), {
        preserveScroll: true,
        onSuccess: () => { deletingMod.value = null; },
    });
};

// ── Beneficiaries Filters ───────────────────────────────────
const benSearch = ref(props.beneficiarySearch || '');
const benPeriod = ref(props.beneficiaryPeriod || '');
const benStatus = ref(props.beneficiaryStatus || '');

const filterBeneficiaries = () => {
    router.get(route('admin.scholarships.index'), {
        tab: 'beneficiaries',
        beneficiary_search: benSearch.value || undefined,
        beneficiary_period: benPeriod.value || undefined,
        beneficiary_status: benStatus.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearBenFilters = () => {
    benSearch.value = '';
    benPeriod.value = '';
    benStatus.value = '';
    router.get(route('admin.scholarships.index'), { tab: 'beneficiaries' });
};

// Toggle status beneficiary
const togglingBenId = ref(null);
const toggleBenStatus = (ben) => {
    togglingBenId.value = ben.id;
    router.patch(route('admin.scholarships.beneficiaries.toggle-status', ben.id), {}, {
        preserveScroll: true,
        onFinish: () => { togglingBenId.value = null; },
    });
};

// Delete beneficiary modal
const deletingBen = ref(null);
const confirmDeleteBen = (ben) => {
    deletingBen.value = ben;
};
const executeDeleteBen = () => {
    if (!deletingBen.value) return;
    router.delete(route('admin.scholarships.beneficiaries.destroy', deletingBen.value.id), {
        preserveScroll: true,
        onSuccess: () => { deletingBen.value = null; },
    });
};

// ── Beneficiary Form Modal (Create / Edit) ───────────────────
const showBenModal = ref(false);
const editingBen = ref(null);

const benForm = useForm({
    _method: 'POST',
    academic_period: '',
    title: '',
    description: '',
    scholarship_id: '',
    resolution_number: '',
    publication_date: '',
    sort_order: 0,
    is_active: true,
    file: null,
});

const openCreateBenModal = () => {
    editingBen.value = null;
    benForm.reset();
    benForm.clearErrors();
    benForm._method = 'POST';
    benForm.is_active = true;
    benForm.publication_date = new Date().toISOString().substring(0, 10);
    showBenModal.value = true;
};

const openEditBenModal = (ben) => {
    editingBen.value = ben;
    benForm.clearErrors();
    benForm._method = 'PUT';
    benForm.academic_period = ben.academic_period || '';
    benForm.title = ben.title || '';
    benForm.description = ben.description || '';
    benForm.scholarship_id = ben.scholarship_id || '';
    benForm.resolution_number = ben.resolution_number || '';
    benForm.publication_date = ben.publication_date ? String(ben.publication_date).substring(0, 10) : '';
    benForm.sort_order = ben.sort_order ?? 0;
    benForm.is_active = Boolean(ben.is_active);
    benForm.file = null;
    showBenModal.value = true;
};

const handleBenFile = (e) => {
    benForm.file = e.target.files[0] || null;
};

const submitBenForm = () => {
    if (editingBen.value) {
        benForm.post(route('admin.scholarships.beneficiaries.update', editingBen.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                showBenModal.value = false;
                editingBen.value = null;
            },
        });
    } else {
        benForm.post(route('admin.scholarships.beneficiaries.store'), {
            preserveScroll: true,
            onSuccess: () => {
                showBenModal.value = false;
            },
        });
    }
};

const switchTab = (tab) => {
    currentTab.value = tab;
};
</script>

<template>
    <AdminLayout title="Gestión de Becas y Beneficiarios"
                 subtitle="Administra las modalidades de becas y publica los padrones de beneficiarios aprobados"
                 icon="bi-award"
                 :breadcrumbs="[
                     { label: 'Becas y Créditos' }
                 ]">
        <div class="space-y-6">

            <!-- Tabs Navigation -->
            <div class="flex items-center gap-2 border-b border-slate-200">
                <button @click="switchTab('modalities')"
                        class="px-5 py-3 rounded-t-2xl border-b-2 text-xs font-bold flex items-center gap-2 transition cursor-pointer"
                        :class="currentTab === 'modalities' ? 'border-purple-600 text-purple-700 bg-white shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    <i class="bi bi-award-fill text-sm" :class="currentTab === 'modalities' ? 'text-purple-600' : 'text-slate-400'"></i>
                    <span>Modalidades de Beca</span>
                    <span class="px-2 py-0.5 text-[11px] rounded-full font-extrabold"
                          :class="currentTab === 'modalities' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-600'">
                        {{ totalModalities }}
                    </span>
                </button>
                <button @click="switchTab('beneficiaries')"
                        class="px-5 py-3 rounded-t-2xl border-b-2 text-xs font-bold flex items-center gap-2 transition cursor-pointer"
                        :class="currentTab === 'beneficiaries' ? 'border-purple-600 text-purple-700 bg-white shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    <i class="bi bi-file-earmark-pdf-fill text-sm" :class="currentTab === 'beneficiaries' ? 'text-red-500' : 'text-slate-400'"></i>
                    <span>Padrón de Beneficiarios (PDF)</span>
                    <span class="px-2 py-0.5 text-[11px] rounded-full font-extrabold"
                          :class="currentTab === 'beneficiaries' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-600'">
                        {{ totalBeneficiaries }}
                    </span>
                </button>
            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 1: MODALIDADES DE BECA                                     -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div v-show="currentTab === 'modalities'" class="space-y-6">

                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 sm:p-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 text-lg shrink-0">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Modalidades de Beca</h2>
                                <p class="text-xs text-slate-400">Exoneraciones parciales, totales, primeros puestos y convenios</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a :href="route('becas-y-creditos')" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                <i class="bi bi-box-arrow-up-right"></i> Ver en Portal
                            </a>
                            <Link :href="route('admin.scholarships.create')"
                                  class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl transition shadow-md shadow-purple-500/20 shrink-0">
                                <i class="bi bi-plus-lg"></i> Nueva Modalidad
                            </Link>
                        </div>
                    </div>

                    <!-- Filtros Modalidades -->
                    <div class="p-4 sm:p-5 bg-slate-50/60 border-b border-slate-100 flex flex-col md:flex-row gap-3">
                        <div class="relative flex-1">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" v-model="modSearch" @keyup.enter="filterModalities"
                                   placeholder="Buscar por nombre o descripción..."
                                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500 transition" />
                        </div>
                        <select v-model="modStatus" @change="filterModalities"
                                class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-purple-500">
                            <option value="">Todos los estados</option>
                            <option value="active">Activas</option>
                            <option value="inactive">Inactivas</option>
                        </select>
                        <button @click="filterModalities"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button v-if="modSearch || modStatus" @click="clearModFilters"
                                class="px-3 py-2 bg-white hover:bg-slate-100 text-slate-500 rounded-xl text-xs font-bold border border-slate-200 transition flex items-center justify-center gap-1">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </button>
                    </div>

                    <!-- Tabla de Modalidades -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-400 font-extrabold uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-5 py-3.5">Orden</th>
                                    <th class="px-5 py-3.5">Modalidad / Slug</th>
                                    <th class="px-5 py-3.5">Beneficio / Descuento</th>
                                    <th class="px-5 py-3.5">Plazas</th>
                                    <th class="px-5 py-3.5">Estado</th>
                                    <th class="px-5 py-3.5 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <tr v-for="sch in scholarships.data" :key="sch.id" class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-4 font-bold font-mono text-slate-400">#{{ sch.sort_order }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0">
                                                <i class="bi" :class="sch.icon || 'bi-award'"></i>
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-sm">{{ sch.name }}</div>
                                                <span class="text-[11px] font-mono text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md">
                                                    /{{ sch.slug }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-percent"></i>
                                            {{ Number(sch.discount_percentage) }}% dscto
                                        </span>
                                        <p v-if="sch.discount_details" class="text-[11px] text-slate-400 truncate max-w-xs mt-1">
                                            {{ sch.discount_details }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-4 font-mono font-bold text-slate-800">
                                        {{ sch.vacancies > 0 ? `${sch.vacancies} plazas` : 'Sin límite' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <button @click="toggleModStatus(sch)"
                                                :disabled="togglingModId === sch.id"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition shadow-xs cursor-pointer"
                                                :class="sch.is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200'">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                  :class="sch.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
                                            <span>{{ sch.is_active ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <Link :href="route('admin.scholarships.edit', sch.id)"
                                                  class="p-2 text-purple-600 hover:bg-purple-50 rounded-xl transition" title="Editar">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </Link>
                                            <button @click="confirmDeleteMod(sch)"
                                                    class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Eliminar">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!scholarships.data || scholarships.data.length === 0">
                                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                        <i class="bi bi-award text-3xl mb-2 block"></i>
                                        <p class="font-bold">No se encontraron modalidades de beca</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación Modalidades -->
                    <div v-if="scholarships.links && scholarships.links.length > 3"
                         class="p-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                        <span class="text-xs text-slate-400">
                            Mostrando {{ scholarships.from || 0 }} - {{ scholarships.to || 0 }} de {{ scholarships.total || 0 }}
                        </span>
                        <div class="flex items-center gap-1">
                            <template v-for="(link, i) in scholarships.links" :key="i">
                                <Link v-if="link.url" :href="link.url"
                                      v-html="link.label"
                                      class="px-2.5 py-1 text-xs font-bold rounded-lg transition"
                                      :class="link.active ? 'bg-purple-600 text-white' : 'text-slate-600 hover:bg-slate-100'"></Link>
                                <span v-else v-html="link.label" class="px-2.5 py-1 text-xs text-slate-300"></span>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════════════════════════════════ -->
            <!-- TAB 2: PADRÓN DE BENEFICIARIOS (PDF)                           -->
            <!-- ══════════════════════════════════════════════════════════════ -->
            <div v-show="currentTab === 'beneficiaries'" class="space-y-6">

                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 sm:p-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 text-lg shrink-0">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Padrón Oficial de Beneficiarios (PDF)</h2>
                                <p class="text-xs text-slate-400">Relaciones de alumnos beneficiarios por período académico y resoluciones</p>
                            </div>
                        </div>
                        <button @click="openCreateBenModal"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl transition shadow-md shadow-rose-500/20 shrink-0">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Subir Nuevo Padrón PDF
                        </button>
                    </div>

                    <!-- Filtros Beneficiarios -->
                    <div class="p-4 sm:p-5 bg-slate-50/60 border-b border-slate-100 flex flex-col md:flex-row gap-3">
                        <div class="relative flex-1">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" v-model="benSearch" @keyup.enter="filterBeneficiaries"
                                   placeholder="Buscar por título, resolución o detalle..."
                                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500 transition" />
                        </div>
                        <select v-model="benPeriod" @change="filterBeneficiaries"
                                class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-purple-500">
                            <option value="">Todos los períodos</option>
                            <option v-for="p in periods" :key="p" :value="p">{{ p }}</option>
                        </select>
                        <select v-model="benStatus" @change="filterBeneficiaries"
                                class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-purple-500">
                            <option value="">Todos los estados</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>
                        <button @click="filterBeneficiaries"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button v-if="benSearch || benPeriod || benStatus" @click="clearBenFilters"
                                class="px-3 py-2 bg-white hover:bg-slate-100 text-slate-500 rounded-xl text-xs font-bold border border-slate-200 transition flex items-center justify-center gap-1">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </button>
                    </div>

                    <!-- Tabla de Beneficiarios -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-400 font-extrabold uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-5 py-3.5">Período</th>
                                    <th class="px-5 py-3.5">Título del Padrón / Resolución</th>
                                    <th class="px-5 py-3.5">Modalidad Asociada</th>
                                    <th class="px-5 py-3.5">Documento PDF</th>
                                    <th class="px-5 py-3.5">Estado</th>
                                    <th class="px-5 py-3.5 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                <tr v-for="ben in beneficiaries.data" :key="ben.id" class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="font-extrabold text-sm text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-200">
                                            {{ ben.academic_period }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ ben.title }}</div>
                                        <p v-if="ben.resolution_number" class="text-[11px] font-mono text-slate-500 mt-0.5">
                                            {{ ben.resolution_number }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span v-if="ben.scholarship" class="font-bold text-slate-700 flex items-center gap-1.5">
                                            <i class="bi text-purple-600" :class="ben.scholarship.icon || 'bi-award'"></i>
                                            {{ ben.scholarship.name }}
                                        </span>
                                        <span v-else class="text-slate-400 italic">General / Todas las modalidades</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <a v-if="ben.file_path" :href="`/storage/${ben.file_path}`" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-xs font-bold transition">
                                            <i class="bi bi-file-earmark-pdf-fill text-red-600"></i> Ver PDF
                                        </a>
                                        <span v-else class="text-slate-400 italic text-[11px]">Sin PDF</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <button @click="toggleBenStatus(ben)"
                                                :disabled="togglingBenId === ben.id"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition shadow-xs cursor-pointer"
                                                :class="ben.is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200'">
                                            <span class="w-1.5 h-1.5 rounded-full"
                                                  :class="ben.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
                                            <span>{{ ben.is_active ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="openEditBenModal(ben)"
                                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-xl transition" title="Editar">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </button>
                                            <button @click="confirmDeleteBen(ben)"
                                                    class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Eliminar">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!beneficiaries.data || beneficiaries.data.length === 0">
                                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                        <i class="bi bi-file-earmark-pdf text-3xl mb-2 block"></i>
                                        <p class="font-bold">No se encontraron padrones de beneficiarios</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación Beneficiarios -->
                    <div v-if="beneficiaries.links && beneficiaries.links.length > 3"
                         class="p-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                        <span class="text-xs text-slate-400">
                            Mostrando {{ beneficiaries.from || 0 }} - {{ beneficiaries.to || 0 }} de {{ beneficiaries.total || 0 }}
                        </span>
                        <div class="flex items-center gap-1">
                            <template v-for="(link, i) in beneficiaries.links" :key="i">
                                <Link v-if="link.url" :href="link.url"
                                      v-html="link.label"
                                      class="px-2.5 py-1 text-xs font-bold rounded-lg transition"
                                      :class="link.active ? 'bg-purple-600 text-white' : 'text-slate-600 hover:bg-slate-100'"></Link>
                                <span v-else v-html="link.label" class="px-2.5 py-1 text-xs text-slate-300"></span>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- MODAL: CREAR / EDITAR BENEFICIARIO                             -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div v-if="showBenModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-5 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <i class="bi bi-file-earmark-pdf-fill text-red-500"></i>
                        {{ editingBen ? 'Editar Padrón de Beneficiarios' : 'Subir Nuevo Padrón PDF' }}
                    </h3>
                    <button @click="showBenModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitBenForm" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                Período Académico <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" v-model="benForm.academic_period" placeholder="Ej: 2026-I" required
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-purple-500" />
                            <p v-if="benForm.errors.academic_period" class="text-rose-500 text-xs">{{ benForm.errors.academic_period }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                Modalidad Asociada
                            </label>
                            <select v-model="benForm.scholarship_id"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-purple-500">
                                <option value="">-- General / Todas --</option>
                                <option v-for="s in allScholarships" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                            Título del Padrón <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" v-model="benForm.title" placeholder="Ej: Padrón Oficial de Beneficiarios 2026-I" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-purple-500" />
                        <p v-if="benForm.errors.title" class="text-rose-500 text-xs">{{ benForm.errors.title }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                N° Resolución
                            </label>
                            <input type="text" v-model="benForm.resolution_number" placeholder="Ej: R.D. N° 045-2026"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-purple-500" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                Fecha Publicación
                            </label>
                            <input type="date" v-model="benForm.publication_date"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-purple-500" />
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                            Archivo PDF del Padrón <span v-if="!editingBen" class="text-rose-500">*</span>
                        </label>
                        <input type="file" accept="application/pdf" @change="handleBenFile"
                               class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer" />
                        <p v-if="editingBen" class="text-[10px] text-slate-400">Deja en blanco para conservar el archivo PDF actual.</p>
                        <p v-if="benForm.errors.file" class="text-rose-500 text-xs">{{ benForm.errors.file }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                            Descripción / Observaciones
                        </label>
                        <textarea v-model="benForm.description" rows="2" placeholder="Notas sobre el padrón..."
                                  class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" v-model="benForm.is_active"
                                   class="w-4 h-4 text-purple-600 bg-slate-100 border-slate-300 rounded focus:ring-purple-500" />
                            <span class="text-xs font-bold text-slate-800">Publicar inmediatamente este padrón</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showBenModal = false"
                                class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="benForm.processing"
                                class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition shadow-md shadow-purple-500/20 disabled:opacity-50">
                            {{ benForm.processing ? 'Guardando...' : (editingBen ? 'Actualizar Padrón' : 'Subir Padrón') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Confirmación Eliminar Modalidad -->
        <div v-if="deletingMod" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-base font-extrabold text-slate-900">¿Eliminar modalidad de beca?</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Se eliminará la modalidad <strong>{{ deletingMod.name }}</strong>. Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="deletingMod = null"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancelar
                    </button>
                    <button @click="executeDeleteMod"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md shadow-rose-500/20">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Confirmación Eliminar Beneficiario -->
        <div v-if="deletingBen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto">
                    <i class="bi bi-trash"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-base font-extrabold text-slate-900">¿Eliminar este padrón de beneficiarios?</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Se eliminará el documento <strong>{{ deletingBen.title }}</strong> y su archivo PDF del servidor.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="deletingBen = null"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancelar
                    </button>
                    <button @click="executeDeleteBen"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md shadow-rose-500/20">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>
