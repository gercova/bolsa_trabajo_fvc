<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const page = usePage();
const props = defineProps({
    title: { type: String, default: '' },
});

// Datos compartidos desde HandleInertiaRequests
const enterprise    = computed(() => page.props.enterprise);
const programs      = computed(() => page.props.headerPrograms || []);
const authUser      = computed(() => page.props.auth?.user);

// Estado reactivo de los dropdowns y móvil
const openDropdown  = ref(null);
const mobileOpen    = ref(false);
const mobileSubOpen = ref({
    admission: false,
    programs: false,
    transparency: false,
    procedures: false,
    about: false,
    services: false,
});
const isScrolled    = ref(false);

const handleScroll = () => {
    const scrolled = window.scrollY > 20;
    isScrolled.value = scrolled;
    const h = scrolled ? 58 : 72;
    document.documentElement.style.setProperty('--nav-h', `${h}px`);
};

const setDropdown = (key) => {
    openDropdown.value = key;
};

const clearDropdown = (key) => {
    if (openDropdown.value === key) {
        openDropdown.value = null;
    }
};

const toggleDropdown = (key) => {
    openDropdown.value = openDropdown.value === key ? null : key;
};

const toggleMobileSection = (key) => {
    mobileSubOpen.value[key] = !mobileSubOpen.value[key];
};

const closeAll = () => {
    openDropdown.value = null;
};

// Cerrar menú móvil y dropdowns al navegar
watch(() => page.url, () => {
    mobileOpen.value = false;
    openDropdown.value = null;
});

// Detectar rutas activas para enlaces principales y dropdowns
const isHomeActive = computed(() => page.url === '/' || page.url === '');
const isAdmissionActive = computed(() => page.url.startsWith('/admision-y-matricula') || page.url.startsWith('/admision-y-matriculas'));
const isProgramsActive = computed(() => page.url.startsWith('/programas-de-estudios'));
const isTransparencyActive = computed(() => page.url.startsWith('/transparencia'));
const isProceduresActive = computed(() => page.url.startsWith('/tramites'));
const isAboutActive = computed(() => page.url.startsWith('/nosotros'));
const isServicesActive = computed(() => page.url.startsWith('/servicios') || page.url.startsWith('/biblioteca') || page.url.startsWith('/validar-certificado'));

onMounted(() => {
    handleScroll();
    window.addEventListener('scroll', handleScroll, { passive: true });
    document.addEventListener('click', closeAll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    document.removeEventListener('click', closeAll);
});

// Datos de navegación
const navAdmission = [
    { label: 'CEPRE FVC',          href: '/admision-y-matricula/cepre-fvc',         icon: 'bi-book' },
    { label: 'Examen de Admisión', href: '/admision-y-matricula/examen-de-admision', icon: 'bi-pencil-square' },
    { label: 'Matrículas',         href: '/admision-y-matricula/matriculas',        icon: 'bi-clipboard-check' },
    { label: 'Becas y Créditos',   href: '/admision-y-matriculas/becas-y-creditos', icon: 'bi-award' },
];

const navTransparency = [
    { label: 'Documentos de Gestión', href: '/transparencia/documentos-de-gestion',  icon: 'bi-folder2-open' },
    { label: 'Estadísticas',          href: '/transparencia/estadisticas',           icon: 'bi-bar-chart-line' },
    { label: 'Inversión y Gestión',   href: '/transparencia/inversion-y-gestion',    icon: 'bi-graph-up-arrow' },
    { label: 'Licenciamiento',        href: '/transparencia/licenciamiento',         icon: 'bi-patch-check' },
    { label: 'Libro de Reclamaciones', href: '/transparencia/libro-de-reclamaciones', icon: 'bi-journal-text' },
];

const navProcedures = [
    { label: 'Mesa de Partes', href: '/tramites/mesa-de-partes', icon: 'bi-inbox' },
    { label: 'TUPA',           href: '/tramites/tupa',           icon: 'bi-file-earmark-ruled' },
];

const navAbout = [
    { label: '¿Quiénes somos?',       href: '/nosotros/quienes-somos',              icon: 'bi-people' },
    { label: 'Reseña Histórica',      href: '/nosotros/historia',                   icon: 'bi-hourglass-split' },
    { label: 'Organigrama',           href: '/nosotros/organigrama-institucional',  icon: 'bi-diagram-3' },
    { label: 'Plana Jerárquica',      href: '/nosotros/plana-jerarquica',           icon: 'bi-person-badge' },
    { label: 'Plana Docente',         href: '/nosotros/plana-de-docentes',          icon: 'bi-mortarboard' },
    { label: 'Plana Administrativa',  href: '/nosotros/plana-administrativa',       icon: 'bi-briefcase' },
    { label: 'Consejo de Estudiantes', href: '/nosotros/consejo-de-estudiantes',    icon: 'bi-person-heart' },
    { label: 'Locales',               href: '/nosotros/locales',                    icon: 'bi-geo-alt' },
];

const navServices = [
    { label: 'Biblioteca Virtual',    href: '/biblioteca',                          icon: 'bi-book-half' },
    { label: 'Bolsa de Trabajo',      href: '/servicios/bolsa-de-trabajo',          icon: 'bi-briefcase-fill' },
    { label: 'Validar Certificados',  href: '/validar-certificado',                 icon: 'bi-patch-check-fill text-emerald-600' },
    { label: 'Enlaces Institucionales', href: '/servicios/enlaces-institucionales', icon: 'bi-box-arrow-up-right' },
];
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <!-- Skip link for accessibility -->
        <a href="#main-content"
           class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[200] focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:text-sm focus:font-semibold">
            Saltar al contenido principal
        </a>

        <!-- ═══ HEADER ═══════════════════════════════════════════════ -->
        <header id="site-header"
                class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
                :class="{ 'scrolled': isScrolled }">

            <!-- Top accent stripe -->
            <div class="header-stripe" aria-hidden="true"></div>

            <!-- Inner wrapper -->
            <div class="nav-inner">

                <!-- Logo -->
                <Link href="/" class="nav-logo" :aria-label="`Inicio — ${enterprise?.trade_name || 'IESTP Francisco Vigo Caballero'}`">
                    <div class="logo-badge">
                        <img :src="enterprise?.logo_path"
                             class="w-full h-full object-contain rounded-lg"
                             :alt="enterprise?.trade_name || 'Logo'" />
                    </div>
                    <div class="logo-text-block">
                        <span class="logo-name">{{ enterprise?.trade_name || 'IESTP Francisco Vigo Caballero' }}</span>
                        <span class="logo-sub">Educación Superior Tecnológica</span>
                    </div>
                </Link>

                <!-- Desktop Navigation -->
                <nav class="nav-links" aria-label="Navegación principal">

                    <!-- Inicio -->
                    <Link href="/"
                          class="nav-link"
                          :class="{ 'active': isHomeActive }"
                          :aria-current="isHomeActive ? 'page' : 'false'">
                        Inicio
                    </Link>

                    <!-- Admisión y Matrícula -->
                    <div class="nav-dropdown"
                         @mouseenter="setDropdown('admission')"
                         @mouseleave="clearDropdown('admission')">
                        <button type="button"
                                class="nav-link nav-link--dropdown"
                                @click.stop="toggleDropdown('admission')"
                                :class="{ 'active': isAdmissionActive || openDropdown === 'admission' }"
                                :aria-expanded="openDropdown === 'admission'">
                            Admisión
                            <i class="bi bi-chevron-down nav-chevron"
                               :class="{ 'rotated': openDropdown === 'admission' }"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out"
                                    enter-from-class="opacity-0 -translate-y-1 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 -translate-y-1 scale-95">
                            <div v-if="openDropdown === 'admission'"
                                 class="dropdown-panel">
                                <div class="dropdown-panel-inner">
                                    <Link v-for="item in navAdmission"
                                          :key="item.href"
                                          :href="item.href"
                                          class="dp-item"
                                          :class="{ 'active': page.url === item.href }"
                                          @click="closeAll">
                                        <span class="dp-icon"><i :class="['bi', item.icon]"></i></span>
                                        <span class="dp-label">{{ item.label }}</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Programas de Estudio -->
                    <div class="nav-dropdown"
                         @mouseenter="setDropdown('programs')"
                         @mouseleave="clearDropdown('programs')">
                        <button type="button"
                                class="nav-link nav-link--dropdown"
                                @click.stop="toggleDropdown('programs')"
                                :class="{ 'active': isProgramsActive || openDropdown === 'programs' }"
                                :aria-expanded="openDropdown === 'programs'">
                            Programas
                            <i class="bi bi-chevron-down nav-chevron"
                               :class="{ 'rotated': openDropdown === 'programs' }"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out"
                                    enter-from-class="opacity-0 -translate-y-1 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 -translate-y-1 scale-95">
                            <div v-if="openDropdown === 'programs'"
                                 class="dropdown-panel dropdown-panel--programs">
                                <div class="dropdown-panel-inner">
                                    <Link href="/programas-de-estudios"
                                          class="dp-item dp-item--all"
                                          :class="{ 'active': page.url === '/programas-de-estudios' }"
                                          @click="closeAll">
                                        <span class="dp-icon dp-icon--all"><i class="bi bi-grid-fill"></i></span>
                                        <span class="dp-label font-semibold text-sky-900">Ver todos los programas</span>
                                        <i class="bi bi-arrow-right text-xs text-sky-600 opacity-60 ml-auto"></i>
                                    </Link>
                                    <div class="dp-divider"></div>
                                    <Link v-for="prog in programs"
                                          :key="prog.slug"
                                          :href="`/programas-de-estudios/${prog.slug}`"
                                          class="dp-item"
                                          :class="{ 'active': page.url === `/programas-de-estudios/${prog.slug}` }"
                                          @click="closeAll">
                                        <span class="dp-icon" :class="[prog.color_config?.light_bg, prog.color_config?.text]">
                                            <i :class="['bi', prog.meta?.icon ?? (prog.icon || 'bi-mortarboard-fill')]"></i>
                                        </span>
                                        <span class="dp-label">{{ prog.name }}</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Transparencia -->
                    <div class="nav-dropdown"
                         @mouseenter="setDropdown('transparency')"
                         @mouseleave="clearDropdown('transparency')">
                        <button type="button"
                                class="nav-link nav-link--dropdown"
                                @click.stop="toggleDropdown('transparency')"
                                :class="{ 'active': isTransparencyActive || openDropdown === 'transparency' }"
                                :aria-expanded="openDropdown === 'transparency'">
                            Transparencia
                            <i class="bi bi-chevron-down nav-chevron"
                               :class="{ 'rotated': openDropdown === 'transparency' }"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out"
                                    enter-from-class="opacity-0 -translate-y-1 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 -translate-y-1 scale-95">
                            <div v-if="openDropdown === 'transparency'"
                                 class="dropdown-panel">
                                <div class="dropdown-panel-inner">
                                    <Link v-for="item in navTransparency"
                                          :key="item.href"
                                          :href="item.href"
                                          class="dp-item"
                                          :class="{ 'active': page.url === item.href }"
                                          @click="closeAll">
                                        <span class="dp-icon"><i :class="['bi', item.icon]"></i></span>
                                        <span class="dp-label">{{ item.label }}</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Trámites -->
                    <div class="nav-dropdown"
                         @mouseenter="setDropdown('procedures')"
                         @mouseleave="clearDropdown('procedures')">
                        <button type="button"
                                class="nav-link nav-link--dropdown"
                                @click.stop="toggleDropdown('procedures')"
                                :class="{ 'active': isProceduresActive || openDropdown === 'procedures' }"
                                :aria-expanded="openDropdown === 'procedures'">
                            Trámites
                            <i class="bi bi-chevron-down nav-chevron"
                               :class="{ 'rotated': openDropdown === 'procedures' }"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out"
                                    enter-from-class="opacity-0 -translate-y-1 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 -translate-y-1 scale-95">
                            <div v-if="openDropdown === 'procedures'"
                                 class="dropdown-panel">
                                <div class="dropdown-panel-inner">
                                    <Link v-for="item in navProcedures"
                                          :key="item.href"
                                          :href="item.href"
                                          class="dp-item"
                                          :class="{ 'active': page.url === item.href }"
                                          @click="closeAll">
                                        <span class="dp-icon"><i :class="['bi', item.icon]"></i></span>
                                        <span class="dp-label">{{ item.label }}</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Nosotros -->
                    <div class="nav-dropdown"
                         @mouseenter="setDropdown('about')"
                         @mouseleave="clearDropdown('about')">
                        <button type="button"
                                class="nav-link nav-link--dropdown"
                                @click.stop="toggleDropdown('about')"
                                :class="{ 'active': isAboutActive || openDropdown === 'about' }"
                                :aria-expanded="openDropdown === 'about'">
                            Nosotros
                            <i class="bi bi-chevron-down nav-chevron"
                               :class="{ 'rotated': openDropdown === 'about' }"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out"
                                    enter-from-class="opacity-0 -translate-y-1 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 -translate-y-1 scale-95">
                            <div v-if="openDropdown === 'about'"
                                 class="dropdown-panel">
                                <div class="dropdown-panel-inner">
                                    <Link v-for="item in navAbout"
                                          :key="item.href"
                                          :href="item.href"
                                          class="dp-item"
                                          :class="{ 'active': page.url === item.href }"
                                          @click="closeAll">
                                        <span class="dp-icon"><i :class="['bi', item.icon]"></i></span>
                                        <span class="dp-label">{{ item.label }}</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Servicios -->
                    <div class="nav-dropdown"
                         @mouseenter="setDropdown('services')"
                         @mouseleave="clearDropdown('services')">
                        <button type="button"
                                class="nav-link nav-link--dropdown"
                                @click.stop="toggleDropdown('services')"
                                :class="{ 'active': isServicesActive || openDropdown === 'services' }"
                                :aria-expanded="openDropdown === 'services'">
                            Servicios
                            <i class="bi bi-chevron-down nav-chevron"
                               :class="{ 'rotated': openDropdown === 'services' }"></i>
                        </button>
                        <transition enter-active-class="transition duration-150 ease-out"
                                    enter-from-class="opacity-0 -translate-y-1 scale-95"
                                    enter-to-class="opacity-100 translate-y-0 scale-100"
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100 translate-y-0 scale-100"
                                    leave-to-class="opacity-0 -translate-y-1 scale-95">
                            <div v-if="openDropdown === 'services'"
                                 class="dropdown-panel">
                                <div class="dropdown-panel-inner">
                                    <Link v-for="item in navServices"
                                          :key="item.href"
                                          :href="item.href"
                                          class="dp-item"
                                          :class="{ 'active': page.url === item.href }"
                                          @click="closeAll">
                                        <span class="dp-icon"><i :class="['bi', item.icon]"></i></span>
                                        <span class="dp-label">{{ item.label }}</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>

                </nav>

                <!-- Right actions (Auth / User) -->
                <div class="nav-right">
                    <template v-if="authUser">
                        <div class="nav-dropdown"
                             @mouseenter="setDropdown('user')"
                             @mouseleave="clearDropdown('user')">
                            <button type="button"
                                    class="user-trigger"
                                    @click.stop="toggleDropdown('user')"
                                    :aria-expanded="openDropdown === 'user'">
                                <div class="user-avatar" aria-hidden="true">
                                    {{ (authUser.names || 'U').charAt(0).toUpperCase() }}
                                </div>
                                <div class="user-info">
                                    <span class="user-greeting">Bienvenido,</span>
                                    <span class="user-name">{{ (authUser.names || '').split(' ')[0] }}</span>
                                </div>
                                <i class="bi bi-chevron-down nav-chevron nav-chevron--sm"
                                   :class="{ 'rotated': openDropdown === 'user' }"></i>
                            </button>

                            <transition enter-active-class="transition duration-150 ease-out"
                                        enter-from-class="opacity-0 -translate-y-1 scale-95"
                                        enter-to-class="opacity-100 translate-y-0 scale-100"
                                        leave-active-class="transition duration-100 ease-in"
                                        leave-from-class="opacity-100 translate-y-0 scale-100"
                                        leave-to-class="opacity-0 -translate-y-1 scale-95">
                                <div v-if="openDropdown === 'user'"
                                     class="dropdown-panel dropdown-panel--right">
                                    <div class="dropdown-panel-inner">
                                        <div class="dp-user-card">
                                            <div class="dp-user-avatar">{{ (authUser.names || 'U').charAt(0).toUpperCase() }}</div>
                                            <div class="dp-user-info">
                                                <p class="dp-user-name">{{ authUser.names }}</p>
                                                <p class="dp-user-email">{{ authUser.email }}</p>
                                            </div>
                                        </div>
                                        <div class="dp-divider"></div>
                                        <Link :href="`/admin-perfil/${authUser.id}`" class="dp-item" @click="closeAll">
                                            <span class="dp-icon"><i class="bi bi-person-circle"></i></span>
                                            <span class="dp-label">Mi Perfil</span>
                                        </Link>
                                        <Link href="/admin/dashboard" class="dp-item" @click="closeAll">
                                            <span class="dp-icon"><i class="bi bi-grid-1x2"></i></span>
                                            <span class="dp-label">Panel de Control</span>
                                        </Link>
                                        <Link href="/biblioteca" class="dp-item" @click="closeAll">
                                            <span class="dp-icon"><i class="bi bi-book"></i></span>
                                            <span class="dp-label">Biblioteca Virtual</span>
                                        </Link>
                                        <div class="dp-divider"></div>
                                        <Link href="/logout" method="post" as="button" class="dp-item dp-item--danger w-full text-left">
                                            <span class="dp-icon"><i class="bi bi-box-arrow-right"></i></span>
                                            <span class="dp-label">Cerrar Sesión</span>
                                        </Link>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </template>
                    <template v-else>
                        <div class="nav-divider" aria-hidden="true"></div>
                        <Link href="/login" class="btn-ghost" id="nav-btn-login">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Ingresar</span>
                        </Link>
                        <Link href="/register" class="btn-primary" id="nav-btn-register">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Registrarse</span>
                        </Link>
                    </template>
                </div>

                <!-- Hamburger (mobile and tablet) -->
                <button class="hamburger"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen"
                        aria-label="Abrir menú"
                        aria-controls="mobile-menu">
                    <i class="bi" :class="mobileOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>

            </div>

            <!-- Mobile menu drawer -->
            <transition enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 -translate-y-3"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 -translate-y-3">
                <div v-if="mobileOpen"
                     id="mobile-menu"
                     role="dialog"
                     aria-label="Menú móvil"
                     aria-modal="true">
                    <div class="mobile-nav-inner">
                        <!-- Inicio -->
                        <Link href="/"
                              class="mobile-link"
                              :class="{ 'active': isHomeActive }"
                              @click="mobileOpen = false">
                            <i class="bi bi-house-door"></i>
                            <span>Inicio</span>
                        </Link>

                        <!-- Admisión y Matrícula -->
                        <div class="w-full">
                            <button type="button"
                                    class="mobile-link mobile-link--accordion w-full"
                                    :class="{ 'active': isAdmissionActive || mobileSubOpen.admission }"
                                    @click="toggleMobileSection('admission')">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-journal-bookmark"></i>
                                    <span>Admisión y Matrícula</span>
                                </div>
                                <i class="bi bi-chevron-down mobile-chevron"
                                   :class="{ 'rotated': mobileSubOpen.admission }"></i>
                            </button>
                            <div v-show="mobileSubOpen.admission" class="mobile-sub">
                                <Link v-for="item in navAdmission"
                                      :key="item.href"
                                      :href="item.href"
                                      class="mobile-sub-link"
                                      :class="{ 'active': page.url === item.href }"
                                      @click="mobileOpen = false">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </div>

                        <!-- Programas de Estudio -->
                        <div class="w-full">
                            <button type="button"
                                    class="mobile-link mobile-link--accordion w-full"
                                    :class="{ 'active': isProgramsActive || mobileSubOpen.programs }"
                                    @click="toggleMobileSection('programs')">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-mortarboard"></i>
                                    <span>Programas de Estudio</span>
                                </div>
                                <i class="bi bi-chevron-down mobile-chevron"
                                   :class="{ 'rotated': mobileSubOpen.programs }"></i>
                            </button>
                            <div v-show="mobileSubOpen.programs" class="mobile-sub">
                                <Link href="/programas-de-estudios"
                                      class="mobile-sub-link font-semibold text-sky-800 bg-sky-50/70 border border-sky-100 flex items-center justify-between"
                                      :class="{ 'active': page.url === '/programas-de-estudios' }"
                                      @click="mobileOpen = false">
                                    <span class="flex items-center gap-2">
                                        <i class="bi bi-grid-fill text-sky-600"></i> Ver todos los programas
                                    </span>
                                    <i class="bi bi-arrow-right text-xs text-sky-600 opacity-60"></i>
                                </Link>
                                <Link v-for="prog in programs"
                                      :key="prog.slug"
                                      :href="`/programas-de-estudios/${prog.slug}`"
                                      class="mobile-sub-link flex items-center gap-2"
                                      :class="{ 'active': page.url === `/programas-de-estudios/${prog.slug}` }"
                                      @click="mobileOpen = false">
                                    <i :class="['bi', prog.meta?.icon ?? (prog.icon || 'bi-mortarboard-fill'), prog.color_config?.text, 'text-sm']"></i>
                                    <span>{{ prog.name }}</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Transparencia -->
                        <div class="w-full">
                            <button type="button"
                                    class="mobile-link mobile-link--accordion w-full"
                                    :class="{ 'active': isTransparencyActive || mobileSubOpen.transparency }"
                                    @click="toggleMobileSection('transparency')">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Transparencia</span>
                                </div>
                                <i class="bi bi-chevron-down mobile-chevron"
                                   :class="{ 'rotated': mobileSubOpen.transparency }"></i>
                            </button>
                            <div v-show="mobileSubOpen.transparency" class="mobile-sub">
                                <Link v-for="item in navTransparency"
                                      :key="item.href"
                                      :href="item.href"
                                      class="mobile-sub-link"
                                      :class="{ 'active': page.url === item.href }"
                                      @click="mobileOpen = false">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </div>

                        <!-- Trámites -->
                        <div class="w-full">
                            <button type="button"
                                    class="mobile-link mobile-link--accordion w-full"
                                    :class="{ 'active': isProceduresActive || mobileSubOpen.procedures }"
                                    @click="toggleMobileSection('procedures')">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Trámites</span>
                                </div>
                                <i class="bi bi-chevron-down mobile-chevron"
                                   :class="{ 'rotated': mobileSubOpen.procedures }"></i>
                            </button>
                            <div v-show="mobileSubOpen.procedures" class="mobile-sub">
                                <Link v-for="item in navProcedures"
                                      :key="item.href"
                                      :href="item.href"
                                      class="mobile-sub-link"
                                      :class="{ 'active': page.url === item.href }"
                                      @click="mobileOpen = false">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </div>

                        <!-- Nosotros -->
                        <div class="w-full">
                            <button type="button"
                                    class="mobile-link mobile-link--accordion w-full"
                                    :class="{ 'active': isAboutActive || mobileSubOpen.about }"
                                    @click="toggleMobileSection('about')">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-info-circle"></i>
                                    <span>Nosotros</span>
                                </div>
                                <i class="bi bi-chevron-down mobile-chevron"
                                   :class="{ 'rotated': mobileSubOpen.about }"></i>
                            </button>
                            <div v-show="mobileSubOpen.about" class="mobile-sub">
                                <Link v-for="item in navAbout"
                                      :key="item.href"
                                      :href="item.href"
                                      class="mobile-sub-link"
                                      :class="{ 'active': page.url === item.href }"
                                      @click="mobileOpen = false">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </div>

                        <!-- Servicios -->
                        <div class="w-full">
                            <button type="button"
                                    class="mobile-link mobile-link--accordion w-full"
                                    :class="{ 'active': isServicesActive || mobileSubOpen.services }"
                                    @click="toggleMobileSection('services')">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-briefcase"></i>
                                    <span>Servicios</span>
                                </div>
                                <i class="bi bi-chevron-down mobile-chevron"
                                   :class="{ 'rotated': mobileSubOpen.services }"></i>
                            </button>
                            <div v-show="mobileSubOpen.services" class="mobile-sub">
                                <Link v-for="item in navServices"
                                      :key="item.href"
                                      :href="item.href"
                                      class="mobile-sub-link"
                                      :class="{ 'active': page.url === item.href }"
                                      @click="mobileOpen = false">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </div>

                        <div class="mobile-divider"></div>

                        <!-- Auth section on mobile -->
                        <template v-if="authUser">
                            <div class="mobile-user-card">
                                <div class="mobile-avatar">{{ (authUser.names || 'U').charAt(0).toUpperCase() }}</div>
                                <div class="overflow-hidden">
                                    <p class="mobile-user-name truncate">{{ authUser.names }}</p>
                                    <p class="mobile-user-email truncate">{{ authUser.email }}</p>
                                </div>
                            </div>
                            <Link :href="`/admin-perfil/${authUser.id}`" class="mobile-link" @click="mobileOpen = false">
                                <i class="bi bi-person-circle"></i> Mi Perfil
                            </Link>
                            <Link href="/biblioteca" class="mobile-link" @click="mobileOpen = false">
                                <i class="bi bi-book"></i> Biblioteca Virtual
                            </Link>
                            <Link href="/logout" method="post" as="button" class="mobile-link mobile-link--danger w-full text-left">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </Link>
                        </template>
                        <template v-else>
                            <div class="mobile-auth-btns">
                                <Link href="/login" class="mobile-btn-ghost" @click="mobileOpen = false">
                                    <i class="bi bi-box-arrow-in-right"></i> Ingresar
                                </Link>
                                <Link href="/register" class="mobile-btn-primary !bg-blue-600 hover:!bg-blue-700 !text-white" @click="mobileOpen = false">
                                    <i class="bi bi-person-plus-fill"></i> Registrarse
                                </Link>
                            </div>
                        </template>
                    </div>
                </div>
            </transition>
        </header>

        <!-- ═══ MAIN ════════════════════════════════════════════════ -->
        <main id="main-content" role="main" class="flex-1">
            <slot />
        </main>

        <!-- ═══ FOOTER ══════════════════════════════════════════════ -->
        <footer class="bg-slate-900 text-slate-300 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-8 md:grid-cols-4">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <img :src="enterprise?.logo_path" class="w-10 h-10 rounded-lg object-contain bg-white p-1" />
                        <div class="text-white font-bold leading-tight text-sm">IESTP<br>Francisco Vigo Caballero</div>
                    </div>
                    <p class="text-sm text-slate-400">{{ enterprise?.phrase ?? 'Educación Superior Tecnológica de Calidad en Uchiza' }}</p>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3 text-sm">Navegación</p>
                    <nav class="space-y-1 text-sm">
                        <Link href="/" class="block hover:text-white">Inicio</Link>
                        <Link href="/servicios/bolsa-de-trabajo" class="block hover:text-white">Bolsa de Trabajo</Link>
                        <Link href="/programas-de-estudios" class="block hover:text-white">Programas de Estudio</Link>
                        <Link href="/nosotros/quienes-somos" class="block hover:text-white">Sobre Nosotros</Link>
                        <Link href="/tramites/mesa-de-partes" class="block hover:text-white">Mesa de Partes</Link>
                    </nav>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3 text-sm">Contacto</p>
                    <div class="space-y-1 text-sm">
                        <p><i class="bi bi-geo-alt-fill mr-2"></i>{{ enterprise?.address ?? 'Av. Ricardo Palma N° 1401' }}, {{ enterprise?.city ?? 'Uchiza' }}</p>
                        <p><i class="bi bi-telephone-fill mr-2"></i>{{ enterprise?.phone_number_1 }}</p>
                        <p><i class="bi bi-envelope-fill mr-2"></i>{{ enterprise?.email }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3 text-sm">Horario</p>
                    <p class="text-sm">Lunes a Viernes<br><span class="text-sky-400">7:30 am – 1:30 pm</span></p>
                </div>
            </div>
            <div class="border-t border-slate-800 py-4 text-center text-xs text-slate-500">
                &copy; {{ new Date().getFullYear() }} IESTP Francisco Vigo Caballero · Todos los derechos reservados.
            </div>
        </footer>
    </div>
</template>