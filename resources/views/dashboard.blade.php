@extends('layouts.app')
@section('title', 'Panel de Control Principal — IESTP Francisco Vigo Caballero')
@push('styles')
<style>
    [x-cloak] { display: none !important; }

    #main-content { padding-top: 64px !important; }
    footer { margin-top: 0 !important; }

    /* Custom aesthetic scrollbars */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.5); }

    /* Tabular numeric rendering */
    .stat-val { font-variant-numeric: tabular-nums; }

    /* Card hover elevation */
    .stat-card {
        transition: transform .25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow .25s cubic-bezier(0.16, 1, 0.3, 1), border-color .25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -10px rgba(99, 102, 241, 0.12);
    }

    /* Ambient pulse animation */
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.75)} }
    .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

    /* Glow blobs */
    .glow-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        pointer-events: none;
    }
</style>
@endpush
@section('content')
<div id="dashboard-container" class="flex w-full bg-slate-50 font-sans text-slate-900 min-h-[calc(100vh-64px)]"
    x-data="dashboardApp()">

    @include('admin.components.aside')

    {{-- ══ Main content area ══════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50/60 relative">

        {{-- ── Top Navigation Header ─────────────────────────── --}}
        <header class="bg-white/95 border-b border-slate-200/80 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md">
            <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()"
                            class="text-slate-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-xl transition-colors lg:hidden"
                            title="Alternar Menú">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight leading-tight">
                                Panel de Control
                            </h1>
                            <span class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-purple-100 text-purple-700 border border-purple-200">
                                <i class="bi bi-shield-check text-xs"></i> Admin
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium hidden sm:block">
                            <i class="bi bi-calendar3 mr-1 text-[11px]"></i>
                            {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-dot"></span>
                        <span>Servidor Conectado</span>
                    </div>

                    <a href="{{ route('inicio') }}" target="_blank"
                       class="inline-flex items-center gap-2 text-xs font-bold bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white px-4 py-2 rounded-xl transition shadow-md shadow-purple-500/20 hover:shadow-purple-500/35">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>Ver Portal Web</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- ── Main Dashboard Body ───────────────────────────── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden space-y-8 max-w-7xl mx-auto w-full">

            {{-- ═══ HERO BANNER: Bienvenida & Status Institucional ═══ --}}
            <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 rounded-3xl p-6 sm:p-8 lg:p-10 text-white shadow-2xl shadow-indigo-950/20 relative overflow-hidden border border-slate-800/80">
                {{-- Decorative glow orbs --}}
                <div class="glow-blob w-72 h-72 bg-purple-600/20 -top-20 -right-20"></div>
                <div class="glow-blob w-64 h-64 bg-indigo-600/20 -bottom-20 left-10"></div>
                <div class="glow-blob w-40 h-40 bg-sky-500/15 top-1/2 right-1/4"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-3 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-purple-200 text-xs font-bold tracking-wide backdrop-blur-md">
                            <i class="bi bi-mortarboard-fill text-amber-400"></i>
                            <span>IESTP Francisco Vigo Caballero • Uchiza</span>
                        </div>

                        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">
                            Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-300 via-sky-300 to-cyan-300">{{ explode(' ', Auth::user()->names ?? 'Administrador')[0] }}</span>
                        </h2>

                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
                            Supervisa y gestiona en tiempo real la admisión, programas de estudio, bolsa de empleo, carrusel de portada y transparencia institucional.
                        </p>

                        {{-- Quick action buttons inside hero --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-2">
                            <a href="{{ route('admin.carousel.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-images text-purple-300"></i>
                                <span>Nuevo Slide Carrusel</span>
                            </a>

                            <a href="{{ route('admin.exams.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-journal-plus text-amber-300"></i>
                                <span>Nuevo Examen Admisión</span>
                            </a>

                            <a href="{{ route('admin.blogs.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-newspaper text-cyan-300"></i>
                                <span>Publicar Noticia</span>
                            </a>

                            <a href="{{ route('admin.works.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-briefcase text-sky-300"></i>
                                <span>Nueva Oferta Laboral</span>
                            </a>
                        </div>
                    </div>

                    {{-- Counter & Identity Badge --}}
                    <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md flex-shrink-0">
                        <div class="flex items-center gap-2 text-right">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 pulse-dot"></span>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-300">Sistema 100% Operativo</span>
                        </div>

                        <div class="space-y-1 text-left sm:text-right lg:text-right">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Visitas del Portal Web</span>
                            <div class="flex items-center gap-1">
                                @foreach($visitDigits as $digit)
                                    <span class="inline-flex items-center justify-center w-6 h-8 rounded-lg bg-slate-900 text-purple-300 font-mono font-black text-base border border-purple-500/30 shadow-inner">
                                        {{ $digit }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 flex items-center gap-2">
                            <i class="bi bi-clock-history"></i>
                            <span>Último acceso: {{ now()->format('H:i') }} hrs</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ SECCIÓN 1: KPIs Principales (4 Columnas) ═══════════ --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <i class="bi bi-grid-fill text-purple-600"></i> Métricas Clave del Sistema
                    </h3>
                    <span class="text-xs text-slate-400 font-medium">Actualizado en tiempo real</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                    {{-- 1. Usuarios & Plana --}}
                    <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 rounded-bl-full transition-all group-hover:scale-110"></div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center shadow-sm text-xl group-hover:scale-110 transition-transform">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $usersActive === $usersTotal ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $usersActive }} activos
                                </span>
                            </div>
                            <div>
                                <p class="stat-val text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $usersTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Usuarios & Personal</p>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-purple-600 font-bold flex items-center gap-1">
                                <i class="bi bi-person-plus-fill"></i> +{{ $usersThisMonth }} este mes
                            </span>
                            <a href="{{ route('admin.users.index') }}" class="font-bold text-slate-400 hover:text-purple-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 2. Bolsa de Trabajo --}}
                    <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full transition-all group-hover:scale-110"></div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shadow-sm text-xl group-hover:scale-110 transition-transform">
                                    <i class="bi bi-briefcase-fill"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $jobOffersActive }} vigentes
                                </span>
                            </div>
                            <div>
                                <p class="stat-val text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $jobOffersTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Ofertas Laborales</p>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-blue-600 font-bold flex items-center gap-1">
                                <i class="bi bi-buildings"></i> {{ $partnersActive }} empresas
                            </span>
                            <a href="{{ route('admin.works.index') }}" class="font-bold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 3. Admisiones & Procesos --}}
                    <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full transition-all group-hover:scale-110"></div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shadow-sm text-xl group-hover:scale-110 transition-transform">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $admissionsActive }} activas
                                </span>
                            </div>
                            <div>
                                <p class="stat-val text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $admissionsTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Convocatorias Admisión</p>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-amber-600 font-bold flex items-center gap-1">
                                <i class="bi bi-calendar-check"></i> {{ $enrollmentSchedulesActive }} matrículas
                            </span>
                            <a href="{{ route('admin.exams.index') }}" class="font-bold text-slate-400 hover:text-amber-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 4. Reclamos & Quejas --}}
                    <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/5 rounded-bl-full transition-all group-hover:scale-110"></div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shadow-sm text-xl group-hover:scale-110 transition-transform">
                                    <i class="bi bi-bookmark-x-fill"></i>
                                </div>
                                @if($claimsThisMonth > 0)
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                        +{{ $claimsThisMonth }} este mes
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                        Sin pendientes
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="stat-val text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $claimsTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Libro de Reclamaciones</p>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-rose-600 font-bold flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $claimsPending }} por atender
                            </span>
                            <a href="{{ route('admin.claims.index') }}" class="font-bold text-slate-400 hover:text-rose-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            {{-- ═══ SECCIÓN 2: KPIs Secundarios (4 Columnas) ═══════════ --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Programas de Estudio --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                            <i class="bi bi-book-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $programsTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">Programas de Estudio</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.programs.index') }}" class="text-xs font-bold text-emerald-600 hover:bg-emerald-50 px-2.5 py-1.5 rounded-lg transition-colors" title="Ver Programas">
                        <i class="bi bi-arrow-right text-base"></i>
                    </a>
                </div>

                {{-- Carrusel Institucional --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-xl">
                            <i class="bi bi-images"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $carouselsTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">Carrusel Principal</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.carousel.index') }}" class="text-xs font-bold text-purple-600 hover:bg-purple-50 px-2.5 py-1.5 rounded-lg transition-colors" title="Gestionar Carrusel">
                        <i class="bi bi-arrow-right text-base"></i>
                    </a>
                </div>

                {{-- TUPA & Trámites --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $tupaTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">TUPA / Trámites</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.tupa.index') }}" class="text-xs font-bold text-indigo-600 hover:bg-indigo-50 px-2.5 py-1.5 rounded-lg transition-colors" title="Gestionar TUPA">
                        <i class="bi bi-arrow-right text-base"></i>
                    </a>
                </div>

                {{-- Noticias & Blog --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-cyan-50 border border-cyan-100 text-cyan-600 flex items-center justify-center text-xl">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $blogsTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">Noticias / Blogs</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.blogs.index') }}" class="text-xs font-bold text-cyan-600 hover:bg-cyan-50 px-2.5 py-1.5 rounded-lg transition-colors" title="Gestionar Blog">
                        <i class="bi bi-arrow-right text-base"></i>
                    </a>
                </div>
            </section>

            {{-- ═══ SECCIÓN 3: Distribución Demográfica + Actividad Multitab ═══ --}}
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Columna Izquierda: Demografía & Distribución de Roles --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-6">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-pie-chart-fill text-purple-600"></i> Distribución de Usuarios
                            </h4>
                            <span class="text-xs text-slate-400 font-medium">Por rol</span>
                        </div>
                        <p class="text-xs text-slate-500">Composición del personal y usuarios registrados en el sistema.</p>
                    </div>

                    @php
                        $roleColors = [
                            'Admin'          => ['bar' => 'bg-purple-600',  'bg' => 'bg-purple-50',  'text' => 'text-purple-700', 'border' => 'border-purple-200'],
                            'Docente'        => ['bar' => 'bg-blue-600',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700',   'border' => 'border-blue-200'],
                            'Administrativo' => ['bar' => 'bg-indigo-600',  'bg' => 'bg-indigo-50',  'text' => 'text-indigo-700', 'border' => 'border-indigo-200'],
                            'Estudiante'     => ['bar' => 'bg-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700','border' => 'border-emerald-200'],
                            'Egresado'       => ['bar' => 'bg-amber-600',   'bg' => 'bg-amber-50',   'text' => 'text-amber-700',  'border' => 'border-amber-200'],
                            'usuario'        => ['bar' => 'bg-slate-600',   'bg' => 'bg-slate-50',   'text' => 'text-slate-700',  'border' => 'border-slate-200'],
                        ];
                        $totalUsersCalc = $usersTotal ?: 1;
                    @endphp

                    <div class="space-y-4">
                        @foreach($usersRoles as $role => $count)
                            @php
                                $pct = round(($count / $totalUsersCalc) * 100);
                                $theme = $roleColors[$role] ?? ['bar' => 'bg-slate-600', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200'];
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700">{{ $role }}</span>
                                    <span class="font-black {{ $theme['text'] }}">{{ $count }} ({{ $pct }}%)</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $theme['bar'] }} transition-all duration-700" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach

                        @if(empty($usersRoles))
                            <p class="text-xs text-slate-400 text-center py-4">No hay datos de usuarios registrados.</p>
                        @endif
                    </div>

                    {{-- Indicadores Adicionales de Transparencia --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 grid grid-cols-2 gap-3 text-center">
                        <div class="border-r border-slate-200/60 pr-2">
                            <p class="text-xs text-slate-500 font-medium">Grados & Títulos</p>
                            <p class="text-lg font-black text-slate-900">{{ $degreeRecordsTotal }}</p>
                        </div>
                        <div class="pl-2">
                            <p class="text-xs text-slate-500 font-medium">Registros Alumnos</p>
                            <p class="text-lg font-black text-indigo-600">{{ $studentRecordsTotal }}</p>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Centro de Actividad Reciente Multitab --}}
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between"
                     x-data="{ activeTab: 'users' }">
                    
                    {{-- Tab Navigation Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-lightning-charge-fill text-amber-500"></i> Centro de Actividad Reciente
                            </h4>
                            <p class="text-xs text-slate-500">Navega entre los últimos eventos y registros en el sistema.</p>
                        </div>

                        {{-- Tab Pills --}}
                        <div class="flex items-center gap-1.5 overflow-x-auto custom-scrollbar pb-1 sm:pb-0">
                            <button type="button" @click="activeTab = 'users'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer"
                                    :class="activeTab === 'users' ? 'bg-purple-600 text-white shadow-sm shadow-purple-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                <i class="bi bi-people mr-1"></i> Usuarios
                            </button>
                            <button type="button" @click="activeTab = 'claims'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer"
                                    :class="activeTab === 'claims' ? 'bg-rose-600 text-white shadow-sm shadow-rose-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                <i class="bi bi-bookmark-x mr-1"></i> Reclamos
                            </button>
                            <button type="button" @click="activeTab = 'jobs'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer"
                                    :class="activeTab === 'jobs' ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                <i class="bi bi-briefcase mr-1"></i> Empleo
                            </button>
                            <button type="button" @click="activeTab = 'blogs'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap cursor-pointer"
                                    :class="activeTab === 'blogs' ? 'bg-cyan-600 text-white shadow-sm shadow-cyan-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                <i class="bi bi-newspaper mr-1"></i> Noticias
                            </button>
                        </div>
                    </div>

                    {{-- Tab 1: Usuarios Recientes --}}
                    <div x-show="activeTab === 'users'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="py-4 space-y-3">
                        <div class="divide-y divide-slate-100">
                            @forelse($recentUsers as $u)
                                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0 gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($u->names ?? $u->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $u->names ?? $u->name }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                            {{ $u->role ?? 'Usuario' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 hidden sm:inline">
                                            {{ $u->created_at?->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <i class="bi bi-people text-3xl"></i>
                                    <p class="text-xs mt-1">Sin usuarios recientes</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="pt-2 text-right">
                            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 transition-colors">
                                Ver todos los usuarios <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Tab 2: Reclamos Recientes --}}
                    <div x-show="activeTab === 'claims'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="py-4 space-y-3" x-cloak>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentClaims as $claim)
                                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0 gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-base flex-shrink-0">
                                            <i class="bi bi-bookmark-x-fill"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $claim->name ?? ($claim->full_name ?? 'Reclamante') }}</p>
                                            <p class="text-[11px] text-slate-500 line-clamp-1">{{ Str::limit($claim->claim ?? $claim->description ?? 'Sin detalle', 65) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-[10px] text-slate-400">{{ $claim->created_at?->diffForHumans() }}</span>
                                        <a href="{{ route('admin.claims.index') }}" class="text-xs text-rose-600 hover:text-rose-700 font-bold">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <i class="bi bi-check-circle-fill text-3xl text-emerald-400"></i>
                                    <p class="text-xs mt-1 text-slate-600 font-semibold">¡Todo al día! No hay reclamos pendientes.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="pt-2 text-right">
                            <a href="{{ route('admin.claims.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors">
                                Ir al Libro de Reclamaciones <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Tab 3: Ofertas Laborales --}}
                    <div x-show="activeTab === 'jobs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="py-4 space-y-3" x-cloak>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentOffers as $offer)
                                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0 gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-base flex-shrink-0">
                                            <i class="bi bi-briefcase-fill"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $offer->title }}</p>
                                            <p class="text-[11px] text-slate-500 truncate">{{ $offer->company }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $offer->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $offer->is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <i class="bi bi-briefcase text-3xl"></i>
                                    <p class="text-xs mt-1">Sin ofertas laborales registradas</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="pt-2 text-right">
                            <a href="{{ route('admin.works.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                Ver Bolsa de Trabajo <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Tab 4: Noticias / Blogs --}}
                    <div x-show="activeTab === 'blogs'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="py-4 space-y-3" x-cloak>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentBlogs as $blog)
                                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0 gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-2xl bg-cyan-50 border border-cyan-100 text-cyan-600 flex items-center justify-center text-base flex-shrink-0">
                                            <i class="bi bi-newspaper"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $blog->title }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $blog->created_at?->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $blog->is_published ? 'bg-cyan-50 text-cyan-700 border border-cyan-200' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $blog->is_published ? 'Publicado' : 'Borrador' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <i class="bi bi-newspaper text-3xl"></i>
                                    <p class="text-xs mt-1">Sin noticias registradas</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="pt-2 text-right">
                            <a href="{{ route('admin.blogs.index') }}" class="text-xs font-bold text-cyan-600 hover:text-cyan-700 transition-colors">
                                Gestionar Noticias & Blog <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            {{-- ═══ SECCIÓN 4: Accesos Rápidos de Gestión ═══════════════ --}}
            <section class="space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                    <i class="bi bi-compass-fill text-indigo-600"></i> Accesos Rápidos a Módulos del Sistema
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                    @php
                        $hubs = [
                            ['route' => route('admin.carousel.index'),     'icon' => 'bi-images',             'label' => 'Carrusel',    'color' => 'from-purple-500 to-indigo-600'],
                            ['route' => route('admin.programs.index'),     'icon' => 'bi-book-fill',          'label' => 'Programas',   'color' => 'from-emerald-500 to-teal-600'],
                            ['route' => route('admin.exams.index'),        'icon' => 'bi-mortarboard-fill',   'label' => 'Admisión',    'color' => 'from-amber-500 to-orange-600'],
                            ['route' => route('admin.enrollments.index'),  'icon' => 'bi-calendar-check-fill','label' => 'Matrículas',  'color' => 'from-rose-500 to-red-600'],
                            ['route' => route('admin.works.index'),        'icon' => 'bi-briefcase-fill',     'label' => 'Bolsa Empleo','color' => 'from-blue-500 to-indigo-600'],
                            ['route' => route('admin.tupa.index'),         'icon' => 'bi-file-earmark-ruled', 'label' => 'TUPA',        'color' => 'from-indigo-500 to-purple-600'],
                            ['route' => route('admin.users.index'),        'icon' => 'bi-people-fill',        'label' => 'Usuarios',    'color' => 'from-violet-500 to-fuchsia-600'],
                            ['route' => route('admin.enterprise.edit'),    'icon' => 'bi-gear-wide-connected','label' => 'Empresa',     'color' => 'from-slate-600 to-slate-800'],
                        ];
                    @endphp

                    @foreach($hubs as $hub)
                        <a href="{{ $hub['route'] }}"
                           class="stat-card bg-white rounded-2xl border border-slate-200/80 p-4 text-center flex flex-col items-center justify-center gap-2 group hover:border-purple-300">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $hub['color'] }} text-white flex items-center justify-center text-lg shadow-md group-hover:scale-110 transition-transform">
                                <i class="bi {{ $hub['icon'] }}"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-purple-600 transition-colors">{{ $hub['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- ═══ SECCIÓN 5: Estado de los Servicios & Infraestructura ══ --}}
            <section class="bg-gradient-to-br from-slate-900 to-slate-950 rounded-3xl p-6 sm:p-8 text-white border border-slate-800 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-emerald-400 text-xl">
                            <i class="bi bi-hdd-rack-fill"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-white">Estado de la Infraestructura & Servicios</h4>
                            <p class="text-xs text-slate-400">Salud del servidor web, base de datos y recursos activos</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"></span>
                        <span>8 / 8 Servicios Operando</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                        <span class="text-xs text-slate-400 font-medium block">Admisión & Matrícula</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-black text-amber-400">{{ $admissionsActive }}</span>
                            <span class="text-xs text-slate-500">de {{ $admissionsTotal }} activas</span>
                        </div>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full" style="width: {{ $admissionsTotal > 0 ? ($admissionsActive/$admissionsTotal)*100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                        <span class="text-xs text-slate-400 font-medium block">Bolsa de Trabajo</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-black text-blue-400">{{ $jobOffersActive }}</span>
                            <span class="text-xs text-slate-500">de {{ $jobOffersTotal }} ofertas</span>
                        </div>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-400 rounded-full" style="width: {{ $jobOffersTotal > 0 ? ($jobOffersActive/$jobOffersTotal)*100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                        <span class="text-xs text-slate-400 font-medium block">Carrusel Institucional</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-black text-purple-400">{{ $carouselsActive }}</span>
                            <span class="text-xs text-slate-500">de {{ $carouselsTotal }} slides</span>
                        </div>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-400 rounded-full" style="width: {{ $carouselsTotal > 0 ? ($carouselsActive/$carouselsTotal)*100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-1.5">
                        <span class="text-xs text-slate-400 font-medium block">TUPA & Transparencia</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-black text-emerald-400">{{ $tupaActive }}</span>
                            <span class="text-xs text-slate-500">de {{ $tupaTotal }} publicados</span>
                        </div>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full" style="width: {{ $tupaTotal > 0 ? ($tupaActive/$tupaTotal)*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
                    <p>IESTP Francisco Vigo Caballero • Sistema de Gestión Académica e Institucional</p>
                    <p class="text-slate-500">Sincronización: {{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm') }} hrs</p>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardApp', () => ({
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; },
            init() {
                window.addEventListener('resize', () => {
                    this.sidebarOpen = window.innerWidth >= 1024;
                });
            }
        }));
    });
</script>
@endpush