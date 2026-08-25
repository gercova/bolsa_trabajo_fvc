@extends('layouts.app')
@section('title', 'Estadísticas y Registros Estudiantiles - Panel Administrativo')

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-bar-chart-line-fill text-purple-600"></i> Estadísticas y Registros Académicos
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-shield-check mr-1 text-purple-500"></i> Transparencia
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Estadísticas</span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="{ activeRecordModal: null, importModal: false, importLoading: false, importFileName: '' }">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
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
                            <button type="button" class="text-red-400 hover:text-red-600" onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                    {{-- KPI Stat Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        {{-- Total Records --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Registros</p>
                                <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalRecords) }}</h3>
                            </div>
                        </div>

                        {{-- Admisión --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Postulantes Admisión</p>
                                <h3 class="text-2xl font-black text-blue-700">{{ number_format($totalAdmission) }}</h3>
                            </div>
                        </div>

                        {{-- Matrícula --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Matriculados</p>
                                <h3 class="text-2xl font-black text-emerald-700">{{ number_format($totalEnrollment) }}</h3>
                            </div>
                        </div>

                        {{-- Programas --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-buildings"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Programas de Estudio</p>
                                <h3 class="text-2xl font-black text-amber-700">{{ $totalPrograms }}</h3>
                            </div>
                        </div>
                    </div>

                    {{-- Filters & Actions Bar --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                        <form action="{{ route('admin.statistics.index') }}" method="GET"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            
                            {{-- Search Input --}}
                            <div class="lg:col-span-4 relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar por DNI, nombres, colegio..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>

                            {{-- Record Type Filter --}}
                            <div class="lg:col-span-2">
                                <select name="record_type" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Tipo: Todos</option>
                                    <option value="ADMISION" {{ request('record_type') === 'ADMISION' ? 'selected' : '' }}>Admisión</option>
                                    <option value="MATRICULA" {{ request('record_type') === 'MATRICULA' ? 'selected' : '' }}>Matrícula</option>
                                </select>
                            </div>

                            {{-- Academic Period Filter --}}
                            <div class="lg:col-span-2">
                                <select name="academic_period" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Período: Todos</option>
                                    @foreach ($academicPeriods as $period)
                                        <option value="{{ $period }}" {{ request('academic_period') === $period ? 'selected' : '' }}>{{ $period }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Study Program Filter --}}
                            <div class="lg:col-span-2">
                                <select name="study_program" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium truncate">
                                    <option value="">Programa: Todos</option>
                                    @foreach ($studyProgramsList as $prog)
                                        <option value="{{ $prog }}" {{ request('study_program') === $prog ? 'selected' : '' }}>{{ $prog }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                                <button type="submit"
                                    class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1">
                                    <i class="bi bi-funnel-fill"></i> Filtrar
                                </button>
                                @if (request()->hasAny(['search', 'record_type', 'academic_period', 'study_program']))
                                    <a href="{{ route('admin.statistics.index') }}"
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
                                Mostrando {{ $studentRecords->total() }} registros encontrados
                            </span>
                            <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-end">
                                {{-- Import Button --}}
                                <button type="button" @click="importModal = true"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Importar Excel / CSV
                                </button>
                                {{-- Create Button --}}
                                <a href="{{ route('admin.statistics.create') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                    <i class="bi bi-plus-circle"></i> Nuevo Registro
                                </a>
                            </div>
                        </div>

                        {{-- ═══ IMPORT MODAL ══════════════════════════════════════════════ --}}
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
                                            <p class="text-xs text-gray-500">Excel (.xlsx, .xls) o CSV — Columnas H a AF</p>
                                        </div>
                                    </div>
                                    <button @click="!importLoading && (importModal = false)"
                                        :class="importLoading ? 'opacity-30 cursor-not-allowed' : 'hover:text-gray-600 hover:bg-gray-100'"
                                        :disabled="importLoading"
                                        class="text-gray-400 p-1 rounded-lg transition">
                                        <i class="bi bi-x-lg text-sm"></i>
                                    </button>
                                </div>

                                {{-- Modal Form --}}
                                <form action="{{ route('admin.statistics.import') }}" method="POST"
                                    enctype="multipart/form-data"
                                    @submit="importLoading = true"
                                    class="px-6 py-5 space-y-5">
                                    @csrf

                                    {{-- Record Type --}}
                                    <div>
                                        <label for="import_record_type"
                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                            Tipo de Registro <span class="text-red-500">*</span>
                                        </label>
                                        <select name="record_type" id="import_record_type" required
                                            class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition font-semibold">
                                            <option value="AUTO" selected>Automático (detectar por ciclo)</option>
                                            <option value="ADMISION">Admisión — todo el archivo</option>
                                            <option value="MATRICULA">Matrícula — todo el archivo</option>
                                        </select>
                                        <p class="text-xs text-gray-400 mt-1">En modo automático: si hay ciclo → Matrícula; si no → Admisión.</p>
                                    </div>

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
                                        <p class="text-xs text-blue-700 leading-relaxed">
                                            El sistema lee el reporte MINEDU completo (<strong>columnas A–AF</strong>, fila 6 en adelante).
                                            El <strong>período académico</strong> se toma automáticamente de la <strong>columna A</strong> de cada fila.
                                            Las primeras 5 filas (título, logos y encabezados) se omiten. Las filas sin documento ni nombre se saltan.
                                        </p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center justify-end gap-3 pt-1">
                                        <button type="button"
                                            @click="!importLoading && (importModal = false)"
                                            :disabled="importLoading"
                                            :class="importLoading ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-200'"
                                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold transition">
                                            Cancelar
                                        </button>
                                        <button type="submit"
                                            :disabled="importLoading"
                                            class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 disabled:opacity-60 text-white rounded-xl text-sm font-extrabold shadow-md transition flex items-center gap-2">
                                            <template x-if="!importLoading">
                                                <span class="flex items-center gap-2">
                                                    <i class="bi bi-upload"></i> Importar Ahora
                                                </span>
                                            </template>
                                            <template x-if="importLoading">
                                                <span class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                                    </svg>
                                                    Procesando...
                                                </span>
                                            </template>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Data Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-600 text-xs uppercase tracking-wider font-extrabold">
                                        <th class="py-3.5 px-4">#</th>
                                        <th class="py-3.5 px-4">Tipo</th>
                                        <th class="py-3.5 px-4">Estudiante / Postulante</th>
                                        <th class="py-3.5 px-4">Documento</th>
                                        <th class="py-3.5 px-4">Programa de Estudios</th>
                                        <th class="py-3.5 px-4">Período</th>
                                        <th class="py-3.5 px-4">Puntaje / Ciclo</th>
                                        <th class="py-3.5 px-4">Situación</th>
                                        <th class="py-3.5 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @forelse ($studentRecords as $record)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-3.5 px-4 font-bold text-gray-400">{{ $record->id }}</td>
                                            <td class="py-3.5 px-4">
                                                @if ($record->record_type === 'ADMISION')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                        <i class="bi bi-journal-check"></i> Admisión
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                                        <i class="bi bi-mortarboard-fill"></i> Matrícula
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-gray-900 leading-tight">
                                                    {{ $record->full_name }}
                                                </div>
                                                @if ($record->email)
                                                    <span class="text-xs text-gray-500">{{ $record->email }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-md text-gray-700 font-semibold">
                                                    {{ $record->document_type->abreviation ?? 'DOC' }}: {{ $record->document }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 font-semibold text-gray-800">
                                                {{ $record->study_program }}
                                            </td>
                                            <td class="py-3.5 px-4 font-extrabold text-purple-700">
                                                {{ $record->academic_period }}
                                            </td>
                                            <td class="py-3.5 px-4 text-xs font-medium">
                                                @if ($record->record_type === 'ADMISION')
                                                    @if ($record->score)
                                                        <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                            Puntaje: {{ number_format($record->score, 2) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">Sin nota</span>
                                                    @endif
                                                @else
                                                    @if ($record->cycle)
                                                        <span class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                                            Ciclo {{ $record->cycle }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">--</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4">
                                                @if ($record->situation)
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                                        {{ $record->situation }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">--</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    {{-- Quick View Modal Button --}}
                                                    <button type="button" @click="activeRecordModal = {{ $record->id }}"
                                                        class="p-2 rounded-lg bg-gray-100 hover:bg-purple-100 text-gray-600 hover:text-purple-700 transition"
                                                        title="Ver Ficha Completa">
                                                        <i class="bi bi-eye-fill text-sm"></i>
                                                    </button>

                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('admin.statistics.edit', $record) }}"
                                                        class="p-2 rounded-lg bg-gray-100 hover:bg-amber-100 text-gray-600 hover:text-amber-700 transition"
                                                        title="Editar Registro">
                                                        <i class="bi bi-pencil-square text-sm"></i>
                                                    </a>

                                                    {{-- Delete Button Form --}}
                                                    <form action="{{ route('admin.statistics.destroy', $record) }}" method="POST"
                                                        onsubmit="return confirm('¿Está seguro de eliminar este registro académico?');"
                                                        class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 rounded-lg bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-700 transition"
                                                            title="Eliminar Registro">
                                                            <i class="bi bi-trash-fill text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                {{-- Inline Modal Detail --}}
                                                <div x-show="activeRecordModal === {{ $record->id }}"
                                                    x-transition:enter="transition ease-out duration-250"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-180"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
                                                    style="display:none;"
                                                    role="dialog"
                                                    aria-modal="true"
                                                    @keydown.escape.window="activeRecordModal = null">

                                                    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[88vh] overflow-y-auto shadow-2xl border border-slate-100 flex flex-col text-left"
                                                        @click.outside="activeRecordModal = null">

                                                        <div class="h-2 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-t-3xl flex-shrink-0"></div>

                                                        <div class="p-6 sm:p-8 space-y-6">
                                                            {{-- Modal Header --}}
                                                            <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-100">
                                                                <div>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase {{ $record->record_type === 'ADMISION' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                                            {{ $record->record_type }}
                                                                        </span>
                                                                        <span class="text-xs font-extrabold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">
                                                                            Período: {{ $record->academic_period }}
                                                                        </span>
                                                                    </div>
                                                                    <h3 class="text-xl font-extrabold text-gray-900 mt-2">
                                                                        {{ $record->full_name }}
                                                                    </h3>
                                                                    <p class="text-xs font-bold text-gray-500">
                                                                        {{ $record->study_program }}
                                                                    </p>
                                                                </div>

                                                                <button @click="activeRecordModal = null"
                                                                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
                                                                    <i class="bi bi-x-lg text-sm"></i>
                                                                </button>
                                                            </div>

                                                            {{-- 1. Datos Personales --}}
                                                            <div>
                                                                <h4 class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                                    <i class="bi bi-person-badge"></i> Datos Personales
                                                                </h4>
                                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-xl text-xs">
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Documento:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->document_type->name ?? 'DNI' }}: {{ $record->document }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Género:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->gender ?? 'No especificado' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Fecha Nacimiento:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->birthdate ? $record->birthdate->format('d/m/Y') : '--' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Lengua Materna:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->mother_tongue ?? 'Español' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Teléfono:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->phone ?? '--' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Correo:</span>
                                                                        <span class="font-bold text-gray-800 truncate block">{{ $record->email ?? '--' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- 2. Procedencia Educativa --}}
                                                            <div>
                                                                <h4 class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                                    <i class="bi bi-building"></i> Institución Educativa de Procedencia
                                                                </h4>
                                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-xl text-xs">
                                                                    <div class="sm:col-span-2">
                                                                        <span class="text-gray-400 block font-semibold">Colegio / I.E.:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->institution_name_ie ?? '--' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Año de Egreso:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->year_graduation ?? '--' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Ubicación I.E.:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->region_ie }} - {{ $record->province_ie }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Gestión I.E.:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->management_type_ie ?? '--' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Código Modular I.E.:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->modular_code_ie ?? '--' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- 3. Datos del Proceso Académico --}}
                                                            <div>
                                                                <h4 class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                                    <i class="bi bi-mortarboard"></i> Datos del Proceso (IESTP FVC)
                                                                </h4>
                                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-xl text-xs">
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Modalidad:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->modality ?? 'ORDINARIO' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Turno:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->shift ?? 'DIURNO' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Sede:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->headquarters ?? 'SEDE PRINCIPAL' }}</span>
                                                                    </div>
                                                                    @if ($record->score)
                                                                        <div>
                                                                            <span class="text-gray-400 block font-semibold">Puntaje Obtenido:</span>
                                                                            <span class="font-extrabold text-emerald-700 text-sm">{{ number_format($record->score, 2) }}</span>
                                                                        </div>
                                                                    @endif
                                                                    @if ($record->cycle)
                                                                        <div>
                                                                            <span class="text-gray-400 block font-semibold">Ciclo:</span>
                                                                            <span class="font-bold text-purple-700">Ciclo {{ $record->cycle }}</span>
                                                                        </div>
                                                                    @endif
                                                                    <div>
                                                                        <span class="text-gray-400 block font-semibold">Situación:</span>
                                                                        <span class="font-bold text-gray-800">{{ $record->situation ?? '--' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Modal Footer --}}
                                                            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                                                                <a href="{{ route('admin.statistics.edit', $record) }}"
                                                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                                                                    <i class="bi bi-pencil-square"></i> Editar
                                                                </a>
                                                                <button type="button" @click="activeRecordModal = null"
                                                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                                                                    Cerrar
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="py-12 px-4 text-center">
                                                <div class="max-w-sm mx-auto">
                                                    <i class="bi bi-inbox text-4xl text-gray-300 mb-3 inline-block"></i>
                                                    <h3 class="text-base font-extrabold text-gray-800 mb-1">No se encontraron registros</h3>
                                                    <p class="text-xs text-gray-500 mb-4">No hay postulantes o matriculados que coincidan con los filtros aplicados.</p>
                                                    <a href="{{ route('admin.statistics.create') }}"
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 transition">
                                                        <i class="bi bi-plus-circle"></i> Crear Primer Registro
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($studentRecords->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $studentRecords->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection
