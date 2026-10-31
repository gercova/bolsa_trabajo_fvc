<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    nextOrder: {
        type: Number,
        default: 1,
    },
});

const form = useForm({
    name: '',
    logo_path: null,
    training_itinerary_path: null,
    images: [],
    main_image_index: 0,
    enable_carousel: false,
    carousel_alt_text: '',
    description: '',
    details: '',
    icon: 'bi-mortarboard-fill',
    accent: 'blue',
    tag: '',
    order: props.nextOrder,
    bg_badge: '',
    is_active: true,
});

// Logo management
const logoPreview = ref(null);
const logoFileName = ref('');

const handleLogoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        logoFileName.value = file.name;
        form.logo_path = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeLogo = () => {
    logoPreview.value = null;
    logoFileName.value = '';
    form.logo_path = null;
};

// PDF Itinerary management
const pdfFileName = ref('');

const handlePdfChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        pdfFileName.value = file.name;
        form.training_itinerary_path = file;
    }
};

const removePdf = () => {
    pdfFileName.value = '';
    form.training_itinerary_path = null;
};

// Gallery Images management
const galleryPreviews = ref([]);
const isDraggingGallery = ref(false);

const formatBytes = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const processGalleryFiles = (files) => {
    Array.from(files).forEach((file) => {
        const exists = galleryPreviews.value.some(
            (p) => p.name === file.name && p.sizeRaw === file.size
        );
        if (!exists) {
            const reader = new FileReader();
            reader.onload = (e) => {
                galleryPreviews.value.push({
                    file: file,
                    name: file.name,
                    size: formatBytes(file.size),
                    sizeRaw: file.size,
                    preview: e.target.result,
                });
                syncFormImages();
            };
            reader.readAsDataURL(file);
        }
    });
};

const handleGalleryChange = (event) => {
    if (event.target.files && event.target.files.length > 0) {
        processGalleryFiles(event.target.files);
    }
};

const handleGalleryDrop = (event) => {
    isDraggingGallery.value = false;
    if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
        processGalleryFiles(event.dataTransfer.files);
    }
};

const removeGalleryFile = (index) => {
    galleryPreviews.value.splice(index, 1);
    if (form.main_image_index >= galleryPreviews.value.length) {
        form.main_image_index = Math.max(0, galleryPreviews.value.length - 1);
    }
    syncFormImages();
};

const clearAllGalleryFiles = () => {
    galleryPreviews.value = [];
    form.main_image_index = 0;
    form.images = [];
};

const setMainGalleryIndex = (index) => {
    form.main_image_index = index;
};

const syncFormImages = () => {
    form.images = galleryPreviews.value.map((item) => item.file);
};

const submit = () => {
    form.post(route('admin.programs.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Registrar Programa de Estudio — Panel Administrativo" />

    <AdminLayout title="Registrar Programa de Estudio">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Breadcrumbs / Back button -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('admin.programs.index')"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors"
                >
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span>Volver a Programas</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
                <!-- Banner -->
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-mortarboard-fill text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Nuevo Programa de Estudio</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Ingresa los datos del programa, logo oficial, perfil del egresado y metadatos visuales.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre del Programa -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Nombre del Programa de Estudio <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-book absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Ej: Enfermería Técnica, Producción Agropecuaria"
                                    required
                                    :class="[
                                        'w-full pl-10 pr-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                        form.errors.name ? 'border-red-500' : 'border-gray-200'
                                    ]"
                                />
                            </div>
                            <p v-if="form.errors.name" class="text-xs text-red-500 font-medium">{{ form.errors.name }}</p>
                        </div>

                        <!-- Logo del Programa -->
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Logo del Programa (PNG, JPG, WEBP, GIF, SVG)
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <!-- Upload Drag & Drop Area -->
                                <div class="md:col-span-2 relative border-2 border-dashed border-gray-200 hover:border-purple-400 bg-gray-50/50 hover:bg-purple-50/20 rounded-2xl p-6 text-center transition-all duration-200 group">
                                    <input
                                        type="file"
                                        id="logo_path"
                                        accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml"
                                        @change="handleLogoChange"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    />
                                    <div class="space-y-2 pointer-events-none">
                                        <div class="w-12 h-12 mx-auto rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                            <i class="bi bi-cloud-arrow-up-fill"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700">Haz clic o arrastra la imagen aquí</p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP, GIF o SVG (Máx. 4MB)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo Preview Box -->
                                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-gray-200 min-h-[150px]">
                                    <div v-if="logoPreview" class="text-center space-y-3 relative w-full">
                                        <div class="relative inline-block group">
                                            <img
                                                :src="logoPreview"
                                                alt="Previsualización Logo"
                                                class="w-24 h-24 object-contain rounded-xl bg-white p-2 border border-gray-200 shadow-sm mx-auto"
                                            />
                                            <button
                                                type="button"
                                                @click="removeLogo"
                                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors cursor-pointer"
                                                title="Quitar logo"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-600 truncate max-w-[200px] mx-auto font-medium">{{ logoFileName }}</div>
                                        <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">
                                            Previsualización
                                        </span>
                                    </div>
                                    <div v-else class="text-center space-y-2 text-gray-400">
                                        <i class="bi bi-image text-3xl opacity-50"></i>
                                        <p class="text-xs font-medium">Sin logo seleccionado</p>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.logo_path" class="text-xs text-red-500 font-medium">{{ form.errors.logo_path }}</p>
                        </div>

                        <!-- Itinerario Formativo (PDF) -->
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Documento del Itinerario Formativo (PDF)
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div class="md:col-span-2 relative border-2 border-dashed border-gray-200 hover:border-indigo-400 bg-gray-50/50 hover:bg-indigo-50/20 rounded-2xl p-6 text-center transition-all duration-200 group">
                                    <input
                                        type="file"
                                        id="training_itinerary_path"
                                        accept="application/pdf,.pdf"
                                        @change="handlePdfChange"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    />
                                    <div class="space-y-2 pointer-events-none">
                                        <div class="w-12 h-12 mx-auto rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700">Haz clic o arrastra el documento PDF aquí</p>
                                            <p class="text-xs text-gray-500 mt-1">Plan de estudios en formato PDF (Máx. 20MB)</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-gray-200 min-h-[150px]">
                                    <div v-if="pdfFileName" class="text-center space-y-3 relative w-full">
                                        <div class="relative inline-block group">
                                            <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 border border-red-100 flex items-center justify-center text-3xl shadow-sm mx-auto">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </div>
                                            <button
                                                type="button"
                                                @click="removePdf"
                                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors cursor-pointer"
                                                title="Quitar documento"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="text-xs text-gray-700 truncate max-w-[200px] mx-auto font-bold">{{ pdfFileName }}</div>
                                        <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">
                                            PDF Seleccionado
                                        </span>
                                    </div>
                                    <div v-else class="text-center space-y-2 text-gray-400">
                                        <i class="bi bi-file-earmark-arrow-up text-3xl opacity-50"></i>
                                        <p class="text-xs font-medium">Sin itinerario PDF</p>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.training_itinerary_path" class="text-xs text-red-500 font-medium">{{ form.errors.training_itinerary_path }}</p>
                        </div>

                        <!-- Galería de Fotos del Programa -->
                        <div class="md:col-span-2 space-y-3 pt-2">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                        Galería de Fotos del Programa <span class="text-gray-400 font-normal">(Múltiples fotos)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Sube fotos de talleres, laboratorios o actividades prácticas. Formatos: PNG, JPG, WEBP, GIF, SVG, AVIF (Máx. 10MB c/u).
                                    </p>
                                </div>
                                <button
                                    v-if="galleryPreviews.length > 0"
                                    type="button"
                                    @click="clearAllGalleryFiles"
                                    class="text-xs font-semibold text-red-600 hover:text-red-700 hover:underline flex items-center gap-1 cursor-pointer"
                                >
                                    <i class="bi bi-trash"></i>
                                    <span>Quitar todas ({{ galleryPreviews.length }})</span>
                                </button>
                            </div>

                            <!-- Multi-image Drag & Drop Area -->
                            <div
                                :class="[
                                    'relative border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-200 group',
                                    isDraggingGallery
                                        ? 'border-purple-500 bg-purple-50/50'
                                        : 'border-gray-200 hover:border-purple-400 bg-gray-50/50 hover:bg-purple-50/20'
                                ]"
                                @dragover.prevent="isDraggingGallery = true"
                                @dragleave.prevent="isDraggingGallery = false"
                                @drop.prevent="handleGalleryDrop"
                            >
                                <input
                                    type="file"
                                    id="gallery_images"
                                    multiple
                                    accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml,image/avif"
                                    @change="handleGalleryChange"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                />
                                <div class="space-y-2 pointer-events-none">
                                    <div class="w-12 h-12 mx-auto rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                        <i class="bi bi-images"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-700">Haz clic o arrastra fotos para la galería aquí</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Puedes seleccionar varias imágenes a la vez (PNG, JPG, WEBP, GIF, SVG, AVIF — Máx. 10MB)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <p v-if="form.errors.images" class="text-xs text-red-500 font-medium">{{ form.errors.images }}</p>

                            <!-- Gallery Previews Grid -->
                            <div v-if="galleryPreviews.length > 0" class="space-y-3 pt-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                                    <span>Fotos seleccionadas ({{ galleryPreviews.length }})</span>
                                    <span class="text-purple-600 font-medium text-[11px] flex items-center gap-1">
                                        <i class="bi bi-star-fill text-amber-400"></i> Haz clic en "Principal" para elegir la foto destacada
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <div
                                        v-for="(item, idx) in galleryPreviews"
                                        :key="idx"
                                        :class="[
                                            'relative group rounded-2xl border bg-white overflow-hidden shadow-sm transition-all duration-200',
                                            form.main_image_index === idx
                                                ? 'border-purple-500 ring-2 ring-purple-200'
                                                : 'border-gray-200 hover:border-gray-300'
                                        ]"
                                    >
                                        <div class="aspect-video w-full bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                            <img :src="item.preview" :alt="item.name" class="w-full h-full object-cover" />

                                            <!-- Main Badge / Action -->
                                            <div class="absolute top-2 left-2 z-20">
                                                <button
                                                    type="button"
                                                    @click="setMainGalleryIndex(idx)"
                                                    :class="[
                                                        'px-2 py-1 rounded-lg text-[10px] font-bold shadow-md transition-all flex items-center gap-1 backdrop-blur-md cursor-pointer',
                                                        form.main_image_index === idx
                                                            ? 'bg-amber-500 text-white'
                                                            : 'bg-black/60 text-white hover:bg-black/80'
                                                    ]"
                                                >
                                                    <i :class="['bi', form.main_image_index === idx ? 'bi-star-fill text-yellow-200' : 'bi-star']"></i>
                                                    <span>{{ form.main_image_index === idx ? 'Principal' : 'Elegir' }}</span>
                                                </button>
                                            </div>

                                            <!-- Delete Button -->
                                            <button
                                                type="button"
                                                @click="removeGalleryFile(idx)"
                                                class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center text-xs shadow-md backdrop-blur-sm transition-transform hover:scale-110 z-20 cursor-pointer"
                                                title="Quitar foto"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div class="p-2.5 bg-white">
                                            <p class="text-xs font-semibold text-gray-800 truncate" :title="item.name">{{ item.name }}</p>
                                            <p class="text-[11px] text-gray-500">{{ item.size }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración de Carrusel -->
                        <div class="md:col-span-2 space-y-4 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <i class="bi bi-images text-blue-600 text-lg"></i>
                                Configuración de Carrusel
                            </h3>

                            <div class="space-y-2">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input
                                        type="checkbox"
                                        v-model="form.enable_carousel"
                                        class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                                    />
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                                            Activar carrusel dinámico en la portada del programa
                                        </span>
                                        <p class="text-xs text-gray-600 mt-1">
                                            Las imágenes de la galería se mostrarán como un carrusel giratorio automático en lugar de una imagen estática.
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <div class="space-y-1.5">
                                <label for="carousel_alt_text" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Texto Alternativo para Carrusel <span class="text-gray-400 font-normal text-[11px]">(Opcional)</span>
                                </label>
                                <textarea
                                    id="carousel_alt_text"
                                    v-model="form.carousel_alt_text"
                                    rows="2"
                                    placeholder="Texto descriptivo personalizado que aparecerá en todas las diapositivas del carrusel..."
                                    class="w-full px-4 py-3 bg-white border border-blue-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                ></textarea>
                                <p v-if="form.errors.carousel_alt_text" class="text-xs text-red-500 font-medium">{{ form.errors.carousel_alt_text }}</p>
                            </div>
                        </div>

                        <!-- Descripción / Perfil del Egresado -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Perfil del Egresado / Descripción <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                placeholder="Ingrese el perfil profesional y descripción del programa de estudio..."
                                required
                                :class="[
                                    'w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                    form.errors.description ? 'border-red-500' : 'border-gray-200'
                                ]"
                            ></textarea>
                            <p v-if="form.errors.description" class="text-xs text-red-500 font-medium">{{ form.errors.description }}</p>
                        </div>

                        <!-- Detalles / Duración y Título -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="details" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Detalles (Duración, Períodos y Título) <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="details"
                                v-model="form.details"
                                rows="3"
                                placeholder="Ej: Duración: 3 años (06 períodos lectivos)&#10;Título: Profesional Técnico en ..."
                                required
                                :class="[
                                    'w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                    form.errors.details ? 'border-red-500' : 'border-gray-200'
                                ]"
                            ></textarea>
                            <p v-if="form.errors.details" class="text-xs text-red-500 font-medium">{{ form.errors.details }}</p>
                        </div>

                        <!-- Ícono Principal -->
                        <div class="space-y-1.5">
                            <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Ícono Principal (Bootstrap Icons)
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                    <i :class="['bi', form.icon || 'bi-mortarboard-fill']"></i>
                                </div>
                                <input
                                    id="icon"
                                    v-model="form.icon"
                                    type="text"
                                    placeholder="Ej: bi-heart-pulse-fill"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <!-- Color de Acento -->
                        <div class="space-y-1.5">
                            <label for="accent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Color de Acento
                            </label>
                            <select
                                id="accent"
                                v-model="form.accent"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            >
                                <option value="blue">Azul (Blue)</option>
                                <option value="emerald">Esmeralda (Emerald)</option>
                                <option value="rose">Rosa (Rose)</option>
                                <option value="sky">Cielo (Sky)</option>
                                <option value="teal">Verde Azulado (Teal)</option>
                                <option value="indigo">Índigo (Indigo)</option>
                            </select>
                        </div>

                        <!-- Etiqueta / Tag -->
                        <div class="space-y-1.5">
                            <label for="tag" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Etiqueta / Tag
                            </label>
                            <input
                                id="tag"
                                v-model="form.tag"
                                type="text"
                                placeholder="Ej: Ciencias de la Salud, Producción & Campo"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Orden Secuencial -->
                        <div class="space-y-1.5">
                            <label for="order" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Orden de Presentación <span class="text-gray-400 font-normal">(Secuencial)</span>
                            </label>
                            <div class="relative">
                                <i class="bi bi-sort-numeric-down absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input
                                    id="order"
                                    v-model.number="form.order"
                                    type="number"
                                    min="0"
                                    max="255"
                                    placeholder="Ej: 1, 2, 3..."
                                    :class="[
                                        'w-full pl-10 pr-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                        form.errors.order ? 'border-red-500' : 'border-gray-200'
                                    ]"
                                />
                            </div>
                            <p v-if="form.errors.order" class="text-xs text-red-500 font-medium">{{ form.errors.order }}</p>
                        </div>

                        <!-- Badge visual CSS -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="bg_badge" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Estilo CSS de Badge
                            </label>
                            <input
                                id="bg_badge"
                                v-model="form.bg_badge"
                                type="text"
                                placeholder="Ej: bg-rose-50 text-rose-800 border-rose-100"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Estado Activo -->
                        <div class="md:col-span-2 pt-2">
                            <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                <input
                                    type="checkbox"
                                    v-model="form.is_active"
                                    class="sr-only peer"
                                />
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                                <div>
                                    <span class="text-sm font-bold text-gray-900 block">Programa Activo</span>
                                    <span class="text-xs text-gray-500 block">Los programas activos se muestran públicamente en el portal institucional.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <Link
                            :href="route('admin.programs.index')"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2 cursor-pointer disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-check-circle-fill"></i>
                            <span>{{ form.processing ? 'Guardando...' : 'Guardar Programa' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
