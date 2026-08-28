@extends('layouts.app')
@section('title', 'Inversión y Gastos — Panel Administrativo')

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">

            {{-- ── Header ──────────────────────────────────────────────────────── --}}
            <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-cash-stack text-purple-600"></i> Inversión y Gastos
                        </h1>
                    </div>
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-shield-check mr-1 text-purple-500"></i> Transparencia
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Inversión y Gastos</span>
                    </div>
                </div>
            </header>

            {{-- ── Main Content ─────────────────────────────────────────────────── --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden"
                  x-data="{ importModal: false, importLoading: false, importFileName: '', truncateModal: false, truncateLoading: false }">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                            </div>
                            <button type="button" class="text-emerald-500 hover:text-emerald-700"
                                onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl"></i>
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button type="button" class="text-red-400 hover:text-red-600"
                                onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                        {{-- ══ TRUNCATE CONFIRMATION MODAL ══════════════════════════════════════ --}}
                        @can('gestionar-inversiones')
                        <div x-show="truncateModal" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

                            <div @click.outside="!truncateLoading && (truncateModal = false)"
                                @keydown.escape.window="!truncateLoading && (truncateModal = false)"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="bg-white rounded-2xl shadow-2xl border border-red-200 w-full max-w-md">

                                {{-- Modal Header --}}
                                <div class="flex items-center justify-between px-6 py-4 border-b border-red-100 bg-red-50 rounded-t-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-extrabold text-red-900">Acción Irreversible</h3>
                                            <p class="text-xs text-red-600">Vaciado completo de la tabla</p>
                                        </div>
                                    </div>
                                    <button id="close-truncate-modal-btn"
                                        @click="!truncateLoading && (truncateModal = false)"
                                        :disabled="truncateLoading"
                                        class="text-red-400 hover:text-red-600 hover:bg-red-100 p-1 rounded-lg transition">
                                        <i class="bi bi-x-lg text-sm"></i>
                                    </button>
                                </div>

                                {{-- Warning Body --}}
                                <div class="px-6 py-5 space-y-4">
                                    <div class="flex items-start gap-3 bg-amber-50 border border-amber-300 rounded-xl p-4">
                                        <i class="bi bi-shield-exclamation text-amber-500 text-xl mt-0.5 shrink-0"></i>
                                        <div class="text-sm text-amber-800 space-y-1">
                                            <p class="font-bold">Estás a punto de eliminar TODOS los registros.</p>
                                            <p>Se eliminarán permanentemente
                                                <span class="font-black text-red-700">
                                                    {{ number_format($totalRecords) }} registros
                                                </span>
                                                de la tabla de Inversión y Gastos.
                                            </p>
                                            <p class="text-xs text-amber-700">Esta acción <strong>no se puede deshacer</strong>. Asegúrate de haber exportado los datos si los necesitas.</p>
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-600">
                                        <p class="font-bold text-slate-700 mb-1">Solo los siguientes roles pueden realizar esta acción:</p>
                                        <ul class="space-y-0.5">
                                            <li class="flex items-center gap-1.5"><i class="bi bi-shield-fill-check text-purple-500"></i> Director</li>
                                            <li class="flex items-center gap-1.5"><i class="bi bi-shield-fill-check text-purple-500"></i> Administrador</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Truncate Form --}}
                                <form action="{{ route('admin.account-balances.truncate') }}" method="POST"
                                    @submit="truncateLoading = true"
                                    class="px-6 pb-6">
                                    @csrf
                                    @method('DELETE')
                                    <div class="flex items-center justify-end gap-3">
                                        <button type="button" id="cancel-truncate-btn"
                                            @click="!truncateLoading && (truncateModal = false)"
                                            :disabled="truncateLoading"
                                            :class="truncateLoading ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-200'"
                                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold transition">
                                            Cancelar
                                        </button>
                                        <button type="submit" id="confirm-truncate-btn"
                                            :disabled="truncateLoading"
                                            :class="truncateLoading ? 'opacity-60 cursor-not-allowed' : 'hover:from-red-700 hover:to-rose-800'"
                                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-700 text-white rounded-xl text-sm font-black shadow transition flex items-center gap-2">
                                            <i class="bi bi-trash3-fill" x-show="!truncateLoading"></i>
                                            <i class="bi bi-arrow-repeat animate-spin" x-show="truncateLoading"></i>
                                            <span x-text="truncateLoading ? 'Vaciando...' : 'Sí, vaciar todo'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endcan
                        {{-- ══ END TRUNCATE MODAL ══════════════════════════════════════════ --}}

                    {{-- ── KPI Cards ──────────────────────────────────────────────── --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        {{-- Total Registros --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Registros</p>
                                <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalRecords) }}</h3>
                            </div>
                        </div>

                        {{-- Total Desembolsos --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Desembolsos</p>
                                <h3 class="text-2xl font-black text-blue-700">S/ {{ number_format($totalAmount, 2) }}</h3>
                            </div>
                        </div>

                        {{-- Filtro Activo --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-funnel-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Filtros Activos</p>
                                <h3 class="text-sm font-bold text-amber-700 mt-1">
                                    {{ collect([request('year'), request('month'), request('category'), request('search')])->filter()->count() }} filtro(s) aplicado(s)
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- ── Filters & Actions Bar ──────────────────────────────────── --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                        <form action="{{ route('admin.account-balances.index') }}" method="GET"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                            {{-- Search --}}
                            <div class="lg:col-span-4 relative">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request('search') }}"
                                    placeholder="Buscar cliente, descripción, N° B/V..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>

                            {{-- Year Filter --}}
                            <div class="lg:col-span-2">
                                <select name="year" id="year-filter" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Año: Todos</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Month Filter --}}
                            <div class="lg:col-span-2">
                                <select name="month" id="month-filter" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Mes: Todos</option>
                                    @foreach ($availableMonths as $month)
                                        <option value="{{ $month }}" {{ request('month') === $month ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category Filter --}}
                            <div class="lg:col-span-2">
                                <select name="category" id="category-filter" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium truncate">
                                    <option value="">Categoría: Todas</option>
                                    @foreach ($availableCategories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                                <button type="submit" id="filter-submit-btn"
                                    class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1">
                                    <i class="bi bi-funnel-fill"></i> Filtrar
                                </button>
                                @if (request()->hasAny(['search', 'year', 'month', 'category']))
                                    <a href="{{ route('admin.account-balances.index') }}"
                                        id="clear-filters-btn"
                                        class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition-all"
                                        title="Limpiar Filtros">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                @endif
                            </div>
                        </form>

                        {{-- Action Buttons Row --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                            <span class="text-xs font-semibold text-gray-500">
                                Mostrando {{ $records->total() }} registros encontrados
                            </span>
                            <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-end">
                                {{-- Import Button --}}
                                <button type="button" id="open-import-modal-btn"
                                    @click="importModal = true"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Importar Excel / CSV
                                </button>

                                @can('gestionar-inversiones')
                                    {{-- Truncate / Clear Table Button --}}
                                    @if ($totalRecords > 0)
                                        <button type="button" id="open-truncate-modal-btn"
                                            @click="truncateModal = true"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                            <i class="bi bi-trash3-fill"></i> Vaciar Tabla
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </div>

                        {{-- ══ IMPORT MODAL ══════════════════════════════════════════════ --}}
                        <div x-show="importModal" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

                            <div @click.outside="!importLoading && (importModal = false)"
                                @keydown.escape.window="!importLoading && (importModal = false)"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg">

                                {{-- Modal Header --}}
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                            <i class="bi bi-file-earmark-spreadsheet-fill text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-extrabold text-gray-900">Importar Registros</h3>
                                            <p class="text-xs text-gray-500">Excel (.xlsx, .xls) o CSV — Columnas A a J</p>
                                        </div>
                                    </div>
                                    <button id="close-import-modal-btn"
                                        @click="!importLoading && (importModal = false)"
                                        :class="importLoading ? 'opacity-30 cursor-not-allowed' : 'hover:text-gray-600 hover:bg-gray-100'"
                                        :disabled="importLoading"
                                        class="text-gray-400 p-1 rounded-lg transition">
                                        <i class="bi bi-x-lg text-sm"></i>
                                    </button>
                                </div>

                                {{-- Modal Form --}}
                                <form action="{{ route('admin.account-balances.import') }}" method="POST"
                                    enctype="multipart/form-data"
                                    @submit="importLoading = true"
                                    class="px-6 py-5 space-y-5">
                                    @csrf

                                    {{-- File Upload --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                            Archivo <span class="text-red-500">*</span>
                                        </label>
                                        <label for="import_file"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-emerald-50 hover:border-emerald-400 transition-all group">
                                            <div x-show="!importFileName" class="flex flex-col items-center gap-2">
                                                <i class="bi bi-cloud-arrow-up-fill text-3xl text-gray-400 group-hover:text-emerald-500 transition"></i>
                                                <p class="text-xs text-gray-500 group-hover:text-emerald-600 text-center">
                                                    <span class="font-semibold">Haz clic para seleccionar</span> o arrastra el archivo aquí
                                                </p>
                                                <p class="text-xs text-gray-400">.xlsx · .xls · .csv — máx. 10 MB</p>
                                            </div>
                                            <div x-show="importFileName" class="flex flex-col items-center gap-2">
                                                <i class="bi bi-file-earmark-check-fill text-3xl text-emerald-500"></i>
                                                <p class="text-xs font-semibold text-emerald-700" x-text="importFileName"></p>
                                                <p class="text-xs text-gray-400">Haz clic para cambiar</p>
                                            </div>
                                            <input type="file" id="import_file" name="file" required
                                                accept=".xlsx,.xls,.csv" class="hidden"
                                                @change="importFileName = $event.target.files[0]?.name || ''">
                                        </label>
                                    </div>

                                    {{-- Info Banner --}}
                                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-3">
                                        <i class="bi bi-info-circle-fill text-blue-500 mt-0.5 shrink-0"></i>
                                        <div class="text-xs text-blue-700 leading-relaxed space-y-1">
                                            <p>El archivo debe tener <strong>encabezados en la fila 1</strong> y datos desde la fila 2.</p>
                                            <p>Columnas esperadas: <strong>A=MES, B=FECHA, C=N° B/V, D=CLIENTE, E=DESCRIPCIÓN, F=CATEGORÍA, G=PROGRAMA (COD.), H=PROGRAMA (NOMBRE), I=MONTO (S/), J=MOTIVO</strong>.</p>
                                            <p>Las filas sin N° B/V ni CLIENTE se omiten automáticamente.</p>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center justify-end gap-3 pt-1">
                                        <button type="button" id="cancel-import-btn"
                                            @click="!importLoading && (importModal = false)"
                                            :disabled="importLoading"
                                            :class="importLoading ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-200'"
                                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold transition">
                                            Cancelar
                                        </button>
                                        <button type="submit" id="submit-import-btn"
                                            :disabled="importLoading"
                                            :class="importLoading ? 'opacity-60 cursor-not-allowed' : 'hover:from-emerald-600 hover:to-teal-700'"
                                            class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl text-sm font-bold shadow transition flex items-center gap-2">
                                            <i class="bi bi-upload" x-show="!importLoading"></i>
                                            <i class="bi bi-arrow-repeat animate-spin" x-show="importLoading"></i>
                                            <span x-text="importLoading ? 'Importando...' : 'Importar'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- ══ END IMPORT MODAL ══════════════════════════════════════════ --}}
                    </div>

                    {{-- ── Data Table ─────────────────────────────────────────────────── --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        @if ($records->isEmpty())
                            <div class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-gray-600">Sin registros</p>
                                    <p class="text-sm mt-1">
                                        @if (request()->hasAny(['search', 'year', 'month', 'category']))
                                            No se encontraron registros con los filtros aplicados.
                                            <a href="{{ route('admin.account-balances.index') }}" class="text-purple-600 font-semibold">Limpiar filtros</a>
                                        @else
                                            Aún no hay registros. Usa el botón <strong>Importar Excel / CSV</strong> para cargar datos.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mes</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">N° B/V</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Descripción</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Categoría</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Programa</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Monto (S/)</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Motivo</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($records as $record)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-medium text-gray-700 whitespace-nowrap">{{ $record->month ?? '—' }}</td>
                                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                                    {{ $record->date ? $record->date->format('d/m/Y') : '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap font-mono text-xs">{{ $record->receipt_number ?? '—' }}</td>
                                                <td class="px-4 py-3 text-gray-700 max-w-[180px] truncate" title="{{ $record->client }}">
                                                    {{ $record->client ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 max-w-[220px] truncate" title="{{ $record->description }}">
                                                    {{ $record->description ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if ($record->category)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                                            {{ $record->category }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 text-xs max-w-[140px] truncate" title="{{ $record->program_name }}">
                                                    @if ($record->program_code)
                                                        <span class="font-bold text-indigo-600">{{ $record->program_code }}</span>
                                                        @if ($record->program_name)
                                                            <span class="text-gray-400 ml-1">· {{ Str::limit($record->program_name, 20) }}</span>
                                                        @endif
                                                    @else
                                                        {{ $record->program_name ? Str::limit($record->program_name, 24) : '—' }}
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right font-bold font-mono whitespace-nowrap
                                                    {{ (float)$record->amount >= 0 ? 'text-blue-700' : 'text-red-600' }}">
                                                    S/ {{ number_format(abs((float)$record->amount), 2) }}
                                                </td>
                                                <td class="px-4 py-3 text-gray-600 text-xs max-w-[120px] truncate" title="{{ $record->reason }}">
                                                    {{ $record->reason ?? '—' }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <form action="{{ route('admin.account-balances.destroy', $record) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('¿Eliminar este registro? Esta acción no se puede deshacer.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                            title="Eliminar">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($records->hasPages())
                                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
                                    <p class="text-xs text-gray-500">
                                        Mostrando <span class="font-bold text-gray-700">{{ $records->firstItem() }}</span>–<span class="font-bold text-gray-700">{{ $records->lastItem() }}</span>
                                        de <span class="font-bold text-gray-700">{{ $records->total() }}</span> registros
                                    </p>
                                    {{ $records->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection
