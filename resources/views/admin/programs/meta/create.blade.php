@extends('layouts.app')
@section('title', 'Crear Metadatos de Presentación - Panel Administrativo')
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
                        Registrar Metadatos de Presentación
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.programs.index', ['tab' => 'meta']) }}" class="hover:text-purple-600">Metadatos</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Nuevos Metadatos</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-3xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.programs.index', ['tab' => 'meta']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8" x-data="{ selectedIcon: '{{ old('icon', 'bi-mortarboard-fill') }}' }">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-palette-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Metadatos de Estilo y Presentación</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Define los colores de acento, insignias e íconos para la interfaz web pública del programa.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.programs.meta.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Programa de Estudio --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="study_program_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Programa de Estudio <span class="text-red-500">*</span>
                                </label>
                                <select id="study_program_id" name="study_program_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('study_program_id') border-red-500 @enderror">
                                    <option value="">Seleccione el Programa de Estudio</option>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" {{ old('study_program_id') == $prog->id ? 'selected' : '' }}>
                                            {{ $prog->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('study_program_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ícono --}}
                            <div class="space-y-1.5">
                                <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Ícono Principal
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                        <i :class="'bi ' + selectedIcon"></i>
                                    </div>
                                    <input type="text" id="icon" name="icon" x-model="selectedIcon" value="{{ old('icon', 'bi-mortarboard-fill') }}" placeholder="Ej: bi-tree-fill, bi-heart-pulse-fill" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            {{-- Color Acento --}}
                            <div class="space-y-1.5">
                                <label for="accent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Color Acento (Tailwind)
                                </label>
                                <input type="text" id="accent" name="accent" value="{{ old('accent', 'blue') }}" placeholder="Ej: emerald, rose, sky, blue, teal" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Tag --}}
                            <div class="space-y-1.5">
                                <label for="tag" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Etiqueta Destacada (Tag)
                                </label>
                                <input type="text" id="tag" name="tag" value="{{ old('tag') }}" placeholder="Ej: Ciencias de la Salud, Producción & Campo" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Color de Barra --}}
                            <div class="space-y-1.5">
                                <label for="color_bar" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Clase Color de Barra
                                </label>
                                <input type="text" id="color_bar" name="color_bar" value="{{ old('color_bar') }}" placeholder="Ej: bg-emerald-500, bg-rose-500" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Badge --}}
                            <div class="space-y-1.5">
                                <label for="bg_badge" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Clase Badge
                                </label>
                                <input type="text" id="bg_badge" name="bg_badge" value="{{ old('bg_badge') }}" placeholder="Ej: bg-emerald-50 text-emerald-800 border-emerald-100" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Glow Class --}}
                            <div class="space-y-1.5">
                                <label for="glow_class" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Glow Class
                                </label>
                                <input type="text" id="glow_class" name="glow_class" value="{{ old('glow_class') }}" placeholder="Ej: bg-emerald-500/20" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- CTA BG Class --}}
                            <div class="space-y-1.5">
                                <label for="cta_bg_class" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    CTA Background Class
                                </label>
                                <input type="text" id="cta_bg_class" name="cta_bg_class" value="{{ old('cta_bg_class') }}" placeholder="Ej: from-emerald-600 to-teal-800" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Bullet Class --}}
                            <div class="space-y-1.5">
                                <label for="bullet_class" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Bullet Class
                                </label>
                                <input type="text" id="bullet_class" name="bullet_class" value="{{ old('bullet_class') }}" placeholder="Ej: bg-emerald-600" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                        </div>

                        {{-- Botones de Acción --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.programs.index', ['tab' => 'meta']) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Guardar Metadatos</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </main>
    </div>
</div>
@endsection
