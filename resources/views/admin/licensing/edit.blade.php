@extends('layouts.app')
@section('title', 'Editar Fase de Licenciamiento - Panel Administrativo')

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="dashboardApp()">
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
                        <i class="bi bi-pencil-square text-purple-600"></i> Editar Fase {{ $licensing->phase_number }}: {{ $licensing->code }}
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <a href="{{ route('admin.licensing.index') }}" class="hover:text-purple-600">Licenciamiento</a>
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600 font-semibold">Editar Fase</span>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-4xl mx-auto space-y-6" x-data="phaseEditForm()">

                {{-- Back Link & Public Link --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.licensing.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="bi bi-arrow-left"></i> Volver a la Lista de Fases
                    </a>

                    <a href="{{ route('licenciamiento') }}" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-700 bg-purple-50 px-3 py-1.5 rounded-xl border border-purple-200">
                        <i class="bi bi-box-arrow-up-right"></i> Ver en Portal Público
                    </a>
                </div>

                {{-- Errors Alert --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-bold text-red-800">Por favor corrige los siguientes errores:</h3>
                                <ul class="list-disc list-inside text-xs text-red-700 mt-1.5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Card --}}
                <form action="{{ route('admin.licensing.update', $licensing) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="milestones_json" :value="JSON.stringify(milestones)">

                    {{-- ── SECTION 1: DATOS PRINCIPALES ──────────────────────── --}}
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="bi bi-info-circle text-purple-600"></i> Información Principal de la Fase
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                            {{-- Phase Number --}}
                            <div class="sm:col-span-3">
                                <label for="phase_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    N° de Fase <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="phase_number" name="phase_number" min="1" max="20"
                                    value="{{ old('phase_number', $licensing->phase_number) }}" required
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold text-gray-800">
                            </div>

                            {{-- Code --}}
                            <div class="sm:col-span-3">
                                <label for="code" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Código / Sigla
                                </label>
                                <input type="text" id="code" name="code" placeholder="Ej. CBC-01, REG-02"
                                    value="{{ old('code', $licensing->code) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono">
                            </div>

                            {{-- Title --}}
                            <div class="sm:col-span-6">
                                <label for="title" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Título de la Fase <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" placeholder="Ej. Documentos de Gestión de las 7 CBC"
                                    value="{{ old('title', $licensing->title) }}" required
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium text-gray-800">
                            </div>

                            {{-- Subtitle --}}
                            <div class="sm:col-span-12">
                                <label for="subtitle" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Subtítulo / Resumen Corto
                                </label>
                                <input type="text" id="subtitle" name="subtitle" placeholder="Breve síntesis de los objetivos de esta etapa..."
                                    value="{{ old('subtitle', $licensing->subtitle) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- Description --}}
                            <div class="sm:col-span-12">
                                <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Descripción Completa y Medios de Verificación
                                </label>
                                <textarea id="description" name="description" rows="4" placeholder="Detalla el alcance, los requerimientos evaluados por el MINEDU y el avance en el IESTP..."
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all leading-relaxed">{{ old('description', $licensing->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 2: ESTADO DEL PROCESO Y ETAPA (P) ──────────── --}}
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="bi bi-hourglass-split text-amber-500"></i> Estado del Proceso y Etapa Actual
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                            {{-- Status Dropdown --}}
                            <div class="sm:col-span-6">
                                <label for="status" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Estado del Proceso <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-semibold text-gray-800">
                                    <option value="pending" {{ old('status', $licensing->status) === 'pending' ? 'selected' : '' }}>
                                        ⚪ Pendiente (PTE) — Próxima Etapa
                                    </option>
                                    <option value="in_progress" {{ old('status', $licensing->status) === 'in_progress' ? 'selected' : '' }}>
                                        🟡 En Proceso (P) — Etapa en Ejecución
                                    </option>
                                    <option value="completed" {{ old('status', $licensing->status) === 'completed' ? 'selected' : '' }}>
                                        🟢 Culminado (C) — Aprobado / Completado
                                    </option>
                                    <option value="observed" {{ old('status', $licensing->status) === 'observed' ? 'selected' : '' }}>
                                        🔴 En Observación (OBS) — En Subsanación
                                    </option>
                                </select>
                            </div>

                            {{-- Stage Tag --}}
                            <div class="sm:col-span-6">
                                <label for="stage_tag" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Etiqueta de Etapa (Tag)
                                </label>
                                <input type="text" id="stage_tag" name="stage_tag" placeholder="Ej. P, C, PTE, OBS"
                                    value="{{ old('stage_tag', $licensing->stage_tag) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold">
                            </div>

                            {{-- Is Current Stage Checkbox --}}
                            <div class="sm:col-span-12 bg-amber-50/70 p-4 rounded-xl border border-amber-300/80">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_current" value="1" {{ old('is_current', $licensing->is_current) ? 'checked' : '' }}
                                        class="w-5 h-5 text-amber-600 rounded border-amber-300 focus:ring-amber-500">
                                    <div>
                                        <span class="text-sm font-extrabold text-amber-900 flex items-center gap-1.5">
                                            Establecer como Etapa Actual (P)
                                        </span>
                                        <p class="text-xs text-amber-700 mt-0.5">
                                            Al activar esta casilla, esta fase se mostrará como la etapa activa destacada en la vista pública de transparencia con la insignia (P) y efecto luminoso.
                                        </p>
                                    </div>
                                </label>
                            </div>

                            {{-- Progress Slider --}}
                            <div class="sm:col-span-12 space-y-2">
                                <div class="flex justify-between items-center">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Porcentaje de Avance
                                    </label>
                                    <span class="text-sm font-black text-purple-600" x-text="progress + '%'"></span>
                                </div>
                                <input type="range" name="progress_percentage" min="0" max="100" step="5"
                                    x-model="progress"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 3: MARCO LEGAL, FECHAS Y ARCHIVOS ──────────── --}}
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i class="bi bi-journal-text text-sky-600"></i> Marco Legal, Fechas y Documentación
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                            {{-- Resolution Number --}}
                            <div class="sm:col-span-6">
                                <label for="resolution_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Número de Resolución / Norma
                                </label>
                                <input type="text" id="resolution_number" name="resolution_number" placeholder="Ej. RVM N° 276-2019-MINEDU"
                                    value="{{ old('resolution_number', $licensing->resolution_number) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- Legal Basis --}}
                            <div class="sm:col-span-6">
                                <label for="legal_basis" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Base Legal
                                </label>
                                <input type="text" id="legal_basis" name="legal_basis" placeholder="Ej. Ley N° 30512, D.S. N° 010-2017-MINEDU"
                                    value="{{ old('legal_basis', $licensing->legal_basis) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- Estimated Date --}}
                            <div class="sm:col-span-4">
                                <label for="estimated_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Periodo Estimado
                                </label>
                                <input type="text" id="estimated_date" name="estimated_date" placeholder="Ej. 2026 - 2027 / II Semestre"
                                    value="{{ old('estimated_date', $licensing->estimated_date) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- Start Date --}}
                            <div class="sm:col-span-4">
                                <label for="start_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Fecha de Inicio
                                </label>
                                <input type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date', $licensing->start_date ? $licensing->start_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- End Date --}}
                            <div class="sm:col-span-4">
                                <label for="end_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Fecha de Culminación
                                </label>
                                <input type="date" id="end_date" name="end_date"
                                    value="{{ old('end_date', $licensing->end_date ? $licensing->end_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- File Upload & Current File --}}
                            <div class="sm:col-span-6 space-y-2">
                                <label for="file_path" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Actualizar Documento / Archivo Adjunto (PDF)
                                </label>
                                <input type="file" id="file_path" name="file_path" accept=".pdf,.doc,.docx,.zip,.rar"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-xl p-1">
                                
                                @if ($licensing->file_path)
                                    <div class="flex items-center gap-2 p-2.5 bg-purple-50 rounded-xl border border-purple-200 text-xs">
                                        <i class="bi bi-file-earmark-pdf-fill text-purple-600 text-base"></i>
                                        <span class="text-purple-900 font-medium truncate flex-1">{{ basename($licensing->file_path) }}</span>
                                        <a href="{{ Storage::url($licensing->file_path) }}" target="_blank" class="text-purple-700 hover:underline font-bold">Ver / Descargar</a>
                                    </div>
                                @endif
                            </div>

                            {{-- External Link --}}
                            <div class="sm:col-span-6">
                                <label for="external_link" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Enlace Externo Oficial (MINEDU / Web)
                                </label>
                                <input type="url" id="external_link" name="external_link" placeholder="https://..."
                                    value="{{ old('external_link', $licensing->external_link) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            {{-- Order & Visibility --}}
                            <div class="sm:col-span-6">
                                <label for="order" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Orden de Visualización
                                </label>
                                <input type="number" id="order" name="order" min="1"
                                    value="{{ old('order', $licensing->order) }}"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            </div>

                            <div class="sm:col-span-6 flex items-center pt-6">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $licensing->is_active) ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                    <span class="text-sm font-semibold text-gray-800">Publicar en el portal de transparencia</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 4: HITOS / SUB-CONDICIONES CBC ─────────────── --}}
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="bi bi-list-check text-emerald-600"></i> Hitos / Sub-Condiciones CBC de esta Fase
                            </h2>
                            <button type="button" @click="addMilestone()"
                                class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition-colors flex items-center gap-1">
                                <i class="bi bi-plus-circle-fill"></i> Agregar Hito / CBC
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(m, index) in milestones" :key="index">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3 relative group">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-700" x-text="'Hito #' + (index + 1)"></span>
                                        <button type="button" @click="removeMilestone(index)"
                                            class="text-red-500 hover:text-red-700 text-xs p-1">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                        <div class="sm:col-span-3">
                                            <input type="text" x-model="m.cbc_number" placeholder="Ej. CBC 1 o Hito 1.1"
                                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                        </div>
                                        <div class="sm:col-span-6">
                                            <input type="text" x-model="m.name" placeholder="Nombre de la condición / hito"
                                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <select x-model="m.status" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white">
                                                <option value="completed">Cumplido</option>
                                                <option value="in_progress">En Adecuación</option>
                                                <option value="pending">Pendiente</option>
                                            </select>
                                        </div>
                                        <div class="sm:col-span-12">
                                            <input type="text" x-model="m.description" placeholder="Descripción breve del medio de verificación..."
                                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="milestones.length === 0" class="text-center py-6 text-xs text-gray-500 bg-gray-50/60 rounded-xl border border-dashed border-gray-300">
                                No se han agregado hitos secundarios. Haga clic en "+ Agregar Hito / CBC" si desea incluir el detalle de las condiciones.
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.licensing.index') }}"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all">
                            Actualizar Fase de Licenciamiento
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('phaseEditForm', () => ({
            progress: {{ old('progress_percentage', $licensing->progress_percentage ?? 0) }},
            milestones: @json($licensing->milestones ?? []),
            addMilestone() {
                this.milestones.push({
                    cbc_number: 'CBC ' + (this.milestones.length + 1),
                    name: '',
                    description: '',
                    status: 'pending',
                    progress: 0
                });
            },
            removeMilestone(index) {
                this.milestones.splice(index, 1);
            }
        }));
    });
</script>
@endpush
