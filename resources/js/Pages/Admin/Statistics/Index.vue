<script setup>
import { ref } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    studentRecords: {
        type: Object,
        required: true,
    },
    totalRecords: {
        type: Number,
        default: 0,
    },
    totalAdmission: {
        type: Number,
        default: 0,
    },
    totalEnrollment: {
        type: Number,
        default: 0,
    },
    totalPrograms: {
        type: Number,
        default: 0,
    },
    programStats: {
        type: Array,
        default: () => [],
    },
    academicPeriods: {
        type: Array,
        default: () => [],
    },
    studyProgramsList: {
        type: Array,
        default: () => [],
    },
    search: {
        type: String,
        default: '',
    },
    recordType: {
        type: String,
        default: '',
    },
    academicPeriod: {
        type: String,
        default: '',
    },
    studyProgram: {
        type: String,
        default: '',
    },
})

// Filters state
const searchInput               = ref(props.search || '')
const selectedRecordType        = ref(props.recordType || '')
const selectedAcademicPeriod    = ref(props.academicPeriod || '')
const selectedStudyProgram      = ref(props.studyProgram || '')

function applyFilters() {
    router.get(route('admin.statistics.index'), {
        search: searchInput.value || undefined,
        record_type: selectedRecordType.value || undefined,
        academic_period: selectedAcademicPeriod.value || undefined,
        study_program: selectedStudyProgram.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    })
}

function clearFilters() {
    searchInput.value               = ''
    selectedRecordType.value        = ''
    selectedAcademicPeriod.value    = ''
    selectedStudyProgram.value      = ''
    router.get(route('admin.statistics.index'))
}

// Detail Modal
const showDetailModal   = ref(false)
const activeRecord      = ref(null)

function openDetail(record) {
    activeRecord.value      = record
    showDetailModal.value   = true
}

// Delete Modal
const showDeleteModal   = ref(false)
const recordToDelete    = ref(null)
const isDeleting        = ref(false)

function confirmDelete(record) {
    recordToDelete.value    = record
    showDeleteModal.value   = true
}

function deleteRecord() {
    if (!recordToDelete.value) return
    isDeleting.value = true

    router.delete(route('admin.statistics.destroy', recordToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value        = false
            showDeleteModal.value   = false
            recordToDelete.value    = null
        },
    })
}

// Import Modal
const showImportModal = ref(false)
const importForm = useForm({
    record_type: 'AUTO',
    file: null,
})
const fileName = ref('')

function onFileSelect(e) {
    const file = e.target.files[0]
    if (file) {
        importForm.file = file
        fileName.value = file.name
    }
}

function submitImport() {
    if (!importForm.file) return
    importForm.post(route('admin.statistics.import'), {
        preserveScroll: true,
        onSuccess: () => {
            showImportModal.value = false
            importForm.reset()
            fileName.value = ''
        },
    })
}
</script>

<template>
    <AdminLayout title="Estadísticas y Registros Académicos">
        <Head title="Estadísticas y Registros Estudiantiles - Transparencia" />
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-bar-chart-line-fill text-lg"></i>
                        </span>
                        Estadísticas y Registros Académicos
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Padrón y estadísticas oficiales para los procesos de Admisión y Matrícula institucional.
                    </p>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button @click="showImportModal = true"
                            type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-500/20 transition-all">
                        <i class="bi bi-file-earmark-spreadsheet-fill text-sm"></i>
                        <span>Importar Excel / CSV</span>
                    </button>

                    <Link :href="route('admin.statistics.create')"
                          class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-500/20 transition-all">
                        <i class="bi bi-plus-circle text-sm"></i>
                        <span>Nuevo Registro</span>
                    </Link>
                </div>
            </div>
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Registros</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ totalRecords.toLocaleString() }}</h3>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Postulantes Admisión</p>
                        <h3 class="text-2xl font-black text-blue-700">{{ totalAdmission.toLocaleString() }}</h3>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Matriculados</p>
                        <h3 class="text-2xl font-black text-emerald-700">{{ totalEnrollment.toLocaleString() }}</h3>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Programas de Estudio</p>
                        <h3 class="text-2xl font-black text-amber-700">{{ totalPrograms }}</h3>
                    </div>
                </div>
            </div>
            <!-- Filters Bar -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                    <!-- Search Input -->
                    <div class="lg:col-span-4 relative">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input v-model="searchInput"
                               @keyup.enter="applyFilters"
                               type="text"
                               placeholder="Buscar por DNI, nombres, colegio..."
                               class="w-full pl-10 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                    </div>
                    <!-- Record Type Filter -->
                    <div class="lg:col-span-2">
                        <select v-model="selectedRecordType"
                                @change="applyFilters"
                                class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                            <option value="">Tipo: Todos</option>
                            <option value="ADMISION">Admisión</option>
                            <option value="MATRICULA">Matrícula</option>
                        </select>
                    </div>
                    <!-- Academic Period Filter -->
                    <div class="lg:col-span-2">
                        <select v-model="selectedAcademicPeriod"
                                @change="applyFilters"
                                class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                            <option value="">Período: Todos</option>
                            <option v-for="period in academicPeriods" :key="period" :value="period">
                                {{ period }}
                            </option>
                        </select>
                    </div>
                    <!-- Study Program Filter -->
                    <div class="lg:col-span-2">
                        <select v-model="selectedStudyProgram"
                                @change="applyFilters"
                                class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium truncate">
                            <option value="">Programa: Todos</option>
                            <option v-for="prog in studyProgramsList" :key="prog" :value="prog">
                                {{ prog }}
                            </option>
                        </select>
                    </div>
                    <!-- Action Buttons -->
                    <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                        <button @click="applyFilters"
                                type="button"
                                class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1">
                            <i class="bi bi-funnel-fill"></i> Filtrar
                        </button>
                        <button v-if="search || recordType || academicPeriod || studyProgram"
                                @click="clearFilters"
                                type="button"
                                class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-all"
                                title="Limpiar Filtros">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[950px]">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                                <th class="p-4 w-12">#</th>
                                <th class="p-4 w-28">Tipo</th>
                                <th class="p-4">Estudiante / Postulante</th>
                                <th class="p-4">Documento</th>
                                <th class="p-4">Programa de Estudios</th>
                                <th class="p-4 w-28">Período</th>
                                <th class="p-4 w-24">Puntaje</th>
                                <th class="p-4 text-center w-28">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            <tr v-for="(record, idx) in studentRecords.data"
                                :key="record.id"
                                class="hover:bg-purple-50/20 transition-colors">
                                <td class="p-4 text-gray-400 font-mono">
                                    {{ (studentRecords.from || 1) + idx }}
                                </td>
                                <td class="p-4">
                                    <span v-if="record.record_type === 'ADMISION'"
                                          class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        Admisión
                                    </span>
                                    <span v-else
                                          class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Matrícula ({{ record.cycle || 'I' }})
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-900">
                                        {{ record.last_name_father }} {{ record.last_name_mother }} {{ record.names }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">{{ record.institution_name_ie || 'Sin I.E. registrada' }}</div>
                                </td>
                                <td class="p-4 font-mono font-medium text-gray-600">
                                    {{ record.document || '-' }}
                                </td>
                                <td class="p-4 text-gray-700 font-medium">
                                    {{ record.study_program }}
                                </td>
                                <td class="p-4 font-bold text-purple-700">
                                    {{ record.academic_period }}
                                </td>
                                <td class="p-4 font-bold text-gray-800">
                                    {{ record.score ?? '-' }}
                                </td>
                                <td class="p-4 text-center space-x-1">
                                    <button @click="openDetail(record)"
                                            type="button"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Ver Detalle">
                                        <i class="bi bi-eye text-sm"></i>
                                    </button>
                                    <Link :href="route('admin.statistics.edit', record.id)"
                                          class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors inline-block"
                                          title="Editar">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </Link>
                                    <button @click="confirmDelete(record)"
                                            type="button"
                                            class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Eliminar">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!studentRecords.data || studentRecords.data.length === 0">
                                <td colspan="8" class="p-12 text-center text-gray-400">
                                    <i class="bi bi-folder-x text-4xl mb-2 block text-gray-300"></i>
                                    <p class="font-bold text-gray-600 text-sm">No se encontraron registros estudiantiles</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Usa los botones superiores para importar registros desde Excel o crear uno nuevo.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div v-if="studentRecords.links && studentRecords.links.length > 3"
                     class="px-6 py-4 bg-gray-50/75 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold text-gray-800">{{ studentRecords.from || 0 }}</span> a <span class="font-bold text-gray-800">{{ studentRecords.to || 0 }}</span> de <span class="font-bold text-gray-800">{{ studentRecords.total }}</span> registros
                    </p>

                    <div class="flex items-center gap-1">
                        <Link v-for="(link, idx) in studentRecords.links"
                              :key="idx"
                              :href="link.url || '#'"
                              v-html="link.label"
                              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                              :class="[
                                  link.active ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-200',
                                  !link.url ? 'opacity-40 cursor-not-allowed hover:bg-transparent' : ''
                              ]" />
                    </div>
                </div>
            </div>
            <!-- Import Modal -->
            <Teleport to="body">
                <div v-if="showImportModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.self="!importForm.processing && (showImportModal = false)">
                    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                    <i class="bi bi-file-earmark-spreadsheet-fill text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-gray-900">Importar Registros</h3>
                                    <p class="text-xs text-gray-500">Excel (.xlsx, .xls) o CSV — Columnas H a AF</p>
                                </div>
                            </div>
                            <button @click="showImportModal = false" :disabled="importForm.processing" class="text-gray-400 hover:text-gray-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitImport" class="space-y-4 text-xs">
                            <!-- Tipo de registro -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Tipo de Registro <span class="text-rose-500">*</span>
                                </label>
                                <select v-model="importForm.record_type"
                                        class="w-full text-xs border border-gray-300 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition font-semibold">
                                    <option value="AUTO">Automático (detectar por ciclo)</option>
                                    <option value="ADMISION">Admisión — todo el archivo</option>
                                    <option value="MATRICULA">Matrícula — todo el archivo</option>
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">En modo automático: si hay ciclo → Matrícula; si no → Admisión.</p>
                            </div>
                            <!-- Archivo -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Archivo <span class="text-rose-500">*</span>
                                </label>
                                <label class="border-2 border-dashed border-gray-300 hover:border-emerald-500 rounded-xl p-5 flex flex-col items-center justify-center cursor-pointer transition bg-gray-50/50">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-emerald-600 mb-1"></i>
                                    <span v-if="fileName" class="text-xs font-bold text-gray-800">{{ fileName }}</span>
                                    <span v-else class="text-xs font-semibold text-gray-600">Haz clic o arrastra un archivo aquí</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5">XLSX, XLS, CSV hasta 20 MB</span>
                                    <input type="file"
                                           accept=".xlsx,.xls,.csv"
                                           class="hidden"
                                           @change="onFileSelect" />
                                </label>
                                <p v-if="importForm.errors.file" class="text-xs text-rose-500 font-semibold mt-1">{{ importForm.errors.file }}</p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                                <button @click="showImportModal = false"
                                        type="button"
                                        class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        :disabled="importForm.processing || !importForm.file"
                                        class="px-5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs rounded-xl shadow-md hover:from-emerald-700 hover:to-teal-700 transition-all flex items-center gap-2 disabled:opacity-50">
                                    <span v-if="importForm.processing">Procesando archivo...</span>
                                    <span v-else class="flex items-center gap-1.5"><i class="bi bi-upload"></i> Iniciar Importación</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>
            <!-- Detail Modal -->
            <Teleport to="body">
                <div v-if="showDetailModal && activeRecord" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.self="showDetailModal = false">
                    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-gray-100 space-y-4 max-h-[90vh] overflow-y-auto text-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="text-sm font-extrabold text-gray-900 flex items-center gap-2">
                                <i class="bi bi-person-vcard text-purple-600"></i> Ficha de Registro Académico #{{ activeRecord.id }}
                            </h3>
                            <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                        <!-- Detail Sections -->
                        <div class="space-y-4">
                            <div class="bg-purple-50/50 p-4 rounded-xl border border-purple-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Tipo de Registro</span>
                                    <span class="font-bold text-gray-800">{{ activeRecord.record_type }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Período Académico</span>
                                    <span class="font-bold text-purple-700">{{ activeRecord.academic_period }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Programa</span>
                                    <span class="font-bold text-gray-800">{{ activeRecord.study_program }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Puntaje</span>
                                    <span class="font-bold text-gray-800">{{ activeRecord.score ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Situación</span>
                                    <span class="font-bold text-gray-800">{{ activeRecord.situation ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Ciclo</span>
                                    <span class="font-bold text-gray-800">{{ activeRecord.cycle ?? '-' }}</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-2">Datos Personales</h4>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Apellidos y Nombres</span>
                                        <span class="font-bold text-gray-900">{{ activeRecord.last_name_father }} {{ activeRecord.last_name_mother }} {{ activeRecord.names }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Documento</span>
                                        <span class="font-mono font-bold text-gray-800">{{ activeRecord.document }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Género</span>
                                        <span>{{ activeRecord.gender ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Fecha Nacimiento</span>
                                        <span>{{ activeRecord.birthdate ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Teléfono</span>
                                        <span>{{ activeRecord.phone ?? '-' }}</span>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Correo Electrónico</span>
                                        <span class="text-purple-700 font-medium">{{ activeRecord.email ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-2">Colegio / I.E. de Procedencia</h4>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Nombre I.E.</span>
                                        <span class="font-bold text-gray-900">{{ activeRecord.institution_name_ie ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Año Egreso</span>
                                        <span class="font-mono font-bold">{{ activeRecord.year_graduation ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Código Modular</span>
                                        <span class="font-mono">{{ activeRecord.modular_code_ie ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Ubicación I.E.</span>
                                        <span>{{ [activeRecord.district_ie, activeRecord.province_ie, activeRecord.region_ie].filter(Boolean).join(', ') || '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-3 border-t border-gray-100">
                            <button @click="showDetailModal = false"
                                    type="button"
                                    class="px-5 py-2 bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </Teleport>
            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" @click.self="!isDeleting && (showDeleteModal = false)">
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto">
                            <i class="bi bi-trash"></i>
                        </div>

                        <div class="text-center">
                            <h3 class="text-base font-bold text-gray-900">¿Eliminar este registro académico?</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Estás a punto de eliminar el registro de <strong class="text-gray-800">{{ recordToDelete?.names }}</strong>. Esta acción no se puede deshacer.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button @click="showDeleteModal = false"
                                    type="button"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </button>
                            <button @click="deleteRecord"
                                    :disabled="isDeleting"
                                    type="button"
                                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-2 disabled:opacity-60">
                                <span v-if="isDeleting">Eliminando...</span>
                                <span v-else>Sí, Eliminar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </Teleport>
        </div>
    </AdminLayout>
</template>
