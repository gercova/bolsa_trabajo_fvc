@extends('layouts.app')

@section('title', 'Organigrama Institucional — IESTP Francisco Vigo Caballero')
@section('meta_description', 'Estructura organizacional oficial del IESTP Francisco Vigo Caballero de Uchiza. Tres niveles jerárquicos: Dirección General, Unidades Académicas, Áreas Administrativas y Servicios de Bienestar.')
@section('meta_keywords', 'organigrama, estructura institucional, direccion general, unidad academica, secretaria academica, uchiza, iestp francisco vigo caballero, san martin, educacion superior')
@section('meta_robots', 'index, follow, max-image-preview:large')
@section('canonical_url', url()->current())

@push('styles')
    {{-- JSON-LD Structured Data for Educational Organization Hierarchy --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "IESTP Francisco Vigo Caballero",
        "alternateName": "IESTP FVC Uchiza",
        "url": "{{ url('/') }}",
        "logo": "{{ url($enterprise->logo_path ?? 'enterprise/favicons/logo-iestpfvc.png') }}",
        "description": "Estructura organizacional e institucional del Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero de Uchiza.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Av. Ricardo Palma N° 1401",
            "addressLocality": "Uchiza",
            "addressRegion": "San Martín",
            "addressCountry": "PE"
        },
        "hasOrganizationalUnit": [
            { "@type": "OrganizationalUnit", "name": "Dirección General" },
            { "@type": "OrganizationalUnit", "name": "Consejo Asesor" },
            { "@type": "OrganizationalUnit", "name": "Concejo Estudiantil" },
            { "@type": "OrganizationalUnit", "name": "Secretaría de Dirección" },
            { "@type": "OrganizationalUnit", "name": "Unidad Académica" },
            { "@type": "OrganizationalUnit", "name": "Secretaría Académica" },
            { "@type": "OrganizationalUnit", "name": "Área de Calidad" },
            { "@type": "OrganizationalUnit", "name": "Unidad de Investigación" },
            { "@type": "OrganizationalUnit", "name": "Unidad de Bienestar y Empleabilidad" },
            { "@type": "OrganizationalUnit", "name": "Unidad de Formación Continua" },
            { "@type": "OrganizationalUnit", "name": "Administración" }
        ]
    }
    </script>

    <style>
        /* ===== DIAGRAM CONTAINER & LEVEL BANDS ===== */
        .chart-frame {
            border: 3px solid #1e293b;
            background-color: #ffffff;
            box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.15);
            font-family: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
            user-select: none;
            position: relative;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top center;
        }

        .level-band-orange {
            background-color: #FCD5B5;
            border-bottom: 2px solid #1e293b;
        }

        .level-band-blue {
            background-color: #B8D9F7;
            border-bottom: 2px solid #1e293b;
        }

        .level-band-pink {
            background-color: #F8C8CE;
        }

        /* ===== LEVEL BADGES ===== */
        .yellow-level-tag {
            background-color: #FFFF00;
            color: #000000;
            font-weight: 900;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 4px 10px;
            border: 1.5px solid #000000;
            display: inline-block;
            box-shadow: 1px 1px 0px #000000;
        }

        /* ===== ORGANIZATIONAL BOXES ===== */
        .org-box {
            background-color: #ffffff;
            color: #000000;
            font-weight: 800;
            font-size: 0.62rem;
            line-height: 1.15;
            text-align: center;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 2;
            text-transform: uppercase;
            box-sizing: border-box;
            border-radius: 2px;
        }

        .org-box-solid {
            border: 2px solid #000000;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.18);
        }

        .org-box-dashed {
            border: 2px dashed #000000;
            box-shadow: 2px 2px 0px rgba(0, 0, 0, 0.1);
        }

        .org-box:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
            z-index: 10;
            background-color: #ffffff;
        }

        /* Highlight match animation */
        .org-box-highlight {
            outline: 3px solid #2563eb !important;
            box-shadow: 0 0 16px rgba(37, 99, 235, 0.6) !important;
            animation: pulseHighlight 1.5s infinite alternate;
        }

        @keyframes pulseHighlight {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        /* ===== CONNECTOR LINES ===== */
        .line-v-solid {
            width: 2px;
            background-color: #000000;
            margin: 0 auto;
        }

        .line-h-solid {
            height: 2px;
            background-color: #000000;
        }

        .line-v-dashed {
            width: 0px;
            border-left: 2px dashed #000000;
            margin: 0 auto;
        }

        .line-h-dashed {
            height: 0px;
            border-top: 2px dashed #000000;
        }

        /* ===== RESPONSIVE VIEWPORT & SCROLLBAR ===== */
        .chart-viewport {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 0.5rem 0;
        }

        .chart-viewport::-webkit-scrollbar {
            height: 8px;
        }
        .chart-viewport::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 4px;
        }
        .chart-viewport::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }
        .chart-viewport::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .chart-canvas {
            min-width: 1050px;
            width: 100%;
            position: relative;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            header, footer, .no-print {
                display: none !important;
            }
            .chart-frame {
                border: 2px solid #000 !important;
                box-shadow: none !important;
            }
            .chart-canvas {
                min-width: 100% !important;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ===== HERO SECTION (Consistent with app.blade.php & theme design pattern) ===== --}}
    <section
        class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <span
                class="inline-flex items-center gap-2 bg-blue-500/20 text-blue-300 text-xs sm:text-sm font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border border-blue-500/30">
                <i class="bi bi-diagram-3 text-base"></i>
                Estructura Orgánica Institucional
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight font-sans">
                Organigrama Institucional
            </h1>
            <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto font-medium">
                Estructura organizacional oficial del <strong class="text-white">IESTP Francisco Vigo Caballero</strong>.
                Tres niveles jerárquicos articulados para garantizar la excelencia académica y administrativa en Uchiza.
            </p>

            {{-- Key Statistics Badges --}}
            <div class="grid grid-cols-3 gap-4 sm:gap-6 max-w-2xl mx-auto mt-10 pt-8 border-t border-white/10">
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                    <p class="text-2xl sm:text-3xl font-black text-amber-400">3</p>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">Niveles Jerárquicos</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                    <p class="text-2xl sm:text-3xl font-black text-sky-400">5</p>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">Coordinaciones</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                    <p class="text-2xl sm:text-3xl font-black text-rose-400">8+</p>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">Áreas de Soporte</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MAIN INTERACTIVE CONTAINER ===== --}}
    <section class="py-10 bg-slate-100" x-data="{
        searchQuery: '',
        zoomLevel: 100,
        isFullscreen: false,
        zoomIn() { if (this.zoomLevel < 140) this.zoomLevel += 10; },
        zoomOut() { if (this.zoomLevel > 70) this.zoomLevel -= 10; },
        resetZoom() { this.zoomLevel = 100; },
        toggleFullscreen() { this.isFullscreen = !this.isFullscreen; },
        isMatch(title) {
            if (!this.searchQuery || this.searchQuery.trim() === '') return false;
            return title.toLowerCase().includes(this.searchQuery.toLowerCase().trim());
        }
    }">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">

            {{-- TOOLBAR CONTROLS (Search, Zoom, Print) --}}
            <div class="no-print bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row items-center justify-between gap-4">

                {{-- Quick Filter Input --}}
                <div class="relative w-full md:w-80">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text"
                        x-model="searchQuery"
                        placeholder="Buscar área o cargo (ej. Docentes, Tesorero)..."
                        class="w-full pl-10 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition" />
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-circle-fill text-sm"></i>
                    </button>
                </div>

                {{-- Legend Summary --}}
                <div class="hidden lg:flex items-center gap-4 text-xs font-bold text-slate-700">
                    <span class="flex items-center gap-1.5">
                        <span class="w-5 h-3 border-2 border-black bg-white inline-block"></span>
                        Órganos Directos
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-5 h-3 border-2 border-dashed border-black bg-white inline-block"></span>
                        Órganos de Apoyo
                    </span>
                </div>

                {{-- Action Controls (Zoom, Reset, Print) --}}
                <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                    <div class="flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
                        <button @click="zoomOut()" title="Alejar (-)" class="px-2.5 py-1 text-slate-700 hover:bg-white rounded-lg transition font-bold text-xs">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <span class="px-2 text-xs font-extrabold text-slate-700 min-w-[42px] text-center" x-text="zoomLevel + '%'">100%</span>
                        <button @click="zoomIn()" title="Acercar (+)" class="px-2.5 py-1 text-slate-700 hover:bg-white rounded-lg transition font-bold text-xs">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button @click="resetZoom()" title="Restablecer Zoom" class="px-2 py-1 text-slate-500 hover:text-slate-900 text-xs font-bold border-l border-slate-200 ml-1 pl-2">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>

                    <button onclick="window.print()" title="Imprimir / Exportar a PDF" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                        <i class="bi bi-printer"></i>
                        <span class="hidden sm:inline">Imprimir</span>
                    </button>
                </div>

            </div>

            {{-- CHART VIEWPORT --}}
            <div class="chart-viewport" :class="isFullscreen ? 'fixed inset-0 z-50 bg-slate-950/90 p-6 overflow-auto' : ''">

                <div class="chart-canvas chart-frame rounded-xl overflow-hidden" :style="'transform: scale(' + (zoomLevel/100) + ')'">

                    {{-- ========================================================= --}}
                    {{-- 1. PRIMER NIVEL (ORANGE / PEACH BAND) --}}
                    {{-- ========================================================= --}}
                    <div class="level-band-orange px-6 pt-4 pb-6 relative">
                        {{-- Level Tag --}}
                        <div class="mb-4">
                            <span class="yellow-level-tag">PRIMER NIVEL</span>
                        </div>

                        {{-- Level 1 Content --}}
                        <div class="relative w-full max-w-4xl mx-auto">

                            {{-- Row: Concejo Estudiantil | Dirección General | Consejo Asesor --}}
                            <div class="flex items-center justify-between gap-4">

                                {{-- Concejo Estudiantil --}}
                                <div class="w-44 flex flex-col items-center">
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Concejo Estudiantil') ? 'org-box-highlight' : ''"
                                        title="Concejo Estudiantil">
                                        CONCEJO ESTUDIANTIL
                                    </div>
                                </div>

                                {{-- Left Connector Line --}}
                                <div class="flex-1 line-h-solid"></div>

                                {{-- Dirección General --}}
                                <div class="w-48 flex flex-col items-center">
                                    <div class="org-box org-box-solid w-full text-xs font-black"
                                        :class="isMatch('Dirección General') ? 'org-box-highlight' : ''"
                                        style="min-height: 48px;" title="Dirección General">
                                        DIRECCIÓN GENERAL
                                    </div>
                                </div>

                                {{-- Right Connector Line --}}
                                <div class="flex-1 line-h-solid"></div>

                                {{-- Consejo Asesor --}}
                                <div class="w-44 flex flex-col items-center">
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Consejo Asesor') ? 'org-box-highlight' : ''"
                                        title="Consejo Asesor">
                                        CONSEJO ASESOR
                                    </div>
                                </div>

                            </div>

                            {{-- Central Trunk Stem --}}
                            <div class="w-full flex justify-center">
                                <div class="line-v-solid" style="height: 32px;"></div>
                            </div>

                        </div>
                    </div>


                    {{-- ========================================================= --}}
                    {{-- 2. SEGUNDO NIVEL (SKY BLUE BAND) --}}
                    {{-- ========================================================= --}}
                    <div class="level-band-blue px-4 pt-4 pb-8 relative">
                        {{-- Level Tag --}}
                        <div class="mb-4">
                            <span class="yellow-level-tag">SEGUNDO NIVEL</span>
                        </div>

                        {{-- Central Stem Continues Down --}}
                        <div class="flex flex-col items-center w-full">

                            {{-- Secretaría de Dirección --}}
                            <div class="relative flex items-center justify-center">
                                <div class="line-h-dashed" style="width: 40px;"></div>
                                <div class="org-box org-box-dashed w-48 text-xs font-bold"
                                    :class="isMatch('Secretaría de Dirección') ? 'org-box-highlight' : ''"
                                    title="Secretaría de Dirección">
                                    SECRETARÍA DE DIRECCIÓN
                                </div>
                                <div class="line-h-dashed" style="width: 40px;"></div>
                            </div>

                            {{-- Trunk Stem to Distribution Line --}}
                            <div class="line-v-solid" style="height: 28px;"></div>

                            {{-- Main Horizontal Line --}}
                            <div class="w-full px-4">
                                <div class="line-h-solid w-full"></div>
                            </div>

                            {{-- 8 Main Columns Grid --}}
                            <div class="grid grid-cols-8 gap-2 w-full px-2 pt-0">

                                {{-- Col 1: ÁREA ADMINISTRATIVA --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-dashed w-full"
                                        :class="isMatch('Área Administrativa') ? 'org-box-highlight' : ''"
                                        title="Área Administrativa">
                                        ÁREA ADMINISTRATIVA
                                    </div>
                                </div>

                                {{-- Col 2: ÁREA DE PRODUCCIÓN --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-dashed w-full"
                                        :class="isMatch('Área de Producción') ? 'org-box-highlight' : ''"
                                        title="Área de Producción">
                                        ÁREA DE PRODUCCIÓN
                                    </div>
                                </div>

                                {{-- Col 3: SECRETARÍA ACADÉMICA --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Secretaría Académica') ? 'org-box-highlight' : ''"
                                        title="Secretaría Académica">
                                        SECRETARÍA ACADÉMICA
                                    </div>
                                </div>

                                {{-- Col 4: UNIDAD ACADÉMICA --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Unidad Académica') ? 'org-box-highlight' : ''"
                                        title="Unidad Académica">
                                        UNIDAD ACADÉMICA
                                    </div>
                                </div>

                                {{-- Col 5: ÁREA DE CALIDAD --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Área de Calidad') ? 'org-box-highlight' : ''"
                                        title="Área de Calidad">
                                        ÁREA DE CALIDAD
                                    </div>
                                </div>

                                {{-- Col 6: UNIDAD DE INVESTIGACIÓN --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Unidad de Investigación') ? 'org-box-highlight' : ''"
                                        title="Unidad de Investigación">
                                        UNIDAD DE INVESTIGACIÓN
                                    </div>
                                </div>

                                {{-- Col 7: UNIDAD DE BIENESTAR Y EMPLEABILIDAD --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-solid" style="height: 18px;"></div>
                                    <div class="org-box org-box-solid w-full"
                                        :class="isMatch('Bienestar') || isMatch('Unidad de Bienestar') ? 'org-box-highlight' : ''"
                                        title="Unidad de Bienestar y Empleabilidad">
                                        UNIDAD DE BIENESTAR Y EMPLEABILIDAD
                                    </div>
                                </div>

                                {{-- Col 8: UNIDAD DE FORMACIÓN CONTINUA --}}
                                <div class="flex flex-col items-center">
                                    <div class="line-v-dashed" style="height: 18px;"></div>
                                    <div class="org-box org-box-dashed w-full"
                                        :class="isMatch('Formación Continua') ? 'org-box-highlight' : ''"
                                        title="Unidad de Formación Continua">
                                        UNIDAD DE FORMACIÓN CONTINUA
                                    </div>
                                </div>

                            </div>


                            {{-- SECONDARY ROW IN LEVEL 2 --}}
                            <div class="w-full px-2 mt-4 relative">

                                <div class="grid grid-cols-8 gap-2 w-full">

                                    {{-- Col 1 Stem: ADMINISTRACIÓN --}}
                                    <div class="flex flex-col items-center">
                                        <div class="line-v-solid" style="height: 20px;"></div>
                                        <div class="org-box org-box-solid w-full"
                                            :class="isMatch('Administración') ? 'org-box-highlight' : ''"
                                            title="Administración">
                                            ADMINISTRACIÓN
                                        </div>
                                    </div>

                                    {{-- Col 2: IMAGEN INSTITUCIONAL --}}
                                    <div class="flex items-center pt-5">
                                        <div class="line-h-dashed flex-1"></div>
                                        <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                            :class="isMatch('Imagen Institucional') ? 'org-box-highlight' : ''"
                                            title="Imagen Institucional">
                                            IMAGEN INSTITUCIONAL
                                        </div>
                                    </div>

                                    {{-- Col 3: Empty --}}
                                    <div></div>

                                    {{-- Col 4 Stem: SECRETARÍA DE UNIDAD ACADÉMICA --}}
                                    <div class="flex flex-col items-center relative">
                                        <div class="line-v-solid" style="height: 20px;"></div>
                                        <div class="flex items-center w-full relative">
                                            <div class="w-full flex justify-center">
                                                <div class="line-v-solid" style="height: 24px;"></div>
                                            </div>
                                            <div class="absolute left-1/2 flex items-center" style="width: 140px;">
                                                <div class="line-h-solid" style="width: 24px;"></div>
                                                <div class="org-box org-box-solid" style="width: 130px; font-size: 0.58rem;"
                                                    :class="isMatch('Secretaría de Unidad Académica') ? 'org-box-highlight' : ''"
                                                    title="Secretaría de Unidad Académica">
                                                    SECRETARÍA DE UNIDAD ACADÉMICA
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Col 5-6: Empty --}}
                                    <div></div>
                                    <div></div>

                                    {{-- Col 7 Stem: Drops into Level 3 --}}
                                    <div class="flex justify-center">
                                        <div class="line-v-solid" style="height: 50px;"></div>
                                    </div>

                                    {{-- Col 8: Empty --}}
                                    <div></div>

                                </div>


                                {{-- 5 ACADEMIC COORDINATORS BRANCH --}}
                                <div class="mt-4 flex flex-col items-center w-full">
                                    <div class="line-v-solid" style="height: 16px;"></div>
                                    <div class="line-h-solid" style="width: 62%;"></div>

                                    <div class="flex items-start justify-center gap-2 mt-0" style="width: 65%;">

                                        {{-- Coord 1 --}}
                                        <div class="flex-1 flex flex-col items-center">
                                            <div class="line-v-solid" style="height: 14px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.55rem; min-height: 46px;"
                                                :class="isMatch('Producción Agropecuaria') || isMatch('Coordinador') ? 'org-box-highlight' : ''"
                                                title="Coordinador Académico de Producción Agropecuaria">
                                                COORDINADOR ACADÉMICO DE PRODUCCIÓN AGROPECUARIA
                                            </div>
                                        </div>

                                        {{-- Coord 2 --}}
                                        <div class="flex-1 flex flex-col items-center">
                                            <div class="line-v-solid" style="height: 14px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.55rem; min-height: 46px;"
                                                :class="isMatch('Enfermería') || isMatch('Coordinador') ? 'org-box-highlight' : ''"
                                                title="Coordinador Académico de Enfermería Técnica">
                                                COORDINADOR ACADÉMICO DE ENFERMERÍA TÉCNICA
                                            </div>
                                        </div>

                                        {{-- Coord 3 --}}
                                        <div class="flex-1 flex flex-col items-center">
                                            <div class="line-v-solid" style="height: 14px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.55rem; min-height: 46px;"
                                                :class="isMatch('Redes') || isMatch('Comunicación') || isMatch('Coordinador') ? 'org-box-highlight' : ''"
                                                title="Coordinador Académico de Administración de Redes y Comunicación">
                                                COORDINADOR ACADÉMICO DE ADMINISTRACIÓN DE REDES Y COMUNICACIÓN
                                            </div>
                                        </div>

                                        {{-- Coord 4 --}}
                                        <div class="flex-1 flex flex-col items-center">
                                            <div class="line-v-solid" style="height: 14px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.55rem; min-height: 46px;"
                                                :class="isMatch('Asistencia Administrativa') || isMatch('Coordinador') ? 'org-box-highlight' : ''"
                                                title="Coordinador Académico de Asistencia Administrativa">
                                                COORDINADOR ACADÉMICO DE ASISTENCIA ADMINISTRATIVA
                                            </div>
                                        </div>

                                        {{-- Coord 5 --}}
                                        <div class="flex-1 flex flex-col items-center">
                                            <div class="line-v-solid" style="height: 14px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.55rem; min-height: 46px;"
                                                :class="isMatch('Manejo Forestal') || isMatch('Coordinador') ? 'org-box-highlight' : ''"
                                                title="Coordinador Académico de Manejo Forestal">
                                                COORDINADOR ACADÉMICO DE MANEJO FORESTAL
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- ========================================================= --}}
                    {{-- 3. TERCER NIVEL (ROSE / PINK BAND) --}}
                    {{-- ========================================================= --}}
                    <div class="level-band-pink px-4 pt-4 pb-10 relative">
                        {{-- Level Tag --}}
                        <div class="mb-4">
                            <span class="yellow-level-tag">TERCER NIVEL</span>
                        </div>

                        {{-- Level 3 Grid: Left Admin | Center Academic | Right Services --}}
                        <div class="grid grid-cols-12 gap-2 w-full items-start">

                            {{-- LEFT SECTION: ADMIN DEPENDENCIES --}}
                            <div class="col-span-4 relative pt-2">
                                <div class="absolute left-1/2 top-0 bottom-4 w-0.5 bg-black" style="transform: translateX(-50%);"></div>

                                <div class="space-y-3 relative z-10">

                                    {{-- Row 1 --}}
                                    <div class="flex items-center justify-between">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Asistente Administrativo') ? 'org-box-highlight' : ''"
                                                title="Asistente Administrativo">
                                                ASISTENTE ADMINISTRATIVO
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                        <div class="w-5/12 flex items-center pl-1">
                                            <div class="line-h-solid" style="width: 16px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Personal de Servicio') ? 'org-box-highlight' : ''"
                                                title="Personal de Servicio">
                                                PERSONAL DE SERVICIO
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Row 2 --}}
                                    <div class="flex items-center justify-between">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Tesorero') ? 'org-box-highlight' : ''"
                                                title="Tesorero">
                                                TESORERO
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                        <div class="w-5/12 flex items-center pl-1">
                                            <div class="line-h-solid" style="width: 16px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Personal de Servicio II') ? 'org-box-highlight' : ''"
                                                title="Personal de Servicio II">
                                                PERSONAL DE SERVICIO II
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Row 3 --}}
                                    <div class="flex items-center justify-between">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Patrimonio') ? 'org-box-highlight' : ''"
                                                title="Área de Patrimonio">
                                                ÁREA DE PATRIMONIO
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                        <div class="w-5/12 flex items-center pl-1">
                                            <div class="line-h-solid" style="width: 16px;"></div>
                                            <div class="org-box org-box-solid w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Personal de Servicio III') ? 'org-box-highlight' : ''"
                                                title="Personal de Servicio III">
                                                PERSONAL DE SERVICIO III
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Row 4 --}}
                                    <div class="flex items-center justify-start">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Abastecimiento') ? 'org-box-highlight' : ''"
                                                title="Área de Abastecimiento">
                                                ÁREA DE ABASTECIMIENTO
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                    </div>

                                    {{-- Row 5 --}}
                                    <div class="flex items-center justify-start">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Guardianía') || isMatch('Diurna') ? 'org-box-highlight' : ''"
                                                title="Guardianía Diurna">
                                                GUARDIANIA DIURNA
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                    </div>

                                    {{-- Row 6 --}}
                                    <div class="flex items-center justify-start">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Asistente de Campo') ? 'org-box-highlight' : ''"
                                                title="Asistente de Campo">
                                                ASISTENTE DE CAMPO
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                    </div>

                                    {{-- Row 7 --}}
                                    <div class="flex items-center justify-start">
                                        <div class="w-5/12 flex items-center pr-1">
                                            <div class="org-box org-box-dashed w-full" style="font-size: 0.58rem;"
                                                :class="isMatch('Personal de Campo') ? 'org-box-highlight' : ''"
                                                title="Personal de Campo">
                                                PERSONAL DE CAMPO
                                            </div>
                                            <div class="line-h-dashed" style="width: 16px;"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            {{-- CENTER SECTION: ACADEMIC HIERARCHY --}}
                            <div class="col-span-4 flex flex-col items-center pt-2">
                                <div class="line-v-solid" style="height: 24px;"></div>

                                {{-- Docentes --}}
                                <div class="org-box org-box-solid w-44 font-black text-xs" style="min-height: 44px;"
                                    :class="isMatch('Docentes') ? 'org-box-highlight' : ''"
                                    title="Docentes">
                                    DOCENTES
                                </div>

                                <div class="line-v-solid" style="height: 20px;"></div>
                                <div class="line-h-solid" style="width: 75%;"></div>

                                {{-- Row: Especialidad | Empleabilidad --}}
                                <div class="flex items-center justify-between w-full max-w-xs pt-0">
                                    <div class="flex flex-col items-center flex-1">
                                        <div class="line-v-solid" style="height: 14px;"></div>
                                        <div class="org-box org-box-solid w-32" style="font-size: 0.62rem;"
                                            :class="isMatch('Especialidad') ? 'org-box-highlight' : ''"
                                            title="Especialidad">
                                            ESPECIALIDAD
                                        </div>
                                        <div class="line-v-solid" style="height: 14px;"></div>
                                    </div>
                                    <div class="flex flex-col items-center flex-1">
                                        <div class="line-v-solid" style="height: 14px;"></div>
                                        <div class="org-box org-box-solid w-32" style="font-size: 0.62rem;"
                                            :class="isMatch('Empleabilidad') ? 'org-box-highlight' : ''"
                                            title="Empleabilidad">
                                            EMPLEABILIDAD
                                        </div>
                                        <div class="line-v-solid" style="height: 14px;"></div>
                                    </div>
                                </div>

                                <div class="line-h-solid" style="width: 75%;"></div>
                                <div class="line-v-solid" style="height: 20px;"></div>

                                {{-- Estudiantes --}}
                                <div class="org-box org-box-solid w-44 font-black text-xs" style="min-height: 44px;"
                                    :class="isMatch('Estudiantes') ? 'org-box-highlight' : ''"
                                    title="Estudiantes">
                                    ESTUDIANTES
                                </div>
                            </div>


                            {{-- RIGHT SECTION: BIENESTAR SERVICES --}}
                            <div class="col-span-4 relative pt-2">
                                <div class="absolute left-6 top-0 bottom-12 w-0.5 bg-black"></div>

                                <div class="space-y-4 pt-4 pl-6">

                                    {{-- Servicio Médico --}}
                                    <div class="flex items-center">
                                        <div class="line-h-dashed" style="width: 24px;"></div>
                                        <div class="org-box org-box-dashed w-64" style="font-size: 0.58rem;"
                                            :class="isMatch('Médico') || isMatch('Tópico') ? 'org-box-highlight' : ''"
                                            title="Servicio Médico (Tópico)">
                                            SERVICIO MÉDICO (TÓPICO)
                                        </div>
                                    </div>

                                    {{-- Servicio Psicopedagógico --}}
                                    <div class="flex items-center">
                                        <div class="line-h-dashed" style="width: 24px;"></div>
                                        <div class="org-box org-box-dashed w-64" style="font-size: 0.58rem;"
                                            :class="isMatch('Psicopedagógico') || isMatch('Psicología') ? 'org-box-highlight' : ''"
                                            title="Servicio Psicopedagógico">
                                            SERVICIO PSICOPEDAGOGICO
                                        </div>
                                    </div>

                                    {{-- Servicio de Bienestar Social --}}
                                    <div class="flex items-center">
                                        <div class="line-h-dashed" style="width: 24px;"></div>
                                        <div class="org-box org-box-dashed w-64" style="font-size: 0.58rem;"
                                            :class="isMatch('Bienestar Social') || isMatch('Consejería') ? 'org-box-highlight' : ''"
                                            title="Servicio de Bienestar Social (Consejería)">
                                            SERVICIO DE BIENESTAR SOCIAL (CONSEJERÍA)
                                        </div>
                                    </div>

                                    {{-- Servicio de Empleabilidad --}}
                                    <div class="flex items-center">
                                        <div class="line-h-dashed" style="width: 24px;"></div>
                                        <div class="org-box org-box-dashed w-64" style="font-size: 0.58rem;"
                                            :class="isMatch('Servicio de Empleabilidad') ? 'org-box-highlight' : ''"
                                            title="Servicio de Empleabilidad">
                                            SERVICIO DE EMPLEABILIDAD
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- Helper note --}}
            <p class="text-center text-xs text-slate-500 mt-4 font-semibold no-print">
                <i class="bi bi-info-circle mr-1 text-blue-600"></i>
                En teléfonos y tablets, deslice horizontalmente el esquema para explorar el organigrama completo.
            </p>

        </div>
    </section>

    {{-- ===== LEVEL DESCRIPTIONS SECTION ===== --}}
    <section class="py-16 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Estructura Jerárquica
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight font-sans">
                    Funciones y Responsabilidades por Nivel
                </h2>
                <p class="text-base text-slate-600 mt-3 leading-relaxed font-medium">
                    Conoce el rol estratégico, de gestión y operativo que cumple cada órgano en la formación profesional tecnológica.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Primer Nivel --}}
                <div class="p-8 rounded-2xl border-2 border-amber-200 bg-amber-50/60 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center mb-6 shadow-sm border border-amber-500">
                        <i class="bi bi-building-gear text-2xl"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-yellow-300 border border-black text-black font-black text-xs uppercase inline-block mb-3">Primer Nivel</span>
                    <h3 class="font-extrabold text-slate-900 text-xl mb-3">Dirección General y Gobierno</h3>
                    <p class="text-sm text-slate-700 leading-relaxed font-medium">
                        Conformado por la <strong>Dirección General</strong>, el <strong>Consejo Asesor</strong> y el <strong>Concejo Estudiantil</strong>. Conduce la política institucional, planificación estratégica y representación técnico-pedagógica de la institución.
                    </p>
                </div>

                {{-- Segundo Nivel --}}
                <div class="p-8 rounded-2xl border-2 border-sky-200 bg-sky-50/60 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-sky-500 text-white flex items-center justify-center mb-6 shadow-sm border border-sky-600">
                        <i class="bi bi-diagram-2 text-2xl"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-yellow-300 border border-black text-black font-black text-xs uppercase inline-block mb-3">Segundo Nivel</span>
                    <h3 class="font-extrabold text-slate-900 text-xl mb-3">Gestión y Coordinaciones</h3>
                    <p class="text-sm text-slate-700 leading-relaxed font-medium">
                        Integra las <strong>Unidades Académicas, de Investigación, Calidad y Bienestar</strong>, así como las 5 <strong>Coordinaciones Académicas de Programa</strong> y las áreas administrativas y de producción.
                    </p>
                </div>

                {{-- Tercer Nivel --}}
                <div class="p-8 rounded-2xl border-2 border-rose-200 bg-rose-50/60 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-rose-500 text-white flex items-center justify-center mb-6 shadow-sm border border-rose-600">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-yellow-300 border border-black text-black font-black text-xs uppercase inline-block mb-3">Tercer Nivel</span>
                    <h3 class="font-extrabold text-slate-900 text-xl mb-3">Operativo, Docencia y Servicios</h3>
                    <p class="text-sm text-slate-700 leading-relaxed font-medium">
                        Compuesto por la plana <strong>Docente, Estudiantes</strong>, el personal administrativo de soporte (tesorería, patrimonio, abastecimiento, servicio) y los <strong>Servicios de Bienestar Estudiantil</strong>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section
        class="py-16 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white text-center relative overflow-hidden border-t border-blue-900/30">
        <div class="container mx-auto px-4 relative z-10 space-y-6">
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight max-w-3xl mx-auto leading-tight font-sans">
                ¿Deseas conocer más sobre el IESTP Francisco Vigo Caballero?
            </h2>
            <p class="text-base text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                Conoce a nuestra plana docente, explora nuestros programas de estudio o revisa la reseña histórica de la institución.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-2">
                <a href="{{ route('historia') }}"
                    class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-3.5 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5 text-sm">
                    <i class="bi bi-clock-history text-blue-600 text-lg"></i>
                    Nuestra Historia
                </a>
                <a href="{{ route('programas-de-estudio') }}"
                    class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-3.5 rounded-xl font-extrabold transition flex items-center justify-center gap-2 text-sm">
                    Programas de Estudio
                    <i class="bi bi-arrow-right text-lg"></i>
                </a>
            </div>
        </div>
    </section>

@endsection
