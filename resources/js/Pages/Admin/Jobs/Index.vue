<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

const props = defineProps({
    jobs: {
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
    source: {
        type: String,
        default: '',
    },
    totalJobs: {
        type: Number,
        default: 0,
    },
    activeJobs: {
        type: Number,
        default: 0,
    },
    inactiveJobs: {
        type: Number,
        default: 0,
    },
    internalJobs: {
        type: Number,
        default: 0,
    },
})

const searchQuery = ref(props.search || '')
const statusFilter = ref(props.status || '')
const sourceFilter = ref(props.source || '')
const isSubmitting = ref(false)

// Bulk selections
const selectedJobs = ref([])
const selectAllCurrentPage = computed({
    get: () => {
        if (!props.jobs.data || props.jobs.data.length === 0) return false
        return props.jobs.data.every(j => selectedJobs.value.includes(j.id))
    },
    set: (val) => {
        if (val) {
            const pageIds = props.jobs.data.map(j => j.id)
            selectedJobs.value = Array.from(new Set([...selectedJobs.value, ...pageIds]))
        } else {
            const pageIds = props.jobs.data.map(j => j.id)
            selectedJobs.value = selectedJobs.value.filter(id => !pageIds.includes(id))
        }
    }
})

// Modals
const deleteModalOpen = ref(false)
const jobToDelete = ref(null)
const bulkDeleteModalOpen = ref(false)
const clearAllModalOpen = ref(false)

// Auto-Fetch Job Search Modal State
const fetchModalOpen = ref(false)
const isFetching = ref(false)
const fetchStatusText = ref('Conectando con los portales de empleo...')
const fetchProgressBar = ref(10)
const fetchResults = ref(null)

function openFetchModal() {
    fetchResults.value = null
    fetchStatusText.value = 'Conectando con los portales de empleo...'
    fetchProgressBar.value = 10
    fetchModalOpen.value = true
}

async function runAutoFetch() {
    isFetching.value = true
    fetchStatusText.value = 'Consultando Computrabajo Perú y Bumeran Perú...'
    fetchProgressBar.value = 35

    const progressInterval = setInterval(() => {
        if (fetchProgressBar.value < 85) {
            fetchProgressBar.value += 5
        }
    }, 1500)

    try {
        const response = await axios.post(route('admin.works.fetch-jobs'), {}, {
            timeout: 130000,
        })
        clearInterval(progressInterval)
        fetchProgressBar.value = 100
        isFetching.value = false
        fetchResults.value = response.data
        fetchStatusText.value = '¡Búsqueda y sincronización completada!'

        // Refresh Inertia data after 1.5s
        setTimeout(() => {
            router.reload({ preserveScroll: true })
        }, 1500)
    } catch (err) {
        clearInterval(progressInterval)
        isFetching.value = false
        fetchStatusText.value = 'Ocurrió un error o tiempo de espera agotado al consultar los portales.'
    }
}

function confirmDelete(job) {
    jobToDelete.value = job
    deleteModalOpen.value = true
}

function executeDelete() {
    if (!jobToDelete.value) return
    isSubmitting.value = true
    router.delete(route('admin.works.destroy', jobToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            deleteModalOpen.value = false
            jobToDelete.value = null
        }
    })
}

function executeBulkDelete() {
    if (selectedJobs.value.length === 0) return
    isSubmitting.value = true
    router.post(route('admin.works.bulk-delete'), {
        ids: selectedJobs.value
    }, {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            bulkDeleteModalOpen.value = false
            selectedJobs.value = []
        }
    })
}

function executeClearAll() {
    isSubmitting.value = true
    router.delete(route('admin.works.clear-all'), {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            clearAllModalOpen.value = false
            selectedJobs.value = []
        }
    })
}

function applyFilters() {
    router.get(route('admin.works.index'), {
        search: searchQuery.value || undefined,
        status: statusFilter.value || undefined,
        source: sourceFilter.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    })
}

function clearFilters() {
    searchQuery.value = ''
    statusFilter.value = ''
    sourceFilter.value = ''
    router.get(route('admin.works.index'))
}

function toggleStatus(job) {
    router.patch(route('admin.works.toggle-status', job.id), {}, {
        preserveScroll: true,
    })
}
</script>

<template>
    <AdminLayout title="Gestión de Bolsa de Trabajo">
        <Head title="Bolsa de Trabajo - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-700 text-white rounded-xl shadow-md shadow-purple-600/20">
                            <i class="bi bi-briefcase text-lg"></i>
                        </span>
                        Ofertas Laborales y Convocatorias
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Administra las oportunidades de empleo, convocatorias externas e inserción laboral para egresados.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Clear All button -->
                    <button @click="clearAllModalOpen = true"
                            :disabled="totalJobs === 0"
                            type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs rounded-xl transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-trash3 text-rose-600"></i>
                        <span>Vaciar Tabla</span>
                    </button>

                    <!-- Auto-Fetch Modal Trigger -->
                    <button @click="openFetchModal"
                            type="button"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition-all">
                        <i class="bi bi-cloud-download text-base"></i>
                        <span>Buscar Automáticamente</span>
                    </button>

                    <!-- New Job Link -->
                    <Link :href="route('admin.works.create')"
                          class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-purple-500/20 transition-all">
                        <i class="bi bi-plus-circle text-base"></i>
                        <span>Nueva Oferta</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                        <i class="bi bi-briefcase-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-900">{{ totalJobs }}</p>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Total Ofertas</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="bi bi-check-circle-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-emerald-600">{{ activeJobs }}</p>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Activas</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="bi bi-eye-slash-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-amber-600">{{ inactiveJobs }}</p>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Inactivas</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="bi bi-building-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-blue-600">{{ internalJobs }}</p>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase">Internas</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-3">
                <div class="relative w-full md:flex-1">
                    <input v-model="searchQuery"
                           @keyup.enter="applyFilters"
                           type="text"
                           placeholder="Buscar por título, empresa o ciudad..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

                <select v-model="statusFilter"
                        @change="applyFilters"
                        class="w-full md:w-44 py-2.5 px-3 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-medium text-gray-700">
                    <option value="">Todos los Estados</option>
                    <option value="active">Activas</option>
                    <option value="inactive">Inactivas</option>
                </select>

                <input v-model="sourceFilter"
                       @keyup.enter="applyFilters"
                       type="text"
                       placeholder="Fuente (ej. Interna)"
                       class="w-full md:w-44 px-3 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button @click="applyFilters"
                            type="button"
                            class="flex-1 md:flex-initial px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs sm:text-sm rounded-xl transition-colors flex items-center justify-center gap-1.5">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>

                    <button v-if="searchQuery || statusFilter || sourceFilter"
                            @click="clearFilters"
                            type="button"
                            class="px-3 py-2.5 text-xs sm:text-sm text-rose-600 hover:bg-rose-50 font-semibold rounded-xl transition-colors"
                            title="Limpiar Filtros">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Floating Bulk Actions Bar -->
            <div v-if="selectedJobs.length > 0"
                 class="bg-slate-900 text-white p-4 rounded-2xl shadow-xl border border-slate-800 flex items-center justify-between gap-4 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-300 flex items-center justify-center font-bold">
                        <i class="bi bi-check2-square"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-bold">
                        {{ selectedJobs.length }} {{ selectedJobs.length === 1 ? 'oferta seleccionada' : 'ofertas seleccionadas' }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="bulkDeleteModalOpen = true"
                            type="button"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-1.5">
                        <i class="bi bi-trash3"></i> Eliminar Seleccionadas
                    </button>
                    <button @click="selectedJobs = []"
                            type="button"
                            class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl transition-colors">
                        Desmarcar
                    </button>
                </div>
            </div>

            <!-- Jobs Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[750px]">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                <th class="p-4 w-12 text-center">
                                    <input type="checkbox" v-model="selectAllCurrentPage" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" />
                                </th>
                                <th class="p-4">Puesto & Empresa</th>
                                <th class="p-4 w-48">Ubicación & Fuente</th>
                                <th class="p-4 w-32">Fecha</th>
                                <th class="p-4 w-28 text-center">Estado</th>
                                <th class="p-4 w-28 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="job in jobs.data" :key="job.id" class="hover:bg-purple-50/20 transition-colors">
                                <!-- Checkbox -->
                                <td class="p-4 text-center">
                                    <input type="checkbox" :value="job.id" v-model="selectedJobs" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" />
                                </td>

                                <!-- Title and Company -->
                                <td class="p-4">
                                    <div class="font-bold text-gray-900 text-sm leading-snug line-clamp-1">
                                        {{ job.title }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                        <span class="font-semibold text-purple-700 flex items-center gap-1">
                                            <i class="bi bi-building"></i> {{ job.company }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Location & Source -->
                                <td class="p-4 text-xs text-gray-600">
                                    <div class="flex items-center gap-1 font-medium text-gray-700">
                                        <i class="bi bi-geo-alt text-rose-500"></i> {{ job.location }}
                                    </div>
                                    <div class="mt-1">
                                        <span :class="[
                                            job.source?.includes('Interna')
                                                ? 'bg-blue-50 text-blue-700 border-blue-200'
                                                : 'bg-gray-100 text-gray-700 border-gray-200'
                                        ]" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold border">
                                            {{ job.source || 'Bolsa Institucional' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="p-4 text-xs text-gray-500 font-medium">
                                    {{ new Date(job.created_at).toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                </td>

                                <!-- Status Toggle -->
                                <td class="p-4 text-center">
                                    <button @click="toggleStatus(job)"
                                            type="button"
                                            :class="[
                                                job.is_active
                                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                                                    : 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100'
                                            ]"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer">
                                        <span :class="job.is_active ? 'bg-emerald-500' : 'bg-amber-500'" class="w-1.5 h-1.5 rounded-full"></span>
                                        {{ job.is_active ? 'Activa' : 'Inactiva' }}
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a v-if="job.url && job.url !== '#'"
                                           :href="job.url"
                                           target="_blank"
                                           class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors"
                                           title="Ver publicación original">
                                            <i class="bi bi-box-arrow-up-right text-xs"></i>
                                        </a>
                                        <Link :href="route('admin.works.edit', job.id)"
                                              class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                              title="Editar oferta">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>
                                        <button @click="confirmDelete(job)"
                                                type="button"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                title="Eliminar oferta">
                                            <i class="bi bi-trash3 text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!jobs.data || jobs.data.length === 0">
                                <td colspan="6" class="p-12 text-center text-gray-400">
                                    <i class="bi bi-briefcase text-4xl mb-3 block text-gray-300"></i>
                                    <p class="font-bold text-gray-600">No se encontraron ofertas laborales</p>
                                    <p class="text-xs text-gray-400 mt-1">Usa la búsqueda automática o publica una nueva oferta.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="jobs.links && jobs.links.length > 3" class="p-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-gray-500">
                        Mostrando del <span class="font-bold">{{ jobs.from || 0 }}</span> al <span class="font-bold">{{ jobs.to || 0 }}</span> de <span class="font-bold">{{ jobs.total || 0 }}</span> ofertas
                    </p>
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(link, i) in jobs.links" :key="i">
                            <Link v-if="link.url"
                                  :href="link.url"
                                  :class="[
                                      link.active
                                          ? 'bg-purple-600 text-white font-bold shadow-sm'
                                          : 'bg-white hover:bg-gray-100 text-gray-700 border border-gray-200'
                                  ]"
                                  class="px-3 py-1.5 text-xs rounded-lg transition-all"
                                  v-html="link.label" />
                            <span v-else
                                  class="px-3 py-1.5 text-xs rounded-lg text-gray-400 bg-gray-50 border border-gray-100 cursor-not-allowed"
                                  v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto-Fetch Modal -->
        <div v-if="fetchModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 transform transition-all">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-base font-black text-gray-900 flex items-center gap-2">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <i class="bi bi-cloud-arrow-down"></i>
                        </span>
                        Búsqueda Automática de Empleos
                    </h3>
                    <button v-if="!isFetching" @click="fetchModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <div class="py-5 space-y-4">
                    <p class="text-xs text-gray-600">
                        Se explorarán los portales líderes de empleo en Perú (<strong>Computrabajo PE</strong> y <strong>Bumeran PE</strong>) buscando convocatorias relacionadas con los 5 programas de estudio del instituto.
                    </p>

                    <!-- In Progress indicator -->
                    <div v-if="isFetching" class="space-y-3 p-4 bg-emerald-50/50 rounded-xl border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-arrow-repeat text-2xl text-emerald-600 animate-spin"></i>
                            <span class="text-xs font-bold text-emerald-800">{{ fetchStatusText }}</span>
                        </div>
                        <div class="w-full bg-emerald-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full transition-all duration-700" :style="{ width: `${fetchProgressBar}%` }"></div>
                        </div>
                        <p class="text-[11px] text-gray-500">Este proceso puede tardar entre 40 a 90 segundos. Por favor espera sin cerrar esta ventana.</p>
                    </div>

                    <!-- Results block if finished -->
                    <div v-else-if="fetchResults" class="space-y-3">
                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-800 font-bold flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i>
                            {{ fetchResults.message || 'Sincronización finalizada correctamente' }}
                        </div>

                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-200">
                                <p class="text-xl font-black text-emerald-700">{{ fetchResults.saved ?? 0 }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Nuevas</p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-xl border border-blue-200">
                                <p class="text-xl font-black text-blue-700">{{ fetchResults.updated ?? 0 }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Actualizadas</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <p class="text-xl font-black text-gray-700">{{ fetchResults.skipped ?? 0 }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Sin cambios</p>
                            </div>
                        </div>
                    </div>

                    <!-- Initial Ready state -->
                    <div v-else class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-800 flex items-start gap-2.5">
                        <i class="bi bi-info-circle-fill text-amber-600 text-base shrink-0 mt-0.5"></i>
                        <span>
                            La búsqueda filtrará automáticamente empleos dentro del territorio nacional peruano para garantizar oportunidades válidas y actualizadas.
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button v-if="!isFetching"
                            @click="fetchModalOpen = false"
                            type="button"
                            class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        {{ fetchResults ? 'Cerrar' : 'Cancelar' }}
                    </button>
                    <button v-if="!fetchResults"
                            @click="runAutoFetch"
                            :disabled="isFetching"
                            type="button"
                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition-all disabled:opacity-50">
                        <span v-if="isFetching">Buscando Ofertas...</span>
                        <span v-else>Iniciar Búsqueda Ahora</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Single Delete Modal -->
        <div v-if="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="bi bi-trash3"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 text-center">¿Eliminar esta oferta laboral?</h3>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Se eliminará permanentemente la oferta "<strong class="text-gray-800">{{ jobToDelete?.title }}</strong>" de {{ jobToDelete?.company }}.
                </p>
                <div class="flex items-center gap-3 mt-6">
                    <button @click="deleteModalOpen = false"
                            type="button"
                            class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button @click="executeDelete"
                            :disabled="isSubmitting"
                            type="button"
                            class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/20 transition-colors disabled:opacity-50">
                        <span v-if="isSubmitting">Eliminando...</span>
                        <span v-else>Sí, Eliminar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Delete Modal -->
        <div v-if="bulkDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="bi bi-trash3-fill"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 text-center">¿Eliminar ofertas seleccionadas?</h3>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Se eliminarán permanentemente <strong class="text-rose-600">{{ selectedJobs.length }} ofertas laborales</strong> seleccionadas.
                </p>
                <div class="flex items-center gap-3 mt-6">
                    <button @click="bulkDeleteModalOpen = false"
                            type="button"
                            class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button @click="executeBulkDelete"
                            :disabled="isSubmitting"
                            type="button"
                            class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/20 transition-colors disabled:opacity-50">
                        <span v-if="isSubmitting">Eliminando...</span>
                        <span v-else>Sí, Eliminar Seleccionadas</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Clear All Modal -->
        <div v-if="clearAllModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 text-center">¿Vaciar completamente la tabla?</h3>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Esta acción es irreversible y eliminará las <strong class="text-rose-600">{{ totalJobs }} ofertas</strong> registradas en la base de datos.
                </p>
                <div class="flex items-center gap-3 mt-6">
                    <button @click="clearAllModalOpen = false"
                            type="button"
                            class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button @click="executeClearAll"
                            :disabled="isSubmitting"
                            type="button"
                            class="flex-1 py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-600/20 transition-colors disabled:opacity-50">
                        <span v-if="isSubmitting">Vaciando...</span>
                        <span v-else>Sí, Vaciar Todo</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
