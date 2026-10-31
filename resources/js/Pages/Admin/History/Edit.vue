<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    history: {
        type: Object,
        required: true
    }
})

const existingImageUrl = ref(props.history.image_url || '')
const newImagePreview = ref('')

const form = useForm({
    _method: 'PUT',
    title: props.history.title || '',
    description: props.history.description || '',
    start_year: props.history.start_year ?? '',
    end_year: props.history.end_year ?? '',
    order: props.history.order ?? 1,
    is_active: Boolean(props.history.is_active),
    image: null,
    remove_image: false
})

function handleImageChange(event) {
    const file = event.target.files[0]
    if (file) {
        form.image = file
        form.remove_image = false
        newImagePreview.value = URL.createObjectURL(file)
    }
}

function removeCurrentPhoto() {
    form.remove_image = true
    form.image = null
    existingImageUrl.value = ''
    newImagePreview.value = ''
}

function cancelNewImage() {
    form.image = null
    newImagePreview.value = ''
}

function submit() {
    form.post(route('admin.history.update', props.history.id), {
        forceFormData: true,
        preserveScroll: true
    })
}
</script>

<template>
    <AdminLayout title="Editar Hito Histórico">
        <Head title="Editar Hito Histórico - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </span>
                        Editar Hito Histórico
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Actualiza la información, período histórico y fotografía de la etapa.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link :href="route('admin.history.index')"
                          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">
                        <i class="bi bi-arrow-left"></i>
                        <span>Volver al listado</span>
                    </Link>
                </div>
            </div>

            <!-- Form Card -->
            <div class="max-w-4xl mx-auto">
                <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 space-y-6">
                        <!-- Título -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Título de la Etapa o Hito <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.title"
                                   type="text"
                                   required
                                   placeholder="Ej: Etapa 1: Creación y funcionamiento (1991 - 2000)"
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium"
                                   :class="form.errors.title ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                            <p v-if="form.errors.title" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.title }}</p>
                        </div>

                        <!-- Período & Orden -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Año de Inicio
                                </label>
                                <div class="relative">
                                    <input v-model="form.start_year"
                                           type="number"
                                           min="1900"
                                           max="2100"
                                           placeholder="Ej: 1991"
                                           class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium"
                                           :class="form.errors.start_year ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                                    <i class="bi bi-calendar-event absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <p v-if="form.errors.start_year" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.start_year }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Año de Fin <span class="text-xs text-gray-400 lowercase font-normal">(opcional)</span>
                                </label>
                                <div class="relative">
                                    <input v-model="form.end_year"
                                           type="number"
                                           min="1900"
                                           max="2100"
                                           placeholder="Ej: 2000 (o vacío para Presente)"
                                           class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium"
                                           :class="form.errors.end_year ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                                    <i class="bi bi-calendar-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <p v-if="form.errors.end_year" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.end_year }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Orden de Presentación
                                </label>
                                <div class="relative">
                                    <input v-model="form.order"
                                           type="number"
                                           min="0"
                                           max="255"
                                           class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-bold"
                                           :class="form.errors.order ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                                    <i class="bi bi-sort-numeric-down absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <p v-if="form.errors.order" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.order }}</p>
                            </div>
                        </div>

                        <!-- Foto / Imagen Representativa -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Fotografía o Imagen Representativa
                                <span class="text-xs font-normal text-gray-400 lowercase">(JPG, PNG, WEBP, máx 5MB)</span>
                            </label>

                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                <div class="flex-1 w-full">
                                    <div class="border-2 border-dashed border-gray-300 hover:border-purple-500 rounded-2xl p-6 text-center transition cursor-pointer bg-gray-50/50 relative group">
                                        <input type="file"
                                               @change="handleImageChange"
                                               accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="space-y-2">
                                            <i class="bi bi-cloud-arrow-up text-3xl text-purple-600"></i>
                                            <p class="text-xs font-bold text-gray-700">Arrastra una fotografía o haz clic para reemplazar</p>
                                            <p class="text-[11px] text-gray-400">Si no seleccionas archivo, se conservará la imagen actual</p>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.image" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.image }}</p>
                                </div>

                                <!-- Current / New Preview -->
                                <div v-if="newImagePreview" class="relative group shrink-0">
                                    <img :src="newImagePreview" class="w-32 h-32 object-cover rounded-xl border border-purple-300 shadow-sm">
                                    <span class="absolute bottom-1 left-1 bg-purple-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold">Nueva</span>
                                    <button type="button"
                                            @click="cancelNewImage"
                                            class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md hover:bg-rose-600 transition">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                <div v-else-if="existingImageUrl && !form.remove_image" class="relative group shrink-0">
                                    <img :src="existingImageUrl" class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                                    <button type="button"
                                            @click="removeCurrentPhoto"
                                            class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-md hover:bg-rose-600 transition"
                                            title="Eliminar foto actual">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Descripción Detallada <span class="text-rose-500">*</span>
                            </label>
                            <textarea v-model="form.description"
                                      rows="5"
                                      required
                                      placeholder="Relata detalladamente los acontecimientos relevantes, directores a cargo, convenios..."
                                      class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition leading-relaxed"
                                      :class="form.errors.description ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.description }}</p>
                        </div>

                        <!-- Estado Activo -->
                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-2 ring-purple-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                <span class="ml-3 text-xs font-bold text-gray-800">Publicar en la Línea de Tiempo</span>
                            </label>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <Link :href="route('admin.history.index')"
                              class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl border border-gray-200 transition">
                            Cancelar
                        </Link>
                        <button type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition disabled:opacity-50 flex items-center gap-2 cursor-pointer">
                            <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <i v-else class="bi bi-check-circle-fill"></i>
                            <span>{{ form.processing ? 'Guardando...' : 'Actualizar Hito' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
