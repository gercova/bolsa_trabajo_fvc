<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import LibraryLayout from './Layout.vue';
import route from '@/route';

const props = defineProps({
    totalUniqueReaders: { type: Number, default: 0 },
    totalAccessSessions: { type: Number, default: 0 },
    activeTeachersCount: { type: Number, default: 0 },
    activeStudentsCount: { type: Number, default: 0 },
    readsByCareer: { type: Array, default: () => [] },
    mostReadBooks: { type: Array, default: () => [] },
    readers: { type: Object, required: true },
    recentLogs: { type: Array, default: () => [] },
    studyPrograms: { type: Array, default: () => [] },
    roleFilter: { type: String, default: 'all' },
    careerFilter: { type: [String, Number], default: 'all' },
    search: { type: String, default: '' },
});

const searchInput = ref(props.search || '');
const currentCareer = ref(props.careerFilter || 'all');
const currentRole = ref(props.roleFilter || 'all');

const applyFilters = () => {
    router.get(
        route('admin.library.readers'),
        {
            role: currentRole.value !== 'all' ? currentRole.value : undefined,
            career: currentCareer.value !== 'all' ? currentCareer.value : undefined,
            q: searchInput.value || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const setRole = (role) => {
    currentRole.value = role;
    applyFilters();
};

const maxReads = Math.max(1, ...(props.readsByCareer.map((c) => Number(c.total_reads || 0))));

const formatTimeAgo = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const now = new Date();
    const diffSec = Math.floor((now - date) / 1000);
    if (diffSec < 60) return 'hace un momento';
    const diffMin = Math.floor(diffSec / 60);
    if (diffMin < 60) return `hace ${diffMin} min`;
    const diffHr = Math.floor(diffMin / 60);
    if (diffHr < 24) return `hace ${diffHr} h`;
    const diffDays = Math.floor(diffHr / 24);
    return `hace ${diffDays} d`;
};
</script>

<template>
    <Head title="Dashboard de Lectores - Monitoreo de Lecturas" />

    <LibraryLayout>
        <div class="p-5 sm:p-7 space-y-6">

            <!-- Header -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-person-video2 text-teal-800"></i> Monitoreo de Lectores
                    </h1>
                    <p class="text-xs text-slate-500">Panel de seguimiento de docentes y estudiantes que acceden, leen y consultan la biblioteca virtual.</p>
                </div>
                <div class="text-xs text-slate-500 font-semibold flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Registro de lecturas en tiempo real</span>
                </div>
            </div>

            <!-- KPI METRIC CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Unique Readers -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-800 flex items-center justify-center text-2xl border border-teal-100 shrink-0">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Lectores Totales</span>
                        <span class="text-2xl font-black text-slate-900">{{ Number(totalUniqueReaders).toLocaleString() }}</span>
                        <span class="text-[11px] text-slate-500 block">Docentes y alumnos</span>
                    </div>
                </div>

                <!-- Total Sessions / Views -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-2xl border border-indigo-100 shrink-0">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Consultas / Lecturas</span>
                        <span class="text-2xl font-black text-slate-900">{{ Number(totalAccessSessions).toLocaleString() }}</span>
                        <span class="text-[11px] text-slate-500 block">Visualizaciones registradas</span>
                    </div>
                </div>

                <!-- Active Teachers -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl border border-amber-100 shrink-0">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Docentes Lectores</span>
                        <span class="text-2xl font-black text-slate-900">{{ Number(activeTeachersCount).toLocaleString() }}</span>
                        <span class="text-[11px] text-amber-700 font-semibold block">Actividad docente</span>
                    </div>
                </div>

                <!-- Active Students -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl border border-emerald-100 shrink-0">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Estudiantes Lectores</span>
                        <span class="text-2xl font-black text-slate-900">{{ Number(activeStudentsCount).toLocaleString() }}</span>
                        <span class="text-[11px] text-emerald-700 font-semibold block">Actividad estudiantil</span>
                    </div>
                </div>
            </div>

            <!-- READS BY CAREER & TOP READ CONTENT -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Reads by Career -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-bar-chart-fill text-teal-700"></i> Distribución de Lecturas por Carrera
                        </h3>
                        <span class="text-[11px] text-slate-400 font-semibold">Consolidado general</span>
                    </div>

                    <div class="space-y-3">
                        <div v-for="(item, idx) in readsByCareer" :key="idx">
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-700">{{ item.program_name }}</span>
                                <span class="text-slate-900 font-bold">{{ Number(item.total_reads).toLocaleString() }} lecturas</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div
                                    class="h-full bg-[#0b4d57] rounded-full transition-all duration-500"
                                    :style="{ width: `${Math.round((item.total_reads / maxReads) * 100)}%` }"
                                ></div>
                            </div>
                        </div>

                        <p v-if="!readsByCareer || readsByCareer.length === 0" class="text-xs text-slate-400 text-center py-4">
                            No se han registrado lecturas en el sistema.
                        </p>
                    </div>
                </div>

                <!-- Top Most Read Content -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i class="bi bi-trophy-fill text-amber-500"></i> Materiales Más Consultados
                        </h3>
                        <span class="text-[11px] text-slate-400 font-semibold">Top lecturas</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <div
                            v-for="(b, idx) in mostReadBooks"
                            :key="b.id"
                            class="py-2.5 flex items-center justify-between gap-3 text-xs"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-black text-xs flex items-center justify-center shrink-0">
                                    {{ idx + 1 }}
                                </span>
                                <div class="truncate">
                                    <h4 class="font-bold text-slate-900 truncate" :title="b.title">{{ b.title }}</h4>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ b.author }} · {{ b.study_program ? b.study_program.name : 'General' }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-extrabold text-teal-800 text-xs px-2.5 py-1 bg-teal-50 rounded-lg shrink-0">
                                {{ Number(b.access_logs_count || 0).toLocaleString() }} visitas
                            </span>
                        </div>

                        <p v-if="!mostReadBooks || mostReadBooks.length === 0" class="text-xs text-slate-400 text-center py-4">
                            Aún no hay lecturas registradas.
                        </p>
                    </div>
                </div>
            </div>

            <!-- READERS DIRECTORY TABLE & FILTER -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs space-y-4 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Directorio de Lectores Activos</h3>
                        <p class="text-xs text-slate-500">Lista de usuarios que han interactuado con los libros y documentos del catálogo.</p>
                    </div>

                    <!-- Role Filters & Search -->
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200">
                            <button
                                type="button"
                                @click="setRole('all')"
                                :class="currentRole === 'all' ? 'bg-[#0b4d57] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                                class="px-3 py-1 rounded-lg text-xs font-bold transition"
                            >
                                Todos
                            </button>
                            <button
                                type="button"
                                @click="setRole('Docente')"
                                :class="currentRole === 'Docente' ? 'bg-[#0b4d57] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                                class="px-3 py-1 rounded-lg text-xs font-bold transition"
                            >
                                Docentes
                            </button>
                            <button
                                type="button"
                                @click="setRole('Estudiante')"
                                :class="currentRole === 'Estudiante' ? 'bg-[#0b4d57] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
                                class="px-3 py-1 rounded-lg text-xs font-bold transition"
                            >
                                Estudiantes
                            </button>
                        </div>

                        <!-- Search -->
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input
                                type="text"
                                v-model="searchInput"
                                placeholder="Buscar lector..."
                                @keydown.enter="applyFilters"
                                class="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none"
                            />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Lector</th>
                                <th class="py-3 px-4">DNI</th>
                                <th class="py-3 px-4">Rol Institucional</th>
                                <th class="py-3 px-4 text-center">Consultas / Lecturas</th>
                                <th class="py-3 px-4 text-right">Contacto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="reader in readers.data" :key="reader.id" class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-4 flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-black text-xs flex items-center justify-center shrink-0">
                                        {{ (reader.names || 'U').substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ reader.names }}</h4>
                                        <p class="text-[11px] text-slate-400">{{ reader.email }}</p>
                                    </div>
                                </td>

                                <td class="py-3 px-4 font-mono text-slate-600">
                                    {{ reader.dni || '—' }}
                                </td>

                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                        :class="reader.role === 'Docente' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200'"
                                    >
                                        {{ reader.role }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-center font-bold text-slate-800">
                                    <span class="px-2.5 py-1 bg-teal-50 text-teal-800 rounded-lg">
                                        {{ Number(reader.library_access_logs_count || 0).toLocaleString() }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-right font-medium text-slate-500">
                                    <a :href="`mailto:${reader.email}`" class="text-teal-700 hover:underline">
                                        {{ reader.email }}
                                    </a>
                                </td>
                            </tr>

                            <tr v-if="!readers.data || readers.data.length === 0">
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    No se encontraron registros de lectores para el filtro seleccionado.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Readers Pagination -->
                <div v-if="readers.links && readers.links.length > 3" class="pt-2 flex items-center justify-between flex-wrap gap-2">
                    <span class="text-xs text-slate-500">
                        Total de lectores: {{ readers.total || 0 }}
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in readers.links"
                            :key="idx"
                            :href="link.url || '#'"
                            :class="[
                                'px-3 py-1 rounded-lg text-xs font-semibold transition',
                                link.active ? 'bg-[#0b4d57] text-white font-bold' : 'text-slate-600 hover:bg-slate-100',
                                !link.url ? 'opacity-40 pointer-events-none' : '',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- ACTIVITY STREAM: RECENT READINGS -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="bi bi-clock-history text-teal-700"></i> Historial Reciente de Lecturas
                    </h3>
                    <span class="text-[11px] text-slate-400">Últimas 15 actividades</span>
                </div>

                <div class="divide-y divide-slate-100 text-xs">
                    <div
                        v-for="log in recentLogs"
                        :key="log.id"
                        class="py-2.5 flex items-center justify-between gap-3"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="truncate">
                                <span class="font-bold text-slate-800 mr-1">{{ log.user ? log.user.names : 'Usuario Anónimo' }}</span>
                                <span class="text-slate-400 mr-1">consultó</span>
                                <span class="font-semibold text-slate-900">{{ log.book ? log.book.title : 'Documento' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-slate-400 text-[11px] shrink-0 font-medium">
                            <span>{{ formatTimeAgo(log.created_at) }}</span>
                        </div>
                    </div>

                    <p v-if="!recentLogs || recentLogs.length === 0" class="text-xs text-slate-400 text-center py-4">
                        No hay lecturas registradas recientemente.
                    </p>
                </div>
            </div>

        </div>
    </LibraryLayout>
</template>
