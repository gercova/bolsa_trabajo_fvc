<script setup>
import { ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const authUser = page.props.auth?.user;

const receptionEmail = props.enterprise?.email || 'secretariageneral@franciscovigocaballero.edu.pe';
const rawPhone = props.enterprise?.phone_number_1
    ? props.enterprise.phone_number_1.replace(/[^0-9]/g, '')
    : '942131215';
const displayPhone = props.enterprise?.phone_number_1 || '942 131 215';

const formData = ref({
    names: authUser?.names || '',
    docType: 'DNI',
    docNum: authUser?.dni || '',
    email: authUser?.email || '',
    phone: authUser?.phone || '',
    type: 'Solicitud General (FUT)',
    subject: '',
    message: '',
});

const uploadedFile = ref(null);
const showSuccessModal = ref(false);
const generationCode = ref('');

const handleFileUpload = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert('El archivo supera el tamaño máximo permitido (10MB).');
            return;
        }
        uploadedFile.value = file;
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    const file = event.dataTransfer?.files?.[0];
    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert('El archivo supera el tamaño máximo permitido (10MB).');
            return;
        }
        uploadedFile.value = file;
    }
};

const submitForm = () => {
    generationCode.value = 'FVC-' + Math.floor(100000 + Math.random() * 900000);
    showSuccessModal.value = true;

    setTimeout(() => {
        formData.value = {
            names: '',
            docType: 'DNI',
            docNum: '',
            email: '',
            phone: '',
            type: 'Solicitud General (FUT)',
            subject: '',
            message: '',
        };
        uploadedFile.value = null;
    }, 200);
};

const redirectToEmail = () => {
    if (!formData.value.names || !formData.value.subject || !formData.value.message) {
        alert('Por favor completa los campos principales (Nombres, Asunto y Mensaje) para generar el correo.');
        return;
    }

    const mailto = `mailto:${receptionEmail}?subject=${encodeURIComponent(formData.value.subject)}&body=${encodeURIComponent(
        `Mesa de Partes Digital — Recepción Documentaria\n` +
        `--------------------------------------------------\n` +
        `Destino: Mesa de Partes / Recepción Documentaria\n` +
        `Remitente: ${formData.value.names} (${formData.value.docType}: ${formData.value.docNum})\n` +
        `Teléfono: ${formData.value.phone}\n` +
        `Correo: ${formData.value.email}\n` +
        `Tipo de Trámite: ${formData.value.type}\n` +
        `Asunto: ${formData.value.subject}\n\n` +
        `Mensaje / Fundamentación:\n${formData.value.message}`
    )}`;
    window.location.href = mailto;
};

const redirectToWhatsApp = () => {
    if (!formData.value.names || !formData.value.subject || !formData.value.message) {
        alert('Por favor completa los campos principales (Nombres, Asunto y Mensaje) para generar el mensaje de WhatsApp.');
        return;
    }

    const waText = `*Mesa de Partes Virtual — Recepción Documentaria*\n\n` +
        `*Destino:* Mesa de Partes / Secretaría General\n` +
        `*Remitente:* ${formData.value.names}\n` +
        `*Documento:* ${formData.value.docType} ${formData.value.docNum}\n` +
        `*Teléfono:* ${formData.value.phone}\n` +
        `*Correo:* ${formData.value.email}\n` +
        `*Tipo de Trámite:* ${formData.value.type}\n` +
        `*Asunto:* ${formData.value.subject}\n\n` +
        `*Mensaje:*\n${formData.value.message}`;

    const waUrl = `https://wa.me/51${rawPhone}?text=${encodeURIComponent(waText)}`;
    window.open(waUrl, '_blank');
};

const jsonLd = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'GovernmentOffice',
    'name': 'Mesa de Partes Virtual — IESTP Francisco Vigo Caballero',
    'description': 'Servicio de mesa de partes virtual y recepción documentaria para el envío y registro de trámites académicos y administrativos.',
    'url': typeof window !== 'undefined' ? window.location.href : '',
    'address': {
        '@type': 'PostalAddress',
        'streetAddress': props.enterprise?.address || 'Av. Ricardo Palma N° 1401',
        'addressLocality': props.enterprise?.city || 'Uchiza',
        'addressCountry': 'PE',
    },
    'openingHours': 'Mo-Fr 07:30-13:30',
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Mesa de Partes Virtual — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Mesa de Partes Virtual del Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero de Uchiza. Envía tus solicitudes, FUT y documentos a la recepción documentaria de manera digital y segura." />
            <meta name="keywords" content="mesa de partes, mesa de partes virtual, recepcion documentaria, tramites digitales, fut, mesa de partes uchiza, IESTP Francisco Vigo Caballero, Uchiza" />
            <meta name="robots" content="index, follow" />
            <meta property="og:type" content="website" />
            <meta property="og:title" content="Mesa de Partes Virtual — IESTP Francisco Vigo Caballero" />
            <meta property="og:description" content="Envía tus solicitudes y documentos oficiales de manera digital a la oficina de recepción documentaria del IESTP Francisco Vigo Caballero." />
            <meta property="og:image" :content="enterprise?.logo_path ? `/${enterprise.logo_path}` : '/img/logo.png'" />
            <component :is="'script'" type="application/ld+json" v-text="jsonLd" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white py-16 lg:py-20 overflow-hidden border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
                <span class="inline-flex items-center gap-1.5 py-1 px-4 rounded-full text-xs font-extrabold bg-blue-500/20 text-sky-300 border border-blue-400/30 uppercase tracking-widest mb-4">
                    <i class="bi bi-inbox-fill text-sky-400"></i>
                    Recepción Documentaria Institucional
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                    Mesa de Partes Virtual
                </h1>
                <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto font-medium">
                    Envía tus solicitudes, expedientes y documentos oficiales de manera digital y segura a la oficina de recepción documentaria del instituto.
                </p>
            </div>
        </section>

        <!-- MAIN GRID SECTION -->
        <section class="py-16 bg-slate-50/50">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 max-w-7xl mx-auto">

                    <!-- LEFT COLUMN: THE CONTACT FORM -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm">
                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 flex items-center gap-3">
                                    <span class="w-2 h-7 bg-blue-600 rounded-full"></span>
                                    Formulario de Envío Digital
                                </h2>
                            </div>

                            <form @submit.prevent="submitForm" class="space-y-8">
                                <!-- Step 1: Applicant Data -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                        1. Datos del Solicitante
                                    </label>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Nombres y Apellidos <span class="text-rose-500">*</span></label>
                                            <input
                                                type="text"
                                                v-model="formData.names"
                                                required
                                                placeholder="Ingresa tus nombres completos"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                            />
                                        </div>

                                        <div class="grid grid-cols-3 gap-3">
                                            <div class="col-span-1 space-y-1.5">
                                                <label class="block text-sm font-bold text-slate-700">Doc.</label>
                                                <select
                                                    v-model="formData.docType"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2 py-3 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                                >
                                                    <option value="DNI">DNI</option>
                                                    <option value="CE">C.E.</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2 space-y-1.5">
                                                <label class="block text-sm font-bold text-slate-700">Nro. de Documento <span class="text-rose-500">*</span></label>
                                                <input
                                                    type="text"
                                                    v-model="formData.docNum"
                                                    required
                                                    placeholder="12345678"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                                />
                                            </div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Correo Electrónico <span class="text-rose-500">*</span></label>
                                            <input
                                                type="email"
                                                v-model="formData.email"
                                                required
                                                placeholder="usuario@ejemplo.com"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                            />
                                        </div>

                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Número de Teléfono / Celular <span class="text-rose-500">*</span></label>
                                            <input
                                                type="tel"
                                                v-model="formData.phone"
                                                required
                                                placeholder="987654321"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Request details -->
                                <div class="pt-6 border-t border-slate-100 space-y-6">
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                        2. Detalles de la Solicitud
                                    </label>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="md:col-span-1 space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Tipo de Trámite <span class="text-rose-500">*</span></label>
                                            <select
                                                v-model="formData.type"
                                                required
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                            >
                                                <option value="Solicitud General (FUT)">Solicitud General (FUT)</option>
                                                <option value="Certificado de Estudios">Certificado de Estudios</option>
                                                <option value="Justificación de Inasistencia">Justificación de Inasistencia</option>
                                                <option value="Constancia de Egresado">Constancia de Egresado</option>
                                                <option value="Reingreso Académico">Reingreso Académico</option>
                                                <option value="Otro">Otro Trámite</option>
                                            </select>
                                        </div>

                                        <div class="md:col-span-2 space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Asunto / Resumen <span class="text-rose-500">*</span></label>
                                            <input
                                                type="text"
                                                v-model="formData.subject"
                                                required
                                                placeholder="Ej. Solicito certificado de estudios del primer periodo"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                            />
                                        </div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Descripción de la Solicitud / Mensaje <span class="text-rose-500">*</span></label>
                                        <textarea
                                            v-model="formData.message"
                                            rows="5"
                                            required
                                            placeholder="Escribe de manera detallada el motivo de tu solicitud o trámite..."
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 transition"
                                        ></textarea>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Adjuntar Documentos (FUT o Solicitud en PDF / Word)</label>
                                        <div
                                            @dragover.prevent
                                            @drop="handleDrop"
                                            class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center relative cursor-pointer hover:border-blue-500 transition-colors"
                                        >
                                            <input
                                                type="file"
                                                @change="handleFileUpload"
                                                accept=".pdf,.doc,.docx"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            />
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                                    <i class="bi bi-cloud-arrow-up text-2xl"></i>
                                                </div>
                                                <div class="text-sm text-slate-600">
                                                    <span class="font-bold text-blue-600 hover:text-blue-700">Haz clic para cargar</span> o arrastra tu archivo aquí
                                                </div>
                                                <p class="text-xs text-slate-400">Formatos permitidos: PDF o Word (Máx. 10MB)</p>
                                            </div>
                                        </div>

                                        <div v-if="uploadedFile" class="mt-3 p-4 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-2xl"></i>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">{{ uploadedFile.name }}</p>
                                                    <p class="text-xs text-slate-400">{{ (uploadedFile.size / 1024 / 1024).toFixed(2) }} MB</p>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                @click="uploadedFile = null"
                                                class="text-slate-400 hover:text-rose-500 p-1.5 transition-colors"
                                            >
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action buttons -->
                                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-4 justify-end">
                                    <button
                                        type="button"
                                        @click="redirectToEmail"
                                        class="px-5 py-3.5 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all cursor-pointer"
                                    >
                                        <i class="bi bi-envelope-at text-blue-600 text-base"></i>
                                        Redirigir por Correo
                                    </button>

                                    <button
                                        type="button"
                                        @click="redirectToWhatsApp"
                                        class="px-5 py-3.5 border border-emerald-200 hover:border-emerald-300 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/50 text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all cursor-pointer"
                                    >
                                        <i class="bi bi-whatsapp text-emerald-600 text-base"></i>
                                        Redirigir por WhatsApp
                                    </button>

                                    <button
                                        type="submit"
                                        class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 shadow-md transition-all cursor-pointer"
                                    >
                                        <i class="bi bi-send-fill text-base"></i>
                                        Enviar Trámite
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: SIDEBAR DETAILS -->
                    <div class="space-y-8">
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2.5">
                                    <i class="bi bi-inbox-fill text-blue-600 text-xl"></i>
                                    Recepción Documentaria
                                </h3>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 bg-blue-50/60 rounded-2xl border border-blue-100 flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-sm shrink-0">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-900">Mesa de Partes Digital</p>
                                        <p class="text-xs text-slate-500 font-medium">Recepción y Trámite Documentario</p>
                                    </div>
                                </div>

                                <div class="space-y-3 pt-1 text-sm">
                                    <div class="flex justify-between py-2 border-b border-slate-100">
                                        <span class="text-slate-500 text-xs font-semibold uppercase">Oficina Central</span>
                                        <span class="font-bold text-slate-800 text-right text-xs">Secretaría General</span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-2 border-b border-slate-100">
                                        <span class="text-slate-500 text-xs font-semibold uppercase">Correo de Recepción</span>
                                        <a
                                            :href="`mailto:${receptionEmail}`"
                                            class="font-bold text-blue-600 hover:underline text-xs break-all flex items-center gap-1.5"
                                        >
                                            <i class="bi bi-envelope-fill text-blue-500"></i>
                                            {{ receptionEmail }}
                                        </a>
                                    </div>

                                    <div class="flex justify-between py-2 border-b border-slate-100">
                                        <span class="text-slate-500 text-xs font-semibold uppercase">Teléfono / WhatsApp</span>
                                        <a :href="`tel:${rawPhone}`" class="font-bold text-slate-800 text-xs hover:text-blue-600 transition">
                                            +51 {{ displayPhone }}
                                        </a>
                                    </div>

                                    <div class="flex justify-between py-2 border-b border-slate-100">
                                        <span class="text-slate-500 text-xs font-semibold uppercase">Horario de Atención</span>
                                        <span class="font-bold text-slate-800 text-xs text-right">Lun a Vie: 7:30 am – 1:30 pm</span>
                                    </div>

                                    <div class="flex flex-col gap-1 py-2">
                                        <span class="text-slate-500 text-xs font-semibold uppercase">Sede Institucional</span>
                                        <span class="font-medium text-slate-700 text-xs leading-relaxed">
                                            {{ enterprise?.address || 'Av. Ricardo Palma N° 1401' }}, {{ enterprise?.city || 'Uchiza' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- General Instructions -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-2.5">
                                <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                                Pautas Generales
                            </h3>

                            <ul class="space-y-4 text-sm text-slate-600">
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-1-circle-fill text-blue-600 text-base mt-0.5 shrink-0"></i>
                                    <span>Los trámites virtuales ingresados fuera de horario se registrarán en el siguiente día hábil.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-2-circle-fill text-blue-600 text-base mt-0.5 shrink-0"></i>
                                    <span>Asegúrate de que tus documentos adjuntos contengan tu firma y huella digital (si aplica).</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-3-circle-fill text-blue-600 text-base mt-0.5 shrink-0"></i>
                                    <span>El tiempo aproximado de respuesta institucional es de 3 a 5 días hábiles según la carga administrativa.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SUCCESS MODAL -->
            <div
                v-if="showSuccessModal"
                class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div class="bg-white rounded-3xl p-8 max-w-lg w-full border border-slate-100 shadow-2xl text-center space-y-6">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto text-emerald-500">
                        <i class="bi bi-check-lg text-3xl"></i>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Trámite Registrado Exitosamente</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Tu solicitud ha sido remitida a la <strong class="text-slate-700">Oficina de Mesa de Partes y Recepción Documentaria</strong> para su respectivo trámite y seguimiento institucional.
                        </p>
                    </div>

                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left space-y-2.5 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span class="font-bold">Trámite:</span>
                            <span>{{ formData.type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold">Código de Registro:</span>
                            <span class="font-mono text-blue-600 font-bold">{{ generationCode }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold">Destino:</span>
                            <span class="text-slate-800 font-semibold">Mesa de Partes — Recepción Documentaria</span>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="showSuccessModal = false"
                        class="w-full py-3.5 bg-slate-950 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors cursor-pointer"
                    >
                        Entendido
                    </button>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
