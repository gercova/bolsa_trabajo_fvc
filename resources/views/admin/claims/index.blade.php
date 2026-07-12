@extends('layouts.app')
@section('title', 'Gestión de Reclamos - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="enterpriseApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-bookmark-x text-blue-600"></i> Gestión de Reclamos
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-blue-600">Reclamos</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden" x-data="claimManagement()">
            <div class="max-w-7xl mx-auto space-y-6">
                
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in flex items-center">
                        <i class="bi bi-check-circle-fill text-green-500 text-xl flex-shrink-0"></i>
                        <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                        <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.remove()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                {{-- Filters & Search --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('admin.claims.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="w-full md:w-1/2 relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por ID, nombres o correo..." 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors text-xs">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                            <select name="status" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-gray-700 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="leido" {{ request('status') === 'leido' ? 'selected' : '' }}>Leído</option>
                                <option value="respondido" {{ request('status') === 'respondido' ? 'selected' : '' }}>Respondido</option>
                                <option value="cerrado" {{ request('status') === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            </select>

                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm font-bold text-xs">
                                <i class="bi bi-filter"></i> Filtrar
                            </button>
                            @if(request()->has('search') || request()->has('status'))
                                <a href="{{ route('admin.claims.index') }}" class="border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 text-xs">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Table container --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                                    <th class="p-4 w-16">ID</th>
                                    <th class="p-4 w-28">DNI</th>
                                    <th class="p-4">Remitente</th>
                                    <th class="p-4">Asunto / Tipo</th>
                                    <th class="p-4">Fecha Registro</th>
                                    <th class="p-4 w-32">Estado</th>
                                    <th class="p-4 text-center w-24">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-xs">
                                @forelse($claims as $claim)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-slate-500">
                                            #{{ $claim->id }}
                                        </td>
                                        <td class="p-4 text-slate-600">
                                            {{ $claim->dni }}
                                        </td>
                                        <td class="p-4">
                                            <div class="font-bold text-slate-800">{{ $claim->names }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $claim->email }}</div>
                                        </td>
                                        <td class="p-4 text-slate-700 font-medium">
                                            {{ $claim->subject }}
                                        </td>
                                        <td class="p-4 text-slate-500">
                                            {{ $claim->created_at->format('d/m/Y h:i a') }}
                                        </td>
                                        <td class="p-4">
                                            @if($claim->status === 'pendiente')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Pendiente
                                                </span>
                                            @elseif($claim->status === 'leido')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-blue-500"></span> Leído
                                                </span>
                                            @elseif($claim->status === 'respondido')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Respondido
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-800">
                                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span> Cerrado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center">
                                            <button type="button" @click="openDetailModal({{ $claim->id }})" class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                                <i class="bi bi-eye-fill text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                                                <p>No se encontraron reclamos registrados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($claims->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $claims->links() }}
                    </div>
                    @endif
                </div>

                {{-- DETAIL & EDIT STATUS MODAL --}}
                <div x-show="showModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showModal = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                            
                            {{-- Modal Header --}}
                            <div class="bg-white px-6 py-4 border-b border-gray-150 flex items-center justify-between">
                                <h3 class="text-sm font-extrabold text-gray-800 flex items-center gap-2">
                                    <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span> Detalle de Reclamación #<span x-text="selectedClaim.id"></span>
                                </h3>
                                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="bi bi-x-lg text-sm"></i>
                                </button>
                            </div>

                            <form :action="'/admin-reclamos/estado/' + selectedClaim.id" method="POST">
                                @csrf
                                <div class="px-6 py-5 space-y-4 text-xs">
                                    
                                    {{-- Two columns info --}}
                                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <div>
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Nombres y Apellidos</span>
                                            <span class="font-bold text-slate-800" x-text="selectedClaim.names"></span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Documento (DNI/CE)</span>
                                            <span class="font-bold text-slate-800" x-text="selectedClaim.dni"></span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Correo Electrónico</span>
                                            <a :href="'mailto:' + selectedClaim.email" class="font-bold text-blue-600 hover:underline" x-text="selectedClaim.email"></a>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Fecha de Envío</span>
                                            <span class="font-bold text-slate-800" x-text="formatDate(selectedClaim.created_at)"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Asunto / Tipo de Incidencia</span>
                                        <span class="font-bold text-slate-800 text-sm" x-text="selectedClaim.subject"></span>
                                    </div>

                                    <div>
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Descripción del Reclamo / Queja</span>
                                        <p class="bg-slate-50/50 border border-slate-100 rounded-xl p-3.5 text-slate-700 leading-relaxed max-h-40 overflow-y-auto whitespace-pre-wrap" x-text="selectedClaim.message"></p>
                                    </div>

                                    {{-- Sustento File Attachment --}}
                                    <div x-show="selectedClaim.file_path">
                                        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1.5">Archivo Adjunto Sustentatorio</span>
                                        <a :href="'/storage/' + selectedClaim.file_path" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-bold transition-all bg-blue-50 border border-blue-100 px-3.5 py-2 rounded-xl">
                                            <i class="bi bi-file-earmark-arrow-down-fill text-base"></i> Descargar Documento Adjunto
                                        </a>
                                    </div>

                                    {{-- Edit Status --}}
                                    <div class="pt-2 border-t border-gray-150">
                                        <label for="status" class="block text-slate-700 font-bold mb-1.5">Actualizar Estado de la Solicitud</label>
                                        <select name="status" id="status" x-model="selectedClaim.status" required
                                            class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none">
                                            <option value="pendiente">Pendiente</option>
                                            <option value="leido">Leído</option>
                                            <option value="respondido">Respondido</option>
                                            <option value="cerrado">Cerrado</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex flex-col sm:flex-row-reverse gap-2">
                                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-xs font-bold text-white hover:bg-blue-700 focus:outline-none transition-all">
                                        <i class="bi bi-floppy-fill"></i> Guardar Cambios
                                    </button>
                                    <button type="button" @click="showModal = false" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition-all">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            if (!Alpine.data('enterpriseApp')) {
                Alpine.data('enterpriseApp', () => ({
                    sidebarOpen: window.innerWidth >= 1024,
                    toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
                }));
            }

            Alpine.data('claimManagement', () => ({
                showModal: false,
                selectedClaim: {
                    id: '',
                    names: '',
                    dni: '',
                    email: '',
                    subject: '',
                    message: '',
                    file_path: null,
                    status: 'pendiente',
                    created_at: ''
                },

                async openDetailModal(id) {
                    try {
                        const response = await fetch(`/admin-reclamos/${id}`);
                        if (response.ok) {
                            this.selectedClaim = await response.json();
                            this.showModal = true;
                        } else {
                            alert('No se pudo cargar la información del reclamo.');
                        }
                    } catch (e) {
                        alert('Ocurrió un error al cargar el reclamo.');
                    }
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    if (isNaN(d.getTime())) return dateStr;
                    return d.toLocaleDateString('es-PE', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }) + ' ' + d.toLocaleTimeString('es-PE', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }
            }));
        })
    </script>
@endpush
@endsection
