<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    name: '',
    slug: '',
    sort_order: 1,
    vacancies: 0,
    discount_percentage: 100.00,
    discount_details: '',
    icon: 'bi-award',
    is_active: true,
    description: '',
    requirements: '',
});

const suggestedIcons = [
    { icon: 'bi-trophy', label: 'Primeros Puestos' },
    { icon: 'bi-person-arms-up', label: 'Deportistas' },
    { icon: 'bi-heart', label: 'Víctimas' },
    { icon: 'bi-book', label: 'Pre-Instituto' },
    { icon: 'bi-person-wheelchair', label: 'Discapacidad' },
    { icon: 'bi-mortarboard', label: 'Titulados' },
    { icon: 'bi-shield-fill', label: 'FF.AA.' },
    { icon: 'bi-award', label: 'General' },
    { icon: 'bi-star-fill', label: 'Destacados' },
    { icon: 'bi-check-circle-fill', label: 'Validado' },
];

const selectIcon = (iconClass) => {
    form.icon = iconClass;
};

const handleNameInput = (e) => {
    form.name = e.target.value;
    // Simple slug generator if user hasn't manually customized slug
    form.slug = e.target.value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

const submit = () => {
    form.post(route('admin.scholarships.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout title="Nueva Modalidad de Beca"
                 subtitle="Configura una nueva categoría o tipo de beca/crédito institucional"
                 icon="bi-award"
                 :breadcrumbs="[
                     { label: 'Becas y Créditos', url: route('admin.scholarships.index') },
                     { label: 'Crear Beca' }
                 ]">
        <div class="max-w-4xl mx-auto space-y-6">

            <div class="flex items-center justify-between">
                <Link :href="route('admin.scholarships.index')"
                      class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-purple-600 transition">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 text-lg shrink-0">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800">
                                Datos de la Modalidad
                            </h2>
                            <p class="text-xs text-slate-400">Nombre, slug, icono y porcentaje de beneficio</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nombre -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Nombre de la Modalidad <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" :value="form.name" @input="handleNameInput"
                                   placeholder="Ej: PRIMEROS PUESTOS, DEPORTISTAS CALIFICADOS, VÍCTIMAS DE VIOLENCIA..."
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"
                                   :class="form.errors.name ? 'border-rose-400 bg-rose-50/30' : ''" />
                            <p v-if="form.errors.name" class="text-rose-500 text-xs">{{ form.errors.name }}</p>
                        </div>

                        <!-- Slug -->
                        <div class="space-y-1.5">
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Enlace Slug <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-mono text-xs">/</span>
                                <input type="text" id="slug" v-model="form.slug" placeholder="primeros-puestos"
                                       class="w-full pl-7 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"
                                       :class="form.errors.slug ? 'border-rose-400 bg-rose-50/30' : ''" />
                            </div>
                            <p v-if="form.errors.slug" class="text-rose-500 text-xs">{{ form.errors.slug }}</p>
                        </div>

                        <!-- Orden -->
                        <div class="space-y-1.5">
                            <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Orden de Aparición
                            </label>
                            <input type="number" id="sort_order" v-model="form.sort_order" min="0"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                            <p class="text-[11px] text-slate-400">Número menor aparece primero en el portal</p>
                        </div>

                        <!-- Vacantes -->
                        <div class="space-y-1.5">
                            <label for="vacancies" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Plazas / Vacantes Reservadas
                            </label>
                            <input type="number" id="vacancies" v-model="form.vacancies" min="0" placeholder="0"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                            <p class="text-[11px] text-slate-400">0 si no tiene límite o es según evaluación</p>
                        </div>

                        <!-- Porcentaje Descuento -->
                        <div class="space-y-1.5">
                            <label for="discount_percentage" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Descuento Principal (%) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" max="100" id="discount_percentage" v-model="form.discount_percentage"
                                       class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">%</span>
                            </div>
                            <p class="text-[11px] text-slate-400">100% para beca completa, 50% para media beca</p>
                        </div>

                        <!-- Detalle Descuento -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="discount_details" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Detalle / Desglose de Descuentos
                            </label>
                            <input type="text" id="discount_details" v-model="form.discount_details"
                                   placeholder="Ej: 100% descuento 1° puesto y 50% descuento 2° puesto / Exoneración total de matrícula"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                        </div>

                        <!-- Icono Bootstrap -->
                        <div class="md:col-span-2 space-y-3 pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Icono Representativo
                            </label>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center text-2xl shadow-xs shrink-0">
                                    <i class="bi" :class="form.icon || 'bi-award'"></i>
                                </div>
                                <input type="text" v-model="form.icon" placeholder="bi-award"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition" />
                            </div>
                            <div class="space-y-1.5">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Iconos sugeridos:</p>
                                <div class="flex flex-wrap gap-2">
                                    <button v-for="item in suggestedIcons" :key="item.icon" type="button"
                                            @click="selectIcon(item.icon)"
                                            class="px-2.5 py-1 rounded-lg border text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer"
                                            :class="form.icon === item.icon ? 'bg-purple-600 text-white border-purple-600 shadow-xs' : 'bg-slate-50 hover:bg-purple-50 text-slate-700 border-slate-200'">
                                        <i class="bi" :class="item.icon"></i>
                                        <span>{{ item.label }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Estado Activo -->
                        <div class="md:col-span-2 pt-2 border-t border-slate-100">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" v-model="form.is_active"
                                       class="w-5 h-5 text-purple-600 bg-slate-100 border-slate-300 rounded focus:ring-purple-500">
                                <div>
                                    <span class="text-xs font-bold text-slate-800">Modalidad Activa y Visible</span>
                                    <p class="text-[11px] text-slate-400">Aparecerá en el portal público de becas y créditos</p>
                                </div>
                            </label>
                        </div>

                        <!-- Descripción -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Descripción Completa de la Modalidad
                            </label>
                            <textarea id="description" v-model="form.description" rows="3"
                                      placeholder="Detalla el alcance de la beca, a quién está dirigida y las consideraciones clave..."
                                      class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"></textarea>
                        </div>

                        <!-- Requisitos -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="requirements" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Requisitos y Documentación Solicitada
                            </label>
                            <textarea id="requirements" v-model="form.requirements" rows="3"
                                      placeholder="Documentos exigidos (uno por línea o separados por guión)..."
                                      class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 transition"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <Link :href="route('admin.scholarships.index')"
                          class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                        Cancelar
                    </Link>
                    <button type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-purple-500/20 transition disabled:opacity-50 flex items-center gap-2">
                        <i class="bi bi-save"></i>
                        <span>{{ form.processing ? 'Guardando...' : 'Crear Modalidad' }}</span>
                    </button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
