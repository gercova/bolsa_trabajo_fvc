<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import route from '@/route';

const props = defineProps({
    title: { type: String, default: 'Biblioteca Virtual' },
    subtitle: { type: String, default: '' },
    breadcrumbs: { type: Array, default: () => [] },
});

const page = usePage();
const enterprise = computed(() => page.props.enterprise || {});
const currentUrl = computed(() => page.url);

const librarySidebarOpen = ref(true);
const mobileMenuOpen = ref(false);

const toggleLibrarySidebar = () => {
    librarySidebarOpen.value = !librarySidebarOpen.value;
};

const isNavActive = (path) => {
    const url = currentUrl.value.split('?')[0];
    if (path === '/admin-biblioteca' || path === '/admin-biblioteca/libros') {
        return url === '/admin-biblioteca' || url === '/admin-biblioteca/libros';
    }
    return url.startsWith(path);
};
</script>

<template>
    <Head :title="title" />

    <div class="flex w-full min-h-screen bg-slate-50 font-sans text-slate-800 antialiased relative">
        <!-- Mobile Drawer Overlay -->
        <div
            v-if="mobileMenuOpen"
            @click="mobileMenuOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
        ></div>

        <!-- SIDEBAR: Admin Library Navigation (Dark Teal #0b4d57) -->
        <aside
            class="bg-[#0b4d57] text-white shrink-0 transition-all duration-300 ease-in-out fixed lg:sticky inset-y-0 left-0 z-50 top-0 h-screen shadow-xl flex flex-col select-none"
            :class="[
                mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                librarySidebarOpen ? 'w-64' : 'lg:w-20'
            ]"
        >
            <!-- Institutional Brand Header in Sidebar -->
            <div class="px-4 py-4 border-b border-teal-800/60 flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden" v-show="librarySidebarOpen">
                    <div class="w-10 h-10 rounded-xl bg-white/10 p-1 shrink-0 flex items-center justify-center border border-white/20">
                        <img
                            :src="enterprise.logo_path || '/images/logo.png'"
                            class="w-full h-full object-contain"
                            alt="Logo"
                        >
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xs font-black tracking-wider text-white uppercase truncate">
                            IESTP "FRANCISCO VIGO CABALLERO"
                        </h2>
                        <span class="text-[10px] text-teal-200 uppercase font-semibold tracking-wider block">
                            Biblioteca Admin
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-1 mx-auto" :class="{ 'lg:mx-0': librarySidebarOpen }">
                    <button
                        type="button"
                        @click="toggleLibrarySidebar"
                        class="hidden lg:flex text-teal-200 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition items-center justify-center"
                        :title="librarySidebarOpen ? 'Colapsar menú' : 'Expandir menú'"
                    >
                        <i :class="librarySidebarOpen ? 'bi-layout-sidebar-inset' : 'bi-layout-sidebar-inset-reverse'" class="text-base"></i>
                    </button>

                    <button
                        type="button"
                        @click="mobileMenuOpen = false"
                        class="lg:hidden text-teal-200 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition"
                    >
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Navigation Items (Matching Image 1) -->
            <div class="px-3 py-4 flex-1 overflow-y-auto space-y-1">
                <span
                    v-show="librarySidebarOpen"
                    class="text-[10px] font-black uppercase tracking-wider text-teal-200/60 px-3 block mb-2"
                >
                    GENERAL
                </span>

                <nav class="space-y-1">
                    <!-- 1. Libros Asignados -->
                    <Link
                        :href="route('admin.library.books.index')"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                        :class="isNavActive('/admin-biblioteca/libros')
                            ? 'bg-[#063d46] text-white font-bold shadow-xs border-l-4 border-teal-300'
                            : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                        title="Libros Asignados"
                    >
                        <i class="bi bi-journal-bookmark-fill text-base shrink-0"></i>
                        <span v-show="librarySidebarOpen" class="truncate">Libros Asignados</span>
                    </Link>

                    <!-- 2. Repositorio -->
                    <Link
                        :href="route('admin.library.repository')"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                        :class="isNavActive('/admin-biblioteca/repositorio')
                            ? 'bg-[#063d46] text-white font-bold shadow-xs border-l-4 border-teal-300'
                            : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                        title="Repositorio"
                    >
                        <i class="bi bi-folder2-open text-base shrink-0"></i>
                        <span v-show="librarySidebarOpen" class="truncate">Repositorio</span>
                    </Link>

                    <!-- 3. Administradores -->
                    <Link
                        :href="route('admin.library.administrators')"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                        :class="isNavActive('/admin-biblioteca/administradores')
                            ? 'bg-[#063d46] text-white font-bold shadow-xs border-l-4 border-teal-300'
                            : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                        title="Administradores"
                    >
                        <i class="bi bi-people-fill text-base shrink-0"></i>
                        <span v-show="librarySidebarOpen" class="truncate">Administradores</span>
                    </Link>

                    <!-- 4. Lectores -->
                    <Link
                        :href="route('admin.library.readers')"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                        :class="isNavActive('/admin-biblioteca/lectores')
                            ? 'bg-[#063d46] text-white font-bold shadow-xs border-l-4 border-teal-300'
                            : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                        title="Lectores"
                    >
                        <i class="bi bi-person-badge text-base shrink-0"></i>
                        <span v-show="librarySidebarOpen" class="truncate">Lectores</span>
                    </Link>

                    <!-- 5. Reportes -->
                    <Link
                        :href="route('admin.library.reports')"
                        class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                        :class="isNavActive('/admin-biblioteca/reportes')
                            ? 'bg-[#063d46] text-white font-bold shadow-xs border-l-4 border-teal-300'
                            : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                        title="Reportes"
                    >
                        <i class="bi bi-flag-fill text-base shrink-0"></i>
                        <span v-show="librarySidebarOpen" class="truncate">Reportes</span>
                    </Link>

                    <!-- Institutional Shortcuts -->
                    <div class="pt-4 border-t border-teal-800/60 mt-4 space-y-1">
                        <Link
                            :href="route('admin.dashboard.index')"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-medium text-teal-200 hover:bg-white/10 hover:text-white transition gap-3"
                            title="Panel General"
                        >
                            <i class="bi bi-arrow-left-circle text-base shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Panel General</span>
                        </Link>

                        <a
                            :href="route('biblioteca.index')"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-medium text-teal-200 hover:bg-white/10 hover:text-white transition gap-3"
                            title="Portal Lectores"
                        >
                            <i class="bi bi-box-arrow-up-right text-base shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Portal Lectores</span>
                        </a>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- MAIN LIBRARY CONTENT AREA: Full browser window -->
        <div class="flex-1 min-w-0 flex flex-col min-h-screen bg-slate-50 overflow-y-auto">
            <!-- Mobile Header with Drawer Toggle -->
            <header class="lg:hidden bg-[#0b4d57] text-white px-4 py-3 flex items-center justify-between border-b border-teal-800/50 sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <img
                        :src="enterprise.logo_path || '/images/logo.png'"
                        class="w-7 h-7 object-contain rounded"
                        alt="Logo"
                    >
                    <span class="text-xs font-bold uppercase text-white tracking-wide truncate">Biblioteca Admin</span>
                </div>
                <button
                    type="button"
                    @click="mobileMenuOpen = true"
                    class="text-teal-100 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition"
                    title="Abrir menú"
                >
                    <i class="bi bi-list text-2xl leading-none"></i>
                </button>
            </header>

            <main class="flex-1 min-w-0">
                <slot />
            </main>
        </div>
    </div>
</template>
