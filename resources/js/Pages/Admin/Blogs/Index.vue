<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    blogs: {
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
    },
    totalBlogs: {
        type: Number,
        default: 0
    },
    publishedBlogs: {
        type: Number,
        default: 0
    },
    draftBlogs: {
        type: Number,
        default: 0
    }
})

const searchQuery = ref(props.search || '')
const statusFilter = ref(props.status || '')
const isSubmitting = ref(false)

// Delete modal
const deleteModalOpen = ref(false)
const blogToDelete = ref(null)

function confirmDelete(blog) {
    blogToDelete.value = blog
    deleteModalOpen.value = true
}

function executeDelete() {
    if (!blogToDelete.value) return
    isSubmitting.value = true
    router.delete(route('admin.blogs.destroy', blogToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false
            deleteModalOpen.value = false
            blogToDelete.value = null
        }
    })
}

function applyFilters() {
    router.get(route('admin.blogs.index'), {
        search: searchQuery.value || undefined,
        status: statusFilter.value || undefined
    }, {
        preserveState: true,
        replace: true
    })
}

function clearFilters() {
    searchQuery.value = ''
    statusFilter.value = ''
    router.get(route('admin.blogs.index'))
}

function toggleStatus(blog) {
    router.patch(route('admin.blogs.toggle-status', blog.id), {}, {
        preserveScroll: true
    })
}

function getImageUrl(blog) {
    if (blog.images && blog.images.length > 0) {
        return blog.images[0].path.startsWith('http')
            ? blog.images[0].path
            : `/storage/${blog.images[0].path}`
    }
    return null
}
</script>

<template>
    <AdminLayout title="Gestión de Noticias y Blogs">
        <Head title="Gestión de Noticias y Blogs - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-xl shadow-md shadow-indigo-500/20">
                            <i class="bi bi-newspaper text-lg"></i>
                        </span>
                        Noticias y Artículos Institucionales
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Crea, edita y difunde noticias, comunicados y publicaciones del IESTP Francisco Vigo Caballero.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link :href="route('admin.blogs.create')"
                          class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all transform active:scale-95">
                        <i class="bi bi-plus-circle text-lg"></i>
                        <span>Nueva Noticia</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                        <i class="bi bi-journals"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-gray-900">{{ totalBlogs }}</p>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Noticias</p>
                    </div>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-emerald-600">{{ publishedBlogs }}</p>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Publicadas</p>
                    </div>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-amber-600">{{ draftBlogs }}</p>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Borradores</p>
                    </div>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="w-full sm:w-auto flex-1 flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full sm:max-w-md">
                        <input v-model="searchQuery"
                               @keyup.enter="applyFilters"
                               type="text"
                               placeholder="Buscar por título o contenido..."
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <select v-model="statusFilter"
                            @change="applyFilters"
                            class="w-full sm:w-48 py-2.5 px-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-medium text-gray-700">
                        <option value="">Todos los Estados</option>
                        <option value="published">Publicados</option>
                        <option value="draft">Borradores</option>
                    </select>

                    <button @click="applyFilters"
                            type="button"
                            class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors flex items-center justify-center gap-1.5">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>

                    <button v-if="searchQuery || statusFilter"
                            @click="clearFilters"
                            type="button"
                            class="px-3 py-2.5 text-sm text-rose-600 hover:bg-rose-50 font-semibold rounded-xl transition-colors"
                            title="Limpiar Filtros">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Blogs Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                <th class="p-4 w-20 text-center">Portada</th>
                                <th class="p-4">Título & Resumen</th>
                                <th class="p-4 w-40">Fecha de Creación</th>
                                <th class="p-4 w-36 text-center">Estado</th>
                                <th class="p-4 w-28 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-for="blog in blogs.data" :key="blog.id" class="hover:bg-purple-50/20 transition-colors">
                                <!-- Thumbnail -->
                                <td class="p-4 text-center">
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center mx-auto shadow-sm">
                                        <img v-if="getImageUrl(blog)" :src="getImageUrl(blog)" :alt="blog.title" class="w-full h-full object-cover">
                                        <i v-else class="bi bi-image text-gray-400 text-xl"></i>
                                    </div>
                                </td>

                                <!-- Title and Snippet -->
                                <td class="p-4">
                                    <div class="font-bold text-gray-900 text-base leading-snug line-clamp-1">
                                        {{ blog.title }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                        {{ blog.content }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-mono font-medium text-gray-400 bg-gray-50 px-2 py-0.5 rounded border border-gray-200">
                                            <i class="bi bi-link-45deg"></i> /{{ blog.slug }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="p-4 text-xs text-gray-500">
                                    <div class="flex items-center gap-1.5 font-medium text-gray-700">
                                        <i class="bi bi-calendar3 text-gray-400"></i>
                                        {{ new Date(blog.created_at).toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                                    </div>
                                </td>

                                <!-- Status Toggle -->
                                <td class="p-4 text-center">
                                    <button @click="toggleStatus(blog)"
                                            type="button"
                                            :class="[
                                                blog.is_published
                                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                                                    : 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100'
                                            ]"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer">
                                        <span :class="blog.is_published ? 'bg-emerald-500' : 'bg-amber-500'" class="w-1.5 h-1.5 rounded-full animate-pulse"></span>
                                        {{ blog.is_published ? 'Publicado' : 'Borrador' }}
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <Link :href="route('admin.blogs.edit', blog.id)"
                                              class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                              title="Editar noticia">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </Link>
                                        <button @click="confirmDelete(blog)"
                                                type="button"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                title="Eliminar noticia">
                                            <i class="bi bi-trash3 text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!blogs.data || blogs.data.length === 0">
                                <td colspan="5" class="p-12 text-center text-gray-400">
                                    <i class="bi bi-inbox text-4xl mb-3 block text-gray-300"></i>
                                    <p class="font-bold text-gray-600">No se encontraron noticias ni artículos</p>
                                    <p class="text-xs text-gray-400 mt-1">Intenta con otros términos de búsqueda o añade una nueva noticia.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="blogs.links && blogs.links.length > 3" class="p-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-gray-500">
                        Mostrando del <span class="font-bold">{{ blogs.from || 0 }}</span> al <span class="font-bold">{{ blogs.to || 0 }}</span> de <span class="font-bold">{{ blogs.total || 0 }}</span> resultados
                    </p>
                    <div class="flex flex-wrap items-center gap-1">
                        <template v-for="(link, i) in blogs.links" :key="i">
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

        <!-- Delete Modal -->
        <div v-if="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform transition-all animate-scale-up">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 text-center">¿Eliminar esta publicación?</h3>
                <p class="text-xs text-gray-500 text-center mt-2">
                    Esta acción eliminará de forma permanente la noticia "<strong class="text-gray-800">{{ blogToDelete?.title }}</strong>" y todas sus imágenes asociadas.
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
    </AdminLayout>
</template>
