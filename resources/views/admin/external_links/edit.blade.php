@extends('layouts.app')
@section('title', 'Editar Enlace Institucional - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.links.index') }}" class="mr-3 text-gray-500 hover:text-purple-600 p-1 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-pencil-square text-purple-600"></i> Editar Enlace: {{ $link->name }}
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.links.index') }}" class="hover:text-purple-600">Enlaces Institucionales</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Enlace</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- General Validation Errors Alert --}}
                @if(isset($errors) && $errors->count() > 0)
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-bold text-rose-800">Se encontraron errores en el formulario:</h3>
                                <ul class="mt-1 text-xs text-rose-700 space-y-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <form action="{{ route('admin.links.update', $link) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nombre de la Plataforma --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Nombre de la Plataforma / Servicio <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $link->name) }}" 
                                    placeholder="Ej. Registra, Titula, Conecta, Avanza..."
                                    class="w-full text-base font-semibold px-4 py-3 border @error('name') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                                    required>
                                @error('name')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Enlace URL --}}
                            <div class="md:col-span-2">
                                <label for="link" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Dirección URL Destino <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"><i class="bi bi-globe"></i></span>
                                    <input type="url" name="link" id="link" value="{{ old('link', $link->link) }}" 
                                        placeholder="https://registra.minedu.gob.pe/"
                                        class="w-full text-sm font-mono pl-10 pr-4 py-3 border @error('link') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                                        required>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-1">Ingrese la URL completa incluyendo <code>https://</code></p>
                                @error('link')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Icono Bootstrap --}}
                            <div class="md:col-span-2 space-y-3" x-data="{ selectedIcon: '{{ old('icon', $link->icon ?? 'bi-box-arrow-up-right') }}' }">
                                <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Icono Representativo (Clase Bootstrap Icons)
                                </label>

                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-purple-100 border border-purple-300 text-purple-700 flex items-center justify-center text-2xl shadow-sm shrink-0">
                                        <i class="bi" :class="selectedIcon || 'bi-box-arrow-up-right'"></i>
                                    </div>
                                    <input type="text" name="icon" id="icon" x-model="selectedIcon" 
                                        placeholder="bi-pencil-square"
                                        class="w-full text-sm font-mono px-4 py-2.5 border @error('icon') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Icon Selector Helper --}}
                                <div>
                                    <p class="text-[11px] text-gray-500 mb-2 font-medium">Selección rápida de iconos sugeridos:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $suggestedIcons = [
                                                'bi-pencil-square'    => 'Registra (Matrícula)',
                                                'bi-award'            => 'Titula (Títulos)',
                                                'bi-people'           => 'Conecta (Comunidad)',
                                                'bi-graph-up-arrow'   => 'Avanza (Trayectoria)',
                                                'bi-box-arrow-up-right' => 'Enlace Externo',
                                                'bi-globe'            => 'Portal Web',
                                                'bi-book'             => 'Biblioteca/Lectura',
                                                'bi-building'         => 'Institucional',
                                                'bi-shield-check'     => 'Verificado'
                                            ];
                                        @endphp
                                        @foreach($suggestedIcons as $iconClass => $label)
                                            <button type="button" @click="selectedIcon = '{{ $iconClass }}'" 
                                                :class="selectedIcon === '{{ $iconClass }}' ? 'bg-purple-600 text-white border-purple-600' : 'bg-gray-100 hover:bg-purple-50 text-gray-700 border-gray-200'"
                                                class="px-2.5 py-1.5 rounded-lg border text-xs font-semibold flex items-center gap-1.5 transition-all">
                                                <i class="bi {{ $iconClass }}"></i> {{ $label }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                @error('icon')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $link->is_active) ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 rounded-md border-gray-300 focus:ring-purple-500">
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">Enlace Activo y Publicado</span>
                                        <p class="text-xs text-gray-500">Si está activo, el enlace se mostrará públicamente en la sección de Enlaces Institucionales.</p>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.links.index') }}" 
                               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all flex items-center gap-2">
                                <i class="bi bi-save"></i> Actualizar Enlace
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
