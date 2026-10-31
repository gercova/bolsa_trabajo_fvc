<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    histories: {
        type: Object,
        required: true
    },
    search: {
        type: String,
        default: ''
    },
    status: {
        type: String,
        default: ''
    }
})

const searchQuery = ref(props.search || '')
const statusFilter = ref(props.status !== null && props.status !== undefined ? props.status : '')
const isSubmitting = ref(false)

// Image preview modal
const previewModalOpen = ref(false)
const previewImageSrc = ref('')
const previewImageTitle = ref('')

function openImagePreview(url, title) {
    previewImageSrc.value = url
    previewImageTitle.value = title
    previewModalOpen.value = true
}

// Delete confirmation modal
const deleteModalOpen = ref(false)
const historyToDelete = ref(null)

function confirmDelete(history) {
    historyToDelete.value = history
    deleteModalOpen.value = true
}

function executeDelete() {
    if (!historyToDelete.value) return
    isSubmitting.value = true
    router.delete(route('admin.history.destroy', historyToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            deleteModalOpen.value = false
            historyToDelete.value = null
        }
    })
}

function applyFilters() {
    router.get(route('admin.history.index'), {
        search: searchQuery.value || undefined,
        status: statusFilter.value !== '' ? statusFilter.value : undefined
    }, {
        preserveState: true,
        replace: true
    })
}

function clearFilters() {
    searchQuery.value = ''
    statusFilter.value = ''
    router.get(route('admin.history.index'))
}

function toggleStatus(history) {
    router.patch(route('admin.history.toggle-status', history.id), {}, {
        preserveScroll: true
    })
}
</script>

<template>
    <AdminLayout title="Historia Institucional">
        <Head title="Historia Institucional - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-clock-history text-lg"></i>
                        </span>
                        Historia Institucional
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Gestiona los hitos, etapas cronológicas y fotografías de la trayectoria del instituto.
                    </p>
                </div>

                <div class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="bi bi-building mr-1"></i> Empresa
                    <i class="bi bi-chevron-right mx-2 text-[10px] text-gray-400"></i>
                    <span class="text-purple-600 font-bold">Historia Institucional</span>
                </div>
            </div>

            <!-- Filter & Action Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                    <div class="relative w-full sm:max-w-md">
                        <input v-model="searchQuery"
                               @keyup.enter="applyFilters"
                               type="text"
                               placeholder="Buscar por título, año o contenido..."
                               class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <div class="w-full sm:w-auto">
                        <select v-model="statusFilter"
                                @change="applyFilters"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                            <option value="">Todos los Estados</option>
                            <option value="active">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="button"
                                @click="applyFilters"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button v-if="searchQuery || statusFilter !== ''"
                                type="button"
                                @click="clearFilters"
                                class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition cursor-pointer">
                            Limpiar
                        </button>
                    </div>
                </div>

                <Link :href="route('admin.history.create')"
                      class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                    <i class="bi bi-plus-circle-fill text-base"></i>
                    <span>Nuevo Hito Histórico</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="p-4 w-20 text-center">Foto</th>
                                <th class="p-4">Etapa / Hito Histórico</th>
                                <th class="p-4">Período</th>
                                <th class="p-4">Descripción</th>
                                <th class="p-4 text-center w-24">Orden</th>
                                <th class="p-4 text-center w-28">Estado</th>
                                <th class="p-4 text-center w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="item in histories.data"
                                :key="item.id"
                                class="hover:bg-purple-50/30 transition-colors">
                                <!-- Foto / Miniatura -->
                                <td class="p-4 text-center">
                                    <div v-if="item.image_url"
                                         @click="openImagePreview(item.image_url, item.title)"
                                         class="relative group mx-auto w-14 h-14 rounded-xl border border-gray-200 overflow-hidden shadow-sm bg-gray-50 flex items-center justify-center cursor-pointer">
                                        <img :src="item.image_url"
                                             :alt="item.title"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i class="bi bi-zoom-in text-sm"></i>
                                        </div>
                                    </div>
                                    <div v-else
                                         class="w-14 h-14 mx-auto rounded-xl bg-purple-50 border border-purple-100 text-purple-400 flex flex-col items-center justify-center text-xs font-semibold shadow-inner">
                                        <i class="bi bi-image text-lg"></i>
                                        <span class="text-[9px] uppercase tracking-wider text-purple-300">Sin foto</span>
                                    </div>
                                </td>

                                <!-- Título -->
                                <td class="p-4">
                                    <div class="font-bold text-gray-900 text-base">
                                        {{ item.title }}
                                    </div>
                                </td>

                                <!-- Período (Años) -->
                                <td class="p-4 whitespace-nowrap">
                                    <span v-if="item.start_year"
                                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="bi bi-calendar3 mr-1 text-[10px]"></i>
                                        {{ item.start_year }} - {{ item.end_year ?? 'Presente' }}
                                    </span>
                                    <span v-else class="text-xs text-gray-400 italic">No especificado</span>
                                </td>

                                <!-- Descripción -->
                                <td class="p-4">
                                    <p class="text-xs text-gray-600 line-clamp-2 max-w-sm leading-relaxed">
                                        {{ item.description }}
                                    </p>
                                </td>

                                <!-- Orden -->
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-gray-100 text-gray-700 border border-gray-200">
                                        #{{ item.order }}
                                    </span>
                                </td>

                                <!-- Estado interactivo -->
                                <td class="p-4 text-center">
                                    <button type="button"
                                            @click="toggleStatus(item)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition shadow-sm cursor-pointer"
                                            :class="item.is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200'">
                                        <span class="w-2 h-2 rounded-full" :class="item.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></span>
                                        <span>{{ item.is_active ? 'Activo' : 'Inactivo' }}</span>
                                    </button>
                                </td>

                                <!-- Acciones -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <Link :href="route('admin.history.edit', item.id)"
                                              class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                              title="Editar hito histórico">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>
                                        <button type="button"
                                                @click="confirmDelete(item)"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                                title="Eliminar hito">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="histories.data.length === 0">
                                <td colspan="7" class="p-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-clock-history text-4xl text-gray-300 mb-2"></i>
                                        <p class="font-bold text-gray-700">No se encontraron hitos históricos</p>
                                        <p class="text-xs text-gray-400 mt-1">Intenta con otros filtros o crea un nuevo hito.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="histories.links && histories.links.length > 3" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold">{{ histories.from || 0 }}</span> a <span class="font-bold">{{ histories.to || 0 }}</span> de <span class="font-bold">{{ histories.total }}</span> resultados
                    </p>
                    <div class="flex items-center gap-1">
                        <Link v-for="(link, i) in histories.links"
                              :key="i"
                              :href="link.url || '#'"
                              :class="[
                                  'px-3 py-1.5 rounded-lg text-xs font-semibold transition',
                                  link.active ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100',
                                  !link.url ? 'opacity-40 pointer-events-none' : ''
                              ]"
                              v-html="link.label" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div v-if="previewModalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 animate-fade-in"
             @click.self="previewModalOpen = false">
            <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden shadow-2xl border border-gray-100">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 truncate">{{ previewImageTitle }}</h3>
                    <button type="button" @click="previewModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="bi bi-x-lg text-base"></i>
                    </button>
                </div>
                <div class="p-4 flex items-center justify-center max-h-[70vh] overflow-hidden bg-gray-50">
                    <img :src="previewImageSrc" class="max-h-[65vh] w-auto object-contain rounded-lg shadow-sm">
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="deleteModalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in"
             @click.self="deleteModalOpen = false">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 space-y-4">
                <div class="flex items-center gap-3 text-rose-600">
                    <div class="p-3 bg-rose-50 rounded-xl">
                        <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Eliminar Hito Histórico</h3>
                        <p class="text-xs text-gray-500">Esta acción no se puede deshacer.</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600">
                    ¿Estás seguro de que deseas eliminar permanentemente el hito <strong class="text-gray-900">«{{ historyToDelete?.title }}»</strong>?
                </p>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button"
                            @click="deleteModalOpen = false"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="button"
                            :disabled="isSubmitting"
                            @click="executeDelete"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl transition disabled:opacity-50 flex items-center gap-2">
                        <svg v-if="isSubmitting" class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ isSubmitting ? 'Eliminando...' : 'Sí, eliminar' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
