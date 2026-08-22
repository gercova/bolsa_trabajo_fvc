@extends('layouts.app')
@section('title', 'Gestión de Exámenes de Admisión / CEPRE - Panel Administrativo')
@push('styles')
    <style>
        [x-cloak] { display: none !important; }

        /* ===== CUSTOM SCROLLBAR ===== */
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ===== FADE-IN ===== */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }

        /* ===== TAB PANEL ===== */
        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px 10px 0 0;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.2s;
            border: 2px solid transparent;
            border-bottom: none;
            cursor: pointer;
            position: relative;
            bottom: -2px;
        }
        .tab-btn.active {
            background: #ffffff;
            border-color: #e5e7eb;
            border-bottom-color: #ffffff;
            color: #7c3aed;
        }
        .tab-btn:not(.active) {
            background: #f9fafb;
            color: #6b7280;
        }
        .tab-btn:not(.active):hover {
            color: #7c3aed;
            background: #f3f4f6;
        }
        .tab-panel { border-top: 2px solid #e5e7eb; }

        /* ===== IMAGE UPLOAD CARD ===== */
        .image-upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .image-upload-zone:hover {
            border-color: #7c3aed;
            background: #f5f3ff;
        }

        /* ===== CUSTOM PAGINATOR ===== */
        .paginator-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1.5px solid #e5e7eb;
            background: #ffffff;
            color: #374151;
            transition: all 0.15s;
        }
        .paginator-btn:hover:not(:disabled):not(.active) {
            border-color: #7c3aed;
            color: #7c3aed;
            background: #f5f3ff;
        }
        .paginator-btn.active {
            background: #7c3aed;
            border-color: #7c3aed;
            color: #ffffff;
            font-weight: 800;
        }
        .paginator-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .paginator-btn.ellipsis {
            border: none;
            background: transparent;
            color: #9ca3af;
        }
    </style>
@endpush
@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="enterpriseApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">

            {{-- ===== STICKY HEADER ===== --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="toggleSidebar()"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                            Gestión de Exámenes de Admisión / CEPRE
                        </h1>
                    </div>
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Inicio
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Admisión / CEPRE</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden"
                x-data="admissionApp()">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- ===== FLASH MESSAGE ===== --}}
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                            <div class="flex items-center">
                                <i class="bi bi-check-circle-fill text-green-500 text-xl flex-shrink-0"></i>
                                <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                                <button type="button" class="ml-auto text-green-500 hover:text-green-700"
                                    onclick="this.parentElement.parentElement.remove()">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                    {{-- SECTION 1: BANNER IMAGE MANAGEMENT --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center">
                                    <i class="bi bi-images text-purple-600 text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-extrabold text-gray-800 tracking-tight">Gestión de Imágenes de Portada</h2>
                                    <p class="text-xs text-gray-500 mt-0.5">Actualiza el banner hero de las páginas públicas de Admisión y CEPRE.</p>
                                </div>
                            </div>
                            <span class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-purple-700 bg-purple-50 border border-purple-200 px-3 py-1 rounded-full">
                                <i class="bi bi-shield-check"></i> PNG · JPG · WEBP · max 4 MB
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">

                            {{-- ─── CEPRE Banner ─── --}}
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full">
                                        <i class="bi bi-journal-bookmark-fill"></i> CEPRE-FVC
                                    </span>
                                    <a href="{{ route('cepre-fvc') }}" target="_blank"
                                        class="ml-auto text-xs font-semibold text-gray-500 hover:text-purple-600 flex items-center gap-1">
                                        <i class="bi bi-box-arrow-up-right text-xs"></i> Ver página
                                    </a>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 items-start">
                                    {{-- Current Image Preview --}}
                                    <div class="w-full sm:w-36 flex-shrink-0">
                                        <div class="aspect-video rounded-xl overflow-hidden border-2 border-gray-100 bg-gray-50">
                                            <img id="ceprePreview"
                                                src="{{ $cepreImage ? $cepreImage->url : asset('images/cepre_hero_banner.png') }}"
                                                alt="Banner CEPRE" class="w-full h-full object-cover">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1.5 text-center font-medium">Imagen actual</p>
                                    </div>
                                    {{-- Upload Form --}}
                                    <form action="{{ route('admin.exams.update-cepre-image') }}" method="POST"
                                        enctype="multipart/form-data" class="flex-1 w-full">
                                        @csrf
                                        <label for="cepreImageInput" class="image-upload-zone block w-full">
                                            <i class="bi bi-cloud-arrow-up text-2xl text-purple-400 mb-1"></i>
                                            <p class="text-sm font-semibold text-gray-700">Subir nueva imagen</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Haz clic para seleccionar o arrastra aquí</p>
                                            <input type="file" id="cepreImageInput" name="image" accept="image/*" class="hidden"
                                                onchange="previewImage(this, 'ceprePreview', 'cepreFileLabel')">
                                        </label>
                                        <p id="cepreFileLabel" class="text-xs text-purple-600 font-semibold mt-2 truncate hidden"></p>
                                        @error('image')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                        <button type="submit"
                                            class="mt-3 w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                            <i class="bi bi-upload"></i> Actualizar Banner CEPRE
                                        </button>
                                    </form>
                                </div>
                            </div>
                            {{-- ─── Admisión Banner ─── --}}
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-full">
                                        <i class="bi bi-award-fill"></i> Examen de Admisión
                                    </span>
                                    <a href="{{ route('examen-de-admision') }}" target="_blank"
                                        class="ml-auto text-xs font-semibold text-gray-500 hover:text-purple-600 flex items-center gap-1">
                                        <i class="bi bi-box-arrow-up-right text-xs"></i> Ver página
                                    </a>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4 items-start">
                                    {{-- Current Image Preview --}}
                                    <div class="w-full sm:w-36 flex-shrink-0">
                                        <div class="aspect-video rounded-xl overflow-hidden border-2 border-gray-100 bg-gray-50">
                                            <img id="admisionPreview"
                                                src="{{ $admisionImage ? $admisionImage->url : asset('images/admission_hero_banner.png') }}"
                                                alt="Banner Admisión" class="w-full h-full object-cover">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1.5 text-center font-medium">Imagen actual</p>
                                    </div>
                                    {{-- Upload Form --}}
                                    <form action="{{ route('admin.exams.update-admission-image') }}" method="POST"
                                        enctype="multipart/form-data" class="flex-1 w-full">
                                        @csrf
                                        <label for="admisionImageInput" class="image-upload-zone block w-full">
                                            <i class="bi bi-cloud-arrow-up text-2xl text-purple-400 mb-1"></i>
                                            <p class="text-sm font-semibold text-gray-700">Subir nueva imagen</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Haz clic para seleccionar o arrastra aquí</p>
                                            <input type="file" id="admisionImageInput" name="image" accept="image/*" class="hidden"
                                                onchange="previewImage(this, 'admisionPreview', 'admisionFileLabel')">
                                        </label>
                                        <p id="admisionFileLabel" class="text-xs text-purple-600 font-semibold mt-2 truncate hidden"></p>
                                        <button type="submit"
                                            class="mt-3 w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                            <i class="bi bi-upload"></i> Actualizar Banner Admisión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- Image Table (records) --}}
                        <div class="border-t border-gray-100">
                            <div class="px-5 py-3 flex items-center gap-2 bg-gray-50/60">
                                <i class="bi bi-table text-gray-400 text-sm"></i>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Registros en Base de Datos</span>
                            </div>
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left text-sm min-w-[600px]">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                            <th class="px-5 py-3">Tipo de Banner</th>
                                            <th class="px-5 py-3">Ruta del Archivo</th>
                                            <th class="px-5 py-3">Principal</th>
                                            <th class="px-5 py-3">Actualizado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @if($cepreImage)
                                            <tr class="hover:bg-gray-50/60 transition-colors">
                                                <td class="px-5 py-3">
                                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-full">
                                                        <i class="bi bi-journal-bookmark-fill"></i> CEPRE-FVC
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3 font-mono text-xs text-gray-600 max-w-xs truncate">{{ $cepreImage->path }}</td>
                                                <td class="px-5 py-3">
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium {{ $cepreImage->is_main ? 'text-green-700 bg-green-50' : 'text-gray-500 bg-gray-100' }} px-2 py-0.5 rounded-full">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $cepreImage->is_main ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                                        {{ $cepreImage->is_main ? 'Sí' : 'No' }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-gray-500">{{ $cepreImage->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @else
                                            <tr><td colspan="4" class="px-5 py-3 text-xs text-gray-400 italic">Sin registro — ejecuta AdmissionImageSeeder</td></tr>
                                        @endif
                                        @if($admisionImage)
                                            <tr class="hover:bg-gray-50/60 transition-colors">
                                                <td class="px-5 py-3">
                                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-purple-50 border border-purple-100 px-2.5 py-1 rounded-full">
                                                        <i class="bi bi-award-fill"></i> Examen de Admisión
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3 font-mono text-xs text-gray-600 max-w-xs truncate">{{ $admisionImage->path }}</td>
                                                <td class="px-5 py-3">
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium {{ $admisionImage->is_main ? 'text-green-700 bg-green-50' : 'text-gray-500 bg-gray-100' }} px-2 py-0.5 rounded-full">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $admisionImage->is_main ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                                        {{ $admisionImage->is_main ? 'Sí' : 'No' }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3 text-xs text-gray-500">{{ $admisionImage->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @else
                                            <tr><td colspan="4" class="px-5 py-3 text-xs text-gray-400 italic">Sin registro — ejecuta AdmissionImageSeeder</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- SECTION 2: FILTERS + NEW BUTTON --}}
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-200 space-y-4">
                        <form action="{{ route('admin.exams.index') }}" method="GET" id="filterForm" class="w-full space-y-4">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="relative lg:col-span-2">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Buscar por período o actividad..."
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm">
                                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    @if (request('search'))
                                        <a href="{{ route('admin.exams.index', request()->except('search')) }}"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <input type="text" name="date" value="{{ request('date') }}"
                                        placeholder="Rango de fechas (AAAA-MM-DD - AAAA-MM-DD)"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm">
                                    <i class="bi bi-calendar-range absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    @if (request('date'))
                                        <a href="{{ route('admin.exams.index', request()->except('date')) }}"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="bi bi-x-circle"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                {{-- Tipo --}}
                                <div class="relative flex-1 min-w-[140px]">
                                    <select name="type"
                                        class="appearance-none w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white"
                                        onchange="this.form.submit()">
                                        <option value="">Todos los tipos</option>
                                        <option value="ordinario" {{ request('type') === 'ordinario' ? 'selected' : '' }}>Ordinario</option>
                                        <option value="extraordinario" {{ request('type') === 'extraordinario' ? 'selected' : '' }}>Extraordinario</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                                {{-- Estado --}}
                                <div class="relative flex-1 min-w-[140px]">
                                    <select name="status"
                                        class="appearance-none w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white"
                                        onchange="this.form.submit()">
                                        <option value="">Todos los estados</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                                {{-- Por página --}}
                                <div class="relative min-w-[120px]">
                                    <select name="per_page"
                                        class="appearance-none w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white"
                                        onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 por pág</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 por pág</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 por pág</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 por pág</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                                <button type="submit"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm font-semibold text-sm">
                                    <i class="bi bi-funnel-fill"></i> Filtrar
                                </button>

                                @if (request()->hasAny(['search', 'type', 'status', 'date']))
                                    <a href="{{ route('admin.exams.index') }}"
                                        class="text-sm font-semibold text-gray-500 hover:text-red-600 flex items-center gap-1.5 transition">
                                        <i class="bi bi-x-circle"></i> Limpiar
                                    </a>
                                @endif

                                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                            </div>
                        </form>

                        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-1">
                            <div class="text-sm text-gray-500 font-medium">
                                <span class="font-bold text-purple-700">{{ $cepreAdmissions->total() }}</span> CEPRE ·
                                <span class="font-bold text-purple-700">{{ $admisionAdmissions->total() }}</span> Admisión
                            </div>
                            <a href="{{ route('admin.exams.create') }}"
                                class="w-full sm:w-auto bg-purple-600 text-white px-5 py-2.5 rounded-xl hover:bg-purple-700 transition flex items-center justify-center gap-2 shadow-sm font-bold text-sm">
                                <i class="bi bi-plus-lg"></i> Nuevo Examen / CEPRE
                            </a>
                        </div>
                    </div>
                    {{-- SECTION 3: TAB PANEL --}}
                    <div x-data="{ activeTab: '{{ request()->has('cepre_page') || !request()->has('admision_page') ? 'cepre' : 'admision' }}' }">

                        {{-- Tab Buttons --}}
                        <div class="flex items-end gap-1 px-1 border-b-2 border-gray-200">
                            <button @click="activeTab = 'cepre'"
                                :class="activeTab === 'cepre' ? 'active' : ''"
                                class="tab-btn">
                                <i class="bi bi-journal-bookmark-fill"></i>
                                CEPRE-FVC
                                <span class="ml-1 text-xs font-black px-2 py-0.5 rounded-full"
                                    :class="activeTab === 'cepre' ? 'bg-purple-100 text-purple-700' : 'bg-gray-200 text-gray-600'">
                                    {{ $cepreAdmissions->total() }}
                                </span>
                            </button>
                            <button @click="activeTab = 'admision'"
                                :class="activeTab === 'admision' ? 'active' : ''"
                                class="tab-btn">
                                <i class="bi bi-award-fill"></i>
                                Examen de Admisión
                                <span class="ml-1 text-xs font-black px-2 py-0.5 rounded-full"
                                    :class="activeTab === 'admision' ? 'bg-purple-100 text-purple-700' : 'bg-gray-200 text-gray-600'">
                                    {{ $admisionAdmissions->total() }}
                                </span>
                            </button>
                        </div>

                        {{-- ─── TAB: CEPRE ─── --}}
                        <div x-show="activeTab === 'cepre'" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="bg-white rounded-b-2xl rounded-tr-2xl border border-t-0 border-gray-200 shadow-sm overflow-hidden tab-panel">
                            @include('admin.admission._table', [
                                'items'   => $cepreAdmissions,
                                'process' => 'cepre',
                                'tabLabel' => 'CEPRE',
                                'pageParam' => 'cepre_page',
                            ])
                        </div>

                        {{-- ─── TAB: ADMISIÓN ─── --}}
                        <div x-show="activeTab === 'admision'" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="bg-white rounded-b-2xl rounded-tr-2xl border border-t-0 border-gray-200 shadow-sm overflow-hidden tab-panel"
                            x-cloak>
                            @include('admin.admission._table', [
                                'items'   => $admisionAdmissions,
                                'process' => 'admision',
                                'tabLabel' => 'Admisión',
                                'pageParam' => 'admision_page',
                            ])
                        </div>

                    </div>
                    {{-- END TAB PANEL --}}

                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ===== Live image preview before upload ===== */
        function previewImage(input, previewId, labelId) {
            const file = input.files[0];
            if (!file) return;
            const label = document.getElementById(labelId);
            label.textContent = file.name;
            label.classList.remove('hidden');
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('alpine:init', () => {
            if (!Alpine.data('enterpriseApp')) {
                Alpine.data('enterpriseApp', () => ({
                    sidebarOpen: window.innerWidth >= 1024,
                    toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
                }));
            }
            Alpine.data('admissionApp', () => ({}));
        });
    </script>
@endpush
