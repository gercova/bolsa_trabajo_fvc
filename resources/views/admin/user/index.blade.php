@extends('layouts.app')
@section('title', 'Configuración de Empresa - Panel Administrativo')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    [x-cloak] { display: none !important; }

    :root {
        --brand-purple: #7C3AED;
        --brand-indigo: #4F46E5;
        --brand-violet: #8B5CF6;
        --surface: #FAFAFA;
        --card: #FFFFFF;
        --border: #E5E7EB;
        --border-focus: #7C3AED;
        --text-primary: #111827;
        --text-secondary: #6B7280;
        --text-muted: #9CA3AF;
    }

    body, #dashboard-container { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Section Nav ─────────────────────────────────────── */
    .section-nav { position: sticky; top: 80px; }
    .nav-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #D1D5DB;
        transition: all .25s ease;
        flex-shrink: 0;
    }
    .nav-item.active .nav-dot { background: var(--brand-purple); transform: scale(1.4); }
    .nav-item.active .nav-label { color: var(--brand-purple); font-weight: 700; }
    .nav-item { cursor: pointer; transition: all .2s; }
    .nav-item:hover .nav-dot { background: var(--brand-violet); }
    .nav-item:hover .nav-label { color: var(--brand-purple); }

    /* ── Cards ───────────────────────────────────────────── */
    .form-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.04);
        transition: box-shadow .3s;
        scroll-margin-top: 90px;
    }
    .form-card:hover { box-shadow: 0 4px 24px rgba(124,58,237,.08); }
    .card-header {
        padding: 18px 28px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 12px;
    }
    .card-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .card-body { padding: 24px 28px; }

    /* ── Inputs ───────────────────────────────────────────── */
    .field-group { display: flex; flex-direction: column; gap: 6px; }
    .field-label {
        font-size: 13px; font-weight: 600;
        color: var(--text-primary);
        display: flex; align-items: center; gap: 6px;
    }
    .field-label .required { color: #EF4444; }
    .input-wrap { position: relative; }
    .input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); font-size: 15px; pointer-events: none;
        z-index: 1;
    }
    .input-icon-right {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); font-size: 12px;
        font-family: 'DM Mono', monospace;
        pointer-events: none;
    }
    .field-input {
        width: 100%;
        padding: 11px 14px 11px 42px;
        font-size: 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #FAFAFA;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        color: var(--text-primary);
        transition: border-color .2s, background .2s, box-shadow .2s;
        outline: none;
    }
    .field-input:focus {
        background: #fff;
        border-color: var(--border-focus);
        box-shadow: 0 0 0 4px rgba(124,58,237,.1);
    }
    .field-input.no-icon { padding-left: 14px; }
    .field-input.has-right { padding-right: 50px; }
    .field-input.is-error { border-color: #EF4444; background: #FEF2F2; }
    .field-input.is-error:focus { box-shadow: 0 0 0 4px rgba(239,68,68,.1); }
    textarea.field-input { resize: vertical; min-height: 100px; padding-top: 12px; }
    .error-msg { font-size: 12px; color: #EF4444; display: flex; align-items: center; gap: 4px; }

    /* ── Social Input Prefix ─────────────────────────────── */
    .social-input-wrap { display: flex; border-radius: 12px; overflow: hidden; border: 1.5px solid var(--border); background: #FAFAFA; transition: border-color .2s, box-shadow .2s; }
    .social-input-wrap:focus-within { border-color: var(--border-focus); box-shadow: 0 0 0 4px rgba(124,58,237,.1); background: #fff; }
    .social-prefix {
        padding: 0 12px;
        background: #F3F4F6;
        border-right: 1.5px solid var(--border);
        display: flex; align-items: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .social-field {
        flex: 1; padding: 11px 14px;
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: transparent; border: none; outline: none;
        color: var(--text-primary);
        min-width: 0;
    }
    .social-field.is-error { color: #EF4444; }

    /* ── Image Upload Zones ──────────────────────────────── */
    .upload-zone {
        border: 2px dashed #D1D5DB;
        border-radius: 16px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all .25s;
        position: relative;
        background: #FAFAFA;
    }
    .upload-zone:hover, .upload-zone.dragging {
        border-color: var(--brand-purple);
        background: rgba(124,58,237,.04);
    }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .upload-preview {
        width: 100%; display: flex; justify-content: center; margin-bottom: 16px;
    }
    .upload-preview img { max-height: 110px; max-width: 100%; object-fit: contain; border-radius: 10px; }
    .upload-icon { font-size: 32px; color: #D1D5DB; margin-bottom: 10px; }
    .upload-label { font-size: 13px; color: var(--text-secondary); }
    .upload-label span { color: var(--brand-purple); font-weight: 600; }
    .upload-hint { font-size: 11px; color: var(--text-muted); margin-top: 4px; }
    .upload-filename {
        font-size: 12px; font-family: 'DM Mono', monospace;
        color: var(--brand-purple); margin-top: 8px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        max-width: 200px; margin-inline: auto;
    }

    /* ── Floating Save ───────────────────────────────────── */
    .save-bar {
        position: sticky; bottom: 0; z-index: 20;
        background: rgba(255,255,255,.85);
        backdrop-filter: blur(16px);
        border-top: 1px solid var(--border);
        padding: 14px 28px;
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px;
    }
    .btn-reset {
        padding: 10px 22px; border-radius: 12px;
        background: #F3F4F6; color: var(--text-secondary);
        font-size: 14px; font-weight: 600;
        border: 1.5px solid var(--border);
        cursor: pointer; transition: all .2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-reset:hover { background: #E5E7EB; color: var(--text-primary); }
    .btn-save {
        padding: 10px 28px; border-radius: 12px;
        background: linear-gradient(135deg, var(--brand-purple) 0%, var(--brand-indigo) 100%);
        color: #fff;
        font-size: 14px; font-weight: 700;
        border: none; cursor: pointer;
        box-shadow: 0 4px 18px rgba(124,58,237,.35);
        transition: all .2s;
        display: flex; align-items: center; gap: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-save:hover { box-shadow: 0 6px 24px rgba(124,58,237,.45); transform: translateY(-1px); }
    .btn-save:active { transform: translateY(0); }

    /* ── Alert ───────────────────────────────────────────── */
    @keyframes slideDown { from { opacity:0; transform:translateY(-8px);} to { opacity:1; transform:translateY(0);} }
    .alert-success { animation: slideDown .35s ease; }

    /* ── Scrollbar ───────────────────────────────────────── */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 1023px) {
        .card-body { padding: 20px; }
        .card-header { padding: 16px 20px; }
        .save-bar { padding: 12px 20px; }
    }
</style>
@endpush

@section('content')
<div id="dashboard-container" class="flex w-full bg-gray-50 font-sans text-gray-900 min-h-[calc(100vh-64px)]" x-data="enterpriseApp()">
    @include('admin.components.aside')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 relative">

        <!-- Page Header -->
        <header class="bg-white border-b border-gray-200 sticky top-[64px] lg:top-0 z-[30] backdrop-blur-md bg-white/90">
            <div class="px-5 sm:px-8 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()" class="text-gray-500 hover:text-purple-600 hover:bg-purple-50 p-2 rounded-lg transition-colors lg:hidden">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight leading-none">
                            Configuración de Empresa
                        </h1>
                        <p class="text-xs text-gray-400 mt-0.5 hidden sm:block">
                            Gestiona la identidad y datos de tu institución
                        </p>
                    </div>
                </div>
                <nav class="hidden sm:flex items-center text-xs font-medium text-gray-400 gap-1.5">
                    <i class="bi bi-house-door"></i>
                    <span>Inicio</span>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                    <span class="text-purple-600 font-semibold">Empresa</span>
                </nav>
            </div>
        </header>

        <!-- Body -->
        <div class="flex-1 flex overflow-hidden">

            <!-- ── Section Navigator (Desktop) ── -->
            <aside class="hidden xl:block w-52 flex-shrink-0 p-6">
                <div class="section-nav">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Secciones</p>
                    <div class="flex flex-col gap-1" id="sectionNav">
                        <div class="nav-item active flex items-center gap-3 px-3 py-2.5 rounded-xl" onclick="scrollToSection('sec-basic')">
                            <div class="nav-dot"></div>
                            <span class="nav-label text-sm text-gray-500 transition-colors">Información Básica</span>
                        </div>
                        <div class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl" onclick="scrollToSection('sec-mv')">
                            <div class="nav-dot"></div>
                            <span class="nav-label text-sm text-gray-500 transition-colors">Misión y Visión</span>
                        </div>
                        <div class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl" onclick="scrollToSection('sec-legal')">
                            <div class="nav-dot"></div>
                            <span class="nav-label text-sm text-gray-500 transition-colors">Representante</span>
                        </div>
                        <div class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl" onclick="scrollToSection('sec-contact')">
                            <div class="nav-dot"></div>
                            <span class="nav-label text-sm text-gray-500 transition-colors">Contacto</span>
                        </div>
                        <div class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl" onclick="scrollToSection('sec-social')">
                            <div class="nav-dot"></div>
                            <span class="nav-label text-sm text-gray-500 transition-colors">Redes Sociales</span>
                        </div>
                        <div class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl" onclick="scrollToSection('sec-images')">
                            <div class="nav-dot"></div>
                            <span class="nav-label text-sm text-gray-500 transition-colors">Imágenes</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ── Form Area ── -->
            <main class="flex-1 overflow-y-auto custom-scrollbar" id="formMain">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 pb-0">

                    <!-- Success Alert -->
                    @if(session('success'))
                    <div class="alert-success flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4 mb-6">
                        <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-check-lg text-emerald-600 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm">¡Guardado correctamente!</p>
                            <p class="text-xs text-emerald-600 mt-0.5">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.closest('.alert-success').remove()" class="text-emerald-400 hover:text-emerald-600 transition p-1">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                    @endif

                    <form action="{{ route('admin.enterprise.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 pb-24">
                        @csrf
                        @method('PUT')

                        {{-- ══════════════════════════════════════════
                             SECCIÓN 1 · INFORMACIÓN BÁSICA
                        ══════════════════════════════════════════ --}}
                        <div class="form-card" id="sec-basic">
                            <div class="card-header">
                                <div class="card-icon" style="background:#EDE9FE;">
                                    <i class="bi bi-building-fill" style="color:#7C3AED;"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-900">Información Básica</h2>
                                    <p class="text-xs text-gray-400">Datos principales de tu institución</p>
                                </div>
                            </div>
                            <div class="card-body space-y-5">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <!-- RUC -->
                                    <div class="field-group">
                                        <label class="field-label"><i class="bi bi-upc-scan"></i> RUC</label>
                                        <div class="input-wrap">
                                            <i class="input-icon bi bi-hash"></i>
                                            <input type="text" name="ruc"
                                                   value="{{ old('ruc', $enterprise->ruc) }}"
                                                   maxlength="11"
                                                   class="field-input has-right @error('ruc') is-error @enderror"
                                                   placeholder="20123456789"
                                                   oninput="updateCounter(this,'cnt-ruc')">
                                            <span class="input-icon-right" id="cnt-ruc">{{ strlen(old('ruc', $enterprise->ruc ?? '')) }}/11</span>
                                        </div>
                                        @error('ruc')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Razón Social -->
                                    <div class="field-group">
                                        <label class="field-label">
                                            <i class="bi bi-building"></i> Razón Social <span class="required">*</span>
                                        </label>
                                        <div class="input-wrap">
                                            <i class="input-icon bi bi-building-fill"></i>
                                            <input type="text" name="company_name"
                                                   value="{{ old('company_name', $enterprise->company_name) }}"
                                                   maxlength="150" required
                                                   class="field-input has-right @error('company_name') is-error @enderror"
                                                   placeholder="Instituto Superior Tecnológico..."
                                                   oninput="updateCounter(this,'cnt-cn')">
                                            <span class="input-icon-right" id="cnt-cn">{{ strlen(old('company_name', $enterprise->company_name ?? '')) }}/150</span>
                                        </div>
                                        @error('company_name')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Nombre Comercial -->
                                    <div class="field-group">
                                        <label class="field-label"><i class="bi bi-shop"></i> Nombre Comercial</label>
                                        <div class="input-wrap">
                                            <i class="input-icon bi bi-tag"></i>
                                            <input type="text" name="trade_name"
                                                   value="{{ old('trade_name', $enterprise->trade_name) }}"
                                                   maxlength="150"
                                                   class="field-input has-right"
                                                   placeholder="Ej: Instituto Francisco Vigo"
                                                   oninput="updateCounter(this,'cnt-tn')">
                                            <span class="input-icon-right" id="cnt-tn">{{ strlen(old('trade_name', $enterprise->trade_name ?? '')) }}/150</span>
                                        </div>
                                    </div>

                                    <!-- Sector -->
                                    <div class="field-group">
                                        <label class="field-label"><i class="bi bi-diagram-3"></i> Sector Empresarial</label>
                                        <div class="input-wrap">
                                            <i class="input-icon bi bi-briefcase"></i>
                                            <input type="text" name="business_sector"
                                                   value="{{ old('business_sector', $enterprise->business_sector) }}"
                                                   maxlength="255"
                                                   class="field-input"
                                                   placeholder="Ej: Educación, Tecnología">
                                        </div>
                                    </div>
                                </div>

                                <!-- Slogan -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-chat-quote"></i> Frase / Slogan</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-stars"></i>
                                        <input type="text" name="phrase"
                                               value="{{ old('phrase', $enterprise->phrase) }}"
                                               maxlength="255"
                                               class="field-input has-right"
                                               placeholder="Ej: Formando profesionales para el futuro"
                                               oninput="updateCounter(this,'cnt-phrase')">
                                        <span class="input-icon-right" id="cnt-phrase">{{ strlen(old('phrase', $enterprise->phrase ?? '')) }}/255</span>
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-file-text"></i> Descripción</label>
                                    <textarea name="description" rows="4"
                                              class="field-input no-icon"
                                              placeholder="Describe brevemente tu institución, sus valores y lo que la hace especial...">{{ old('description', $enterprise->description) }}</textarea>
                                </div>

                            </div>
                        </div>

                        {{-- ══════════════════════════════════════════
                             SECCIÓN 2 · MISIÓN Y VISIÓN
                        ══════════════════════════════════════════ --}}
                        <div class="form-card" id="sec-mv">
                            <div class="card-header">
                                <div class="card-icon" style="background:#DBEAFE;">
                                    <i class="bi bi-eye-fill" style="color:#2563EB;"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-900">Misión y Visión</h2>
                                    <p class="text-xs text-gray-400">El propósito y futuro de tu institución</p>
                                </div>
                            </div>
                            <div class="card-body grid sm:grid-cols-2 gap-5">
                                <div class="field-group">
                                    <label class="field-label">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-[10px] font-bold">M</span>
                                        Misión
                                    </label>
                                    <textarea name="mission" rows="5"
                                              class="field-input no-icon"
                                              placeholder="¿Cuál es el propósito central de tu institución? ¿Qué hace y para quién?">{{ old('mission', $enterprise->mission) }}</textarea>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold">V</span>
                                        Visión
                                    </label>
                                    <textarea name="vision" rows="5"
                                              class="field-input no-icon"
                                              placeholder="¿A dónde quiere llegar tu institución en el futuro?">{{ old('vision', $enterprise->vision) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ══════════════════════════════════════════
                             SECCIÓN 3 · REPRESENTANTE LEGAL + UBICACIÓN
                        ══════════════════════════════════════════ --}}
                        <div class="form-card" id="sec-legal">
                            <div class="card-header">
                                <div class="card-icon" style="background:#D1FAE5;">
                                    <i class="bi bi-person-badge-fill" style="color:#059669;"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-900">Representante Legal y Ubicación</h2>
                                    <p class="text-xs text-gray-400">Datos del representante y sede</p>
                                </div>
                            </div>
                            <div class="card-body grid sm:grid-cols-2 gap-5">
                                <!-- Nombre Rep -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-person-fill"></i> Nombre del Representante</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-person"></i>
                                        <input type="text" name="legal_representative"
                                               value="{{ old('legal_representative', $enterprise->legal_representative) }}"
                                               maxlength="100"
                                               class="field-input"
                                               placeholder="Ej: Juan Pérez García">
                                    </div>
                                </div>
                                <!-- DNI -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-credit-card"></i> DNI del Representante</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-credit-card-fill"></i>
                                        <input type="text" name="legal_representative_dni"
                                               value="{{ old('legal_representative_dni', $enterprise->legal_representative_dni) }}"
                                               maxlength="10"
                                               class="field-input has-right @error('legal_representative_dni') is-error @enderror"
                                               placeholder="Ej: 12345678"
                                               oninput="updateCounter(this,'cnt-dni')">
                                        <span class="input-icon-right" id="cnt-dni">{{ strlen(old('legal_representative_dni', $enterprise->legal_representative_dni ?? '')) }}/10</span>
                                    </div>
                                    @error('legal_representative_dni')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>
                                <!-- Dirección -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-pin-map"></i> Dirección</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-geo-alt"></i>
                                        <input type="text" name="address"
                                               value="{{ old('address', $enterprise->address) }}"
                                               maxlength="100"
                                               class="field-input"
                                               placeholder="Ej: Av. Principal 123">
                                    </div>
                                </div>
                                <!-- Ciudad -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-building"></i> Ciudad</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-geo"></i>
                                        <input type="text" name="city"
                                               value="{{ old('city', $enterprise->city) }}"
                                               maxlength="150"
                                               class="field-input"
                                               placeholder="Ej: Trujillo">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ══════════════════════════════════════════
                             SECCIÓN 4 · CONTACTO
                        ══════════════════════════════════════════ --}}
                        <div class="form-card" id="sec-contact">
                            <div class="card-header">
                                <div class="card-icon" style="background:#FEE2E2;">
                                    <i class="bi bi-telephone-fill" style="color:#DC2626;"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-900">Información de Contacto</h2>
                                    <p class="text-xs text-gray-400">Canales para que te contacten</p>
                                </div>
                            </div>
                            <div class="card-body grid sm:grid-cols-3 gap-5">
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-telephone"></i> Teléfono Principal</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-telephone-fill"></i>
                                        <input type="text" name="phone_number_1"
                                               value="{{ old('phone_number_1', $enterprise->phone_number_1) }}"
                                               maxlength="20"
                                               class="field-input"
                                               placeholder="+51 123 456 789">
                                    </div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-telephone"></i> Teléfono Secundario</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-telephone"></i>
                                        <input type="text" name="phone_number_2"
                                               value="{{ old('phone_number_2', $enterprise->phone_number_2) }}"
                                               maxlength="20"
                                               class="field-input"
                                               placeholder="+51 987 654 321">
                                    </div>
                                </div>
                                <div class="field-group sm:col-span-1">
                                    <label class="field-label"><i class="bi bi-envelope"></i> Correo Electrónico</label>
                                    <div class="input-wrap">
                                        <i class="input-icon bi bi-envelope-fill"></i>
                                        <input type="email" name="email"
                                               value="{{ old('email', $enterprise->email) }}"
                                               class="field-input @error('email') is-error @enderror"
                                               placeholder="info@instituto.edu">
                                    </div>
                                    @error('email')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ══════════════════════════════════════════
                             SECCIÓN 5 · REDES SOCIALES
                        ══════════════════════════════════════════ --}}
                        <div class="form-card" id="sec-social">
                            <div class="card-header">
                                <div class="card-icon" style="background:#E0F2FE;">
                                    <i class="bi bi-share-fill" style="color:#0284C7;"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-900">Redes Sociales</h2>
                                    <p class="text-xs text-gray-400">Links de tus perfiles en redes</p>
                                </div>
                            </div>
                            <div class="card-body grid sm:grid-cols-2 gap-5">

                                <!-- Facebook -->
                                <div class="field-group">
                                    <label class="field-label" style="color:#1877F2;">
                                        <i class="bi bi-facebook"></i> Facebook
                                    </label>
                                    <div class="social-input-wrap @error('facebook_link') border-red-400 @enderror">
                                        <span class="social-prefix" style="color:#1877F2;"><i class="bi bi-facebook"></i></span>
                                        <input type="url" name="facebook_link"
                                               value="{{ old('facebook_link', $enterprise->facebook_link) }}"
                                               class="social-field @error('facebook_link') is-error @enderror"
                                               placeholder="https://facebook.com/tu-pagina">
                                    </div>
                                    @error('facebook_link')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                                <!-- LinkedIn -->
                                <div class="field-group">
                                    <label class="field-label" style="color:#0A66C2;">
                                        <i class="bi bi-linkedin"></i> LinkedIn
                                    </label>
                                    <div class="social-input-wrap @error('linkedin_link') border-red-400 @enderror">
                                        <span class="social-prefix" style="color:#0A66C2;"><i class="bi bi-linkedin"></i></span>
                                        <input type="url" name="linkedin_link"
                                               value="{{ old('linkedin_link', $enterprise->linkedin_link) }}"
                                               class="social-field @error('linkedin_link') is-error @enderror"
                                               placeholder="https://linkedin.com/company/...">
                                    </div>
                                    @error('linkedin_link')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                                <!-- Twitter/X -->
                                <div class="field-group">
                                    <label class="field-label" style="color:#111827;">
                                        <i class="bi bi-twitter-x"></i> Twitter / X
                                    </label>
                                    <div class="social-input-wrap @error('twitter_link') border-red-400 @enderror">
                                        <span class="social-prefix" style="color:#111827;"><i class="bi bi-twitter-x"></i></span>
                                        <input type="url" name="twitter_link"
                                               value="{{ old('twitter_link', $enterprise->twitter_link) }}"
                                               class="social-field @error('twitter_link') is-error @enderror"
                                               placeholder="https://twitter.com/tu-cuenta">
                                    </div>
                                    @error('twitter_link')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                                <!-- Instagram -->
                                <div class="field-group">
                                    <label class="field-label" style="color:#E1306C;">
                                        <i class="bi bi-instagram"></i> Instagram
                                    </label>
                                    <div class="social-input-wrap @error('instagram_link') border-red-400 @enderror">
                                        <span class="social-prefix" style="color:#E1306C;"><i class="bi bi-instagram"></i></span>
                                        <input type="url" name="instagram_link"
                                               value="{{ old('instagram_link', $enterprise->instagram_link) }}"
                                               class="social-field @error('instagram_link') is-error @enderror"
                                               placeholder="https://instagram.com/tu-perfil">
                                    </div>
                                    @error('instagram_link')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                                <!-- WhatsApp -->
                                <div class="field-group sm:col-span-2">
                                    <label class="field-label" style="color:#25D366;">
                                        <i class="bi bi-whatsapp"></i> WhatsApp
                                    </label>
                                    <div class="social-input-wrap @error('whatsapp_link') border-red-400 @enderror">
                                        <span class="social-prefix" style="color:#25D366;"><i class="bi bi-whatsapp"></i></span>
                                        <input type="url" name="whatsapp_link"
                                               value="{{ old('whatsapp_link', $enterprise->whatsapp_link) }}"
                                               class="social-field @error('whatsapp_link') is-error @enderror"
                                               placeholder="https://wa.me/51999999999">
                                    </div>
                                    @error('whatsapp_link')<p class="error-msg"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        {{-- ══════════════════════════════════════════
                             SECCIÓN 6 · IMÁGENES
                        ══════════════════════════════════════════ --}}
                        <div class="form-card" id="sec-images">
                            <div class="card-header">
                                <div class="card-icon" style="background:#F3E8FF;">
                                    <i class="bi bi-images" style="color:#9333EA;"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-gray-900">Imágenes de Marca</h2>
                                    <p class="text-xs text-gray-400">Logo y favicon de tu institución</p>
                                </div>
                            </div>
                            <div class="card-body grid sm:grid-cols-2 gap-6">

                                <!-- Logo -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-image"></i> Logo de la Empresa</label>
                                    <div class="upload-zone" id="logoZone"
                                         ondragover="handleDragOver(event, 'logoZone')"
                                         ondragleave="handleDragLeave('logoZone')"
                                         ondrop="handleDrop(event, 'logoZone', 'logoPreview', 'logoFilename')">
                                        <input type="file" name="logo_path"
                                               accept="image/jpeg,image/png,image/jpg,image/svg+xml"
                                               onchange="previewImage(this, 'logoPreview', 'logoFilename')">
                                        <div class="upload-preview" id="logoPreview">
                                            @if($enterprise->logo_path)
                                                <img src="{{ $enterprise->logo_path }}" alt="Logo actual">
                                            @endif
                                        </div>
                                        @if(!$enterprise->logo_path)
                                        <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                        @endif
                                        <p class="upload-label">
                                            <span>Haz clic</span> o arrastra tu logo aquí
                                        </p>
                                        <p class="upload-hint">JPEG · PNG · JPG · SVG &nbsp;·&nbsp; Máx. 2 MB</p>
                                        <p class="upload-filename hidden" id="logoFilename"></p>
                                    </div>
                                    @error('logo_path')<p class="error-msg mt-1"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                                <!-- Favicon -->
                                <div class="field-group">
                                    <label class="field-label"><i class="bi bi-star"></i> Favicon</label>
                                    <div class="upload-zone" id="faviconZone"
                                         ondragover="handleDragOver(event, 'faviconZone')"
                                         ondragleave="handleDragLeave('faviconZone')"
                                         ondrop="handleDrop(event, 'faviconZone', 'faviconPreview', 'faviconFilename')">
                                        <input type="file" name="favicon_path"
                                               accept="image/x-icon,image/png,image/jpeg,image/jpg,image/svg+xml"
                                               onchange="previewImage(this, 'faviconPreview', 'faviconFilename')">
                                        <div class="upload-preview" id="faviconPreview">
                                            @if($enterprise->favicon_path)
                                                <img src="{{ $enterprise->favicon_path }}" alt="Favicon actual" style="max-height:64px;width:64px;">
                                            @endif
                                        </div>
                                        @if(!$enterprise->favicon_path)
                                        <div class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                        @endif
                                        <p class="upload-label">
                                            <span>Haz clic</span> o arrastra tu favicon aquí
                                        </p>
                                        <p class="upload-hint">ICO · PNG · JPG · SVG &nbsp;·&nbsp; Máx. 1 MB</p>
                                        <p class="upload-filename hidden" id="faviconFilename"></p>
                                    </div>
                                    @error('favicon_path')<p class="error-msg mt-1"><i class="bi bi-exclamation-circle-fill"></i>{{ $message }}</p>@enderror
                                </div>

                            </div>
                        </div>

                        <!-- ── Floating Save Bar ── -->
                        <div class="save-bar">
                            <p class="text-xs text-gray-400 hidden sm:block">
                                <i class="bi bi-info-circle mr-1"></i>
                                Los cambios se aplican de inmediato al guardar
                            </p>
                            <div class="flex gap-3 ml-auto">
                                <button type="reset" class="btn-reset" onclick="resetPreviews()">
                                    <i class="bi bi-arrow-counterclockwise mr-1.5"></i>Restablecer
                                </button>
                                <button type="submit" class="btn-save">
                                    <i class="bi bi-check-lg"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </main>

        </div><!-- end body flex -->
    </div><!-- end main content -->
</div>

@push('scripts')
<script>
/* ── Counter ──────────────────────────────────────────────── */
function updateCounter(input, counterId) {
    const el = document.getElementById(counterId);
    if (!el) return;
    const max = input.maxLength;
    const len = input.value.length;
    el.textContent = `${len}/${max}`;
    el.style.color = len >= max * 0.9 ? '#EF4444' : '';
}

/* ── Image Preview ────────────────────────────────────────── */
function previewImage(input, previewId, filenameId) {
    const file = input.files[0];
    if (!file) return;
    const preview = document.getElementById(previewId);
    const filename = document.getElementById(filenameId);

    const reader = new FileReader();
    reader.onload = e => {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);

    if (filename) {
        filename.textContent = file.name;
        filename.classList.remove('hidden');
    }
}

/* ── Drag & Drop ──────────────────────────────────────────── */
function handleDragOver(e, zoneId) {
    e.preventDefault();
    document.getElementById(zoneId).classList.add('dragging');
}
function handleDragLeave(zoneId) {
    document.getElementById(zoneId).classList.remove('dragging');
}
function handleDrop(e, zoneId, previewId, filenameId) {
    e.preventDefault();
    const zone = document.getElementById(zoneId);
    zone.classList.remove('dragging');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const input = zone.querySelector('input[type="file"]');
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    previewImage(input, previewId, filenameId);
}

/* ── Reset previews on form reset ────────────────────────── */
function resetPreviews() {
    ['logoPreview','faviconPreview'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = '';
    });
    ['logoFilename','faviconFilename'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.textContent = ''; el.classList.add('hidden'); }
    });
}

/* ── Section Navigator ────────────────────────────────────── */
function scrollToSection(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

const sections = ['sec-basic','sec-mv','sec-legal','sec-contact','sec-social','sec-images'];
const navItems = document.querySelectorAll('#sectionNav .nav-item');

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const idx = sections.indexOf(entry.target.id);
            navItems.forEach((item, i) => item.classList.toggle('active', i === idx));
        }
    });
}, { threshold: 0.35 });

sections.forEach(id => {
    const el = document.getElementById(id);
    if (el) observer.observe(el);
});
</script>
@endpush

@endsection