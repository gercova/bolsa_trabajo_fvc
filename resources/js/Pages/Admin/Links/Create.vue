<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from '@/route';

const suggestedIcons = [
    { icon: 'bi-pencil-square', label: 'Registra (Matrícula)' },
    { icon: 'bi-award', label: 'Titula (Títulos)' },
    { icon: 'bi-people', label: 'Conecta (Comunidad)' },
    { icon: 'bi-graph-up-arrow', label: 'Avanza (Trayectoria)' },
    { icon: 'bi-box-arrow-up-right', label: 'Enlace Externo' },
    { icon: 'bi-globe', label: 'Portal Web' },
    { icon: 'bi-book', label: 'Biblioteca / Lectura' },
    { icon: 'bi-building', label: 'Institucional' },
    { icon: 'bi-shield-check', label: 'Verificado' },
];

const form = useForm({
    name: '',
    link: '',
    icon: 'bi-box-arrow-up-right',
    is_active: true,
});

const submit = () => {
    form.post(route('admin.links.store'), {
        preserveScroll: true,
    });
};

const selectIcon = (iconClass) => {
    form.icon = iconClass;
};
</script>

<template>
    <Head title="Nuevo Enlace Institucional - Panel Administrativo" />

    <AdminLayout title="Nuevo Enlace Institucional">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.links.index')"
                        class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition"
                    >
                        <i class="bi bi-arrow-left text-xl"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                            <i class="bi bi-box-arrow-up-right text-purple-600"></i>
                            Nuevo Enlace Institucional
                        </h1>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Registre un nuevo acceso directo a servicios o plataformas institucionales.
                        </p>
                    </div>
                </div>

                <Link
                    :href="route('admin.links.index')"
                    class="px-4 py-2 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition"
                >
                    Volver
                </Link>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                    <!-- General validation alert -->
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                Nombre de la Plataforma / Servicio <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Ej. Registra, Titula, Conecta, Avanza..."
                                :class="[
                                    'w-full text-base font-semibold px-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400',
                                    form.errors.name ? 'border-rose-500 bg-rose-50/20' : 'border-gray-300'
                                ]"
                                required
                            />
                            <p v-if="form.errors.name" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.name }}</p>
                        </div>

                        <!-- URL Link -->
                        <div class="md:col-span-2">
                            <label for="link" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                                Dirección URL Destino <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    <i class="bi bi-globe"></i>
                                </span>
                                <input
                                    id="link"
                                    v-model="form.link"
                                    type="url"
                                    placeholder="https://registra.minedu.gob.pe/"
                                    :class="[
                                        'w-full text-sm font-mono pl-10 pr-4 py-3 border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400',
                                        form.errors.link ? 'border-rose-500 bg-rose-50/20' : 'border-gray-300'
                                    ]"
                                    required
                                />
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1">Ingrese la URL completa incluyendo <code>https://</code></p>
                            <p v-if="form.errors.link" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.link }}</p>
                        </div>

                        <!-- Bootstrap Icon with suggestions and live preview -->
                        <div class="md:col-span-2 space-y-3">
                            <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                Icono Representativo (Clase Bootstrap Icons)
                            </label>

                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 border border-purple-300 text-purple-700 flex items-center justify-center text-2xl shadow-sm shrink-0">
                                    <i :class="['bi', form.icon || 'bi-box-arrow-up-right']"></i>
                                </div>
                                <input
                                    id="icon"
                                    v-model="form.icon"
                                    type="text"
                                    placeholder="bi-box-arrow-up-right"
                                    :class="[
                                        'w-full text-sm font-mono px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all',
                                        form.errors.icon ? 'border-rose-500 bg-rose-50/20' : 'border-gray-300'
                                    ]"
                                />
                            </div>

                            <!-- Suggested Icons Pills -->
                            <div>
                                <p class="text-[11px] text-gray-500 mb-2 font-medium">Selección rápida de iconos sugeridos:</p>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-for="item in suggestedIcons"
                                        :key="item.icon"
                                        type="button"
                                        @click="selectIcon(item.icon)"
                                        :class="[
                                            'px-2.5 py-1.5 rounded-lg border text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer',
                                            form.icon === item.icon
                                                ? 'bg-purple-600 text-white border-purple-600 shadow-sm'
                                                : 'bg-gray-100 hover:bg-purple-50 text-gray-700 border-gray-200'
                                        ]"
                                    >
                                        <i :class="['bi', item.icon]"></i>
                                        <span>{{ item.label }}</span>
                                    </button>
                                </div>
                            </div>
                            <p v-if="form.errors.icon" class="text-xs text-rose-600 mt-1 font-medium">{{ form.errors.icon }}</p>
                        </div>

                        <!-- Active State -->
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-3 cursor-pointer">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="w-5 h-5 text-purple-600 rounded-md border-gray-300 focus:ring-purple-500 cursor-pointer"
                                />
                                <div>
                                    <span class="text-sm font-bold text-gray-800">Enlace Activo y Publicado</span>
                                    <p class="text-xs text-gray-500">
                                        Si está activo, el enlace se mostrará públicamente en la sección institucional.
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Live Card Preview -->
                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">
                            Vista Previa de Tarjeta Pública
                        </label>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex items-center gap-4 max-w-sm">
                            <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center text-xl shadow-md shadow-purple-500/20">
                                <i :class="['bi', form.icon || 'bi-box-arrow-up-right']"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-extrabold text-gray-900 truncate">
                                    {{ form.name || 'Nombre del Enlace' }}
                                </h4>
                                <p class="text-xs text-purple-600 font-medium truncate">
                                    {{ form.link || 'https://enlace-institucional.gob.pe' }}
                                </p>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
                        <Link
                            :href="route('admin.links.index')"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 transition-all flex items-center gap-2 disabled:opacity-50"
                        >
                            <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                            <i v-else class="bi bi-save"></i>
                            <span>{{ form.processing ? 'Guardando...' : 'Guardar Enlace' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
