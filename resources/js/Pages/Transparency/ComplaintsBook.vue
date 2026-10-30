<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);

const form = useForm({
    names: '',
    dni: '',
    email: '',
    subject: '',
    message: '',
    file_path: null,
});

const uploadedFile = ref(null);
const fileInput = ref(null);
const fileError = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    fileError.value = null;

    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            fileError.value = 'El archivo supera el tamaño máximo permitido (10MB).';
            if (fileInput.value) fileInput.value.value = '';
            uploadedFile.value = null;
            form.file_path = null;
            return;
        }

        form.file_path = file;
        uploadedFile.value = {
            name: file.name,
            size: (file.size / 1024 / 1024).toFixed(2) + ' MB',
        };
    }
};

const handleDrop = (event) => {
    event.preventDefault();
    const file = event.dataTransfer?.files?.[0];
    if (file) {
        const fakeEvent = { target: { files: [file] } };
        handleFileUpload(fakeEvent);
    }
};

const clearFileUpload = () => {
    uploadedFile.value = null;
    form.file_path = null;
    fileError.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const submitForm = () => {
    form.post('/transparencia/libro-de-reclamaciones', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            clearFileUpload();
        },
    });
};

const pageTitle = 'Libro de Reclamaciones — IESTP Francisco Vigo Caballero';
const pageDescription = 'Libro de Reclamaciones Virtual del IESTP Francisco Vigo Caballero de Uchiza. Registra tus reclamos o quejas en línea de manera oficial, de acuerdo con la Ley N° 29571.';
</script>

<template>
    <PublicLayout>
        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="pageDescription" />
            <meta name="keywords" content="libro de reclamaciones, libro de reclamaciones virtual, reclamos, quejas, libro de reclamaciones uchiza, IESTP Francisco Vigo Caballero, Uchiza" />
            <meta property="og:type" content="website" />
            <meta property="og:title" :content="pageTitle" />
            <meta property="og:description" :content="pageDescription" />
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" :content="pageTitle" />
            <meta name="twitter:description" :content="pageDescription" />
        </Head>

        <!-- HERO SECTION -->
        <section class="relative bg-slate-900 text-white py-16 lg:py-20 overflow-hidden border-b border-slate-800">
            <div class="h-1.5 w-full bg-blue-700 absolute top-0 left-0"></div>
            <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                    Libro de Reclamaciones Virtual
                </h1>
                <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                    Conforme a lo establecido en el Código de Protección y Defensa del Consumidor, nuestra institución cuenta con este canal digital para registrar tu reclamo o queja.
                </p>
            </div>
        </section>

        <!-- MAIN GRID SECTION -->
        <section class="py-16 bg-slate-50/50">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 max-w-7xl mx-auto">
                    <!-- LEFT COLUMN: CLAIMS FORM (Col Span 2) -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Alert Messages -->
                        <div
                            v-if="flashSuccess"
                            class="p-6 bg-emerald-50 border border-emerald-100 rounded-3xl flex gap-4 items-start shadow-sm"
                        >
                            <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md">
                                <i class="bi bi-check-lg text-2xl"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">¡Registro Exitoso!</h3>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ flashSuccess }}</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-extrabold text-slate-900 flex items-center gap-3">
                                    <span class="w-2 h-7 bg-blue-600 rounded-full"></span>
                                    Formulario de Reclamos y Quejas
                                </h2>
                                <span class="text-sm font-bold bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full border border-blue-100/50">
                                    Ley N° 29571
                                </span>
                            </div>

                            <form @submit.prevent="submitForm" class="space-y-8">
                                <!-- Section 1: Identificación del Reclamante -->
                                <div class="space-y-6">
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                        1. Identificación del Consumidor Reclamante
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Full Names -->
                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Nombres y Apellidos</label>
                                            <input
                                                v-model="form.names"
                                                type="text"
                                                required
                                                placeholder="Ingresa tu nombre completo"
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"
                                                :class="form.errors.names ? 'border-red-500' : 'border-slate-200'"
                                            />
                                            <p v-if="form.errors.names" class="mt-1 text-sm text-red-600">{{ form.errors.names }}</p>
                                        </div>

                                        <!-- DNI / Document -->
                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Documento de Identidad (DNI/CE)</label>
                                            <input
                                                v-model="form.dni"
                                                type="text"
                                                required
                                                placeholder="Número de documento"
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"
                                                :class="form.errors.dni ? 'border-red-500' : 'border-slate-200'"
                                            />
                                            <p v-if="form.errors.dni" class="mt-1 text-sm text-red-600">{{ form.errors.dni }}</p>
                                        </div>

                                        <!-- Email -->
                                        <div class="md:col-span-2 space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Correo Electrónico</label>
                                            <input
                                                v-model="form.email"
                                                type="email"
                                                required
                                                placeholder="correo@ejemplo.com"
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"
                                                :class="form.errors.email ? 'border-red-500' : 'border-slate-200'"
                                            />
                                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2: Detalles del Reclamo o Queja -->
                                <div class="pt-6 border-t border-slate-100 space-y-6">
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                        2. Detalles del Reclamo o Queja
                                    </label>

                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Subject -->
                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Tipo de Reclamación</label>
                                            <select
                                                v-model="form.subject"
                                                required
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"
                                                :class="form.errors.subject ? 'border-red-500' : 'border-slate-200'"
                                            >
                                                <option value="" disabled>Selecciona una opción</option>
                                                <option value="Reclamo">Reclamo (Disconformidad relacionada a los servicios)</option>
                                                <option value="Queja">Queja (Disconformidad no relacionada directamente a los servicios o malestar en la atención)</option>
                                                <option value="Otros">Otros</option>
                                            </select>
                                            <p v-if="form.errors.subject" class="mt-1 text-sm text-red-600">{{ form.errors.subject }}</p>
                                        </div>

                                        <!-- Message -->
                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Descripción del Reclamo o Queja</label>
                                            <textarea
                                                v-model="form.message"
                                                rows="5"
                                                required
                                                placeholder="Detalla tu disconformidad de la forma más clara posible..."
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"
                                                :class="form.errors.message ? 'border-red-500' : 'border-slate-200'"
                                            ></textarea>
                                            <p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p>
                                        </div>

                                        <!-- Attachment -->
                                        <div class="space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Adjuntar archivo sustentatorio (Opcional)</label>
                                            <div
                                                @dragover.prevent
                                                @drop="handleDrop"
                                                class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center relative cursor-pointer hover:border-blue-500 transition-all group"
                                            >
                                                <input
                                                    ref="fileInput"
                                                    type="file"
                                                    @change="handleFileUpload"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                />

                                                <div class="flex flex-col items-center justify-center gap-3">
                                                    <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-600 group-hover:border-blue-200 transition-colors shadow-sm">
                                                        <i class="bi bi-cloud-arrow-up text-2xl"></i>
                                                    </div>
                                                    <div class="text-sm text-slate-600">
                                                        <span class="font-bold text-blue-600 hover:text-blue-700">Haz clic para cargar</span> o arrastra tu archivo aquí
                                                    </div>
                                                    <p class="text-xs text-slate-400">PDF, JPG, JPEG, PNG (Máx. 10MB)</p>
                                                </div>
                                            </div>

                                            <div
                                                v-if="uploadedFile"
                                                class="mt-3 p-4 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <i class="bi bi-file-earmark-check text-blue-500 text-2xl"></i>
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">{{ uploadedFile.name }}</p>
                                                        <p class="text-xs text-slate-400">{{ uploadedFile.size }}</p>
                                                    </div>
                                                </div>
                                                <button
                                                    type="button"
                                                    @click="clearFileUpload"
                                                    class="text-slate-400 hover:text-red-500 p-1.5 transition-colors"
                                                    title="Eliminar archivo"
                                                >
                                                    <i class="bi bi-trash text-lg"></i>
                                                </button>
                                            </div>

                                            <p v-if="fileError" class="mt-1 text-sm text-red-600">{{ fileError }}</p>
                                            <p v-if="form.errors.file_path" class="mt-1 text-sm text-red-600">{{ form.errors.file_path }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-6 border-t border-slate-100 flex justify-end">
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all"
                                    >
                                        <i v-if="form.processing" class="bi bi-arrow-clockwise animate-spin text-base"></i>
                                        <i v-else class="bi bi-send-fill text-base"></i>
                                        {{ form.processing ? 'Enviando...' : 'Registrar Reclamación' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: SIDEBAR DETAILS -->
                    <div class="space-y-8">
                        <!-- Download official complaints book PDF -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                <i class="bi bi-file-earmark-pdf-fill text-blue-600 text-xl"></i>
                                Formato Físico (PDF)
                            </h3>

                            <div v-if="enterprise && enterprise.complaints_book_path" class="space-y-4">
                                <p class="text-sm text-slate-500 leading-relaxed">
                                    Si lo prefieres, puedes descargar la hoja de reclamación física oficial en formato PDF, llenarla y hacérnosla llegar.
                                </p>
                                <a
                                    :href="enterprise.complaints_book_path"
                                    target="_blank"
                                    class="inline-flex items-center justify-center gap-2 w-full py-3.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-red-500/20"
                                >
                                    <i class="bi bi-download text-base"></i> Descargar Libro de Reclamaciones
                                </a>
                            </div>
                            <p v-else class="text-sm text-slate-400 italic">
                                El formato en PDF no está disponible en este momento.
                            </p>
                        </div>

                        <!-- General Definitions -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                            <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                                <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                                Definiciones Útiles
                            </h3>

                            <div class="space-y-4 text-sm">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> Reclamo
                                    </h4>
                                    <p class="text-slate-500 leading-relaxed pl-3.5">
                                        Disconformidad relacionada a los servicios brindados por la institución académica.
                                    </p>
                                </div>

                                <div class="space-y-1">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> Queja
                                    </h4>
                                    <p class="text-slate-500 leading-relaxed pl-3.5">
                                        Disconformidad no relacionada con el servicio académico, sino con el malestar respecto a la atención al cliente o personal.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mesa de Partes Shortcut -->
                        <div class="bg-slate-900 rounded-3xl p-6 text-white space-y-5 border border-slate-800 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-blue-400">
                                <i class="bi bi-send-fill text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold">¿Tienes un trámite académico?</h4>
                                <p class="text-sm text-slate-400 mt-1 leading-relaxed">
                                    Para solicitudes de certificados, FUT, reingreso u otros, utiliza nuestra Mesa de Partes Virtual.
                                </p>
                            </div>
                            <Link
                                href="/tramites/mesa-de-partes"
                                class="inline-flex items-center justify-center gap-2 w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-blue-500/20"
                            >
                                Ir a Mesa de Partes <i class="bi bi-arrow-right"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
