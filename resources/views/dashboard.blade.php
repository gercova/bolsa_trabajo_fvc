@extends('layouts.app')
@section('title', 'Panel de Control — IESTP Francisco Vigo Caballero')

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    #main-content { padding-top: 64px !important; }
    footer { margin-top: 0 !important; }

    /* Scrollbar elegante para el sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }

    /* Animaciones de contadores */
    .stat-val { font-variant-numeric: tabular-nums; }

    /* Card hover glow */
    .stat-card { transition: transform .22s ease, box-shadow .22s ease; }
    .stat-card:hover { transform: translateY(-3px); }

    /* Spark bar */
    .spark-bar {
        display: inline-block;
        width: 6px;
        border-radius: 3px;
        vertical-align: bottom;
        transition: height .4s ease;
        background: currentColor;
    }

    /* Pulse dot */
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.8)} }
    .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }
</style>
@endpush

@section('content')
<div id="dashboard-container"
     class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
     x-data="dashboardApp()">

    @include('admin.components.aside')

    {{-- ══ Main content area ══════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()"
                            class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight leading-tight">
                            Panel de Control
                        </h1>
                        <p class="text-xs text-gray-400 hidden sm:block">
                            {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Inicio
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Resumen General</span>
                    </div>
                    <a href="{{ route('inicio') }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold bg-purple-50 text-purple-600 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition-colors border border-purple-100">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span class="hidden sm:inline">Ver portal</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- ── Dashboard content ─────────────────────────────── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden space-y-8">

            {{-- ═══ Bienvenida contextual ════════════════════════════ --}}
            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-700 rounded-2xl p-6 sm:p-8 text-white shadow-lg shadow-purple-600/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-24 w-40 h-40 bg-indigo-500/20 rounded-full translate-y-1/2"></div>
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-purple-200 text-sm font-medium">Bienvenido de nuevo,</p>
                        <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">
                            {{ explode(' ', Auth::user()->names)[0] ?? 'Administrador' }}
                            <span class="wave-hand" style="display:inline-block">👋</span>
                        </h2>
                        <p class="text-purple-200 text-sm mt-2 max-w-md">
                            Aquí tienes un resumen completo del estado actual del sistema. Todo en un solo lugar.
                        </p>
                    </div>
                    <div class="hidden sm:flex flex-col items-end gap-1 text-right">
                        <span class="text-xs text-purple-300 uppercase tracking-widest font-bold">Sistema</span>
                        <span class="text-xl font-black">IESTP FVC</span>
                        <span class="inline-flex items-center gap-1.5 bg-green-400/20 text-green-300 border border-green-400/30 text-xs font-bold px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 pulse-dot"></span>
                            Sistema Operativo
                        </span>
                    </div>
                </div>
            </div>


            {{-- ═══ FILA 1 — KPIs Principales (4 columnas) ══════════ --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">
                    <i class="bi bi-grid-3x3-gap mr-1.5"></i> Indicadores Clave
                </h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Usuarios totales --}}
                    <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-sm">
                                <i class="bi bi-people-fill text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full
                                {{ $usersActive === $usersTotal ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-yellow-50 text-yellow-600 border border-yellow-100' }}">
                                {{ $usersActive }} activos
                            </span>
                        </div>
                        <div>
                            <p class="stat-val text-3xl font-black text-gray-900" data-target="{{ $usersTotal }}">{{ $usersTotal }}</p>
                            <p class="text-sm text-gray-500 font-medium mt-0.5">Usuarios registrados</p>
                        </div>
                        <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-xs text-purple-600 font-semibold">+{{ $usersThisMonth }} este mes</span>
                            <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-400 hover:text-purple-600 transition-colors">
                                Ver todos <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Bolsa de trabajo --}}
                    <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm">
                                <i class="bi bi-briefcase-fill text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                                {{ $jobOffersActive }} activas
                            </span>
                        </div>
                        <div>
                            <p class="stat-val text-3xl font-black text-gray-900" data-target="{{ $jobOffersTotal }}">{{ $jobOffersTotal }}</p>
                            <p class="text-sm text-gray-500 font-medium mt-0.5">Ofertas de empleo</p>
                        </div>
                        <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-xs text-blue-600 font-semibold">{{ $jobOffersTotal - $jobOffersActive }} inactivas</span>
                            <a href="{{ route('admin.works.index') }}" class="text-xs text-gray-400 hover:text-blue-600 transition-colors">
                                Ver todas <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Reclamos --}}
                    <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 rounded-xl bg-red-100 text-red-500 flex items-center justify-center shadow-sm">
                                <i class="bi bi-bookmark-x-fill text-xl"></i>
                            </div>
                            @if($claimsThisMonth > 0)
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-red-50 text-red-500 border border-red-100">
                                    +{{ $claimsThisMonth }} nuevos
                                </span>
                            @else
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-gray-50 text-gray-400 border border-gray-100">
                                    Sin nuevos
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="stat-val text-3xl font-black text-gray-900" data-target="{{ $claimsTotal }}">{{ $claimsTotal }}</p>
                            <p class="text-sm text-gray-500 font-medium mt-0.5">Reclamos totales</p>
                        </div>
                        <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-xs text-red-500 font-semibold">{{ $claimsThisMonth }} este mes</span>
                            <a href="{{ route('admin.claims.index') }}" class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                                Gestionar <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Aliados / Partners --}}
                    <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm">
                                <i class="bi bi-buildings-fill text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                {{ $partnersActive }} activos
                            </span>
                        </div>
                        <div>
                            <p class="stat-val text-3xl font-black text-gray-900" data-target="{{ $partnersTotal }}">{{ $partnersTotal }}</p>
                            <p class="text-sm text-gray-500 font-medium mt-0.5">Empresas aliadas</p>
                        </div>
                        <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-xs text-emerald-600 font-semibold">{{ $partnersTotal - $partnersActive }} inactivas</span>
                            <a href="{{ route('admin.partners.index') }}" class="text-xs text-gray-400 hover:text-emerald-600 transition-colors">
                                Ver todas <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ═══ FILA 2 — KPIs Secundarios (4 columnas) ══════════ --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Programas de Estudio --}}
                <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                            <i class="bi bi-mortarboard-fill text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $programsTotal }}</p>
                            <p class="text-xs text-gray-400 font-medium">Programas de Estudio</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-sky-600 font-semibold">{{ $programsActive }} publicados</span>
                        <a href="{{ route('admin.programs.index') }}" class="text-gray-400 hover:text-sky-600 transition-colors">
                            Gestionar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Exámenes / Admisiones --}}
                <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="bi bi-journal-text text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $admissionsTotal }}</p>
                            <p class="text-xs text-gray-400 font-medium">Convocatorias</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-amber-600 font-semibold">{{ $admissionsActive }} activas</span>
                        <a href="{{ route('admin.exams.index') }}" class="text-gray-400 hover:text-amber-600 transition-colors">
                            Gestionar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- TUPA --}}
                <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class="bi bi-file-earmark-ruled text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $tupaTotal }}</p>
                            <p class="text-xs text-gray-400 font-medium">Registros TUPA</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-indigo-600 font-semibold">{{ $tupaActive }} publicados</span>
                        <a href="{{ route('admin.tupa.index') }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                            Gestionar <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Blog --}}
                <div class="stat-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center">
                            <i class="bi bi-newspaper text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $blogsTotal }}</p>
                            <p class="text-xs text-gray-400 font-medium">Artículos del Blog</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-cyan-600 font-semibold">{{ $blogsPublished }} publicados</span>
                        <span class="text-gray-300">{{ $blogsTotal - $blogsPublished }} borradores</span>
                    </div>
                </div>

            </div>


            {{-- ═══ FILA 3 — Distribución de Usuarios + Actividad Reciente ══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Distribución por rol --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Distribución de Usuarios</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Por rol en el sistema</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="bi bi-pie-chart-fill text-sm"></i>
                        </div>
                    </div>

                    @php
                        $roleColors = [
                            'Admin'          => ['bg-purple-500', 'bg-purple-100', 'text-purple-700'],
                            'Docente'        => ['bg-blue-500',   'bg-blue-100',   'text-blue-700'],
                            'Administrativo' => ['bg-indigo-500', 'bg-indigo-100', 'text-indigo-700'],
                            'usuario'        => ['bg-emerald-500','bg-emerald-100','text-emerald-700'],
                        ];
                        $totalUsers = $usersTotal ?: 1;
                    @endphp

                    <div class="space-y-3">
                        @foreach($usersRoles as $role => $count)
                            @php
                                $pct   = round(($count / $totalUsers) * 100);
                                $colors = $roleColors[$role] ?? ['bg-gray-400','bg-gray-100','text-gray-600'];
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-gray-700">{{ $role }}</span>
                                    <span class="text-xs font-bold {{ $colors[2] }}">{{ $count }} ({{ $pct }}%)</span>
                                </div>
                                <div class="h-2 {{ $colors[1] }} rounded-full overflow-hidden">
                                    <div class="{{ $colors[0] }} h-full rounded-full transition-all duration-700"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach

                        @if(empty($usersRoles))
                            <p class="text-xs text-gray-400 text-center py-4">Sin datos disponibles.</p>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-50 grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <p class="text-xl font-black text-gray-900">{{ $usersTotal }}</p>
                            <p class="text-[11px] text-gray-400">Total usuarios</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-black text-purple-600">{{ $usersThisMonth }}</p>
                            <p class="text-[11px] text-gray-400">Nuevos este mes</p>
                        </div>
                    </div>
                </div>

                {{-- Últimos usuarios registrados --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Últimos Usuarios Registrados</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Los más recientes en el sistema</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}"
                           class="text-xs font-semibold text-purple-600 hover:text-purple-700 transition-colors flex items-center gap-1">
                            Ver todos <i class="bi bi-arrow-right text-sm"></i>
                        </a>
                    </div>

                    @php
                        $roleBadge = [
                            'Admin'          => 'bg-purple-100 text-purple-700',
                            'Docente'        => 'bg-blue-100 text-blue-700',
                            'Administrativo' => 'bg-indigo-100 text-indigo-700',
                            'usuario'        => 'bg-emerald-100 text-emerald-700',
                        ];
                    @endphp

                    <div class="divide-y divide-gray-50">
                        @forelse($recentUsers as $u)
                            <div class="flex items-center gap-3 py-2.5 first:pt-0 last:pb-0">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold shadow-sm">
                                    {{ strtoupper(substr($u->names ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $u->names }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                                </div>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $roleBadge[$u->role] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $u->role }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400">
                                <i class="bi bi-people text-3xl block mb-2"></i>
                                <p class="text-sm">No hay usuarios registrados aún.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>


            {{-- ═══ FILA 4 — Bolsa de Trabajo + Reclamos ══════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Últimas Ofertas de Trabajo --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">Bolsa de Trabajo</h3>
                                <p class="text-xs text-gray-400">Últimas ofertas</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.works.index') }}"
                           class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1">
                            Ver todas <i class="bi bi-arrow-right text-sm"></i>
                        </a>
                    </div>

                    @forelse($recentOffers as $offer)
                        <div class="flex items-start gap-3 py-2.5 border-b border-gray-50 last:border-b-0">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="bi bi-laptop text-blue-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $offer->title }}</p>
                                <p class="text-xs text-gray-400">{{ $offer->company }}</p>
                            </div>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0
                                {{ $offer->is_active ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-gray-50 text-gray-400 border border-gray-100' }}">
                                {{ $offer->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-3">
                                <i class="bi bi-briefcase text-blue-300 text-2xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Sin ofertas registradas</p>
                            <p class="text-xs text-gray-400 mt-1">Agrega ofertas desde la sección de Bolsa de Trabajo.</p>
                            <a href="{{ route('admin.works.index') }}"
                               class="mt-4 inline-flex items-center gap-1.5 bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="bi bi-plus-lg"></i> Agregar oferta
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Reclamos recientes --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-500 flex items-center justify-center">
                                <i class="bi bi-bookmark-x-fill"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">Reclamos</h3>
                                <p class="text-xs text-gray-400">Libro de Reclamaciones</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.claims.index') }}"
                           class="text-xs font-semibold text-red-500 hover:text-red-600 transition-colors flex items-center gap-1">
                            Ver todos <i class="bi bi-arrow-right text-sm"></i>
                        </a>
                    </div>

                    @forelse($recentClaims as $claim)
                        <div class="flex items-start gap-3 py-2.5 border-b border-gray-50 last:border-b-0">
                            <div class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="bi bi-exclamation-circle text-red-400 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $claim->name ?? ($claim->full_name ?? 'Reclamante') }}
                                </p>
                                <p class="text-xs text-gray-400 line-clamp-1">
                                    {{ Str::limit($claim->claim ?? $claim->description ?? 'Sin detalle', 60) }}
                                </p>
                            </div>
                            <span class="text-[11px] text-gray-400 flex-shrink-0">
                                {{ $claim->created_at?->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-3">
                                <i class="bi bi-check-circle text-green-400 text-2xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Sin reclamos pendientes</p>
                            <p class="text-xs text-gray-400 mt-1">No hay reclamos en el sistema actualmente.</p>
                        </div>
                    @endforelse
                </div>

            </div>


            {{-- ═══ FILA 5 — Accesos rápidos de gestión ══════════════ --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">
                    <i class="bi bi-lightning-fill mr-1.5"></i> Accesos Rápidos de Gestión
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @php
                        $quickLinks = [
                            ['route'=>route('admin.users.index'),    'icon'=>'bi-people-fill',         'label'=>'Usuarios',    'color'=>'purple'],
                            ['route'=>route('admin.works.index'),    'icon'=>'bi-briefcase-fill',       'label'=>'Bolsa',       'color'=>'blue'],
                            ['route'=>route('admin.claims.index'),   'icon'=>'bi-bookmark-x-fill',      'label'=>'Reclamos',    'color'=>'red'],
                            ['route'=>route('admin.programs.index'), 'icon'=>'bi-mortarboard-fill',     'label'=>'Programas',   'color'=>'sky'],
                            ['route'=>route('admin.exams.index'),    'icon'=>'bi-journal-text',         'label'=>'Exámenes',    'color'=>'amber'],
                            ['route'=>route('admin.tupa.index'),     'icon'=>'bi-file-earmark-ruled',   'label'=>'TUPA',        'color'=>'indigo'],
                            ['route'=>route('admin.partners.index'), 'icon'=>'bi-buildings-fill',       'label'=>'Partners',    'color'=>'emerald'],
                            ['route'=>route('admin.enterprise.edit'),'icon'=>'bi-building',             'label'=>'Empresa',     'color'=>'slate'],
                        ];
                        $qlColors = [
                            'purple'  => 'bg-purple-50  border-purple-100  text-purple-600  hover:bg-purple-100  hover:border-purple-300',
                            'blue'    => 'bg-blue-50    border-blue-100    text-blue-600    hover:bg-blue-100    hover:border-blue-300',
                            'red'     => 'bg-red-50     border-red-100     text-red-500     hover:bg-red-100     hover:border-red-300',
                            'sky'     => 'bg-sky-50     border-sky-100     text-sky-600     hover:bg-sky-100     hover:border-sky-300',
                            'amber'   => 'bg-amber-50   border-amber-100   text-amber-600   hover:bg-amber-100   hover:border-amber-300',
                            'indigo'  => 'bg-indigo-50  border-indigo-100  text-indigo-600  hover:bg-indigo-100  hover:border-indigo-300',
                            'emerald' => 'bg-emerald-50 border-emerald-100 text-emerald-600 hover:bg-emerald-100 hover:border-emerald-300',
                            'slate'   => 'bg-slate-50   border-slate-100   text-slate-600   hover:bg-slate-100   hover:border-slate-300',
                        ];
                    @endphp

                    @foreach($quickLinks as $ql)
                        <a href="{{ $ql['route'] }}"
                           class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border transition-all duration-200 text-center group {{ $qlColors[$ql['color']] }}">
                            <i class="bi {{ $ql['icon'] }} text-2xl group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-xs font-bold">{{ $ql['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>


            {{-- ═══ FILA 6 — Resumen del sistema ══════════════════════ --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                        <i class="bi bi-activity text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm">Estado del Sistema</h3>
                        <p class="text-xs text-slate-400">Resumen de recursos activos</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @php
                        $systemStats = [
                            ['label'=>'Usuarios Activos',     'value'=>$usersActive,     'total'=>$usersTotal,     'color'=>'purple'],
                            ['label'=>'Ofertas Activas',      'value'=>$jobOffersActive,  'total'=>$jobOffersTotal,  'color'=>'blue'],
                            ['label'=>'Admisiones Activas',   'value'=>$admissionsActive, 'total'=>$admissionsTotal, 'color'=>'amber'],
                            ['label'=>'Partners Activos',     'value'=>$partnersActive,   'total'=>$partnersTotal,   'color'=>'emerald'],
                        ];
                        $sysCols = ['purple'=>'text-purple-400','blue'=>'text-blue-400','amber'=>'text-amber-400','emerald'=>'text-emerald-400'];
                    @endphp

                    @foreach($systemStats as $ss)
                        @php $pct = $ss['total'] > 0 ? round(($ss['value']/$ss['total'])*100) : 0; @endphp
                        <div class="bg-white/5 rounded-xl p-4">
                            <p class="text-xs text-slate-400 font-medium mb-2">{{ $ss['label'] }}</p>
                            <p class="text-2xl font-black {{ $sysCols[$ss['color']] }}">{{ $ss['value'] }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">de {{ $ss['total'] }} totales</p>
                            <div class="mt-2 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700
                                    {{ $ss['color']==='purple' ? 'bg-purple-500' : '' }}
                                    {{ $ss['color']==='blue'   ? 'bg-blue-500'   : '' }}
                                    {{ $ss['color']==='amber'  ? 'bg-amber-500'  : '' }}
                                    {{ $ss['color']==='emerald'? 'bg-emerald-500': '' }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-green-400 pulse-dot"></span>
                        Todos los servicios operando correctamente
                    </div>
                    <p class="text-xs text-slate-500">
                        Última actualización: {{ now()->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                    </p>
                </div>
            </div>

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