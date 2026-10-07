<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado — {{ $certificate->certificate_code }} — {{ $certificate->user->names ?? 'Estudiante' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/enterprise/favicons/logo-iestpfvc.png') }}">

    <!-- Google Fonts for High-Fidelity Official Printing -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800;900&family=Great+Vibes&family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ── Reset & Page Setup ── */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cert-gold: #c99e3a;
            --cert-gold-light: #e6c86e;
            --cert-gold-dark: #9a7620;
            --cert-blue: #1e3a8a;
            --cert-red: #dc2626;
            --cert-black: #0f1115;
            --cert-slate: #334155;
        }

        body {
            background-color: #0b0f19;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #111827;
            -webkit-font-smoothing: antialiased;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* ── Floating Admin Toolbar (No-Print) ── */
        .toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1000;
            color: #f8fafc;
        }

        .toolbar-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .toolbar-badge {
            background: rgba(201, 158, 58, 0.2);
            border: 1px solid rgba(201, 158, 58, 0.4);
            color: #f3d789;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
        }

        .toolbar-code {
            font-family: monospace;
            font-weight: 800;
            color: #ffffff;
            font-size: 14px;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #c99e3a 0%, #b38927 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(201, 158, 58, 0.35);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #d8ac44 0%, #c4982f 100%);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* ── Certificate Preview Stage ── */
        .stage {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 80px 20px 40px;
            min-height: 100vh;
        }

        /* ── Exact A4 Landscape Certificate Canvas (297mm x 210mm) ── */
        .certificate-sheet {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 12mm 18mm 12mm 18mm;
        }

        /* ── Geometric Ribbon Accents (Top-Right & Bottom-Left) ── */
        .ribbon-svg {
            position: absolute;
            pointer-events: none;
            z-index: 10;
        }

        .ribbon-top-right {
            top: 0;
            right: 0;
            width: 82mm;
            height: 60mm;
        }

        .ribbon-bottom-left {
            bottom: 0;
            left: 0;
            width: 82mm;
            height: 60mm;
        }

        /* ── Watermark ── */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140mm;
            height: 140mm;
            opacity: 0.11;
            pointer-events: none;
            z-index: 1;
            object-fit: contain;
            filter: grayscale(10%);
        }

        /* ── Top Header Section ── */
        .cert-header {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
            margin-bottom: 4mm;
        }

        .cert-logo {
            width: 25mm;
            height: auto;
            max-height: 27mm;
            object-fit: contain;
            flex-shrink: 0;
        }

        .cert-institution-titles {
            flex: 1;
            text-align: center;
            padding-right: 18mm; /* balance top right ribbon offset */
        }

        .inst-title-1 {
            font-family: 'Montserrat', 'Arial', sans-serif;
            font-size: 11pt;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .inst-title-2 {
            font-family: 'Cinzel', 'Times New Roman', serif;
            font-size: 23pt;
            font-weight: 900;
            letter-spacing: 1.5px;
            color: #1e3a8a;
            text-shadow: 1px 1px 0px rgba(0,0,0,0.08);
            text-transform: uppercase;
            line-height: 1.15;
            margin: 1.5mm 0;
        }

        .inst-subtitle-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-top: 1mm;
        }

        .inst-program-title {
            font-family: 'Montserrat', 'Arial', sans-serif;
            font-size: 9.5pt;
            font-weight: 800;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inst-city-calligraphy {
            font-family: 'Alex Brush', 'Great Vibes', cursive;
            font-size: 23pt;
            color: #b45309;
            font-weight: 700;
            line-height: 0.8;
            letter-spacing: 1px;
            text-shadow: 1px 1px 0px #fff, -1px -1px 0px #fff, 1px -1px 0px #fff, -1px 1px 0px #fff, 0 1px 2px rgba(0,0,0,0.15);
        }

        /* ── Certificate Main Heading ── */
        .cert-main-title {
            position: relative;
            z-index: 5;
            text-align: center;
            margin: 1mm 0 3mm 0;
        }

        .cert-main-title h1 {
            font-family: 'Montserrat', 'Arial Black', sans-serif;
            font-size: 38pt;
            font-weight: 900;
            letter-spacing: 5px;
            color: #000000;
            text-transform: uppercase;
            line-height: 1;
            text-shadow: 2px 2px 0px #ffffff, 3px 3px 0px rgba(0,0,0,0.05);
        }

        /* ── Content Grid: Narrative & Temario ── */
        .cert-body-grid {
            position: relative;
            z-index: 5;
            display: grid;
            grid-template-columns: 1fr 78mm;
            gap: 10mm;
            align-items: start;
            flex: 1;
        }

        /* ── Left Column: Narrative Details ── */
        .narrative-col {
            padding-right: 4mm;
        }

        .granted-to-label {
            font-family: 'Cinzel', 'Georgia', serif;
            font-size: 13pt;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 2mm;
        }

        .recipient-box {
            text-align: center;
            margin: 2mm 0 4mm 0;
        }

        .recipient-name {
            font-family: 'Montserrat', 'Arial Black', sans-serif;
            font-size: 19pt;
            font-weight: 900;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .narrative-paragraph {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.62;
            color: #0f172a;
            text-align: justify;
            text-justify: inter-word;
        }

        .narrative-paragraph strong {
            font-weight: 800;
            color: #000000;
        }

        .narrative-date-location {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 10.5pt;
            font-weight: 600;
            color: #0f172a;
            text-align: right;
            margin-top: 4mm;
        }

        /* ── Right Column: Temario Card ── */
        .temario-col {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .temario-badge {
            background: linear-gradient(180deg, #c5cad3 0%, #858b97 45%, #636974 55%, #4f5560 100%);
            border: 1.5px solid #3e444f;
            box-shadow: 0 3px 6px rgba(0,0,0,0.22), inset 0 1px 0 rgba(255,255,255,0.7);
            border-radius: 9999px;
            padding: 5px 28px;
            color: #ffffff;
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 10.5pt;
            font-weight: 900;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            z-index: 2;
            margin-bottom: -11px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        }

        .temario-card {
            width: 100%;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #858b97;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.9);
            padding: 16px 14px 14px 16px;
            z-index: 1;
        }

        .temario-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6.5px;
        }

        .temario-item {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            font-family: 'Inter', Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.35;
            color: #0f172a;
            font-weight: 500;
        }

        .temario-bullet {
            font-size: 10pt;
            line-height: 1;
            color: #000000;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── Bottom Section: QR Code in Bottom-Right Corner ── */
        .cert-bottom-bar {
            position: relative;
            z-index: 15;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            margin-top: 2mm;
        }

        .qr-corner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 4px;
            border-radius: 6px;
        }

        .qr-svg-wrapper {
            width: 25mm;
            height: 25mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-svg-wrapper svg {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        .cert-serial-code {
            font-family: 'Montserrat', monospace, sans-serif;
            font-size: 7.5pt;
            font-weight: 800;
            color: #000000;
            letter-spacing: 0.8px;
        }

        /* ── Modular Certificate Fallback Styling ── */
        .modular-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin: 4mm 0;
        }

        .modular-table th {
            background: #1e3a8a;
            color: #ffffff;
            font-weight: 700;
            padding: 5px 8px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .modular-table td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            color: #0f172a;
        }

        .modular-table tr:nth-child(even) {
            background: #f8fafc;
        }

        /* ── Print Media Optimization ── */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            html, body {
                width: 297mm;
                height: 210mm;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print, .toolbar {
                display: none !important;
            }

            .stage {
                padding: 0 !important;
                margin: 0 !important;
                width: 297mm !important;
                height: 210mm !important;
                min-height: 210mm !important;
            }

            .certificate-sheet {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 297mm !important;
                height: 210mm !important;
                page-break-after: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Toolbar for Screen View -->
    <header class="toolbar no-print">
        <div class="toolbar-info">
            <span class="toolbar-badge">
                {{ $certificate->isTraining() ? 'Certificado de Capacitación' : 'Certificado Modular' }}
            </span>
            <span class="toolbar-code">CÓDIGO: {{ $certificate->certificate_code }}</span>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('validar-certificado', $certificate->certificate_code) }}" target="_blank" class="btn btn-secondary">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Verificación Pública
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg>
                Imprimir / Guardar PDF
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </header>

    <main class="stage">
        <article class="certificate-sheet">

            <!-- ── Top-Right Geometric Ribbon Accent ── -->
            <svg class="ribbon-svg ribbon-top-right" viewBox="0 0 320 240" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="goldGradientTop" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#dfbc69" />
                        <stop offset="30%" stop-color="#f5e197" />
                        <stop offset="60%" stop-color="#c99e3a" />
                        <stop offset="100%" stop-color="#a17a1e" />
                    </linearGradient>
                </defs>
                <!-- Gold stripe -->
                <polygon points="120,0 320,180 320,240 60,0" fill="url(#goldGradientTop)" />
                <!-- Black outer triangle -->
                <polygon points="190,0 320,0 320,130" fill="#0f1115" />
            </svg>

            <!-- ── Bottom-Left Geometric Ribbon Accent ── -->
            <svg class="ribbon-svg ribbon-bottom-left" viewBox="0 0 320 240" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="goldGradientBottom" x1="100%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#dfbc69" />
                        <stop offset="30%" stop-color="#f5e197" />
                        <stop offset="60%" stop-color="#c99e3a" />
                        <stop offset="100%" stop-color="#a17a1e" />
                    </linearGradient>
                </defs>
                <!-- Gold stripe -->
                <polygon points="0,50 220,240 140,240 0,110" fill="url(#goldGradientBottom)" />
                <!-- Black outer triangle -->
                <polygon points="0,130 0,240 120,240" fill="#0f1115" />
            </svg>

            <!-- ── Centered Watermark Shield ── -->
            <img 
                src="{{ asset('storage/enterprise/favicons/logo-iestpfvc.png') }}" 
                alt="Marca de agua institucional" 
                class="watermark"
            >

            <!-- ── Header Section ── -->
            <header class="cert-header">
                <img 
                    src="{{ asset('storage/enterprise/favicons/logo-iestpfvc.png') }}" 
                    alt="Logo IESTP FVC" 
                    class="cert-logo"
                >
                <div class="cert-institution-titles">
                    <p class="inst-title-1">INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</p>
                    <h2 class="inst-title-2">“FRANCISCO VIGO CABALLERO”</h2>
                    <div class="inst-subtitle-row">
                        <span class="inst-program-title">
                            PROGRAMA DE ESTUDIOS DE {{ mb_strtoupper($certificate->effective_study_program?->name ?? 'ADMINISTRACIÓN DE REDES Y COMUNICACIONES', 'UTF-8') }}
                        </span>
                        <span class="inst-city-calligraphy">Uchiza</span>
                    </div>
                </div>
            </header>

            <!-- ── Title ── -->
            <div class="cert-main-title">
                <h1>{{ $certificate->isTraining() ? 'CERTIFICADO' : 'CERTIFICADO MODULAR' }}</h1>
            </div>

            <!-- ── Body Area: Narrative & Temario ── -->
            <div class="cert-body-grid">
                
                <!-- Left Narrative Column -->
                <div class="narrative-col">
                    <p class="granted-to-label">Otorgado a:</p>
                    
                    <div class="recipient-box">
                        <div class="recipient-name">
                            {{ mb_strtoupper($certificate->user->names ?? 'ESTUDIANTE REGISTRADO', 'UTF-8') }}
                        </div>
                    </div>

                    @if($certificate->isTraining())
                        {{-- Training / Completion Certificate Narrative matching standard format --}}
                        <p class="narrative-paragraph">
                            Por su participación en la calidad de <strong>{{ mb_strtoupper($certificate->participation_type ?? 'ASISTENTE', 'UTF-8') }}</strong> en el curso de capacitación en “<strong>{{ $certificate->description ?: ($certificate->course->name ?? 'Tecnologías de Información y Comunicación') }}</strong>”; organizado por el Programa de Estudios de {{ $certificate->effective_study_program?->name ?? 'Administración de Redes y Comunicaciones' }} del {{ $certificate->institution_name ?? 'Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero”' }} de Uchiza, con motivo de celebrarse la {{ $certificate->event_name ?? $certificate->course?->event_name ?? 'Semana Técnica 2026' }}, realizado {{ $certificate->formatted_date_range }}, con una duración de {{ $certificate->duration ?? '90 horas pedagógicas' }}.
                        </p>
                    @else
                        {{-- Modular Certificate Narrative --}}
                        <p class="narrative-paragraph">
                            Por haber aprobado satisfactoriamente los módulos técnico-profesionales correspondientes al plan curricular de <strong>{{ $certificate->course->name }}</strong>, en el Programa de Estudios de {{ $certificate->effective_study_program?->name ?? 'Administración de Redes y Comunicaciones' }} del {{ $certificate->institution_name ?? 'Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero”' }} de Uchiza, realizado {{ $certificate->formatted_date_range }}, con una duración de {{ $certificate->duration ?? 'Horas pedagógicas curriculares' }}.
                        </p>

                        {{-- Modules table for modular certificates --}}
                        <table class="modular-table">
                            <thead>
                                <tr>
                                    <th>Módulo Formativo</th>
                                    <th style="width: 70px; text-align: center;">Créditos</th>
                                    <th style="width: 70px; text-align: center;">Nota</th>
                                    <th style="width: 90px; text-align: center;">Condición</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certificate->details as $det)
                                    <tr>
                                        <td>{{ $det->title }}</td>
                                        <td style="text-align: center;">{{ $det->module?->credits ?? '3' }}</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $det->score ?? '16' }}</td>
                                        <td style="text-align: center; color: #166534; font-weight: bold;">Aprobado</td>
                                    </tr>
                                @empty
                                    @foreach($certificate->course?->modules ?? [] as $mod)
                                        <tr>
                                            <td>{{ $mod->name }}</td>
                                            <td style="text-align: center;">{{ $mod->credits ?? '3' }}</td>
                                            <td style="text-align: center; font-weight: bold;">16</td>
                                            <td style="text-align: center; color: #166534; font-weight: bold;">Aprobado</td>
                                        </tr>
                                    @endforeach
                                @endforelse
                            </tbody>
                        </table>
                    @endif

                    <div class="narrative-date-location">
                        {{ $certificate->formatted_issue_date }}
                    </div>
                </div>

                <!-- Right Temario Column -->
                <div class="temario-col">
                    <div class="temario-badge">TEMARIO</div>
                    <div class="temario-card">
                        <ul class="temario-list">
                            @php
                                $topics = $certificate->topics_list;
                            @endphp
                            @forelse($topics as $topic)
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>{{ $topic }}</span>
                                </li>
                            @empty
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>Análisis y Visualización de Datos con Power BI.</span>
                                </li>
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>IoT para la Transformación Digital de las Instituciones Públicas.</span>
                                </li>
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>MikroTik, Administración y Seguridad de Redes Institucionales.</span>
                                </li>
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>Sistemas ERP para la Gestión Empresarial.</span>
                                </li>
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>El Rol del Ingeniero en la Era de la IA: Requisitos, Patrones de Software y Producción Real.</span>
                                </li>
                                <li class="temario-item">
                                    <span class="temario-bullet">•</span>
                                    <span>El Impacto de la IA Agéntica en tu Futuro Profesional.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>

            <!-- ── Bottom Area: QR in Bottom-Right Corner & Certificate Code ── -->
            <footer class="cert-bottom-bar">
                <div class="qr-corner-container">
                    <div class="qr-svg-wrapper">
                        {!! $certificate->qr_code_svg !!}
                    </div>
                    <span class="cert-serial-code">{{ $certificate->certificate_code }}</span>
                </div>
            </footer>

        </article>
    </main>

</body>
</html>
