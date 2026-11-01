<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import route from '@/route';

defineProps({
    pageTitle: { type: String, default: 'Inicio' },
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user || page.props.user || null);

const isReaderSidebarOpen = ref(true);
const toggleSidebar = () => {
    isReaderSidebarOpen.value = !isReaderSidebarOpen.value;
};

const isAdmin = computed(() => {
    const role = authUser.value?.role;
    return role && ['Admin', 'Administrador', 'Director', 'Bibliotecario'].includes(role);
});

const currentUrl = computed(() => page.url);
const isRouteActive = (pattern) => {
    return currentUrl.value.includes(pattern);
};
</script>

<template>
    <div class="flex w-full bg-white font-sans text-slate-800 min-h-screen">

        <!-- Mobile Drawer Overlay -->
        <div
            v-if="isReaderSidebarOpen"
            @click="isReaderSidebarOpen = false"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-30 lg:hidden"
        ></div>

        <!-- Reader Navigation Sidebar -->
        <aside
            class="bg-white border-r border-slate-200/80 shrink-0 transition-all duration-300 ease-in-out fixed lg:sticky inset-y-0 left-0 z-40 top-0 h-screen overflow-hidden flex flex-col p-4"
            :class="isReaderSidebarOpen ? 'w-60 translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-20'"
        >
            <!-- Logo & Brand Header -->
            <div class="flex items-center gap-3 pb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 p-1 shrink-0 flex items-center justify-center border border-blue-100 shadow-2xs">
                    <img src="/images/logo.png" class="w-full h-full object-contain" alt="Logo IESTP FVC" onerror="this.style.display='none'" />
                    <i class="bi bi-book-half text-blue-700 text-xl" onerror=""></i>
                </div>
                <div v-show="isReaderSidebarOpen" class="min-w-0 transition-opacity">
                    <h2 class="text-xs font-black text-slate-900 uppercase leading-snug tracking-tight">IESTP F. VIGO CABALLERO</h2>
                    <span class="text-[10px] text-blue-700 font-bold tracking-wider uppercase block">Biblioteca Virtual</span>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-slate-100 w-full mb-4"></div>

            <!-- Nav Links -->
            <nav class="space-y-1.5 flex-1">
                <!-- 1. Inicio -->
                <Link
                    :href="route('biblioteca.index')"
                    :class="[
                        'group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden',
                        isRouteActive('/biblioteca') && !isRouteActive('/biblioteca/buscar') && !isRouteActive('/biblioteca/mi-biblioteca')
                            ? 'bg-blue-50/90 text-blue-900 font-bold shadow-2xs'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
                    ]"
                    title="Inicio"
                >
                    <div
                        v-if="isRouteActive('/biblioteca') && !isRouteActive('/biblioteca/buscar') && !isRouteActive('/biblioteca/mi-biblioteca')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-blue-600 rounded-r-md"
                    ></div>
                    <i
                        class="bi bi-house-door-fill text-lg mr-3 shrink-0"
                        :class="isRouteActive('/biblioteca') && !isRouteActive('/biblioteca/buscar') && !isRouteActive('/biblioteca/mi-biblioteca') ? 'text-blue-700' : 'text-slate-400 group-hover:text-slate-600'"
                    ></i>
                    <span v-show="isReaderSidebarOpen" class="truncate">Inicio</span>
                </Link>

                <!-- 2. Buscar -->
                <Link
                    :href="route('biblioteca.search')"
                    :class="[
                        'group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden',
                        isRouteActive('/biblioteca/buscar')
                            ? 'bg-blue-50/90 text-blue-900 font-bold shadow-2xs'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
                    ]"
                    title="Buscar"
                >
                    <div
                        v-if="isRouteActive('/biblioteca/buscar')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-blue-600 rounded-r-md"
                    ></div>
                    <i
                        class="bi bi-search text-lg mr-3 shrink-0"
                        :class="isRouteActive('/biblioteca/buscar') ? 'text-blue-700' : 'text-slate-400 group-hover:text-slate-600'"
                    ></i>
                    <span v-show="isReaderSidebarOpen" class="truncate">Buscar</span>
                </Link>

                <!-- 3. Mi Biblioteca -->
                <Link
                    :href="route('biblioteca.favorites')"
                    :class="[
                        'group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden',
                        isRouteActive('/biblioteca/mi-biblioteca')
                            ? 'bg-blue-50/90 text-blue-900 font-bold shadow-2xs'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
                    ]"
                    title="Mi Biblioteca"
                >
                    <div
                        v-if="isRouteActive('/biblioteca/mi-biblioteca')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-blue-600 rounded-r-md"
                    ></div>
                    <i
                        class="bi bi-bookmark-heart-fill text-lg mr-3 shrink-0"
                        :class="isRouteActive('/biblioteca/mi-biblioteca') ? 'text-blue-700' : 'text-slate-400 group-hover:text-slate-600'"
                    ></i>
                    <span v-show="isReaderSidebarOpen" class="truncate">Mi Biblioteca</span>
                </Link>
            </nav>

            <!-- Bottom shortcuts (Admin or Student profile) -->
            <div class="pt-3 border-t border-slate-100 space-y-1">
                <Link
                    v-if="isAdmin"
                    :href="route('admin.library.index')"
                    class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold text-teal-800 hover:bg-teal-50 transition gap-2"
                    title="Panel Administrativo"
                >
                    <i class="bi bi-sliders text-sm shrink-0"></i>
                    <span v-show="isReaderSidebarOpen" class="truncate font-bold">Panel Admin</span>
                </Link>

                <Link
                    v-if="authUser"
                    :href="route('admin.profile.edit', authUser.id)"
                    class="flex items-center px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition gap-2"
                    title="Mi Perfil"
                >
                    <i class="bi bi-person-circle text-sm shrink-0"></i>
                    <span v-show="isReaderSidebarOpen" class="truncate">Mi Perfil</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-white">
            <!-- Top bar -->
            <header class="px-5 sm:px-8 py-3.5 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-md z-20">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="toggleSidebar"
                        class="p-2 text-slate-500 hover:text-slate-800 rounded-xl hover:bg-slate-100 lg:hidden"
                    >
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                        <i class="bi bi-book-half text-blue-600"></i>
                        <span>Biblioteca Virtual</span>
                        <span>/</span>
                        <span class="text-slate-900 font-bold">{{ pageTitle }}</span>
                    </div>
                </div>

                <!-- User profile badge -->
                <div v-if="authUser" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-rose-500 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                        {{ (authUser.names || 'U').substring(0, 2).toUpperCase() }}
                    </div>
                    <div class="hidden sm:block text-right leading-tight">
                        <span class="text-xs font-bold text-slate-800 block truncate max-w-[160px]">{{ authUser.names }}</span>
                        <span class="text-[10px] text-slate-400 block truncate max-w-[160px]">{{ authUser.email }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Body Slot -->
            <main class="flex-1 p-5 sm:p-8 max-w-7xl w-full mx-auto relative">
                <slot />
            </main>
        </div>

        <!-- Floating Help Button -->
        <div class="fixed bottom-5 right-5 z-20">
            <a
                :href="route('mesa-de-partes')"
                title="Ayuda / Consultas sobre la Biblioteca"
                class="w-10 h-10 rounded-full bg-slate-400 hover:bg-slate-600 text-white flex items-center justify-center shadow-md font-bold text-base transition"
            >
                <i class="bi bi-question-lg"></i>
            </a>
        </div>

    </div>
</template>
