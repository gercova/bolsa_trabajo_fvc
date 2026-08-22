@extends('layouts.app')
@php
    // Dynamic visual metadata derived directly from ProgramMeta model or StudyProgram attributes
    $metaObj = $program->meta;

    $accent           = $metaObj?->accent ?? $program->accent ?? 'blue';
    $icon             = $metaObj?->icon ?? $program->icon ?? 'bi-mortarboard-fill';
    $badge            = $metaObj?->tag ?? $program->tag ?? 'Carrera Profesional Técnica';
    $glowClass        = $metaObj?->glow_class ?? $program->glow_class ?? "bg-{$accent}-500/20";
    $badgeClass       = $metaObj?->badge_class ?? $program->badge_class ?? "bg-{$accent}-500/15 text-{$accent}-300 border-{$accent}-500/30";
    $accentText       = $metaObj?->accent_text ?? $program->accent_text ?? "text-{$accent}-300";
    $bulletClass      = $metaObj?->bullet_class ?? $program->bullet_class ?? "bg-{$accent}-600";
    $iconBgClass      = $metaObj?->icon_bg_class ?? $program->icon_bg_class ?? "bg-{$accent}-50 text-{$accent}-600 border-{$accent}-100";
    $borderHoverClass = $metaObj?->border_hover_class ?? $program->border_hover_class ?? "hover:border-{$accent}-300";
    $badgeModuleClass = $metaObj?->badge_module_class ?? $program->badge_module_class ?? "bg-{$accent}-100 text-{$accent}-800";
    $sidebarIconClass = $metaObj?->sidebar_icon_class ?? $program->sidebar_icon_class ?? "text-{$accent}-600";
    $ctaBgClass       = $metaObj?->cta_bg_class ?? $program->cta_bg_class ?? "from-{$accent}-600 to-indigo-800";
    $barColorClass    = $metaObj?->bar_color_class ?? $program->bar_color_class ?? "bg-{$accent}-500";

    $mainImage = $program->images->first(fn($img) => $img->is_main) ?? $program->images->first();
    $albumImages = $program->images;
@endphp

@section('title', $program->name . ' — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- Font Awesome 6.5.1 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- SEO Meta Tags --}}
    <meta name="description" content="{{ Str::limit(strip_tags($program->description), 155) }}">
    <meta name="keywords"
        content="{{ $program->name }}, carrera tecnica, IESTP Francisco Vigo Caballero, Uchiza, {{ $badge }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $program->name }} — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="{{ Str::limit(strip_tags($program->description), 155) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($mainImage)
        <meta property="og:image"
            content="{{ Str::startsWith($mainImage->path, ['http://', 'https://']) ? $mainImage->path : asset('storage/' . $mainImage->path) }}">
    @endif

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Course",
      "name": "{{ $program->name }}",
      "description": "{{ strip_tags($program->description) }}",
      "provider": {
        "@type": "EducationalOrganization",
        "name": "IESTP Francisco Vigo Caballero",
        "sameAs": "{{ url('/') }}"
      },
      "educationalCredentialAwarded": "Profesional Técnico en {{ $program->name }}"
    }
    </script>

    <style>
        .glow-effect {
            box-shadow: 0 0 25px -5px rgba(0, 0, 0, 0.08), 0 0 15px -3px rgba(0, 0, 0, 0.03);
        }

        .album-hover {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .album-hover:hover {
            transform: scale(1.04);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    {{-- ===== HERO HEADER SECTION ===== --}}
    <section class="bg-gradient-to-br from-slate-900 via-slate-950 to-blue-950 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 {{ $glowClass }} rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                {{-- Text column --}}
                <div class="lg:w-7/12 text-left">
                    <span
                        class="inline-flex items-center gap-1.5 {{ $badgeClass }} text-sm font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border">
                        <i class="bi {{ $icon }} text-sm"></i>
                        {{ $badge }}
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                        {{ $program->name }}
                    </h1>
                    <p class="text-base md:text-lg text-slate-300 mb-8 leading-relaxed max-w-2xl">
                        Prepárate para destacar en el campo laboral de manera rápida y efectiva con nuestro programa
                        formativo integral, gratuito y con certificación oficial.
                    </p>

                    {{-- Quick Metadata Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-6 border-t border-white/10 max-w-xl">
                        <div>
                            <p class="text-sm text-gray-400 uppercase font-semibold tracking-wider">Duración</p>
                            <p class="text-xl font-bold {{ $accentText }} mt-1">3 Años</p>
                            <p class="text-sm text-gray-500">6 Ciclos Académicos</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400 uppercase font-semibold tracking-wider">Certificaciones</p>
                            <p class="text-xl font-bold {{ $accentText }} mt-1">Modulares</p>
                            <p class="text-sm text-gray-500">Anuales de Minedu</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <p class="text-sm text-gray-400 uppercase font-semibold tracking-wider">Inversión</p>
                            <p class="text-xl font-bold {{ $accentText }} mt-1">Gratuito</p>
                            <p class="text-sm text-gray-500">Educación Pública</p>
                        </div>
                    </div>
                </div>

                {{-- Visual column (Cover Image) --}}
                <div class="lg:w-5/12 w-full">
                    <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent z-10">
                        </div>

                        @if ($program->logo_path)
                            <img src="{{ Str::startsWith($program->logo_path, ['http://', 'https://']) ? $program->logo_path : asset('storage/' . $program->logo_path) }}"
                                alt="{{ $program->name }}"
                                class="w-full h-80 lg:h-96 object-cover transform scale-105 hover:scale-100 transition-transform duration-700">
                        @else
                            <div
                                class="w-full h-80 lg:h-96 bg-gradient-to-br {{ $ctaBgClass }} flex items-center justify-center">
                                <i class="bi {{ $icon }} text-9xl text-white/20"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MAIN LAYOUT GRID ===== --}}
    <section class="py-16 bg-gray-50/50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- LEFT/MAIN COLUMN (2 spans) --}}
                <div class="lg:col-span-2 space-y-12">

                    {{-- About card --}}
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-2 h-8 {{ $barColorClass }} rounded-full"></span>
                            Sobre la Carrera Profesional
                        </h2>
                        <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                            <p class="text-base md:text-lg font-medium text-gray-800">
                                {{ $program->description }}
                            </p>
                        </div>
                    </div>

                    {{-- Competencies --}}
                    @if($program->competencies->isNotEmpty())
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 {{ $barColorClass }} rounded-full"></span>
                                Perfil de Competencias Específicas
                            </h2>
                            <p class="text-gray-600 mb-8 text-base">
                                A lo largo de los 3 años de formación práctica, el estudiante del programa desarrollará
                                capacidades técnicas en las siguientes áreas de especialización:
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($program->competencies as $item)
                                    <div
                                        class="flex gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 {{ $borderHoverClass }} transition-colors">
                                        <div
                                            class="w-12 h-12 rounded-xl {{ $iconBgClass }} flex items-center justify-center shrink-0 border">
                                            <i class="fa-solid {{ $item->icon ?? 'fa-graduation-cap' }} text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-base mb-1">{{ $item->title }}</h3>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $item->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Curriculum & Modular certifications / Itinerario Formativo --}}
                    @if ($program->modules->isNotEmpty() || $program->training_itinerary_path)
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-8">
                            <div>
                                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                    <span class="w-2 h-8 {{ $barColorClass }} rounded-full"></span>
                                    Plan de Estudios e Itinerario Formativo
                                </h2>
                                <p class="text-gray-600 text-base">
                                    Nuestro plan curricular está estructurado modularmente para permitir una rápida inserción
                                    laboral. Cada año académico completado te acredita con un certificado oficial del Ministerio
                                    de Educación:
                                </p>
                            </div>

                            @if ($program->modules->isNotEmpty())
                                <div class="space-y-6">
                                    @foreach ($program->modules as $index => $module)
                                        <div class="timeline-item flex gap-6 relative">
                                            {{-- Bullet decoration --}}
                                            <div
                                                class="timeline-bullet w-10 h-10 rounded-full {{ $bulletClass }} text-white font-extrabold flex items-center justify-center shrink-0 relative z-10 shadow-sm">
                                                {{ $index + 1 }}
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-grow bg-gray-50 border border-gray-100 rounded-2xl p-6 relative">
                                                <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                                                    <span
                                                        class="px-3 py-1 text-sm font-bold uppercase tracking-wider rounded-full {{ $badgeModuleClass }}">
                                                        Módulo Informativo {{ $index + 1 }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 font-medium flex items-center gap-1">
                                                        <i class="bi bi-calendar3"></i> Año Académico {{ $index + 1 }}
                                                    </span>
                                                </div>
                                                <h3 class="font-extrabold text-gray-900 text-lg mb-2">
                                                    {{ $module->module }}
                                                </h3>
                                                <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                                                    Certificación otorgada al concluir satisfactoriamente las unidades
                                                    didácticas teóricas y las correspondientes experiencias formativas en situaciones
                                                    reales de trabajo.
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Itinerario Formativo Document Download / View Card --}}
                            @if ($program->training_itinerary_path)
                                @php
                                    $itineraryUrl = $program->training_itinerary_url;
                                @endphp
                                <div class="p-6 md:p-8 rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white border border-slate-700/60 shadow-lg relative overflow-hidden">
                                    {{-- Ambient decorative background lights --}}
                                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
                                    <div class="absolute -left-10 -top-10 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

                                    <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                                        <div class="flex items-start gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-red-500/20 border border-red-400/30 text-red-400 flex items-center justify-center text-3xl shrink-0 shadow-inner">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-red-500/20 text-red-300 border border-red-500/30">
                                                        Documento Oficial
                                                    </span>
                                                    <span class="text-xs text-slate-400 font-medium">Itinerario Formativo (PDF)</span>
                                                </div>
                                                <h3 class="text-lg md:text-xl font-black text-white tracking-tight">
                                                    Itinerario Formativo y Malla Curricular
                                                </h3>
                                                <p class="text-xs md:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                                                    Consulta el plan de estudios oficial, distribución de unidades didácticas, créditos y horas lectivas de {{ $program->name }}.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 w-full lg:w-auto shrink-0 flex-wrap sm:flex-nowrap">
                                            {{-- Botón 1: Ver documento en nueva pestaña --}}
                                            <a href="{{ $itineraryUrl }}" target="_blank" rel="noopener noreferrer"
                                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs md:text-sm border border-white/20 backdrop-blur-sm transition-all duration-200 shadow-sm hover:scale-[1.02] active:scale-[0.98]">
                                                <i class="bi bi-box-arrow-up-right text-sm"></i>
                                                <span>Ver Documento</span>
                                            </a>

                                            {{-- Botón 2: Descargar documento --}}
                                            <a href="{{ $itineraryUrl }}" download="{{ Str::slug($program->name) }}-itinerario-formativo.pdf"
                                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs md:text-sm transition-all duration-200 shadow-md hover:shadow-purple-500/25 hover:scale-[1.02] active:scale-[0.98]">
                                                <i class="bi bi-download text-sm"></i>
                                                <span>Descargar PDF</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- PHOTO ALBUM / GALLERY --}}
                    @if ($albumImages->isNotEmpty())
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm" x-data="{
                            open: false,
                            activeIndex: 0,
                            images: [
                                @foreach ($albumImages as $img)
                                        '{{ Str::startsWith($img->path, ['http://', 'https://']) ? $img->path : asset('storage/' . $img->path) }}', @endforeach
                            ],
                            next() {
                                this.activeIndex = (this.activeIndex + 1) % this.images.length;
                            },
                            prev() {
                                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                            }
                        }"
                            @keyup.window.escape="open = false" @keyup.window.right="if (open) next()"
                            @keyup.window.left="if (open) prev()">

                            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 {{ $barColorClass }} rounded-full"></span>
                                Álbum de Fotos & Galería
                            </h2>
                            <p class="text-gray-600 mb-8 text-base">
                                Explora las actividades prácticas, laboratorios, talleres y el trabajo de campo que realizan
                                nuestros estudiantes.
                            </p>

                            {{-- Grid layout --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($albumImages as $idx => $img)
                                    @php
                                        $imgPath = Str::startsWith($img->path, ['http://', 'https://'])
                                            ? $img->path
                                            : asset('storage/' . $img->path);
                                    @endphp
                                    <div class="relative group rounded-2xl overflow-hidden cursor-pointer album-hover aspect-video border border-gray-100"
                                        @click="open = true; activeIndex = {{ $idx }}">
                                        <img src="{{ $imgPath }}" alt="Galería {{ $program->name }}"
                                            class="w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-lg">
                                                <i class="bi bi-eye-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- LIGHTBOX MODAL --}}
                            <template x-teleport="body">
                                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/95 backdrop-blur-sm p-4 select-none"
                                    style="display: none;">

                                    {{-- Close area --}}
                                    <div class="absolute inset-0" @click="open = false"></div>

                                    {{-- Lightbox content --}}
                                    <div class="relative max-w-5xl w-full flex flex-col items-center justify-center z-10"
                                        @click.stop>

                                        {{-- Image block --}}
                                        <div
                                            class="relative max-h-[80vh] w-full flex items-center justify-center overflow-hidden rounded-2xl">
                                            <img :src="images[activeIndex]"
                                                class="max-h-[80vh] max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300">

                                            {{-- Next/Prev Buttons --}}
                                            <button @click="prev()"
                                                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-sm border border-white/10 transition-colors">
                                                <i class="bi bi-chevron-left text-xl"></i>
                                            </button>
                                            <button @click="next()"
                                                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-sm border border-white/10 transition-colors">
                                                <i class="bi bi-chevron-right text-xl"></i>
                                            </button>
                                        </div>

                                        {{-- Footer metadata & actions --}}
                                        <div class="w-full flex items-center justify-between text-white mt-6 px-2">
                                            <span class="text-base font-semibold tracking-wide">
                                                {{ $program->name }} — Foto <span x-text="activeIndex + 1"></span> de
                                                <span x-text="images.length"></span>
                                            </span>
                                            <button @click="open = false"
                                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-base font-bold border border-white/10 transition-colors">
                                                <i class="bi bi-x-lg"></i> Cerrar
                                            </button>
                                        </div>

                                        {{-- Thumbnails indicators --}}
                                        <div class="flex items-center justify-center gap-2 mt-4 flex-wrap">
                                            <template x-for="(img, idx) in images" :key="idx">
                                                <button @click="activeIndex = idx"
                                                    class="w-3 h-3 rounded-full transition-all"
                                                    :class="activeIndex === idx ? '{{ $barColorClass }} scale-125' :
                                                        'bg-white/40 hover:bg-white/60'"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                        </div>
                    @endif

                </div>

                {{-- RIGHT/SIDEBAR COLUMN (1 span) --}}
                <div class="space-y-8">

                    {{-- Technical sheet --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm glow-effect">
                        <h3
                            class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                            <i class="bi bi-info-square {{ $sidebarIconClass }} text-lg"></i>
                            Ficha Técnica
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start gap-3.5">
                                <div
                                    class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-award text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Título Oficial</p>
                                    <p class="text-sm text-gray-700 font-semibold mt-0.5 leading-snug">
                                        Profesional Técnico a Nombre de la Nación
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3.5">
                                <div
                                    class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-hourglass-split text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Duración de Carrera</p>
                                    <p class="text-sm text-gray-700 font-semibold mt-0.5">3 Años (6 Semestres)</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3.5">
                                <div
                                    class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-clock text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Horario Regular</p>
                                    <p class="text-sm text-gray-700 font-semibold mt-0.5">Lunes a Viernes 7:30 am – 1:30 pm</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3.5">
                                <div
                                    class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-cash-coin text-base"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Pensión y Mensualidad</p>
                                    <p class="text-sm text-gray-700 font-semibold mt-0.5">Costo Social S/ 0 (Gratuito)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Career Prospects / Campo Laboral --}}
                    @if($program->jobFields->isNotEmpty())
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm glow-effect">
                            <h3
                                class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                                <i class="bi bi-briefcase {{ $sidebarIconClass }} text-lg"></i>
                                Campo Laboral
                            </h3>
                            <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                                Al egresar, estarás preparado para desempeñarte con éxito en:
                            </p>

                            <ul class="space-y-3">
                                @foreach ($program->jobFields as $item)
                                    <li class="text-sm text-gray-700 flex items-start gap-2.5 leading-relaxed">
                                        <i
                                            class="bi bi-check2-circle {{ $sidebarIconClass }} shrink-0 mt-0.5 text-base"></i>
                                        <span>{{ $item->description }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Admission Requirements --}}
                    @if($program->requirements->isNotEmpty())
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm glow-effect">
                            <h3
                                class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                                <i class="bi bi-clipboard2-check {{ $sidebarIconClass }} text-lg"></i>
                                Requisitos de Matrícula
                            </h3>
                            <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                                Documentación mínima necesaria para el proceso de admisión:
                            </p>

                            <ul class="space-y-3">
                                @foreach ($program->requirements as $item)
                                    <li class="text-sm text-gray-700 flex items-start gap-2.5 leading-relaxed font-semibold">
                                        <i class="bi bi-dot {{ $sidebarIconClass }} shrink-0 text-xl -mt-1"></i>
                                        <span>{{ $item->description }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- CTA Box --}}
                    <div
                        class="bg-gradient-to-br {{ $ctaBgClass }} rounded-3xl p-6 text-white text-center shadow-lg relative overflow-hidden">
                        <div
                            class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:12px_12px] z-0">
                        </div>
                        <div class="relative z-10">
                            <h4 class="font-extrabold text-xl mb-3">¿Deseas iniciar tu inscripción?</h4>
                            <p class="text-sm text-white/80 leading-relaxed mb-6">
                                Las vacantes son limitadas y no pagas mensualidad alguna durante toda la carrera técnica.
                                ¡Asegura tu futuro!
                            </p>
                            <a href="{{ route('examen-de-admision') }}"
                                class="inline-block w-full bg-white text-slate-900 hover:bg-gray-100 py-3 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-md">
                                Iniciar Inscripción
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
