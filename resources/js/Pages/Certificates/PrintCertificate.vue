<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    certificate: {
        type: Object,
        required: true,
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const activeView = ref('both'); // 'both', 'front', 'back'

const isBasicEnglish = computed(() => {
    const cert = props.certificate;
    const name = (cert.course?.name || cert.event_name || '').toLowerCase();
    return name.includes('inglés') || name.includes('ingles') || cert.certificate_type === 'english' || (cert.english_modules_data && cert.english_modules_data.length > 0);
});

const isSmartOffice = computed(() => {
    const cert = props.certificate;
    const name = (cert.course?.name || cert.description || '').toLowerCase();
    return name.includes('inteligente') || name.includes('oficina') || cert.certificate_type === 'smart_office';
});

const isTraining = computed(() => {
    const cert = props.certificate;
    if (isBasicEnglish.value) return false;
    const modality = (cert.modality || '').toLowerCase();
    const certType = (cert.certificate_type || '').toLowerCase();
    return certType === 'training' || modality === 'capacitación' || modality === 'capacitacion' || (cert.topics_list && cert.topics_list.length > 0 && (!cert.details || cert.details.length === 0));
});

const logoSrc = computed(() => {
    return props.enterprise?.logo_base64 || props.enterprise?.logo_path || '/enterprise/favicons/logo-iestpfvc.png';
});

const printDoc = () => {
    if (typeof window !== 'undefined') {
        window.print();
    }
};

const goBack = () => {
    if (typeof window !== 'undefined' && window.history.length > 1) {
        window.history.back();
    }
};
</script>

<template>
    <div class="print-page-wrapper bg-[#0b0f19] text-slate-900 min-h-screen">
        <Head>
            <title>{{ `Certificado — ${certificate.certificate_code} — ${certificate.user?.names ?? 'Estudiante'}` }}</title>
        </Head>

        <!-- Floating Action Toolbar (Hidden in Print) -->
        <header class="toolbar no-print">
            <div class="toolbar-info">
                <span class="toolbar-badge">{{ isBasicEnglish ? 'Certificado de Inglés' : (isTraining ? 'Capacitación Oficial' : 'Certificado Modular') }}</span>
                <span class="toolbar-code">{{ certificate.certificate_code }}</span>
                <span class="text-xs text-slate-400 hidden sm:inline">{{ certificate.user?.names }}</span>
            </div>

            <!-- View Switcher for English 2-sided Certificate -->
            <div v-if="isBasicEnglish" class="view-switcher flex items-center gap-1 bg-white/10 p-1 rounded-xl text-xs">
                <button type="button" @click="activeView = 'both'"
                    :class="activeView === 'both' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:text-white'"
                    class="px-2.5 py-1 rounded-lg transition-all">
                    Ambas Caras
                </button>
                <button type="button" @click="activeView = 'front'"
                    :class="activeView === 'front' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:text-white'"
                    class="px-2.5 py-1 rounded-lg transition-all">
                    Anverso (Frente)
                </button>
                <button type="button" @click="activeView = 'back'"
                    :class="activeView === 'back' ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-300 hover:text-white'"
                    class="px-2.5 py-1 rounded-lg transition-all">
                    Reverso (Notas)
                </button>
            </div>

            <div class="toolbar-actions flex items-center gap-2">
                <Link :href="route('validar-certificado', { certificate_code: certificate.certificate_code })"
                    class="btn btn-secondary">
                    <i class="bi bi-shield-check"></i>
                    <span class="hidden md:inline">Validación Pública</span>
                </Link>
                <button type="button" @click="printDoc" class="btn btn-primary">
                    <i class="bi bi-printer-fill"></i>
                    <span>Imprimir Documento</span>
                </button>
                <button type="button" @click="goBack" class="btn btn-secondary">
                    <span>Volver</span>
                </button>
            </div>
        </header>

        <!-- Preview Stage Canvas -->
        <main class="stage" :class="{ 'english-stage': isBasicEnglish }">
            <!-- 1. ENGLISH CERTIFICATE (ANVERSO & REVERSO) -->
            <template v-if="isBasicEnglish">
                <!-- ANVERSO (FRENTE) -->
                <article v-show="activeView === 'both' || activeView === 'front'" class="certificate-sheet english-sheet page-break" id="sheet-anverso">
                    <!-- Top-Left Flowing Corner Wave SVG -->
                    <svg class="english-wave english-wave-tl" viewBox="0 0 520 280" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="dotPatternTL" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="1.2" fill="#2b477d" opacity="0.35" />
                            </pattern>
                            <linearGradient id="engNavyGradTL" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#0a1832" />
                                <stop offset="50%" stop-color="#122a56" />
                                <stop offset="100%" stop-color="#1b3d7a" />
                            </linearGradient>
                            <linearGradient id="engGoldGradTL" x1="0%" y1="100%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#9d741c" />
                                <stop offset="35%" stop-color="#dfb957" />
                                <stop offset="65%" stop-color="#fae79d" />
                                <stop offset="100%" stop-color="#b68926" />
                            </linearGradient>
                            <linearGradient id="engRoyalGradTL" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#1a3d78" />
                                <stop offset="100%" stop-color="#2c5aa0" />
                            </linearGradient>
                        </defs>
                        <path d="M0,0 L340,0 C280,95 200,180 0,225 Z" fill="url(#engNavyGradTL)" />
                        <path d="M0,0 L340,0 C280,95 200,180 0,225 Z" fill="url(#dotPatternTL)" />
                        <path d="M0,185 C190,150 285,75 350,0 L370,0 C305,85 205,165 0,205 Z" fill="url(#engGoldGradTL)" />
                        <path d="M0,0 L195,0 C130,75 75,120 0,155 Z" fill="url(#engRoyalGradTL)" />
                        <path d="M0,155 C70,118 122,75 190,0 L200,0 C132,80 77,122 0,162 Z" fill="url(#engGoldGradTL)" />
                    </svg>

                    <!-- Bottom-Right Flowing Corner Wave SVG -->
                    <svg class="english-wave english-wave-br" viewBox="0 0 520 280" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="dotPatternBR" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="1.2" fill="#2b477d" opacity="0.35" />
                            </pattern>
                            <linearGradient id="engNavyGradBR" x1="100%" y1="100%" x2="0%" y2="0%">
                                <stop offset="0%" stop-color="#0a1832" />
                                <stop offset="50%" stop-color="#122a56" />
                                <stop offset="100%" stop-color="#1b3d7a" />
                            </linearGradient>
                            <linearGradient id="engGoldGradBR" x1="100%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#9d741c" />
                                <stop offset="35%" stop-color="#dfb957" />
                                <stop offset="65%" stop-color="#fae79d" />
                                <stop offset="100%" stop-color="#b68926" />
                            </linearGradient>
                        </defs>
                        <path d="M520,280 L180,280 C240,185 320,100 520,55 Z" fill="url(#engNavyGradBR)" />
                        <path d="M520,280 L180,280 C240,185 320,100 520,55 Z" fill="url(#dotPatternBR)" />
                        <path d="M520,95 C330,130 235,205 170,280 L150,280 C215,195 315,115 520,75 Z" fill="url(#engGoldGradBR)" />
                    </svg>

                    <!-- Watermark -->
                    <img :src="logoSrc" alt="Marca de agua" class="english-watermark" />

                    <!-- Top Header Section -->
                    <div class="english-header">
                        <img :src="logoSrc" alt="Logo IESTP FVC" class="english-logo-img" />
                        <div class="english-inst-text">
                            <div class="eng-inst-line1">INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</div>
                            <div class="eng-inst-line2">“FRANCISCO VIGO CABALLERO”</div>
                            <div class="eng-inst-subline">LEY DE CREACIÓN N° 24016 • REVALIDADO CON R.D. N° 0450-2006-ED</div>
                        </div>
                    </div>

                    <!-- Certificate Title Banner -->
                    <div class="english-title-box">
                        <h1 class="english-title-h1">CERTIFICADO</h1>
                        <div class="english-title-sub">DE INGLÉS A NIVEL BÁSICO</div>
                    </div>

                    <!-- Recipient Block -->
                    <div class="english-content-center">
                        <p class="english-granted-to">Otorgado a:</p>
                        <div class="english-recipient-name">
                            {{ (certificate.user?.names || 'ESTUDIANTE REGISTRADO').toUpperCase() }}
                        </div>
                        <p class="english-narrative-text">
                            Por haber culminado y aprobado satisfactoriamente los <strong>tres módulos formativos</strong> de la
                            capacitación en <strong>“INGLÉS A NIVEL BÁSICO”</strong>, desarrollado en el
                            Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero” de Uchiza,
                            del <strong>{{ certificate.formatted_date_range }}</strong>, con una duración de
                            <strong>{{ certificate.duration || '128 horas pedagógicas' }}</strong>.
                        </p>
                    </div>

                    <!-- Location & Date -->
                    <div class="english-date-location">
                        {{ certificate.city || 'Uchiza' }}, {{ certificate.formatted_issue_date }}
                    </div>
                </article>

                <!-- REVERSO (NOTAS & QR) -->
                <article v-show="activeView === 'both' || activeView === 'back'" class="certificate-sheet english-sheet english-sheet-back" id="sheet-reverso">
                    <div class="english-back-grid flex gap-8 items-center h-full">
                        <!-- Left QR Column -->
                        <div class="english-qr-column w-64 flex flex-col items-center justify-center p-6 border-r border-slate-200">
                            <div class="english-qr-card text-center">
                                <a :href="certificate.validation_url" target="_blank" class="block w-48 h-48 bg-white p-2 border border-slate-200 rounded-xl shadow-inner mx-auto mb-3">
                                    <div v-if="certificate.qr_code_svg" class="w-full h-full [&>svg]:w-full [&>svg]:h-full" v-html="certificate.qr_code_svg"></div>
                                    <div v-else class="text-slate-400"><i class="bi bi-qr-code text-6xl"></i></div>
                                </a>
                                <div class="font-mono font-bold text-sm text-slate-800">{{ certificate.certificate_code }}</div>
                                <div class="text-[10px] uppercase font-bold text-slate-500 mt-1">ESCANEAR PARA VALIDAR</div>
                            </div>
                        </div>

                        <!-- Right Academic Table Column -->
                        <div class="english-table-column flex-1 relative">
                            <img :src="logoSrc" alt="Marca de agua" class="english-table-watermark absolute inset-0 m-auto opacity-10 w-64 pointer-events-none" />
                            <table class="w-full text-xs border border-slate-300 border-collapse">
                                <thead>
                                    <tr class="bg-slate-100 text-slate-800 font-bold text-center">
                                        <th rowspan="2" class="border border-slate-300 p-2 text-left">MÓDULOS Y CONTENIDOS</th>
                                        <th rowspan="2" class="border border-slate-300 p-2">CRÉDITOS</th>
                                        <th colspan="2" class="border border-slate-300 p-2">CALIFICACIÓN</th>
                                        <th rowspan="2" class="border border-slate-300 p-2">AÑO</th>
                                        <th rowspan="2" class="border border-slate-300 p-2">OBSERVACIÓN</th>
                                    </tr>
                                    <tr class="bg-slate-50 text-[10px] text-slate-600">
                                        <th class="border border-slate-300 p-1">NÚMERO</th>
                                        <th class="border border-slate-300 p-1">LETRAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="mod in (certificate.english_modules_data || [])" :key="mod.name">
                                        <td class="border border-slate-300 p-2">
                                            <div class="font-bold text-slate-900 text-xs mb-1">{{ mod.name }}</div>
                                            <ul class="text-[10.5px] text-slate-600 space-y-0.5">
                                                <li v-for="c in (mod.contents || [])" :key="c" class="flex items-center gap-1">
                                                    <span class="text-emerald-600 font-bold">✓</span>
                                                    <span>{{ c }}</span>
                                                </li>
                                            </ul>
                                        </td>
                                        <td class="border border-slate-300 p-2 text-center font-bold">{{ mod.credits }}</td>
                                        <td class="border border-slate-300 p-2 text-center font-bold text-indigo-700">{{ mod.score_num }}</td>
                                        <td class="border border-slate-300 p-2 text-center">{{ mod.score_text }}</td>
                                        <td class="border border-slate-300 p-2 text-center font-mono">{{ mod.year }}</td>
                                        <td class="border border-slate-300 p-2 text-center text-slate-500">{{ mod.observation || '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            </template>

            <!-- 2. STANDARD MODULAR / TRAINING CERTIFICATE -->
            <template v-else>
                <article class="certificate-sheet relative">
                    <!-- Geometric Ribbons -->
                    <svg class="ribbon-svg ribbon-top-right" viewBox="0 0 320 240" fill="none" preserveAspectRatio="none">
                        <polygon points="120,0 320,180 320,240 60,0" fill="#c99e3a" />
                        <polygon points="190,0 320,0 320,130" fill="#0f1115" />
                    </svg>
                    <svg class="ribbon-svg ribbon-bottom-left" viewBox="0 0 320 240" fill="none" preserveAspectRatio="none">
                        <polygon points="0,50 220,240 140,240 0,110" fill="#c99e3a" />
                        <polygon points="0,130 0,240 120,240" fill="#0f1115" />
                    </svg>

                    <!-- Watermark -->
                    <img :src="logoSrc" alt="Marca de agua" class="watermark" />

                    <!-- Header -->
                    <header class="cert-header">
                        <img :src="logoSrc" alt="Logo IESTP FVC" class="cert-logo h-20 w-auto object-contain" />
                        <div class="cert-institution-titles">
                            <p class="inst-title-1 text-xs font-bold tracking-widest text-slate-600 uppercase">INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</p>
                            <h2 class="inst-title-2 text-2xl font-black text-slate-900 font-display">“FRANCISCO VIGO CABALLERO”</h2>
                            <div class="inst-subtitle-row flex items-center gap-4 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>PROGRAMA DE ESTUDIOS DE {{ (certificate.study_program?.name || certificate.effective_study_program?.name || 'ADMINISTRACIÓN DE REDES Y COMUNICACIONES').toUpperCase() }}</span>
                                <span class="font-serif italic font-normal text-amber-600">Uchiza</span>
                            </div>
                        </div>
                    </header>

                    <!-- Main Title -->
                    <div class="cert-main-title text-center my-4">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#9a7620] tracking-widest uppercase font-serif">
                            {{ isTraining ? 'CERTIFICADO' : 'CERTIFICADO MODULAR' }}
                        </h1>
                    </div>

                    <!-- Body Area: Narrative & Temario -->
                    <div class="cert-body-grid grid grid-cols-1 md:grid-cols-3 gap-6 items-start my-auto">
                        <div class="narrative-col md:col-span-2 space-y-3">
                            <p class="granted-to-label text-xs uppercase tracking-widest font-bold text-slate-500">Otorgado a:</p>
                            <div class="recipient-box border-b-2 border-amber-500/40 pb-1">
                                <div class="recipient-name text-2xl font-black text-slate-900 font-serif uppercase">
                                    {{ (certificate.user?.names || 'ESTUDIANTE REGISTRADO').toUpperCase() }}
                                </div>
                            </div>

                            <p v-if="isTraining" class="narrative-paragraph text-xs sm:text-sm text-slate-700 leading-relaxed text-justify">
                                Por su participación en calidad de
                                <strong>{{ (certificate.participation_type || 'ASISTENTE').toUpperCase() }}</strong>
                                en el curso de capacitación en
                                “<strong>{{ certificate.description || certificate.course?.name || 'Tecnologías de Información y Comunicación' }}</strong>”;
                                organizado por el Programa de Estudios de
                                {{ certificate.study_program?.name || 'Administración de Redes y Comunicaciones' }}
                                del
                                {{ certificate.institution_name || 'Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero”' }}
                                de Uchiza, con motivo de celebrarse la
                                {{ certificate.event_name || certificate.course?.event_name || 'Semana Técnica 2026' }},
                                realizado {{ certificate.formatted_date_range }}, con una duración de
                                {{ certificate.duration || '90 horas pedagógicas' }}.
                            </p>

                            <p v-else class="narrative-paragraph text-xs sm:text-sm text-slate-700 leading-relaxed text-justify">
                                Por haber aprobado satisfactoriamente los módulos técnico-profesionales correspondientes al plan curricular de
                                <strong>{{ certificate.course?.name }}</strong>, en el Programa de Estudios de
                                {{ certificate.study_program?.name || 'Administración de Redes y Comunicaciones' }}
                                del Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero” de Uchiza,
                                realizado {{ certificate.formatted_date_range }}, con una duración de
                                {{ certificate.duration || 'Horas pedagógicas curriculares' }}.
                            </p>

                            <!-- Modular Details Table if applicable -->
                            <div v-if="!isTraining && certificate.details && certificate.details.length > 0" class="modular-table-wrap mt-2">
                                <table class="w-full text-[11px] border border-slate-200">
                                    <thead class="bg-slate-100 font-bold text-slate-700">
                                        <tr>
                                            <th class="p-1.5 text-left">Módulo Formativo</th>
                                            <th class="p-1.5 text-center w-16">Créditos</th>
                                            <th class="p-1.5 text-center w-16">Nota</th>
                                            <th class="p-1.5 text-center w-24">Condición</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="det in certificate.details" :key="det.id" class="border-t border-slate-200">
                                            <td class="p-1.5">{{ det.title || det.module?.name }}</td>
                                            <td class="p-1.5 text-center">{{ det.module?.credits || 3 }}</td>
                                            <td class="p-1.5 text-center font-bold text-indigo-900">{{ det.score }}</td>
                                            <td class="p-1.5 text-center font-bold text-emerald-700">Aprobado</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="narrative-date-location text-xs text-slate-600 font-serif italic pt-2">
                                {{ certificate.city || 'Uchiza' }}, {{ certificate.formatted_issue_date }}
                            </div>
                        </div>

                        <!-- Right Temario Column -->
                        <div class="temario-col border border-slate-200 rounded-xl bg-slate-50/60 p-3">
                            <div class="temario-badge text-center text-xs font-bold uppercase tracking-wider text-amber-700 border-b border-amber-200 pb-1 mb-2">TEMARIO</div>
                            <ul class="temario-list space-y-1 text-[11px] text-slate-700">
                                <li v-for="(topic, idx) in (certificate.topics_list || [])" :key="idx" class="flex items-start gap-1.5">
                                    <span class="text-amber-600 font-bold">•</span>
                                    <span>{{ topic }}</span>
                                </li>
                                <li v-if="!certificate.topics_list || certificate.topics_list.length === 0" class="text-slate-400 italic text-center py-2">
                                    Temario curricular institucional
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Bar: QR and Certificate Code -->
                    <footer class="cert-bottom-bar flex items-center justify-between border-t border-slate-200 pt-3 mt-4">
                        <div class="text-[10px] text-slate-400">
                            Registro Oficial IESTP FVC • R.M. N° 0450-1997-ED
                        </div>
                        <div class="qr-corner-container flex items-center gap-3">
                            <span class="cert-serial-code font-mono text-xs font-extrabold text-slate-700">{{ certificate.certificate_code }}</span>
                            <div class="w-16 h-16 bg-white border border-slate-200 rounded p-0.5">
                                <div v-if="certificate.qr_code_svg" class="w-full h-full [&>svg]:w-full [&>svg]:h-full" v-html="certificate.qr_code_svg"></div>
                                <div v-else><i class="bi bi-qr-code text-3xl"></i></div>
                            </div>
                        </div>
                    </footer>
                </article>
            </template>
        </main>
    </div>
</template>

<style scoped>
.toolbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    z-index: 1000;
    color: #f8fafc;
}

.toolbar-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toolbar-badge {
    background: rgba(201, 158, 58, 0.2);
    border: 1px solid rgba(201, 158, 58, 0.4);
    color: #f3d789;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 9999px;
}

.toolbar-code {
    font-family: monospace;
    font-weight: 800;
    color: #ffffff;
    font-size: 13px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #c99e3a 0%, #b38927 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(201, 158, 58, 0.35);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #f1f5f9;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.stage {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 20px 40px;
    min-height: 100vh;
}

.english-stage {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 32px;
}

.certificate-sheet {
    width: 297mm;
    height: 210mm;
    position: relative;
    background: #ffffff;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 12mm 18mm 12mm 18mm;
}

.ribbon-svg {
    position: absolute;
    pointer-events: none;
    z-index: 10;
}

.ribbon-top-right {
    top: 0;
    right: 0;
    width: 82mm;
    height: 60mm;
}

.ribbon-bottom-left {
    bottom: 0;
    left: 0;
    width: 82mm;
    height: 60mm;
}

.watermark,
.english-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 140mm;
    height: 140mm;
    opacity: 0.08;
    pointer-events: none;
    z-index: 1;
    object-fit: contain;
}

.english-wave {
    position: absolute;
    pointer-events: none;
    z-index: 10;
}

.english-wave-tl {
    top: 0;
    left: 0;
    width: 85mm;
    height: 50mm;
}

.english-wave-br {
    bottom: 0;
    right: 0;
    width: 85mm;
    height: 50mm;
}

.english-header {
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
    z-index: 15;
}

.english-logo-img {
    height: 16mm;
    width: auto;
    object-fit: contain;
}

.eng-inst-line1 {
    font-size: 8pt;
    font-weight: 700;
    color: #475569;
    letter-spacing: 0.5px;
}

.eng-inst-line2 {
    font-size: 15pt;
    font-weight: 900;
    color: #0f172a;
    font-family: serif;
}

.eng-inst-subline {
    font-size: 6.5pt;
    color: #64748b;
    letter-spacing: 0.3px;
}

.english-title-box {
    text-align: center;
    margin: 3mm 0;
}

.english-title-h1 {
    font-size: 26pt;
    font-weight: 900;
    letter-spacing: 3px;
    color: #1e3a8a;
    font-family: serif;
    line-height: 1;
}

.english-title-sub {
    font-size: 11pt;
    font-weight: 800;
    color: #b45309;
    letter-spacing: 1.5px;
    margin-top: 1mm;
}

.english-content-center {
    text-align: center;
    max-width: 220mm;
    margin: 0 auto;
}

.english-granted-to {
    font-size: 8.5pt;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
}

.english-recipient-name {
    font-size: 18pt;
    font-weight: 900;
    color: #0f172a;
    font-family: serif;
    margin: 2mm 0;
}

.english-narrative-text {
    font-size: 9pt;
    line-height: 1.6;
    color: #334155;
    text-align: justify;
}

.english-date-location {
    text-align: right;
    font-size: 8.5pt;
    color: #475569;
    font-style: italic;
    font-family: serif;
}

@media print {
    @page {
        size: A4 landscape;
        margin: 0;
    }

    body,
    .print-page-wrapper {
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .no-print,
    .toolbar {
        display: none !important;
    }

    .stage {
        padding: 0 !important;
        margin: 0 !important;
    }

    .certificate-sheet {
        box-shadow: none !important;
        margin: 0 !important;
        page-break-after: always;
        break-after: page;
    }
}
</style>
