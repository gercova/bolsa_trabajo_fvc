@extends('layouts.app')
@section('title', 'Documentos de Gestión Institucional — IESTP Francisco Vigo Caballero')
@push('styles')
    {{-- SEO Meta Tags --}}
    <meta name="description" content="Documentos de Gestión Institucional del IESTP Francisco Vigo Caballero de Uchiza. Accede al Reglamento Interno (RI), Plan Anual de Trabajo (PAT), Manual de Perfil de Puestos (MPP) y más.">
    <meta name="keywords" content="documentos de gestion, PAT 2026, reglamento interno, MPP, IESTP Francisco Vigo Caballero, transparencia institucional, uchiza, educacion superior">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Documentos de Gestión Institucional — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Consulta los instrumentos normativos y de gestión oficiales que rigen el IESTP Francisco Vigo Caballero.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "IESTP Francisco Vigo Caballero",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('enterprise/favicons/logo-iestpfvc.png') }}",
      "department": {
        "@type": "GovernmentService",
        "name": "Portal de Transparencia - Documentos de Gestión",
        "serviceType": "Public Transparency Documents",
        "provider": {
          "@type": "EducationalOrganization",
          "name": "IESTP Francisco Vigo Caballero"
        }
      }
    }
    </script>
@endpush

@section('content')
    <div x-data="managementDocumentsApp()">
        {{-- ===== HERO SECTION ===== --}}
        <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white overflow-hidden py-16 lg:py-24 border-b border-blue-900/30">
            {{-- Decorative Light Pattern --}}
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(56,189,248,0.15),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(59,130,246,0.12),transparent_40%)]"></div>
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
                {{-- Hero Heading --}}
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                    Documentos de <span class="text-sky-400 bg-gradient-to-r from-sky-400 to-blue-400 bg-clip-text text-transparent">Gestión</span>
                </h1>
                
                <p class="text-lg sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                    Instrumentos normativos, planeamiento estratégico y marcos de regulación oficial que orientan el funcionamiento institucional y académico de nuestra casa de estudios.
                </p>

                {{-- Institutional Badges Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-4xl mx-auto mt-10 pt-8 border-t border-white/10">
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center justify-center gap-3">
                        <i class="bi bi-shield-check text-2xl text-sky-400"></i>
                        <div class="text-left">
                            <p class="text-xs text-slate-400 font-semibold">Marco Legal</p>
                            <p class="text-sm font-bold text-white">Ley N° 30512</p>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center justify-center gap-3">
                        <i class="bi bi-eye text-2xl text-emerald-400"></i>
                        <div class="text-left">
                            <p class="text-xs text-slate-400 font-semibold">Acceso Público</p>
                            <p class="text-sm font-bold text-white">Transparencia 100%</p>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center justify-center gap-3">
                        <i class="bi bi-calendar-check text-2xl text-amber-400"></i>
                        <div class="text-left">
                            <p class="text-xs text-slate-400 font-semibold">Periodos Vigentes</p>
                            <p class="text-sm font-bold text-white">2026 — 2027</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== MAIN CONTENT SECTION ===== --}}
        <section class="py-16 sm:py-24 bg-slate-50 min-h-[600px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

                {{-- Filter and Search Control Bar --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                                <i class="bi bi-folder2-open text-blue-600"></i> Catálogo de Documentos Oficiales
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Busque o filtre por título, objetivo o contenido específico.</p>
                        </div>
                        <div class="text-xs font-semibold px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                            <span x-text="filteredDocuments.length"></span> Documentos Disponibles
                        </div>
                    </div>

                    {{-- Search Input & Quick Filter Pills --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Search Input --}}
                        <div class="md:col-span-2 relative">
                            <input type="text" x-model="searchQuery" placeholder="Buscar por título, palabra clave o descripción..."
                                class="w-full pl-11 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                            <button type="button" x-show="searchQuery" @click="searchQuery = ''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>

                        {{-- Category Filter --}}
                        <div>
                            <select x-model="selectedCategory"
                                class="w-full py-3.5 px-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="all">Todos los Documentos</option>
                                <option value="PAT">Plan Anual de Trabajo (PAT)</option>
                                <option value="RI">Reglamento Interno (RI)</option>
                                <option value="MPP">Manual de Perfil de Puestos (MPP)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Documents Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @forelse ($documents as $doc)
                        @php
                            $ext = $doc->file_path ? strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION)) : 'pdf';
                            $fileUrl = $doc->file_path ? Storage::url($doc->file_path) : null;
                        @endphp
                        
                        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group"
                            x-show="matchesFilter('{{ addslashes($doc->title) }}', '{{ addslashes($doc->description ?? '') }}', '{{ addslashes($doc->details ?? '') }}')">
                            
                            <div class="p-6 sm:p-8 space-y-5">
                                {{-- Card Header: Icon & Badges --}}
                                <div class="flex items-center justify-between gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                        @if (str_contains(strtoupper($doc->title), 'PAT') || str_contains(strtoupper($doc->title), 'PLAN'))
                                            <i class="bi bi-journal-text text-2xl text-blue-600 group-hover:text-white transition-colors"></i>
                                        @elseif (str_contains(strtoupper($doc->title), 'REGLAMENTO') || str_contains(strtoupper($doc->title), 'RI'))
                                            <i class="bi bi-shield-lock-fill text-2xl text-indigo-600 group-hover:text-white transition-colors"></i>
                                        @elseif (str_contains(strtoupper($doc->title), 'PUESTOS') || str_contains(strtoupper($doc->title), 'MPP'))
                                            <i class="bi bi-person-lines-fill text-2xl text-emerald-600 group-hover:text-white transition-colors"></i>
                                        @else
                                            <i class="bi bi-file-earmark-pdf-fill text-2xl text-red-600 group-hover:text-white transition-colors"></i>
                                        @endif
                                    </div>

                                    <div class="flex flex-col items-end gap-1.5">
                                        @if ($doc->validity_period)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                <i class="bi bi-calendar3"></i>
                                                Vigencia {{ $doc->validity_period->format('Y') }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700">
                                            Documento Oficial
                                        </span>
                                    </div>
                                </div>

                                {{-- Title --}}
                                <div>
                                    <h3 class="text-xl font-extrabold text-slate-900 leading-snug tracking-tight group-hover:text-blue-600 transition-colors">
                                        {{ $doc->title }}
                                    </h3>
                                </div>

                                {{-- Description --}}
                                @if ($doc->description)
                                    <p class="text-sm text-slate-600 leading-relaxed font-normal">
                                        {{ $doc->description }}
                                    </p>
                                @endif

                                {{-- Details Accordion Toggle --}}
                                @if ($doc->details)
                                    <div x-data="{ open: false }" class="pt-2">
                                        <button type="button" @click="open = !open"
                                            class="w-full py-2 px-3 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-xl text-xs font-bold transition-all flex items-center justify-between border border-slate-200/80">
                                            <span class="flex items-center gap-1.5">
                                                <i class="bi bi-list-nested"></i> Estructura y Contenido
                                            </span>
                                            <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                        </button>
                                        
                                        <div x-show="open" x-collapse x-cloak class="mt-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200 text-xs text-slate-600 leading-relaxed space-y-2">
                                            <p class="font-bold text-slate-800 flex items-center gap-1">
                                                <i class="bi bi-info-circle-fill text-blue-500"></i> Detalle del documento:
                                            </p>
                                            <p>{{ $doc->details }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Action Footer --}}
                            <div class="p-6 bg-slate-50/60 border-t border-slate-100 flex items-center gap-3">
                                @if ($fileUrl)
                                    <a href="{{ $fileUrl }}" target="_blank"
                                        class="flex-1 py-3 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl font-bold text-xs transition-all duration-300 flex items-center justify-center gap-2 shadow-sm hover:shadow-blue-600/30">
                                        <i class="bi bi-download text-sm"></i>
                                        <span>Descargar PDF</span>
                                    </a>

                                    <button type="button" @click="openPdfModal('{{ $fileUrl }}', '{{ addslashes($doc->title) }}')"
                                        class="py-3 px-4 bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 shadow-sm"
                                        title="Previsualizar en pantalla">
                                        <i class="bi bi-eye text-sm text-blue-600"></i>
                                        <span class="hidden sm:inline">Ver</span>
                                    </button>
                                @else
                                    <div class="w-full py-3 px-4 bg-amber-50 border border-amber-200/80 rounded-xl text-center">
                                        <p class="text-xs font-semibold text-amber-800 flex items-center justify-center gap-1.5">
                                            <i class="bi bi-check-circle-fill text-amber-600"></i>
                                            Aprobado y Registrado en Secretaría
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200">
                            <div class="max-w-md mx-auto space-y-4">
                                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                                    <i class="bi bi-folder-x"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">No hay documentos de gestión publicados</h3>
                                <p class="text-sm text-slate-500">Actualmente no hay documentos registrados para consulta pública.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Empty Search Results Notice --}}
                <div x-show="filteredDocumentsCount === 0" x-cloak
                    class="py-16 text-center bg-white rounded-3xl border border-slate-200">
                    <div class="max-w-md mx-auto space-y-4">
                        <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto text-2xl">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No se encontraron resultados</h3>
                        <p class="text-sm text-slate-500">No hay documentos que coincidan con la búsqueda "<span x-text="searchQuery"></span>".</p>
                        <button type="button" @click="searchQuery = ''; selectedCategory = 'all'"
                            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-colors inline-flex items-center gap-2">
                            <i class="bi bi-x-lg"></i> Limpiar Búsqueda
                        </button>
                    </div>
                </div>

                {{-- Transparency Notice Banner --}}
                <div class="bg-gradient-to-r from-slate-900 to-blue-950 text-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-800 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="space-y-2 text-center md:text-left">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-xs font-bold text-sky-300 uppercase tracking-wider">
                                Ley de Transparencia N° 27806
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black tracking-tight">¿Desea solicitar un documento público adicional?</h3>
                            <p class="text-sm text-slate-300 max-w-2xl">
                                De acuerdo con los lineamientos de acceso a la información pública, puede presentar su solicitud mediante nuestra Mesa de Partes Virtual o presencial.
                            </p>
                        </div>
                        <a href="{{ route('mesa-de-partes') }}"
                            class="px-6 py-3.5 bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold text-sm rounded-2xl transition-all duration-300 shadow-lg shadow-sky-500/20 flex-shrink-0 flex items-center gap-2">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Ir a Mesa de Partes</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        {{-- ===== PDF READER MODAL ===== --}}
        <div x-show="showPdfModal" x-transition.opacity
            class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6" x-cloak>
            <div @click.away="showPdfModal = false"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden border border-slate-200">
                {{-- Modal Top Bar --}}
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-earmark-pdf-fill text-red-400 text-lg"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base truncate" x-text="pdfModalTitle"></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="pdfModalUrl" download target="_blank"
                            class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5">
                            <i class="bi bi-download"></i> Descargar
                        </a>
                        <button type="button" @click="showPdfModal = false"
                            class="p-2 text-slate-400 hover:text-white rounded-lg transition-colors">
                            <i class="bi bi-x-lg text-base"></i>
                        </button>
                    </div>
                </div>

                {{-- Modal iFrame Body --}}
                <div class="flex-1 bg-slate-100 relative">
                    <iframe :src="pdfModalUrl" class="w-full h-full border-none"></iframe>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('managementDocumentsApp', () => ({
                    searchQuery: '',
                    selectedCategory: 'all',
                    showPdfModal: false,
                    pdfModalUrl: '',
                    pdfModalTitle: '',
                    filteredDocumentsCount: {{ $documents->count() }},

                    matchesFilter(title, description, details) {
                        const query = this.searchQuery.toLowerCase().trim();
                        const titleUpper = title.toUpperCase();
                        
                        // Category check
                        let categoryMatch = true;
                        if (this.selectedCategory === 'PAT') {
                            categoryMatch = titleUpper.includes('PAT') || titleUpper.includes('PLAN');
                        } else if (this.selectedCategory === 'RI') {
                            categoryMatch = titleUpper.includes('REGLAMENTO') || titleUpper.includes('RI');
                        } else if (this.selectedCategory === 'MPP') {
                            categoryMatch = titleUpper.includes('PUESTOS') || titleUpper.includes('MPP');
                        }

                        if (!categoryMatch) return false;

                        // Query check
                        if (!query) return true;

                        return title.toLowerCase().includes(query) ||
                            description.toLowerCase().includes(query) ||
                            details.toLowerCase().includes(query);
                    },

                    openPdfModal(url, title) {
                        this.pdfModalUrl = url;
                        this.pdfModalTitle = title;
                        this.showPdfModal = true;
                    }
                }));
            });
        </script>
    @endpush
@endsection
