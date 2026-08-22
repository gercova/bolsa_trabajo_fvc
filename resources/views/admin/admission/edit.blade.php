@extends('layouts.app')
@section('title', 'Actualizar Examen - Panel Administrativo')
@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="enterpriseApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="toggleSidebar()"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                            <i class="bi bi-journal-plus text-purple-600 mr-3"></i>
                            Actualizar Examen / CEPRE
                        </h1>
                    </div>
                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <a href="{{ route('admin.exams.index') }}" class="hover:text-purple-600 transition">Exámenes</a>
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Actualizar examen</span>
                    </div>
                </div>
            </header>

            {{-- Formulario --}}
            <div class="p-4 sm:p-6 lg:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-150">
                        <form action="{{ route('admin.exams.update', $admission->id) }}" method="POST"
                            enctype="multipart/form-data" class="p-6 md:p-8">
                            @csrf
                            @method('PUT')
                            {{-- Grid de 3 columnas --}}
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                {{-- Columna Izquierda: PDF y Estado --}}
                                <div class="lg:col-span-1 space-y-6">
                                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                                        <h3
                                            class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                            <i class="bi bi-gear-fill text-purple-600"></i>
                                            Configuración General
                                        </h3>

                                        {{-- PDF Upload --}}
                                        <div class="mb-6">
                                            <label for="url_pdf"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-file-earmark-pdf-fill text-red-500 mr-1"></i>
                                                Documento de Bases (PDF)
                                            </label>
                                            <input type="file" name="url_pdf" id="url_pdf" accept="application/pdf"
                                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border file:border-gray-300 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition cursor-pointer">
                                            <p class="text-[10px] text-gray-500 mt-2">
                                                <i class="bi bi-info-circle"></i>
                                                Formato admitido: PDF. Máx: 10MB
                                            </p>
                                            @error('url_pdf')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Estado del examen --}}
                                        <div class="border-t border-gray-200 pt-4">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $admission->is_active) ? 'checked' : '' }}
                                                    class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                                <span class="ml-3 text-sm font-semibold text-gray-700">
                                                    Examen Activo
                                                </span>
                                            </label>
                                            <p class="text-[10px] text-gray-500 mt-2 ml-8">Los exámenes inactivos no se
                                                listarán en la página pública para los postulantes.</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Columna Derecha: Datos del Examen --}}
                                <div class="lg:col-span-2">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Periodo --}}
                                        <div>
                                            <label for="period"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-calendar3 text-purple-600 mr-1"></i>
                                                Período Académico
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-tag text-gray-400"></i>
                                                </div>
                                                <input type="text" name="period" id="period"
                                                    value="{{ old('period', $admission->period) }}" placeholder="Ej: 2026-I" required
                                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('period') border-red-500 @enderror">
                                            </div>
                                            @error('period')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Total Vacantes --}}
                                        <div>
                                            <label for="total_vacancies"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-people-fill text-purple-600 mr-1"></i>
                                                Total Vacantes
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-hash text-gray-400"></i>
                                                </div>
                                                <input type="number" name="total_vacancies" id="total_vacancies"
                                                    value="{{ old('total_vacancies', $admission->total_vacancies) }}" placeholder="Ej: 150"
                                                    min="0" required
                                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('total_vacancies') border-red-500 @enderror">
                                            </div>
                                            @error('total_vacancies')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Precio --}}
                                        <div>
                                            <label for="price"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-cash-coin text-purple-600 mr-1"></i>
                                                Costo de Inscripción (S/)
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-400 text-sm">S/</span>
                                                </div>
                                                <input type="number" step="0.01" name="price" id="price"
                                                    value="{{ old('price', $admission->price) }}" placeholder="Ej: 150.00" min="0"
                                                    required
                                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('price') border-red-500 @enderror">
                                            </div>
                                            @error('price')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Tipo --}}
                                        <div>
                                            <label for="type"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-award-fill text-purple-600 mr-1"></i>
                                                Tipo de Examen
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-gear-wide text-gray-400"></i>
                                                </div>
                                                <select name="type" id="type" required
                                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition appearance-none text-sm @error('type') border-red-500 @enderror">
                                                    <option value="ordinario"
                                                        {{ old('type', $admission->type) == 'ordinario' ? 'selected' : '' }}>Ordinario
                                                    </option>
                                                    <option value="extraordinario"
                                                        {{ old('type', $admission->type) == 'extraordinario' ? 'selected' : '' }}>
                                                        Extraordinario</option>
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-chevron-down text-gray-400"></i>
                                                </div>
                                            </div>
                                            @error('type')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Proceso --}}
                                        <div>
                                            <label for="process"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-journal-bookmark-fill text-purple-600 mr-1"></i>
                                                Proceso
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-bookmark text-gray-400"></i>
                                                </div>
                                                <select name="process" id="process" required
                                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition appearance-none text-sm @error('process') border-red-500 @enderror">
                                                    <option value="cepre"
                                                        {{ old('process', $admission->process) == 'cepre' ? 'selected' : '' }}>CEPRE</option>
                                                    <option value="admisión"
                                                        {{ old('process', $admission->process) == 'admisión' ? 'selected' : '' }}>Admisión</option>
                                                    <option value="matrícula"
                                                        {{ old('process', $admission->process) == 'matrícula' ? 'selected' : '' }}>Matrícula</option>
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-chevron-down text-gray-400"></i>
                                                </div>
                                            </div>
                                            @error('process')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Área Académica --}}
                                        <div>
                                             <label for="area_id" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                 <i class="bi bi-building text-purple-600 mr-1"></i>
                                                 Área Académica Responsable
                                             </label>
                                             <div class="relative">
                                                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                     <i class="bi bi-house text-gray-400"></i>
                                                 </div>
                                                 <select name="area_id" id="area_id"
                                                     class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition appearance-none text-sm @error('area_id') border-red-500 @enderror">
                                                     <option value="">-- Seleccionar Área (Opcional) --</option>
                                                     @foreach($areas as $area)
                                                         <option value="{{ $area->id }}" {{ old('area_id', $admission->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                                     @endforeach
                                                 </select>
                                                 <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                     <i class="bi bi-chevron-down text-gray-400"></i>
                                                 </div>
                                             </div>
                                             @error('area_id')
                                                 <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                             @enderror
                                        </div>

                                        {{-- Fecha Examen --}}
                                        <div>
                                            <label for="exam_date"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-calendar-event text-purple-600 mr-1"></i>
                                                Fecha de Examen
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-calendar text-gray-400"></i>
                                                </div>
                                                <input type="date" name="exam_date" id="exam_date"
                                                    value="{{ old('exam_date', $admission->exam_date) }}" required
                                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('exam_date') border-red-500 @enderror">
                                            </div>
                                            @error('exam_date')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Fecha Inicio Inscripción --}}
                                        <div>
                                            <label for="inscription_start_date"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-calendar-check text-purple-600 mr-1"></i>
                                                Inicio de Inscripciones
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-calendar-plus text-gray-400"></i>
                                                </div>
                                                <input type="date" name="inscription_start_date"
                                                    id="inscription_start_date"
                                                    value="{{ old('inscription_start_date', $admission->inscription_start_date) }}" required
                                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('inscription_start_date') border-red-500 @enderror">
                                            </div>
                                            @error('inscription_start_date')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Fecha Fin Inscripción --}}
                                        <div>
                                            <label for="inscription_end_date"
                                                class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-calendar-x text-purple-600 mr-1"></i>
                                                Fin de Inscripciones
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-calendar-minus text-gray-400"></i>
                                                </div>
                                                <input type="date" name="inscription_end_date" id="inscription_end_date" value="{{ old('inscription_end_date', $admission->inscription_end_date) }}" required class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('inscription_end_date') border-red-500 @enderror">
                                            </div>
                                            @error('inscription_end_date')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Actividad / Nombre del Proceso --}}
                                        <div class="md:col-span-2">
                                            <label for="activity" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <i class="bi bi-info-circle-fill text-purple-600 mr-1"></i>
                                                Actividad / Nombre del Proceso
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="bi bi-tag-fill text-gray-400"></i>
                                                </div>
                                                <input type="text" name="activity" id="activity" value="{{ old('activity', $admission->activity) }}" placeholder="Ej: Matrícula Regular 2026-I, Examen de Admisión General..." required
                                                     class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm @error('activity') border-red-500 @enderror">
                                            </div>
                                            @error('activity')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Vacantes por Programa de Estudio --}}
                                        <div class="md:col-span-2 mt-6">
                                            <h3
                                                class="text-sm font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2 uppercase tracking-wider">
                                                <i class="bi bi-book text-purple-600"></i>
                                                Vacantes por Programa de Estudio
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach ($programs as $index => $program)
                                                    <div
                                                        class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center justify-between shadow-sm">
                                                        <span
                                                            class="text-xs font-semibold text-gray-700">{{ $program->name }}</span>
                                                        <div class="w-32">
                                                            <input type="hidden"
                                                                name="programs[{{ $index }}][program_id]"
                                                                value="{{ $program->id }}">
                                                            <input type="number"
                                                                name="programs[{{ $index }}][vacancies]"
                                                                min="0"
                                                                value="{{ old("programs.{$index}.vacancies", $program->vacancies) }}"
                                                                class="w-full text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent py-2 px-3 text-right"
                                                                placeholder="0">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                    <a href="{{ route('admin.exams.index') }}"
                                        class="px-6 py-3 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                                        <i class="bi bi-x-circle mr-2"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit"
                                        class="px-6 py-3 text-sm font-bold bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-md hover:shadow-lg">
                                        <i class="bi bi-save-fill mr-2"></i>
                                        Guardar Evento
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                // Inicializador del layout base del dashboard
                if (!Alpine.data('enterpriseApp')) {
                    Alpine.data('enterpriseApp', () => ({
                        sidebarOpen: window.innerWidth >= 1024,
                        toggleSidebar() {
                            this.sidebarOpen = !this.sidebarOpen;
                        },
                        init() {
                            window.addEventListener('resize', () => {
                                this.sidebarOpen = window.innerWidth >= 1024;
                            });
                        }
                    }));
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            input:focus,
            select:focus,
            textarea:focus {
                outline: none;
            }
        </style>
    @endpush
@endsection
