@extends('layouts.app')
@section('title', 'Gestión de Enlaces Institucionales - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-box-arrow-up-right text-purple-600"></i> Gestión de Enlaces Institucionales
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Panel
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Enlaces Institucionales</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">
                {{-- Alert Messages --}}
                @if(session('success'))
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

                @if(session('error'))
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-xl"></i>
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-rose-500 hover:text-rose-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Summary Stat Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $totalLinks ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Total Enlaces</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $activeLinks ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Enlaces Activos</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl font-bold">
                            <i class="bi bi-eye-slash-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $inactiveLinks ?? 0 }}</p>
                            <p class="text-xs text-gray-500 font-semibold uppercase">Enlaces Inactivos</p>
                        </div>
                    </div>
                </div>

                {{-- Filter and Actions Bar --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.links.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                        {{-- Search Input --}}
                        <div class="relative w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar por nombre o dirección URL..." 
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
                            <button type="submit" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                            @if(request('search') || request('status') !== null)
                                <a href="{{ route('admin.links.index') }}" class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Create Button --}}
                    <a href="{{ route('admin.links.create') }}" 
                       class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                        <i class="bi bi-plus-circle-fill text-lg"></i>
                        <span>Nuevo Enlace</span>
                    </a>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[650px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4 w-16 text-center">Icono</th>
                                    <th class="p-4">Plataforma / Nombre</th>
                                    <th class="p-4">Dirección URL Destino</th>
                                    <th class="p-4 text-center w-28">Estado</th>
                                    <th class="p-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                                @forelse($links as $link)
                                    <tr class="hover:bg-purple-50/30 transition-colors">
                                        {{-- Icon --}}
                                        <td class="p-4 text-center">
                                            <div class="w-10 h-10 mx-auto rounded-xl bg-purple-100/80 border border-purple-200 text-purple-700 flex items-center justify-center text-lg shadow-sm">
                                                <i class="bi {{ $link->icon ?? 'bi-box-arrow-up-right' }}"></i>
                                            </div>
                                        </td>

                                        {{-- Name --}}
                                        <td class="p-4 font-bold text-gray-900">
                                            <div class="text-base font-extrabold text-blue-950">{{ $link->name }}</div>
                                            <span class="text-xs text-gray-400 font-mono">Clase: {{ $link->icon ?? 'bi-box-arrow-up-right' }}</span>
                                        </td>

                                        {{-- Link URL --}}
                                        <td class="p-4">
                                            <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-medium transition-colors break-all">
                                                <i class="bi bi-link-45deg text-sm"></i>
                                                <span>{{ $link->link }}</span>
                                                <i class="bi bi-box-arrow-up-right text-[10px] ml-1"></i>
                                            </a>
                                        </td>

                                        {{-- Status Toggle --}}
                                        <td class="p-4 text-center whitespace-nowrap">
                                            <form action="{{ route('admin.links.toggle-status', $link) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm {{ $link->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $link->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                    {{ $link->is_active ? 'Activo' : 'Inactivo' }}
                                                </button>
                                            </form>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="p-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.links.edit', $link) }}" 
                                                   class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors" 
                                                   title="Editar enlace">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>

                                                <form action="{{ route('admin.links.destroy', $link) }}" method="POST" 
                                                      onsubmit="return confirm('¿Está seguro de eliminar el enlace «{{ $link->name }}»?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" 
                                                        title="Eliminar enlace">
                                                        <i class="bi bi-trash text-base"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-12 text-center text-gray-500">
                                            <div class="max-w-sm mx-auto space-y-3">
                                                <i class="bi bi-box-arrow-up-right text-4xl text-gray-300"></i>
                                                <p class="text-base font-bold text-gray-700">No se encontraron enlaces institucionales</p>
                                                <p class="text-xs text-gray-500">No hay enlaces externos registrados o ninguno coincide con los filtros aplicados.</p>
                                                <a href="{{ route('admin.links.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition">
                                                    Registrar Primer Enlace
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($links->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $links->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
