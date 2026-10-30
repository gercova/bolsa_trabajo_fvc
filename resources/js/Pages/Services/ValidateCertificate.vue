<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    certificate: {
        type: Object,
        default: null,
    },
    certificates: {
        type: Array,
        default: () => [],
    },
    searchedUser: {
        type: Object,
        default: null,
    },
    searchedByDni: {
        type: Boolean,
        default: false,
    },
    searchCode: {
        type: String,
        default: '',
    },
    searched: {
        type: Boolean,
        default: false,
    },
    yearsSummary: {
        type: Array,
        default: () => [],
    },
    periodsSummary: {
        type: Array,
        default: () => [],
    },
    coursesSummary: {
        type: Array,
        default: () => [],
    },
    studyPrograms: {
        type: Array,
        default: () => [],
    },
    totalCertificates: {
        type: Number,
        default: 0,
    },
    validCertificates: {
        type: Number,
        default: 0,
    },
    totalStudents: {
        type: Number,
        default: 0,
    },
    totalCourses: {
        type: Number,
        default: 0,
    },
    totalHours: {
        type: Number,
        default: 0,
    },
});

// Search input model
const query = ref(props.searchCode || '');

// Certificate list resolution
const certList = computed(() => {
    if (props.certificates && props.certificates.length > 0) {
        return props.certificates;
    }
    return props.certificate ? [props.certificate] : [];
});

// Active certificate code selection
const defaultCertCode = computed(() => {
    return props.certificate?.certificate_code ||
        props.certificate?.code ||
        (certList.value.length > 0 ? (certList.value[0].certificate_code || certList.value[0].code) : '');
});

const activeCertCode = ref(defaultCertCode.value);

// Update activeCertCode if props change
if (!activeCertCode.value && defaultCertCode.value) {
    activeCertCode.value = defaultCertCode.value;
}

// Active Tab for summary tables
const activeTab = ref('years');

// Copy link notification state
const copiedLink = ref(false);
const copyValidationLink = (targetUrl = null) => {
    const url = targetUrl || (typeof window !== 'undefined' ? window.location.href : '');
    if (navigator?.clipboard?.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            copiedLink.value = true;
            setTimeout(() => {
                copiedLink.value = false;
            }, 3000);
        });
    }
};

const handleSearch = () => {
    const term = query.value.trim();
    if (term) {
        router.get(route('validar-certificado'), { code: term }, {
            preserveState: false,
            preserveScroll: true,
        });
    }
};

const printSheet = () => {
    if (typeof window !== 'undefined') {
        window.print();
    }
};

// Formatting helpers
const formatNumber = (val) => {
    const num = Number(val) || 0;
    return new Intl.NumberFormat('es-PE').format(num);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '—';
    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
    } catch {
        return dateStr;
    }
};

// Summary tab totals calculations
const sumYears = computed(() => {
    return props.yearsSummary.reduce(
        (acc, row) => {
            acc.total += Number(row.total_certificates) || 0;
            acc.presencial += Number(row.presencial_count) || 0;
            acc.semi += Number(row.semipresencial_count) || 0;
            acc.virtual += Number(row.virtual_count) || 0;
            acc.students += Number(row.total_students) || 0;
            acc.active += Number(row.active_count) || 0;
            return acc;
        },
        { total: 0, presencial: 0, semi: 0, virtual: 0, students: 0, active: 0 }
    );
});

const sumPeriods = computed(() => {
    return props.periodsSummary.reduce(
        (acc, row) => {
            acc.total += Number(row.total_certificates) || 0;
            acc.students += Number(row.total_students) || 0;
            acc.active += Number(row.active_count) || 0;
            return acc;
        },
        { total: 0, students: 0, active: 0 }
    );
});

// Helpers to identify certificate types
const isBasicEnglish = (cert) => {
    if (!cert) return false;
    const name = (cert.course?.name || cert.event_name || '').toLowerCase();
    return name.includes('inglés') || name.includes('ingles') || cert.certificate_type === 'english' || (cert.english_modules_data && cert.english_modules_data.length > 0);
};

const isTraining = (cert) => {
    if (!cert) return false;
    if (isBasicEnglish(cert)) return false;
    const modality = (cert.modality || '').toLowerCase();
    const certType = (cert.certificate_type || '').toLowerCase();
    return certType === 'training' || modality === 'capacitación' || modality === 'capacitacion' || (cert.topics_list && cert.topics_list.length > 0 && (!cert.details || cert.details.length === 0));
};

const calculateAverage = (details) => {
    if (!details || details.length === 0) return null;
    let sum = 0;
    let count = 0;
    details.forEach((d) => {
        const num = parseFloat(d.score);
        if (!isNaN(num)) {
            sum += num;
            count++;
        }
    });
    return count > 0 ? (sum / count).toFixed(2) : null;
};
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>{{ certificate ? `Validación Oficial: ${certificate.certificate_code} — IESTP Francisco Vigo Caballero` : 'Validar Certificados Oficiales — IESTP Francisco Vigo Caballero' }}</title>
            <meta name="description" :content="certificate ? `Verificación digital oficial del certificado ${certificate.certificate_code} emitido a ${certificate.user?.names ?? 'Estudiante'} para el curso ${certificate.course?.name ?? 'Curso Modular'}.` : 'Portal oficial de validación y verificación de certificados modulares y académicos del IESTP Francisco Vigo Caballero mediante código QR, DNI o código institucional.'" />
            <meta name="keywords" content="validar certificado, verificar certificado qr, certificacion modular, iestp francisco vigo caballero, uchiza, minedu, acreditacion academica, notas modulares" />
            <meta property="og:title" :content="certificate ? `Validación Oficial: ${certificate.certificate_code} — IESTP FVC` : 'Validar Certificados Oficiales — IESTP FVC'" />
            <meta property="og:description" :content="certificate ? `Verificación digital del certificado ${certificate.certificate_code}` : 'Portal oficial de validación de certificados modulares'" />
        </Head>

        <!-- Page Styles for Print and Verified Certificate Badge -->
        <div class="validate-page-wrapper bg-slate-50 min-h-screen font-sans text-slate-800">
            <!-- ═══ HERO & SEARCH SECTION (HIDDEN IN PRINT) ════════════════ -->
            <section class="hero-section no-print relative bg-slate-900 text-white overflow-hidden py-14 lg:py-18 border-b border-slate-800">
                <div class="h-1.5 w-full bg-emerald-600 absolute top-0 left-0"></div>
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-6">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs sm:text-sm font-semibold backdrop-blur-md shadow-inner">
                        <i class="bi bi-patch-check-fill text-emerald-400 text-base"></i>
                        <span>Sistema Oficial de Validación Digital por QR &amp; Código</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight font-display">
                        Validación de <span class="text-emerald-400">Certificados</span> Institucionales
                    </h1>
                    <p class="text-sm sm:text-base lg:text-lg text-slate-300 max-w-3xl mx-auto font-normal leading-relaxed">
                        Consulte y verifique la autenticidad de los certificados modulares y académicos emitidos por el
                        <strong class="text-white font-semibold">{{ enterprise.trade_name || enterprise.company_name || 'IESTP Francisco Vigo Caballero' }}</strong>.
                        Ingrese el código impreso, escanee el código QR o busque por DNI.
                    </p>

                    <!-- Search Bar -->
                    <div class="max-w-2xl mx-auto pt-2">
                        <form @submit.prevent="handleSearch"
                            class="relative flex items-center shadow-2xl rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 p-2 focus-within:border-emerald-400 focus-within:ring-4 focus-within:ring-emerald-500/20 transition-all">
                            <div class="pl-3 pr-2 text-slate-400 flex items-center">
                                <i class="bi bi-qr-code-scan text-xl text-emerald-400"></i>
                            </div>
                            <input v-model="query" type="text"
                                placeholder="Ejemplo: CERT-00000011-1 o número de DNI..."
                                aria-label="Código de certificado o DNI" required
                                class="w-full bg-transparent text-white placeholder-slate-400 text-sm sm:text-base px-2 py-2.5 focus:outline-none font-mono font-medium" />
                            <button type="submit"
                                class="shrink-0 px-5 sm:px-7 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                                <i class="bi bi-search"></i>
                                <span class="hidden sm:inline">Validar</span>
                            </button>
                        </form>
                        <div class="flex items-center justify-center gap-4 text-xs text-slate-400 mt-2.5">
                            <span><i class="bi bi-shield-lock-fill text-emerald-400"></i> Verificación criptográfica segura</span>
                            <span>•</span>
                            <span><i class="bi bi-lightning-charge-fill text-amber-400"></i> Resultados en tiempo real</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══ CERTIFICATE VALIDATION RESULT (IF SEARCHED) ═══════════════ -->
            <div v-if="searched" class="certificate-result-wrapper max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20 mb-12">
                <!-- If matches found -->
                <div v-if="certList.length > 0">
                    <!-- MULTI-CERTIFICATE SELECTOR BANNER -->
                    <div v-if="certList.length > 1" class="no-print bg-white rounded-3xl shadow-xl border border-indigo-100 p-5 sm:p-6 mb-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-2xl shadow-md shrink-0">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                                            Estudiante / Titular
                                        </span>
                                        <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                            DNI: {{ searchedUser?.dni || certificate?.user?.dni || searchCode }}
                                        </span>
                                    </div>
                                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 font-display mt-0.5">
                                        {{ searchedUser?.names || certificate?.user?.names || 'Estudiante Institucional' }}
                                    </h2>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-200 text-purple-700 text-xs font-bold">
                                    <i class="bi bi-patch-check-fill text-purple-600"></i>
                                    {{ certList.length }} Certificados Disponibles
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 space-y-3">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <i class="bi bi-collection-fill text-indigo-500"></i> Certificados del estudiante — Seleccione para ver el detalle oficial:
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div v-for="item in certList" :key="item.certificate_code || item.id"
                                    role="button" tabindex="0"
                                    @click="activeCertCode = item.certificate_code"
                                    :class="activeCertCode === item.certificate_code
                                        ? 'ring-2 ring-indigo-500 bg-indigo-50/70 border-indigo-300 shadow-sm'
                                        : 'bg-slate-50/70 hover:bg-slate-100 border-slate-200'"
                                    class="cursor-pointer p-4 rounded-2xl border transition-all text-left flex flex-col justify-between group">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="font-mono text-[11px] font-bold px-2 py-0.5 rounded-md"
                                                :class="activeCertCode === item.certificate_code ? 'bg-indigo-600 text-white' : 'bg-slate-200/80 text-slate-700'">
                                                {{ item.certificate_code }}
                                            </span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                                :class="item.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'">
                                                {{ item.is_active ? 'Válido' : 'Inactivo' }}
                                            </span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-800 line-clamp-2 group-hover:text-indigo-700 transition-colors">
                                            {{ item.course?.name || 'Curso Modular Institucional' }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500">
                                            {{ isBasicEnglish(item) ? 'Inglés a Nivel Básico' : (isTraining(item) ? 'Capacitación' : 'Formación Modular') }}
                                            • {{ item.modality || 'Presencial' }}
                                        </p>
                                    </div>
                                    <div class="mt-3 pt-2.5 border-t border-slate-200/70 flex items-center justify-between text-[11px] text-slate-500">
                                        <span><i class="bi bi-calendar3"></i> {{ formatDate(item.issue_date) }}</span>
                                        <span class="text-indigo-600 font-semibold flex items-center gap-1">
                                            <span v-if="activeCertCode === item.certificate_code" class="font-bold text-indigo-700">Viendo ahora</span>
                                            <span v-else>Ver Ficha</span>
                                            <i class="bi bi-arrow-right-short text-base"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CERTIFICATE CARDS FOR EACH CERTIFICATE -->
                    <div v-for="cItem in certList" :key="cItem.certificate_code || cItem.id">
                        <div v-show="activeCertCode === cItem.certificate_code"
                            :id="`certificate-print-card-${cItem.certificate_code}`"
                            class="bg-white rounded-3xl shadow-xl border border-emerald-200/80 overflow-hidden transition-all mb-8">
                            <!-- Official Institutional Letterhead (Visible in Print) -->
                            <div class="print-only border-b-2 border-emerald-600 bg-slate-50 px-6 py-4">
                                <div class="print-header-flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <img v-if="enterprise.logo_base64 || enterprise.logo_path"
                                            :src="enterprise.logo_base64 || enterprise.logo_path"
                                            alt="Logo IESTP FVC" class="h-14 w-auto object-contain" />
                                        <div v-else class="w-12 h-12 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-xl font-bold">
                                            <i class="bi bi-mortarboard-fill"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none">
                                                INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO
                                            </p>
                                            <h2 class="text-sm font-black text-slate-900 leading-tight uppercase font-display mt-0.5">
                                                "FRANCISCO VIGO CABALLERO" — UCHIZA
                                            </h2>
                                            <p class="text-[9px] text-slate-500 leading-none mt-0.5">
                                                R.M. N° 0450-1997-ED | Código Modular: 0548123 | San Martín - Perú
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded block">
                                            SISTEMA DE VERIFICACIÓN OFICIAL
                                        </span>
                                        <span class="text-[9px] text-slate-500 font-mono block mt-0.5">
                                            Emisión: {{ formatDate(cItem.issue_date) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Banner -->
                            <div class="bg-emerald-700 text-white px-6 py-3.5 flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-lg shrink-0">
                                        <i class="bi bi-patch-check-fill text-emerald-200"></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-extrabold tracking-widest text-emerald-200 block">
                                            ESTADO DE VALIDACIÓN
                                        </span>
                                        <h2 class="text-sm sm:text-base font-black tracking-wide text-white">
                                            {{ cItem.is_active ? 'CERTIFICADO AUTÉNTICO Y VÁLIDO' : 'CERTIFICADO INACTIVO / REVOCADO' }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold"
                                        :class="cItem.is_active ? 'bg-emerald-400/20 text-emerald-100 border border-emerald-300/30' : 'bg-red-400/20 text-red-100 border border-red-300/30'">
                                        <i class="bi bi-circle-fill text-[8px] mr-1"
                                            :class="cItem.is_active ? 'text-emerald-300 animate-pulse' : 'text-red-300'"></i>
                                        {{ cItem.is_active ? 'Registro Vigente' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="print-card-body p-6 sm:p-8 space-y-6 sm:space-y-8 bg-white">
                                <!-- Main Certificate Header Data -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center border-b border-slate-100 pb-5">
                                    <div class="md:col-span-2 space-y-2">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold">
                                            <i class="bi bi-award-fill"></i>
                                            Código Oficial: <span class="font-mono font-black text-indigo-900">{{ cItem.certificate_code }}</span>
                                        </div>
                                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-display">
                                            {{ cItem.course?.name || 'CURSO MODULAR INSTITUCIONAL' }}
                                        </h3>
                                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                            {{ cItem.description || cItem.course?.description || 'Certificado emitido a favor del participante por haber completado satisfactoriamente los módulos de formación y evaluación modular.' }}
                                        </p>
                                    </div>

                                    <!-- QR and Stamp Badge -->
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-xs">
                                        <a :href="cItem.validation_url || route('validar-certificado', { certificate_code: cItem.certificate_code })" target="_blank"
                                            class="w-40 h-40 rounded-xl bg-white border border-slate-200 shadow-inner p-1 flex items-center justify-center mb-1.5 overflow-hidden hover:opacity-90 transition-opacity block"
                                            title="Escanear o hacer clic para verificar certificado">
                                            <div v-if="cItem.qr_code_svg" class="w-full h-full [&>svg]:w-full [&>svg]:h-full [&>svg]:block" v-html="cItem.qr_code_svg"></div>
                                            <div v-else class="text-slate-400 text-xs">
                                                <i class="bi bi-qr-code text-5xl text-slate-700"></i>
                                            </div>
                                        </a>
                                        <span class="text-[10px] font-bold text-slate-700">Verificación Digital QR</span>
                                        <span class="text-[9px] text-slate-400 font-mono mt-0.5">{{ cItem.certificate_code }}</span>
                                        <span class="text-[9px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                                            <i class="bi bi-shield-check"></i> Sello Institucional
                                        </span>
                                    </div>
                                </div>

                                <!-- Beneficiary & Course Metadata Grid -->
                                <div class="print-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Estudiante / Titular</span>
                                        <p class="text-xs sm:text-sm font-extrabold text-slate-900 mt-0.5 uppercase leading-tight">
                                            {{ cItem.user?.names || 'No especificado' }}
                                        </p>
                                        <span class="text-[11px] text-slate-500 mt-0.5 block font-mono">
                                            DNI: {{ cItem.user?.dni || '—' }}
                                        </span>
                                    </div>

                                    <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                            {{ isBasicEnglish(cItem) ? 'Curso & Duración' : (isTraining(cItem) ? 'Condición de Participación' : 'Modalidad & Horas') }}
                                        </span>
                                        <p class="text-xs sm:text-sm font-extrabold text-indigo-700 mt-0.5 flex items-center gap-1.5 leading-tight">
                                            <i class="bi" :class="isBasicEnglish(cItem) ? 'bi-translate' : (isTraining(cItem) ? 'bi-person-badge-fill' : (cItem.modality === 'Virtual' ? 'bi-laptop' : (cItem.modality === 'Semipresencial' ? 'bi-shuffle' : 'bi-building')))"></i>
                                            {{ isBasicEnglish(cItem) ? 'Inglés a Nivel Básico' : (isTraining(cItem) ? (cItem.participation_type || 'ASISTENTE') : (cItem.modality || 'Presencial')) }}
                                        </p>
                                        <span class="text-[11px] text-slate-500 mt-0.5 block">
                                            Duración: <strong>{{ cItem.duration || (isBasicEnglish(cItem) ? '128 horas pedagógicas' : (isTraining(cItem) ? '90 horas pedagógicas' : '128 Horas')) }}</strong>
                                        </span>
                                    </div>

                                    <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Periodo &amp; Emisión</span>
                                        <p class="text-[11px] sm:text-xs font-bold text-slate-800 mt-0.5 leading-tight">
                                            {{ cItem.formatted_date_range || formatDate(cItem.start_date) }}
                                        </p>
                                        <span class="text-[10px] text-slate-500 mt-0.5 block">
                                            Emisión: <strong>{{ formatDate(cItem.issue_date) }}</strong>
                                        </span>
                                    </div>

                                    <div class="print-meta-box bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Institución &amp; Sede</span>
                                        <p class="text-[11px] sm:text-xs font-bold text-slate-800 mt-0.5 leading-tight">
                                            {{ enterprise.trade_name || enterprise.company_name || 'IESTP Francisco Vigo Caballero' }}
                                        </p>
                                        <span class="text-[10px] text-slate-500 mt-0.5 block">
                                            {{ cItem.city || 'Uchiza' }}, San Martín
                                        </span>
                                    </div>
                                </div>

                                <!-- Details Section: English vs Training vs Modular Grades -->
                                <!-- 1. Basic English Academic Record -->
                                <div v-if="isBasicEnglish(cItem)" class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="bi bi-award-fill text-amber-500"></i>
                                            Registro Académico y Calificaciones — Inglés a Nivel Básico
                                        </h4>
                                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                            <i class="bi bi-check-circle-fill text-[9px] mr-1"></i> Aprobado Satisfactoriamente
                                        </span>
                                    </div>

                                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs bg-white">
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left border-collapse text-xs">
                                                <thead>
                                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-extrabold uppercase text-[10px] sm:text-[11px] tracking-wider text-center">
                                                        <th class="px-4 py-3 text-left w-[46%]">Módulos y Contenidos</th>
                                                        <th class="px-3 py-3 w-[12%]">N° Créditos</th>
                                                        <th class="px-3 py-3 w-[10%]">Calificación<br><span class="text-[9px] text-slate-400 font-normal">En Número</span></th>
                                                        <th class="px-3 py-3 w-[14%]">Calificación<br><span class="text-[9px] text-slate-400 font-normal">En Letras</span></th>
                                                        <th class="px-3 py-3 w-[10%]">Año</th>
                                                        <th class="px-3 py-3 w-[8%]">Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                                    <tr v-for="mod in (cItem.english_modules_data || [])" :key="mod.name" class="hover:bg-slate-50/50 transition-colors">
                                                        <td class="px-4 py-3.5 align-top">
                                                            <div class="font-bold text-slate-900 text-xs mb-1.5 underline decoration-slate-300 underline-offset-2">
                                                                {{ mod.name }}
                                                            </div>
                                                            <ul class="space-y-0.5 text-[11px] text-slate-600 pl-0 list-none">
                                                                <li v-for="content in (mod.contents || [])" :key="content" class="flex items-start gap-1.5">
                                                                    <span class="text-emerald-600 font-bold shrink-0">✓</span>
                                                                    <span>{{ content }}</span>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td class="px-3 py-3.5 text-center font-bold text-slate-900 align-middle">
                                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 font-black">
                                                                {{ mod.credits }}
                                                            </span>
                                                        </td>
                                                        <td class="px-3 py-3.5 text-center font-extrabold text-indigo-700 text-sm align-middle">
                                                            {{ mod.score_num }}
                                                        </td>
                                                        <td class="px-3 py-3.5 text-center font-semibold text-slate-800 align-middle">
                                                            {{ mod.score_text }}
                                                        </td>
                                                        <td class="px-3 py-3.5 text-center font-mono text-[11px] text-slate-600 align-middle">
                                                            {{ mod.year }}
                                                        </td>
                                                        <td class="px-3 py-3.5 text-center text-slate-400 italic text-[11px] align-middle">
                                                            {{ mod.observation || '—' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Training Course Syllabus -->
                                <div v-else-if="isTraining(cItem)" class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="bi bi-list-check text-indigo-600"></i>
                                            Temario y Contenidos Desarrollados
                                        </h4>
                                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                            <i class="bi bi-check-circle-fill text-[9px] mr-1"></i> Curso de Capacitación Aprobado
                                        </span>
                                    </div>

                                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs bg-slate-50/50 p-4 sm:p-5">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div v-for="(topic, index) in (cItem.topics_list || [])" :key="index"
                                                class="flex items-start gap-2.5 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
                                                <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                                    {{ index + 1 }}
                                                </span>
                                                <span class="text-xs font-medium text-slate-800 leading-snug">
                                                    {{ topic }}
                                                </span>
                                            </div>
                                            <p v-if="!cItem.topics_list || cItem.topics_list.length === 0" class="col-span-2 text-xs text-slate-500 italic text-center py-3">
                                                Temario general del curso de capacitación institucional.
                                            </p>
                                        </div>
                                        <div v-if="cItem.event_name || cItem.course?.event_name"
                                            class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-600">
                                            <span><strong>Marco Institucional:</strong> {{ cItem.event_name || cItem.course?.event_name }}</span>
                                            <span><strong>Duración Certificada:</strong> {{ cItem.duration || '90 horas pedagógicas' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Modular Course Evaluation Grades -->
                                <div v-else class="space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="bi bi-journal-check text-indigo-600"></i>
                                            Detalle de Calificaciones Modulares
                                        </h4>
                                        <span class="text-[11px] text-slate-500">Escala vigesimal (0 a 20)</span>
                                    </div>

                                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs bg-white">
                                        <table class="w-full text-left border-collapse text-xs">
                                            <thead>
                                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] sm:text-[11px] tracking-wider">
                                                    <th class="px-3.5 py-2.5 text-center w-10">#</th>
                                                    <th class="px-3.5 py-2.5">Módulo / Unidad de Competencia</th>
                                                    <th class="px-3.5 py-2.5 text-center">Créditos</th>
                                                    <th class="px-3.5 py-2.5 text-center">Calificación</th>
                                                    <th class="px-3.5 py-2.5 text-center">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                                <tr v-for="(detail, index) in (cItem.details || [])" :key="detail.id || index" class="hover:bg-slate-50/80 transition-colors">
                                                    <td class="px-3.5 py-2.5 text-center font-mono font-bold text-slate-400">
                                                        {{ index + 1 }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 font-semibold text-slate-900">
                                                        {{ detail.module?.name || `Módulo ${index + 1}` }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 text-center font-mono text-slate-600">
                                                        {{ detail.module?.credits || 3 }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 text-center">
                                                        <span class="inline-flex items-center justify-center font-mono font-bold px-2 py-0.5 rounded"
                                                            :class="parseFloat(detail.score) >= 13 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'">
                                                            {{ detail.score }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3.5 py-2.5 text-center">
                                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold"
                                                            :class="parseFloat(detail.score) >= 13 ? 'text-emerald-700' : 'text-red-600'">
                                                            <i class="bi bi-check-circle-fill"></i> Aprobado
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr v-if="!cItem.details || cItem.details.length === 0">
                                                    <td colspan="5" class="px-4 py-5 text-center text-slate-400 italic">
                                                        No se registran detalles individuales de notas para este certificado.
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot v-if="cItem.details && cItem.details.length > 0 && calculateAverage(cItem.details)">
                                                <tr class="bg-indigo-50/50 border-t-2 border-indigo-100 font-bold text-slate-900">
                                                    <td colspan="3" class="px-3.5 py-2.5 text-right uppercase text-[10px] sm:text-xs tracking-wider text-indigo-900">
                                                        Promedio Modular Ponderado:
                                                    </td>
                                                    <td class="px-3.5 py-2.5 text-center font-mono font-black text-indigo-950 text-sm">
                                                        {{ calculateAverage(cItem.details) }}
                                                    </td>
                                                    <td class="px-3.5 py-2.5 text-center text-[11px] font-extrabold text-emerald-700">
                                                        APROBADO
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <!-- Official Print Footer Notes (Visible in Print) -->
                                <div class="print-only border-t border-slate-200 pt-3 text-[8.5pt] text-slate-500 space-y-1">
                                    <div class="flex justify-between items-center">
                                        <span><strong>Enlace Permanente de Verificación:</strong> {{ cItem.validation_url }}</span>
                                        <span><strong>Fecha y Hora de Consulta:</strong> {{ new Date().toLocaleString('es-PE') }}</span>
                                    </div>
                                    <p class="text-[8pt] text-slate-400 italic">
                                        * Constancia de verificación emitida electrónicamente por el IESTP Francisco Vigo Caballero de conformidad con la Ley General de Educación N° 28044 y normas del MINEDU.
                                    </p>
                                </div>

                                <!-- Action Buttons (No-print) -->
                                <div class="no-print flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a :href="route('validar-certificado.print', cItem.certificate_code)"
                                            target="_blank"
                                            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2">
                                            <i class="bi bi-award-fill"></i>
                                            <span>Ver Certificado Oficial (Formato Original)</span>
                                        </a>
                                        <button type="button" @click="printSheet"
                                            class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2">
                                            <i class="bi bi-printer-fill"></i>
                                            <span>Imprimir Ficha</span>
                                        </button>
                                        <button type="button" @click="copyValidationLink(cItem.validation_url)"
                                            class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-all inline-flex items-center gap-2">
                                            <i class="bi" :class="copiedLink ? 'bi-check2 text-emerald-600' : 'bi-link-45deg'"></i>
                                            <span>{{ copiedLink ? '¡Enlace Copiado!' : 'Copiar Enlace Directo' }}</span>
                                        </button>
                                    </div>
                                    <Link :href="route('validar-certificado')"
                                        class="text-xs sm:text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
                                        <i class="bi bi-arrow-repeat"></i> Validar otro documento
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Not Found Card -->
                <div v-else class="bg-white rounded-3xl shadow-xl border border-red-200 p-8 text-center space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-red-50 text-red-500 border border-red-100 flex items-center justify-center text-3xl shadow-inner">
                        <i class="bi bi-shield-x"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-black text-slate-900 font-display">Certificado No Encontrado</h3>
                        <p class="text-sm text-slate-600 max-w-lg mx-auto">
                            No se encontró ningún certificado registrado bajo el código o DNI <strong class="font-mono text-red-600">"{{ searchCode }}"</strong>.
                        </p>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4 text-xs max-w-xl mx-auto text-left space-y-1">
                        <p class="font-bold flex items-center gap-1.5 text-amber-900">
                            <i class="bi bi-lightbulb-fill text-amber-600"></i> Sugerencias para la búsqueda:
                        </p>
                        <ul class="list-disc list-inside space-y-0.5 text-amber-800/90 pl-1">
                            <li>Verifique que el código incluya el prefijo <code class="bg-amber-100/80 px-1 py-0.5 rounded font-mono">CERT-</code> (ej: <code class="font-mono">CERT-00000011-1</code>).</li>
                            <li>Asegúrese de ingresar el número de DNI completo de 8 dígitos.</li>
                            <li>Si escaneó un código QR, intente volver a enfocar la cámara.</li>
                        </ul>
                    </div>
                    <div class="pt-2">
                        <Link :href="route('validar-certificado')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition-all">
                            <i class="bi bi-arrow-counterclockwise"></i> Nueva Búsqueda
                        </Link>
                    </div>
                </div>
            </div>

            <!-- ═══ SECTION: SUMMARY TABLES BY YEAR, PERIODS, & STUDY PROGRAMS (HIDDEN IN PRINT) ════ -->
            <section class="summary-tables-section no-print max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8">
                <!-- Section Title -->
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                        <i class="bi bi-table"></i>
                        Consolidado Oficial de Certificaciones
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 font-display">
                        Resumen Estadístico Institucional
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600">
                        Resúmenes tabulares oficiales de certificados emitidos, consolidados y agrupados por <strong>año de emisión</strong>, <strong>periodos académicos</strong> y <strong>programas de estudio</strong>.
                    </p>
                </div>

                <!-- KPI Stat Chips Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Certificados</p>
                            <h3 class="text-2xl font-black text-slate-900 font-display">{{ formatNumber(totalCertificates) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Certificados Válidos</p>
                            <h3 class="text-2xl font-black text-emerald-700 font-display">{{ formatNumber(validCertificates) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Estudiantes Acreditados</p>
                            <h3 class="text-2xl font-black text-blue-700 font-display">{{ formatNumber(totalStudents) }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl shrink-0">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cursos &amp; Programas</p>
                            <h3 class="text-2xl font-black text-indigo-700 font-display">{{ formatNumber(totalCourses) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Tabbed Summary Tables Container -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Tab Navigation Bar -->
                    <div class="bg-slate-50 border-b border-slate-200 px-4 sm:px-6 pt-3 flex flex-wrap items-center gap-2">
                        <button type="button" @click="activeTab = 'years'"
                            :class="activeTab === 'years' ? 'border-indigo-600 text-indigo-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-3 rounded-t-xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-calendar-event"></i>
                            <span>1. Resumen por Años</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200/70 text-slate-700 font-mono">{{ yearsSummary.length }}</span>
                        </button>

                        <button type="button" @click="activeTab = 'periods'"
                            :class="activeTab === 'periods' ? 'border-indigo-600 text-indigo-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-3 rounded-t-xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-calendar3-range"></i>
                            <span>2. Resumen por Periodos</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200/70 text-slate-700 font-mono">{{ periodsSummary.length }}</span>
                        </button>

                        <button type="button" @click="activeTab = 'programs'"
                            :class="activeTab === 'programs' ? 'border-indigo-600 text-indigo-700 bg-white shadow-xs font-bold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-3 rounded-t-xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-mortarboard"></i>
                            <span>3. Resumen por Programas &amp; Cursos</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200/70 text-slate-700 font-mono">{{ coursesSummary.length }}</span>
                        </button>
                    </div>

                    <!-- Tab 1: Resumen por Años -->
                    <div v-show="activeTab === 'years'" class="p-6 sm:p-8 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Resumen Consolidado de Certificados por Año</h3>
                                <p class="text-xs text-slate-500">Distribución de certificaciones emitidas agrupadas por año de emisión y modalidad académica.</p>
                            </div>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-hidden overflow-x-auto shadow-xs">
                            <table class="min-w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                        <th class="px-4 py-3.5 text-center">Año</th>
                                        <th class="px-4 py-3.5 text-center">Total Certificados</th>
                                        <th class="px-4 py-3.5 text-center">Presencial</th>
                                        <th class="px-4 py-3.5 text-center">Semipresencial</th>
                                        <th class="px-4 py-3.5 text-center">Virtual</th>
                                        <th class="px-4 py-3.5 text-center">Estudiantes Únicos</th>
                                        <th class="px-4 py-3.5 text-center">Certificados Válidos</th>
                                        <th class="px-4 py-3.5 text-center">% Conformidad</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    <tr v-for="row in yearsSummary" :key="row.year" class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 text-center font-mono font-bold text-indigo-900 bg-indigo-50/30">
                                            {{ row.year }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-900 font-mono">
                                            {{ formatNumber(row.total_certificates) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-600">
                                            {{ formatNumber(row.presencial_count) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-600">
                                            {{ formatNumber(row.semipresencial_count) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-600">
                                            {{ formatNumber(row.virtual_count) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-semibold text-blue-700">
                                            {{ formatNumber(row.total_students) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-semibold text-emerald-700">
                                            {{ formatNumber(row.active_count) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <i class="bi bi-check-circle-fill text-[10px]"></i>
                                                {{ row.total_certificates > 0 ? Math.round((row.active_count / row.total_certificates) * 1000) / 10 : 100 }}%
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="yearsSummary.length === 0">
                                        <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">
                                            No hay registros de certificados para agrupar por año.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot v-if="yearsSummary.length > 0">
                                    <tr class="bg-slate-100/80 border-t-2 border-slate-300 font-bold text-slate-900">
                                        <td class="px-4 py-3 text-center uppercase text-xs tracking-wider text-slate-700">
                                            TOTAL CONSOLIDADO
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-black text-indigo-950">
                                            {{ formatNumber(sumYears.total) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-700">
                                            {{ formatNumber(sumYears.presencial) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-700">
                                            {{ formatNumber(sumYears.semi) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-700">
                                            {{ formatNumber(sumYears.virtual) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-bold text-blue-800">
                                            {{ formatNumber(sumYears.students) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-bold text-emerald-800">
                                            {{ formatNumber(sumYears.active) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-emerald-800">
                                            100.0%
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Resumen por Periodos Académicos -->
                    <div v-show="activeTab === 'periods'" class="p-6 sm:p-8 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Resumen Consolidado por Periodo Académico</h3>
                                <p class="text-xs text-slate-500">Agrupación semestral de certificados (Periodo I: Enero–Junio, Periodo II: Julio–Diciembre).</p>
                            </div>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-hidden overflow-x-auto shadow-xs">
                            <table class="min-w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                        <th class="px-4 py-3.5 text-center">Periodo</th>
                                        <th class="px-4 py-3.5 text-center">Cursos Dictados</th>
                                        <th class="px-4 py-3.5 text-center">Estudiantes Registrados</th>
                                        <th class="px-4 py-3.5 text-center">Modalidad Predominante</th>
                                        <th class="px-4 py-3.5 text-center">Total Certificados</th>
                                        <th class="px-4 py-3.5 text-center">Certificados Activos</th>
                                        <th class="px-4 py-3.5 text-center">Estado del Periodo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    <tr v-for="row in periodsSummary" :key="row.period" class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 text-center font-mono font-bold text-indigo-900 bg-indigo-50/30">
                                            {{ row.period }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-600">
                                            {{ row.total_courses }} Curso(s)
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-semibold text-blue-700">
                                            {{ formatNumber(row.total_students) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center gap-1 font-semibold text-slate-700 text-xs">
                                                <i class="bi" :class="(row.virtual_count || 0) >= (row.presencial_count || 0) ? 'bi-laptop text-indigo-600' : 'bi-building text-blue-600'"></i>
                                                {{ (row.virtual_count || 0) >= (row.presencial_count || 0) ? 'Virtual' : 'Presencial' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-900 font-mono">
                                            {{ formatNumber(row.total_certificates) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-semibold text-emerald-700">
                                            {{ formatNumber(row.active_count) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <i class="bi bi-check2"></i> Concluido &amp; Auditado
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="periodsSummary.length === 0">
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">
                                            No hay registros de certificados para agrupar por periodo.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot v-if="periodsSummary.length > 0">
                                    <tr class="bg-slate-100/80 border-t-2 border-slate-300 font-bold text-slate-900">
                                        <td class="px-4 py-3 text-center uppercase text-xs tracking-wider text-slate-700">
                                            TOTAL CONSOLIDADO
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono text-slate-700">
                                            {{ totalCourses }} Cursos
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-bold text-blue-800">
                                            {{ formatNumber(sumPeriods.students) }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-slate-500 font-normal">
                                            —
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-black text-indigo-950">
                                            {{ formatNumber(sumPeriods.total) }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono font-bold text-emerald-800">
                                            {{ formatNumber(sumPeriods.active) }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-emerald-800 font-bold">
                                            100% Válidos
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Resumen por Programas de Estudio y Cursos -->
                    <div v-show="activeTab === 'programs'" class="p-6 sm:p-8 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Resumen Consolidado por Cursos y Programas de Estudio</h3>
                                <p class="text-xs text-slate-500">Métricas de desempeño académico, módulos evaluados y total de beneficiarios por curso.</p>
                            </div>
                        </div>

                        <div class="border border-slate-200 rounded-2xl overflow-hidden overflow-x-auto shadow-xs">
                            <table class="min-w-full text-left border-collapse text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                        <th class="px-4 py-3.5">Curso / Programa Vinculado</th>
                                        <th class="px-4 py-3.5 text-center">Módulos</th>
                                        <th class="px-4 py-3.5 text-center">Total Certificados</th>
                                        <th class="px-4 py-3.5 text-center">Estudiantes</th>
                                        <th class="px-4 py-3.5 text-center">Promedio Notas</th>
                                        <th class="px-4 py-3.5 text-center">Rango (Mín / Máx)</th>
                                        <th class="px-4 py-3.5 text-center">Certificados Activos</th>
                                        <th class="px-4 py-3.5 text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    <tr v-for="row in coursesSummary" :key="row.course_name" class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3.5">
                                            <span class="font-bold text-slate-900 block font-display">{{ row.course_name }}</span>
                                            <span class="text-xs text-slate-500 block line-clamp-1">{{ row.course_description }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-mono text-slate-600">
                                            {{ row.modules_count || 2 }} Módulos
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-bold text-slate-900 font-mono">
                                            {{ formatNumber(row.total_certificates) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-mono font-semibold text-blue-700">
                                            {{ formatNumber(row.total_students) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span v-if="row.avg_score !== null"
                                                class="inline-flex items-center justify-center font-mono font-bold px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-800 border border-indigo-100">
                                                {{ Number(row.avg_score).toFixed(2) }}
                                            </span>
                                            <span v-else class="text-xs text-slate-400 font-medium italic">Capacitación</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-mono text-xs text-slate-500">
                                            <span v-if="row.min_score !== null && row.max_score !== null">
                                                {{ Math.round(row.min_score) }} - {{ Math.round(row.max_score) }}
                                            </span>
                                            <span v-else>—</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-mono font-semibold text-emerald-700">
                                            {{ formatNumber(row.active_count) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <i class="bi bi-patch-check-fill text-emerald-600"></i> Activo
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="coursesSummary.length === 0">
                                        <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">
                                            No hay registros de cursos o programas vinculados.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Study Programs Reference Grid -->
                        <div class="pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                                Programas de Estudio del Instituto con Certificación Modular:
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div v-for="program in studyPrograms" :key="program.id"
                                    class="p-3 bg-slate-50/80 border border-slate-200 rounded-xl flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm shrink-0"
                                        :class="program.icon_bg_class || 'bg-indigo-50 text-indigo-600'">
                                        <i class="bi" :class="program.icon || 'bi-mortarboard'"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-xs font-bold text-slate-900 truncate">{{ program.name }}</p>
                                        <p class="text-[11px] text-slate-500 truncate">Acreditación y Licenciamiento FVC</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Institutional Legal & Security Notice Card -->
                <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-800 relative overflow-hidden">
                    <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="space-y-2 max-w-2xl">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-bold">
                                <i class="bi bi-shield-lock-fill"></i> Seguridad Institucional Garantizada
                            </div>
                            <h3 class="text-xl font-bold text-white font-display">
                                Validez Oficial de Certificados Digitales
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                Los certificados emitidos por el <strong class="text-white">{{ enterprise.company_name || 'IESTP Francisco Vigo Caballero' }}</strong>
                                cuentan con firma digital, correlativo único institucional y registro en los sistemas académicos. Cualquier consulta adicional puede ser canalizada a través de la Mesa de Partes Virtual.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full md:w-auto">
                            <Link :href="route('mesa-de-partes')"
                                class="px-5 py-3 bg-white text-slate-900 hover:bg-slate-100 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition-all text-center">
                                <i class="bi bi-inbox-fill mr-1.5 text-blue-600"></i> Mesa de Partes
                            </Link>
                            <Link :href="route('bolsa-de-trabajo')"
                                class="px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs sm:text-sm font-bold transition-all text-center">
                                <i class="bi bi-briefcase-fill mr-1.5 text-emerald-400"></i> Bolsa de Trabajo
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

<style>
.print-only {
    display: none !important;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 6mm 8mm 6mm 8mm;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box !important;
    }

    #site-header,
    .nav-inner,
    #site-footer,
    .footer-grid,
    .footer-bottom,
    .hero-section,
    .summary-tables-section,
    .no-print,
    a.sr-only,
    header,
    nav,
    footer,
    form {
        display: none !important;
        position: absolute !important;
        visibility: hidden !important;
        height: 0 !important;
        max-height: 0 !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        top: -99999px !important;
        left: -99999px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        pointer-events: none !important;
    }

    html,
    body {
        background: #ffffff !important;
        color: #0f172a !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
        overflow: visible !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    main,
    #main-content,
    .validate-page-wrapper,
    .certificate-result-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        min-height: 0 !important;
        position: static !important;
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        background: transparent !important;
    }

    .print-only {
        display: block !important;
    }

    .print-header-flex {
        display: flex !important;
    }

    .print-card-body {
        padding: 12pt !important;
        gap: 8pt !important;
    }

    .print-grid {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 6pt !important;
    }

    .print-meta-box {
        border: 0.75pt solid #cbd5e1 !important;
        background-color: #f8fafc !important;
        padding: 5pt 6pt !important;
        border-radius: 4pt !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    th,
    td {
        border: 0.5pt solid #cbd5e1 !important;
        padding: 3.5pt 5pt !important;
    }

    thead th {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }
}
</style>
