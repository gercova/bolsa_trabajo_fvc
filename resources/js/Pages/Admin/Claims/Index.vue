<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    claims: {
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
})

const searchInput       = ref(props.search || '')
const statusFilter      = ref(props.status || '')

// Detail & status update modal
const showModal         = ref(false)
const selectedClaim     = ref(null)
const selectedStatus    = ref('')
const isUpdatingStatus  = ref(false)

function filter() {
    router.get(route('admin.claims.index'), {
        search: searchInput.value || undefined,
        status: statusFilter.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    })
}

function clearFilters() {
    searchInput.value = ''
    statusFilter.value = ''
    router.get(route('admin.claims.index'))
}

function openDetailModal(claim) {
    selectedClaim.value = claim
    selectedStatus.value = claim.status || 'pendiente'
    showModal.value = true
}

function updateStatus() {
    if (!selectedClaim.value) return
    isUpdatingStatus.value = true

    router.patch(route('admin.claims.status', selectedClaim.value.id), {
        status: selectedStatus.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false
            selectedClaim.value = null
        },
        onFinish: () => {
            isUpdatingStatus.value = false
        },
    })
}

function formatDate(dateStr) {
    if (!dateStr) return '-'
    const d = new Date(dateStr)
    return d.toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}
</script>

<template>
    <AdminLayout title="Gestión de Reclamos">
        <Head title="Libro de Reclamaciones - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-xl shadow-md shadow-blue-500/20">
                            <i class="bi bi-bookmark-x text-lg"></i>
                        </span>
                        Libro de Reclamaciones Virtual
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Gestiona las quejas, reclamos y sugerencias presentadas por la comunidad académica y público en general.
                    </p>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="w-full md:w-1/2 relative">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input v-model="searchInput"
                           @keyup.enter="filter"
                           type="text"
                           placeholder="Buscar por ID, nombres, DNI o correo..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" />
                </div>

                <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <select v-model="statusFilter"
                            @change="filter"
                            class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="leido">Leído</option>
                        <option value="respondido">Respondido</option>
                        <option value="cerrado">Cerrado</option>
                    </select>

                    <button @click="filter"
                            type="button"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm font-bold text-xs">
                        <i class="bi bi-filter"></i> Filtrar
                    </button>

                    <button v-if="search || status"
                            @click="clearFilters"
                            type="button"
                            class="border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 text-xs">
                        Limpiar
                    </button>
                </div>
            </div>

            <!-- Table of Claims -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[850px]">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                                <th class="p-4 w-16">ID</th>
                                <th class="p-4 w-28">DNI</th>
                                <th class="p-4">Remitente</th>
                                <th class="p-4">Asunto / Tipo</th>
                                <th class="p-4">Fecha Registro</th>
                                <th class="p-4 w-32">Estado</th>
                                <th class="p-4 text-center w-24">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            <tr v-for="claim in claims.data"
                                :key="claim.id"
                                class="hover:bg-blue-50/30 transition-colors">
                                <td class="p-4 font-mono font-bold text-slate-500">
                                    #{{ claim.id }}
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    {{ claim.dni }}
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-900">{{ claim.names }}</div>
                                    <div class="text-[11px] text-slate-400">{{ claim.email }}</div>
                                </td>
                                <td class="p-4 text-slate-700 font-medium">
                                    {{ claim.subject }}
                                </td>
                                <td class="p-4 text-slate-500">
                                    {{ formatDate(claim.created_at) }}
                                </td>
                                <td class="p-4">
                                    <span v-if="claim.status === 'pendiente'"
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Pendiente
                                    </span>
                                    <span v-else-if="claim.status === 'leido'"
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-blue-500"></span> Leído
                                    </span>
                                    <span v-else-if="claim.status === 'respondido'"
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Respondido
                                    </span>
                                    <span v-else
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span> Cerrado
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button @click="openDetailModal(claim)"
                                            type="button"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all"
                                            title="Ver Detalle y Gestionar">
                                        <i class="bi bi-eye-fill text-sm"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="!claims.data || claims.data.length === 0">
                                <td colspan="7" class="p-12 text-center text-gray-400">
                                    <i class="bi bi-inbox text-4xl mb-2 block text-gray-300"></i>
                                    <p class="font-bold text-gray-600 text-sm">No se encontraron reclamos registrados</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Los reclamos enviados a través del portal institucional aparecerán aquí.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="claims.links && claims.links.length > 3"
                     class="px-6 py-4 bg-gray-50/75 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold text-gray-800">{{ claims.from || 0 }}</span> a <span class="font-bold text-gray-800">{{ claims.to || 0 }}</span> de <span class="font-bold text-gray-800">{{ claims.total }}</span> registros
                    </p>

                    <div class="flex items-center gap-1">
                        <Link v-for="(link, idx) in claims.links"
                              :key="idx"
                              :href="link.url || '#'"
                              v-html="link.label"
                              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                              :class="[
                                  link.active ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-200',
                                  !link.url ? 'opacity-40 cursor-not-allowed hover:bg-transparent' : ''
                              ]" />
                    </div>
                </div>
            </div>

            <!-- Detail & Status Modal -->
            <div v-if="showModal && selectedClaim" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-xl border border-gray-100 space-y-4 max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h3 class="text-sm font-extrabold text-gray-800 flex items-center gap-2">
                            <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span>
                            Detalle de Reclamación #{{ selectedClaim.id }}
                        </h3>
                        <button @click="showModal = false" type="button" class="text-gray-400 hover:text-gray-600">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>

                    <!-- Claim Info -->
                    <div class="space-y-4 text-xs">
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Nombres y Apellidos</span>
                                <span class="font-bold text-slate-800">{{ selectedClaim.names }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Documento (DNI/CE)</span>
                                <span class="font-bold text-slate-800">{{ selectedClaim.dni }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Correo Electrónico</span>
                                <a :href="`mailto:${selectedClaim.email}`" class="font-bold text-blue-600 hover:underline">
                                    {{ selectedClaim.email }}
                                </a>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Fecha de Envío</span>
                                <span class="font-bold text-slate-800">{{ formatDate(selectedClaim.created_at) }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Asunto / Tipo de Incidencia</span>
                            <span class="font-bold text-slate-800 text-sm">{{ selectedClaim.subject }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Descripción del Reclamo / Queja</span>
                            <p class="bg-slate-50/50 border border-slate-100 rounded-xl p-3.5 text-slate-700 leading-relaxed max-h-40 overflow-y-auto whitespace-pre-wrap">
                                {{ selectedClaim.message }}
                            </p>
                        </div>

                        <!-- Sustento Attachment -->
                        <div v-if="selectedClaim.file_path">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1.5">Archivo Adjunto Sustentatorio</span>
                            <a :href="`/storage/${selectedClaim.file_path}`"
                               target="_blank"
                               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-bold transition-all bg-blue-50 border border-blue-100 px-3.5 py-2 rounded-xl">
                                <i class="bi bi-file-earmark-arrow-down-fill text-base"></i> Descargar Documento Adjunto
                            </a>
                        </div>

                        <!-- Update Status Form -->
                        <div class="pt-2 border-t border-gray-100">
                            <label class="block text-slate-700 font-bold mb-1.5">Actualizar Estado de la Solicitud</label>
                            <select v-model="selectedStatus"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="pendiente">Pendiente</option>
                                <option value="leido">Leído</option>
                                <option value="respondido">Respondido</option>
                                <option value="cerrado">Cerrado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-3 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-2 rounded-b-2xl">
                        <button @click="updateStatus"
                                :disabled="isUpdatingStatus"
                                type="button"
                                class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-xs font-bold text-white hover:bg-blue-700 transition-all disabled:opacity-60">
                            <span v-if="isUpdatingStatus">Guardando...</span>
                            <span v-else class="flex items-center gap-1.5"><i class="bi bi-floppy-fill"></i> Guardar Cambios</span>
                        </button>
                        <button @click="showModal = false"
                                type="button"
                                class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 transition-all">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
