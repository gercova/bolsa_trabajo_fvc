@extends('layouts.app')
@section('title', 'Crear Modalidad de Beca - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="{ sidebarOpen: true }">
    @include('admin.components.aside')

    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">  
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.scholarships.index') }}" class="mr-3 text-gray-500 hover:text-purple-600 p-1 rounded-lg transition-colors">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                        <i class="bi bi-award text-purple-600"></i> Nueva Modalidad de Beca
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.scholarships.index') }}" class="hover:text-purple-600">Becas y Créditos</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Crear Beca</span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- General Validation Errors Alert --}}
                @if(isset($errors) && $errors->any())
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
                    <form action="{{ route('admin.scholarships.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nombre --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Nombre de la Beca o Modalidad <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                    placeholder="Ej. PRIMEROS PUESTOS, DEPORTISTAS CALIFICADOS, etc."
                                    oninput="generateSlug(this.value)"
                                    class="w-full text-base font-semibold px-4 py-3 border @error('name') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                                    required>
                                @error('name')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div>
                                <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Enlace Slug <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-mono text-xs">/</span>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                                        placeholder="primeros-puestos"
                                        class="w-full text-sm font-mono pl-7 pr-4 py-2.5 border @error('slug') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-gray-50"
                                        required>
                                </div>
                                @error('slug')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Orden de Clasificación --}}
                            <div>
                                <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Orden de Posición
                                </label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 1) }}" min="0"
                                    class="w-full text-sm font-semibold px-4 py-2.5 border @error('sort_order') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <p class="text-[11px] text-gray-500 mt-1">Número entero para determinar la secuencia de aparición (1, 2, 3...)</p>
                                @error('sort_order')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Icono Bootstrap --}}
                            <div class="md:col-span-2 space-y-3" x-data="{ selectedIcon: '{{ old('icon', 'bi-award') }}' }">
                                <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Icono Representativo (Clase Bootstrap Icons)
                                </label>

                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-purple-100 border border-purple-300 text-purple-700 flex items-center justify-center text-2xl shadow-sm shrink-0">
                                        <i class="bi" :class="selectedIcon || 'bi-award'"></i>
                                    </div>
                                    <input type="text" name="icon" id="icon" x-model="selectedIcon" 
                                        placeholder="bi-trophy"
                                        class="w-full text-sm font-mono px-4 py-2.5 border @error('icon') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Icon Selector Helper --}}
                                <div>
                                    <p class="text-[11px] text-gray-500 mb-2 font-medium">Selección rápida de iconos sugeridos:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $suggestedIcons = [
                                                'bi-trophy' => 'Primeros Puestos',
                                                'bi-person-arms-up' => 'Deportistas',
                                                'bi-heart' => 'Víctimas',
                                                'bi-book' => 'Pre-Instituto',
                                                'bi-person-wheelchair' => 'Discapacidad',
                                                'bi-mortarboard' => 'Titulados',
                                                'bi-shield-fill' => 'FF.AA.',
                                                'bi-award' => 'General',
                                                'bi-star-fill' => 'Destacados',
                                                'bi-check-circle-fill' => 'Validado'
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
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 rounded-md border-gray-300 focus:ring-purple-500">
                                    <div>
                                        <span class="text-sm font-bold text-gray-800">Modalidad Activa y Publicada</span>
                                        <p class="text-xs text-gray-500">Si está activa, la modalidad estará visible públicamente en el portal web.</p>
                                    </div>
                                </label>
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                    Descripción Completa y Requisitos
                                </label>
                                <textarea name="description" id="description" rows="5" 
                                    placeholder="Ingrese los detalles de la beca, público objetivo, alcance de los beneficios y requisitos necesarios..."
                                    class="w-full text-sm p-4 border @error('description') border-rose-500 @else border-gray-300 @enderror rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all leading-relaxed">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.scholarships.index') }}" 
                               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all flex items-center gap-2">
                                <i class="bi bi-save"></i> Guardar Beca
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    function generateSlug(text) {
        const slugInput = document.getElementById('slug');
        if (slugInput) {
            slugInput.value = text
                .toLowerCase()
                .trim()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    }
</script>
@endsection
