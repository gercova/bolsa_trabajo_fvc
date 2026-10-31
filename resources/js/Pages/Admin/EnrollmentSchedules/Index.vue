<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    schedules: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

// Filters
const search = ref(props.filters.search || '');
const type = ref(props.filters.type || '');
const status = ref(props.filters.status || '');

const handleFilter = () => {
    router.get(route('admin.enrollments.index'), {
        search: search.value || undefined,
        type: type.value || undefined,
        status: status.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilter = () => {
    search.value = '';
    type.value = '';
    status.value = '';
    router.get(route('admin.enrollments.index'));
};

// Toggle status
const togglingId = ref(null);
const toggleStatus = (schedule) => {
    togglingId.value = schedule.id;
    router.patch(route('admin.enrollments.toggle-status', schedule.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            togglingId.value = null;
        },
    });
};

// Delete modal
const deletingSchedule = ref(null);
const confirmDelete = (schedule) => {
    deletingSchedule.value = schedule;
};

const executeDelete = () => {
    if (!deletingSchedule.value) return;
    router.delete(route('admin.enrollments.destroy', deletingSchedule.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            deletingSchedule.value = null;
        },
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const parts = dateStr.split('-');
    if (parts.length === 3) {
        return `${parts[2].substring(0, 2)}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
};
</script>

<template>
    <AdminLayout title="Cronogramas de Matrícula"
                 subtitle="Gestiona los períodos de matrícula ordinaria y extraordinaria con sus calendarios y cupos"
                 icon="bi-calendar2-check"
                 :breadcrumbs="[
                     { label: 'Matrículas' }
                 ]">
        <div class="space-y-6">

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs text-center">
                    <div class="text-2xl font-black text-indigo-600">{{ schedules.total || 0 }}</div>
                    <div class="text-xs text-slate-500 font-semibold mt-0.5">Total Cronogramas</div>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs text-center">
                    <div class="text-2xl font-black text-emerald-600">
                        {{ schedules.data ? schedules.data.filter(s => s.is_active).length : 0 }}
                    </div>
                    <div class="text-xs text-slate-500 font-semibold mt-0.5">Activos en Página</div>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs text-center">
                    <div class="text-2xl font-black text-blue-600">
                        {{ schedules.data ? schedules.data.filter(s => s.enrollment_type === 'ordinaria').length : 0 }}
                    </div>
                    <div class="text-xs text-slate-500 font-semibold mt-0.5">Ordinarias</div>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs text-center">
                    <div class="text-2xl font-black text-purple-600">
                        {{ schedules.data ? schedules.data.filter(s => s.enrollment_type === 'extraordinaria').length : 0 }}
                    </div>
                    <div class="text-xs text-slate-500 font-semibold mt-0.5">Extraordinarias</div>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">

                <!-- Header de la tarjeta -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-5 sm:p-6 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-lg shrink-0">
                            <i class="bi bi-calendar2-check-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Cronogramas Registrados</h2>
                            <p class="text-xs text-slate-400">Control de fechas límites, derecho de matrícula y cupos por carrera</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a :href="route('matriculas')" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            <i class="bi bi-box-arrow-up-right"></i> Ver en Web
                        </a>
                        <Link :href="route('admin.enrollments.create')"
                              class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl transition shadow-md shadow-indigo-500/20 shrink-0">
                            <i class="bi bi-plus-lg"></i> Nuevo Cronograma
                        </Link>
                    </div>
                </div>

                <!-- Buscador y Filtros -->
                <div class="p-4 sm:p-5 bg-slate-50/60 border-b border-slate-100 flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" v-model="search" @keyup.enter="handleFilter"
                               placeholder="Buscar por período académico u observaciones..."
                               class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" />
                    </div>
                    <select v-model="type" @change="handleFilter"
                            class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos los tipos</option>
                        <option value="ordinaria">Matrícula Ordinaria</option>
                        <option value="extraordinaria">Matrícula Extraordinaria</option>
                    </select>
                    <select v-model="status" @change="handleFilter"
                            class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                    <button @click="handleFilter"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    <button v-if="search || type || status" @click="clearFilter"
                            class="px-3 py-2 bg-white hover:bg-slate-100 text-slate-500 rounded-xl text-xs font-bold border border-slate-200 transition flex items-center justify-center gap-1">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </button>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 text-slate-400 font-extrabold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-3.5">#</th>
                                <th class="px-5 py-3.5">Período / Tipo</th>
                                <th class="px-5 py-3.5">Fechas (Inicio - Cierre)</th>
                                <th class="px-5 py-3.5">Costo (S/)</th>
                                <th class="px-5 py-3.5">Cupos Detallados</th>
                                <th class="px-5 py-3.5">Estado</th>
                                <th class="px-5 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr v-for="schedule in schedules.data" :key="schedule.id" class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 font-mono font-bold text-slate-400">{{ schedule.id }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-900 text-sm">{{ schedule.academic_period }}</div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider mt-1"
                                          :class="schedule.enrollment_type === 'ordinaria' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200'">
                                        <i class="bi" :class="schedule.enrollment_type === 'ordinaria' ? 'bi-check-circle' : 'bi-stars'"></i>
                                        {{ schedule.enrollment_type === 'ordinaria' ? 'Ordinaria' : 'Extraordinaria' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-medium">
                                    <div class="flex items-center gap-1.5 text-slate-800">
                                        <i class="bi bi-calendar-event text-slate-400"></i>
                                        <span>{{ formatDate(schedule.start_date) }}</span>
                                        <span class="text-slate-300">al</span>
                                        <span>{{ formatDate(schedule.end_date) }}</span>
                                    </div>
                                    <p v-if="schedule.observations" class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">
                                        {{ schedule.observations }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    S/ {{ Number(schedule.enrollment_fee).toFixed(2) }}
                                </td>
                                <td class="px-5 py-4">
                                    <div v-if="schedule.details && schedule.details.length" class="flex flex-wrap gap-1">
                                        <span v-for="d in schedule.details.slice(0, 3)" :key="d.id"
                                              class="text-[10px] bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded-md">
                                            {{ d.program ? d.program.name.substring(0, 15) : 'Prog' }}... ({{ d.available_slots }})
                                        </span>
                                        <span v-if="schedule.details.length > 3" class="text-[10px] text-slate-400 font-bold self-center">
                                            +{{ schedule.details.length - 3 }} más
                                        </span>
                                    </div>
                                    <span v-else class="text-slate-400 italic text-[11px]">Sin detalle de cupos</span>
                                </td>
                                <td class="px-5 py-4">
                                    <button @click="toggleStatus(schedule)"
                                            :disabled="togglingId === schedule.id"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition shadow-xs cursor-pointer"
                                            :class="schedule.is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200'">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="schedule.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></span>
                                        <span>{{ schedule.is_active ? 'Activo' : 'Inactivo' }}</span>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link :href="route('admin.enrollments.edit', schedule.id)"
                                              class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Editar">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button @click="confirmDelete(schedule)"
                                                class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Eliminar">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!schedules.data || schedules.data.length === 0">
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    <i class="bi bi-calendar-x text-3xl mb-2 block"></i>
                                    <p class="font-bold">No se encontraron cronogramas de matrícula</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Prueba ajustando los filtros o registra uno nuevo</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="schedules.links && schedules.links.length > 3"
                     class="p-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                    <span class="text-xs text-slate-400">
                        Mostrando {{ schedules.from || 0 }} - {{ schedules.to || 0 }} de {{ schedules.total || 0 }}
                    </span>
                    <div class="flex items-center gap-1">
                        <template v-for="(link, i) in schedules.links" :key="i">
                            <Link v-if="link.url" :href="link.url"
                                  v-html="link.label"
                                  class="px-2.5 py-1 text-xs font-bold rounded-lg transition"
                                  :class="link.active ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100'"></Link>
                            <span v-else v-html="link.label" class="px-2.5 py-1 text-xs text-slate-300"></span>
                        </template>
                    </div>
                </div>

            </div>

        </div>

        <!-- Modal Confirmación Eliminar -->
        <div v-if="deletingSchedule" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-base font-extrabold text-slate-900">¿Eliminar este cronograma?</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Se eliminará el cronograma del período <strong>{{ deletingSchedule.academic_period }}</strong> y todos sus cupos asociados. Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button @click="deletingSchedule = null"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                        Cancelar
                    </button>
                    <button @click="executeDelete"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md shadow-rose-500/20">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
