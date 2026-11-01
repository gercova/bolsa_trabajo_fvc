<script setup>
import { Head, Link } from '@inertiajs/vue3';
import LibraryLayout from './Layout.vue';
import route from '@/route';

defineProps({
    admins: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Administradores - Biblioteca" />

    <LibraryLayout>
        <div class="p-5 sm:p-7 space-y-6">

            <!-- Header -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="bi bi-people text-teal-800"></i> Administradores de Biblioteca
                    </h1>
                    <p class="text-xs text-slate-500">Personal con privilegios de gestión de contenidos, publicación y monitoreo de la biblioteca institucional.</p>
                </div>
                <div>
                    <Link
                        :href="route('admin.partners.index')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition inline-flex items-center gap-2"
                    >
                        <i class="bi bi-gear"></i>
                        <span>Gestionar Usuarios</span>
                    </Link>
                </div>
            </div>

            <!-- Administrators Table -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4">Administrador</th>
                                <th class="py-3.5 px-4">Rol Asignado</th>
                                <th class="py-3.5 px-4">Correo Electrónico</th>
                                <th class="py-3.5 px-4">Teléfono</th>
                                <th class="py-3.5 px-4 text-center">Estado</th>
                                <th class="py-3.5 px-4 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="admin in admins" :key="admin.id" class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-900 font-black text-xs flex items-center justify-center shrink-0">
                                        {{ (admin.names || 'AD').substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ admin.names }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">ID: #{{ admin.id }}</span>
                                    </div>
                                </td>

                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ admin.role }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-slate-600 font-medium">
                                    <a :href="`mailto:${admin.email}`" class="hover:text-teal-800 transition">
                                        {{ admin.email }}
                                    </a>
                                </td>

                                <td class="py-3 px-4 text-slate-500 font-mono">
                                    {{ admin.phone || '—' }}
                                </td>

                                <td class="py-3 px-4 text-center">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                                        :class="admin.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500'"
                                    >
                                        {{ admin.is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <Link
                                        :href="route('admin.profile.edit', admin.id)"
                                        class="p-1.5 text-slate-400 hover:text-teal-800 hover:bg-slate-100 rounded-lg inline-block transition"
                                        title="Ver Perfil"
                                    >
                                        <i class="bi bi-person-gear"></i>
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="!admins || admins.length === 0">
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    No se encontraron administradores registrados en el sistema.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </LibraryLayout>
</template>
