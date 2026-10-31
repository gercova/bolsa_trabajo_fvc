<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    schedule: { type: Object, required: true },
    programs: { type: Array, default: () => [] },
    existingDetails: { type: Object, default: () => ({}) },
});

// Pre-fill details array from existing details
const initialDetails = (props.schedule.details || []).map((d) => ({
    program_id: d.program_id,
    available_slots: d.available_slots || 0,
    observations: d.observations || '',
}));

const form = useForm({
    academic_period: props.schedule.academic_period || '',
    enrollment_type: props.schedule.enrollment_type || 'ordinaria',
    start_date: props.schedule.start_date ? String(props.schedule.start_date).substring(0, 10) : '',
    end_date: props.schedule.end_date ? String(props.schedule.end_date).substring(0, 10) : '',
    enrollment_fee: props.schedule.enrollment_fee ?? 0,
    observations: props.schedule.observations || '',
    is_active: Boolean(props.schedule.is_active),
    details: initialDetails,
});

const addDetail = () => {
    form.details.push({
        program_id: '',
        available_slots: 0,
        observations: '',
    });
};

const removeDetail = (index) => {
    form.details.splice(index, 1);
};

const submit = () => {
    form.put(route('admin.enrollments.update', props.schedule.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout :title="`Editar Cronograma - ${schedule.academic_period}`"
                 :subtitle="`Modifica las fechas y cupos para la matrícula ${schedule.enrollment_type}`"
                 icon="bi-pencil-square"
                 :breadcrumbs="[
                     { label: 'Matrículas', url: route('admin.enrollments.index') },
                     { label: `Editar #${schedule.id}` }
                 ]">
        <div class="max-w-4xl mx-auto space-y-6">

            <div class="flex items-center justify-between">
                <Link :href="route('admin.enrollments.index')"
                      class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-indigo-600 transition">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </Link>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider"
                          :class="schedule.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'">
                        {{ schedule.is_active ? 'Publicado Activo' : 'Inactivo / Oculto' }}
                    </span>
                    <span class="text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider"
                          :class="schedule.enrollment_type === 'ordinaria' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'">
                        {{ schedule.enrollment_type === 'ordinaria' ? 'Ordinaria' : 'Extraordinaria' }}
                    </span>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Card 1: Información del Cronograma -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-lg shrink-0">
                            <i class="bi bi-calendar2-range-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800">
                                Información General del Período
                            </h2>
                            <p class="text-xs text-slate-400">Datos básicos del cronograma institucional</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Período Académico -->
                        <div class="space-y-1.5">
                            <label for="academic_period" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Período Académico <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="academic_period" v-model="form.academic_period" placeholder="Ej: 2026-II"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition"
                                   :class="form.errors.academic_period ? 'border-rose-400 bg-rose-50/30' : ''" />
                            <p v-if="form.errors.academic_period" class="text-rose-500 text-xs">{{ form.errors.academic_period }}</p>
                        </div>

                        <!-- Tipo de Matrícula -->
                        <div class="space-y-1.5">
                            <label for="enrollment_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Tipo de Matrícula <span class="text-rose-500">*</span>
                            </label>
                            <select id="enrollment_type" v-model="form.enrollment_type"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="ordinaria">Matrícula Ordinaria (Regular)</option>
                                <option value="extraordinaria">Matrícula Extraordinaria (Extemporánea / con FUT)</option>
                            </select>
                            <p v-if="form.errors.enrollment_type" class="text-rose-500 text-xs">{{ form.errors.enrollment_type }}</p>
                        </div>
                    </div>

                    <!-- Tipo banner info -->
                    <div class="p-3.5 rounded-2xl border text-xs flex items-center gap-2.5"
                         :class="form.enrollment_type === 'ordinaria' ? 'bg-blue-50/70 border-blue-200 text-blue-900' : 'bg-purple-50/70 border-purple-200 text-purple-900'">
                        <i class="bi text-base"
                           :class="form.enrollment_type === 'ordinaria' ? 'bi-info-circle-fill text-blue-600' : 'bi-exclamation-circle-fill text-purple-600'"></i>
                        <span>
                            {{ form.enrollment_type === 'ordinaria'
                                ? 'Matrícula Ordinaria: Dirigida a estudiantes regulares dentro del calendario estándar.'
                                : 'Matrícula Extraordinaria: Para matrículas rezagadas o autorizadas mediante trámite de FUT.' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Fecha Inicio -->
                        <div class="space-y-1.5">
                            <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Fecha de Inicio <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="start_date" v-model="form.start_date"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition"
                                   :class="form.errors.start_date ? 'border-rose-400 bg-rose-50/30' : ''" />
                            <p v-if="form.errors.start_date" class="text-rose-500 text-xs">{{ form.errors.start_date }}</p>
                        </div>

                        <!-- Fecha Cierre -->
                        <div class="space-y-1.5">
                            <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Fecha de Cierre <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="end_date" v-model="form.end_date"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition"
                                   :class="form.errors.end_date ? 'border-rose-400 bg-rose-50/30' : ''" />
                            <p v-if="form.errors.end_date" class="text-rose-500 text-xs">{{ form.errors.end_date }}</p>
                        </div>
                    </div>

                    <!-- Costo / Tarifa -->
                    <div class="space-y-1.5">
                        <label for="enrollment_fee" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Costo del Derecho de Matrícula (S/) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">S/</span>
                            <input type="number" id="enrollment_fee" v-model="form.enrollment_fee" step="0.01" min="0"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition"
                                   :class="form.errors.enrollment_fee ? 'border-rose-400 bg-rose-50/30' : ''" />
                        </div>
                        <p class="text-[11px] text-slate-400">Ingresa 0 si es gratuito o se liquida por concepto especial.</p>
                        <p v-if="form.errors.enrollment_fee" class="text-rose-500 text-xs">{{ form.errors.enrollment_fee }}</p>
                    </div>

                    <!-- Observaciones -->
                    <div class="space-y-1.5">
                        <label for="observations" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Observaciones / Indicaciones (Opcional)
                        </label>
                        <textarea id="observations" v-model="form.observations" rows="3"
                                  placeholder="Notas adicionales o requisitos específicos para los alumnos..."
                                  class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-normal text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                        <p v-if="form.errors.observations" class="text-rose-500 text-xs">{{ form.errors.observations }}</p>
                    </div>

                    <!-- Toggle Activo -->
                    <div class="pt-3 border-t border-slate-100">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" v-model="form.is_active"
                                   class="w-5 h-5 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-bold text-slate-800">Cronograma Activo</span>
                                <p class="text-[11px] text-slate-400">Visible en la sección pública de matrículas del portal</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Card 2: Cupos por Programa de Estudio -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-5">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 text-lg shrink-0">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800">
                                    Cupos por Carrera / Programa de Estudio
                                </h2>
                                <p class="text-xs text-slate-400">Opcional. Configura las vacantes disponibles por cada carrera</p>
                            </div>
                        </div>
                        <button type="button" @click="addDetail"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-3.5 py-2 rounded-xl transition">
                            <i class="bi bi-plus-lg"></i> Agregar Carrera
                        </button>
                    </div>

                    <div v-if="form.details.length === 0" class="text-center py-8 text-slate-400">
                        <i class="bi bi-diagram-3 text-3xl mb-2 block text-slate-300"></i>
                        <p class="text-xs font-semibold">No se han asignado cupos específicos por carrera.</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Haz clic en "Agregar Carrera" si deseas establecer vacantes específicas.</p>
                    </div>

                    <div v-else class="space-y-3">
                        <div v-for="(detail, index) in form.details" :key="index"
                             class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <div class="flex-1 w-full">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Programa</label>
                                <select v-model="detail.program_id"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500">
                                    <option value="" disabled>Seleccionar carrera...</option>
                                    <option v-for="p in programs" :key="p.id" :value="p.id">{{ p.name }}</option>
                                </select>
                            </div>
                            <div class="w-full sm:w-32">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cupos</label>
                                <input type="number" v-model="detail.available_slots" min="0" placeholder="0"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 text-center focus:ring-2 focus:ring-indigo-500" />
                            </div>
                            <div class="flex-1 w-full">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nota (Opcional)</label>
                                <input type="text" v-model="detail.observations" placeholder="Ej: Turno mañana"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-indigo-500" />
                            </div>
                            <button type="button" @click="removeDetail(index)"
                                    class="self-end sm:self-center p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Eliminar fila">
                                <i class="bi bi-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <Link :href="route('admin.enrollments.index')"
                          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Cancelar
                    </Link>
                    <button type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-indigo-500/20 transition disabled:opacity-50 flex items-center gap-2">
                        <i class="bi bi-save"></i>
                        <span>{{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}</span>
                    </button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
