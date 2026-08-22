@extends('layouts.app')
@section('title', 'Gestión de Programas de Estudio - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Gestión de Programas de Estudio
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Programas de Estudio</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="programManagement('{{ $activeTab }}')">
            <div class="max-w-7xl mx-auto space-y-6">
                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-emerald-500 hover:text-emerald-700 p-1" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700 p-1" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- TAB NAVIGATION PANEL --}}
                <div class="bg-white rounded-2xl p-2 border border-gray-100 shadow-sm flex flex-wrap lg:flex-nowrap gap-2 overflow-x-auto">
                    <button type="button" @click="switchTab('programs')"
                        :class="currentTab === 'programs' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="bi bi-mortarboard-fill text-base"></i>
                        <span>Programas</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'programs' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $programs->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('modules')"
                        :class="currentTab === 'modules' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="bi bi-award-fill text-base"></i>
                        <span>Certificaciones</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'modules' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $modules->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('competencies')"
                        :class="currentTab === 'competencies' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="bi bi-star-fill text-base"></i>
                        <span>Competencias</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'competencies' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $competencies->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('job_fields')"
                        :class="currentTab === 'job_fields' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="bi bi-briefcase-fill text-base"></i>
                        <span>Campo Laboral</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'job_fields' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $jobFields->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('meta')"
                        :class="currentTab === 'meta' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="bi bi-palette-fill text-base"></i>
                        <span>Metadatos</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'meta' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $metas->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('requirements')"
                        :class="currentTab === 'requirements' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-3.5 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="bi bi-check2-square text-base"></i>
                        <span>Requisitos</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'requirements' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $requirements->total() }}</span>
                    </button>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 1: PROGRAMAS DE ESTUDIO (study_programs)                      --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'programs'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Programas de Estudio Profesionales</h2>
                            <p class="text-sm text-gray-500">Administra las carreras técnicas y profesionales ofertadas por el instituto.</p>
                        </div>

                        <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-plus-circle text-lg"></i>
                            <span>Registrar Nuevo Programa</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <input type="hidden" name="tab" value="programs">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Programa</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'programs' ? request('search') : '' }}" placeholder="Nombre o palabra clave..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                                <select name="status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.programs.index', ['tab' => 'programs']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                        <th class="py-4 px-6">Programa de Estudio</th>
                                        <th class="py-4 px-6">Identificador (Tag / Acento)</th>
                                        <th class="py-4 px-6">Detalles / Duración</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($programs as $program)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    @if($program->logo_path)
                                                        <img src="{{ asset('storage/' . $program->logo_path) }}" alt="{{ $program->name }}" class="w-10 h-10 object-contain rounded-xl border border-gray-200 bg-white p-1 flex-shrink-0">
                                                    @else
                                                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                                            <i class="bi {{ $program->icon ?? 'bi-mortarboard-fill' }}"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 leading-tight">{{ $program->name }}</h3>
                                                        <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $program->description }}</p>
                                                        @if($program->training_itinerary_path)
                                                            <a href="{{ $program->training_itinerary_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 hover:text-red-800 mt-1 bg-red-50 px-2 py-0.5 rounded-md border border-red-100" title="Ver Itinerario Formativo">
                                                                <i class="bi bi-file-earmark-pdf-fill"></i> Itinerario PDF
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <div class="space-y-1">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                                        <i class="bi {{ $program->icon ?? 'bi-tag-fill' }}"></i> {{ $program->tag ?? 'General' }}
                                                    </span>
                                                    <div class="text-[11px] text-gray-400 font-mono">Acento: {{ $program->accent ?? 'blue' }}</div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6">
                                                <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">{{ $program->details }}</p>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.programs.toggle-status', $program) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $program->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $program->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $program->is_active ? 'Activo' : 'Inactivo' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.programs.edit', $program) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Programa">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.programs.destroy', $program) }}', '{{ addslashes($program->name) }}', 'Programa de Estudio')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Programa">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-mortarboard text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron programas</p>
                                                    <p class="text-xs text-gray-500">Registra un nuevo programa de estudio profesional.</p>
                                                    <a href="{{ route('admin.programs.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Programa
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($programs->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $programs->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 2: CERTIFICACIONES MODULARES (modular_certification)          --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'modules'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Módulos de Certificación</h2>
                            <p class="text-sm text-gray-500">Administra las certificaciones modulares de cada carrera técnica profesional.</p>
                        </div>

                        <a href="{{ route('admin.programs.modules.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-award text-lg"></i>
                            <span>Registrar Nuevo Módulo</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <input type="hidden" name="tab" value="modules">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Módulo</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'modules' ? request('search') : '' }}" placeholder="Nombre del módulo..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                                <select name="study_program_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los programas</option>
                                    @foreach($allProgramsList as $prog)
                                        <option value="{{ $prog->id }}" {{ request('study_program_id') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                                <select name="status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.programs.index', ['tab' => 'modules']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                        <th class="py-4 px-6">Módulo Formativo</th>
                                        <th class="py-4 px-6">Programa de Estudio</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($modules as $module)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="p-2.5 bg-indigo-100 text-indigo-600 rounded-xl flex-shrink-0">
                                                        <i class="bi bi-patch-check-fill text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 leading-tight">{{ $module->module }}</h3>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                    {{ $module->studyProgram->name ?? 'Programa no hallado' }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.programs.modules.toggle-status', $module) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $module->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $module->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $module->is_active ? 'Activo' : 'Inactivo' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.programs.modules.edit', $module) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Módulo">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.programs.modules.destroy', $module) }}', '{{ addslashes($module->module) }}', 'Módulo de Certificación')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Módulo">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-award text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron módulos</p>
                                                    <p class="text-xs text-gray-500">Registra un nuevo módulo formativo de certificación.</p>
                                                    <a href="{{ route('admin.programs.modules.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Módulo
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($modules->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $modules->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 3: COMPETENCIAS DEL PROGRAMA (program_competencies)            --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'competencies'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Competencias del Perfil Profesional</h2>
                            <p class="text-sm text-gray-500">Gestiona los logros de aprendizaje y habilidades del graduado.</p>
                        </div>

                        <a href="{{ route('admin.programs.competencies.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-star-fill text-lg"></i>
                            <span>Registrar Nueva Competencia</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <input type="hidden" name="tab" value="competencies">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Competencia</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'competencies' ? request('search') : '' }}" placeholder="Título o palabra clave..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                                <select name="study_program_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los programas</option>
                                    @foreach($allProgramsList as $prog)
                                        <option value="{{ $prog->id }}" {{ request('study_program_id') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                                <select name="status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activa</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactiva</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.programs.index', ['tab' => 'competencies']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                        <th class="py-4 px-6">Competencia</th>
                                        <th class="py-4 px-6">Programa de Estudio</th>
                                        <th class="py-4 px-6 text-center">Orden</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($competencies as $competency)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-start gap-3">
                                                    <div class="p-2.5 bg-amber-100 text-amber-700 rounded-xl flex-shrink-0 mt-0.5">
                                                        <i class="fa-solid {{ $competency->icon ?? 'fa-graduation-cap' }} text-base"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 leading-tight">{{ $competency->title }}</h3>
                                                        <p class="text-xs text-gray-500 line-clamp-2 mt-0.5 leading-relaxed">{{ $competency->description }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                    {{ $competency->studyProgram->name ?? 'Programa no hallado' }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                                <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded-md text-gray-700">#{{ $competency->order }}</span>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.programs.competencies.toggle-status', $competency) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $competency->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $competency->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $competency->is_active ? 'Activa' : 'Inactiva' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.programs.competencies.edit', $competency) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Competencia">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.programs.competencies.destroy', $competency) }}', '{{ addslashes($competency->title) }}', 'Competencia')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Competencia">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-star text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron competencias</p>
                                                    <p class="text-xs text-gray-500">Registra competencias profesionales para los programas de estudio.</p>
                                                    <a href="{{ route('admin.programs.competencies.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Competencia
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($competencies->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $competencies->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 4: CAMPO LABORAL (program_job_fields)                         --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'job_fields'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Campo Laboral & Oportunidades</h2>
                            <p class="text-sm text-gray-500">Gestiona los nichos de empleabilidad para los egresados.</p>
                        </div>

                        <a href="{{ route('admin.programs.job-fields.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-briefcase text-lg"></i>
                            <span>Registrar Nuevo Campo Laboral</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <input type="hidden" name="tab" value="job_fields">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Campo Laboral</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'job_fields' ? request('search') : '' }}" placeholder="Palabra clave..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                                <select name="study_program_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los programas</option>
                                    @foreach($allProgramsList as $prog)
                                        <option value="{{ $prog->id }}" {{ request('study_program_id') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                                <select name="status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.programs.index', ['tab' => 'job_fields']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                        <th class="py-4 px-6">Descripción del Campo Laboral</th>
                                        <th class="py-4 px-6">Programa de Estudio</th>
                                        <th class="py-4 px-6 text-center">Orden</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($jobFields as $jobField)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-start gap-3">
                                                    <div class="p-2.5 bg-blue-100 text-blue-600 rounded-xl flex-shrink-0 mt-0.5">
                                                        <i class="bi bi-building text-base"></i>
                                                    </div>
                                                    <p class="font-medium text-gray-900 leading-relaxed">{{ $jobField->description }}</p>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                    {{ $jobField->studyProgram->name ?? 'Programa no hallado' }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                                <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded-md text-gray-700">#{{ $jobField->order }}</span>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.programs.job-fields.toggle-status', $jobField) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $jobField->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $jobField->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $jobField->is_active ? 'Activo' : 'Inactivo' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.programs.job-fields.edit', $jobField) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Campo Laboral">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.programs.job-fields.destroy', $jobField) }}', '{{ addslashes($jobField->description) }}', 'Campo Laboral')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Campo Laboral">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-briefcase text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron registros</p>
                                                    <p class="text-xs text-gray-500">Registra campos laborales para orientar a los postulantes.</p>
                                                    <a href="{{ route('admin.programs.job-fields.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Campo Laboral
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($jobFields->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $jobFields->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 5: METADATOS DE PRESENTACIÓN (program_metas)                  --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'meta'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Metadatos & Estilos de Presentación</h2>
                            <p class="text-sm text-gray-500">Personaliza la estética visual, insignias, colores de acento y gradientes del portal web.</p>
                        </div>

                        <a href="{{ route('admin.programs.meta.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-palette text-lg"></i>
                            <span>Registrar Metadatos</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <input type="hidden" name="tab" value="meta">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar por Programa</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'meta' ? request('search') : '' }}" placeholder="Nombre del programa..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Programa Específico</label>
                                <select name="study_program_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los programas</option>
                                    @foreach($allProgramsList as $prog)
                                        <option value="{{ $prog->id }}" {{ request('study_program_id') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.programs.index', ['tab' => 'meta']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                        <th class="py-4 px-6">Programa</th>
                                        <th class="py-4 px-6">Ícono & Acento</th>
                                        <th class="py-4 px-6">Tag / Etiqueta</th>
                                        <th class="py-4 px-6">Estilos Configurados</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($metas as $meta)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6 font-bold text-gray-900">
                                                {{ $meta->studyProgram->name ?? 'N/A' }}
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg">
                                                        <i class="bi {{ $meta->icon ?? 'bi-mortarboard-fill' }}"></i>
                                                    </div>
                                                    <span class="font-semibold text-gray-700 capitalize text-xs">{{ $meta->accent ?? 'blue' }}</span>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-md text-xs font-semibold">
                                                    {{ $meta->tag ?? 'Sin tag' }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-6">
                                                <div class="text-[11px] font-mono text-gray-500 space-y-0.5">
                                                    <div>Barra: {{ $meta->color_bar ?? 'Por defecto' }}</div>
                                                    <div>Badge: {{ $meta->bg_badge ?? 'Por defecto' }}</div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.programs.meta.edit', $meta) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Metadatos">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.programs.meta.destroy', $meta) }}', 'Metadatos de {{ addslashes($meta->studyProgram->name ?? '') }}', 'Metadatos')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Metadatos">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-palette text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron metadatos</p>
                                                    <p class="text-xs text-gray-500">Configura metadatos de estilo para personalizar la interfaz pública.</p>
                                                    <a href="{{ route('admin.programs.meta.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Metadatos
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($metas->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $metas->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 6: REQUISITOS (program_requirements)                           --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'requirements'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Requisitos de Admisión & Matrícula</h2>
                            <p class="text-sm text-gray-500">Administra los documentos exigidos a los postulantes de cada programa.</p>
                        </div>

                        <a href="{{ route('admin.programs.requirements.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-check2-square text-lg"></i>
                            <span>Registrar Nuevo Requisito</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <input type="hidden" name="tab" value="requirements">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Requisito</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'requirements' ? request('search') : '' }}" placeholder="Palabra clave..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Programa</label>
                                <select name="study_program_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los programas</option>
                                    @foreach($allProgramsList as $prog)
                                        <option value="{{ $prog->id }}" {{ request('study_program_id') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                                <select name="status" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.programs.index', ['tab' => 'requirements']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-semibold text-gray-500">
                                        <th class="py-4 px-6">Requisito</th>
                                        <th class="py-4 px-6">Programa de Estudio</th>
                                        <th class="py-4 px-6 text-center">Orden</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($requirements as $req)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-start gap-3">
                                                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg flex-shrink-0 mt-0.5">
                                                        <i class="bi bi-file-earmark-check text-base"></i>
                                                    </div>
                                                    <p class="font-medium text-gray-900 leading-relaxed">{{ $req->description }}</p>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                    {{ $req->studyProgram->name ?? 'Programa no hallado' }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                                <span class="font-mono text-xs font-bold bg-gray-100 px-2 py-1 rounded-md text-gray-700">#{{ $req->order }}</span>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.programs.requirements.toggle-status', $req) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $req->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $req->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $req->is_active ? 'Activo' : 'Inactivo' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.programs.requirements.edit', $req) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Requisito">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.programs.requirements.destroy', $req) }}', '{{ addslashes($req->description) }}', 'Requisito')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Requisito">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-check2-square text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron requisitos</p>
                                                    <p class="text-xs text-gray-500">Registra los documentos necesarios para la inscripción.</p>
                                                    <a href="{{ route('admin.programs.requirements.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Requisito
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($requirements->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $requirements->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Delete Confirmation Modal --}}
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
                <div @click.away="showDeleteModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center space-y-4">
                    <div class="w-16 h-16 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">¿Eliminar <span x-text="deleteType"></span>?</h3>
                        <p class="text-xs text-gray-500 mt-1">Esta acción eliminará permanentemente <strong class="text-gray-700" x-text="deleteTitle"></strong>.</p>
                    </div>
                    <form method="POST" :action="deleteUrl" class="flex gap-3 pt-2">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showDeleteModal = false" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 transition-colors">
                            Sí, Eliminar
                        </button>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>

@push('scripts')
    <script>
        function programManagement(initialTab) {
            return {
                currentTab: initialTab || 'programs',
                showDeleteModal: false,
                deleteUrl: '',
                deleteTitle: '',
                deleteType: '',

                switchTab(tab) {
                    this.currentTab = tab;
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url.toString());
                },

                confirmDelete(url, title, type) {
                    this.deleteUrl = url;
                    this.deleteTitle = title;
                    this.deleteType = type || 'registro';
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
@endpush
@endsection
