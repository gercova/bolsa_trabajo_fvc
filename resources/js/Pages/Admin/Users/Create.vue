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
                <div>
                  <label for="dni" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    DNI / Documento <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <i class="bi bi-person-vcard absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                      type="text"
                      id="dni"
                      v-model="form.dni"
                      maxlength="15"
                      placeholder="Ej: 12345678"
                      class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                      required
                    />
                  </div>
                  <p v-if="form.errors.dni" class="text-xs text-red-600 mt-1">{{ form.errors.dni }}</p>
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
