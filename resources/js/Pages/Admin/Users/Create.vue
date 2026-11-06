<template>
  <AdminLayout title="Crear Usuario">
    <div class="space-y-6 max-w-5xl mx-auto">
      <!-- Breadcrumb & Top Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
            <Link :href="route('admin.users.index')" class="hover:text-purple-600 transition-colors">Usuarios</Link>
            <i class="bi bi-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-purple-600">Crear Nuevo Usuario</span>
          </div>
          <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-purple-100 text-purple-700 inline-flex">
              <i class="bi bi-person-plus-fill text-xl"></i>
            </span>
            Crear Nuevo Usuario
          </h1>
        </div>

        <div>
          <Link
            :href="route('admin.users.index')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-sm rounded-xl transition-all shadow-sm"
          >
            <i class="bi bi-arrow-left text-base"></i>
            <span>Volver a la Lista</span>
          </Link>
        </div>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-8">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Photo & Status -->
            <div class="lg:col-span-1 space-y-6">
              <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200 text-center space-y-4">
                <h3 class="font-bold text-gray-800 text-sm flex items-center justify-center gap-2">
                  <i class="bi bi-camera-fill text-purple-600"></i>
                  Foto de Perfil
                </h3>

                <!-- Image Preview Area -->
                <div class="w-36 h-36 mx-auto rounded-2xl bg-white border border-gray-200 overflow-hidden flex items-center justify-center shadow-inner relative group">
                  <img
                    v-if="photoPreview"
                    :src="photoPreview"
                    alt="Preview"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="text-gray-400 flex flex-col items-center">
                    <i class="bi bi-person-circle text-5xl"></i>
                    <span class="text-[10px] uppercase font-semibold text-gray-400 mt-1">Sin foto</span>
                  </div>
                </div>

                <!-- File Input -->
                <div>
                  <label
                    for="photo_profile"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-semibold cursor-pointer transition-colors"
                  >
                    <i class="bi bi-upload"></i>
                    Seleccionar Foto
                  </label>
                  <input
                    type="file"
                    id="photo_profile"
                    name="photo_profile"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    @change="handlePhotoChange"
                    class="hidden"
                  />
                  <p class="text-[11px] text-gray-500 mt-2">JPG, PNG o WEBP. Máximo 2MB.</p>
                  <p v-if="form.errors.photo_profile" class="text-xs text-red-600 mt-1">
                    {{ form.errors.photo_profile }}
                  </p>
                </div>
              </div>

              <!-- Active Status Card -->
              <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <label class="flex items-start gap-3 cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="form.is_active"
                    class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-5 h-5 mt-0.5"
                  />
                  <div>
                    <span class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                      <i class="bi bi-check-circle-fill text-emerald-600"></i>
                      Usuario Activo
                    </span>
                    <p class="text-xs text-gray-500 mt-0.5">
                      Los usuarios inactivos no pueden acceder a su cuenta ni iniciar sesión.
                    </p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Right Column: User Information -->
            <div class="lg:col-span-2 space-y-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- DNI -->
                <div class="sm:col-span-2 lg:col-span-1">
                  <div class="flex items-center justify-between mb-1.5">
                    <label for="dni" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                      DNI / Documento <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[11px] font-semibold text-purple-700 flex items-center gap-1">
                      <i class="bi bi-shield-check"></i> Decolecta RENIEC
                    </span>
                  </div>
                  <div class="relative flex items-center">
                    <i class="bi bi-person-vcard absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                    <input
                      type="text"
                      id="dni"
                      v-model="form.dni"
                      maxlength="8"
                      @keydown.enter.prevent="consultarDni"
                      placeholder="Ej: 12345678"
                      class="w-full pl-10 pr-24 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                      required
                    />
                    <button
                      type="button"
                      @click="consultarDni"
                      :disabled="isSearchingDni || !form.dni || form.dni.toString().trim().length !== 8"
                      class="absolute right-1.5 px-3 py-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5 cursor-pointer"
                      title="Buscar en RENIEC vía Decolecta API"
                    >
                      <i v-if="isSearchingDni" class="bi bi-arrow-repeat animate-spin"></i>
                      <i v-else class="bi bi-search"></i>
                      <span>{{ isSearchingDni ? 'Buscando...' : 'Buscar' }}</span>
                    </button>
                  </div>
                  <p v-if="form.errors.dni" class="text-xs text-red-600 mt-1">{{ form.errors.dni }}</p>

                  <!-- Decolecta Success Notification -->
                  <div
                    v-if="searchSuccessMessage"
                    class="mt-2.5 p-3 rounded-xl bg-emerald-50/80 border border-emerald-200 text-emerald-800 text-xs flex items-start gap-2.5"
                  >
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                      <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="font-bold text-emerald-900 flex items-center gap-1.5">
                        <span>Datos obtenidos con Decolecta (RENIEC)</span>
                      </p>
                      <p class="text-[11px] text-emerald-700 mt-0.5 truncate">{{ searchSuccessMessage }}</p>
                    </div>
                  </div>

                  <!-- Error Notification -->
                  <div
                    v-if="searchErrorMessage"
                    class="mt-2.5 p-3 rounded-xl bg-red-50/90 border border-red-200 text-red-800 text-xs flex items-start gap-2.5"
                  >
                    <div class="w-5 h-5 rounded-full bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                      <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="font-bold text-red-900">Consulta de DNI</p>
                      <p class="text-[11px] text-red-700 mt-0.5">{{ searchErrorMessage }}</p>
                    </div>
                    <button
                      type="button"
                      @click="searchErrorMessage = ''"
                      class="text-red-400 hover:text-red-600 p-0.5"
                      title="Cerrar mensaje"
                    >
                      <i class="bi bi-x-lg text-xs"></i>
                    </button>
                  </div>

                  <!-- Existing User Alert -->
                  <div
                    v-if="institutionalData?.existing_user"
                    class="mt-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-1.5"
                  >
                    <div class="flex items-center gap-1.5 font-bold text-amber-800">
                      <i class="bi bi-person-exclamation text-base text-amber-600"></i>
                      <span>Usuario ya registrado en la plataforma</span>
                    </div>
                    <p class="text-[11px] text-amber-700">
                      Este DNI ya pertenece al usuario <strong>{{ institutionalData.existing_user.names }}</strong> con el rol institucional <strong>{{ institutionalData.existing_user.role }}</strong>
                      <span v-if="institutionalData.existing_user.job_position"> ({{ institutionalData.existing_user.job_position }})</span>.
                    </p>
                    <div class="pt-1">
                      <Link
                        :href="route('admin.users.edit', institutionalData.existing_user.id)"
                        class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-900 hover:underline"
                      >
                        <i class="bi bi-pencil-square"></i> Ver / Editar este usuario registrado
                      </Link>
                    </div>
                  </div>

                  <!-- Student Record Institutional Match -->
                  <div
                    v-if="institutionalData?.student_record"
                    class="mt-2.5 p-3 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs space-y-2"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-1.5 font-bold text-indigo-900">
                        <i class="bi bi-mortarboard-fill text-indigo-600"></i>
                        <span>Registro de Estudiante FVC encontrado</span>
                      </div>
                      <span class="px-2 py-0.5 bg-indigo-200/70 text-indigo-800 rounded-full text-[10px] font-bold uppercase">
                        {{ institutionalData.student_record.enrollment_status || 'Estudiante' }}
                      </span>
                    </div>
                    <p class="text-[11px] text-indigo-700">
                      Programa: <strong>{{ institutionalData.student_record.study_program }}</strong>
                      <span v-if="institutionalData.student_record.academic_period"> • Periodo: {{ institutionalData.student_record.academic_period }}</span>
                      <span v-if="institutionalData.student_record.cycle"> • Ciclo: {{ institutionalData.student_record.cycle }}</span>
                    </p>
                    <button
                      type="button"
                      @click="applyStudentData"
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition-colors cursor-pointer"
                    >
                      <i class="bi bi-magic"></i>
                      <span>Autocompletar rol y datos de estudiante</span>
                    </button>
                  </div>
                </div>

                <!-- Names -->
                <div>
                  <label for="names" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Nombres y Apellidos <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <i class="bi bi-person absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                      type="text"
                      id="names"
                      v-model="form.names"
                      placeholder="Ej: Juan Carlos Pérez"
                      class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                      required
                    />
                  </div>
                  <p v-if="form.errors.names" class="text-xs text-red-600 mt-1">{{ form.errors.names }}</p>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Correo Electrónico <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <i class="bi bi-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                      type="email"
                      id="email"
                      v-model="form.email"
                      placeholder="usuario@iestpfvc.edu.pe"
                      class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                      required
                    />
                  </div>
                  <p v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</p>
                </div>

                <!-- Role -->
                <div>
                  <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Rol Institucional <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <i class="bi bi-shield-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select
                      id="role"
                      v-model="form.role"
                      class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                      required
                    >
                      <option value="" disabled>Seleccionar un rol...</option>
                      <option v-for="r in roles" :key="r.id" :value="r.name">
                        {{ r.name }}
                      </option>
                    </select>
                  </div>
                  <p v-if="form.errors.role" class="text-xs text-red-600 mt-1">{{ form.errors.role }}</p>
                </div>

                <!-- Job Position -->
                <div class="sm:col-span-2">
                  <label for="job_position" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Cargo / Puesto Laboral
                  </label>
                  <div class="relative">
                    <i class="bi bi-briefcase absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                      type="text"
                      id="job_position"
                      v-model="form.job_position"
                      placeholder="Ej: Docente de Contabilidad / Coordinador de Carrera"
                      class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    />
                  </div>
                  <p v-if="form.errors.job_position" class="text-xs text-red-600 mt-1">{{ form.errors.job_position }}</p>
                </div>

                <!-- Password and Confirmation -->
                <div>
                  <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Contraseña <span class="text-xs font-normal text-gray-400 lowercase">(opcional: por defecto DNI)</span>
                  </label>
                  <div class="relative">
                    <i class="bi bi-key absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                      name="password"
                      :type="showPassword ? 'text' : 'password'"
                      id="password"
                      v-model="form.password"
                      placeholder="Mínimo 8 caracteres"
                      class="w-full pl-10 pr-10 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    />
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                      <i :class="showPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
                    </button>
                  </div>
                  <p v-if="form.errors.password" class="text-xs text-red-600 mt-1">{{ form.errors.password }}</p>
                </div>

                <div>
                  <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Confirmar Contraseña
                  </label>
                  <div class="relative">
                    <i class="bi bi-key-fill absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                      name="password_confirmation"
                      :type="showPassword ? 'text' : 'password'"
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      placeholder="Repetir contraseña"
                      class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                    />
                  </div>
                  <p v-if="form.errors.password_confirmation" class="text-xs text-red-600 mt-1">
                    {{ form.errors.password_confirmation }}
                  </p>
                </div>

                <!-- CV Document Upload -->
                <div class="sm:col-span-2">
                  <label for="cv_file" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Currículum Vitae (CV) <span class="text-xs font-normal text-gray-400 lowercase">(PDF, DOC, DOCX - Máx 5MB)</span>
                  </label>
                  <input
                    type="file"
                    id="cv_file"
                    accept=".pdf,.doc,.docx"
                    @change="handleCvChange"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-colors border border-gray-200 rounded-xl p-1"
                  />
                  <p v-if="form.errors.cv_file" class="text-xs text-red-600 mt-1">{{ form.errors.cv_file }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Action Buttons -->
          <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
            <Link
              :href="route('admin.users.index')"
              class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors text-center"
            >
              Cancelar
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full sm:w-auto px-7 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all transform active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2"
            >
              <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
              <span>{{ form.processing ? 'Guardando...' : 'Crear Usuario' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
  roles: {
    type: Array,
    default: () => []
  }
});

const showPassword = ref(false);
const photoPreview = ref(null);

// Estados para consulta de DNI con Decolecta
const isSearchingDni = ref(false);
const searchSuccessMessage = ref('');
const searchErrorMessage = ref('');
const institutionalData = ref(null);

const form = useForm({
  dni: '',
  names: '',
  email: '',
  role: '',
  job_position: '',
  password: '',
  password_confirmation: '',
  photo_profile: null,
  cv_file: null,
  is_active: true
});

async function consultarDni() {
  searchSuccessMessage.value = '';
  searchErrorMessage.value = '';
  institutionalData.value = null;

  const cleanDni = form.dni ? form.dni.toString().trim() : '';
  if (!cleanDni) {
    searchErrorMessage.value = 'Por favor, ingrese un número de DNI.';
    return;
  }

  if (cleanDni.length !== 8) {
    searchErrorMessage.value = 'El DNI debe contener exactamente 8 dígitos.';
    return;
  }

  isSearchingDni.value = true;
  try {
    const response = await axios.get(route('admin.users.search-dni', { dni: cleanDni }));
    if (response.data && response.data.success) {
      const data = response.data.data;
      form.names = data.names || data.full_name;
      institutionalData.value = data.institutional_records || null;
      searchSuccessMessage.value = `${data.names || data.full_name}`;

      if (form.errors.dni) delete form.errors.dni;
      if (form.errors.names) delete form.errors.names;
    } else {
      searchErrorMessage.value = response.data?.message || 'No se encontraron datos para el DNI ingresado.';
    }
  } catch (error) {
    const errorMsg = error.response?.data?.message || 'Error al consultar la API de Decolecta / RENIEC.';
    searchErrorMessage.value = errorMsg;
  } finally {
    isSearchingDni.value = false;
  }
}

function applyStudentData() {
  if (!institutionalData.value?.student_record) return;
  const student = institutionalData.value.student_record;

  const studentRole = props.roles.find(
    (r) => r.name.toLowerCase() === 'estudiante' || r.name.toLowerCase() === 'student'
  );
  if (studentRole) {
    form.role = studentRole.name;
  }

  if (student.email && !form.email) {
    form.email = student.email;
  }

  if (student.study_program) {
    form.job_position = `Estudiante de ${student.study_program}`;
  }
}

function handlePhotoChange(e) {
  const file = e.target.files[0];
  if (file) {
    form.photo_profile = file;
    const reader = new FileReader();
    reader.onload = (event) => {
      photoPreview.value = event.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.photo_profile = null;
    photoPreview.value = null;
  }
}

function handleCvChange(e) {
  form.cv_file = e.target.files[0] || null;
}

function submit() {
  form.post(route('admin.users.store'), {
    forceFormData: true,
    preserveScroll: true
  });
}
</script>
