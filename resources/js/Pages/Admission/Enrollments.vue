<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed } from 'vue';

const page = usePage();

const props = defineProps({
    schedules:    { type: Array, default: () => [] },
    enrollments:  { type: Array, default: () => [] },
    requirements: { type: Array, default: () => [] },
    enterprise:   { type: Object, default: () => null },
});

// Fallback to shared enterprise
const ent = computed(() => props.enterprise || page.props.enterprise || {});

// Active Tab for enrollment types
const activeType = ref('ordinaria');

// Date formatting helper
const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const clean = String(dateStr).split('T')[0];
    const parts = clean.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
};

// Currency formatting helper
const formatCurrency = (val) => {
    return Number(val || 0).toLocaleString('es-PE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

// Helper for schedule fees
const ordFee = computed(() => {
    return props.schedules.find(s => s.enrollment_type === 'ordinaria')?.enrollment_fee;
});

const extFee = computed(() => {
    return props.schedules.find(s => s.enrollment_type === 'extraordinaria')?.enrollment_fee;
});
</script>

<template>
    <Head>
        <title>Matrículas — IESTP Francisco Vigo Caballero</title>
        <meta name="description" content="Información oficial sobre el proceso de matrícula ordinaria y extraordinaria del IESTP Francisco Vigo Caballero. Consulta calendarios, requisitos y cronogramas." />
        <meta name="keywords" content="matricula, fvc, instituto francisco vigo caballero, matriculas 2026, cronograma de matricula, matricula ordinaria, matricula extraordinaria, educacion superior tecnica" />
        <meta property="og:title" content="Matrículas — IESTP Francisco Vigo Caballero" />
        <meta property="og:description" content="Cronogramas oficiales, costos reglamentarios y guías paso a paso del proceso de matrícula." />
    </Head>

    <PublicLayout title="Matrículas">
        <!-- ═══ 1. HERO SECTION ═══════════════════════════════════════ -->
        <section class="relative bg-slate-900 text-white overflow-hidden py-20 lg:py-28 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
                <div class="inline-flex items-center gap-2 bg-slate-800 border border-slate-700 text-sky-400 text-sm font-bold px-5 py-2 rounded-full">
                    <i class="bi bi-mortarboard-fill"></i>
                    Proceso Oficial de Matrícula Académica
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                    Matrícula <span class="text-sky-400">Ordinaria</span> y
                    <span class="text-violet-400">Extraordinaria</span>
                </h1>
                <p class="text-lg sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                    Accede a los cronogramas oficiales, costos reglamentarios y guías paso a paso del proceso de matrícula para el período vigente.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                    <a href="#tipos-matricula"
                       class="inline-flex items-center justify-center px-8 py-4 text-base font-black text-white bg-blue-700 hover:bg-blue-800 rounded-xl transition-all shadow-md hover:-translate-y-0.5">
                        <i class="bi bi-list-check mr-2.5 text-xl text-white"></i>
                        Ver Tipos de Matrícula
                    </a>
                    <a href="#cronogramas"
                       class="inline-flex items-center justify-center px-8 py-4 text-base font-black text-white border-2 border-slate-700 hover:bg-slate-800 rounded-xl transition-all">
                        <i class="bi bi-calendar3 mr-2.5 text-xl text-slate-300"></i>
                        Cronogramas Vigentes
                    </a>
                </div>
            </div>
        </section>

        <!-- ═══ 2. TIPOS DE MATRÍCULA + STEPS ═════════════════════════ -->
        <section id="tipos-matricula" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest">¿Qué tipo de matrícula necesitas?</span>
                    <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Tipos de Matrícula</h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-base text-gray-500 mt-5 leading-relaxed">
                        Selecciona el tipo de matrícula que corresponde a tu situación y sigue los pasos indicados.
                    </p>
                </div>

                <!-- Type Selector Pills -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-14">
                    <button type="button"
                            @click="activeType = 'ordinaria'"
                            :class="[
                                'inline-flex items-center gap-2 px-7 py-2.5 rounded-full text-sm font-extrabold transition-all border-2',
                                activeType === 'ordinaria'
                                    ? 'bg-sky-600 text-white border-sky-600 shadow-lg shadow-sky-600/30'
                                    : 'bg-sky-50 text-sky-800 border-sky-200 hover:bg-sky-100'
                            ]">
                        <i class="bi bi-calendar2-check-fill text-xl"></i>
                        Matrícula Ordinaria
                    </button>

                    <button type="button"
                            @click="activeType = 'extraordinaria'"
                            :class="[
                                'inline-flex items-center gap-2 px-7 py-2.5 rounded-full text-sm font-extrabold transition-all border-2',
                                activeType === 'extraordinaria'
                                    ? 'bg-purple-600 text-white border-purple-600 shadow-lg shadow-purple-600/30'
                                    : 'bg-purple-50 text-purple-800 border-purple-200 hover:bg-purple-100'
                            ]">
                        <i class="bi bi-calendar2-x-fill text-xl"></i>
                        Matrícula Extraordinaria
                    </button>
                </div>

                <!-- ── ORDINARIA PANEL ── -->
                <div v-show="activeType === 'ordinaria'" class="transition-all duration-300">
                    <div class="max-w-5xl mx-auto">
                        <div class="bg-blue-50/70 border border-blue-200 rounded-3xl p-5 sm:p-8 lg:p-10 mb-10 shadow-sm">
                            <div class="flex items-start gap-4 mb-8">
                                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md">
                                    <i class="bi bi-calendar2-check-fill text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-blue-950">Matrícula Ordinaria</h3>
                                    <p class="text-blue-600 font-semibold mt-1">Dentro del período regular establecido por el calendario académico.</p>
                                </div>
                            </div>

                            <!-- Steps -->
                            <div class="space-y-8 pl-0 sm:pl-2">
                                <!-- Step 1 -->
                                <div class="step-card flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md z-10">01</div>
                                    <div class="bg-white rounded-2xl border border-blue-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-blue-950 mb-2 flex items-center gap-2">
                                            <i class="bi bi-bank text-blue-600"></i>
                                            Pago del Derecho de Matrícula
                                        </h4>
                                        <p class="text-gray-600 leading-relaxed">
                                            Efectúa el pago del derecho de matrícula en la <strong class="text-blue-800">Oficina de Administración</strong> del instituto.
                                            El costo está establecido según el TUPA institucional vigente.
                                        </p>
                                        <div v-if="ordFee && Number(ordFee) > 0"
                                             class="mt-4 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-extrabold px-4 py-2 rounded-xl">
                                            <i class="bi bi-cash-coin text-lg"></i>
                                            Costo: S/ {{ formatCurrency(ordFee) }}
                                        </div>
                                        <div v-else
                                             class="mt-4 inline-flex items-center gap-2 bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            <i class="bi bi-info-circle"></i>
                                            Consulta el costo en Administración
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="step-card flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md z-10">02</div>
                                    <div class="bg-white rounded-2xl border border-blue-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-blue-950 mb-2 flex items-center gap-2">
                                            <i class="bi bi-receipt text-blue-600"></i>
                                            Presentar Voucher a Secretaría Académica
                                        </h4>
                                        <p class="text-gray-600 leading-relaxed">
                                            Una vez realizado el pago, presenta el <strong class="text-blue-800">voucher o comprobante de pago</strong> en la
                                            <strong class="text-blue-800">Secretaría Académica</strong> para formalizar tu registro de matrícula.
                                        </p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span class="bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                                <i class="bi bi-file-earmark-text mr-1"></i> Voucher original
                                            </span>
                                            <span class="bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                                <i class="bi bi-person-badge mr-1"></i> DNI vigente
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="step-card flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-emerald-500 text-white font-black text-lg flex items-center justify-center shadow-md z-10">
                                        <i class="bi bi-check-lg text-2xl"></i>
                                    </div>
                                    <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-emerald-800 mb-2 flex items-center gap-2">
                                            <i class="bi bi-patch-check-fill text-emerald-600"></i>
                                            ¡Matrícula Completada!
                                        </h4>
                                        <p class="text-emerald-700 leading-relaxed">
                                            Una vez validado el voucher, tu matrícula queda registrada oficialmente. Recibirás tu constancia de matrícula firmada por la Secretaría Académica.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── EXTRAORDINARIA PANEL ── -->
                <div v-show="activeType === 'extraordinaria'" class="transition-all duration-300">
                    <div class="max-w-5xl mx-auto">
                        <div class="bg-purple-50/70 border border-purple-200 rounded-3xl p-5 sm:p-8 lg:p-10 mb-10 shadow-sm">
                            <div class="flex items-start gap-4 mb-8">
                                <div class="w-14 h-14 rounded-2xl bg-violet-700 text-white flex items-center justify-center shrink-0 shadow-md">
                                    <i class="bi bi-calendar2-x-fill text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-violet-950">Matrícula Extraordinaria</h3>
                                    <p class="text-violet-600 font-semibold mt-1">Fuera del período regular. Requiere presentación de FUT y documentación adicional.</p>
                                </div>
                            </div>

                            <!-- Discount Alerts -->
                            <div class="mb-8 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center">
                                    <i class="bi bi-star-fill text-base"></i>
                                </div>
                                <div>
                                    <h5 class="font-extrabold text-amber-900 mb-2">Descuentos Especiales Aplicables</h5>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="bi bi-trophy-fill text-amber-600"></i>
                                            Primer puesto de admisión: <strong class="ml-1">100% de descuento</strong>
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                            <i class="bi bi-shield-fill text-slate-500"></i>
                                            Miembros de las Fuerzas Armadas: <strong class="ml-1">50% de descuento</strong>
                                        </span>
                                    </div>
                                    <p class="text-xs text-amber-700 mt-2 font-medium">Los descuentos aplican sobre el derecho de matrícula y requieren documentación sustentatoria.</p>
                                </div>
                            </div>

                            <!-- Steps -->
                            <div class="space-y-8 pl-0 sm:pl-2">
                                <!-- Step 1 -->
                                <div class="step-card step-card-extra flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-violet-700 text-white font-black text-lg flex items-center justify-center shadow-md z-10">01</div>
                                    <div class="bg-white rounded-2xl border border-purple-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-violet-950 mb-2 flex items-center gap-2">
                                            <i class="bi bi-bank text-violet-600"></i>
                                            Pago del Derecho de Matrícula
                                        </h4>
                                        <p class="text-gray-600 leading-relaxed">
                                            Efectúa el pago del derecho de matrícula extraordinaria en la <strong class="text-violet-800">Oficina de Administración</strong>.
                                            Si eres primer puesto o miembro de las FFAA, presenta la documentación correspondiente para aplicar el descuento antes del pago.
                                        </p>
                                        <div v-if="extFee && Number(extFee) > 0"
                                             class="mt-4 inline-flex items-center gap-2 bg-violet-700 text-white text-sm font-extrabold px-4 py-2 rounded-xl">
                                            <i class="bi bi-cash-coin text-lg"></i>
                                            Costo: S/ {{ formatCurrency(extFee) }}
                                        </div>
                                        <div v-else
                                             class="mt-4 inline-flex items-center gap-2 bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-xl">
                                            <i class="bi bi-info-circle"></i>
                                            Consulta el costo en Administración
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="step-card step-card-extra flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-violet-700 text-white font-black text-lg flex items-center justify-center shadow-md z-10">02</div>
                                    <div class="bg-white rounded-2xl border border-purple-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-violet-950 mb-2 flex items-center gap-2">
                                            <i class="bi bi-file-earmark-medical text-violet-600"></i>
                                            Presentar FUT a la Oficina de Administración
                                        </h4>
                                        <p class="text-gray-600 leading-relaxed">
                                            Llena y presenta el formulario <strong class="text-violet-800">FUT (Formulario Único de Trámite)</strong> en la Oficina de Administración, adjuntando según corresponda:
                                        </p>
                                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div class="bg-purple-50 border border-purple-100 rounded-xl p-3 flex items-start gap-2.5">
                                                <i class="bi bi-shield-fill text-violet-500 mt-0.5 shrink-0"></i>
                                                <span class="text-sm text-violet-800 font-semibold">Libreta militar o carnet de identidad militar (Fuerzas Armadas)</span>
                                            </div>
                                            <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 flex items-start gap-2.5">
                                                <i class="bi bi-trophy-fill text-amber-500 mt-0.5 shrink-0"></i>
                                                <span class="text-sm text-amber-800 font-semibold">Resolución de reconocimiento como primer puesto de admisión</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="step-card step-card-extra flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-violet-700 text-white font-black text-lg flex items-center justify-center shadow-md z-10">03</div>
                                    <div class="bg-white rounded-2xl border border-purple-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-violet-950 mb-2 flex items-center gap-2">
                                            <i class="bi bi-receipt text-violet-600"></i>
                                            Presentar Voucher a Secretaría Académica
                                        </h4>
                                        <p class="text-gray-600 leading-relaxed">
                                            Una vez procesado el FUT, presenta el <strong class="text-violet-800">voucher de pago</strong> en la <strong class="text-violet-800">Secretaría Académica</strong> para completar el registro de matrícula.
                                        </p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span class="bg-purple-50 border border-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                                <i class="bi bi-file-earmark-text mr-1"></i> Voucher de pago
                                            </span>
                                            <span class="bg-purple-50 border border-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                                <i class="bi bi-file-earmark-check mr-1"></i> Copia del FUT aprobado
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div class="step-card step-card-extra flex gap-4 sm:gap-5">
                                    <div class="shrink-0 w-14 h-14 rounded-full bg-emerald-500 text-white font-black text-lg flex items-center justify-center shadow-md z-10">
                                        <i class="bi bi-check-lg text-2xl"></i>
                                    </div>
                                    <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-5 sm:p-6 flex-1 min-w-0 shadow-sm">
                                        <h4 class="text-lg font-extrabold text-emerald-800 mb-2 flex items-center gap-2">
                                            <i class="bi bi-patch-check-fill text-emerald-600"></i>
                                            ¡Matrícula Completada!
                                        </h4>
                                        <p class="text-emerald-700 leading-relaxed">
                                            Con la validación del voucher y el FUT aprobado, tu matrícula extraordinaria queda registrada oficialmente. Recibirás la constancia de matrícula por Secretaría Académica.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- ═══ 3. CRONOGRAMAS VIGENTES ═══════════════════════════════ -->
        <section id="cronogramas" class="py-20 bg-slate-50 border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest">Calendarios Académicos</span>
                    <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Cronogramas de Matrícula</h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-base text-gray-500 mt-5 leading-relaxed">
                        Cronogramas oficiales vigentes establecidos por la Secretaría Académica para el período en curso.
                    </p>
                </div>

                <!-- If new schedules exist -->
                <div v-if="schedules && schedules.length > 0"
                     class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">
                    <div v-for="schedule in schedules" :key="schedule.id"
                         :class="[
                             'schedule-card bg-white rounded-3xl border shadow-md overflow-hidden',
                             schedule.enrollment_type === 'ordinaria' ? 'border-blue-100' : 'border-violet-100'
                         ]">
                        
                        <!-- Card Header -->
                        <div :class="[
                            'px-7 py-5 flex flex-col sm:flex-row justify-between sm:items-center gap-3',
                            schedule.enrollment_type === 'ordinaria' ? 'bg-blue-800' : 'bg-violet-800'
                        ]">
                            <div class="flex items-center gap-3">
                                <span :class="[
                                    'text-sm font-extrabold px-3 py-1 rounded-full uppercase tracking-wider',
                                    schedule.enrollment_type === 'ordinaria' ? 'bg-blue-900/60 text-blue-200' : 'bg-violet-900/60 text-violet-200'
                                ]">
                                    {{ schedule.type_label || (schedule.enrollment_type === 'ordinaria' ? 'Matrícula Ordinaria' : 'Matrícula Extraordinaria') }}
                                </span>
                                <span class="text-white/80 text-sm font-bold">{{ schedule.academic_period }}</span>
                            </div>
                            
                            <span v-if="schedule.is_open"
                                  class="flex items-center gap-1.5 bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-extrabold px-3 py-1 rounded-full self-start sm:self-auto">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Abierto Ahora
                            </span>
                            <span v-else
                                  class="flex items-center gap-1.5 bg-white/10 border border-white/20 text-white/60 text-xs font-extrabold px-3 py-1 rounded-full self-start sm:self-auto">
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                                Próximo / Cerrado
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-7 space-y-5">
                            <!-- Dates Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div :class="[
                                    'border p-4 rounded-2xl flex items-center gap-3',
                                    schedule.enrollment_type === 'ordinaria' ? 'bg-blue-50/60 border-blue-100' : 'bg-violet-50/60 border-violet-100'
                                ]">
                                    <i :class="['bi bi-calendar3 text-2xl shrink-0', schedule.enrollment_type === 'ordinaria' ? 'text-blue-600' : 'text-violet-600']"></i>
                                    <div>
                                        <span :class="['text-xs font-extrabold uppercase tracking-wider block', schedule.enrollment_type === 'ordinaria' ? 'text-blue-800' : 'text-violet-800']">Inicio</span>
                                        <span class="text-slate-800 font-extrabold text-base">{{ formatDate(schedule.start_date) }}</span>
                                    </div>
                                </div>

                                <div :class="[
                                    'border p-4 rounded-2xl flex items-center gap-3',
                                    schedule.enrollment_type === 'ordinaria' ? 'bg-blue-50/60 border-blue-100' : 'bg-violet-50/60 border-violet-100'
                                ]">
                                    <i :class="['bi bi-calendar3-range text-2xl shrink-0', schedule.enrollment_type === 'ordinaria' ? 'text-blue-600' : 'text-violet-600']"></i>
                                    <div>
                                        <span :class="['text-xs font-extrabold uppercase tracking-wider block', schedule.enrollment_type === 'ordinaria' ? 'text-blue-800' : 'text-violet-800']">Cierre</span>
                                        <span class="text-slate-800 font-extrabold text-base">{{ formatDate(schedule.end_date) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fee -->
                            <div v-if="schedule.enrollment_fee && Number(schedule.enrollment_fee) > 0"
                                 class="flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3">
                                <i :class="['bi bi-cash-coin text-xl', schedule.enrollment_type === 'ordinaria' ? 'text-blue-500' : 'text-violet-500']"></i>
                                <div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Derecho de Matrícula</span>
                                    <span class="font-extrabold text-slate-800 text-lg">S/ {{ formatCurrency(schedule.enrollment_fee) }}</span>
                                </div>
                            </div>

                            <!-- Program Slots -->
                            <div v-if="schedule.details && schedule.details.length > 0">
                                <span class="text-slate-500 text-xs font-extrabold uppercase tracking-wider block mb-2">Cupos por Programa</span>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="detail in schedule.details" :key="detail.id"
                                          class="bg-slate-50 text-slate-700 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5">
                                        <span :class="['w-1.5 h-1.5 rounded-full', schedule.enrollment_type === 'ordinaria' ? 'bg-blue-500' : 'bg-violet-500']"></span>
                                        {{ detail.program?.name }}: <strong class="ml-1">{{ detail.available_slots }}</strong>
                                    </span>
                                </div>
                            </div>

                            <!-- Observations -->
                            <p v-if="schedule.observations"
                               class="text-sm text-slate-500 leading-relaxed bg-slate-50 border border-slate-100 rounded-xl p-3">
                                <i class="bi bi-info-circle mr-1 text-slate-400"></i>
                                {{ schedule.observations }}
                            </p>

                            <!-- WhatsApp CTA -->
                            <a v-if="ent.whatsapp_link"
                               :href="ent.whatsapp_link"
                               target="_blank"
                               class="inline-flex items-center justify-center gap-2 w-full py-3 bg-green-500 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition shadow">
                                <i class="bi bi-whatsapp text-lg"></i>
                                Consultar por WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Fallback: legacy admission records -->
                <div v-else-if="enrollments && enrollments.length > 0"
                     class="relative border-l-4 border-blue-200 ml-4 md:ml-32 space-y-12">
                    <div v-for="item in enrollments" :key="item.id"
                         class="relative pl-8 md:pl-12 group">
                        <div class="absolute -left-[14px] top-1.5 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow group-hover:scale-110 transition-transform"></div>
                        <div class="bg-white rounded-2xl border border-blue-100 shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            <div class="bg-blue-800 px-6 py-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="bg-blue-900/60 text-blue-200 text-sm font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ item.type }}</span>
                                    <span v-if="item.period" class="text-blue-100 text-sm font-bold">Período: {{ item.period }}</span>
                                </div>
                                <span class="bg-emerald-500/25 border border-emerald-400/30 text-emerald-200 text-sm font-extrabold px-3 py-1 rounded-full flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Semanas de Registro
                                </span>
                            </div>
                            <div class="p-6 sm:p-8 space-y-6">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <div>
                                        <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest block">Actividad</span>
                                        <h3 class="text-2xl font-black text-blue-950 leading-tight">{{ item.activity }}</h3>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center gap-3">
                                            <i class="bi bi-calendar3 text-blue-600 text-2xl"></i>
                                            <div>
                                                <span class="text-sm font-bold text-blue-800 uppercase tracking-wider block">Inicio</span>
                                                <span class="text-base font-extrabold text-gray-700">{{ formatDate(item.inscription_start_date) }}</span>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center gap-3">
                                            <i class="bi bi-calendar3-range text-blue-600 text-2xl"></i>
                                            <div>
                                                <span class="text-sm font-bold text-blue-800 uppercase tracking-wider block">Límite</span>
                                                <span class="text-base font-extrabold text-gray-700">{{ formatDate(item.inscription_end_date) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-3xl border border-blue-100 shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-calendar-x text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-blue-900 mb-3">No hay cronogramas activos</h3>
                    <p class="text-base text-gray-600 leading-relaxed mb-6">
                        Actualmente no contamos con cronogramas de matrícula activos para el período escolar vigente.
                        Ponte en contacto con Secretaría Académica para más información.
                    </p>
                    <a v-if="ent.whatsapp_link"
                       :href="ent.whatsapp_link"
                       target="_blank"
                       class="inline-flex items-center justify-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white text-base font-bold rounded-xl transition shadow">
                        <i class="bi bi-whatsapp mr-2 text-lg"></i>
                        Consultar por WhatsApp
                    </a>
                </div>
            </div>
        </section>

        <!-- ═══ 4. REQUISITOS DE MATRÍCULA ════════════════════════════ -->
        <section v-if="requirements && requirements.length > 0" id="requirements" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-blue-600 text-sm font-extrabold uppercase tracking-widest">Documentación</span>
                    <h2 class="text-3xl sm:text-5xl font-black text-blue-950 mt-2">Requisitos de Matrícula</h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-base text-gray-500 mt-5 leading-relaxed">
                        Prepara y presenta la documentación indicada para validar satisfactoriamente tu matrícula.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <div v-for="(req, index) in requirements" :key="req.id"
                         class="bg-blue-50/20 p-6 rounded-2xl border border-blue-100 flex gap-4 transition hover:bg-blue-50/60 hover:shadow-sm">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center">
                            {{ String(index + 1).padStart(2, '0') }}
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-blue-950 mb-1">Requisito Obligatorio</h4>
                            <p class="text-gray-700 text-sm leading-relaxed font-semibold">{{ req.requirement }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 5. CTA CONTACTO ═══════════════════════════════════════ -->
        <section id="contact" class="py-20 bg-slate-900 text-white relative overflow-hidden border-t border-slate-800">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-8">
                <h2 class="text-3xl sm:text-5xl font-black">¿Tienes dudas sobre tu matrícula?</h2>
                <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed font-medium">
                    Comunícate con nuestra Secretaría Académica para orientación sobre pagos, documentación o fechas del proceso de matrícula.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-6 pt-4">
                    <a v-if="ent.phone_number_1"
                       :href="`tel:${ent.phone_number_1}`"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-white text-blue-900 hover:bg-blue-50 rounded-xl transition shadow">
                        <i class="bi bi-telephone-fill mr-2 text-xl text-blue-600"></i>
                        Llamar: {{ ent.phone_number_1 }}
                    </a>

                    <a v-if="ent.whatsapp_link"
                       :href="ent.whatsapp_link"
                       target="_blank"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 text-base font-bold bg-green-500 hover:bg-green-600 text-white rounded-xl transition shadow">
                        <i class="bi bi-whatsapp mr-2 text-xl"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.step-card {
    position: relative;
}
.step-card:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 28px;
    left: 27px;
    width: 2px;
    height: calc(100% + 2rem);
    background: #bfdbfe;
    z-index: 0;
}
.step-card-extra:not(:last-child)::after {
    background: #ddd6fe;
}
.schedule-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.schedule-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.10);
}
</style>
