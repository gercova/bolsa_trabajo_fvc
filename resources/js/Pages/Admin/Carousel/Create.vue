<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    nextOrder: {
        type: Number,
        default: 1
    }
})

const imagePreview = ref('')

const colorOptions = [
    { value: 'amber',   label: 'Amber / Oro',      bg: 'bg-amber-500' },
    { value: 'sky',     label: 'Sky / Celeste',    bg: 'bg-sky-500' },
    { value: 'rose',    label: 'Rose / Rosa',      bg: 'bg-rose-500' },
    { value: 'emerald', label: 'Emerald / Verde',  bg: 'bg-emerald-500' },
    { value: 'indigo',  label: 'Indigo / Azul',    bg: 'bg-indigo-500' },
    { value: 'purple',  label: 'Purple / Violeta', bg: 'bg-purple-500' },
]

const form = useForm({
    title: '',
    highlight_text: '',
    tag: '',
    tag_icon: 'bi-mortarboard-fill',
    tag_color: 'amber',
    description: '',
    primary_button_text: '',
    primary_button_url: '',
    primary_button_icon: 'bi-pencil-square',
    secondary_button_text: '',
    secondary_button_url: '',
    secondary_button_icon: 'bi-grid-3x3-gap-fill',
    indicator_label: '',
    order: props.nextOrder,
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

// Live Preview Helpers
const solidAccentBarClass = computed(() => {
    switch (form.tag_color) {
        case 'sky': return 'bg-sky-500'
        case 'rose': return 'bg-rose-500'
        case 'emerald': return 'bg-emerald-500'
        case 'indigo': return 'bg-blue-500'
        case 'purple': return 'bg-purple-500'
        default: return 'bg-amber-500'
    }
})

const solidHighlightClass = computed(() => {
    switch (form.tag_color) {
        case 'sky': return 'text-sky-400'
        case 'rose': return 'text-rose-400'
        case 'emerald': return 'text-emerald-400'
        case 'indigo': return 'text-blue-400'
        case 'purple': return 'text-purple-400'
        default: return 'text-amber-400'
    }
})

const tagClasses = computed(() => {
    switch (form.tag_color) {
        case 'sky': return 'bg-slate-900/90 border border-sky-400/80 text-sky-300'
        case 'rose': return 'bg-slate-900/90 border border-rose-400/80 text-rose-300'
        case 'emerald': return 'bg-slate-900/90 border border-emerald-400/80 text-emerald-300'
        case 'indigo': return 'bg-slate-900/90 border border-blue-400/80 text-blue-300'
        case 'purple': return 'bg-slate-900/90 border border-purple-400/80 text-purple-300'
        default: return 'bg-slate-900/90 border border-amber-400/80 text-amber-300'
    }
})

const primaryButtonClasses = computed(() => {
    switch (form.tag_color) {
        case 'sky': return 'bg-sky-600 hover:bg-sky-700 text-white shadow-md'
        case 'rose': return 'bg-rose-600 hover:bg-rose-700 text-white shadow-md'
        case 'emerald': return 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md'
        case 'indigo': return 'bg-blue-600 hover:bg-blue-700 text-white shadow-md'
        case 'purple': return 'bg-purple-600 hover:bg-purple-700 text-white shadow-md'
        default: return 'bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold shadow-md'
    }
})

function submit() {
    form.post(route('admin.carousel.store'), {
        forceFormData: true,
        preserveScroll: true
    })
}
</script>

<template>
    <AdminLayout title="Nueva Diapositiva del Carrusel">
        <Head title="Nueva Diapositiva - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-plus-circle-fill text-lg"></i>
                        </span>
                        Nueva Diapositiva
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Crea una diapositiva destacada con botones de llamado a la acción e imagen de fondo.
                    </p>
                </div>

                <div class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="bi bi-building mr-1"></i> Empresa
                    <i class="bi bi-chevron-right mx-2 text-[10px] text-gray-400"></i>
                    <Link :href="route('admin.carousel.index')" class="hover:text-purple-600 transition">Carrusel</Link>
                    <i class="bi bi-chevron-right mx-2 text-[10px] text-gray-400"></i>
                    <span class="text-purple-600 font-bold">Crear</span>
                </div>
            </div>

            <!-- Live Preview Hero Box -->
            <div class="bg-slate-950 rounded-3xl shadow-xl border border-slate-800 overflow-hidden relative select-none">
                <div class="px-5 py-3 bg-slate-900/90 border-b border-slate-800 flex items-center justify-between text-xs text-slate-400">
                    <span class="font-semibold flex items-center gap-2 text-amber-300">
                        <i class="bi bi-eye-fill"></i> Vista Previa en Vivo (Hero Carrusel)
                    </span>
                    <span class="text-[11px] text-slate-500">Visualización interactiva en tiempo real</span>
                </div>

                <div class="relative min-h-[260px] sm:min-h-[300px] p-6 sm:p-10 flex flex-col justify-center overflow-hidden">
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center transition-all duration-500"
                         :style="imagePreview ? `background-image: url('${imagePreview}')` : 'background-color: #0f172a'">
                    </div>

                    <!-- Solid Scrim & Program Solid Accent Bar -->
                    <div class="absolute inset-0 bg-slate-950/85"></div>
                    <div class="h-1.5 w-full absolute top-0 left-0 transition-colors duration-300"
                         :class="solidAccentBarClass"></div>

                    <!-- Content -->
                    <div class="relative z-10 max-w-2xl space-y-3.5">
                        <!-- Tag Pill -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-md transition-all"
                             :class="tagClasses">
                            <i class="bi" :class="form.tag_icon || 'bi-mortarboard-fill'"></i>
                            <span>{{ form.tag || 'Admisión 2026-I • Modalidades Abiertas' }}</span>
                        </div>

                        <!-- Title & Highlight -->
                        <h2 class="text-xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                            <span>{{ form.title || 'Tu futuro profesional empieza aquí, en el' }}</span>
                            <br>
                            <span class="transition-colors duration-300"
                                  :class="solidHighlightClass">
                                {{ form.highlight_text || 'IESTP Francisco Vigo Caballero' }}
                            </span>
                        </h2>

                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-200 line-clamp-2 leading-relaxed max-w-xl">
                            {{ form.description || 'Estudia una de nuestras carreras técnicas a Nombre de la Nación con la mejor tecnología.' }}
                        </p>

                        <!-- Buttons -->
                        <div class="flex flex-wrap gap-2.5 pt-1">
                            <div v-if="form.primary_button_text"
                                 class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl shadow-md transition-all"
                                 :class="primaryButtonClasses">
                                <i class="bi" :class="form.primary_button_icon || 'bi-pencil-square'"></i>
                                <span>{{ form.primary_button_text }}</span>
                            </div>
                            <div v-if="form.secondary_button_text"
                                 class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 border border-white/25 text-white text-xs font-bold rounded-xl backdrop-blur-md">
                                <i class="bi" :class="form.secondary_button_icon || 'bi-grid-3x3-gap-fill'"></i>
                                <span>{{ form.secondary_button_text }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- 1. Información Principal -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-card-heading text-purple-600"></i> Contenido de la Diapositiva
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Define los textos, títulos y acento visual que resaltarán en la portada.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Título Principal <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.title"
                                   type="text"
                                   required
                                   placeholder="Ej: Tu futuro profesional empieza aquí, en el"
                                   class="w-full text-sm border rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium"
                                   :class="form.errors.title ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                            <p v-if="form.errors.title" class="text-xs text-red-600 font-medium">{{ form.errors.title }}</p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Texto Resaltado (Acento de Carrera / Institución)
                            </label>
                            <input v-model="form.highlight_text"
                                   type="text"
                                   placeholder="Ej: IESTP Francisco Vigo Caballero"
                                   class="w-full text-sm border rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium"
                                   :class="form.errors.highlight_text ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                            <p v-if="form.errors.highlight_text" class="text-xs text-red-600 font-medium">{{ form.errors.highlight_text }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Etiqueta / Badge Superior
                            </label>
                            <input v-model="form.tag"
                                   type="text"
                                   placeholder="Ej: Admisión 2026-I • Modalidades Abiertas"
                                   class="w-full text-sm border rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium"
                                   :class="form.errors.tag ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                            <p v-if="form.errors.tag" class="text-xs text-red-600 font-medium">{{ form.errors.tag }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Ícono de la Etiqueta (Bootstrap Icons)
                            </label>
                            <div class="relative">
                                <input v-model="form.tag_icon"
                                       type="text"
                                       placeholder="bi-mortarboard-fill, bi-cpu-fill, bi-heart-pulse-fill"
                                       class="w-full text-sm border rounded-xl py-2.5 pl-10 pr-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium"
                                       :class="form.errors.tag_icon ? 'border-red-500 bg-red-50/20' : 'border-gray-300'">
                                <i class="bi absolute left-3.5 top-1/2 -translate-y-1/2 text-purple-600" :class="form.tag_icon || 'bi-mortarboard-fill'"></i>
                            </div>
                            <p v-if="form.errors.tag_icon" class="text-xs text-red-600 font-medium">{{ form.errors.tag_icon }}</p>
                        </div>

                        <!-- Paleta de Color -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Color Institucional / Programa de Estudio
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                <div v-for="opt in colorOptions"
                                     :key="opt.value"
                                     @click="form.tag_color = opt.value"
                                     class="p-3 rounded-2xl border-2 text-center transition-all flex flex-col items-center gap-2 cursor-pointer"
                                     :class="form.tag_color === opt.value ? 'border-purple-600 bg-purple-50/50 shadow-sm' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                    <span class="w-5 h-5 rounded-full shadow-sm" :class="opt.bg"></span>
                                    <span class="text-xs font-bold text-gray-800">{{ opt.label }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Descripción / Párrafo
                            </label>
                            <textarea v-model="form.description"
                                      rows="3"
                                      placeholder="Detalles breves de la convocatoria o carrera técnica..."
                                      class="w-full text-sm border rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                                      :class="form.errors.description ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"></textarea>
                            <p v-if="form.errors.description" class="text-xs text-red-600 font-medium">{{ form.errors.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Botones de Acción -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-cursor-fill text-purple-600"></i> Botones de Llamado a la Acción (CTA)
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Configura botones con enlaces a páginas públicas del instituto.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Botón Primario -->
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                            <span class="text-xs font-extrabold uppercase text-purple-700 tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-1-circle-fill"></i> Botón Principal (Destacado)
                            </span>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Texto del Botón</label>
                                <input v-model="form.primary_button_text"
                                       type="text"
                                       placeholder="Ej: Examen de Admisión"
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-medium">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Enlace / Ruta / URL</label>
                                <input v-model="form.primary_button_url"
                                       type="text"
                                       placeholder="Ej: examen-de-admision o /admision"
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Ícono (Bootstrap Icons)</label>
                                <input v-model="form.primary_button_icon"
                                       type="text"
                                       placeholder="bi-pencil-square"
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                            </div>
                        </div>

                        <!-- Botón Secundario -->
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                            <span class="text-xs font-extrabold uppercase text-slate-700 tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-2-circle-fill"></i> Botón Secundario (Translúcido)
                            </span>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Texto del Botón</label>
                                <input v-model="form.secondary_button_text"
                                       type="text"
                                       placeholder="Ej: Ver 5 Carreras"
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-medium">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Enlace / Ruta / URL</label>
                                <input v-model="form.secondary_button_url"
                                       type="text"
                                       placeholder="Ej: programas-de-estudio o /nosotros"
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700">Ícono (Bootstrap Icons)</label>
                                <input v-model="form.secondary_button_icon"
                                       type="text"
                                       placeholder="bi-grid-3x3-gap-fill"
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Imagen de Fondo & Opciones -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-image-fill text-purple-600"></i> Imagen de Fondo & Parámetros
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Sube una foto de alta calidad (1920x800 px recomendado, JPG, PNG o WEBP).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Fotografía de Fondo
                            </label>

                            <div class="border-2 border-dashed border-gray-300 hover:border-purple-500 rounded-2xl p-5 text-center transition-colors bg-gray-50/50 relative">
                                <input type="file"
                                       @change="handleImageChange"
                                       accept="image/jpeg,image/png,image/webp,image/jpg"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="space-y-2">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-purple-600"></i>
                                    <p class="text-xs font-bold text-gray-700">Arrastra una imagen o haz clic aquí</p>
                                    <p class="text-[11px] text-gray-400">Recomendado: 1920x800 px. Máx: 5MB (JPG, WEBP, PNG)</p>
                                </div>
                            </div>
                            <p v-if="form.errors.image" class="text-xs text-red-600 font-medium">{{ form.errors.image }}</p>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Etiqueta en Barra de Navegación Inferior
                                </label>
                                <input v-model="form.indicator_label"
                                       type="text"
                                       placeholder="Ej: Admisión 2026, Enfermería, Redes TI..."
                                       class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Orden
                                    </label>
                                    <input v-model="form.order"
                                           type="number"
                                           min="1"
                                           class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold">
                                </div>

                                <div class="flex items-center pt-6">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-2 ring-purple-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        <span class="ml-3 text-xs font-bold text-gray-800">Publicado</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <Link :href="route('admin.carousel.index')"
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
                        <span>{{ form.processing ? 'Guardando...' : 'Guardar Diapositiva' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
