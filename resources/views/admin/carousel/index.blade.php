@extends('layouts.app')
@section('title', 'Gestión de Carrusel Institucional - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative" x-data="carouselManagement()">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-images text-purple-600"></i> Carrusel Institucional (Portada)
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-building mr-1"></i> Empresa
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600 font-semibold">Carrusel Principal</span>
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
                    <form action="{{ route('admin.carousel.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
                        {{-- Search Input --}}
                        <div class="relative w-full sm:max-w-md">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por título, texto resaltado o etiqueta..."
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
                                <a href="{{ route('admin.carousel.index') }}" class="px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold rounded-xl transition-colors">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    {{-- Create Button --}}
                    <a href="{{ route('admin.carousel.create') }}"
                        class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md shadow-purple-500/20 flex items-center justify-center gap-2">
                        <i class="bi bi-plus-circle-fill text-lg"></i>
                        <span>Nueva Diapositiva</span>
                    </a>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4 w-28 text-center">Imagen</th>
                                    <th class="p-4">Diapositiva / Contenido</th>
                                    <th class="p-4">Etiqueta & Color</th>
                                    <th class="p-4">Botones de Acción</th>
                                    <th class="p-4 text-center w-20">Orden</th>
                                    <th class="p-4 text-center w-28">Estado</th>
                                    <th class="p-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($carousels as $carousel)
                                    <tr class="hover:bg-purple-50/30 transition-colors" x-data="{ isActive: {{ $carousel->is_active ? 'true' : 'false' }} }">
                                        
                                        {{-- Foto / Miniatura --}}
                                        <td class="p-4 text-center">
                                            <div class="relative group mx-auto w-24 h-16 rounded-xl border border-gray-200 overflow-hidden shadow-sm bg-slate-900 flex items-center justify-center cursor-pointer"
                                                 @click="previewImage('{{ $carousel->image_url }}', '{{ addslashes($carousel->title) }}')">
                                                <img src="{{ $carousel->image_url }}" 
                                                     alt="{{ $carousel->title }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                    <i class="bi bi-zoom-in text-base"></i>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Contenido del Slide --}}
                                        <td class="p-4 max-w-xs">
                                            <div class="space-y-1">
                                                <div class="font-bold text-gray-900 text-sm leading-snug">
                                                    {{ $carousel->title }}
                                                </div>
                                                @if($carousel->highlight_text)
                                                    <div class="inline-block px-2 py-0.5 rounded-md text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                                        <i class="bi bi-stars text-purple-600 mr-1"></i>{{ $carousel->highlight_text }}
                                                    </div>
                                                @endif
                                                @if($carousel->description)
                                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                                        {{ $carousel->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Etiqueta & Color --}}
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="space-y-1.5">
                                                @if($carousel->tag)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                        <i class="bi {{ $carousel->tag_icon ?? 'bi-tag-fill' }} text-purple-600"></i>
                                                        <span class="truncate max-w-[150px]">{{ $carousel->tag }}</span>
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sin etiqueta</span>
                                                @endif

                                                <div>
                                                    @php
                                                        $colorsMap = [
                                                            'amber'   => ['bg' => 'bg-amber-500', 'text' => 'Amber / Dorado'],
                                                            'sky'     => ['bg' => 'bg-sky-500', 'text' => 'Sky / Celeste'],
                                                            'rose'    => ['bg' => 'bg-rose-500', 'text' => 'Rose / Rosa'],
                                                            'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'Emerald / Verde'],
                                                            'indigo'  => ['bg' => 'bg-indigo-500', 'text' => 'Indigo / Azul'],
                                                            'purple'  => ['bg' => 'bg-purple-500', 'text' => 'Purple / Morado'],
                                                        ];
                                                        $c = $colorsMap[$carousel->tag_color ?? 'amber'] ?? $colorsMap['amber'];
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-600">
                                                        <span class="w-2.5 h-2.5 rounded-full {{ $c['bg'] }}"></span>
                                                        {{ $c['text'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Botones de Acción --}}
                                        <td class="p-4">
                                            <div class="space-y-1 text-xs">
                                                @if($carousel->primary_button_text)
                                                    <div class="flex items-center gap-1.5 text-blue-700 font-medium truncate max-w-[180px]">
                                                        <i class="bi {{ $carousel->primary_button_icon ?? 'bi-link' }}"></i>
                                                        <span class="font-semibold">{{ $carousel->primary_button_text }}</span>
                                                    </div>
                                                @endif
                                                @if($carousel->secondary_button_text)
                                                    <div class="flex items-center gap-1.5 text-slate-600 font-medium truncate max-w-[180px]">
                                                        <i class="bi {{ $carousel->secondary_button_icon ?? 'bi-link' }}"></i>
                                                        <span>{{ $carousel->secondary_button_text }}</span>
                                                    </div>
                                                @endif
                                                @if(!$carousel->primary_button_text && !$carousel->secondary_button_text)
                                                    <span class="text-xs text-gray-400 italic">Sin botones</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Orden --}}
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-gray-100 text-gray-700 border border-gray-200">
                                                #{{ $carousel->order }}
                                            </span>
                                        </td>

                                        {{-- Estado interactivo --}}
                                        <td class="p-4 text-center">
                                            <button type="button"
                                                @click="toggleCarouselStatus({{ $carousel->id }}, $data)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-purple-500 cursor-pointer"
                                                :class="isActive ? 'bg-emerald-100 text-emerald-700 border border-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 hover:bg-rose-200'">
                                                <span class="w-2 h-2 rounded-full" :class="isActive ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></span>
                                                <span x-text="isActive ? 'Activo' : 'Inactivo'"></span>
                                            </button>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.carousel.edit', $carousel) }}"
                                                    class="p-2 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors"
                                                    title="Editar diapositiva">
                                                    <i class="bi bi-pencil-square text-base"></i>
                                                </a>

                                                <form action="{{ route('admin.carousel.destroy', $carousel) }}" method="POST"
                                                    onsubmit="return confirm('¿Está seguro de eliminar la diapositiva «{{ addslashes($carousel->title) }}»? Esta acción eliminará permanentemente la foto asociada.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer"
                                                        title="Eliminar diapositiva">
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
                                                <i class="bi bi-images text-4xl text-gray-300"></i>
                                                <p class="text-base font-bold text-gray-700">No se encontraron diapositivas</p>
                                                <p class="text-xs text-gray-500">No hay diapositivas registradas o ninguna coincide con los filtros aplicados.</p>
                                                <a href="{{ route('admin.carousel.create') }}"
                                                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition shadow-sm">
                                                    Crear Primera Diapositiva
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($carousels->hasPages())
                        <div class="p-4 bg-gray-50 border-t border-gray-200">
                            {{ $carousels->links() }}
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
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                             class="relative inline-block bg-slate-900 rounded-3xl overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full border border-slate-700/60 text-left">
                            
                            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800 bg-slate-950/50">
                                <h3 class="text-base font-bold text-white flex items-center gap-2 truncate" x-text="modalTitle"></h3>
                                <button type="button" @click="showImageModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                                    <i class="bi bi-x-lg text-lg"></i>
                                </button>
                            </div>

                            <div class="p-4 bg-slate-950 flex items-center justify-center min-h-[300px] max-h-[70vh]">
                                <img :src="modalImageUrl" :alt="modalTitle" class="max-h-[65vh] w-auto max-w-full object-contain rounded-xl shadow-lg">
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
    function carouselManagement() {
        return {
            toasts: [],
            showImageModal: false,
            modalImageUrl: '',
            modalTitle: '',

            addToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 4000);
            },

            previewImage(url, title) {
                this.modalImageUrl = url;
                this.modalTitle = title;
                this.showImageModal = true;
            },

            async toggleCarouselStatus(id, rowScope) {
                try {
                    const response = await fetch(`/admin-carrusel/estado/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        rowScope.isActive = data.is_active;
                        this.addToast(data.message || 'Estado actualizado.', 'success');
                    } else {
                        this.addToast('No se pudo actualizar el estado.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    this.addToast('Error de comunicación con el servidor.', 'error');
                }
            }
        }
    }
</script>
@endpush
