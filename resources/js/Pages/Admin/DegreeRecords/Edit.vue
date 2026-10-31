<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    degreeRecord: {
        type: Object,
        required: true,
    },
})

const r = props.degreeRecord

const form = useForm({
    // Datos Institución
    modular_code: r.modular_code || '',
    institution_name: r.institution_name || '',
    management_type: r.management_type || '',
    department: r.department || '',
    study_program: r.study_program || '',
    mention: r.mention || '',
    formative_level: r.formative_level || '',
    productive_family: r.productive_family || '',

    // Datos Personales
    full_names: r.full_names || '',
    document_type: r.document_type || '',
    document_number: r.document_number || '',
    gender: r.gender || '',
    birth_date: r.birth_date ? r.birth_date.substring(0, 10) : '',
    graduation_date: r.graduation_date ? r.graduation_date.substring(0, 10) : '',

    // Datos del Título
    institutional_registration_number: r.institutional_registration_number || '',
    diploma_issue_date: r.diploma_issue_date ? r.diploma_issue_date.substring(0, 10) : '',
    minedu_registration_date: r.minedu_registration_date ? r.minedu_registration_date.substring(0, 10) : '',
    generated_title_code: r.generated_title_code || '',
    file_number: r.file_number || '',
    registration_type: r.registration_type || '',
    specialist_user: r.specialist_user || '',
    diploma_type: r.diploma_type || '',
})

function submit() {
    form.put(route('admin.degree-records.update', r.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <AdminLayout title="Editar Grado / Título">
        <Head :title="`Editar: ${degreeRecord.full_names} - Panel Administrativo`" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800">
                            {{ degreeRecord.diploma_type || 'TÍTULO' }}
                        </span>
                        <span class="text-xs text-gray-400">• ID: #{{ degreeRecord.id }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </span>
                        Editar Registro de Grado / Título
                    </h1>
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
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Programa de Estudios <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.study_program"
                                   type="text"
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
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-bold"
                                   :class="form.errors.full_names ? 'border-rose-400 bg-rose-50/20' : ''" />
                            <p v-if="form.errors.full_names" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.full_names }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipo de Documento</label>
                            <input v-model="form.document_type"
                                   type="text"
                                   class="w-full text-xs border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Número de Documento</label>
                            <input v-model="form.document_number"
                                   type="text"
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
                        <span v-if="form.processing">Actualizando...</span>
                        <span v-else>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
