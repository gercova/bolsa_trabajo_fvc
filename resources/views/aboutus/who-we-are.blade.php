@extends('layouts.app')
@section('title', '¿Quiénes Somos? — IESTP Francisco Vigo Caballero')
@push('styles')
    <style>
        .value-card {
            transition: all 0.3s ease;
        }
        .value-card:hover {
            transform: translateY(-4px);
            border-color: rgba(217, 119, 6, 0.3);
            box-shadow: 0 10px 20px -5px rgba(217, 119, 6, 0.08);
        }
        .principle-badge {
            transition: all 0.2s ease;
        }
        .principle-badge:hover {
            background-color: #d97706;
            color: #ffffff;
            border-color: #d97706;
        }
    </style>
@endpush
@section('content')
    @php
        $ent = is_iterable($enterprise) ? $enterprise->first() : $enterprise;
        $ent = $ent ?? \App\Models\Enterprise::getDefault();

        // Safe extraction of principles and values
        $principles = array_filter(array_map('trim', explode(',', $ent->principles ?? 'Calidad, Transparencia, Inclusión, Trabajo colaborativo, Interculturalidad, Innovación')));
        $values = array_filter(array_map('trim', explode(',', $ent->values ?? 'Responsabilidad, Orden, Disciplina, Solidaridad, Honestidad, Respeto')));

        // Mappings for icons for values to make it look extremely rich and custom
        $valueIcons = [
            'Responsabilidad' => 'bi-shield-check text-emerald-500 bg-emerald-50 border-emerald-100',
            'Orden' => 'bi-layout-sidebar-inset text-blue-500 bg-blue-50 border-blue-100',
            'Disciplina' => 'bi-star-fill text-amber-500 bg-amber-50 border-amber-200',
            'Solidaridad' => 'bi-heart-fill text-rose-500 bg-rose-50 border-rose-100',
            'Honestidad' => 'bi-check-circle-fill text-indigo-500 bg-indigo-50 border-indigo-100',
            'Respeto' => 'bi-people-fill text-violet-500 bg-violet-50 border-violet-100',
        ];
        
        $principleIcons = [
            'Calidad' => 'bi-award text-amber-600',
            'Transparencia' => 'bi-eye text-cyan-600',
            'Inclusión' => 'bi-people text-purple-600',
            'Trabajo colaborativo' => 'bi-briefcase text-emerald-600',
            'Interculturalidad' => 'bi-globe text-blue-600',
            'Innovación' => 'bi-lightbulb text-rose-600',
        ];
    @endphp
    {{-- ===== HERO SECTION ===== --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tight leading-tight">
                ¿Quiénes Somos?
            </h1>
            @if($ent->phrase)
                <p class="text-amber-400 font-bold uppercase tracking-wider text-sm mb-4">
                    "{{ $ent->phrase }}"
                </p>
            @endif
            <p class="text-base md:text-lg text-slate-300 leading-relaxed max-w-3xl mx-auto">
                {{ $ent->description }}
            </p>
        </div>
    </section>
    {{-- ===== MAIN GRID SECTION ===== --}}
    <section class="py-16 bg-slate-50/50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 max-w-7xl mx-auto">
                {{-- LEFT COLUMN: MISSION, VISION, VALUES, PRINCIPLES (Col Span 2) --}}
                <div class="lg:col-span-2 space-y-12">
                    {{-- Mission & Vision Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Misión --}}
                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 mb-4">Nuestra Misión</h3>
                                <p class="text-slate-600 text-sm leading-relaxed">
                                    {{ $ent->mission }}
                                </p>
                            </div>
                        </div>
                        {{-- Visión --}}
                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 mb-4">Nuestra Visión</h3>
                                <p class="text-slate-600 text-sm leading-relaxed">
                                    {{ $ent->vision }}
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Values Section --}}
                    @if(count($values) > 0)
                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                                Nuestros Valores
                            </h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                                Los valores del IESTP "Francisco Vigo Caballero" rigen el comportamiento de nuestra comunidad y son el fundamento de la formación integral de nuestros estudiantes.
                            </p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                @foreach($values as $value)
                                    @php
                                        // Match value style or fallback to default
                                        $style = $valueIcons[$value] ?? 'bi-patch-check-fill text-slate-500 bg-slate-50 border-slate-100';
                                        $iconClass = explode(' ', $style)[0];
                                        $badgeColorClasses = implode(' ', array_slice(explode(' ', $style), 1));
                                    @endphp
                                    <div class="value-card p-5 bg-white border border-slate-100 rounded-2xl flex flex-col items-center text-center">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center border {{ $badgeColorClasses }} mb-4">
                                            <i class="bi {{ $iconClass }} text-lg"></i>
                                        </div>
                                        <h4 class="font-extrabold text-slate-800 text-sm">{{ $value }}</h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- Principles Section --}}
                    @if(count($principles) > 0)
                        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                                Principios Institucionales
                            </h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                                Orientamos nuestro servicio educativo bajo principios esenciales que garantizan la pertinencia, equidad e innovación académica.
                            </p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($principles as $principle)
                                    @php
                                        $icon = $principleIcons[$principle] ?? 'bi-arrow-right-circle text-amber-600';
                                    @endphp
                                    <div class="principle-badge p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center shrink-0">
                                            <i class="bi {{ $icon }} text-lg"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">{{ $principle }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                {{-- RIGHT COLUMN: SIDEBAR (Col Span 1) --}}
                <div class="space-y-8">
                    {{-- Identity Details Card --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-card-text text-amber-500 text-lg"></i>
                            Datos de la Institución
                        </h3>
                        
                        <div class="space-y-4 text-xs">
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400 font-medium">Nombre Oficial</span>
                                <span class="font-bold text-slate-800 text-right">{{ $ent->company_name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400 font-medium">R.U.C.</span>
                                <span class="font-bold text-slate-800">{{ $ent->ruc }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400 font-medium">Sector Económico</span>
                                <span class="font-bold text-slate-800">{{ $ent->business_sector }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-400 font-medium">Director General</span>
                                <span class="font-bold text-slate-800">{{ $ent->legal_representative }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-400 font-medium">Ciudad</span>
                                <span class="font-bold text-slate-800">{{ $ent->city }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- Licenciamiento Progress Card --}}
                    {{-- <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-shield-check text-amber-500 text-lg"></i>
                            Licenciamiento Institucional
                        </h3>
                        
                        <div class="space-y-4">
                            <p class="text-xs text-slate-500 leading-relaxed">
                                El IESTP "Francisco Vigo Caballero" se encuentra en un proceso activo de licenciamiento ante el Ministerio de Educación (MINEDU), asegurando las Condiciones Básicas de Calidad (CBC) para todos nuestros programas de estudio.
                            </p>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-[11px] font-bold text-slate-700">
                                    <span>Avance de CBC</span>
                                    <span>100%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>

                            <ul class="space-y-2.5 text-[11px] text-slate-600 pt-2">
                                <li class="flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                                    Gestión académica e institucional idónea.
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                                    Plana docente calificada y certificada.
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                                    Infraestructura y equipamiento adecuado.
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                                    Previsión económica y financiera estable.
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    {{-- Contact Info Card --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-base font-extrabold text-slate-900 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2.5">
                            <i class="bi bi-telephone text-amber-500 text-lg"></i>
                            Contacto y Redes
                        </h3>
                        <div class="space-y-4">
                            <div class="flex gap-3 text-xs">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                                    <i class="bi bi-geo-alt text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Dirección</p>
                                    <p class="text-slate-500">{{ $ent->address }}, {{ $ent->city }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3 text-xs">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0">
                                    <i class="bi bi-envelope text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Correo Electrónico</p>
                                    <a href="mailto:{{ $ent->email }}" class="text-slate-500 hover:text-indigo-600 transition-colors">{{ $ent->email }}</a>
                                </div>
                            </div>
                            <div class="flex gap-3 text-xs">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                                    <i class="bi bi-telephone text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Teléfono</p>
                                    <p class="text-slate-500">{{ $ent->phone_number_1 }}</p>
                                </div>
                            </div>
                            {{-- Social Media Icons --}}
                            <div class="flex gap-2 pt-2 border-t border-slate-50">
                                @if($ent->facebook_link)
                                    <a href="{{ $ent->facebook_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-600 border border-slate-100 flex items-center justify-center transition-colors">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                @endif
                                @if($ent->whatsapp_link)
                                    <a href="{{ $ent->whatsapp_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 border border-slate-100 flex items-center justify-center transition-colors">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                @endif
                                @if($ent->linkedin_link)
                                    <a href="{{ $ent->linkedin_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-blue-50 text-slate-400 hover:text-blue-700 border border-slate-100 flex items-center justify-center transition-colors">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                @endif
                                @if($ent->twitter_link)
                                    <a href="{{ $ent->twitter_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-900 border border-slate-100 flex items-center justify-center transition-colors">
                                        <i class="bi bi-twitter-x"></i>
                                    </a>
                                @endif
                                @if($ent->instagram_link)
                                    <a href="{{ $ent->instagram_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-pink-50 text-slate-400 hover:text-pink-600 border border-slate-100 flex items-center justify-center transition-colors">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection