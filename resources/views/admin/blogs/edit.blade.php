@extends('layouts.app')
@section('title', 'Editar Publicación de Blog - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.blogs.index') }}" class="mr-3 text-gray-500 hover:text-purple-600 p-1 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Editar Publicación: {{ Str::limit($blog->title, 40) }}
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.blogs.index') }}" class="hover:text-purple-600">Blogs y Noticias</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Entrada</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- General Validation Errors Alert --}}
                @if(isset($errors) && $errors->count() > 0)
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-bold text-rose-800">Se encontraron errores en el formulario:</h3>
                                <ul class="mt-1 text-xs text-rose-700 space-y-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Título --}}
                            <div class="md:col-span-2">
                                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Título de la Publicación <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" 
                                    placeholder="Ingrese el título principal de la noticia o artículo..."
                                    class="w-full text-base font-semibold px-4 py-3 border @error('title') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                                    required>
                                @error('title')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Imagen de Portada --}}
                            @php
                                $currentCover = $blog->images->first()?->url ?? null;
                            @endphp
                            <div class="md:col-span-2" x-data="{ imagePreview: '{{ $currentCover }}' }">
                                <label for="image" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Imagen Principal / Portada
                                </label>
                                
                                <div class="flex flex-col sm:flex-row items-center gap-4">
                                    {{-- Preview Box --}}
                                    <div class="w-full sm:w-40 h-32 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
                                        <template x-if="imagePreview">
                                            <img :src="imagePreview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!imagePreview">
                                            <div class="text-center p-3 text-gray-400">
                                                <i class="bi bi-image text-3xl block mb-1"></i>
                                                <span class="text-[10px] font-semibold">Sin imagen</span>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- File Input --}}
                                    <div class="w-full flex-1">
                                        <input type="file" name="image" id="image" accept="image/*"
                                            @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    imagePreview = URL.createObjectURL(file);
                                                }
                                            "
                                            class="w-full text-xs text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all border border-gray-300 rounded-xl p-1">
                                        <p class="text-[11px] text-gray-500 mt-1.5">Suba una nueva imagen únicamente si desea reemplazar la portada existente.</p>
                                        @error('image')
                                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Contenido Principal --}}
                            <div class="md:col-span-2">
                                <label for="content" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Contenido Principal del Blog <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="content" id="content" rows="6" 
                                    placeholder="Escriba el cuerpo del artículo o la noticia completa..."
                                    class="w-full text-sm p-4 border @error('content') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                                    required>{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Detalles Adicionales / Notas Secundarias --}}
                            <div class="md:col-span-2">
                                <label for="details" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Detalles Adicionales o Notas Secundarias (Opcional)
                                </label>
                                <textarea name="details" id="details" rows="3" 
                                    placeholder="Agregue información complementaria, referencias, fuentes o notas adicionales..."
                                    class="w-full text-sm p-4 border @error('details') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">{{ old('details', $blog->details) }}</textarea>
                                @error('details')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Publicado --}}
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 rounded-md border-gray-300 focus:ring-purple-500">
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">Publicado</span>
                                        <p class="text-xs text-gray-500">Si está marcado, la noticia estará visible públicamente en el portal web.</p>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.blogs.index') }}" 
                               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all flex items-center gap-2">
                                <i class="bi bi-save"></i> Actualizar Publicación
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
