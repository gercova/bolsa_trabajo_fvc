<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { route } from '@/route'

const props = defineProps({
    records: {
        type: Object,
        required: true,
    },
    totalRecords: {
        type: Number,
        default: 0,
    },
    totalAmount: {
        type: Number,
        default: 0,
    },
    totalTableRecords: {
        type: Number,
        default: 0,
    },
    availableYears: {
        type: Array,
        default: () => [],
    },
    availableMonths: {
        type: Array,
        default: () => [],
    },
    availableCategories: {
        type: Array,
        default: () => [],
    },
    yearCounts: {
        type: Object,
        default: () => ({}),
    },
    year: {
        type: [String, Number],
        default: '',
    },
    month: {
        type: String,
        default: '',
    },
    category: {
        type: String,
        default: '',
    },
    search: {
        type: String,
        default: '',
    },
})

// Filters
const searchInput = ref(props.search || '')
const selectedYear = ref(props.year ? String(props.year) : '')
const selectedMonth = ref(props.month || '')
const selectedCategory = ref(props.category || '')

const activeFiltersCount = computed(() => {
    let count = 0
    if (searchInput.value) count++
    if (selectedYear.value) count++
    if (selectedMonth.value) count++
    if (selectedCategory.value) count++
    return count
})

function applyFilters() {
    router.get(route('admin.account-balances.index'), {
        search: searchInput.value || undefined,
        year: selectedYear.value || undefined,
        month: selectedMonth.value || undefined,
        category: selectedCategory.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    })
}

function clearFilters() {
    searchInput.value = ''
    selectedYear.value = ''
    selectedMonth.value = ''
    selectedCategory.value = ''
    router.get(route('admin.account-balances.index'))
}

// Formatters
function formatCurrency(amount) {
    const num = Number(amount) || 0
    return num.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

function formatDate(dateStr) {
    if (!dateStr) return '—'
    const parts = String(dateStr).substring(0, 10).split('-')
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`
    }
    return dateStr
}

// Delete Single Record
const showDeleteModal = ref(false)
const recordToDelete = ref(null)
const isDeleting = ref(false)

function confirmDelete(record) {
    recordToDelete.value = record
    showDeleteModal.value = true
}

function deleteRecord() {
    if (!recordToDelete.value) return
    isDeleting.value = true
    router.delete(route('admin.account-balances.destroy', recordToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value = false
            showDeleteModal.value = false
            recordToDelete.value = null
        },
    })
}

// Import Modal
const showImportModal = ref(false)
const importFileName = ref('')
const importFileInput = ref(null)

const importForm = useForm({
    file: null,
})

function handleFileSelect(e) {
    const file = e.target.files?.[0]
    if (file) {
        importFileName.value = file.name
        importForm.file = file
    } else {
        importFileName.value = ''
        importForm.file = null
    }
}

function submitImport() {
    if (!importForm.file) return
    importForm.post(route('admin.account-balances.import'), {
        onSuccess: () => {
            showImportModal.value = false
            importForm.reset()
            importFileName.value = ''
        },
    })
}

// Truncate / Clear Period Modal
const showTruncateModal = ref(false)
const truncateScope = ref(props.year ? String(props.year) : 'all')
const isTruncating = ref(false)

function openTruncateModal(scope = 'all') {
    truncateScope.value = scope
    showTruncateModal.value = true
}

function submitTruncate() {
    isTruncating.value = true
    router.delete(route('admin.account-balances.truncate'), {
        data: {
            year: truncateScope.value,
        },
        preserveScroll: true,
        onFinish: () => {
            isTruncating.value = false
            showTruncateModal.value = false
        },
    })
}
</script>

<template>
    <AdminLayout title="Inversión y Gastos">
        <Head title="Inversión y Gastos — Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Bar -->
            <div class="bg-white border-b border-gray-200 -mt-4 sm:-mt-6 lg:-mt-8 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 py-4 shadow-sm backdrop-blur-md bg-white/95 sticky top-0 z-20">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-xs">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                                Inversión y Gastos
                            </h1>
                            <p class="text-xs text-gray-500 font-medium">
                                Transparencia económica institucional y ejecución presupuestal
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <a
                            href="/transparencia/inversion-y-gestion"
                            target="_blank"
                            class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs sm:text-sm rounded-xl transition-colors flex items-center gap-1.5"
                            title="Ver en portal de transparencia"
                        >
                            <i class="bi bi-eye"></i>
                            <span class="hidden sm:inline">Ver Vista Pública</span>
                        </a>

                        <button
                            type="button"
                            @click="showImportModal = true"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all duration-200"
                        >
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                            <span>Importar Excel / CSV</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Flash Notifications -->
            <div
                v-if="$page.props.flash?.success"
                class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-xs flex items-center justify-between transition-all"
            >
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                    <p class="text-sm font-medium text-emerald-800">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div
                v-if="$page.props.flash?.error"
                class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs flex items-center justify-between transition-all"
            >
                <div class="flex items-center gap-3">
                    <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl"></i>
                    <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Total Registros Filtrados -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Registros</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ totalRecords.toLocaleString() }}</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">de {{ totalTableRecords.toLocaleString() }} en total</p>
                    </div>
                </div>

                <!-- Total Desembolsos -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Desembolsos</p>
                        <h3 class="text-2xl font-black text-blue-700">S/ {{ formatCurrency(totalAmount) }}</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">monto acumulado según filtros</p>
                    </div>
                </div>

                <!-- Filtros Activos -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl shrink-0">
                        <i class="bi bi-funnel-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Filtros Activos</p>
                        <h3 class="text-sm font-bold text-amber-700 mt-1">
                            {{ activeFiltersCount }} filtro(s) aplicado(s)
                        </h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            {{ selectedYear ? `Año ${selectedYear}` : 'Todos los años' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Filters & Actions Bar -->
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-200 space-y-4">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                    <!-- Search Input -->
                    <div class="lg:col-span-4 relative">
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Buscar cliente, descripción, N° B/V..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                        />
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Year Filter -->
                    <div class="lg:col-span-2">
                        <select
                            v-model="selectedYear"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Año: Todos</option>
                            <option v-for="yr in availableYears" :key="yr" :value="String(yr)">
                                {{ yr }}
                            </option>
                        </select>
                    </div>

                    <!-- Month Filter -->
                    <div class="lg:col-span-2">
                        <select
                            v-model="selectedMonth"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Mes: Todos</option>
                            <option v-for="m in availableMonths" :key="m" :value="m">
                                {{ m }}
                            </option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="lg:col-span-2">
                        <select
                            v-model="selectedCategory"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium truncate"
                        >
                            <option value="">Categoría: Todas</option>
                            <option v-for="c in availableCategories" :key="c" :value="c">
                                {{ c }}
                            </option>
                        </select>
                    </div>

                    <!-- Filter & Clear Buttons -->
                    <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs flex items-center justify-center gap-1.5 flex-1"
                        >
                            <i class="bi bi-funnel-fill"></i> Filtrar
                        </button>
                        <button
                            v-if="activeFiltersCount > 0"
                            type="button"
                            @click="clearFilters"
                            class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition-all"
                            title="Limpiar Filtros"
                        >
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </form>

                <!-- Actions Row -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                    <span class="text-xs font-semibold text-gray-500">
                        Mostrando {{ records.total || 0 }} de {{ totalTableRecords.toLocaleString() }} registros en total
                    </span>

                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-end">
                        <!-- Limpiar por Periodo -->
                        <button
                            v-if="totalTableRecords > 0 && selectedYear"
                            type="button"
                            @click="openTruncateModal(selectedYear)"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all"
                            :title="`Eliminar únicamente los registros del año ${selectedYear}`"
                        >
                            <i class="bi bi-calendar-x-fill"></i>
                            <span>Limpiar Año {{ selectedYear }}</span>
                            <span class="ml-1 bg-amber-700/60 text-white px-2 py-0.5 rounded-full text-[11px]">
                                {{ (yearCounts[selectedYear] || 0).toLocaleString() }}
                            </span>
                        </button>

                        <button
                            v-else-if="totalTableRecords > 0"
                            type="button"
                            @click="openTruncateModal(availableYears[0] ? String(availableYears[0]) : 'all')"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 rounded-xl text-xs sm:text-sm font-bold shadow-2xs hover:shadow transition-all"
                            title="Seleccionar un período específico para vaciar"
                        >
                            <i class="bi bi-calendar-x-fill text-amber-600"></i>
                            <span>Limpiar por Período</span>
                        </button>

                        <!-- Vaciar Toda la Tabla -->
                        <button
                            v-if="totalTableRecords > 0"
                            type="button"
                            @click="openTruncateModal('all')"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all"
                            title="Vaciar completamente la tabla de registros"
                        >
                            <i class="bi bi-trash3-fill"></i>
                            <span>Vaciar Tabla</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div v-if="!records.data || records.data.length === 0" class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-gray-600">Sin registros</p>
                        <p class="text-sm mt-1">
                            <template v-if="activeFiltersCount > 0">
                                No se encontraron registros con los filtros aplicados.
                                <button type="button" @click="clearFilters" class="text-purple-600 font-semibold underline ml-1">
                                    Limpiar filtros
                                </button>
                            </template>
                            <template v-else>
                                Aún no hay registros cargados. Usa el botón <strong>Importar Excel / CSV</strong> para cargar el primer lote.
                            </template>
                        </p>
                    </div>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mes</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">N° B/V</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Categoría</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Programa</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Monto (S/)</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Motivo</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="record in records.data"
                                :key="record.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-4 py-3 font-medium text-gray-700 whitespace-nowrap">
                                    {{ record.month || '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ formatDate(record.date) }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap font-mono text-xs">
                                    {{ record.receipt_number || '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 font-medium max-w-[180px] truncate" :title="record.client">
                                    {{ record.client || '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-[220px] truncate" :title="record.description">
                                    {{ record.description || '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        v-if="record.category"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700"
                                    >
                                        {{ record.category }}
                                    </span>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs max-w-[140px] truncate" :title="record.program_name">
                                    <span v-if="record.program_code" class="font-bold text-indigo-600 mr-1">
                                        {{ record.program_code }}
                                    </span>
                                    <span>{{ record.program_name || '—' }}</span>
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-bold font-mono whitespace-nowrap"
                                    :class="Number(record.amount) >= 0 ? 'text-blue-700' : 'text-red-600'"
                                >
                                    S/ {{ formatCurrency(Math.abs(Number(record.amount))) }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs max-w-[120px] truncate" :title="record.reason">
                                    {{ record.reason || '—' }}
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <button
                                        type="button"
                                        @click="confirmDelete(record)"
                                        class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Eliminar Registro"
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="records.links && records.links.length > 3" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold text-gray-700">{{ records.from || 0 }}</span>–<span class="font-bold text-gray-700">{{ records.to || 0 }}</span>
                        de <span class="font-bold text-gray-700">{{ records.total }}</span> registros
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="(link, i) in records.links" :key="i">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition"
                                :class="link.active ? 'bg-purple-600 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ IMPORT MODAL ══════════════════════════════════════════════ -->
        <div
            v-if="showImportModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
        >
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <i class="bi bi-file-earmark-spreadsheet-fill text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">Importar Registros de Inversión</h3>
                            <p class="text-xs text-gray-500">Formato Excel (.xlsx, .xls) o CSV — Columnas A a J</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="showImportModal = false"
                        :disabled="importForm.processing"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg"
                    >
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <form @submit.prevent="submitImport" class="px-6 py-5 space-y-5">
                    <!-- File input drag area -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Archivo a Importar <span class="text-red-500">*</span>
                        </label>
                        <label
                            for="import_file_input"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-emerald-50 hover:border-emerald-400 transition-all group"
                        >
                            <div v-if="!importFileName" class="flex flex-col items-center gap-2">
                                <i class="bi bi-cloud-arrow-up-fill text-3xl text-gray-400 group-hover:text-emerald-500 transition"></i>
                                <p class="text-xs text-gray-500 group-hover:text-emerald-600 text-center">
                                    <span class="font-semibold">Haz clic para seleccionar</span> o arrastra el archivo aquí
                                </p>
                                <p class="text-xs text-gray-400">.xlsx · .xls · .csv — máx. 10 MB</p>
                            </div>
                            <div v-else class="flex flex-col items-center gap-2">
                                <i class="bi bi-file-earmark-check-fill text-3xl text-emerald-500"></i>
                                <p class="text-xs font-semibold text-emerald-700">{{ importFileName }}</p>
                                <p class="text-xs text-gray-400">Haz clic para cambiar de archivo</p>
                            </div>
                            <input
                                id="import_file_input"
                                ref="importFileInput"
                                type="file"
                                accept=".xlsx,.xls,.csv"
                                required
                                class="hidden"
                                @change="handleFileSelect"
                            />
                        </label>
                        <p v-if="importForm.errors.file" class="text-xs text-red-600 mt-1">{{ importForm.errors.file }}</p>
                    </div>

                    <!-- Instructions -->
                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <i class="bi bi-info-circle-fill text-blue-500 mt-0.5 shrink-0"></i>
                        <div class="text-xs text-blue-700 leading-relaxed space-y-1">
                            <p>El archivo debe tener <strong>encabezados en la fila 1</strong> y datos a partir de la fila 2.</p>
                            <p class="font-mono text-[11px]">
                                A=MES, B=FECHA, C=N° B/V, D=CLIENTE, E=DESCRIPCIÓN, F=CATEGORÍA, G=PROGRAMA (COD.), H=PROGRAMA (NOMBRE), I=MONTO (S/), J=MOTIVO
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-1">
                        <button
                            type="button"
                            @click="showImportModal = false"
                            :disabled="importForm.processing"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="importForm.processing || !importForm.file"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="importForm.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-upload"></i>
                            <span>{{ importForm.processing ? 'Importando...' : 'Iniciar Importación' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ══ TRUNCATE / CLEAR PERIOD MODAL ═════════════════════════════════ -->
        <div
            v-if="showTruncateModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
        >
            <div class="bg-white rounded-2xl shadow-2xl border border-red-200 w-full max-w-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-red-100 bg-red-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-red-900">Limpieza y Vaciado de Registros</h3>
                            <p class="text-xs text-red-600">Eliminación masiva por año o tabla completa</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="showTruncateModal = false"
                        :disabled="isTruncating"
                        class="text-red-400 hover:text-red-600 p-1 rounded-lg"
                    >
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Selecciona el alcance de la eliminación:
                        </label>
                        <select
                            v-model="truncateScope"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition bg-white text-gray-800 font-semibold"
                        >
                            <option value="all">
                                ⚠️ Vaciar TODA la tabla (Historial completo — {{ totalTableRecords.toLocaleString() }} registros)
                            </option>
                            <option v-for="yr in availableYears" :key="yr" :value="String(yr)">
                                Solo registros del año {{ yr }} ({{ (yearCounts[yr] || 0).toLocaleString() }} registros)
                            </option>
                        </select>
                    </div>

                    <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-800">
                        <i class="bi bi-shield-exclamation text-red-500 text-xl mt-0.5 shrink-0"></i>
                        <div>
                            <template v-if="truncateScope === 'all'">
                                <p class="font-bold text-red-900">Estás a punto de vaciar TODA la tabla de inversión y gastos.</p>
                                <p class="mt-0.5">
                                    Se eliminarán de forma permanente
                                    <strong class="font-black text-red-700">{{ totalTableRecords.toLocaleString() }} registros</strong>
                                    históricos. Esta acción es irreversible.
                                </p>
                            </template>
                            <template v-else>
                                <p class="font-bold text-red-900">
                                    Estás a punto de eliminar únicamente los registros del año {{ truncateScope }}.
                                </p>
                                <p class="mt-0.5">
                                    Se removerán los {{ (yearCounts[truncateScope] || 0).toLocaleString() }} registros de ese período. Los demás años permanecerán intactos.
                                </p>
                            </template>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600">
                        <p class="font-bold text-slate-700 mb-0.5">Autorización requerida:</p>
                        <p>Esta acción está restringida a usuarios con rol Director, Administrador o permiso <code>gestionar-inversiones</code>.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="showTruncateModal = false"
                            :disabled="isTruncating"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            @click="submitTruncate"
                            :disabled="isTruncating"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white rounded-xl text-sm font-black shadow-md transition flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="isTruncating" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-trash3-fill"></i>
                            <span>
                                {{ isTruncating ? 'Eliminando...' : (truncateScope === 'all' ? 'Sí, vaciar toda la tabla' : `Sí, eliminar año ${truncateScope}`) }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Single Record Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs"
        >
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-center">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">¿Eliminar este registro de gasto?</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        ¿Estás seguro de que deseas eliminar el registro
                        <strong class="text-gray-800">
                            {{ recordToDelete?.receipt_number ? `N° ${recordToDelete.receipt_number}` : '' }}
                            {{ recordToDelete?.client ? `— ${recordToDelete.client}` : '' }}
                        </strong>?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        :disabled="isDeleting"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        @click="deleteRecord"
                        :disabled="isDeleting"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2 disabled:opacity-50"
                    >
                        <i v-if="isDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>{{ isDeleting ? 'Eliminando...' : 'Sí, eliminar' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
