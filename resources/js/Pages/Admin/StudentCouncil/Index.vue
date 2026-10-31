<template>
  <AdminLayout title="Consejo Estudiantil">
    <div class="space-y-6">
      <!-- Top Bar / Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
            <Link :href="route('admin.dashboard')" class="hover:text-purple-600 transition-colors">Dashboard</Link>
            <i class="bi bi-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-purple-600">Consejo Estudiantil</span>
          </div>
          <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span class="p-2 rounded-xl bg-purple-100 text-purple-700 inline-flex">
              <i class="bi bi-award-fill text-xl"></i>
            </span>
            Consejo Estudiantil
          </h1>
          <p class="text-sm text-gray-500 mt-1">
            Gestión de representantes, secretarías y líderes estudiantiles institucionales.
          </p>
        </div>

        <div>
          <button
            type="button"
            @click="openCreateModal"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all transform active:scale-95"
          >
            <i class="bi bi-plus-lg text-base"></i>
            <span>Agregar Representante</span>
          </button>
        </div>
      </div>

      <!-- Quick Metrics Cards -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Miembros</p>
            <h3 class="text-2xl font-black text-purple-700 mt-1">{{ councils.total ?? 0 }}</h3>
            <span class="text-xs text-purple-600 font-medium">Registrados</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
            <i class="bi bi-people-fill"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Directiva</p>
            <h3 class="text-2xl font-black text-blue-700 mt-1">{{ leadershipCount }}</h3>
            <span class="text-xs text-blue-600 font-medium">Presidencia / Mesa</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
            <i class="bi bi-award-fill"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Activos</p>
            <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ activeCount }}</h3>
            <span class="text-xs text-emerald-600 font-medium">En gestión</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Períodos</p>
            <h3 class="text-2xl font-black text-amber-600 mt-1">{{ periods?.length ?? 0 }}</h3>
            <span class="text-xs text-amber-600 font-medium">Gestiones lectivas</span>
          </div>
          <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
            <i class="bi bi-calendar-range-fill"></i>
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
              placeholder="Buscar por nombre, cargo, DNI o período..."
              class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            />
          </div>

          <!-- Academic Period Filter -->
          <div class="w-full sm:w-52">
            <select
              v-model="filters.period"
              @change="applyFilters"
              class="w-full py-2.5 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los Períodos</option>
              <option v-for="p in periods" :key="p" :value="p">
                Gestión {{ p }}
              </option>
            </select>
          </div>

          <!-- Program Filter -->
          <div class="w-full sm:w-60">
            <select
              v-model="filters.program_id"
              @change="applyFilters"
              class="w-full py-2.5 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-700 font-medium"
            >
              <option value="">Todos los Programas</option>
              <option v-for="prog in programs" :key="prog.id" :value="prog.id">
                {{ prog.name }}
              </option>
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
              v-if="filters.search || filters.period || filters.program_id"
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

      <!-- Data Table Card -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4">Representante</th>
                <th class="px-6 py-4">Cargo / Posición</th>
                <th class="px-6 py-4">Programa de Estudio</th>
                <th class="px-6 py-4">Período</th>
                <th class="px-6 py-4 text-center">Estado</th>
                <th class="px-6 py-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              <tr
                v-for="council in councils.data"
                :key="council.id"
                class="hover:bg-gray-50/60 transition-colors"
              >
                <!-- Representative -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm overflow-hidden flex-shrink-0 border border-purple-200">
                      <img
                        v-if="council.user?.photo_profile"
                        :src="getStorageUrl(council.user.photo_profile)"
                        :alt="council.name || council.user?.names"
                        class="w-full h-full object-cover"
                      />
                      <span v-else>{{ getInitials(council.name || council.user?.names) }}</span>
                    </div>
                    <div>
                      <p class="font-bold text-gray-900">{{ council.name || council.user?.names || 'Sin nombre' }}</p>
                      <p class="text-xs text-gray-500">{{ council.user?.email || `DNI: ${council.user?.dni ?? '—'}` }}</p>
                    </div>
                  </div>
                </td>

                <!-- Position -->
                <td class="px-6 py-4">
                  <span
                    :class="getPositionBadgeClass(council.position)"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold"
                  >
                    <i class="bi bi-person-badge"></i>
                    {{ council.position }}
                  </span>
                </td>

                <!-- Program -->
                <td class="px-6 py-4">
                  <span
                    v-if="council.study_program"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-100"
                  >
                    <i class="bi bi-mortarboard"></i>
                    {{ council.study_program.name }}
                  </span>
                  <span v-else class="text-xs text-gray-400 italic">General / Institucional</span>
                </td>

                <!-- Period -->
                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    <i class="bi bi-calendar3 text-[10px]"></i>
                    {{ council.academic_period }}
                  </span>
                </td>

                <!-- Status Switch -->
                <td class="px-6 py-4 text-center">
                  <button
                    type="button"
                    @click="toggleStatus(council)"
                    :class="council.is_active ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-600'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                    :title="council.is_active ? 'Desactivar representante' : 'Activar representante'"
                  >
                    <span
                      :class="council.is_active ? 'translate-x-6 bg-white' : 'translate-x-1 bg-white'"
                      class="inline-block h-4 w-4 transform rounded-full transition-transform"
                    />
                  </button>
                  <span class="block text-[11px] font-medium mt-0.5" :class="council.is_active ? 'text-emerald-700' : 'text-gray-400'">
                    {{ council.is_active ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button
                      type="button"
                      @click="openEditModal(council)"
                      class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                      title="Editar Representante"
                    >
                      <i class="bi bi-pencil-square text-base"></i>
                    </button>
                    <button
                      type="button"
                      @click="openDeleteModal(council)"
                      class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                      title="Eliminar Representante"
                    >
                      <i class="bi bi-trash-fill text-base"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!councils.data || councils.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                  <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-400 mb-3">
                    <i class="bi bi-award"></i>
                  </div>
                  <h4 class="font-bold text-gray-700">No se encontraron miembros del Consejo</h4>
                  <p class="text-xs text-gray-400 mt-1">Agrega un nuevo representante o limpia los filtros.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="councils.links && councils.links.length > 3" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
          <p class="text-xs text-gray-500">
            Mostrando del <span class="font-medium text-gray-700">{{ councils.from ?? 0 }}</span> al
            <span class="font-medium text-gray-700">{{ councils.to ?? 0 }}</span> de
            <span class="font-medium text-gray-700">{{ councils.total ?? 0 }}</span> representantes
          </p>

          <div class="flex flex-wrap gap-1">
            <template v-for="(link, i) in councils.links" :key="i">
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

    <!-- ─── MODAL: CREAR / EDITAR REPRESENTANTE ────────────────────────── -->
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
                {{ modal.isEdit ? 'Editar Representante del Consejo' : 'Nuevo Representante del Consejo' }}
              </h3>
              <p class="text-xs text-gray-500">Configura los datos del estudiante y su cargo</p>
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
          <!-- User selection -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Estudiante / Integrante <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.user_id"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              required
            >
              <option value="" disabled>Seleccionar estudiante...</option>
              <option v-for="u in users" :key="u.id" :value="u.id">
                {{ u.names }} (DNI: {{ u.dni }})
              </option>
            </select>
            <p v-if="form.errors.user_id" class="text-xs text-red-600 mt-1">{{ form.errors.user_id }}</p>
          </div>

          <!-- Position / Cargo -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Cargo o Posición <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              v-model="form.position"
              placeholder="Ej: Presidente, Secretaria de Organización, Vocal de Asuntos Académicos"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              required
            />
            <p v-if="form.errors.position" class="text-xs text-red-600 mt-1">{{ form.errors.position }}</p>
          </div>

          <!-- Academic Period & Study Program -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                Período Académico <span class="text-red-500">*</span>
              </label>
              <input
                type="text"
                v-model="form.academic_period"
                placeholder="Ej: 2026-2027"
                class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                required
              />
              <p v-if="form.errors.academic_period" class="text-xs text-red-600 mt-1">{{ form.errors.academic_period }}</p>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                Programa de Estudios
              </label>
              <select
                v-model="form.study_program_id"
                class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              >
                <option :value="null">General / Institucional</option>
                <option v-for="prog in programs" :key="prog.id" :value="prog.id">
                  {{ prog.name }}
                </option>
              </select>
              <p v-if="form.errors.study_program_id" class="text-xs text-red-600 mt-1">{{ form.errors.study_program_id }}</p>
            </div>
          </div>

          <!-- Custom Name (Optional) -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Nombre Personalizado <span class="text-gray-400 font-normal lowercase">(opcional, se toma del usuario)</span>
            </label>
            <input
              type="text"
              v-model="form.name"
              placeholder="Dejar vacío para usar el nombre del usuario"
              class="w-full px-3.5 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            />
            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
          </div>

          <!-- Active checkbox -->
          <div class="pt-1">
            <label class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200 cursor-pointer">
              <input
                type="checkbox"
                v-model="form.is_active"
                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4"
              />
              <span class="text-xs font-bold text-gray-800 flex items-center gap-1">
                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                Integrante activo en el portal institucional
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
              <span>{{ modal.isEdit ? 'Guardar Cambios' : 'Registrar Miembro' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ─── MODAL: ELIMINAR REPRESENTANTE ──────────────────────────────── -->
    <div
      v-if="deleteModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in"
    >
      <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4 animate-scale-up border border-gray-100 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-2xl">
          <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900">¿Eliminar del Consejo?</h3>
          <p class="text-xs text-gray-500 mt-1">
            Se eliminará a <strong class="text-gray-800">{{ deleteModal.name }}</strong> del Consejo Estudiantil.
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
  councils: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  users: {
    type: Array,
    default: () => []
  },
  programs: {
    type: Array,
    default: () => []
  },
  periods: {
    type: Array,
    default: () => []
  },
  search: {
    type: String,
    default: ''
  },
  period: {
    type: String,
    default: ''
  },
  programId: {
    type: [Number, String],
    default: ''
  }
});

// Filters
const filters = ref({
  search: props.search || '',
  period: props.period || '',
  program_id: props.programId || ''
});

function applyFilters() {
  const payload = {};
  if (filters.value.search) payload.search = filters.value.search;
  if (filters.value.period) payload.period = filters.value.period;
  if (filters.value.program_id) payload.program_id = filters.value.program_id;

  router.get(route('admin.student-council.index'), payload, {
    preserveState: true,
    replace: true
  });
}

function clearFilters() {
  filters.value = { search: '', period: '', program_id: '' };
  router.get(route('admin.student-council.index'), {}, {
    preserveState: true,
    replace: true
  });
}

// Stats
const leadershipCount = computed(() => {
  return (props.councils.data || []).filter(c => {
    const p = (c.position || '').toLowerCase();
    return p.includes('presidente') || p.includes('directiva');
  }).length;
});

const activeCount = computed(() => {
  return (props.councils.data || []).filter(c => c.is_active).length;
});

// Helpers
function getStorageUrl(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  return `/storage/${path}`;
}

function getInitials(name) {
  if (!name) return 'C';
  return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
}

function getPositionBadgeClass(pos) {
  const p = (pos || '').toLowerCase();
  if (p.includes('presidente') || p.includes('lider')) {
    return 'bg-purple-100 text-purple-700 border border-purple-200';
  }
  if (p.includes('secretar') || p.includes('coordinad')) {
    return 'bg-blue-100 text-blue-700 border border-blue-200';
  }
  if (p.includes('vocal') || p.includes('delegad')) {
    return 'bg-amber-100 text-amber-800 border border-amber-200';
  }
  return 'bg-gray-100 text-gray-700 border border-gray-200';
}

function toggleStatus(council) {
  router.patch(route('admin.student-council.toggle-status', council.id), {}, {
    preserveScroll: true
  });
}

// Modal Create / Edit
const modal = ref({
  open: false,
  isEdit: false,
  councilId: null
});

const form = useForm({
  user_id: '',
  study_program_id: null,
  name: '',
  position: '',
  academic_period: '2026-2027',
  is_active: true
});

function openCreateModal() {
  modal.value = { open: true, isEdit: false, councilId: null };
  form.reset();
  form.clearErrors();
  form.is_active = true;
  form.academic_period = props.periods && props.periods.length > 0 ? props.periods[0] : '2026-2027';
  form.study_program_id = null;
}

function openEditModal(council) {
  modal.value = { open: true, isEdit: true, councilId: council.id };
  form.clearErrors();
  form.user_id = council.user_id;
  form.study_program_id = council.study_program_id;
  form.name = council.name || '';
  form.position = council.position || '';
  form.academic_period = council.academic_period || '';
  form.is_active = Boolean(council.is_active);
}

function submitModal() {
  if (modal.value.isEdit) {
    form.put(route('admin.student-council.update', modal.value.councilId), {
      preserveScroll: true,
      onSuccess: () => {
        modal.value.open = false;
      }
    });
  } else {
    form.post(route('admin.student-council.store'), {
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
  name: ''
});

function openDeleteModal(council) {
  deleteModal.value = {
    open: true,
    id: council.id,
    name: council.name || council.user?.names || 'este miembro'
  };
}

function confirmDelete() {
  if (!deleteModal.value.id) return;
  router.delete(route('admin.student-council.destroy', deleteModal.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      deleteModal.value.open = false;
    }
  });
}
</script>
