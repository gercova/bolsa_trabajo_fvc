<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import route from '@/route';

const props = defineProps({
    user: { type: Object, required: true },
});

const page = usePage();
const authUser = computed(() => page.props.auth?.user || {});
const isSelf = computed(() => authUser.value?.id === props.user.id);

// Active Tab
const activeTab = ref('profile'); // 'profile' | 'password' | 'account'

// ── TOAST NOTIFICATIONS ────────────────────────────────────────────────────────
const toastMessage = ref('');
const toastType = ref('success');
const showToast = ref(false);

const notify = (msg, type = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 4500);
};

// ── TAB 1: PROFILE UPDATE FORM ─────────────────────────────────────────────────
const photoPreview = ref(props.user.photo_profile ? `/storage/${props.user.photo_profile}` : null);
const cvFileName = ref('');

const profileForm = useForm({
    names: props.user.names || '',
    dni: props.user.dni || '',
    email: props.user.email || '',
    personal_email: props.user.personal_email || '',
    phone: props.user.phone || '',
    job_position: props.user.job_position || '',
    birthdate: props.user.birthdate ? props.user.birthdate.substring(0, 10) : '',
    sex: props.user.sex || '',
    mother_tongue: props.user.mother_tongue || 'Español',
    address: props.user.address || '',
    photo_profile: null,
    cv_file: null,
});

const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        profileForm.photo_profile = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const handleCvChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        profileForm.cv_file = file;
        cvFileName.value = file.name;
    }
};

const submitProfile = () => {
    router.post(
        route('admin.profile.update', props.user.id),
        {
            _method: 'PUT',
            ...profileForm.data(),
        },
        {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                notify('¡Los datos del perfil se actualizaron exitosamente!');
            },
            onError: (errors) => {
                profileForm.setError(errors);
                notify('Por favor corrige los errores indicados en el formulario.', 'error');
            },
        }
    );
};

// ── TAB 2: PASSWORD UPDATE FORM ────────────────────────────────────────────────
const showCurrentPass = ref(false);
const showNewPass = ref(false);
const showConfirmPass = ref(false);

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitPassword = () => {
    const targetRoute = isSelf.value
        ? route('password.update')
        : route('admin.users.password.update', props.user.id);

    passwordForm.put(targetRoute, {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            notify('La contraseña de acceso se ha actualizado satisfactoriamente.');
        },
        onError: () => {
            notify('Revise los datos ingresados para la contraseña.', 'error');
        },
    });
};

// ── TAB 3: ACCOUNT DELETION ────────────────────────────────────────────────────
const showDeleteModal = ref(false);
const deleteForm = useForm({
    password: '',
});

const submitDeleteAccount = () => {
    deleteForm.delete(route('admin.profile.destroy', props.user.id), {
        preserveScroll: true,
        onError: () => {
            notify('No se pudo eliminar la cuenta. Verifique su contraseña actual.', 'error');
        },
    });
};

const breadcrumbs = [
    { label: 'Dashboard', url: route('admin.dashboard.index') },
    { label: 'Usuarios', url: route('admin.partners.index') },
    { label: props.user.names || 'Perfil', active: true },
];
</script>

<template>
    <Head :title="`Perfil de Usuario - ${user.names || 'Usuario'}`" />

    <AdminLayout
        title="Perfil de Usuario"
        :subtitle="`Gestión de datos personales, credenciales y preferencias de ${user.names}`"
        icon="bi-person-circle"
        :breadcrumbs="breadcrumbs"
    >
        <div class="space-y-6">

            <!-- Toast Alert -->
            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform -translate-y-4 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-4 opacity-0"
            >
                <div
                    v-if="showToast"
                    class="fixed top-20 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-xs font-bold border backdrop-blur-md"
                    :class="toastType === 'success' ? 'bg-emerald-500/95 text-white border-emerald-400' : 'bg-rose-500/95 text-white border-rose-400'"
                >
                    <i :class="toastType === 'success' ? 'bi bi-check-circle-fill text-base' : 'bi bi-exclamation-triangle-fill text-base'"></i>
                    <span>{{ toastMessage }}</span>
                    <button type="button" @click="showToast = false" class="ml-2 hover:opacity-75">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </transition>

            <!-- User Hero Card Summary -->
            <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800 relative overflow-hidden">
                <div class="h-1.5 w-full bg-gradient-to-r from-teal-500 via-indigo-500 to-purple-600 absolute top-0 left-0"></div>
                <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">

                    <!-- User Avatar Thumbnail -->
                    <div class="relative group shrink-0">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl overflow-hidden bg-slate-800 p-1 border-2 border-indigo-500/30 shadow-md">
                            <img
                                v-if="photoPreview"
                                :src="photoPreview"
                                :alt="user.names"
                                class="w-full h-full object-cover rounded-[22px]"
                            />
                            <div v-else class="w-full h-full bg-slate-800 rounded-[22px] flex items-center justify-center text-3xl font-black text-purple-300">
                                {{ (user.names || 'US').substring(0, 2).toUpperCase() }}
                            </div>
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full border-2 border-slate-900 flex items-center justify-center text-white text-xs shadow-md"
                            :class="user.is_active ? 'bg-emerald-500' : 'bg-rose-500'"
                            :title="user.is_active ? 'Usuario Activo' : 'Usuario Inactivo'"
                        >
                            <i class="bi" :class="user.is_active ? 'bi-patch-check-fill' : 'bi-x-circle-fill'"></i>
                        </div>
                    </div>

                    <!-- User Details Info -->
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wider uppercase bg-purple-500/20 text-purple-300 border border-purple-400/30">
                                <i class="bi bi-shield-shaded mr-1"></i> Rol: {{ user.role || 'Usuario' }}
                            </span>
                            <span v-if="user.job_position" class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/10">
                                <i class="bi bi-briefcase-fill mr-1 text-indigo-400"></i> {{ user.job_position }}
                            </span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight font-display">
                            {{ user.names }}
                        </h2>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-5 gap-y-1.5 text-xs sm:text-sm text-slate-300 pt-1">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-person-vcard text-purple-400"></i>
                                DNI: <strong class="text-white font-mono">{{ user.dni || '—' }}</strong>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-envelope text-indigo-400"></i>
                                {{ user.email }}
                            </span>
                            <span v-if="user.phone" class="flex items-center gap-1.5">
                                <i class="bi bi-telephone text-emerald-400"></i>
                                {{ user.phone }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Form Card with Tabs -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

                <!-- Tab Navigation Bar -->
                <div class="bg-slate-50 border-b border-slate-200 px-4 sm:px-6 pt-3 flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        @click="activeTab = 'profile'"
                        :class="[
                            'px-5 py-3 rounded-t-2xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer',
                            activeTab === 'profile'
                                ? 'border-purple-600 text-purple-700 bg-white shadow-xs font-bold'
                                : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold',
                        ]"
                    >
                        <i class="bi bi-person-gear text-base"></i>
                        <span>1. Información del Perfil</span>
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'password'"
                        :class="[
                            'px-5 py-3 rounded-t-2xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer',
                            activeTab === 'password'
                                ? 'border-purple-600 text-purple-700 bg-white shadow-xs font-bold'
                                : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold',
                        ]"
                    >
                        <i class="bi bi-shield-lock text-base"></i>
                        <span>2. Seguridad &amp; Contraseña</span>
                    </button>

                    <button
                        type="button"
                        @click="activeTab = 'account'"
                        :class="[
                            'px-5 py-3 rounded-t-2xl border-b-2 text-xs sm:text-sm transition-all flex items-center gap-2 cursor-pointer',
                            activeTab === 'account'
                                ? 'border-purple-600 text-purple-700 bg-white shadow-xs font-bold'
                                : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold',
                        ]"
                    >
                        <i class="bi bi-info-circle text-base"></i>
                        <span>3. Detalles de Cuenta</span>
                    </button>
                </div>

                <!-- TAB 1: INFORMACIÓN DEL PERFIL -->
                <div v-show="activeTab === 'profile'" class="p-6 sm:p-8">
                    <form @submit.prevent="submitProfile" class="space-y-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                            <!-- Left Column: Foto de Perfil & CV Document -->
                            <div class="lg:col-span-1 space-y-6">

                                <!-- Avatar Box -->
                                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-4">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-camera-fill text-purple-600"></i>
                                        Fotografía de Perfil
                                    </h3>

                                    <div class="relative group w-full aspect-square max-w-[200px] mx-auto rounded-2xl overflow-hidden bg-slate-200 border-2 border-dashed border-purple-300 flex items-center justify-center shadow-inner">
                                        <img
                                            v-if="photoPreview"
                                            :src="photoPreview"
                                            alt="Vista previa"
                                            class="w-full h-full object-cover"
                                        />
                                        <div v-else class="text-center p-4">
                                            <i class="bi bi-person-bounding-box text-5xl text-slate-400"></i>
                                            <p class="text-[11px] text-slate-500 mt-2">Sin fotografía asignada</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Cambiar Imagen de Perfil
                                        </label>
                                        <input
                                            type="file"
                                            accept="image/jpeg,image/png,image/jpg,image/webp"
                                            @change="handlePhotoChange"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition cursor-pointer border border-slate-200 rounded-xl bg-white p-1"
                                        />
                                        <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                                            <i class="bi bi-info-circle"></i> JPG, PNG, WEBP. Máx. 2MB
                                        </p>
                                    </div>
                                </div>

                                <!-- CV Box -->
                                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-4">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                        <i class="bi bi-file-earmark-person-fill text-indigo-600"></i>
                                        Curículum Vitae (CV)
                                    </h3>

                                    <div v-if="user.cv_file" class="p-3 bg-white border border-slate-200 rounded-xl flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 truncate text-xs">
                                            <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-lg"></i>
                                            <span class="font-bold text-slate-800 truncate">CV Registrado</span>
                                        </div>
                                        <a
                                            :href="`/storage/${user.cv_file}`"
                                            target="_blank"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 font-bold shrink-0"
                                        >
                                            Ver PDF
                                        </a>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Subir / Reemplazar CV (PDF)
                                        </label>
                                        <input
                                            type="file"
                                            accept="application/pdf"
                                            @change="handleCvChange"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer border border-slate-200 rounded-xl bg-white p-1"
                                        />
                                        <p v-if="cvFileName" class="text-emerald-600 font-semibold text-[11px] mt-1">
                                            <i class="bi bi-check2"></i> Archivo seleccionado: {{ cvFileName }}
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- Right Column: Personal Data Form Fields -->
                            <div class="lg:col-span-2 space-y-6">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- Names -->
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Nombres y Apellidos Completos <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            v-model="profileForm.names"
                                            required
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                            :class="profileForm.errors.names ? 'border-rose-500 ring-2 ring-rose-200' : ''"
                                        />
                                        <p v-if="profileForm.errors.names" class="text-xs text-rose-600 mt-1 font-semibold">{{ profileForm.errors.names }}</p>
                                    </div>

                                    <!-- DNI -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Número de Documento (DNI) <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            v-model="profileForm.dni"
                                            required
                                            maxlength="15"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                            :class="profileForm.errors.dni ? 'border-rose-500 ring-2 ring-rose-200' : ''"
                                        />
                                        <p v-if="profileForm.errors.dni" class="text-xs text-rose-600 mt-1 font-semibold">{{ profileForm.errors.dni }}</p>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Teléfono / Celular
                                        </label>
                                        <input
                                            type="text"
                                            v-model="profileForm.phone"
                                            placeholder="Ej: 987654321"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        />
                                    </div>

                                    <!-- Institutional Email -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Correo Institucional <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            v-model="profileForm.email"
                                            required
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                            :class="profileForm.errors.email ? 'border-rose-500 ring-2 ring-rose-200' : ''"
                                        />
                                        <p v-if="profileForm.errors.email" class="text-xs text-rose-600 mt-1 font-semibold">{{ profileForm.errors.email }}</p>
                                    </div>

                                    <!-- Personal Email -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Correo Personal
                                        </label>
                                        <input
                                            type="email"
                                            v-model="profileForm.personal_email"
                                            placeholder="correo_personal@gmail.com"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        />
                                    </div>

                                    <!-- Job Position -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Cargo u Ocupación
                                        </label>
                                        <input
                                            type="text"
                                            v-model="profileForm.job_position"
                                            placeholder="Ej: Docente / Administrador"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        />
                                    </div>

                                    <!-- Birthdate -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Fecha de Nacimiento
                                        </label>
                                        <input
                                            type="date"
                                            v-model="profileForm.birthdate"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        />
                                    </div>

                                    <!-- Sex / Gender -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Sexo / Género
                                        </label>
                                        <select
                                            v-model="profileForm.sex"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        >
                                            <option value="">Seleccione opción</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </div>

                                    <!-- Mother Tongue -->
                                    <div>
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Lengua Materna
                                        </label>
                                        <input
                                            type="text"
                                            v-model="profileForm.mother_tongue"
                                            placeholder="Ej: Español, Quechua"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        />
                                    </div>

                                    <!-- Address -->
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                            Dirección Domiciliaria
                                        </label>
                                        <input
                                            type="text"
                                            v-model="profileForm.address"
                                            placeholder="Ej: Jr. San Martín N° 240, Uchiza"
                                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        />
                                    </div>
                                </div>

                                <!-- Actions Bar -->
                                <div class="pt-6 border-t border-slate-200 flex flex-wrap items-center justify-end gap-3">
                                    <Link
                                        :href="route('admin.partners.index')"
                                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition"
                                    >
                                        Cancelar
                                    </Link>
                                    <button
                                        type="submit"
                                        :disabled="profileForm.processing"
                                        class="px-7 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50"
                                    >
                                        <span v-if="profileForm.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                        <i v-else class="bi bi-floppy-fill"></i>
                                        <span>Guardar Cambios del Perfil</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

                <!-- TAB 2: SEGURIDAD & CONTRASEÑA -->
                <div v-show="activeTab === 'password'" class="p-6 sm:p-8 max-w-2xl">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 font-display">Actualización de Contraseña</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Asegúrese de que su cuenta utilice una contraseña de alta seguridad con al menos 8 caracteres.
                            </p>
                        </div>

                        <form @submit.prevent="submitPassword" class="space-y-5">
                            <!-- Current Password (only required if updating self) -->
                            <div v-if="isSelf">
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Contraseña Actual <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-shield-lock text-purple-600"></i>
                                    </div>
                                    <input
                                        :type="showCurrentPass ? 'text' : 'password'"
                                        v-model="passwordForm.current_password"
                                        required
                                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        :class="passwordForm.errors.current_password ? 'border-rose-500 ring-2 ring-rose-200' : ''"
                                    />
                                    <button
                                        type="button"
                                        @click="showCurrentPass = !showCurrentPass"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    >
                                        <i class="bi" :class="showCurrentPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                    </button>
                                </div>
                                <p v-if="passwordForm.errors.current_password" class="text-xs text-rose-600 mt-1 font-semibold">
                                    {{ passwordForm.errors.current_password }}
                                </p>
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Nueva Contraseña <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-key-fill text-purple-600"></i>
                                    </div>
                                    <input
                                        :type="showNewPass ? 'text' : 'password'"
                                        v-model="passwordForm.password"
                                        required
                                        minlength="8"
                                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                        :class="passwordForm.errors.password ? 'border-rose-500 ring-2 ring-rose-200' : ''"
                                    />
                                    <button
                                        type="button"
                                        @click="showNewPass = !showNewPass"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    >
                                        <i class="bi" :class="showNewPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                    </button>
                                </div>
                                <p v-if="passwordForm.errors.password" class="text-xs text-rose-600 mt-1 font-semibold">
                                    {{ passwordForm.errors.password }}
                                </p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Confirmar Nueva Contraseña <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-check2-all text-purple-600"></i>
                                    </div>
                                    <input
                                        :type="showConfirmPass ? 'text' : 'password'"
                                        v-model="passwordForm.password_confirmation"
                                        required
                                        minlength="8"
                                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirmPass = !showConfirmPass"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                    >
                                        <i class="bi" :class="showConfirmPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                                <button
                                    type="submit"
                                    :disabled="passwordForm.processing"
                                    class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50"
                                >
                                    <span v-if="passwordForm.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                    <i v-else class="bi bi-shield-check"></i>
                                    <span>Actualizar Contraseña</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB 3: DETALLES DE CUENTA & AUDITORÍA -->
                <div v-show="activeTab === 'account'" class="p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 font-display">Detalles del Sistema &amp; Auditoría</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Información técnica y registros de actividad de la cuenta.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ID de Usuario</span>
                            <p class="text-base font-black text-slate-900 font-mono mt-1">#{{ user.id }}</p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Fecha de Registro</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ user.created_at ? new Date(user.created_at).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Última Actualización</span>
                            <p class="text-sm font-bold text-slate-800 mt-1">
                                {{ user.updated_at ? new Date(user.updated_at).toLocaleDateString('es-PE') : '—' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Verificación de Email</span>
                            <p class="text-sm font-bold mt-1 flex items-center gap-1.5" :class="user.email_verified_at ? 'text-emerald-700' : 'text-amber-600'">
                                <i class="bi" :class="user.email_verified_at ? 'bi-check-circle-fill' : 'bi-clock-history'"></i>
                                <span>{{ user.email_verified_at ? 'Verificado' : 'Pendiente de verificación' }}</span>
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rol Principal</span>
                            <p class="text-sm font-extrabold text-purple-700 mt-1">
                                {{ user.role || 'Solicitante' }}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Estado de Acceso</span>
                            <p class="text-sm font-bold mt-1" :class="user.is_active ? 'text-emerald-700' : 'text-rose-600'">
                                {{ user.is_active ? 'Habilitado para inicio de sesión' : 'Bloqueado / Inactivo' }}
                            </p>
                        </div>
                    </div>

                    <!-- Account Deletion Zone -->
                    <div class="pt-6 border-t border-slate-200">
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-rose-900 flex items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-rose-600"></i> Zona de Peligro: Eliminar Cuenta
                                </h4>
                                <p class="text-xs text-rose-700 mt-1">
                                    Una vez eliminada la cuenta, todos sus recursos y datos asociados serán permanentemente destruidos.
                                </p>
                            </div>
                            <button
                                type="button"
                                @click="showDeleteModal = true"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-xs transition shrink-0 cursor-pointer"
                            >
                                Eliminar Cuenta
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- MODAL: CONFIRM ACCOUNT DELETION -->
            <div
                v-if="showDeleteModal"
                class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
                @click.self="showDeleteModal = false"
            >
                <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto">
                        <i class="bi bi-shield-slash"></i>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-bold text-slate-900">¿Confirmar eliminación de cuenta?</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Por favor ingrese su contraseña actual para confirmar la eliminación permanente de la cuenta de
                            <strong class="text-slate-800">{{ user.names }}</strong>.
                        </p>
                    </div>

                    <form @submit.prevent="submitDeleteAccount" class="space-y-4 pt-2">
                        <div>
                            <input
                                type="password"
                                v-model="deleteForm.password"
                                required
                                placeholder="Ingrese su contraseña actual..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-200"
                                :class="deleteForm.errors.password ? 'border-rose-500' : ''"
                            />
                            <p v-if="deleteForm.errors.password" class="text-xs text-rose-600 mt-1">
                                {{ deleteForm.errors.password }}
                            </p>
                        </div>

                        <div class="flex items-center justify-center gap-3">
                            <button
                                type="button"
                                @click="showDeleteModal = false"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="deleteForm.processing"
                                class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-xs transition disabled:opacity-50 inline-flex items-center gap-1.5"
                            >
                                <span v-if="deleteForm.processing" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <span>Eliminar Definitivamente</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>
