<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    programs: { type: Array, default: () => [] },
    areas: { type: Array, default: () => [] },
});

// Setup initial vacancies per program
const programVacancies = ref(
    props.programs.map((p) => ({
        program_id: p.id,
        name: p.name,
        vacancies: 0,
    }))
);

const form = useForm({
    period: '',
    process: 'admisión',
    type: 'ordinario',
    activity: '',
    exam_date: '',
    inscription_start_date: '',
    inscription_end_date: '',
    price: 150.00,
    tuition_fee: '',
    monthly_fee: '',
    duration: '',
    area_id: '',
    indications: '',
    total_vacancies: 0,
    is_active: true,
    url_pdf: null,
    results_url_pdf: null,
    programs: [],
});

const updateTotalFromPrograms = () => {
    const sum = programVacancies.value.reduce((acc, curr) => acc + (Number(curr.vacancies) || 0), 0);
    if (sum > 0) {
        form.total_vacancies = sum;
    }
};

const handleBasesFile = (e) => {
    form.url_pdf = e.target.files[0] || null;
};

const handleResultsFile = (e) => {
    form.results_url_pdf = e.target.files[0] || null;
};

const submit = () => {
    form.programs = programVacancies.value.map((pv) => ({
        program_id: pv.program_id,
        vacancies: Number(pv.vacancies) || 0,
    }));

    form.post(route('admin.exams.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout title="Crear Nuevo Examen / Ciclo"
                 subtitle="Configura una nueva convocatoria de admisión o ciclo CEPRE"
                 icon="bi-journal-plus"
                 :breadcrumbs="[
                     { label: 'Exámenes', url: route('admin.exams.index') },
                     { label: 'Crear Examen' }
                 ]">
        <div class="max-w-5xl mx-auto space-y-6">

            <div class="flex items-center justify-between">
                <Link :href="route('admin.exams.index')"
                      class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-purple-600 transition">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Columna Izquierda: Configuración, Archivos y Estado -->
                    <div class="lg:col-span-1 space-y-5">
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="bi bi-gear-fill text-purple-600"></i> Parámetros del Proceso
                            </h3>

                            <!-- Proceso -->
                            <div class="space-y-1.5">
                                <label for="process" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Proceso <span class="text-rose-500">*</span>
                                </label>
                                <select id="process" v-model="form.process"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="admisión">Examen de Admisión</option>
                                    <option value="cepre">Ciclo CEPRE</option>
                                    <option value="matrícula">Matrícula</option>
                                </select>
                                <p v-if="form.errors.process" class="text-rose-500 text-xs">{{ form.errors.process }}</p>
                            </div>

                            <!-- Tipo de examen -->
                            <div class="space-y-1.5">
                                <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Tipo de Convocatoria <span class="text-rose-500">*</span>
                                </label>
                                <select id="type" v-model="form.type"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="ordinario">Ordinario</option>
                                    <option value="extraordinario">Extraordinario</option>
                                </select>
                                <p v-if="form.errors.type" class="text-rose-500 text-xs">{{ form.errors.type }}</p>
                            </div>

                            <!-- Área Académica -->
                            <div class="space-y-1.5">
                                <label for="area_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Área Responsable
                                </label>
                                <select id="area_id" v-model="form.area_id"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition">
                                    <option value="">-- General / Sin Área --</option>
                                    <option v-for="area in areas" :key="area.id" :value="area.id">{{ area.name }}</option>
                                </select>
                                <p v-if="form.errors.area_id" class="text-rose-500 text-xs">{{ form.errors.area_id }}</p>
                            </div>

                            <!-- Documento de Bases (PDF) -->
                            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                <label for="url_pdf" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    <i class="bi bi-file-earmark-pdf-fill text-rose-500 mr-1"></i> Bases Oficiales (PDF)
                                </label>
                                <input type="file" id="url_pdf" accept="application/pdf"
                                       @change="handleBasesFile"
                                       class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer" />
                                <p class="text-[11px] text-slate-400">Prospecto, bases o temario oficial en PDF.</p>
                                <p v-if="form.errors.url_pdf" class="text-rose-500 text-xs">{{ form.errors.url_pdf }}</p>
                            </div>

                            <!-- Documento de Resultados (PDF) -->
                            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                <label for="results_url_pdf" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    <i class="bi bi-file-earmark-check-fill text-emerald-600 mr-1"></i> Resultados (PDF)
                                </label>
                                <input type="file" id="results_url_pdf" accept="application/pdf"
                                       @change="handleResultsFile"
                                       class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer" />
                                <p class="text-[11px] text-slate-400">Opcional. Cuadro de méritos / lista de ingresantes.</p>
                                <p v-if="form.errors.results_url_pdf" class="text-rose-500 text-xs">{{ form.errors.results_url_pdf }}</p>
                            </div>

                            <!-- Estado Activo -->
                            <div class="pt-3 border-t border-slate-100">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" v-model="form.is_active"
                                           class="w-5 h-5 text-purple-600 bg-slate-100 border-slate-300 rounded focus:ring-purple-500">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800">Convocatoria Activa</span>
                                        <p class="text-[11px] text-slate-400">Visible para el público en general</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Datos Generales, Tarifas, Fechas y Vacantes -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Datos principales -->
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="bi bi-info-circle-fill text-purple-600"></i> Información de la Convocatoria
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Periodo -->
                                <div class="space-y-1.5">
                                    <label for="period" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                        Periodo Académico <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="period" v-model="form.period" placeholder="Ej. 2026-I"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"
                                           :class="form.errors.period ? 'border-rose-400 bg-rose-50/30' : ''" />
                                    <p v-if="form.errors.period" class="text-rose-500 text-xs">{{ form.errors.period }}</p>
                                </div>

                                <!-- Duración -->
                                <div class="space-y-1.5">
                                    <label for="duration" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                        Duración del Ciclo
                                    </label>
                                    <input type="text" id="duration" v-model="form.duration" placeholder="Ej. 3 meses / 12 semanas"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    <p v-if="form.errors.duration" class="text-rose-500 text-xs">{{ form.errors.duration }}</p>
                                </div>
                            </div>

                            <!-- Costos y Tarifas -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100">
                                <div class="space-y-1.5">
                                    <label for="price" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                        Derecho Inscripción (S/) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">S/</span>
                                        <input type="number" id="price" v-model="form.price" step="0.01" min="0"
                                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    </div>
                                    <p v-if="form.errors.price" class="text-rose-500 text-xs">{{ form.errors.price }}</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="tuition_fee" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                        Costo Matrícula (S/)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">S/</span>
                                        <input type="number" id="tuition_fee" v-model="form.tuition_fee" step="0.01" min="0" placeholder="0.00"
                                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    </div>
                                    <p v-if="form.errors.tuition_fee" class="text-rose-500 text-xs">{{ form.errors.tuition_fee }}</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="monthly_fee" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                        Cuota Mensual (S/)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">S/</span>
                                        <input type="number" id="monthly_fee" v-model="form.monthly_fee" step="0.01" min="0" placeholder="0.00"
                                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    </div>
                                    <p v-if="form.errors.monthly_fee" class="text-rose-500 text-xs">{{ form.errors.monthly_fee }}</p>
                                </div>
                            </div>

                            <!-- Actividad / Nombre -->
                            <div class="space-y-1.5">
                                <label for="activity" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Nombre de la Actividad / Descripción <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="activity" v-model="form.activity" placeholder="Ej. Examen General de Admisión Ordinario 2026-I"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"
                                       :class="form.errors.activity ? 'border-rose-400 bg-rose-50/30' : ''" />
                                <p v-if="form.errors.activity" class="text-rose-500 text-xs">{{ form.errors.activity }}</p>
                            </div>

                            <!-- Fechas -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100">
                                <div class="space-y-1.5">
                                    <label for="inscription_start_date" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                        Inicio Inscripción <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="inscription_start_date" v-model="form.inscription_start_date"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    <p v-if="form.errors.inscription_start_date" class="text-rose-500 text-xs">{{ form.errors.inscription_start_date }}</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="inscription_end_date" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                        Fin Inscripción <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="inscription_end_date" v-model="form.inscription_end_date"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    <p v-if="form.errors.inscription_end_date" class="text-rose-500 text-xs">{{ form.errors.inscription_end_date }}</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="exam_date" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                                        Fecha del Examen <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="exam_date" v-model="form.exam_date"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                    <p v-if="form.errors.exam_date" class="text-rose-500 text-xs">{{ form.errors.exam_date }}</p>
                                </div>
                            </div>

                            <!-- Indicaciones -->
                            <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                <label for="indications" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Indicaciones e Instrucciones para el Postulante
                                </label>
                                <textarea id="indications" v-model="form.indications" rows="3"
                                          placeholder="Detalla los requisitos el día del examen, hora de ingreso, útiles permitidos, vestimenta, etc."
                                          class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-normal text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"></textarea>
                                <p v-if="form.errors.indications" class="text-rose-500 text-xs">{{ form.errors.indications }}</p>
                            </div>
                        </div>

                        <!-- Distribución de Vacantes por Programa -->
                        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-mortarboard text-purple-600"></i> Vacantes por Carrera Profesional
                                    </h3>
                                    <p class="text-[11px] text-slate-400">Distribuye las vacantes disponibles para cada programa de estudio</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Total Vacantes</span>
                                    <input type="number" v-model="form.total_vacancies" min="0"
                                           class="w-24 px-3 py-1 bg-purple-50 border border-purple-200 rounded-xl text-sm font-extrabold text-purple-700 text-center" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                <div v-for="pv in programVacancies" :key="pv.program_id"
                                     class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center justify-between gap-3">
                                    <span class="text-xs font-bold text-slate-700 truncate">{{ pv.name }}</span>
                                    <input type="number" v-model="pv.vacancies" min="0" @input="updateTotalFromPrograms"
                                           placeholder="0"
                                           class="w-20 px-2.5 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 text-center focus:ring-2 focus:ring-purple-500 transition" />
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <Link :href="route('admin.exams.index')"
                                  class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                                Cancelar
                            </Link>
                            <button type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-purple-500/20 transition disabled:opacity-50 flex items-center gap-2">
                                <i class="bi bi-save"></i>
                                <span>{{ form.processing ? 'Guardando...' : 'Crear Convocatoria' }}</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </AdminLayout>
</template>
