@extends('layouts.app')

@section('title', 'Registro Institucional — IESTP Francisco Vigo Caballero')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: var(--sand);">
    <div class="w-full max-w-xl">
        <div class="bg-white rounded-3xl shadow-2xl shadow-[rgba(15,23,42,.08)] p-6 sm:p-10 border border-slate-200/80 transition-all duration-300"
            x-data="registerForm({
                initialDni: '{{ old('dni', $initialDni ?? '') }}',
                initialNames: '{{ old('names', old('name', $matchedUser->names ?? '')) }}',
                initialBirthdate: '{{ old('birthdate', ($matchedUser && $matchedUser->birthdate) ? (is_string($matchedUser->birthdate) ? substr($matchedUser->birthdate, 0, 10) : $matchedUser->birthdate->format('Y-m-d')) : '') }}',
                initialPersonalEmail: '{{ old('personal_email', old('email', '')) }}',
                initialPhone: '{{ old('phone', $matchedUser->phone ?? '') }}',
                hasServerError: {{ $errors->any() ? 'true' : 'false' }},
                hasDniError: {{ $errors->has('dni') ? 'true' : 'false' }},
                verifyUrl: '{{ route('register.verify-dni') }}'
            })">

            {{-- Encabezado con Logo Institucional --}}
            <div class="flex flex-col items-center text-center mb-6">
                @if(isset($enterprise) && $enterprise->logo_path)
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-3">
                        <img src="{{ asset($enterprise->logo_path) }}" alt="Logo {{ $enterprise->company_name ?? 'IESTP FVC' }}" class="w-20 h-20 object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 bg-slate-900 text-amber-400">
                        <i class="bi bi-mortarboard-fill text-3xl"></i>
                    </div>
                @endif
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-display">
                    Registro de Cuenta Institucional
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md">
                    Acceso exclusivo para estudiantes, egresados, plana docente y personal del <strong class="text-slate-700">IESTP Francisco Vigo Caballero</strong>.
                </p>
                <p class="text-xs text-slate-500 mt-2">
                    ¿Ya completaste tu registro?
                    <a href="{{ route('login') }}" class="font-bold underline decoration-1 underline-offset-2 transition-colors hover:text-purple-700 text-purple-600">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>

            {{-- Banner informativo del padrón --}}
            <div class="mb-6 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-start gap-3 text-xs sm:text-sm text-amber-900">
                <i class="bi bi-shield-check text-amber-600 text-lg flex-shrink-0 mt-0.5"></i>
                <div class="leading-relaxed">
                    <p class="font-bold text-amber-950">Validación de Padrón Institucional</p>
                    <p class="text-amber-800/90 text-xs mt-0.5">
                        Para activar tu cuenta, tus datos y número de DNI deben constar en la base de datos de la institución. Ingresa tu DNI para verificar tu identidad y completar tu correo personal.
                    </p>
                </div>
            </div>

            {{-- Formulario Principal --}}
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5" @submit="handleSubmit($event)">
                @csrf

                {{-- Paso 1: Ingreso y Validación del DNI --}}
                <div class="bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200">
                    <label for="dni" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        1. Documento Nacional de Identidad (DNI) <span class="text-red-500">*</span>
                    </label>

                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="bi bi-person-vcard text-lg text-slate-400"></i>
                            </div>
                            <input id="dni" name="dni" type="text" x-model="dni" @input="onDniInput()" @keydown.enter.prevent="verifyDni()"
                                maxlength="8" required autofocus autocomplete="off"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-mono tracking-wider font-semibold transition-all duration-200 outline-none
                                @error('dni') border-red-300 ring-2 ring-red-100 bg-red-50/20 @else border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600 bg-white @enderror"
                                placeholder="8 dígitos (Ej: 71234567)">
                        </div>

                        <button type="button" @click="verifyDni()" :disabled="isLoading || dni.length !== 8"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-white transition-all duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background-color: var(--ink, #0F172A);">
                            <template x-if="isLoading">
                                <div class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Buscando...</span>
                                </div>
                            </template>
                            <template x-if="!isLoading">
                                <div class="flex items-center gap-1.5">
                                    <i class="bi bi-search"></i>
                                    <span>Verificar</span>
                                </div>
                            </template>
                        </button>
                    </div>

                    {{-- Mensaje de Error del DNI en backend --}}
                    @error('dni')
                        <div class="mt-2.5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-start gap-2">
                            <i class="bi bi-x-circle-fill text-red-500 mt-0.5 flex-shrink-0"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    {{-- Mensajes dinámicos de Alpine --}}
                    <div x-show="errorMessage" x-transition.opacity class="mt-2.5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-start gap-2.5" x-cloak>
                        <i class="bi bi-exclamation-triangle-fill text-red-500 text-base mt-0.5 flex-shrink-0"></i>
                        <div class="leading-relaxed">
                            <p class="font-bold text-red-800">No autorizado para registro libre</p>
                            <p class="mt-0.5" x-text="errorMessage"></p>
                        </div>
                    </div>

                    {{-- Mensaje de DNI ya registrado --}}
                    <div x-show="alreadyRegistered" x-transition.opacity class="mt-2.5 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-start gap-2.5" x-cloak>
                        <i class="bi bi-info-circle-fill text-blue-600 text-base mt-0.5 flex-shrink-0"></i>
                        <div class="leading-relaxed flex-1">
                            <p class="font-bold text-blue-900">Usuario ya registrado</p>
                            <p class="mt-0.5" x-text="alreadyRegisteredMessage"></p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 mt-2 font-bold text-blue-700 hover:text-blue-900 underline">
                                <span>Ir a Iniciar Sesión</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Confirmación de Verificación Exitosa --}}
                    <div x-show="isVerified && !alreadyRegistered" x-transition.opacity class="mt-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between" x-cloak>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i>
                            <div>
                                <span class="font-bold">Identidad confirmada:</span>
                                <span class="font-semibold text-emerald-950" x-text="names"></span>
                                <template x-if="userRole">
                                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300" x-text="userRole"></span>
                                </template>
                            </div>
                        </div>
                        <i class="bi bi-shield-fill-check text-emerald-600 text-lg"></i>
                    </div>
                </div>

                {{-- Paso 2: Datos Personales Auto-completados y Correo Personal --}}
                <div x-show="isVerified && !alreadyRegistered" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-4 pt-1" x-cloak>

                    <div class="border-t border-slate-200 pt-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-purple-700 flex items-center gap-1.5 mb-3">
                            <i class="bi bi-person-fill-gear"></i>
                            <span>2. Completa tus datos personales y credenciales</span>
                        </p>
                    </div>

                    {{-- Nombres y Apellidos Completos (Auto-completados) --}}
                    <div>
                        <label for="names" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nombres y Apellidos Completos <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="bi bi-person text-slate-400"></i>
                            </div>
                            <input id="names" name="names" type="text" x-model="names" required
                                class="w-full pl-10 pr-4 py-2 rounded-xl border text-sm font-medium transition-all outline-none bg-slate-50/50 border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                placeholder="Nombres completos">
                        </div>
                        @error('names')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fila: Fecha de Nacimiento y Teléfono --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Fecha de Nacimiento (Auto-completada si existe) --}}
                        <div>
                            <label for="birthdate" class="block text-xs font-semibold text-slate-700 mb-1">
                                Fecha de Nacimiento
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bi bi-calendar3 text-slate-400"></i>
                                </div>
                                <input id="birthdate" name="birthdate" type="date" x-model="birthdate"
                                    class="w-full pl-10 pr-3 py-2 rounded-xl border text-sm font-medium transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600">
                            </div>
                            @error('birthdate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Teléfono / Celular --}}
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">
                                Teléfono / Celular
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bi bi-telephone text-slate-400"></i>
                                </div>
                                <input id="phone" name="phone" type="tel" x-model="phone"
                                    class="w-full pl-10 pr-3 py-2 rounded-xl border text-sm font-medium transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                    placeholder="Ej: 987654321">
                            </div>
                            @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Correo Electrónico Personal (Obligatorio) --}}
                    <div class="p-3.5 bg-purple-50/40 rounded-2xl border border-purple-200/80">
                        <label for="personal_email" class="block text-xs font-bold text-purple-950 mb-1 flex items-center justify-between">
                            <span>Correo Electrónico Personal <span class="text-red-500">*</span></span>
                            <span class="text-[10px] font-normal text-purple-700">Gmail, Outlook, etc.</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="bi bi-envelope-at text-purple-600 text-lg"></i>
                            </div>
                            <input id="personal_email" name="personal_email" type="email" x-model="personalEmail" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-medium transition-all outline-none bg-white
                                @error('personal_email') border-red-300 ring-2 ring-red-100 @else border-purple-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600 @enderror"
                                placeholder="tu_correo_personal@gmail.com">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Se utilizará como tu dirección personal de contacto y notificaciones directas.
                        </p>
                        @error('personal_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Subida Opcional de CV (PDF) --}}
                    <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200" x-data="{ cvFileName: '' }">
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
                                <span class="truncate font-medium text-slate-700" x-text="cvFileName || 'Seleccionar archivo PDF de tu CV'"></span>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 flex-shrink-0">
                                Examinar
                            </span>
                            <input id="cv_file" name="cv_file" type="file" accept=".pdf,application/pdf" class="hidden"
                                @change="cvFileName = $event.target.files[0]?.name || ''">
                        </label>
                        @error('cv_file')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Contraseña y Confirmación --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" x-data="{ showPass: false }">
                        {{-- Contraseña --}}
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                                Nueva Contraseña <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bi bi-lock text-slate-400"></i>
                                </div>
                                <input id="password" name="password" :type="showPass ? 'text' : 'password'" required autocomplete="new-password"
                                    class="w-full pl-10 pr-10 py-2 rounded-xl border text-sm transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                    placeholder="Mínimo 8 caracteres">
                                <button type="button" @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                    aria-label="Ver u ocultar contraseña">
                                    <i class="bi text-sm" :class="showPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        {{-- Confirmar Contraseña --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="bi bi-lock-fill text-slate-400"></i>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" :type="showPass ? 'text' : 'password'" required autocomplete="new-password"
                                    class="w-full pl-10 pr-3 py-2 rounded-xl border text-sm transition-all outline-none bg-white border-slate-200 focus:ring-2 focus:ring-purple-100 focus:border-purple-600"
                                    placeholder="Repite la contraseña">
                            </div>
                        </div>
                    </div>

                    {{-- Botón de Envío / Activación --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3.5 px-6 text-sm font-bold rounded-xl text-white transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                            style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 100%);">
                            <i class="bi bi-person-check-fill text-lg text-amber-400"></i>
                            <span>Completar Registro y Activar Cuenta</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-xs text-slate-500">
            Al registrarte aceptas las políticas institucionales y el
            <a href="{{ route('admin.regulations.index') }}" class="underline hover:text-slate-700 text-slate-600 font-semibold" target="_blank">
                Reglamento Interno
            </a>
            del IESTP Francisco Vigo Caballero.
        </p>
    </div>
</div>

@push('scripts')
<script>
    function registerForm(config) {
        return {
            dni: config.initialDni || '',
            names: config.initialNames || '',
            birthdate: config.initialBirthdate || '',
            personalEmail: config.initialPersonalEmail || '',
            phone: config.initialPhone || '',
            userRole: '',
            isLoading: false,
            isVerified: Boolean(config.initialNames || (config.initialDni && !config.hasDniError && config.hasServerError)),
            alreadyRegistered: false,
            alreadyRegisteredMessage: '',
            errorMessage: '',
            verifyUrl: config.verifyUrl,

            init() {
                if (this.dni.length === 8 && !this.isVerified && !config.hasDniError) {
                    this.verifyDni();
                }
            },

            onDniInput() {
                this.dni = this.dni.replace(/[^0-9]/g, '').slice(0, 8);
                this.errorMessage = '';
                this.alreadyRegistered = false;

                if (this.dni.length !== 8) {
                    this.isVerified = false;
                } else {
                    // Auto-verificar cuando se completan los 8 dígitos
                    this.verifyDni();
                }
            },

            async verifyDni() {
                if (!this.dni || this.dni.length !== 8) {
                    this.errorMessage = 'El DNI debe contener exactamente 8 dígitos.';
                    this.isVerified = false;
                    return;
                }

                this.isLoading = true;
                this.errorMessage = '';
                this.alreadyRegistered = false;

                try {
                    const response = await fetch(`${this.verifyUrl}?dni=${encodeURIComponent(this.dni)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        if (data.already_registered) {
                            this.alreadyRegistered = true;
                            this.alreadyRegisteredMessage = data.message;
                            this.isVerified = false;
                        } else {
                            this.isVerified = true;
                            this.names = data.user.names || this.names;
                            this.birthdate = data.user.birthdate || this.birthdate;
                            this.phone = data.user.phone || this.phone;
                            this.userRole = data.user.role || '';
                            if (!this.personalEmail && data.user.personal_email) {
                                this.personalEmail = data.user.personal_email;
                            }
                        }
                    } else {
                        this.isVerified = false;
                        this.errorMessage = data.message || 'El DNI ingresado no se encuentra en el padrón institucional.';
                    }
                } catch (err) {
                    this.isVerified = false;
                    this.errorMessage = 'Ocurrió un error al verificar el DNI. Inténtelo nuevamente.';
                } finally {
                    this.isLoading = false;
                }
            },

            handleSubmit(e) {
                if (!this.isVerified) {
                    e.preventDefault();
                    this.errorMessage = 'Debe verificar su número de DNI en el padrón institucional antes de continuar.';
                    return false;
                }
            }
        };
    }
</script>
@endpush
@endsection