@extends('layouts.app')
@section('title', 'Gestión de Becas y Beneficiarios - Panel Administrativo')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .pdf-drop-zone {
            border: 2px dashed #cbd5e1;
            transition: all 0.2s ease-in-out;
        }

        .pdf-drop-zone:hover, .pdf-drop-zone.dragover {
            border-color: #7c3aed;
            background-color: #faf5ff;
        }
    </style>
@endpush

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="scholarshipsAdminApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            {{-- Header --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-award text-purple-600"></i> Gestión de Becas y Beneficiarios
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Panel
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Becas y Créditos</span>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div
                            class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
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
                        <div
                            class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-xl"></i>
                                <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                            </div>
                            <button type="button" class="text-rose-500 hover:text-rose-700"
                                onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-exclamation-octagon-fill text-rose-600 text-xl mt-0.5"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-rose-900">Se encontraron observaciones al procesar el formulario:</h4>
                                    <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Navigation Tabs --}}
                    <div class="flex flex-wrap gap-2 border-b border-gray-200">
                        <button type="button"
                            @click="switchTab('modalities')"
                            :class="activeTab === 'modalities' 
                                ? 'border-purple-600 text-purple-700 bg-white font-extrabold shadow-sm' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 font-semibold'"
                            class="px-5 py-3 rounded-t-2xl border-b-2 text-sm flex items-center gap-2.5 transition-all">
                            <i class="bi bi-award-fill text-base" :class="activeTab === 'modalities' ? 'text-purple-600' : 'text-gray-400'"></i>
                            <span>Modalidades de Beca</span>
                            <span class="px-2 py-0.5 text-xs rounded-full"
                                :class="activeTab === 'modalities' ? 'bg-purple-100 text-purple-800 font-black' : 'bg-gray-200 text-gray-600'">
                                {{ $totalModalities }}
                            </span>
                        </button>

                        <button type="button"
                            @click="switchTab('beneficiaries')"
                            :class="activeTab === 'beneficiaries' 
                                ? 'border-purple-600 text-purple-700 bg-white font-extrabold shadow-sm' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 font-semibold'"
                            class="px-5 py-3 rounded-t-2xl border-b-2 text-sm flex items-center gap-2.5 transition-all">
                            <i class="bi bi-file-earmark-pdf-fill text-base" :class="activeTab === 'beneficiaries' ? 'text-red-500' : 'text-gray-400'"></i>
                            <span>Padrón de Beneficiarios (PDF)</span>
                            <span class="px-2 py-0.5 text-xs rounded-full"
                                :class="activeTab === 'beneficiaries' ? 'bg-purple-100 text-purple-800 font-black' : 'bg-gray-200 text-gray-600'">
                                {{ $totalBeneficiaries }}
                            </span>
                        </button>
                    </div>

                    {{-- ══════════════════════════════════════════════════════ --}}
                    {{-- TAB 1: MODALIDADES DE BECA                             --}}
                    {{-- ══════════════════════════════════════════════════════ --}}
                    <div x-show="activeTab === 'modalities'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                        class="space-y-6">

                        {{-- Filter and Actions Bar --}}
                        <div
                            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                            <form action="{{ route('admin.scholarships.index') }}" method="GET"
                                class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                                <input type="hidden" name="tab" value="modalities">

                                {{-- Search Input --}}
                                <div class="relative w-full sm:max-w-md">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Buscar por nombre o descripción..."
                                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>

                                {{-- Status Filter --}}
                                <div class="w-full sm:w-auto">
                                    <select name="status" onchange="this.form.submit()"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                        <option value="">Todos los Estados</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
                                </div>

                                {{-- Submit / Clear Buttons --}}
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <button type="submit"
                                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                                        <i class="bi bi-funnel"></i> Filtrar
                                    </button>
                                    @if (request('search') || request('status') !== null)
                                        <a href="{{ route('admin.scholarships.index', ['tab' => 'modalities']) }}"
                                            class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                                            Limpiar
                                        </a>
                                    @endif
                                </div>
                            </form>

                            {{-- Create Button --}}
                            <a href="{{ route('admin.scholarships.create') }}"
                                class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                                <i class="bi bi-plus-circle-fill text-lg"></i>
                                <span>Nueva Beca / Modalidad</span>
                            </a>
                        </div>

                        {{-- Table Container --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse min-w-[850px]">
                                    <thead>
                                        <tr
                                            class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                            <th class="p-4 w-16 text-center">Icono</th>
                                            <th class="p-4">Nombre & Enlace Slug</th>
                                            <th class="p-4 text-center">Vacantes</th>
                                            <th class="p-4">Descuento / Beneficio</th>
                                            <th class="p-4">Descripción & Requisitos</th>
                                            <th class="p-4 text-center w-20">Orden</th>
                                            <th class="p-4 text-center w-28">Estado</th>
                                            <th class="p-4 text-center w-28">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($scholarships as $scholarship)
                                            <tr class="hover:bg-purple-50/30 transition-colors">
                                                {{-- Icon --}}
                                                <td class="p-4 text-center">
                                                    <div
                                                        class="w-10 h-10 mx-auto rounded-xl bg-purple-100/70 border border-purple-200 text-purple-700 flex items-center justify-center text-lg shadow-sm">
                                                        <i class="bi {{ $scholarship->icon ?? 'bi-award' }}"></i>
                                                    </div>
                                                </td>

                                                {{-- Name & Slug --}}
                                                <td class="p-4">
                                                    <div class="font-bold text-gray-900 text-sm sm:text-base">
                                                        {{ $scholarship->name }}
                                                    </div>
                                                    <div class="text-xs text-purple-600 font-mono mt-0.5">
                                                        /{{ $scholarship->slug }}
                                                    </div>
                                                </td>

                                                {{-- Vacancies --}}
                                                <td class="p-4 text-center">
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-800 border border-blue-200">
                                                        <i class="bi bi-people-fill text-blue-600"></i>
                                                        {{ $scholarship->vacancies ?? 0 }} vac.
                                                    </span>
                                                </td>

                                                {{-- Discount / Benefit --}}
                                                <td class="p-4">
                                                    <div class="space-y-1 max-w-xs">
                                                        @if ($scholarship->discount_percentage > 0)
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                                <i class="bi bi-tag-fill text-emerald-600"></i>
                                                                {{ number_format($scholarship->discount_percentage, 0) }}% Descuento
                                                            </span>
                                                        @endif
                                                        @if ($scholarship->discount_details)
                                                            <p class="text-xs text-gray-700 font-medium leading-tight">
                                                                {{ $scholarship->discount_details }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Description --}}
                                                <td class="p-4">
                                                    <p class="text-xs text-gray-600 line-clamp-2 max-w-xs">
                                                        {{ $scholarship->description ?? 'Sin descripción ingresada.' }}
                                                    </p>
                                                    @if ($scholarship->requirements)
                                                        <span class="inline-flex items-center gap-1 text-[11px] text-purple-700 font-semibold mt-1">
                                                            <i class="bi bi-file-earmark-check"></i> Con requisitos configurados
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Sort Order --}}
                                                <td class="p-4 text-center">
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-black bg-gray-100 text-gray-700 border border-gray-200">
                                                        #{{ $scholarship->sort_order }}
                                                    </span>
                                                </td>

                                                {{-- Status Toggle --}}
                                                <td class="p-4 text-center">
                                                    <form
                                                        action="{{ route('admin.scholarships.toggle-status', $scholarship) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm {{ $scholarship->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200' }}">
                                                            <span
                                                                class="w-2 h-2 rounded-full {{ $scholarship->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                            {{ $scholarship->is_active ? 'Activo' : 'Inactivo' }}
                                                        </button>
                                                    </form>
                                                </td>

                                                {{-- Actions --}}
                                                <td class="p-4 text-center">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <a href="{{ route('admin.scholarships.edit', $scholarship) }}"
                                                            class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                                            title="Editar modalidad">
                                                            <i class="bi bi-pencil-square text-base"></i>
                                                        </a>

                                                        <form action="{{ route('admin.scholarships.destroy', $scholarship) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Está seguro de eliminar la modalidad de beca «{{ $scholarship->name }}»?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors"
                                                                title="Eliminar modalidad">
                                                                <i class="bi bi-trash text-base"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="p-12 text-center text-gray-500">
                                                    <div class="max-w-sm mx-auto space-y-3">
                                                        <i class="bi bi-award text-4xl text-gray-300"></i>
                                                        <p class="text-base font-bold text-gray-700">No se encontraron becas</p>
                                                        <p class="text-xs text-gray-500">No hay modalidades de beca registradas o ninguna coincide con los filtros aplicados.</p>
                                                        <a href="{{ route('admin.scholarships.create') }}"
                                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition">
                                                            Crear Primera Beca
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($scholarships->hasPages())
                                <div class="p-4 bg-gray-50 border-t border-gray-200">
                                    {{ $scholarships->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════════════ --}}
                    {{-- TAB 2: PADRÓN DE BENEFICIARIOS (PDF)                  --}}
                    {{-- ══════════════════════════════════════════════════════ --}}
                    <div x-show="activeTab === 'beneficiaries'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                        class="space-y-6" x-cloak>

                        {{-- Info Banner --}}
                        <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-blue-900 rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
                            <div class="absolute right-0 top-0 -mt-6 -mr-6 w-36 h-36 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
                                <div class="space-y-1.5 max-w-2xl">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-bold text-purple-200 tracking-wide">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-400"></i>
                                        Padrón Oficial de Estudiantes Beneficiarios
                                    </div>
                                    <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                        Publicación de Resoluciones y Listas de Becarios
                                    </h2>
                                    <p class="text-xs sm:text-sm text-purple-100 leading-relaxed">
                                        Suba y organice los documentos oficiales en formato PDF con la relación de estudiantes adjudicados con becas, clasificados por periodo académico, ciclo o semestre (ej. <strong>2026-I</strong>, <strong>2026-II</strong>).
                                    </p>
                                </div>

                                <button type="button" @click="openUploadModal()"
                                    class="px-5 py-3 bg-white text-purple-900 hover:bg-purple-50 font-extrabold text-sm rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center gap-2 flex-shrink-0">
                                    <i class="bi bi-cloud-arrow-up-fill text-purple-600 text-lg"></i>
                                    <span>Subir Nuevo Padrón PDF</span>
                                </button>
                            </div>
                        </div>

                        {{-- Filter and Search Controls for Beneficiaries --}}
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                            <form action="{{ route('admin.scholarships.index') }}" method="GET"
                                class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                                <input type="hidden" name="tab" value="beneficiaries">

                                {{-- Search Input --}}
                                <div class="relative w-full sm:max-w-md">
                                    <input type="text" name="beneficiary_search" value="{{ request('beneficiary_search') }}"
                                        placeholder="Buscar por título, resolución o descripción..."
                                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>

                                {{-- Period Filter --}}
                                <div class="w-full sm:w-auto">
                                    <select name="beneficiary_period" onchange="this.form.submit()"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-semibold">
                                        <option value="">Todos los Periodos</option>
                                        @foreach($periods as $periodOption)
                                            <option value="{{ $periodOption }}" {{ request('beneficiary_period') === $periodOption ? 'selected' : '' }}>
                                                Periodo {{ $periodOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status Filter --}}
                                <div class="w-full sm:w-auto">
                                    <select name="beneficiary_status" onchange="this.form.submit()"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                        <option value="">Todos los Estados</option>
                                        <option value="active" {{ request('beneficiary_status') === 'active' ? 'selected' : '' }}>Activos</option>
                                        <option value="0" {{ request('beneficiary_status') === '0' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
                                </div>

                                {{-- Filter / Clear Buttons --}}
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <button type="submit"
                                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                                        <i class="bi bi-funnel"></i> Filtrar
                                    </button>
                                    @if (request('beneficiary_search') || request('beneficiary_period') || request('beneficiary_status') !== null)
                                        <a href="{{ route('admin.scholarships.index', ['tab' => 'beneficiaries']) }}"
                                            class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                                            Limpiar
                                        </a>
                                    @endif
                                </div>
                            </form>

                            {{-- Upload Button (Secondary) --}}
                            <button type="button" @click="openUploadModal()"
                                class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                                <i class="bi bi-plus-circle-fill text-lg"></i>
                                <span>Subir Padrón</span>
                            </button>
                        </div>

                        {{-- Beneficiaries Table --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse min-w-[900px]">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                            <th class="p-4 w-32 text-center">Periodo / Ciclo</th>
                                            <th class="p-4">Título & Resolución</th>
                                            <th class="p-4">Modalidad</th>
                                            <th class="p-4 text-center">Archivo PDF</th>
                                            <th class="p-4 text-center">Fecha Publ.</th>
                                            <th class="p-4 text-center w-28">Estado</th>
                                            <th class="p-4 text-center w-36">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($beneficiaries as $beneficiary)
                                            <tr class="hover:bg-purple-50/30 transition-colors">
                                                {{-- Period Badge --}}
                                                <td class="p-4 text-center">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-purple-100 text-purple-900 border border-purple-200 shadow-xs">
                                                        <i class="bi bi-calendar-event text-purple-600"></i>
                                                        {{ $beneficiary->academic_period }}
                                                    </span>
                                                </td>

                                                {{-- Title & Resolution --}}
                                                <td class="p-4">
                                                    <div class="font-extrabold text-gray-900 text-sm sm:text-base leading-snug">
                                                        {{ $beneficiary->title }}
                                                    </div>
                                                    @if ($beneficiary->resolution_number)
                                                        <div class="mt-1 flex items-center gap-1.5">
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                                <i class="bi bi-file-earmark-check-fill text-amber-600"></i>
                                                                {{ $beneficiary->resolution_number }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    @if ($beneficiary->description)
                                                        <p class="text-xs text-gray-500 mt-1 line-clamp-1 max-w-md">
                                                            {{ $beneficiary->description }}
                                                        </p>
                                                    @endif
                                                </td>

                                                {{-- Modality --}}
                                                <td class="p-4">
                                                    @if ($beneficiary->scholarship)
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                            <i class="bi bi-award"></i>
                                                            {{ $beneficiary->scholarship->name }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                                            <i class="bi bi-collection"></i>
                                                            Todas las Modalidades
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- PDF File --}}
                                                <td class="p-4 text-center">
                                                    <div class="inline-flex flex-col items-center gap-1">
                                                        <a href="{{ $beneficiary->file_url }}" target="_blank"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg text-xs font-bold transition">
                                                            <i class="bi bi-file-earmark-pdf-fill text-red-600 text-sm"></i>
                                                            <span>PDF</span>
                                                            <span class="text-[10px] text-red-500 font-normal">({{ $beneficiary->formatted_file_size }})</span>
                                                        </a>
                                                    </div>
                                                </td>

                                                {{-- Publication Date --}}
                                                <td class="p-4 text-center">
                                                    @if ($beneficiary->publication_date)
                                                        <span class="text-xs text-gray-700 font-semibold flex items-center justify-center gap-1">
                                                            <i class="bi bi-clock-history text-gray-400"></i>
                                                            {{ $beneficiary->publication_date->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">No especificada</span>
                                                    @endif
                                                </td>

                                                {{-- Status Toggle --}}
                                                <td class="p-4 text-center">
                                                    <form action="{{ route('admin.scholarships.beneficiaries.toggle-status', $beneficiary) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm {{ $beneficiary->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200' }}">
                                                            <span class="w-2 h-2 rounded-full {{ $beneficiary->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                            {{ $beneficiary->is_active ? 'Activo' : 'Inactivo' }}
                                                        </button>
                                                    </form>
                                                </td>

                                                {{-- Actions --}}
                                                <td class="p-4 text-center">
                                                    <div class="flex items-center justify-center gap-1">
                                                        {{-- Direct Download --}}
                                                        <a href="{{ route('admin.scholarships.beneficiaries.download', $beneficiary) }}"
                                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                                            title="Descargar PDF">
                                                            <i class="bi bi-download text-base"></i>
                                                        </a>

                                                        {{-- Preview in Browser --}}
                                                        <a href="{{ $beneficiary->file_url }}" target="_blank"
                                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                            title="Abrir PDF en pestaña nueva">
                                                            <i class="bi bi-box-arrow-up-right text-base"></i>
                                                        </a>

                                                        {{-- Edit Modal Trigger --}}
                                                        <button type="button" @click="openEditModal({{ $beneficiary->id }})"
                                                            class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                                                            title="Editar datos del padrón">
                                                            <i class="bi bi-pencil-square text-base"></i>
                                                        </button>

                                                        {{-- Delete --}}
                                                        <form action="{{ route('admin.scholarships.beneficiaries.destroy', $beneficiary) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Está seguro de eliminar el padrón «{{ $beneficiary->title }}» del periodo {{ $beneficiary->academic_period }}? Esta acción borrará el archivo PDF.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                                title="Eliminar padrón">
                                                                <i class="bi bi-trash text-base"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="p-12 text-center text-gray-500">
                                                    <div class="max-w-sm mx-auto space-y-3">
                                                        <i class="bi bi-file-earmark-pdf text-4xl text-gray-300"></i>
                                                        <p class="text-base font-bold text-gray-700">No hay padrones de beneficiarios registrados</p>
                                                        <p class="text-xs text-gray-500">Suba el primer archivo PDF con la relación de estudiantes beneficiarios categorizado por periodo académico.</p>
                                                        <button type="button" @click="openUploadModal()"
                                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition gap-2">
                                                            <i class="bi bi-cloud-arrow-up-fill"></i>
                                                            <span>Subir Primer Padrón</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Beneficiaries Pagination --}}
                            @if ($beneficiaries->hasPages())
                                <div class="p-4 bg-gray-50 border-t border-gray-200">
                                    {{ $beneficiaries->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </main>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- MODAL: SUBIR NUEVO PADRÓN DE BENEFICIARIOS (PDF)                   --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div x-show="showUploadModal" x-transition.opacity
            class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            style="display: none;" @keydown.escape.window="showUploadModal = false">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl relative space-y-4 my-auto border border-gray-100 max-h-[92vh] overflow-y-auto"
                @click.away="showUploadModal = false">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Subir Padrón de Beneficiarios</h3>
                            <p class="text-xs text-gray-500">Adjunte el archivo PDF oficial con la relación de estudiantes adjudicados.</p>
                        </div>
                    </div>
                    <button type="button" @click="showUploadModal = false"
                        class="text-gray-400 hover:text-gray-700 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form action="{{ route('admin.scholarships.beneficiaries.store') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4" @submit="isUploading = true">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Academic Period --}}
                        <div class="sm:col-span-1">
                            <label for="create_period" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Periodo / Semestre <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="academic_period" id="create_period" x-model="uploadForm.period"
                                placeholder="Ej. 2026-I" required
                                class="w-full text-xs font-bold px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                            
                            {{-- Quick Period Pills --}}
                            <div class="flex flex-wrap gap-1 mt-1.5">
                                <button type="button" @click="uploadForm.period = '2026-I'"
                                    class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition">
                                    2026-I
                                </button>
                                <button type="button" @click="uploadForm.period = '2026-II'"
                                    class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition">
                                    2026-II
                                </button>
                                <button type="button" @click="uploadForm.period = '2025-II'"
                                    class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 transition">
                                    2025-II
                                </button>
                            </div>
                        </div>

                        {{-- Resolution Number --}}
                        <div class="sm:col-span-1">
                            <label for="create_resolution" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                N° de Resolución (Opcional)
                            </label>
                            <input type="text" name="resolution_number" id="create_resolution"
                                placeholder="Ej. R.D. N° 045-2026-IESTP-FVC"
                                class="w-full text-xs font-medium px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>

                        {{-- Title --}}
                        <div class="sm:col-span-2">
                            <label for="create_title" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Título del Padrón <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="create_title" required
                                placeholder="Ej. Padrón Oficial de Estudiantes Beneficiarios 2026-I"
                                class="w-full text-xs font-semibold px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>

                        {{-- PDF File Dropzone --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Archivo PDF del Padrón <span class="text-rose-500">*</span>
                            </label>
                            <div class="pdf-drop-zone p-4 rounded-xl text-center cursor-pointer bg-slate-50 hover:bg-purple-50/40 border border-dashed border-gray-300 hover:border-purple-400 transition relative"
                                @click="$refs.createFileInput.click()">
                                <input type="file" name="file" accept="application/pdf" required x-ref="createFileInput"
                                    class="hidden" @change="handleFileSelect($event)">
                                <div class="space-y-1.5">
                                    <div class="w-9 h-9 mx-auto rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-lg shadow-sm">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>
                                    <template x-if="!selectedFileName">
                                        <div>
                                            <p class="text-xs font-bold text-gray-700">Haga clic o arrastre el archivo PDF aquí</p>
                                            <p class="text-[11px] text-gray-400">Solo formato .pdf · Tamaño máximo: 25 MB</p>
                                        </div>
                                    </template>
                                    <template x-if="selectedFileName">
                                        <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-200">
                                            <p class="text-xs font-extrabold text-emerald-800 flex items-center justify-center gap-1.5 truncate">
                                                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                                                <span x-text="selectedFileName"></span>
                                            </p>
                                            <p class="text-[10px] text-emerald-600 font-semibold" x-text="selectedFileSize"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Reference Activate Checkbox (from /admin-programas/crear-programa) --}}
                        <div class="sm:col-span-2 pt-1">
                            <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative flex-shrink-0"></div>
                                <div>
                                    <span class="text-xs font-bold text-gray-900 block">Padrón Activo</span>
                                    <span class="text-[11px] text-gray-500 block">Los documentos activos se muestran públicamente en el portal institucional.</span>
                                </div>
                            </label>
                        </div>

                        {{-- Optional Details Collapsible --}}
                        <div class="sm:col-span-2">
                            <details class="group border border-gray-200 rounded-xl bg-gray-50/50 overflow-hidden text-xs">
                                <summary class="flex items-center justify-between px-3 py-2 text-gray-600 cursor-pointer hover:bg-gray-100/80 transition select-none font-semibold">
                                    <span class="flex items-center gap-1.5"><i class="bi bi-sliders text-purple-600"></i> Opciones complementarias (Modalidad, Fecha, Observaciones)</span>
                                    <i class="bi bi-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                                </summary>
                                <div class="p-3 pt-2 border-t border-gray-100 space-y-3 bg-white">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label for="create_scholarship_id" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">
                                                Modalidad de Beca
                                            </label>
                                            <select name="scholarship_id" id="create_scholarship_id"
                                                class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition bg-white text-gray-700">
                                                <option value="">General (Todas las modalidades)</option>
                                                @foreach($allScholarships as $sch)
                                                    <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="create_pub_date" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">
                                                Fecha de Publicación
                                            </label>
                                            <input type="date" name="publication_date" id="create_pub_date" value="{{ date('Y-m-d') }}"
                                                class="w-full text-xs px-2.5 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="create_desc" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">
                                            Descripción u Observaciones (Opcional)
                                        </label>
                                        <textarea name="description" id="create_desc" rows="2"
                                            placeholder="Detalles adicionales, alcances de la resolución o indicaciones pertinentes..."
                                            class="w-full text-xs px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition"></textarea>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showUploadModal = false"
                            class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="isUploading"
                            class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 rounded-xl shadow-md transition flex items-center gap-2">
                            <template x-if="isUploading">
                                <i class="bi bi-arrow-repeat animate-spin"></i>
                            </template>
                            <template x-if="!isUploading">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </template>
                            <span>Guardar y Publicar Padrón</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- MODAL: EDITAR PADRÓN DE BENEFICIARIOS                              --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div x-show="showEditModal" x-transition.opacity
            class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            style="display: none;" @keydown.escape.window="showEditModal = false">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl relative space-y-4 my-auto border border-gray-100 max-h-[92vh] overflow-y-auto"
                @click.away="showEditModal = false">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Editar Padrón de Beneficiarios</h3>
                            <p class="text-xs text-gray-500">Actualice la información o reemplace el documento PDF.</p>
                        </div>
                    </div>
                    <button type="button" @click="showEditModal = false"
                        class="text-gray-400 hover:text-gray-700 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form :action="editActionUrl" method="POST" enctype="multipart/form-data" class="space-y-4"
                    @submit="isUploading = true">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Academic Period --}}
                        <div class="sm:col-span-1">
                            <label for="edit_period" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Periodo / Semestre <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="academic_period" id="edit_period" x-model="editForm.academic_period"
                                required
                                class="w-full text-xs font-bold px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>

                        {{-- Resolution Number --}}
                        <div class="sm:col-span-1">
                            <label for="edit_resolution" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                N° de Resolución (Opcional)
                            </label>
                            <input type="text" name="resolution_number" id="edit_resolution" x-model="editForm.resolution_number"
                                class="w-full text-xs font-medium px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>

                        {{-- Title --}}
                        <div class="sm:col-span-2">
                            <label for="edit_title" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Título del Padrón <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="edit_title" x-model="editForm.title" required
                                class="w-full text-xs font-semibold px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                        </div>

                        {{-- Existing PDF Info & Replacement Dropzone --}}
                        <div class="sm:col-span-2 space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Archivo PDF Actual y Reemplazo
                            </label>
                            
                            <template x-if="editForm.file_url">
                                <div class="flex items-center justify-between p-2.5 bg-purple-50 border border-purple-200 rounded-xl text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500 text-lg flex-shrink-0"></i>
                                        <div class="truncate">
                                            <p class="font-bold text-purple-950 truncate">Archivo adjunto actual</p>
                                            <p class="text-[10px] text-purple-700" x-text="editForm.file_size"></p>
                                        </div>
                                    </div>
                                    <a :href="editForm.file_url" target="_blank"
                                        class="px-2.5 py-1 bg-white hover:bg-purple-100 text-purple-800 font-bold rounded-lg border border-purple-300 text-xs transition flex-shrink-0 flex items-center gap-1">
                                        <i class="bi bi-box-arrow-up-right"></i> Ver PDF
                                    </a>
                                </div>
                            </template>

                            <div class="pdf-drop-zone p-3 rounded-xl text-center cursor-pointer bg-slate-50 hover:bg-purple-50/40 border border-dashed border-gray-300 hover:border-purple-400 transition relative"
                                @click="$refs.editFileInput.click()">
                                <input type="file" name="file" accept="application/pdf" x-ref="editFileInput" class="hidden"
                                    @change="handleEditFileSelect($event)">
                                <div class="space-y-1">
                                    <i class="bi bi-cloud-arrow-up text-xl text-gray-400"></i>
                                    <p class="text-xs font-semibold text-gray-700">Seleccione un nuevo PDF si desea reemplazar el actual</p>
                                    <template x-if="editSelectedFileName">
                                        <p class="text-[11px] font-extrabold text-emerald-700" x-text="'Nuevo: ' + editSelectedFileName"></p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Reference Activate Checkbox (from /admin-programas/crear-programa) --}}
                        <div class="sm:col-span-2 pt-1">
                            <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                <input type="checkbox" name="is_active" value="1" x-model="editForm.is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative flex-shrink-0"></div>
                                <div>
                                    <span class="text-xs font-bold text-gray-900 block">Padrón Activo</span>
                                    <span class="text-[11px] text-gray-500 block">Los documentos activos se muestran públicamente en el portal institucional.</span>
                                </div>
                            </label>
                        </div>

                        {{-- Optional Details Collapsible --}}
                        <div class="sm:col-span-2">
                            <details class="group border border-gray-200 rounded-xl bg-gray-50/50 overflow-hidden text-xs">
                                <summary class="flex items-center justify-between px-3 py-2 text-gray-600 cursor-pointer hover:bg-gray-100/80 transition select-none font-semibold">
                                    <span class="flex items-center gap-1.5"><i class="bi bi-sliders text-purple-600"></i> Opciones complementarias (Modalidad, Fecha, Observaciones)</span>
                                    <i class="bi bi-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                                </summary>
                                <div class="p-3 pt-2 border-t border-gray-100 space-y-3 bg-white">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label for="edit_scholarship_id" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">
                                                Modalidad de Beca
                                            </label>
                                            <select name="scholarship_id" id="edit_scholarship_id" x-model="editForm.scholarship_id"
                                                class="w-full text-xs border border-gray-300 rounded-lg py-2 px-2.5 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition bg-white text-gray-700">
                                                <option value="">General (Todas las modalidades)</option>
                                                @foreach($allScholarships as $sch)
                                                    <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="edit_pub_date" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">
                                                Fecha de Publicación
                                            </label>
                                            <input type="date" name="publication_date" id="edit_pub_date" x-model="editForm.publication_date"
                                                class="w-full text-xs px-2.5 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="edit_desc" class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">
                                            Descripción u Observaciones (Opcional)
                                        </label>
                                        <textarea name="description" id="edit_desc" rows="2" x-model="editForm.description"
                                            placeholder="Detalles adicionales, alcances de la resolución o indicaciones pertinentes..."
                                            class="w-full text-xs px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition"></textarea>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="isUploading"
                            class="px-5 py-2 text-xs font-extrabold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 rounded-xl shadow-md transition flex items-center gap-2">
                            <template x-if="isUploading">
                                <i class="bi bi-arrow-repeat animate-spin"></i>
                            </template>
                            <template x-if="!isUploading">
                                <i class="bi bi-check2-circle"></i>
                            </template>
                            <span>Guardar Cambios</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function scholarshipsAdminApp() {
            return {
                sidebarOpen: true,
                activeTab: '{{ $activeTab }}',
                showUploadModal: false,
                showEditModal: false,
                isUploading: false,

                init() {
                    this.$watch('showUploadModal', () => this.syncBodyScroll());
                    this.$watch('showEditModal', () => this.syncBodyScroll());
                },

                syncBodyScroll() {
                    if (this.showUploadModal || this.showEditModal) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                },

                // Upload Form State
                uploadForm: {
                    period: '{{ date("Y") }}-I'
                },
                selectedFileName: '',
                selectedFileSize: '',

                // Edit Form State
                editForm: {
                    id: null,
                    academic_period: '',
                    title: '',
                    description: '',
                    resolution_number: '',
                    publication_date: '',
                    scholarship_id: '',
                    is_active: true,
                    sort_order: 0,
                    file_url: '',
                    file_size: ''
                },
                editActionUrl: '',
                editSelectedFileName: '',

                switchTab(tab) {
                    this.activeTab = tab;
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);
                },

                openUploadModal() {
                    this.selectedFileName = '';
                    this.selectedFileSize = '';
                    this.isUploading = false;
                    this.showUploadModal = true;
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFileName = file.name;
                        this.selectedFileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                    } else {
                        this.selectedFileName = '';
                        this.selectedFileSize = '';
                    }
                },

                handleEditFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.editSelectedFileName = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                    } else {
                        this.editSelectedFileName = '';
                    }
                },

                async openEditModal(beneficiaryId) {
                    this.editSelectedFileName = '';
                    this.isUploading = false;

                    try {
                        const response = await fetch(`/admin-scholarships/beneficiarios/editar/${beneficiaryId}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Error al cargar datos del padrón');
                        }

                        const json = await response.json();
                        if (json.success && json.data) {
                            const data = json.data;
                            this.editForm = {
                                id: data.id,
                                academic_period: data.academic_period || '',
                                title: data.title || '',
                                description: data.description || '',
                                resolution_number: data.resolution_number || '',
                                publication_date: data.publication_date || '',
                                scholarship_id: data.scholarship_id || '',
                                is_active: data.is_active,
                                sort_order: data.sort_order || 0,
                                file_url: data.file_url || '',
                                file_size: data.file_size || ''
                            };
                            this.editActionUrl = `/admin-scholarships/beneficiarios/editar/${data.id}`;
                            this.showEditModal = true;
                        }
                    } catch (error) {
                        alert('No se pudo obtener la información del padrón. Intente nuevamente.');
                        console.error(error);
                    }
                }
            };
        }
    </script>
@endpush
