<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from '@/route';

const props = defineProps({
    status: {
        type: [Number, String],
        default: 404,
    },
    message: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});

const statusCode = computed(() => {
    const parsed = parseInt(props.status, 10);
    return isNaN(parsed) ? 404 : parsed;
});

const errorCatalog = {
    400: {
        code: '400',
        title: 'Solicitud Incorrecta',
        description: 'El servidor no pudo procesar la solicitud debido a un formato no válido o parámetros incorrectos.',
        icon: 'bi-exclamation-diamond-fill',
        theme: 'amber',
        accentBadge: 'Error de Sintaxis',
    },
    401: {
        code: '401',
        title: 'Autenticación Requerida',
        description: 'Debes iniciar sesión con una cuenta autorizada para acceder a esta sección institucional.',
        icon: 'bi-key-fill',
        theme: 'blue',
        accentBadge: 'Acceso no Autenticado',
    },
    402: {
        code: '402',
        title: 'Pago Requerido',
        description: 'Se requiere completar un trámite o pago previo para acceder al recurso solicitado.',
        icon: 'bi-credit-card-2-front-fill',
        theme: 'amber',
        accentBadge: 'Trámite Requerido',
    },
    403: {
        code: '403',
        title: 'Acceso Restringido',
        description: 'No tienes permisos suficientes para ver este recurso o realizar esta acción en el sistema.',
        icon: 'bi-shield-slash-fill',
        theme: 'rose',
        accentBadge: 'Permisos Insuficientes',
    },
    404: {
        code: '404',
        title: 'Página No Encontrada',
        description: 'Lo sentimos, la página que buscas no existe, ha sido eliminada o cambió de dirección.',
        icon: 'bi-compass-fill',
        theme: 'amber',
        accentBadge: 'Recurso No Localizado',
    },
    419: {
        code: '419',
        title: 'Sesión Expirada',
        description: 'La sesión de esta página ha caducado por inactividad. Por favor, vuelve a cargar la página e intenta de nuevo.',
        icon: 'bi-clock-history',
        theme: 'amber',
        accentBadge: 'Token de Seguridad Caducado',
    },
    429: {
        code: '429',
        title: 'Límite de Peticiones Excedido',
        description: 'Has realizado demasiadas solicitudes en poco tiempo. Por favor, espera unos minutos antes de intentar de nuevo.',
        icon: 'bi-hourglass-split',
        theme: 'amber',
        accentBadge: 'Demasiadas Solicitudes',
    },
    500: {
        code: '500',
        title: 'Error Interno del Servidor',
        description: 'Algo salió mal en nuestros servidores. Ya estamos trabajando para solucionarlo lo antes posible.',
        icon: 'bi-exclamation-octagon-fill',
        theme: 'rose',
        accentBadge: 'Falla del Servidor',
    },
    503: {
        code: '503',
        title: 'Servicio en Mantenimiento',
        description: 'Estamos realizando mejoras y mantenimiento programado. Estaremos de vuelta muy pronto.',
        icon: 'bi-tools',
        theme: 'emerald',
        accentBadge: 'Mantenimiento en Curso',
    },
};

const currentError = computed(() => {
    return errorCatalog[statusCode.value] || {
        code: String(statusCode.value),
        title: 'Error Inesperado',
        description: 'Ha ocurrido un problema al procesar la solicitud. Por favor, intenta de nuevo.',
        icon: 'bi-exclamation-triangle-fill',
        theme: 'amber',
        accentBadge: 'Aviso del Sistema',
    };
});

const displayTitle = computed(() => props.message || currentError.value.title);
const displayDescription = computed(() => props.description || currentError.value.description);
const displayIcon = computed(() => currentError.value.icon);
const theme = computed(() => currentError.value.theme);

const headTitle = computed(() => `${currentError.value.code}: ${displayTitle.value} — IESTP Francisco Vigo Caballero`);

const goBack = () => {
    if (typeof window !== 'undefined' && window.history.length > 1) {
        window.history.back();
    } else {
        router.visit(route('inicio'));
    }
};

const reloadPage = () => {
    if (typeof window !== 'undefined') {
        window.location.reload();
    }
};
</script>

<template>
    <Head :title="headTitle" />

    <div class="min-h-screen bg-[#0F172A] text-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden antialiased selection:bg-blue-600 selection:text-white">
        <!-- Ambient Decorative Blurred Glows -->
        <div class="absolute -top-32 -left-32 w-80 sm:w-96 h-80 sm:h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 sm:w-96 h-80 sm:h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-72 bg-indigo-600/5 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Main Card Component -->
        <div class="w-full max-w-xl bg-[#1E293B] border border-slate-700/80 rounded-3xl p-6 sm:p-10 md:p-12 text-center relative z-10 shadow-2xl overflow-hidden backdrop-blur-xl animate-fade-in-up">
            <!-- Top Gradient Accent Bar -->
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-amber-500 absolute top-0 left-0"></div>

            <!-- Institution Header -->
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="w-11 h-11 bg-slate-900 border border-slate-700 rounded-xl flex items-center justify-center shadow-md shrink-0">
                    <i class="bi bi-mortarboard-fill text-amber-500 text-2xl"></i>
                </div>
                <div class="text-left leading-none">
                    <span class="text-[11px] uppercase tracking-widest text-slate-400 font-bold block">PORTAL WEB</span>
                    <h2 class="text-sm font-bold text-slate-200 mt-1">
                        IESTP <span class="text-amber-500">Francisco Vigo Caballero</span>
                    </h2>
                </div>
            </div>

            <!-- Error Icon Container -->
            <div
                class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-6 shadow-inner transition-transform duration-300 hover:scale-105"
                :class="{
                    'bg-amber-500/10 border border-amber-500/20 text-amber-500': theme === 'amber',
                    'bg-rose-500/10 border border-rose-500/20 text-rose-500': theme === 'rose',
                    'bg-blue-500/10 border border-blue-500/20 text-blue-500': theme === 'blue',
                    'bg-emerald-500/10 border border-emerald-500/20 text-emerald-500': theme === 'emerald',
                }"
            >
                <i :class="['bi text-4xl', displayIcon]"></i>
            </div>

            <!-- Error Status Code -->
            <h1 class="text-7xl sm:text-8xl md:text-9xl font-black tracking-tighter text-white mb-2 font-display select-none">
                {{ currentError.code }}
            </h1>

            <!-- Error Title -->
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-100 mb-3 tracking-tight font-display">
                {{ displayTitle }}
            </h3>

            <!-- Error Description -->
            <p class="text-slate-400 text-sm sm:text-base mb-8 max-w-md mx-auto leading-relaxed">
                {{ displayDescription }}
            </p>

            <!-- Action Buttons Grid -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <!-- Regresar -->
                <button
                    type="button"
                    @click="goBack"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-700 hover:border-slate-500 bg-slate-800 hover:bg-slate-700/80 text-slate-300 hover:text-white font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer shadow-sm active:scale-95"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Regresar</span>
                </button>

                <!-- Ir al Inicio (SPA Link) -->
                <Link
                    :href="route('inicio')"
                    class="w-full sm:w-auto px-7 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm shadow-md hover:shadow-blue-600/25 transition-all duration-200 flex items-center justify-center gap-2 active:scale-95"
                >
                    <i class="bi bi-house-fill"></i>
                    <span>Ir al Inicio</span>
                </Link>

                <!-- Recargar (for 419, 500, 503) -->
                <button
                    v-if="[419, 500, 503].includes(statusCode)"
                    type="button"
                    @click="reloadPage"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl border border-amber-500/40 hover:border-amber-400 bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer active:scale-95"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Recargar</span>
                </button>

                <!-- Iniciar Sesión (for 401, 403) -->
                <Link
                    v-if="[401, 403].includes(statusCode)"
                    :href="route('login')"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl border border-blue-500/40 hover:border-blue-400 bg-blue-600/10 hover:bg-blue-600/20 text-blue-300 font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 active:scale-95"
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Iniciar Sesión</span>
                </Link>
            </div>

            <!-- Footer Help Note -->
            <div class="mt-10 pt-6 border-t border-slate-800/80 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>Si crees que esto es un error del sistema, por favor contacta al administrador.</span>
                <span class="font-mono text-slate-600 text-[11px]">{{ currentError.accentBadge }}</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}
</style>
