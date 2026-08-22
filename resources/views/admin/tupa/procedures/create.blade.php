@extends('layouts.app')
@section('title', 'Registrar Procedimiento TUPA - Panel Administrativo')
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
                        Registrar Nuevo Procedimiento TUPA
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.tupa.index', ['tab' => 'procedures']) }}" class="hover:text-purple-600">Procedimientos</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Nuevo Procedimiento</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.tupa.index', ['tab' => 'procedures']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left text-lg"></i>
                        <span>Volver al listado</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
                    
                    {{-- Banner --}}
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-earmark-code-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Nuevo Procedimiento / Trámite Administrativo</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Ingresa la información detallada del trámite, requisitos, derecho de pago y plazos de atención.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.tupa.procedures.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Documento TUPA --}}
                            <div class="space-y-1.5">
                                <label for="tupa_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Documento TUPA Vigente <span class="text-red-500">*</span>
                                </label>
                                <select id="tupa_id" name="tupa_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('tupa_id') border-red-500 @enderror">
                                    <option value="">Seleccione el Documento TUPA</option>
                                    @foreach($tupas as $tupa)
                                        <option value="{{ $tupa->id }}" {{ old('tupa_id') == $tupa->id ? 'selected' : '' }}>
                                            {{ $tupa->title }} ({{ $tupa->year ?? 'General' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tupa_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Categoría TUPA --}}
                            <div class="space-y-1.5">
                                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Categoría <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id" name="category_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('category_id') border-red-500 @enderror">
                                    <option value="">Seleccione una Categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Código --}}
                            <div class="space-y-1.5">
                                <label for="code" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Código del Trámite <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-hash absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="code" name="code" value="{{ old('code') }}" placeholder="Ej: P-01, P-02, P-15" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('code') border-red-500 @enderror">
                                </div>
                                @error('code')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nombre del Procedimiento --}}
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Nombre del Procedimiento <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-card-text absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Certificado de Estudios (Por Semestre / Módulo)" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Descripción del Trámite <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="3" placeholder="Detalle el objetivo, alcance o finalidad del trámite..." required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Requisitos --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="requirements" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Requisitos Exigidos <span class="text-red-500">*</span>
                                </label>
                                <textarea id="requirements" name="requirements" rows="4" placeholder="Ingrese cada requisito en una línea nueva. Ejemplo:&#10;FUT dirigido al Director General.&#10;Comprobante de pago por derecho de trámite.&#10;Fotos tamaño carné en fondo blanco." required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('requirements') border-red-500 @enderror">{{ is_array(old('requirements')) ? implode("\n", old('requirements')) : old('requirements') }}</textarea>
                                <p class="text-xs text-gray-400">Escriba cada requisito en una línea separada (el sistema lo formateará como lista).</p>
                                @error('requirements')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Costo (S/.) --}}
                            <div class="space-y-1.5">
                                <label for="cost" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Derecho de Pago / Costo <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-cash-stack absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="cost" name="cost" value="{{ old('cost', 'S/. 0.00') }}" placeholder="Ej: S/. 35.00 o Gratuito" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('cost') border-red-500 @enderror">
                                </div>
                                @error('cost')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Porcentaje UIT --}}
                            <div class="space-y-1.5">
                                <label for="uit_percent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Porcentaje de la UIT
                                </label>
                                <div class="relative">
                                    <i class="bi bi-percent absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="uit_percent" name="uit_percent" value="{{ old('uit_percent', '0.00%') }}" placeholder="Ej: 0.65%" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('uit_percent') border-red-500 @enderror">
                                </div>
                                @error('uit_percent')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Calificación --}}
                            <div class="space-y-1.5">
                                <label for="qualification" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Calificación <span class="text-red-500">*</span>
                                </label>
                                <select id="qualification" name="qualification" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('qualification') border-red-500 @enderror">
                                    <option value="Evaluación Previa (Positivo)" {{ old('qualification') == 'Evaluación Previa (Positivo)' ? 'selected' : '' }}>Evaluación Previa (Positivo)</option>
                                    <option value="Evaluación Previa (Negativo)" {{ old('qualification') == 'Evaluación Previa (Negativo)' ? 'selected' : '' }}>Evaluación Previa (Negativo)</option>
                                    <option value="Aprobación Automática" {{ old('qualification') == 'Aprobación Automática' ? 'selected' : '' }}>Aprobación Automática</option>
                                </select>
                                @error('qualification')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Plazo de Atención --}}
                            <div class="space-y-1.5">
                                <label for="duration" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Plazo de Atención <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-clock-history absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="duration" name="duration" value="{{ old('duration') }}" placeholder="Ej: 5 días hábiles, 1 día hábil, Inmediata" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('duration') border-red-500 @enderror">
                                </div>
                                @error('duration')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Oficina / Dependencia --}}
                            <div class="md:col-span-2 space-y-1.5">
                                <label for="office" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Oficina o Dependencia que Atiende <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="bi bi-building absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" id="office" name="office" value="{{ old('office') }}" placeholder="Ej: Secretaría Académica, Unidad Académica, Dirección General" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('office') border-red-500 @enderror">
                                </div>
                                @error('office')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Estado Activo --}}
                            <div class="md:col-span-2 pt-2">
                                <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Procedimiento Activo</span>
                                        <span class="text-xs text-gray-500 block">Los procedimientos activos se muestran públicamente en el catálogo TUPA.</span>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Botones de Acción --}}
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.tupa.index', ['tab' => 'procedures']) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Guardar Procedimiento</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </main>
    </div>
</div>
@endsection
