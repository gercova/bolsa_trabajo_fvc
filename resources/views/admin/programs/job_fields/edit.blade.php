@extends('layouts.app')
@section('title', 'Editar Campo Laboral - Panel Administrativo')
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
                        Editar Campo Laboral
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.programs.index', ['tab' => 'job_fields']) }}" class="hover:text-purple-600">Campo Laboral</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Editar Campo Laboral</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-3xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.programs.index', ['tab' => 'job_fields']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Modificar Campo Laboral</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Actualiza la descripción, programa asignado u orden de presentación.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.programs.job-fields.update', $jobField) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            {{-- Programa de Estudio --}}
                            <div class="space-y-1.5">
                                <label for="study_program_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Programa de Estudio <span class="text-red-500">*</span>
                                </label>
                                <select id="study_program_id" name="study_program_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('study_program_id') border-red-500 @enderror">
                                    <option value="">Seleccione el Programa de Estudio</option>
                                    @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" {{ old('study_program_id', $jobField->study_program_id) == $prog->id ? 'selected' : '' }}>
                                            {{ $prog->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('study_program_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Descripción del Campo Laboral <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="3" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description', $jobField->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Orden --}}
                            <div class="space-y-1.5">
                                <label for="order" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Orden de Visualización
                                </label>
                                <input type="number" id="order" name="order" value="{{ old('order', $jobField->order) }}" min="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>

                            {{-- Estado Activo --}}
                            <div class="pt-2">
                                <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $jobField->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Campo Laboral Activo</span>
                                        <span class="text-xs text-gray-500 block">Los registros activos se muestran en la sección de inserción laboral del programa.</span>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Botones de Acción --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.programs.index', ['tab' => 'job_fields']) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Actualizar Campo Laboral</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </main>
    </div>
</div>
@endsection
