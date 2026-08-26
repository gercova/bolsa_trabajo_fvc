@extends('layouts.app')
@section('title', 'Editar Diapositiva del Carrusel - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="carouselEditApp()">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Editar Diapositiva
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-building mr-1"></i> Empresa
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <a href="{{ route('admin.carousel.index') }}" class="hover:text-purple-600 transition-colors">Carrusel</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600 font-semibold">Editar</span>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-6xl mx-auto space-y-6">

                {{-- Errors Alert --}}
                @if (isset($errors) && $errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-octagon-fill text-rose-600 text-xl"></i>
                            <h3 class="text-sm font-bold text-rose-800">Por favor corrige los siguientes errores:</h3>
                        </div>
                        <ul class="mt-2 ml-7 list-disc text-xs font-medium text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Live Preview Box --}}
                <div class="bg-slate-950 rounded-3xl shadow-xl border border-slate-800 overflow-hidden relative select-none">
                    <div class="px-5 py-3 bg-slate-900/90 border-b border-slate-800 flex items-center justify-between text-xs text-slate-400">
                        <span class="font-semibold flex items-center gap-2 text-amber-300">
                            <i class="bi bi-eye-fill"></i> Vista Previa en Vivo (Hero Carrusel)
                        </span>
                        <span class="text-[11px] text-slate-500">Actualización en tiempo real</span>
                    </div>

                    <div class="relative min-h-[280px] sm:min-h-[320px] p-6 sm:p-10 flex flex-col justify-center overflow-hidden">
                        {{-- Background Image --}}
                        <div class="absolute inset-0 bg-cover bg-center transition-all duration-500"
                             :style="'background-image: url(' + (imagePreview || '{{ $carousel->image_url }}') + ')'">
                        </div>

                        {{-- Multi-layered Dark Vignette --}}
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 via-55% to-slate-950/30"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                        {{-- Content --}}
                        <div class="relative z-10 max-w-2xl space-y-3.5">
                            {{-- Tag Pill --}}
                            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold tracking-wide backdrop-blur-md transition-all"
                                 :class="getTagClasses()">
                                <i class="bi" :class="tag_icon || 'bi-mortarboard-fill'"></i>
                                <span x-text="tag || 'Admisión 2026-I • Modalidades Abiertas'"></span>
                            </div>

                            {{-- Title & Highlight --}}
                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                                <span x-text="title || 'Tu futuro profesional empieza aquí, en el'"></span>
                                <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r transition-all"
                                      :class="getGradientClasses()"
                                      x-text="highlight_text || 'IESTP Francisco Vigo Caballero'">
                                </span>
                            </h2>

                            {{-- Description --}}
                            <p class="text-xs sm:text-sm text-slate-200 line-clamp-2 leading-relaxed max-w-xl"
                               x-text="description || 'Estudia una de nuestras 5 carreras técnicas a Nombre de la Nación en Uchiza.'">
                            </p>

                            {{-- Buttons --}}
                            <div class="flex flex-wrap gap-2.5 pt-1">
                                <template x-if="primary_button_text">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r text-white text-xs font-bold rounded-xl shadow-lg transition-all"
                                         :class="getPrimaryButtonClasses()">
                                        <i class="bi" :class="primary_button_icon || 'bi-pencil-square'"></i>
                                        <span x-text="primary_button_text"></span>
                                    </div>
                                </template>
                                <template x-if="secondary_button_text">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 border border-white/25 text-white text-xs font-bold rounded-xl backdrop-blur-md">
                                        <i class="bi" :class="secondary_button_icon || 'bi-grid-3x3-gap-fill'"></i>
                                        <span x-text="secondary_button_text"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Card --}}
                <form action="{{ route('admin.carousel.update', $carousel) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- ── 1. Información Principal ─────────────────────── --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 sm:p-8 space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="bi bi-card-heading text-purple-600"></i> Contenido de la Diapositiva
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Edita los textos, títulos y colores que resaltarán en la portada.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Título Principal --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label for="title" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Título Principal <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" x-model="title" required
                                    placeholder="Ej: Tu futuro profesional empieza aquí, en el"
                                    value="{{ old('title', $carousel->title) }}"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                            </div>

                            {{-- Texto Resaltado --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label for="highlight_text" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Texto Resaltado (Con Degradado Luminoso)
                                </label>
                                <input type="text" id="highlight_text" name="highlight_text" x-model="highlight_text"
                                    placeholder="Ej: IESTP Francisco Vigo Caballero"
                                    value="{{ old('highlight_text', $carousel->highlight_text) }}"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                <p class="text-[11px] text-gray-500">Este fragmento se mostrará debajo del título con un efecto degradado llamativo.</p>
                            </div>

                            {{-- Etiqueta / Pill --}}
                            <div class="space-y-1.5">
                                <label for="tag" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Etiqueta / Badge Superior
                                </label>
                                <input type="text" id="tag" name="tag" x-model="tag"
                                    placeholder="Ej: Admisión 2026-I • Modalidades Abiertas"
                                    value="{{ old('tag', $carousel->tag) }}"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                            </div>

                            {{-- Ícono del Badge --}}
                            <div class="space-y-1.5">
                                <label for="tag_icon" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Ícono de la Etiqueta (Bootstrap Icons)
                                </label>
                                <div class="relative">
                                    <input type="text" id="tag_icon" name="tag_icon" x-model="tag_icon"
                                        placeholder="Ej: bi-mortarboard-fill, bi-cpu-fill, bi-heart-pulse-fill"
                                        value="{{ old('tag_icon', $carousel->tag_icon ?? 'bi-mortarboard-fill') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 pl-10 pr-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                    <i class="bi absolute left-3.5 top-1/2 -translate-y-1/2 text-purple-600" :class="tag_icon || 'bi-mortarboard-fill'"></i>
                                </div>
                            </div>

                            {{-- Paleta de Color / Acento --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Color de Acento & Degradados
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                                    @php
                                        $colorOptions = [
                                            ['value' => 'amber',   'label' => 'Amber / Oro',     'bg' => 'bg-amber-500'],
                                            ['value' => 'sky',     'label' => 'Sky / Celeste',   'bg' => 'bg-sky-500'],
                                            ['value' => 'rose',    'label' => 'Rose / Rosa',     'bg' => 'bg-rose-500'],
                                            ['value' => 'emerald', 'label' => 'Emerald / Verde', 'bg' => 'bg-emerald-500'],
                                            ['value' => 'indigo',  'label' => 'Indigo / Azul',   'bg' => 'bg-indigo-500'],
                                            ['value' => 'purple',  'label' => 'Purple / Violeta','bg' => 'bg-purple-500'],
                                        ];
                                    @endphp
                                    @foreach($colorOptions as $opt)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="tag_color" value="{{ $opt['value'] }}" x-model="tag_color" class="sr-only">
                                            <div class="p-3 rounded-2xl border-2 text-center transition-all flex flex-col items-center gap-2"
                                                 :class="tag_color === '{{ $opt['value'] }}' ? 'border-purple-600 bg-purple-50/50 shadow-sm' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                                <span class="w-5 h-5 rounded-full {{ $opt['bg'] }} shadow-sm"></span>
                                                <span class="text-xs font-bold text-gray-800">{{ $opt['label'] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <div class="space-y-1.5 md:col-span-2">
                                <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Descripción / Párrafo
                                </label>
                                <textarea id="description" name="description" x-model="description" rows="3"
                                    placeholder="Detalles breves de la convocatoria, carrera o innovación..."
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">{{ old('description', $carousel->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── 2. Botones de Acción ─────────────────────────── --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 sm:p-8 space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="bi bi-cursor-fill text-purple-600"></i> Botones de Llamado a la Acción (Call to Action)
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Configura hasta 2 botones con enlaces directos a secciones públicas o URLs externas.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Botón Primario --}}
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                                <span class="text-xs font-extrabold uppercase text-purple-700 tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-1-circle-fill"></i> Botón Principal (Destacado)
                                </span>

                                <div class="space-y-1.5">
                                    <label for="primary_button_text" class="block text-xs font-bold text-gray-700">Texto del Botón</label>
                                    <input type="text" id="primary_button_text" name="primary_button_text" x-model="primary_button_text"
                                        placeholder="Ej: Examen de Admisión" value="{{ old('primary_button_text', $carousel->primary_button_text) }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-medium">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="primary_button_url" class="block text-xs font-bold text-gray-700">Enlace / Ruta / URL</label>
                                    <input type="text" id="primary_button_url" name="primary_button_url"
                                        placeholder="Ej: examen-de-admision o /admision" value="{{ old('primary_button_url', $carousel->primary_button_url) }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="primary_button_icon" class="block text-xs font-bold text-gray-700">Ícono (Bootstrap Icons)</label>
                                    <input type="text" id="primary_button_icon" name="primary_button_icon" x-model="primary_button_icon"
                                        placeholder="Ej: bi-pencil-square" value="{{ old('primary_button_icon', $carousel->primary_button_icon ?? 'bi-pencil-square') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                                </div>
                            </div>

                            {{-- Botón Secundario --}}
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                                <span class="text-xs font-extrabold uppercase text-slate-700 tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-2-circle-fill"></i> Botón Secundario (Translúcido)
                                </span>

                                <div class="space-y-1.5">
                                    <label for="secondary_button_text" class="block text-xs font-bold text-gray-700">Texto del Botón</label>
                                    <input type="text" id="secondary_button_text" name="secondary_button_text" x-model="secondary_button_text"
                                        placeholder="Ej: Ver 5 Carreras" value="{{ old('secondary_button_text', $carousel->secondary_button_text) }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-medium">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="secondary_button_url" class="block text-xs font-bold text-gray-700">Enlace / Ruta / URL</label>
                                    <input type="text" id="secondary_button_url" name="secondary_button_url"
                                        placeholder="Ej: programas-de-estudio o /nosotros" value="{{ old('secondary_button_url', $carousel->secondary_button_url) }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="secondary_button_icon" class="block text-xs font-bold text-gray-700">Ícono (Bootstrap Icons)</label>
                                    <input type="text" id="secondary_button_icon" name="secondary_button_icon" x-model="secondary_button_icon"
                                        placeholder="Ej: bi-grid-3x3-gap-fill" value="{{ old('secondary_button_icon', $carousel->secondary_button_icon ?? 'bi-grid-3x3-gap-fill') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── 3. Imagen y Parámetros del Carrusel ──────────── --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 sm:p-8 space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="bi bi-image-fill text-purple-600"></i> Imagen de Fondo & Opciones
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Cambia o mantén la fotografía actual almacenada en el modelo Image.php.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Upload de Imagen --}}
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Fotografía de Fondo (Almacenada en Image.php)
                                </label>
                                
                                <div class="border-2 border-dashed border-gray-300 hover:border-purple-500 rounded-2xl p-5 text-center transition-colors bg-gray-50/50 relative">
                                    <input type="file" name="image" id="image" accept="image/*"
                                           @change="handleImageChange($event)"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="space-y-2">
                                        <i class="bi bi-cloud-arrow-up text-3xl text-purple-600"></i>
                                        <p class="text-xs font-bold text-gray-700">Haz clic o arrastra para cambiar la foto</p>
                                        <p class="text-[11px] text-gray-400">Deja vacío si deseas conservar la imagen actual</p>
                                    </div>
                                </div>

                                @if($carousel->image)
                                    <div class="flex items-center gap-2 text-xs text-gray-600 bg-gray-100 p-2.5 rounded-xl">
                                        <i class="bi bi-check-circle-fill text-emerald-600 text-sm"></i>
                                        <span class="font-medium truncate">Foto actual: {{ basename($carousel->image->path) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Opciones de Navegación y Orden --}}
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label for="indicator_label" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Etiqueta en Barra de Navegación Inferior
                                    </label>
                                    <input type="text" id="indicator_label" name="indicator_label"
                                        placeholder="Ej: Admisión 2026, Redes & TI, Enfermería..."
                                        value="{{ old('indicator_label', $carousel->indicator_label) }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                    <p class="text-[11px] text-gray-500">Texto breve que aparece en la píldora inferior del carrusel.</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label for="order" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Orden de Aparición
                                        </label>
                                        <input type="number" id="order" name="order" min="1"
                                            value="{{ old('order', $carousel->order) }}"
                                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold">
                                    </div>

                                    <div class="flex items-center pt-6">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $carousel->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-2 ring-purple-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                            <span class="ml-3 text-xs font-bold text-gray-800">Publicado</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('admin.carousel.index') }}"
                           class="px-6 py-3 bg-white hover:bg-gray-100 text-gray-700 text-sm font-bold rounded-xl border border-gray-200 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-7 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-purple-500/25 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="bi bi-check-circle-fill text-base"></i>
                            <span>Actualizar Diapositiva</span>
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function carouselEditApp() {
        return {
            title: '{{ old('title', addslashes($carousel->title)) }}',
            highlight_text: '{{ old('highlight_text', addslashes($carousel->highlight_text ?? '')) }}',
            tag: '{{ old('tag', addslashes($carousel->tag ?? '')) }}',
            tag_icon: '{{ old('tag_icon', $carousel->tag_icon ?? 'bi-mortarboard-fill') }}',
            tag_color: '{{ old('tag_color', $carousel->tag_color ?? 'amber') }}',
            description: '{{ old('description', addslashes($carousel->description ?? '')) }}',
            primary_button_text: '{{ old('primary_button_text', addslashes($carousel->primary_button_text ?? '')) }}',
            primary_button_icon: '{{ old('primary_button_icon', $carousel->primary_button_icon ?? 'bi-pencil-square') }}',
            secondary_button_text: '{{ old('secondary_button_text', addslashes($carousel->secondary_button_text ?? '')) }}',
            secondary_button_icon: '{{ old('secondary_button_icon', $carousel->secondary_button_icon ?? 'bi-grid-3x3-gap-fill') }}',
            imagePreview: null,

            handleImageChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            getTagClasses() {
                switch(this.tag_color) {
                    case 'sky':     return 'bg-sky-500/20 border border-sky-400/40 text-sky-300';
                    case 'rose':    return 'bg-rose-500/20 border border-rose-400/40 text-rose-300';
                    case 'emerald': return 'bg-emerald-500/20 border border-emerald-400/40 text-emerald-300';
                    case 'indigo':  return 'bg-indigo-500/20 border border-indigo-400/40 text-indigo-300';
                    case 'purple':  return 'bg-purple-500/20 border border-purple-400/40 text-purple-300';
                    default:        return 'bg-amber-500/20 border border-amber-400/40 text-amber-300';
                }
            },

            getGradientClasses() {
                switch(this.tag_color) {
                    case 'sky':     return 'from-sky-300 via-cyan-300 to-indigo-300';
                    case 'rose':    return 'from-rose-300 via-pink-300 to-amber-300';
                    case 'emerald': return 'from-emerald-300 via-teal-300 to-lime-300';
                    case 'indigo':  return 'from-indigo-300 via-purple-300 to-sky-300';
                    case 'purple':  return 'from-purple-300 via-pink-300 to-amber-300';
                    default:        return 'from-amber-300 via-sky-300 to-cyan-300';
                }
            },

            getPrimaryButtonClasses() {
                switch(this.tag_color) {
                    case 'sky':     return 'from-sky-500 to-blue-600 shadow-sky-500/25';
                    case 'rose':    return 'from-rose-500 to-red-600 shadow-rose-500/25';
                    case 'emerald': return 'from-emerald-500 to-teal-600 shadow-emerald-500/25';
                    case 'indigo':  return 'from-indigo-500 to-purple-600 shadow-indigo-500/25';
                    case 'purple':  return 'from-purple-500 to-indigo-600 shadow-purple-500/25';
                    default:        return 'from-sky-500 to-blue-600 shadow-sky-500/25';
                }
            }
        }
    }
</script>
@endpush
