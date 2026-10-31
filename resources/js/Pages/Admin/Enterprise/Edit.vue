<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({})
    }
})

const logoPreview = ref(props.enterprise.logo_path || '')
const faviconPreview = ref(props.enterprise.favicon_path || '')
const complaintsBookPreview = ref(props.enterprise.complaints_book_path || '')
const complaintsBookName = ref('')
const complaintsBookSize = ref('')

const colorOptions = [
    { label: 'Azul', value: 'bg-blue-500', colorClass: 'bg-blue-500' },
    { label: 'Índigo', value: 'bg-indigo-500', colorClass: 'bg-indigo-500' },
    { label: 'Ámbar', value: 'bg-amber-500', colorClass: 'bg-amber-500' },
    { label: 'Esmeralda', value: 'bg-emerald-500', colorClass: 'bg-emerald-500' },
    { label: 'Violeta', value: 'bg-violet-500', colorClass: 'bg-violet-500' },
    { label: 'Rosa', value: 'bg-rose-500', colorClass: 'bg-rose-500' },
    { label: 'Pizarra', value: 'bg-slate-500', colorClass: 'bg-slate-500' },
]

const form = useForm({
    _method: 'PUT',
    ruc: props.enterprise.ruc || '',
    company_name: props.enterprise.company_name || '',
    trade_name: props.enterprise.trade_name || '',
    business_sector: props.enterprise.business_sector || '',
    phrase: props.enterprise.phrase || '',
    description: props.enterprise.description || '',
    mission: props.enterprise.mission || '',
    vision: props.enterprise.vision || '',
    principles: props.enterprise.principles || '',
    values: props.enterprise.values || '',
    legal_representative: props.enterprise.legal_representative || '',
    legal_representative_dni: props.enterprise.legal_representative_dni || '',
    address: props.enterprise.address || '',
    city: props.enterprise.city || '',
    google_maps_iframe: props.enterprise.google_maps_iframe || '',
    phone_number_1: props.enterprise.phone_number_1 || '',
    phone_number_2: props.enterprise.phone_number_2 || '',
    email: props.enterprise.email || '',
    facebook_link: props.enterprise.facebook_link || '',
    linkedin_link: props.enterprise.linkedin_link || '',
    twitter_link: props.enterprise.twitter_link || '',
    instagram_link: props.enterprise.instagram_link || '',
    whatsapp_link: props.enterprise.whatsapp_link || '',
    color: props.enterprise.color || 'bg-blue-500',
    logo_path: null,
    favicon_path: null,
    complaints_book_path: null
})

function handleLogoChange(event) {
    const file = event.target.files[0]
    if (file) {
        form.logo_path = file
        logoPreview.value = URL.createObjectURL(file)
    }
}

function handleFaviconChange(event) {
    const file = event.target.files[0]
    if (file) {
        form.favicon_path = file
        faviconPreview.value = URL.createObjectURL(file)
    }
}

function handleComplaintsBookChange(event) {
    const file = event.target.files[0]
    if (file) {
        form.complaints_book_path = file
        complaintsBookName.value = file.name
        complaintsBookSize.value = (file.size / (1024 * 1024)).toFixed(2) + ' MB'
        complaintsBookPreview.value = URL.createObjectURL(file)
    }
}

function submit() {
    const targetId = props.enterprise.id || 1
    form.post(route('admin.enterprise.update', targetId), {
        forceFormData: true,
        preserveScroll: true
    })
}

function resetForm() {
    form.reset()
    logoPreview.value = props.enterprise.logo_path || ''
    faviconPreview.value = props.enterprise.favicon_path || ''
    complaintsBookPreview.value = props.enterprise.complaints_book_path || ''
    complaintsBookName.value = ''
    complaintsBookSize.value = ''
}
</script>

<template>
    <AdminLayout title="Configuración de Empresa">
        <Head title="Configuración de Empresa - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-building text-lg"></i>
                        </span>
                        Configuración de Empresa
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Gestiona los datos institucionales, contacto, identidad visual y redes sociales.
                    </p>
                </div>

                <div class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-[10px] text-gray-400"></i>
                    <span class="text-purple-600 font-bold">Configuración de Empresa</span>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Información Básica -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-base font-bold text-gray-800 flex items-center">
                            <i class="bi bi-building mr-2 text-purple-600"></i>
                            Información Básica de la Empresa
                        </h2>
                        <span class="text-xs font-medium text-purple-700 bg-purple-100/60 px-2.5 py-1 rounded-full">Datos Principales</span>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-hash mr-1"></i>RUC
                                </label>
                                <input v-model="form.ruc"
                                       type="text"
                                       maxlength="11"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.ruc ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: 20123456789">
                                <p v-if="form.errors.ruc" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.ruc }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-building-fill mr-1 text-purple-600"></i>Razón Social <span class="text-red-500">*</span>
                                </label>
                                <input v-model="form.company_name"
                                       type="text"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.company_name ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: Instituto Superior Tecnológico Francisco Vigo Caballero SAC"
                                       required>
                                <p v-if="form.errors.company_name" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.company_name }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-shop mr-1"></i>Nombre Comercial
                                </label>
                                <input v-model="form.trade_name"
                                       type="text"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.trade_name ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: Instituto Francisco Vigo Caballero">
                                <p v-if="form.errors.trade_name" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.trade_name }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-briefcase-fill mr-1"></i>Sector Empresarial
                                </label>
                                <input v-model="form.business_sector"
                                       type="text"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.business_sector ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: Educación Superior Tecnológica">
                                <p v-if="form.errors.business_sector" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.business_sector }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <i class="bi bi-chat-quote-fill mr-1"></i>Frase / Lema Institucional
                            </label>
                            <input v-model="form.phrase"
                                   type="text"
                                   class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   :class="form.errors.phrase ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                   placeholder="Ej: Formando profesionales de excelencia para el desarrollo del país">
                            <p v-if="form.errors.phrase" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.phrase }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <i class="bi bi-file-text-fill mr-1"></i>Descripción de la Empresa
                            </label>
                            <textarea v-model="form.description"
                                      rows="3"
                                      class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                      :class="form.errors.description ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                      placeholder="Resumen descriptivo sobre la institución..."></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Filosofía Institucional -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-800 flex items-center">
                            <i class="bi bi-eye-fill mr-2 text-blue-600"></i>
                            Filosofía Institucional (Misión, Visión, Principios y Valores)
                        </h2>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-flag-fill mr-1 text-blue-600"></i>Misión
                                </label>
                                <textarea v-model="form.mission"
                                          rows="4"
                                          class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                          :class="form.errors.mission ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                          placeholder="Somos una institución dedicada a..."></textarea>
                                <p v-if="form.errors.mission" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.mission }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-star-fill mr-1 text-amber-500"></i>Visión
                                </label>
                                <textarea v-model="form.vision"
                                          rows="4"
                                          class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                          :class="form.errors.vision ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                          placeholder="Ser reconocidos como líderes en formación tecnológica..."></textarea>
                                <p v-if="form.errors.vision" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.vision }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-shield-check mr-1 text-emerald-600"></i>Principios Institucionales <span class="text-xs text-gray-400 font-normal lowercase">(separados por comas)</span>
                                </label>
                                <textarea v-model="form.principles"
                                          rows="3"
                                          class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                          :class="form.errors.principles ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                          placeholder="Ej: Calidad educativa, Transparencia, Innovación, Inclusión social"></textarea>
                                <p v-if="form.errors.principles" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.principles }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-heart-fill mr-1 text-rose-500"></i>Valores Institucionales <span class="text-xs text-gray-400 font-normal lowercase">(separados por comas)</span>
                                </label>
                                <textarea v-model="form.values"
                                          rows="3"
                                          class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                          :class="form.errors.values ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                          placeholder="Ej: Responsabilidad, Respeto, Solidaridad, Honestidad"></textarea>
                                <p v-if="form.errors.values" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.values }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Representante Legal & Ubicación -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Representante Legal -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gradient-to-r from-emerald-50 to-green-50 border-b border-gray-100">
                            <h2 class="text-base font-bold text-gray-800 flex items-center">
                                <i class="bi bi-person-badge-fill mr-2 text-emerald-600"></i>
                                Representante Legal
                            </h2>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-person-fill mr-1"></i>Nombre Completo
                                </label>
                                <input v-model="form.legal_representative"
                                       type="text"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.legal_representative ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: Juan Pérez García">
                                <p v-if="form.errors.legal_representative" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.legal_representative }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-credit-card-fill mr-1"></i>DNI / Identificación
                                </label>
                                <input v-model="form.legal_representative_dni"
                                       type="text"
                                       maxlength="11"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.legal_representative_dni ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: 12345678">
                                <p v-if="form.errors.legal_representative_dni" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.legal_representative_dni }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación y Mapa -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-base font-bold text-gray-800 flex items-center">
                                <i class="bi bi-geo-alt-fill mr-2 text-orange-600"></i>
                                Ubicación Institucional
                            </h2>
                            <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-700 bg-orange-100/80 hover:bg-orange-200 px-2.5 py-1 rounded-lg transition">
                                <i class="bi bi-box-arrow-up-right"></i>
                                <span>Google Maps</span>
                            </a>
                        </div>

                        <div class="p-5 sm:p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        <i class="bi bi-pin-map-fill mr-1"></i>Dirección
                                    </label>
                                    <input v-model="form.address"
                                           type="text"
                                           class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           :class="form.errors.address ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                           placeholder="Ej: Av. Ricardo Palma N° 1401">
                                    <p v-if="form.errors.address" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.address }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        <i class="bi bi-building mr-1"></i>Ciudad
                                    </label>
                                    <input v-model="form.city"
                                           type="text"
                                           class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           :class="form.errors.city ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                           placeholder="Ej: Uchiza, San Martín">
                                    <p v-if="form.errors.city" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.city }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                    <i class="bi bi-map-fill mr-1 text-orange-600"></i>Código Iframe de Google Maps
                                </label>
                                <p class="text-[11px] text-gray-400 mb-2">Pega la etiqueta &lt;iframe&gt; completa copiada desde Google Maps.</p>
                                <textarea v-model="form.google_maps_iframe"
                                          rows="3"
                                          class="w-full font-mono text-xs px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                          :class="form.errors.google_maps_iframe ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                          placeholder="<iframe src='https://www.google.com/maps/embed?...' width='100%' height='450' style='border:0;' allowfullscreen='' loading='lazy'></iframe>"></textarea>
                                <p v-if="form.errors.google_maps_iframe" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.google_maps_iframe }}</p>
                            </div>

                            <!-- Live Map Preview -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                                    <i class="bi bi-eye mr-1"></i>Vista Previa del Mapa
                                </label>
                                <div class="w-full h-44 bg-gray-50 rounded-xl border border-gray-200 overflow-hidden relative flex items-center justify-center">
                                    <div v-if="form.google_maps_iframe"
                                         v-html="form.google_maps_iframe"
                                         class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                                    </div>
                                    <div v-else class="text-center p-4 text-gray-400">
                                        <i class="bi bi-geo-alt text-2xl mb-1 block"></i>
                                        <span class="text-xs">Pega el código iframe para visualizar la vista previa en vivo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-800 flex items-center">
                            <i class="bi bi-telephone-fill mr-2 text-red-600"></i>
                            Información de Contacto
                        </h2>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-telephone-fill mr-1 text-red-500"></i>Teléfono Principal
                                </label>
                                <input v-model="form.phone_number_1"
                                       type="text"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.phone_number_1 ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: +51 942 000 111">
                                <p v-if="form.errors.phone_number_1" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.phone_number_1 }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-telephone mr-1 text-gray-500"></i>Teléfono Secundario
                                </label>
                                <input v-model="form.phone_number_2"
                                       type="text"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.phone_number_2 ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: (042) 587123">
                                <p v-if="form.errors.phone_number_2" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.phone_number_2 }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-envelope-fill mr-1 text-indigo-500"></i>Correo Electrónico
                                </label>
                                <input v-model="form.email"
                                       type="email"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.email ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="Ej: informes@instituto.edu.pe">
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 bg-gradient-to-r from-sky-50 to-blue-50 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-800 flex items-center">
                            <i class="bi bi-share-fill mr-2 text-sky-600"></i>
                            Redes Sociales y Canales Digitales
                        </h2>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-facebook text-blue-600 mr-1.5"></i>Facebook
                                </label>
                                <input v-model="form.facebook_link"
                                       type="url"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.facebook_link ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="https://facebook.com/instituto">
                                <p v-if="form.errors.facebook_link" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.facebook_link }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-linkedin text-blue-700 mr-1.5"></i>LinkedIn
                                </label>
                                <input v-model="form.linkedin_link"
                                       type="url"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.linkedin_link ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="https://linkedin.com/company/instituto">
                                <p v-if="form.errors.linkedin_link" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.linkedin_link }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-twitter-x text-gray-800 mr-1.5"></i>Twitter / X
                                </label>
                                <input v-model="form.twitter_link"
                                       type="url"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.twitter_link ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="https://x.com/instituto">
                                <p v-if="form.errors.twitter_link" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.twitter_link }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-instagram text-pink-600 mr-1.5"></i>Instagram
                                </label>
                                <input v-model="form.instagram_link"
                                       type="url"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.instagram_link ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="https://instagram.com/instituto">
                                <p v-if="form.errors.instagram_link" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.instagram_link }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-whatsapp text-emerald-600 mr-1.5"></i>WhatsApp
                                </label>
                                <input v-model="form.whatsapp_link"
                                       type="url"
                                       class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       :class="form.errors.whatsapp_link ? 'border-red-500 bg-red-50/20' : 'border-gray-300'"
                                       placeholder="https://wa.me/51999999999">
                                <p v-if="form.errors.whatsapp_link" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.whatsapp_link }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Identidad Visual y Color del Tema -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-800 flex items-center">
                            <i class="bi bi-palette-fill mr-2 text-purple-600"></i>
                            Color del Tema Institucional
                        </h2>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        <p class="text-xs text-gray-500">
                            Selecciona el color primario representativo de la institución. Se aplicará a portales dinámicos públicos como Mesa de Partes Virtual y acentos de navegación.
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                            <button v-for="c in colorOptions"
                                    :key="c.value"
                                    type="button"
                                    @click="form.color = c.value"
                                    class="flex flex-col items-center p-3 rounded-xl border transition-all duration-200"
                                    :class="form.color === c.value ? 'border-purple-600 bg-purple-50/60 shadow-sm ring-2 ring-purple-500/30' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                <span class="w-8 h-8 rounded-full shadow-sm mb-2 flex items-center justify-center text-white" :class="c.colorClass">
                                    <i v-if="form.color === c.value" class="bi bi-check-lg text-sm"></i>
                                </span>
                                <span class="text-xs font-semibold text-gray-700">{{ c.label }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Archivos e Imágenes -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Logo -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-100">
                            <h2 class="text-sm font-bold text-gray-800 flex items-center">
                                <i class="bi bi-image-fill mr-2 text-purple-600"></i>
                                Logo Institucional
                            </h2>
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 min-h-[160px]">
                                <img v-if="logoPreview"
                                     :src="logoPreview"
                                     alt="Logo Preview"
                                     class="h-28 object-contain transition-all duration-300 rounded-lg">
                                <div v-else class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-image text-3xl mb-1"></i>
                                    <span class="text-xs">Sin logo configurado</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-upload mr-1"></i>Subir nuevo logo
                                </label>
                                <input type="file"
                                       @change="handleLogoChange"
                                       accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                       class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                                <p class="mt-1.5 text-[11px] text-gray-400">PNG, JPG, SVG. Máximo 2MB</p>
                                <p v-if="form.errors.logo_path" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.logo_path }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-100">
                            <h2 class="text-sm font-bold text-gray-800 flex items-center">
                                <i class="bi bi-star-fill mr-2 text-indigo-600"></i>
                                Favicon
                            </h2>
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 min-h-[160px]">
                                <img v-if="faviconPreview"
                                     :src="faviconPreview"
                                     alt="Favicon Preview"
                                     class="h-14 w-14 object-contain transition-all duration-300 rounded-lg shadow-sm">
                                <div v-else class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-browser-chrome text-3xl mb-1"></i>
                                    <span class="text-xs">Sin favicon</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-upload mr-1"></i>Subir nuevo favicon
                                </label>
                                <input type="file"
                                       @change="handleFaviconChange"
                                       accept="image/x-icon,image/png,image/jpeg,image/jpg,image/svg+xml"
                                       class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                                <p class="mt-1.5 text-[11px] text-gray-400">ICO, PNG, SVG. Máximo 1MB</p>
                                <p v-if="form.errors.favicon_path" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.favicon_path }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Libro de Reclamaciones -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 bg-gradient-to-r from-red-50 to-rose-50 border-b border-gray-100">
                            <h2 class="text-sm font-bold text-gray-800 flex items-center">
                                <i class="bi bi-file-earmark-pdf-fill mr-2 text-red-600"></i>
                                Libro de Reclamaciones
                            </h2>
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 min-h-[160px] text-center">
                                <template v-if="complaintsBookName">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-500 text-4xl mb-1"></i>
                                    <span class="text-xs font-bold text-gray-800 truncate max-w-[180px]">{{ complaintsBookName }}</span>
                                    <span class="text-[10px] text-gray-400">{{ complaintsBookSize }}</span>
                                </template>
                                <template v-else-if="complaintsBookPreview">
                                    <i class="bi bi-file-earmark-pdf-fill text-red-500 text-4xl mb-1"></i>
                                    <a :href="complaintsBookPreview" target="_blank" class="text-xs font-bold text-purple-600 hover:text-purple-700 underline flex items-center gap-1">
                                        <i class="bi bi-eye"></i> Ver archivo actual
                                    </a>
                                </template>
                                <template v-else>
                                    <i class="bi bi-file-earmark-pdf text-3xl text-gray-400 mb-1"></i>
                                    <span class="text-xs text-gray-400">Sin archivo PDF</span>
                                </template>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <i class="bi bi-upload mr-1"></i>Subir PDF
                                </label>
                                <input type="file"
                                       @change="handleComplaintsBookChange"
                                       accept="application/pdf"
                                       class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                                <p class="mt-1.5 text-[11px] text-gray-400">Formato PDF. Máximo 5MB</p>
                                <p v-if="form.errors.complaints_book_path" class="mt-1 text-xs text-red-600 font-medium">{{ form.errors.complaints_book_path }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end pt-2">
                    <button type="button"
                            @click="resetForm"
                            class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-semibold text-sm flex items-center justify-center gap-2">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Restablecer
                    </button>
                    <button type="submit"
                            :disabled="form.processing"
                            class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl transition font-semibold text-sm shadow-md shadow-purple-500/20 disabled:opacity-50 flex items-center justify-center gap-2">
                        <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <i v-else class="bi bi-check-lg"></i>
                        <span>{{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
