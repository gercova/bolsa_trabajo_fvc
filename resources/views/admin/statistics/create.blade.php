@extends('layouts.app')
@section('title', 'Nuevo Registro Académico - Panel Administrativo')

@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
        x-data="dashboardApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight flex items-center gap-2">
                            <i class="bi bi-plus-circle text-purple-600"></i> Nuevo Registro Académico
                        </h1>
                    </div>

                    <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                        <i class="bi bi-shield-check mr-1 text-purple-500"></i> Transparencia
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <a href="{{ route('admin.statistics.index') }}" class="hover:text-purple-600">Estadísticas</a>
                        <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                        <span class="text-purple-600">Crear</span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <div class="max-w-5xl mx-auto space-y-6">

                    {{-- Validation Errors --}}
                    @if (isset($errors) && $errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="bi bi-exclamation-octagon-fill text-red-600 text-lg"></i>
                                <h3 class="text-sm font-bold text-red-800">Por favor corrige los siguientes errores:</h3>
                            </div>
                            <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.statistics.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- ═══ BLOQUE 1: PROCESO ACADÉMICO ═══════════════════════ --}}
                        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-mortarboard-fill text-purple-600"></i> 1. Datos del Proceso Académico
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Define si corresponde al proceso de Admisión o al padrón de Matrícula.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                {{-- Record Type --}}
                                <div>
                                    <label for="record_type" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Tipo de Registro <span class="text-red-500">*</span>
                                    </label>
                                    <select name="record_type" id="record_type" required
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold">
                                        <option value="ADMISION" {{ old('record_type') === 'ADMISION' ? 'selected' : '' }}>Admisión (Postulante / Ingresante)</option>
                                        <option value="MATRICULA" {{ old('record_type') === 'MATRICULA' ? 'selected' : '' }}>Matrícula (Estudiante Regular)</option>
                                    </select>
                                </div>

                                {{-- Academic Period --}}
                                <div>
                                    <label for="academic_period" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Período Académico <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="academic_period" id="academic_period" required
                                        value="{{ old('academic_period', '2026-I') }}" placeholder="ej. 2026-I, 2026-II"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold text-purple-700">
                                </div>

                                {{-- Study Program --}}
                                <div>
                                    <label for="study_program" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Programa de Estudios <span class="text-red-500">*</span>
                                    </label>
                                    <select name="study_program" id="study_program" required
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                        <option value="">Seleccione Programa...</option>
                                        @foreach ($studyPrograms as $prog)
                                            <option value="{{ $prog->name }}" {{ old('study_program') === $prog->name ? 'selected' : '' }}>{{ $prog->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Modality --}}
                                <div>
                                    <label for="modality" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Modalidad
                                    </label>
                                    <input type="text" name="modality" id="modality"
                                        value="{{ old('modality', 'ORDINARIO') }}" placeholder="ej. ORDINARIO, EXTRAORDINARIO, CEPRE"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Shift --}}
                                <div>
                                    <label for="shift" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Turno
                                    </label>
                                    <select name="shift" id="shift"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                        <option value="DIURNO" {{ old('shift') === 'DIURNO' ? 'selected' : '' }}>DIURNO</option>
                                        <option value="MAÑANA" {{ old('shift') === 'MAÑANA' ? 'selected' : '' }}>MAÑANA</option>
                                        <option value="NOCHE" {{ old('shift') === 'NOCHE' ? 'selected' : '' }}>NOCHE</option>
                                    </select>
                                </div>

                                {{-- Score --}}
                                <div>
                                    <label for="score" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Puntaje Obtenido
                                    </label>
                                    <input type="number" step="0.01" min="0" max="100" name="score" id="score"
                                        value="{{ old('score') }}" placeholder="ej. 16.50"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Situation --}}
                                <div>
                                    <label for="situation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Situación
                                    </label>
                                    <input type="text" name="situation" id="situation"
                                        value="{{ old('situation', 'INGRESÓ') }}" placeholder="ej. INGRESÓ, NO INGRESÓ, MATRICULADO"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Cycle (Solo Matrícula) --}}
                                <div>
                                    <label for="cycle" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Ciclo (Matrícula)
                                    </label>
                                    <select name="cycle" id="cycle"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                        <option value="">Seleccione ciclo...</option>
                                        <option value="I" {{ old('cycle') === 'I' ? 'selected' : '' }}>Ciclo I</option>
                                        <option value="II" {{ old('cycle') === 'II' ? 'selected' : '' }}>Ciclo II</option>
                                        <option value="III" {{ old('cycle') === 'III' ? 'selected' : '' }}>Ciclo III</option>
                                        <option value="IV" {{ old('cycle') === 'IV' ? 'selected' : '' }}>Ciclo IV</option>
                                        <option value="V" {{ old('cycle') === 'V' ? 'selected' : '' }}>Ciclo V</option>
                                        <option value="VI" {{ old('cycle') === 'VI' ? 'selected' : '' }}>Ciclo VI</option>
                                    </select>
                                </div>

                                {{-- Headquarters --}}
                                <div>
                                    <label for="headquarters" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Sede
                                    </label>
                                    <input type="text" name="headquarters" id="headquarters"
                                        value="{{ old('headquarters', 'SEDE PRINCIPAL - UCHIZA') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- ═══ BLOQUE 2: DATOS PERSONALES ════════════════════════ --}}
                        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-person-vcard text-purple-600"></i> 2. Datos Personales del Estudiante
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Información de identidad y contacto del postulante o estudiante.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                {{-- Document Type --}}
                                <div>
                                    <label for="document_type_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Tipo de Documento
                                    </label>
                                    <select name="document_type_id" id="document_type_id"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                        @foreach ($documentTypes as $dt)
                                            <option value="{{ $dt->id }}" {{ old('document_type_id', 1) == $dt->id ? 'selected' : '' }}>
                                                {{ $dt->abreviation }} - {{ $dt->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Document Number --}}
                                <div>
                                    <label for="document" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Número de Documento
                                    </label>
                                    <input type="text" name="document" id="document" maxlength="20"
                                        value="{{ old('document') }}" placeholder="ej. 74859632"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono">
                                </div>

                                {{-- Names --}}
                                <div>
                                    <label for="names" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Nombres <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="names" id="names" required maxlength="150"
                                        value="{{ old('names') }}" placeholder="ej. Juan Carlos"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold">
                                </div>

                                {{-- Father Last Name --}}
                                <div>
                                    <label for="last_name_father" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Apellido Paterno
                                    </label>
                                    <input type="text" name="last_name_father" id="last_name_father" maxlength="100"
                                        value="{{ old('last_name_father') }}" placeholder="ej. Pérez"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold">
                                </div>

                                {{-- Mother Last Name --}}
                                <div>
                                    <label for="last_name_mother" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Apellido Materno
                                    </label>
                                    <input type="text" name="last_name_mother" id="last_name_mother" maxlength="100"
                                        value="{{ old('last_name_mother') }}" placeholder="ej. Gómez"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold">
                                </div>

                                {{-- Gender --}}
                                <div>
                                    <label for="gender" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Género
                                    </label>
                                    <select name="gender" id="gender"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                        <option value="">Seleccione género...</option>
                                        <option value="MASCULINO" {{ old('gender') === 'MASCULINO' ? 'selected' : '' }}>MASCULINO</option>
                                        <option value="FEMENINO" {{ old('gender') === 'FEMENINO' ? 'selected' : '' }}>FEMENINO</option>
                                    </select>
                                </div>

                                {{-- Birthdate --}}
                                <div>
                                    <label for="birthdate" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Fecha de Nacimiento
                                    </label>
                                    <input type="date" name="birthdate" id="birthdate"
                                        value="{{ old('birthdate') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Mother Tongue --}}
                                <div>
                                    <label for="mother_tongue" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Lengua Materna
                                    </label>
                                    <input type="text" name="mother_tongue" id="mother_tongue"
                                        value="{{ old('mother_tongue', 'Español') }}" placeholder="ej. Español, Quechua"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Teléfono / Celular
                                    </label>
                                    <input type="text" name="phone" id="phone" maxlength="20"
                                        value="{{ old('phone') }}" placeholder="ej. 987654321"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono">
                                </div>

                                {{-- Email --}}
                                <div class="sm:col-span-2">
                                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" name="email" id="email" maxlength="150"
                                        value="{{ old('email') }}" placeholder="ej. estudiante@iestp.edu.pe"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- País --}}
                                <div>
                                    <label for="pais_procedencia" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        País de Procedencia
                                    </label>
                                    <input type="text" name="pais_procedencia" id="pais_procedencia"
                                        value="{{ old('pais_procedencia', 'PERÚ') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- ═══ BLOQUE 3: COLEGIO DE PROCEDENCIA ═══════════════════ --}}
                        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-building-check text-purple-600"></i> 3. Institución Educativa de Procedencia
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">Datos del colegio o institución donde culminó sus estudios secundarios.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                {{-- IE Name --}}
                                <div class="sm:col-span-2">
                                    <label for="institution_name_ie" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Nombre del Colegio / I.E.
                                    </label>
                                    <input type="text" name="institution_name_ie" id="institution_name_ie" maxlength="200"
                                        value="{{ old('institution_name_ie') }}" placeholder="ej. I.E. Francisco Vigo Caballero"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Year Graduation --}}
                                <div>
                                    <label for="year_graduation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Año de Egreso Escolar
                                    </label>
                                    <input type="number" min="1950" max="2100" name="year_graduation" id="year_graduation"
                                        value="{{ old('year_graduation', date('Y') - 1) }}" placeholder="ej. 2025"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono">
                                </div>

                                {{-- Modular Code IE --}}
                                <div>
                                    <label for="modular_code_ie" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Código Modular I.E.
                                    </label>
                                    <input type="text" name="modular_code_ie" id="modular_code_ie" maxlength="20"
                                        value="{{ old('modular_code_ie') }}" placeholder="ej. 0485963"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono">
                                </div>

                                {{-- Management Type IE --}}
                                <div>
                                    <label for="management_type_ie" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Tipo de Gestión I.E.
                                    </label>
                                    <select name="management_type_ie" id="management_type_ie"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                        <option value="PÚBLICA" {{ old('management_type_ie') === 'PÚBLICA' ? 'selected' : '' }}>PÚBLICA</option>
                                        <option value="PRIVADA" {{ old('management_type_ie') === 'PRIVADA' ? 'selected' : '' }}>PRIVADA</option>
                                        <option value="PARROQUIAL" {{ old('management_type_ie') === 'PARROQUIAL' ? 'selected' : '' }}>PARROQUIAL</option>
                                    </select>
                                </div>

                                {{-- Region IE --}}
                                <div>
                                    <label for="region_ie" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Región / Departamento I.E.
                                    </label>
                                    <input type="text" name="region_ie" id="region_ie"
                                        value="{{ old('region_ie', 'SAN MARTÍN') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- Province IE --}}
                                <div>
                                    <label for="province_ie" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Provincia I.E.
                                    </label>
                                    <input type="text" name="province_ie" id="province_ie"
                                        value="{{ old('province_ie', 'TOCACHE') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>

                                {{-- District IE --}}
                                <div>
                                    <label for="district_ie" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        Distrito I.E.
                                    </label>
                                    <input type="text" name="district_ie" id="district_ie"
                                        value="{{ old('district_ie', 'UCHIZA') }}"
                                        class="w-full text-sm border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Form Footer Actions --}}
                        <div class="flex items-center justify-end gap-3 pt-4">
                            <a href="{{ route('admin.statistics.index') }}"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-bold transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-sm font-extrabold shadow-md hover:shadow-lg transition flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i> Guardar Registro
                            </button>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>
@endsection
