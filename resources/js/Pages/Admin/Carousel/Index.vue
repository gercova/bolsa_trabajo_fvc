<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    carousels: {
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
const carouselToDelete = ref(null)

function confirmDelete(carousel) {
    carouselToDelete.value = carousel
    deleteModalOpen.value = true
}

function executeDelete() {
    if (!carouselToDelete.value) return
    isSubmitting.value = true
    router.delete(route('admin.carousel.destroy', carouselToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            deleteModalOpen.value = false
            carouselToDelete.value = null
        }
    })
}

function applyFilters() {
    router.get(route('admin.carousel.index'), {
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
    router.get(route('admin.carousel.index'))
}

function toggleStatus(carousel) {
    router.patch(route('admin.carousel.toggle-status', carousel.id), {}, {
        preserveScroll: true
    })
}

const colorBadgeMap = {
    amber: { bg: 'bg-amber-500', text: 'Amber / Dorado' },
    sky: { bg: 'bg-sky-500', text: 'Sky / Celeste' },
    rose: { bg: 'bg-rose-500', text: 'Rose / Rosa' },
    emerald: { bg: 'bg-emerald-500', text: 'Emerald / Verde' },
    indigo: { bg: 'bg-indigo-500', text: 'Indigo / Azul' },
    purple: { bg: 'bg-purple-500', text: 'Purple / Morado' }
}
</script>

<template>
    <AdminLayout title="Carrusel Institucional">
        <Head title="Carrusel Institucional - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-images text-lg"></i>
                        </span>
                        Carrusel Institucional (Portada)
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Gestiona las diapositivas del carrusel de la página de inicio, enlaces de acción y colores por carrera.
                    </p>
                </div>

                <div class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="bi bi-building mr-1"></i> Empresa
                    <i class="bi bi-chevron-right mx-2 text-[10px] text-gray-400"></i>
                    <span class="text-purple-600 font-bold">Carrusel Principal</span>
                </div>
            </div>

            <!-- Filter & Action Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                    <div class="relative w-full sm:max-w-md">
                        <input v-model="searchQuery"
                               @keyup.enter="applyFilters"
                               type="text"
                               placeholder="Buscar por título, texto resaltado o etiqueta..."
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

                <Link :href="route('admin.carousel.create')"
                      class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                    <i class="bi bi-plus-circle-fill text-base"></i>
                    <span>Nueva Diapositiva</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="p-4 w-28 text-center">Imagen</th>
                                <th class="p-4">Diapositiva / Contenido</th>
                                <th class="p-4">Etiqueta & Color</th>
                                <th class="p-4">Botones de Acción</th>
                                <th class="p-4 text-center w-20">Orden</th>
                                <th class="p-4 text-center w-28">Estado</th>
                                <th class="p-4 text-center w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="item in carousels.data"
                                :key="item.id"
                                class="hover:bg-purple-50/30 transition-colors">
                                <!-- Imagen / Miniatura -->
                                <td class="p-4 text-center">
                                    <div @click="openImagePreview(item.image_url, item.title)"
                                         class="relative group mx-auto w-24 h-16 rounded-xl border border-gray-200 overflow-hidden shadow-sm bg-slate-900 flex items-center justify-center cursor-pointer">
                                        <img :src="item.image_url"
                                             :alt="item.title"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i class="bi bi-zoom-in text-base"></i>
                                        </div>
                                    </div>
                                </td>

                                <!-- Contenido del Slide -->
                                <td class="p-4 max-w-xs">
                                    <div class="space-y-1">
                                        <div class="font-bold text-gray-900 text-sm leading-snug">
                                            {{ item.title }}
                                        </div>
                                        <div v-if="item.highlight_text"
                                             class="inline-block px-2 py-0.5 rounded-md text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="bi bi-stars text-purple-600 mr-1"></i>{{ item.highlight_text }}
                                        </div>
                                        <p v-if="item.description" class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                            {{ item.description }}
                                        </p>
                                    </div>
                                </td>

                                <!-- Etiqueta & Color -->
                                <td class="p-4 whitespace-nowrap">
                                    <div class="space-y-1.5">
                                        <span v-if="item.tag"
                                              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                            <i class="bi" :class="item.tag_icon || 'bi-tag-fill'" style="color: #9333ea;"></i>
                                            <span class="truncate max-w-[150px]">{{ item.tag }}</span>
                                        </span>
                                        <span v-else class="text-xs text-gray-400 italic">Sin etiqueta</span>

                                        <div>
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-gray-600">
                                                <span class="w-2.5 h-2.5 rounded-full" :class="colorBadgeMap[item.tag_color]?.bg || 'bg-amber-500'"></span>
                                                {{ colorBadgeMap[item.tag_color]?.text || 'Amber / Dorado' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Botones de Acción -->
                                <td class="p-4">
                                    <div class="space-y-1 text-xs">
                                        <div v-if="item.primary_button_text" class="flex items-center gap-1.5 text-blue-700 font-medium truncate max-w-[180px]">
                                            <i class="bi" :class="item.primary_button_icon || 'bi-link'"></i>
                                            <span class="font-semibold">{{ item.primary_button_text }}</span>
                                        </div>
                                        <div v-if="item.secondary_button_text" class="flex items-center gap-1.5 text-slate-600 font-medium truncate max-w-[180px]">
                                            <i class="bi" :class="item.secondary_button_icon || 'bi-link'"></i>
                                            <span>{{ item.secondary_button_text }}</span>
                                        </div>
                                        <span v-if="!item.primary_button_text && !item.secondary_button_text" class="text-xs text-gray-400 italic">
                                            Sin botones
                                        </span>
                                    </div>
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
                                        <Link :href="route('admin.carousel.edit', item.id)"
                                              class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                              title="Editar diapositiva">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>
                                        <button type="button"
                                                @click="confirmDelete(item)"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                                title="Eliminar diapositiva">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="carousels.data.length === 0">
                                <td colspan="7" class="p-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-images text-4xl text-gray-300 mb-2"></i>
                                        <p class="font-bold text-gray-700">No se encontraron diapositivas</p>
                                        <p class="text-xs text-gray-400 mt-1">Intenta con otros filtros o crea una nueva diapositiva.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="carousels.links && carousels.links.length > 3" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold">{{ carousels.from || 0 }}</span> a <span class="font-bold">{{ carousels.to || 0 }}</span> de <span class="font-bold">{{ carousels.total }}</span> resultados
                    </p>
                    <div class="flex items-center gap-1">
                        <Link v-for="(link, i) in carousels.links"
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
            <div class="bg-slate-900 rounded-2xl max-w-3xl w-full overflow-hidden shadow-2xl border border-slate-700">
                <div class="px-5 py-3 border-b border-slate-800 flex items-center justify-between text-white">
                    <h3 class="text-sm font-bold truncate">{{ previewImageTitle }}</h3>
                    <button type="button" @click="previewModalOpen = false" class="text-slate-400 hover:text-white p-1">
                        <i class="bi bi-x-lg text-base"></i>
                    </button>
                </div>
                <div class="p-4 flex items-center justify-center max-h-[70vh] overflow-hidden bg-black">
                    <img :src="previewImageSrc" class="max-h-[65vh] w-auto object-contain rounded-lg">
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
                        <h3 class="text-base font-bold text-gray-900">Eliminar Diapositiva</h3>
                        <p class="text-xs text-gray-500">Esta acción no se puede deshacer.</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600">
                    ¿Estás seguro de que deseas eliminar permanentemente la diapositiva <strong class="text-gray-900">«{{ carouselToDelete?.title }}»</strong> y su imagen asociada?
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
