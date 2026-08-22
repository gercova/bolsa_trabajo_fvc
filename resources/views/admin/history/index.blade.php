@extends('layouts.app')
@section('title', 'Gestión de Historia Institucional - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="historyManagement()">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-clock-history text-purple-600"></i> Historia Institucional
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-building mr-1"></i> Empresa
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600 font-semibold">Historia Institucional</span>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Toasts Flotantes Dinámicos --}}
                <div class="fixed bottom-5 right-5 z-[100] space-y-3 w-full max-w-sm" x-cloak>
                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'"
                             class="text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between gap-3 border border-white/10">
                            <div class="flex items-center gap-2.5">
                                <i :class="toast.type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'" class="bi text-base"></i>
                                <p class="text-sm font-semibold tracking-wide" x-text="toast.message"></p>
                            </div>
                            <button type="button" @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-white/80 hover:text-white transition-colors p-1">
                                <i class="bi bi-x-lg text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Session Flash Alert --}}
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

                {{-- Filter and Actions Bar --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.history.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                        {{-- Search Input --}}
                        <div class="relative w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por título, año o contenido..."
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
                            @if (request('search') || request('status') !== null)
                                <a href="{{ route('admin.history.index') }}" class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Create Button --}}
                    <a href="{{ route('admin.history.create') }}"
                        class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                        <i class="bi bi-plus-circle-fill text-lg"></i>
                        <span>Nuevo Hito Histórico</span>
                    </a>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4 w-20 text-center">Foto</th>
                                    <th class="p-4">Etapa / Hito Histórico</th>
                                    <th class="p-4">Período</th>
                                    <th class="p-4">Descripción</th>
                                    <th class="p-4 text-center w-24">Orden</th>
                                    <th class="p-4 text-center w-28">Estado</th>
                                    <th class="p-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($histories as $history)
                                    <tr class="hover:bg-purple-50/30 transition-colors" x-data="{ isActive: {{ $history->is_active ? 'true' : 'false' }} }">
                                        
                                        {{-- Foto / Miniatura --}}
                                        <td class="p-4 text-center">
                                            @if($history->image_path)
                                                <div class="relative group mx-auto w-14 h-14 rounded-xl border border-gray-200 overflow-hidden shadow-sm bg-gray-50 flex items-center justify-center cursor-pointer"
                                                     @click="previewImage('{{ $history->image_url }}', '{{ addslashes($history->title) }}')">
                                                    <img src="{{ $history->image_url }}" 
                                                         alt="{{ $history->title }}" 
                                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                        <i class="bi bi-zoom-in text-sm"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="w-14 h-14 mx-auto rounded-xl bg-purple-50 border border-purple-100 text-purple-400 flex flex-col items-center justify-center text-xs font-semibold shadow-inner">
                                                    <i class="bi bi-image text-lg"></i>
                                                    <span class="text-[9px] uppercase tracking-wider text-purple-300">Sin foto</span>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Título --}}
                                        <td class="p-4">
                                            <div class="font-bold text-gray-900 text-base">
                                                {{ $history->title }}
                                            </div>
                                        </td>

                                        {{-- Período (Años) --}}
                                        <td class="p-4 whitespace-nowrap">
                                            @if($history->start_year)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i class="bi bi-calendar3 mr-1 text-[10px]"></i>
                                                    {{ $history->start_year }} - {{ $history->end_year ?? 'Presente' }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No especificado</span>
                                            @endif
                                        </td>

                                        {{-- Descripción --}}
                                        <td class="p-4">
                                            <p class="text-xs text-gray-600 line-clamp-2 max-w-sm leading-relaxed">
                                                {{ Str::limit(strip_tags($history->description), 140) }}
                                            </p>
                                        </td>

                                        {{-- Orden --}}
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-gray-100 text-gray-700 border border-gray-200">
                                                #{{ $history->order }}
                                            </span>
                                        </td>

                                        {{-- Estado interactivo --}}
                                        <td class="p-4 text-center">
                                            <button type="button"
                                                @click="toggleHistoryStatus({{ $history->id }}, $data)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-purple-500"
                                                :class="isActive ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200'">
                                                <span class="w-2 h-2 rounded-full" :class="isActive ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></span>
                                                <span x-text="isActive ? 'Activo' : 'Inactivo'"></span>
                                            </button>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.history.edit', $history) }}"
                                                    class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                                    title="Editar hito histórico">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>

                                                <form action="{{ route('admin.history.destroy', $history) }}" method="POST"
                                                    onsubmit="return confirm('¿Está seguro de eliminar el hito histórico «{{ addslashes($history->title) }}»? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors"
                                                        title="Eliminar hito histórico">
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
                                                <i class="bi bi-clock-history text-4xl text-gray-300"></i>
                                                <p class="text-base font-bold text-gray-700">No se encontraron hitos históricos</p>
                                                <p class="text-xs text-gray-500">No hay etapas históricas registradas o ninguna coincide con los filtros aplicados.</p>
                                                <a href="{{ route('admin.history.create') }}"
                                                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition shadow-sm">
                                                    Registrar Primer Hito
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($histories->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $histories->links() }}
                        </div>
                    @endif
                </div>

                {{-- Lightbox Modal para Preview de Foto --}}
                <div x-show="showImageModal" class="fixed inset-0 z-[70] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:p-0">
                        <div x-show="showImageModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                             class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm transition-opacity" 
                             @click="showImageModal = false"></div>

                        <div x-show="showImageModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
                             class="relative bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl z-10 text-left overflow-hidden">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                                <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2" x-text="modalImageTitle"></h3>
                                <button type="button" @click="showImageModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                                    <i class="bi bi-x-lg text-lg"></i>
                                </button>
                            </div>
                            <div class="rounded-xl overflow-hidden bg-gray-100 border border-gray-200 max-h-[70vh] flex items-center justify-center">
                                <img :src="modalImageUrl" :alt="modalImageTitle" class="max-h-[65vh] w-auto object-contain rounded-lg">
                            </div>
                            <div class="mt-4 text-right">
                                <a :href="modalImageUrl" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 hover:text-purple-800">
                                    <i class="bi bi-box-arrow-up-right"></i> Ver imagen en tamaño original
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('historyManagement', () => ({
            toasts: [],
            showImageModal: false,
            modalImageUrl: '',
            modalImageTitle: '',

            previewImage(url, title) {
                this.modalImageUrl = url;
                this.modalImageTitle = title;
                this.showImageModal = true;
            },

            showToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 3500);
            },

            async toggleHistoryStatus(id, rowScope) {
                try {
                    const response = await fetch(`/admin-historia/estado/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        rowScope.isActive = data.is_active;
                        this.showToast(data.message || 'Estado actualizado.', 'success');
                    } else {
                        this.showToast(data.message || 'No se pudo actualizar el estado.', 'error');
                    }
                } catch (error) {
                    this.showToast('Ocurrió un error inesperado al actualizar el estado.', 'error');
                }
            }
        }));
    });
</script>
@endpush
