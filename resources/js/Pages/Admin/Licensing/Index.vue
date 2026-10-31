<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { route } from '@/route'

const props = defineProps({
    phases: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        default: () => ({
            total: 0,
            completed: 0,
            in_progress: 0,
            current: null,
            avg_progress: 0,
        }),
    },
    search: {
        type: String,
        default: '',
    },
    status: {
        type: String,
        default: '',
    },
    isActive: {
        type: [String, Number],
        default: '',
    },
})

// Filter state
const searchInput = ref(props.search || '')
const selectedStatus = ref(props.status || '')
const selectedIsActive = ref(props.isActive !== null && props.isActive !== undefined ? String(props.isActive) : '')

function applyFilters() {
    router.get(route('admin.licensing.index'), {
        search: searchInput.value || undefined,
        status: selectedStatus.value || undefined,
        is_active: selectedIsActive.value !== '' ? selectedIsActive.value : undefined,
    }, {
        preserveState: true,
        replace: true,
    })
}

function clearFilters() {
    searchInput.value = ''
    selectedStatus.value = ''
    selectedIsActive.value = ''
    router.get(route('admin.licensing.index'))
}

// Toggle active status
const togglingId = ref(null)
function toggleStatus(phase) {
    togglingId.value = phase.id
    router.patch(route('admin.licensing.toggle-status', phase.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            togglingId.value = null
        },
    })
}

// Set as current stage (P)
const settingCurrentId = ref(null)
function setCurrentStage(phase) {
    settingCurrentId.value = phase.id
    router.patch(route('admin.licensing.set-current', phase.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            settingCurrentId.value = null
        },
    })
}

// Delete confirmation modal
const showDeleteModal = ref(false)
const phaseToDelete = ref(null)
const isDeleting = ref(false)

function confirmDelete(phase) {
    phaseToDelete.value = phase
    showDeleteModal.value = true
}

function deletePhase() {
    if (!phaseToDelete.value) return
    isDeleting.value = true
    router.delete(route('admin.licensing.destroy', phaseToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value = false
            showDeleteModal.value = false
            phaseToDelete.value = null
        },
    })
}

// Badge resolver fallback
function getBadgeClass(phase) {
    if (phase.status_badge && phase.status_badge.bg_class) {
        return phase.status_badge.bg_class
    }
    switch (phase.status) {
        case 'completed':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200'
        case 'in_progress':
            return 'bg-amber-50 text-amber-800 border-amber-200'
        case 'observed':
            return 'bg-rose-50 text-rose-700 border-rose-200'
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200'
    }
}

function getBadgeIcon(phase) {
    if (phase.status_badge && phase.status_badge.icon) {
        return phase.status_badge.icon
    }
    switch (phase.status) {
        case 'completed':
            return 'bi-check-circle-fill'
        case 'in_progress':
            return 'bi-hourglass-split'
        case 'observed':
            return 'bi-exclamation-octagon-fill'
        default:
            return 'bi-circle'
    }
}

function getBadgeLabel(phase) {
    if (phase.status_badge && phase.status_badge.label) {
        return phase.status_badge.label
    }
    switch (phase.status) {
        case 'completed':
            return 'Culminado (C)'
        case 'in_progress':
            return 'En Proceso (P)'
        case 'observed':
            return 'En Observación (OBS)'
        default:
            return 'Pendiente (PTE)'
    }
}
</script>

<template>
    <AdminLayout title="Gestión de Licenciamiento">
        <Head title="Gestión de Licenciamiento - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Bar -->
            <div class="bg-white border-b border-gray-200 -mt-4 sm:-mt-6 lg:-mt-8 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 py-4 shadow-sm backdrop-blur-md bg-white/95 sticky top-0 z-20">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-xs">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                Licenciamiento Institucional
                            </h1>
                            <p class="text-xs text-gray-500 font-medium">
                                Administración y seguimiento de fases, CBC y medios de verificación
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <a
                            href="/licenciamiento"
                            target="_blank"
                            class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs sm:text-sm rounded-xl transition-colors flex items-center gap-1.5"
                            title="Abrir vista pública del licenciamiento"
                        >
                            <i class="bi bi-eye"></i>
                            <span class="hidden sm:inline">Ver Vista Pública</span>
                        </a>

                        <Link
                            :href="route('admin.licensing.create')"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md shadow-purple-600/20 hover:shadow-purple-600/30 transition-all duration-200"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Nueva Fase</span>
                        </Link>
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
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
                    <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
                </div>
            </div>

            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Fases -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-layers-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Fases</p>
                        <p class="text-2xl font-black text-gray-800">{{ stats.total }}</p>
                    </div>
                </div>

                <!-- Etapa Actual (P) -->
                <div class="bg-white p-5 rounded-2xl border border-amber-300 shadow-xs flex items-center gap-4 relative overflow-hidden">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider flex items-center gap-1.5">
                            Etapa Actual <span class="px-1.5 py-0.2 rounded bg-amber-500 text-white font-extrabold text-[10px]">(P)</span>
                        </p>
                        <p class="text-sm sm:text-base font-extrabold text-gray-900 truncate">
                            {{ stats.current ? `Fase ${stats.current.phase_number}: ${stats.current.code || stats.current.title}` : 'No definida' }}
                        </p>
                    </div>
                </div>

                <!-- Culminadas -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Culminadas</p>
                        <p class="text-2xl font-black text-emerald-600">{{ stats.completed }}</p>
                    </div>
                </div>

                <!-- Avance Global -->
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Avance Promedio</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-2xl font-black text-sky-600">{{ stats.avg_progress }}%</span>
                            <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div
                                    class="bg-sky-500 h-full rounded-full transition-all duration-500"
                                    :style="{ width: `${stats.avg_progress}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-3">
                <form @submit.prevent="applyFilters" class="w-full flex flex-col sm:flex-row items-center gap-3 flex-1">
                    <!-- Search Input -->
                    <div class="relative w-full sm:max-w-md">
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Buscar por título, código o resolución..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                        />
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full sm:w-auto">
                        <select
                            v-model="selectedStatus"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Todos los Estados</option>
                            <option value="in_progress">En Proceso (P)</option>
                            <option value="completed">Culminado (C)</option>
                            <option value="pending">Pendiente (PTE)</option>
                            <option value="observed">En Observación (OBS)</option>
                        </select>
                    </div>

                    <!-- Active Filter -->
                    <div class="w-full sm:w-auto">
                        <select
                            v-model="selectedIsActive"
                            @change="applyFilters"
                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium"
                        >
                            <option value="">Visibilidad: Todas</option>
                            <option value="1">Públicos / Activos</option>
                            <option value="0">Ocultos / Inactivos</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    <button
                        v-if="searchInput || selectedStatus || selectedIsActive !== ''"
                        type="button"
                        @click="clearFilters"
                        class="px-3.5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all flex items-center gap-1.5 w-full sm:w-auto justify-center"
                    >
                        <i class="bi bi-x-circle text-gray-500"></i> Limpiar
                    </button>
                </form>
            </div>

            <!-- Phases Table Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Fase / Código</th>
                                <th class="px-6 py-4">Título y Marco Legal</th>
                                <th class="px-6 py-4">Estado del Proceso</th>
                                <th class="px-6 py-4 text-center">Etapa Actual (P)</th>
                                <th class="px-6 py-4">Avance</th>
                                <th class="px-6 py-4 text-center">Publicado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            <tr
                                v-for="phase in phases.data"
                                :key="phase.id"
                                class="hover:bg-gray-50/60 transition-colors"
                                :class="phase.is_current ? 'bg-amber-50/30' : ''"
                            >
                                <!-- Phase Number & Code -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm"
                                            :class="phase.is_current ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-gray-100 text-gray-700 border border-gray-200'"
                                        >
                                            {{ phase.phase_number }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">Fase {{ phase.phase_number }}</p>
                                            <p class="text-xs text-gray-500 font-mono">{{ phase.code || `FASE-0${phase.phase_number}` }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Title & Legal Basis -->
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900 max-w-md">{{ phase.title }}</p>
                                    <div v-if="phase.resolution_number || phase.estimated_date" class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                        <span v-if="phase.resolution_number" class="inline-flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded">
                                            <i class="bi bi-file-earmark-text"></i> {{ phase.resolution_number }}
                                        </span>
                                        <span v-if="phase.estimated_date">&bull; {{ phase.estimated_date }}</span>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border"
                                        :class="getBadgeClass(phase)"
                                    >
                                        <i class="bi" :class="getBadgeIcon(phase)"></i>
                                        {{ getBadgeLabel(phase) }}
                                    </span>
                                </td>

                                <!-- Current Stage (P) Action / Indicator -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        v-if="phase.is_current"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-amber-500 text-slate-950 shadow-xs animate-pulse"
                                    >
                                        <i class="bi bi-star-fill text-xs"></i> Actual (P)
                                    </span>
                                    <button
                                        v-else
                                        type="button"
                                        @click="setCurrentStage(phase)"
                                        :disabled="settingCurrentId === phase.id"
                                        title="Establecer como la Etapa Actual (P)"
                                        class="text-xs px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-amber-100 text-gray-600 hover:text-amber-800 font-semibold transition-colors border border-gray-200 disabled:opacity-50"
                                    >
                                        <i v-if="settingCurrentId === phase.id" class="bi bi-arrow-repeat animate-spin mr-1"></i>
                                        Fijar (P)
                                    </button>
                                </td>

                                <!-- Progress Bar -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-20 bg-gray-200 h-2 rounded-full overflow-hidden">
                                            <div
                                                class="h-full rounded-full transition-all duration-300"
                                                :class="phase.status === 'completed' ? 'bg-emerald-500' : (phase.is_current ? 'bg-amber-500' : 'bg-purple-500')"
                                                :style="{ width: `${phase.progress_percentage || 0}%` }"
                                            ></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">{{ phase.progress_percentage || 0 }}%</span>
                                    </div>
                                </td>

                                <!-- Toggle Active Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button
                                        type="button"
                                        @click="toggleStatus(phase)"
                                        :disabled="togglingId === phase.id"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden disabled:opacity-50"
                                        :class="phase.is_active ? 'bg-purple-600' : 'bg-gray-200'"
                                        :title="phase.is_active ? 'Fase publicada — clic para ocultar' : 'Fase oculta — clic para publicar'"
                                    >
                                        <span
                                            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                            :class="phase.is_active ? 'translate-x-5' : 'translate-x-0'"
                                        ></span>
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.licensing.edit', phase.id)"
                                            class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                            title="Editar Fase y Estado"
                                        >
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>

                                        <button
                                            type="button"
                                            @click="confirmDelete(phase)"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Eliminar Fase"
                                        >
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!phases.data || phases.data.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto space-y-3">
                                        <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto text-2xl">
                                            <i class="bi bi-patch-question"></i>
                                        </div>
                                        <p class="text-base font-bold text-gray-700">No se encontraron fases registradas</p>
                                        <p class="text-xs text-gray-500">Comience agregando las fases del proceso de licenciamiento institucional.</p>
                                        <Link
                                            :href="route('admin.licensing.create')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-semibold hover:bg-purple-700 transition"
                                        >
                                            <i class="bi bi-plus-lg"></i> Registrar Primera Fase
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="phases.links && phases.links.length > 3" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold text-gray-700">{{ phases.from || 0 }}</span>–<span class="font-bold text-gray-700">{{ phases.to || 0 }}</span>
                        de <span class="font-bold text-gray-700">{{ phases.total }}</span> fases
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="(link, i) in phases.links" :key="i">
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

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs"
        >
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-center">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">¿Eliminar Fase de Licenciamiento?</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        ¿Estás seguro de que deseas eliminar la
                        <strong class="text-gray-800">Fase {{ phaseToDelete?.phase_number }}: {{ phaseToDelete?.title }}</strong>?
                        Esta acción no se puede deshacer y eliminará sus archivos adjuntos.
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
                        @click="deletePhase"
                        :disabled="isDeleting"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-md transition flex items-center gap-2"
                    >
                        <i v-if="isDeleting" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>{{ isDeleting ? 'Eliminando...' : 'Sí, eliminar' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
