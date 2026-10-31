<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    studentRecord: {
        type: Object,
        required: true,
    },
    documentTypes: {
        type: Array,
        default: () => [],
    },
    studyPrograms: {
        type: Array,
        default: () => [],
    },
})

const r = props.studentRecord

const form = useForm({
    // Proceso académico
    record_type: r.record_type || 'ADMISION',
    academic_period: r.academic_period || '',
    study_program: r.study_program || '',
    modality: r.modality || '',
    modality_type: r.modality_type || '',
    headquarters: r.headquarters || '',
    route_type: r.route_type || '',
    shift: r.shift || 'DIURNO',
    score: r.score ?? '',
    situation: r.situation || '',
    cycle: r.cycle || '',
    enrollment_status: r.enrollment_status || '',
    period_status: r.period_status || '',

    // Datos personales
    document_type_id: r.document_type_id || (props.documentTypes.length > 0 ? props.documentTypes[0].id : null),
    document: r.document || '',
    last_name_father: r.last_name_father || '',
    last_name_mother: r.last_name_mother || '',
    names: r.names || '',
    birthdate: r.birthdate ? r.birthdate.substring(0, 10) : '',
    gender: r.gender || '',
    mother_tongue: r.mother_tongue || 'Español',
    email: r.email || '',
    phone: r.phone || '',
    pais_procedencia: r.pais_procedencia || 'PERÚ',

    // Colegio de procedencia
    institution_name_ie: r.institution_name_ie || '',
    modular_code_ie: r.modular_code_ie || '',
    institution_type_ie: r.institution_type_ie || '',
    management_type_ie: r.management_type_ie || 'PÚBLICA',
    year_graduation: r.year_graduation || '',
    region_ie: r.region_ie || '',
    province_ie: r.province_ie || '',
    district_ie: r.district_ie || '',
    ubigeo_ie: r.ubigeo_ie || '',
})

function submit() {
    form.put(route('admin.statistics.update', r.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <AdminLayout title="Editar Registro Académico">
        <Head :title="`Editar: ${studentRecord.names} - Panel Administrativo`" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full"
                              :class="studentRecord.record_type === 'ADMISION' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800'">
                            {{ studentRecord.record_type }}
                        </span>
                        <span class="text-xs text-gray-400">• ID: #{{ studentRecord.id }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </span>
                        Editar Registro Académico
                    </h1>
                </div>

                <Link :href="route('admin.statistics.index')"
                      class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition-all">
                    <i class="bi bi-arrow-left"></i> Volver a Registros
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Bloque 1: Proceso Académico -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-mortarboard-fill text-purple-600"></i> 1. Datos del Proceso Académico
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Tipo de Registro <span class="text-rose-500">*</span>
                            </label>
                            <select v-model="form.record_type"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold"
                                    :class="form.errors.record_type ? 'border-rose-400 bg-rose-50/20' : ''">
                                <option value="ADMISION">Admisión (Postulante / Ingresante)</option>
                                <option value="MATRICULA">Matrícula (Estudiante Regular)</option>
                            </select>
                            <p v-if="form.errors.record_type" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.record_type }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Período Académico <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.academic_period"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold text-purple-700"
                                   :class="form.errors.academic_period ? 'border-rose-400 bg-rose-50/20' : ''" />
                            <p v-if="form.errors.academic_period" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.academic_period }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Programa de Estudios <span class="text-rose-500">*</span>
                            </label>
                            <select v-model="form.study_program"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium"
                                    :class="form.errors.study_program ? 'border-rose-400 bg-rose-50/20' : ''">
                                <option value="">Seleccione Programa...</option>
                                <option v-for="prog in studyPrograms" :key="prog.id" :value="prog.name">
                                    {{ prog.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.study_program" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.study_program }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Modalidad
                            </label>
                            <input v-model="form.modality"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Turno
                            </label>
                            <select v-model="form.shift"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <option value="DIURNO">DIURNO</option>
                                <option value="MAÑANA">MAÑANA</option>
                                <option value="NOCHE">NOCHE</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Puntaje Obtenido
                            </label>
                            <input v-model="form.score"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                            <p v-if="form.errors.score" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.score }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Situación
                            </label>
                            <input v-model="form.situation"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Ciclo (Matrícula)
                            </label>
                            <select v-model="form.cycle"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <option value="">Seleccione ciclo...</option>
                                <option value="I">Ciclo I</option>
                                <option value="II">Ciclo II</option>
                                <option value="III">Ciclo III</option>
                                <option value="IV">Ciclo IV</option>
                                <option value="V">Ciclo V</option>
                                <option value="VI">Ciclo VI</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Sede
                            </label>
                            <input v-model="form.headquarters"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>
                    </div>
                </div>

                <!-- Bloque 2: Datos Personales -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-person-vcard text-purple-600"></i> 2. Datos Personales del Estudiante
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Tipo de Documento
                            </label>
                            <select v-model="form.document_type_id"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                <option v-for="dt in documentTypes" :key="dt.id" :value="dt.id">
                                    {{ dt.abreviation }} - {{ dt.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Número de Documento
                            </label>
                            <input v-model="form.document"
                                   type="text"
                                   maxlength="20"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Nombres <span class="text-rose-500">*</span>
                            </label>
                            <input v-model="form.names"
                                   type="text"
                                   maxlength="150"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold"
                                   :class="form.errors.names ? 'border-rose-400 bg-rose-50/20' : ''" />
                            <p v-if="form.errors.names" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.names }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Apellido Paterno
                            </label>
                            <input v-model="form.last_name_father"
                                   type="text"
                                   maxlength="100"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Apellido Materno
                            </label>
                            <input v-model="form.last_name_mother"
                                   type="text"
                                   maxlength="100"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-semibold" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Género
                            </label>
                            <select v-model="form.gender"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                                <option value="">Seleccione género...</option>
                                <option value="MASCULINO">MASCULINO</option>
                                <option value="FEMENINO">FEMENINO</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Fecha de Nacimiento
                            </label>
                            <input v-model="form.birthdate"
                                   type="date"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Lengua Materna
                            </label>
                            <input v-model="form.mother_tongue"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Teléfono / Celular
                            </label>
                            <input v-model="form.phone"
                                   type="text"
                                   maxlength="20"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Correo Electrónico
                            </label>
                            <input v-model="form.email"
                                   type="email"
                                   maxlength="150"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                            <p v-if="form.errors.email" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.email }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                País de Procedencia
                            </label>
                            <input v-model="form.pais_procedencia"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>
                    </div>
                </div>

                <!-- Bloque 3: Colegio de Procedencia -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-building-check text-purple-600"></i> 3. Institución Educativa de Procedencia
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Nombre del Colegio / I.E.
                            </label>
                            <input v-model="form.institution_name_ie"
                                   type="text"
                                   maxlength="200"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Año de Egreso Escolar
                            </label>
                            <input v-model="form.year_graduation"
                                   type="number"
                                   min="1950"
                                   max="2100"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Código Modular I.E.
                            </label>
                            <input v-model="form.modular_code_ie"
                                   type="text"
                                   maxlength="20"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-mono" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Tipo de Gestión I.E.
                            </label>
                            <select v-model="form.management_type_ie"
                                    class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                <option value="PÚBLICA">PÚBLICA</option>
                                <option value="PRIVADA">PRIVADA</option>
                                <option value="PARROQUIAL">PARROQUIAL</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Región / Departamento I.E.
                            </label>
                            <input v-model="form.region_ie"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Provincia I.E.
                            </label>
                            <input v-model="form.province_ie"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Distrito I.E.
                            </label>
                            <input v-model="form.district_ie"
                                   type="text"
                                   class="w-full text-sm border border-gray-200 rounded-xl py-2.5 px-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <Link :href="route('admin.statistics.index')"
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
