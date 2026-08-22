@extends('layouts.app')
@section('title', 'Gestión de Becas y Créditos - Panel Administrativo')
{{-- @push('styles')
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
    </style>
@endpush --}}
@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
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
                            <i class="bi bi-award text-purple-600"></i> Gestión de Becas y Créditos
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-house-door mr-1"></i> Panel
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Becas y Créditos</span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
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

                    {{-- Filter and Actions Bar --}}
                    <div
                        class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <form action="{{ route('admin.scholarships.index') }}" method="GET"
                            class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
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
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos
                                    </option>
                                </select>
                            </div>

                            {{-- Submit / Clear Buttons --}}
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <button type="submit"
                                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                                    <i class="bi bi-funnel"></i> Filtrar
                                </button>
                                @if (request('search') || request('status') !== null)
                                    <a href="{{ route('admin.scholarships.index') }}"
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
                            <table class="w-full text-left border-collapse min-w-[700px]">
                                <thead>
                                    <tr
                                        class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                        <th class="p-4 w-16 text-center">Icono</th>
                                        <th class="p-4">Nombre & Enlace Slug</th>
                                        <th class="p-4">Descripción</th>
                                        <th class="p-4 text-center w-24">Orden</th>
                                        <th class="p-4 text-center w-28">Estado</th>
                                        <th class="p-4 text-center w-36">Acciones</th>
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
                                                <div class="font-bold text-gray-900 text-base">
                                                    {{ $scholarship->name }}
                                                </div>
                                                <div class="text-xs text-purple-600 font-mono mt-0.5">
                                                    /{{ $scholarship->slug }}
                                                </div>
                                            </td>

                                            {{-- Description --}}
                                            <td class="p-4">
                                                <p class="text-xs text-gray-600 line-clamp-2 max-w-md">
                                                    {{ $scholarship->description ?? 'Sin descripción ingresada.' }}
                                                </p>
                                            </td>

                                            {{-- Sort Order --}}
                                            <td class="p-4 text-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-gray-100 text-gray-700 border border-gray-200">
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
                                                <div class="flex items-center justify-center gap-2">
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
                                            <td colspan="6" class="p-12 text-center text-gray-500">
                                                <div class="max-w-sm mx-auto space-y-3">
                                                    <i class="bi bi-award text-4xl text-gray-300"></i>
                                                    <p class="text-base font-bold text-gray-700">No se encontraron becas</p>
                                                    <p class="text-xs text-gray-500">No hay modalidades de beca registradas
                                                        o ninguna coincide con los filtros aplicados.</p>
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
            </main>
        </div>
    </div>
@endsection
