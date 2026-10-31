<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
    programs: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    study_program_id: props.meta.study_program_id || '',
    icon: props.meta.icon || 'bi-mortarboard-fill',
    accent: props.meta.accent || 'blue',
    tag: props.meta.tag || '',
    color_bar: props.meta.color_bar || '',
    bg_badge: props.meta.bg_badge || '',
    glow_class: props.meta.glow_class || '',
    badge_class: props.meta.badge_class || '',
    accent_text: props.meta.accent_text || '',
    bullet_class: props.meta.bullet_class || '',
    icon_bg_class: props.meta.icon_bg_class || '',
    border_hover_class: props.meta.border_hover_class || '',
    badge_module_class: props.meta.badge_module_class || '',
    sidebar_icon_class: props.meta.sidebar_icon_class || '',
    cta_bg_class: props.meta.cta_bg_class || '',
    bar_color_class: props.meta.bar_color_class || '',
});

const submit = () => {
    form.put(route('admin.programs.meta.update', props.meta.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Editar Metadatos — Panel Administrativo" />

    <AdminLayout title="Editar Metadatos de Presentación">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Breadcrumbs / Back button -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('admin.programs.index', { tab: 'meta' })"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-purple-600 transition-colors"
                >
                    <i class="bi bi-arrow-left text-lg"></i>
                    <span>Volver a Metadatos</span>
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
                        <h3 class="text-sm font-bold text-gray-900">Editar Metadatos y Estilos</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Modifica los colores, estilos de insignia y presentación visual de la carrera.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Programa de Estudio -->
                        <div class="md:col-span-2 space-y-1.5">
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

                        <!-- Ícono Principal -->
                        <div class="space-y-1.5">
                            <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Ícono Principal (Bootstrap Icons)
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                                    <i :class="['bi', form.icon || 'bi-mortarboard-fill']"></i>
                                </div>
                                <input
                                    id="icon"
                                    v-model="form.icon"
                                    type="text"
                                    placeholder="Ej: bi-tree-fill, bi-heart-pulse-fill"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                />
                            </div>
                        </div>

                        <!-- Color Acento -->
                        <div class="space-y-1.5">
                            <label for="accent" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Color Acento (Tailwind)
                            </label>
                            <input
                                id="accent"
                                v-model="form.accent"
                                type="text"
                                placeholder="Ej: emerald, rose, sky, blue, teal"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Tag Destacado -->
                        <div class="space-y-1.5">
                            <label for="tag" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Etiqueta Destacada (Tag)
                            </label>
                            <input
                                id="tag"
                                v-model="form.tag"
                                type="text"
                                placeholder="Ej: Ciencias de la Salud, Producción & Campo"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Color de Barra -->
                        <div class="space-y-1.5">
                            <label for="color_bar" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Clase Color de Barra
                            </label>
                            <input
                                id="color_bar"
                                v-model="form.color_bar"
                                type="text"
                                placeholder="Ej: bg-emerald-500, bg-rose-500"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Badge -->
                        <div class="space-y-1.5">
                            <label for="bg_badge" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Clase Badge
                            </label>
                            <input
                                id="bg_badge"
                                v-model="form.bg_badge"
                                type="text"
                                placeholder="Ej: bg-emerald-50 text-emerald-800 border-emerald-100"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Glow Class -->
                        <div class="space-y-1.5">
                            <label for="glow_class" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Glow Class
                            </label>
                            <input
                                id="glow_class"
                                v-model="form.glow_class"
                                type="text"
                                placeholder="Ej: bg-emerald-500/20"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- CTA Background Class -->
                        <div class="space-y-1.5">
                            <label for="cta_bg_class" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                CTA Background Class
                            </label>
                            <input
                                id="cta_bg_class"
                                v-model="form.cta_bg_class"
                                type="text"
                                placeholder="Ej: from-emerald-600 to-teal-800"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>

                        <!-- Bullet Class -->
                        <div class="space-y-1.5">
                            <label for="bullet_class" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Bullet Class
                            </label>
                            <input
                                id="bullet_class"
                                v-model="form.bullet_class"
                                type="text"
                                placeholder="Ej: bg-emerald-600"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <Link
                            :href="route('admin.programs.index', { tab: 'meta' })"
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
                            <span>{{ form.processing ? 'Actualizando...' : 'Actualizar Metadatos' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
