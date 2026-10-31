<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
    programs: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    program_id: props.module.program_id || '',
    module: props.module.module || '',
    is_active: Boolean(props.module.is_active),
});

const submit = () => {
    form.put(route('admin.programs.modules.update', props.module.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Editar Módulo: ${module.module} — Panel Administrativo`" />

    <AdminLayout title="Editar Módulo de Certificación">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Breadcrumbs / Back button -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('admin.programs.index', { tab: 'modules' })"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors"
                >
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span>Volver a Certificaciones Modulares</span>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
                <!-- Banner -->
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-500/10 via-indigo-500/10 to-blue-500/10 border border-purple-100">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-pencil-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Editar Módulo de Certificación</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Modifica el nombre del módulo formativo o el programa al que pertenece.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Programa de Estudio -->
                    <div class="space-y-1.5">
                        <label for="program_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            Programa de Estudio <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="program_id"
                            v-model="form.program_id"
                            required
                            :class="[
                                'w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                form.errors.program_id ? 'border-red-500' : 'border-gray-200'
                            ]"
                        >
                            <option value="">Seleccione el Programa de Estudio</option>
                            <option v-for="prog in programs" :key="prog.id" :value="prog.id">
                                {{ prog.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.program_id" class="text-xs text-red-500 font-medium">{{ form.errors.program_id }}</p>
                    </div>

                    <!-- Nombre del Módulo -->
                    <div class="space-y-1.5">
                        <label for="module" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            Nombre del Módulo de Certificación <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="bi bi-patch-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                            <input
                                id="module"
                                v-model="form.module"
                                type="text"
                                placeholder="Ej: Implementación de Infraestructura Agropecuaria"
                                required
                                :class="[
                                    'w-full pl-10 pr-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                    form.errors.module ? 'border-red-500' : 'border-gray-200'
                                ]"
                            />
                        </div>
                        <p v-if="form.errors.module" class="text-xs text-red-500 font-medium">{{ form.errors.module }}</p>
                    </div>

                    <!-- Estado Activo -->
                    <div class="pt-2">
                        <label class="inline-flex items-center cursor-pointer gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100/80 transition-colors w-full">
                            <input
                                type="checkbox"
                                v-model="form.is_active"
                                class="sr-only peer"
                            />
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 relative"></div>
                            <div>
                                <span class="text-sm font-bold text-gray-900 block">Módulo Activo</span>
                                <span class="text-xs text-gray-500 block">Los módulos activos se muestran en la sección académica del programa.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <Link
                            :href="route('admin.programs.index', { tab: 'modules' })"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-md hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 inline-flex items-center gap-2 cursor-pointer disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-check-circle-fill"></i>
                            <span>{{ form.processing ? 'Actualizando...' : 'Actualizar Módulo' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
