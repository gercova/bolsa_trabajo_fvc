<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\StudyProgram;
use App\Models\ModularCertification;
use App\Models\ProgramMeta;
use App\Models\ProgramCompetency;
use App\Models\ProgramJobField;
use App\Models\ProgramRequirement;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar registros existentes
        ModularCertification::where('model_type', StudyProgram::class)->delete();
        ProgramMeta::query()->delete();
        ProgramCompetency::query()->delete();
        ProgramJobField::query()->delete();
        ProgramRequirement::query()->delete();
        Image::where('imageable_type', StudyProgram::class)->delete();
        StudyProgram::query()->delete();

        $programMeta = [
            'Producción Agropecuaria' => [
                'icon' => 'bi-tree-fill',
                'accent' => 'emerald',
                'bg_badge' => 'bg-emerald-50 text-emerald-800 border-emerald-100',
                'tag' => 'Producción & Campo',
                'color_bar' => 'bg-emerald-500',
                'glow_class' => 'bg-emerald-500/20',
                'badge_class' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
                'accent_text' => 'text-emerald-300',
                'bullet_class' => 'bg-emerald-600',
                'icon_bg_class' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                'border_hover_class' => 'hover:border-emerald-300',
                'badge_module_class' => 'bg-emerald-100 text-emerald-800',
                'sidebar_icon_class' => 'text-emerald-600',
                'cta_bg_class' => 'from-emerald-600 to-teal-800',
                'bar_color_class' => 'bg-emerald-500',
                'competencias' => [
                    [
                        'title' => 'Competencia General',
                        'desc' => 'Formación teórica y práctica acorde a las directrices de la especialidad en producción agropecuaria.',
                        'icon' => 'fa-graduation-cap',
                    ],
                    [
                        'title' => 'Competencia Técnica',
                        'desc' => 'Capacidad para implementar y supervisar procesos productivos agrícolas y pecuarios.',
                        'icon' => 'fa-tractor',
                    ],
                    [
                        'title' => 'Competencia Emprendedora',
                        'desc' => 'Habilidad para gestionar proyectos agropecuarios con criterios de rentabilidad y sostenibilidad.',
                        'icon' => 'fa-hand-holding-usd',
                    ],
                ],
                'campo_laboral' => [
                    'Empresas agropecuarias públicas y privadas.',
                    'Consultoría técnica independiente en el sector agropecuario.',
                    'Emprendimientos en procesamiento y comercialización de productos agropecuarios.',
                    'Instituciones de investigación y desarrollo agrícola.',
                ],
                'requisitos' => [
                    'Certificado de estudios de Educación Secundaria completa (original).',
                    'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                    'Partida de Nacimiento original.',
                    'Fotos tamaño carnet (2).',
                    'Comprobante de pago por derecho de inscripción.',
                ],
            ],
            'Enfermería Técnica' => [
                'icon' => 'bi-heart-pulse-fill',
                'accent' => 'rose',
                'bg_badge' => 'bg-rose-50 text-rose-800 border-rose-100',
                'tag' => 'Ciencias de la Salud',
                'color_bar' => 'bg-rose-500',
                'glow_class' => 'bg-rose-500/20',
                'badge_class' => 'bg-rose-500/15 text-rose-300 border-rose-500/30',
                'accent_text' => 'text-rose-300',
                'bullet_class' => 'bg-rose-600',
                'icon_bg_class' => 'bg-rose-50 text-rose-600 border-rose-100',
                'border_hover_class' => 'hover:border-rose-300',
                'badge_module_class' => 'bg-rose-100 text-rose-800',
                'sidebar_icon_class' => 'text-rose-600',
                'cta_bg_class' => 'from-rose-600 to-pink-800',
                'bar_color_class' => 'bg-rose-500',
                'competencias' => [
                    [
                        'title' => 'Competencia Asistencial',
                        'desc' => 'Capacidad para brindar cuidados integrales de enfermería en diferentes etapas de vida.',
                        'icon' => 'fa-heartbeat',
                    ],
                    [
                        'title' => 'Competencia Preventiva',
                        'desc' => 'Habilidad para realizar actividades de promoción y prevención de la salud comunitaria.',
                        'icon' => 'fa-shield-alt',
                    ],
                    [
                        'title' => 'Competencia Ética',
                        'desc' => 'Actuación profesional basada en principios éticos y respeto a la dignidad de las personas.',
                        'icon' => 'fa-hand-holding-heart',
                    ],
                ],
                'campo_laboral' => [
                    'Hospitales, clínicas y centros de salud.',
                    'Programas de salud comunitaria.',
                    'Centros de atención primaria y postas médicas.',
                    'Organizaciones de salud y bienestar social.',
                ],
                'requisitos' => [
                    'Certificado de estudios de Educación Secundaria completa (original).',
                    'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                    'Partida de Nacimiento original.',
                    'Fotos tamaño carnet (2).',
                    'Comprobante de pago por derecho de inscripción.',
                    'Certificado de salud física y mental.',
                ],
            ],
            'Administración de Redes y Comunicaciones' => [
                'icon' => 'bi-router-fill',
                'accent' => 'sky',
                'bg_badge' => 'bg-sky-50 text-sky-800 border-sky-100',
                'tag' => 'Soporte e Infraestructura TI',
                'color_bar' => 'bg-sky-500',
                'glow_class' => 'bg-sky-500/20',
                'badge_class' => 'bg-sky-500/15 text-sky-300 border-sky-500/30',
                'accent_text' => 'text-sky-300',
                'bullet_class' => 'bg-sky-600',
                'icon_bg_class' => 'bg-sky-50 text-sky-600 border-sky-100',
                'border_hover_class' => 'hover:border-sky-300',
                'badge_module_class' => 'bg-sky-100 text-sky-800',
                'sidebar_icon_class' => 'text-sky-600',
                'cta_bg_class' => 'from-sky-600 to-blue-800',
                'bar_color_class' => 'bg-sky-500',
                'competencias' => [
                    [
                        'title' => 'Competencia Técnica',
                        'desc' => 'Habilidad para implementar, configurar y administrar infraestructura de redes y comunicaciones.',
                        'icon' => 'fa-network-wired',
                    ],
                    [
                        'title' => 'Competencia de Seguridad',
                        'desc' => 'Capacidad para implementar políticas de seguridad en redes según perfiles de usuarios.',
                        'icon' => 'fa-shield-alt',
                    ],
                    [
                        'title' => 'Competencia de Soporte',
                        'desc' => 'Habilidad para brindar soporte técnico y monitoreo de equipos y servicios de TI.',
                        'icon' => 'fa-headset',
                    ],
                ],
                'campo_laboral' => [
                    'Empresas y organizaciones del sector público y privado.',
                    'Proveedores de servicios de internet y telecomunicaciones.',
                    'Consultoría técnica en infraestructura de TI.',
                    'Emprendimientos en servicios tecnológicos.',
                ],
                'requisitos' => [
                    'Certificado de estudios de Educación Secundaria completa (original).',
                    'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                    'Partida de Nacimiento original.',
                    'Fotos tamaño carnet (2).',
                    'Comprobante de pago por derecho de inscripción.',
                    'Conocimientos básicos de informática (deseable).',
                ],
            ],
            'Asistencia Administrativa' => [
                'icon' => 'bi-briefcase-fill',
                'accent' => 'blue',
                'bg_badge' => 'bg-blue-50 text-blue-800 border-blue-100',
                'tag' => 'Administración & Finanzas',
                'color_bar' => 'bg-blue-600',
                'glow_class' => 'bg-blue-500/20',
                'badge_class' => 'bg-blue-500/15 text-blue-300 border-blue-500/30',
                'accent_text' => 'text-blue-300',
                'bullet_class' => 'bg-blue-600',
                'icon_bg_class' => 'bg-blue-50 text-blue-600 border-blue-100',
                'border_hover_class' => 'hover:border-blue-300',
                'badge_module_class' => 'bg-blue-100 text-blue-800',
                'sidebar_icon_class' => 'text-blue-600',
                'cta_bg_class' => 'from-blue-600 to-indigo-800',
                'bar_color_class' => 'bg-blue-500',
                'competencias' => [
                    [
                        'title' => 'Competencia Organizativa',
                        'desc' => 'Capacidad para planificar, organizar y controlar actividades y eventos administrativos.',
                        'icon' => 'fa-tasks',
                    ],
                    [
                        'title' => 'Competencia Documental',
                        'desc' => 'Habilidad en la gestión y administración de documentación organizacional.',
                        'icon' => 'fa-file-alt',
                    ],
                    [
                        'title' => 'Competencia Comunicativa',
                        'desc' => 'Capacidad para comunicarse de manera efectiva en el ámbito administrativo.',
                        'icon' => 'fa-comments',
                    ],
                ],
                'campo_laboral' => [
                    'Instituciones públicas y privadas.',
                    'Empresas y organizaciones administrativas.',
                    'Consultoría en gestión administrativa.',
                    'Emprendimientos en servicios de oficina y gestión.',
                ],
                'requisitos' => [
                    'Certificado de estudios de Educación Secundaria completa (original).',
                    'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                    'Partida de Nacimiento original.',
                    'Fotos tamaño carnet (2).',
                    'Comprobante de pago por derecho de inscripción.',
                ],
            ],
            'Manejo Forestal' => [
                'icon' => 'bi-globe-americas',
                'accent' => 'teal',
                'bg_badge' => 'bg-teal-50 text-teal-800 border-teal-100',
                'tag' => 'Recursos Naturales',
                'color_bar' => 'bg-teal-500',
                'glow_class' => 'bg-teal-500/20',
                'badge_class' => 'bg-teal-500/15 text-teal-300 border-teal-500/30',
                'accent_text' => 'text-teal-300',
                'bullet_class' => 'bg-teal-600',
                'icon_bg_class' => 'bg-teal-50 text-teal-600 border-teal-100',
                'border_hover_class' => 'hover:border-teal-300',
                'badge_module_class' => 'bg-teal-100 text-teal-800',
                'sidebar_icon_class' => 'text-teal-600',
                'cta_bg_class' => 'from-teal-600 to-cyan-800',
                'bar_color_class' => 'bg-teal-500',
                'competencias' => [
                    [
                        'title' => 'Competencia Técnica Forestal',
                        'desc' => 'Capacidad para gestionar y aprovechar recursos forestales de manera sostenible.',
                        'icon' => 'fa-tree',
                    ],
                    [
                        'title' => 'Competencia Ambiental',
                        'desc' => 'Habilidad para preservar el medio ambiente y conservar la biodiversidad.',
                        'icon' => 'fa-leaf',
                    ],
                    [
                        'title' => 'Competencia Emprendedora',
                        'desc' => 'Capacidad para desarrollar proyectos forestales con criterios de sostenibilidad.',
                        'icon' => 'fa-seedling',
                    ],
                ],
                'campo_laboral' => [
                    'Instituciones forestales públicas y privadas.',
                    'Empresas de manejo y aprovechamiento forestal.',
                    'Consultoría en manejo sostenible de recursos naturales.',
                    'Organizaciones de conservación y desarrollo sostenible.',
                ],
                'requisitos' => [
                    'Certificado de estudios de Educación Secundaria completa (original).',
                    'Copia simple de Documento Nacional de Identidad (DNI) vigente.',
                    'Partida de Nacimiento original.',
                    'Fotos tamaño carnet (2).',
                    'Comprobante de pago por derecho de inscripción.',
                    'Buena condición física para trabajo de campo.',
                ],
            ],
        ];

        $programsData = [
            [
                'name' => 'Producción Agropecuaria',
                'slug' => 'produccion-agropecuaria',
                'description' => 'El Profesional Técnico en Producción Agropecuaria tiene una sólida formación técnica y humanística, que le permite alcanzar las competencias para incorporarse y desarrollarse, en una explotación agropecuaria familiar y/o empresarial, pequeña o mediana según las características socioculturales, ecológicas y regionales del país, realizando las funciones de supervisión de la infraestructura y los procesos productivos agrícolas y pecuarias, además, del procesamiento y aprovechamiento de los productos agropecuarios para su almacenamiento de acuerdo a los procedimientos establecidos, así como, la comercialización de la producción agrícola y/o pecuaria de acuerdo al requerimiento del mercado y las buenas prácticas agropecuarias, considerando las normas de seguridad, higiene y preservación del medio ambiente con criterio rentabilidad y sostenibilidad, así mismo se comunica efectiva y asertivamente, utiliza el idioma inglés y las tecnologías de la información como soporte a sus actividades, soluciona problemas de su entorno, es innovador y emprendedor, y aplica la ética estableciendo relaciones con respeto y justicia, contribuyendo a una convivencia democrática.',
                'details' => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Producción Agropecuaria",
                'modules' => [
                    'Implementación de Infraestructura Agropecuaria',
                    'Supervisión de Procesos Productivos Agropecuarios',
                    'Procesamiento Primario y Aprovechamiento de Productos Agropecuarios',
                    'Almacenamiento y Comercialización de la Producción Agropecuaria',
                ],
                'album' => [
                    'https://images.unsplash.com/photo-1595974482597-4b8da8879bc5?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1516253593875-bd7ba052fbc5?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name' => 'Enfermería Técnica',
                'slug' => 'enfermeria-tecnica',
                'description' => 'El egresado del Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, del programa de estudio de Enfermería Técnica, es un profesional Técnico que realiza actividades de promoción, prevención, asistencia en las necesidades básicas de la salud; en los cuidados integrales de la persona por etapas de vida y colectiva, aplica la comunicación efectiva, utiliza el inglés, las herramientas informáticas, comprendiendo la cultura ambiental, con ética laboral, en solución de problemas de salud con innovación tecnológica y emprendimiento empresarial de acuerdo a la normativa vigente.',
                'details' => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Enfermería Técnica",
                'modules' => [
                    'Asistencia en Promoción y Prevención de la Salud',
                    'Asistencia en la Atención Básica de la Salud',
                    'Asistencia en la Atención Integral de la Salud',
                ],
                'album' => [
                    'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1581056771107-24ca5f033842?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name' => 'Administración de Redes y Comunicaciones',
                'slug' => 'administracion-de-redes-y-comunicaciones',
                'description' => 'El egresado del Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, del programa de estudio de Administración de Redes y Comunicaciones, brinda asistencia a nivel operativo y functional en los sistemas o servicios de TI, realizando la puesta en producción, implementación, configuración y administración de la infraestructura de redes y servicios de comunicaciones, teniendo en cuenta las políticas de seguridad de acuerdo a los roles y perfiles de los colaboradores de la organización y a la planificación efectuada. Así mismo se comunica de manera clara e interactúa con otras personas en contextos sociales y laborales, empleando el idioma inglés, operando las herramientas informáticas de las TIC, demostrando principios éticos en la solución de problemas de acuerdo a la necesidad del sector productivo y educativo, aplicando el emprendimiento y la innovación, en el ámbito personal, profesional y laboral.',
                'details' => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Administración de Redes y Comunicación",
                'modules' => [
                    'Soporte Técnico y Monitoreo de Equipos',
                    'Implementación de Redes y Comunicaciones',
                    'Administración y Seguridad de Redes y Comunicaciones',
                ],
                'album' => [
                    'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1597852074816-d933c4d2b988?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1600132806370-bf17e65e942f?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name' => 'Asistencia Administrativa',
                'slug' => 'asistencia-administrativa',
                'description' => 'El egresado del Programa de Estudios de Asistencia Administrativa está en la capacidad de programar, controlar y administrar actividades y eventos, además de administrar los recursos y la documentación en función a las políticas y normativas vigentes. Se comunica de manera asertiva y efectiva, emplea el idioma inglés y las tecnologías de la información como apoyo para desarrollar sus actividades técnico profesionales; es ético en el desempeño de sus labores y destaca por sus aptitudes para identificar problemas y brindar alternativas de soluciones innovadoras en los procesos productivos y de servicios. Asimismo, es emprendedor identificando oportunidades de negocios, mejorando así los procesos de servicio con calidad, es responsable en prevención y control progresivo de los impactos ambientales con responsabilidad social y desarrollo sostenible, destaca por su liderazgo y trabajo colaborativo.',
                'details' => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Asistencia Administrativa",
                'modules' => [
                    'Planificación y Organización de Actividades Administrativas',
                    'Gestión de Actividades Organizacionales',
                    'Asistencia en Administración Documentaria',
                ],
                'album' => [
                    'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name' => 'Manejo Forestal',
                'slug' => 'manejo-forestal',
                'description' => 'El egresado de Manejo Forestal, está capacitado para desarrollar procesos en plantaciones forestales como la recolección de la información del bosque natural, teniendo en cuenta el inventario o censo forestal; asimismo, controla la construcción y mantenimiento de caminos forestales, patios, trochas e infraestructura complementaria, para dar sostenimiento a los bosques y sus recursos afines, según el plan general de manejo forestal. De igual modo, controla la ejecución del aprovechamiento/ cosecha, considerando el plan operativo y la normatividad vigente; considerando las normas de seguridad, higiene y preservación del medio ambiente con criterio de rentabilidad y sostenibilidad, así mismo se comunica efectiva y asertivamente, utiliza el idioma inglés y las tecnologías de la información como soporte a sus actividades, soluciona problemas de su entorno, es innovador y emprendedor, y aplica la ética estableciendo relaciones con respeto y justicia, contribuyendo a una convivencia democrática.',
                'details' => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Manejo Forestal",
                'modules' => [
                    'Manejo Silvicultural',
                    'Planificación para el Control del Manejo Forestal',
                    'Aprovechamiento de Recursos Forestales Maderables y No Maderables',
                ],
                'album' => [
                    'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&q=80&w=800',
                ]
            ],
        ];

        foreach ($programsData as $program) {
            // Crear el programa
            $studyProgram = StudyProgram::create([
                'name' => $program['name'],
                'slug' => $program['slug'],
                'description' => $program['description'],
                'details' => $program['details'],
                'is_active' => true,
            ]);

            // Obtener los datos del meta
            $metaData = $programMeta[$program['name']] ?? [
                'icon' => 'bi-mortarboard-fill',
                'accent' => 'blue',
                'bg_badge' => 'bg-blue-50 text-blue-800 border-blue-100',
                'tag' => 'Educación Superior',
                'color_bar' => 'bg-blue-500',
                'glow_class' => 'bg-blue-500/20',
                'badge_class' => 'bg-blue-500/15 text-blue-300 border-blue-500/30',
                'accent_text' => 'text-blue-300',
                'bullet_class' => 'bg-blue-600',
                'icon_bg_class' => 'bg-blue-50 text-blue-600 border-blue-100',
                'border_hover_class' => 'hover:border-blue-300',
                'badge_module_class' => 'bg-blue-100 text-blue-800',
                'sidebar_icon_class' => 'text-blue-600',
                'cta_bg_class' => 'from-blue-600 to-indigo-800',
                'bar_color_class' => 'bg-blue-500',
                'competencias' => [],
                'campo_laboral' => [],
                'requisitos' => [],
            ];

            // Crear meta datos (solo los campos de la tabla program_metas)
            $studyProgram->meta()->create([
                'icon' => $metaData['icon'],
                'accent' => $metaData['accent'],
                'bg_badge' => $metaData['bg_badge'],
                'tag' => $metaData['tag'],
                'color_bar' => $metaData['color_bar'],
                'glow_class' => $metaData['glow_class'] ?? null,
                'badge_class' => $metaData['badge_class'] ?? null,
                'accent_text' => $metaData['accent_text'] ?? null,
                'bullet_class' => $metaData['bullet_class'] ?? null,
                'icon_bg_class' => $metaData['icon_bg_class'] ?? null,
                'border_hover_class' => $metaData['border_hover_class'] ?? null,
                'badge_module_class' => $metaData['badge_module_class'] ?? null,
                'sidebar_icon_class' => $metaData['sidebar_icon_class'] ?? null,
                'cta_bg_class' => $metaData['cta_bg_class'] ?? null,
                'bar_color_class' => $metaData['bar_color_class'] ?? null,
            ]);

            // Crear competencias
            if (isset($metaData['competencias']) && is_array($metaData['competencias'])) {
                foreach ($metaData['competencias'] as $index => $competencia) {
                    $studyProgram->competencies()->create([
                        'title' => $competencia['title'],
                        'description' => $competencia['desc'],
                        'icon' => $competencia['icon'] ?? 'fa-graduation-cap',
                        'order' => $index,
                        'is_active' => true,
                    ]);
                }
            }

            // Crear campo laboral
            if (isset($metaData['campo_laboral']) && is_array($metaData['campo_laboral'])) {
                foreach ($metaData['campo_laboral'] as $index => $field) {
                    $studyProgram->jobFields()->create([
                        'description' => $field,
                        'order' => $index,
                        'is_active' => true,
                    ]);
                }
            }

            // Crear requisitos
            if (isset($metaData['requisitos']) && is_array($metaData['requisitos'])) {
                foreach ($metaData['requisitos'] as $index => $requirement) {
                    $studyProgram->requirements()->create([
                        'description' => $requirement,
                        'order' => $index,
                        'is_active' => true,
                    ]);
                }
            }

            // Crear los módulos de certificación
            foreach ($program['modules'] as $moduleName) {
                ModularCertification::create([
                    'module' => $moduleName,
                    'model_type' => StudyProgram::class,
                    'program_id' => $studyProgram->id,
                    'is_active' => true,
                ]);
            }

            // Crear el álbum de imágenes
            if (isset($program['album'])) {
                foreach ($program['album'] as $index => $imageUrl) {
                    $studyProgram->images()->create([
                        'path' => $imageUrl,
                        'is_main' => $index === 0,
                    ]);
                }
            }
        }
    }
}