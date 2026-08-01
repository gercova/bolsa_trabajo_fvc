@extends('layouts.app')
@section('title', 'Plana Docente y Equipo Académico — IESTP Francisco Vigo Caballero')
@push('styles')
    {{-- SEO & OpenGraph Meta Tags --}}
    <meta name="description"
        content="Conoce a la plana docente y equipo de coordinadores académicos del IESTP Francisco Vigo Caballero. Profesionales experimentados comprometidos con la educación técnica de excelencia en Uchiza.">
    <meta name="keywords"
        content="plana docente, profesores iestp, coordinadores academicos, educadores tecnicos, francisco vigo caballero, uchiza, docentes produccion agropecuaria, docentes enfermeria tecnica, docentes redes, docentes administracion, docentes manejo forestal">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- OpenGraph Meta Tags --}}
    <meta property="og:title" content="Plana Docente y Equipo Académico — IESTP Francisco Vigo Caballero">
    <meta property="og:description"
        content="Conoce a nuestros educadores y coordinadores de carrera altamente calificados, comprometidos con la excelencia en la educación técnica superior.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($enterprise->logo_path) }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Plana Docente — IESTP Francisco Vigo Caballero">
    <meta name="twitter:description"
        content="Conoce al cuerpo docente y coordinadores académicos del IESTP Francisco Vigo Caballero.">
    <meta name="twitter:image" content="{{ asset($enterprise->logo_path) }}">

    <style>
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.15);
        }

        .coordinator-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .coordinator-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(245, 158, 11, 0.2);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- Structured Data JSON-LD --}}
    @php
        $schemaItemList = [];
        $pos = 1;
        foreach ($teacherDetails as $detail) {
            if ($detail->user) {
                $schemaItemList[] = [
                    '@type' => 'ListItem',
                    'position' => $pos++,
                    'item' => [
                        '@type' => 'Person',
                        'name' => $detail->user->names,
                        'jobTitle' => $detail->user->job_position ?? ($detail->is_coordinator ? 'Coordinador Académico' : 'Docente'),
                        'email' => $detail->user->email ?? null,
                        'worksFor' => [
                            '@type' => 'EducationalOrganization',
                            'name' => 'IESTP Francisco Vigo Caballero',
                        ],
                        'knowsAbout' => $detail->specialty ?? ($detail->program ? $detail->program->name : 'Educación Técnica Superior'),
                    ]
                ];
            }
        }
    @endphp
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "EducationalOrganization",
                "@id": "{{ config('app.url') }}/#organization",
                "name": "IESTP Francisco Vigo Caballero",
                "url": "{{ config('app.url') }}",
                "description": "Plana docente y equipo de coordinadores académicos del IESTP Francisco Vigo Caballero en Uchiza.",
                "department": [
                    @foreach($programs as $index => $program)
                    {
                        "@type": "AcademicDepartment",
                        "name": "{{ $program->name }}",
                        "url": "{{ url('/programas-de-estudios/' . $program->slug) }}"
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            },
            {
                "@type": "ItemList",
                "name": "Plana Docente IESTP Francisco Vigo Caballero",
                "numberOfItems": {{ count($schemaItemList) }},
                "itemListElement": {!! json_encode($schemaItemList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            }
        ]
    }
    </script>
@endpush

@section('content')
    @php
        $totalTeachersCount = $teacherDetails
            ->pluck('user_id')
            ->concat($unassignedTeachers->pluck('id'))
            ->unique()
            ->count();
        $coordinators = $teacherDetails->where('is_coordinator', true);
        $coordinatorsCount = $coordinators->count();
        $programsCount = $programs->count();

        // Program Metadata mappings matching study-programs.blade.php
        $programMeta = [
            'Producción Agropecuaria' => [
                'icon'      => 'bi-tree-fill',
                'accent'    => 'emerald',
                'bg_badge'  => 'bg-emerald-50 text-emerald-800 border-emerald-100',
                'tag'       => 'Producción & Campo',
                'color_bar' => 'bg-emerald-500',
                'avatar_bg' => 'from-emerald-600 to-teal-700 text-white',
            ],
            'Enfermería Técnica' => [
                'icon'      => 'bi-heart-pulse-fill',
                'accent'    => 'rose',
                'bg_badge'  => 'bg-rose-50 text-rose-800 border-rose-100',
                'tag'       => 'Ciencias de la Salud',
                'color_bar' => 'bg-rose-500',
                'avatar_bg' => 'from-rose-600 to-pink-700 text-white',
            ],
            'Administración de Redes y Comunicaciones' => [
                'icon'      => 'bi-router-fill',
                'accent'    => 'sky',
                'bg_badge'  => 'bg-sky-50 text-sky-800 border-sky-100',
                'tag'       => 'Soporte e Infraestructura TI',
                'color_bar' => 'bg-sky-500',
                'avatar_bg' => 'from-sky-600 to-blue-700 text-white',
            ],
            'Asistencia Administrativa' => [
                'icon'      => 'bi-briefcase-fill',
                'accent'    => 'blue',
                'bg_badge'  => 'bg-blue-50 text-blue-800 border-blue-100',
                'tag'       => 'Administración & Finanzas',
                'color_bar' => 'bg-blue-600',
                'avatar_bg' => 'from-blue-600 to-indigo-700 text-white',
            ],
            'Manejo Forestal' => [
                'icon'      => 'bi-globe-americas',
                'accent'    => 'teal',
                'bg_badge'  => 'bg-teal-50 text-teal-800 border-teal-100',
                'tag'       => 'Recursos Naturales',
                'color_bar' => 'bg-teal-500',
                'avatar_bg' => 'from-teal-600 to-emerald-700 text-white',
            ],
        ];

        $defaultMeta = [
            'icon' => 'bi-mortarboard-fill',
            'accent' => 'blue',
            'bg_badge' => 'bg-blue-50 text-blue-800 border-blue-100',
            'tag' => 'Educación Técnica',
            'color_bar' => 'bg-blue-500',
            'avatar_bg' => 'from-blue-600 to-indigo-700 text-white',
        ];
    @endphp

    <div x-data="{
        selectedProgram: 'all',
        searchQuery: '',
        activeTeacher: null,
        showModal: false,

        openTeacherModal(name, role, program, specialty, email, photo, isCoordinator) {
            this.activeTeacher = {
                name: name,
                role: role || 'Docente Institucional',
                program: program || 'IESTP Francisco Vigo Caballero',
                specialty: specialty || 'Educación Técnica Superior',
                email: email || '',
                photo: photo || '',
                isCoordinator: isCoordinator || false
            };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.activeTeacher = null;
        },

        matchesSearch(name, specialty, position, programName) {
            if (!this.searchQuery.trim()) return true;
            const q = this.searchQuery.toLowerCase().trim();
            const n = (name || '').toLowerCase();
            const s = (specialty || '').toLowerCase();
            const p = (position || '').toLowerCase();
            const pr = (programName || '').toLowerCase();
            return n.includes(q) || s.includes(q) || p.includes(q) || pr.includes(q);
        }
    }" @keydown.escape.window="closeModal()" class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

        {{-- ===== HERO SECTION (Identical visual theme to study-programs.blade.php) ===== --}}
        <section class="relative bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-blue-900/30">
            {{-- Elegant glow patterns --}}
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(56,189,248,0.15),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(59,130,246,0.12),transparent_40%)]"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                    Plana Docente y <span class="text-sky-400 bg-gradient-to-r from-sky-400 to-blue-400 bg-clip-text text-transparent">Equipo Académico</span>
                </h1>

                <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                    Profesionales capacitados y educadores experimentados en el sector productivo, dedicados a guiar la formación profesional técnica de nuestros estudiantes.
                </p>

                {{-- Metrics Grid in Hero --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ $totalTeachersCount }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Docentes Calificados</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-amber-400">{{ $coordinatorsCount }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Coordinaciones de Carrera</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-emerald-400">{{ $programsCount }}</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Programas de Estudio</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                        <p class="text-3xl sm:text-4xl font-black text-sky-400">100%</p>
                        <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Formación Práctica</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== COORDINATORS HIGHLIGHT SECTION ===== --}}
        @if ($coordinators->isNotEmpty())
            <section class="py-20 bg-slate-100 border-b border-slate-200/80" x-show="selectedProgram === 'all' && searchQuery.trim() === ''">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-amber-100 text-amber-800 uppercase tracking-wider">
                            <i class="bi bi-star-fill text-amber-500"></i> Liderazgo Académico
                        </span>
                        <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                            Coordinadores de Programa de Estudio
                        </h2>
                        <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed font-medium">
                            Responsables del desarrollo curricular, articulación pedagógica y supervisión académica de cada carrera profesional.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($coordinators as $detail)
                            @php
                                $user = $detail->user;
                                $program = $detail->program;
                                $pMeta = $program ? ($programMeta[$program->name] ?? $defaultMeta) : $defaultMeta;
                            @endphp
                            @if ($user)
                                <div class="coordinator-card bg-white rounded-3xl border border-amber-200/90 shadow-md hover-card p-6 flex flex-col justify-between relative overflow-hidden group cursor-pointer"
                                     @click="openTeacherModal('{{ addslashes($user->names) }}', '{{ addslashes($user->job_position ?? 'Docente y Coordinador') }}', '{{ addslashes($program ? $program->name : 'IESTP FVC') }}', '{{ addslashes($detail->specialty ?? 'Especialista Académico') }}', '{{ addslashes($user->email ?? '') }}', '{{ $user->photo_profile ? Storage::url($user->photo_profile) : '' }}', true)">

                                    {{-- Badge Overlay --}}
                                    <div class="absolute top-0 right-0 bg-gradient-to-l from-amber-500 to-amber-600 text-white text-xs font-black px-4 py-1.5 rounded-bl-2xl shadow-sm flex items-center gap-1.5">
                                        <i class="bi bi-star-fill text-yellow-200"></i>
                                        Coordinador
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-4 mb-5">
                                            <div class="w-20 h-20 rounded-2xl bg-slate-100 overflow-hidden border-2 border-amber-300 shrink-0 shadow-inner flex items-center justify-center relative">
                                                @if ($user->photo_profile)
                                                    <img src="{{ Storage::url($user->photo_profile) }}"
                                                        alt="{{ $user->names }}"
                                                        loading="lazy"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br {{ $pMeta['avatar_bg'] }} flex items-center justify-center text-2xl font-black">
                                                        {{ strtoupper(substr($user->names, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="pr-20 min-w-0">
                                                <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                                                    {{ $user->names }}
                                                </h3>
                                                <p class="text-xs font-bold text-slate-500 mt-1">
                                                    {{ $user->job_position ?? 'Docente y Coordinador' }}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Program Tag --}}
                                        @if ($program)
                                            <div class="mb-4">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-black border shadow-sm {{ $pMeta['bg_badge'] }}">
                                                    <i class="bi {{ $pMeta['icon'] }}"></i>
                                                    {{ $program->name }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Specialty --}}
                                        @if ($detail->specialty)
                                            <div class="bg-amber-50/70 p-4 rounded-xl border border-amber-100 mb-4">
                                                <p class="text-[10px] font-black text-amber-900 uppercase tracking-wider mb-0.5">
                                                    Especialidad
                                                </p>
                                                <p class="text-xs font-semibold text-slate-800 leading-relaxed">
                                                    {{ $detail->specialty }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Footer Email --}}
                                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-2">
                                        @if ($user->email)
                                            <a href="mailto:{{ $user->email }}" @click.stop
                                                class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-blue-600 transition-colors truncate max-w-[75%]"
                                                title="{{ $user->email }}">
                                                <i class="bi bi-envelope-at text-blue-600 text-sm shrink-0"></i>
                                                <span class="truncate">{{ $user->email }}</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Contacto Institucional</span>
                                        @endif

                                        <span class="text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                            Ver Perfil <i class="bi bi-arrow-right"></i>
                                        </span>
                                    </div>

                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ===== MAIN TEACHERS GRID BY STUDY PROGRAM ===== --}}
        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">

                @foreach ($programs as $program)
                    @php
                        $progTeachers = $teacherDetails->where('program_id', $program->id);
                        $pMeta = $programMeta[$program->name] ?? $defaultMeta;
                    @endphp

                    <div x-show="(selectedProgram === 'all' || selectedProgram === '{{ $program->id }}')"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        id="programa-{{ $program->id }}"
                        class="space-y-8">

                        {{-- Program Header Bar --}}
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center text-2xl font-black shadow-md shrink-0">
                                    <i class="bi {{ $pMeta['icon'] }}"></i>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-xs font-black rounded-lg uppercase tracking-wider border mb-1 inline-block {{ $pMeta['bg_badge'] }}">
                                        {{ $pMeta['tag'] }}
                                    </span>
                                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                        {{ $program->name }}
                                    </h2>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-extrabold border border-slate-200">
                                    {{ $progTeachers->count() }} Docentes
                                </span>
                                <a href="{{ url('/programas-de-estudios/' . $program->slug) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-950 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-sm">
                                    Plan de Estudios
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Teachers Cards Grid --}}
                        @if ($progTeachers->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                                @foreach ($progTeachers as $detail)
                                    @php
                                        $user = $detail->user;
                                    @endphp
                                    @if ($user)
                                        <div x-show="matchesSearch('{{ addslashes($user->names) }}', '{{ addslashes($detail->specialty ?? '') }}', '{{ addslashes($user->job_position ?? '') }}', '{{ addslashes($program->name) }}')"
                                             class="group bg-white rounded-3xl border border-slate-100 shadow-md hover-card overflow-hidden flex flex-col justify-between h-full p-6 relative cursor-pointer"
                                             @click="openTeacherModal('{{ addslashes($user->names) }}', '{{ addslashes($user->job_position ?? 'Docente') }}', '{{ addslashes($program->name) }}', '{{ addslashes($detail->specialty ?? 'Especialista') }}', '{{ addslashes($user->email ?? '') }}', '{{ $user->photo_profile ? Storage::url($user->photo_profile) : '' }}', {{ $detail->is_coordinator ? 'true' : 'false' }})">

                                            {{-- Top accent bar matching study program --}}
                                            <div class="w-16 h-1 {{ $pMeta['color_bar'] }} rounded-full mb-4"></div>

                                            @if ($detail->is_coordinator)
                                                <span class="absolute top-4 right-4 px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 text-[10px] font-black uppercase tracking-wider border border-amber-200 flex items-center gap-1 shadow-sm">
                                                    <i class="bi bi-star-fill text-amber-500"></i> Coordinador
                                                </span>
                                            @endif

                                            <div>
                                                {{-- User Avatar & Info --}}
                                                <div class="flex items-center gap-4 mb-5">
                                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-inner">
                                                        @if ($user->photo_profile)
                                                            <img src="{{ Storage::url($user->photo_profile) }}"
                                                                alt="{{ $user->names }}"
                                                                loading="lazy"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full bg-gradient-to-br {{ $pMeta['avatar_bg'] }} flex items-center justify-center text-xl font-black">
                                                                {{ strtoupper(substr($user->names, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0 flex-1 pr-4">
                                                        <h3 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug truncate">
                                                            {{ $user->names }}
                                                        </h3>
                                                        <p class="text-xs font-bold text-slate-500 truncate mt-0.5">
                                                            {{ $user->job_position ?? 'Docente' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- Specialty Tag --}}
                                                @if ($detail->specialty)
                                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mb-4">
                                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                                            Especialidad
                                                        </p>
                                                        <p class="text-xs font-bold text-slate-700 leading-snug line-clamp-2">
                                                            {{ $detail->specialty }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Footer / Email Contact --}}
                                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs mt-2">
                                                @if ($user->email)
                                                    <a href="mailto:{{ $user->email }}" @click.stop title="{{ $user->email }}"
                                                        class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-blue-600 transition-colors truncate max-w-[80%]">
                                                        <i class="bi bi-envelope text-blue-600 text-sm shrink-0"></i>
                                                        <span class="truncate">{{ $user->email }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-slate-400 italic text-[11px]">Contacto Institucional</span>
                                                @endif

                                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0" title="Docente Activo"></span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                                <i class="bi bi-person-workspace text-4xl text-slate-300 mb-2"></i>
                                <p class="font-bold text-slate-700">La información docente de este programa se actualizará próximamente.</p>
                                <p class="text-xs text-slate-400 mt-1">Para consultas académicas directas, comunícate con la dirección institucional.</p>
                            </div>
                        @endif

                    </div>
                @endforeach

                {{-- Unassigned / General Education Teachers --}}
                @if ($unassignedTeachers->isNotEmpty())
                    <div x-show="selectedProgram === 'all'" class="space-y-8 pt-8 border-t border-slate-200">
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-md flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-950 text-white flex items-center justify-center text-2xl font-black shadow-md shrink-0">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div>
                                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">
                                    Educación Transversal
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                    Docentes de Formación General y Transversal
                                </h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                            @foreach ($unassignedTeachers as $teacher)
                                <div x-show="matchesSearch('{{ addslashes($teacher->names) }}', '', '{{ addslashes($teacher->job_position ?? '') }}', 'Formación General')"
                                     class="group bg-white rounded-3xl border border-slate-100 shadow-md hover-card overflow-hidden flex flex-col justify-between h-full p-6 cursor-pointer"
                                     @click="openTeacherModal('{{ addslashes($teacher->names) }}', '{{ addslashes($teacher->job_position ?? 'Docente Institucional') }}', 'Formación General y Transversal', 'Módulos Transversales', '{{ addslashes($teacher->email ?? '') }}', '{{ $teacher->photo_profile ? Storage::url($teacher->photo_profile) : '' }}', false)">

                                    <div class="w-16 h-1 bg-slate-400 rounded-full mb-4"></div>

                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                            @if ($teacher->photo_profile)
                                                <img src="{{ Storage::url($teacher->photo_profile) }}"
                                                    alt="{{ $teacher->names }}"
                                                    loading="lazy"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br {{ $defaultMeta['avatar_bg'] }} flex items-center justify-center text-xl font-black">
                                                    {{ strtoupper(substr($teacher->names, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 pr-2">
                                            <h3 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug truncate">
                                                {{ $teacher->names }}
                                            </h3>
                                            <p class="text-xs font-bold text-slate-500 truncate mt-0.5">
                                                {{ $teacher->job_position ?? 'Docente Institucional' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs mt-2">
                                        @if ($teacher->email)
                                            <a href="mailto:{{ $teacher->email }}" @click.stop
                                                class="font-bold text-slate-500 hover:text-blue-600 transition-colors truncate">
                                                <i class="bi bi-envelope text-blue-600"></i>
                                                {{ $teacher->email }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Docente FVC</span>
                                        @endif
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </section>

        {{-- ===== WHY STUDY HERE / METHODOLOGY (Matching study-programs.blade.php layout) ===== --}}
        <section class="py-24 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        Modelo Educativo
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Excelencia y Metodología de Enseñanza
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed font-medium">
                        Formamos profesionales técnicos con sólidos valores éticos, dominio práctico e inserción laboral efectiva.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    {{-- Pillar 1 --}}
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                                <i class="bi bi-award text-2xl"></i>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-lg mb-3">Enfoque por Competencias</h3>
                            <p class="text-base text-slate-600 leading-relaxed font-medium">
                                Mallas curriculares alineadas al Catálogo Nacional de la Oferta Formativa (CNOF) del MINEDU.
                            </p>
                        </div>
                    </div>

                    {{-- Pillar 2 --}}
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                                <i class="bi bi-diagram-3 text-2xl"></i>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-lg mb-3">Experiencia Práctica</h3>
                            <p class="text-base text-slate-600 leading-relaxed font-medium">
                                Formación integral en laboratorios, talleres de simulación y campos de práctica técnica.
                            </p>
                        </div>
                    </div>

                    {{-- Pillar 3 --}}
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                                <i class="bi bi-person-check text-2xl"></i>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-lg mb-3">Tutoría Continua</h3>
                            <p class="text-base text-slate-600 leading-relaxed font-medium">
                                Acompañamiento psicopedagógico y tutoría continua para asegurar la permanencia y éxito académico.
                            </p>
                        </div>
                    </div>

                    {{-- Pillar 4 --}}
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                                <i class="bi bi-file-earmark-check text-2xl"></i>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-lg mb-3">Certificación Modular</h3>
                            <p class="text-base text-slate-600 leading-relaxed font-medium">
                                Certificaciones técnicas oficiales progresivas al culminar cada año académico.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== FAQ SECTION (Matching study-programs.blade.php) ===== --}}
        <section class="py-24 bg-white border-t border-slate-100" x-data="{ activeFaq: null }">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        Ayuda
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Preguntas Frecuentes sobre la Plana Docente
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                        Resolvemos de forma inmediata las consultas comunes acerca de los docentes y coordinadores del instituto.
                    </p>
                </div>

                <div class="space-y-4">
                    {{-- FAQ 1 --}}
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="activeFaq === 1 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                        <button class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                            @click="activeFaq = activeFaq === 1 ? null : 1">
                            <span>¿Qué perfil profesional tienen los docentes del IESTP Francisco Vigo Caballero?</span>
                            <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                                :class="activeFaq === 1 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                        </button>
                        <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq1"
                            :style="activeFaq === 1 ? 'max-height: ' + $refs.faq1.scrollHeight + 'px' : ''">
                            <div class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                                Todos nuestros docentes cuentan con título profesional universitario o técnico superior en su área de especialidad, acreditando amplia experiencia práctica demostrada en el sector productivo e industrial.
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="activeFaq === 2 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                        <button class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                            @click="activeFaq = activeFaq === 2 ? null : 2">
                            <span>¿Cómo puedo comunicarme con un coordinador de carrera?</span>
                            <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                                :class="activeFaq === 2 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                        </button>
                        <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq2"
                            :style="activeFaq === 2 ? 'max-height: ' + $refs.faq2.scrollHeight + 'px' : ''">
                            <div class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                                Puedes contactar directamente con cada coordinador haciendo clic en la opción de correo electrónico que figura en su perfil en este portal, o acudiendo a la oficina de coordinación de carrera en el campus institucional.
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                        :class="activeFaq === 3 ? 'border-blue-400 shadow-md bg-blue-50/5' : ''">
                        <button class="w-full text-left p-6 font-bold text-slate-900 text-base sm:text-lg flex items-center justify-between gap-4 focus:outline-none"
                            @click="activeFaq = activeFaq === 3 ? null : 3">
                            <span>¿Los docentes brindan asesorías fuera del horario de clases?</span>
                            <i class="bi transition-transform duration-300 text-blue-600 text-xl"
                                :class="activeFaq === 3 ? 'bi-dash-lg rotate-180' : 'bi-plus-lg'"></i>
                        </button>
                        <div class="transition-all duration-300 max-h-0 overflow-hidden" x-ref="faq3"
                            :style="activeFaq === 3 ? 'max-height: ' + $refs.faq3.scrollHeight + 'px' : ''">
                            <div class="p-6 pt-0 text-base text-slate-600 border-t border-slate-100 leading-relaxed font-medium">
                                Sí. Los profesores disponen de horas de atención académica y tutoría destinadas a resolver dudas sobre proyectos, trabajos prácticos y reforzar los módulos de estudio.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== CTA SECTION (Identical to study-programs.blade.php) ===== --}}
        <section class="py-24 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white text-center relative overflow-hidden border-t border-blue-900/30">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.1),transparent_40%)]"></div>
            <div class="container mx-auto px-4 relative z-10 space-y-8">
                <h2 class="text-3xl sm:text-5xl font-black tracking-tight max-w-4xl mx-auto leading-tight">
                    ¿Listo para iniciar tu desarrollo profesional?
                </h2>
                <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                    Asegura tu vacante y prepárate con profesores expertos y talleres equipados. ¡Las inscripciones de admisión están abiertas!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                    <a href="{{ route('programas-de-estudio') }}"
                        class="bg-white text-slate-950 hover:bg-slate-100 px-8 py-4.5 rounded-xl font-extrabold transition shadow-lg flex items-center justify-center gap-2.5">
                        <i class="bi bi-grid-fill text-blue-600 text-lg"></i>
                        Ver Programas de Estudio
                    </a>
                    <a href="{{ route('examen-de-admision') }}"
                        class="bg-blue-600/20 text-white border border-blue-500/30 hover:bg-blue-600/40 px-8 py-4.5 rounded-xl font-extrabold transition flex items-center justify-center gap-2">
                        Proceso de Admisión
                        <i class="bi bi-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== TEACHER PROFILE DETAIL MODAL (Alpine.js) ===== --}}
        <div x-show="showModal"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md"
            @click.self="closeModal()">

            <div x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 relative text-slate-800">

                {{-- Modal Header Banner --}}
                <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 p-6 text-white relative">
                    <button @click="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white text-xl transition-colors p-1 rounded-lg">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 overflow-hidden flex items-center justify-center shrink-0 shadow-inner">
                            <template x-if="activeTeacher && activeTeacher.photo">
                                <img :src="activeTeacher.photo" :alt="activeTeacher.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!activeTeacher || !activeTeacher.photo">
                                <span class="text-2xl font-black text-sky-400" x-text="activeTeacher ? activeTeacher.name.charAt(0).toUpperCase() : 'D'"></span>
                            </template>
                        </div>
                        <div>
                            <span x-show="activeTeacher && activeTeacher.isCoordinator" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[10px] font-black uppercase mb-1">
                                <i class="bi bi-star-fill"></i> Coordinador
                            </span>
                            <h3 class="text-xl font-black text-white leading-snug" x-text="activeTeacher ? activeTeacher.name : ''"></h3>
                            <p class="text-xs text-sky-300 font-semibold mt-0.5" x-text="activeTeacher ? activeTeacher.role : ''"></p>
                        </div>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Programa de Estudio</p>
                        <p class="text-sm font-bold text-slate-900 mt-0.5" x-text="activeTeacher ? activeTeacher.program : ''"></p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Especialidad Académica</p>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 mt-1">
                            <p class="text-xs font-semibold text-slate-700 leading-relaxed" x-text="activeTeacher ? activeTeacher.specialty : ''"></p>
                        </div>
                    </div>

                    <div x-show="activeTeacher && activeTeacher.email">
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Correo Institucional</p>
                        <a :href="'mailto:' + (activeTeacher ? activeTeacher.email : '')" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:underline mt-1">
                            <i class="bi bi-envelope-fill text-blue-500"></i>
                            <span x-text="activeTeacher ? activeTeacher.email : ''"></span>
                        </a>
                    </div>

                    <div class="pt-4 border-t border-slate-100 text-center">
                        <button @click="closeModal()" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl transition-all text-xs">
                            Cerrar ventana
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
