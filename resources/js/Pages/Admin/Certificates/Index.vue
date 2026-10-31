<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    certificates: {
        type: Object,
        required: true,
    },
    courses: {
        type: Array,
        default: () => [],
    },
    totalCertificates: {
        type: Number,
        default: 0,
    },
    activeCertificates: {
        type: Number,
        default: 0,
    },
    presencialCount: {
        type: Number,
        default: 0,
    },
    virtualSemipresCount: {
        type: Number,
        default: 0,
    },
    totalDownloads: {
        type: Number,
        default: 0,
    },
    issuedCoursesCount: {
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
    userId: {
        type: [String, Number],
        default: '',
    },
    modality: {
        type: String,
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
});

// Filters
const filterSearch = ref(props.search);
const filterCourseId = ref(props.courseId ? String(props.courseId) : '');
const filterModality = ref(props.modality || '');
const filterStatus = ref(props.status !== null && props.status !== undefined ? String(props.status) : '');

const applyFilters = () => {
    router.get(
        route('admin.certificates.index'),
        {
            search: filterSearch.value || undefined,
            course_id: filterCourseId.value || undefined,
            modality: filterModality.value || undefined,
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
    filterModality.value = '';
    filterStatus.value = '';
    router.get(route('admin.certificates.index'), {}, { preserveState: true });
};

// Selection for bulk actions
const selectedCerts = ref([]);
const selectAll = ref(false);

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedCerts.value = props.certificates.data?.map(c => c.id) || [];
    } else {
        selectedCerts.value = [];
    }
};

watch(
    () => props.certificates.data,
    () => {
        selectedCerts.value = [];
        selectAll.value = false;
    }
);

// ── CREATE / EDIT CERTIFICATE MODAL ──
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingCertId = ref(null);

const form = useForm({
    user_id: '',
    course_id: '',
    certificate_type: 'capacitacion',
    participation_type: 'ASISTENTE',
    event_name: '',
    certificate_code: '',
    modality: 'Presencial',
    duration: '',
    start_date: '',
    end_date: '',
    issue_date: new Date().toISOString().slice(0, 10),
    description: '',
    is_active: true,
});

// Student autocomplete
const studentSearch = ref('');
const studentResults = ref([]);
const isSearchingStudents = ref(false);
const studentDropdownOpen = ref(false);
const selectedStudent = ref(null);
let searchDebounceTimer = null;

const onStudentSearchInput = () => {
    clearTimeout(searchDebounceTimer);
    if (!studentSearch.value.trim()) {
        studentResults.value = [];
        isSearchingStudents.value = false;
        return;
    }
    isSearchingStudents.value = true;
    searchDebounceTimer = setTimeout(async () => {
        try {
            const resp = await fetch(route('admin.certificates.search-students') + '?q=' + encodeURIComponent(studentSearch.value.trim()));
            if (resp.ok) {
                studentResults.value = await resp.json();
            }
        } catch (err) {
            console.error('Error searching students:', err);
        } finally {
            isSearchingStudents.value = false;
        }
    }, 250);
};

const selectStudent = (student) => {
    selectedStudent.value = student;
    form.user_id = student.id;
    studentDropdownOpen.value = false;
    studentSearch.value = '';
};

const clearStudentSelection = () => {
    selectedStudent.value = null;
    form.user_id = '';
    studentSearch.value = '';
    studentResults.value = [];
};

const generateCode = () => {
    const dni = selectedStudent.value?.dni || Math.floor(10000000 + Math.random() * 90000000);
    const rand = Math.floor(1 + Math.random() * 999);
    form.certificate_code = `CERT-${dni}-${rand}`;
};

const openCreateModal = () => {
    isEditing.value = false;
    editingCertId.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    form.participation_type = 'ASISTENTE';
    form.modality = 'Presencial';
    form.certificate_type = 'capacitacion';
    form.issue_date = new Date().toISOString().slice(0, 10);
    clearStudentSelection();
    isModalOpen.value = true;
};

const openEditModal = (cert) => {
    isEditing.value = true;
    editingCertId.value = cert.id;
    form.clearErrors();
    form.user_id = cert.user_id || '';
    form.course_id = cert.course_id || '';
    form.certificate_type = cert.certificate_type || 'capacitacion';
    form.participation_type = cert.participation_type || 'ASISTENTE';
    form.event_name = cert.event_name || '';
    form.certificate_code = cert.certificate_code || cert.code || '';
    form.modality = cert.modality || 'Presencial';
    form.duration = cert.duration || '';
    form.start_date = cert.start_date ? cert.start_date.slice(0, 10) : '';
    form.end_date = cert.end_date ? cert.end_date.slice(0, 10) : '';
    form.issue_date = cert.issue_date ? cert.issue_date.slice(0, 10) : '';
    form.description = cert.description || '';
    form.is_active = Boolean(cert.is_active);

    if (cert.user) {
        selectedStudent.value = {
            id: cert.user.id,
            names: cert.user.names,
            dni: cert.user.dni,
            email: cert.user.email,
            phone: cert.user.phone,
        };
    } else {
        selectedStudent.value = null;
    }

    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
    clearStudentSelection();
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('admin.certificates.update', editingCertId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('admin.certificates.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};

// ── MODULE SCORES / DETAILS MODAL ──
const detailsModalOpen = ref(false);
const activeCert = ref(null);
const detailForm = useForm({
    module_id: '',
    score: '',
});
const isSavingDetail = ref(false);

const openDetailsModal = async (cert) => {
    activeCert.value = cert;
    detailForm.reset();
    detailForm.clearErrors();
    detailsModalOpen.value = true;

    // Refresh certificate details
    try {
        const resp = await fetch(route('admin.certificates.show', cert.id));
        if (resp.ok) {
            activeCert.value = await resp.json();
        }
    } catch (e) {
        console.error('Error fetching certificate details:', e);
    }
};

const availableModules = computed(() => {
    if (!activeCert.value || !activeCert.value.course) return [];
    return activeCert.value.course.modules || [];
});

const getExistingScore = (moduleId) => {
    if (!activeCert.value || !activeCert.value.details) return null;
    const found = activeCert.value.details.find(d => String(d.module_id) === String(moduleId));
    return found ? found.score : null;
};

const onDetailModuleChange = () => {
    const existing = getExistingScore(detailForm.module_id);
    if (existing) {
        detailForm.score = existing;
    }
};

const submitDetailForm = () => {
    if (!activeCert.value) return;
    detailForm.post(route('admin.certificates.details.store', activeCert.value.id), {
        preserveScroll: true,
        onSuccess: async () => {
            detailForm.reset();
            const resp = await fetch(route('admin.certificates.show', activeCert.value.id));
            if (resp.ok) {
                activeCert.value = await resp.json();
            }
        },
    });
};

const editDetail = (detail) => {
    detailForm.module_id = String(detail.module_id);
    detailForm.score = detail.score;
};

const deleteDetail = (detailId) => {
    if (!confirm('¿Eliminar esta calificación?')) return;
    router.delete(route('admin.certificates.details.destroy', detailId), {
        preserveScroll: true,
        onSuccess: async () => {
            if (activeCert.value) {
                const resp = await fetch(route('admin.certificates.show', activeCert.value.id));
                if (resp.ok) {
                    activeCert.value = await resp.json();
                }
            }
        },
    });
};

// ── BULK IMPORT MODAL ──
const importModalOpen = ref(false);
const importTab = ref('upload');
const importFileName = ref('');
const importForm = useForm({
    file: null,
});

const openImportModal = () => {
    importTab.value = 'upload';
    importFileName.value = '';
    importForm.reset();
    importForm.clearErrors();
    importModalOpen.value = true;
};

const onImportFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        importFileName.value = file.name;
        importForm.file = file;
    }
};

const submitImport = () => {
    if (!importForm.file) return;
    importForm.post(route('admin.certificates.import'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            importModalOpen.value = false;
            importForm.reset();
            importFileName.value = '';
        },
    });
};

// ── BULK PDF EXPORT MODAL ──
const bulkPdfModalOpen = ref(false);
const pdfSelectedCourseIds = ref(['all']);
const pdfCourseFilterText = ref('');
const pdfFaces = ref('all');
const pdfModality = ref('all');
const pdfParticipationType = ref('all');
const pdfOrderBy = ref('names_asc');
const pdfDateFrom = ref('');
const pdfDateTo = ref('');

const openBulkPdfModal = () => {
    pdfSelectedCourseIds.value = ['all'];
    pdfCourseFilterText.value = '';
    pdfFaces.value = 'all';
    pdfModality.value = 'all';
    pdfParticipationType.value = 'all';
    pdfOrderBy.value = 'names_asc';
    pdfDateFrom.value = '';
    pdfDateTo.value = '';
    bulkPdfModalOpen.value = true;
};

const togglePdfCourse = (courseId) => {
    if (courseId === 'all') {
        if (pdfSelectedCourseIds.value.includes('all')) {
            pdfSelectedCourseIds.value = [];
        } else {
            pdfSelectedCourseIds.value = ['all'];
        }
        return;
    }

    // Unselect 'all' if specific course selected
    pdfSelectedCourseIds.value = pdfSelectedCourseIds.value.filter(id => id !== 'all');

    const index = pdfSelectedCourseIds.value.indexOf(String(courseId));
    if (index > -1) {
        pdfSelectedCourseIds.value.splice(index, 1);
    } else {
        pdfSelectedCourseIds.value.push(String(courseId));
    }

    if (pdfSelectedCourseIds.value.length === 0) {
        pdfSelectedCourseIds.value = ['all'];
    }
};

const isPdfCourseSelected = (courseId) => {
    return pdfSelectedCourseIds.value.includes('all') || pdfSelectedCourseIds.value.includes(String(courseId));
};

const selectAllPdfCourses = () => {
    pdfSelectedCourseIds.value = ['all'];
};

const deselectAllPdfCourses = () => {
    pdfSelectedCourseIds.value = [];
};

// ── DELETE MODALS ──
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);
const isDeleting = ref(false);

const confirmDelete = (cert) => {
    itemToDelete.value = cert;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (!itemToDelete.value) return;
    isDeleting.value = true;
    router.delete(route('admin.certificates.destroy', itemToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value = false;
            isDeleteModalOpen.value = false;
            itemToDelete.value = null;
        },
    });
};

// Bulk Delete Selected
const bulkDeleteModalOpen = ref(false);
const isBulkDeleting = ref(false);

const confirmBulkDelete = () => {
    if (selectedCerts.value.length === 0) return;
    bulkDeleteModalOpen.value = true;
};

const executeBulkDelete = () => {
    if (selectedCerts.value.length === 0) return;
    isBulkDeleting.value = true;
    router.post(
        route('admin.certificates.bulk-delete'),
        { ids: selectedCerts.value },
        {
            preserveScroll: true,
            onFinish: () => {
                isBulkDeleting.value = false;
                bulkDeleteModalOpen.value = false;
                selectedCerts.value = [];
                selectAll.value = false;
            },
        }
    );
};

// Delete All
const deleteAllModalOpen = ref(false);
const isDeletingAll = ref(false);

const executeDeleteAll = () => {
    isDeletingAll.value = true;
    router.delete(route('admin.certificates.delete-all'), {
        preserveScroll: true,
        onFinish: () => {
            isDeletingAll.value = false;
            deleteAllModalOpen.value = false;
            selectedCerts.value = [];
        },
    });
};

// Toggle active status
const togglingId = ref(null);
const toggleStatus = (cert) => {
    togglingId.value = cert.id;
    router.patch(
        route('admin.certificates.toggle-status', cert.id),
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
    <AdminLayout title="Gestión de Certificados">
        <Head title="Gestión de Certificados — Panel Administrativo" />

        <div class="space-y-6">
            <!-- ── Header & Sub-Navigation Tabs ── -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight leading-none flex items-center gap-2">
                                Certificados Emitidos
                            </h1>
                            <p class="text-xs text-gray-500 font-medium mt-1">
                                Emisión, validación y gestión de notas modulares de certificados
                            </p>
                        </div>
                    </div>

                    <!-- Quick Actions Buttons -->
                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            @click="openCreateModal"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition-all"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Nuevo Certificado</span>
                        </button>
                        <button
                            type="button"
                            @click="openImportModal"
                            class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all"
                        >
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                            <span>Importar Excel/CSV</span>
                        </button>
                        <button
                            type="button"
                            @click="openBulkPdfModal"
                            class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all"
                        >
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                            <span>Exportar PDF Masivo</span>
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
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-purple-600 text-purple-700 whitespace-nowrap"
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
                        class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap"
                    >
                        <i class="bi bi-diagram-3"></i> Itinerarios
                    </Link>
                </div>
            </div>

            <!-- ── Stat Cards ── -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Certificados</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ Number(totalCertificates).toLocaleString() }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Certificados Válidos</p>
                        <h3 class="text-2xl font-black text-emerald-700">{{ Number(activeCertificates).toLocaleString() }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Presencial</p>
                        <h3 class="text-2xl font-black text-blue-700">{{ Number(presencialCount).toLocaleString() }}</h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Descargas</p>
                        <h3 class="text-2xl font-black text-amber-700">{{ Number(totalDownloads).toLocaleString() }}</h3>
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
                            placeholder="Buscar por código, estudiante, DNI, curso..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium"
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

                    <!-- Modality Filter -->
                    <div class="lg:col-span-2">
                        <select
                            v-model="filterModality"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium text-gray-700"
                        >
                            <option value="">Modalidad: Todas</option>
                            <option value="Presencial">Presencial</option>
                            <option value="Semipresencial">Semipresencial</option>
                            <option value="Virtual">Virtual</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="lg:col-span-1">
                        <select
                            v-model="filterStatus"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-2 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium text-gray-700"
                        >
                            <option value="">Estado</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition shadow-sm flex items-center justify-center gap-1.5 flex-1"
                        >
                            <i class="bi bi-funnel-fill"></i> Filtrar
                        </button>
                        <button
                            v-if="filterSearch || filterCourseId || filterModality || filterStatus !== ''"
                            type="button"
                            @click="clearFilters"
                            class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition"
                            title="Limpiar Filtros"
                        >
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </form>

                <!-- Bulk Selection Action Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-gray-500">
                            Mostrando {{ certificates.total || certificates.data?.length || 0 }} certificados registrados
                        </span>
                        <div v-if="selectedCerts.length > 0" class="flex items-center gap-2">
                            <span class="text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-md">
                                {{ selectedCerts.length }} seleccionados
                            </span>
                            <button
                                type="button"
                                @click="confirmBulkDelete"
                                class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-bold transition flex items-center gap-1"
                            >
                                <i class="bi bi-trash-fill"></i> Eliminar seleccionados
                            </button>
                        </div>
                    </div>

                    <button
                        v-if="totalCertificates > 0"
                        type="button"
                        @click="deleteAllModalOpen = true"
                        class="text-xs font-bold text-gray-400 hover:text-red-600 transition flex items-center gap-1"
                    >
                        <i class="bi bi-trash3"></i> Vaciar todos los certificados
                    </button>
                </div>
            </div>

            <!-- ── Table ── -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div v-if="!certificates.data || certificates.data.length === 0" class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                    <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-400 flex items-center justify-center text-3xl">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-gray-700">Sin certificados registrados</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span v-if="filterSearch || filterCourseId || filterModality || filterStatus !== ''">
                                No se encontraron registros con los filtros aplicados.
                                <button type="button" @click="clearFilters" class="text-purple-600 font-semibold hover:underline">
                                    Limpiar filtros
                                </button>
                            </span>
                            <span v-else>
                                Emita un nuevo certificado con el botón <strong>Nuevo Certificado</strong> o cargue una lista en Excel.
                            </span>
                        </p>
                    </div>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-center w-10">
                                    <input
                                        v-model="selectAll"
                                        @change="toggleSelectAll"
                                        type="checkbox"
                                        class="rounded text-purple-600 focus:ring-purple-500"
                                    />
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Curso / Evento</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Modalidad / Fecha</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Descargas</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="cert in certificates.data" :key="cert.id" class="hover:bg-gray-50/80 transition-colors">
                                <!-- Checkbox -->
                                <td class="px-4 py-3 text-center">
                                    <input
                                        v-model="selectedCerts"
                                        :value="cert.id"
                                        type="checkbox"
                                        class="rounded text-purple-600 focus:ring-purple-500"
                                    />
                                </td>

                                <!-- Student -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ (cert.user?.names || 'U').charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="truncate max-w-[200px]">
                                            <div class="font-bold text-gray-900 text-xs truncate" :title="cert.user?.names">
                                                {{ cert.user?.names || '—' }}
                                            </div>
                                            <div class="text-[11px] text-gray-500 flex items-center gap-1.5 mt-0.5">
                                                <span class="font-semibold text-purple-700">DNI: {{ cert.user?.dni || 'S/D' }}</span>
                                                <span v-if="cert.participation_type" class="text-[10px] bg-purple-50 text-purple-700 font-bold px-1.5 py-0.2 rounded">
                                                    {{ cert.participation_type }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Course -->
                                <td class="px-4 py-3">
                                    <div class="max-w-[220px]">
                                        <div class="font-bold text-gray-800 text-xs truncate" :title="cert.course?.name">
                                            {{ cert.course?.name || '—' }}
                                        </div>
                                        <div class="text-[11px] text-gray-400 truncate">
                                            {{ cert.event_name || cert.course?.study_program?.name || 'Capacitación' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Code -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-purple-800 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200">
                                        {{ cert.certificate_code || cert.code || '—' }}
                                    </span>
                                </td>

                                <!-- Modality & Dates -->
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span
                                        class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold"
                                        :class="{
                                            'bg-blue-100 text-blue-800': cert.modality === 'Presencial',
                                            'bg-indigo-100 text-indigo-800': cert.modality === 'Virtual',
                                            'bg-amber-100 text-amber-800': cert.modality === 'Semipresencial',
                                        }"
                                    >
                                        {{ cert.modality }}
                                    </span>
                                    <div class="text-[11px] text-gray-500 font-medium mt-0.5">
                                        {{ cert.issue_date ? cert.issue_date.slice(0, 10) : '—' }}
                                    </div>
                                </td>

                                <!-- Downloads -->
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">
                                        <i class="bi bi-download text-[10px]"></i>
                                        <span>{{ cert.download_count || 0 }}</span>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <button
                                        type="button"
                                        @click="toggleStatus(cert)"
                                        :disabled="togglingId === cert.id"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition cursor-pointer disabled:opacity-50"
                                        :class="cert.is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        title="Clic para cambiar estado"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" :class="cert.is_active ? 'bg-emerald-500' : 'bg-gray-400'"></span>
                                        <span v-if="togglingId === cert.id">
                                            <i class="bi bi-arrow-repeat animate-spin"></i>
                                        </span>
                                        <span v-else>
                                            {{ cert.is_active ? 'Válido' : 'Inactivo' }}
                                        </span>
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1">
                                        <!-- Print / View Certificate in new tab -->
                                        <a
                                            :href="route('admin.certificates.print', cert.id)"
                                            target="_blank"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                            title="Ver / Imprimir Certificado Oficial"
                                        >
                                            <i class="bi bi-printer text-base"></i>
                                        </a>

                                        <!-- Module Details / Scores -->
                                        <button
                                            type="button"
                                            @click="openDetailsModal(cert)"
                                            class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                            title="Notas Modulares"
                                        >
                                            <i class="bi bi-card-checklist text-base"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button
                                            type="button"
                                            @click="openEditModal(cert)"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Editar Certificado"
                                        >
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>

                                        <!-- Delete -->
                                        <button
                                            type="button"
                                            @click="confirmDelete(cert)"
                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition"
                                            title="Eliminar Certificado"
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
                    v-if="certificates.links && certificates.links.length > 3"
                    class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3"
                >
                    <p class="text-xs text-gray-500">
                        Mostrando
                        <strong>{{ certificates.from || 1 }}</strong>–<strong>{{ certificates.to || certificates.data.length }}</strong>
                        de <strong>{{ certificates.total }}</strong> certificados
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in certificates.links"
                            :key="i"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition"
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

        <!-- ══ MODAL CREATE / EDIT CERTIFICATE ═════════════════════════════ -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
        >
            <div
                @click.stop
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden animate-scale-up"
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i :class="isEditing ? 'bi bi-pencil-square' : 'bi bi-patch-check-fill'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">
                                {{ isEditing ? 'Editar Certificado' : 'Nuevo Certificado' }}
                            </h3>
                            <p class="text-xs text-gray-500">Emisión de certificado con código único y control anti-duplicados</p>
                        </div>
                    </div>
                    <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <!-- Validation Errors -->
                    <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border-l-4 border-red-500 p-3 rounded-xl">
                        <p class="text-xs font-bold text-red-800 mb-1">Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                            <li v-for="(err, f) in form.errors" :key="f">{{ err }}</li>
                        </ul>
                    </div>

                    <!-- Row 1: Student Search & Course -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Student Autocomplete Search -->
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>Estudiante / Usuario <span class="text-red-500">*</span></span>
                                <span class="text-[10px] text-purple-600 font-semibold lowercase">búsqueda rápida</span>
                            </label>

                            <button
                                type="button"
                                @click="studentDropdownOpen = !studentDropdownOpen"
                                class="w-full text-left text-sm border rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium flex items-center justify-between transition shadow-sm cursor-pointer"
                                :class="selectedStudent ? 'border-purple-300 bg-purple-50/20' : 'border-gray-300'"
                            >
                                <div class="flex items-center gap-2 overflow-hidden truncate">
                                    <template v-if="selectedStudent">
                                        <div class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                            {{ (selectedStudent.names || 'U').charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="truncate text-xs font-bold text-gray-900">
                                            {{ selectedStudent.names }}
                                            <span class="text-gray-400 font-normal">({{ selectedStudent.dni || 'S/D' }})</span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <span class="text-gray-400 text-xs flex items-center gap-1.5">
                                            <i class="bi bi-search"></i> Buscar por nombre o DNI...
                                        </span>
                                    </template>
                                </div>
                                <div class="flex items-center gap-1 shrink-0 ml-1">
                                    <span v-if="selectedStudent" role="button" @click.stop="clearStudentSelection" class="p-0.5 text-gray-400 hover:text-red-500">
                                        <i class="bi bi-x-lg text-xs"></i>
                                    </span>
                                    <i class="bi bi-chevron-expand text-gray-400 text-xs"></i>
                                </div>
                            </button>

                            <!-- Dropdown Panel -->
                            <div
                                v-if="studentDropdownOpen"
                                class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden divide-y divide-gray-100"
                            >
                                <div class="p-2.5 bg-gray-50">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input
                                            v-model="studentSearch"
                                            @input="onStudentSearchInput"
                                            type="text"
                                            placeholder="Escriba nombre o DNI..."
                                            class="w-full pl-8 pr-4 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white"
                                            autofocus
                                        />
                                    </div>
                                </div>

                                <ul class="max-h-52 overflow-y-auto divide-y divide-gray-50 py-1 text-xs">
                                    <li v-if="isSearchingStudents" class="p-4 text-center text-purple-600 flex items-center justify-center gap-2">
                                        <i class="bi bi-arrow-repeat animate-spin"></i> Buscando...
                                    </li>
                                    <li v-else-if="studentSearch && studentResults.length === 0" class="p-4 text-center text-gray-400">
                                        Sin coincidencias encontradas.
                                    </li>
                                    <li v-else-if="!studentSearch" class="p-4 text-center text-gray-400">
                                        Escriba al menos 2 caracteres para buscar.
                                    </li>
                                    <li v-for="st in studentResults" :key="st.id">
                                        <button
                                            type="button"
                                            @click="selectStudent(st)"
                                            class="w-full px-3 py-2 text-left hover:bg-purple-50 flex items-center justify-between transition cursor-pointer"
                                        >
                                            <div>
                                                <div class="font-bold text-gray-900 text-xs">{{ st.names }}</div>
                                                <div class="text-[11px] text-gray-500">DNI: {{ st.dni || 'S/D' }} • {{ st.email || '' }}</div>
                                            </div>
                                            <i v-if="form.user_id == st.id" class="bi bi-check2 text-purple-600 font-bold"></i>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <p v-if="form.errors.user_id" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.user_id }}</p>
                        </div>

                        <!-- Course Select -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Curso <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.course_id"
                                required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium"
                                :class="{ 'border-red-500': form.errors.course_id }"
                            >
                                <option value="">-- Seleccione curso --</option>
                                <option v-for="c in courses" :key="c.id" :value="c.id">
                                    {{ c.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.course_id" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.course_id }}</p>
                        </div>
                    </div>

                    <!-- Row 2: Format, Participation, Event -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Formato Certificado
                            </label>
                            <select
                                v-model="form.certificate_type"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium"
                            >
                                <option value="capacitacion">Capacitación Estándar</option>
                                <option value="oficina_4_0">Oficina 4.0</option>
                                <option value="modular">Modular (Notas/Créditos)</option>
                                <option value="ingles">Inglés Básico</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Condición / Rol
                            </label>
                            <input
                                v-model="form.participation_type"
                                type="text"
                                placeholder="Ej: ASISTENTE, PONENTE"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold uppercase"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Evento / Marco
                            </label>
                            <input
                                v-model="form.event_name"
                                type="text"
                                placeholder="Ej: Semana Técnica 2026"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold"
                            />
                        </div>
                    </div>

                    <!-- Row 3: Code, Modality, Duration -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    v-model="form.certificate_code"
                                    type="text"
                                    required
                                    placeholder="Ej: CERT-2026-001"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2 pl-3 pr-16 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase font-mono font-bold"
                                    :class="{ 'border-red-500': form.errors.certificate_code }"
                                />
                                <button
                                    type="button"
                                    @click="generateCode"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] font-bold text-purple-600 hover:text-purple-800 bg-purple-50 px-1.5 py-0.5 rounded cursor-pointer"
                                >
                                    Generar
                                </button>
                            </div>
                            <p v-if="form.errors.certificate_code" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.certificate_code }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Modalidad <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.modality"
                                required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium"
                            >
                                <option value="Presencial">Presencial</option>
                                <option value="Semipresencial">Semipresencial</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Duración / Horas
                            </label>
                            <input
                                v-model="form.duration"
                                type="text"
                                placeholder="Ej: 120 Horas"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium"
                            />
                        </div>
                    </div>

                    <!-- Row 4: Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Inicio
                            </label>
                            <input
                                v-model="form.start_date"
                                type="date"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Fin
                            </label>
                            <input
                                v-model="form.end_date"
                                type="date"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Emisión <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.issue_date"
                                type="date"
                                required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-bold"
                            />
                        </div>
                    </div>

                    <!-- Row 5: Description -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Descripción / Mención
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="2"
                            maxlength="500"
                            placeholder="Por haber aprobado satisfactoriamente el curso..."
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium"
                        ></textarea>
                    </div>

                    <!-- Active Toggle -->
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Certificado Válido / Activo</h4>
                            <p class="text-xs text-gray-500">Determina si es verificable públicamente mediante el código QR.</p>
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

                    <!-- Footer Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <span>{{ isEditing ? 'Guardar Cambios' : 'Emitir Certificado' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══ MODAL MODULE SCORES (DETAILS) ═══════════════════════════════ -->
        <div
            v-if="detailsModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
        >
            <div
                @click.stop
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden animate-scale-up"
            >
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Calificaciones por Módulo</h3>
                            <p class="text-xs text-gray-500">Certificado: {{ activeCert?.certificate_code || activeCert?.code }}</p>
                        </div>
                    </div>
                    <button type="button" @click="detailsModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5 overflow-y-auto flex-1">
                    <!-- Summary Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-gray-500 font-semibold block">Estudiante:</span>
                            <span class="font-bold text-gray-800 text-sm">{{ activeCert?.user?.names || '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Curso:</span>
                            <span class="font-bold text-purple-700 text-sm">{{ activeCert?.course?.name || '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Emisión:</span>
                            <span class="font-bold text-gray-700">{{ activeCert?.issue_date ? activeCert.issue_date.slice(0, 10) : '—' }}</span>
                        </div>
                    </div>

                    <!-- Assign Score Form -->
                    <form @submit.prevent="submitDetailForm" class="bg-purple-50/50 border border-purple-200 rounded-xl p-4 space-y-3">
                        <div class="text-xs font-bold text-purple-900 uppercase tracking-wider flex items-center justify-between">
                            <span>Asignar / Actualizar Nota de Módulo</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-7">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Módulo del Curso <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="detailForm.module_id"
                                    @change="onDetailModuleChange"
                                    required
                                    class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 bg-white font-medium"
                                >
                                    <option value="">-- Seleccionar Módulo --</option>
                                    <option v-for="mod in availableModules" :key="mod.id" :value="String(mod.id)">
                                        {{ mod.name }} {{ mod.credits ? `(${mod.credits} cr)` : '' }}
                                    </option>
                                </select>
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Calificación / Nota <span class="text-red-500">*</span></label>
                                <input
                                    v-model="detailForm.score"
                                    type="text"
                                    required
                                    placeholder="Ej: 18, Aprobado"
                                    class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 font-bold"
                                />
                            </div>

                            <div class="sm:col-span-2">
                                <button
                                    type="submit"
                                    :disabled="detailForm.processing"
                                    class="w-full py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold shadow transition flex items-center justify-center gap-1 cursor-pointer disabled:opacity-50"
                                >
                                    <i v-if="detailForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Guardar</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Existing Scores Table -->
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Módulos Registrados</h4>
                        <div v-if="!activeCert?.details || activeCert.details.length === 0" class="p-6 text-center text-xs text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                            No se han registrado notas modulares para este certificado aún.
                        </div>
                        <div v-else class="border border-gray-200 rounded-xl overflow-hidden">
                            <table class="min-w-full text-xs">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-bold text-gray-600 uppercase">Módulo</th>
                                        <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Créditos</th>
                                        <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Calificación</th>
                                        <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="d in activeCert.details" :key="d.id" class="hover:bg-gray-50/80">
                                        <td class="px-3 py-2 font-semibold text-gray-800">{{ d.module?.name || `Módulo #${d.module_id}` }}</td>
                                        <td class="px-3 py-2 text-center text-gray-500">{{ d.module?.credits || '—' }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <span class="px-2 py-0.5 rounded-full font-bold bg-purple-100 text-purple-800">{{ d.score || 'Aprobado' }}</span>
                                        </td>
                                        <td class="px-3 py-2 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-1">
                                                <button
                                                    type="button"
                                                    @click="editDetail(d)"
                                                    class="text-indigo-600 hover:bg-indigo-50 p-1 rounded transition"
                                                    title="Editar nota"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    @click="deleteDetail(d.id)"
                                                    class="text-red-500 hover:bg-red-50 p-1 rounded transition"
                                                    title="Eliminar nota"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-end shrink-0">
                    <button
                        type="button"
                        @click="detailsModalOpen = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs font-bold transition"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- ══ MODAL BULK IMPORT EXCEL / CSV ═══════════════════════════════ -->
        <div
            v-if="importModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in"
        >
            <div
                @click.stop
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden animate-scale-up"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-700 text-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-md text-white flex items-center justify-center text-2xl shadow-inner border border-white/20">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold tracking-tight">Importación Masiva de Certificados</h3>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-400/30 text-emerald-100 border border-emerald-300/30">
                                    XLSX • XLS • CSV
                                </span>
                            </div>
                            <p class="text-xs text-emerald-100/90">Carga masiva, registro de notas modulares y control anti-duplicados</p>
                        </div>
                    </div>
                    <button type="button" @click="importModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex items-center gap-2 px-6 pt-3 border-b border-gray-200 bg-gray-50/80 shrink-0">
                    <button
                        type="button"
                        @click="importTab = 'upload'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'upload' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    >
                        <i class="bi bi-cloud-arrow-up text-sm"></i> 1. Cargar Archivo y Plantillas
                    </button>
                    <button
                        type="button"
                        @click="importTab = 'guide'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'guide' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    >
                        <i class="bi bi-card-list text-sm"></i> 2. Guía de Columnas (A–P)
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-5 overflow-y-auto flex-1 bg-white">
                    <!-- Tab 1: Upload -->
                    <div v-show="importTab === 'upload'" class="space-y-5">
                        <!-- Template Banner -->
                        <div class="p-5 bg-gradient-to-br from-indigo-50/90 via-purple-50/50 to-emerald-50/60 border border-indigo-100 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-100/80 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                    <i class="bi bi-file-earmark-check"></i> Plantilla Guía Oficial
                                </span>
                                <h4 class="text-sm font-bold text-gray-900">Descarga la plantilla con la estructura exacta</h4>
                                <p class="text-xs text-gray-600 max-w-xl">
                                    Incluye los 16 campos oficiales (A–P), filas de ejemplo ilustrando la <strong>Condición / Participación</strong> (ASISTENTE, PONENTE, etc.) y código autogenerable.
                                </p>
                            </div>
                            <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                                <a
                                    :href="route('admin.certificates.template', { format: 'xlsx' })"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-sm transition"
                                >
                                    <i class="bi bi-file-earmark-excel-fill text-base"></i>
                                    <span>Descargar Excel (.xlsx)</span>
                                </a>
                                <a
                                    :href="route('admin.certificates.template', { format: 'csv' })"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold shadow-sm transition"
                                >
                                    <i class="bi bi-filetype-csv text-base text-gray-500"></i>
                                    <span>Descargar CSV (.csv)</span>
                                </a>
                            </div>
                        </div>

                        <!-- Dropzone -->
                        <form @submit.prevent="submitImport" class="space-y-4">
                            <label
                                class="flex flex-col items-center justify-center w-full border-2 border-dashed rounded-2xl cursor-pointer transition-all p-8"
                                :class="importFileName ? 'border-emerald-500 bg-emerald-50/50' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'"
                            >
                                <div class="text-center space-y-3">
                                    <div v-if="!importFileName" class="space-y-2">
                                        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl shadow-sm">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">
                                            Arrastra tu archivo aquí o <span class="text-emerald-600 underline">haz clic para examinar</span>
                                        </p>
                                        <p class="text-xs text-gray-400">Formatos soportados: .XLSX, .XLS, .CSV (Máximo 10 MB)</p>
                                    </div>
                                    <div v-else class="space-y-2">
                                        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-3xl shadow-md">
                                            <i class="bi bi-file-earmark-check-fill"></i>
                                        </div>
                                        <p class="text-sm font-extrabold text-emerald-900">{{ importFileName }}</p>
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full">
                                            <i class="bi bi-check-circle-fill text-[11px]"></i> Archivo preparado
                                        </span>
                                    </div>
                                </div>
                                <input
                                    type="file"
                                    accept=".xlsx,.xls,.csv"
                                    @change="onImportFileChange"
                                    class="sr-only"
                                />
                            </label>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button
                                    type="button"
                                    @click="importModalOpen = false"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="!importForm.file || importForm.processing"
                                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 disabled:opacity-50"
                                >
                                    <i v-if="importForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Procesar e Importar</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: Guide -->
                    <div v-show="importTab === 'guide'" class="space-y-4">
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <table class="min-w-full text-xs divide-y divide-gray-200">
                                <thead class="bg-gray-50 font-bold text-gray-700 uppercase">
                                    <tr>
                                        <th class="px-3 py-2 text-center w-12">Col</th>
                                        <th class="px-3 py-2 text-left">Campo</th>
                                        <th class="px-3 py-2 text-center">Tipo</th>
                                        <th class="px-3 py-2 text-left">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">B</td><td class="px-3 py-2 font-bold">DNI</td><td class="px-3 py-2 text-center text-red-600 font-bold">REQUERIDO</td><td class="px-3 py-2 text-gray-600">Documento de identidad (8 dígitos)</td></tr>
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">C</td><td class="px-3 py-2 font-bold">Nombres</td><td class="px-3 py-2 text-center text-red-600 font-bold">REQUERIDO</td><td class="px-3 py-2 text-gray-600">Nombres y apellidos completos del estudiante</td></tr>
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">D</td><td class="px-3 py-2 font-bold">Curso</td><td class="px-3 py-2 text-center text-red-600 font-bold">REQUERIDO</td><td class="px-3 py-2 text-gray-600">Nombre exacto del curso existente en el sistema</td></tr>
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">G</td><td class="px-3 py-2 font-bold">Fecha Emisión</td><td class="px-3 py-2 text-center text-red-600 font-bold">REQUERIDO</td><td class="px-3 py-2 text-gray-600">Formato YYYY-MM-DD</td></tr>
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">N</td><td class="px-3 py-2 font-bold">Modalidad</td><td class="px-3 py-2 text-center text-red-600 font-bold">REQUERIDO</td><td class="px-3 py-2 text-gray-600">Presencial, Semipresencial o Virtual</td></tr>
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">O</td><td class="px-3 py-2 font-bold text-purple-700">Código</td><td class="px-3 py-2 text-center text-purple-600 font-bold">OPCIONAL</td><td class="px-3 py-2 text-gray-600">Si está vacío, se auto-genera: CERT-{DNI}-{secuencia}</td></tr>
                                    <tr><td class="px-3 py-2 text-center font-bold font-mono">P</td><td class="px-3 py-2 font-bold text-emerald-700">Condición</td><td class="px-3 py-2 text-center text-emerald-600 font-bold">OPCIONAL</td><td class="px-3 py-2 text-gray-600">ASISTENTE, PONENTE, ORGANIZADOR (por defecto ASISTENTE)</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ MODAL BULK PDF EXPORT ═══════════════════════════════════════ -->
        <div
            v-if="bulkPdfModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in"
        >
            <div
                @click.stop
                class="bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-scale-up"
            >
                <div class="bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 px-6 py-4 flex items-center justify-between text-white shrink-0 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl shadow-inner">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black tracking-tight leading-tight">Exportación Masiva de Certificados en PDF</h3>
                            <p class="text-xs text-rose-100">Genere el lote oficial de certificados listos para impresión o guardado como PDF</p>
                        </div>
                    </div>
                    <button type="button" @click="bulkPdfModalOpen = false" class="text-white/80 hover:text-white p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <form
                    :action="route('admin.certificates.bulk-export-pdf')"
                    method="POST"
                    target="_blank"
                    @submit="bulkPdfModalOpen = false"
                    class="flex-1 flex flex-col overflow-hidden"
                >
                    <input type="hidden" name="scope" value="courses" />
                    <input type="hidden" name="faces" :value="pdfFaces" />
                    <input type="hidden" name="modality" :value="pdfModality" />
                    <input type="hidden" name="participation_type" :value="pdfParticipationType" />
                    <input type="hidden" name="order_by" :value="pdfOrderBy" />
                    <input v-if="pdfDateFrom" type="hidden" name="date_from" :value="pdfDateFrom" />
                    <input v-if="pdfDateTo" type="hidden" name="date_to" :value="pdfDateTo" />

                    <!-- Hidden Course Ids -->
                    <template v-for="cid in pdfSelectedCourseIds" :key="cid">
                        <input type="hidden" name="course_ids[]" :value="cid" />
                    </template>

                    <div class="p-6 space-y-5 overflow-y-auto flex-1 text-gray-800">
                        <!-- Course Selection -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <i class="bi bi-journal-bookmark-fill text-rose-600"></i>
                                    <span>Cursos a Exportar</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-extrabold">
                                        {{ pdfSelectedCourseIds.includes('all') ? 'Todos los cursos' : `${pdfSelectedCourseIds.length} seleccionados` }}
                                    </span>
                                </label>
                                <div class="flex items-center gap-2 text-xs">
                                    <button type="button" @click="selectAllPdfCourses" class="text-rose-600 hover:underline font-bold text-[11px] cursor-pointer">
                                        Seleccionar Todos
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" @click="deselectAllPdfCourses" class="text-gray-500 hover:underline font-bold text-[11px] cursor-pointer">
                                        Deseleccionar
                                    </button>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-xl bg-gray-50/50 p-2 max-h-48 overflow-y-auto space-y-1 divide-y divide-gray-100">
                                <label
                                    class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition"
                                    :class="pdfSelectedCourseIds.includes('all') ? 'bg-white font-bold text-rose-900 border border-rose-200 shadow-xs' : 'text-gray-700'"
                                >
                                    <div class="flex items-center gap-2.5">
                                        <input
                                            type="checkbox"
                                            :checked="pdfSelectedCourseIds.includes('all')"
                                            @change="togglePdfCourse('all')"
                                            class="rounded text-rose-600 focus:ring-rose-500"
                                        />
                                        <span class="text-xs">📚 Todos los Cursos Registrados</span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-bold">
                                        {{ totalCertificates }} certs
                                    </span>
                                </label>

                                <label
                                    v-for="c in courses"
                                    :key="c.id"
                                    class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition text-gray-700"
                                    :class="isPdfCourseSelected(c.id) ? 'bg-white font-semibold text-gray-900' : ''"
                                >
                                    <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                        <input
                                            type="checkbox"
                                            :checked="isPdfCourseSelected(c.id)"
                                            @change="togglePdfCourse(c.id)"
                                            class="rounded text-rose-600 focus:ring-rose-500"
                                        />
                                        <span class="text-xs truncate">{{ c.name }}</span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-200/80 text-gray-700 font-medium shrink-0">
                                        {{ c.certificates_count || 0 }} certs
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Faces to Print -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                <i class="bi bi-file-earmark-break text-rose-600"></i> Páginas / Caras a Incluir
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                                <label
                                    class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition"
                                    :class="pdfFaces === 'all' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-gray-200 bg-white'"
                                >
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" value="all" v-model="pdfFaces" class="text-rose-600 focus:ring-rose-500" />
                                        <span class="font-bold text-gray-900">Ambas Caras</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Frente y Reverso con notas</p>
                                </label>
                                <label
                                    class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition"
                                    :class="pdfFaces === 'front' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-gray-200 bg-white'"
                                >
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" value="front" v-model="pdfFaces" class="text-rose-600 focus:ring-rose-500" />
                                        <span class="font-bold text-gray-900">Solo Frente</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Diploma principal</p>
                                </label>
                                <label
                                    class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition"
                                    :class="pdfFaces === 'back' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-gray-200 bg-white'"
                                >
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" value="back" v-model="pdfFaces" class="text-rose-600 focus:ring-rose-500" />
                                        <span class="font-bold text-gray-900">Solo Reverso</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">Notas y temario</p>
                                </label>
                            </div>
                        </div>

                        <!-- Modality, Condition & Order -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Modalidad</label>
                                <select v-model="pdfModality" class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2 bg-white">
                                    <option value="all">Todas</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Semipresencial">Semipresencial</option>
                                    <option value="Virtual">Virtual</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Condición</label>
                                <select v-model="pdfParticipationType" class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2 bg-white">
                                    <option value="all">Todas</option>
                                    <option value="ASISTENTE">ASISTENTE</option>
                                    <option value="PONENTE">PONENTE</option>
                                    <option value="ORGANIZADOR">ORGANIZADOR</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Ordenar por</label>
                                <select v-model="pdfOrderBy" class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2 bg-white">
                                    <option value="names_asc">Nombres A-Z</option>
                                    <option value="issue_date_desc">Fecha de Emisión (recientes)</option>
                                    <option value="code_asc">Código de Certificado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between shrink-0">
                        <span class="text-xs text-gray-500">Se abrirá el visualizador oficial de impresión en una nueva pestaña</span>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="bulkPdfModalOpen = false"
                                class="px-4 py-2 border border-gray-300 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-100 transition"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5"
                            >
                                <i class="bi bi-printer-fill"></i>
                                <span>Generar e Imprimir Lote</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══ MODAL CONFIRM DELETE SINGLE ═════════════════════════════════ -->
        <div
            v-if="isDeleteModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
        >
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md p-6 text-center animate-scale-up">
                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2">¿Eliminar Certificado?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    ¿Está seguro de eliminar el certificado «<strong>{{ itemToDelete?.certificate_code || itemToDelete?.code }}</strong>» correspondiente al estudiante <strong>{{ itemToDelete?.user?.names }}</strong>? Esta acción no se puede deshacer.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button
                        type="button"
                        @click="isDeleteModalOpen = false"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition flex-1"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="executeDelete"
                        :disabled="isDeleting"
                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center justify-center gap-2 flex-1 disabled:opacity-50"
                    >
                        <i v-if="isDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>Eliminar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ══ MODAL CONFIRM BULK DELETE ═══════════════════════════════════ -->
        <div
            v-if="bulkDeleteModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
        >
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md p-6 text-center animate-scale-up">
                <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="bi bi-trash3-fill"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2">Eliminar Certificados Seleccionados</h3>
                <p class="text-sm text-gray-600 mb-6">
                    ¿Está seguro de eliminar los <strong class="text-rose-600 font-extrabold">{{ selectedCerts.length }}</strong> certificados seleccionados y todas sus notas modulares?
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button
                        type="button"
                        @click="bulkDeleteModalOpen = false"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition flex-1"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="executeBulkDelete"
                        :disabled="isBulkDeleting"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center justify-center gap-2 flex-1 disabled:opacity-50"
                    >
                        <i v-if="isBulkDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>Eliminar Seleccionados</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ══ MODAL CONFIRM DELETE ALL ════════════════════════════════════ -->
        <div
            v-if="deleteAllModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in"
        >
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md p-6 text-center animate-scale-up">
                <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4 animate-pulse">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">¿Eliminar TODOS los Certificados?</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Está a punto de vaciar por completo la base de datos de certificados (<strong class="text-red-600 font-extrabold">{{ totalCertificates }}</strong> registros).
                </p>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-800 text-left mb-6">
                    Esta acción es irreversible y eliminará todas las calificaciones asociadas.
                </div>
                <div class="flex items-center justify-center gap-3">
                    <button
                        type="button"
                        @click="deleteAllModalOpen = false"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition flex-1"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="executeDeleteAll"
                        :disabled="isDeletingAll"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center justify-center gap-2 flex-1 disabled:opacity-50"
                    >
                        <i v-if="isDeletingAll" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>Vaciar Todo</span>
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
