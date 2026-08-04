@extends('layouts.app')
@section('title', 'Organigrama Institucional — IESTP Francisco Vigo Caballero')
@push('styles')
    {{-- SEO Meta Tags --}}
    <meta name="description"
        content="Conoce la estructura organizacional del IESTP Francisco Vigo Caballero de Uchiza. Dirección General, Secretaría Académica, Unidades y áreas que conforman nuestra institución educativa.">
    <meta name="keywords"
        content="organigrama, estructura institucional, dirección general, secretaría académica, unidad académica, área administrativa, IESTP Francisco Vigo Caballero, Uchiza">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Organigrama Institucional — IESTP Francisco Vigo Caballero">
    <meta property="og:description"
        content="Estructura organizacional completa del IESTP Francisco Vigo Caballero: niveles de dirección, áreas académicas, administrativas y de servicio.">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "IESTP Francisco Vigo Caballero",
        "description": "Estructura organizacional institucional del IESTP Francisco Vigo Caballero de Uchiza.",
        "url": "{{ url('/') }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Av. Ricardo Palma N° 1401",
            "addressLocality": "Uchiza",
            "addressCountry": "PE"
        }
    }
    </script>

    <style>
        /* ===== CHART NODES ===== */
        .org-node {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 0.65rem;
            font-weight: 700;
            line-height: 1.2;
            padding: 8px 10px;
            border-radius: 6px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
            letter-spacing: 0.01em;
            position: relative;
            z-index: 2;
        }

        /* Solid nodes — for key/primary positions */
        .org-node-solid {
            background-color: #ffffff;
            border: 2px solid #334155;
            color: #0f172a;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }

        /* Dashed nodes — for advisory or support positions */
        .org-node-dashed {
            background-color: #f8fafc;
            border: 2px dashed #64748b;
            color: #334155;
        }

        .org-node-solid:hover,
        .org-node-dashed:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.18);
            border-color: #2563eb;
            color: #1e40af;
            z-index: 10;
        }

        /* ===== LEVEL BADGE ===== */
        .level-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
        }

        /* ===== SVG CONNECTORS ===== */
        .org-connector {
            position: absolute;
            pointer-events: none;
            overflow: visible;
            z-index: 1;
        }

        /* ===== SECTION BANDS ===== */
        .level-band-1 { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
        .level-band-2 { background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); }
        .level-band-3 { background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); }

        /* ===== CHART AREA ===== */
        .chart-wrapper {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 24px;
        }

        .chart-inner {
            min-width: 900px;
            position: relative;
        }

        /* ===== CONNECTOR LINES (Pure CSS) ===== */
        .v-line {
            width: 2px;
            background-color: #475569;
            margin: 0 auto;
        }

        .h-line {
            height: 2px;
            background-color: #475569;
        }

        .connector-row {
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .connector-branch {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Fade-in animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s ease forwards;
        }
        .animate-delay-100 { animation-delay: 0.1s; }
        .animate-delay-200 { animation-delay: 0.2s; }
        .animate-delay-300 { animation-delay: 0.3s; }
    </style>
@endpush

@section('content')

    {{-- ===== HERO SECTION ===== --}}
    <section
        class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <span
                class="inline-flex items-center gap-2 bg-blue-500/20 text-blue-300 text-sm font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border border-blue-500/30">
                <i class="bi bi-diagram-3 text-base"></i>
                Estructura Institucional
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                Organigrama Institucional
            </h1>
            <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                Conoce la estructura organizacional del <strong class="text-white">IESTP Francisco Vigo Caballero</strong>.
                Tres niveles jerárquicos que articulan la gestión académica, administrativa y de bienestar estudiantil.
            </p>

            {{-- Quick stats --}}
            <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto mt-14 pt-10 border-t border-white/10">
                <div class="bg-white/5 backdrop-blur-md p-5 rounded-2xl border border-white/10">
                    <p class="text-2xl font-black text-sky-400">3</p>
                    <p class="text-xs font-bold text-slate-400 mt-1">Niveles Jerárquicos</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-5 rounded-2xl border border-white/10">
                    <p class="text-2xl font-black text-sky-400">5</p>
                    <p class="text-xs font-bold text-slate-400 mt-1">Coordinaciones Académicas</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-5 rounded-2xl border border-white/10">
                    <p class="text-2xl font-black text-sky-400">8+</p>
                    <p class="text-xs font-bold text-slate-400 mt-1">Áreas de Soporte</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== LEGEND SECTION ===== --}}
    <section class="bg-white border-b border-slate-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm font-semibold text-slate-600">
                <span class="flex items-center gap-2">
                    <span class="w-10 h-5 rounded border-2 border-slate-600 bg-white inline-block"></span>
                    Dependencia directa
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-10 h-5 rounded border-2 border-dashed border-slate-500 bg-slate-50 inline-block"></span>
                    Dependencia de apoyo / staff
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-yellow-400 inline-block border border-yellow-500"></span>
                    Primer Nivel
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-blue-400 inline-block border border-blue-500"></span>
                    Segundo Nivel
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-pink-400 inline-block border border-pink-500"></span>
                    Tercer Nivel
                </span>
            </div>
        </div>
    </section>

    {{-- ===== ORGANIZATIONAL CHART ===== --}}
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-12">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    <i class="bi bi-diagram-3-fill"></i>
                    Jerarquía Institucional
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                    Estructura Organizacional
                </h2>
                <p class="text-base sm:text-lg text-slate-600 mt-3 leading-relaxed">
                    Distribución de áreas, unidades y coordinaciones que conforman la gestión integral de nuestra institución.
                </p>
            </div>

            {{-- ======================================================= --}}
            {{-- CHART CONTAINER --}}
            {{-- ======================================================= --}}
            <div class="chart-wrapper rounded-3xl shadow-xl border border-slate-200 bg-white overflow-hidden">

                {{-- ============================== --}}
                {{-- PRIMER NIVEL --}}
                {{-- ============================== --}}
                <div class="level-band-1 px-6 pt-6 pb-8">
                    {{-- Level Label --}}
                    <div class="mb-6">
                        <span class="level-badge bg-yellow-500 text-white shadow-sm">
                            <i class="bi bi-1-circle-fill"></i>
                            Primer Nivel
                        </span>
                    </div>

                    {{-- Row: Dirección General + Consejo Asesor --}}
                    <div class="flex items-center justify-center gap-0">

                        {{-- Left spacer for Concejo Estudiantil --}}
                        <div class="flex flex-col items-center" style="width:180px;">
                            <div class="org-node org-node-dashed animate-fade-up" style="width:140px; min-height:52px;"
                                title="Concejo Estudiantil">
                                CONCEJO ESTUDIANTIL
                            </div>
                            {{-- Connector down --}}
                            <div class="v-line" style="height:40px;"></div>
                        </div>

                        {{-- Center: Dirección General --}}
                        <div class="flex flex-col items-center flex-1">
                            <div class="flex items-center justify-center gap-4">
                                {{-- Dirección General --}}
                                <div class="flex flex-col items-center">
                                    <div class="org-node org-node-solid animate-fade-up font-black text-sm"
                                        style="width:180px; min-height:52px; background:#1e293b; color:#f8fafc; border-color:#1e293b;"
                                        title="Dirección General">
                                        DIRECCIÓN GENERAL
                                    </div>
                                </div>
                                {{-- Horizontal connector --}}
                                <div class="h-line" style="width:40px;"></div>
                                {{-- Consejo Asesor --}}
                                <div class="flex flex-col items-center">
                                    <div class="org-node org-node-dashed animate-fade-up"
                                        style="width:140px; min-height:52px;" title="Consejo Asesor">
                                        CONSEJO ASESOR
                                    </div>
                                </div>
                            </div>
                            {{-- Connector down from Dirección General --}}
                            <div class="v-line" style="height:40px;"></div>
                        </div>

                        {{-- Right spacer --}}
                        <div style="width:180px;"></div>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- SEGUNDO NIVEL --}}
                {{-- ============================== --}}
                <div class="level-band-2 px-6 pt-4 pb-8">
                    <div class="mb-6">
                        <span class="level-badge bg-blue-600 text-white shadow-sm">
                            <i class="bi bi-2-circle-fill"></i>
                            Segundo Nivel
                        </span>
                    </div>

                    {{-- Secretaría de Dirección --}}
                    <div class="flex flex-col items-center animate-fade-up animate-delay-100">
                        <div class="org-node org-node-solid" style="width:170px; min-height:52px;"
                            title="Secretaría de Dirección">
                            SECRETARÍA DE DIRECCIÓN
                        </div>
                        <div class="v-line" style="height:32px;"></div>

                        {{-- Horizontal spread bar --}}
                        <div class="w-full relative" style="max-width:1100px;">
                            <div class="h-line w-full" style="margin: 0 auto;"></div>
                        </div>

                        {{-- Row of 2nd level units --}}
                        <div class="flex items-start justify-between w-full" style="max-width:1100px; gap:4px;">

                            {{-- ÁREA ADMINISTRATIVA (dashed) --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-100">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-dashed" style="width:100%; max-width:120px; min-height:52px; margin:0 auto;"
                                    title="Área Administrativa">
                                    ÁREA ADMINISTRATIVA
                                </div>
                            </div>

                            {{-- ÁREA DE PRODUCCIÓN (dashed) --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-100">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-dashed" style="width:100%; max-width:120px; min-height:52px; margin:0 auto;"
                                    title="Área de Producción">
                                    ÁREA DE PRODUCCIÓN
                                </div>
                            </div>

                            {{-- SECRETARÍA ACADÉMICA --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-100">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-solid" style="width:100%; max-width:130px; min-height:52px; margin:0 auto;"
                                    title="Secretaría Académica">
                                    SECRETARÍA ACADÉMICA
                                </div>
                            </div>

                            {{-- UNIDAD ACADÉMICA --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-100">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-solid" style="width:100%; max-width:120px; min-height:52px; margin:0 auto;"
                                    title="Unidad Académica">
                                    UNIDAD ACADÉMICA
                                </div>
                            </div>

                            {{-- ÁREA DE CALIDAD --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-100">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-solid" style="width:100%; max-width:110px; min-height:52px; margin:0 auto;"
                                    title="Área de Calidad">
                                    ÁREA DE CALIDAD
                                </div>
                            </div>

                            {{-- UNIDAD DE INVESTIGACIÓN --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-100">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-solid" style="width:100%; max-width:120px; min-height:52px; margin:0 auto;"
                                    title="Unidad de Investigación">
                                    UNIDAD DE INVESTIGACIÓN
                                </div>
                            </div>

                            {{-- UNIDAD DE BIENESTAR Y EMPLEABILIDAD (dashed) --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-200">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-dashed" style="width:100%; max-width:130px; min-height:52px; margin:0 auto;"
                                    title="Unidad de Bienestar y Empleabilidad">
                                    UNIDAD DE BIENESTAR Y EMPLEABILIDAD
                                </div>
                            </div>

                            {{-- UNIDAD DE FORMACIÓN CONTINUA (dashed) --}}
                            <div class="connector-branch flex-1 animate-fade-up animate-delay-200">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-dashed" style="width:100%; max-width:120px; min-height:52px; margin:0 auto;"
                                    title="Unidad de Formación Continua">
                                    UNIDAD DE FORMACIÓN CONTINUA
                                </div>
                            </div>
                        </div>

                        {{-- Sub-rows: Imagen Institucional under Área Prod, Secretaría UA under Unidad Académica --}}
                        <div class="flex items-start justify-between w-full mt-4" style="max-width:1100px; gap:4px;">
                            {{-- Under Área Administrativa --}}
                            <div class="flex-1 connector-branch">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-solid" style="width:100%; max-width:110px; min-height:44px; margin:0 auto; font-size:0.6rem;"
                                    title="Administración">
                                    ADMINISTRACIÓN
                                </div>
                            </div>

                            {{-- Under Área de Producción --}}
                            <div class="flex-1 connector-branch">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-dashed" style="width:100%; max-width:110px; min-height:44px; margin:0 auto; font-size:0.6rem;"
                                    title="Imagen Institucional">
                                    IMAGEN INSTITUCIONAL
                                </div>
                            </div>

                            {{-- Empty under Secretaría Académica --}}
                            <div class="flex-1"></div>

                            {{-- Under Unidad Académica → Secretaría de Unidad Académica --}}
                            <div class="flex-1 connector-branch">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="org-node org-node-solid" style="width:100%; max-width:140px; min-height:44px; margin:0 auto; font-size:0.6rem;"
                                    title="Secretaría de Unidad Académica">
                                    SECRETARÍA DE UNIDAD ACADÉMICA
                                </div>
                            </div>

                            {{-- Empty for Área de Calidad --}}
                            <div class="flex-1"></div>
                            {{-- Empty for Unidad de Investigación --}}
                            <div class="flex-1"></div>
                            {{-- Empty for Bienestar --}}
                            <div class="flex-1"></div>
                            {{-- Empty for Formación --}}
                            <div class="flex-1"></div>
                        </div>

                        {{-- Coordinadores Académicos row --}}
                        <div class="mt-6 w-full" style="max-width:900px; margin-left:auto; margin-right:auto; padding-left:calc(100%/8*3); padding-right:calc(100%/8*1);">
                            <div class="flex flex-col items-center">
                                <div class="v-line" style="height:24px;"></div>
                                <div class="h-line w-full"></div>
                                <div class="flex items-start justify-between w-full gap-2">
                                    {{-- Coord. Producción Agropecuaria --}}
                                    <div class="connector-branch flex-1 animate-fade-up animate-delay-200">
                                        <div class="v-line" style="height:20px;"></div>
                                        <div class="org-node org-node-solid"
                                            style="width:100%; max-width:130px; min-height:56px; margin:0 auto; font-size:0.58rem;"
                                            title="Coordinador Académico de Producción Agropecuaria">
                                            COORDINADOR ACADÉMICO DE PRODUCCIÓN AGROPECUARIA
                                        </div>
                                    </div>
                                    {{-- Coord. Enfermería Técnica --}}
                                    <div class="connector-branch flex-1 animate-fade-up animate-delay-200">
                                        <div class="v-line" style="height:20px;"></div>
                                        <div class="org-node org-node-solid"
                                            style="width:100%; max-width:130px; min-height:56px; margin:0 auto; font-size:0.58rem;"
                                            title="Coordinador Académico de Enfermería Técnica">
                                            COORDINADOR ACADÉMICO DE ENFERMERÍA TÉCNICA
                                        </div>
                                    </div>
                                    {{-- Coord. Administración de Redes --}}
                                    <div class="connector-branch flex-1 animate-fade-up animate-delay-200">
                                        <div class="v-line" style="height:20px;"></div>
                                        <div class="org-node org-node-solid"
                                            style="width:100%; max-width:130px; min-height:56px; margin:0 auto; font-size:0.58rem;"
                                            title="Coordinador Académico de Administración de Redes y Comunicación">
                                            COORDINADOR ACADÉMICO DE ADM. DE REDES Y COMUNICACIÓN
                                        </div>
                                    </div>
                                    {{-- Coord. Asistencia Administrativa --}}
                                    <div class="connector-branch flex-1 animate-fade-up animate-delay-200">
                                        <div class="v-line" style="height:20px;"></div>
                                        <div class="org-node org-node-solid"
                                            style="width:100%; max-width:130px; min-height:56px; margin:0 auto; font-size:0.58rem;"
                                            title="Coordinador Académico de Asistencia Administrativa">
                                            COORDINADOR ACADÉMICO DE ASISTENCIA ADMINISTRATIVA
                                        </div>
                                    </div>
                                    {{-- Coord. Manejo Forestal --}}
                                    <div class="connector-branch flex-1 animate-fade-up animate-delay-300">
                                        <div class="v-line" style="height:20px;"></div>
                                        <div class="org-node org-node-solid"
                                            style="width:100%; max-width:130px; min-height:56px; margin:0 auto; font-size:0.58rem;"
                                            title="Coordinador Académico de Manejo Forestal">
                                            COORDINADOR ACADÉMICO DE MANEJO FORESTAL
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TERCER NIVEL --}}
                {{-- ============================== --}}
                <div class="level-band-3 px-6 pt-4 pb-10">
                    <div class="mb-6">
                        <span class="level-badge bg-pink-600 text-white shadow-sm">
                            <i class="bi bi-3-circle-fill"></i>
                            Tercer Nivel
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-6 animate-fade-up animate-delay-200">

                        {{-- LEFT COLUMN: Administrative staff --}}
                        <div class="space-y-4">
                            <h3
                                class="text-xs font-black text-pink-800 uppercase tracking-widest flex items-center gap-2 mb-4">
                                <i class="bi bi-building text-pink-600"></i>
                                Dependencia Administrativa
                            </h3>

                            <div class="space-y-2">
                                {{-- Asistente Administrativo --}}
                                <div class="org-node org-node-solid text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Asistente Administrativo">
                                    <i class="bi bi-person-badge text-blue-600 shrink-0"></i>
                                    ASISTENTE ADMINISTRATIVO
                                </div>
                                {{-- Tesorero --}}
                                <div class="org-node org-node-solid text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Tesorero">
                                    <i class="bi bi-cash-coin text-blue-600 shrink-0"></i>
                                    TESORERO
                                </div>
                                {{-- Área de Patrimonio --}}
                                <div class="org-node org-node-solid text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Área de Patrimonio">
                                    <i class="bi bi-archive text-blue-600 shrink-0"></i>
                                    ÁREA DE PATRIMONIO
                                </div>
                                {{-- Área de Abastecimiento --}}
                                <div class="org-node org-node-solid text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Área de Abastecimiento">
                                    <i class="bi bi-box-seam text-blue-600 shrink-0"></i>
                                    ÁREA DE ABASTECIMIENTO
                                </div>
                            </div>

                            <div class="border-t border-pink-200 pt-3 space-y-2">
                                {{-- Personal de Servicio I, II, III --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Personal de Servicio">
                                    <i class="bi bi-people text-slate-500 shrink-0"></i>
                                    PERSONAL DE SERVICIO
                                </div>
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Personal de Servicio II">
                                    <i class="bi bi-people text-slate-500 shrink-0"></i>
                                    PERSONAL DE SERVICIO II
                                </div>
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Personal de Servicio III">
                                    <i class="bi bi-people text-slate-500 shrink-0"></i>
                                    PERSONAL DE SERVICIO III
                                </div>
                                {{-- Guardianía Diurna --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Guardianía Diurna">
                                    <i class="bi bi-shield-check text-slate-500 shrink-0"></i>
                                    GUARDIANÍA DIURNA
                                </div>
                                {{-- Asistente de Campo --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Asistente de Campo">
                                    <i class="bi bi-tree text-slate-500 shrink-0"></i>
                                    ASISTENTE DE CAMPO
                                </div>
                                {{-- Personal de Campo --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Personal de Campo">
                                    <i class="bi bi-tree-fill text-slate-500 shrink-0"></i>
                                    PERSONAL DE CAMPO
                                </div>
                            </div>
                        </div>

                        {{-- CENTER COLUMN: Academic --}}
                        <div class="space-y-6 flex flex-col items-center">
                            <h3
                                class="text-xs font-black text-pink-800 uppercase tracking-widest flex items-center gap-2 mb-4 self-start w-full">
                                <i class="bi bi-mortarboard text-pink-600"></i>
                                Comunidad Académica
                            </h3>

                            {{-- Docentes --}}
                            <div class="org-node org-node-solid font-black text-sm"
                                style="width:180px; min-height:52px; background:#1e3a5f; color:#e0f2fe; border-color:#1e3a5f;"
                                title="Docentes">
                                DOCENTES
                            </div>
                            <div class="v-line" style="height:20px;"></div>
                            <div class="h-line" style="width:180px;"></div>

                            {{-- Especialidad | Empleabilidad --}}
                            <div class="flex items-start justify-between w-full gap-4">
                                <div class="connector-branch flex-1">
                                    <div class="v-line" style="height:16px;"></div>
                                    <div class="org-node org-node-solid" style="width:100%; min-height:44px; margin:0 auto; font-size:0.65rem;"
                                        title="Especialidad">
                                        ESPECIALIDAD
                                    </div>
                                </div>
                                <div class="connector-branch flex-1">
                                    <div class="v-line" style="height:16px;"></div>
                                    <div class="org-node org-node-solid" style="width:100%; min-height:44px; margin:0 auto; font-size:0.65rem;"
                                        title="Empleabilidad">
                                        EMPLEABILIDAD
                                    </div>
                                </div>
                            </div>

                            <div class="v-line" style="height:20px;"></div>

                            {{-- Estudiantes --}}
                            <div class="org-node org-node-solid font-black text-sm"
                                style="width:180px; min-height:52px; background:#0f4c81; color:#e0f2fe; border-color:#0f4c81;"
                                title="Estudiantes">
                                ESTUDIANTES
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Bienestar services --}}
                        <div class="space-y-4">
                            <h3
                                class="text-xs font-black text-pink-800 uppercase tracking-widest flex items-center gap-2 mb-4">
                                <i class="bi bi-heart-pulse text-pink-600"></i>
                                Unidad de Bienestar
                            </h3>

                            <div class="space-y-2">
                                {{-- Servicio Médico --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Servicio Médico (Tópico)">
                                    <i class="bi bi-hospital text-pink-600 shrink-0"></i>
                                    SERVICIO MÉDICO (TÓPICO)
                                </div>
                                {{-- Servicio Psicopedagógico --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Servicio Psicopedagógico">
                                    <i class="bi bi-brain text-pink-600 shrink-0"></i>
                                    SERVICIO PSICOPEDAGÓGICO
                                </div>
                                {{-- Servicio de Bienestar Social --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Servicio de Bienestar Social (Consejería)">
                                    <i class="bi bi-people-fill text-pink-600 shrink-0"></i>
                                    SERVICIO DE BIENESTAR SOCIAL (CONSEJERÍA)
                                </div>
                                {{-- Servicio de Empleabilidad --}}
                                <div class="org-node org-node-dashed text-left justify-start gap-2 px-3 py-2"
                                    style="font-size:0.65rem; width:100%;" title="Servicio de Empleabilidad">
                                    <i class="bi bi-briefcase-fill text-pink-600 shrink-0"></i>
                                    SERVICIO DE EMPLEABILIDAD
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- END chart-wrapper --}}

            {{-- Download note --}}
            <p class="text-center text-xs text-slate-400 mt-4 font-medium">
                <i class="bi bi-info-circle mr-1"></i>
                Puede desplazarse horizontalmente en pantallas pequeñas para visualizar el organigrama completo.
            </p>
        </div>
    </section>

    {{-- ===== LEVEL DESCRIPTIONS SECTION ===== --}}
    <section class="py-20 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Descripción de Niveles
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                    ¿Qué función cumple cada nivel?
                </h2>
                <p class="text-base sm:text-lg text-slate-600 mt-3 leading-relaxed">
                    Cada nivel jerárquico tiene responsabilidades específicas que garantizan el funcionamiento integral de la institución.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Primer Nivel --}}
                <div class="p-8 rounded-3xl border-2 border-yellow-200 bg-yellow-50 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-yellow-400 text-white flex items-center justify-center mb-6 shadow-sm">
                        <i class="bi bi-building-gear text-2xl"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-xl mb-3">Primer Nivel</h3>
                    <p class="text-base text-slate-600 leading-relaxed">
                        Conformado por la <strong>Dirección General</strong> y el <strong>Consejo Asesor</strong>. Es el máximo órgano de gobierno, responsable de la conducción estratégica, normativa y representación institucional. También incorpora al <strong>Concejo Estudiantil</strong> como voz participativa de los estudiantes.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-yellow-200 text-yellow-900">Dirección General</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-yellow-200 text-yellow-900">Consejo Asesor</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-yellow-200 text-yellow-900">Concejo Estudiantil</span>
                    </div>
                </div>

                {{-- Segundo Nivel --}}
                <div class="p-8 rounded-3xl border-2 border-blue-200 bg-blue-50 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-6 shadow-sm">
                        <i class="bi bi-diagram-2 text-2xl"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-xl mb-3">Segundo Nivel</h3>
                    <p class="text-base text-slate-600 leading-relaxed">
                        Integrado por la <strong>Secretaría de Dirección</strong>, áreas operativas y la <strong>Unidad Académica</strong>. Coordina la gestión académica, la calidad educativa, la investigación, el bienestar estudiantil y la formación continua, articulando los planes institucionales.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-blue-200 text-blue-900">Secretaría Académica</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-blue-200 text-blue-900">Unidad Académica</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-blue-200 text-blue-900">Área de Calidad</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-blue-200 text-blue-900">Investigación</span>
                    </div>
                </div>

                {{-- Tercer Nivel --}}
                <div class="p-8 rounded-3xl border-2 border-pink-200 bg-pink-50 hover:shadow-lg transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-pink-600 text-white flex items-center justify-center mb-6 shadow-sm">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-xl mb-3">Tercer Nivel</h3>
                    <p class="text-base text-slate-600 leading-relaxed">
                        Compuesto por el personal de apoyo, docentes, estudiantes y los servicios de bienestar. Es el nivel operativo que ejecuta las actividades académicas y administrativas, brindando soporte directo al proceso formativo de los estudiantes.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-pink-200 text-pink-900">Docentes</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-pink-200 text-pink-900">Estudiantes</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-pink-200 text-pink-900">Bienestar Social</span>
                        <span class="px-2 py-1 text-xs font-bold rounded-lg bg-pink-200 text-pink-900">Empleabilidad</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== KEY UNITS SECTION ===== --}}
    <section class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Unidades Clave
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                    Áreas Estratégicas
                </h2>
                <p class="text-base sm:text-lg text-slate-600 mt-3 leading-relaxed">
                    Las coordinaciones académicas articulan las 5 carreras técnicas y garantizan la calidad del proceso de enseñanza-aprendizaje.
                </p>
            </div>

            {{-- 5 Coordinaciones Académicas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-12">
                @php
                    $coords = [
                        ['icon' => 'bi-tree-fill', 'color' => 'emerald', 'name' => 'Coordinación Académica', 'program' => 'Producción Agropecuaria'],
                        ['icon' => 'bi-heart-pulse-fill', 'color' => 'rose', 'name' => 'Coordinación Académica', 'program' => 'Enfermería Técnica'],
                        ['icon' => 'bi-router-fill', 'color' => 'sky', 'name' => 'Coordinación Académica', 'program' => 'Adm. de Redes y Comunicación'],
                        ['icon' => 'bi-briefcase-fill', 'color' => 'blue', 'name' => 'Coordinación Académica', 'program' => 'Asistencia Administrativa'],
                        ['icon' => 'bi-globe-americas', 'color' => 'teal', 'name' => 'Coordinación Académica', 'program' => 'Manejo Forestal'],
                    ];
                @endphp

                @foreach ($coords as $coord)
                    <div
                        class="bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-md transition duration-300 flex flex-col items-center text-center gap-3">
                        <div
                            class="w-12 h-12 rounded-xl bg-{{ $coord['color'] }}-100 text-{{ $coord['color'] }}-600 flex items-center justify-center shadow-sm">
                            <i class="bi {{ $coord['icon'] }} text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ $coord['name'] }}</p>
                            <h4 class="font-extrabold text-slate-900 text-sm mt-1 leading-tight">{{ $coord['program'] }}</h4>
                        </div>
                        <div class="w-full h-1 rounded-full bg-{{ $coord['color'] }}-500 mt-1"></div>
                    </div>
                @endforeach
            </div>

            {{-- Bienestar Services Row --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                    <span class="w-2 h-7 bg-pink-500 rounded-full"></span>
                    Servicios de Bienestar Estudiantil
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $services = [
                            ['icon' => 'bi-hospital', 'color' => 'red', 'name' => 'Servicio Médico', 'detail' => 'Tópico de primeros auxilios'],
                            ['icon' => 'bi-brain', 'color' => 'purple', 'name' => 'Servicio Psicopedagógico', 'detail' => 'Apoyo al aprendizaje'],
                            ['icon' => 'bi-people-fill', 'color' => 'pink', 'name' => 'Bienestar Social', 'detail' => 'Consejería estudiantil'],
                            ['icon' => 'bi-briefcase-fill', 'color' => 'indigo', 'name' => 'Empleabilidad', 'detail' => 'Inserción laboral'],
                        ];
                    @endphp
                    @foreach ($services as $svc)
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-sm transition">
                            <div class="w-9 h-9 rounded-lg bg-{{ $svc['color'] }}-100 text-{{ $svc['color'] }}-600 flex items-center justify-center shrink-0 shadow-sm">
                                <i class="bi {{ $svc['icon'] }} text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-slate-900 leading-tight">{{ $svc['name'] }}</p>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $svc['detail'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section
        class="py-20 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white text-center relative overflow-hidden border-t border-blue-900/30">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.1),transparent_40%)]">
        </div>
        <div class="container mx-auto px-4 relative z-10 space-y-8">
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight max-w-3xl mx-auto leading-tight">
                ¿Quieres conocer más sobre nuestra institución?
            </h2>
            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                Explora nuestra historia, conoce a nuestros docentes y descubre los programas de estudio que ofrecemos.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-2">
                <a href="{{ route('historia') }}"
                    class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-4 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5">
                    <i class="bi bi-clock-history text-blue-600 text-lg"></i>
                    Nuestra Historia
                </a>
                <a href="{{ route('programas-de-estudio') }}"
                    class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-4 rounded-xl font-extrabold transition flex items-center justify-center gap-2">
                    Programas de Estudio
                    <i class="bi bi-arrow-right text-lg"></i>
                </a>
            </div>
        </div>
    </section>

@endsection
