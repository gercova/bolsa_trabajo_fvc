<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    enterprise: {
        type: Object,
        default: () => ({}),
    },
    status: {
        type: String,
        default: null,
    },
});

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
    _hp_security_check: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <PublicLayout :enterprise="enterprise">
        <Head>
            <title>Iniciar Sesión — Portal FVC</title>
            <meta name="description" content="Inicio de sesión institucional para estudiantes, docentes y egresados del IESTP Francisco Vigo Caballero." />
        </Head>

        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-sky-50/50">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-2xl shadow-xl shadow-[rgba(15,23,42,.06)] p-8 md:p-10 border border-slate-200">
                    <!-- Institutional Logo -->
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 rounded-2xl flex items-center justify-center overflow-hidden bg-white p-2 border border-slate-100 shadow-sm">
                            <img v-if="enterprise?.logo_path || enterprise?.logo_base64"
                                :src="enterprise?.logo_base64 || enterprise?.logo_path"
                                :alt="`Logo ${enterprise?.company_name || 'IESTP FVC'}`"
                                class="w-20 h-20 object-contain" />
                            <div v-else class="w-20 h-20 rounded-2xl bg-blue-900 text-blue-200 flex items-center justify-center text-3xl">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Header -->
                    <h2 class="text-center text-2xl sm:text-3xl font-extrabold mb-2 text-slate-900 tracking-tight">
                        Iniciar Sesión
                    </h2>
                    <p class="text-center text-sm mb-6 text-slate-500">
                        ¿No tienes una cuenta?
                        <Link href="/register"
                            class="font-semibold underline decoration-1 underline-offset-2 transition-colors hover:text-blue-800 text-blue-600">
                            Regístrate aquí
                        </Link>
                    </p>

                    <!-- Status / Session Flash -->
                    <div v-if="status"
                        class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-sm text-emerald-800">
                        <i class="bi bi-check-circle-fill text-emerald-600 text-lg flex-shrink-0"></i>
                        <span>{{ status }}</span>
                    </div>

                    <!-- Rate Limit Lockout Banner -->
                    <div v-if="form.errors.email && form.errors.email.toLowerCase().includes('intentos')"
                        class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 text-sm text-red-800">
                        <i class="bi bi-shield-slash-fill text-red-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold">Acceso bloqueado temporalmente</p>
                            <p class="text-xs text-red-700 mt-0.5">{{ form.errors.email }}</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Email or DNI -->
                        <div>
                            <label for="email" class="block text-sm font-semibold mb-1.5 text-slate-700">
                                Correo Electrónico o N° de DNI
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bi bi-person-vcard text-lg text-slate-400"></i>
                                </div>
                                <input id="email" v-model="form.email" type="text" required autofocus
                                    autocomplete="username"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none bg-white text-slate-900"
                                    :class="form.errors.email ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-600'"
                                    placeholder="correo@ejemplo.com o N° de DNI" />
                            </div>
                            <p v-if="form.errors.email && !form.errors.email.toLowerCase().includes('intentos')"
                                class="mt-1.5 text-sm text-red-500 flex items-center gap-1.5">
                                <i class="bi bi-exclamation-circle text-xs"></i>
                                <span>{{ form.errors.email }}</span>
                            </p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold mb-1.5 text-slate-700">
                                Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bi bi-lock text-lg text-slate-400"></i>
                                </div>
                                <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
                                    required autocomplete="current-password"
                                    class="w-full pl-10 pr-11 py-2.5 rounded-xl border text-sm transition-all duration-200 outline-none bg-white text-slate-900"
                                    :class="form.errors.password ? 'border-red-300 ring-2 ring-red-100' : 'border-slate-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-600'"
                                    placeholder="••••••••" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors"
                                    aria-label="Alternar visibilidad de contraseña">
                                    <i class="bi text-lg" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-500 flex items-center gap-1.5">
                                <i class="bi bi-exclamation-circle text-xs"></i>
                                <span>{{ form.errors.password }}</span>
                            </p>
                        </div>

                        <!-- Remember me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input id="remember_me" v-model="form.remember" type="checkbox"
                                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-200 focus:ring-offset-0" />
                                <span class="text-sm text-slate-600">Recordarme</span>
                            </label>

                            <Link href="/forgot-password"
                                class="text-sm font-medium underline decoration-1 underline-offset-2 transition-colors hover:text-blue-800 text-blue-600">
                                ¿Olvidaste tu contraseña?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex items-center justify-center gap-2 py-3 px-6 text-sm font-bold rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg cursor-pointer bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50 shadow-md shadow-blue-500/20">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>{{ form.processing ? 'Iniciando...' : 'Iniciar Sesión' }}</span>
                        </button>

                        <!-- Protection Indicator -->
                        <div class="pt-2 flex items-center justify-center gap-1.5 text-[11.5px] text-slate-500">
                            <i class="bi bi-shield-check text-emerald-600 text-sm"></i>
                            <span>Protección activa contra accesos no autorizados y fuerza bruta</span>
                        </div>

                        <!-- Security Honeypot Field -->
                        <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; width: 0; height: 0; overflow: hidden;"
                            aria-hidden="true">
                            <input v-model="form._hp_security_check" type="text" tabindex="-1" autocomplete="off" />
                        </div>
                    </form>
                </div>

                <p class="text-center mt-6 text-xs text-slate-500">
                    Al iniciar sesión aceptas nuestros
                    <a href="#" class="underline decoration-1 underline-offset-2 transition-colors hover:text-slate-800 text-slate-500">
                        Términos y Condiciones
                    </a>
                </p>
            </div>
        </div>
    </PublicLayout>
</template>
