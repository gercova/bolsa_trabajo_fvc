<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    title: { type: String, default: 'Panel Administrativo' },
    subtitle: { type: String, default: '' },
    icon: { type: String, default: 'bi-grid-1x2' },
    breadcrumbs: { type: Array, default: () => [] },
});

const page = usePage();

const enterprise = computed(() => page.props.enterprise || {});
const authUser = computed(() => page.props.auth?.user || {});
const isStudent = computed(() => Boolean(authUser.value?.is_student));

const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

const showFlash = ref(true);
watch([flashSuccess, flashError], () => {
    showFlash.value = true;
});

const dismissFlash = () => {
    showFlash.value = false;
};

// Sidebar state
const sidebarOpen = ref(true);
const handleResize = () => {
    if (window.innerWidth >= 1024) {
        sidebarOpen.value = true;
    } else {
        sidebarOpen.value = false;
    }
};

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

// Accordions
const currentUrl = computed(() => page.url);

const isRouteActive = (pattern) => {
    if (!pattern) return false;
    const url = currentUrl.value.split('?')[0];
    return url.startsWith(pattern);
};

const calendarOpen = ref(
    currentUrl.value.includes('/admin-exams') ||
    currentUrl.value.includes('/admin-matriculas') ||
    currentUrl.value.includes('/admin-scholarships')
);

const docsOpen = ref(
    currentUrl.value.includes('/admin-tupa') ||
    currentUrl.value.includes('/admin-documentos')
);

const peopleOpen = ref(
    currentUrl.value.includes('/admin-usuarios') ||
    currentUrl.value.includes('/admin-docentes-roles') ||
    currentUrl.value.includes('/admin-consejo-estudiantil')
);

const companyOpen = ref(
    currentUrl.value.includes('/admin-empresa') ||
    currentUrl.value.includes('/admin-carrusel') ||
    currentUrl.value.includes('/admin-historia') ||
    currentUrl.value.includes('/admin-socios') ||
    currentUrl.value.includes('/admin-enlaces') ||
    currentUrl.value.includes('/admin-areas')
);

const programsOpen = ref(
    currentUrl.value.includes('/admin-programas') ||
    currentUrl.value.includes('/admin-certificados') ||
    currentUrl.value.includes('/admin-cursos') ||
    currentUrl.value.includes('/admin-modulos') ||
    currentUrl.value.includes('/admin-itinerarios')
);

const transparencyOpen = ref(
    currentUrl.value.includes('/admin-reclamos') ||
    currentUrl.value.includes('/admin-estadisticas') ||
    currentUrl.value.includes('/admin-titulos') ||
    currentUrl.value.includes('/admin-licenciamiento') ||
    currentUrl.value.includes('/admin-inversiones')
);

const isCalendarActive = computed(() => isRouteActive('/admin-exams') || isRouteActive('/admin-matriculas') || isRouteActive('/admin-scholarships'));
const isDocsActive = computed(() => isRouteActive('/admin-tupa') || isRouteActive('/admin-documentos'));
const isPeopleActive = computed(() => isRouteActive('/admin-usuarios') || isRouteActive('/admin-docentes-roles') || isRouteActive('/admin-consejo-estudiantil'));
const isCompanyActive = computed(() => isRouteActive('/admin-empresa') || isRouteActive('/admin-carrusel') || isRouteActive('/admin-historia') || isRouteActive('/admin-socios') || isRouteActive('/admin-enlaces') || isRouteActive('/admin-areas'));
const isProgramsActive = computed(() => isRouteActive('/admin-programas') || isRouteActive('/admin-certificados') || isRouteActive('/admin-cursos') || isRouteActive('/admin-modulos') || isRouteActive('/admin-itinerarios'));
const isTransparencyActive = computed(() => isRouteActive('/admin-reclamos') || isRouteActive('/admin-estadisticas') || isRouteActive('/admin-titulos') || isRouteActive('/admin-licenciamiento') || isRouteActive('/admin-inversiones'));

// Close mobile sidebar on page navigation & auto-expand active accordion
watch(() => page.url, (newUrl) => {
    if (window.innerWidth < 1024) {
        sidebarOpen.value = false;
    }
    if (newUrl.includes('/admin-exams') || newUrl.includes('/admin-matriculas') || newUrl.includes('/admin-scholarships')) {
        calendarOpen.value = true;
    }
    if (newUrl.includes('/admin-tupa') || newUrl.includes('/admin-documentos')) {
        docsOpen.value = true;
    }
    if (newUrl.includes('/admin-usuarios') || newUrl.includes('/admin-docentes-roles') || newUrl.includes('/admin-consejo-estudiantil')) {
        peopleOpen.value = true;
    }
    if (newUrl.includes('/admin-empresa') || newUrl.includes('/admin-carrusel') || newUrl.includes('/admin-historia') || newUrl.includes('/admin-socios') || newUrl.includes('/admin-enlaces') || newUrl.includes('/admin-areas')) {
        companyOpen.value = true;
    }
    if (newUrl.includes('/admin-programas') || newUrl.includes('/admin-certificados') || newUrl.includes('/admin-cursos') || newUrl.includes('/admin-modulos') || newUrl.includes('/admin-itinerarios')) {
        programsOpen.value = true;
    }
    if (newUrl.includes('/admin-reclamos') || newUrl.includes('/admin-estadisticas') || newUrl.includes('/admin-titulos') || newUrl.includes('/admin-licenciamiento') || newUrl.includes('/admin-inversiones')) {
        transparencyOpen.value = true;
    }
});

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased flex flex-col">
        <Head :title="`${title} — ${enterprise?.trade_name || 'IESTP FVC'}`" />

        <!-- Overlay para móvil -->
        <div v-if="sidebarOpen"
             class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[40] lg:hidden transition-opacity"
             @click="sidebarOpen = false">
        </div>

        <div class="flex flex-1 w-full relative min-h-screen">
            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- SIDEBAR                                                 -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <aside class="bg-slate-900 shadow-2xl transition-all duration-300 ease-in-out flex-shrink-0 fixed lg:sticky inset-y-0 left-0 z-[45] lg:z-[40] top-0 h-screen flex flex-col"
                   :class="sidebarOpen ? 'w-72 translate-x-0' : 'w-0 -translate-x-full lg:translate-x-0 lg:w-20'">

                <!-- Brand Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800/80 shrink-0">
                    <Link :href="route('admin.dashboard.index')" class="flex items-center space-x-3 whitespace-nowrap overflow-hidden" v-show="sidebarOpen">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-purple-500/25 shrink-0 text-white">
                            <i :class="isStudent ? 'bi-mortarboard-fill' : 'bi-grid-1x2-fill'" class="bi text-base"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-white font-bold text-sm tracking-wide leading-tight truncate">
                                {{ enterprise?.trade_name || 'Panel de Control' }}
                            </span>
                            <span class="text-[10px] font-bold text-purple-400 uppercase tracking-wider leading-none mt-0.5 truncate">
                                {{ isStudent ? 'Estudiante / Lector' : 'Administración' }}
                            </span>
                        </div>
                    </Link>

                    <button @click="toggleSidebar()"
                            type="button"
                            class="text-slate-400 hover:text-white transition-colors p-2 rounded-xl hover:bg-slate-800"
                            aria-label="Alternar menú lateral">
                        <i class="bi text-xl" :class="sidebarOpen ? 'bi-chevron-left' : 'bi-list'"></i>
                    </button>
                </div>

                <!-- Nav Menu Items (Scrollable) -->
                <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto sidebar-scrollbar custom-scrollbar">

                    <!-- SECCIÓN ESTUDIANTE -->
                    <template v-if="isStudent">
                        <div v-show="sidebarOpen" class="px-3 pb-2 pt-1">
                            <p class="text-[11px] font-extrabold uppercase tracking-widest text-purple-400/90 flex items-center gap-1.5">
                                <i class="bi bi-person-badge-fill text-xs text-purple-400"></i>
                                <span>Portal del Estudiante</span>
                            </p>
                        </div>

                        <!-- 1. Actualizar Datos -->
                        <Link :href="route('admin.profile.edit')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-perfil') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Actualizar Datos">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-perfil') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-person-gear text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/admin-perfil') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Actualizar Datos</span>
                        </Link>

                        <!-- 2. Biblioteca Virtual -->
                        <Link :href="route('biblioteca.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/biblioteca') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Biblioteca Virtual">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/biblioteca') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-book-half text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/biblioteca') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Biblioteca Virtual</span>
                        </Link>

                        <!-- 3. Mesa de Partes -->
                        <Link :href="route('mesa-de-partes')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/tramites/mesa-de-partes') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Mesa de Partes">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/tramites/mesa-de-partes') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-inbox-fill text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/tramites/mesa-de-partes') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Mesa de Partes</span>
                        </Link>

                        <!-- 4. Mis Certificados -->
                        <Link :href="route('admin.certificates.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-certificados') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Mis Certificados">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-certificados') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-patch-check-fill text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/admin-certificados') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Mis Certificados</span>
                        </Link>

                        <!-- 5. Reglamento Interno -->
                        <Link :href="route('admin.regulations.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-reglamento-interno') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Reglamento Interno">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-reglamento-interno') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-shield-check text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/admin-reglamento-interno') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Reglamento Interno</span>
                        </Link>
                    </template>

                    <!-- SECCIÓN ADMINISTRADOR -->
                    <template v-else>
                        <!-- Resumen General / Dashboard -->
                        <Link :href="route('admin.dashboard.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-dashboard') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Resumen General">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-dashboard') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-grid-1x2 text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/admin-dashboard') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Resumen General</span>
                            <div v-if="isRouteActive('/admin-dashboard')"
                                 class="absolute right-3.5 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                        </Link>

                        <!-- Biblioteca Virtual -->
                        <Link :href="route('admin.library.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-biblioteca') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Biblioteca Virtual">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-biblioteca') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-book-half text-lg transition-transform group-hover:scale-110"
                               :class="isRouteActive('/admin-biblioteca') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Biblioteca Virtual</span>
                            <div v-if="isRouteActive('/admin-biblioteca')"
                                 class="absolute right-3.5 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                        </Link>

                        <!-- ── GRUPO: Admisión & Calendario ── -->
                        <div>
                            <button @click="sidebarOpen ? calendarOpen = !calendarOpen : (sidebarOpen = true, calendarOpen = true)"
                                    type="button"
                                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                    :class="(calendarOpen || isCalendarActive) ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                     :class="(calendarOpen || isCalendarActive) ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                                <i class="bi bi-calendar3 text-lg transition-transform group-hover:scale-110"
                                   :class="(calendarOpen || isCalendarActive) ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                                <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Admisión</span>
                                <i v-show="sidebarOpen" class="bi text-xs ml-auto transition-transform duration-200"
                                   :class="calendarOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div v-show="calendarOpen && sidebarOpen" class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <Link :href="route('admin.exams.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-exams') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-journal-text text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-exams') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Exámenes</span>
                                    <div v-if="isRouteActive('/admin-exams')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.enrollments.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-matriculas') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-calendar-check text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-matriculas') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Matrículas</span>
                                    <div v-if="isRouteActive('/admin-matriculas')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.scholarships.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-scholarships') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-award text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-scholarships') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Becas</span>
                                    <div v-if="isRouteActive('/admin-scholarships')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>
                            </div>
                        </div>

                        <!-- ── GRUPO: Trámite & Documentación ── -->
                        <div>
                            <button @click="sidebarOpen ? docsOpen = !docsOpen : (sidebarOpen = true, docsOpen = true)"
                                    type="button"
                                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                    :class="(docsOpen || isDocsActive) ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                     :class="(docsOpen || isDocsActive) ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                                <i class="bi bi-folder2-open text-lg transition-transform group-hover:scale-110"
                                   :class="(docsOpen || isDocsActive) ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                                <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Trámite</span>
                                <i v-show="sidebarOpen" class="bi text-xs ml-auto transition-transform duration-200"
                                   :class="docsOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div v-show="docsOpen && sidebarOpen" class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <Link :href="route('admin.tupa.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-tupa') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-file-earmark-text text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-tupa') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">TUPA</span>
                                    <div v-if="isRouteActive('/admin-tupa')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.documents.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-documentos') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-folder-symlink text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-documentos') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Documentos</span>
                                    <div v-if="isRouteActive('/admin-documentos')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>
                            </div>
                        </div>

                        <!-- ── GRUPO: Personas ── -->
                        <div>
                            <button @click="sidebarOpen ? peopleOpen = !peopleOpen : (sidebarOpen = true, peopleOpen = true)"
                                    type="button"
                                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                    :class="(peopleOpen || isPeopleActive) ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                     :class="(peopleOpen || isPeopleActive) ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                                <i class="bi bi-people-fill text-lg transition-transform group-hover:scale-110"
                                   :class="(peopleOpen || isPeopleActive) ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                                <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Personas</span>
                                <i v-show="sidebarOpen" class="bi text-xs ml-auto transition-transform duration-200"
                                   :class="peopleOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div v-show="peopleOpen && sidebarOpen" class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <Link :href="route('admin.users.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-usuarios') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-people text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-usuarios') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Usuarios</span>
                                    <div v-if="isRouteActive('/admin-usuarios')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.teacher-roles.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-docentes-roles') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-person-workspace text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-docentes-roles') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Plana Docente</span>
                                    <div v-if="isRouteActive('/admin-docentes-roles')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.student-council.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-consejo-estudiantil') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-mortarboard text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-consejo-estudiantil') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Concejo Estudiantil</span>
                                    <div v-if="isRouteActive('/admin-consejo-estudiantil')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>
                            </div>
                        </div>

                        <!-- ── GRUPO: Empresa ── -->
                        <div>
                            <button @click="sidebarOpen ? companyOpen = !companyOpen : (sidebarOpen = true, companyOpen = true)"
                                    type="button"
                                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                    :class="(companyOpen || isCompanyActive) ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                     :class="(companyOpen || isCompanyActive) ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                                <i class="bi bi-building text-lg transition-transform group-hover:scale-110"
                                   :class="(companyOpen || isCompanyActive) ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                                <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Empresa</span>
                                <i v-show="sidebarOpen" class="bi text-xs ml-auto transition-transform duration-200"
                                   :class="companyOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div v-show="companyOpen && sidebarOpen" class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <Link :href="route('admin.enterprise.edit')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-empresa') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-gear-wide-connected text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-empresa') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Configurar Empresa</span>
                                    <div v-if="isRouteActive('/admin-empresa')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.carousel.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-carrusel') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-images text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-carrusel') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Carrusel Principal</span>
                                    <div v-if="isRouteActive('/admin-carrusel')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.history.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-historia') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-clock-history text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-historia') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Historia Institucional</span>
                                    <div v-if="isRouteActive('/admin-historia')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.partners.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-socios') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-buildings text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-socios') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Gestionar Partners</span>
                                    <div v-if="isRouteActive('/admin-socios')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.links.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-enlaces') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-box-arrow-up-right text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-enlaces') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Enlaces Institucionales</span>
                                    <div v-if="isRouteActive('/admin-enlaces')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.areas.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-areas') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-diagram-3 text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-areas') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Áreas Institucionales</span>
                                    <div v-if="isRouteActive('/admin-areas')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>
                            </div>
                        </div>

                        <!-- ── GRUPO: Programas ── -->
                        <div>
                            <button @click="sidebarOpen ? programsOpen = !programsOpen : (sidebarOpen = true, programsOpen = true)"
                                    type="button"
                                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                    :class="(programsOpen || isProgramsActive) ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                     :class="(programsOpen || isProgramsActive) ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                                <i class="bi bi-book text-lg transition-transform group-hover:scale-110"
                                   :class="(programsOpen || isProgramsActive) ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                                <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Programas</span>
                                <i v-show="sidebarOpen" class="bi text-xs ml-auto transition-transform duration-200"
                                   :class="programsOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div v-show="programsOpen && sidebarOpen" class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <Link :href="route('admin.programs.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-programas') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-mortarboard text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-programas') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Programas de estudio</span>
                                    <div v-if="isRouteActive('/admin-programas')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <!-- Subtítulo: CERTIFICACIONES -->
                                <div class="pt-2 pb-1 px-3 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-purple-400/80">
                                    <i class="bi bi-patch-check"></i>
                                    <span>CERTIFICACIONES</span>
                                </div>

                                <Link :href="route('admin.certificates.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-certificados') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-patch-check text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-certificados') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Certificados</span>
                                    <div v-if="isRouteActive('/admin-certificados')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.courses.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-cursos') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-journal-bookmark text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-cursos') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Cursos</span>
                                    <div v-if="isRouteActive('/admin-cursos')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.modules.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-modulos') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-layers text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-modulos') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Módulos</span>
                                    <div v-if="isRouteActive('/admin-modulos')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.itineraries.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-itinerarios') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-diagram-3 text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-itinerarios') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Itinerarios</span>
                                    <div v-if="isRouteActive('/admin-itinerarios')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>
                            </div>
                        </div>

                        <!-- ── ENLACE: Gestionar Blogs ── -->
                        <Link :href="route('admin.blogs.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-blogs') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Gestionar Blogs">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-blogs') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-newspaper text-xl transition-transform duration-200 group-hover:scale-110"
                               :class="isRouteActive('/admin-blogs') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-4 font-medium whitespace-nowrap">Gestionar Blogs</span>
                            <div v-if="isRouteActive('/admin-blogs')"
                                 class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                        </Link>

                        <!-- ── ENLACE: Bolsa de Trabajo ── -->
                        <Link :href="route('admin.works.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-trabajos') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Bolsa de Trabajo">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-trabajos') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-briefcase text-xl transition-transform duration-200 group-hover:scale-110"
                               :class="isRouteActive('/admin-trabajos') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-4 font-medium whitespace-nowrap">Bolsa de Trabajo</span>
                            <div v-if="isRouteActive('/admin-trabajos')"
                                 class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                        </Link>

                        <!-- ── ENLACE: Roles del sistema ── -->
                        <Link :href="route('admin.roles.index')"
                              class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                              :class="isRouteActive('/admin-roles') ? 'bg-gradient-to-r from-purple-600/25 to-indigo-600/25 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'"
                              title="Roles del sistema">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                 :class="isRouteActive('/admin-roles') ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                            <i class="bi bi-shield-lock text-xl transition-transform duration-200 group-hover:scale-110"
                               :class="isRouteActive('/admin-roles') ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                            <span v-show="sidebarOpen" class="ml-4 font-medium whitespace-nowrap">Roles del sistema</span>
                            <div v-if="isRouteActive('/admin-roles')"
                                 class="absolute right-4 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                        </Link>

                        <!-- ── GRUPO: Transparencia ── -->
                        <div>
                            <button @click="sidebarOpen ? transparencyOpen = !transparencyOpen : (sidebarOpen = true, transparencyOpen = true)"
                                    type="button"
                                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                    :class="(transparencyOpen || isTransparencyActive) ? 'bg-gradient-to-r from-purple-600/20 to-indigo-600/20 text-white shadow-lg shadow-purple-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-indigo-600 rounded-r-md transition-all duration-200"
                                     :class="(transparencyOpen || isTransparencyActive) ? 'scale-y-100' : 'scale-y-0 group-hover:scale-y-75'"></div>
                                <i class="bi bi-shield-check text-lg transition-transform group-hover:scale-110"
                                   :class="(transparencyOpen || isTransparencyActive) ? 'text-purple-400' : 'text-slate-400 group-hover:text-purple-400'"></i>
                                <span v-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Transparencia</span>
                                <i v-show="sidebarOpen" class="bi text-xs ml-auto transition-transform duration-200"
                                   :class="transparencyOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>

                            <div v-show="transparencyOpen && sidebarOpen" class="mt-1 ml-4 pl-3 border-l border-slate-700/60 space-y-1">
                                <Link :href="route('admin.claims.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-reclamos') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-bookmark-x text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-reclamos') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Reclamos</span>
                                    <div v-if="isRouteActive('/admin-reclamos')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.statistics.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-estadisticas') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-bar-chart-line text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-estadisticas') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Estadísticas</span>
                                    <div v-if="isRouteActive('/admin-estadisticas')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.degree-records.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-titulos') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-award text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-titulos') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Títulos</span>
                                    <div v-if="isRouteActive('/admin-titulos')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.licensing.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-licenciamiento') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-patch-check text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-licenciamiento') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Licenciamiento</span>
                                    <div v-if="isRouteActive('/admin-licenciamiento')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>

                                <Link :href="route('admin.account-balances.index')"
                                      class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                      :class="isRouteActive('/admin-inversiones') ? 'bg-purple-600/15 text-white font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'">
                                    <i class="bi bi-cash-stack text-base group-hover:scale-110 transition-transform duration-200"
                                       :class="isRouteActive('/admin-inversiones') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400'"></i>
                                    <span class="ml-3 text-sm font-medium whitespace-nowrap">Inversión y Gastos</span>
                                    <div v-if="isRouteActive('/admin-inversiones')"
                                         class="absolute right-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-purple-400 shadow-lg shadow-purple-400/50"></div>
                                </Link>
                            </div>
                        </div>
                    </template>
                </nav>

                <!-- User Profile & Session Footer -->
                <div class="p-3 border-t border-slate-800/80 bg-slate-950/40 shrink-0">
                    <div class="flex items-center justify-between gap-2 p-2 rounded-xl hover:bg-slate-800/50 transition">
                        <Link :href="route('admin.profile.edit')" class="flex items-center space-x-3 min-w-0 flex-1">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0 text-white font-bold text-xs shadow-md">
                                <img v-if="authUser?.photo_profile_url" :src="authUser.photo_profile_url" class="w-full h-full object-cover rounded-full" alt="avatar" />
                                <span v-else>{{ String(authUser?.names || 'U').charAt(0).toUpperCase() }}</span>
                            </div>
                            <div v-show="sidebarOpen" class="overflow-hidden min-w-0 flex-1">
                                <p class="text-xs font-bold text-white truncate leading-tight">
                                    {{ authUser?.names || 'Usuario' }}
                                </p>
                                <p class="text-[11px] text-purple-400 font-medium truncate leading-tight mt-0.5">
                                    {{ authUser?.role || 'Personal' }}
                                </p>
                            </div>
                        </Link>

                        <button @click="logout"
                                type="button"
                                class="text-slate-400 hover:text-rose-400 p-2 rounded-lg hover:bg-rose-500/10 transition-colors shrink-0"
                                title="Cerrar Sesión">
                            <i class="bi bi-box-arrow-right text-base"></i>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- MAIN CONTENT AREA                                       -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <div class="flex-1 flex flex-col min-w-0 bg-slate-50/70 relative">
                <!-- Sticky Top Navigation Bar -->
                <header class="bg-white/95 border-b border-slate-200/80 sticky top-0 z-[30] shadow-sm backdrop-blur-md">
                    <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <button @click="toggleSidebar()"
                                    type="button"
                                    class="text-slate-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-xl transition-colors lg:hidden shrink-0"
                                    title="Alternar Menú">
                                <i class="bi bi-list text-2xl"></i>
                            </button>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h1 class="text-lg sm:text-xl font-black text-slate-800 tracking-tight leading-tight truncate flex items-center gap-2">
                                        <i v-if="icon" :class="`bi ${icon} text-purple-600`"></i>
                                        <span>{{ title }}</span>
                                    </h1>
                                </div>
                                <!-- Breadcrumbs / Subtitle -->
                                <nav v-if="breadcrumbs && breadcrumbs.length > 0" class="flex items-center gap-1.5 text-xs text-slate-400 font-medium mt-0.5">
                                    <Link :href="route('admin.dashboard.index')" class="hover:text-purple-600 transition flex items-center gap-1">
                                        <i class="bi bi-house-door"></i> Inicio
                                    </Link>
                                    <template v-for="(bc, index) in breadcrumbs" :key="index">
                                        <i class="bi bi-chevron-right text-[10px] text-slate-300"></i>
                                        <Link v-if="bc.url" :href="bc.url" class="hover:text-purple-600 transition">
                                            {{ bc.label }}
                                        </Link>
                                        <span v-else class="text-purple-600 font-semibold truncate">{{ bc.label }}</span>
                                    </template>
                                </nav>
                                <p v-else-if="subtitle" class="text-xs text-slate-400 font-medium mt-0.5 truncate">{{ subtitle }}</p>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <a :href="route('inicio')" target="_blank"
                               class="inline-flex items-center gap-1.5 text-xs font-bold bg-blue-700 hover:bg-blue-800 text-white px-3.5 py-2 rounded-xl transition shadow-sm">
                                <i class="bi bi-box-arrow-up-right"></i>
                                <span class="hidden sm:inline">Ver Portal</span>
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Flash Messages -->
                <div v-if="showFlash && (flashSuccess || flashError)" class="px-4 sm:px-6 lg:px-8 pt-4">
                    <!-- Success Flash -->
                    <div v-if="flashSuccess"
                         class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center justify-between text-emerald-900 transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-lg shrink-0"></i>
                            <p class="text-sm font-semibold">{{ flashSuccess }}</p>
                        </div>
                        <button @click="dismissFlash" type="button" class="text-emerald-600 hover:text-emerald-800 p-1">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>

                    <!-- Error Flash -->
                    <div v-if="flashError"
                         class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm flex items-center justify-between text-rose-900 transition-all">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg shrink-0"></i>
                            <p class="text-sm font-semibold">{{ flashError }}</p>
                        </div>
                        <button @click="dismissFlash" type="button" class="text-rose-600 hover:text-rose-800 p-1">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Page Body Slot -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sidebar-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.sidebar-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.25);
    border-radius: 20px;
}
.sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.4);
}
</style>
