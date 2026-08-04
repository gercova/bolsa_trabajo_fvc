@extends('layouts.app')
@section('title', 'Registrar Categoría TUPA - Panel Administrativo')
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
                        Registrar Nueva Categoría TUPA
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.tupa.index', ['tab' => 'categories']) }}" class="hover:text-purple-600">Categorías TUPA</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Nueva Categoría</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-3xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.tupa.index', ['tab' => 'categories']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8" x-data="{ selectedIcon: '{{ old('icon', 'bi-journal-check') }}' }">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-folder-plus text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Nueva Categoría de Procedimientos TUPA</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Define los grupos principales para clasificar los trámites y conceptos administrativos.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.tupa.categories.store') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-6">
                            {{-- Nombre --}}
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Nombre de la Categoría <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-tag absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Trámites Académicos y Certificación" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ícono Bootstrap Icons --}}
                            <div class="space-y-1.5">
                                <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Ícono Bootstrap Icons <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl flex-shrink-0 border border-purple-200">
                                        <i :class="'bi ' + selectedIcon"></i>
                                    </div>
                                    <div class="relative flex-1">
                                        <i class="bi bi-app-indicator absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                        <input type="text" id="icon" name="icon" x-model="selectedIcon" placeholder="Ej: bi-journal-check, bi-person-badge, bi-clipboard-check" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('icon') border-red-500 @enderror">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400">Ingresa la clase de ícono (ej. <code>bi-journal-check</code>, <code>bi-person-badge</code>, <code>bi-clipboard-check</code>, <code>bi-card-heading</code>).</p>
                                @error('icon')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="pt-2">
                                <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Categoría Activa</span>
                                        <span class="text-xs text-gray-500 block">Las categorías activas se muestran en el portal público de trámites TUPA.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.tupa.index', ['tab' => 'categories']) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Guardar Categoría</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </main>
    </div>
</div>
@endsection
