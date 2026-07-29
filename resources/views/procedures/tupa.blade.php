@extends('layouts.app')

@section('title', 'TUPA — Texto Único de Procedimientos Administrativos — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO Meta Tags --}}
    <meta name="description" content="Reglamento del Texto Único de Procedimientos Administrativos (TUPA) del IESTP Francisco Vigo Caballero de Uchiza. Consulta todos los trámites académicos, requisitos, tasas, derechos de pago y plazos de atención.">
    <meta name="keywords" content="TUPA, TUPA IESTP Francisco Vigo Caballero, reglamentos de trámites, requisitos de trámites, tasas educativas, derechos de pago, Uchiza, certificado de estudios, titulación, matrícula">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="TUPA — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Texto Único de Procedimientos Administrativos del IESTP Francisco Vigo Caballero. Descarga el reglamento oficial y consulta trámites, costos y requisitos.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentOffice",
      "name": "Texto Único de Procedimientos Administrativos (TUPA) — IESTP Francisco Vigo Caballero",
      "description": "Reglamento oficial que compendia los procedimientos administrativos, requisitos, costos y plazos de atención para estudiantes y público general.",
      "url": "{{ url()->current() }}",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Av. Ricardo Palma N° 1401",
        "addressLocality": "Uchiza",
        "addressCountry": "PE"
      }
    }
    </script>

    <style>
        .procedure-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .procedure-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -10px rgba(37, 99, 235, 0.12);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
@endpush

@section('content')
    @php
        $theme = [
            'glow' => 'bg-blue-500/20',
            'bar' => 'bg-blue-600',
            'badge' => 'bg-blue-50 text-blue-700 border-blue-100',
            'accent' => 'text-blue-600',
            'btn_submit' => 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700'
        ];
    @endphp

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="absolute -top-32 -right-32 w-80 h-80 {{ $theme['glow'] }} rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-300 border border-blue-400/20 text-xs font-semibold uppercase tracking-wider mb-6">
                <i class="bi bi-shield-check text-blue-400"></i> Marco Normativo Institucional
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                Texto Único de Procedimientos Administrativos
            </h1>
            <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                Compendio de todos los trámites, requisitos, derechos de pago y plazos estipulados para la comunidad educativa y usuarios del IESTP Francisco Vigo Caballero.
            </p>
        </div>
    </section>

    {{-- ===== MAIN CONTENT CONTAINER WITH ALPINE APP ===== --}}
    <section class="py-16 bg-slate-50/60" x-data="publicTupaApp()">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl space-y-12">

            {{-- ===== SECTION 1: DOCUMENTO TUPA PDF VIGENTE ===== --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl overflow-hidden p-6 sm:p-8 lg:p-10 relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full blur-3xl -z-10"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-600 text-white shadow-sm">
                                TUPA Vigente {{ $currentTupa ? $currentTupa->year : date('Y') }}
                            </span>
                            @if($currentTupa && $currentTupa->effective_start_date)
                                <span class="text-xs text-slate-500 flex items-center gap-1 font-medium">
                                    <i class="bi bi-calendar3 text-blue-600"></i> Vigente desde el {{ $currentTupa->effective_start_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                            {{ $currentTupa->title ?? 'Reglamento Oficial TUPA - IESTP Francisco Vigo Caballero' }}
                        </h2>

                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                            {{ $currentTupa->description ?? 'Consulte y descargue el documento normativo completo que estipula todas las normas, resoluciones y el cuadro tarifario oficial vigente en la institución.' }}
                        </p>

                        <div class="flex flex-wrap gap-3 pt-2">
                            @if($currentTupa && $currentTupa->url)
                                <button type="button" @click="openPdfModal('{{ $currentTupa->url }}', '{{ addslashes($currentTupa->title) }}')" class="inline-flex items-center gap-2.5 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/20 transition-all duration-200">
                                    <i class="bi bi-file-earmark-pdf text-lg"></i>
                                    <span>Ver PDF Interactivo</span>
                                </button>
                                
                                <a href="{{ $currentTupa->url }}" target="_blank" download class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors">
                                    <i class="bi bi-download text-base"></i>
                                    <span>Descargar PDF</span>
                                </a>
                            @else
                                <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs sm:text-sm font-medium flex items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-amber-600 text-lg"></i>
                                    <span>El archivo PDF correspondiente al periodo actual se encuentra en proceso de actualización administrativa.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card right graphic / summary badge --}}
                    <div class="lg:col-span-4 bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-2xl p-6 sm:p-8 space-y-6 shadow-2xl relative overflow-hidden">
                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-blue-500/20 rounded-full blur-xl"></div>
                        <div class="flex items-center justify-between">
                            <i class="bi bi-folder-check text-4xl text-blue-400"></i>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-white/10 rounded-lg backdrop-blur-md">Oficial</span>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-bold">Total Trámites Reglamentados</p>
                            <p class="text-3xl font-black text-white mt-1">14 Conceptos</p>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-white/10 text-xs text-slate-300">
                            <div class="flex justify-between">
                                <span>Unidad Impositiva Tributaria (UIT):</span>
                                <strong class="text-white">Año Vigente</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Atención Presencial / Virtual:</span>
                                <strong class="text-emerald-400">Habilitado</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION 2: BUSCADOR Y CATÁLOGO DE TRÁMITES Y CONCEPTOS ===== --}}
            <div class="space-y-8">
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <h2 class="text-3xl font-extrabold text-slate-900">Catálogo de Trámites, Requisitos y Tasas</h2>
                    <p class="text-slate-600 text-sm sm:text-base">
                        Filtre y explore los procedimientos administrativos por categoría o busque directamente por palabra clave para conocer los requisitos, costos y tiempos de atención.
                    </p>
                </div>

                {{-- Control Filters Bar --}}
                <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        {{-- Search Input --}}
                        <div class="md:col-span-6 relative">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                            <input type="text" x-model="search" placeholder="Buscar trámite, requisito o código (ej: Certificado, Titulación, P-01)..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        </div>

                        {{-- Category Select Pills --}}
                        <div class="md:col-span-6 flex flex-wrap gap-2">
                            <button type="button" @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                Todos ({{ count(array_merge(...array_column($procedures, 'items'))) }})
                            </button>
                            @foreach($procedures as $group)
                                <button type="button" @click="selectedCategory = '{{ addslashes($group['category']) }}'" :class="selectedCategory === '{{ addslashes($group['category']) }}' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                    {{ $group['category'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Procedures Grid --}}
                <div class="space-y-10">
                    @foreach($procedures as $group)
                        <div x-show="shouldShowCategory('{{ addslashes($group['category']) }}', [{{ implode(',', array_map(fn($item) => "'".addslashes($item['code'])."'", $group['items'])) }}])" class="space-y-6">
                            
                            {{-- Group Title Header --}}
                            <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                                    <i class="bi {{ $group['icon'] }} text-xl"></i>
                                </div>
                                <h3 class="text-xl font-extrabold text-slate-900">{{ $group['category'] }}</h3>
                            </div>

                            {{-- Cards Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($group['items'] as $item)
                                    <div x-show="matchesSearch('{{ addslashes($item['code']) }}', '{{ addslashes($item['name']) }}', '{{ addslashes($item['description']) }}', '{{ addslashes(implode(' ', $item['requirements'])) }}')" class="procedure-card bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between space-y-6 relative overflow-hidden">
                                        
                                        <div class="space-y-4">
                                            {{-- Card Header --}}
                                            <div class="flex items-start justify-between gap-3">
                                                <span class="px-2.5 py-1 bg-slate-900 text-white rounded-lg text-xs font-bold tracking-wider">
                                                    {{ $item['code'] }}
                                                </span>
                                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    {{ $item['qualification'] }}
                                                </span>
                                            </div>

                                            <div>
                                                <h4 class="text-lg font-bold text-slate-900 leading-snug">{{ $item['name'] }}</h4>
                                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $item['description'] }}</p>
                                            </div>

                                            {{-- Requisitos List --}}
                                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                                <p class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                                    <i class="bi bi-list-check text-blue-600"></i> Requisitos Exigidos:
                                                </p>
                                                <ul class="space-y-1.5 text-xs text-slate-600">
                                                    @foreach($item['requirements'] as $req)
                                                        <li class="flex items-start gap-2">
                                                            <i class="bi bi-check2-circle text-blue-600 flex-shrink-0 mt-0.5"></i>
                                                            <span>{{ $req }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        {{-- Card Footer Metadata --}}
                                        <div class="pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                                            <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-100">
                                                <span class="block text-[10px] uppercase font-bold text-slate-500">Derecho Pago</span>
                                                <strong class="text-sm font-extrabold text-blue-700">{{ $item['cost'] }}</strong>
                                                <span class="block text-[10px] text-slate-400">({{ $item['uit_percent'] }} UIT)</span>
                                            </div>

                                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase font-bold text-slate-500">Plazo Atención</span>
                                                <strong class="text-xs font-bold text-slate-800">{{ $item['duration'] }}</strong>
                                            </div>

                                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase font-bold text-slate-500">Oficina Atiende</span>
                                                <span class="block text-[11px] font-semibold text-slate-700 leading-tight mt-0.5">{{ $item['office'] }}</span>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Empty Search State --}}
                <div x-show="noResultsFound()" class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-md mx-auto space-y-4" x-cloak>
                    <div class="w-16 h-16 mx-auto bg-slate-100 text-slate-400 rounded-full flex items-center justify-center">
                        <i class="bi bi-search text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">No se encontraron trámites</h3>
                        <p class="text-xs text-slate-500 mt-1">Intente buscando con otros términos o seleccione otra categoría.</p>
                    </div>
                    <button type="button" @click="search = ''; selectedCategory = 'all'" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        Restablecer Filtros
                    </button>
                </div>
            </div>

            {{-- ===== SECTION 3: ACCIONES RÁPIDAS Y MESA DE PARTES ===== --}}
            <div class="bg-gradient-to-r from-blue-900 to-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-3">
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-xs font-semibold border border-blue-400/20">
                            ¿Listo para iniciar tu trámite?
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white">Presenta tu solicitud a través de la Mesa de Partes Virtual</h3>
                        <p class="text-slate-300 text-sm leading-relaxed">
                            Adjunta tu Formulario Único de Trámite (FUT), comprobante de pago y requisitos desde cualquier lugar de forma segura e inmediata.
                        </p>
                    </div>

                    <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3">
                        <a href="{{ route('mesa-de-partes') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-lg transition-all text-center">
                            <i class="bi bi-send-fill"></i>
                            <span>Ir a Mesa de Partes Virtual</span>
                        </a>

                        <a href="{{ asset('documents/fut-iestp-fvc.pdf') }}" target="_blank" download class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-sm rounded-xl border border-white/20 transition-all text-center">
                            <i class="bi bi-file-earmark-arrow-down"></i>
                            <span>Descargar Modelo FUT</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Modal Reader for PDF --}}
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" x-cloak>
                <div @click.away="showModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-950 text-white">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-file-earmark-pdf text-red-400 text-xl"></i>
                            <h3 class="font-bold text-sm sm:text-base truncate max-w-lg" x-text="modalTitle"></h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <a :href="modalUrl" target="_blank" download class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-semibold transition-colors flex items-center gap-1">
                                <i class="bi bi-download"></i> Descargar
                            </a>
                            <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 bg-slate-100 relative">
                        <iframe :src="modalUrl" class="w-full h-full border-none"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @push('scripts')
    <script>
        function publicTupaApp() {
            return {
                search: '',
                selectedCategory: 'all',
                showModal: false,
                modalUrl: '',
                modalTitle: '',

                openPdfModal(url, title) {
                    this.modalUrl = url;
                    this.modalTitle = title;
                    this.showModal = true;
                },

                shouldShowCategory(categoryName, itemCodes) {
                    if (this.selectedCategory !== 'all' && this.selectedCategory !== categoryName) {
                        return false;
                    }
                    if (!this.search.trim()) {
                        return true;
                    }
                    return true;
                },

                matchesSearch(code, name, description, reqs) {
                    if (!this.search.trim()) return true;
                    const q = this.search.toLowerCase();
                    return code.toLowerCase().includes(q) ||
                        name.toLowerCase().includes(q) ||
                        description.toLowerCase().includes(q) ||
                        reqs.toLowerCase().includes(q);
                },

                noResultsFound() {
                    // Logic handled smoothly visually if all items hidden
                    return false;
                }
            }
        }
    </script>
    @endpush
@endsection
