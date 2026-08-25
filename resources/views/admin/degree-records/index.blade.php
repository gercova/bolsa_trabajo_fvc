@extends('layouts.app')
@section('title', 'Grados y Títulos - Panel Administrativo')

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
                            <i class="bi bi-award-fill text-purple-600"></i> Grados y Títulos Registrados
                        </h1>
                    </div>
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-shield-check mr-1 text-purple-500"></i> Transparencia
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Títulos</span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden"
                x-data="{ importModal: false, importLoading: false, importFileName: '' }">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600">
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
                            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                    {{-- KPI Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Registros</p>
                                <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalRecords) }}</h3>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Programas de Estudio</p>
                                <h3 class="text-2xl font-black text-blue-700">{{ number_format($totalPrograms) }}</h3>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Departamentos</p>
                                <h3 class="text-2xl font-black text-amber-700">{{ number_format($totalDepts) }}</h3>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Emitidos {{ now()->year }}</p>
                                <h3 class="text-2xl font-black text-emerald-700">{{ number_format($totalThisYear) }}</h3>
                            </div>
                        </div>
                    </div>

                    {{-- Filters & Actions --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                        <form action="{{ route('admin.degree-records.index') }}" method="GET"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                            <div class="lg:col-span-4 relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar por nombre, DNI, expediente, código de título..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>

                            <div class="lg:col-span-2">
                                <select name="study_program" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium truncate">
                                    <option value="">Programa: Todos</option>
                                    @foreach ($programs as $prog)
                                        <option value="{{ $prog }}" {{ request('study_program') === $prog ? 'selected' : '' }}>{{ $prog }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lg:col-span-2">
                                <select name="department" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Depto.: Todos</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lg:col-span-2">
                                <select name="diploma_type" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Tipo Diploma: Todos</option>
                                    @foreach ($diplomaTypes as $dt)
                                        <option value="{{ $dt }}" {{ request('diploma_type') === $dt ? 'selected' : '' }}>{{ $dt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                                <button type="submit"
                                    class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition flex items-center gap-1.5 flex-1 justify-center">
                                    <i class="bi bi-funnel-fill"></i> Filtrar
                                </button>
                                @if (request()->hasAny(['search', 'study_program', 'department', 'diploma_type']))
                                    <a href="{{ route('admin.degree-records.index') }}"
                                        class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition" title="Limpiar">
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
                                <button type="button" @click="importModal = true"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Importar Excel / CSV
                                </button>
                                {{-- New Record --}}
                                <a href="{{ route('admin.degree-records.create') }}"
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
                                            <h3 class="text-sm font-extrabold text-gray-900">Importar Grados y Títulos</h3>
                                            <p class="text-xs text-gray-500">Reporte MINEDU — Columnas B a X (A, G, W ignoradas)</p>
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
                                <form action="{{ route('admin.degree-records.import') }}" method="POST"
                                    enctype="multipart/form-data"
                                    @submit="importLoading = true"
                                    class="px-6 py-5 space-y-5">
                                    @csrf

                                    {{-- File Upload --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                            Archivo <span class="text-red-500">*</span>
                                        </label>
                                        <label for="import_degree_file"
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
                                            <input type="file" id="import_degree_file" name="file" required
                                                accept=".xlsx,.xls,.csv" class="hidden"
                                                @change="importFileName = $event.target.files[0]?.name || ''">
                                        </label>
                                    </div>

                                    {{-- Info Banner --}}
                                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-3">
                                        <i class="bi bi-info-circle-fill text-blue-500 mt-0.5 shrink-0"></i>
                                        <p class="text-xs text-blue-700 leading-relaxed">
                                            El sistema lee el reporte MINEDU (<strong>fila 5 en adelante</strong>).
                                            Las filas 1–4 (título, fuente, fecha, encabezados) se omiten automáticamente.
                                            <strong>Columnas ignoradas:</strong> A (N°), G (Mención), W (sin uso).
                                            Las filas sin documento ni nombre se saltan.
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
                                        <button type="submit" :disabled="importLoading"
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

                    {{-- Data Table --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-600 text-xs uppercase tracking-wider font-extrabold">
                                        <th class="py-3.5 px-4">#</th>
                                        <th class="py-3.5 px-4">Nombres Completos</th>
                                        <th class="py-3.5 px-4">Documento</th>
                                        <th class="py-3.5 px-4">Programa de Estudios</th>
                                        <th class="py-3.5 px-4">Dpto.</th>
                                        <th class="py-3.5 px-4">Tipo Diploma</th>
                                        <th class="py-3.5 px-4">F. Emisión</th>
                                        <th class="py-3.5 px-4">Código Título</th>
                                        <th class="py-3.5 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($records as $record)
                                        <tr class="hover:bg-gray-50/60 transition-colors">
                                            <td class="py-3 px-4 text-gray-400 text-xs font-mono">{{ $record->id }}</td>
                                            <td class="py-3 px-4">
                                                <span class="font-semibold text-gray-800 text-sm">{{ $record->full_names }}</span>
                                                @if ($record->gender)
                                                    <span class="ml-1 text-xs px-1.5 py-0.5 rounded-md font-medium
                                                        {{ $record->gender === 'FEMENINO' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }}">
                                                        {{ $record->gender === 'FEMENINO' ? '♀' : '♂' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-xs text-gray-500">{{ $record->document_type }}</span>
                                                <p class="font-mono text-xs font-bold text-gray-700">{{ $record->document_number ?? '—' }}</p>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-xs font-medium text-gray-700 leading-tight line-clamp-2 max-w-[200px]">{{ $record->study_program }}</span>
                                            </td>
                                            <td class="py-3 px-4 text-xs text-gray-600">{{ $record->department ?? '—' }}</td>
                                            <td class="py-3 px-4">
                                                @if ($record->diploma_type)
                                                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-lg bg-purple-100 text-purple-700">
                                                        <i class="bi bi-award"></i> {{ $record->diploma_type }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-xs text-gray-600 font-mono">
                                                {{ $record->diploma_issue_date?->format('d/m/Y') ?? '—' }}
                                            </td>
                                            <td class="py-3 px-4 font-mono text-xs text-gray-600">{{ $record->generated_title_code ?? '—' }}</td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.degree-records.edit', $record) }}"
                                                        class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Editar">
                                                        <i class="bi bi-pencil-square text-sm"></i>
                                                    </a>
                                                    <form action="{{ route('admin.degree-records.destroy', $record) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar este registro de grado/título?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                                            <i class="bi bi-trash3 text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="py-16 text-center text-gray-400">
                                                <i class="bi bi-award text-5xl mb-3 block text-gray-300"></i>
                                                <p class="font-semibold text-sm">No se encontraron registros.</p>
                                                <p class="text-xs mt-1">Importa un reporte MINEDU o crea un registro manualmente.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($records->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $records->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection
