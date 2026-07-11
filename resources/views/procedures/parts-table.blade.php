@extends('layouts.app')
@section('title', 'Mesa de Partes Virtual — IESTP Francisco Vigo Caballero')
@push('styles')
    <style>
        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.1);
        }

        .office-card {
            transition: all 0.3s ease;
        }

        .office-card.active {
            border-color: #d97706;
            background-color: rgba(217, 119, 6, 0.02);
            box-shadow: 0 4px 20px -2px rgba(217, 119, 6, 0.08);
        }

        .file-dropzone {
            transition: all 0.3s ease;
        }

        .file-dropzone:hover {
            border-color: #d97706;
            background-color: rgba(217, 119, 6, 0.01);
        }
    </style>
@endpush

@section('content')
    @php
        $colorName = str_replace('bg-', '', $enterprise->color ?? 'blue-500');
    @endphp

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]">
        </div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-{{ $colorName }}/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight leading-tight">
                Mesa de Partes Virtual
            </h1>
            <p class="text-base md:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto">
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
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-3">
                                <span class="w-2 h-7 bg-{{ $colorName }} rounded-full"></span>
                                Formulario de Envío Digital
                            </h2>
                            <span
                                class="text-xs font-bold bg-slate-50 text-slate-500 px-3 py-1 rounded-full border border-slate-100">
                                Trámite Virtual
                            </span>
                        </div>

                        {{-- Form Content --}}
                        <form @submit.prevent="submitForm()" class="space-y-6">
                            {{-- Step 1: Select Office --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                    1. Selecciona la Oficina de Destino
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="office in offices" :key="office.id">
                                        <div @click="selectedOffice = office"
                                            class="office-card p-4 border border-slate-200 rounded-2xl cursor-pointer flex gap-4 items-center hover:border-{{ $colorName }}/50 hover:bg-slate-50/50"
                                            :class="selectedOffice.id === office.id ? 'active' : ''">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center border shrink-0"
                                                :class="selectedOffice.id === office.id ?
                                                    'bg-{{ $colorName }}/10 border-{{ $colorName }}/20 text-{{ $colorName }}/80 font-bold' :
                                                    'bg-slate-50 border-slate-100 text-slate-500'">
                                                <i class="bi" :class="office.icon + ' text-lg'"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-slate-800" x-text="office.name"></h4>
                                                <p class="text-[11px] text-slate-400" x-text="office.officer"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Step 2: Applicant Data --}}
                            <div class="pt-4 border-t border-slate-100">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">
                                    2. Datos del Solicitante
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Full name --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nombres y
                                            Apellidos</label>
                                        <input type="text" x-model="formData.names" required
                                            placeholder="Ingresa tus nombres completos"
                                            class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>

                                    {{-- Document Type and Number --}}
                                    <div class="grid grid-cols-3 gap-3">
                                        <div class="col-span-1">
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Documento</label>
                                            <select x-model="formData.docType"
                                                class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-2 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none">
                                                <option value="DNI">DNI</option>
                                                <option value="CE">C.E.</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nro. de
                                                Documento</label>
                                            <input type="text" x-model="formData.docNum" required placeholder="12345678"
                                                class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Correo
                                            Electrónico</label>
                                        <input type="email" x-model="formData.email" required
                                            placeholder="usuario@ejemplo.com"
                                            class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>

                                    {{-- Phone --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Número Telefónico /
                                            Celular</label>
                                        <input type="tel" x-model="formData.phone" required placeholder="987654321"
                                            class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3: Request details --}}
                            <div class="pt-4 border-t border-slate-100">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">
                                    3. Detalles de la Solicitud
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    {{-- Request Type --}}
                                    <div class="md:col-span-1">
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipo de Trámite</label>
                                        <select x-model="formData.type" required
                                            class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none">
                                            <option value="Solicitud General (FUT)">Solicitud General (FUT)</option>
                                            <option value="Certificado de Estudios">Certificado de Estudios</option>
                                            <option value="Justificación de Inasistencia">Justificación de Inasistencia
                                            </option>
                                            <option value="Constancia de Egresado">Constancia de Egresado</option>
                                            <option value="Reingreso Académico">Reingreso Académico</option>
                                            <option value="Otro">Otro Trámite</option>
                                        </select>
                                    </div>

                                    {{-- Subject --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Asunto /
                                            Resumen</label>
                                        <input type="text" x-model="formData.subject" required
                                            placeholder="Ej. Solicito certificado de estudios del primer periodo"
                                            class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Descripción de la Solicitud
                                        / Mensaje</label>
                                    <textarea x-model="formData.message" rows="4" required placeholder="Escribe los detalles de tu solicitud..."
                                        class="w-full form-input bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none"></textarea>
                                </div>

                                {{-- Document Attachment --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Adjuntar Documentos (FUT o
                                        Solicitud en PDF)</label>
                                    <div
                                        class="file-dropzone border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center relative cursor-pointer">
                                        <input type="file" @change="handleFileUpload($event)" accept=".pdf,.doc,.docx"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <div
                                                class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                                                <i class="bi bi-cloud-arrow-up text-xl"></i>
                                            </div>
                                            <div class="text-xs">
                                                <span class="font-bold text-blue-600 hover:text-blue-700">Haz clic para
                                                    cargar</span> o arrastra tu archivo aquí
                                            </div>
                                            <p class="text-[10px] text-slate-400">Solo archivos PDF o Word (Máx. 10MB)</p>
                                        </div>
                                    </div>

                                    <template x-if="uploadedFile">
                                        <div
                                            class="mt-3 p-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between">
                                            <div class="flex items-center gap-2.5">
                                                <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-lg"></i>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800"
                                                        x-text="uploadedFile.name"></p>
                                                    <p class="text-[10px] text-slate-400"
                                                        x-text="(uploadedFile.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                                </div>
                                            </div>
                                            <button type="button" @click="uploadedFile = null"
                                                class="text-slate-400 hover:text-rose-500 p-1">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-end">
                                {{-- Email Redirect --}}
                                <button type="button" @click="redirectToEmail()"
                                    class="px-5 py-3 border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition-all">
                                    <i class="bi bi-envelope-at text-slate-500 text-sm"></i>
                                    Redirigir por Correo
                                </button>

                                {{-- WhatsApp Redirect --}}
                                <button type="button" @click="redirectToWhatsApp()"
                                    class="px-5 py-3 border border-emerald-200 hover:border-emerald-300 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/50 text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition-all">
                                    <i class="bi bi-whatsapp text-emerald-600 text-sm"></i>
                                    Redirigir por WhatsApp
                                </button>

                                {{-- Main Submit Button --}}
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-sm shadow-blue-500/10 transition-all">
                                    <i class="bi bi-send"></i>
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
                        <h3
                            class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-building text-blue-500 text-lg"></i>
                            Oficina de Destino Seleccionada
                        </h3>

                        <div class="space-y-4">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white border flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="bi" :class="selectedOffice.icon + ' text-lg'"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800" x-text="selectedOffice.name"></p>
                                    <p class="text-[10px] text-slate-400" x-text="selectedOffice.officer"></p>
                                </div>
                            </div>

                            <div class="space-y-3.5 pt-2 text-xs">
                                <div class="flex justify-between py-1 border-b border-slate-50">
                                    <span class="text-slate-400">Responsable</span>
                                    <span class="font-bold text-slate-700" x-text="selectedOffice.officer"></span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-50">
                                    <span class="text-slate-400">Correo Directo</span>
                                    <a :href="'mailto:' + selectedOffice.email"
                                        class="font-bold text-blue-600 hover:underline"
                                        x-text="selectedOffice.email"></a>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-50">
                                    <span class="text-slate-400">Horario de Oficina</span>
                                    <span class="font-bold text-slate-700 text-right"
                                        x-text="selectedOffice.hours"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- General Instructions --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2.5">
                            <i class="bi bi-info-circle text-blue-500 text-lg"></i>
                            Pautas Generales
                        </h3>

                        <ul class="space-y-3.5 text-xs text-slate-600">
                            <li class="flex items-start gap-2.5">
                                <i class="bi bi-1-circle-fill text-blue-500 text-sm mt-0.5 shrink-0"></i>
                                <span>Los trámites virtuales ingresados fuera de horario se registrarán en el siguiente día
                                    hábil.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="bi bi-2-circle-fill text-blue-500 text-sm mt-0.5 shrink-0"></i>
                                <span>Asegúrate de que tus documentos adjuntos contengan tu firma y huella digital (si
                                    aplica).</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="bi bi-3-circle-fill text-blue-500 text-sm mt-0.5 shrink-0"></i>
                                <span>El tiempo aproximado de respuesta institucional es de 3 a 5 días hábiles según la
                                    carga administrativa.</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>

        {{-- SUCCESS MODAL --}}
        <div x-show="showSuccessModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-3xl p-8 max-w-md w-full border border-slate-100 shadow-2xl text-center space-y-6"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                <div
                    class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto text-emerald-500">
                    <i class="bi bi-check-lg text-3xl"></i>
                </div>

                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Trámite Enviado Exitosamente</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Tu solicitud ha sido enviada a la <strong class="text-slate-700"
                            x-text="selectedOffice.name"></strong>. Te hemos enviado un correo de confirmación de recepción
                        del documento.
                    </p>
                </div>

                <div
                    class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left space-y-2 text-[11px] text-slate-600">
                    <div class="flex justify-between"><span class="font-bold">Trámite:</span> <span
                            x-text="formData.type"></span></div>
                    <div class="flex justify-between"><span class="font-bold">Código de Registro:</span> <span
                            class="font-mono text-blue-600 font-bold" x-text="generationCode"></span></div>
                    <div class="flex justify-between"><span class="font-bold">Destinatario:</span> <span
                            x-text="selectedOffice.name"></span></div>
                </div>

                <button @click="showSuccessModal = false"
                    class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors">
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
                            hours: 'Lunes a Viernes: 7:30 am – 1:30 pm',
                            officer: 'Mg. Teodorico Ganoza Medina',
                            icon: 'bi-person-badge'
                        },
                        {
                            id: 'secretaria',
                            name: 'Secretaría Académica',
                            email: 'secretariageneral@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lunes a Viernes: 7:30 am – 1:30 pm',
                            officer: 'Lic. Luis Viviano Hilario Vega',
                            icon: 'bi-journal-check'
                        },
                        {
                            id: 'administracion',
                            name: 'Administración',
                            email: 'administracion@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lunes a Viernes: 7:30 am – 1:30 pm',
                            officer: 'Econ. Demetrio Díaz Guevara',
                            icon: 'bi-cash-coin'
                        },
                        {
                            id: 'unidad_academica',
                            name: 'Jefatura de Unidad Académica',
                            email: 'unidad.academica@franciscovigocaballero.edu.pe',
                            phone: '942131215',
                            hours: 'Lunes a Viernes: 7:30 am – 1:30 pm',
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
