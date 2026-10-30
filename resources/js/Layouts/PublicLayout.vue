<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const page = usePage();
const props = defineProps({
    title: { type: String, default: '' },
});

// Datos compartidos desde HandleInertiaRequests
const enterprise    = computed(() => page.props.enterprise);
const programs      = computed(() => page.props.headerPrograms || []);
const authUser      = computed(() => page.props.auth?.user);

// Estado reactivo de los dropdowns (reemplaza Alpine)
const openDropdown  = ref(null);
const mobileOpen    = ref(false);
const mobileSubOpen = ref({});

const toggleDropdown = (key) => {
    openDropdown.value = openDropdown.value === key ? null : key;
};

const closeAll = () => { openDropdown.value = null; };

// Cerrar menú móvil al navegar
watch(() => page.url, () => {
    mobileOpen.value = false;
    openDropdown.value = null;
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
    { label: 'Biblioteca Virtual',    href: '/biblioteca',                     icon: 'bi-book-half' },
    { label: 'Bolsa de Trabajo',      href: '/servicios/bolsa-de-trabajo',     icon: 'bi-briefcase-fill' },
    { label: 'Validar Certificados',  href: '/validar-certificado',            icon: 'bi-patch-check-fill' },
    { label: 'Enlaces Institucionales', href: '/servicios/enlaces-institucionales', icon: 'bi-box-arrow-up-right' },
];
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white">
        <!-- ═══ HEADER ═══════════════════════════════════════════════ -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">

                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-3 shrink-0">
                        <img :src="enterprise?.logo_path" :alt="enterprise?.trade_name"
                             class="w-10 h-10 rounded-lg object-contain" />
                        <div class="hidden sm:flex flex-col">
                            <span class="text-sm font-extrabold text-slate-900 leading-tight">{{ enterprise?.trade_name }}</span>
                            <span class="text-[10px] uppercase tracking-wider text-sky-600 font-semibold">Educación Superior Tecnológica</span>
                        </div>
                    </Link>

                    <!-- Desktop Nav -->
                    <nav class="hidden lg:flex items-center gap-1">
                        <Link href="/" class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-600 rounded-lg hover:bg-sky-50 transition">Inicio</Link>

                        <!-- Admisión -->
                        <div class="relative">
                            <button @click.stop="toggleDropdown('admission')"
                                    class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-600 rounded-lg hover:bg-sky-50 transition flex items-center gap-1">
                                Admisión <i class="bi bi-chevron-down text-xs transition-transform" :class="{ 'rotate-180': openDropdown === 'admission' }"></i>
                            </button>
                            <transition enter-active-class="transition duration-150" enter-from-class="opacity-0 -translate-y-1" leave-active-class="transition duration-100" leave-to-class="opacity-0 -translate-y-1">
                                <div v-if="openDropdown === 'admission'" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-xl shadow-xl border border-slate-200 py-2">
                                    <Link v-for="item in navAdmission" :key="item.href" :href="item.href"
                                          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                        <i :class="['bi', item.icon, 'text-sky-600']"></i> {{ item.label }}
                                    </Link>
                                </div>
                            </transition>
                        </div>

                        <!-- Programas -->
                        <div class="relative">
                            <button @click.stop="toggleDropdown('programs')"
                                    class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-600 rounded-lg hover:bg-sky-50 transition flex items-center gap-1">
                                Programas <i class="bi bi-chevron-down text-xs transition-transform" :class="{ 'rotate-180': openDropdown === 'programs' }"></i>
                            </button>
                            <transition enter-active-class="transition duration-150" enter-from-class="opacity-0 -translate-y-1" leave-active-class="transition duration-100" leave-to-class="opacity-0 -translate-y-1">
                                <div v-if="openDropdown === 'programs'" class="absolute top-full left-0 mt-1 w-72 bg-white rounded-xl shadow-xl border border-slate-200 py-2">
                                    <Link href="/programas-de-estudios" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-sky-900 hover:bg-sky-50">
                                        <i class="bi bi-grid-fill text-sky-600"></i> Ver todos los programas
                                    </Link>
                                    <div class="my-1 border-t border-slate-100"></div>
                                    <Link v-for="prog in programs" :key="prog.slug" :href="`/programas-de-estudios/${prog.slug}`"
                                          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                        <i :class="['bi', prog.meta?.icon ?? 'bi-mortarboard-fill', 'text-sky-600']"></i>
                                        {{ prog.name }}
                                    </Link>
                                </div>
                            </transition>
                        </div>

                        <!-- Transparencia / Trámites / Nosotros / Servicios (mismo patrón) -->
                        <div v-for="group in [{key:'transp', label:'Transparencia', items:navTransparency}, {key:'proc', label:'Trámites', items:navProcedures}, {key:'about', label:'Nosotros', items:navAbout}, {key:'serv', label:'Servicios', items:navServices}]"
                             :key="group.key" class="relative">
                            <button @click.stop="toggleDropdown(group.key)"
                                    class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-600 rounded-lg hover:bg-sky-50 transition flex items-center gap-1">
                                {{ group.label }} <i class="bi bi-chevron-down text-xs transition-transform" :class="{ 'rotate-180': openDropdown === group.key }"></i>
                            </button>
                            <transition enter-active-class="transition duration-150" enter-from-class="opacity-0 -translate-y-1" leave-active-class="transition duration-100" leave-to-class="opacity-0 -translate-y-1">
                                <div v-if="openDropdown === group.key" class="absolute top-full left-0 mt-1 w-64 bg-white rounded-xl shadow-xl border border-slate-200 py-2">
                                    <Link v-for="item in group.items" :key="item.href" :href="item.href"
                                          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                                        <i :class="['bi', item.icon, 'text-sky-600']"></i> {{ item.label }}
                                    </Link>
                                </div>
                            </transition>
                        </div>
                    </nav>

                    <!-- Auth -->
                    <div class="hidden lg:flex items-center gap-2">
                        <template v-if="authUser">
                            <Link :href="`/admin-perfil/${authUser.id}`" class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-600">
                                <i class="bi bi-person-circle mr-1"></i> {{ authUser.names }}
                            </Link>
                            <Link href="/logout" method="post" as="button" class="px-3 py-2 text-sm font-medium text-rose-600 hover:text-rose-700">
                                <i class="bi bi-box-arrow-right mr-1"></i> Salir
                            </Link>
                        </template>
                        <template v-else>
                            <Link href="/login" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-sky-600 rounded-lg">
                                <i class="bi bi-box-arrow-in-right mr-1"></i> Ingresar
                            </Link>
                            <Link href="/register" class="px-4 py-2 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg transition shadow-sm">
                                <i class="bi bi-person-plus-fill mr-1"></i> Registrarse
                            </Link>
                        </template>
                    </div>

                    <!-- Hamburger -->
                    <button class="lg:hidden p-2 text-slate-700" @click="mobileOpen = !mobileOpen">
                        <i :class="['bi text-2xl', mobileOpen ? 'bi-x-lg' : 'bi-list']"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 -translate-y-2" leave-active-class="transition duration-150" leave-to-class="opacity-0 -translate-y-2">
                <div v-if="mobileOpen" class="lg:hidden bg-white border-t border-slate-200 max-h-[calc(100vh-4rem)] overflow-y-auto">
                    <div class="px-4 py-4 space-y-1">
                        <Link href="/" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">Inicio</Link>
                        <Link href="/programas-de-estudios" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">Programas de Estudio</Link>
                        <Link href="/servicios/bolsa-de-trabajo" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">Bolsa de Trabajo</Link>
                        <Link href="/biblioteca" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">Biblioteca Virtual</Link>
                        <Link href="/validar-certificado" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">Validar Certificados</Link>
                        <div class="border-t border-slate-100 pt-2 mt-2">
                            <template v-if="authUser">
                                <Link :href="`/admin-perfil/${authUser.id}`" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">
                                    <i class="bi bi-person-circle mr-2"></i>{{ authUser.names }}
                                </Link>
                                <Link href="/logout" method="post" as="button" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium text-rose-600 hover:bg-rose-50">
                                    <i class="bi bi-box-arrow-right mr-2"></i>Cerrar Sesión
                                </Link>
                            </template>
                            <template v-else>
                                <Link href="/login" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50">Ingresar</Link>
                                <Link href="/register" class="block px-4 py-3 rounded-lg text-sm font-semibold text-white bg-sky-600 text-center mt-2">Registrarse</Link>
                            </template>
                        </div>
                    </div>
                </div>
            </transition>
        </header>

        <!-- ═══ MAIN ════════════════════════════════════════════════ -->
        <main class="flex-1">
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