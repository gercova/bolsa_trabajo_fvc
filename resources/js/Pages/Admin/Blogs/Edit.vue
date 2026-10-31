<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    blog: {
        type: Object,
        required: true,
    },
})

const existingImage = props.blog.images && props.blog.images.length > 0 ? props.blog.images[0] : null
const existingImageUrl = existingImage
    ? (existingImage.path.startsWith('http') ? existingImage.path : `/storage/${existingImage.path}`)
    : null

const form = useForm({
    _method: 'PUT',
    title: props.blog.title || '',
    content: props.blog.content || '',
    details: props.blog.details || '',
    is_published: Boolean(props.blog.is_published),
    image: null,
})

const imagePreview = ref(existingImageUrl)

function onFileChange(e) {
    const file = e.target.files[0]
    if (file) {
        form.image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

function removeImage() {
    form.image = null
    imagePreview.value = null
}

function submit() {
    form.post(route('admin.blogs.update', props.blog.id), {
        preserveScroll: true,
        forceFormData: true,
    })
}
</script>

<template>
    <AdminLayout title="Editar Noticia / Blog">
        <Head :title="`Editar: ${blog.title} - Panel Administrativo`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-xl shadow-md shadow-indigo-500/20">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </span>
                        Editar Noticia / Publicación
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Actualiza la información, portada o visibilidad del artículo en el portal web.
                    </p>
                </div>

                <Link :href="route('admin.blogs.index')"
                      class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition-all">
                    <i class="bi bi-arrow-left"></i> Volver a Noticias
                </Link>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Título de la Noticia <span class="text-rose-500">*</span>
                    </label>
                    <input v-model="form.title"
                           type="text"
                           placeholder="Ingresa un título descriptivo y llamativo..."
                           class="w-full text-sm px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                           :class="form.errors.title ? 'border-rose-400 bg-rose-50/20' : 'border-gray-300'" />
                    <p v-if="form.errors.title" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.title }}</p>
                </div>

                <!-- Slug info -->
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center gap-2 text-xs text-gray-500">
                    <i class="bi bi-link-45deg text-lg text-purple-600"></i>
                    <span>Enlace permanente: <code class="font-bold text-gray-800">/noticias/{{ blog.slug }}</code></span>
                </div>

                <!-- Image Upload / Replace -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Imagen de Portada
                    </label>
                    <div class="border-2 border-dashed border-gray-300 hover:border-purple-400 rounded-2xl p-6 text-center transition-all bg-gray-50/50">
                        <div v-if="imagePreview" class="relative inline-block">
                            <img :src="imagePreview" alt="Previsualización" class="max-h-64 rounded-xl shadow-md mx-auto object-cover border border-gray-200" />
                            <div class="mt-3 flex items-center justify-center gap-2">
                                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs rounded-xl cursor-pointer transition-colors">
                                    <i class="bi bi-arrow-repeat"></i> Cambiar Imagen
                                    <input type="file" accept="image/*" class="hidden" @change="onFileChange" />
                                </label>
                                <button @click="removeImage"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs rounded-xl transition-colors">
                                    <i class="bi bi-trash3"></i> Quitar
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-2 block"></i>
                            <p class="text-xs font-semibold text-gray-700">Arrastra o selecciona una nueva imagen de portada</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">PNG, JPG, WEBP hasta 4 MB</p>
                            <label class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold text-xs rounded-xl cursor-pointer transition-colors">
                                <i class="bi bi-image"></i> Seleccionar Imagen
                                <input type="file" accept="image/*" class="hidden" @change="onFileChange" />
                            </label>
                        </div>
                    </div>
                    <p v-if="form.errors.image" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.image }}</p>
                </div>

                <!-- Main Content -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Contenido Principal de la Noticia <span class="text-rose-500">*</span>
                    </label>
                    <textarea v-model="form.content"
                              rows="6"
                              placeholder="Redacta el contenido principal o cuerpo del artículo..."
                              class="w-full text-sm px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-sans"
                              :class="form.errors.content ? 'border-rose-400 bg-rose-50/20' : 'border-gray-300'"></textarea>
                    <p v-if="form.errors.content" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.content }}</p>
                </div>

                <!-- Additional Details -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Detalles Adicionales o Notas (Opcional)
                    </label>
                    <textarea v-model="form.details"
                              rows="3"
                              placeholder="Información adicional, agradecimientos, fuentes, etc..."
                              class="w-full text-sm px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-sans"></textarea>
                    <p v-if="form.errors.details" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.details }}</p>
                </div>

                <!-- Publication Status Switch -->
                <div class="flex items-center justify-between p-4 bg-purple-50/40 rounded-xl border border-purple-100">
                    <div>
                        <span class="text-xs font-bold text-gray-900 block">Estado de Publicación</span>
                        <span class="text-xs text-gray-500">Marca para hacerla visible de inmediato o desmarca para guardarla como borrador.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input v-model="form.is_published" type="checkbox" class="sr-only peer" />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <!-- Submit / Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <Link :href="route('admin.blogs.index')"
                          class="px-5 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        Cancelar
                    </Link>
                    <button type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-purple-500/20 transition-all disabled:opacity-50">
                        <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle text-base"></i>
                        <span>{{ form.processing ? 'Actualizando Noticia...' : 'Guardar Cambios' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
