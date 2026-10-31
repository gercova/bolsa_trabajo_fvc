<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    partners: {
        type: Object,
        required: true
    },
    search: {
        type: String,
        default: ''
    }
})

const searchQuery = ref(props.search || '')
const isSubmitting = ref(false)

// Delete modal
const deleteModalOpen = ref(false)
const partnerToDelete = ref(null)

function confirmDelete(partner) {
    partnerToDelete.value = partner
    deleteModalOpen.value = true
}

function executeDelete() {
    if (!partnerToDelete.value) return
    isSubmitting.value = true
    router.delete(route('admin.partners.destroy', partnerToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            deleteModalOpen.value = false
            partnerToDelete.value = null
        }
    })
}

function applyFilters() {
    router.get(route('admin.partners.index'), {
        search: searchQuery.value || undefined
    }, {
        preserveState: true,
        replace: true
    })
}

function clearFilters() {
    searchQuery.value = ''
    router.get(route('admin.partners.index'))
}

function toggleStatus(partner) {
    router.patch(route('admin.partners.toggle-status', partner.id), {}, {
        preserveScroll: true
    })
}
</script>

<template>
    <AdminLayout title="Gestión de Socios">
        <Head title="Gestión de Socios - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-people text-lg"></i>
                        </span>
                        Gestión de Socios / Aliados
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Empresas colaboradoras e instituciones aliadas para convenios de empleabilidad y prácticas.
                    </p>
                </div>

                <div class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-[10px] text-gray-400"></i>
                    <span class="text-purple-600 font-bold">Socios</span>
                </div>
            </div>

            <!-- Filter & Action Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="w-full sm:w-auto flex-1 flex items-center gap-3">
                    <div class="relative w-full sm:max-w-md">
                        <input v-model="searchQuery"
                               @keyup.enter="applyFilters"
                               type="text"
                               placeholder="Buscar por empresa o socio..."
                               class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <button type="button"
                            @click="applyFilters"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    <button v-if="searchQuery"
                            type="button"
                            @click="clearFilters"
                            class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition cursor-pointer">
                        Limpiar
                    </button>
                </div>

                <Link :href="route('admin.partners.create')"
                      class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                    <i class="bi bi-plus-circle-fill text-base"></i>
                    <span>Nuevo Partner</span>
                </Link>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="p-4">Partner / Empresa</th>
                                <th class="p-4">Enlace Web</th>
                                <th class="p-4 text-center w-32">Estado</th>
                                <th class="p-4 text-center w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="item in partners.data"
                                :key="item.id"
                                class="hover:bg-purple-50/30 transition-colors">
                                <!-- Partner Name & Logo -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div v-if="item.image_url"
                                             class="w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden shrink-0 p-1 shadow-sm">
                                            <img :src="item.logo_url || `/storage/${item.image_url}`"
                                                 :alt="item.company"
                                                 class="max-w-full max-h-full object-contain">
                                        </div>
                                        <div v-else
                                             class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-700 font-bold shrink-0 uppercase shadow-inner text-base">
                                            {{ item.company ? item.company.substring(0, 2) : 'PA' }}
                                        </div>

                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ item.company }}</div>
                                            <div class="text-[11px] text-gray-400">ID: #{{ item.id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Link -->
                                <td class="p-4">
                                    <a v-if="item.link"
                                       :href="item.link"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1.5 text-xs text-purple-600 hover:text-purple-800 font-semibold hover:underline truncate max-w-xs">
                                        <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                                        <span>{{ item.link }}</span>
                                    </a>
                                    <span v-else class="text-xs text-gray-400 italic">Sin enlace</span>
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
                                        <Link :href="route('admin.partners.edit', item.id)"
                                              class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                              title="Editar partner">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>
                                        <button type="button"
                                                @click="confirmDelete(item)"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                                title="Eliminar partner">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="partners.data.length === 0">
                                <td colspan="4" class="p-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-building-slash text-4xl text-gray-300 mb-2"></i>
                                        <p class="font-bold text-gray-700">No se encontraron socios registrados</p>
                                        <p class="text-xs text-gray-400 mt-1">Registra alianzas estratégicas para enriquecer la bolsa de trabajo.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="partners.links && partners.links.length > 3" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Mostrando <span class="font-bold">{{ partners.from || 0 }}</span> a <span class="font-bold">{{ partners.to || 0 }}</span> de <span class="font-bold">{{ partners.total }}</span> resultados
                    </p>
                    <div class="flex items-center gap-1">
                        <Link v-for="(link, i) in partners.links"
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
                        <h3 class="text-base font-bold text-gray-900">Eliminar Partner</h3>
                        <p class="text-xs text-gray-500">Esta acción no se puede deshacer.</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600">
                    ¿Estás seguro de que deseas eliminar permanentemente a <strong class="text-gray-900">«{{ partnerToDelete?.company }}»</strong> y su logo asociado?
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
