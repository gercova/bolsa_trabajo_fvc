<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    offer: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    title: props.offer.title || '',
    company: props.offer.company || '',
    location: props.offer.location || '',
    url: props.offer.url === '#' ? '' : (props.offer.url || ''),
    source: props.offer.source || '',
    description: props.offer.description || '',
    is_active: Boolean(props.offer.is_active),
})

function submit() {
    form.put(route('admin.works.update', props.offer.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <AdminLayout title="Editar Oferta Laboral">
        <Head :title="`Editar: ${offer.title} - Panel Administrativo`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full"
                              :class="offer.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'">
                            {{ offer.is_active ? 'Publicado Activo' : 'Inactivo / Oculto' }}
                        </span>
                        <span class="text-xs text-gray-400">• ID: #{{ offer.id }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </span>
                        Editar Oferta Laboral
                    </h1>
                </div>
                <Link :href="route('admin.works.index')"
                      class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition-all">
                    <i class="bi bi-arrow-left"></i> Volver al Listado
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Título del puesto -->
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Título del Puesto <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.title"
                                   type="text"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                   :class="form.errors.title ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'" />
                            <p v-if="form.errors.title" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.title }}</p>
                        </div>

                        <!-- Empresa -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Empresa / Institución Contratante <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.company"
                                   type="text"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                   :class="form.errors.company ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'" />
                            <p v-if="form.errors.company" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.company }}</p>
                        </div>

                        <!-- Ubicación -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Ubicación <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.location"
                                   type="text"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                   :class="form.errors.location ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'" />
                            <p v-if="form.errors.location" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.location }}</p>
                        </div>

                        <!-- URL Postulación -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Enlace / URL de Postulación <span class="text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <input v-model="form.url"
                                   type="url"
                                   placeholder="https://ejemplo.com/postular"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                   :class="form.errors.url ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'" />
                            <p v-if="form.errors.url" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.url }}</p>
                        </div>

                        <!-- Fuente -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Fuente del Empleo <span class="text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <input v-model="form.source"
                                   type="text"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                   :class="form.errors.source ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'" />
                            <p v-if="form.errors.source" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.source }}</p>
                        </div>

                        <!-- Descripción -->
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Descripción del Puesto y Requisitos <span class="text-rose-500">*</span>
                            </label>
                            <textarea v-model="form.description"
                                      rows="6"
                                      class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                      :class="form.errors.description ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'"></textarea>
                            <p v-if="form.errors.description" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.description }}</p>
                        </div>
                    </div>

                    <!-- Estado Activo Toggle -->
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Estado de Publicación</h4>
                            <p class="text-xs text-gray-500">Activa o desactiva la visibilidad pública de esta oferta.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <Link :href="route('admin.works.index')"
                              class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                            Cancelar
                        </Link>
                        <button type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2 disabled:opacity-60">
                            <i v-if="!form.processing" class="bi bi-check-lg text-lg"></i>
                            <span v-if="form.processing" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Actualizando...
                            </span>
                            <span v-else>Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
