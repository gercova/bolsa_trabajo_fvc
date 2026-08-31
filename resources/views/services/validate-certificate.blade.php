@extends('layouts.app')
@php
    $pageTitle = $certificate
        ? 'Validación Oficial: ' . $certificate->certificate_code . ' — IESTP Francisco Vigo Caballero'
        : 'Validar Certificados Oficiales — IESTP Francisco Vigo Caballero';
    $metaDesc = $certificate
        ? 'Verificación digital oficial del certificado ' . $certificate->certificate_code . ' emitido a ' . ($certificate->user->names ?? 'Estudiante') . ' para el curso ' . ($certificate->course->name ?? 'Curso Modular') . ' en el IESTP Francisco Vigo Caballero.'
        : 'Portal oficial de validación y verificación de certificados modulares y académicos del IESTP Francisco Vigo Caballero mediante código QR, DNI o código institucional.';
@endphp
@section('title', $pageTitle)
@section('meta_description', $metaDesc)
@section('meta_keywords', 'validar certificado, verificar certificado qr, certificacion modular, iestp francisco vigo caballero, uchiza, minedu, acreditacion academica, notas modulares')
@section('canonical_url', url()->current())

@push('styles')
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($enterprise->logo_path ?? 'enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data for SEO & Verification --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "EducationalOrganization",
                "@id": "{{ url('/') }}#organization",
                "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}",
                "alternateName": "{{ $enterprise->trade_name ?? 'IESTP FVC' }}",
                "url": "{{ url('/') }}",
                "logo": "{{ asset($enterprise->logo_path ?? 'enterprise/favicons/logo-iestpfvc.png') }}",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
                    "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
                    "addressRegion": "San Martín",
                    "addressCountry": "PE"
                }
            },
            {
                "@type": "BreadcrumbList",
                "@id": "{{ url()->current() }}#breadcrumb",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Inicio",
                        "item": "{{ url('/') }}"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "Servicios",
                        "item": "{{ route('enlaces-institucionales') }}"
                    },
                    {
                        "@type": "ListItem",
                        "position": 3,
                        "name": "Validar Certificados",
                        "item": "{{ route('validar-certificado') }}"
                    }
                ]
            }
            @if($certificate)
            ,{
                "@type": "EducationalOccupationalCredential",
                "@id": "{{ url('/validar-certificado/' . $certificate->certificate_code) }}#credential",
                "name": "{{ $certificate->course->name ?? 'Certificación Modular' }}",
                "credentialCategory": "Certificado de Aprobación Modular",
                "identifier": "{{ $certificate->certificate_code }}",
                "recognizedBy": {
                    "@type": "EducationalOrganization",
                    "name": "{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}"
                },
                "validFor": "P3Y",
                "about": {
                    "@type": "Person",
                    "name": "{{ $certificate->user->names ?? 'Estudiante' }}"
                }
            }
            @endif
        ]
    }
    </script>

    <style>
        .print-only {
            display: none !important;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 6mm 8mm 6mm 8mm;
            }

            *, *::before, *::after {
                box-sizing: border-box !important;
            }

            /* 1. COMPLETELY HIDE & REMOVE NON-PRINT ELEMENTS FROM DOM LAYOUT */
            #site-header,
            .nav-inner,
            #site-footer,
            .footer-grid,
            .footer-bottom,
            .hero-section,
            .summary-tables-section,
            .no-print,
            a.sr-only,
            header,
            nav,
            footer,
            form {
                display: none !important;
                position: absolute !important;
                visibility: hidden !important;
                height: 0 !important;
                max-height: 0 !important;
                min-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                top: -99999px !important;
                left: -99999px !important;
                overflow: hidden !important;
                clip: rect(0, 0, 0, 0) !important;
                pointer-events: none !important;
            }

            /* 2. RESET ALL ANCESTOR CONTAINERS TO ZERO PADDING / MARGINS */
            html, body {
                background: #ffffff !important;
                color: #0f172a !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            main,
            #main-content {
                margin: 0 !important;
                padding: 0 !important;
                padding-top: 0 !important;
                min-height: 0 !important;
                position: static !important;
                display: block !important;
                width: 100% !important;
                background: transparent !important;
            }

            .validate-page-wrapper {
                margin: 0 !important;
                padding: 0 !important;
                min-height: 0 !important;
                background: transparent !important;
            }

            .certificate-result-wrapper {
                margin: 0 !important;
                padding: 0 !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                top: 0 !important;
                position: static !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            /* 3. CERTIFICATE PRINT CARD */
            #certificate-print-card {
                margin: 0 auto !important;
                padding: 0 !important;
                position: static !important;
                width: 100% !important;
                max-width: 100% !important;
                border: 1.5pt solid #059669 !important;
                border-radius: 8pt !important;
                box-shadow: none !important;
                background: #ffffff !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                display: block !important;
            }

            .print-only {
                display: block !important;
            }

            .print-header-flex {
                display: flex !important;
            }

            .print-card-body {
                padding: 12pt !important;
                gap: 8pt !important;
            }

            .print-grid {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 6pt !important;
            }

            .print-meta-box {
                border: 0.75pt solid #cbd5e1 !important;
                background-color: #f8fafc !important;
                padding: 5pt 6pt !important;
                border-radius: 4pt !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th, td {
                border: 0.5pt solid #cbd5e1 !important;
                padding: 3.5pt 5pt !important;
            }

            thead th {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
            }
        }
    </style>
@endpush

@section('content')
<div class="validate-page-wrapper bg-slate-50 min-h-screen font-sans text-slate-800" x-data="{
    activeTab: 'years',
    searchQuery: '{{ $searchCode }}',
    copiedLink: false,
    copyValidationLink() {
        navigator.clipboard.writeText(window.location.href);
        this.copiedLink = true;
        setTimeout(() => this.copiedLink = false, 3000);
    }
}">
    {{-- ═══ HERO & SEARCH SECTION (HIDDEN IN PRINT) ════════════════ --}}
    <section class="hero-section no-print relative bg-gradient-to-br from-slate-950 via-indigo-950 to-blue-900 text-white overflow-hidden py-14 lg:py-18 border-b border-indigo-900/40">
        {{-- Decorative Background Patterns --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_25%,rgba(99,102,241,0.25),transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_75%,rgba(16,185,129,0.18),transparent_40%)]"></div>
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)]"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs sm:text-sm font-semibold backdrop-blur-md shadow-inner">
                <i class="bi bi-patch-check-fill text-emerald-400 text-base"></i>
                <span>Sistema Oficial de Validación Digital por QR &amp; Código</span>
            </div>

            {{-- Main Heading --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight font-display">
                Validación de <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Certificados</span> Institucionales
            </h1>

            <p class="text-sm sm:text-base lg:text-lg text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed">
                Consulte y verifique la autenticidad de los certificados modulares y académicos emitidos por el 
                <strong class="text-white font-semibold">{{ $enterprise->trade_name ?? 'IESTP Francisco Vigo Caballero' }}</strong>. Ingrese el código impreso, escanee el código QR o busque por DNI.
            </p>

            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto pt-2">
                <form action="{{ route('validar-certificado') }}" method="GET" class="relative flex items-center shadow-2xl rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 p-2 focus-within:border-emerald-400 focus-within:ring-4 focus-within:ring-emerald-500/20 transition-all">
                    <div class="pl-3 pr-2 text-slate-400 flex items-center">
                        <i class="bi bi-qr-code-scan text-xl text-emerald-400"></i>
                    </div>
                    <input 
                        type="text" 
                        name="code" 
                        value="{{ $searchCode }}" 
                        placeholder="Ejemplo: CERT-00000011-1 o número de DNI..." 
                        aria-label="Código de certificado o DNI"
                        required
                        class="w-full bg-transparent text-white placeholder-slate-400 text-sm sm:text-base px-2 py-2.5 focus:outline-none font-mono font-medium">
                    <button 
                        type="submit" 
                        class="shrink-0 px-5 sm:px-7 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 transition-all flex items-center gap-2">
                        <i class="bi bi-search"></i>
                        <span class="hidden sm:inline">Validar</span>
                    </button>
                </form>
                <div class="flex items-center justify-center gap-4 text-xs text-slate-400 mt-2.5">
                    <span><i class="bi bi-shield-lock-fill text-emerald-400"></i> Verificación criptográfica segura</span>
                    <span>•</span>
                    <span><i class="bi bi-lightning-charge-fill text-amber-400"></i> Resultados en tiempo real</span>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══ CERTIFICATE VALIDATION RESULT (IF SEARCHED) ═══════════════ --}}
    @if($searched)
        <div class="certificate-result-wrapper max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20 mb-12">

            @if($certificate)
                {{-- Valid Certificate Card --}}
                <div id="certificate-print-card" class="bg-white rounded-3xl shadow-xl border border-emerald-200/80 overflow-hidden transition-all animate-fade-in">
                    
                    {{-- Official Institutional Letterhead (Visible in Print) --}}
                    <div class="print-only border-b-2 border-emerald-600 bg-slate-50 px-6 py-4">
                        <div class="print-header-flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                @if($enterprise->logo_path)
                                    <img src="{{ asset($enterprise->logo_path) }}" alt="Logo IESTP FVC" class="h-14 w-auto object-contain">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xl font-bold">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none">INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</p>
                                    <h2 class="text-sm font-black text-slate-900 leading-tight uppercase font-display mt-0.5">"FRANCISCO VIGO CABALLERO" — UCHIZA</h2>
                                    <p class="text-[9px] text-slate-500 leading-none mt-0.5">R.M. N° 0450-1997-ED | Código Modular: 0548123 | San Martín - Perú</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded block">SISTEMA DE VERIFICACIÓN OFICIAL</span>
                                <span class="text-[9px] text-slate-500 font-mono block mt-0.5">Emisión: {{ $certificate->issue_date ? \Carbon\Carbon::parse($certificate->issue_date)->format('d/m/Y') : date('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Banner --}}
                    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white px-6 py-3.5 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-lg shrink-0">
                                <i class="bi bi-patch-check-fill text-emerald-200"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-extrabold tracking-widest text-emerald-200 block">ESTADO DE VALIDACIÓN</span>
                                <h2 class="text-sm sm:text-base font-black tracking-wide text-white">
                                    {{ $certificate->is_active ? 'CERTIFICADO AUTÉNTICO Y VÁLIDO' : 'CERTIFICADO INACTIVO / REVOCADO' }}
                                </h2>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $certificate->is_active ? 'bg-emerald-400/20 text-emerald-100 border border-emerald-300/30' : 'bg-red-400/20 text-red-100 border border-red-300/30' }}">
                                <i class="bi bi-circle-fill text-[8px] mr-1 {{ $certificate->is_active ? 'text-emerald-300 animate-pulse' : 'text-red-300' }}"></i>
                                {{ $certificate->is_active ? 'Registro Vigente' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="print-card-body p-6 sm:p-8 space-y-6 sm:space-y-8 bg-gradient-to-b from-emerald-50/20 to-white">

                        {{-- Main Certificate Header Data --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center border-b border-slate-100 pb-5">
                            <div class="md:col-span-2 space-y-2">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold">
                                    <i class="bi bi-award-fill"></i>
                                    Código Oficial: <span class="font-mono font-black text-indigo-900">{{ $certificate->certificate_code }}</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-display">
                                    {{ $certificate->course->name ?? 'CURSO MODULAR INSTITUCIONAL' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    {{ $certificate->description ?: ($certificate->course->description ?: 'Certificado emitido a favor del participante por haber completado satisfactoriamente los módulos de formación y evaluación modular.') }}
                                </p>
                            </div>

                            {{-- QR and Stamp Badge --}}
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-xs">
                                <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 shadow-inner flex items-center justify-center mb-1.5">
                                    <i class="bi bi-qr-code text-2xl sm:text-3xl text-slate-800"></i>
                                </div>
                                <span class="text-[10px] font-bold text-slate-700">Verificación Digital QR</span>
                                <span class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $certificate->certificate_code }}</span>
                                <span class="text-[9px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                                    <i class="bi bi-shield-check"></i> Sello Institucional
                                </span>
                            </div>
                        </div>

                        {{-- Beneficiary & Course Metadata Grid --}}
                        <div class="print-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Estudiante / Titular</span>
                                <p class="text-xs sm:text-sm font-extrabold text-slate-900 mt-0.5 uppercase leading-tight">{{ $certificate->user->names ?? 'No especificado' }}</p>
                                <span class="text-[11px] text-slate-500 mt-0.5 block font-mono">DNI: {{ $certificate->user->dni ?? '—' }}</span>
                            </div>

                            <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Modalidad &amp; Horas</span>
                                <p class="text-xs sm:text-sm font-extrabold text-indigo-700 mt-0.5 flex items-center gap-1.5 leading-tight">
                                    <i class="bi {{ $certificate->modality === 'Virtual' ? 'bi-laptop' : ($certificate->modality === 'Semipresencial' ? 'bi-shuffle' : 'bi-building') }}"></i>
                                    {{ $certificate->modality }}
                                </p>
                                <span class="text-[11px] text-slate-500 mt-0.5 block">Duración: <strong>{{ $certificate->duration ?: '128 Horas' }}</strong></span>
                            </div>

                            <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Periodo de Ejecución</span>
                                <p class="text-[11px] sm:text-xs font-bold text-slate-800 mt-0.5 leading-tight">
                                    {{ $certificate->start_date ? \Carbon\Carbon::parse($certificate->start_date)->format('d/m/Y') : '—' }}
                                    al
                                    {{ $certificate->end_date ? \Carbon\Carbon::parse($certificate->end_date)->format('d/m/Y') : '—' }}
                                </p>
                                <span class="text-[10px] text-slate-500 mt-0.5 block">Emisión: <strong>{{ $certificate->issue_date ? \Carbon\Carbon::parse($certificate->issue_date)->format('d/m/Y') : '—' }}</strong></span>
                            </div>

                            <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Institución Emisora</span>
                                <p class="text-[11px] sm:text-xs font-bold text-slate-800 mt-0.5 leading-tight">{{ $enterprise->trade_name ?? 'IESTP Francisco Vigo Caballero' }}</p>
                                <span class="text-[10px] text-slate-500 mt-0.5 block">Uchiza, San Martín</span>
                            </div>
                        </div>

                        {{-- Modular Evaluation Details Table --}}
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-journal-check text-indigo-600"></i>
                                    Detalle de Calificaciones Modulares
                                </h4>
                                <span class="text-[11px] text-slate-500">Escala vigesimal (0 a 20)</span>
                            </div>

                            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs bg-white">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] sm:text-[11px] tracking-wider">
                                            <th class="px-3.5 py-2.5 text-center w-10">#</th>
                                            <th class="px-3.5 py-2.5">Módulo / Unidad de Competencia</th>
                                            <th class="px-3.5 py-2.5 text-center">Créditos</th>
                                            <th class="px-3.5 py-2.5 text-center">Calificación</th>
                                            <th class="px-3.5 py-2.5 text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                        @php
                                            $totalScore = 0;
                                            $scoreCount = 0;
                                        @endphp
                                        @forelse($certificate->details as $index => $detail)
                                            @php
                                                $numericScore = is_numeric($detail->score) ? (float)$detail->score : null;
                                                if ($numericScore !== null) {
                                                    $totalScore += $numericScore;
                                                    $scoreCount++;
                                                }
                                            @endphp
                                            <tr class="hover:bg-slate-50/80 transition-colors">
                                                <td class="px-3.5 py-2.5 text-center font-mono font-bold text-slate-400">{{ $index + 1 }}</td>
                                                <td class="px-3.5 py-2.5 font-semibold text-slate-900">
                                                    {{ $detail->module->name ?? ('Módulo ' . ($index + 1)) }}
                                                </td>
                                                <td class="px-3.5 py-2.5 text-center font-mono text-slate-600">
                                                    {{ $detail->module->credits ?? 3 }}
                                                </td>
                                                <td class="px-3.5 py-2.5 text-center">
                                                    <span class="inline-flex items-center justify-center font-mono font-bold px-2 py-0.5 rounded {{ ($numericScore >= 13 || $detail->score >= 13) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                                        {{ $detail->score }}
                                                    </span>
                                                </td>
                                                <td class="px-3.5 py-2.5 text-center">
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ ($numericScore >= 13 || $detail->score >= 13) ? 'text-emerald-700' : 'text-red-600' }}">
                                                        <i class="bi bi-check-circle-fill"></i> Aprobado
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-5 text-center text-slate-400 italic">
                                                    No se registran detalles individuales de notas para este certificado.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($scoreCount > 0)
                                        <tfoot>
                                            <tr class="bg-indigo-50/50 border-t-2 border-indigo-100 font-bold text-slate-900">
                                                <td colspan="3" class="px-3.5 py-2.5 text-right uppercase text-[10px] sm:text-xs tracking-wider text-indigo-900">
                                                    Promedio Modular Ponderado:
                                                </td>
                                                <td class="px-3.5 py-2.5 text-center font-mono font-black text-indigo-950 text-sm">
                                                    {{ number_format($totalScore / $scoreCount, 2) }}
                                                </td>
                                                <td class="px-3.5 py-2.5 text-center text-[11px] font-extrabold text-emerald-700">
                                                    APROBADO
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        {{-- Official Print Footer Notes (Visible in Print) --}}
                        <div class="print-only border-t border-slate-200 pt-3 text-[8.5pt] text-slate-500 space-y-1">
                            <div class="flex justify-between items-center">
                                <span><strong>Enlace Permanente de Verificación:</strong> {{ url('/validar-certificado/' . $certificate->certificate_code) }}</span>
                                <span><strong>Fecha y Hora de Consulta:</strong> {{ now()->format('d/m/Y H:i:s') }}</span>
                            </div>
                            <p class="text-[8pt] text-slate-400 italic">
                                * Constancia de verificación emitida electrónicamente por el IESTP Francisco Vigo Caballero de conformidad con la Ley General de Educación N° 28044 y normas del MINEDU.
                            </p>
                        </div>

                        {{-- Action Buttons (No-print) --}}
                        <div class="no-print flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    onclick="window.print()" 
                                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2">
                                    <i class="bi bi-printer-fill"></i>
                                    <span>Imprimir Constancia</span>
                                </button>
                                <button 
                                    type="button" 
                                    @click="copyValidationLink()" 
                                    class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-all inline-flex items-center gap-2">
                                    <i class="bi" :class="copiedLink ? 'bi-check2 text-emerald-600' : 'bi-link-45deg'"></i>
                                    <span x-text="copiedLink ? '¡Enlace Copiado!' : 'Copiar Enlace Directo'"></span>
                                </button>
                            </div>
                            <a 
                                href="{{ route('validar-certificado') }}" 
                                class="text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
                                <i class="bi bi-arrow-repeat"></i> Validar otro documento
                            </a>
                        </div>

                    </div>
                </div>
            @else
                {{-- Not Found Card --}}
                <div class="bg-white rounded-3xl shadow-xl border border-red-200 p-8 text-center space-y-4 animate-fade-in">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-red-50 text-red-500 border border-red-100 flex items-center justify-center text-3xl shadow-inner">
                        <i class="bi bi-shield-x"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-black text-slate-900 font-display">Certificado No Encontrado</h3>
                        <p class="text-sm text-slate-600 max-w-lg mx-auto">
                            No se encontró ningún certificado registrado bajo el código o DNI <strong class="font-mono text-red-600">"{{ $searchCode }}"</strong>.
                        </p>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4 text-xs max-w-xl mx-auto text-left space-y-1">
                        <p class="font-bold flex items-center gap-1.5 text-amber-900">
                            <i class="bi bi-lightbulb-fill text-amber-600"></i> Sugerencias para la búsqueda:
                        </p>
                        <ul class="list-disc list-inside space-y-0.5 text-amber-800/90 pl-1">
                            <li>Verifique que el código incluya el prefijo <code class="bg-amber-100/80 px-1 py-0.5 rounded font-mono">CERT-</code> (ej: <code class="font-mono">CERT-00000011-1</code>).</li>
                            <li>Asegúrese de ingresar el número de DNI completo de 8 dígitos.</li>
                            <li>Si escaneó un código QR, intente volver a enfocar la cámara.</li>
                        </ul>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('validar-certificado') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition-all">
                            <i class="bi bi-arrow-counterclockwise"></i> Nueva Búsqueda
                        </a>
                    </div>
                </div>
            @endif

        </div>
    @endif

    {{-- ═══ SECTION: SUMMARY TABLES BY YEAR, PERIODS, & STUDY PROGRAMS (HIDDEN IN PRINT) ════ --}}
    <section class="summary-tables-section no-print max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8">
        {{-- Section Title --}}
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                <i class="bi bi-table"></i>
                Consolidado Oficial de Certificaciones
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-display">
                Resumen Estadístico Institucional
            </h2>
            <p class="text-sm sm:text-base text-slate-600">
                Resúmenes tabulares oficiales de certificados emitidos, consolidados y agrupados por <strong>año de emisión</strong>, <strong>periodos académicos</strong> y <strong>programas de estudio</strong>.
            </p>
        </div>
        {{-- KPI Stat Chips Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Certificados</p>
                    <h3 class="text-2xl font-black text-slate-900 font-display">{{ number_format($totalCertificates) }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Certificados Válidos</p>
                    <h3 class="text-2xl font-black text-emerald-700 font-display">{{ number_format($validCertificates) }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Estudiantes Acreditados</p>
                    <h3 class="text-2xl font-black text-blue-700 font-display">{{ number_format($totalStudents) }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl shrink-0">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cursos &amp; Programas</p>
                    <h3 class="text-2xl font-black text-indigo-700 font-display">{{ number_format($totalCourses) }}</h3>
                </div>
            </div>
        </div>

        {{-- Tabbed Summary Tables Container --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Tab Navigation Bar --}}
            <div class="bg-slate-50 border-b border-slate-200 px-4 sm:px-6 pt-3 flex flex-wrap items-center gap-2">
                <button 
                    type="button" 
                    @click="activeTab = 'years'" 
                    :class="activeTab === 'years' ? 'border-indigo-600 text-indigo-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-3 rounded-t-xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-calendar-event"></i>
                    <span>1. Resumen por Años</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200/70 text-slate-700 font-mono">{{ $yearsSummary->count() }}</span>
                </button>

                <button 
                    type="button" 
                    @click="activeTab = 'periods'" 
                    :class="activeTab === 'periods' ? 'border-indigo-600 text-indigo-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-3 rounded-t-xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-calendar3-range"></i>
                    <span>2. Resumen por Periodos</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200/70 text-slate-700 font-mono">{{ $periodsSummary->count() }}</span>
                </button>

                <button 
                    type="button" 
                    @click="activeTab = 'programs'" 
                    :class="activeTab === 'programs' ? 'border-indigo-600 text-indigo-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-4 py-3 rounded-t-xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-mortarboard"></i>
                    <span>3. Resumen por Programas &amp; Cursos</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200/70 text-slate-700 font-mono">{{ $coursesSummary->count() }}</span>
                </button>
            </div>

            {{-- Tab 1: Resumen por Años --}}
            <div x-show="activeTab === 'years'" class="p-6 sm:p-8 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Resumen Consolidado de Certificados por Año</h3>
                        <p class="text-xs text-slate-500">Distribución de certificaciones emitidas agrupadas por año de emisión y modalidad académica.</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden overflow-x-auto shadow-xs">
                    <table class="min-w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                <th class="px-4 py-3.5 text-center">Año</th>
                                <th class="px-4 py-3.5 text-center">Total Certificados</th>
                                <th class="px-4 py-3.5 text-center">Presencial</th>
                                <th class="px-4 py-3.5 text-center">Semipresencial</th>
                                <th class="px-4 py-3.5 text-center">Virtual</th>
                                <th class="px-4 py-3.5 text-center">Estudiantes Únicos</th>
                                <th class="px-4 py-3.5 text-center">Certificados Válidos</th>
                                <th class="px-4 py-3.5 text-center">% Conformidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @php
                                $sumYearsTotal = 0;
                                $sumYearsPresencial = 0;
                                $sumYearsSemi = 0;
                                $sumYearsVirtual = 0;
                                $sumYearsStudents = 0;
                                $sumYearsActive = 0;
                            @endphp
                            @forelse($yearsSummary as $row)
                                @php
                                    $sumYearsTotal += $row->total_certificates;
                                    $sumYearsPresencial += $row->presencial_count;
                                    $sumYearsSemi += $row->semipresencial_count;
                                    $sumYearsVirtual += $row->virtual_count;
                                    $sumYearsStudents += $row->total_students;
                                    $sumYearsActive += $row->active_count;
                                    $conformity = $row->total_certificates > 0 ? round(($row->active_count / $row->total_certificates) * 100, 1) : 100;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 text-center font-mono font-bold text-indigo-900 bg-indigo-50/30">
                                        {{ $row->year }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-900 font-mono">
                                        {{ number_format($row->total_certificates) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-600">
                                        {{ number_format($row->presencial_count) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-600">
                                        {{ number_format($row->semipresencial_count) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-600">
                                        {{ number_format($row->virtual_count) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-semibold text-blue-700">
                                        {{ number_format($row->total_students) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-semibold text-emerald-700">
                                        {{ number_format($row->active_count) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <i class="bi bi-check-circle-fill text-[10px]"></i> {{ $conformity }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">
                                        No hay registros de certificados para agrupar por año.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($yearsSummary->isNotEmpty())
                            <tfoot>
                                <tr class="bg-slate-100/80 border-t-2 border-slate-300 font-bold text-slate-900">
                                    <td class="px-4 py-3 text-center uppercase text-xs tracking-wider text-slate-700">
                                        TOTAL CONSOLIDADO
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-black text-indigo-950">
                                        {{ number_format($sumYearsTotal) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-700">
                                        {{ number_format($sumYearsPresencial) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-700">
                                        {{ number_format($sumYearsSemi) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-700">
                                        {{ number_format($sumYearsVirtual) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold text-blue-800">
                                        {{ number_format($sumYearsStudents) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold text-emerald-800">
                                        {{ number_format($sumYearsActive) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-emerald-800">
                                        100.0%
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Tab 2: Resumen por Periodos Académicos --}}
            <div x-show="activeTab === 'periods'" style="display:none;" class="p-6 sm:p-8 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Resumen Consolidado por Periodo Académico</h3>
                        <p class="text-xs text-slate-500">Agrupación semestral de certificados (Periodo I: Enero–Junio, Periodo II: Julio–Diciembre).</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden overflow-x-auto shadow-xs">
                    <table class="min-w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                <th class="px-4 py-3.5 text-center">Periodo</th>
                                <th class="px-4 py-3.5 text-center">Cursos Dictados</th>
                                <th class="px-4 py-3.5 text-center">Estudiantes Registrados</th>
                                <th class="px-4 py-3.5 text-center">Modalidad Predominante</th>
                                <th class="px-4 py-3.5 text-center">Total Certificados</th>
                                <th class="px-4 py-3.5 text-center">Certificados Activos</th>
                                <th class="px-4 py-3.5 text-center">Estado del Periodo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @php
                                $sumPeriodsTotal = 0;
                                $sumPeriodsStudents = 0;
                                $sumPeriodsActive = 0;
                            @endphp
                            @forelse($periodsSummary as $row)
                                @php
                                    $sumPeriodsTotal += $row->total_certificates;
                                    $sumPeriodsStudents += $row->total_students;
                                    $sumPeriodsActive += $row->active_count;
                                    $predominant = $row->virtual_count >= $row->presencial_count ? 'Virtual' : 'Presencial';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 text-center font-mono font-bold text-indigo-900 bg-indigo-50/30">
                                        {{ $row->period }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-600">
                                        {{ $row->total_courses }} Curso(s)
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-semibold text-blue-700">
                                        {{ number_format($row->total_students) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center gap-1 font-semibold text-slate-700 text-xs">
                                            <i class="bi {{ $predominant === 'Virtual' ? 'bi-laptop text-indigo-600' : 'bi-building text-blue-600' }}"></i>
                                            {{ $predominant }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-900 font-mono">
                                        {{ number_format($row->total_certificates) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-semibold text-emerald-700">
                                        {{ number_format($row->active_count) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <i class="bi bi-check2"></i> Concluido &amp; Auditado
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">
                                        No hay registros de certificados para agrupar por periodo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($periodsSummary->isNotEmpty())
                            <tfoot>
                                <tr class="bg-slate-100/80 border-t-2 border-slate-300 font-bold text-slate-900">
                                    <td class="px-4 py-3 text-center uppercase text-xs tracking-wider text-slate-700">
                                        TOTAL CONSOLIDADO
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-700">
                                        {{ $totalCourses }} Cursos
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold text-blue-800">
                                        {{ number_format($sumPeriodsStudents) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-500 font-normal">
                                        —
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-black text-indigo-950">
                                        {{ number_format($sumPeriodsTotal) }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono font-bold text-emerald-800">
                                        {{ number_format($sumPeriodsActive) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-emerald-800 font-bold">
                                        100% Válidos
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Tab 3: Resumen por Programas de Estudio y Cursos --}}
            <div x-show="activeTab === 'programs'" style="display:none;" class="p-6 sm:p-8 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Resumen Consolidado por Cursos y Programas de Estudio</h3>
                        <p class="text-xs text-slate-500">Métricas de desempeño académico, módulos evaluados y total de beneficiarios por curso.</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden overflow-x-auto shadow-xs">
                    <table class="min-w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                <th class="px-4 py-3.5">Curso / Programa Vinculado</th>
                                <th class="px-4 py-3.5 text-center">Módulos</th>
                                <th class="px-4 py-3.5 text-center">Total Certificados</th>
                                <th class="px-4 py-3.5 text-center">Estudiantes</th>
                                <th class="px-4 py-3.5 text-center">Promedio Notas</th>
                                <th class="px-4 py-3.5 text-center">Rango (Mín / Máx)</th>
                                <th class="px-4 py-3.5 text-center">Certificados Activos</th>
                                <th class="px-4 py-3.5 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($coursesSummary as $row)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3.5">
                                        <span class="font-bold text-slate-900 block font-display">{{ $row->course_name }}</span>
                                        <span class="text-xs text-slate-500 block line-clamp-1">{{ $row->course_description }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono text-slate-600">
                                        {{ $row->modules_count ?: 2 }} Módulos
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-bold text-slate-900 font-mono">
                                        {{ number_format($row->total_certificates) }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold text-blue-700">
                                        {{ number_format($row->total_students) }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex items-center justify-center font-mono font-bold px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-800 border border-indigo-100">
                                            {{ number_format($row->avg_score, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono text-xs text-slate-500">
                                        {{ number_format($row->min_score, 0) }} - {{ number_format($row->max_score, 0) }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-mono font-semibold text-emerald-700">
                                        {{ number_format($row->active_count) }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <i class="bi bi-patch-check-fill text-emerald-600"></i> Activo
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">
                                        No hay registros de cursos o programas vinculados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Study Programs Reference Grid --}}
                <div class="pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                        Programas de Estudio del Instituto con Certificación Modular:
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($studyPrograms as $program)
                            <div class="p-3 bg-slate-50/80 border border-slate-200 rounded-xl flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $program->icon_bg_class ?: 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center text-sm shrink-0">
                                    <i class="bi {{ $program->icon ?: 'bi-mortarboard' }}"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $program->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">Acreditación y Licenciamiento FVC</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

        {{-- Institutional Legal & Security Notice Card --}}
        <div class="bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-bold">
                        <i class="bi bi-shield-lock-fill"></i> Seguridad Institucional Garantizada
                    </div>
                    <h3 class="text-xl font-bold text-white font-display">
                        Validez Oficial de Certificados Digitales
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Los certificados emitidos por el <strong class="text-white">{{ $enterprise->company_name ?? 'IESTP Francisco Vigo Caballero' }}</strong> cuentan con firma digital, correlativo único institucional y registro en los sistemas académicos. Cualquier consulta adicional puede ser canalizada a través de la Mesa de Partes Virtual.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full md:w-auto">
                    <a href="{{ route('mesa-de-partes') }}" class="px-5 py-3 bg-white text-slate-900 hover:bg-slate-100 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition-all text-center">
                        <i class="bi bi-inbox-fill mr-1.5 text-blue-600"></i> Mesa de Partes
                    </a>
                    <a href="{{ route('bolsa-de-trabajo') }}" class="px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs sm:text-sm font-bold transition-all text-center">
                        <i class="bi bi-briefcase-fill mr-1.5 text-emerald-400"></i> Bolsa de Trabajo
                    </a>
                </div>
            </div>
        </div>

    </section>

</div>
@endsection
