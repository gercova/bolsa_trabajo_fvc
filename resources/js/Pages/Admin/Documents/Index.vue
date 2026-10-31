<template>
  <AdminLayout title="Documentos de Gestión">
    <div class="space-y-6">
      <!-- Breadcrumb & Top Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-purple-100 text-purple-700 inline-flex">
              <i class="bi bi-folder-symlink-fill text-xl"></i>
            </span>
            Documentos de Gestión Institucional
          </h1>
          <p class="text-sm text-gray-500 mt-1">
            Administra los instrumentos normativos, reglamentos, planes operativos y resoluciones oficiales.
          </p>
        </div>

        <div>
          <Link
            :href="route('admin.documents.create')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all transform active:scale-95"
          >
            <i class="bi bi-plus-lg text-base"></i>
            <span>Nuevo Documento</span>
          </Link>
        </div>
      </div>

      <!-- Quick Metrics Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Documentos</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ documents.total ?? 0 }}</h3>
            <span class="text-xs text-purple-600 font-medium">Instrumentos normativos</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
            <i class="bi bi-folder2-open"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Documentos Activos</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ activeCount }}</h3>
            <span class="text-xs text-emerald-700 font-medium">Publicados en el portal</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Con Resolución</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ withResolutionCount }}</h3>
            <span class="text-xs text-blue-700 font-medium">Respaldados formalmente</span>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
            <i class="bi bi-file-earmark-check-fill"></i>
          </div>
        </div>
      </div>

      <!-- Filters Bar -->
      <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form @submit.prevent="applyFilters" class="flex flex-col sm:flex-row items-center gap-3">
          <div class="relative w-full sm:max-w-md">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="text"
              v-model="filters.search"
              placeholder="Buscar por título, descripción o detalles..."
              class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            />
          </div>

          <div class="w-full sm:w-56">
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="w-full py-2.5 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los Estados</option>
              <option value="active">Activos</option>
              <option value="inactive">Inactivos</option>
            </select>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto">
            <button
              type="submit"
              class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-xl transition-colors flex items-center gap-1.5"
            >
              <i class="bi bi-funnel-fill text-xs"></i>
              Filtrar
            </button>
            <button
              v-if="filters.search || filters.status"
              type="button"
              @click="clearFilters"
              class="px-3.5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all flex items-center gap-1.5"
              title="Limpiar filtros"
            >
              <i class="bi bi-x-circle text-gray-500"></i>
              Limpiar
            </button>
          </div>
        </form>
      </div>

      <!-- Table Card -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4">Documento</th>
                <th class="px-6 py-4">Archivos Adjuntos</th>
                <th class="px-6 py-4">Vigencia</th>
                <th class="px-6 py-4 text-center">Estado</th>
                <th class="px-6 py-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              <tr
                v-for="doc in documents.data"
                :key="doc.id"
                class="hover:bg-purple-50/20 transition-colors"
              >
                <!-- Document Info -->
                <td class="px-6 py-4">
                  <div class="font-bold text-gray-900 text-base max-w-md truncate">
                    {{ doc.title }}
                  </div>
                  <p v-if="doc.description" class="text-xs text-gray-500 line-clamp-2 mt-0.5">
                    {{ doc.description }}
                  </p>
                  <p v-if="doc.details" class="text-[11px] text-gray-400 line-clamp-1 mt-0.5 italic">
                    <i class="bi bi-info-circle mr-0.5"></i> {{ doc.details }}
                  </p>
                </td>

                <!-- Files -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col gap-1.5 items-start">
                    <!-- Main File -->
                    <a
                      v-if="doc.file_url"
                      :href="doc.file_url"
                      target="_blank"
                      class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg border border-gray-200 bg-gray-50 hover:bg-purple-50 hover:border-purple-200 text-gray-700 hover:text-purple-700 text-xs font-medium transition-all group"
                    >
                      <i :class="getFileIcon(doc.file_path)" class="text-sm group-hover:scale-110 transition-transform"></i>
                      <span class="uppercase tracking-wider font-semibold text-[10px]">{{ getFileExtension(doc.file_path) }}</span>
                      <i class="bi bi-box-arrow-up-right text-[10px] text-gray-400 group-hover:text-purple-600"></i>
                    </a>
                    <span v-else class="text-xs text-gray-400 italic">Sin archivo principal</span>

                    <!-- Resolution File -->
                    <a
                      v-if="doc.resolution_document_url"
                      :href="doc.resolution_document_url"
                      target="_blank"
                      class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-[11px] font-semibold transition-all"
                    >
                      <i class="bi bi-file-earmark-pdf-fill text-red-600"></i>
                      <span>Resolución PDF</span>
                      <i class="bi bi-box-arrow-up-right text-[9px] opacity-70"></i>
                    </a>
                  </div>
                </td>

                <!-- Validity Period -->
                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                  <span
                    v-if="doc.validity_period"
                    class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200/80 px-2.5 py-1 rounded-full font-medium"
                  >
                    <i class="bi bi-calendar-event text-xs"></i>
                    {{ formatDate(doc.validity_period) }}
                  </span>
                  <span v-else class="text-gray-400 italic">Sin fecha de vigencia</span>
                </td>

                <!-- Status Toggle -->
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <button
                    type="button"
                    @click="toggleStatus(doc)"
                    title="Haz clic para alternar estado"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-all shadow-sm"
                    :class="doc.is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full"
                      :class="doc.is_active ? 'bg-emerald-500' : 'bg-gray-400'"
                    ></span>
                    {{ doc.is_active ? 'Activo' : 'Inactivo' }}
                  </button>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a
                      v-if="doc.file_url"
                      :href="doc.file_url"
                      target="_blank"
                      title="Ver / Descargar archivo"
                      class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition-colors"
                    >
                      <i class="bi bi-eye text-lg"></i>
                    </a>

                    <Link
                      :href="route('admin.documents.edit', doc.id)"
                      title="Editar documento"
                      class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors"
                    >
                      <i class="bi bi-pencil-square text-lg"></i>
                    </Link>

                    <button
                      type="button"
                      @click="openDeleteModal(doc)"
                      title="Eliminar documento"
                      class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
                    >
                      <i class="bi bi-trash text-lg"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!documents.data || documents.data.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                  <div class="max-w-sm mx-auto flex flex-col items-center justify-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500">
                      <i class="bi bi-folder2-open text-3xl"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">No se encontraron documentos</h3>
                    <p class="text-xs text-gray-500">
                      {{ filters.search || filters.status ? 'No hay resultados para los filtros seleccionados.' : 'Comience registrando un nuevo documento de gestión.' }}
                    </p>
                    <Link
                      v-if="!filters.search && !filters.status"
                      :href="route('admin.documents.create')"
                      class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs rounded-xl transition-all shadow-sm"
                    >
                      <i class="bi bi-plus-lg"></i> Crear Primer Documento
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="documents.links && documents.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
          <div>
            Mostrando <span class="font-bold">{{ documents.from ?? 0 }}</span> a <span class="font-bold">{{ documents.to ?? 0 }}</span> de <span class="font-bold">{{ documents.total ?? 0 }}</span> documentos
          </div>
          <div class="flex items-center gap-1">
            <template v-for="(link, i) in documents.links" :key="i">
              <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors"
                :class="link.active ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100'"
              />
              <span
                v-else
                v-html="link.label"
                class="px-3 py-1.5 rounded-lg border text-xs font-medium text-gray-400 border-gray-100 bg-gray-50 opacity-60"
              />
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="deleteModal.open"
      class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
    >
      <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center space-y-4"
        @click.stop
      >
        <div class="w-16 h-16 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center">
          <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900">¿Eliminar documento de gestión?</h3>
          <p class="text-xs text-gray-500 mt-1">
            Esta acción eliminará permanentemente <strong class="text-gray-700">{{ deleteModal.title }}</strong> y sus archivos adjuntos.
          </p>
        </div>
        <div class="flex gap-3 pt-2">
          <button
            type="button"
            @click="deleteModal.open = false"
            class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
          >
            Cancelar
          </button>
          <button
            type="button"
            @click="confirmDelete"
            class="flex-1 py-2.5 bg-red-600 text-white font-semibold text-sm rounded-xl hover:bg-red-700 transition-colors"
          >
            Sí, Eliminar
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
  documents: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  filters: {
    type: Object,
    default: () => ({})
  }
});

const filters = ref({
  search: props.filters?.search || '',
  status: props.filters?.status || ''
});

const activeCount = computed(() => {
  if (!props.documents?.data) return 0;
  return props.documents.data.filter(d => d.is_active).length;
});

const withResolutionCount = computed(() => {
  if (!props.documents?.data) return 0;
  return props.documents.data.filter(d => Boolean(d.resolution_document_path)).length;
});

function applyFilters() {
  const payload = { ...filters.value };
  Object.keys(payload).forEach(key => {
    if (payload[key] === '' || payload[key] === null || payload[key] === undefined) {
      delete payload[key];
    }
  });

  router.get(route('admin.documents.index'), payload, {
    preserveState: true,
    replace: true
  });
}

function clearFilters() {
  filters.value = { search: '', status: '' };
  router.get(route('admin.documents.index'), {}, {
    preserveState: true,
    replace: true
  });
}

function formatDate(dateStr) {
  if (!dateStr) return 'N/A';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function getFileExtension(path) {
  if (!path) return 'DOC';
  const parts = path.split('.');
  return parts.length > 1 ? parts.pop().toLowerCase() : 'DOC';
}

function getFileIcon(path) {
  const ext = getFileExtension(path);
  if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
    return 'bi bi-file-earmark-image text-emerald-600';
  } else if (ext === 'pdf') {
    return 'bi bi-file-earmark-pdf text-red-600';
  } else if (['doc', 'docx'].includes(ext)) {
    return 'bi bi-file-earmark-word text-blue-600';
  } else if (['xls', 'xlsx'].includes(ext)) {
    return 'bi bi-file-earmark-excel text-emerald-700';
  } else if (['ppt', 'pptx'].includes(ext)) {
    return 'bi bi-file-earmark-slides text-amber-600';
  }
  return 'bi bi-file-earmark-text text-purple-600';
}

function toggleStatus(doc) {
  router.patch(route('admin.documents.toggle-status', doc.id), {}, {
    preserveScroll: true
  });
}

// Delete Confirmation
const deleteModal = ref({
  open: false,
  id: null,
  title: ''
});

function openDeleteModal(doc) {
  deleteModal.value = {
    open: true,
    id: doc.id,
    title: doc.title
  };
}

function confirmDelete() {
  if (!deleteModal.value.id) return;
  router.delete(route('admin.documents.destroy', deleteModal.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      deleteModal.value.open = false;
    }
  });
}
</script>
