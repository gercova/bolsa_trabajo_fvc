@extends('layouts.app')
@section('title', 'Libro de Reclamaciones — IESTP Francisco Vigo Caballero')
@push('styles')
@endpush
@section('content')
    @php
        $colorName = str_replace('bg-', '', $enterprise->color ?? 'blue-500');
    @endphp

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white py-16 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]">
        </div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-{{ $colorName }}/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight leading-tight">
                Libro de Reclamaciones
            </h1>
            <p class="text-base md:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto">
                Conforme a lo establecido en el Código de Protección y Defensa del Consumidor, nuestra institución cuenta con un Libro de Reclamaciones Virtual a su disposición.
            </p>
        </div>
    </section>

    {{-- ===== CONTENT SECTION ===== --}}
    <section class="py-16 bg-slate-50/50">
        <div class="container mx-auto px-6 max-w-3xl">
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm text-center space-y-6">
                <div class="w-20 h-20 rounded-full bg-red-50 border border-red-100 flex items-center justify-center mx-auto text-red-500 shadow-sm">
                    <i class="bi bi-journal-text text-4xl"></i>
                </div>
                
                <div class="space-y-3">
                    <h2 class="text-2xl font-extrabold text-slate-900">Formulario de Reclamos y Sugerencias</h2>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-lg mx-auto">
                        Puedes descargar el formato PDF del Libro de Reclamaciones oficial de nuestra institución, completarlo y enviarlo digitalmente a través de nuestra Mesa de Partes Virtual.
                    </p>
                </div>

                @if($enterprise && $enterprise->complaints_book_path)
                    <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl max-w-md mx-auto space-y-4">
                        <div class="flex items-center justify-center gap-3">
                            <i class="bi bi-file-earmark-pdf-fill text-red-500 text-4xl shadow-sm"></i>
                            <div class="text-left">
                                <p class="text-sm font-bold text-slate-800">Hoja de Reclamación (PDF)</p>
                                <p class="text-xs text-slate-400">Descarga el archivo oficial para registrar tu reclamo.</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <a href="{{ $enterprise->complaints_book_path }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-3 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-md hover:shadow-lg shadow-red-500/20">
                                <i class="bi bi-download"></i> Descargar Libro de Reclamaciones
                            </a>
                            <a href="{{ route('mesa-de-partes') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-bold rounded-xl transition-all hover:bg-slate-50">
                                <i class="bi bi-send-fill text-slate-500"></i> Enviar a Mesa de Partes Virtual
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl max-w-md mx-auto">
                        <p class="text-xs text-slate-500 leading-relaxed">
                            <i class="bi bi-info-circle text-amber-500 text-lg mr-1.5 align-middle"></i>
                            El archivo del Libro de Reclamaciones no está disponible para descarga en este momento. Por favor, comunícate con el área administrativa.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
