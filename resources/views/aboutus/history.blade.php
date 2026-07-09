@extends('layouts.app')

@section('title', 'Reseña Histórica — IESTP Francisco Vigo Caballero')

@push('styles')
    <style>
        .timeline-container::before {
            content: '';
            position: absolute;
            top: 24px;
            bottom: 24px;
            left: 20px;
            width: 2px;
            background: linear-gradient(to bottom, #d97706 0%, #e2e8f0 100%);
        }
        @media (min-width: 1024px) {
            .timeline-container::before {
                left: 50%;
                transform: translateX(-50%);
            }
        }
        .timeline-badge {
            transition: all 0.3s ease;
        }
        .timeline-card:hover .timeline-badge {
            transform: scale(1.1);
            background-color: #d97706;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(217, 119, 6, 0.4);
        }
    </style>
@endpush

@section('content')
    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <span class="inline-flex items-center gap-1.5 bg-amber-500/25 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border border-amber-500/40">
                <i class="bi bi-clock-history"></i>
                Nuestra Identidad
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                Reseña Histórica
            </h1>
            <p class="text-base md:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto">
                Conoce la trayectoria de nuestra institución educativa, desde sus humildes comienzos ad honorem en 1991, hasta consolidarse como pilar del licenciamiento y desarrollo tecnológico en Uchiza.
            </p>
        </div>
    </section>

    {{-- ===== MAIN GRID SECTION ===== --}}
    <section class="py-16 bg-slate-50/50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 max-w-7xl mx-auto">
                
                {{-- LEFT COLUMN: TIMELINE (Col Span 2) --}}
                <div class="lg:col-span-2 space-y-12">
                    
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm mb-8">
                        <h2 class="text-2xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                            <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                            Nuestra Trayectoria en el Tiempo
                        </h2>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            A través de décadas de dedicación, el IESTP "Francisco Vigo Caballero" ha evolucionado respondiendo a las necesidades productivas de la región San Martín, formando técnicos profesionales competentes y éticos.
                        </p>
                    </div>

                    {{-- Vertical Timeline Wrapper --}}
                    <div class="relative timeline-container pl-10 lg:pl-0">
                        @foreach ($histories as $index => $history)
                            @php
                                $isEven = $index % 2 === 0;
                            @endphp
                            
                            {{-- Timeline Item --}}
                            <div class="relative mb-16 timeline-card lg:grid lg:grid-cols-2 lg:gap-8">
                                
                                {{-- Central Badge Indicator --}}
                                <div class="absolute left-0 lg:left-1/2 top-4 w-10 h-10 -translate-x-[45px] lg:-translate-x-1/2 rounded-full bg-white border-4 border-slate-200 text-amber-600 flex items-center justify-center font-extrabold text-sm z-20 shadow-md timeline-badge">
                                    {{ $index + 1 }}
                                </div>
                                
                                {{-- Card Container (Alternating placement) --}}
                                <div class="w-full {{ $isEven ? 'lg:col-start-1 lg:text-right lg:pr-8' : 'lg:col-start-2 lg:pl-8' }}">
                                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 relative">
                                        
                                        {{-- Year Badge --}}
                                        <span class="inline-block px-3 py-1 text-xs font-extrabold tracking-wider rounded-full bg-amber-50 text-amber-800 border border-amber-200 mb-4">
                                            @if ($history->end_year)
                                                {{ $history->start_year }} - {{ $history->end_year }}
                                            @else
                                                {{ $history->start_year }} - Presente
                                            @endif
                                        </span>
                                        
                                        {{-- Title --}}
                                        <h3 class="text-lg md:text-xl font-black text-slate-900 mb-4 leading-tight">
                                            {{ $history->title }}
                                        </h3>
                                        
                                        {{-- Paragraphs split --}}
                                        <div class="prose max-w-none text-slate-600 text-sm leading-relaxed space-y-4 text-left">
                                            @foreach(explode("\n", str_replace("\r", "", $history->description)) as $paragraph)
                                                @if(trim($paragraph))
                                                    <p>{{ trim($paragraph) }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- RIGHT COLUMN: SIDEBAR (Col Span 1) --}}
                <div class="space-y-8">
                    
                    {{-- Milestones Card --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-bookmark-star text-amber-500 text-lg"></i>
                            Hitos Históricos Clave
                        </h3>
                        
                        <div class="relative border-l border-slate-100 pl-4 space-y-5 ml-2">
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Julio 1990</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">Primera asamblea promotora de directores en Uchiza.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Enero 1991</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">Se crea oficialmente mediante Decreto Ejecutivo Regional Nº 05-91-CR-SMLL.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Noviembre 1994</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">R.M. Nº 0868-94-ED otorga el reconocimiento y funcionamiento definitivo.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Marzo 1997</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">Se crea la especialidad de Secretariado Ejecutivo con R.D. Nº 0091-97-ED.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Mayo 2000</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">Apertura del programa de Laboratorio Clínico con R.D. Nº 483-2000-ED.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Abril 2006</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">Revalidación oficial de carreras con R.D. Nº 0298-2006-ED.</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Abril 2010</p>
                                <p class="text-xs text-slate-700 font-semibold mt-0.5 leading-snug">Creación de Medio Ambiente y Recursos Naturales con R.D. Nº 0341-2010-ED.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Historic Leaders --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-people text-amber-500 text-lg"></i>
                            Gestores y Directores Destacados
                        </h3>
                        
                        <ul class="space-y-4">
                            <li class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-100 font-bold text-xs">
                                    LH
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Lic. Luis Viviano Hilario Vega</p>
                                    <p class="text-[10px] text-slate-500">Jefe de Coordinación Educativa y Director (1998 - 2001)</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-100 font-bold text-xs">
                                    DD
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Econ. Demetrio Díaz Guevara</p>
                                    <p class="text-[10px] text-slate-500">Ex-Alcalde de Uchiza e impulsor de la creación del IESTP</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-100 font-bold text-xs">
                                    EC
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Ing. Edison Calvo Trujillo</p>
                                    <p class="text-[10px] text-slate-500">Primer Director del Instituto (1991)</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-100 font-bold text-xs">
                                    MG
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Ing. Manuel Adolfo Guerra Meléndez</p>
                                    <p class="text-[10px] text-slate-500">Director Revalidador y Gestor de Terrenos (2005 - 2010)</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-100 font-bold text-xs">
                                    TG
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Mg. Teodorico Ganoza Medina</p>
                                    <p class="text-[10px] text-slate-500">Director General Actual (2026 - Presente)</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Assets and Infrastructure --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-geo text-amber-500 text-lg"></i>
                            Predios e Infraestructura
                        </h3>
                        
                        <div class="space-y-4 text-xs">
                            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                                <p class="font-bold text-slate-800 mb-1">Sede Central (Barro Blanco)</p>
                                <p class="text-[11px] text-slate-500 leading-relaxed">2.0 Hectáreas destinadas a aulas académicas, laboratorios, auditorios, módulos pecuarios y estanques piscícolas.</p>
                            </div>
                            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                                <p class="font-bold text-slate-800 mb-1">Fundo Vista Alegre</p>
                                <p class="text-[11px] text-slate-500 leading-relaxed">115.0 Hectáreas dedicadas al proceso de reforestación en Barro Blanco, Pucarrumi y Peso.</p>
                            </div>
                            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                                <p class="font-bold text-slate-800 mb-1">Fundo Gavilán</p>
                                <p class="text-[11px] text-slate-500 leading-relaxed">42.0 Hectáreas en Gavilán - Paraíso con plantaciones de palma aceitera en producción, cacao y plátanos.</p>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </section>
@endsection
