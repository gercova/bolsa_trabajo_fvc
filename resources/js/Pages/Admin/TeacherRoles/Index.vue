<template>
  <AdminLayout title="Plana Docente">
    <div class="space-y-6">
      <!-- Top Bar / Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
            <Link :href="route('admin.users.index')" class="hover:text-purple-600 transition-colors">Usuarios</Link>
            <i class="bi bi-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-purple-600">Plana Docente</span>
          </div>
          <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-purple-100 text-purple-700 inline-flex">
              <i class="bi bi-person-workspace text-xl"></i>
            </span>
            Plana Docente y Asignaciones
          </h1>
          <p class="text-sm text-gray-500 mt-1">
            Gestiona la asignación académica de docentes a los diferentes programas de estudio.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="route('admin.users.index')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-sm rounded-xl transition-all shadow-sm"
          >
            <i class="bi bi-people"></i>
            <span>Ver Usuarios</span>
          </Link>
          <button
            type="button"
            @click="openCreateModal"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all transform active:scale-95"
          >
            <i class="bi bi-plus-lg text-base"></i>
            <span>Nueva Asignación</span>
          </button>
        </div>
      </div>

      <!-- Quick Stats Cards -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Docentes</p>
            <h3 class="text-2xl font-black text-purple-700 mt-1">{{ totalTeachersCount }}</h3>
            <span class="text-xs text-purple-600 font-medium">Asignados</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
            <i class="bi bi-person-badge"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Coordinadores</p>
            <h3 class="text-2xl font-black text-blue-700 mt-1">{{ totalCoordinatorsCount }}</h3>
            <span class="text-xs text-blue-600 font-medium">Responsables</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
            <i class="bi bi-star-fill text-amber-500"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Programas</p>
            <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ programs.length }}</h3>
            <span class="text-xs text-emerald-600 font-medium">Con docentes</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
            <i class="bi bi-mortarboard-fill"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Activas</p>
            <h3 class="text-2xl font-black text-orange-600 mt-1">{{ activeCount }}</h3>
            <span class="text-xs text-orange-600 font-medium">Asignaciones</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
            <i class="bi bi-check2-circle"></i>
          </div>
        </div>
      </div>

      <!-- Search & Filters Bar -->
      <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form @submit.prevent="applyFilters" class="flex flex-col sm:flex-row items-center gap-3">
          <!-- Text search -->
          <div class="flex-1 relative w-full">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="text"
              v-model="filters.search"
              placeholder="Buscar por nombre de docente, DNI o especialidad..."
              class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Program filter -->
          <div class="w-full sm:w-64">
            <select
              v-model="filters.program_id"
              @change="applyFilters"
              class="w-full py-2.5 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los programas</option>
              <option v-for="p in programs" :key="p.id" :value="p.id">
                {{ p.name }}
              </option>
            </select>
          </div>

          <!-- Coordinator only toggle -->
          <label class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer text-sm font-medium text-gray-700 hover:bg-gray-100 select-none whitespace-nowrap">
            <input
              type="checkbox"
              v-model="filters.only_coordinators"
              @change="applyFilters"
              class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4"
            />
            <span>Solo Coordinadores</span>
          </label>

          <div class="flex items-center gap-2 w-full sm:w-auto">
            <button
              type="submit"
              class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium text-sm rounded-xl transition-colors flex items-center gap-1.5"
            >
              <i class="bi bi-funnel-fill text-xs"></i>
              Filtrar
            </button>
            <button
              v-if="filters.search || filters.program_id || filters.only_coordinators"
              type="button"
              @click="clearFilters"
              class="px-3.5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all"
              title="Limpiar filtros"
            >
              <i class="bi bi-x-circle text-gray-500"></i>
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
                <th class="px-6 py-4">Docente</th>
                <th class="px-6 py-4">Programa de Estudio</th>
                <th class="px-6 py-4">Especialidad</th>
                <th class="px-6 py-4 text-center">Coordinador</th>
                <th class="px-6 py-4 text-center">Estado</th>
                <th class="px-6 py-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              <tr
                v-for="detail in details.data"
                :key="detail.id"
                class="hover:bg-gray-50/60 transition-colors"
              >
                <!-- Teacher -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm overflow-hidden flex-shrink-0 border border-purple-200">
                      <img
                        v-if="detail.user?.photo_profile"
                        :src="getStorageUrl(detail.user.photo_profile)"
                        :alt="detail.user.names"
                        class="w-full h-full object-cover"
                      />
                      <span v-else>{{ getInitials(detail.user?.names) }}</span>
                    </div>
                    <div>
                      <p class="font-bold text-gray-900">{{ detail.user?.names ?? 'Docente no encontrado' }}</p>
                      <p class="text-xs text-gray-500">DNI: {{ detail.user?.dni ?? '—' }}</p>
                    </div>
                  </div>
                </td>

                <!-- Study Program -->
                <td class="px-6 py-4">
                  <span
                    v-if="detail.program"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-100"
                  >
                    <i class="bi bi-mortarboard"></i>
                    {{ detail.program.name }}
                  </span>
                  <span v-else class="text-xs text-gray-400 italic">Sin programa asignado</span>
                </td>

                <!-- Specialty -->
                <td class="px-6 py-4 text-gray-700 text-xs">
                  {{ detail.specialty || '—' }}
                </td>

                <!-- Coordinator -->
                <td class="px-6 py-4 text-center">
                  <span
                    v-if="detail.is_coordinator"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200"
                  >
                    <i class="bi bi-star-fill text-amber-500 text-[10px]"></i>
                    Coordinador
                  </span>
                  <span v-else class="text-gray-300 text-xs">—</span>
                </td>

                <!-- Status Switch -->
                <td class="px-6 py-4 text-center">
                  <button
                    type="button"
                    @click="toggleStatus(detail)"
                    :class="detail.is_active ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-600'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                    :title="detail.is_active ? 'Desactivar asignación' : 'Activar asignación'"
                  >
                    <span
                      :class="detail.is_active ? 'translate-x-6 bg-white' : 'translate-x-1 bg-white'"
                      class="inline-block h-4 w-4 transform rounded-full transition-transform"
                    />
                  </button>
                  <span class="block text-[11px] font-medium mt-0.5" :class="detail.is_active ? 'text-emerald-700' : 'text-gray-400'">
                    {{ detail.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button
                      type="button"
                      @click="openEditModal(detail)"
                      class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                      title="Editar Asignación"
                    >
                      <i class="bi bi-pencil-square text-base"></i>
                    </button>
                    <button
                      type="button"
                      @click="openDeleteModal(detail)"
                      class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                      title="Eliminar Asignación"
                    >
                      <i class="bi bi-trash-fill text-base"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!details.data || details.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                  <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-400 mb-3">
                    <i class="bi bi-person-workspace"></i>
                  </div>
                  <h4 class="font-bold text-gray-700">No se encontraron asignaciones de docentes</h4>
                  <p class="text-xs text-gray-400 mt-1">Crea una nueva asignación o ajusta los filtros.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="details.links && details.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
          <p class="text-xs text-gray-500">
            Mostrando del <span class="font-medium text-gray-700">{{ details.from ?? 0 }}</span> al
            <span class="font-medium text-gray-700">{{ details.to ?? 0 }}</span> de
            <span class="font-medium text-gray-700">{{ details.total ?? 0 }}</span> asignaciones
          </p>

          <div class="flex flex-wrap gap-1">
            <template v-for="(link, i) in details.links" :key="i">
              <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                :class="link.active ? 'bg-purple-600 text-white font-bold' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'"
                class="px-3 py-1.5 text-xs rounded-lg transition-colors"
              />
              <span
                v-else
                v-html="link.label"
                class="px-3 py-1.5 text-xs rounded-lg text-gray-400 bg-gray-100"
              />
            </template>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── MODAL: CREAR / EDITAR ASIGNACIÓN DE DOCENTE ─────────────────── -->
    <div
      v-if="modal.open"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-scale-up border border-gray-100">
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg">
              <i :class="modal.isEdit ? 'bi bi-pencil-square' : 'bi bi-person-plus-fill'"></i>
            </div>
            <div>
              <h3 class="text-base font-bold text-gray-900">
                {{ modal.isEdit ? 'Editar Asignación Docente' : 'Nueva Asignación Docente' }}
              </h3>
              <p class="text-xs text-gray-500">Asigna un docente a su programa y especialidad</p>
            </div>
          </div>
          <button
            type="button"
            @click="modal.open = false"
            class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitModal" class="space-y-4">
          <!-- Select Teacher -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Docente <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.user_id"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              required
            >
              <option value="" disabled>Selecciona un docente...</option>
              <option v-for="t in teachers" :key="t.id" :value="t.id">
                {{ t.names }} (DNI: {{ t.dni }})
              </option>
            </select>
            <p v-if="form.errors.user_id" class="text-xs text-red-600 mt-1">{{ form.errors.user_id }}</p>
          </div>

          <!-- Select Program -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Programa de Estudio
            </label>
            <select
              v-model="form.program_id"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            >
              <option :value="null">Sin programa específico</option>
              <option v-for="p in programs" :key="p.id" :value="p.id">
                {{ p.name }}
              </option>
            </select>
            <p v-if="form.errors.program_id" class="text-xs text-red-600 mt-1">{{ form.errors.program_id }}</p>
          </div>

          <!-- Specialty -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Especialidad o Área Temática
            </label>
            <input
              type="text"
              v-model="form.specialty"
              placeholder="Ej: Desarrollo Web, Mecánica de Suelos, Contabilidad de Costos"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            />
            <p v-if="form.errors.specialty" class="text-xs text-red-600 mt-1">{{ form.errors.specialty }}</p>
          </div>

          <!-- Checkboxes: Coordinator and Active -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
            <label class="flex items-center gap-2.5 p-3 rounded-xl bg-amber-50/70 border border-amber-200 cursor-pointer">
              <input
                type="checkbox"
                v-model="form.is_coordinator"
                class="rounded border-gray-300 text-amber-600 focus:ring-amber-500 w-4 h-4"
              />
              <span class="text-xs font-bold text-amber-900 flex items-center gap-1">
                <i class="bi bi-star-fill text-amber-500"></i>
                Es Coordinador
              </span>
            </label>

            <label class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200 cursor-pointer">
              <input
                type="checkbox"
                v-model="form.is_active"
                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4"
              />
              <span class="text-xs font-bold text-gray-800 flex items-center gap-1">
                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                Asignación Activa
              </span>
            </label>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3 pt-3 border-t border-gray-100">
            <button
              type="button"
              @click="modal.open = false"
              class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white font-semibold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2"
            >
              <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
              <span>{{ modal.isEdit ? 'Actualizar' : 'Guardar Asignación' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ─── MODAL: ELIMINAR ASIGNACIÓN ─────────────────────────────────── -->
    <div
      v-if="deleteModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4 animate-scale-up border border-gray-100 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-2xl">
          <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900">¿Eliminar esta asignación?</h3>
          <p class="text-xs text-gray-500 mt-1">
            Se desvinculará a <strong class="text-gray-800">{{ deleteModal.teacherName }}</strong> del programa.
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
            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition-colors shadow-md"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
  details: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  programs: {
    type: Array,
    default: () => []
  },
  teachers: {
    type: Array,
    default: () => []
  },
  roles: {
    type: Array,
    default: () => []
  },
  search: {
    type: String,
    default: ''
  },
  programId: {
    type: [Number, String],
    default: ''
  },
  onlyCoord: {
    type: Boolean,
    default: false
  }
});

// Filters
const filters = ref({
  search: props.search || '',
  program_id: props.programId || '',
  only_coordinators: props.onlyCoord || false
});

function applyFilters() {
  const payload = {};
  if (filters.value.search) payload.search = filters.value.search;
  if (filters.value.program_id) payload.program_id = filters.value.program_id;
  if (filters.value.only_coordinators) payload.only_coordinators = 1;

  router.get(route('admin.teacher-roles.index'), payload, {
    preserveState: true,
    replace: true
  });
}

function clearFilters() {
  filters.value = { search: '', program_id: '', only_coordinators: false };
  router.get(route('admin.teacher-roles.index'), {}, {
    preserveState: true,
    replace: true
  });
}

// Stats
const totalTeachersCount = computed(() => {
  const ids = new Set((props.details.data || []).map(d => d.user_id));
  return ids.size;
});

const totalCoordinatorsCount = computed(() => {
  return (props.details.data || []).filter(d => d.is_coordinator).length;
});

const activeCount = computed(() => {
  return (props.details.data || []).filter(d => d.is_active).length;
});

// Helpers
function getStorageUrl(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  return `/storage/${path}`;
}

function getInitials(name) {
  if (!name) return 'D';
  return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
}

function toggleStatus(detail) {
  router.patch(route('admin.teacher-roles.toggle-status', detail.id), {}, {
    preserveScroll: true
  });
}

// Modal Create / Edit
const modal = ref({
  open: false,
  isEdit: false,
  detailId: null
});

const form = useForm({
  user_id: '',
  program_id: null,
  specialty: '',
  is_coordinator: false,
  is_active: true
});

function openCreateModal() {
  modal.value = { open: true, isEdit: false, detailId: null };
  form.reset();
  form.clearErrors();
  form.is_active = true;
  form.is_coordinator = false;
  form.program_id = null;
}

function openEditModal(detail) {
  modal.value = { open: true, isEdit: true, detailId: detail.id };
  form.clearErrors();
  form.user_id = detail.user_id;
  form.program_id = detail.program_id;
  form.specialty = detail.specialty || '';
  form.is_coordinator = Boolean(detail.is_coordinator);
  form.is_active = Boolean(detail.is_active);
}

function submitModal() {
  if (modal.value.isEdit) {
    form.put(route('admin.teacher-roles.update', modal.value.detailId), {
      preserveScroll: true,
      onSuccess: () => {
        modal.value.open = false;
      }
    });
  } else {
    form.post(route('admin.teacher-roles.store'), {
      preserveScroll: true,
      onSuccess: () => {
        modal.value.open = false;
        form.reset();
      }
    });
  }
}

// Delete Modal
const deleteModal = ref({
  open: false,
  id: null,
  teacherName: ''
});

function openDeleteModal(detail) {
  deleteModal.value = {
    open: true,
    id: detail.id,
    teacherName: detail.user?.names || 'este docente'
  };
}

function confirmDelete() {
  if (!deleteModal.value.id) return;
  router.delete(route('admin.teacher-roles.destroy', deleteModal.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      deleteModal.value.open = false;
    }
  });
}
</script>
