@extends('layouts.app')
@section('title', 'Editar Cronograma de Matrícula - Panel Administrativo')
@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            background: #fff;
        }

        .form-input.is-invalid {
            border-color: #ef4444;
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 4px;
            font-weight: 600;
        }

        .program-row {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
        }
    </style>
@endpush
@section('content')
    <div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]"
    x-data="dashboardApp()">
        @include('admin.components.aside')
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">

            {{-- Header --}}
            <header
                class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button @click="toggleSidebar()"
                            class="mr-3 text-gray-500 hover:text-indigo-600 p-2 rounded-lg transition lg:hidden">
                            <i class="bi bi-list text-xl sm:text-2xl"></i>
                        </button>
                        <div>
                            <h1 class="text-xl font-extrabold text-gray-800 tracking-tight">Editar Cronograma</h1>
                            <p class="text-xs text-gray-400 mt-0.5 hidden sm:block">
                                Período: <strong>{{ $schedule->academic_period }}</strong> — {{ $schedule->type_label }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('matriculas') }}" target="_blank"
                            class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-indigo-600 bg-gray-100 px-3 py-2 rounded-xl transition">
                            <i class="bi bi-box-arrow-up-right text-xs"></i> Ver página
                        </a>
                        <a href="{{ route('admin.enrollments.index') }}"
                            class="inline-flex items-center gap-1.5 text-sm font-bold text-gray-600 hover:text-indigo-700 bg-gray-100 hover:bg-indigo-50 px-4 py-2 rounded-xl transition border border-gray-200">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-4xl mx-auto">
                    <form action="{{ route('admin.enrollments.update', $schedule) }}" method="POST" x-data="editEnrollmentForm()"
                        id="form-edit-enrollment">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            {{-- ── Card 1: Información General ── --}}
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                                        <i class="bi bi-calendar2-check text-indigo-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-extrabold text-gray-800">Información del Cronograma</h2>
                                        <p class="text-xs text-gray-500 mt-0.5">Edita el período, tipo, fechas y costo de
                                            matrícula.</p>
                                    </div>
                                </div>
                                <div class="p-6 space-y-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        {{-- Período --}}
                                        <div>
                                            <label class="form-label" for="academic_period">Período Académico <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" id="academic_period" name="academic_period"
                                                value="{{ old('academic_period', $schedule->academic_period) }}"
                                                placeholder="Ej: 2026-II"
                                                class="form-input @error('academic_period') is-invalid @enderror">
                                            @error('academic_period')
                                                <p class="error-msg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- Tipo --}}
                                        <div>
                                            <label class="form-label" for="enrollment_type">Tipo de Matrícula <span
                                                    class="text-red-500">*</span></label>
                                            <select id="enrollment_type" name="enrollment_type" x-model="enrollmentType"
                                                class="form-input @error('enrollment_type') is-invalid @enderror">
                                                <option value="ordinaria"
                                                    {{ old('enrollment_type', $schedule->enrollment_type) === 'ordinaria' ? 'selected' : '' }}>
                                                    Matrícula Ordinaria</option>
                                                <option value="extraordinaria"
                                                    {{ old('enrollment_type', $schedule->enrollment_type) === 'extraordinaria' ? 'selected' : '' }}>
                                                    Matrícula Extraordinaria</option>
                                            </select>
                                            @error('enrollment_type')
                                                <p class="error-msg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Type Preview --}}
                                    <div x-show="enrollmentType" x-cloak class="p-4 rounded-xl border"
                                        :class="enrollmentType === 'ordinaria' ? 'bg-blue-50 border-blue-100' :
                                            'bg-violet-50 border-violet-100'">
                                        <div class="flex items-center gap-2">
                                            <i class="bi"
                                                :class="enrollmentType === 'ordinaria' ?
                                                    'bi-calendar2-check-fill text-blue-600' :
                                                    'bi-calendar2-x-fill text-violet-600'"></i>
                                            <span class="font-extrabold text-sm"
                                                :class="enrollmentType === 'ordinaria' ? 'text-blue-800' : 'text-violet-800'"
                                                x-text="enrollmentType === 'ordinaria' ? 'Matrícula Ordinaria — Período regular.' : 'Matrícula Extraordinaria — Fuera del período regular.'"></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        {{-- Inicio --}}
                                        <div>
                                            <label class="form-label" for="start_date">Fecha de Inicio <span
                                                    class="text-red-500">*</span></label>
                                            <input type="date" id="start_date" name="start_date"
                                                value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}"
                                                class="form-input @error('start_date') is-invalid @enderror">
                                            @error('start_date')
                                                <p class="error-msg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- Cierre --}}
                                        <div>
                                            <label class="form-label" for="end_date">Fecha de Cierre <span
                                                    class="text-red-500">*</span></label>
                                            <input type="date" id="end_date" name="end_date"
                                                value="{{ old('end_date', $schedule->end_date->format('Y-m-d')) }}"
                                                class="form-input @error('end_date') is-invalid @enderror">
                                            @error('end_date')
                                                <p class="error-msg">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Fee --}}
                                    <div>
                                        <label class="form-label" for="enrollment_fee">Costo del Derecho de Matrícula (S/)
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">S/</span>
                                            <input type="number" id="enrollment_fee" name="enrollment_fee" step="0.01"
                                                min="0"
                                                value="{{ old('enrollment_fee', $schedule->enrollment_fee) }}"
                                                class="form-input pl-9 @error('enrollment_fee') is-invalid @enderror">
                                        </div>
                                        @error('enrollment_fee')
                                            <p class="error-msg">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- Observations --}}
                                    <div>
                                        <label class="form-label" for="observations">Observaciones (Opcional)</label>
                                        <textarea id="observations" name="observations" rows="3" placeholder="Notas adicionales..."
                                            class="form-input @error('observations') is-invalid @enderror">{{ old('observations', $schedule->observations) }}</textarea>
                                        @error('observations')
                                            <p class="error-msg">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Active Toggle --}}
                                    <div
                                        class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <div>
                                            <p class="text-sm font-extrabold text-gray-800">Estado del Cronograma</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Los cronogramas activos son visibles en
                                                la página pública.</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" id="is_active" class="sr-only peer"
                                                {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-400 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                            </div>
                                            <span class="ms-2 text-sm font-bold text-gray-700">Activo</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- ── Card 2: Cupos por Programa ── --}}
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                                            <i class="bi bi-diagram-3 text-purple-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-sm font-extrabold text-gray-800">Cupos por Programa de Estudio
                                            </h2>
                                            <p class="text-xs text-gray-500 mt-0.5">Opcional. Al guardar se reemplazarán
                                                los cupos actuales.</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="addDetail()"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 px-3 py-2 rounded-xl transition">
                                        <i class="bi bi-plus-lg"></i> Agregar
                                    </button>
                                </div>
                                <div class="p-6 space-y-3">
                                    <template x-for="(detail, index) in details" :key="index">
                                        <div
                                            class="program-row flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                                            <div class="flex-1">
                                                <select :name="`details[${index}][program_id]`" x-model="detail.program_id"
                                                    class="form-input text-sm">
                                                    <option value="">Seleccionar programa...</option>
                                                    @foreach ($programs as $program)
                                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="w-full sm:w-36">
                                                <input type="number" :name="`details[${index}][available_slots]`"
                                                    x-model="detail.slots" min="0" placeholder="Cupos"
                                                    class="form-input text-sm text-center">
                                            </div>
                                            <button type="button" @click="removeDetail(index)"
                                                class="text-red-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition shrink-0">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <div x-show="details.length === 0" class="text-center py-6 text-gray-400">
                                        <i class="bi bi-diagram-3 text-3xl mb-2 block"></i>
                                        <p class="text-sm font-semibold">Sin cupos por programa. Haz clic en "Agregar".</p>
                                    </div>
                                </div>
                            </div>
                            {{-- Danger Zone --}}
                            <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
                                <h3 class="text-sm font-extrabold text-red-800 mb-3 flex items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-red-500"></i> Zona de Peligro
                                </h3>
                                <p class="text-xs text-red-600 mb-4 leading-relaxed">
                                    Eliminar este cronograma borrará también todos los cupos registrados por programa. Esta
                                    acción no se puede deshacer.
                                </p>
                                <form action="{{ route('admin.enrollments.destroy', $schedule) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar el cronograma del período {{ $schedule->academic_period }}? Esta acción no se puede revertir.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-xl transition">
                                        <i class="bi bi-trash3-fill"></i> Eliminar Cronograma
                                    </button>
                                </form>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end gap-3 pb-4">
                                <a href="{{ route('admin.enrollments.index') }}"
                                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-5 py-3 rounded-xl transition">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-xl transition shadow-md shadow-indigo-500/20">
                                    <i class="bi bi-check-circle-fill"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    @push('scripts')
        <script>
            function editEnrollmentForm() {
                // Pre-populate existing details from PHP
                const existingDetails = @json($schedule->details->map(fn($d) => ['program_id' => (string) $d->program_id, 'slots' => $d->available_slots]));
                return {
                    enrollmentType: '{{ old('enrollment_type', $schedule->enrollment_type) }}',
                    details: existingDetails,
                    addDetail() {
                        this.details.push({
                            program_id: '',
                            slots: 0
                        });
                    },
                    removeDetail(index) {
                        this.details.splice(index, 1);
                    }
                };
            }
        </script>
    @endpush
@endsection
