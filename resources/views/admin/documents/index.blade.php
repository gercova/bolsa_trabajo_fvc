@extends('layouts.app')
@section('title', 'Documentos de Gestión - Panel Administrativo')

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
                            <i class="bi bi-folder-symlink text-purple-600"></i> Documentos de Gestión
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Panel
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Documentos de Gestión</span>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-7xl mx-auto space-y-6">
                    
                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
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
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Actions & Filter Bar --}}
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <form action="{{ route('admin.documents.index') }}" method="GET"
                            class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                            {{-- Search Input --}}
                            <div class="relative w-full sm:max-w-md">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar por título o descripción..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>

                            {{-- Status Filter --}}
                            <div class="w-full sm:w-auto">
                                <select name="status" onchange="this.form.submit()"
                                    class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700 font-medium">
                                    <option value="">Todos los Estados</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                                </select>
                            </div>

                            {{-- Clear Filters --}}
                            @if (request()->hasAny(['search', 'status']))
                                <a href="{{ route('admin.documents.index') }}"
                                    class="px-3.5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all flex items-center gap-1.5 w-full sm:w-auto justify-center">
                                    <i class="bi bi-x-circle text-gray-500"></i> Limpiar
                                </a>
                            @endif
                        </form>

                        {{-- Create Button --}}
                        <a href="{{ route('admin.documents.create') }}"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all duration-200">
                            <i class="bi bi-plus-lg text-base"></i>
                            <span>Nuevo Documento</span>
                        </a>
                    </div>

                    {{-- Documents List Table --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="px-6 py-4">Documento</th>
                                        <th class="px-6 py-4">Archivo</th>
                                        <th class="px-6 py-4">Vigencia</th>
                                        <th class="px-6 py-4 text-center">Estado</th>
                                        <th class="px-6 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm">
                                    @forelse ($documents as $doc)
                                        @php
                                            $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                                            $fileUrl = Storage::url($doc->file_path);
                                        @endphp
                                        <tr class="hover:bg-gray-50/60 transition-colors">
                                            {{-- Title & Info --}}
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-gray-900 text-base max-w-md truncate">
                                                    {{ $doc->title }}
                                                </div>
                                                @if ($doc->description)
                                                    <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">
                                                        {{ $doc->description }}
                                                    </p>
                                                @endif
                                            </td>

                                            {{-- File Preview/Badge --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-purple-50 hover:border-purple-200 text-gray-700 hover:text-purple-700 text-xs font-medium transition-all group">
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                                        <i class="bi bi-file-earmark-image text-emerald-600 text-base group-hover:scale-110 transition-transform"></i>
                                                    @elseif ($ext === 'pdf')
                                                        <i class="bi bi-file-earmark-pdf text-red-600 text-base group-hover:scale-110 transition-transform"></i>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <i class="bi bi-file-earmark-word text-blue-600 text-base group-hover:scale-110 transition-transform"></i>
                                                    @elseif (in_array($ext, ['xls', 'xlsx']))
                                                        <i class="bi bi-file-earmark-excel text-emerald-700 text-base group-hover:scale-110 transition-transform"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark-text text-purple-600 text-base group-hover:scale-110 transition-transform"></i>
                                                    @endif
                                                    <span class="uppercase tracking-wider font-semibold text-[11px]">{{ $ext ?: 'DOC' }}</span>
                                                    <i class="bi bi-box-arrow-up-right text-[10px] text-gray-400 group-hover:text-purple-600"></i>
                                                </a>
                                            </td>

                                            {{-- Validity Period --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                                @if ($doc->validity_period)
                                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200/80 px-2.5 py-1 rounded-full font-medium">
                                                        <i class="bi bi-calendar-event text-xs"></i>
                                                        {{ $doc->validity_period->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">Sin fecha de vigencia</span>
                                                @endif
                                            </td>

                                            {{-- Status Toggle --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <form action="{{ route('admin.documents.toggle-status', $doc) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        title="Haz clic para cambiar estado"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-all shadow-sm {{ $doc->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $doc->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                                        {{ $doc->is_active ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </form>
                                            </td>

                                            {{-- Action Buttons --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    {{-- View / Download --}}
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        title="Ver / Descargar"
                                                        class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                                        <i class="bi bi-eye text-lg"></i>
                                                    </a>

                                                    {{-- Edit --}}
                                                    <a href="{{ route('admin.documents.edit', $doc) }}"
                                                        title="Editar documento"
                                                        class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                                        <i class="bi bi-pencil-square text-lg"></i>
                                                    </a>

                                                    {{-- Delete --}}
                                                    <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST"
                                                        onsubmit="return confirm('¿Está seguro de que desea eliminar este documento de gestión? Esta acción no se puede deshacer.');"
                                                        class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            title="Eliminar documento"
                                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                            <i class="bi bi-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                <div class="max-w-sm mx-auto flex flex-col items-center justify-center space-y-3">
                                                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500">
                                                        <i class="bi bi-folder2-open text-3xl"></i>
                                                    </div>
                                                    <h3 class="text-base font-semibold text-gray-800">No se encontraron documentos</h3>
                                                    <p class="text-xs text-gray-500">
                                                        {{ request('search') || request('status') ? 'No hay resultados para los filtros seleccionados.' : 'Comience registrando un nuevo documento de gestión.' }}
                                                    </p>
                                                    @if (!request('search') && !request('status'))
                                                        <a href="{{ route('admin.documents.create') }}"
                                                            class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs rounded-xl transition-all shadow-sm">
                                                            <i class="bi bi-plus-lg"></i> Crear Primer Documento
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($documents->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                                {{ $documents->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
