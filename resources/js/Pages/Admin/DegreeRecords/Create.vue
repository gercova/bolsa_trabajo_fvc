<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const form = useForm({
    // Datos Institución
    modular_code: '0485963',
    institution_name: 'INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO FRANCISCO VIGO CABALLERO',
    management_type: 'PÚBLICA',
    department: 'SAN MARTÍN',
    study_program: '',
    mention: '',
    formative_level: 'PROFESIONAL TÉCNICO',
    productive_family: '',

    // Datos Personales
    full_names: '',
    document_type: 'DNI',
    document_number: '',
    gender: '',
    birth_date: '',
    graduation_date: '',

    // Datos del Título
    institutional_registration_number: '',
    diploma_issue_date: '',
    minedu_registration_date: '',
    generated_title_code: '',
    file_number: '',
    registration_type: 'REGISTRO DE TÍTULOS DE EDUCACIÓN SUPERIOR TECNOLÓGICA',
    specialist_user: '',
    diploma_type: 'TÍTULO',
})

function submit() {
    form.post(route('admin.degree-records.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <AdminLayout title="Registrar Grado / Título">
        <Head title="Registrar Grado / Título - Panel Administrativo" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-award-fill text-lg"></i>
                        </span>
                        Nuevo Registro de Grado / Título
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Registra manualmente un diploma de egresado o profesional técnico titulado.
                    </p>
                </div>

                <Link :href="route('admin.degree-records.index')"
                      class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition-all">
                    <i class="bi bi-arrow-left"></i> Volver a Títulos
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Sección 1: Institución -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
                    <h2 class="text-sm font-extrabold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-building text-purple-600"></i> Datos de la Institución
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Código Modular</label>
                            <input v-model="form.modular_code"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-mono" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Nombre Institución <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.institution_name"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-medium"
                                   :class="form.errors.institution_name ? 'border-rose-400 bg-rose-50/20' : ''" />
                            <p v-if="form.errors.institution_name" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.institution_name }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipo de Gestión</label>
                            <input v-model="form.management_type"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Departamento</label>
                            <input v-model="form.department"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nivel Formativo</label>
                            <input v-model="form.formative_level"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Familia Productiva</label>
                            <input v-model="form.productive_family"
                                   type="text"
                                   placeholder="ej. ACTIVIDADES AGRARIAS, COMPUTACIÓN"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Programa de Estudios <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.study_program"
                                   type="text"
                                   placeholder="ej. REDES Y COMUNICACIONES"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-bold"
                                   :class="form.errors.study_program ? 'border-rose-400 bg-rose-50/20' : ''" />
                            <p v-if="form.errors.study_program" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.study_program }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Datos Personales -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
                    <h2 class="text-sm font-extrabold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-person-badge text-purple-600"></i> Datos Personales del Egresado
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="sm:col-span-2 lg:col-span-3">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Nombres y Apellidos Completos <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.full_names"
                                   type="text"
                                   placeholder="ej. PÉREZ GÓMEZ, JUAN CARLOS"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-bold"
                                   :class="form.errors.full_names ? 'border-rose-400 bg-rose-50/20' : ''" />
                            <p v-if="form.errors.full_names" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.full_names }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipo de Documento</label>
                            <input v-model="form.document_type"
                                   type="text"
                                   placeholder="DNI, CE, PAS..."
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Número de Documento</label>
                            <input v-model="form.document_number"
                                   type="text"
                                   placeholder="ej. 74859632"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sexo / Género</label>
                            <select v-model="form.gender"
                                    class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                <option value="">— Seleccionar —</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Fecha de Nacimiento</label>
                            <input v-model="form.birth_date"
                                   type="date"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Fecha de Egreso</label>
                            <input v-model="form.graduation_date"
                                   type="date"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Datos del Grado / Título -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
                    <h2 class="text-sm font-extrabold text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-award text-purple-600"></i> Datos del Grado / Título
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">N° Registro Institucional</label>
                            <input v-model="form.institutional_registration_number"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Fecha Emisión Diploma</label>
                            <input v-model="form.diploma_issue_date"
                                   type="date"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Fecha Registro MINEDU</label>
                            <input v-model="form.minedu_registration_date"
                                   type="date"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Código de Título Generado</label>
                            <input v-model="form.generated_title_code"
                                   type="text"
                                   placeholder="ej. T-2025-0042"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-mono font-bold text-purple-700" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Número de Expediente</label>
                            <input v-model="form.file_number"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipo de Diploma</label>
                            <input v-model="form.diploma_type"
                                   type="text"
                                   placeholder="TÍTULO, GRADO..."
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Usuario Especialista</label>
                            <input v-model="form.specialist_user"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-mono" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipo de Registro</label>
                            <input v-model="form.registration_type"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <Link :href="route('admin.degree-records.index')"
                          class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                        Cancelar
                    </Link>
                    <button type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2 disabled:opacity-60">
                        <i v-if="!form.processing" class="bi bi-check-lg text-lg"></i>
                        <span v-if="form.processing">Guardando...</span>
                        <span v-else>Guardar Título</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
