<template>
  <AdminLayout title="Editar Documento de Gestión">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Back Link -->
      <div>
        <Link
          :href="route('admin.documents.index')"
          class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors"
        >
          <i class="bi bi-arrow-left text-lg"></i>
          <span>Volver a la lista de documentos</span>
        </Link>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/40 flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm">
                <i class="bi bi-pencil-square"></i>
              </span>
              Modificar Información del Documento
            </h2>
            <p class="text-xs text-gray-500 mt-1">
              Actualice los datos o reemplace los archivos cargados para este documento de gestión.
            </p>
          </div>

          <span
            class="text-xs font-semibold px-3 py-1 rounded-full"
            :class="managementDocument.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'"
          >
            {{ managementDocument.is_active ? 'Publicado Activo' : 'Inactivo' }}
          </span>
        </div>

        <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
          <!-- Title -->
          <div class="space-y-1.5">
            <label for="title" class="block text-sm font-semibold text-gray-700">
              Título del Documento <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="title"
              v-model="form.title"
              required
              placeholder="Ej. Reglamento Interno de Trabajo 2026"
              class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-gray-50/50 focus:bg-white"
              :class="form.errors.title ? 'border-red-500 bg-red-50/30' : 'border-gray-200'"
            />
            <p v-if="form.errors.title" class="text-xs text-red-500 flex items-center gap-1 mt-1">
              <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.title }}
            </p>
          </div>

          <!-- Description -->
          <div class="space-y-1.5">
            <label for="description" class="block text-sm font-semibold text-gray-700">
              Descripción Resumida
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              placeholder="Breve resumen del contenido o propósito del documento..."
              class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-gray-50/50 focus:bg-white border-gray-200"
              :class="{ 'border-red-500 bg-red-50/30': form.errors.description }"
            ></textarea>
            <p v-if="form.errors.description" class="text-xs text-red-500 flex items-center gap-1 mt-1">
              <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.description }}
            </p>
          </div>

          <!-- Details -->
          <div class="space-y-1.5">
            <label for="details" class="block text-sm font-semibold text-gray-700">
              Detalles Adicionales / Observaciones
            </label>
            <textarea
              id="details"
              v-model="form.details"
              rows="2"
              placeholder="Notas adicionales, resolución de aprobación, o código de documento..."
              class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-gray-50/50 focus:bg-white border-gray-200"
              :class="{ 'border-red-500 bg-red-50/30': form.errors.details }"
            ></textarea>
            <p v-if="form.errors.details" class="text-xs text-red-500 flex items-center gap-1 mt-1">
              <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.details }}
            </p>
          </div>

          <!-- Validity Period -->
          <div class="space-y-1.5">
            <label for="validity_period" class="block text-sm font-semibold text-gray-700">
              Fecha / Periodo de Vigencia
            </label>
            <div class="relative">
              <input
                type="date"
                id="validity_period"
                v-model="form.validity_period"
                class="w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-gray-50/50 focus:bg-white border-gray-200"
                :class="{ 'border-red-500 bg-red-50/30': form.errors.validity_period }"
              />
            </div>
            <p v-if="form.errors.validity_period" class="text-xs text-red-500 flex items-center gap-1 mt-1">
              <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.validity_period }}
            </p>
          </div>

          <!-- Publication Status Toggle -->
          <div class="pt-2">
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
              <div>
                <h4 class="text-sm font-bold text-gray-800">Estado de Publicación</h4>
                <p class="text-xs text-gray-500">Determina si este registro es visible en el portal público de la institución.</p>
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

          <!-- Files Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <!-- 1. Main Document Field -->
            <div class="space-y-3">
              <label class="block text-sm font-semibold text-gray-700">
                Archivo Principal del Documento
              </label>

              <!-- Current file preview if exists -->
              <div
                v-if="managementDocument.file_url"
                class="bg-purple-50/60 border border-purple-200/80 rounded-2xl p-3.5 shadow-sm flex items-center justify-between gap-3"
              >
                <div class="flex items-center gap-3 overflow-hidden">
                  <div class="w-12 h-12 rounded-xl bg-white border border-purple-200 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i :class="getMainFileIcon(managementDocument.file_path)" class="text-2xl"></i>
                  </div>
                  <div class="text-left overflow-hidden">
                    <div class="flex items-center gap-1.5">
                      <span class="text-[10px] font-bold text-purple-900 uppercase">Actual</span>
                      <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-purple-200 text-purple-800 uppercase">
                        {{ getMainFileExt(managementDocument.file_path) }}
                      </span>
                    </div>
                    <p class="text-xs font-semibold text-gray-800 truncate max-w-[130px] sm:max-w-[160px] mt-0.5">
                      {{ getFileName(managementDocument.file_path) }}
                    </p>
                  </div>
                </div>

                <a
                  :href="managementDocument.file_url"
                  target="_blank"
                  class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-purple-700 bg-white border border-purple-200 hover:bg-purple-100 rounded-xl transition-all shadow-sm flex-shrink-0"
                >
                  <i class="bi bi-box-arrow-up-right"></i> Ver
                </a>
              </div>

              <!-- Replacement dropzone -->
              <div
                class="relative border-2 border-dashed rounded-2xl p-4 transition-all text-center cursor-pointer min-h-[150px] flex flex-col justify-center"
                :class="isDraggingMain ? 'border-purple-500 bg-purple-50/50' : 'border-gray-300 hover:border-purple-400 bg-gray-50/30 hover:bg-gray-50'"
                @dragover.prevent="isDraggingMain = true"
                @dragleave.prevent="isDraggingMain = false"
                @drop.prevent="onMainFileDrop"
                @click="mainFileInput.click()"
              >
                <input
                  type="file"
                  ref="mainFileInput"
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp"
                  class="hidden"
                  @change="onMainFileChange"
                />

                <!-- Empty replacement state -->
                <div v-if="!mainFile" class="space-y-1.5 py-1">
                  <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mx-auto shadow-sm">
                    <i class="bi bi-arrow-repeat text-lg"></i>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-700">
                      Reemplazar archivo principal
                    </p>
                    <p class="text-[10px] text-gray-500">
                      (Opcional) Dejar vacío para conservar el actual
                    </p>
                  </div>
                </div>

                <!-- Selected replacement state -->
                <div v-else class="p-1" @click.stop>
                  <div class="bg-white border border-purple-200 rounded-xl p-3 shadow-sm flex items-center justify-between gap-3 text-left">
                    <div class="flex items-center gap-3 overflow-hidden">
                      <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center flex-shrink-0">
                        <i :class="getMainFileIcon(mainFile.name)" class="text-xl"></i>
                      </div>
                      <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-800 truncate max-w-[130px] sm:max-w-[150px]">
                          {{ mainFile.name }}
                        </p>
                        <div class="flex items-center gap-1 mt-0.5">
                          <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                            Nuevo
                          </span>
                          <span class="text-[10px] text-gray-500">
                            {{ (mainFile.size / (1024 * 1024)).toFixed(2) }} MB
                          </span>
                        </div>
                      </div>
                    </div>

                    <button
                      type="button"
                      @click="clearMainFile"
                      class="p-1.5 text-xs text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex-shrink-0"
                      title="Quitar nuevo archivo"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <p v-if="form.errors.file_path" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.file_path }}
              </p>
            </div>

            <!-- 2. Resolution Document Field -->
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <label class="block text-sm font-semibold text-gray-700">
                  Documento de Resolución
                </label>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-100">
                  <i class="bi bi-file-earmark-pdf-fill"></i> Solo PDF
                </span>
              </div>

              <!-- Current resolution preview if exists -->
              <div
                v-if="managementDocument.resolution_document_url"
                class="bg-red-50/60 border border-red-200/80 rounded-2xl p-3.5 shadow-sm flex items-center justify-between gap-3"
              >
                <div class="flex items-center gap-3 overflow-hidden">
                  <div class="w-12 h-12 rounded-xl bg-white border border-red-200 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill text-red-600 text-2xl"></i>
                  </div>
                  <div class="text-left overflow-hidden">
                    <div class="flex items-center gap-1.5">
                      <span class="text-[10px] font-bold text-red-900 uppercase">Actual</span>
                      <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-red-200 text-red-800 uppercase">
                        PDF
                      </span>
                    </div>
                    <p class="text-xs font-semibold text-gray-800 truncate max-w-[130px] sm:max-w-[160px] mt-0.5">
                      {{ getFileName(managementDocument.resolution_document_path) }}
                    </p>
                  </div>
                </div>

                <a
                  :href="managementDocument.resolution_document_url"
                  target="_blank"
                  class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-red-700 bg-white border border-red-200 hover:bg-red-100 rounded-xl transition-all shadow-sm flex-shrink-0"
                >
                  <i class="bi bi-box-arrow-up-right"></i> Ver
                </a>
              </div>

              <!-- Replacement dropzone -->
              <div
                class="relative border-2 border-dashed rounded-2xl p-4 transition-all text-center cursor-pointer min-h-[150px] flex flex-col justify-center"
                :class="isDraggingRes ? 'border-red-500 bg-red-50/50' : 'border-gray-300 hover:border-red-400 bg-gray-50/30 hover:bg-gray-50'"
                @dragover.prevent="isDraggingRes = true"
                @dragleave.prevent="isDraggingRes = false"
                @drop.prevent="onResFileDrop"
                @click="resFileInput.click()"
              >
                <input
                  type="file"
                  ref="resFileInput"
                  accept=".pdf"
                  class="hidden"
                  @change="onResFileChange"
                />

                <!-- Empty replacement state -->
                <div v-if="!resFile" class="space-y-1.5 py-1">
                  <div class="w-9 h-9 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto shadow-sm">
                    <i class="bi bi-arrow-repeat text-lg"></i>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-700">
                      Reemplazar resolución (opcional)
                    </p>
                    <p class="text-[10px] text-gray-500">
                      Dejar vacío para conservar el actual
                    </p>
                  </div>
                </div>

                <!-- Selected replacement state -->
                <div v-else class="p-1" @click.stop>
                  <div class="bg-white border border-red-200 rounded-xl p-3 shadow-sm flex items-center justify-between gap-3 text-left">
                    <div class="flex items-center gap-3 overflow-hidden">
                      <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-file-earmark-pdf-fill text-red-600 text-xl"></i>
                      </div>
                      <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-800 truncate max-w-[130px] sm:max-w-[150px]">
                          {{ resFile.name }}
                        </p>
                        <div class="flex items-center gap-1 mt-0.5">
                          <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                            Nuevo
                          </span>
                          <span class="text-[10px] text-gray-500">
                            {{ (resFile.size / (1024 * 1024)).toFixed(2) }} MB
                          </span>
                        </div>
                      </div>
                    </div>

                    <button
                      type="button"
                      @click="clearResFile"
                      class="p-1.5 text-xs text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex-shrink-0"
                      title="Quitar nuevo archivo"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <p v-if="form.errors.resolution_document_path" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.resolution_document_path }}
              </p>
            </div>
          </div>

          <!-- Buttons -->
          <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
            <Link
              :href="route('admin.documents.index')"
              class="w-full sm:w-auto px-5 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm rounded-xl transition-all text-center"
            >
              Cancelar
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 transition-all disabled:opacity-50"
            >
              <i class="bi bi-check-circle"></i>
              <span>{{ form.processing ? 'Actualizando...' : 'Actualizar Documento' }}</span>
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
  managementDocument: {
    type: Object,
    required: true
  }
});

function formatInitialDate(d) {
  if (!d) return '';
  return String(d).substring(0, 10);
}

const form = useForm({
  _method: 'PUT',
  title: props.managementDocument.title || '',
  description: props.managementDocument.description || '',
  details: props.managementDocument.details || '',
  validity_period: formatInitialDate(props.managementDocument.validity_period),
  is_active: Boolean(props.managementDocument.is_active),
  file_path: null,
  resolution_document_path: null
});

function getFileName(path) {
  if (!path) return '';
  return path.split('/').pop();
}

function getMainFileExt(path) {
  if (!path) return 'DOC';
  const parts = path.split('.');
  return parts.length > 1 ? parts.pop().toLowerCase() : 'DOC';
}

function getMainFileIcon(path) {
  const ext = getMainFileExt(path);
  if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) return 'bi bi-file-earmark-image text-emerald-600';
  if (ext === 'pdf') return 'bi bi-file-earmark-pdf text-red-600';
  if (['doc', 'docx'].includes(ext)) return 'bi bi-file-earmark-word text-blue-600';
  if (['xls', 'xlsx'].includes(ext)) return 'bi bi-file-earmark-excel text-emerald-700';
  return 'bi bi-file-earmark-text text-purple-600';
}

// Main file upload state
const mainFileInput = ref(null);
const mainFile = ref(null);
const isDraggingMain = ref(false);

function onMainFileChange(e) {
  const f = e.target.files[0];
  if (f) {
    mainFile.value = f;
    form.file_path = f;
  }
}

function onMainFileDrop(e) {
  isDraggingMain.value = false;
  const f = e.dataTransfer.files[0];
  if (f) {
    mainFile.value = f;
    form.file_path = f;
  }
}

function clearMainFile() {
  mainFile.value = null;
  form.file_path = null;
  if (mainFileInput.value) mainFileInput.value.value = '';
}

// Resolution file upload state
const resFileInput = ref(null);
const resFile = ref(null);
const isDraggingRes = ref(false);

function onResFileChange(e) {
  const f = e.target.files[0];
  if (f) {
    resFile.value = f;
    form.resolution_document_path = f;
  }
}

function onResFileDrop(e) {
  isDraggingRes.value = false;
  const f = e.dataTransfer.files[0];
  if (f) {
    resFile.value = f;
    form.resolution_document_path = f;
  }
}

function clearResFile() {
  resFile.value = null;
  form.resolution_document_path = null;
  if (resFileInput.value) resFileInput.value.value = '';
}

function submit() {
  form.post(route('admin.documents.update', props.managementDocument.id), {
    forceFormData: true
  });
}
</script>
