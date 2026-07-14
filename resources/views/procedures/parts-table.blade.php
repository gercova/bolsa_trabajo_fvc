@extends('layouts.app')

@section('title', 'Mesa de Partes Virtual — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO Meta Tags --}}
    <meta name="description" content="Mesa de Partes Virtual del Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero de Uchiza. Envía tus solicitudes, FUT y documentos de manera digital y segura.">
    <meta name="keywords" content="mesa de partes, mesa de partes virtual, tramites digitales, fut, mesa de partes uchiza, IESTP Francisco Vigo Caballero, Uchiza">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="Mesa de Partes Virtual — IESTP Francisco Vigo Caballero">
    <meta property="og:description" content="Envía tus solicitudes y documentos oficiales de manera digital a las oficinas administrativas del IESTP Francisco Vigo Caballero.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('enterprise/favicons/logo-iestpfvc.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentOffice",
      "name": "Mesa de Partes Virtual — IESTP Francisco Vigo Caballero",
      "description": "Servicio de mesa de partes virtual para el envío y registro de trámites académicos y administrativos de la institución.",
      "url": "{{ url()->current() }}",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Av. Ricardo Palma N° 1401",
        "addressLocality": "Uchiza",
        "addressCountry": "PE"
      },
      "openingHours": "Mo-Fr 07:30-13:30"
    }
    </script>

    <style>
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .office-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .office-card.active {
            border-color: #2563eb;
            background-color: rgba(37, 99, 235, 0.03);
            box-shadow: 0 4px 20px -2px rgba(37, 99, 235, 0.08);
        }

        .file-dropzone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .file-dropzone:hover {
            border-color: #2563eb;
            background-color: rgba(37, 99, 235, 0.01);
        }
    </style>
@endpush

@section('content')
    @php
        // Primary branding colors mapping to avoid dynamic interpolation problems
        $theme = [
            'glow' => 'bg-blue-500/20',
            'bar' => 'bg-blue-600',
            'badge' => 'bg-blue-50 text-blue-600 border-blue-100',
            'accent' => 'text-blue-600',
            'btn_submit' => 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:ring-blue-200'
        ];
    @endphp

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]">
        </div>
        <div class="absolute -top-32 -right-32 w-80 h-80 {{ $theme['glow'] }} rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                Mesa de Partes Virtual
            </h1>
            <p class="text-base md:text-lg lg:text-xl text-slate-300 leading-relaxed max-w-3xl mx-auto">
                Envía tus solicitudes y documentos oficiales de manera digital a las diferentes oficinas administrativas del
                instituto.
            </p>
        </div>
    </section>

    {{-- ===== MAIN GRID SECTION ===== --}}
    <section class="py-16 bg-slate-50/50" x-data="partsTableHandler()">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 max-w-7xl mx-auto">

                {{-- LEFT COLUMN: THE CONTACT FORM (Col Span 2) --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Interactive Step Card --}}
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                            <h2 class="text-2xl font-extrabold text-slate-900 flex items-center gap-3">
                                <span class="w-2 h-7 {{ $theme['bar'] }} rounded-full"></span>
                                Formulario de Envío Digital
                            </h2>
                            <span class="text-sm font-bold bg-slate-100 text-slate-600 px-4 py-1.5 rounded-full border border-slate-200">
                                Trámite Virtual
                            </span>
                        </div>

                        {{-- Form Content --}}
                        <form @submit.prevent="submitForm()" class="space-y-8">
                            {{-- Step 1: Select Office --}}
                            <div class="space-y-4">
                                <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                    1. Selecciona la Oficina de Destino
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="office in offices" :key="office.id">
                                        <div @click="selectedOffice = office"
                                            class="office-card p-5 border border-slate-200 rounded-2xl cursor-pointer flex gap-4 items-center hover:border-blue-400 hover:bg-slate-50/50"
                                            :class="selectedOffice.id === office.id ? 'active' : ''">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border shrink-0"
                                                :class="selectedOffice.id === office.id ?
                                                    'bg-blue-100/50 border-blue-200 text-blue-600 font-bold' :
                                                    'bg-slate-50 border-slate-150 text-slate-500'">
                                                <i class="bi text-xl" :class="office.icon"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-base font-bold text-slate-800" x-text="office.name"></h4>
                                                <p class="text-sm text-slate-500" x-text="office.officer"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Step 2: Applicant Data --}}
                            <div class="pt-6 border-t border-slate-100 space-y-4">
                                <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                    2. Datos del Solicitante
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Full name --}}
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Nombres y Apellidos</label>
                                        <input type="text" x-model="formData.names" required
                                            placeholder="Ingresa tus nombres completos"
                                            class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>

                                    {{-- Document Type and Number --}}
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="col-span-1 space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Doc.</label>
                                            <select x-model="formData.docType"
                                                class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-2 py-3 text-sm text-slate-800 focus:bg-white focus:outline-none">
                                                <option value="DNI">DNI</option>
                                                <option value="CE">C.E.</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2 space-y-1.5">
                                            <label class="block text-sm font-bold text-slate-700">Nro. de Documento</label>
                                            <input type="text" x-model="formData.docNum" required placeholder="12345678"
                                                class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Correo Electrónico</label>
                                        <input type="email" x-model="formData.email" required
                                            placeholder="usuario@ejemplo.com"
                                            class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>

                                    {{-- Phone --}}
                                    <div class="space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Número de Teléfono / Celular</label>
                                        <input type="tel" x-model="formData.phone" required placeholder="987654321"
                                            class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3: Request details --}}
                            <div class="pt-6 border-t border-slate-100 space-y-6">
                                <label class="block text-sm font-bold uppercase tracking-wider text-slate-500">
                                    3. Detalles de la Solicitud
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    {{-- Request Type --}}
                                    <div class="md:col-span-1 space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Tipo de Trámite</label>
                                        <select x-model="formData.type" required
                                            class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 focus:bg-white focus:outline-none">
                                            <option value="Solicitud General (FUT)">Solicitud General (FUT)</option>
                                            <option value="Certificado de Estudios">Certificado de Estudios</option>
                                            <option value="Justificación de Inasistencia">Justificación de Inasistencia</option>
                                            <option value="Constancia de Egresado">Constancia de Egresado</option>
                                            <option value="Reingreso Académico">Reingreso Académico</option>
                                            <option value="Otro">Otro Trámite</option>
                                        </select>
                                    </div>

                                    {{-- Subject --}}
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-sm font-bold text-slate-700">Asunto / Resumen</label>
                                        <input type="text" x-model="formData.subject" required
                                            placeholder="Ej. Solicito certificado de estudios del primer periodo"
                                            class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-bold text-slate-700">Descripción de la Solicitud / Mensaje</label>
                                    <textarea x-model="formData.message" rows="5" required placeholder="Escribe de manera detallada el motivo de tu solicitud..."
                                        class="w-full form-input bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none"></textarea>
                                </div>

                                {{-- Document Attachment --}}
                                <div class="space-y-1.5">
                                    <label class="block text-sm font-bold text-slate-700">Adjuntar Documentos (FUT o Solicitud en PDF / Word)</label>
                                    <div class="file-dropzone border-2 border-dashed border-slate-350 rounded-2xl p-8 text-center relative cursor-pointer hover:border-blue-500">
                                        <input type="file" @change="handleFileUpload($event)" accept=".pdf,.doc,.docx"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

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

                                    <template x-if="uploadedFile">
                                        <div class="mt-3 p-4 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-2xl"></i>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800" x-text="uploadedFile.name"></p>
                                                    <p class="text-xs text-slate-400" x-text="(uploadedFile.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                                </div>
                                            </div>
                                            <button type="button" @click="uploadedFile = null"
                                                class="text-slate-400 hover:text-rose-500 p-1.5 transition-colors">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-4 justify-end">
                                {{-- Email Redirect --}}
                                <button type="button" @click="redirectToEmail()"
                                    class="px-5 py-3.5 border border-slate-250 hover:border-slate-350 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all">
                                    <i class="bi bi-envelope-at text-slate-500 text-base"></i>
                                    Redirigir por Correo
                                </button>

                                {{-- WhatsApp Redirect --}}
                                <button type="button" @click="redirectToWhatsApp()"
                                    class="px-5 py-3.5 border border-emerald-250 hover:border-emerald-350 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/50 text-sm font-bold rounded-xl flex items-center justify-center gap-2 transition-all">
                                    <i class="bi bi-whatsapp text-emerald-600 text-base"></i>
                                    Redirigir por WhatsApp
                                </button>

                                {{-- Main Submit Button --}}
                                <button type="submit"
                                    class="px-6 py-3.5 {{ $theme['btn_submit'] }} text-white text-sm font-bold rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
                                    <i class="bi bi-send-fill text-base"></i>
                                    Enviar Trámite
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- RIGHT COLUMN: SIDEBAR DETAILS (Col Span 1) --}}
                <div class="space-y-8">

                    {{-- Selected Office Information --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-lg font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-building {{ $theme['accent'] }} text-xl"></i>
                            Oficina de Destino
                        </h3>

                        <div class="space-y-4">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-white border flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="bi text-xl" :class="selectedOffice.icon"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800" x-text="selectedOffice.name"></p>
                                    <p class="text-xs text-slate-400" x-text="selectedOffice.officer"></p>
                                </div>
                            </div>

                            <div class="space-y-3.5 pt-2 text-sm">
                                <div class="flex justify-between py-1.5 border-b border-slate-100">
                                    <span class="text-slate-450">Responsable</span>
                                    <span class="font-bold text-slate-700" x-text="selectedOffice.officer"></span>
                                </div>
                                <div class="flex justify-between py-1.5 border-b border-slate-100">
                                    <span class="text-slate-450">Correo Directo</span>
                                    <a :href="'mailto:' + selectedOffice.email"
                                        class="font-bold text-blue-600 hover:underline"
                                        x-text="selectedOffice.email"></a>
                                </div>
                                <div class="flex justify-between py-1.5 border-b border-slate-100">
                                    <span class="text-slate-455">Horario de Atención</span>
                                    <span class="font-bold text-slate-700 text-right" x-text="selectedOffice.hours"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- General Instructions --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-2.5">
                            <i class="bi bi-info-circle {{ $theme['accent'] }} text-xl"></i>
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

        {{-- SUCCESS MODAL --}}
        <div x-show="showSuccessModal" style="display: none;"
            class="fixed inset-0 z-55 flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-3xl p-8 max-w-lg w-full border border-slate-100 shadow-2xl text-center space-y-6"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto text-emerald-500">
                    <i class="bi bi-check-lg text-3xl"></i>
                </div>

                <div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Trámite Enviado Exitosamente</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Tu solicitud ha sido enviada a la <strong class="text-slate-700"
                            x-text="selectedOffice.name"></strong>. Te hemos enviado un correo de confirmación de recepción
                        del documento.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left space-y-2.5 text-sm text-slate-600">
                    <div class="flex justify-between"><span class="font-bold">Trámite:</span> <span
                            x-text="formData.type"></span></div>
                    <div class="flex justify-between"><span class="font-bold">Código de Registro:</span> <span
                            class="font-mono text-blue-600 font-bold" x-text="generationCode"></span></div>
                    <div class="flex justify-between"><span class="font-bold">Destinatario:</span> <span
                            x-text="selectedOffice.name"></span></div>
                </div>

                <button @click="showSuccessModal = false"
                    class="w-full py-3.5 bg-slate-950 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors">
                    Entendido
                </button>
            </div>
        </div>

    </section>

    @push('scripts')
        <script>
            function partsTableHandler() {
                return {
                    offices: [{
                            id: 'direccion',
                            name: 'Dirección General',
                            email: 'direccion@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lun a Vie: 7:30 am – 1:30 pm',
                            officer: 'Mg. Teodorico Ganoza Medina',
                            icon: 'bi-person-badge'
                        },
                        {
                            id: 'secretaria',
                            name: 'Secretaría Académica',
                            email: 'secretariageneral@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lun a Vie: 7:30 am – 1:30 pm',
                            officer: 'Lic. Luis Viviano Hilario Vega',
                            icon: 'bi-journal-check'
                        },
                        {
                            id: 'administracion',
                            name: 'Administración',
                            email: 'administracion@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lun a Vie: 7:30 am – 1:30 pm',
                            officer: 'Econ. Demetrio Díaz Guevara',
                            icon: 'bi-cash-coin'
                        },
                        {
                            id: 'unidad_academica',
                            name: 'Jefatura de Unidad Académica',
                            email: 'unidad.academica@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lun a Vie: 7:30 am – 1:30 pm',
                            officer: 'Ing. Manuel Adolfo Guerra Meléndez',
                            icon: 'bi-mortarboard'
                        }
                    ],
                    selectedOffice: null,
                    formData: {
                        names: '',
                        docType: 'DNI',
                        docNum: '',
                        email: '',
                        phone: '',
                        type: 'Solicitud General (FUT)',
                        subject: '',
                        message: ''
                    },
                    uploadedFile: null,
                    showSuccessModal: false,
                    generationCode: '',

                    init() {
                        this.selectedOffice = this.offices[0];
                    },

                    handleFileUpload(event) {
                        const file = event.target.files[0];
                        if (file) {
                            if (file.size > 10 * 1024 * 1024) {
                                alert("El archivo supera el tamaño máximo permitido (10MB).");
                                return;
                            }
                            this.uploadedFile = file;
                        }
                    },

                    submitForm() {
                        // Generate a unique registration code for the mockup
                        this.generationCode = 'FVC-' + Math.floor(100000 + Math.random() * 900000);
                        this.showSuccessModal = true;

                        // Clear form
                        setTimeout(() => {
                            this.formData.names = '';
                            this.formData.docNum = '';
                            this.formData.email = '';
                            this.formData.phone = '';
                            this.formData.subject = '';
                            this.formData.message = '';
                            this.uploadedFile = null;
                        }, 200);
                    },

                    redirectToEmail() {
                        if (!this.formData.names || !this.formData.subject || !this.formData.message) {
                            alert(
                                "Por favor completa los campos principales (Nombres, Asunto y Mensaje) para generar el correo.");
                            return;
                        }

                        const mailto = `mailto:${this.selectedOffice.email}?subject=${encodeURIComponent(this.formData.subject)}&body=${encodeURIComponent(
                            `Solicitud de Trámite Digital\n` +
                            `---------------------------------\n` +
                            `Oficina de Destino: ${this.selectedOffice.name}\n` +
                            `Remitente: ${this.formData.names} (${this.formData.docType}: ${this.formData.docNum})\n` +
                            `Teléfono: ${this.formData.phone}\n` +
                            `Correo: ${this.formData.email}\n` +
                            `Tipo de Trámite: ${this.formData.type}\n` +
                            `Mensaje:\n${this.formData.message}`
                        )}`;
                        window.location.href = mailto;
                    },

                    redirectToWhatsApp() {
                        if (!this.formData.names || !this.formData.subject || !this.formData.message) {
                            alert(
                                "Por favor completa los campos principales (Nombres, Asunto y Mensaje) para generar el mensaje de WhatsApp.");
                            return;
                        }

                        const waText = `*Mesa de Partes Virtual - IESTP FVC*\n\n` +
                            `*Oficina:* ${this.selectedOffice.name}\n` +
                            `*Remitente:* ${this.formData.names}\n` +
                            `*Documento:* ${this.formData.docType} ${this.formData.docNum}\n` +
                            `*Trámite:* ${this.formData.type}\n` +
                            `*Asunto:* ${this.formData.subject}\n\n` +
                            `*Mensaje:* ${this.formData.message}`;

                        const waUrl = `https://wa.me/51${this.selectedOffice.phone}?text=${encodeURIComponent(waText)}`;
                        window.open(waUrl, '_blank');
                    }
                }
            }
        </script>
    @endpush
@endsection
