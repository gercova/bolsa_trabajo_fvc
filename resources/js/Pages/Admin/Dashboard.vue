<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage();
const authUser = computed(() => page.props.auth?.user || {});

const props = defineProps({
    usersTotal: { type: Number, default: 0 },
    usersActive: { type: Number, default: 0 },
    usersThisMonth: { type: Number, default: 0 },
    usersRoles: { type: Object, default: () => ({}) },
    recentUsers: { type: Array, default: () => [] },

    jobOffersTotal: { type: Number, default: 0 },
    jobOffersActive: { type: Number, default: 0 },
    recentOffers: { type: Array, default: () => [] },

    claimsTotal: { type: Number, default: 0 },
    claimsPending: { type: Number, default: 0 },
    claimsThisMonth: { type: Number, default: 0 },
    recentClaims: { type: Array, default: () => [] },

    partnersTotal: { type: Number, default: 0 },
    partnersActive: { type: Number, default: 0 },

    programsTotal: { type: Number, default: 0 },
    programsActive: { type: Number, default: 0 },

    admissionsTotal: { type: Number, default: 0 },
    admissionsActive: { type: Number, default: 0 },
    recentAdmissions: { type: Array, default: () => [] },

    enrollmentSchedulesActive: { type: Number, default: 0 },
    scholarshipsTotal: { type: Number, default: 0 },

    tupaTotal: { type: Number, default: 0 },
    tupaActive: { type: Number, default: 0 },

    documentsTotal: { type: Number, default: 0 },
    documentsActive: { type: Number, default: 0 },

    blogsTotal: { type: Number, default: 0 },
    blogsPublished: { type: Number, default: 0 },
    recentBlogs: { type: Array, default: () => [] },

    carouselsTotal: { type: Number, default: 0 },
    carouselsActive: { type: Number, default: 0 },

    totalVisits: { type: Number, default: 0 },
    visitDigits: { type: Array, default: () => ['0', '0', '0', '0', '0', '0'] },

    degreeRecordsTotal: { type: Number, default: 0 },
    studentRecordsTotal: { type: Number, default: 0 },
});

// Activity Tab state
const activeTab = ref('users');

const roleColors = {
    Admin: { bar: 'bg-purple-600', text: 'text-purple-700', bg: 'bg-purple-50', border: 'border-purple-200' },
    Docente: { bar: 'bg-blue-600', text: 'text-blue-700', bg: 'bg-blue-50', border: 'border-blue-200' },
    Administrativo: { bar: 'bg-indigo-600', text: 'text-indigo-700', bg: 'bg-indigo-50', border: 'border-indigo-200' },
    Estudiante: { bar: 'bg-emerald-600', text: 'text-emerald-700', bg: 'bg-emerald-50', border: 'border-emerald-200' },
    Egresado: { bar: 'bg-amber-600', text: 'text-amber-700', bg: 'bg-amber-50', border: 'border-amber-200' },
    usuario: { bar: 'bg-slate-600', text: 'text-slate-700', bg: 'bg-slate-50', border: 'border-slate-200' },
};

const getRoleTheme = (role) => {
    return roleColors[role] || { bar: 'bg-slate-600', text: 'text-slate-700', bg: 'bg-slate-50', border: 'border-slate-200' };
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    try {
        const d = new Date(dateStr);
        return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' });
    } catch {
        return dateStr;
    }
};

const firstName = computed(() => {
    const n = authUser.value?.names || 'Administrador';
    return n.split(' ')[0];
});
</script>

<template>
    <AdminLayout title="Panel de Control" subtitle="Supervisión general e indicadores en tiempo real" icon="bi-grid-1x2-fill">
        <div class="space-y-8 max-w-7xl mx-auto">
            <!-- ══ HERO BANNER ══ -->
            <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 lg:p-10 text-white shadow-xl relative overflow-hidden border border-slate-800">
                <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-3 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-purple-200 text-xs font-bold tracking-wide backdrop-blur-md">
                            <i class="bi bi-mortarboard-fill text-amber-400"></i>
                            <span>IESTP Francisco Vigo Caballero • Uchiza</span>
                        </div>

                        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">
                            Hola, <span class="text-sky-400">{{ firstName }}</span>
                        </h2>

                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
                            Supervisa y gestiona en tiempo real la admisión, programas de estudio, bolsa de empleo, carrusel de portada y transparencia institucional.
                        </p>

                        <!-- Quick action buttons -->
                        <div class="flex flex-wrap items-center gap-2.5 pt-2">
                            <Link :href="route('admin.carousel.create')"
                                  class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-images text-purple-300"></i>
                                <span>Nuevo Slide Carrusel</span>
                            </Link>

                            <Link :href="route('admin.exams.create')"
                                  class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-journal-plus text-amber-300"></i>
                                <span>Nuevo Examen Admisión</span>
                            </Link>

                            <Link :href="route('admin.blogs.create')"
                                  class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-newspaper text-cyan-300"></i>
                                <span>Publicar Noticia</span>
                            </Link>

                            <Link :href="route('admin.works.create')"
                                  class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all shadow-sm">
                                <i class="bi bi-briefcase text-sky-300"></i>
                                <span>Nueva Oferta Laboral</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Visit counter & identity badge -->
                    <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md flex-shrink-0">
                        <div class="flex items-center gap-2 text-right">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-300">Sistema 100% Operativo</span>
                        </div>

                        <div class="space-y-1 text-left sm:text-right lg:text-right">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">Visitas del Portal Web</span>
                            <div class="flex items-center gap-1">
                                <span v-for="(digit, index) in visitDigits" :key="index"
                                      class="inline-flex items-center justify-center w-6 h-8 rounded-lg bg-slate-900 text-purple-300 font-mono font-black text-base border border-purple-500/30 shadow-inner">
                                    {{ digit }}
                                </span>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                            <i class="bi bi-eye"></i>
                            <span>Total acumulado: {{ totalVisits.toLocaleString('es-PE') }} visitas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══ SECCIÓN 1: KPIs Principales (4 Columnas) ══ -->
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <i class="bi bi-grid-fill text-purple-600"></i> Métricas Clave del Sistema
                    </h3>
                    <span class="text-xs text-slate-400 font-medium">Actualizado en tiempo real</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- 1. Usuarios -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center shadow-sm text-xl">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full"
                                      :class="usersActive === usersTotal ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'">
                                    {{ usersActive }} activos
                                </span>
                            </div>
                            <div>
                                <p class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ usersTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Usuarios & Personal</p>
                            </div>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-purple-600 font-bold flex items-center gap-1">
                                <i class="bi bi-person-plus-fill"></i> +{{ usersThisMonth }} este mes
                            </span>
                            <Link :href="route('admin.users.index')" class="font-bold text-slate-400 hover:text-purple-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </Link>
                        </div>
                    </div>

                    <!-- 2. Bolsa de Trabajo -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shadow-sm text-xl">
                                    <i class="bi bi-briefcase-fill"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ jobOffersActive }} vigentes
                                </span>
                            </div>
                            <div>
                                <p class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ jobOffersTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Ofertas Laborales</p>
                            </div>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-blue-600 font-bold flex items-center gap-1">
                                <i class="bi bi-buildings"></i> {{ partnersActive }} empresas
                            </span>
                            <Link :href="route('admin.works.index')" class="font-bold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </Link>
                        </div>
                    </div>

                    <!-- 3. Admisiones & Matrículas -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shadow-sm text-xl">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ admissionsActive }} activas
                                </span>
                            </div>
                            <div>
                                <p class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ admissionsTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Convocatorias Admisión</p>
                            </div>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-amber-600 font-bold flex items-center gap-1">
                                <i class="bi bi-calendar-check"></i> {{ enrollmentSchedulesActive }} matrículas
                            </span>
                            <Link :href="route('admin.exams.index')" class="font-bold text-slate-400 hover:text-amber-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </Link>
                        </div>
                    </div>

                    <!-- 4. Reclamos -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between relative overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shadow-sm text-xl">
                                    <i class="bi bi-bookmark-x-fill"></i>
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full"
                                      :class="claimsPending > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600 border border-slate-200'">
                                    {{ claimsPending > 0 ? `${claimsPending} pendientes` : 'Sin pendientes' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ claimsTotal }}</p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Libro de Reclamaciones</p>
                            </div>
                        </div>
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-rose-600 font-bold flex items-center gap-1">
                                <i class="bi bi-calendar2-event"></i> +{{ claimsThisMonth }} este mes
                            </span>
                            <Link :href="route('admin.claims.index')" class="font-bold text-slate-400 hover:text-rose-600 transition-colors flex items-center gap-1">
                                Gestionar <i class="bi bi-chevron-right text-[10px]"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══ SECCIÓN 2: KPIs Secundarios (4 Columnas) ══ -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                            <i class="bi bi-book-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ programsTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">Programas de Estudio</p>
                        </div>
                    </div>
                    <Link :href="route('admin.programs.index')" class="text-xs font-bold text-emerald-600 hover:bg-emerald-50 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-right text-base"></i>
                    </Link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-xl">
                            <i class="bi bi-images"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ carouselsTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">Carrusel Principal</p>
                        </div>
                    </div>
                    <Link :href="route('admin.carousel.index')" class="text-xs font-bold text-purple-600 hover:bg-purple-50 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-right text-base"></i>
                    </Link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ tupaTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">TUPA / Trámites</p>
                        </div>
                    </div>
                    <Link :href="route('admin.tupa.index')" class="text-xs font-bold text-indigo-600 hover:bg-indigo-50 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-right text-base"></i>
                    </Link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-cyan-50 border border-cyan-100 text-cyan-600 flex items-center justify-center text-xl">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ blogsTotal }}</p>
                            <p class="text-xs font-medium text-slate-500">Noticias / Blogs</p>
                        </div>
                    </div>
                    <Link :href="route('admin.blogs.index')" class="text-xs font-bold text-cyan-600 hover:bg-cyan-50 px-2.5 py-1.5 rounded-lg transition-colors">
                        <i class="bi bi-arrow-right text-base"></i>
                    </Link>
                </div>
            </section>

            <!-- ══ SECCIÓN 3: Distribución Demográfica + Actividad Multitab ══ -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Columna Izquierda: Demografía & Roles -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-6">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-pie-chart-fill text-purple-600"></i> Distribución de Usuarios
                            </h4>
                            <span class="text-xs text-slate-400 font-medium">Por rol</span>
                        </div>
                        <p class="text-xs text-slate-500">Composición del personal y usuarios registrados en el sistema.</p>
                    </div>

                    <div class="space-y-4">
                        <template v-for="(count, role) in usersRoles" :key="role">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700">{{ role }}</span>
                                    <div class="flex items-center gap-2 font-mono">
                                        <span class="font-bold text-slate-900">{{ count }}</span>
                                        <span class="text-slate-400 text-[11px]">
                                            ({{ Math.round((count / (usersTotal || 1)) * 100) }}%)
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :class="getRoleTheme(role).bar"
                                         :style="{ width: `${Math.round((count / (usersTotal || 1)) * 100)}%` }"></div>
                                </div>
                            </div>
                        </template>

                        <div v-if="!usersRoles || Object.keys(usersRoles).length === 0" class="text-center py-6 text-xs text-slate-400">
                            No hay información de roles disponible.
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Total: <strong class="text-slate-800">{{ usersTotal }} usuarios</strong></span>
                        <Link :href="route('admin.users.index')" class="text-purple-600 font-bold hover:underline">
                            Ver listado completo
                        </Link>
                    </div>
                </div>

                <!-- Columna Derecha: Panel Multitab de Actividad -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                                <i class="bi bi-activity text-purple-600"></i> Registro de Actividad Reciente
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Últimos movimientos registrados en las áreas principales.</p>
                        </div>

                        <!-- Tab buttons -->
                        <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-2xl overflow-x-auto text-xs font-bold">
                            <button @click="activeTab = 'users'"
                                    type="button"
                                    class="px-3 py-1.5 rounded-xl transition"
                                    :class="activeTab === 'users' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                                Usuarios
                            </button>
                            <button @click="activeTab = 'offers'"
                                    type="button"
                                    class="px-3 py-1.5 rounded-xl transition"
                                    :class="activeTab === 'offers' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                                Ofertas
                            </button>
                            <button @click="activeTab = 'claims'"
                                    type="button"
                                    class="px-3 py-1.5 rounded-xl transition"
                                    :class="activeTab === 'claims' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                                Reclamos
                            </button>
                            <button @click="activeTab = 'admissions'"
                                    type="button"
                                    class="px-3 py-1.5 rounded-xl transition"
                                    :class="activeTab === 'admissions' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                                Admisión
                            </button>
                        </div>
                    </div>

                    <!-- Tab Contents -->
                    <div class="flex-1 min-h-[260px]">
                        <!-- Users List -->
                        <div v-show="activeTab === 'users'" class="divide-y divide-slate-100">
                            <div v-for="u in recentUsers" :key="u.id" class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 font-bold flex items-center justify-center shrink-0 border border-purple-100">
                                        {{ String(u.names || 'U').charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 truncate">{{ u.names }}</p>
                                        <p class="text-slate-400 text-[11px] truncate">{{ u.email }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="getRoleTheme(u.role).bg + ' ' + getRoleTheme(u.role).text">
                                        {{ u.role || 'Usuario' }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ formatDate(u.created_at) }}</p>
                                </div>
                            </div>
                            <div v-if="!recentUsers || recentUsers.length === 0" class="text-center py-10 text-xs text-slate-400">
                                No hay usuarios registrados recientemente.
                            </div>
                        </div>

                        <!-- Job Offers List -->
                        <div v-show="activeTab === 'offers'" class="divide-y divide-slate-100">
                            <div v-for="job in recentOffers" :key="job.id" class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate">{{ job.title }}</p>
                                    <p class="text-slate-400 text-[11px] truncate">{{ job.company_name || 'Institucional' }} • {{ job.location || 'Uchiza' }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="job.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'">
                                        {{ job.is_active ? 'Activa' : 'Cerrada' }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ formatDate(job.created_at) }}</p>
                                </div>
                            </div>
                            <div v-if="!recentOffers || recentOffers.length === 0" class="text-center py-10 text-xs text-slate-400">
                                No hay ofertas de empleo registradas recientemente.
                            </div>
                        </div>

                        <!-- Claims List -->
                        <div v-show="activeTab === 'claims'" class="divide-y divide-slate-100">
                            <div v-for="c in recentClaims" :key="c.id" class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate">{{ c.full_name || 'Reclamante' }}</p>
                                    <p class="text-slate-400 text-[11px] truncate">{{ c.claim_type || 'Reclamo' }} • DNI: {{ c.dni || 'N/A' }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="c.status === 'atendido' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">
                                        {{ c.status || 'Pendiente' }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ formatDate(c.created_at) }}</p>
                                </div>
                            </div>
                            <div v-if="!recentClaims || recentClaims.length === 0" class="text-center py-10 text-xs text-slate-400">
                                No hay reclamos registrados recientemente.
                            </div>
                        </div>

                        <!-- Admissions List -->
                        <div v-show="activeTab === 'admissions'" class="divide-y divide-slate-100">
                            <div v-for="a in recentAdmissions" :key="a.id" class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate">{{ a.period }} — {{ a.activity }}</p>
                                    <p class="text-slate-400 text-[11px] truncate">Proceso: {{ a.process }} • Fecha examen: {{ a.exam_date }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="a.is_active ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600'">
                                        {{ a.is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Vacantes: {{ a.total_vacancies }}</p>
                                </div>
                            </div>
                            <div v-if="!recentAdmissions || recentAdmissions.length === 0" class="text-center py-10 text-xs text-slate-400">
                                No hay convocatorias de admisión registradas.
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-medium">Bolsa FVC • Sistema de Gestión Institucional</span>
                        <Link :href="route('admin.exams.index')" class="text-purple-600 font-bold hover:underline flex items-center gap-1">
                            Ir a Convocatorias <i class="bi bi-chevron-right text-[10px]"></i>
                        </Link>
                    </div>
                </div>
            </section>

            <!-- ══ SECCIÓN 4: Accesos Rápidos de Transparencia & Registros ══ -->
            <section class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <Link :href="route('admin.statistics.index')"
                      class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">Estadísticas</p>
                        <p class="text-[11px] text-slate-400">Postulantes</p>
                    </div>
                </Link>

                <Link :href="route('admin.degree-records.index')"
                      class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-file-earmark-ruled-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">Registro Títulos</p>
                        <p class="text-[11px] text-slate-400">{{ degreeRecordsTotal }} registros</p>
                    </div>
                </Link>

                <Link :href="route('admin.documents.index')"
                      class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">Documentos</p>
                        <p class="text-[11px] text-slate-400">{{ documentsTotal }} archivos</p>
                    </div>
                </Link>

                <Link :href="route('admin.enterprise.edit')"
                      class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">Configuración</p>
                        <p class="text-[11px] text-slate-400">Institucional</p>
                    </div>
                </Link>
            </section>
        </div>
    </AdminLayout>
</template>
