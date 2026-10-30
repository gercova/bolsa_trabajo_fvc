<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed, watch } from 'vue';

const page = usePage();

const props = defineProps({
    scholarships:              { type: Array, default: () => [] },
    totalScholarshipVacancies: { type: Number, default: 0 },
    enterprise:                { type: Object, default: () => null },
    beneficiaries:             { type: Array, default: () => [] },
    beneficiariesByPeriod:     { type: Object, default: () => ({}) },
    beneficiaryPeriods:        { type: Array, default: () => [] },
});

// Fallback to shared enterprise
const ent = computed(() => props.enterprise || page.props.enterprise || {});

// Total vacancies sum
const totalVacancies = computed(() => {
    if (props.totalScholarshipVacancies > 0) return props.totalScholarshipVacancies;
    return props.scholarships.reduce((acc, s) => acc + (Number(s.vacancies) || 0), 0);
});

// Modal state for scholarship detail
const activeModal = ref(null);

const activeScholarship = computed(() => {
    return props.scholarships.find(s => s.id === activeModal.value) || null;
});

// Period filter for beneficiaries
const selectedPeriod = ref('all');

// PDF Reader Modal state
const showPdfModal = ref(false);
const pdfReaderUrl = ref('');
const pdfReaderTitle = ref('');

const openPdfReader = (url, title) => {
    if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
        try {
            const parsed = new URL(url);
            if (typeof window !== 'undefined' && (parsed.hostname === 'localhost' || parsed.hostname === '127.0.0.1' || parsed.hostname === window.location.hostname)) {
                url = parsed.pathname;
            }
        } catch (e) {
            console.error('URL parse error:', e);
        }
    }
    pdfReaderUrl.value = url;
    pdfReaderTitle.value = title;
    showPdfModal.value = true;
};

// Lock body scroll when PDF modal is open
watch(showPdfModal, (val) => {
    if (typeof document !== 'undefined') {
        document.body.style.overflow = val ? 'hidden' : '';
    }
});

// Filtered beneficiaries
const filteredBeneficiaries = computed(() => {
    if (selectedPeriod.value === 'all') {
        return props.beneficiaries;
    }
    return props.beneficiaries.filter(b => b.academic_period === selectedPeriod.value);
});

// Helper for requirements split or fallback
const getRequirementsList = (scholarship) => {
    if (scholarship.requirements && scholarship.requirements.trim()) {
        return scholarship.requirements
            .split('\n')
            .map(r => r.trim())
            .filter(r => r.length > 0);
    }
    const nameUpper = (scholarship.name || '').toUpperCase();
    if (nameUpper.includes('PRIMEROS')) {
        return [
            'Certificado de estudios con acreditación de 1° o 2° puesto escolar.',
            'Acta de sesión de promoción escolar emitida por la I.E. de origen.'
        ];
    }
    if (nameUpper.includes('DEPORTISTAS')) {
        return [
            'Credencial vigente emitida por el IPD o Federación Nacional.',
            'Carta de compromiso de representación institucional en torneos.'
        ];
    }
    if (nameUpper.includes('TERRORISMO')) {
        return [
            'Acreditación en el Registro Único de Víctimas (RUV - Ley 28592).',
            'Certificado acreditativo expedido por el Consejo de Reparaciones.'
        ];
    }
    if (nameUpper.includes('PRE-INSTITUTO') || nameUpper.includes('CEPRE')) {
        return [
            'Constancia de nota final y orden de mérito de la CEPRE-FVC.',
            'Asistencia mínima del 85% a clases preparatorias.'
        ];
    }
    if (nameUpper.includes('DISCAPACITADOS')) {
        return [
            'Carné oficial emitido por CONADIS (Ley N° 29973).',
            'Certificado médico oficial de discapacidad emitido por MINSA o EsSalud.'
        ];
    }
    if (nameUpper.includes('TITULADOS')) {
        return [
            'Copia autenticada de Título Profesional o Grado de Bachiller.',
            'Certificado oficial de estudios de nivel superior para convalidación.'
        ];
    }
    if (nameUpper.includes('FUERZAS ARMADAS')) {
        return [
            'Libreta Militar o Constancia de Licenciado del Servicio Militar Voluntario.',
            'Documento oficial de acreditación emitido por las FF.AA.'
        ];
    }
    return [
        'Documento de Identidad (DNI) vigente y solicitud dirigida al Director.'
    ];
};

// Date formatter
const formatDate = (dateStr) => {
    if (!dateStr) return 'Oficial';
    const clean = String(dateStr).split('T')[0];
    const parts = clean.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
};

// FAQ Accordion state
const openFaq = ref(1);
const toggleFaq = (index) => {
    openFaq.value = openFaq.value === index ? null : index;
};
</script>

<template>
    <Head>
        <title>Becas y Créditos Educativos — IESTP Francisco Vigo Caballero</title>
        <meta name="description" content="Descubre el programa integral de becas, exoneraciones de pago por ley y opciones de crédito educativo en el IESTP Francisco Vigo Caballero en Uchiza. Primeros Puestos, Beca 18 PRONABEC, Deportistas, Discapacidad y Convenios." />
        <meta name="keywords" content="becas, creditos educativos, beca 18, pronabec, iestp francisco vigo caballero, uchiza, exoneracion pago, primeros puestos, san martin, educacion superior tecnica" />
        <meta property="og:title" content="Becas y Créditos Educativos — IESTP Francisco Vigo Caballero" />
        <meta property="og:description" content="Información oficial sobre becas institucionales, exoneraciones de ley y programas de financiamiento en Uchiza." />
    </Head>

    <PublicLayout title="Becas y Créditos">
        <!-- ═══ 1. HERO SECTION ═══════════════════════════════════════ -->
        <section class="relative bg-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <!-- Col Left: Title & Actions -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs sm:text-sm font-semibold tracking-wide backdrop-blur-sm">
                            <i class="bi bi-patch-check-fill text-blue-300"></i>
                            <span>Inclusión y Apoyo Estudiantil</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight text-white font-sans">
                            Becas, Beneficios y <br />
                            <span class="text-blue-200">Créditos Educativos</span>
                        </h1>

                        <p class="text-lg sm:text-xl text-blue-100 max-w-2xl leading-relaxed">
                            Garantizamos una educación técnica superior inclusiva. Accede a exoneraciones de pago por mérito académico, reservas por ley y acompañamiento en convenios nacionales como <strong class="text-white font-semibold">Beca 18 - PRONABEC</strong>.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="#modalidades-becas"
                               class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-blue-900 bg-white hover:bg-blue-50 rounded-xl transition shadow-lg hover:shadow-xl group">
                                <i class="bi bi-award-fill mr-2 text-lg text-blue-700 group-hover:scale-110 transition-transform"></i>
                                Becas Institucionales
                            </a>
                            <a href="#padron-beneficiarios"
                               class="inline-flex items-center justify-center px-6 py-4 text-base font-bold text-white border-2 border-blue-300/40 hover:bg-white/10 rounded-xl transition backdrop-blur-sm group">
                                <i class="bi bi-file-earmark-pdf-fill mr-2 text-lg text-amber-300 group-hover:scale-110 transition-transform"></i>
                                Padrón de Beneficiarios (PDF)
                            </a>
                        </div>
                    </div>

                    <!-- Col Right: Visual Graphic Card -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative mx-auto max-w-md lg:max-w-none">
                            <div class="relative bg-slate-800 border border-slate-700 p-8 rounded-3xl shadow-xl text-white space-y-6">
                                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-inner">
                                            <i class="bi bi-mortarboard"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wider text-blue-200 font-bold">Oportunidades FVC</p>
                                            <h2 class="text-xl font-bold text-white">Apoyo Educativo</h2>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 rounded-full text-xs font-bold">
                                        Convocatoria 2026
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-xl border border-white/10">
                                        <i class="bi bi-check-circle-fill text-emerald-400 text-lg mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-bold text-white">Exoneración por Mérito</p>
                                            <p class="text-xs text-blue-100">Primeros puestos de secundaria y CEPRE-FVC.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-xl border border-white/10">
                                        <i class="bi bi-shield-check text-blue-300 text-lg mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-bold text-white">Becas e Integración por Ley</p>
                                            <p class="text-xs text-blue-100">Discapacidad (CONADIS), VÍCTIMAS y FF.AA.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 flex items-center justify-between text-xs text-blue-200">
                                    <span class="flex items-center gap-1"><i class="bi bi-geo-alt text-blue-300"></i> {{ ent.city || 'Uchiza' }}, San Martín</span>
                                    <span class="flex items-center gap-1"><i class="bi bi-clock-history text-blue-300"></i> Atención presencial</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 2. RESUMEN DE IMPACTO / STAT CARDS ═════════════════════ -->
        <section class="py-12 bg-white relative -mt-6 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1: Total Vacantes -->
                    <div class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-blue-600/20">
                            <i class="bi bi-people-fill text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-blue-900 mb-1">{{ totalVacancies }} Plazas</h3>
                        <p class="text-sm font-bold text-blue-700">Vacantes Disponibles</p>
                        <p class="text-xs text-gray-600 mt-1">Plazas totales reservadas en modalidades preferenciales de becas.</p>
                    </div>

                    <!-- Card 2: Primeros Puestos -->
                    <div class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 bg-emerald-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-emerald-600/20">
                            <i class="bi bi-trophy-fill text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-emerald-900 mb-1">100% y 50%</h3>
                        <p class="text-sm font-bold text-emerald-700">Primeros Puestos</p>
                        <p class="text-xs text-gray-600 mt-1">100% de descuento para el 1° puesto y 50% para el 2° puesto escolar.</p>
                    </div>

                    <!-- Card 3: Fuerzas Armadas -->
                    <div class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-indigo-600/20">
                            <i class="bi bi-shield-fill-check text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-indigo-900 mb-1">50% Descuento</h3>
                        <p class="text-sm font-bold text-indigo-700">Servicio Militar (FF.AA.)</p>
                        <p class="text-xs text-gray-600 mt-1">Beneficio arancelario para personal del SMV y licenciados.</p>
                    </div>

                    <!-- Card 4: Inclusión y Leyes -->
                    <div class="bg-blue-50/70 hover:bg-blue-50 border border-blue-100 p-6 rounded-2xl transition-all shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-4 shadow-md shadow-blue-600/20">
                            <i class="bi bi-patch-check-fill text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-blue-900 mb-1">100% y 5% Ley</h3>
                        <p class="text-sm font-bold text-blue-700">Inclusión y Reparaciones</p>
                        <p class="text-xs text-gray-600 mt-1">Exoneración total PIR y reserva legal del 5% CONADIS.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 3. MODALIDADES DE BECAS INSTITUCIONALES ═══════════════ -->
        <section id="modalidades-becas" class="py-16 bg-slate-50 border-y border-blue-100/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-100/80 px-3.5 py-1.5 rounded-full inline-flex items-center gap-1.5">
                        <i class="bi bi-award-fill text-blue-600"></i>
                        Modalidades Institucionales • {{ totalVacancies }} Plazas Disponibles
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-4 font-sans">
                        Becas, Descuentos y Exoneraciones
                    </h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                        Conoce las modalidades de ingreso preferencial, vacantes disponibles y el porcentaje de descuento arancelario establecido por norma institucional y legislación nacional en favor de nuestros postulantes.
                    </p>
                </div>

                <div v-if="scholarships && scholarships.length > 0"
                     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(scholarship, idx) in scholarships" :key="scholarship.id"
                         class="bg-white rounded-3xl border border-blue-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                        <div class="p-8">
                            <!-- Card Header: Icon & Badges -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 bg-blue-700 text-white rounded-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                    <i :class="['bi text-2xl', scholarship.icon || 'bi-award']"></i>
                                </div>
                                <div class="flex flex-col items-end gap-1.5">
                                    <span class="bg-blue-50 text-blue-700 text-xs font-extrabold px-3 py-0.5 rounded-full border border-blue-200">
                                        Modalidad #{{ idx + 1 }}
                                    </span>
                                    <span class="bg-emerald-50 text-emerald-800 text-xs font-black px-3 py-0.5 rounded-full border border-emerald-200 flex items-center gap-1 shadow-xs">
                                        <i class="bi bi-people-fill text-emerald-600"></i>
                                        {{ scholarship.vacancies || 0 }} Plazas
                                    </span>
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-bold text-blue-900 mb-3 group-hover:text-blue-600 transition-colors">
                                {{ scholarship.name }}
                            </h3>

                            <!-- Discount Highlight Box -->
                            <div v-if="scholarship.discount_details || Number(scholarship.discount_percentage) > 0"
                                 class="mb-4 p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-950">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[11px] font-black px-2.5 py-0.5 rounded-md shadow-xs">
                                        <i class="bi bi-tag-fill text-[10px]"></i>
                                        <template v-if="Number(scholarship.discount_percentage) > 0">
                                            {{ Math.round(Number(scholarship.discount_percentage)) }}% Beneficio
                                        </template>
                                        <template v-else>
                                            Descuento
                                        </template>
                                    </span>
                                    <span class="text-xs font-extrabold text-emerald-900 uppercase tracking-wide">
                                        Beneficio Arancelario
                                    </span>
                                </div>
                                <p class="text-xs font-semibold text-emerald-900 leading-snug">
                                    {{ scholarship.discount_details || 'Exoneración arancelaria preferencial de acuerdo al reglamento institucional.' }}
                                </p>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                                {{ scholarship.description || 'Modalidad especial de beca y exoneración de acuerdo a los requerimientos de evaluación e ingreso institucional del IESTP Francisco Vigo Caballero.' }}
                            </p>

                            <!-- Requirements List -->
                            <div class="border-t border-gray-100 pt-4 space-y-2 text-xs text-gray-700">
                                <p class="font-bold text-blue-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <i class="bi bi-file-earmark-check-fill text-blue-500"></i> Requisitos principales:
                                </p>
                                <div v-for="(reqLine, rIdx) in getRequirementsList(scholarship)" :key="rIdx"
                                     class="flex items-start gap-2">
                                    <i class="bi bi-check2 text-blue-600 font-bold"></i>
                                    <span>{{ reqLine }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-8 py-4 bg-slate-50 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-800 flex items-center gap-1">
                                <i class="bi bi-check-circle-fill text-emerald-600"></i>
                                {{ scholarship.vacancies || 0 }} Vacantes
                            </span>
                            <button type="button"
                                    @click="activeModal = scholarship.id"
                                    class="inline-flex items-center text-xs font-extrabold text-blue-700 hover:text-blue-900 group-hover:translate-x-1 transition-transform">
                                Ver Ficha y Requisitos <i class="bi bi-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-12 bg-white rounded-3xl border border-blue-100 p-8 max-w-xl mx-auto shadow-sm">
                    <i class="bi bi-info-circle text-4xl text-blue-500 mb-4 inline-block"></i>
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Información en actualización</h3>
                    <p class="text-gray-600 text-sm">Próximamente se publicarán las modalidades de becas vigentes para el presente período académico.</p>
                </div>

            </div>
        </section>

        <!-- ═══ MODAL DE DETALLE POR BECA ══════════════════════════════ -->
        <div v-if="activeScholarship"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
             @keydown.escape.window="activeModal = null">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative space-y-6"
                 @click.stop>
                <button type="button"
                        @click="activeModal = null"
                        class="absolute top-5 right-5 text-gray-400 hover:text-gray-700 w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>

                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold shadow-md shadow-blue-600/20">
                        <i :class="['bi', activeScholarship.icon || 'bi-award']"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600">Ficha de Modalidad</span>
                            <span class="bg-blue-100 text-blue-900 text-[10px] font-black px-2 py-0.5 rounded-full border border-blue-200">
                                {{ activeScholarship.vacancies || 0 }} Plazas
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-blue-900">{{ activeScholarship.name }}</h3>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-gray-700">
                    <!-- Discount in modal -->
                    <div v-if="activeScholarship.discount_details || Number(activeScholarship.discount_percentage) > 0"
                         class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-emerald-950">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1 bg-emerald-600 text-white text-[10px] font-black px-2 py-0.5 rounded shadow-xs">
                                <i class="bi bi-tag-fill"></i>
                                <template v-if="Number(activeScholarship.discount_percentage) > 0">
                                    {{ Math.round(Number(activeScholarship.discount_percentage)) }}% DESCUENTO
                                </template>
                                <template v-else>
                                    BENEFICIO
                                </template>
                            </span>
                            <span class="text-xs font-extrabold text-emerald-900 uppercase">Cuadro de Descuentos</span>
                        </div>
                        <p class="text-xs font-semibold text-emerald-900 leading-snug">
                            {{ activeScholarship.discount_details }}
                        </p>
                    </div>

                    <p class="leading-relaxed bg-blue-50/60 p-4 rounded-2xl border border-blue-100 text-xs sm:text-sm">
                        {{ activeScholarship.description }}
                    </p>

                    <div>
                        <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                            <i class="bi bi-folder-check text-blue-600"></i> Documentación obligatoria a presentar:
                        </h4>
                        <ul class="space-y-2 pl-1">
                            <li v-for="(reqLine, rIdx) in getRequirementsList(activeScholarship)" :key="rIdx"
                                class="flex items-start gap-2 text-xs text-gray-700">
                                <i class="bi bi-check-circle-fill text-blue-500 text-sm mt-0.5"></i>
                                <span>{{ reqLine }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-900 text-xs flex items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-amber-500 text-base"></i>
                        <span>La presentación de documentos se realiza de manera presencial en la Secretaría Académica o mediante la Mesa de Partes Virtual durante las fechas habilitadas en la convocatoria.</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <Link href="/tramites/mesa-de-partes"
                          class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-md">
                        Presentar Expediente
                    </Link>
                    <button type="button"
                            @click="activeModal = null"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- ═══ 4. PADRÓN OFICIAL DE BENEFICIARIOS (PDF) ══════════════ -->
        <section id="padron-beneficiarios" class="py-16 sm:py-24 bg-white border-b border-blue-100/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="text-xs font-bold tracking-widest text-blue-700 uppercase bg-blue-100/80 px-4 py-1.5 rounded-full inline-flex items-center gap-1.5 border border-blue-200/60 shadow-xs">
                        <i class="bi bi-shield-check text-blue-600"></i>
                        Transparencia Institucional • Padrón de Adjudicados
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-blue-900 mt-4 font-sans tracking-tight">
                        Relación de Estudiantes Beneficiarios <br class="hidden sm:inline" />
                        <span class="text-blue-600">de Becas y Exoneraciones</span>
                    </h2>
                    <div class="w-24 h-1.5 bg-blue-600 mx-auto mt-5 rounded-full"></div>
                    <p class="text-base sm:text-lg text-gray-600 mt-6 leading-relaxed">
                        Ponemos a disposición de la comunidad educativa y público en general las resoluciones y nóminas oficiales con la relación de estudiantes que obtuvieron becas, medias becas o exoneraciones, clasificados por periodo académico o semestre.
                    </p>
                </div>

                <!-- Beneficiary Documents Content -->
                <template v-if="beneficiaries && beneficiaries.length > 0">
                    <!-- Period Filter Tabs -->
                    <div v-if="beneficiaryPeriods && beneficiaryPeriods.length > 1"
                         class="flex flex-wrap items-center justify-center gap-2 mb-12">
                        <button type="button"
                                @click="selectedPeriod = 'all'"
                                :class="[
                                    'px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 flex items-center gap-2',
                                    selectedPeriod === 'all'
                                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25 font-black scale-105'
                                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold'
                                ]">
                            <i class="bi bi-collection"></i>
                            <span>Todos los Periodos</span>
                            <span :class="['px-2 py-0.5 text-[11px] rounded-full', selectedPeriod === 'all' ? 'bg-blue-800 text-blue-100' : 'bg-slate-200 text-slate-700']">
                                {{ beneficiaries.length }}
                            </span>
                        </button>

                        <button v-for="period in beneficiaryPeriods" :key="period"
                                type="button"
                                @click="selectedPeriod = period"
                                :class="[
                                    'px-5 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 flex items-center gap-2',
                                    selectedPeriod === period
                                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25 font-black scale-105'
                                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold'
                                ]">
                            <i class="bi bi-calendar3"></i>
                            <span>Periodo {{ period }}</span>
                            <span :class="['px-2 py-0.5 text-[11px] rounded-full', selectedPeriod === period ? 'bg-blue-800 text-blue-100' : 'bg-slate-200 text-slate-700']">
                                {{ (beneficiariesByPeriod[period] || []).length || beneficiaries.filter(b => b.academic_period === period).length }}
                            </span>
                        </button>
                    </div>

                    <!-- Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="ben in filteredBeneficiaries" :key="ben.id"
                             class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                            
                            <div class="p-6 sm:p-8 space-y-4">
                                <!-- Card Header -->
                                <div class="flex items-center justify-between gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-red-50 border border-red-100 text-red-600 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 group-hover:bg-red-600 group-hover:text-white transition-all duration-300 flex-shrink-0">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </div>
                                    <div class="flex flex-col items-end gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-purple-50 text-purple-800 border border-purple-200">
                                            <i class="bi bi-calendar-check text-purple-600"></i>
                                            Periodo {{ ben.academic_period }}
                                        </span>
                                        <span v-if="ben.scholarship"
                                              class="text-[11px] font-extrabold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-md line-clamp-1 max-w-[190px]">
                                            {{ ben.scholarship.name }}
                                        </span>
                                        <span v-else
                                              class="text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-md">
                                            Todas las Modalidades
                                        </span>
                                    </div>
                                </div>

                                <!-- Title & Resolution -->
                                <div class="space-y-2">
                                    <h3 class="text-xl font-bold text-blue-950 group-hover:text-blue-600 transition-colors leading-snug">
                                        {{ ben.title }}
                                    </h3>
                                    <div v-if="ben.resolution_number"
                                         class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-900 bg-amber-50 border border-amber-200/90 px-2.5 py-1 rounded-lg">
                                        <i class="bi bi-file-earmark-check-fill text-amber-600"></i>
                                        <span>{{ ben.resolution_number }}</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p v-if="ben.description"
                                   class="text-xs sm:text-sm text-gray-600 leading-relaxed line-clamp-3">
                                    {{ ben.description }}
                                </p>

                                <!-- Meta -->
                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium">
                                    <span class="flex items-center gap-1.5">
                                        <i class="bi bi-clock-history text-gray-400"></i>
                                        {{ formatDate(ben.publication_date) }}
                                    </span>
                                    <span class="flex items-center gap-1 font-bold text-slate-700">
                                        <i class="bi bi-filetype-pdf text-red-500"></i>
                                        {{ ben.formatted_file_size || 'PDF' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Footer Actions -->
                            <div class="px-6 py-4 bg-slate-50 border-t border-gray-100 flex items-center gap-3">
                                <button type="button"
                                        @click="openPdfReader(ben.file_url, ben.title)"
                                        class="flex-1 py-3 px-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl transition shadow-md shadow-blue-600/20 flex items-center justify-center gap-2">
                                    <i class="bi bi-eye-fill"></i>
                                    <span>Visualizar PDF</span>
                                </button>

                                <a :href="ben.file_url"
                                   download
                                   target="_blank"
                                   class="py-3 px-4 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-xs"
                                   title="Descargar archivo PDF">
                                    <i class="bi bi-download"></i>
                                    <span class="hidden sm:inline">Descargar</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div v-else class="max-w-xl mx-auto bg-slate-50 border border-blue-100 rounded-3xl p-8 sm:p-12 text-center space-y-4 shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-blue-100/70 text-blue-600 mx-auto flex items-center justify-center text-3xl shadow-inner">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900">Padrones en proceso de actualización</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Las nóminas oficiales de estudiantes adjudicados son publicadas al finalizar cada convocatoria y ratificadas mediante Resolución Directoral. Próximamente se encontrarán disponibles para consulta pública.
                    </p>
                    <div class="pt-2">
                        <Link href="/tramites/mesa-de-partes"
                              class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold text-xs rounded-xl hover:bg-blue-700 transition shadow-sm">
                            <i class="bi bi-envelope-paper"></i> Consultar en Mesa de Partes
                        </Link>
                    </div>
                </div>

            </div>

            <!-- ===== PDF READER MODAL ===== -->
            <div v-if="showPdfModal"
                 class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
                 @keydown.escape.window="showPdfModal = false">
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden border border-slate-200"
                     @click.stop>
                    <!-- Modal Topbar -->
                    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-file-earmark-pdf-fill text-red-400 text-lg"></i>
                            </div>
                            <h3 class="font-bold text-sm sm:text-base truncate">{{ pdfReaderTitle }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <a :href="pdfReaderUrl" download target="_blank"
                               class="px-3.5 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5">
                                <i class="bi bi-download"></i> Descargar
                            </a>
                            <button type="button"
                                    @click="showPdfModal = false"
                                    class="p-2 text-slate-400 hover:text-white rounded-lg transition-colors">
                                <i class="bi bi-x-lg text-base"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal iFrame Body -->
                    <div class="flex-1 bg-slate-100 relative min-h-0">
                        <iframe :src="pdfReaderUrl" class="w-full h-full border-none" type="application/pdf">
                            <div class="p-8 text-center text-gray-500">
                                <p>Tu navegador no soporta la visualización integrada de archivos PDF.</p>
                                <a :href="pdfReaderUrl" target="_blank"
                                   class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold">
                                    <i class="bi bi-box-arrow-up-right"></i> Abrir o descargar documento PDF
                                </a>
                            </div>
                        </iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 5. PASOS PARA POSTULAR ════════════════════════════════ -->
        <section id="proceso-postulacion" class="py-16 bg-blue-50/40 border-t border-blue-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-100 px-3.5 py-1.5 rounded-full">
                        Guía Práctica
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-4 font-sans">
                        ¿Cómo solicitar tu Beca o Exoneración?
                    </h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-lg text-gray-600 mt-6 leading-relaxed">
                        Sigue estos 4 pasos para formalizar tu postulación y acceder a los beneficios arancelarios del IESTP Francisco Vigo Caballero.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                        <div class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                            1
                        </div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Verifica tu Modalidad</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Revisa las bases de admisión y confirma si cumples las condiciones de Primeros Puestos, CONADIS, RUV, Deportista o FF.AA.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                        <div class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                            2
                        </div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Reúne tus Documentos</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Organiza tu DNI, certificado de estudios secundarios y los comprobantes oficiales requeridos según tu modalidad elegida.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                        <div class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                            3
                        </div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Ingresa tu Expediente</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Presenta tu solicitud a través de la Mesa de Partes Virtual o acude presencialmente a la Secretaría Académica.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-3xl border border-blue-100 shadow-sm relative group hover:shadow-md transition">
                        <div class="w-10 h-10 bg-blue-600 text-white font-extrabold rounded-2xl flex items-center justify-center text-lg mb-6 shadow-md shadow-blue-600/30">
                            4
                        </div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Evaluación y Adjudicación</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            La Comisión Evaluadora valida tu documentación y formaliza la adjudicación del beneficio en la nómina oficial.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 6. PREGUNTAS FRECUENTES (FAQ) ═════════════════════════ -->
        <section id="preguntas-frecuentes" class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-xs font-bold tracking-widest text-blue-600 uppercase bg-blue-100 px-3.5 py-1.5 rounded-full">
                        Resuelve tus dudas
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-900 mt-4 font-sans">
                        Preguntas Frecuentes
                    </h2>
                    <div class="w-20 h-1.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
                </div>

                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                        <button type="button"
                                @click="toggleFaq(1)"
                                class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                            <span>¿Las becas y exoneraciones aplican para todas las carreras profesionales técnicas?</span>
                            <i :class="['bi text-xl text-blue-600', openFaq === 1 ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                        </button>
                        <div v-show="openFaq === 1"
                             class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                            Sí, los beneficios contemplados por Ley y las exoneraciones de admisión por mérito institucional aplican equitativamente para los 5 programas de estudio dictados en el IESTP Francisco Vigo Caballero, sujeto a las vacantes aprobadas para cada modalidad.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                        <button type="button"
                                @click="toggleFaq(2)"
                                class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                            <span>¿Puedo postular a Beca 18 si ya estoy matriculado en el instituto?</span>
                            <i :class="['bi text-xl text-blue-600', openFaq === 2 ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                        </button>
                        <div v-show="openFaq === 2"
                             class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                            Las bases de Beca 18 de PRONABEC distinguen entre modalidades para ingresantes y para estudiantes en continuidad de estudios. Te sugerimos consultar la convocatoria anual vigente de PRONABEC o acercarte a nuestra oficina de Bienestar Estudiantil para evaluar tu caso.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                        <button type="button"
                                @click="toggleFaq(3)"
                                class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                            <span>¿Es necesario actualizar la acreditación de CONADIS o RUV cada ciclo?</span>
                            <i :class="['bi text-xl text-blue-600', openFaq === 3 ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                        </button>
                        <div v-show="openFaq === 3"
                             class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                            No. Una vez acreditada la condición legal de beneficiario (carné de CONADIS permanente o inclusión en el Registro Único de Víctimas RUV), el beneficio se registra formalmente en tu expediente único de estudiante.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="border border-blue-100 rounded-2xl overflow-hidden shadow-sm">
                        <button type="button"
                                @click="toggleFaq(4)"
                                class="w-full p-6 text-left bg-slate-50 hover:bg-blue-50/50 flex items-center justify-between font-bold text-blue-900 text-base sm:text-lg transition">
                            <span>¿Cómo se realiza el trámite si soy beneficiario del Servicio Militar Voluntario?</span>
                            <i :class="['bi text-xl text-blue-600', openFaq === 4 ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                        </button>
                        <div v-show="openFaq === 4"
                             class="p-6 bg-white text-gray-600 text-sm leading-relaxed border-t border-blue-100">
                            Debes adjuntar tu Libreta Militar o Constancia de Licenciado emitida por el Comando de las Fuerzas Armadas (Ejército, Marina o Fuerza Aérea) en tu carpeta de admisión para otorgarte la bonificación del 10% adicional y descuentos correspondientes.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ 7. CONTACTO Y ASESORÍA DIRECTA ═════════════════════════ -->
        <section id="contacto-asesoria" class="py-16 bg-slate-900 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-blue-900 rounded-3xl p-8 sm:p-12 border border-blue-800 shadow-md">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-8 space-y-4">
                            <span class="text-xs font-extrabold uppercase tracking-widest text-blue-300 bg-blue-950/60 px-3 py-1 rounded-full border border-blue-400/30">
                                Atención Personalizada
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-sans">
                                ¿Tienes dudas sobre los requisitos o postulaciones?
                            </h2>
                            <p class="text-blue-100 text-base leading-relaxed">
                                Nuestra <strong class="text-white">Unidad de Bienestar Estudiantil y Secretaría Académica</strong> se encuentra a tu disposición para asesorarte paso a paso en el armado de tu expediente.
                            </p>

                            <div class="flex flex-wrap gap-6 pt-2 text-sm text-blue-200">
                                <div v-if="ent.address" class="flex items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-blue-400 text-lg"></i>
                                    <span>{{ ent.address }}, {{ ent.city || 'Uchiza' }}</span>
                                </div>
                                <div v-if="ent.phone_number_1" class="flex items-center gap-2">
                                    <i class="bi bi-telephone-fill text-blue-400 text-lg"></i>
                                    <a :href="`tel:${ent.phone_number_1}`" class="hover:text-white transition-colors">
                                        {{ ent.phone_number_1 }}
                                    </a>
                                </div>
                                <div v-if="ent.email" class="flex items-center gap-2">
                                    <i class="bi bi-envelope-fill text-blue-400 text-lg"></i>
                                    <a :href="`mailto:${ent.email}`" class="hover:text-white transition-colors">
                                        {{ ent.email }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4">
                            <a v-if="ent.whatsapp_link"
                               :href="ent.whatsapp_link"
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm rounded-xl transition shadow-lg hover:shadow-emerald-500/20">
                                <i class="bi bi-whatsapp mr-2 text-lg"></i> Consulta por WhatsApp
                            </a>

                            <Link href="/tramites/mesa-de-partes"
                                  class="w-full inline-flex items-center justify-center px-6 py-4 bg-white hover:bg-blue-50 text-blue-900 font-extrabold text-sm rounded-xl transition shadow-lg">
                                <i class="bi bi-file-earmark-arrow-up mr-2 text-lg text-blue-700"></i> Ir a Mesa de Partes
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
