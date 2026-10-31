<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    role: {
        type: Object,
        required: true,
    },
    permissions: {
        type: Array,
        default: () => [],
    },
    rolePermissions: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    name: props.role.name || '',
    permissions: [...(props.rolePermissions || [])],
})

function selectAll() {
    form.permissions = props.permissions.map(p => p.name)
}

function unselectAll() {
    form.permissions = []
}

function submit() {
    form.put(route('admin.roles.update', props.role.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <AdminLayout title="Editar Rol">
        <Head :title="`Editar: ${role.name} - Panel Administrativo`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs text-gray-400 font-semibold">ID: #{{ role.id }}</span>
                        <span v-if="['Administrador', 'Director', 'Admin'].includes(role.name)"
                              class="text-[10px] font-bold px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md">
                            Rol del Sistema
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-shield-check text-lg"></i>
                        </span>
                        Editar Rol y Permisos
                    </h1>
                </div>

                <Link :href="route('admin.roles.index')"
                      class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition-all">
                    <i class="bi bi-arrow-left"></i> Volver a Roles
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nombre del Rol -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            Nombre del Rol <span class="text-rose-500">*</span>
                        </label>
                        <input v-model="form.name"
                               type="text"
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border rounded-xl transition-all focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500"
                               :class="form.errors.name ? 'border-rose-400 bg-rose-50/20' : 'border-gray-200'" />
                        <p v-if="form.errors.name" class="text-xs text-rose-500 font-semibold mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Permisos del Sistema -->
                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">Permisos Modulares del Sistema</h3>
                                <p class="text-xs text-gray-500">Selecciona o desmarca los permisos asignados a este perfil.</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <button type="button"
                                        @click="selectAll"
                                        class="font-bold text-purple-600 hover:text-purple-700 hover:underline">
                                    Seleccionar todos
                                </button>
                                <span class="text-gray-300">|</span>
                                <button type="button"
                                        @click="unselectAll"
                                        class="font-bold text-gray-500 hover:text-gray-700 hover:underline">
                                    Desmarcar todos
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                            <label v-for="perm in permissions"
                                   :key="perm.id"
                                   class="p-3 bg-gray-50 hover:bg-purple-50/50 border border-gray-200 hover:border-purple-300 rounded-xl cursor-pointer transition-all flex items-start gap-3 group">
                                <input v-model="form.permissions"
                                       type="checkbox"
                                       :value="perm.name"
                                       class="mt-0.5 h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer" />
                                <div>
                                    <p class="text-xs font-bold text-gray-800 group-hover:text-purple-700 transition-colors">
                                        {{ perm.name }}
                                    </p>
                                    <p class="text-[11px] text-gray-500">
                                        Acceso a {{ perm.name.replace('gestionar-', '') }}
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <Link :href="route('admin.roles.index')"
                              class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors">
                            Cancelar
                        </Link>
                        <button type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all flex items-center gap-2 disabled:opacity-60">
                            <i v-if="!form.processing" class="bi bi-check-lg text-lg"></i>
                            <span v-if="form.processing" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Actualizando...
                            </span>
                            <span v-else>Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
