<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    area: {
        type: Object,
        required: true,
    },
    programs: {
        type: Array,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    name: props.area.name || '',
    program_id: props.area.program_id ?? '',
    user_id: props.area.user_id ?? '',
    description: props.area.description || '',
    details: props.area.details || '',
});

// Searchable user dropdown state
const userDropdownOpen = ref(false);
const userSearch = ref('');

const selectedUser = computed(() => {
    if (!form.user_id) return null;
    return props.users.find((u) => String(u.id) === String(form.user_id)) || null;
});

const filteredUsers = computed(() => {
    if (!userSearch.value.trim()) {
        return props.users;
    }
    const q = userSearch.value.toLowerCase();
    return props.users.filter((u) => {
        const fullName = `${u.names || ''} ${u.last_name1 || ''} ${u.last_name2 || ''}`.toLowerCase();
        const email = (u.email || '').toLowerCase();
        const role = (u.job_position || u.role || '').toLowerCase();
        return fullName.includes(q) || email.includes(q) || role.includes(q);
    });
});

const limitedUsers = computed(() => filteredUsers.value.slice(0, 6));

const selectUser = (userId) => {
    form.user_id = userId;
    userDropdownOpen.value = false;
    userSearch.value = '';
};

const clearUser = () => {
    form.user_id = '';
    userSearch.value = '';
};

const submit = () => {
    form.put(route('admin.areas.update', props.area.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Editar Área: ${area.name} - Panel Administrativo`" />

    <AdminLayout :title="`Editar Área: ${area.name}`">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.areas.index')"
                        class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition"
                    >
                        <i class="bi bi-arrow-left text-xl"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                            <i class="bi bi-pencil-square text-purple-600"></i>
                            Editar Área Institucional
                        </h1>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Modifique los datos y asignación del área «{{ area.name }}».
                        </p>
                    </div>
                </div>

                <Link
                    :href="route('admin.areas.index')"
                    class="px-4 py-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition"
                >
                    Volver
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-gray-100 bg-gray-50/40">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-info-circle text-purple-600"></i>
                        Información del Área
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Actualice los campos requeridos para esta área o dependencia institucional.
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                    <!-- Validation Errors -->
                    <div v-if="form.hasErrors" class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl shadow-sm">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-bold text-rose-800">Se encontraron errores en el formulario:</h3>
                                <ul class="mt-1 text-xs text-rose-700 space-y-1 list-disc list-inside">
                                    <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Area Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-sm font-semibold text-gray-700">
                            Nombre del Área o Dependencia <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            maxlength="100"
                            placeholder="Ej. Secretaría Académica, Jefatura de Unidad Académica, etc."
                            :class="[
                                'w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400',
                                form.errors.name ? 'border-red-500 bg-red-50/20' : 'border-gray-300'
                            ]"
                        />
                        <p v-if="form.errors.name" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- 2 Columns: Program & Responsible User -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Program -->
                        <div class="space-y-1.5">
                            <label for="program_id" class="block text-sm font-semibold text-gray-700">
                                Programa de Estudio Asociado
                            </label>
                            <select
                                id="program_id"
                                v-model="form.program_id"
                                :class="[
                                    'w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white text-gray-700',
                                    form.errors.program_id ? 'border-red-500 bg-red-50/20' : 'border-gray-300'
                                ]"
                            >
                                <option value="">Institucional General (Sin programa específico)</option>
                                <option v-for="program in programs" :key="program.id" :value="program.id">
                                    {{ program.name }}
                                </option>
                            </select>
                            <p class="text-[11px] text-gray-400">
                                Seleccione si el área pertenece exclusivamente a un programa de estudio.
                            </p>
                            <p v-if="form.errors.program_id" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.program_id }}
                            </p>
                        </div>

                        <!-- Responsible User Searchable Dropdown -->
                        <div class="space-y-1.5 relative">
                            <label class="block text-sm font-semibold text-gray-700">
                                Responsable / Encargado
                            </label>

                            <!-- Trigger -->
                            <button
                                type="button"
                                @click="userDropdownOpen = !userDropdownOpen"
                                :class="[
                                    'w-full px-4 py-2.5 text-left text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white flex items-center justify-between shadow-sm cursor-pointer',
                                    form.errors.user_id ? 'border-red-500 bg-red-50/20' : 'border-gray-300'
                                ]"
                            >
                                <div class="flex items-center gap-2.5 overflow-hidden truncate">
                                    <div v-if="selectedUser" class="flex items-center gap-2 truncate">
                                        <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                                            {{ (selectedUser.names || 'U').charAt(0).toUpperCase() }}
                                        </div>
                                        <span class="font-medium text-gray-800 truncate">
                                            {{ selectedUser.names }} {{ selectedUser.last_name1 || '' }}
                                        </span>
                                        <span class="text-xs text-gray-400 truncate">({{ selectedUser.email }})</span>
                                    </div>
                                    <span v-else class="text-gray-500 flex items-center gap-1.5 text-sm">
                                        <i class="bi bi-person-dash text-gray-400"></i>
                                        <span>Sin responsable asignado</span>
                                    </span>
                                </div>

                                <div class="flex items-center gap-1 text-gray-400 shrink-0 ml-2">
                                    <span
                                        v-if="form.user_id"
                                        role="button"
                                        @click.stop="clearUser"
                                        class="p-1 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors"
                                        title="Limpiar selección"
                                    >
                                        <i class="bi bi-x-lg text-xs"></i>
                                    </span>
                                    <i class="bi bi-chevron-expand text-sm"></i>
                                </div>
                            </button>

                            <!-- Dropdown panel -->
                            <div
                                v-if="userDropdownOpen"
                                class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden"
                            >
                                <!-- Search Input inside dropdown -->
                                <div class="p-2.5 border-b border-gray-100 bg-gray-50/70">
                                    <div class="relative">
                                        <input
                                            v-model="userSearch"
                                            type="text"
                                            placeholder="Buscar por nombre, correo o cargo..."
                                            class="w-full pl-9 pr-4 py-2 text-xs border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all bg-white outline-none"
                                        />
                                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    </div>
                                </div>

                                <!-- Options list -->
                                <ul class="max-h-56 overflow-y-auto divide-y divide-gray-50 py-1 text-xs">
                                    <!-- Option "Sin responsable" -->
                                    <li>
                                        <button
                                            type="button"
                                            @click="selectUser('')"
                                            :class="[
                                                'w-full px-3.5 py-2.5 text-left flex items-center justify-between hover:bg-purple-50 transition-colors cursor-pointer',
                                                !form.user_id ? 'bg-purple-50 font-semibold text-purple-700' : 'text-gray-600'
                                            ]"
                                        >
                                            <span class="flex items-center gap-2">
                                                <i class="bi bi-dash-circle text-gray-400"></i>
                                                <span>Sin responsable asignado</span>
                                            </span>
                                            <i v-if="!form.user_id" class="bi bi-check2 text-purple-600 text-sm font-bold"></i>
                                        </button>
                                    </li>

                                    <!-- User options -->
                                    <li v-for="user in limitedUsers" :key="user.id">
                                        <button
                                            type="button"
                                            @click="selectUser(user.id)"
                                            :class="[
                                                'w-full px-3.5 py-2.5 text-left flex items-center justify-between hover:bg-purple-50 transition-colors cursor-pointer',
                                                String(form.user_id) === String(user.id) ? 'bg-purple-50 font-semibold text-purple-700' : 'text-gray-700'
                                            ]"
                                        >
                                            <div class="flex items-center gap-2.5 overflow-hidden">
                                                <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center text-[10px] font-bold shrink-0">
                                                    {{ (user.names || 'U').charAt(0).toUpperCase() }}
                                                </div>
                                                <div class="leading-tight truncate">
                                                    <p class="font-medium text-gray-800 truncate">
                                                        {{ user.names }} {{ user.last_name1 || '' }} {{ user.last_name2 || '' }}
                                                    </p>
                                                    <p class="text-[11px] text-gray-400 truncate">
                                                        {{ user.email }} {{ user.job_position ? ' • ' + user.job_position : '' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <i v-if="String(form.user_id) === String(user.id)" class="bi bi-check2 text-purple-600 text-sm font-bold ml-2"></i>
                                        </button>
                                    </li>

                                    <li v-if="filteredUsers.length === 0" class="px-4 py-6 text-center text-xs text-gray-400">
                                        <i class="bi bi-search text-base block mb-1"></i>
                                        No se encontraron usuarios coincidentes
                                    </li>
                                </ul>

                                <!-- Dropdown Footer -->
                                <div class="px-3.5 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                                    <span>
                                        {{ filteredUsers.length > 6 ? `Mostrando 6 de ${filteredUsers.length} resultados` : `${filteredUsers.length} resultados` }}
                                    </span>
                                    <span class="text-[10px] text-purple-600 font-medium">Docentes / Administrativos</span>
                                </div>
                            </div>

                            <p class="text-[11px] text-gray-400">
                                Personal docente o administrativo encargado del área.
                            </p>
                            <p v-if="form.errors.user_id" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.user_id }}
                            </p>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="space-y-1.5">
                        <label for="description" class="block text-sm font-semibold text-gray-700">
                            Descripción del Área
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            maxlength="1000"
                            placeholder="Breve resumen del propósito, objetivos o alcance del área..."
                            :class="[
                                'w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400',
                                form.errors.description ? 'border-red-500 bg-red-50/20' : 'border-gray-300'
                            ]"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Details Field -->
                    <div class="space-y-1.5">
                        <label for="details" class="block text-sm font-semibold text-gray-700">
                            Funciones, Responsabilidades o Detalles Adicionales
                        </label>
                        <textarea
                            id="details"
                            v-model="form.details"
                            rows="4"
                            placeholder="Detalle de funciones reglamentarias, horarios de atención o información complementaria..."
                            :class="[
                                'w-full px-4 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400',
                                form.errors.details ? 'border-red-500 bg-red-50/20' : 'border-gray-300'
                            ]"
                        ></textarea>
                        <p v-if="form.errors.details" class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <i class="bi bi-exclamation-circle-fill"></i> {{ form.errors.details }}
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                        <Link
                            :href="route('admin.areas.index')"
                            class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition text-center"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/25 hover:shadow-purple-600/35 transition-all duration-200 cursor-pointer disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-check-lg text-lg"></i>
                            <span>{{ form.processing ? 'Actualizando...' : 'Actualizar Área' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
