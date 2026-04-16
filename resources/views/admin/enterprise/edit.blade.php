@extends('layouts.app')
@section('title', 'Configuración de Empresa - Panel Administrativo')
@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="enterpriseApp()">
    @include('admin.components.aside')

    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative">
        
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] shadow-sm backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="mr-3 sm:mr-4 text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-xl sm:text-2xl"></i>
                    </button>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">
                        Configuración de Empresa
                    </h1>
                </div>

                <div class="hidden sm:flex items-center text-sm font-medium text-gray-500">
                    <i class="bi bi-house-door mr-1"></i> Inicio
                    <i class="bi bi-chevron-right mx-2 text-xs text-gray-400"></i>
                    <span class="text-purple-600">Configuración de Empresa</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Mensaje de éxito -->
                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.remove()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                @endif

                <form action="{{ route('admin.enterprise.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Información Básica -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-100">
                            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                <i class="bi bi-building mr-2 text-purple-600"></i>
                                Información Básica de la Empresa
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6 space-y-4">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-hash mr-1"></i>RUC
                                    </label>
                                    <input type="text" 
                                           name="ruc" 
                                           value="{{ old('ruc', $enterprise->ruc) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('ruc') border-red-500 @enderror"
                                           placeholder="Ej: 20123456789">
                                    @error('ruc')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-building-fill mr-1"></i>Razón Social <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="company_name" 
                                           value="{{ old('company_name', $enterprise->company_name) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('company_name') border-red-500 @enderror"
                                           placeholder="Ej: Instituto Superior Tecnológico Francisco Vigo Caballero SAC"
                                           required>
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-shop mr-1"></i>Nombre Comercial
                                    </label>
                                    <input type="text" 
                                           name="trade_name" 
                                           value="{{ old('trade_name', $enterprise->trade_name) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: Instituto Francisco Vigo Caballero">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-briefcase-fill mr-1"></i>Sector Empresarial
                                    </label>
                                    <input type="text" 
                                           name="business_sector" 
                                           value="{{ old('business_sector', $enterprise->business_sector) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: Educación, Tecnología">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-chat-quote-fill mr-1"></i>Frase / Slogan
                                </label>
                                <input type="text" 
                                       name="phrase" 
                                       value="{{ old('phrase', $enterprise->phrase) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                       placeholder="Ej: Formando profesionales para el futuro">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-file-text-fill mr-1"></i>Descripción de la Empresa
                                </label>
                                <textarea name="description" 
                                          rows="3"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">{{ old('description', $enterprise->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Misión y Visión -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-100">
                            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                <i class="bi bi-eye-fill mr-2 text-blue-600"></i>
                                Misión y Visión
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-flag-fill mr-1"></i>Misión
                                </label>
                                <textarea name="mission" 
                                          rows="3"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">{{ old('mission', $enterprise->mission) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="bi bi-star-fill mr-1"></i>Visión
                                </label>
                                <textarea name="vision" 
                                          rows="3"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">{{ old('vision', $enterprise->vision) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Representante Legal y Ubicación -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                                <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                    <i class="bi bi-person-badge-fill mr-2 text-green-600"></i>
                                    Representante Legal
                                </h2>
                            </div>
                            
                            <div class="p-4 sm:p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-person-fill mr-1"></i>Nombre Completo
                                    </label>
                                    <input type="text" 
                                           name="legal_representative" 
                                           value="{{ old('legal_representative', $enterprise->legal_representative) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: Juan Pérez García">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-credit-card-fill mr-1"></i>DNI
                                    </label>
                                    <input type="text" 
                                           name="legal_representative_dni" 
                                           value="{{ old('legal_representative_dni', $enterprise->legal_representative_dni) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: 12345678">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-100">
                                <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                    <i class="bi bi-geo-alt-fill mr-2 text-orange-600"></i>
                                    Ubicación
                                </h2>
                            </div>
                            
                            <div class="p-4 sm:p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-pin-map-fill mr-1"></i>Dirección
                                    </label>
                                    <input type="text" 
                                           name="address" 
                                           value="{{ old('address', $enterprise->address) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: Av. Principal 123">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-building mr-1"></i>Ciudad
                                    </label>
                                    <input type="text" 
                                           name="city" 
                                           value="{{ old('city', $enterprise->city) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: Lima">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-100">
                            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                <i class="bi bi-telephone-fill mr-2 text-red-600"></i>
                                Información de Contacto
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-telephone-fill mr-1"></i>Teléfono Principal
                                    </label>
                                    <input type="text" 
                                           name="phone_number_1" 
                                           value="{{ old('phone_number_1', $enterprise->phone_number_1) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: +51 123 456 789">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-telephone-fill mr-1"></i>Teléfono Secundario
                                    </label>
                                    <input type="text" 
                                           name="phone_number_2" 
                                           value="{{ old('phone_number_2', $enterprise->phone_number_2) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                           placeholder="Ej: +51 987 654 321">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-envelope-fill mr-1"></i>Correo Electrónico
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           value="{{ old('email', $enterprise->email) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                           placeholder="Ej: info@instituto.edu">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-sky-50 to-blue-50 border-b border-gray-100">
                            <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                <i class="bi bi-share-fill mr-2 text-sky-600"></i>
                                Redes Sociales
                            </h2>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-facebook text-blue-600 mr-1"></i>Facebook
                                    </label>
                                    <input type="url" 
                                           name="facebook_link" 
                                           value="{{ old('facebook_link', $enterprise->facebook_link) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('facebook_link') border-red-500 @enderror"
                                           placeholder="https://facebook.com/instituto">
                                    @error('facebook_link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-linkedin text-blue-700 mr-1"></i>LinkedIn
                                    </label>
                                    <input type="url" 
                                           name="linkedin_link" 
                                           value="{{ old('linkedin_link', $enterprise->linkedin_link) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('linkedin_link') border-red-500 @enderror"
                                           placeholder="https://linkedin.com/company/instituto">
                                    @error('linkedin_link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-twitter-x text-gray-800 mr-1"></i>Twitter/X
                                    </label>
                                    <input type="url" 
                                           name="twitter_link" 
                                           value="{{ old('twitter_link', $enterprise->twitter_link) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('twitter_link') border-red-500 @enderror"
                                           placeholder="https://twitter.com/instituto">
                                    @error('twitter_link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-instagram text-pink-600 mr-1"></i>Instagram
                                    </label>
                                    <input type="url" 
                                           name="instagram_link" 
                                           value="{{ old('instagram_link', $enterprise->instagram_link) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('instagram_link') border-red-500 @enderror"
                                           placeholder="https://instagram.com/instituto">
                                    @error('instagram_link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-whatsapp text-green-600 mr-1"></i>WhatsApp
                                    </label>
                                    <input type="url" 
                                           name="whatsapp_link" 
                                           value="{{ old('whatsapp_link', $enterprise->whatsapp_link) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('whatsapp_link') border-red-500 @enderror"
                                           placeholder="https://wa.me/51999999999">
                                    @error('whatsapp_link')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Imágenes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-100">
                                <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                    <i class="bi bi-image-fill mr-2 text-purple-600"></i>
                                    Logo de la Empresa
                                </h2>
                            </div>
                            
                            <div class="p-4 sm:p-6 space-y-4">
                                @if($enterprise->logo_path)
                                <div class="flex justify-center p-4 bg-gray-50 rounded-lg">
                                    <img src="{{ $enterprise->logo_path }}" 
                                         alt="Logo actual" 
                                         class="h-32 object-contain">
                                </div>
                                @endif
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-upload mr-1"></i>Subir nuevo logo
                                    </label>
                                    <input type="file" 
                                           name="logo_path" 
                                           accept="image/jpeg,image/png,image/jpg,image/svg"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('logo_path') border-red-500 @enderror">
                                    <p class="mt-2 text-xs text-gray-500">
                                        Formatos permitidos: JPEG, PNG, JPG, SVG. Tamaño máximo: 2MB
                                    </p>
                                    @error('logo_path')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-100">
                                <h2 class="text-base sm:text-lg font-bold text-gray-800 flex items-center">
                                    <i class="bi bi-star-fill mr-2 text-indigo-600"></i>
                                    Favicon
                                </h2>
                            </div>
                            
                            <div class="p-4 sm:p-6 space-y-4">
                                @if($enterprise->favicon_path)
                                <div class="flex justify-center p-4 bg-gray-50 rounded-lg">
                                    <img src="{{ $enterprise->favicon_path }}" 
                                         alt="Favicon actual" 
                                         class="h-16 w-16 object-contain">
                                </div>
                                @endif
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="bi bi-upload mr-1"></i>Subir nuevo favicon
                                    </label>
                                    <input type="file" 
                                           name="favicon_path" 
                                           accept="image/x-icon,image/png,image/jpeg,image/jpg,image/svg"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('favicon_path') border-red-500 @enderror">
                                    <p class="mt-2 text-xs text-gray-500">
                                        Formatos permitidos: ICO, PNG, JPG, SVG. Tamaño máximo: 1MB
                                    </p>
                                    @error('favicon_path')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end pt-4">
                        <button type="reset" 
                                class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                            <i class="bi bi-arrow-counterclockwise mr-2"></i>Restablecer
                        </button>
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition font-medium shadow-lg shadow-purple-500/30">
                            <i class="bi bi-check-lg mr-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
    
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endpush


@endsection