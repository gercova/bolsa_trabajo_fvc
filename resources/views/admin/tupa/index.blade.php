@extends('layouts.app')
@section('title', 'Gestión del TUPA - Panel Administrativo')
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
                        Gestión del TUPA Institucional
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Reglamento y Procedimientos TUPA</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="tupaManagement('{{ $activeTab }}')">
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
                <div class="bg-white rounded-2xl p-2 border border-gray-100 shadow-sm flex flex-wrap sm:flex-nowrap gap-2">
                    <button type="button" @click="switchTab('documents')"
                        :class="currentTab === 'documents' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-4 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="bi bi-file-earmark-pdf-fill text-base"></i>
                        <span>Documentos TUPA</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'documents' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $tupas->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('categories')"
                        :class="currentTab === 'categories' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-4 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="bi bi-folder-fill text-base"></i>
                        <span>Categorías TUPA</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'categories' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $categories->total() }}</span>
                    </button>

                    <button type="button" @click="switchTab('procedures')"
                        :class="currentTab === 'procedures' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'"
                        class="flex-1 py-3 px-4 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="bi bi-list-check text-base"></i>
                        <span>Procedimientos TUPA</span>
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full" :class="currentTab === 'procedures' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ $procedures->total() }}</span>
                    </button>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 1: DOCUMENTOS TUPA                                            --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'documents'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Registros de Documentos TUPA</h2>
                            <p class="text-sm text-gray-500">Administra los reglamentos y cuadros de tasas administrativas oficiales del instituto.</p>
                        </div>

                        <a href="{{ route('admin.tupa.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-plus-circle text-lg"></i>
                            <span>Registrar Nuevo TUPA</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.tupa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <input type="hidden" name="tab" value="documents">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Documento</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'documents' ? request('search') : '' }}" placeholder="Título o palabra clave..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
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

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Año Vigencia</label>
                                <input type="number" name="year" value="{{ request('year') }}" placeholder="Ej: 2026" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 py-2 px-4 bg-purple-600 text-white font-medium text-sm rounded-xl hover:bg-purple-700 transition-colors">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.tupa.index', ['tab' => 'documents']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
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
                                        <th class="py-4 px-6">Documento TUPA</th>
                                        <th class="py-4 px-6">Archivo PDF</th>
                                        <th class="py-4 px-6">Vigencia</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($tupas as $tupa)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-start gap-3">
                                                    <div class="p-2.5 bg-purple-100 text-purple-600 rounded-xl flex-shrink-0 mt-0.5">
                                                        <i class="bi bi-file-earmark-pdf text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 leading-tight">{{ $tupa->title }}</h3>
                                                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $tupa->description }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                @if($tupa->url)
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click="previewPdf('{{ $tupa->url }}', '{{ addslashes($tupa->title) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors">
                                                            <i class="bi bi-eye"></i> Previsualizar
                                                        </button>
                                                        <a href="{{ $tupa->url }}" target="_blank" download class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg transition-colors" title="Descargar PDF">
                                                            <i class="bi bi-download text-base"></i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sin archivo</span>
                                                @endif
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <div class="text-xs space-y-0.5">
                                                    <div class="flex items-center gap-1.5 text-gray-700">
                                                        <i class="bi bi-calendar-event text-purple-500"></i>
                                                        <span>Desde: <strong>{{ $tupa->effective_start_date ? $tupa->effective_start_date->format('d/m/Y') : 'N/A' }}</strong></span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 text-gray-500">
                                                        <i class="bi bi-calendar-check"></i>
                                                        <span>Hasta: {{ $tupa->effective_end_date ? $tupa->effective_end_date->format('d/m/Y') : 'Indefinido' }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.tupa.toggle-status', $tupa) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $tupa->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $tupa->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $tupa->is_active ? 'Activo' : 'Inactivo' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.tupa.edit', $tupa) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar TUPA">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.tupa.destroy', $tupa) }}', '{{ addslashes($tupa->title) }}', 'Documento TUPA')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar TUPA">
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
                                                        <i class="bi bi-file-earmark-x text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron registros TUPA</p>
                                                    <p class="text-xs text-gray-500">Prueba ajustando los términos de búsqueda o registra un nuevo TUPA.</p>
                                                    <a href="{{ route('admin.tupa.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar TUPA
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($tupas->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $tupas->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 2: CATEGORÍAS TUPA (tupa_categories)                          --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'categories'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Categorías de Procedimientos TUPA</h2>
                            <p class="text-sm text-gray-500">Administra las agrupaciones temáticas para organizar los trámites institucionales.</p>
                        </div>

                        <a href="{{ route('admin.tupa.categories.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-folder-plus text-lg"></i>
                            <span>Registrar Nueva Categoría</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.tupa.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <input type="hidden" name="tab" value="categories">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Categoría</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'categories' ? request('search') : '' }}" placeholder="Nombre de categoría..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
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
                                <a href="{{ route('admin.tupa.index', ['tab' => 'categories']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
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
                                        <th class="py-4 px-6">Ícono & Categoría</th>
                                        <th class="py-4 px-6 text-center">Procedimientos</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($categories as $category)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                                        <i class="bi {{ $category->icon }}"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 leading-tight">{{ $category->name }}</h3>
                                                        <span class="text-xs text-gray-400 font-mono">Ícono: {{ $category->icon }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full text-xs font-bold">
                                                    <i class="bi bi-file-earmark-code"></i> {{ $category->procedures_count }} Trámites
                                                </span>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.tupa.categories.toggle-status', $category) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $category->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $category->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $category->is_active ? 'Activa' : 'Inactiva' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.tupa.categories.edit', $category) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Categoría">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.tupa.categories.destroy', $category) }}', '{{ addslashes($category->name) }}', 'Categoría TUPA')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Categoría">
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
                                                        <i class="bi bi-folder-x text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron categorías</p>
                                                    <p class="text-xs text-gray-500">Registra nuevas categorías para agrupar los procedimientos del TUPA.</p>
                                                    <a href="{{ route('admin.tupa.categories.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Categoría
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($categories->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ═════════════════════════════════════════════════════════════════ --}}
                {{-- TAB 3: PROCEDIMIENTOS TUPA (tupa_procedures)                     --}}
                {{-- ═════════════════════════════════════════════════════════════════ --}}
                <div x-show="currentTab === 'procedures'" x-transition:enter="transition ease-out duration-200" class="space-y-6">

                    {{-- Header Actions Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Catálogo de Procedimientos TUPA</h2>
                            <p class="text-sm text-gray-500">Gestiona los requisitos, derechos de pago, plazos y oficinas de cada trámite institucional.</p>
                        </div>

                        <a href="{{ route('admin.tupa.procedures.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2 whitespace-nowrap">
                            <i class="bi bi-file-earmark-plus text-lg"></i>
                            <span>Registrar Nuevo Procedimiento</span>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <form method="GET" action="{{ route('admin.tupa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                            <input type="hidden" name="tab" value="procedures">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar Trámite</label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="search" value="{{ request('tab') === 'procedures' ? request('search') : '' }}" placeholder="Código, nombre..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Categoría</label>
                                <select name="category_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todas las categorías</option>
                                    @foreach($allCategoriesList as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Documento TUPA</label>
                                <select name="tupa_id" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                    <option value="">Todos los TUPAs</option>
                                    @foreach($allTupasList as $tDoc)
                                        <option value="{{ $tDoc->id }}" {{ request('tupa_id') == $tDoc->id ? 'selected' : '' }}>{{ $tDoc->title }}</option>
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
                                <a href="{{ route('admin.tupa.index', ['tab' => 'procedures']) }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
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
                                        <th class="py-4 px-6">Código & Procedimiento</th>
                                        <th class="py-4 px-6">Categoría</th>
                                        <th class="py-4 px-6">Costo / UIT</th>
                                        <th class="py-4 px-6">Plazo / Oficina</th>
                                        <th class="py-4 px-6">Estado</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($procedures as $procedure)
                                        <tr class="hover:bg-purple-50/30 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-start gap-3">
                                                    <span class="px-2.5 py-1 bg-slate-900 text-white font-extrabold text-xs rounded-lg flex-shrink-0 font-mono">
                                                        {{ $procedure->code }}
                                                    </span>
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 leading-tight">{{ $procedure->name }}</h3>
                                                        <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $procedure->description }}</p>
                                                        <div class="mt-1 flex items-center gap-2">
                                                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                                {{ $procedure->qualification }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">
                                                    <i class="bi {{ $procedure->category->icon ?? 'bi-folder' }}"></i>
                                                    {{ $procedure->category->name ?? 'Sin categoría' }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <div class="text-xs">
                                                    <strong class="text-purple-700 font-extrabold block text-sm">{{ $procedure->cost }}</strong>
                                                    @if($procedure->uit_percent)
                                                        <span class="text-gray-400">({{ $procedure->uit_percent }} UIT)</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <div class="text-xs space-y-0.5">
                                                    <div class="font-semibold text-gray-800 flex items-center gap-1">
                                                        <i class="bi bi-clock text-gray-400"></i> {{ $procedure->duration }}
                                                    </div>
                                                    <div class="text-gray-500 flex items-center gap-1">
                                                        <i class="bi bi-building text-gray-400"></i> {{ $procedure->office }}
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap">
                                                <form method="POST" action="{{ route('admin.tupa.procedures.toggle-status', $procedure) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $procedure->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $procedure->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        <span>{{ $procedure->is_active ? 'Activo' : 'Inactivo' }}</span>
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.tupa.procedures.edit', $procedure) }}" class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-xl transition-colors" title="Editar Procedimiento">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <button type="button" @click="confirmDelete('{{ route('admin.tupa.procedures.destroy', $procedure) }}', '{{ addslashes($procedure->code . ' - ' . $procedure->name) }}', 'Procedimiento TUPA')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar Procedimiento">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-12 px-6 text-center">
                                                <div class="max-w-xs mx-auto text-center space-y-3">
                                                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-400 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-file-earmark-x text-3xl"></i>
                                                    </div>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron procedimientos</p>
                                                    <p class="text-xs text-gray-500">Registra nuevos procedimientos administrativos en el catálogo TUPA.</p>
                                                    <a href="{{ route('admin.tupa.procedures.create') }}" class="inline-block px-4 py-2 bg-purple-600 text-white text-xs font-semibold rounded-xl hover:bg-purple-700 transition-colors">
                                                        Registrar Procedimiento
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($procedures->hasPages())
                            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                                {{ $procedures->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- PDF Preview Modal --}}
            <div x-show="showPdfModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
                <div @click.away="showPdfModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-900 text-white">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-file-earmark-pdf text-red-400 text-xl"></i>
                            <h3 class="font-bold text-sm sm:text-base truncate max-w-md" x-text="pdfTitle"></h3>
                        </div>
                        <button type="button" @click="showPdfModal = false" class="text-gray-400 hover:text-white p-1 rounded-lg">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                    <div class="flex-1 bg-gray-100 relative">
                        <iframe :src="pdfUrl" class="w-full h-full border-none"></iframe>
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
    function tupaManagement(initialTab) {
        return {
            currentTab: initialTab || 'documents',
            showPdfModal: false,
            pdfUrl: '',
            pdfTitle: '',
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

            previewPdf(url, title) {
                this.pdfUrl = url;
                this.pdfTitle = title;
                this.showPdfModal = true;
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
