@extends('layouts.app')
@section('title', 'Editar Programa de Estudio - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Editar Programa de Estudio
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.programs.index') }}" class="hover:text-purple-600">Programas</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Programa</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8" 
                    x-data="studyProgramEditForm('{{ old('icon', $program->icon ?? 'bi-mortarboard-fill') }}', '{{ $program->logo_path ? asset('storage/' . $program->logo_path) : '' }}', '{{ $program->logo_path ? basename($program->logo_path) : '' }}', '{{ $program->training_itinerary_path ? $program->training_itinerary_url : '' }}', '{{ $program->training_itinerary_path ? basename($program->training_itinerary_path) : '' }}', @js($program->images->map(fn($img) => [
                        'id' => $img->id,
                        'url' => $img->url,
                        'filename' => basename($img->path),
                        'is_main' => (bool)$img->is_main,
                        'destroy_url' => route('admin.programs.images.destroy', [$program, $img]),
                        'set_main_url' => route('admin.programs.images.set-main', [$program, $img]),
                    ])))">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Modificar: {{ $program->name }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Actualiza el perfil profesional, logo, itinerario formativo, detalles o metadatos de presentación de este programa de estudio.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nombre --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Nombre del Programa de Estudio <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-book absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="name" name="name" value="{{ old('name', $program->name) }}" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- FILE INPUT CON PREVISUALIZACIÓN Y LOGO ACTUAL --}}
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Logo del Programa (Formatos: PNG, JPG, JPEG, GIF, WEBP, SVG)
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                    {{-- Drag and Drop Upload Area --}}
                                    <div class="md:col-span-2 relative border-2 border-dashed border-gray-200 hover:border-purple-400 bg-gray-50/50 hover:bg-purple-50/20 rounded-2xl p-6 text-center transition-all duration-200 group">
                                        <input type="file" id="logo_path" name="logo_path" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml" 
                                            @change="handleLogoChange($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="space-y-2 pointer-events-none">
                                            <div class="w-12 h-12 mx-auto rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">
                                                    Haz clic o arrastra una nueva imagen para cambiar el logo
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP, GIF o SVG (Máx. 4MB)</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Live Thumbnail Display Box --}}
                                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-gray-200 min-h-[150px]">
                                        <template x-if="logoPreview">
                                            <div class="text-center space-y-3 relative w-full">
                                                <div class="relative inline-block group">
                                                    <img :src="logoPreview" alt="Previsualización Logo" class="w-24 h-24 object-contain rounded-xl bg-white p-2 border border-gray-200 shadow-sm mx-auto">
                                                    <button type="button" @click="resetLogo()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors" title="Restablecer / Deshacer cambio">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="text-xs text-gray-600 truncate max-w-[200px] mx-auto font-medium" x-text="fileName"></div>
                                                <span class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full"
                                                      :class="isNewFile ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800'">
                                                    <span x-text="isNewFile ? 'Nuevo Logo Seleccionado' : 'Logo Actual Registrado'"></span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="!logoPreview">
                                            <div class="text-center space-y-2 text-gray-400">
                                                <i class="bi bi-image text-3xl opacity-50"></i>
                                                <p class="text-xs font-medium">Sin logo registrado</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                @error('logo_path')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- FILE INPUT CON PREVISUALIZACIÓN Y DOCUMENTO ITINERARIO FORMATIVO (PDF) --}}
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Documento del Itinerario Formativo (Formato: PDF)
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                    {{-- Drag and Drop Upload Area --}}
                                    <div class="md:col-span-2 relative border-2 border-dashed border-gray-200 hover:border-indigo-400 bg-gray-50/50 hover:bg-indigo-50/20 rounded-2xl p-6 text-center transition-all duration-200 group">
                                        <input type="file" id="training_itinerary_path" name="training_itinerary_path" accept="application/pdf,.pdf" 
                                            @change="handlePdfChange($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div class="space-y-2 pointer-events-none">
                                            <div class="w-12 h-12 mx-auto rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">
                                                    Haz clic o arrastra un nuevo documento PDF para actualizar el itinerario
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Documento del plan de estudios / itinerario en formato PDF (Máx. 20MB)</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Live Document Status Box --}}
                                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-2xl border border-gray-200 min-h-[150px]">
                                        <template x-if="pdfFileName">
                                            <div class="text-center space-y-3 relative w-full">
                                                <div class="relative inline-block group">
                                                    <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 border border-red-100 flex items-center justify-center text-3xl shadow-sm mx-auto">
                                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                                    </div>
                                                    <template x-if="isNewPdf">
                                                        <button type="button" @click="resetPdf()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full text-xs shadow-md transition-colors" title="Restablecer / Deshacer cambio">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                                <div class="text-xs text-gray-700 truncate max-w-[200px] mx-auto font-bold" x-text="pdfFileName"></div>
                                                
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full"
                                                          :class="isNewPdf ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800'">
                                                        <span x-text="isNewPdf ? 'Nuevo PDF Seleccionado' : 'PDF Actual Registrado'"></span>
                                                    </span>
                                                    <template x-if="existingPdfUrl && !isNewPdf">
                                                        <a :href="existingPdfUrl" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-indigo-600 hover:text-indigo-800 font-bold hover:underline">
                                                            <i class="bi bi-eye"></i> Ver PDF Actual
                                                        </a>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="!pdfFileName">
                                            <div class="text-center space-y-2 text-gray-400">
                                                <i class="bi bi-file-earmark-arrow-up text-3xl opacity-50"></i>
                                                <p class="text-xs font-medium">Sin documento PDF registrado</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                @error('training_itinerary_path')
                                    <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- FOTOGRAFÍAS / GALERÍA DEL PROGRAMA --}}
                            <div class="md:col-span-2 space-y-5 pt-2">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                            Galería de Fotos del Programa <span class="text-gray-400 font-normal">(Múltiples Formatos)</span>
                                        </label>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Administra las fotos registradas y agrega nuevas imágenes representativas del programa. Formatos: PNG, JPG, JPEG, WEBP, GIF, SVG, AVIF (Máx. 10MB c/u).
                                        </p>
                                    </div>
                                    <div class="text-xs font-semibold text-gray-500">
                                        Total activo: <span x-text="existingImages.filter(img => !deletedImageIds.includes(img.id)).length + galleryFiles.length"></span> fotos
                                    </div>
                                </div>

                                {{-- Hidden Inputs for Deleted Images & Main Image --}}
                                <template x-for="delId in deletedImageIds" :key="delId">
                                    <input type="hidden" name="delete_images[]" :value="delId">
                                </template>
                                <input type="hidden" name="main_image_id" :value="mainImageId">
                                <input type="hidden" name="main_image_index" :value="mainImageIndex">

                                {{-- 1. FOTOS ACTUALES REGISTRADAS --}}
                                <div class="space-y-3">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-600 flex items-center gap-1.5">
                                        <i class="bi bi-images text-purple-600"></i>
                                        <span>Fotos Registradas Actualmente (<span x-text="existingImages.length"></span>)</span>
                                    </h4>

                                    <template x-if="existingImages.length === 0">
                                        <div class="p-6 bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-center text-gray-400">
                                            <i class="bi bi-image text-3xl opacity-50 block mb-1"></i>
                                            <p class="text-xs font-medium">Este programa aún no tiene fotos en su galería.</p>
                                        </div>
                                    </template>

                                    <template x-if="existingImages.length > 0">
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                            <template x-for="(img, idx) in existingImages" :key="img.id">
                                                <div class="relative group rounded-2xl border overflow-hidden shadow-sm transition-all duration-200"
                                                     :class="{
                                                         'border-red-400 bg-red-50/50 opacity-60': deletedImageIds.includes(img.id),
                                                         'border-purple-500 ring-2 ring-purple-200 bg-white': !deletedImageIds.includes(img.id) && mainImageId === img.id,
                                                         'border-gray-200 bg-white hover:border-gray-300': !deletedImageIds.includes(img.id) && mainImageId !== img.id
                                                     }">
                                                    
                                                    {{-- Thumbnail --}}
                                                    <div class="aspect-video w-full bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                                        <img :src="img.url" :alt="img.filename" class="w-full h-full object-cover">
                                                        
                                                        {{-- Strikethrough overlay when marked for deletion --}}
                                                        <template x-if="deletedImageIds.includes(img.id)">
                                                            <div class="absolute inset-0 bg-red-900/60 backdrop-blur-[1px] flex flex-col items-center justify-center text-white p-2 text-center z-10">
                                                                <i class="bi bi-trash-fill text-xl text-red-200 mb-1"></i>
                                                                <span class="text-[10px] font-bold uppercase tracking-wider">Marcada para eliminar</span>
                                                                <button type="button" @click="toggleDeleteImage(img.id)" class="mt-1.5 px-2 py-0.5 bg-white text-red-700 text-[10px] font-extrabold rounded-md hover:bg-gray-100 transition-colors">
                                                                    Deshacer
                                                                </button>
                                                            </div>
                                                        </template>

                                                        {{-- Main Badge / Action --}}
                                                        <template x-if="!deletedImageIds.includes(img.id)">
                                                            <div class="absolute top-2 left-2 z-20">
                                                                <button type="button" @click="setMainExistingImage(img)"
                                                                    class="px-2 py-1 rounded-lg text-[10px] font-bold shadow-md transition-all flex items-center gap-1 backdrop-blur-md"
                                                                    :class="mainImageId === img.id ? 'bg-amber-500 text-white' : 'bg-black/60 text-white hover:bg-black/80'">
                                                                    <i class="bi" :class="mainImageId === img.id ? 'bi-star-fill text-yellow-200' : 'bi-star'"></i>
                                                                    <span x-text="mainImageId === img.id ? 'Principal' : 'Elegir'"></span>
                                                                </button>
                                                            </div>
                                                        </template>

                                                        {{-- Delete Action --}}
                                                        <template x-if="!deletedImageIds.includes(img.id)">
                                                            <div class="absolute top-2 right-2 flex items-center gap-1 z-20">
                                                                <button type="button" @click="toggleDeleteImage(img.id)"
                                                                    class="w-6 h-6 rounded-full bg-red-600/90 hover:bg-red-700 text-white flex items-center justify-center text-xs shadow-md backdrop-blur-sm transition-transform hover:scale-110"
                                                                    title="Marcar para eliminar al guardar">
                                                                    <i class="bi bi-trash-fill"></i>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    {{-- Info --}}
                                                    <div class="p-2.5 bg-white">
                                                        <p class="text-xs font-semibold text-gray-800 truncate" :title="img.filename" x-text="img.filename"></p>
                                                        <div class="flex items-center justify-between mt-1">
                                                            <span class="text-[10px] font-semibold"
                                                                  :class="deletedImageIds.includes(img.id) ? 'text-red-500' : (mainImageId === img.id ? 'text-amber-600' : 'text-gray-400')">
                                                                <span x-text="deletedImageIds.includes(img.id) ? 'Se eliminará' : (mainImageId === img.id ? 'Foto destacada' : 'Foto de galería')"></span>
                                                            </span>
                                                            <template x-if="!deletedImageIds.includes(img.id)">
                                                                <button type="button" @click="deleteImageImmediately(img, idx)" class="text-[10px] text-red-500 hover:text-red-700 hover:underline font-medium" title="Eliminar inmediatamente de la base de datos">
                                                                    Eliminar ya
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                {{-- 2. AGREGAR NUEVAS FOTOS A LA GALERÍA --}}
                                <div class="space-y-3 pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-600 flex items-center gap-1.5">
                                            <i class="bi bi-cloud-arrow-up text-purple-600"></i>
                                            <span>Subir Nuevas Fotos a la Galería</span>
                                        </h4>
                                        <template x-if="galleryFiles.length > 0">
                                            <button type="button" @click="clearAllNewGalleryFiles()" class="text-xs font-semibold text-red-600 hover:text-red-700 hover:underline flex items-center gap-1">
                                                <i class="bi bi-trash"></i>
                                                <span>Quitar nuevas (<span x-text="galleryFiles.length"></span>)</span>
                                            </button>
                                        </template>
                                    </div>

                                    {{-- Drag & Drop Upload Area --}}
                                    <div class="relative border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-200 group"
                                         :class="isDraggingGallery ? 'border-purple-500 bg-purple-50/50' : 'border-gray-200 hover:border-purple-400 bg-gray-50/50 hover:bg-purple-50/20'"
                                         @dragover.prevent="isDraggingGallery = true"
                                         @dragleave.prevent="isDraggingGallery = false"
                                         @drop.prevent="handleGalleryDrop($event)">
                                        
                                        <input type="file" id="gallery_images_edit" name="images[]" multiple accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml,image/avif"
                                            @change="handleGalleryChange($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <div class="space-y-2 pointer-events-none">
                                            <div class="w-12 h-12 mx-auto rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                                <i class="bi bi-images"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-700">
                                                    Haz clic o arrastra fotos nuevas aquí para agregarlas
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Formatos: PNG, JPG, WEBP, GIF, SVG, AVIF (Máx. 10MB c/u)
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @error('images')
                                        <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('images.*')
                                        <p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>
                                    @enderror

                                    {{-- Live Preview of Newly Selected Images --}}
                                    <template x-if="galleryFiles.length > 0">
                                        <div class="space-y-3 pt-1">
                                            <div class="flex items-center justify-between text-xs font-semibold text-gray-600">
                                                <span>Nuevas fotos por subir (<span x-text="galleryFiles.length"></span>)</span>
                                                <span class="text-purple-600 font-medium text-[11px] flex items-center gap-1">
                                                    <i class="bi bi-star-fill text-amber-400"></i> También puedes marcar una foto nueva como Principal
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                                <template x-for="(item, idx) in galleryFiles" :key="idx">
                                                    <div class="relative group rounded-2xl border bg-white overflow-hidden shadow-sm transition-all duration-200"
                                                         :class="mainImageIndex === idx ? 'border-purple-500 ring-2 ring-purple-200' : 'border-gray-200 hover:border-gray-300'">
                                                        
                                                        {{-- Thumbnail --}}
                                                        <div class="aspect-video w-full bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                                            <img :src="item.preview" :alt="item.name" class="w-full h-full object-cover">
                                                            
                                                            {{-- Main Badge / Action --}}
                                                            <div class="absolute top-2 left-2 z-20">
                                                                <button type="button" @click="setMainNewGalleryIndex(idx)"
                                                                    class="px-2 py-1 rounded-lg text-[10px] font-bold shadow-md transition-all flex items-center gap-1 backdrop-blur-md"
                                                                    :class="mainImageIndex === idx ? 'bg-amber-500 text-white' : 'bg-black/60 text-white hover:bg-black/80'">
                                                                    <i class="bi" :class="mainImageIndex === idx ? 'bi-star-fill text-yellow-200' : 'bi-star'"></i>
                                                                    <span x-text="mainImageIndex === idx ? 'Principal' : 'Elegir'"></span>
                                                                </button>
                                                            </div>

                                                            {{-- Delete Button --}}
                                                            <button type="button" @click="removeGalleryFile(idx)"
                                                                class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center text-xs shadow-md backdrop-blur-sm transition-transform hover:scale-110 z-20"
                                                                title="Quitar foto nueva">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>

                                                        {{-- Info --}}
                                                        <div class="p-2.5 bg-white">
                                                            <p class="text-xs font-semibold text-gray-800 truncate" :title="item.name" x-text="item.name"></p>
                                                            <p class="text-[11px] text-gray-500" x-text="item.size"></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Configuración de Carrusel --}}
                            <div class="md:col-span-2 space-y-4 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-images text-blue-600 text-lg"></i>
                                    Configuración de Carrusel
                                </h3>
                                
                                {{-- Enable Carousel Checkbox --}}
                                <div class="space-y-2">
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" 
                                               name="enable_carousel" 
                                               value="1"
                                               {{ old('enable_carousel', $program->enable_carousel) ? 'checked' : '' }}
                                               class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                                        <div class="flex-1">
                                            <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                                                Activar carrusel dinámico en la portada del programa
                                            </span>
                                            <p class="text-xs text-gray-600 mt-1">
                                                Las imágenes de la galería se mostrarán como un carrusel giratorio automático en lugar de una imagen estática. El logo se mantiene visible.
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Alternative Text for Carousel --}}
                                <div class="space-y-1.5">
                                    <label for="carousel_alt_text" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                        Texto Alternativo para Carrusel
                                        <span class="text-gray-400 font-normal text-[11px]">(Opcional)</span>
                                    </label>
                                    <textarea id="carousel_alt_text" 
                                              name="carousel_alt_text"
                                              rows="2"
                                              placeholder="Texto descriptivo personalizado que aparecerá en todas las diapositivas del carrusel..."
                                              class="w-full px-4 py-3 bg-white border border-blue-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    >{{ old('carousel_alt_text', $program->carousel_alt_text) }}</textarea>
                                    <p class="text-xs text-gray-500">
                                        <i class="bi bi-info-circle text-blue-500"></i>
                                        Este texto se mostrará en el carrusel. Si se deja vacío, se usará el texto predeterminado del sistema.
                                    </p>
                                </div>
                            </div>

                            {{-- Descripción / Perfil --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Perfil del Egresado / Descripción <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description', $program->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Detalles / Duración y Título --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="details" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Detalles (Duración, Períodos y Título) <span class="text-red-500">*</span>
                                </label>
                                <textarea id="details" name="details" rows="3" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('details') border-red-500 @enderror">{{ old('details', $program->details) }}</textarea>
                                @error('details')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ícono --}}
                            <div class="space-y-1.5">
                                <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Ícono Principal (Bootstrap Icons)
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                        <i :class="'bi ' + selectedIcon"></i>
                                    </div>
                                    <input type="text" id="icon" name="icon" x-model="selectedIcon" value="{{ old('icon', $program->icon) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            {{-- Color de Acento --}}
                            <div class="space-y-1.5">
                                <label for="accent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Color de Acento
                                </label>
                                <select id="accent" name="accent" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="blue" {{ old('accent', $program->accent) == 'blue' ? 'selected' : '' }}>Azul (Blue)</option>
                                    <option value="emerald" {{ old('accent', $program->accent) == 'emerald' ? 'selected' : '' }}>Esmeralda (Emerald)</option>
                                    <option value="rose" {{ old('accent', $program->accent) == 'rose' ? 'selected' : '' }}>Rosa (Rose)</option>
                                    <option value="sky" {{ old('accent', $program->accent) == 'sky' ? 'selected' : '' }}>Cielo (Sky)</option>
                                    <option value="teal" {{ old('accent', $program->accent) == 'teal' ? 'selected' : '' }}>Verde Azulado (Teal)</option>
                                    <option value="indigo" {{ old('accent', $program->accent) == 'indigo' ? 'selected' : '' }}>Índigo (Indigo)</option>
                                </select>
                            </div>

                            {{-- Tag Informativo --}}
                            <div class="space-y-1.5">
                                <label for="tag" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Etiqueta / Tag
                                </label>
                                <input type="text" id="tag" name="tag" value="{{ old('tag', $program->tag) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Orden Secuencial --}}
                            <div class="space-y-1.5">
                                <label for="order" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Orden de Presentación <span class="text-gray-400 font-normal">(Secuencial)</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-sort-numeric-down absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="number" id="order" name="order" value="{{ old('order', $program->order ?? 1) }}" min="0" max="255" placeholder="Ej: 1, 2, 3..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('order') border-red-500 @enderror">
                                </div>
                                @error('order')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Badge visual CSS --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="bg_badge" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Estilo CSS de Badge
                                </label>
                                <input type="text" id="bg_badge" name="bg_badge" value="{{ old('bg_badge', $program->bg_badge) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2 pt-2">
                                <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $program->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Programa Activo</span>
                                        <span class="text-xs text-gray-500 block">Los programas activos se muestran públicamente en el portal institucional.</span>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Botones de Acción --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.programs.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Actualizar Programa</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    function studyProgramEditForm(defaultIcon, existingLogoUrl, originalFileName, existingPdfUrl, originalPdfName, initialImages) {
        return {
            selectedIcon: defaultIcon || 'bi-mortarboard-fill',
            logoPreview: existingLogoUrl || null,
            originalLogoUrl: existingLogoUrl || null,
            originalName: originalFileName || '',
            fileName: originalFileName || '',
            isNewFile: false,

            existingPdfUrl: existingPdfUrl || null,
            originalPdfName: originalPdfName || '',
            pdfFileName: originalPdfName || '',
            isNewPdf: false,

            // Gallery state
            existingImages: initialImages || [],
            deletedImageIds: [],
            mainImageId: (initialImages && initialImages.find(i => i.is_main)) ? initialImages.find(i => i.is_main).id : ((initialImages && initialImages.length > 0) ? initialImages[0].id : null),
            galleryFiles: [],
            mainImageIndex: null,
            isDraggingGallery: false,

            handleLogoChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.isNewFile = true;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            resetLogo() {
                this.logoPreview = this.originalLogoUrl;
                this.fileName = this.originalName;
                this.isNewFile = false;
                const input = document.getElementById('logo_path');
                if (input) input.value = '';
            },

            handlePdfChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.pdfFileName = file.name;
                    this.isNewPdf = true;
                }
            },

            resetPdf() {
                this.pdfFileName = this.originalPdfName;
                this.isNewPdf = false;
                const input = document.getElementById('training_itinerary_path');
                if (input) input.value = '';
            },

            toggleDeleteImage(id) {
                const idx = this.deletedImageIds.indexOf(id);
                if (idx > -1) {
                    this.deletedImageIds.splice(idx, 1);
                } else {
                    this.deletedImageIds.push(id);
                    if (this.mainImageId === id) {
                        const remaining = this.existingImages.filter(img => !this.deletedImageIds.includes(img.id));
                        this.mainImageId = remaining.length > 0 ? remaining[0].id : null;
                    }
                }
            },

            setMainExistingImage(img) {
                this.mainImageId = img.id;
                this.mainImageIndex = null;
            },

            async deleteImageImmediately(img, index) {
                if (!confirm('¿Estás seguro de que deseas eliminar permanentemente esta imagen de la base de datos?')) {
                    return;
                }

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const response = await fetch(img.destroy_url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token || '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        this.existingImages.splice(index, 1);
                        const delIdx = this.deletedImageIds.indexOf(img.id);
                        if (delIdx > -1) this.deletedImageIds.splice(delIdx, 1);
                        if (this.mainImageId === img.id) {
                            this.mainImageId = this.existingImages.length > 0 ? this.existingImages[0].id : null;
                        }
                    } else {
                        alert(data.message || 'Error al eliminar la imagen.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('No se pudo conectar con el servidor para eliminar la imagen.');
                }
            },

            handleGalleryChange(event) {
                const files = Array.from(event.target.files);
                files.forEach((file) => {
                    const exists = this.galleryFiles.some(f => f.name === file.name && f.sizeRaw === file.size);
                    if (!exists) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.galleryFiles.push({
                                file: file,
                                name: file.name,
                                size: this.formatBytes(file.size),
                                sizeRaw: file.size,
                                preview: e.target.result,
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
                this.syncGalleryInput();
            },

            handleGalleryDrop(event) {
                this.isDraggingGallery = false;
                const files = Array.from(event.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                files.forEach((file) => {
                    const exists = this.galleryFiles.some(f => f.name === file.name && f.sizeRaw === file.size);
                    if (!exists) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.galleryFiles.push({
                                file: file,
                                name: file.name,
                                size: this.formatBytes(file.size),
                                sizeRaw: file.size,
                                preview: e.target.result,
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
                this.syncGalleryInput();
            },

            removeGalleryFile(index) {
                this.galleryFiles.splice(index, 1);
                if (this.mainImageIndex === index) {
                    this.mainImageIndex = null;
                } else if (this.mainImageIndex > index) {
                    this.mainImageIndex--;
                }
                this.syncGalleryInput();
            },

            clearAllNewGalleryFiles() {
                this.galleryFiles = [];
                this.mainImageIndex = null;
                const input = document.getElementById('gallery_images_edit');
                if (input) input.value = '';
            },

            syncGalleryInput() {
                setTimeout(() => {
                    const dt = new DataTransfer();
                    this.galleryFiles.forEach(item => dt.items.add(item.file));
                    const input = document.getElementById('gallery_images_edit');
                    if (input) {
                        input.files = dt.files;
                    }
                }, 50);
            },

            setMainNewGalleryIndex(index) {
                this.mainImageIndex = index;
                this.mainImageId = null;
            },

            formatBytes(bytes) {
                if (!bytes || bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }
        }
    }
</script>

@endpush
@endsection
