@extends('layouts.app')
@php
    // Mapping of dynamic premium visual metadata and deep content based on the program slug
    $programSlug = $program->slug;
    
    $programMeta = [
        'produccion-agropecuaria' => [
            'icon' => 'bi-tree-fill',
            'color' => 'emerald',
            'gradient' => 'from-emerald-600 to-teal-800',
            'bg_light' => 'bg-emerald-50',
            'border' => 'border-emerald-100',
            'text' => 'text-emerald-700',
            'badge' => 'Sector Primario & Agro',
            'perfil' => 'El Profesional Técnico en Producción Agropecuaria es un especialista altamente capacitado para dirigir, supervisar y optimizar explotaciones agrícolas y ganaderas de mediana y gran escala. Su formación integral le faculta para aplicar tecnologías agrarias avanzadas, gestionar la sanidad vegetal y animal de manera responsable, procesar materias primas con estrictas normas de inocuidad y desarrollar modelos de negocio rentables y sostenibles en el sector.',
            'competencias' => [
                [
                    'title' => 'Gestión Fitosanitaria',
                    'desc' => 'Identifica, diagnostica y controla plagas y enfermedades mediante métodos químicos, orgánicos y biológicos, asegurando la productividad de los cultivos.',
                    'icon' => 'fa-seedling'
                ],
                [
                    'title' => 'Tecnología de Riego',
                    'desc' => 'Diseña y administra sistemas de riego por goteo, aspersión y gravedad para un uso eficiente del recurso hídrico.',
                    'icon' => 'fa-droplet'
                ],
                [
                    'title' => 'Sanidad Animal',
                    'desc' => 'Supervisa calendarios sanitarios, alimentación balanceada, reproducción y primeros auxilios de ganadería regional (vacuno, porcino, avícola).',
                    'icon' => 'fa-cow'
                ],
                [
                    'title' => 'Procesamiento de Alimentos',
                    'desc' => 'Domina técnicas de conservación, empaque y transformación agroindustrial de cultivos y derivados ganaderos.',
                    'icon' => 'fa-boxes-packing'
                ]
            ],
            'campo_laboral' => [
                'Empresas y fundos agroexportadores de cultivos intensivos.',
                'Centros de producción pecuaria (granjas, establos lecheros, avícolas).',
                'Empresas comercializadoras de insumos y equipos agrícolas.',
                'Organismos no gubernamentales (ONGs) y cooperativas agrarias.',
                'Consultor técnico independiente y administrador de agronegocios propios.'
            ],
            'requisitos' => [
                'Certificado de estudios de Educación Secundaria completa (original).',
                'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                'Partida de Nacimiento original y actualizada.',
                'Carpeta de postulante y constancia de pago de inscripción.',
                '02 fotografías a color tamaño carnet (fondo blanco).'
            ]
        ],
        'enfermeria-tecnica' => [
            'icon' => 'bi-heart-pulse-fill',
            'color' => 'rose',
            'gradient' => 'from-rose-600 to-red-800',
            'bg_light' => 'bg-rose-50',
            'border' => 'border-rose-100',
            'text' => 'text-rose-700',
            'badge' => 'Ciencias de la Salud',
            'perfil' => 'El Profesional Técnico en Enfermería Técnica está preparado para brindar cuidados integrales de salud a personas en diferentes etapas del ciclo vital, tanto en contextos hospitalarios como comunitarios. Desarrolla acciones de promoción de hábitos saludables, prevención de enfermedades y asistencia en procedimientos clínicos y de recuperación, siempre rigiéndose por elevados estándares éticos y de bioseguridad.',
            'competencias' => [
                [
                    'title' => 'Atención Básica de Salud',
                    'desc' => 'Asiste en la toma de signos vitales, aseo, confort y alimentación de pacientes hospitalizados o en cama.',
                    'icon' => 'fa-stethoscope'
                ],
                [
                    'title' => 'Procedimientos Clínicos',
                    'desc' => 'Colabora en la administración de medicamentos, curación de heridas y colocación de vías bajo estricta supervisión profesional.',
                    'icon' => 'fa-syringe'
                ],
                [
                    'title' => 'Primeros Auxilios',
                    'desc' => 'Capacidad de respuesta inmediata y estabilización en emergencias médicas, accidentes y desastres naturales.',
                    'icon' => 'fa-kit-medical'
                ],
                [
                    'title' => 'Salud Comunitaria',
                    'desc' => 'Participa activamente en campañas de vacunación, prevención de brotes epidémicos y educación sanitaria familiar.',
                    'icon' => 'fa-users-medical'
                ]
            ],
            'campo_laboral' => [
                'Hospitales y centros asistenciales del MINSA y EsSalud.',
                'Clínicas de salud privadas, policlínicos y centros de rehabilitación.',
                'Tópicos de salud ocupacional en empresas agroindustriales e industriales.',
                'Centros de atención geriátrica y asilos residenciales.',
                'Servicios de enfermería y cuidado domiciliario privado.'
            ],
            'requisitos' => [
                'Certificado de estudios de Educación Secundaria completa (original).',
                'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                'Partida de Nacimiento original y actualizada.',
                'Carpeta de postulante y constancia de pago de inscripción.',
                '02 fotografías a color tamaño carnet (fondo blanco).'
            ]
        ],
        'administracion-de-redes-y-comunicaciones' => [
            'icon' => 'bi-router-fill',
            'color' => 'blue',
            'gradient' => 'from-blue-600 to-indigo-800',
            'bg_light' => 'bg-blue-50',
            'border' => 'border-blue-100',
            'text' => 'text-blue-700',
            'badge' => 'Tecnología & Redes',
            'perfil' => 'El egresado en Administración de Redes y Comunicaciones es un profesional tecnológico clave para la infraestructura digital moderna. Domina el diseño, implementación, configuración y mantenimiento de redes informáticas por cable y fibra óptica, la seguridad de la información corporativa, la administración de servidores en la nube y físicos, y el soporte técnico especializado en tecnologías de información.',
            'competencias' => [
                [
                    'title' => 'Infraestructura de Redes',
                    'desc' => 'Diseña, cablea y estructura redes LAN/WAN utilizando switches, routers e infraestructuras inalámbricas modernas.',
                    'icon' => 'fa-network-wired'
                ],
                [
                    'title' => 'Seguridad Informática',
                    'desc' => 'Configura cortafuegos (firewalls), VPNs y políticas de acceso para proteger la información y datos corporativos.',
                    'icon' => 'fa-shield-halved'
                ],
                [
                    'title' => 'Soporte y Diagnóstico',
                    'desc' => 'Soluciona fallas de hardware y software en computadoras, servidores y periféricos de manera veloz.',
                    'icon' => 'fa-screwdriver-wrench'
                ],
                [
                    'title' => 'Administración Cloud',
                    'desc' => 'Maneja sistemas operativos de servidor (Windows Server, Linux) y servicios web o bases de datos.',
                    'icon' => 'fa-cloud'
                ]
            ],
            'campo_laboral' => [
                'Empresas proveedoras de servicios de Internet y Telecomunicaciones.',
                'Áreas de soporte y sistemas de empresas públicas y privadas.',
                'Administrador de centros de datos (Data Centers) corporativos.',
                'Consultor y desarrollador independiente de proyectos de redes y cableado.',
                'Soporte técnico integral y venta de soluciones informáticas.'
            ],
            'requisitos' => [
                'Certificado de estudios de Educación Secundaria completa (original).',
                'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                'Partida de Nacimiento original y actualizada.',
                'Carpeta de postulante y constancia de pago de inscripción.',
                '02 fotografías a color tamaño carnet (fondo blanco).'
            ]
        ],
        'asistencia-administrativa' => [
            'icon' => 'bi-briefcase-fill',
            'color' => 'purple',
            'gradient' => 'from-purple-600 to-indigo-800',
            'bg_light' => 'bg-purple-50',
            'border' => 'border-purple-100',
            'text' => 'text-purple-700',
            'badge' => 'Gestión Corporativa',
            'perfil' => 'El Profesional Técnico en Asistencia Administrativa constituye el soporte estratégico fundamental para la gestión interna de cualquier organización. Su formación integral le permite coordinar flujos documentales físicos y digitales, planificar agendas y eventos directivos, gestionar la comunicación interna y externa, brindar asistencia en contabilidad y finanzas básicas, e integrar eficientemente herramientas informáticas de oficina.',
            'competencias' => [
                [
                    'title' => 'Gestión Documental',
                    'desc' => 'Clasifica, archiva y digitaliza correspondencia y documentos corporativos bajo estrictas normas de confidencialidad.',
                    'icon' => 'fa-file-invoice'
                ],
                [
                    'title' => 'Ofimática Avanzada',
                    'desc' => 'Domina procesadores de texto, hojas de cálculo complejas, presentaciones dinámicas y herramientas colaborativas online.',
                    'icon' => 'fa-laptop-file'
                ],
                [
                    'title' => 'Atención al Cliente',
                    'desc' => 'Aplica técnicas de oratoria, comunicación asertiva y relaciones públicas para atender visitas y llamadas ejecutivas.',
                    'icon' => 'fa-headset'
                ],
                [
                    'title' => 'Gestión Financiera',
                    'desc' => 'Apoya en la facturación, registro de gastos, control de caja chica y conciliaciones de cuentas básicas.',
                    'icon' => 'fa-calculator'
                ]
            ],
            'campo_laboral' => [
                'Asistente de gerencia en empresas privadas de cualquier rubro mercantil.',
                'Áreas de secretaría, trámite documentario y mesa de partes públicas.',
                'Auxiliar administrativo en departamentos de Recursos Humanos y Contabilidad.',
                'Organizaciones financieras, cooperativas de crédito y cajas de ahorro.',
                'Gestor independiente de servicios administrativos virtuales (Virtual Assistant).'
            ],
            'requisitos' => [
                'Certificado de estudios de Educación Secundaria completa (original).',
                'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                'Partida de Nacimiento original y actualizada.',
                'Carpeta de postulante y constancia de pago de inscripción.',
                '02 fotografías a color tamaño carnet (fondo blanco).'
            ]
        ],
        'manejo-forestal' => [
            'icon' => 'bi-globe-americas',
            'color' => 'teal',
            'gradient' => 'from-teal-600 to-emerald-800',
            'bg_light' => 'bg-teal-50',
            'border' => 'border-teal-100',
            'text' => 'text-teal-700',
            'badge' => 'Conservación & Bosques',
            'perfil' => 'El egresado del programa de Manejo Forestal se posiciona como un guardián y gestor técnico del patrimonio natural. Está capacitado para recolectar información florística y de fauna, levantar inventarios y censos forestales precisos, controlar la silvicultura de plantaciones comerciales, supervisar cosechas y aprovechamiento maderable bajo lineamientos legales, y diseñar estrategias de conservación y ecoturismo.',
            'competencias' => [
                [
                    'title' => 'Inventario Forestal',
                    'desc' => 'Utiliza GPS, brújulas e instrumentos dasométricos para registrar volumen y biodiversidad de bosques naturales.',
                    'icon' => 'fa-compass'
                ],
                [
                    'title' => 'Silvicultura Aplicada',
                    'desc' => 'Dirige viveros forestales, plantaciones, podas y raleos para la reforestación y restauración ecológica.',
                    'icon' => 'fa-tree'
                ],
                [
                    'title' => 'Aprovechamiento Legal',
                    'desc' => 'Supervisa la tala dirigida, arrastre y cubicación de madera de acuerdo a los planes operativos y ambientales.',
                    'icon' => 'fa-truck-wood'
                ],
                [
                    'title' => 'Conservación Ecológica',
                    'desc' => 'Diseña proyectos de protección de microcuencas, prevención de incendios forestales y control de deforestación.',
                    'icon' => 'fa-leaf'
                ]
            ],
            'campo_laboral' => [
                'Empresas y concesionarias de aprovechamiento forestal maderable.',
                'Organismos del Estado dedicados a la fiscalización y fauna silvestre (SERFOR, OSINFOR).',
                'Viveros forestales gubernamentales, municipales y privados.',
                'Áreas naturales protegidas y reservas ecológicas nacionales o privadas.',
                'Proyectos de reforestación corporativa y consultoras de impacto ambiental.'
            ],
            'requisitos' => [
                'Certificado de estudios de Educación Secundaria completa (original).',
                'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                'Partida de Nacimiento original y actualizada.',
                'Carpeta de postulante y constancia de pago de inscripción.',
                '02 fotografías a color tamaño carnet (fondo blanco).'
            ]
        ]
    ];
    
    // Default fallback in case slug doesn't match predefined mappings
    $meta = $programMeta[$programSlug] ?? [
        'icon' => 'bi-mortarboard-fill',
        'color' => 'purple',
        'gradient' => 'from-purple-600 to-indigo-800',
        'bg_light' => 'bg-purple-50',
        'border' => 'border-purple-100',
        'text' => 'text-purple-700',
        'badge' => 'Educación Superior',
        'perfil' => $program->description,
        'competencias' => [
            [
                'title' => 'Competencia General',
                'desc' => 'Formación teórica y práctica acorde a las directrices de la especialidad.',
                'icon' => 'fa-graduation-cap'
            ]
        ],
        'campo_laboral' => [
            'Empresas del sector público y privado afines a la especialidad.',
            'Consultoría técnica independiente.',
            'Desarrollo de proyectos y emprendimientos autónomos.'
        ],
        'requisitos' => [
            'Certificado de estudios de Educación Secundaria completa (original).',
            'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
            'Partida de Nacimiento original.',
            'Fotos tamaño carnet.'
        ]
    ];

    $mainImage = $program->images->where('is_main', true)->first() ?? $program->images->first();
    $albumImages = $program->images;
@endphp
@section('title', $program->name . ' — IESTP Francisco Vigo Caballero')
@push('styles')
    <style>
        .glow-effect {
            box-shadow: 0 0 25px -5px rgba(0, 0, 0, 0.08), 0 0 15px -3px rgba(0, 0, 0, 0.03);
        }
        .album-hover {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .album-hover:hover {
            transform: scale(1.04);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.2);
        }
        .timeline-bullet::after {
            content: '';
            position: absolute;
            top: 24px;
            bottom: -24px;
            left: 50%;
            width: 2px;
            background: rgba(15, 23, 42, 0.1);
            transform: translateX(-50%);
        }
        .timeline-item:last-child .timeline-bullet::after {
            display: none;
        }
    </style>
@endpush

@section('content')
    {{-- ===== HERO HEADER SECTION ===== --}}
    <section class="hero-gradient text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-{{ $meta['color'] }}-500/25 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/25 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                {{-- Text column --}}
                <div class="lg:w-7/12 text-left">
                    <span class="inline-flex items-center gap-1.5 bg-{{ $meta['color'] }}-500/25 text-{{ $meta['color'] }}-200 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-6 border border-{{ $meta['color'] }}-500/40">
                        <i class="bi {{ $meta['icon'] }} text-sm"></i>
                        {{ $meta['badge'] }}
                    </span>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black mb-6 tracking-tight leading-tight">
                        {{ $program->name }}
                    </h1>
                    <p class="text-base md:text-lg text-gray-300 mb-8 leading-relaxed max-w-2xl">
                        Prepárate para destacar en el campo laboral de manera rápida y efectiva con nuestro programa formativo integral, gratuito y con certificación oficial.
                    </p>
                    
                    {{-- Quick Metadata Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-6 border-t border-white/10 max-w-xl">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Duración</p>
                            <p class="text-lg font-bold text-{{ $meta['color'] }}-300 mt-1">3 Años</p>
                            <p class="text-xs text-gray-500">6 Ciclos Académicos</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Certificaciones</p>
                            <p class="text-lg font-bold text-{{ $meta['color'] }}-300 mt-1">Modulares</p>
                            <p class="text-xs text-gray-500">Anuales de Minedu</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <p class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Inversión</p>
                            <p class="text-lg font-bold text-{{ $meta['color'] }}-300 mt-1">Gratuito</p>
                            <p class="text-xs text-gray-500">Educación Pública</p>
                        </div>
                    </div>
                </div>
                
                {{-- Visual column (Cover Image) --}}
                <div class="lg:w-5/12 w-full">
                    <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent z-10"></div>
                        @if ($mainImage)
                            <img src="{{ Str::startsWith($mainImage->path, ['http://', 'https://']) ? $mainImage->path : Storage::url($mainImage->path) }}" 
                                 alt="{{ $program->name }}" 
                                 class="w-full h-80 lg:h-96 object-cover transform scale-105 hover:scale-100 transition-transform duration-700">
                        @else
                            <div class="w-full h-80 lg:h-96 bg-gradient-to-br {{ $meta['gradient'] }} flex items-center justify-center">
                                <i class="bi {{ $meta['icon'] }} text-9xl text-white/20"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MAIN LAYOUT GRID ===== --}}
    <section class="py-16 bg-gray-50/50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                {{-- LEFT/MAIN COLUMN (2 spans) --}}
                <div class="lg:col-span-2 space-y-12">
                    
                    {{-- About card --}}
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-2 h-8 bg-{{ $meta['color'] }}-500 rounded-full"></span>
                            Sobre la Carrera Profesional
                        </h2>
                        <div class="prose max-w-none text-gray-600 leading-relaxed space-y-4">
                            <p class="text-base text-gray-700 font-medium">
                                {{ $meta['perfil'] }}
                            </p>
                            @if($program->description && $program->description !== $meta['perfil'])
                                <p class="text-sm">
                                    {{ $program->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Competencies --}}
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-2 h-8 bg-{{ $meta['color'] }}-500 rounded-full"></span>
                            Perfil de Competencias Específicas
                        </h2>
                        <p class="text-gray-500 mb-8 text-sm">
                            A lo largo de los 3 años de formación práctica, el estudiante del programa desarrollará capacidades técnicas en las siguientes áreas de especialización:
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($meta['competencias'] as $item)
                                <div class="flex gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-{{ $meta['color'] }}-200 transition-colors">
                                    <div class="w-12 h-12 rounded-xl bg-{{ $meta['color'] }}-100 text-{{ $meta['color'] }}-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $item['icon'] }} text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-base mb-1">{{ $item['title'] }}</h3>
                                        <p class="text-xs text-gray-600 leading-relaxed">{{ $item['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Curriculum & Modular certifications --}}
                    @if ($program->modules->isNotEmpty())
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                            <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 bg-{{ $meta['color'] }}-500 rounded-full"></span>
                                Plan de Estudios y Certificaciones Modulares
                            </h2>
                            <p class="text-gray-500 mb-8 text-sm">
                                Nuestro plan curricular está estructurado modularmente para permitir una rápida inserción laboral. Cada año académico completado te acredita con un certificado oficial del Ministerio de Educación:
                            </p>
                            
                            <div class="space-y-6">
                                @foreach ($program->modules as $index => $module)
                                    <div class="timeline-item flex gap-6 relative">
                                        {{-- Bullet decoration --}}
                                        <div class="timeline-bullet w-10 h-10 rounded-full bg-{{ $meta['color'] }}-500 text-white font-extrabold flex items-center justify-center shrink-0 relative z-10 shadow-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        
                                        {{-- Content --}}
                                        <div class="flex-grow bg-gray-50 border border-gray-100 rounded-2xl p-6 relative">
                                            <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                                                <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full bg-{{ $meta['color'] }}-100 text-{{ $meta['color'] }}-800">
                                                    Módulo Técnico {{ $index + 1 }}
                                                </span>
                                                <span class="text-xs text-gray-400 font-medium flex items-center gap-1">
                                                    <i class="bi bi-calendar3"></i> Año Académico {{ $index + 1 }}
                                                </span>
                                            </div>
                                            <h3 class="font-extrabold text-gray-900 text-base mb-2">
                                                {{ $module->module }}
                                            </h3>
                                            <p class="text-xs text-gray-600 leading-relaxed">
                                                Certificación otorgada al concluir satisfactoriamente las asignaturas y las horas de experiencias formativas correspondientes a este módulo.
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- PHOTO ALBUM / GALLERY --}}
                    @if ($albumImages->isNotEmpty())
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm"
                             x-data="{ 
                                open: false, 
                                activeIndex: 0, 
                                images: [
                                    @foreach($albumImages as $img)
                                        '{{ Str::startsWith($img->path, ['http://', 'https://']) ? $img->path : Storage::url($img->path) }}',
                                    @endforeach
                                ],
                                next() {
                                    this.activeIndex = (this.activeIndex + 1) % this.images.length;
                                },
                                prev() {
                                    this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                                }
                             }"
                             @keyup.window.escape="open = false"
                             @keyup.window.right="if (open) next()"
                             @keyup.window.left="if (open) prev()">
                            
                            <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-2 h-8 bg-{{ $meta['color'] }}-500 rounded-full"></span>
                                Álbum de Fotos & Galería
                            </h2>
                            <p class="text-gray-500 mb-8 text-sm">
                                Conoce nuestras aulas, laboratorios, parcelas de producción o actividades prácticas que realizan nuestros estudiantes en Uchiza.
                            </p>
                            
                            {{-- Grid layout --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($albumImages as $idx => $img)
                                    @php
                                        $imgPath = Str::startsWith($img->path, ['http://', 'https://']) ? $img->path : Storage::url($img->path);
                                    @endphp
                                    <div class="relative group rounded-2xl overflow-hidden cursor-pointer album-hover aspect-video border border-gray-100"
                                         @click="open = true; activeIndex = {{ $idx }}">
                                        <img src="{{ $imgPath }}" 
                                             alt="Galería {{ $program->name }}" 
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-lg">
                                                <i class="bi bi-eye-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- LIGHTBOX MODAL --}}
                            <template x-teleport="body">
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/95 backdrop-blur-sm p-4 select-none"
                                     style="display: none;">
                                    
                                    {{-- Close area --}}
                                    <div class="absolute inset-0" @click="open = false"></div>
                                    
                                    {{-- Lightbox content --}}
                                    <div class="relative max-w-5xl w-full flex flex-col items-center justify-center z-10"
                                         @click.stop>
                                        
                                        {{-- Image block --}}
                                        <div class="relative max-h-[80vh] w-full flex items-center justify-center overflow-hidden rounded-2xl">
                                            <img :src="images[activeIndex]" 
                                                 class="max-h-[80vh] max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300">
                                            
                                            {{-- Next/Prev Buttons (inside image container for comfort) --}}
                                            <button @click="prev()" 
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-sm border border-white/10 transition-colors">
                                                <i class="bi bi-chevron-left text-xl"></i>
                                            </button>
                                            <button @click="next()" 
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-sm border border-white/10 transition-colors">
                                                <i class="bi bi-chevron-right text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        {{-- Footer metadata & actions --}}
                                        <div class="w-full flex items-center justify-between text-white mt-6 px-2">
                                            <span class="text-sm font-semibold tracking-wide">
                                                {{ $program->name }} — Foto <span x-text="activeIndex + 1"></span> de <span x-text="images.length"></span>
                                            </span>
                                            <button @click="open = false" 
                                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-bold border border-white/10 transition-colors">
                                                <i class="bi bi-x-lg"></i> Cerrar
                                            </button>
                                        </div>
                                        
                                        {{-- Thumbnails indicators --}}
                                        <div class="flex items-center justify-center gap-2 mt-4 flex-wrap">
                                            <template x-for="(img, idx) in images" :key="idx">
                                                <button @click="activeIndex = idx" 
                                                        class="w-3 h-3 rounded-full transition-all"
                                                        :class="activeIndex === idx ? 'bg-{{ $meta['color'] }}-500 scale-125' : 'bg-white/40 hover:bg-white/60'"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                        </div>
                    @endif

                </div>
                
                {{-- RIGHT/SIDEBAR COLUMN (1 span) --}}
                <div class="space-y-8">
                    
                    {{-- Technical sheet --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm glow-effect">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                            <i class="bi bi-info-square text-{{ $meta['color'] }}-500 text-lg"></i>
                            Ficha Técnica
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-award text-base"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Título Oficial</p>
                                    <p class="text-xs text-gray-700 font-semibold mt-0.5 leading-snug">
                                        Profesional Técnico a Nombre de la Nación
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-hourglass-split text-base"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Duración de Estudios</p>
                                    <p class="text-xs text-gray-700 font-semibold mt-0.5">3 Años (6 semestres académicos)</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-clock text-base"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Horario de Clases</p>
                                    <p class="text-xs text-gray-700 font-semibold mt-0.5">Lunes a Viernes 7:30 am – 1:30 pm</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-lg bg-gray-50 text-gray-500 flex items-center justify-center shrink-0 border border-gray-100">
                                    <i class="bi bi-cash-coin text-base"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Matrícula y Pensión</p>
                                    <p class="text-xs text-gray-700 font-semibold mt-0.5">Costo Social Anual (Sin mensualidades)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Career Prospects / Campo Laboral --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm glow-effect">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                            <i class="bi bi-briefcase text-{{ $meta['color'] }}-500 text-lg"></i>
                            Campo Laboral
                        </h3>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                            Al culminar la carrera podrás desempeñarte eficazmente en:
                        </p>
                        
                        <ul class="space-y-3">
                            @foreach ($meta['campo_laboral'] as $item)
                                <li class="text-xs text-gray-700 flex items-start gap-2.5 leading-relaxed">
                                    <i class="bi bi-check2-circle text-{{ $meta['color'] }}-500 shrink-0 mt-0.5 text-sm"></i>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Admission Requirements --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm glow-effect">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2.5">
                            <i class="bi bi-clipboard2-check text-{{ $meta['color'] }}-500 text-lg"></i>
                            Requisitos de Matrícula
                        </h3>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                            Documentación requerida para formalizar tu postulación:
                        </p>
                        
                        <ul class="space-y-3">
                            @foreach ($meta['requisitos'] as $item)
                                <li class="text-xs text-gray-700 flex items-start gap-2.5 leading-relaxed">
                                    <i class="bi bi-dot text-{{ $meta['color'] }}-500 shrink-0 text-xl -mt-1"></i>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- CTA Box --}}
                    <div class="bg-gradient-to-br {{ $meta['gradient'] }} rounded-3xl p-6 text-white text-center shadow-lg relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:12px_12px] z-0"></div>
                        <div class="relative z-10">
                            <h4 class="font-extrabold text-lg mb-3">¿Listo para postular?</h4>
                            <p class="text-xs text-white/80 leading-relaxed mb-6">
                                El examen de admisión está cerca. Asegura tu futuro profesional técnico sin pagar mensualidades.
                            </p>
                            <a href="{{ route('examen-de-admision') }}" 
                               class="inline-block w-full bg-white text-slate-900 hover:bg-gray-100 py-3 rounded-xl font-bold text-xs tracking-wider uppercase transition-colors shadow-md">
                                Iniciar Inscripción
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
