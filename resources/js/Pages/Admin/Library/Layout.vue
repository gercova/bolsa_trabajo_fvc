<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
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
    <AdminLayout
        :title="title"
        :subtitle="subtitle"
        icon="bi-book-half"
        :breadcrumbs="breadcrumbs"
    >
        <div class="flex w-full min-h-[calc(100vh-120px)] bg-slate-100/70 font-sans text-slate-800 rounded-2xl overflow-hidden shadow-xs border border-slate-200">
            <!-- SIDEBAR: Admin Library Navigation (Dark Teal #0b4d57) --> 
            <aside
                class="bg-[#0b4d57] text-white flex-shrink-0 transition-all duration-300 ease-in-out relative z-20 shadow-lg flex flex-col"
                :class="librarySidebarOpen ? 'w-64' : 'w-16 lg:w-20'"
            >
                <!-- Brand Header -->
                <div class="px-4 py-4 border-b border-teal-800/60 flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden" v-show="librarySidebarOpen">
                        <div class="w-9 h-9 rounded-xl bg-white/10 p-1 flex-shrink-0 flex items-center justify-center border border-white/20">
                            <img
                                :src="enterprise.logo_path || '/images/logo.png'"
                                class="w-full h-full object-contain"
                                alt="Logo"
                            >
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-[11px] font-black tracking-wider text-white uppercase truncate">
                                IESTP FVC
                            </h2>
                            <span class="text-[10px] text-teal-200 uppercase font-semibold tracking-wider block">
                                Biblioteca Admin
                            </span>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="toggleLibrarySidebar"
                        class="text-teal-200 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition flex items-center justify-center mx-auto"
                        :title="librarySidebarOpen ? 'Colapsar menú' : 'Expandir menú'"
                    >
                        <i :class="librarySidebarOpen ? 'bi-layout-sidebar-inset' : 'bi-layout-sidebar-inset-reverse'" class="text-base"></i>
                    </button>
                </div>

                <!-- Navigation Items -->
                <div class="px-2 py-4 flex-1 overflow-y-auto space-y-1">
                    <span
                        v-show="librarySidebarOpen"
                        class="text-[10px] font-black uppercase tracking-wider text-teal-200/60 px-3 block mb-2"
                    >
                        GESTIÓN GENERAL
                    </span>

                    <nav class="space-y-1">
                        <!-- 1. Libros Asignados -->
                        <Link
                            :href="route('admin.library.books.index')"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                            :class="isNavActive('/admin-biblioteca/libros')
                                ? 'bg-teal-900/90 text-white font-bold shadow-xs border-l-4 border-teal-300'
                                : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                            title="Libros Asignados"
                        >
                            <i class="bi bi-journal-bookmark-fill text-base flex-shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Libros Asignados</span>
                        </Link>

                        <!-- 2. Repositorio -->
                        <Link
                            :href="route('admin.library.repository')"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                            :class="isNavActive('/admin-biblioteca/repositorio')
                                ? 'bg-teal-900/90 text-white font-bold shadow-xs border-l-4 border-teal-300'
                                : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                            title="Repositorio"
                        >
                            <i class="bi bi-folder2-open text-base flex-shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Repositorio</span>
                        </Link>

                        <!-- 3. Administradores -->
                        <Link
                            :href="route('admin.library.administrators')"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                            :class="isNavActive('/admin-biblioteca/administradores')
                                ? 'bg-teal-900/90 text-white font-bold shadow-xs border-l-4 border-teal-300'
                                : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                            title="Administradores"
                        >
                            <i class="bi bi-people-fill text-base flex-shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Administradores</span>
                        </Link>

                        <!-- 4. Lectores -->
                        <Link
                            :href="route('admin.library.readers')"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                            :class="isNavActive('/admin-biblioteca/lectores')
                                ? 'bg-teal-900/90 text-white font-bold shadow-xs border-l-4 border-teal-300'
                                : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                            title="Lectores"
                        >
                            <i class="bi bi-person-video2 text-base flex-shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Lectores</span>
                        </Link>

                        <!-- 5. Reportes -->
                        <Link
                            :href="route('admin.library.reports')"
                            class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition-all gap-3"
                            :class="isNavActive('/admin-biblioteca/reportes')
                                ? 'bg-teal-900/90 text-white font-bold shadow-xs border-l-4 border-teal-300'
                                : 'text-teal-100/80 hover:bg-white/10 hover:text-white'"
                            title="Reportes"
                        >
                            <i class="bi bi-flag-fill text-base flex-shrink-0"></i>
                            <span v-show="librarySidebarOpen" class="truncate">Reportes</span>
                        </Link>

                        <!-- Institutional Shortcuts -->
                        <div class="pt-4 border-t border-teal-800/60 mt-4 space-y-1">
                            <Link
                                :href="route('admin.dashboard.index')"
                                class="flex items-center px-3 py-2 rounded-xl text-xs font-medium text-teal-200 hover:bg-white/10 hover:text-white transition gap-3"
                                title="Panel General"
                            >
                                <i class="bi bi-arrow-left-circle text-base flex-shrink-0"></i>
                                <span v-show="librarySidebarOpen" class="truncate">Panel General</span>
                            </Link>

                            <a
                                :href="route('biblioteca.index')"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center px-3 py-2 rounded-xl text-xs font-medium text-teal-200 hover:bg-white/10 hover:text-white transition gap-3"
                                title="Portal Lectores"
                            >
                                <i class="bi bi-box-arrow-up-right text-base flex-shrink-0"></i>
                                <span v-show="librarySidebarOpen" class="truncate">Portal Lectores</span>
                            </a>
                        </div>
                    </nav>
                </div>
            </aside>
            <!-- MAIN LIBRARY BODY -->
            <main class="flex-1 min-w-0 bg-slate-50 overflow-y-auto">
                <slot />
            </main>
        </div>
    </AdminLayout>
</template>
