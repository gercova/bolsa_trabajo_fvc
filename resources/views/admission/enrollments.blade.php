@extends('layouts.app')
@section('title', 'Matrículas — IESTP Francisco Vigo Caballero')
@push('styles')
    {{-- SEO Optimization Meta Tags --}}
    <meta name="description"
        content="Información oficial sobre el proceso de matrícula ordinaria y extraordinaria del IESTP Francisco Vigo Caballero. Consulta calendarios, requisitos de admisión y cronogramas académicos por áreas.">
    <meta name="keywords"
        content="matricula, fvc, instituto francisco vigo caballero, matriculas 2026, cronograma de matricula, matricula ordinaria, matricula extraordinaria, educacion superior tecnica">
    <meta name="robots" content="index, follow">
    <style>
        [x-cloak] { display: none !important; }

        /* ===== TYPE TAB PILLS ===== */
        .enroll-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.25s ease;
            border: 2px solid transparent;
            letter-spacing: 0.02em;
        }
        .enroll-tab-ordinaria {
            background: #e0f2fe;
            color: #0369a1;
            border-color: #bae6fd;
        }
        .enroll-tab-ordinaria.active, .enroll-tab-ordinaria:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 4px 20px rgba(2,132,199,0.3);
        }
        .enroll-tab-extraordinaria {
            background: #fdf4ff;
            color: #7e22ce;
            border-color: #e9d5ff;
        }
        .enroll-tab-extraordinaria.active, .enroll-tab-extraordinaria:hover {
            background: #7c3aed;
            color: #ffffff;
            border-color: #7c3aed;
            box-shadow: 0 4px 20px rgba(124,58,237,0.3);
        }

        /* ===== STEP CARDS ===== */
        .step-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .step-card::after {
            content: '';
            position: absolute;
            top: 28px;
            left: 28px;
            width: 2px;
            height: calc(100% + 24px);
            background: linear-gradient(to bottom, #bfdbfe, transparent);
        }
        .step-card:last-child::after { display: none; }

        /* ===== SCHEDULE CARD ===== */
        .schedule-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .schedule-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.10);
        }

        /* ===== DISCOUNT BADGE ===== */
        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
        }

        /* ===== STATUS BADGE ===== */
        @keyframes pulse-open {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .badge-open { animation: pulse-open 1.5s ease-in-out infinite; }
    </style>
@endpush

@section('content')

    {{-- ═══ HERO ═══ --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 text-white overflow-hidden py-24 lg:py-36">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-500/25 via-transparent to-transparent pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-purple-600/15 via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
            <div class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-400/25 text-blue-300 text-sm font-bold px-5 py-2 rounded-full">
                <i class="bi bi-mortarboard-fill"></i>
                Proceso Oficial de Matrícula Académica
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                Matrícula <span class="text-blue-300">Ordinaria</span> y
                <span class="text-purple-300">Extraordinaria</span>
            </h1>
            <p class="text-lg sm:text-xl text-blue-100/80 max-w-3xl mx-auto leading-relaxed font-medium">
                Accede a los cronogramas oficiales, costos reglamentarios y guías paso a paso del proceso de matrícula para el período vigente.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                <a href="#tipos-matricula" id="btn-hero-tipos"
                    class="inline-flex items-center justify-center px-8 py-4 text-base font-black text-blue-950 bg-white hover:bg-blue-50 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="bi bi-list-check mr-2.5 text-xl text-blue-600"></i>
                    Ver Tipos de Matrícula
                </a>
                <a href="#cronogramas" id="btn-hero-cronograma"
                    class="inline-flex items-center justify-center px-8 py-4 text-base font-black text-white border-2 border-blue-400/30 hover:bg-white/10 rounded-xl transition-all">
                    <i class="bi bi-calendar3 mr-2.5 text-xl"></i>
                    Cronogramas Vigentes
                </a>
            </div>
        </div>
    </section>

    {{-- ═══ TIPOS DE MATRÍCULA + STEPS ═══ --}}
    <section id="tipos-matricula" class="py-20 bg-white" x-data="{ activeType: 'ordinaria' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest">¿Qué tipo de matrícula necesitas?</span>
                <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Tipos de Matrícula</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-base text-gray-500 mt-5 leading-relaxed">
                    Selecciona el tipo de matrícula que corresponde a tu situación y sigue los pasos indicados.
                </p>
            </div>

            {{-- Type Selector Pills --}}
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-14">
                <button @click="activeType = 'ordinaria'"
                    :class="activeType === 'ordinaria' ? 'active' : ''"
                    class="enroll-tab enroll-tab-ordinaria" id="tab-ordinaria">
                    <i class="bi bi-calendar2-check-fill text-xl"></i>
                    Matrícula Ordinaria
                </button>
                <button @click="activeType = 'extraordinaria'"
                    :class="activeType === 'extraordinaria' ? 'active' : ''"
                    class="enroll-tab enroll-tab-extraordinaria" id="tab-extraordinaria">
                    <i class="bi bi-calendar2-x-fill text-xl"></i>
                    Matrícula Extraordinaria
                </button>
            </div>

            {{-- ── ORDINARIA PANEL ── --}}
            <div x-show="activeType === 'ordinaria'" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">

                <div class="max-w-5xl mx-auto">
                    <div class="bg-gradient-to-br from-blue-50 to-sky-50 border border-blue-100 rounded-3xl p-8 sm:p-10 mb-10 shadow-sm">
                        <div class="flex items-start gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md">
                                <i class="bi bi-calendar2-check-fill text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-blue-950">Matrícula Ordinaria</h3>
                                <p class="text-blue-600 font-semibold mt-1">Dentro del período regular establecido por el calendario académico.</p>
                            </div>
                        </div>

                        {{-- Steps --}}
                        <div class="space-y-8 pl-4">
                            {{-- Step 1 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md z-10">01</div>
                                <div class="bg-white rounded-2xl border border-blue-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-blue-950 mb-2 flex items-center gap-2">
                                        <i class="bi bi-bank text-blue-600"></i>
                                        Pago del Derecho de Matrícula
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        Efectúa el pago del derecho de matrícula en la <strong class="text-blue-800">Oficina de Administración</strong> del instituto.
                                        El costo está establecido según el TUPA institucional vigente.
                                    </p>
                                    @php
                                        $ordFee = $schedules->where('enrollment_type', 'ordinaria')->first()?->enrollment_fee;
                                    @endphp
                                    @if ($ordFee && $ordFee > 0)
                                        <div class="mt-4 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-extrabold px-4 py-2 rounded-xl">
                                            <i class="bi bi-cash-coin text-lg"></i>
                                            Costo: S/ {{ number_format($ordFee, 2) }}
                                        </div>
                                    @else
                                        <div class="mt-4 inline-flex items-center gap-2 bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            <i class="bi bi-info-circle"></i>
                                            Consulta el costo en Administración
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Step 2 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md z-10">02</div>
                                <div class="bg-white rounded-2xl border border-blue-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-blue-950 mb-2 flex items-center gap-2">
                                        <i class="bi bi-receipt text-blue-600"></i>
                                        Presentar Voucher a Secretaría Académica
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        Una vez realizado el pago, presenta el <strong class="text-blue-800">voucher o comprobante de pago</strong> en la
                                        <strong class="text-blue-800">Secretaría Académica</strong> para formalizar tu registro de matrícula.
                                    </p>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                            <i class="bi bi-file-earmark-text mr-1"></i> Voucher original
                                        </span>
                                        <span class="bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                            <i class="bi bi-person-badge mr-1"></i> DNI vigente
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-emerald-500 text-white font-black text-lg flex items-center justify-center shadow-md z-10">
                                    <i class="bi bi-check-lg text-2xl"></i>
                                </div>
                                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-emerald-800 mb-2 flex items-center gap-2">
                                        <i class="bi bi-patch-check-fill text-emerald-600"></i>
                                        ¡Matrícula Completada!
                                    </h4>
                                    <p class="text-emerald-700 leading-relaxed">
                                        Una vez validado el voucher, tu matrícula queda registrada oficialmente. Recibirás
                                        tu constancia de matrícula firmada por la Secretaría Académica.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── EXTRAORDINARIA PANEL ── --}}
            <div x-show="activeType === 'extraordinaria'" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">

                <div class="max-w-5xl mx-auto">
                    <div class="bg-gradient-to-br from-purple-50 to-violet-50 border border-purple-100 rounded-3xl p-8 sm:p-10 mb-10 shadow-sm">
                        <div class="flex items-start gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-violet-700 text-white flex items-center justify-center shrink-0 shadow-md">
                                <i class="bi bi-calendar2-x-fill text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-violet-950">Matrícula Extraordinaria</h3>
                                <p class="text-violet-600 font-semibold mt-1">Fuera del período regular. Requiere presentación de FUT y documentación adicional.</p>
                            </div>
                        </div>

                        {{-- Discount Alerts --}}
                        <div class="mb-8 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-4">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center">
                                <i class="bi bi-star-fill text-base"></i>
                            </div>
                            <div>
                                <h5 class="font-extrabold text-amber-900 mb-2">Descuentos Especiales Aplicables</h5>
                                <div class="flex flex-wrap gap-2">
                                    <span class="discount-badge bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="bi bi-trophy-fill text-amber-600"></i>
                                        Primer puesto de admisión: <strong class="ml-1">100% de descuento</strong>
                                    </span>
                                    <span class="discount-badge bg-slate-100 text-slate-700 border border-slate-200">
                                        <i class="bi bi-shield-fill text-slate-500"></i>
                                        Miembros de las Fuerzas Armadas: <strong class="ml-1">50% de descuento</strong>
                                    </span>
                                </div>
                                <p class="text-xs text-amber-700 mt-2 font-medium">Los descuentos aplican sobre el derecho de matrícula y requieren documentación sustentatoria.</p>
                            </div>
                        </div>

                        {{-- Steps --}}
                        <div class="space-y-8 pl-4">
                            {{-- Step 1 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-violet-700 text-white font-black text-lg flex items-center justify-center shadow-md z-10">01</div>
                                <div class="bg-white rounded-2xl border border-purple-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-violet-950 mb-2 flex items-center gap-2">
                                        <i class="bi bi-bank text-violet-600"></i>
                                        Pago del Derecho de Matrícula
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        Efectúa el pago del derecho de matrícula extraordinaria en la
                                        <strong class="text-violet-800">Oficina de Administración</strong>.
                                        Si eres primer puesto o miembro de las FFAA, presenta la documentación
                                        correspondiente para aplicar el descuento antes del pago.
                                    </p>
                                    @php
                                        $extFee = $schedules->where('enrollment_type', 'extraordinaria')->first()?->enrollment_fee;
                                    @endphp
                                    @if ($extFee && $extFee > 0)
                                        <div class="mt-4 inline-flex items-center gap-2 bg-violet-700 text-white text-sm font-extrabold px-4 py-2 rounded-xl">
                                            <i class="bi bi-cash-coin text-lg"></i>
                                            Costo: S/ {{ number_format($extFee, 2) }}
                                        </div>
                                    @else
                                        <div class="mt-4 inline-flex items-center gap-2 bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            <i class="bi bi-info-circle"></i>
                                            Consulta el costo en Administración
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Step 2 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-violet-700 text-white font-black text-lg flex items-center justify-center shadow-md z-10">02</div>
                                <div class="bg-white rounded-2xl border border-purple-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-violet-950 mb-2 flex items-center gap-2">
                                        <i class="bi bi-file-earmark-medical text-violet-600"></i>
                                        Presentar FUT a la Oficina de Administración
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        Llena y presenta el formulario <strong class="text-violet-800">FUT (Formulario Único de Trámite)</strong> en la
                                        Oficina de Administración, adjuntando según corresponda:
                                    </p>
                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <div class="bg-purple-50 border border-purple-100 rounded-xl p-3 flex items-start gap-2.5">
                                            <i class="bi bi-shield-fill text-violet-500 mt-0.5 shrink-0"></i>
                                            <span class="text-sm text-violet-800 font-semibold">Libreta militar o carnet de identidad militar (Fuerzas Armadas)</span>
                                        </div>
                                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 flex items-start gap-2.5">
                                            <i class="bi bi-trophy-fill text-amber-500 mt-0.5 shrink-0"></i>
                                            <span class="text-sm text-amber-800 font-semibold">Resolución de reconocimiento como primer puesto de admisión</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-violet-700 text-white font-black text-lg flex items-center justify-center shadow-md z-10">03</div>
                                <div class="bg-white rounded-2xl border border-purple-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-violet-950 mb-2 flex items-center gap-2">
                                        <i class="bi bi-receipt text-violet-600"></i>
                                        Presentar Voucher a Secretaría Académica
                                    </h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        Una vez procesado el FUT, presenta el <strong class="text-violet-800">voucher de pago</strong> en la
                                        <strong class="text-violet-800">Secretaría Académica</strong> para completar el registro de matrícula.
                                    </p>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="bg-purple-50 border border-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                            <i class="bi bi-file-earmark-text mr-1"></i> Voucher de pago
                                        </span>
                                        <span class="bg-purple-50 border border-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                            <i class="bi bi-file-earmark-check mr-1"></i> Copia del FUT aprobado
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 4 --}}
                            <div class="flex gap-5 step-card">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-emerald-500 text-white font-black text-lg flex items-center justify-center shadow-md z-10">
                                    <i class="bi bi-check-lg text-2xl"></i>
                                </div>
                                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-6 flex-1 shadow-sm">
                                    <h4 class="text-lg font-extrabold text-emerald-800 mb-2 flex items-center gap-2">
                                        <i class="bi bi-patch-check-fill text-emerald-600"></i>
                                        ¡Matrícula Completada!
                                    </h4>
                                    <p class="text-emerald-700 leading-relaxed">
                                        Con la validación del voucher y el FUT aprobado, tu matrícula extraordinaria queda
                                        registrada oficialmente. Recibirás la constancia de matrícula por Secretaría Académica.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ CRONOGRAMAS VIGENTES ═══ --}}
    <section id="cronogramas" class="py-20 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest">Calendarios Académicos</span>
                <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Cronogramas de Matrícula</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-base text-gray-500 mt-5 leading-relaxed">
                    Cronogramas oficiales vigentes establecidos por la Secretaría Académica para el período en curso.
                </p>
            </div>

            @if ($schedules->count() > 0)
                {{-- New enrollment_schedules records --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
                    @foreach ($schedules as $schedule)
                        @php
                            $isOrdinaria = $schedule->enrollment_type === 'ordinaria';
                            $accentColor   = $isOrdinaria ? 'blue'   : 'violet';
                            $bgGrad        = $isOrdinaria ? 'from-blue-800 to-blue-700'     : 'from-violet-800 to-violet-700';
                            $badgeBg       = $isOrdinaria ? 'bg-blue-900/60 text-blue-200'  : 'bg-violet-900/60 text-violet-200';
                            $cardBorder    = $isOrdinaria ? 'border-blue-100'                : 'border-violet-100';
                            $iconColor     = $isOrdinaria ? 'text-blue-400'                  : 'text-violet-400';
                            $dateCardBg    = $isOrdinaria ? 'bg-blue-50/60 border-blue-100'  : 'bg-violet-50/60 border-violet-100';
                            $dateLabel     = $isOrdinaria ? 'text-blue-800'                  : 'text-violet-800';
                            $dateIcon      = $isOrdinaria ? 'text-blue-600'                  : 'text-violet-600';
                        @endphp
                        <div class="schedule-card bg-white rounded-3xl border {{ $cardBorder }} shadow-md overflow-hidden">
                            {{-- Card Header --}}
                            <div class="bg-gradient-to-r {{ $bgGrad }} px-7 py-5 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="{{ $badgeBg }} text-sm font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                                        {{ $schedule->type_label }}
                                    </span>
                                    <span class="text-white/80 text-sm font-bold">{{ $schedule->academic_period }}</span>
                                </div>
                                @if ($schedule->is_open)
                                    <span class="flex items-center gap-1.5 bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-extrabold px-3 py-1 rounded-full self-start sm:self-auto">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 badge-open"></span>
                                        Abierto Ahora
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 bg-white/10 border border-white/20 text-white/60 text-xs font-extrabold px-3 py-1 rounded-full self-start sm:self-auto">
                                        <span class="w-2 h-2 rounded-full bg-white/40"></span>
                                        Próximo / Cerrado
                                    </span>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-7 space-y-5">
                                {{-- Dates --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="{{ $dateCardBg }} border p-4 rounded-2xl flex items-center gap-3">
                                        <i class="bi bi-calendar3 {{ $dateIcon }} text-2xl shrink-0"></i>
                                        <div>
                                            <span class="{{ $dateLabel }} text-xs font-extrabold uppercase tracking-wider block">Inicio</span>
                                            <span class="text-slate-800 font-extrabold text-base">{{ $schedule->start_date->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="{{ $dateCardBg }} border p-4 rounded-2xl flex items-center gap-3">
                                        <i class="bi bi-calendar3-range {{ $dateIcon }} text-2xl shrink-0"></i>
                                        <div>
                                            <span class="{{ $dateLabel }} text-xs font-extrabold uppercase tracking-wider block">Cierre</span>
                                            <span class="text-slate-800 font-extrabold text-base">{{ $schedule->end_date->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Fee --}}
                                @if ($schedule->enrollment_fee > 0)
                                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3">
                                        <i class="bi bi-cash-coin {{ $iconColor }} text-xl"></i>
                                        <div>
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Derecho de Matrícula</span>
                                            <span class="font-extrabold text-slate-800 text-lg">S/ {{ number_format($schedule->enrollment_fee, 2) }}</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Program Slots --}}
                                @if ($schedule->details->count() > 0)
                                    <div>
                                        <span class="text-slate-500 text-xs font-extrabold uppercase tracking-wider block mb-2">Cupos por Programa</span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($schedule->details as $detail)
                                                @if ($detail->program)
                                                    <span class="bg-slate-50 text-slate-700 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $isOrdinaria ? 'bg-blue-500' : 'bg-violet-500' }}"></span>
                                                        {{ $detail->program->name }}: <strong class="ml-1">{{ $detail->available_slots }}</strong>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Observations --}}
                                @if ($schedule->observations)
                                    <p class="text-sm text-slate-500 leading-relaxed bg-slate-50 border border-slate-100 rounded-xl p-3">
                                        <i class="bi bi-info-circle mr-1 text-slate-400"></i>
                                        {{ $schedule->observations }}
                                    </p>
                                @endif

                                {{-- WhatsApp CTA --}}
                                @if ($enterprise?->whatsapp_link)
                                    <a href="{{ $enterprise->whatsapp_link }}" target="_blank"
                                        class="inline-flex items-center justify-center gap-2 w-full py-3 bg-green-500 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition shadow">
                                        <i class="bi bi-whatsapp text-lg"></i>
                                        Consultar por WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            @elseif ($enrollments->count() > 0)
                {{-- Fallback: legacy admission records --}}
                <div class="relative border-l-4 border-blue-200 ml-4 md:ml-32 space-y-12">
                    @foreach ($enrollments as $item)
                        <div class="relative pl-8 md:pl-12 group">
                            <div class="absolute -left-[14px] top-1.5 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow group-hover:scale-110 transition-transform"></div>
                            <div class="bg-white rounded-2xl border border-blue-100 shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                <div class="bg-gradient-to-r from-blue-800 to-blue-700 px-6 py-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="bg-blue-900/60 text-blue-200 text-sm font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $item->type }}</span>
                                        @if ($item->period)
                                            <span class="text-blue-100 text-sm font-bold">Período: {{ $item->period }}</span>
                                        @endif
                                    </div>
                                    <span class="bg-emerald-500/25 border border-emerald-400/30 text-emerald-200 text-sm font-extrabold px-3 py-1 rounded-full flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Semanas de Registro
                                    </span>
                                </div>
                                <div class="p-6 sm:p-8 space-y-6">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                        <div>
                                            <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest block">Actividad</span>
                                            <h3 class="text-2xl font-black text-blue-950 leading-tight">{{ $item->activity }}</h3>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-4">
                                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center gap-3">
                                                <i class="bi bi-calendar3 text-blue-600 text-2xl"></i>
                                                <div>
                                                    <span class="text-sm font-bold text-blue-800 uppercase tracking-wider block">Inicio</span>
                                                    <span class="text-base font-extrabold text-gray-700">{{ \Carbon\Carbon::parse($item->inscription_start_date)->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center gap-3">
                                                <i class="bi bi-calendar3-range text-blue-600 text-2xl"></i>
                                                <div>
                                                    <span class="text-sm font-bold text-blue-800 uppercase tracking-wider block">Límite</span>
                                                    <span class="text-base font-extrabold text-gray-700">{{ \Carbon\Carbon::parse($item->inscription_end_date)->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                {{-- Empty state --}}
                <div class="bg-white rounded-3xl border border-blue-100 shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-calendar-x text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">No hay cronogramas activos</h3>
                    <p class="text-base text-gray-600 leading-relaxed mb-6">
                        Actualmente no contamos con cronogramas de matrícula activos para el período escolar vigente.
                        Ponte en contacto con Secretaría Académica para más información.
                    </p>
                    @if ($enterprise?->whatsapp_link)
                        <a href="{{ $enterprise->whatsapp_link }}" target="_blank" id="btn-cron-contact"
                            class="inline-flex items-center justify-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white text-base font-bold rounded-xl transition shadow">
                            <i class="bi bi-whatsapp mr-2 text-lg"></i>Consultar por WhatsApp
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- ═══ REQUISITOS DE MATRÍCULA ═══ --}}
    @if ($requirements->count() > 0)
    <section id="requirements" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest">Documentación</span>
                <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Requisitos de Matrícula</h2>
                <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                <p class="text-base text-gray-500 mt-5 leading-relaxed">
                    Prepara y presenta la documentación indicada para validar satisfactoriamente tu matrícula.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach ($requirements as $index => $req)
                    <div class="bg-blue-50/20 p-6 rounded-2xl border border-blue-100 flex gap-4 transition hover:bg-blue-50/60 hover:shadow-sm">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center">
                            {{ sprintf('%02d', $index + 1) }}
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-blue-950 mb-1">Requisito Obligatorio</h4>
                            <p class="text-gray-700 text-sm leading-relaxed font-semibold">{{ $req->requirement }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══ CTA CONTACTO ═══ --}}
    <section id="contact" class="py-20 bg-blue-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-stops))] from-blue-700/40 via-transparent to-transparent"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
            <h2 class="text-3xl sm:text-5xl font-black">¿Tienes dudas sobre tu matrícula?</h2>
            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed font-medium">
                Comunícate con nuestra Secretaría Académica para orientación sobre pagos,
                documentación o fechas del proceso de matrícula.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 pt-4">
                @if ($enterprise?->phone_number_1)
                    <a href="tel:{{ $enterprise->phone_number_1 }}" id="contact-tel-link"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-white text-blue-900 hover:bg-blue-50 rounded-xl transition shadow">
                        <i class="bi bi-telephone-fill mr-2 text-xl text-blue-600"></i>
                        Llamar: {{ $enterprise->phone_number_1 }}
                    </a>
                @endif
                @if ($enterprise?->whatsapp_link)
                    <a href="{{ $enterprise->whatsapp_link }}" target="_blank" id="contact-whats-link"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-green-500 hover:bg-green-600 text-white rounded-xl transition shadow">
                        <i class="bi bi-whatsapp mr-2 text-xl"></i>
                        WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </section>

@endsection
