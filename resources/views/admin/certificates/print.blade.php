@php
    $logoSrc = $enterprise?->logo_base64 ?? asset('storage/enterprise/favicons/logo-iestpfvc.png');
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado — {{ $certificate->certificate_code }} — {{ $certificate->user->names ?? 'Estudiante' }}</title>
    <link rel="icon" type="image/png" href="{{ $logoSrc }}">

    <!-- Google Fonts for High-Fidelity Official Printing -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800;900&family=Cormorant+Garamond:ital,wght@1,600;1,700&family=Great+Vibes&family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800;900&family=Playfair+Display:ital,wght@1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <style>
        /* ── Reset & Page Setup ── */
        *,
        *::before,
        *::after {
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
            padding-right: 18mm;
            /* balance top right ribbon offset */
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
            text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.08);
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
            text-shadow: 1px 1px 0px #fff, -1px -1px 0px #fff, 1px -1px 0px #fff, -1px 1px 0px #fff, 0 1px 2px rgba(0, 0, 0, 0.15);
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
            text-shadow: 2px 2px 0px #ffffff, 3px 3px 0px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.22), inset 0 1px 0 rgba(255, 255, 255, 0.7);
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
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .temario-card {
            width: 100%;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #858b97;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.9);
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
            width: 50mm;
            height: 50mm;
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

        /* ── Basic English Certificate Custom Styles ── */
        .english-sheet {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 0;
            box-sizing: border-box;
        }

        .english-wave {
            position: absolute;
            pointer-events: none;
            z-index: 10;
        }

        .english-wave-tl {
            top: 0;
            left: 0;
            width: 115mm;
            height: 70mm;
        }

        .english-wave-br {
            bottom: 0;
            right: 0;
            width: 115mm;
            height: 70mm;
        }

        .english-header-logo-container {
            position: absolute;
            top: 6mm;
            right: 9mm;
            z-index: 15;
            text-align: center;
        }

        .english-top-logo {
            width: 34mm;
            height: auto;
            max-height: 40mm;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.12));
        }

        .english-rosette-container {
            position: absolute;
            top: 48%;
            left: 7mm;
            transform: translateY(-50%);
            z-index: 15;
            width: 44mm;
            height: 58mm;
            pointer-events: none;
        }

        .english-rosette-svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .english-front-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140mm;
            height: 140mm;
            opacity: 0.08;
            pointer-events: none;
            z-index: 1;
            object-fit: contain;
        }

        .english-content-container {
            position: relative;
            z-index: 8;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 24mm 38mm 16mm 38mm;
            text-align: center;
            box-sizing: border-box;
        }

        .english-cert-title {
            font-family: 'Cinzel', 'Times New Roman', serif;
            font-size: 40pt;
            font-weight: 800;
            color: #102a54;
            letter-spacing: 2.5px;
            line-height: 1;
            margin: 0;
            text-transform: uppercase;
        }

        .english-cert-granted {
            font-family: 'Cinzel', 'Times New Roman', serif;
            font-size: 15pt;
            font-weight: 700;
            color: #b88628;
            letter-spacing: 4px;
            line-height: 1;
            margin: 3.5mm 0 2mm 0;
            text-transform: uppercase;
        }

        .english-recipient-name {
            font-family: 'Playfair Display', 'Cormorant Garamond', 'Georgia', serif;
            font-style: italic;
            font-weight: 700;
            font-size: 26pt;
            color: #143360;
            line-height: 1.2;
            margin: 1.5mm 0 3.5mm 0;
            max-width: 215mm;
            letter-spacing: 0.5px;
        }

        .english-intro-text {
            font-family: 'Inter', sans-serif;
            font-size: 15pt;
            font-weight: 400;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
        }

        .english-course-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 20pt;
            font-weight: 800;
            color: #0d2852;
            letter-spacing: 1.5px;
            line-height: 1.3;
            margin: 2mm 0 3.5mm 0;
            text-transform: uppercase;
        }

        .english-narrative-box {
            width: 100%;
            max-width: 190mm;
            font-family: 'Inter', sans-serif;
            font-size: 15pt;
            font-weight: 400;
            color: #1e293b;
            line-height: 1.6;
            margin: 0 auto;
        }

        .english-narrative-line {
            margin: 0;
        }

        .english-closing {
            margin-top: 3.5mm !important;
        }

        .english-date-location {
            margin-top: 8mm;
            align-self: flex-end;
            padding-right: 22mm;
            font-family: 'Inter', sans-serif;
            font-size: 15pt;
            font-weight: 700;
            color: #0f2347;
            text-align: right;
        }

        /* ── Back Page (Reverso) ── */
        .english-sheet-back {
            padding: 14mm 16mm 14mm 16mm;
            justify-content: center;
            background: #ffffff;
        }

        .english-back-grid {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: 100%;
            gap: 12mm;
        }

        .english-qr-column {
            width: 44mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .english-qr-card {
            border: 1.5px solid #000000;
            padding: 3mm;
            background: #ffffff;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            width: 44mm;
            box-sizing: border-box;
        }

        .english-qr-svg-wrapper svg {
            width: 36mm;
            height: 36mm;
            display: block;
            margin: 0 auto;
        }

        .english-qr-code-text {
            font-family: 'Inter', monospace;
            font-size: 7.5pt;
            font-weight: 800;
            color: #000000;
            margin-top: 2mm;
            letter-spacing: 0.5px;
        }

        .english-qr-caption {
            font-family: 'Montserrat', sans-serif;
            font-size: 6pt;
            font-weight: 800;
            color: #475569;
            letter-spacing: 1px;
            margin-top: 1mm;
            text-transform: uppercase;
        }

        .english-table-column {
            position: relative;
            flex: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .english-table-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140mm;
            height: 140mm;
            opacity: 0.16;
            pointer-events: none;
            z-index: 1;
            object-fit: contain;
        }

        .english-academic-table {
            position: relative;
            z-index: 2;
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            background: transparent;
        }

        .english-academic-table th,
        .english-academic-table td {
            border: 1px solid #000000;
            padding: 2.2mm 2.8mm;
        }

        .english-academic-table thead th {
            font-family: 'Montserrat', sans-serif;
            font-size: 8.5pt;
            font-weight: 800;
            color: #000000;
            text-align: center;
            vertical-align: middle;
            background: rgba(255, 255, 255, 0.40);
            line-height: 1.25;
            text-transform: uppercase;
        }

        .english-academic-table tbody td {
            background: rgba(255, 255, 255, 0.30);
        }

        .english-academic-table .col-modulos {
            width: 48%;
        }

        .english-academic-table .col-creditos {
            width: 12%;
        }

        .english-academic-table .col-calificacion {
            width: 22%;
        }

        .english-academic-table .col-sub-numero {
            width: 10%;
        }

        .english-academic-table .col-sub-letras {
            width: 12%;
        }

        .english-academic-table .col-ano {
            width: 9%;
        }

        .english-academic-table .col-observacion {
            width: 9%;
        }

        .english-academic-table .mod-heading {
            font-family: 'Montserrat', sans-serif;
            font-size: 8.5pt;
            font-weight: 800;
            text-decoration: underline;
            margin-bottom: 1.5mm;
            color: #000000;
        }

        .english-academic-table .mod-items-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-family: 'Inter', sans-serif;
            font-size: 7.2pt;
            line-height: 1.34;
            color: #000000;
            font-weight: 500;
        }

        .english-academic-table .mod-items-list li {
            margin-bottom: 0.3mm;
            display: flex;
            align-items: baseline;
            gap: 1.5mm;
        }

        .english-academic-table .chk-mark {
            font-weight: 800;
            font-size: 8pt;
            color: #000000;
        }

        .english-academic-table .cell-center {
            text-align: center;
            vertical-align: middle;
            font-family: 'Inter', sans-serif;
            font-size: 8.5pt;
            color: #000000;
        }

        .btn-tab {
            background: rgba(255, 255, 255, 0.08);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-tab:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .btn-tab.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #3b82f6;
        }

        .view-switch-group {
            display: flex;
            align-items: center;
            gap: 4px;
            background: rgba(0, 0, 0, 0.25);
            padding: 3px;
            border-radius: 8px;
            margin-right: 8px;
        }

        /* ── Print Media Optimization ── */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            html,
            body {
                width: 297mm;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print,
            .toolbar {
                display: none !important;
            }

            .stage {
                padding: 0 !important;
                margin: 0 !important;
                width: 297mm !important;
                display: block !important;
                min-height: auto !important;
            }

            .certificate-sheet {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 297mm !important;
                height: 210mm !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .page-break {
                page-break-after: always !important;
                break-after: page !important;
            }

            /* Per-face selective printing support */
            body.print-front-only #sheet-reverso {
                display: none !important;
            }

            body.print-front-only #sheet-anverso {
                display: flex !important;
                page-break-after: auto !important;
                break-after: auto !important;
            }

            body.print-back-only #sheet-anverso {
                display: none !important;
            }

            body.print-back-only #sheet-reverso {
                display: flex !important;
                page-break-after: auto !important;
                break-after: auto !important;
            }
        }
    </style>
</head>

<body>

    <!-- Floating Toolbar for Screen View -->
    <header class="toolbar no-print">
        <div class="toolbar-info">
            <span class="toolbar-badge">
                {{ $certificate->isBasicEnglish() ? 'Certificado de Inglés a Nivel Básico' : ($certificate->isTraining() ? 'Certificado de Capacitación' : 'Certificado Modular') }}
            </span>
            <span class="toolbar-code">CÓDIGO: {{ $certificate->certificate_code }}</span>
        </div>
        <div class="toolbar-actions">
            @if ($certificate->isBasicEnglish())
                <div class="view-switch-group no-print">
                    <button type="button" class="btn btn-tab active" onclick="switchCertView('all', this)">Ambas
                        Caras</button>
                    <button type="button" class="btn btn-tab" onclick="switchCertView('front', this)">Frente</button>
                    <button type="button" class="btn btn-tab" onclick="switchCertView('back', this)">Reverso</button>
                </div>
            @endif
            <a href="{{ route('validar-certificado', $certificate->certificate_code) }}" target="_blank"
                class="btn btn-secondary">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Verificación Pública
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                    <path
                        d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                </svg>
                Imprimir Documento
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </header>

    <main class="stage"
        style="{{ $certificate->isBasicEnglish() ? 'display: flex; flex-direction: column; align-items: center; gap: 32px; padding: 80px 20px 40px;' : '' }}">

        @if ($certificate->isBasicEnglish())
            {{-- CERTIFICADO DE INGLÉS A NIVEL BÁSICO: ANVERSO (FRENTE)      --}}
            <article class="certificate-sheet english-sheet page-break" id="sheet-anverso">
                <!-- ── Top-Left Flowing Corner Wave SVG ── -->
                <svg class="english-wave english-wave-tl" viewBox="0 0 520 280" fill="none"
                    preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dotPatternTL" x="0" y="0" width="8" height="8"
                            patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.2" fill="#2b477d" opacity="0.35" />
                        </pattern>
                        <linearGradient id="engNavyGradTL" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#0a1832" />
                            <stop offset="50%" stop-color="#122a56" />
                            <stop offset="100%" stop-color="#1b3d7a" />
                        </linearGradient>
                        <linearGradient id="engGoldGradTL" x1="0%" y1="100%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#9d741c" />
                            <stop offset="35%" stop-color="#dfb957" />
                            <stop offset="65%" stop-color="#fae79d" />
                            <stop offset="100%" stop-color="#b68926" />
                        </linearGradient>
                        <linearGradient id="engRoyalGradTL" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#1a3d78" />
                            <stop offset="100%" stop-color="#2c5aa0" />
                        </linearGradient>
                    </defs>
                    <!-- Outer large deep navy wave -->
                    <path d="M0,0 L340,0 C280,95 200,180 0,225 Z" fill="url(#engNavyGradTL)" />
                    <path d="M0,0 L340,0 C280,95 200,180 0,225 Z" fill="url(#dotPatternTL)" />
                    <!-- First gold ribbon stripe -->
                    <path d="M0,185 C190,150 285,75 350,0 L370,0 C305,85 205,165 0,205 Z" fill="url(#engGoldGradTL)" />
                    <!-- Inner royal navy wave -->
                    <path d="M0,0 L195,0 C130,75 75,120 0,155 Z" fill="url(#engRoyalGradTL)" />
                    <!-- Second gold contour line -->
                    <path d="M0,155 C70,118 122,75 190,0 L200,0 C132,80 77,122 0,162 Z" fill="url(#engGoldGradTL)" />
                    <!-- Accent swoosh extending outward -->
                    <path d="M0,225 C170,190 300,105 445,0 L465,0 C310,115 180,205 0,245 Z"
                        fill="url(#engNavyGradTL)" />
                    <path d="M0,245 C180,205 320,110 475,0 L490,0 C325,120 190,220 0,265 Z"
                        fill="url(#engGoldGradTL)" />
                </svg>

                <!-- ── Bottom-Right Flowing Corner Wave SVG ── -->
                <svg class="english-wave english-wave-br" viewBox="0 0 520 280" fill="none"
                    preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dotPatternBR" x="0" y="0" width="8" height="8"
                            patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.2" fill="#2b477d" opacity="0.35" />
                        </pattern>
                        <linearGradient id="engNavyGradBR" x1="100%" y1="100%" x2="0%"
                            y2="0%">
                            <stop offset="0%" stop-color="#0a1832" />
                            <stop offset="50%" stop-color="#122a56" />
                            <stop offset="100%" stop-color="#1b3d7a" />
                        </linearGradient>
                        <linearGradient id="engGoldGradBR" x1="100%" y1="0%" x2="0%"
                            y2="100%">
                            <stop offset="0%" stop-color="#9d741c" />
                            <stop offset="35%" stop-color="#dfb957" />
                            <stop offset="65%" stop-color="#fae79d" />
                            <stop offset="100%" stop-color="#b68926" />
                        </linearGradient>
                        <linearGradient id="engRoyalGradBR" x1="100%" y1="100%" x2="0%"
                            y2="0%">
                            <stop offset="0%" stop-color="#1a3d78" />
                            <stop offset="100%" stop-color="#2c5aa0" />
                        </linearGradient>
                    </defs>
                    <!-- Outer deep navy wave from bottom-right -->
                    <path d="M520,280 L180,280 C240,185 320,100 520,55 Z" fill="url(#engNavyGradBR)" />
                    <path d="M520,280 L180,280 C240,185 320,100 520,55 Z" fill="url(#dotPatternBR)" />
                    <!-- Gold ribbon stripe -->
                    <path d="M520,95 C330,130 235,205 170,280 L150,280 C215,195 315,115 520,75 Z"
                        fill="url(#engGoldGradBR)" />
                    <!-- Inner royal navy wave -->
                    <path d="M520,280 L325,280 C390,205 445,160 520,125 Z" fill="url(#engRoyalGradBR)" />
                    <!-- Gold contour ribbon -->
                    <path d="M520,125 C450,162 398,205 330,280 L320,280 C388,200 443,158 520,118 Z"
                        fill="url(#engGoldGradBR)" />
                    <!-- Outer extending swoosh -->
                    <path d="M520,55 C350,90 220,175 75,280 L55,280 C210,165 340,75 520,35 Z"
                        fill="url(#engNavyGradBR)" />
                    <path d="M520,35 C340,75 200,170 45,280 L30,280 C195,160 330,60 520,15 Z"
                        fill="url(#engGoldGradBR)" />
                </svg>

                <!-- ── Center Institutional Logo Watermark ── -->
                <img src="{{ $logoSrc }}" alt="Marca de agua institucional"
                    class="english-watermark english-front-watermark">

                <!-- ── Top-Right Institutional Crest / Shield ── -->
                <div class="english-header-logo-container">
                    <img src="{{ $logoSrc }}" alt="Logo IESTP FVC" class="english-top-logo">
                </div>

                <!-- ── Mid-Left Metallic Golden Rosette / Seal with Ribbons ── -->
                <div class="english-rosette-container">
                    <svg class="english-rosette-svg" viewBox="0 0 160 210" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="ribbonGoldGrad1" x1="0%" y1="0%" x2="100%"
                                y2="100%">
                                <stop offset="0%" stop-color="#dfb957" />
                                <stop offset="50%" stop-color="#b88924" />
                                <stop offset="100%" stop-color="#80590c" />
                            </linearGradient>
                            <linearGradient id="ribbonGoldGrad2" x1="100%" y1="0%" x2="0%"
                                y2="100%">
                                <stop offset="0%" stop-color="#f5e197" />
                                <stop offset="50%" stop-color="#c99e3a" />
                                <stop offset="100%" stop-color="#8a610f" />
                            </linearGradient>
                            <radialGradient id="medalRadial" cx="38%" cy="32%" r="65%">
                                <stop offset="0%" stop-color="#fffbe8" />
                                <stop offset="25%" stop-color="#f7e4a3" />
                                <stop offset="55%" stop-color="#d4aa3b" />
                                <stop offset="85%" stop-color="#a47519" />
                                <stop offset="100%" stop-color="#6e4c08" />
                            </radialGradient>
                            <radialGradient id="innerMedalRadial" cx="45%" cy="40%" r="55%">
                                <stop offset="0%" stop-color="#fff8db" />
                                <stop offset="40%" stop-color="#e8c25f" />
                                <stop offset="80%" stop-color="#b8871f" />
                                <stop offset="100%" stop-color="#825a0a" />
                            </radialGradient>
                        </defs>
                        <!-- Ribbon Tails (Left) -->
                        <polygon points="52,110 32,195 56,182 80,195 68,110" fill="url(#ribbonGoldGrad1)" />
                        <!-- Ribbon Tails (Right) -->
                        <polygon points="92,110 80,195 104,182 128,195 108,110" fill="url(#ribbonGoldGrad2)" />

                        <!-- Rosette Star / Serrated Polygon (24 points) -->
                        <g transform="translate(80, 75)">
                            <path
                                d="M0,-65 L8,-58 L18,-63 L24,-53 L35,-56 L38,-44 L50,-44 L50,-31 L60,-28 L57,-15 L65,-10 L58,3 L63,14 L53,19 L56,31 L44,34 L44,46 L31,46 L28,56 L15,53 L10,61 L-3,54 L-14,59 L-19,49 L-31,52 L-34,40 L-46,40 L-46,27 L-56,24 L-53,11 L-61,6 L-54,-7 L-59,-18 L-49,-23 L-52,-35 L-40,-38 L-40,-50 L-27,-50 L-24,-60 L-11,-57 L-6,-65 Z"
                                fill="url(#medalRadial)" />
                            <!-- Outer Ring Border -->
                            <circle cx="0" cy="0" r="48" fill="none" stroke="#fceebb"
                                stroke-width="2" />
                            <!-- Inner Disc -->
                            <circle cx="0" cy="0" r="46" fill="url(#innerMedalRadial)" />
                            <circle cx="0" cy="0" r="41" fill="none" stroke="#875d0b"
                                stroke-width="1.5" stroke-dasharray="2 2" />
                            <!-- Radial Sunburst Facets -->
                            <path d="M0,-40 L0,40 M-40,0 L40,0 M-28,-28 L28,28 M-28,28 L28,-28" stroke="#ffeaa7"
                                stroke-width="1.5" opacity="0.6" />
                            <!-- Center Polished Disc -->
                            <circle cx="0" cy="0" r="26" fill="url(#medalRadial)" />
                            <circle cx="0" cy="0" r="22" fill="none" stroke="#fef0c7"
                                stroke-width="1.5" />
                        </g>
                    </svg>
                </div>

                <!-- ── Main Content Area ── -->
                <div class="english-content-container">
                    <h1 class="english-cert-title">CERTIFICADO</h1>
                    <h2 class="english-cert-granted">OTORGADO A:</h2>

                    @php
                        $rawStudentName = $certificate->user->names;
                        $cleanStudentName = preg_replace('/\s*,\s*/', ', ', trim($rawStudentName));
                        $displayName = mb_convert_case($cleanStudentName, MB_CASE_TITLE, 'UTF-8');
                    @endphp
                    <div class="english-recipient-name">
                        {{ $displayName }}
                    </div>

                    <p class="english-intro-text">
                        Por haber concluido satisfactoria el curso de:
                    </p>

                    <div class="english-course-title">
                        {{ $certificate->course->name }}
                    </div>

                    <div class="english-narrative-box">
                        @php
                            $formattedRange =
                                $certificate->formatted_date_range ?: 'del 14 de mayo al 16 de Julio del 2026';
                            $cleanDuration = rtrim($certificate->duration ?: '128 horas pedagógicas', '.');
                        @endphp
                        <p class="english-narrative-line">
                            Realizado {{ $formattedRange }},
                        </p>
                        <p class="english-narrative-line">
                            duración {{ $cleanDuration }}.
                        </p>
                        <p class="english-narrative-line english-closing">
                            Se le expide este documento a solicitud del interesado para los fines pertinentes.
                        </p>
                    </div>

                    @php
                        $issueDateObj = $certificate->issue_date
                            ? \Carbon\Carbon::parse($certificate->issue_date)
                            : null;
                        $mesesCap = [
                            1 => 'Enero',
                            2 => 'Febrero',
                            3 => 'Marzo',
                            4 => 'Abril',
                            5 => 'Mayo',
                            6 => 'Junio',
                            7 => 'Julio',
                            8 => 'Agosto',
                            9 => 'Setiembre',
                            10 => 'Octubre',
                            11 => 'Noviembre',
                            12 => 'Diciembre',
                        ];
                        $issueDay = $issueDateObj ? $issueDateObj->day : '29';
                        $issueMonth = $issueDateObj
                            ? $mesesCap[$issueDateObj->month] ?? $issueDateObj->format('F')
                            : 'Diciembre';
                        $issueYear = $issueDateObj ? $issueDateObj->year : '2025';
                        $issueCity = $certificate->city ?: 'Uchiza';
                    @endphp
                    <div class="english-date-location">
                        {{ $issueCity }} {{ $issueDay }} de {{ $issueMonth }} del {{ $issueYear }}
                    </div>
                </div>
            </article>
            {{-- CERTIFICADO DE INGLÉS A NIVEL BÁSICO: REVERSO (CALIFICACIONES & QR) --}}
            <article class="certificate-sheet english-sheet english-sheet-back" id="sheet-reverso">

                <!-- ── Back Grid: Left QR & Right Academic Table ── -->
                <div class="english-back-grid">

                    <!-- Left Column: Official Verification QR Code -->
                    <div class="english-qr-column">
                        <div class="english-qr-card">
                            <div class="english-qr-svg-wrapper">
                                {!! $certificate->qr_code_svg !!}
                            </div>
                            <div class="english-qr-code-text">{{ $certificate->certificate_code }}</div>
                            <div class="english-qr-caption">ESCANEAR PARA VALIDAR</div>
                        </div>
                    </div>

                    <!-- Right Column: Academic Evaluation & Syllabus Table -->
                    <div class="english-table-column">
                        <!-- Institutional Shield Watermark Behind Table -->
                        <img src="{{ $logoSrc }}" alt="Marca de agua institucional"
                            class="english-table-watermark">

                        <table class="english-academic-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="col-modulos">MODULOS Y CONTENIDOS</th>
                                    <th rowspan="2" class="col-creditos">NÚMERO<br>DE<br>CREDITOS</th>
                                    <th colspan="2" class="col-calificacion">CALIFICACIÓN</th>
                                    <th rowspan="2" class="col-ano">AÑO</th>
                                    <th rowspan="2" class="col-observacion">OBSERVACIÓN</th>
                                </tr>
                                <tr>
                                    <th class="col-sub-numero">EN<br>NÚMERO</th>
                                    <th class="col-sub-letras">EN LETRAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($certificate->english_modules_data as $mod)
                                    <tr>
                                        <td class="cell-contents">
                                            <div class="mod-heading">{{ $mod['name'] }}</div>
                                            <ul class="mod-items-list">
                                                @foreach ($mod['contents'] as $content)
                                                    <li><span class="chk-mark">✓</span>
                                                        <span>{{ $content }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="cell-center font-bold">{{ $mod['credits'] }}</td>
                                        <td class="cell-center font-bold">{{ $mod['score_num'] }}</td>
                                        <td class="cell-center">{{ $mod['score_text'] }}</td>
                                        <td class="cell-center font-mono">{{ $mod['year'] }}</td>
                                        <td class="cell-center">{{ $mod['observation'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        @else
            {{-- FORMATO ESTÁNDAR: CAPACITACIÓN / SEMANA TÉCNICA / MODULAR --}}
            <article class="certificate-sheet">
                <!-- ── Top-Right Geometric Ribbon Accent ── -->
                <svg class="ribbon-svg ribbon-top-right" viewBox="0 0 320 240" fill="none"
                    preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="goldGradientTop" x1="0%" y1="100%" x2="100%"
                            y2="0%">
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
                <svg class="ribbon-svg ribbon-bottom-left" viewBox="0 0 320 240" fill="none"
                    preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="goldGradientBottom" x1="100%" y1="0%" x2="0%"
                            y2="100%">
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
                <img src="{{ $logoSrc }}" alt="Marca de agua institucional" class="watermark">

                <!-- ── Header Section ── -->
                <header class="cert-header">
                    <img src="{{ $logoSrc }}" alt="Logo IESTP FVC" class="cert-logo">
                    <div class="cert-institution-titles">
                        <p class="inst-title-1">INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</p>
                        <h2 class="inst-title-2">“FRANCISCO VIGO CABALLERO”</h2>
                        <div class="inst-subtitle-row">
                            <span class="inst-program-title">
                                PROGRAMA DE ESTUDIOS DE
                                {{ mb_strtoupper($certificate->effective_study_program?->name ?? 'ADMINISTRACIÓN DE REDES Y COMUNICACIONES', 'UTF-8') }}
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
                        @if ($certificate->isTraining())
                            {{-- Training / Completion Certificate Narrative matching standard format --}}
                            <p class="narrative-paragraph">
                                Por su participación en la calidad de
                                <strong>{{ mb_strtoupper($certificate->participation_type ?? 'ASISTENTE', 'UTF-8') }}</strong>
                                en el curso de capacitación en
                                “<strong>{{ $certificate->description ?: $certificate->course->name ?? 'Tecnologías de Información y Comunicación' }}</strong>”;
                                organizado por el Programa de Estudios de
                                {{ $certificate->effective_study_program?->name ?? 'Administración de Redes y Comunicaciones' }}
                                del
                                {{ $certificate->institution_name ?? 'Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero”' }}
                                de Uchiza, con motivo de celebrarse la
                                {{ $certificate->event_name ?? ($certificate->course?->event_name ?? 'Semana Técnica 2026') }},
                                realizado {{ $certificate->formatted_date_range }}, con una duración de
                                {{ $certificate->duration ?? '90 horas pedagógicas' }}.
                            </p>
                        @else
                            {{-- Modular Certificate Narrative --}}
                            <p class="narrative-paragraph">
                                Por haber aprobado satisfactoriamente los módulos técnico-profesionales correspondientes
                                al plan curricular de <strong>{{ $certificate->course->name }}</strong>, en el Programa
                                de Estudios de
                                {{ $certificate->effective_study_program?->name ?? 'Administración de Redes y Comunicaciones' }}
                                del
                                {{ $certificate->institution_name ?? 'Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero”' }}
                                de Uchiza, realizado {{ $certificate->formatted_date_range }}, con una duración de
                                {{ $certificate->duration ?? 'Horas pedagógicas curriculares' }}.
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
                                            <td style="text-align: center; font-weight: bold;">
                                                {{ $det->score ?? '16' }}</td>
                                            <td style="text-align: center; color: #166534; font-weight: bold;">Aprobado
                                            </td>
                                        </tr>
                                    @empty
                                        @foreach ($certificate->course?->modules ?? [] as $mod)
                                            <tr>
                                                <td>{{ $mod->name }}</td>
                                                <td style="text-align: center;">{{ $mod->credits ?? '3' }}</td>
                                                <td style="text-align: center; font-weight: bold;">16</td>
                                                <td style="text-align: center; color: #166534; font-weight: bold;">
                                                    Aprobado</td>
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
        @endif
    </main>

    <script>
        function switchCertView(view, btn) {
            const front = document.getElementById('sheet-anverso');
            const back = document.getElementById('sheet-reverso');
            const btns = document.querySelectorAll('.btn-tab');
            btns.forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            document.body.classList.remove('print-front-only', 'print-back-only');

            if (view === 'front') {
                document.body.classList.add('print-front-only');
                if (front) front.style.display = 'flex';
                if (back) back.style.display = 'none';
            } else if (view === 'back') {
                document.body.classList.add('print-back-only');
                if (front) front.style.display = 'none';
                if (back) back.style.display = 'flex';
            } else {
                if (front) front.style.display = 'flex';
                if (back) back.style.display = 'flex';
            }
        }
    </script>

</body>

</html>
