<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    roles: {
        type: Array,
        default: () => [],
    },
    permissions: {
        type: Array,
        default: () => [],
    },
    search: {
        type: String,
        default: '',
    },
})

const searchQuery = ref(props.search || '')
const categoryFilter = ref('')
const viewMode = ref('grid') // 'grid' | 'table'

// Delete modal state
const showDeleteModal = ref(false)
const roleToDelete = ref(null)
const isDeleting = ref(false)

const systemRoles = ['Administrador', 'Director', 'Admin']

const filteredRoles = computed(() => {
    return props.roles.filter((role) => {
        const matchesSearch = !searchQuery.value ||
            role.name.toLowerCase().includes(searchQuery.value.toLowerCase())

        let matchesCat = true
        if (categoryFilter.value) {
            matchesCat = role.name.toLowerCase().includes(categoryFilter.value.toLowerCase())
        }

        return matchesSearch && matchesCat
    })
})

const stats = computed(() => {
    const total = props.roles.length
    const permCount = props.permissions.length
    const coordinations = props.roles.filter(r => r.name.toLowerCase().includes('coordinador')).length
    const totalUsers = props.roles.reduce((acc, r) => acc + (r.users_count || 0), 0)
    return { total, permCount, coordinations, totalUsers }
})

function confirmDelete(role) {
    if (systemRoles.includes(role.name)) {
        alert(`El rol '${role.name}' es un rol del sistema y no puede ser eliminado.`)
        return
    }
    roleToDelete.value = role
    showDeleteModal.value = true
}

function deleteRole() {
    if (!roleToDelete.value) return
    isDeleting.value = true

    router.delete(route('admin.roles.destroy', roleToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            isDeleting.value = false
            showDeleteModal.value = false
            roleToDelete.value = null
        },
    })
}
</script>

<template>
    <AdminLayout title="Gestión de Roles y Permisos">
        <Head title="Roles y Permisos - Panel Administrativo" />

        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                        <span class="p-2.5 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-xl shadow-md shadow-purple-500/20">
                            <i class="bi bi-shield-lock-fill text-lg"></i>
                        </span>
                        Roles y Permisos del Sistema
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Administra los roles institucionales (Director, Docentes, Administradores, Coordinadores) y asigna privilegios.
                    </p>
                </div>

                <Link :href="route('admin.roles.create')"
                      class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-xs rounded-xl shadow-md shadow-purple-600/20 hover:from-purple-700 hover:to-indigo-700 transition-all gap-2">
                    <i class="bi bi-shield-plus text-base"></i>
                    <span>Crear Nuevo Rol</span>
                </Link>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="bi bi-shield-lock-fill text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-gray-900">{{ stats.total }}</p>
                            <p class="text-xs text-gray-400 font-medium">Total Roles</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="bi bi-key-fill text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-gray-900">{{ stats.permCount }}</p>
                            <p class="text-xs text-gray-400 font-medium">Permisos del Sistema</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class="bi bi-person-badge-fill text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-gray-900">{{ stats.coordinations }}</p>
                            <p class="text-xs text-gray-400 font-medium">Coordinaciones</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <i class="bi bi-people-fill text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-gray-900">{{ stats.totalUsers }}</p>
                            <p class="text-xs text-gray-400 font-medium">Usuarios Asignados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-80">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input v-model="searchQuery"
                               type="text"
                               placeholder="Buscar por nombre de rol..."
                               class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                    </div>

                    <select v-model="categoryFilter"
                            class="w-full sm:w-48 py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                        <option value="">Todas las categorías</option>
                        <option value="coordinador">Coordinaciones</option>
                        <option value="director">Dirección</option>
                        <option value="docente">Docencia</option>
                        <option value="admin">Administración</option>
                    </select>
                </div>

                <!-- View Switcher -->
                <div class="flex items-center bg-gray-100 p-1 rounded-xl shrink-0 self-end sm:self-center">
                    <button type="button"
                            @click="viewMode = 'grid'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5"
                            :class="viewMode === 'grid' ? 'bg-white text-purple-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                        <i class="bi bi-grid-fill"></i> Cuadrícula
                    </button>
                    <button type="button"
                            @click="viewMode = 'table'"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5"
                            :class="viewMode === 'table' ? 'bg-white text-purple-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                        <i class="bi bi-table"></i> Tabla
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredRoles.length === 0" class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="bi bi-shield-x"></i>
                </div>
                <h3 class="text-base font-bold text-gray-800">No se encontraron roles</h3>
                <p class="text-xs text-gray-500 mt-1">Prueba con otro término de búsqueda o categoría.</p>
            </div>

            <!-- Grid View -->
            <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="role in filteredRoles"
                     :key="role.id"
                     class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all p-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-sm">
                                    {{ role.name.charAt(0) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                        {{ role.name }}
                                        <span v-if="systemRoles.includes(role.name)"
                                              class="text-[10px] font-bold px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md">
                                            Sistema
                                        </span>
                                    </h3>
                                    <p class="text-xs text-gray-400">ID: #{{ role.id }}</p>
                                </div>
                            </div>

                            <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg">
                                {{ role.users_count || 0 }} usuarios
                            </span>
                        </div>

                        <!-- Permissions summary -->
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Permisos Asignados ({{ role.permissions ? role.permissions.length : 0 }})
                            </p>
                            <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto pr-1">
                                <span v-for="perm in (role.permissions || []).slice(0, 6)"
                                      :key="perm.id"
                                      class="text-[11px] font-medium px-2 py-0.5 bg-purple-50 text-purple-700 rounded-md border border-purple-100">
                                    {{ perm.name.replace('gestionar-', '') }}
                                </span>
                                <span v-if="role.permissions && role.permissions.length > 6"
                                      class="text-[11px] font-bold px-2 py-0.5 bg-gray-100 text-gray-500 rounded-md">
                                    +{{ role.permissions.length - 6 }} más
                                </span>
                                <span v-if="!role.permissions || role.permissions.length === 0"
                                      class="text-xs text-gray-400 italic">
                                    Sin permisos especiales
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-4 mt-4 border-t border-gray-100">
                        <Link :href="route('admin.roles.edit', role.id)"
                              class="p-2 text-purple-600 hover:bg-purple-50 rounded-xl transition-colors font-medium text-xs flex items-center gap-1.5">
                            <i class="bi bi-pencil-square"></i> Editar
                        </Link>
                        <button v-if="!systemRoles.includes(role.name)"
                                @click="confirmDelete(role)"
                                type="button"
                                class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-medium text-xs flex items-center gap-1.5">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table View -->
            <div v-else class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50/75 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">Rol</th>
                                <th class="px-6 py-3.5">Tipo</th>
                                <th class="px-6 py-3.5">Usuarios</th>
                                <th class="px-6 py-3.5">Permisos</th>
                                <th class="px-6 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="role in filteredRoles" :key="role.id" class="hover:bg-purple-50/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                    {{ role.name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="systemRoles.includes(role.name)"
                                          class="px-2.5 py-1 text-[11px] font-bold bg-amber-100 text-amber-700 rounded-md">
                                        Sistema
                                    </span>
                                    <span v-else class="px-2.5 py-1 text-[11px] font-bold bg-gray-100 text-gray-600 rounded-md">
                                        Personalizado
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-700">
                                    {{ role.users_count || 0 }} usuarios
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100">
                                        {{ role.permissions ? role.permissions.length : 0 }} asignados
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <Link :href="route('admin.roles.edit', role.id)"
                                          class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-800 font-bold text-xs p-1">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </Link>
                                    <button v-if="!systemRoles.includes(role.name)"
                                            @click="confirmDelete(role)"
                                            type="button"
                                            class="inline-flex items-center gap-1 text-rose-500 hover:text-rose-700 font-bold text-xs p-1">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>

                    <div class="text-center">
                        <h3 class="text-base font-bold text-gray-900">¿Eliminar este Rol?</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Estás a punto de eliminar el rol <strong class="text-gray-800">{{ roleToDelete?.name }}</strong>. Los usuarios asignados perderán estos privilegios.
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button @click="showDeleteModal = false"
                                type="button"
                                class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                            Cancelar
                        </button>
                        <button @click="deleteRole"
                                :disabled="isDeleting"
                                type="button"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-2 disabled:opacity-60">
                            <span v-if="isDeleting">Eliminando...</span>
                            <span v-else>Sí, Eliminar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
