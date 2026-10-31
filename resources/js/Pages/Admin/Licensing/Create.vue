<script setup>
import { ref, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { route } from '@/route'

const props = defineProps({
    nextPhaseNumber: {
        type: Number,
        default: 1,
    },
    nextOrder: {
        type: Number,
        default: 1,
    },
})

// Milestones list
const milestones = ref([])

function addMilestone() {
    milestones.value.push({
        cbc_number: `Hito ${milestones.value.length + 1}`,
        name: '',
        status: 'pending',
        description: '',
    })
}

function removeMilestone(index) {
    milestones.value.splice(index, 1)
}

const form = useForm({
    phase_number: props.nextPhaseNumber,
    code: `FASE-0${props.nextPhaseNumber}`,
    title: '',
    subtitle: '',
    description: '',
    status: 'pending',
    stage_tag: 'PTE',
    is_current: false,
    progress_percentage: 0,
    resolution_number: '',
    legal_basis: '',
    estimated_date: '',
    start_date: '',
    end_date: '',
    file_path: null,
    external_link: '',
    order: props.nextOrder,
    is_active: true,
    milestones_json: '',
})

// Auto-sync stage_tag with status
watch(() => form.status, (newStatus) => {
    if (!form.is_current) {
        if (newStatus === 'completed') form.stage_tag = 'C'
        else if (newStatus === 'in_progress') form.stage_tag = 'P'
        else if (newStatus === 'observed') form.stage_tag = 'OBS'
        else if (newStatus === 'pending') form.stage_tag = 'PTE'
    }
})

// Auto-sync status with is_current
watch(() => form.is_current, (isCurrent) => {
    if (isCurrent) {
        form.status = 'in_progress'
        form.stage_tag = 'P'
    }
})

function handleFileChange(event) {
    const file = event.target.files?.[0]
    form.file_path = file || null
}

function submit() {
    form.milestones_json = milestones.value.length > 0 ? JSON.stringify(milestones.value) : ''
    form.post(route('admin.licensing.store'))
}
</script>

<template>
    <AdminLayout title="Registrar Fase de Licenciamiento">
        <Head title="Nueva Fase del Licenciamiento - Panel Administrativo" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header & Navigation -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl shadow-xs">
                        <i class="bi bi-patch-plus-fill"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                            Registrar Fase de Licenciamiento
                        </h1>
                        <p class="text-xs text-gray-500 font-medium">
                            Configure las condiciones básicas de calidad (CBC), cronograma y documentación legal
                        </p>
                    </div>
                </div>

                <Link
                    :href="route('admin.licensing.index')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-semibold rounded-xl transition"
                >
                    <i class="bi bi-arrow-left"></i> Volver a la Lista
                </Link>
            </div>

            <!-- Errors Alert -->
            <div
                v-if="Object.keys(form.errors).length > 0"
                class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs"
            >
                <div class="flex items-start gap-3">
                    <i class="bi bi-exclamation-octagon-fill text-red-600 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">Por favor corrige los siguientes errores:</h3>
                        <ul class="list-disc list-inside text-xs text-red-700 mt-1.5 space-y-1">
                            <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- ── SECTION 1: DATOS PRINCIPALES ──────────────────────── -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200 space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-info-circle text-purple-600"></i> Información Principal de la Fase
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                        <!-- Phase Number -->
                        <div class="sm:col-span-3">
                            <label for="phase_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                N° de Fase <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="phase_number"
                                v-model.number="form.phase_number"
                                type="number"
                                min="1"
                                max="20"
                                required
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold text-gray-800"
                            />
                            <p v-if="form.errors.phase_number" class="text-xs text-red-600 mt-1">{{ form.errors.phase_number }}</p>
                        </div>

                        <!-- Code -->
                        <div class="sm:col-span-3">
                            <label for="code" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Código / Sigla
                            </label>
                            <input
                                id="code"
                                v-model="form.code"
                                type="text"
                                placeholder="Ej. CBC-01, REG-02"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono"
                            />
                            <p v-if="form.errors.code" class="text-xs text-red-600 mt-1">{{ form.errors.code }}</p>
                        </div>

                        <!-- Title -->
                        <div class="sm:col-span-6">
                            <label for="title" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Título de la Fase <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Ej. Documentos de Gestión de las 7 CBC"
                                required
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium text-gray-800"
                            />
                            <p v-if="form.errors.title" class="text-xs text-red-600 mt-1">{{ form.errors.title }}</p>
                        </div>

                        <!-- Subtitle -->
                        <div class="sm:col-span-12">
                            <label for="subtitle" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Subtítulo / Resumen Corto
                            </label>
                            <input
                                id="subtitle"
                                v-model="form.subtitle"
                                type="text"
                                placeholder="Breve síntesis de los objetivos de esta etapa..."
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.subtitle" class="text-xs text-red-600 mt-1">{{ form.errors.subtitle }}</p>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-12">
                            <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Descripción Completa y Medios de Verificación
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                placeholder="Detalla el alcance, los requerimientos evaluados por el MINEDU y el avance en el IESTP..."
                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all leading-relaxed"
                            ></textarea>
                            <p v-if="form.errors.description" class="text-xs text-red-600 mt-1">{{ form.errors.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- ── SECTION 2: ESTADO DEL PROCESO Y ETAPA (P) ──────────── -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200 space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-hourglass-split text-amber-500"></i> Estado del Proceso y Etapa Actual
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                        <!-- Status Dropdown -->
                        <div class="sm:col-span-6">
                            <label for="status" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Estado del Proceso <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                required
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white font-semibold text-gray-800"
                            >
                                <option value="pending">⚪ Pendiente (PTE) — Próxima Etapa</option>
                                <option value="in_progress">🟡 En Proceso (P) — Etapa en Ejecución</option>
                                <option value="completed">🟢 Culminado (C) — Aprobado / Completado</option>
                                <option value="observed">🔴 En Observación (OBS) — En Subsanación</option>
                            </select>
                            <p v-if="form.errors.status" class="text-xs text-red-600 mt-1">{{ form.errors.status }}</p>
                        </div>

                        <!-- Stage Tag -->
                        <div class="sm:col-span-6">
                            <label for="stage_tag" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Etiqueta de Etapa (Tag)
                            </label>
                            <input
                                id="stage_tag"
                                v-model="form.stage_tag"
                                type="text"
                                placeholder="Ej. P, C, PTE, OBS"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold"
                            />
                            <p v-if="form.errors.stage_tag" class="text-xs text-red-600 mt-1">{{ form.errors.stage_tag }}</p>
                        </div>

                        <!-- Is Current Stage Checkbox -->
                        <div class="sm:col-span-12 bg-amber-50/70 p-4 rounded-xl border border-amber-300/80">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    v-model="form.is_current"
                                    type="checkbox"
                                    class="w-5 h-5 text-amber-600 rounded border-amber-300 focus:ring-amber-500"
                                />
                                <div>
                                    <span class="text-sm font-extrabold text-amber-900 flex items-center gap-1.5">
                                        Establecer como Etapa Actual (P)
                                    </span>
                                    <p class="text-xs text-amber-700 mt-0.5">
                                        Al activar esta casilla, esta fase se mostrará como la etapa activa destacada en la vista pública de transparencia con la insignia luminosa (P).
                                    </p>
                                </div>
                            </label>
                        </div>

                        <!-- Progress Slider -->
                        <div class="sm:col-span-12 space-y-2">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Porcentaje de Avance
                                </label>
                                <span class="text-sm font-black text-purple-600">{{ form.progress_percentage }}%</span>
                            </div>
                            <input
                                v-model.number="form.progress_percentage"
                                type="range"
                                min="0"
                                max="100"
                                step="5"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600"
                            />
                            <p v-if="form.errors.progress_percentage" class="text-xs text-red-600 mt-1">{{ form.errors.progress_percentage }}</p>
                        </div>
                    </div>
                </div>

                <!-- ── SECTION 3: MARCO LEGAL, FECHAS Y ARCHIVOS ──────────── -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200 space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-journal-text text-sky-600"></i> Marco Legal, Fechas y Documentación
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5">
                        <!-- Resolution Number -->
                        <div class="sm:col-span-6">
                            <label for="resolution_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Número de Resolución / Norma
                            </label>
                            <input
                                id="resolution_number"
                                v-model="form.resolution_number"
                                type="text"
                                placeholder="Ej. RVM N° 276-2019-MINEDU"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.resolution_number" class="text-xs text-red-600 mt-1">{{ form.errors.resolution_number }}</p>
                        </div>

                        <!-- Legal Basis -->
                        <div class="sm:col-span-6">
                            <label for="legal_basis" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Base Legal
                            </label>
                            <input
                                id="legal_basis"
                                v-model="form.legal_basis"
                                type="text"
                                placeholder="Ej. Ley N° 30512, D.S. N° 010-2017-MINEDU"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.legal_basis" class="text-xs text-red-600 mt-1">{{ form.errors.legal_basis }}</p>
                        </div>

                        <!-- Estimated Date -->
                        <div class="sm:col-span-4">
                            <label for="estimated_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Periodo Estimado
                            </label>
                            <input
                                id="estimated_date"
                                v-model="form.estimated_date"
                                type="text"
                                placeholder="Ej. 2026 - 2027 / II Semestre"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.estimated_date" class="text-xs text-red-600 mt-1">{{ form.errors.estimated_date }}</p>
                        </div>

                        <!-- Start Date -->
                        <div class="sm:col-span-4">
                            <label for="start_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Fecha de Inicio
                            </label>
                            <input
                                id="start_date"
                                v-model="form.start_date"
                                type="date"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.start_date" class="text-xs text-red-600 mt-1">{{ form.errors.start_date }}</p>
                        </div>

                        <!-- End Date -->
                        <div class="sm:col-span-4">
                            <label for="end_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Fecha de Culminación
                            </label>
                            <input
                                id="end_date"
                                v-model="form.end_date"
                                type="date"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.end_date" class="text-xs text-red-600 mt-1">{{ form.errors.end_date }}</p>
                        </div>

                        <!-- File Upload -->
                        <div class="sm:col-span-6">
                            <label for="file_path" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Documento / Expediente Adjunto
                            </label>
                            <input
                                id="file_path"
                                type="file"
                                accept=".pdf,.doc,.docx,.zip,.rar"
                                @change="handleFileChange"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-xl p-1"
                            />
                            <p class="text-[11px] text-gray-400 mt-1">Formatos admitidos: PDF, DOC, DOCX, ZIP (Máx. 20MB)</p>
                            <p v-if="form.errors.file_path" class="text-xs text-red-600 mt-1">{{ form.errors.file_path }}</p>
                        </div>

                        <!-- External Link -->
                        <div class="sm:col-span-6">
                            <label for="external_link" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Enlace Externo Oficial (MINEDU / Web)
                            </label>
                            <input
                                id="external_link"
                                v-model="form.external_link"
                                type="url"
                                placeholder="https://..."
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.external_link" class="text-xs text-red-600 mt-1">{{ form.errors.external_link }}</p>
                        </div>

                        <!-- Order -->
                        <div class="sm:col-span-6">
                            <label for="order" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Orden de Visualización
                            </label>
                            <input
                                id="order"
                                v-model.number="form.order"
                                type="number"
                                min="1"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            />
                            <p v-if="form.errors.order" class="text-xs text-red-600 mt-1">{{ form.errors.order }}</p>
                        </div>

                        <!-- Active Checkbox -->
                        <div class="sm:col-span-6 flex items-center pt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
                                />
                                <span class="text-sm font-semibold text-gray-800">Publicar en el portal de transparencia</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ── SECTION 4: HITOS / SUB-CONDICIONES CBC ─────────────── -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-list-check text-emerald-600"></i> Hitos / Sub-Condiciones CBC de esta Fase
                        </h2>
                        <button
                            type="button"
                            @click="addMilestone"
                            class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition-colors flex items-center gap-1"
                        >
                            <i class="bi bi-plus-circle-fill"></i> Agregar Hito / CBC
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(m, index) in milestones"
                            :key="index"
                            class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3 relative group"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-700">Hito #{{ index + 1 }}</span>
                                <button
                                    type="button"
                                    @click="removeMilestone(index)"
                                    class="text-red-500 hover:text-red-700 text-xs p-1"
                                >
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                <div class="sm:col-span-3">
                                    <input
                                        v-model="m.cbc_number"
                                        type="text"
                                        placeholder="Ej. CBC 1 o Hito 1.1"
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                    />
                                </div>
                                <div class="sm:col-span-6">
                                    <input
                                        v-model="m.name"
                                        type="text"
                                        placeholder="Nombre de la condición / hito"
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold"
                                    />
                                </div>
                                <div class="sm:col-span-3">
                                    <select
                                        v-model="m.status"
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white"
                                    >
                                        <option value="completed">Cumplido</option>
                                        <option value="in_progress">En Adecuación</option>
                                        <option value="pending">Pendiente</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-12">
                                    <input
                                        v-model="m.description"
                                        type="text"
                                        placeholder="Descripción breve del medio de verificación..."
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="milestones.length === 0"
                            class="text-center py-6 text-xs text-gray-500 bg-gray-50/60 rounded-xl border border-dashed border-gray-300"
                        >
                            No se han agregado hitos secundarios. Haga clic en "+ Agregar Hito / CBC" si desea incluir el detalle de las condiciones.
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <Link
                        :href="route('admin.licensing.index')"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-purple-600/25 transition-all flex items-center gap-2 disabled:opacity-50"
                    >
                        <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>{{ form.processing ? 'Guardando...' : 'Guardar Fase de Licenciamiento' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
