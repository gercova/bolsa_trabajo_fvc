@extends('layouts.app')
@section('title', 'Gestión de Certificados — Panel Administrativo')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .animate-fade-in { animation: fadeIn 0.25s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
    x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="certificatesApp()">

        {{-- ── Header ── --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight leading-none flex items-center gap-2">
                            <i class="bi bi-patch-check text-purple-600"></i> Certificados Emitidos
                        </h1>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Emisión, validación y gestión de notas modulares de certificados</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-book mr-1 text-purple-500"></i> Programas
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Certificados</span>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="px-4 sm:px-6 border-t border-gray-100 bg-gray-50/60 flex items-center gap-1 overflow-x-auto">
                <a href="{{ route('admin.programs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-mortarboard"></i> Programas de Estudio
                </a>
                <a href="{{ route('admin.certificates.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-purple-600 text-purple-700 whitespace-nowrap">
                    <i class="bi bi-patch-check"></i> Certificados
                </a>
                <a href="{{ route('admin.courses.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-journal-bookmark"></i> Cursos
                </a>
                <a href="{{ route('admin.modules.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-layers"></i> Módulos
                </a>
                <a href="{{ route('admin.itineraries.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 transition-colors whitespace-nowrap">
                    <i class="bi bi-diagram-3"></i> Itinerarios
                </a>
            </div>
        </header>

        {{-- ── Main Content ── --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-red-400 hover:text-red-600" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm animate-fade-in">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                            <p class="text-sm font-bold text-red-800">Por favor corrige los siguientes errores:</p>
                        </div>
                        <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Import per-row errors --}}
                @if (session('import_errors'))
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-sm animate-fade-in">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-amber-600 text-lg"></i>
                                <p class="text-sm font-bold text-amber-900">Advertencias durante la importación ({{ count(session('import_errors')) }} fila(s) con problemas):</p>
                            </div>
                            <button type="button" class="text-amber-500 hover:text-amber-700" onclick="this.closest('[class*=bg-amber]').remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                        <ul class="list-disc list-inside text-xs text-amber-800 space-y-1 max-h-40 overflow-y-auto">
                            @foreach (session('import_errors') as $importError)
                                <li>{{ $importError }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ── Stat Cards ── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Certificados</p>
                            <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalCertificates) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Certificados Válidos</p>
                            <h3 class="text-2xl font-black text-emerald-700">{{ number_format($activeCertificates) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Presencial</p>
                            <h3 class="text-2xl font-black text-blue-700">{{ number_format($presencialCount) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Virtual / Semipres.</p>
                            <h3 class="text-2xl font-black text-indigo-700">{{ number_format($virtualSemipresCount) }}</h3>
                        </div>
                    </div>
                </div>

                {{-- ── Filters & Actions ── --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                    <form action="{{ route('admin.certificates.index') }}" method="GET"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                        {{-- Search --}}
                        <div class="lg:col-span-4 relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por código, estudiante o DNI..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        {{-- Course Filter --}}
                        <div class="lg:col-span-3">
                            <select name="course_id" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium truncate">
                                <option value="">Curso: Todos</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Modality Filter --}}
                        <div class="lg:col-span-2">
                            <select name="modality" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Modalidad: Todas</option>
                                <option value="Presencial" {{ request('modality') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                <option value="Semipresencial" {{ request('modality') === 'Semipresencial' ? 'selected' : '' }}>Semipresencial</option>
                                <option value="Virtual" {{ request('modality') === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="lg:col-span-1">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-2 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                <option value="">Estado</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="lg:col-span-2 flex items-center gap-2 justify-end">
                            <button type="submit"
                                class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center gap-1.5 flex-1">
                                <i class="bi bi-funnel-fill"></i> Filtrar
                            </button>
                            @if (request()->hasAny(['search', 'course_id', 'modality', 'status']))
                                <a href="{{ route('admin.certificates.index') }}"
                                    class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm transition-all"
                                    title="Limpiar Filtros">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500">
                            Mostrando {{ $certificates->total() }} certificados registrados
                        </span>
                        <div class="flex items-center gap-2">
                            @if ($totalCertificates > 0)
                                {{-- Delete All Button --}}
                                <button type="button" @click="deleteAllModalOpen = true"
                                    class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-800 border border-rose-200 hover:border-rose-300 rounded-xl text-sm font-bold shadow-xs transition-all cursor-pointer"
                                    title="Eliminar todos los certificados del sistema">
                                    <i class="bi bi-trash3 text-rose-600"></i>
                                    <span class="hidden sm:inline">Eliminar Todos</span>
                                </button>
                            @endif
                            {{-- Export Bulk PDF Button --}}
                            <button type="button" @click="openBulkPdfModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 hover:from-red-700 hover:via-rose-700 hover:to-pink-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all cursor-pointer">
                                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                            </button>
                            {{-- Import Button --}}
                            <button type="button" @click="importModalOpen = true"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                <i class="bi bi-file-earmark-arrow-up"></i> Importar Excel / CSV
                            </button>
                            {{-- New Certificate Button --}}
                            <button type="button" @click="openCreateModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
                                <i class="bi bi-plus-lg"></i> Nuevo Certificado
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Bulk Action Floating Bar --}}
                <div x-show="selectedCerts.length > 0" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="bg-slate-900 text-white p-4 rounded-2xl shadow-xl border border-slate-800 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-600/30 border border-purple-500/30 text-purple-300 flex items-center justify-center text-lg font-bold">
                            <i class="bi bi-check2-square"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-purple-600 text-white text-xs font-black" x-text="selectedCerts.length"></span>
                                <span>certificados seleccionados</span>
                            </p>
                            <p class="text-xs text-slate-400">Registros marcados para eliminación masiva</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button type="button" @click="deselectAll()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition cursor-pointer">
                            <i class="bi bi-x-circle mr-1"></i> Desmarcar todos
                        </button>
                        <button type="button" @click="openBulkPdfModal()" class="px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-rose-600/30 hover:scale-105 active:scale-95 cursor-pointer">
                            <i class="bi bi-file-earmark-pdf-fill text-sm"></i>
                            <span>Exportar PDF</span>
                        </button>
                        <button type="button" @click="bulkDeleteModalOpen = true" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-rose-600/30 hover:scale-105 active:scale-95 cursor-pointer">
                            <i class="bi bi-trash-fill text-sm"></i>
                            <span>Eliminar Seleccionados</span>
                        </button>
                    </div>
                </div>

                {{-- ── Table ── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    @if ($certificates->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 gap-4 text-gray-400">
                            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-400 flex items-center justify-center text-3xl">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-gray-700">Sin certificados registrados</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if (request()->hasAny(['search', 'course_id', 'status']))
                                        No hay certificados con los filtros aplicados.
                                        <a href="{{ route('admin.certificates.index') }}" class="text-purple-600 font-semibold hover:underline">Limpiar filtros</a>
                                    @else
                                        Emita el primer certificado haciendo clic en <strong>Nuevo Certificado</strong>.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="p-4 w-12 text-center">
                                            <input type="checkbox" @change="toggleSelectAll($event.target.checked)"
                                                :checked="isAllSelected()"
                                                class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 transition cursor-pointer"
                                                title="Seleccionar todos los certificados de esta página">
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estudiante / DNI</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Curso</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Emisión</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Notas / Módulos</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($certificates as $cert)
                                        <tr class="hover:bg-gray-50/80 transition-colors"
                                            :class="selectedCerts.map(Number).includes({{ $cert->id }}) ? 'bg-purple-50/70' : ''">
                                            <td class="p-4 w-12 text-center">
                                                <input type="checkbox" :value="{{ $cert->id }}" x-model.number="selectedCerts"
                                                    class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 transition cursor-pointer">
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="font-mono font-bold text-purple-700 text-xs bg-purple-50 border border-purple-200 px-2 py-1 rounded-lg">
                                                    {{ $cert->certificate_code }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-bold text-gray-800">{{ $cert->user?->names ?? 'Usuario no asignado' }}</span>
                                                    @if (($cert->user?->certificates_count ?? 0) > 1)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 rounded-full"
                                                            title="Este estudiante posee {{ $cert->user->certificates_count }} certificados registrados">
                                                            <i class="bi bi-collection"></i> {{ $cert->user->certificates_count }} certs
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500 font-mono">DNI: {{ $cert->user?->dni ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-gray-800 text-xs">{{ $cert->course?->name ?? '—' }}</div>
                                                <div class="mt-1">
                                                    @if ($cert->modality === 'Virtual')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                            <i class="bi bi-laptop text-[10px]"></i> Virtual
                                                        </span>
                                                    @elseif ($cert->modality === 'Semipresencial')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                            <i class="bi bi-arrow-left-right text-[10px]"></i> Semipresencial
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                            <i class="bi bi-building text-[10px]"></i> Presencial
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                                <div class="font-semibold">{{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d/m/Y') : '—' }}</div>
                                                @if ($cert->duration)
                                                    <div class="text-[11px] text-gray-400">{{ $cert->duration }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <button type="button" @click="openDetailsModal({{ json_encode($cert) }})"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold transition {{ $cert->details->count() > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                    <i class="bi bi-card-checklist"></i> {{ $cert->details->count() }} notas
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <form action="{{ route('admin.certificates.toggle-status', $cert) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold transition {{ $cert->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $cert->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        {{ $cert->is_active ? 'Válido' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <a href="{{ route('admin.certificates.print', $cert) }}" target="_blank"
                                                        class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                                        title="Ver / Imprimir Certificado Oficial">
                                                        <i class="bi bi-printer text-base"></i>
                                                    </a>
                                                    <a href="{{ $cert->validation_url }}" target="_blank"
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                        title="Validación Pública QR">
                                                        <i class="bi bi-qr-code text-base"></i>
                                                    </a>
                                                    <button type="button" @click="openDetailsModal({{ json_encode($cert) }})"
                                                        class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                                        title="Ver / Gestionar Calificaciones y Temario">
                                                        <i class="bi bi-card-checklist text-base"></i>
                                                    </button>
                                                    <button type="button" @click="openEditModal({{ json_encode($cert) }})"
                                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                        title="Editar Certificado">
                                                        <i class="bi bi-pencil-square text-base"></i>
                                                    </button>
                                                    <form action="{{ route('admin.certificates.destroy', $cert) }}" method="POST"
                                                        onsubmit="return confirm('¿Está seguro de eliminar el certificado «{{ addslashes($cert->certificate_code) }}»?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                            title="Eliminar Certificado">
                                                            <i class="bi bi-trash3 text-base"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($certificates->hasPages())
                            <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                                <p class="text-xs text-gray-500">
                                    Mostrando <strong>{{ $certificates->firstItem() }}</strong>–<strong>{{ $certificates->lastItem() }}</strong> de <strong>{{ $certificates->total() }}</strong> certificados
                                </p>
                                {{ $certificates->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </main>

        {{-- ══ MODAL CREATE / EDIT CERTIFICATE ═══════════════════════════ --}}
        <div x-show="modalOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

            <div @click.outside="modalOpen = false"
                @keydown.escape.window="modalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i :class="isEdit ? 'bi-pencil-square' : 'bi-patch-check-fill'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900" x-text="isEdit ? 'Editar Certificado' : 'Nuevo Certificado'"></h3>
                            <p class="text-xs text-gray-500">Emisión de certificado para estudiante con código único</p>
                        </div>
                    </div>
                    <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form :action="isEdit ? updateUrl : '{{ route('admin.certificates.store') }}'" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Student & Course Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Select2-like Student/User Search Component --}}
                        <div class="relative" @click.outside="studentDropdownOpen = false">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>Estudiante / Usuario <span class="text-red-500">*</span></span>
                                <span class="text-[10px] text-purple-600 font-semibold tracking-normal normal-case">Búsqueda rápida</span>
                            </label>

                            {{-- Hidden input for form submission --}}
                            <input type="hidden" name="user_id" :value="form.user_id" required>

                            {{-- Select2 Trigger Button --}}
                            <button type="button" @click="toggleStudentDropdown()"
                                class="w-full text-left text-sm border rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium flex items-center justify-between transition-all shadow-sm cursor-pointer"
                                :class="selectedStudent ? 'border-purple-300 bg-purple-50/20' : 'border-gray-300 hover:border-gray-400'">
                                
                                <div class="flex items-center gap-2.5 overflow-hidden truncate">
                                    <template x-if="selectedStudent">
                                        <div class="flex items-center gap-2 truncate">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm"
                                                x-text="(selectedStudent.names || 'U').charAt(0).toUpperCase()">
                                            </div>
                                            <div class="truncate leading-tight">
                                                <div class="font-bold text-gray-900 text-xs truncate" x-text="selectedStudent.names"></div>
                                                <div class="text-[11px] text-gray-500 flex items-center gap-1.5 truncate">
                                                    <span class="font-semibold text-purple-700" x-text="'DNI: ' + (selectedStudent.dni || 'S/D')"></span>
                                                    <span x-show="selectedStudent.email" class="text-gray-400 truncate" x-text="'• ' + selectedStudent.email"></span>
                                                    <span x-show="selectedStudent.phone" class="text-emerald-600 font-medium" x-text="'• Tel: ' + selectedStudent.phone"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!selectedStudent">
                                        <span class="text-gray-400 flex items-center gap-2 text-xs">
                                            <i class="bi bi-search text-gray-400"></i>
                                            <span>Buscar por nombre, DNI, correo o teléfono...</span>
                                        </span>
                                    </template>
                                </div>

                                <div class="flex items-center gap-1.5 flex-shrink-0 ml-2">
                                    <template x-if="selectedStudent">
                                        <span role="button" @click.stop="clearStudentSelection()"
                                            class="p-1 hover:text-red-500 hover:bg-red-50 rounded-md text-gray-400 transition"
                                            title="Quitar estudiante">
                                            <i class="bi bi-x-lg text-xs"></i>
                                        </span>
                                    </template>
                                    <i class="bi bi-chevron-expand text-gray-400 text-xs"></i>
                                </div>
                            </button>

                            {{-- Select2 Dropdown Panel --}}
                            <div x-show="studentDropdownOpen" style="display: none;"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden divide-y divide-gray-100">
                                
                                {{-- Search Input with Icons --}}
                                <div class="p-2.5 bg-gray-50/80">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" x-ref="studentSearchInput" x-model="studentSearch"
                                            @input="onStudentSearchInput()"
                                            @keydown.arrow-down.prevent="navigateStudents(1)"
                                            @keydown.arrow-up.prevent="navigateStudents(-1)"
                                            @keydown.enter.prevent="selectHighlightedStudent()"
                                            @keydown.escape="studentDropdownOpen = false"
                                            placeholder="Escriba nombre, DNI, correo o teléfono..."
                                            class="w-full pl-8 pr-8 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white outline-none">
                                        
                                        <template x-if="isSearchingStudents">
                                            <i class="bi bi-arrow-repeat animate-spin absolute right-2.5 top-1/2 -translate-y-1/2 text-purple-600 text-xs"></i>
                                        </template>
                                        <template x-if="!isSearchingStudents && studentSearch">
                                            <button type="button" @click="studentSearch = ''; onStudentSearchInput();"
                                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                                                <i class="bi bi-x-circle-fill text-xs"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Results List (Limited to max 5 matches) --}}
                                <ul class="max-h-60 overflow-y-auto divide-y divide-gray-50 py-1 text-xs">
                                    {{-- Initial Prompt when search is empty --}}
                                    <template x-if="!studentSearch.trim()">
                                        <li class="px-4 py-5 text-center text-xs text-gray-400 space-y-1">
                                            <i class="bi bi-person-bounding-box text-2xl text-purple-300 block mb-1"></i>
                                            <p class="font-semibold text-gray-600">Búsqueda de Estudiante / Usuario</p>
                                            <p class="text-[11px] text-gray-400">Escriba para buscar por nombre, DNI, correo o teléfono.<br>Se mostrarán los primeros 5 resultados.</p>
                                        </li>
                                    </template>

                                    {{-- Loading State --}}
                                    <template x-if="isSearchingStudents">
                                        <li class="px-4 py-4 text-center text-xs text-purple-600 flex items-center justify-center gap-2">
                                            <i class="bi bi-arrow-repeat animate-spin"></i>
                                            <span>Buscando coincidencias...</span>
                                        </li>
                                    </template>

                                    {{-- Empty State --}}
                                    <template x-if="!isSearchingStudents && studentSearch.trim() && studentResults.length === 0">
                                        <li class="px-4 py-6 text-center text-xs text-gray-400 space-y-1">
                                            <i class="bi bi-search text-xl text-gray-300 block mb-1"></i>
                                            <p class="font-medium text-gray-700">Sin coincidencias encontradas</p>
                                            <p class="text-[11px] text-gray-400">No encontramos ningún estudiante con "<span x-text="studentSearch"></span>".</p>
                                        </li>
                                    </template>

                                    {{-- Results Items (Max 5) --}}
                                    <template x-for="(st, idx) in studentResults" :key="st.id">
                                        <li>
                                            <button type="button" @click="selectStudent(st)"
                                                @mouseenter="highlightedStudentIndex = idx"
                                                class="w-full px-3.5 py-2.5 text-left flex items-center justify-between transition-colors cursor-pointer"
                                                :class="{
                                                    'bg-purple-50 text-purple-900': highlightedStudentIndex === idx,
                                                    'hover:bg-gray-50 text-gray-800': highlightedStudentIndex !== idx,
                                                    'font-semibold': form.user_id == st.id
                                                }">
                                                <div class="flex items-center gap-2.5 overflow-hidden min-w-0">
                                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold flex-shrink-0"
                                                        :class="form.user_id == st.id ? 'bg-purple-600 text-white' : 'bg-purple-100 text-purple-700'"
                                                        x-text="(st.names || 'U').charAt(0).toUpperCase()">
                                                    </div>
                                                    <div class="leading-tight truncate">
                                                        <div class="font-bold text-gray-900 text-xs truncate" x-text="st.names"></div>
                                                        <div class="text-[11px] text-gray-500 flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-gray-100 text-gray-700">
                                                                DNI: <span x-text="st.dni || 'S/D'" class="ml-0.5"></span>
                                                            </span>
                                                            <template x-if="st.email">
                                                                <span class="text-gray-500 truncate" x-text="st.email"></span>
                                                            </template>
                                                            <template x-if="st.phone">
                                                                <span class="text-emerald-600 font-medium flex items-center gap-0.5">
                                                                    <i class="bi bi-telephone text-[9px]"></i> <span x-text="st.phone"></span>
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                                <template x-if="form.user_id == st.id">
                                                    <i class="bi bi-check2 text-purple-600 text-base font-bold ml-2"></i>
                                                </template>
                                            </button>
                                        </li>
                                    </template>
                                </ul>

                                {{-- Footer --}}
                                <div class="px-3.5 py-2 bg-gray-50 flex items-center justify-between text-[11px] text-gray-500">
                                    <span x-text="studentResults.length > 0 ? (studentResults.length + ' de máx. 5 coincidencias') : 'Filtro: Nombre, DNI, Correo, Teléfono'"></span>
                                    <span class="text-[10px] text-purple-600 font-semibold uppercase">Primeros 5 registros</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Curso <span class="text-red-500">*</span>
                            </label>
                            <select name="course_id" x-model="form.course_id" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="">-- Seleccione curso --</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Format & Participation Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Formato Certificado
                            </label>
                            <select name="certificate_type" x-model="form.certificate_type"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="capacitacion">Capacitación (Con Temario y QR)</option>
                                <option value="modular">Modular (Con Notas y Créditos)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Calidad / Condición
                            </label>
                            <input type="text" name="participation_type" x-model="form.participation_type"
                                placeholder="Ej: ASISTENTE, PONENTE"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Marco / Evento
                            </label>
                            <input type="text" name="event_name" x-model="form.event_name"
                                placeholder="Ej: Semana Técnica 2026"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold">
                        </div>
                    </div>

                    {{-- Code, Modality & Duration Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="certificate_code" x-model="form.certificate_code" required maxlength="100"
                                    placeholder="Ej: CERT-2026-001"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 uppercase font-mono font-bold">
                                <button type="button" @click="generateCode()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] font-bold text-purple-600 hover:text-purple-800 bg-purple-50 px-1.5 py-0.5 rounded">
                                    Generar
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Modalidad <span class="text-red-500">*</span>
                            </label>
                            <select name="modality" x-model="form.modality" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 bg-white font-medium">
                                <option value="Presencial">Presencial</option>
                                <option value="Semipresencial">Semipresencial</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Duración / Horas
                            </label>
                            <input type="text" name="duration" x-model="form.duration" maxlength="100"
                                placeholder="Ej: 120 Horas"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium">
                        </div>
                    </div>

                    {{-- Dates Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Inicio
                            </label>
                            <input type="date" name="start_date" x-model="form.start_date"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Fin
                            </label>
                            <input type="date" name="end_date" x-model="form.end_date"
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Fecha Emisión <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="issue_date" x-model="form.issue_date" required
                                class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-bold">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Descripción / Mención
                        </label>
                        <textarea name="description" x-model="form.description" rows="2" maxlength="500"
                            placeholder="Por haber aprobado satisfactoriamente el curso de..."
                            class="w-full text-sm border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"></textarea>
                    </div>

                    {{-- Active Toggle --}}
                    <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Certificado Válido / Activo</h4>
                            <p class="text-xs text-gray-500">Los certificados activos son visibles y verificables en el portal institucional.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="cert_is_active" name="is_active" value="1" x-model="form.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-bold shadow transition flex items-center gap-2">
                            <i class="bi bi-check2"></i>
                            <span x-text="isEdit ? 'Guardar Cambios' : 'Emitir Certificado'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══ MODAL VIEW & MANAGE MODULE SCORES (DETAILS) ═════════════ --}}
        <div x-show="detailsModalOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

            <div @click.outside="detailsModalOpen = false"
                @keydown.escape.window="detailsModalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Calificaciones por Módulo</h3>
                            <p class="text-xs text-gray-500" x-text="'Certificado: ' + (activeCert?.certificate_code || '')"></p>
                        </div>
                    </div>
                    <button type="button" @click="detailsModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-5 overflow-y-auto flex-1">
                    {{-- Certificate Summary Card --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-gray-500 font-semibold block">Estudiante:</span>
                            <span class="font-bold text-gray-800 text-sm" x-text="activeCert?.user?.names || 'N/A'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Curso:</span>
                            <span class="font-bold text-purple-700 text-sm" x-text="activeCert?.course?.name || 'N/A'"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold block">Fecha Emisión:</span>
                            <span class="font-bold text-gray-700" x-text="activeCert?.issue_date || 'N/A'"></span>
                        </div>
                    </div>

                    {{-- Add / Update Score Form --}}
                    <form :action="activeCert ? `{{ url('admin-certificados') }}/${activeCert.id}/detalles` : '#'" method="POST"
                        class="bg-purple-50/50 border border-purple-200 rounded-xl p-4 space-y-3">
                        @csrf
                        <div class="text-xs font-bold text-purple-900 uppercase tracking-wider flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-plus-circle-fill text-purple-600"></i>
                                <span x-text="detailForm.module_id && getExistingScore(detailForm.module_id) ? 'Actualizar Nota de Módulo' : 'Asignar Nota de Módulo'"></span>
                            </span>
                            <template x-if="detailForm.module_id">
                                <button type="button" @click="resetDetailForm()" class="text-[11px] text-gray-500 hover:text-purple-700 underline font-normal cursor-pointer">
                                    Limpiar campos
                                </button>
                            </template>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-7">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Módulo asociado al curso <span class="text-red-500">*</span>
                                </label>
                                <select name="module_id" x-model="detailForm.module_id" @change="onDetailModuleChange()" required
                                    class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 bg-white font-medium focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                    <option value="">-- Seleccionar Módulo --</option>
                                    <template x-if="isLoadingModules">
                                        <option value="" disabled>Cargando módulos del curso...</option>
                                    </template>
                                    <template x-if="!isLoadingModules && availableModules.length === 0">
                                        <option value="" disabled>No hay módulos registrados para este curso</option>
                                    </template>
                                    <template x-for="mod in availableModules" :key="mod.id">
                                        <option :value="mod.id"
                                            x-text="mod.name + (mod.credits ? ' (' + mod.credits + ' créditos)' : '') + (getExistingScore(mod.id) ? ' — [Nota actual: ' + getExistingScore(mod.id) + ']' : '')">
                                        </option>
                                    </template>
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Calificación / Nota *</label>
                                <input type="text" name="score" x-model="detailForm.score" required placeholder="Ej: 18, Aprobado" maxlength="50"
                                    class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 font-bold focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                            </div>
                            <div class="sm:col-span-2">
                                <button type="submit"
                                    class="w-full py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold shadow transition flex items-center justify-center gap-1 cursor-pointer">
                                    <i class="bi bi-save"></i>
                                    <span x-text="detailForm.module_id && getExistingScore(detailForm.module_id) ? 'Actualizar' : 'Guardar'"></span>
                                </button>
                            </div>
                        </div>

                        {{-- Helper / Status Messages --}}
                        <template x-if="isLoadingModules">
                            <p class="text-[11px] text-purple-600 flex items-center gap-1">
                                <i class="bi bi-arrow-repeat animate-spin"></i> Cargando lista de módulos correspondientes al curso...
                            </p>
                        </template>
                        <template x-if="!isLoadingModules && availableModules.length === 0">
                            <p class="text-[11px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-2.5 flex items-center gap-1.5">
                                <i class="bi bi-exclamation-triangle-fill text-amber-500"></i>
                                Este curso aún no tiene módulos registrados o activos. Puede crearlos en el módulo de Cursos y Módulos.
                            </p>
                        </template>
                        <template x-if="!isLoadingModules && availableModules.length > 0">
                            <p class="text-[11px] text-gray-500">
                                Mostrando <strong class="text-purple-700" x-text="availableModules.length"></strong> módulo(s) asociados a este curso. Seleccione el módulo e ingrese la calificación correspondiente.
                            </p>
                        </template>
                    </form>

                    {{-- List of Current Details --}}
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Módulos Registrados</h4>
                        <template x-if="!activeCert?.details || activeCert.details.length === 0">
                            <div class="p-6 text-center text-xs text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                                No se han registrado notas modulares para este certificado aún.
                            </div>
                        </template>
                        <template x-if="activeCert?.details && activeCert.details.length > 0">
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <table class="min-w-full text-xs">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-bold text-gray-600 uppercase">Módulo</th>
                                            <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Créditos</th>
                                            <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Calificación</th>
                                            <th class="px-3 py-2 text-center font-bold text-gray-600 uppercase">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <template x-for="d in activeCert.details" :key="d.id">
                                            <tr class="hover:bg-gray-50/80">
                                                <td class="px-3 py-2 font-semibold text-gray-800" x-text="d.module?.name || 'Módulo #' + d.module_id"></td>
                                                <td class="px-3 py-2 text-center text-gray-500" x-text="d.module?.credits || '—'"></td>
                                                <td class="px-3 py-2 text-center">
                                                    <span class="px-2 py-0.5 rounded-full font-bold bg-purple-100 text-purple-800" x-text="d.score || 'Aprobado'"></span>
                                                </td>
                                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <button type="button" @click="editDetail(d)"
                                                            class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-1 rounded transition cursor-pointer"
                                                            title="Editar calificación de este módulo">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form :action="`{{ url('admin-certificados/detalles') }}/${d.id}`" method="POST"
                                                            onsubmit="return confirm('¿Eliminar esta calificación?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition cursor-pointer"
                                                                title="Eliminar calificación">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-end shrink-0">
                    <button type="button" @click="detailsModalOpen = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs font-bold transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ MODAL IMPORTAR EXCEL / CSV ════════════════════════════════ --}}
        <div x-show="importModalOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

            <div @click.outside="importModalOpen = false"
                @keydown.escape.window="importModalOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl overflow-hidden flex flex-col max-h-[92vh]">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-700 text-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-md text-white flex items-center justify-center text-2xl shadow-inner border border-white/20">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold tracking-tight">Importación Masiva de Certificados</h3>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-400/30 text-emerald-100 border border-emerald-300/30">
                                    XLSX • XLS • CSV
                                </span>
                            </div>
                            <p class="text-xs text-emerald-100/90">Carga masiva, registro de notas modulares y control anti-duplicados</p>
                        </div>
                    </div>
                    <button type="button" @click="importModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition-colors">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Navigation Tabs --}}
                <div class="flex items-center gap-2 px-6 pt-3 border-b border-gray-200 bg-gray-50/80 shrink-0">
                    <button type="button" @click="importTab = 'upload'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'upload' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                        <i class="bi bi-cloud-arrow-up text-sm"></i>
                        1. Cargar Archivo y Plantillas
                    </button>
                    <button type="button" @click="importTab = 'examples'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'examples' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                        <i class="bi bi-table text-sm"></i>
                        2. Ejemplos de Registros
                        <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Condición / Rol
                        </span>
                    </button>
                    <button type="button" @click="importTab = 'guide'"
                        class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition-all border-b-2 flex items-center gap-2 -mb-px"
                        :class="importTab === 'guide' ? 'border-emerald-600 text-emerald-800 bg-white shadow-sm' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/60'">
                        <i class="bi bi-card-list text-sm"></i>
                        3. Estructura de Columnas (A–P)
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5 overflow-y-auto flex-1 bg-white">

                    {{-- ══ TAB 1: UPLOAD & DOWNLOAD TEMPLATES ════════════════ --}}
                    <div x-show="importTab === 'upload'" class="space-y-5">

                        {{-- Download Template Banner --}}
                        <div class="relative overflow-hidden p-5 bg-gradient-to-br from-indigo-50/90 via-purple-50/50 to-emerald-50/60 border border-indigo-100 rounded-2xl shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-100/80 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                            <i class="bi bi-file-earmark-check"></i> Plantilla Guía Oficial
                                        </span>
                                        <span class="text-xs text-gray-500 font-medium">Formatos soportados: <strong>.xlsx</strong>, <strong>.xls</strong>, <strong>.csv</strong></span>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900">¿Primera vez o necesitas la estructura exacta?</h4>
                                    <p class="text-xs text-gray-600 max-w-xl">
                                        Descarga el documento guía prediseñado. Incluye los 16 campos oficiales (A–P), filas de ejemplo ilustrando la <strong>Condición / Participación</strong> (<code class="bg-indigo-100/70 text-indigo-800 px-1 py-0.5 rounded text-[11px]">participation_type</code>: ASISTENTE, PONENTE, ORGANIZADOR) y una hoja complementaria con instrucciones detalladas.
                                    </p>
                                </div>

                                {{-- Download Action Buttons --}}
                                <div class="flex flex-wrap sm:flex-col gap-2 shrink-0">
                                    <a href="{{ route('admin.certificates.template', ['format' => 'xlsx']) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-sm transition hover:shadow flex-1 sm:flex-none">
                                        <i class="bi bi-file-earmark-excel-fill text-base"></i>
                                        <span>Descargar Excel (.xlsx)</span>
                                        <span class="text-[10px] bg-emerald-500/50 px-1.5 py-0.5 rounded-md font-semibold text-emerald-100">Recomendado</span>
                                    </a>
                                    <a href="{{ route('admin.certificates.template', ['format' => 'csv']) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold shadow-sm transition flex-1 sm:flex-none">
                                        <i class="bi bi-filetype-csv text-base text-gray-500"></i>
                                        <span>Descargar CSV (.csv)</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Form --}}
                        <form id="import-cert-form"
                            action="{{ route('admin.certificates.import') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            {{-- Drag & Drop Zone --}}
                            <div class="relative">
                                <label for="cert-import-file"
                                    class="flex flex-col items-center justify-center w-full border-2 border-dashed rounded-2xl cursor-pointer transition-all p-6"
                                    :class="fileName ? 'border-emerald-500 bg-emerald-50/50' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'"
                                    @dragover.prevent
                                    @drop.prevent="
                                        const f = $event.dataTransfer.files[0];
                                        if (f) { fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                                    ">
                                    <div class="text-center space-y-3">
                                        <template x-if="!fileName">
                                            <div class="space-y-2">
                                                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl shadow-sm">
                                                    <i class="bi bi-cloud-arrow-up"></i>
                                                </div>
                                                <p class="text-sm font-bold text-gray-800">
                                                    Arrastra tu archivo aquí o <span class="text-emerald-600 underline">haz clic para examinar</span>
                                                </p>
                                                <div class="flex items-center justify-center gap-2 pt-1">
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">.XLSX</span>
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 border border-teal-200">.XLS</span>
                                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 border border-blue-200">.CSV</span>
                                                    <span class="text-xs text-gray-400">• Máximo 10 MB</span>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="fileName">
                                            <div class="space-y-2">
                                                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-3xl shadow-md">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                </div>
                                                <p class="text-sm font-extrabold text-emerald-900" x-text="fileName"></p>
                                                <div class="flex items-center justify-center gap-2">
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full">
                                                        <i class="bi bi-check-circle-fill text-[11px]"></i> Archivo preparado para procesar
                                                    </span>
                                                    <button type="button" @click.stop="fileName = ''; $refs.fileInput.value = ''"
                                                        class="text-xs font-bold text-red-600 hover:text-red-700 underline">
                                                        Cambiar archivo
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </label>
                                <input id="cert-import-file" name="file" type="file"
                                    accept=".xlsx,.xls,.csv"
                                    x-ref="fileInput"
                                    @change="fileName = $event.target.files[0]?.name || ''"
                                    class="sr-only">
                            </div>

                            {{-- Highlight Rules Box --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                                    <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                                        <i class="bi bi-shield-check text-emerald-600"></i> Control Anti-duplicados
                                    </div>
                                    <p class="text-gray-500 leading-snug">Se valida que no exista un certificado previo para el mismo estudiante, curso/evento y fecha.</p>
                                </div>

                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                                    <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                                        <i class="bi bi-person-badge text-indigo-600"></i> Condición de Participación
                                    </div>
                                    <p class="text-gray-500 leading-snug">Columna P: <code class="text-indigo-600 font-bold">ASISTENTE</code>, <code class="text-purple-600 font-bold">PONENTE</code>, <code class="text-emerald-600 font-bold">ORGANIZADOR</code> (por defecto ASISTENTE).</p>
                                </div>

                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl">
                                    <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                                        <i class="bi bi-upc-scan text-purple-600"></i> Auto-código Inteligente
                                    </div>
                                    <p class="text-gray-500 leading-snug">Columna O: Si se deja vacía, se autogenera <code class="font-mono text-purple-700 bg-purple-50 px-1 rounded">CERT-{DNI}-{secuencia}</code>.</p>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="importTab = 'examples'"
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                        <i class="bi bi-eye"></i> Ver ejemplos de filas
                                    </button>
                                    <span class="text-gray-300">•</span>
                                    <button type="button" @click="importTab = 'guide'"
                                        class="text-xs font-bold text-gray-600 hover:text-gray-800 flex items-center gap-1">
                                        <i class="bi bi-list-columns"></i> Ver guía de columnas
                                    </button>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" @click="importModalOpen = false"
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2"
                                        :disabled="!fileName"
                                        :class="!fileName ? 'opacity-50 cursor-not-allowed' : ''">
                                        <i class="bi bi-upload"></i>
                                        <span>Procesar e Importar</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- ══ TAB 2: EXAMPLES (FEATURING PARTICIPATION_TYPE) ═══ --}}
                    <div x-show="importTab === 'examples'" class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 bg-indigo-50/70 border border-indigo-100 rounded-xl">
                            <div>
                                <h4 class="text-xs font-extrabold text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-award-fill text-indigo-600"></i> Ejemplos Reales con Condición / Tipo de Participación
                                </h4>
                                <p class="text-xs text-indigo-700 mt-0.5">
                                    Observe cómo el campo <strong>participation_type</strong> (Columna P) permite definir roles institucionales específicos (ASISTENTE, PONENTE, ORGANIZADOR, etc.), mientras que la columna Código (Columna O) puede ingresarse manualmente o dejarse vacía para autogeneración.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('admin.certificates.template', ['format' => 'xlsx']) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition whitespace-nowrap">
                                    <i class="bi bi-download"></i> Descargar Excel (.xlsx)
                                </a>
                            </div>
                        </div>

                        {{-- Table of Examples --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs divide-y divide-gray-200">
                                    <thead class="bg-gray-50 font-bold text-gray-700 uppercase tracking-wider text-[11px]">
                                        <tr>
                                            <th class="px-3 py-2.5 text-center bg-gray-100/60">Col A (N°)</th>
                                            <th class="px-3 py-2.5 text-left">Col B (DNI)</th>
                                            <th class="px-3 py-2.5 text-left">Col C (Estudiante)</th>
                                            <th class="px-3 py-2.5 text-left">Col D (Curso)</th>
                                            <th class="px-3 py-2.5 text-center">Fechas (E, F, G)</th>
                                            <th class="px-3 py-2.5 text-center">Col H (Horas)</th>
                                            <th class="px-3 py-2.5 text-center">Notas (I, K)</th>
                                            <th class="px-3 py-2.5 text-center">Col N (Modalidad)</th>
                                            <th class="px-3 py-2.5 text-left bg-purple-50/50 text-purple-900 border-l border-r border-purple-100">
                                                Col O (Código)
                                            </th>
                                            <th class="px-3 py-2.5 text-left bg-emerald-50 text-emerald-900 font-extrabold border-l border-emerald-200">
                                                <i class="bi bi-star-fill text-emerald-500 mr-1"></i> Col P (Condición / Rol)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        {{-- Row 1: ASISTENTE --}}
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 bg-gray-50/40">1</td>
                                            <td class="px-3 py-2.5 font-mono font-bold text-gray-900">74123456</td>
                                            <td class="px-3 py-2.5 font-medium text-gray-800 whitespace-nowrap">García López, María Elena</td>
                                            <td class="px-3 py-2.5 font-semibold text-indigo-700 whitespace-nowrap">Excel Avanzado</td>
                                            <td class="px-3 py-2.5 text-center text-gray-500 font-mono whitespace-nowrap">
                                                <div>2026-01-10 → 2026-03-20</div>
                                                <div class="text-[10px] text-gray-400">Emisión: 2026-03-21</div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-600">120 Horas</td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700">M1: 17</span>
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700 ml-1">M2: 19</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Presencial</span>
                                            </td>
                                            <td class="px-3 py-2.5 font-mono text-purple-700 font-bold bg-purple-50/30 whitespace-nowrap border-l border-r border-purple-100">
                                                CERT-74123456-1
                                            </td>
                                            <td class="px-3 py-2.5 bg-emerald-50/40 whitespace-nowrap border-l border-emerald-200">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-300">
                                                    <i class="bi bi-person-check-fill text-[11px]"></i> ASISTENTE
                                                </span>
                                            </td>
                                        </tr>

                                        {{-- Row 2: PONENTE with empty code (auto-generated) --}}
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 bg-gray-50/40">2</td>
                                            <td class="px-3 py-2.5 font-mono font-bold text-gray-900">71234568</td>
                                            <td class="px-3 py-2.5 font-medium text-gray-800 whitespace-nowrap">Rodríguez Quispe, Carlos Alberto</td>
                                            <td class="px-3 py-2.5 font-semibold text-indigo-700 whitespace-nowrap">Desarrollo Web Full Stack</td>
                                            <td class="px-3 py-2.5 text-center text-gray-500 font-mono whitespace-nowrap">
                                                <div>2026-02-01 → 2026-04-15</div>
                                                <div class="text-[10px] text-gray-400">Emisión: 2026-04-16</div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-600">180 Horas</td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700">M1: 20</span>
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700 ml-1">M2: 18</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Virtual</span>
                                            </td>
                                            <td class="px-3 py-2.5 font-mono text-purple-600 bg-purple-50/30 whitespace-nowrap border-l border-r border-purple-100">
                                                <span class="italic text-gray-400 text-[11px] font-sans">[Vacío → Auto: CERT-71234568-1]</span>
                                            </td>
                                            <td class="px-3 py-2.5 bg-emerald-50/40 whitespace-nowrap border-l border-emerald-200">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-300">
                                                    <i class="bi bi-mic-fill text-[11px]"></i> PONENTE
                                                </span>
                                            </td>
                                        </tr>

                                        {{-- Row 3: ORGANIZADOR --}}
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 bg-gray-50/40">3</td>
                                            <td class="px-3 py-2.5 font-mono font-bold text-gray-900">70987654</td>
                                            <td class="px-3 py-2.5 font-medium text-gray-800 whitespace-nowrap">Mamani Flores, Ana Lucía</td>
                                            <td class="px-3 py-2.5 font-semibold text-indigo-700 whitespace-nowrap">Inteligencia Artificial Aplicada</td>
                                            <td class="px-3 py-2.5 text-center text-gray-500 font-mono whitespace-nowrap">
                                                <div>2026-03-01 → 2026-05-10</div>
                                                <div class="text-[10px] text-gray-400">Emisión: 2026-05-12</div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-gray-600">90 Horas</td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700">M1: 18</span>
                                                <span class="px-1.5 py-0.5 rounded bg-gray-100 font-mono font-bold text-gray-700 ml-1">M2: 17</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Semipresencial</span>
                                            </td>
                                            <td class="px-3 py-2.5 font-mono text-purple-700 font-bold bg-purple-50/30 whitespace-nowrap border-l border-r border-purple-100">
                                                CERT-IA-2026-003
                                            </td>
                                            <td class="px-3 py-2.5 bg-emerald-50/40 whitespace-nowrap border-l border-emerald-200">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    <i class="bi bi-award text-[11px]"></i> ORGANIZADOR
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Explanatory Note on Participation Type --}}
                        <div class="flex items-start gap-3 p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-xl text-xs text-emerald-900">
                            <div class="w-6 h-6 rounded-lg bg-emerald-200 text-emerald-800 flex items-center justify-center shrink-0 text-sm font-bold">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="space-y-1">
                                <p class="font-bold">Efecto del campo «Condición / Participación» en el Certificado Oficial:</p>
                                <p class="text-emerald-800 leading-relaxed">
                                    El valor ingresado en la <strong>Columna P</strong> se reflejará directamente en la impresión oficial y en la vista de validación pública QR («en su calidad de <strong>PONENTE</strong>», «en calidad de <strong>ASISTENTE</strong>», etc.). Si se omite o se deja en blanco en el archivo, el sistema asignará <strong>ASISTENTE</strong> automáticamente.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" @click="importTab = 'upload'"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                                <i class="bi bi-arrow-left"></i> Volver a la Carga de Archivos
                            </button>
                        </div>
                    </div>

                    {{-- ══ TAB 3: COMPLETE COLUMN GUIDE (A–P) ════════════════ --}}
                    <div x-show="importTab === 'guide'" class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-xl">
                            <div>
                                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Estructura Detallada de Columnas (16 Columnas: A a P)</h4>
                                <p class="text-xs text-gray-500">Revise la posición, obligatoriedad y función de cada columna en el archivo de importación.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.certificates.template', ['format' => 'xlsx']) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                                    <i class="bi bi-file-earmark-excel"></i> Descargar Plantilla (.xlsx)
                                </a>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="overflow-x-auto max-h-[50vh]">
                                <table class="min-w-full text-xs divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0 font-bold text-gray-700 uppercase tracking-wider text-[11px] shadow-sm">
                                        <tr>
                                            <th class="px-3 py-2 text-center">Col</th>
                                            <th class="px-3 py-2 text-left">Encabezado en Plantilla</th>
                                            <th class="px-3 py-2 text-center">Estado</th>
                                            <th class="px-3 py-2 text-left">Campo Destino</th>
                                            <th class="px-3 py-2 text-left">Descripción y Reglas de Importación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">A</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">N°</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Número correlativo de fila. Solo referencia visual.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">B</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">DNI / N° Documento</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">user.dni</td>
                                            <td class="px-3 py-2 text-gray-600">DNI del estudiante. Si no existe en el sistema, se crea un usuario automáticamente con contraseña provisional.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">C</td>
                                            <td class="px-3 py-2 font-semibold text-gray-500">Apellidos y Nombres</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">REFERENCIA</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-400">user.names</td>
                                            <td class="px-3 py-2 text-gray-500">Nombre de referencia. Si el usuario ya existe se conserva su nombre en base de datos.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">D</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">Curso</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">course_id</td>
                                            <td class="px-3 py-2 text-gray-600">Nombre exacto del curso registrado en el sistema (búsqueda sin distinción de mayúsculas).</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">E</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">Fecha de Inicio</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-600">start_date</td>
                                            <td class="px-3 py-2 text-gray-600">Fecha de inicio del curso en formato <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">F</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">Fecha de Término</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-600">end_date</td>
                                            <td class="px-3 py-2 text-gray-600">Fecha de finalización del curso en formato <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">G</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">Fecha de Emisión</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">issue_date</td>
                                            <td class="px-3 py-2 text-gray-600">Fecha oficial de expedición del certificado en formato <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">H</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">Horas</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-gray-600">duration</td>
                                            <td class="px-3 py-2 text-gray-600">Carga horaria o duración. Ejemplo: "120 Horas".</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-indigo-700">I</td>
                                            <td class="px-3 py-2 font-semibold text-indigo-900">Calificación I (numérica)</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">score (Módulo 1)</td>
                                            <td class="px-3 py-2 text-gray-600">Nota numérica registrada en el primer módulo del curso según orden.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">J</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">Calificación Letras I</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Nota en letras del módulo 1. No se almacena.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-indigo-700">K</td>
                                            <td class="px-3 py-2 font-semibold text-indigo-900">Calificación II (numérica)</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">score (Módulo 2)</td>
                                            <td class="px-3 py-2 text-gray-600">Nota numérica registrada en el segundo módulo del curso según orden.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">L</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">Calificación Letras II</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Nota en letras del módulo 2. No se almacena.</td>
                                        </tr>
                                        <tr class="bg-gray-50/50">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-gray-400">M</td>
                                            <td class="px-3 py-2 font-semibold text-gray-400">Promedio</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">IGNORADA</span></td>
                                            <td class="px-3 py-2 text-gray-400 italic">—</td>
                                            <td class="px-3 py-2 text-gray-400">Promedio ponderado calculado automáticamente por el sistema.</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50/70">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">N</td>
                                            <td class="px-3 py-2 font-bold text-gray-900">Modalidad</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">REQUERIDO</span></td>
                                            <td class="px-3 py-2 font-mono text-indigo-700">modality</td>
                                            <td class="px-3 py-2 text-gray-600">Acepta exactamente: <code class="font-bold text-gray-700">Presencial</code>, <code class="font-bold text-gray-700">Virtual</code> o <code class="font-bold text-gray-700">Semipresencial</code>.</td>
                                        </tr>
                                        <tr class="hover:bg-purple-50/30">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-purple-700">O</td>
                                            <td class="px-3 py-2 font-bold text-purple-900">Código</td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700">AUTO-CÓDIGO</span></td>
                                            <td class="px-3 py-2 font-mono text-purple-700">code</td>
                                            <td class="px-3 py-2 text-gray-600">Código identificador único. Si está vacío, se auto-genera como <code class="bg-purple-100 text-purple-800 px-1 py-0.5 rounded font-bold font-mono">CERT-{DNI}-{secuencia}</code>. Si se ingresa, se valida unicidad.</td>
                                        </tr>
                                        <tr class="hover:bg-emerald-50/40 bg-emerald-50/20">
                                            <td class="px-3 py-2 text-center font-mono font-bold text-emerald-700">P</td>
                                            <td class="px-3 py-2 font-extrabold text-emerald-950 flex items-center gap-1.5">
                                                <i class="bi bi-star-fill text-emerald-600 text-[10px]"></i> Condición / Participación
                                            </td>
                                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">OPCIONAL</span></td>
                                            <td class="px-3 py-2 font-mono text-emerald-800 font-bold">participation_type</td>
                                            <td class="px-3 py-2 text-gray-700">Rol o condición académica: <code class="bg-white border px-1 rounded font-bold text-blue-700">ASISTENTE</code>, <code class="bg-white border px-1 rounded font-bold text-purple-700">PONENTE</code>, <code class="bg-white border px-1 rounded font-bold text-emerald-700">ORGANIZADOR</code>, etc. Si está vacío se asigna <strong>ASISTENTE</strong>.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" @click="importTab = 'upload'"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
                                <i class="bi bi-arrow-left"></i> Volver a la Carga de Archivos
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Modal: Confirm Bulk Delete Selected ── --}}
        <div x-show="bulkDeleteModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs animate-fade-in"
            @keydown.escape.window="bulkDeleteModalOpen = false">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-gray-100 overflow-hidden"
                @click.outside="bulkDeleteModalOpen = false">
                <div class="p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4 border border-rose-200">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-2">Eliminar Certificados Seleccionados</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        ¿Está seguro de que desea eliminar los <strong class="text-rose-600 font-extrabold" x-text="selectedCerts.length"></strong> certificados seleccionados?
                    </p>
                    <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-xs text-rose-800 text-left mb-6 flex items-start gap-2.5">
                        <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base shrink-0 mt-0.5"></i>
                        <span>Esta acción eliminará de forma permanente los registros y sus calificaciones modulares asociadas. No se puede deshacer.</span>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="bulkDeleteModalOpen = false"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition cursor-pointer">
                            Cancelar
                        </button>
                        <form action="{{ route('admin.certificates.bulk-delete') }}" method="POST" class="inline">
                            @csrf
                            <template x-for="id in selectedCerts" :key="id">
                                <input type="hidden" name="ids[]" :value="id">
                            </template>
                            <button type="submit"
                                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/30 transition flex items-center gap-2 cursor-pointer">
                                <i class="bi bi-trash-fill"></i> Sí, eliminar seleccionados
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Modal: Confirm Delete All Records ── --}}
        <div x-show="deleteAllModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs animate-fade-in"
            @keydown.escape.window="deleteAllModalOpen = false">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-gray-100 overflow-hidden"
                @click.outside="deleteAllModalOpen = false">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4 border border-red-200 animate-pulse">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">¿Eliminar TODOS los Certificados?</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Está a punto de eliminar <strong class="text-red-600 font-extrabold">{{ number_format($totalCertificates) }}</strong> certificados registrados en la plataforma.
                    </p>
                    <div class="bg-red-50 border border-red-300 rounded-xl p-3.5 text-xs text-red-800 text-left mb-6 space-y-1">
                        <p class="font-bold flex items-center gap-1.5 text-red-900">
                            <i class="bi bi-exclamation-triangle-fill text-red-600"></i> ACCIÓN DE ALTO RIESGO
                        </p>
                        <p>Esta acción vaciará por completo la base de datos de certificados y sus calificaciones. No podrá recuperar esta información.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="deleteAllModalOpen = false"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition cursor-pointer">
                            Cancelar
                        </button>
                        <form action="{{ route('admin.certificates.delete-all') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-md shadow-red-600/30 transition flex items-center gap-2 cursor-pointer">
                                <i class="bi bi-trash3-fill"></i> Sí, eliminar TODOS los registros
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Modal: Bulk Export Certificates in PDF ── --}}
        <div x-show="bulkPdfModalOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs animate-fade-in"
            @keydown.escape.window="bulkPdfModalOpen = false">
            <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]"
                @click.outside="bulkPdfModalOpen = false">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 px-6 py-4 flex items-center justify-between text-white shrink-0 shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl shadow-inner">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black tracking-tight leading-tight">Exportación Masiva de Certificados en PDF</h3>
                            <p class="text-xs text-rose-100">Genere el lote oficial de certificados listos para impresión o guardado como PDF</p>
                        </div>
                    </div>
                    <button type="button" @click="bulkPdfModalOpen = false"
                        class="text-white/80 hover:text-white p-1.5 rounded-xl hover:bg-white/10 transition-colors cursor-pointer">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('admin.certificates.bulk-export-pdf') }}" method="POST" target="_blank"
                    @submit="bulkPdfModalOpen = false" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    <input type="hidden" name="scope" value="courses">

                    {{-- Hidden inputs for course ids --}}
                    <template x-for="cid in selectedCourseIds" :key="'bulk-pdf-course-' + cid">
                        <input type="hidden" name="course_ids[]" :value="cid">
                    </template>

                    {{-- Scrollable Content Body --}}
                    <div class="p-6 space-y-5 overflow-y-auto flex-1 text-gray-800">

                        {{-- Section: Course Selection --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <i class="bi bi-journal-bookmark-fill text-rose-600"></i>
                                    <span>Cursos a Exportar</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-extrabold"
                                        x-text="selectedCourseIds.includes('all') ? 'Todos los Cursos' : `${selectedCourseIds.filter(id => id !== 'all').length} seleccionados`"></span>
                                </label>
                                <div class="flex items-center gap-2 text-xs">
                                    <button type="button" @click="selectAllCourses()"
                                        class="text-rose-600 hover:text-rose-800 hover:underline font-bold text-[11px] cursor-pointer">
                                        Seleccionar Todos
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" @click="deselectAllCourses()"
                                        class="text-gray-500 hover:text-gray-700 hover:underline font-bold text-[11px] cursor-pointer">
                                        Deseleccionar
                                    </button>
                                </div>
                            </div>

                            {{-- Search input inside courses --}}
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                                <input type="text" x-model="courseFilterText"
                                    placeholder="Filtrar cursos por nombre..."
                                    class="w-full pl-8 pr-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 focus:bg-white focus:border-rose-400 focus:ring-1 focus:ring-rose-400 focus:outline-hidden transition">
                            </div>

                            {{-- Course Checklist Box --}}
                            <div class="border border-gray-200 rounded-xl bg-gray-50/50 p-2 max-h-48 overflow-y-auto space-y-1 divide-y divide-gray-100">
                                {{-- Option: All Courses --}}
                                <label class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition"
                                    :class="selectedCourseIds.includes('all') ? 'bg-white font-bold text-rose-900 border border-rose-200 shadow-xs' : 'text-gray-700'">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox" :checked="selectedCourseIds.includes('all')"
                                            @change="toggleCourse('all')"
                                            class="rounded text-rose-600 focus:ring-rose-500 cursor-pointer">
                                        <span class="text-xs">📚 Todos los Cursos Registrados</span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-bold">
                                        {{ $totalCertificates }} certificados
                                    </span>
                                </label>

                                @foreach ($courses as $c)
                                    <label class="flex items-center justify-between p-2 rounded-lg hover:bg-white cursor-pointer transition"
                                        x-show="!courseFilterText || '{{ strtolower(addslashes($c->name)) }}'.includes(courseFilterText.toLowerCase())"
                                        :class="isCourseSelected('{{ $c->id }}') ? 'bg-white text-gray-900 font-semibold' : 'text-gray-600'">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <input type="checkbox" :checked="isCourseSelected('{{ $c->id }}')"
                                                @change="toggleCourse('{{ $c->id }}')"
                                                class="rounded text-rose-600 focus:ring-rose-500 cursor-pointer">
                                            <div class="truncate">
                                                <p class="text-xs truncate">{{ $c->name }}</p>
                                                @if($c->studyProgram)
                                                    <p class="text-[10px] text-gray-400 truncate">{{ $c->studyProgram->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            @if($c->certificate_type === 'ingles')
                                                <span class="text-[10px] px-1.5 py-0.2 rounded-full bg-blue-100 text-blue-700 font-bold">Inglés</span>
                                            @endif
                                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-200/80 text-gray-700 font-medium">
                                                {{ $c->certificates_count }} certs
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Section: Faces to Print (Ambas caras / Solo Frente / Solo Reverso) --}}
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                <i class="bi bi-file-earmark-break text-rose-600"></i> Páginas / Caras a Incluir
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                                <label class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition"
                                    :class="facesChoice === 'all' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-gray-200 bg-white hover:bg-gray-50'">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="faces" value="all" x-model="facesChoice" class="text-rose-600 focus:ring-rose-500">
                                        <span class="font-bold text-gray-900">Ambas Caras</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 leading-tight">Frente y Reverso con notas/temario</p>
                                </label>
                                <label class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition"
                                    :class="facesChoice === 'front' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-gray-200 bg-white hover:bg-gray-50'">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="faces" value="front" x-model="facesChoice" class="text-rose-600 focus:ring-rose-500">
                                        <span class="font-bold text-gray-900">Solo Anverso</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 leading-tight">Solo portada oficial principal</p>
                                </label>
                                <label class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition"
                                    :class="facesChoice === 'back' ? 'border-rose-500 bg-rose-50/50 shadow-xs' : 'border-gray-200 bg-white hover:bg-gray-50'">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="faces" value="back" x-model="facesChoice" class="text-rose-600 focus:ring-rose-500">
                                        <span class="font-bold text-gray-900">Solo Reverso</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 leading-tight">Solo calificaciones / QR posterior</p>
                                </label>
                            </div>
                        </div>

                        {{-- Section: Other Filter Options (Grid) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1 border-t border-gray-100">
                            {{-- Modalidad --}}
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Modalidad</label>
                                <select name="modality"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                                    <option value="all">Todas las Modalidades</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Virtual">Virtual</option>
                                    <option value="Semipresencial">Semipresencial</option>
                                </select>
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Estado de Certificados</label>
                                <select name="status"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                                    <option value="1" selected>Solo Activos (Habilitados)</option>
                                    <option value="all">Todos (Activos e Inactivos)</option>
                                    <option value="0">Solo Inactivos</option>
                                </select>
                            </div>

                            {{-- Tipo de Participación --}}
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Tipo de Participación</label>
                                <select name="participation_type"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                                    <option value="all">Todos los Tipos</option>
                                    <option value="ASISTENTE">ASISTENTE</option>
                                    <option value="ORGANIZADOR">ORGANIZADOR</option>
                                    <option value="PONENTE">PONENTE</option>
                                    <option value="DOCENTE">DOCENTE</option>
                                    <option value="PARTICIPANTE">PARTICIPANTE</option>
                                </select>
                            </div>

                            {{-- Ordenación --}}
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Criterio de Ordenación</label>
                                <select name="order_by"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                                    <option value="names_asc" selected>Apellidos y Nombres (A - Z)</option>
                                    <option value="issue_date_desc">Fecha de Emisión (Más reciente primero)</option>
                                    <option value="issue_date_asc">Fecha de Emisión (Más antiguo primero)</option>
                                    <option value="code_asc">Código de Certificado</option>
                                    <option value="course_name">Nombre de Curso</option>
                                </select>
                            </div>

                            {{-- Date From --}}
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Fecha Emisión Desde</label>
                                <input type="date" name="date_from"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                            </div>

                            {{-- Date To --}}
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Fecha Emisión Hasta</label>
                                <input type="date" name="date_to"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                            </div>
                        </div>

                        {{-- Output Settings & Tips --}}
                        <div class="space-y-2 pt-1 border-t border-gray-100">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-800">
                                <input type="checkbox" name="auto_print" value="1" checked
                                    class="rounded text-rose-600 focus:ring-rose-500 cursor-pointer">
                                <span>Abrir diálogo de impresión/guardado en PDF automáticamente al cargar</span>
                            </label>

                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-900 flex items-start gap-2.5">
                                <i class="bi bi-lightbulb-fill text-amber-600 text-base shrink-0 mt-0.5"></i>
                                <div class="space-y-0.5">
                                    <p class="font-bold text-amber-950">Consejo para exportar como archivo PDF:</p>
                                    <p class="text-[11px] text-amber-800 leading-normal">
                                        En la ventana del navegador que se abrirá, seleccione el Destino <strong>"Guardar como PDF"</strong>, tamaño <strong>A4</strong>, orientación <strong>Horizontal</strong> y márgenes <strong>"Ninguno"</strong> para asegurar un documento vectorial perfecto y nítido.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="bulkPdfModalOpen = false"
                            class="px-4 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold transition cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                            :disabled="selectedCourseIds.length === 0"
                            :class="selectedCourseIds.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] active:scale-[0.98]'"
                            class="px-6 py-2.5 bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 hover:from-red-700 hover:via-rose-700 hover:to-pink-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/30 hover:shadow-lg transition flex items-center gap-2 cursor-pointer">
                            <i class="bi bi-file-earmark-pdf-fill text-sm"></i>
                            <span>Generar y Exportar PDF</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function certificatesApp() {
        return {
            modalOpen: false,
            detailsModalOpen: false,
            importModalOpen: false,
            bulkDeleteModalOpen: false,
            deleteAllModalOpen: false,
            bulkPdfModalOpen: false,
            bulkPdfScope: 'courses',
            facesChoice: 'all',
            selectedCourseIds: ['all'],
            courseFilterText: '',
            allCourseIds: @json($courses->pluck('id')->values()->all()),

            openBulkPdfModal() {
                this.bulkPdfScope = 'courses';
                this.bulkPdfModalOpen = true;
            },

            selectAllCourses() {
                this.selectedCourseIds = ['all', ...this.allCourseIds.map(String)];
            },

            deselectAllCourses() {
                this.selectedCourseIds = [];
            },

            toggleCourse(id) {
                id = String(id);
                if (id === 'all') {
                    if (this.selectedCourseIds.includes('all')) {
                        this.selectedCourseIds = [];
                    } else {
                        this.selectedCourseIds = ['all', ...this.allCourseIds.map(String)];
                    }
                    return;
                }

                const index = this.selectedCourseIds.indexOf(id);
                if (index > -1) {
                    this.selectedCourseIds.splice(index, 1);
                    const allIdx = this.selectedCourseIds.indexOf('all');
                    if (allIdx > -1) this.selectedCourseIds.splice(allIdx, 1);
                } else {
                    this.selectedCourseIds.push(id);
                    if (this.allCourseIds.length > 0 && this.allCourseIds.every(cid => this.selectedCourseIds.includes(String(cid)))) {
                        if (!this.selectedCourseIds.includes('all')) {
                            this.selectedCourseIds.push('all');
                        }
                    }
                }
            },

            isCourseSelected(id) {
                id = String(id);
                return this.selectedCourseIds.includes('all') || this.selectedCourseIds.includes(id);
            },

            importTab: 'upload',
            fileName: '',
            isEdit: false,
            updateUrl: '',
            activeCert: null,
            availableModules: [],
            isLoadingModules: false,
            coursesData: @json($courses),
            detailForm: {
                module_id: '',
                score: '',
            },
            selectedCerts: [],
            pageCertIds: @json($certificates->pluck('id')->values()->all()),

            // Student Search & Select2-like State
            selectedStudent: null,
            studentSearch: '',
            studentResults: [],
            studentDropdownOpen: false,
            isSearchingStudents: false,
            studentSearchTimer: null,
            highlightedStudentIndex: 0,

            toggleStudentDropdown() {
                this.studentDropdownOpen = !this.studentDropdownOpen;
                if (this.studentDropdownOpen) {
                    this.$nextTick(() => {
                        if (this.$refs.studentSearchInput) {
                            this.$refs.studentSearchInput.focus();
                        }
                    });
                }
            },

            onStudentSearchInput() {
                clearTimeout(this.studentSearchTimer);
                const query = (this.studentSearch || '').trim();

                if (!query) {
                    this.studentResults = [];
                    this.isSearchingStudents = false;
                    this.highlightedStudentIndex = 0;
                    return;
                }

                this.isSearchingStudents = true;
                this.studentSearchTimer = setTimeout(() => {
                    fetch(`{{ route('admin.certificates.search-students') }}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (Array.isArray(data)) {
                                this.studentResults = data.slice(0, 5);
                                this.highlightedStudentIndex = 0;
                            }
                        })
                        .catch(err => {
                            console.error('Error buscando estudiantes:', err);
                            this.studentResults = [];
                        })
                        .finally(() => {
                            this.isSearchingStudents = false;
                        });
                }, 250);
            },

            navigateStudents(direction) {
                if (!this.studentResults.length) return;
                this.highlightedStudentIndex = (this.highlightedStudentIndex + direction + this.studentResults.length) % this.studentResults.length;
            },

            selectHighlightedStudent() {
                if (this.studentResults.length && this.studentResults[this.highlightedStudentIndex]) {
                    this.selectStudent(this.studentResults[this.highlightedStudentIndex]);
                }
            },

            selectStudent(student) {
                this.selectedStudent = student;
                this.form.user_id = student.id;
                this.studentDropdownOpen = false;
                this.studentSearch = '';
                this.studentResults = [];
            },

            clearStudentSelection() {
                this.selectedStudent = null;
                this.form.user_id = '';
                this.studentSearch = '';
                this.studentResults = [];
            },

            toggleSelectAll(checked) {
                if (checked) {
                    const currentSet = new Set(this.selectedCerts.map(Number));
                    this.pageCertIds.forEach(id => currentSet.add(Number(id)));
                    this.selectedCerts = Array.from(currentSet);
                } else {
                    const toRemove = new Set(this.pageCertIds.map(Number));
                    this.selectedCerts = this.selectedCerts.filter(id => !toRemove.has(Number(id)));
                }
            },

            isAllSelected() {
                if (!this.pageCertIds.length) return false;
                const currentSet = new Set(this.selectedCerts.map(Number));
                return this.pageCertIds.every(id => currentSet.has(Number(id)));
            },

            deselectAll() {
                this.selectedCerts = [];
            },

            form: {
                id: null,
                user_id: '',
                course_id: '',
                certificate_type: 'capacitacion',
                participation_type: 'ASISTENTE',
                event_name: '',
                certificate_code: '',
                description: '',
                start_date: '',
                end_date: '',
                duration: '',
                modality: 'Presencial',
                issue_date: '{{ date('Y-m-d') }}',
                is_active: true,
            },

            openCreateModal() {
                this.isEdit = false;
                this.updateUrl = '';
                this.clearStudentSelection();
                this.form = {
                    id: null,
                    user_id: '',
                    course_id: '',
                    certificate_type: 'capacitacion',
                    participation_type: 'ASISTENTE',
                    event_name: 'Semana Técnica 2026',
                    certificate_code: this.generateCodeStr(),
                    description: '',
                    start_date: '',
                    end_date: '',
                    duration: '90 horas pedagógicas',
                    modality: 'Presencial',
                    issue_date: '{{ date('Y-m-d') }}',
                    is_active: true,
                };
                this.modalOpen = true;
            },

            openEditModal(cert) {
                this.isEdit = true;
                this.updateUrl = `{{ url('admin-certificados') }}/${cert.id}`;
                this.studentSearch = '';
                this.studentResults = [];
                this.studentDropdownOpen = false;
                this.selectedStudent = cert.user ? {
                    id: cert.user.id,
                    names: cert.user.names,
                    dni: cert.user.dni || '',
                    email: cert.user.email || '',
                    phone: cert.user.phone || '',
                } : (cert.user_id ? {
                    id: cert.user_id,
                    names: 'Estudiante #' + cert.user_id,
                    dni: '',
                    email: '',
                    phone: '',
                } : null);

                this.form = {
                    id: cert.id,
                    user_id: cert.user_id || '',
                    course_id: cert.course_id || '',
                    certificate_type: cert.certificate_type || cert.course?.certificate_type || 'capacitacion',
                    participation_type: cert.participation_type || 'ASISTENTE',
                    event_name: cert.event_name || cert.course?.event_name || '',
                    certificate_code: cert.certificate_code || '',
                    description: cert.description || '',
                    start_date: cert.start_date ? cert.start_date.substring(0, 10) : '',
                    end_date: cert.end_date ? cert.end_date.substring(0, 10) : '',
                    duration: cert.duration || '',
                    modality: cert.modality || 'Presencial',
                    issue_date: cert.issue_date ? cert.issue_date.substring(0, 10) : '{{ date('Y-m-d') }}',
                    is_active: Boolean(cert.is_active),
                };
                this.modalOpen = true;
            },

            resetDetailForm() {
                this.detailForm = {
                    module_id: '',
                    score: '',
                };
            },

            openDetailsModal(cert) {
                this.activeCert = cert;
                this.resetDetailForm();
                this.availableModules = [];
                this.isLoadingModules = true;

                // 1. Initial resolution from cert.course.modules if present
                if (cert.course?.modules && Array.isArray(cert.course.modules) && cert.course.modules.length > 0) {
                    this.availableModules = cert.course.modules;
                    this.isLoadingModules = false;
                } else {
                    // 2. Fallback to coursesData
                    const found = this.coursesData.find(c => c.id == cert.course_id);
                    if (found?.modules && Array.isArray(found.modules) && found.modules.length > 0) {
                        this.availableModules = found.modules;
                        this.isLoadingModules = false;
                    }
                }

                // 3. Always fetch fresh modules for this course
                if (cert.course_id) {
                    fetch(`{{ url('admin-modulos/por-curso') }}/${cert.course_id}`)
                        .then(res => res.json())
                        .then(data => {
                            if (Array.isArray(data)) {
                                this.availableModules = data;
                            }
                        })
                        .catch(err => {
                            console.error('Error al cargar módulos del curso:', err);
                        })
                        .finally(() => {
                            this.isLoadingModules = false;
                        });
                } else {
                    this.isLoadingModules = false;
                }

                this.detailsModalOpen = true;
            },

            getExistingScore(moduleId) {
                if (!this.activeCert?.details || !moduleId) return null;
                const d = this.activeCert.details.find(item => item.module_id == moduleId);
                return d ? (d.score || 'Aprobado') : null;
            },

            onDetailModuleChange() {
                const existing = this.getExistingScore(this.detailForm.module_id);
                if (existing && !this.detailForm.score) {
                    this.detailForm.score = existing;
                }
            },

            editDetail(d) {
                this.detailForm.module_id = d.module_id;
                this.detailForm.score = d.score || '';
                this.$nextTick(() => {
                    const el = document.querySelector('input[name="score"]');
                    if (el) el.focus();
                });
            },

            generateCodeStr() {
                const year = new Date().getFullYear();
                const rand = Math.floor(1000 + Math.random() * 9000);
                return `CERT-FVC-${year}-${rand}`;
            },

            generateCode() {
                this.form.certificate_code = this.generateCodeStr();
            }
        }
    }
</script>
@endpush
