@extends('layouts.app')
@section('title', 'Libro de Reclamaciones — IESTP Francisco Vigo Caballero')
@push('styles')
    <style>
        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .file-dropzone {
            transition: all 0.3s ease;
        }

        .file-dropzone:hover {
            border-color: #2563eb;
            background-color: rgba(37, 99, 235, 0.01);
        }
    </style>
@endpush

@section('content')
    @php
        $colorName = str_replace('bg-', '', $enterprise->color ?? 'blue-500');
    @endphp

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-16 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]">
        </div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-black mb-4 tracking-tight leading-tight">
                Libro de Reclamaciones Virtual
            </h1>
            <p class="text-base md:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto">
                Conforme a lo establecido en el Código de Protección y Defensa del Consumidor, nuestra institución cuenta con este canal digital para registrar tu reclamo o queja.
            </p>
        </div>
    </section>

    {{-- ===== MAIN GRID SECTION ===== --}}
    <section class="py-16 bg-slate-50/50" x-data="complaintsFormHandler()">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 max-w-7xl mx-auto">
                {{-- LEFT COLUMN: THE CLAIMS FORM (Col Span 2) --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-3xl flex gap-4 items-start shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md">
                                <i class="bi bi-check-lg text-xl"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-sm font-bold text-slate-800">¡Registro Exitoso!</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-3">
                                <span class="w-2 h-7 bg-blue-600 rounded-full"></span>
                                Formulario de Reclamos y Quejas
                            </h2>
                            <span class="text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100/50">
                                Ley N° 29571
                            </span>
                        </div>

                        <form action="{{ route('libro-de-reclamaciones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                                    1. Identificación del Consumidor Reclamante
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Full Names --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nombres y Apellidos</label>
                                        <input type="text" name="names" value="{{ old('names') }}" required
                                            placeholder="Ingresa tu nombre completo"
                                            class="w-full form-input bg-slate-50 border @error('names') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                        @error('names')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- DNI/CE Document --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Documento de Identidad (DNI/CE)</label>
                                        <input type="text" name="dni" value="{{ old('dni') }}" required
                                            placeholder="Número de documento"
                                            class="w-full form-input bg-slate-50 border @error('dni') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                        @error('dni')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Correo Electrónico</label>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            placeholder="correo@ejemplo.com"
                                            class="w-full form-input bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">
                                        @error('email')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Detalle del Reclamo --}}
                            <div class="pt-4 border-t border-slate-100">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                                    2. Detalles del Reclamo o Queja
                                </label>

                                <div class="grid grid-cols-1 gap-6">
                                    {{-- Subject / Type of Claim --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipo / Asunto</label>
                                        <select name="subject" required
                                            class="w-full form-input bg-slate-50 border @error('subject') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none">
                                            <option value="" disabled selected>Selecciona una opción</option>
                                            <option value="Reclamo" {{ old('subject') == 'Reclamo' ? 'selected' : '' }}>Reclamo (Disconformidad relacionada a los servicios)</option>
                                            <option value="Queja" {{ old('subject') == 'Queja' ? 'selected' : '' }}>Queja (Disconformidad no relacionada directamente a los servicios o malestar en la atención)</option>
                                        </select>
                                        @error('subject')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Message --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Descripción del Reclamo o Queja</label>
                                        <textarea name="message" rows="5" required
                                            placeholder="Detalla tu disconformidad de la forma más clara posible..."
                                            class="w-full form-input bg-slate-50 border @error('message') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none">{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Attachment --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Adjuntar archivo sustentatorio (Opcional)</label>
                                        <div class="file-dropzone border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center relative cursor-pointer">
                                            <input type="file" name="file_path" @change="handleFileUpload($event)" accept=".pdf,.jpg,.jpeg,.png"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 shadow-sm">
                                                    <i class="bi bi-cloud-arrow-up text-xl"></i>
                                                </div>
                                                <div class="text-xs">
                                                    <span class="font-bold text-blue-600 hover:text-blue-700">Haz clic para cargar</span> o arrastra tu archivo aquí
                                                </div>
                                                <p class="text-[10px] text-slate-400">PDF, JPG, JPEG, PNG (Máx. 10MB)</p>
                                            </div>
                                        </div>

                                        <template x-if="uploadedFile">
                                            <div class="mt-3 p-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between">
                                                <div class="flex items-center gap-2.5">
                                                    <i class="bi bi-file-earmark-check text-blue-500 text-lg"></i>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-800" x-text="uploadedFile.name"></p>
                                                        <p class="text-[10px] text-slate-400" x-text="uploadedFile.size"></p>
                                                    </div>
                                                </div>
                                                <button type="button" @click="clearFileUpload()" class="text-slate-400 hover:text-red-500 p-1 transition-colors">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </template>
                                        @error('file_path')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="pt-6 border-t border-slate-100 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-md shadow-blue-500/10 hover:shadow-lg transition-all">
                                    <i class="bi bi-send-fill"></i>
                                    Registrar Reclamación
                                </button>
                            </div>

                        </form>
                    </div>

                </div>

                {{-- RIGHT COLUMN: SIDEBAR DETAILS (Col Span 1) --}}
                <div class="space-y-8">

                    {{-- Download official complaints book PDF --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <i class="bi bi-file-earmark-pdf-fill text-blue-600 text-lg"></i>
                            Formato Físico (PDF)
                        </h3>
                        
                        @if($enterprise && $enterprise->complaints_book_path)
                            <div class="space-y-4">
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Si lo prefieres, puedes descargar la hoja de reclamación física oficial en formato PDF, llenarla y hacérnosla llegar.
                                </p>
                                <a href="{{ $enterprise->complaints_book_path }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-3 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-md hover:shadow-lg shadow-red-500/20">
                                    <i class="bi bi-download"></i> Descargar Libro de Reclamaciones
                                </a>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">
                                El formato en PDF no está disponible en este momento.
                            </p>
                        @endif
                    </div>

                    {{-- General Instructions --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5 pb-3 border-b border-slate-100">
                            <i class="bi bi-info-circle text-blue-600 text-lg"></i>
                            Definiciones Útiles
                        </h3>

                        <div class="space-y-3 text-xs">
                            <div>
                                <h4 class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Reclamo
                                </h4>
                                <p class="text-slate-500 mt-1 leading-relaxed pl-3">
                                    Disconformidad relacionada a los servicios brindados por la institución académica.
                                </p>
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Queja
                                </h4>
                                <p class="text-slate-500 mt-1 leading-relaxed pl-3">
                                    Disconformidad que no se encuentra relacionada directamente con el servicio académico, sino con el malestar respecto a la atención al cliente o personal.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Mesa de Partes Shortcut --}}
                    <div class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-6 text-white space-y-4 shadow-xl">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                            <i class="bi bi-send-fill text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold">¿Tienes un trámite académico?</h4>
                            <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                Para solicitudes de certificados, FUT, reingreso u otros, utiliza nuestra Mesa de Partes Virtual.
                            </p>
                        </div>
                        <a href="{{ route('mesa-de-partes') }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20">
                            Ir a Mesa de Partes <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function complaintsFormHandler() {
            return {
                uploadedFile: null,

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > 10 * 1024 * 1024) {
                            alert("El archivo supera el tamaño máximo permitido (10MB).");
                            event.target.value = '';
                            return;
                        }
                        this.uploadedFile = {
                            name: file.name,
                            size: (file.size / 1024 / 1024).toFixed(2) + ' MB'
                        };
                    }
                },

                clearFileUpload() {
                    this.uploadedFile = null;
                    const fileInput = document.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.value = '';
                    }
                }
            }
        }
    </script>
@endpush
