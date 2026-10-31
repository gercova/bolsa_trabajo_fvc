<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const imagePreview = ref('')

const form = useForm({
    company: '',
    link: '',
    is_active: true,
    image: null
})

function handleImageChange(event) {
    const file = event.target.files[0]
    if (file) {
        form.image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

function submit() {
    form.post(route('admin.partners.store'), {
        forceFormData: true,
        preserveScroll: true
    })
}
</script>

<template>
    <AdminLayout title="Nuevo Partner">
        <Head title="Nuevo Partner - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-building-add text-lg"></i>
                        </span>
                        Nuevo Partner / Aliado
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Registra una nueva empresa o institución colaboradora.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link :href="route('admin.partners.index')"
                          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">
                        <i class="bi bi-arrow-left"></i>
                        <span>Volver a Socios</span>
                    </Link>
                </div>
            </div>

            <!-- Form Card -->
            <div class="max-w-3xl mx-auto">
                <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Empresa / Institución <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.company"
                                   type="text"
                                   required
                                   placeholder="Ej: Microsoft Perú, Cámara de Comercio..."
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium"
                                   :class="form.errors.company ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                            <p v-if="form.errors.company" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.company }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Enlace / Sitio Web Oficial
                            </label>
                            <input v-model="form.link"
                                   type="text"
                                   placeholder="https://empresa.com"
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-medium"
                                   :class="form.errors.link ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                            <p v-if="form.errors.link" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.link }}</p>
                        </div>

                        <!-- Logo Upload & Preview -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Logo del Partner <span class="text-rose-500">*</span>
                                </label>
                                <input type="file"
                                       @change="handleImageChange"
                                       accept="image/png,image/jpeg,image/jpg,image/gif,image/webp"
                                       class="w-full text-xs text-gray-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                                <p class="mt-1.5 text-[11px] text-gray-400">Formatos: PNG, JPG, WEBP. Máx: 2MB.</p>
                                <p v-if="form.errors.image" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.image }}</p>
                            </div>

                            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-4 flex flex-col items-center justify-center min-h-[140px]">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Vista Previa</span>
                                <img v-if="imagePreview"
                                     :src="imagePreview"
                                     class="max-h-24 max-w-full object-contain rounded-lg p-1.5 bg-white shadow-sm">
                                <div v-else class="text-center text-gray-400">
                                    <i class="bi bi-image text-3xl mb-1 block"></i>
                                    <span class="text-xs">Selecciona un archivo</span>
                                </div>
                            </div>
                        </div>

                        <!-- Active Switch -->
                        <div class="flex items-center gap-3 bg-purple-50/60 p-4 rounded-xl border border-purple-100">
                            <input v-model="form.is_active"
                                   type="checkbox"
                                   id="is_active"
                                   class="h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer">
                            <div>
                                <label for="is_active" class="text-sm font-bold text-gray-800 cursor-pointer">Partner activo en la plataforma</label>
                                <p class="text-xs text-gray-500">Se mostrará en la sección pública de empresas aliadas.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <Link :href="route('admin.partners.index')"
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
                            <span>{{ form.processing ? 'Guardando...' : 'Guardar Partner' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
