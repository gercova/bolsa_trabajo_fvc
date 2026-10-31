<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    requirement: {
        type: Object,
        required: true,
    },
    programs: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    study_program_id: props.requirement.study_program_id || '',
    description: props.requirement.description || '',
    order: props.requirement.order ?? 0,
    is_active: Boolean(props.requirement.is_active),
});

const submit = () => {
    form.put(route('admin.programs.requirements.update', props.requirement.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Editar Requisito — Panel Administrativo" />

    <AdminLayout title="Editar Requisito de Admisión / Matrícula">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Breadcrumbs / Back button -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('admin.programs.index', { tab: 'requirements' })"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors"
                >
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span>Volver a Requisitos</span>
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
                        <h3 class="text-sm font-bold text-gray-900">Editar Requisito de Admisión</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Modifica la descripción del documento o requisito exigido.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Programa de Estudio -->
                    <div class="space-y-1.5">
                        <label for="study_program_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            Programa de Estudio <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="study_program_id"
                            v-model="form.study_program_id"
                            required
                            :class="[
                                'w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                form.errors.study_program_id ? 'border-red-500' : 'border-gray-200'
                            ]"
                        >
                            <option value="">Seleccione el Programa de Estudio</option>
                            <option v-for="prog in programs" :key="prog.id" :value="prog.id">
                                {{ prog.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.study_program_id" class="text-xs text-red-500 font-medium">{{ form.errors.study_program_id }}</p>
                    </div>

                    <!-- Descripción del Requisito -->
                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            Descripción del Requisito <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            placeholder="Ej: Certificado de estudios secundarios concluidos en original..."
                            required
                            :class="[
                                'w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm transition-all focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent',
                                form.errors.description ? 'border-red-500' : 'border-gray-200'
                            ]"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-xs text-red-500 font-medium">{{ form.errors.description }}</p>
                    </div>

                    <!-- Orden -->
                    <div class="space-y-1.5">
                        <label for="order" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                            Orden de Visualización
                        </label>
                        <input
                            id="order"
                            v-model.number="form.order"
                            type="number"
                            min="0"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        />
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
                                <span class="text-sm font-bold text-gray-900 block">Requisito Activo</span>
                                <span class="text-xs text-gray-500 block">Los registros activos se muestran en la sección de admisión del programa.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <Link
                            :href="route('admin.programs.index', { tab: 'requirements' })"
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
                            <span>{{ form.processing ? 'Actualizando...' : 'Actualizar Requisito' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
