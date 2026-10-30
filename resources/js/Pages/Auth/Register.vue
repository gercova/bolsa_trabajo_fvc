<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    matchedUser: {
        type: Object,
        default: null,
    },
    initialDni: {
        type: String,
        default: '',
    },
});

const showPass = ref(false);
const isLoading = ref(false);
const isVerified = ref(Boolean(props.matchedUser?.names || props.initialDni));
const alreadyRegistered = ref(false);
const alreadyRegisteredMessage = ref('');
const errorMessage = ref('');
const userRole = ref(props.matchedUser?.role || '');
const cvFileName = ref('');

const form = useForm({
    dni: props.initialDni || '',
    names: props.matchedUser?.names || '',
    birthdate: props.matchedUser?.birthdate
        ? (typeof props.matchedUser.birthdate === 'string' ? props.matchedUser.birthdate.substring(0, 10) : '')
        : '',
    phone: props.matchedUser?.phone || '',
    personal_email: props.matchedUser?.personal_email || '',
    cv_file: null,
    password: '',
    password_confirmation: '',
});

const onDniInput = () => {
    form.dni = form.dni.replace(/[^0-9]/g, '').slice(0, 8);
    errorMessage.value = '';
    alreadyRegistered.value = false;

    if (form.dni.length !== 8) {
        isVerified.value = false;
    } else {
        verifyDni();
    }
};

const verifyDni = async () => {
    if (!form.dni || form.dni.length !== 8) {
        errorMessage.value = 'El DNI debe contener exactamente 8 dígitos.';
        isVerified.value = false;
        return;
    }

    isLoading.value = true;
    errorMessage.value = '';
    alreadyRegistered.value = false;

    try {
        const response = await fetch(`${route('register.verify-dni')}?dni=${encodeURIComponent(form.dni)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const data = await response.json();

        if (response.ok && data.success) {
            if (data.already_registered) {
                alreadyRegistered.value = true;
                alreadyRegisteredMessage.value = data.message;
                isVerified.value = false;
            } else {
                isVerified.value = true;
                form.names = data.user.names || form.names;
                form.birthdate = data.user.birthdate || form.birthdate;
                form.phone = data.user.phone || form.phone;
                userRole.value = data.user.role || '';
                if (!form.personal_email && data.user.personal_email) {
                    form.personal_email = data.user.personal_email;
                }
            }
        } else {
            isVerified.value = false;
            errorMessage.value = data.message || 'El DNI ingresado no se encuentra en el padrón institucional.';
        }
    } catch {
        isVerified.value = false;
        errorMessage.value = 'Ocurrió un error al verificar el DNI. Inténtelo nuevamente.';
    } finally {
        isLoading.value = false;
    }
};

const onCvChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.cv_file = file;
        cvFileName.value = file.name;
    } else {
        form.cv_file = null;
        cvFileName.value = '';
    }
};

const submit = () => {
    if (!isVerified.value) {
        errorMessage.value = 'Debe verificar su número de DNI en el padrón institucional antes de continuar.';
        return;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => {
    if (form.dni.length === 8 && !isVerified.value) {
        verifyDni();
    }
});
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Registro Institucional — IESTP Francisco Vigo Caballero</title>
            <meta name="description" content="Portal oficial de activación y registro institucional para la comunidad del IESTP Francisco Vigo Caballero." />
        </Head>

        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#F4F1EA]">
            <div class="w-full max-w-xl">
                <div class="bg-white rounded-3xl shadow-2xl shadow-[rgba(15,23,42,.08)] p-6 sm:p-10 border border-slate-200/80 transition-all duration-300">
                    <!-- Encabezado con Logo Institucional -->
                    <div class="flex flex-col items-center text-center mb-6">
                        <div v-if="enterprise.logo_path || enterprise.logo_base64"
                            class="w-20 h-20 rounded-2xl flex items-center justify-center mb-3">
                            <img :src="enterprise.logo_base64 || enterprise.logo_path"
                                :alt="`Logo ${enterprise.company_name || 'IESTP FVC'}`"
                                class="w-20 h-20 object-contain" />
                        </div>
                        <div v-else class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 bg-slate-900 text-amber-400">
                            <i class="bi bi-mortarboard-fill text-3xl"></i>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-display">
                            Registro de Cuenta Institucional
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md">
                            Acceso exclusivo para estudiantes, egresados, plana docente y personal del <strong class="text-slate-700">IESTP Francisco Vigo Caballero</strong>.
                        </p>
                        <p class="text-xs text-slate-500 mt-2">
                            ¿Ya completaste tu registro?
                            <Link :href="route('login')" class="font-bold underline decoration-1 underline-offset-2 transition-colors hover:text-purple-700 text-purple-600">
                                Inicia sesión aquí
                            </Link>
                        </p>
                    </div>

                    <!-- Banner informativo del padrón -->
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-start gap-3 text-xs sm:text-sm text-amber-900">
                        <i class="bi bi-shield-check text-amber-600 text-lg shrink-0 mt-0.5"></i>
                        <div class="leading-relaxed">
                            <p class="font-bold text-amber-950">Validación de Padrón Institucional</p>
                            <p class="text-amber-800/90 text-xs mt-0.5">
                                Para activar tu cuenta, tus datos y número de DNI deben constar en la base de datos de la institución. Ingresa tu DNI para verificar tu identidad y completar tu correo personal.
                            </p>
                        </div>
                    </div>

                    <!-- Formulario Principal -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Paso 1: Ingreso y Validación del DNI -->
                        <div class="bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200">
                            <label for="dni" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                1. Documento Nacional de Identidad (DNI) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col sm:flex-row gap-2.5">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-person-vcard text-lg text-slate-400"></i>
                                    </div>
                                    <input id="dni" v-model="form.dni" type="text" maxlength="8" required autofocus
                                        autocomplete="off" @input="onDniInput" @keydown.enter.prevent="verifyDni"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-mono tracking-wider font-semibold transition-all duration-200 outline-none bg-white"
                                        :class="form.errors.dni ? 'border-red-300 ring-2 ring-red-100 bg-red-50/20' : 'border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600'"
                                        placeholder="8 dígitos (Ej: 71234567)" />
                                </div>
                                <button type="button" @click="verifyDni" :disabled="isLoading || form.dni.length !== 8"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-white transition-all duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed bg-slate-900 hover:bg-slate-800">
                                    <div v-if="isLoading" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        <span>Buscando...</span>
                                    </div>
                                    <div v-else class="flex items-center gap-1.5">
                                        <i class="bi bi-search"></i>
                                        <span>Verificar</span>
                                    </div>
                                </button>
                            </div>

                            <!-- Error del DNI en backend -->
                            <div v-if="form.errors.dni"
                                class="mt-2.5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-start gap-2">
                                <i class="bi bi-x-circle-fill text-red-500 mt-0.5 shrink-0"></i>
                                <span>{{ form.errors.dni }}</span>
                            </div>

                            <!-- Mensajes dinámicos de error en verificación -->
                            <div v-if="errorMessage"
                                class="mt-2.5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-start gap-2.5">
                                <i class="bi bi-exclamation-triangle-fill text-red-500 text-base mt-0.5 shrink-0"></i>
                                <div class="leading-relaxed">
                                    <p class="font-bold text-red-800">No autorizado para registro libre</p>
                                    <p class="mt-0.5">{{ errorMessage }}</p>
                                </div>
                            </div>

                            <!-- Mensaje de DNI ya registrado -->
                            <div v-if="alreadyRegistered"
                                class="mt-2.5 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-start gap-2.5">
                                <i class="bi bi-info-circle-fill text-blue-600 text-base mt-0.5 shrink-0"></i>
                                <div class="leading-relaxed flex-1">
                                    <p class="font-bold text-blue-900">Usuario ya registrado</p>
                                    <p class="mt-0.5">{{ alreadyRegisteredMessage }}</p>
                                    <Link :href="route('login')" class="inline-flex items-center gap-1 mt-2 font-bold text-blue-700 hover:text-blue-900 underline">
                                        <span>Ir a Iniciar Sesión</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </Link>
                                </div>
                            </div>

                            <!-- Confirmación de Verificación Exitosa -->
                            <div v-if="isVerified && !alreadyRegistered"
                                class="mt-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i>
                                    <div>
                                        <span class="font-bold">Identidad confirmada: </span>
                                        <span class="font-semibold text-emerald-950">{{ form.names }}</span>
                                        <span v-if="userRole"
                                            class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            {{ userRole }}
                                        </span>
                                    </div>
                                </div>
                                <i class="bi bi-shield-fill-check text-emerald-600 text-lg"></i>
                            </div>
                        </div>

                        <!-- Paso 2: Datos Personales Auto-completados y Correo Personal -->
                        <div v-if="isVerified && !alreadyRegistered" class="space-y-4 pt-1">
                            <div class="border-t border-slate-200 pt-3">
                                <p class="text-xs font-bold uppercase tracking-wider text-purple-700 flex items-center gap-1.5 mb-3">
                                    <i class="bi bi-person-fill-gear"></i>
                                    <span>2. Completa tus datos personales y credenciales</span>
                                </p>
                            </div>

                            <!-- Nombres y Apellidos Completos -->
                            <div>
                                <label for="names" class="block text-xs font-semibold text-slate-700 mb-1">
                                    Nombres y Apellidos Completos <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-person text-slate-400"></i>
                                    </div>
                                    <input id="names" v-model="form.names" type="text" required
                                        class="w-full pl-10 pr-4 py-2 rounded-xl border text-sm font-medium transition-all outline-none bg-slate-50/50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                        placeholder="Nombres completos" />
                                </div>
                                <p v-if="form.errors.names" class="mt-1 text-xs text-red-500">{{ form.errors.names }}</p>
                            </div>

                            <!-- Fila: Fecha de Nacimiento y Teléfono -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <!-- Fecha de Nacimiento -->
                                <div>
                                    <label for="birthdate" class="block text-xs font-semibold text-slate-700 mb-1">
                                        Fecha de Nacimiento
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="bi bi-calendar3 text-slate-400"></i>
                                        </div>
                                        <input id="birthdate" v-model="form.birthdate" type="date"
                                            class="w-full pl-10 pr-3 py-2 rounded-xl border text-sm font-medium transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600" />
                                    </div>
                                    <p v-if="form.errors.birthdate" class="mt-1 text-xs text-red-500">{{ form.errors.birthdate }}</p>
                                </div>

                                <!-- Teléfono / Celular -->
                                <div>
                                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">
                                        Teléfono / Celular
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="bi bi-telephone text-slate-400"></i>
                                        </div>
                                        <input id="phone" v-model="form.phone" type="tel"
                                            class="w-full pl-10 pr-3 py-2 rounded-xl border text-sm font-medium transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                            placeholder="Ej: 987654321" />
                                    </div>
                                    <p v-if="form.errors.phone" class="mt-1 text-xs text-red-500">{{ form.errors.phone }}</p>
                                </div>
                            </div>

                            <!-- Correo Personal -->
                            <div class="p-3.5 bg-purple-50/40 rounded-2xl border border-purple-200/80">
                                <label for="personal_email" class="block text-xs font-bold text-purple-950 mb-1 flex items-center justify-between">
                                    <span>Correo Electrónico Personal <span class="text-red-500">*</span></span>
                                    <span class="text-[10px] font-normal text-purple-700">Gmail, Outlook, etc.</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="bi bi-envelope-at text-purple-600 text-lg"></i>
                                    </div>
                                    <input id="personal_email" v-model="form.personal_email" type="email" required
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-medium transition-all outline-none bg-white"
                                        :class="form.errors.personal_email ? 'border-red-300 ring-2 ring-red-100' : 'border-purple-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600'"
                                        placeholder="tu_correo_personal@gmail.com" />
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Se utilizará como tu dirección personal de contacto y notificaciones directas.
                                </p>
                                <p v-if="form.errors.personal_email" class="mt-1 text-xs text-red-500">{{ form.errors.personal_email }}</p>
                            </div>

                            <!-- Subida Opcional de CV (PDF) -->
                            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200">
                                <label class="block text-xs font-bold text-slate-800 mb-1 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500 text-sm"></i>
                                        <span>Currículum Vítae (CV)</span>
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Opcional · PDF máx 5MB</span>
                                </label>

                                <label for="cv_file"
                                    class="flex items-center justify-between px-4 py-2.5 bg-white border border-dashed rounded-xl cursor-pointer hover:border-purple-500 hover:bg-purple-50/20 transition-all text-xs"
                                    :class="cvFileName ? 'border-purple-500 bg-purple-50/30' : 'border-slate-300'">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <i class="bi bi-cloud-arrow-up-fill text-base" :class="cvFileName ? 'text-purple-600' : 'text-slate-400'"></i>
                                        <span class="truncate font-medium text-slate-700">{{ cvFileName || 'Seleccionar archivo PDF de tu CV' }}</span>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 shrink-0">
                                        Examinar
                                    </span>
                                    <input id="cv_file" type="file" accept=".pdf,application/pdf" class="hidden" @change="onCvChange" />
                                </label>
                                <p v-if="form.errors.cv_file" class="mt-1.5 text-xs text-red-500">{{ form.errors.cv_file }}</p>
                            </div>

                            <!-- Contraseña y Confirmación -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                                        Nueva Contraseña <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="bi bi-lock text-slate-400"></i>
                                        </div>
                                        <input id="password" v-model="form.password" :type="showPass ? 'text' : 'password'"
                                            required autocomplete="new-password"
                                            class="w-full pl-10 pr-10 py-2 rounded-xl border text-sm transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                            placeholder="Mínimo 8 caracteres" />
                                        <button type="button" @click="showPass = !showPass"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                            aria-label="Ver u ocultar contraseña">
                                            <i class="bi text-sm" :class="showPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                        </button>
                                    </div>
                                    <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                                        Confirmar Contraseña <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="bi bi-lock-fill text-slate-400"></i>
                                        </div>
                                        <input id="password_confirmation" v-model="form.password_confirmation"
                                            :type="showPass ? 'text' : 'password'" required autocomplete="new-password"
                                            class="w-full pl-10 pr-3 py-2 rounded-xl border text-sm transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                            placeholder="Repite la contraseña" />
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Envío / Activación -->
                            <div class="pt-2">
                                <button type="submit" :disabled="form.processing"
                                    class="w-full flex items-center justify-center gap-2 py-3.5 px-6 text-sm font-bold rounded-xl text-white transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:opacity-50"
                                    style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 100%);">
                                    <i class="bi bi-person-check-fill text-lg text-amber-400"></i>
                                    <span>{{ form.processing ? 'Procesando registro...' : 'Completar Registro y Activar Cuenta' }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <p class="text-center mt-6 text-xs text-slate-500">
                    Al registrarte aceptas las políticas institucionales y el
                    <a href="#" class="underline hover:text-slate-700 text-slate-600 font-semibold">
                        Reglamento Interno
                    </a>
                    del IESTP Francisco Vigo Caballero.
                </p>
            </div>
        </div>
    </PublicLayout>
</template>
