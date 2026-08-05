@extends('layouts.app')

@section('title', 'Consejo de Estudiantes (' . $selectedPeriod . ') — IESTP Francisco Vigo Caballero')

@push('styles')
    {{-- SEO Optimization Meta Tags --}}
    <meta name="description"
        content="Conoce a los representantes electos del Consejo de Estudiantes del IESTP Francisco Vigo Caballero en Uchiza. Liderazgo, defensa de derechos y gestión estudiantil.">
    <meta name="keywords"
        content="consejo de estudiantes, consejo estudiantil, representantes de estudiantes, iestp francisco vigo caballero, uchiza, san martin, educacion tecnica, liderazgo estudiantil">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/nosotros/consejo-de-estudiantes') }}">

    {{-- JSON-LD Structured Data --}}
    @php
        $schemaMembers = [];
        $pos = 1;
        foreach ($members as $m) {
            $name = $m->user->names ?? $m->name;
            $schemaMembers[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'item' => [
                    '@type' => 'Person',
                    'name' => $name,
                    'jobTitle' => $m->position . ' — Consejo de Estudiantes (' . $m->academic_period . ')',
                    'email' => $m->user->email ?? null,
                    'telephone' => $m->user->phone ?? null,
                    'worksFor' => [
                        '@type' => 'EducationalOrganization',
                        'name' => 'IESTP Francisco Vigo Caballero',
                    ],
                ],
            ];
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
                "description": "Consejo de Estudiantes del IESTP Francisco Vigo Caballero de Uchiza.",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "{{ $enterprise->address ?? 'Av. Ricardo Palma N° 1401' }}",
                    "addressLocality": "{{ $enterprise->city ?? 'Uchiza' }}",
                    "addressRegion": "San Martín",
                    "addressCountry": "PE"
                }
            },
            {
                "@type": "ItemList",
                "name": "Consejo de Estudiantes {{ $selectedPeriod }} — IESTP Francisco Vigo Caballero",
                "numberOfItems": {{ count($schemaMembers) }},
                "itemListElement": {!! json_encode($schemaMembers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            }
        ]
    }
    </script>

    <style>
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.15);
        }
    </style>
@endpush

@section('content')
    {{-- ===== HERO SECTION ===== --}}
    <section
        class="relative bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white overflow-hidden py-16 lg:py-24 border-b border-blue-900/30">
        {{-- Elegant glow patterns --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(56,189,248,0.15),transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(59,130,246,0.12),transparent_40%)]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center justify-center text-sm font-semibold text-slate-400 space-x-2">
                <a href="{{ route('inicio') }}" class="hover:text-sky-400 transition-colors flex items-center gap-1.5">
                    <i class="bi bi-house-door text-base"></i> Inicio
                </a>
                <i class="bi bi-chevron-right text-xs opacity-50"></i>
                <span class="text-slate-300">Nosotros</span>
                <i class="bi bi-chevron-right text-xs opacity-50"></i>
                <span class="text-sky-400 font-bold">Consejo de Estudiantes</span>
            </nav>

            <h1
                class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight leading-none text-white max-w-5xl mx-auto">
                Consejo de <span
                    class="text-sky-400 bg-gradient-to-r from-sky-400 to-blue-400 bg-clip-text text-transparent">Estudiantes</span>
            </h1>

            <p class="text-xl sm:text-2xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-medium">
                Voz oficial, liderazgo democrático y representación activa de los alumnos del IESTP Francisco Vigo
                Caballero.
            </p>

            {{-- Metrics & Management Selector Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto mt-16 pt-12 border-t border-white/10">
                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ $members->count() }}</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Representantes Electos</p>
                </div>

                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">
                        {{ $members->pluck('study_program_id')->filter()->unique()->count() }}
                    </p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Programas de Estudio</p>
                </div>

                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300">
                    <p class="text-3xl sm:text-4xl font-black text-sky-400">{{ $secretaries->count() }}</p>
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Secretarías de Gestión</p>
                </div>

                <div
                    class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/5 hover:border-sky-500/20 transition-all duration-300 flex flex-col justify-center">
                    @if ($periods->count() > 1)
                        <form method="GET" action="{{ route('consejo-de-estudiantes') }}" class="w-full">
                            <select name="period" onchange="this.form.submit()"
                                class="w-full bg-slate-900/90 border border-slate-700 text-sky-400 font-extrabold text-sm sm:text-base rounded-xl px-3 py-2 text-center focus:ring-2 focus:ring-sky-400 cursor-pointer">
                                @foreach ($periods as $period)
                                    <option value="{{ $period }}" {{ $period === $selectedPeriod ? 'selected' : '' }}>
                                        Gestión {{ $period }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        <p class="text-2xl sm:text-3xl font-black text-sky-400">{{ $selectedPeriod }}</p>
                    @endif
                    <p class="text-sm sm:text-base font-bold text-slate-400 mt-2">Período Institucional</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== JUNTA DIRECTIVA SECTION ===== --}}
    @if ($board->isNotEmpty())
        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span
                        class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        Liderazgo Directivo
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Junta Directiva Principal
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                        Representantes que lideran el diálogo institucional, coordinan las asambleas generales y representan
                        oficialmente al alumnado.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    @foreach ($board as $leader)
                        @php
                            $user = $leader->user;
                            $program = $leader->studyProgram;
                            $fullName = $user->names ?? $leader->name;
                            $photo = $user?->photo_profile ? asset('storage/' . $user->photo_profile) : null;
                            $isPresident =
                                str_contains(mb_strtolower($leader->position, 'UTF-8'), 'presidente') &&
                                !str_contains(mb_strtolower($leader->position, 'UTF-8'), 'vice');
                            $accentTag = $isPresident
                                ? 'bg-blue-100 text-blue-800 border-blue-200'
                                : 'bg-slate-100 text-slate-800 border-slate-200';
                            $colorBar = $isPresident ? 'bg-blue-600' : 'bg-sky-500';
                        @endphp

                        <div
                            class="group bg-white rounded-3xl border border-slate-100 shadow-md hover-card overflow-hidden flex flex-col h-full">
                            {{-- Header Banner & Avatar --}}
                            <div
                                class="h-48 relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-slate-950 flex items-center justify-center p-6">
                                <div
                                    class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(56,189,248,0.1),transparent_60%)]">
                                </div>

                                {{-- Position Tag --}}
                                <div class="absolute top-4 left-4 z-20">
                                    <span
                                        class="px-3.5 py-1.5 text-xs font-black rounded-lg uppercase tracking-wider shadow border {{ $accentTag }}">
                                        <i class="bi {{ $isPresident ? 'bi-award-fill' : 'bi-shield-check' }} mr-1.5"></i>
                                        {{ $leader->position }}
                                    </span>
                                </div>

                                {{-- Period Tag --}}
                                <div class="absolute top-4 right-4 z-20">
                                    <span
                                        class="px-3 py-1 text-xs font-bold text-slate-300 bg-white/10 rounded-lg backdrop-blur-md">
                                        Gestión {{ $leader->academic_period }}
                                    </span>
                                </div>

                                {{-- Avatar / Photo --}}
                                <div class="relative z-10 flex flex-col items-center mt-4">
                                    @if ($photo)
                                        <img src="{{ $photo }}" alt="{{ $fullName }}"
                                            class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white/20 shadow-xl group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div
                                            class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-600 to-sky-500 text-white flex items-center justify-center font-black text-3xl ring-4 ring-white/20 shadow-xl">
                                            {{ strtoupper(substr($fullName, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-8 flex flex-col flex-grow space-y-6">
                                <div class="text-center">
                                    <h3
                                        class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-tight mb-2">
                                        {{ $fullName }}
                                    </h3>
                                    <div class="w-16 h-1 {{ $colorBar }} rounded-full mx-auto"></div>
                                </div>

                                {{-- Program Details Box --}}
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2.5">
                                    @if ($program)
                                        <div class="flex items-center gap-2.5 text-sm font-bold text-slate-800">
                                            <i
                                                class="bi {{ $program->icon ?? 'bi-mortarboard-fill' }} text-blue-600 text-base shrink-0"></i>
                                            <span>{{ $program->name }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2.5 text-sm font-bold text-slate-700">
                                        <i class="bi bi-person-badge text-blue-600 text-base shrink-0"></i>
                                        <span>DNI: {{ $user->dni ?? '61543597' }}</span>
                                    </div>

                                    @if ($user && $user->phone)
                                        <div class="flex items-center gap-2.5 text-sm font-bold text-slate-700">
                                            <i class="bi bi-telephone-fill text-blue-600 text-base shrink-0"></i>
                                            <span>Teléfono: {{ $user->phone }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Institutional Email / Action --}}
                                @if ($user && $user->email)
                                    <div class="pt-2">
                                        <a href="mailto:{{ $user->email }}"
                                            class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-black text-white bg-slate-950 hover:bg-blue-600 rounded-xl transition duration-200 shadow-md hover:shadow-lg">
                                            <i class="bi bi-envelope-fill mr-2"></i> Contactar Representante
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== SECRETARÍAS ESPECIALIZADAS SECTION ===== --}}
    @if ($secretaries->isNotEmpty())
        <section class="py-24 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span
                        class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                        Estructura Operativa
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                        Secretarías Especializadas
                    </h2>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                        Equipo de secretarios responsables de impulsar actividades de bienestar, deporte, cultura, defensa y
                        organización.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($secretaries as $secretary)
                        @php
                            $u = $secretary->user;
                            $prog = $secretary->studyProgram;
                            $name = $u->names ?? $secretary->name;
                            $photo = $u?->photo_profile ? asset('storage/' . $u->photo_profile) : null;
                            $pos = mb_strtolower($secretary->position, 'UTF-8');

                            $iconClass = 'bi-award-fill';
                            if (str_contains($pos, 'organización')) {
                                $iconClass = 'bi-diagram-3-fill';
                            } elseif (str_contains($pos, 'defensa') || str_contains($pos, 'derechos')) {
                                $iconClass = 'bi-shield-check';
                            } elseif (str_contains($pos, 'bienestar')) {
                                $iconClass = 'bi-heart-pulse-fill';
                            } elseif (str_contains($pos, 'prensa') || str_contains($pos, 'propaganda')) {
                                $iconClass = 'bi-megaphone-fill';
                            } elseif (str_contains($pos, 'economía')) {
                                $iconClass = 'bi-cash-coin';
                            } elseif (str_contains($pos, 'actas')) {
                                $iconClass = 'bi-journal-bookmark-fill';
                            } elseif (str_contains($pos, 'deportes')) {
                                $iconClass = 'bi-trophy-fill';
                            } elseif (str_contains($pos, 'arte') || str_contains($pos, 'cultura')) {
                                $iconClass = 'bi-palette-fill';
                            } elseif (str_contains($pos, 'disciplina')) {
                                $iconClass = 'bi-patch-check-fill';
                            } elseif (str_contains($pos, 'ecología') || str_contains($pos, 'ambiente')) {
                                $iconClass = 'bi-tree-fill';
                            } elseif (str_contains($pos, 'relaciones')) {
                                $iconClass = 'bi-globe-americas';
                            }
                        @endphp

                        <div
                            class="group bg-white rounded-3xl border border-slate-100 shadow-md hover-card overflow-hidden flex flex-col h-full">
                            {{-- Card Header Gradient --}}
                            <div
                                class="h-28 relative overflow-hidden bg-gradient-to-br from-slate-900 to-blue-950 p-4 flex items-start justify-between">
                                <span
                                    class="px-3 py-1 text-xs font-black rounded-lg uppercase tracking-wider bg-blue-100 text-blue-900 shadow">
                                    {{ $secretary->position }}
                                </span>
                                <div
                                    class="w-9 h-9 rounded-xl bg-sky-400/20 text-sky-300 flex items-center justify-center text-lg">
                                    <i class="bi {{ $iconClass }}"></i>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6 flex flex-col flex-grow space-y-5 -mt-10 relative z-10">
                                {{-- Avatar & Name --}}
                                <div class="flex items-center gap-4">
                                    @if ($photo)
                                        <img src="{{ $photo }}" alt="{{ $name }}"
                                            class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white shadow-lg shrink-0">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-sky-500 text-white flex items-center justify-center font-black text-xl ring-4 ring-white shadow-lg shrink-0">
                                            {{ strtoupper(substr($name, 0, 2)) }}
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <h3
                                            class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-tight">
                                            @php
                                                // $count      = mb_substr_count($name, ' ');
                                                $parts = array_filter(explode(' ', $name));

                                                switch (count($parts)) {
                                                    case 0:
                                                        $nameView = '';
                                                        break;
                                                    case 1:
                                                        $nameView = $name;
                                                        break;
                                                    default:
                                                        $firstName = $parts[0];
                                                        $lastName = $parts[count($parts) - 2];
                                                        $middleInitial =
                                                            strlen($parts[1]) > 0 ? substr($parts[1], 0, 1) . '.' : '';
                                                        $nameView = trim("{$firstName} {$middleInitial} {$lastName}");
                                                        break;
                                                }
                                            @endphp
                                            {{ $nameView }}
                                            {{-- {{ $name }} --}}
                                        </h3>
                                        <div class="w-12 h-1 bg-blue-500 rounded-full mt-1.5"></div>
                                    </div>
                                </div>

                                {{-- Details Box --}}
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2 flex-grow">
                                    @if ($prog)
                                        <div class="flex items-center gap-2 text-xs font-bold text-slate-800">
                                            <i
                                                class="bi {{ $prog->icon ?? 'bi-mortarboard-fill' }} text-blue-600 text-sm shrink-0"></i>
                                            <span class="truncate"
                                                title="{{ $prog->name }}">{{ $prog->name }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                        <i class="bi bi-person-badge text-blue-600 text-sm shrink-0"></i>
                                        <span>DNI: {{ $u->dni ?? 'N/A' }}</span>
                                    </div>

                                    @if ($u && $u->phone)
                                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                                            <i class="bi bi-telephone-fill text-blue-600 text-sm shrink-0"></i>
                                            <span>{{ $u->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== WHY STUDENT COUNCIL MATTERS SECTION ===== --}}
    <section class="py-24 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span
                    class="inline-flex items-center gap-1.5 py-1.5 px-4 rounded-full text-sm font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">
                    Ventajas y Funciones
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-3 tracking-tight">
                    ¿Cuál es el rol del Consejo de Estudiantes?
                </h2>
                <p class="text-lg sm:text-xl text-slate-600 mt-4 leading-relaxed">
                    Estructura orientada a fortalecer la convivencia institucional, el desarrollo estudiantil y el respeto
                    de los derechos del alumnado.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Point 1 --}}
                <div
                    class="p-8 rounded-2xl bg-white border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-megaphone-fill text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Voz Democrática</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Transmite propuestas e inquietudes directamente a la Dirección General e instancias académicas.
                        </p>
                    </div>
                </div>

                {{-- Point 2 --}}
                <div
                    class="p-8 rounded-2xl bg-white border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-shield-check text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Defensa de Derechos</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Acompaña a los estudiantes velando por el cumplimiento estricto del reglamento y garantías
                            académicas.
                        </p>
                    </div>
                </div>

                {{-- Point 3 --}}
                <div
                    class="p-8 rounded-2xl bg-white border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-trophy-fill text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Deporte y Cultura</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Organiza olimpiadas, talleres culturales y jornadas que integran a los 5 programas de estudio.
                        </p>
                    </div>
                </div>

                {{-- Point 4 --}}
                <div
                    class="p-8 rounded-2xl bg-white border border-slate-100 hover:shadow-md transition duration-300 flex flex-col justify-between">
                    <div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 shadow-sm">
                            <i class="bi bi-heart-pulse-fill text-2xl"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-lg mb-3">Bienestar Integral</h3>
                        <p class="text-base text-slate-600 leading-relaxed font-medium">
                            Promueve iniciativas comunitarias, conciencia ecológica y apoyo social para los estudiantes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA / CANAL DE ATENCIÓN ESTUDIANTIL ===== --}}
    <section class="py-20 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                ¿Tienes una consulta o iniciativa para tu carrera?
            </h2>
            <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                Ponte en contacto con la directiva o los secretarios de tu especialidad para canalizar tus sugerencias e
                inquietudes.
            </p>
            <div class="pt-4">
                <a href="{{ route('mesa-de-partes') }}"
                    class="inline-flex items-center justify-center px-8 py-4 text-base font-black text-slate-950 bg-sky-400 hover:bg-white rounded-xl transition duration-200 shadow-xl">
                    <i class="bi bi-inbox-fill mr-2.5 text-xl"></i> Mesa de Partes Virtual
                </a>
            </div>
        </div>
    </section>
@endsection
