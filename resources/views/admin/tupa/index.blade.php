@extends('layouts.app')
@section('title', 'Gestión de TUPA - Panel Administrativo')
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
                    <span class="text-purple-600">Reglamento TUPA</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="tupaManagement()">
            <div class="max-w-7xl mx-auto space-y-6">

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

                {{-- Header Actions Bar --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Registros de Documentos TUPA</h2>
                        <p class="text-sm text-gray-500">Administra los reglamentos y cuadros de tasas administrativas del instituto.</p>
                    </div>

                    <a href="{{ route('admin.tupa.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 gap-2">
                        <i class="bi bi-plus-circle text-lg"></i>
                        <span>Registrar Nuevo TUPA</span>
                    </a>
                </div>

                {{-- Filters --}}
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <form method="GET" action="{{ route('admin.tupa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Título o palabra clave..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
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
                            <a href="{{ route('admin.tupa.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm transition-colors" title="Limpiar filtros">
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

                                                <button type="button" @click="confirmDelete('{{ route('admin.tupa.destroy', $tupa) }}', '{{ addslashes($tupa->title) }}')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Eliminar TUPA">
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
                        <h3 class="text-lg font-bold text-gray-900">¿Eliminar registro TUPA?</h3>
                        <p class="text-xs text-gray-500 mt-1">Esta acción eliminará permanentemente el registro <strong class="text-gray-700" x-text="deleteTitle"></strong> y su archivo adjunto.</p>
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
    function tupaManagement() {
        return {
            showPdfModal: false,
            pdfUrl: '',
            pdfTitle: '',
            showDeleteModal: false,
            deleteUrl: '',
            deleteTitle: '',

            previewPdf(url, title) {
                this.pdfUrl = url;
                this.pdfTitle = title;
                this.showPdfModal = true;
            },

            confirmDelete(url, title) {
                this.deleteUrl = url;
                this.deleteTitle = title;
                this.showDeleteModal = true;
            }
        }
    }
</script>
@endpush
@endsection
