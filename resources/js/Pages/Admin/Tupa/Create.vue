<template>
  <AdminLayout title="Registrar Nuevo TUPA">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Top Navigation -->
      <div class="flex items-center justify-between">
        <Link
          :href="route('admin.tupa.index')"
          class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors"
        >
          <i class="bi bi-arrow-left text-lg"></i>
          <span>Volver al listado</span>
        </Link>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
        <!-- Informative Banner -->
        <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
          <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
            <i class="bi bi-file-earmark-plus-fill text-xl"></i>
          </div>
          <div>
            <h3 class="text-sm font-bold text-gray-900">Nuevo Registro de Documento TUPA</h3>
            <p class="text-xs text-gray-500 mt-0.5">
              Sube el documento PDF del TUPA institucional e ingresa el periodo de vigencia correspondiente.
            </p>
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2 space-y-1.5">
              <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                Título del Documento TUPA <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <i class="bi bi-card-heading absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                <input
                  type="text"
                  id="title"
                  v-model="form.title"
                  placeholder="Ej: Texto Único de Procedimientos Administrativos - TUPA 2026"
                  required
                  class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                  :class="{ 'border-red-500 bg-red-50/20': form.errors.title }"
                />
              </div>
              <p v-if="form.errors.title" class="text-xs text-red-500 font-medium">
                {{ form.errors.title }}
              </p>
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2 space-y-1.5">
              <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                Descripción / Resumen Informativo <span class="text-red-500">*</span>
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                placeholder="Ingrese una descripción general del contenido, resolución de aprobación o marco legal del TUPA..."
                required
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                :class="{ 'border-red-500 bg-red-50/20': form.errors.description }"
              ></textarea>
              <p v-if="form.errors.description" class="text-xs text-red-500 font-medium">
                {{ form.errors.description }}
              </p>
            </div>

            <!-- Archivo PDF -->
            <div class="md:col-span-2 space-y-3 pt-2">
              <div class="flex items-center justify-between">
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                  Archivo Documento PDF del TUPA <span class="text-red-500">*</span>
                </label>
                <span class="text-xs text-gray-400 font-medium">Solo archivos .PDF (Máx. 20MB)</span>
              </div>

              <!-- Drag and Drop Dropzone -->
              <div
                class="border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-300 relative cursor-pointer group"
                :class="{
                  'border-purple-500 bg-purple-50/50 shadow-md ring-4 ring-purple-100': isDragging,
                  'border-emerald-400 bg-emerald-50/30': fileSelected && !fileError,
                  'border-red-400 bg-red-50/30': fileError,
                  'border-gray-200 hover:border-purple-400 bg-gray-50/40 hover:bg-purple-50/20': !isDragging && !fileSelected
                }"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="onFileDrop"
                @click="fileInputRef.click()"
              >
                <input
                  type="file"
                  ref="fileInputRef"
                  accept="application/pdf"
                  class="hidden"
                  @change="onFileChange"
                />

                <!-- Estado sin archivo -->
                <div v-if="!fileSelected" class="space-y-3 py-2">
                  <div class="w-14 h-14 mx-auto bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="bi bi-cloud-arrow-up-fill text-3xl"></i>
                  </div>
                  <div>
                    <p class="text-sm font-extrabold text-gray-800">
                      <span class="text-purple-600 underline">Haz clic para seleccionar</span> o arrastra el archivo PDF aquí
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                      El documento será almacenado y disponible para la visualización del público.
                    </p>
                  </div>
                  <div class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                    <i class="bi bi-filetype-pdf text-red-500"></i> Documento Formato PDF (Máx. 20MB)
                  </div>
                </div>

                <!-- Estado con archivo seleccionado -->
                <div v-else class="flex flex-col sm:flex-row items-center justify-between p-4 bg-white rounded-xl border border-emerald-200 shadow-sm gap-4 text-left" @click.stop>
                  <div class="flex items-center gap-3.5 min-w-0 w-full sm:w-auto">
                    <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                      <i class="bi bi-file-earmark-check-fill text-2xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Archivo Seleccionado
                      </span>
                      <p class="text-sm font-bold text-gray-900 truncate mt-0.5 max-w-xs sm:max-w-md">
                        {{ fileName }}
                      </p>
                      <p class="text-xs text-gray-500">{{ fileSizeFormatted }}</p>
                    </div>
                  </div>

                  <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button
                      type="button"
                      v-if="filePreviewUrl"
                      @click="showPdfPreview = true"
                      class="px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5"
                    >
                      <i class="bi bi-eye"></i> Previsualizar
                    </button>

                    <button
                      type="button"
                      @click="clearFile"
                      class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-xs font-bold transition-colors"
                      title="Quitar archivo"
                    >
                      <i class="bi bi-trash3 text-sm"></i>
                    </button>
                  </div>
                </div>
              </div>

              <p v-if="fileError" class="text-xs text-red-500 font-semibold flex items-center gap-1">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ fileError }}</span>
              </p>
              <p v-if="form.errors.file_path" class="text-xs text-red-500 font-medium">
                {{ form.errors.file_path }}
              </p>
            </div>

            <!-- Fecha Inicio Vigencia -->
            <div class="space-y-1.5">
              <label for="effective_start_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                Fecha Inicio de Vigencia <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <i class="bi bi-calendar-event absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                <input
                  type="date"
                  id="effective_start_date"
                  v-model="form.effective_start_date"
                  required
                  class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                  :class="{ 'border-red-500 bg-red-50/20': form.errors.effective_start_date }"
                />
              </div>
              <p v-if="form.errors.effective_start_date" class="text-xs text-red-500 font-medium">
                {{ form.errors.effective_start_date }}
              </p>
            </div>

            <!-- Fecha Fin Vigencia -->
            <div class="space-y-1.5">
              <label for="effective_end_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                Fecha Fin de Vigencia (Opcional)
              </label>
              <div class="relative">
                <i class="bi bi-calendar-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                <input
                  type="date"
                  id="effective_end_date"
                  v-model="form.effective_end_date"
                  class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                  :class="{ 'border-red-500 bg-red-50/20': form.errors.effective_end_date }"
                />
              </div>
              <p class="text-[11px] text-gray-400">Dejar en blanco si la vigencia es indefinida.</p>
              <p v-if="form.errors.effective_end_date" class="text-xs text-red-500 font-medium">
                {{ form.errors.effective_end_date }}
              </p>
            </div>

            <!-- Estado Activo Toggle -->
            <div class="md:col-span-2 pt-2">
              <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-bold text-gray-800">Estado de Publicación</h4>
                  <p class="text-xs text-gray-500">Publicar como registro activo inmediatamente después de guardar.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="form.is_active"
                    class="sr-only peer"
                  />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <Link
              :href="route('admin.tupa.index')"
              class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2 disabled:opacity-50"
            >
              <i class="bi bi-check-lg text-lg"></i>
              <span>{{ form.processing ? 'Guardando...' : 'Guardar TUPA' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- PDF Modal for new upload preview -->
    <div
      v-if="showPdfPreview"
      class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
    >
      <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden"
        @click.stop
      >
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-900 text-white">
          <div class="flex items-center gap-3">
            <i class="bi bi-file-earmark-pdf text-red-400 text-xl"></i>
            <h3 class="font-bold text-sm sm:text-base truncate max-w-md">Vista Previa: {{ fileName }}</h3>
          </div>
          <button
            type="button"
            @click="showPdfPreview = false"
            class="text-gray-400 hover:text-white p-1 rounded-lg"
          >
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>
        <div class="flex-1 bg-gray-100 relative">
          <iframe :src="filePreviewUrl" class="w-full h-full border-none"></iframe>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const currentYear = new Date().getFullYear();

const form = useForm({
  title: '',
  description: '',
  file_path: null,
  effective_start_date: `${currentYear}-01-01`,
  effective_end_date: '',
  is_active: true
});

const fileInputRef = ref(null);
const isDragging = ref(false);
const fileSelected = ref(false);
const fileName = ref('');
const fileSizeFormatted = ref('');
const filePreviewUrl = ref('');
const fileError = ref('');
const showPdfPreview = ref(false);

function validateAndSetFile(file) {
  fileError.value = '';
  if (!file) return;

  if (file.type !== 'application/pdf') {
    fileError.value = 'El archivo debe ser un documento PDF válido.';
    return;
  }

  // 20 MB max
  if (file.size > 20 * 1024 * 1024) {
    fileError.value = 'El archivo supera el tamaño máximo permitido de 20MB.';
    return;
  }

  form.file_path = file;
  fileSelected.value = true;
  fileName.value = file.name;
  fileSizeFormatted.value = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
  filePreviewUrl.value = URL.createObjectURL(file);
}

function onFileChange(e) {
  const file = e.target.files[0];
  validateAndSetFile(file);
}

function onFileDrop(e) {
  isDragging.value = false;
  const file = e.dataTransfer.files[0];
  validateAndSetFile(file);
}

function clearFile() {
  form.file_path = null;
  fileSelected.value = false;
  fileName.value = '';
  fileSizeFormatted.value = '';
  if (filePreviewUrl.value) {
    URL.revokeObjectURL(filePreviewUrl.value);
    filePreviewUrl.value = '';
  }
  fileError.value = '';
  if (fileInputRef.value) {
    fileInputRef.value.value = '';
  }
}

function submit() {
  form.post(route('admin.tupa.store'), {
    forceFormData: true
  });
}
</script>
