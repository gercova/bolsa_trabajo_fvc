<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\StudyProgram;
use App\Models\ModularCertification;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar registros existentes para evitar duplicaciones
        ModularCertification::where('model_type', StudyProgram::class)->delete();
        StudyProgram::query()->delete();
        Image::where('imageable_type', StudyProgram::class)->delete();

        $programsData = [
            [
                'name'        => 'Producción Agropecuaria',
                'slug'        => 'produccion-agropecuaria',
                'description' => 'El Profesional Técnico en Producción Agropecuaria tiene una sólida formación técnica y humanística, que le permite alcanzar las competencias para incorporarse y desarrollarse, en una explotación agropecuaria familiar y/o empresarial, pequeña o mediana según las características socioculturales, ecológicas y regionales del país, realizando las funciones de supervisión de la infraestructura y los procesos productivos agrícolas y pecuarias, además, del procesamiento y aprovechamiento de los productos agropecuarios para su almacenamiento de acuerdo a los procedimientos establecidos, así como, la comercialización de la producción agrícola y/o pecuaria de acuerdo al requerimiento del mercado y las buenas prácticas agropecuarias, considerando las normas de seguridad, higiene y preservación del medio ambiente con criterio rentabilidad y sostenibilidad, así mismo se comunica efectiva y asertivamente, utiliza el idioma inglés y las tecnologías de la información como soporte a sus actividades, soluciona problemas de su entorno, es innovador y emprendedor, y aplica la ética estableciendo relaciones con respeto y justicia, contribuyendo a una convivencia democrática.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Producción Agropecuaria",
                'modules'     => [
                    'Implementación de Infraestructura Agropecuaria',
                    'Supervisión de Procesos Productivos Agropecuarios',
                    'Procesamiento Primario y Aprovechamiento de Productos Agropecuarios',
                    'Almacenamiento y Comercialización de la Producción Agropecuaria',
                ],
                'album'       => [
                    'https://images.unsplash.com/photo-1595974482597-4b8da8879bc5?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1516253593875-bd7ba052fbc5?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name'        => 'Enfermería Técnica',
                'slug'        => 'enfermeria-tecnica',
                'description' => 'El egresado del Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero” de Uchiza, del programa de estudio de Enfermería Técnica, es un profesional Técnico que realiza actividades de promoción, prevención, asistencia en las necesidades básicas de la salud; en los cuidados integrales de la persona por etapas de vida y colectiva, aplica la comunicación efectiva, utiliza el inglés, las herramientas informáticas, comprendiendo la cultura ambiental, con ética laboral, en solución de problemas de salud con innovación tecnológica y emprendimiento empresarial de acuerdo a la normativa vigente.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Enfermería Técnica",
                'modules'     => [
                    'Asistencia en Promoción y Prevención de la Salud',
                    'Asistencia en la Atención Básica de la Salud',
                    'Asistencia en la Atención Integral de la Salud',
                ],
                'album'       => [
                    'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1516549655169-df83a0774514?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1581056771107-24ca5f033842?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name'        => 'Administración de Redes y Comunicaciones',
                'slug'        => 'administracion-de-redes-y-comunicaciones',
                'description' => 'El egresado del Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, del programa de estudio de Administración de Redes y Comunicaciones, brinda asistencia a nivel operativo y functional en los sistemas o servicios de TI, realizando la puesta en producción, implementación, configuración y administración de la infraestructura de redes y servicios de comunicaciones, teniendo en cuenta las políticas de seguridad de acuerdo a los roles y perfiles de los colaboradores de la organización y a la planificación efectuada. Así mismo se comunica de manera clara e interactúa con otras personas en contextos sociales y laborales, empleando el idioma inglés, operando las herramientas informáticas de las TIC, demostrando principios éticos en la solución de problemas de acuerdo a la necesidad del sector productivo y educativo, aplicando el emprendimiento y la innovación, en el ámbito personal, profesional y laboral.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Administración de Redes y Comunicación",
                'modules'     => [
                    'Soporte Técnico y Monitoreo de Equipos',
                    'Implementación de Redes y Comunicaciones',
                    'Administración y Seguridad de Redes y Comunicaciones',
                ],
                'album'       => [
                    'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1597852074816-d933c4d2b988?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1600132806370-bf17e65e942f?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name'        => 'Asistencia Administrativa',
                'slug'        => 'asistencia-administrativa',
                'description' => 'El egresado del Programa de Estudios de Asistencia Administrativa está en la capacidad de programar, controlar y administrar actividades y eventos, además de administrar los recursos y la documentación en función a las políticas y normativas vigentes. Se comunica de manera asertiva y efectiva, emplea el idioma inglés y las tecnologías de la información como apoyo para desarrollar sus actividades técnico profesionales; es ético en el desempeño de sus labores y destaca por sus aptitudes para identificar problemas y brindar alternativas de soluciones innovadoras en los procesos productivos y de servicios. Asimismo, es emprendedor identificando oportunidades de negocios, mejorando así los procesos de servicio con calidad, es responsable en prevención y control progresivo de los impactos ambientales con responsabilidad social y desarrollo sostenible, destaca por su liderazgo y trabajo colaborativo.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Asistencia Administrativa",
                'modules'     => [
                    'Planificación y Organización de Actividades Administrativas',
                    'Gestión de Actividades Organizacionales',
                    'Asistencia en Administración Documentaria',
                ],
                'album'       => [
                    'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=1200',
                    'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=800',
                    'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&q=80&w=800',
                ]
            ],
            [
                'name'        => 'Manejo Forestal',
                'slug'        => 'manejo-forestal',
                'description' => 'El egresado de Manejo Forestal, está capacitado para desarrollar procesos en plantaciones forestales como la recolección de la información del bosque natural, teniendo en cuenta el inventario o censo forestal; asimismo, controla la construcción y mantenimiento de caminos forestales, patios, trochas e infraestructura complementaria, para dar sostenimiento a los bosques y sus recursos afines, según el plan general de manejo forestal. De igual modo, controla la ejecución del aprovechamiento/ cosecha, considerando el plan operativo y la normatividad vigente; considerando las normas de seguridad, higiene y preservación del medio ambiente con criterio de rentabilidad y sostenibilidad, así mismo se comunica efectiva y asertivamente, utiliza el idioma inglés y las tecnologías de la información como soporte a sus actividades, soluciona problemas de su entorno, es innovador y emprendedor, y aplica la ética estableciendo relaciones con respeto y justicia, contribuyendo a una convivencia democrática.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Manejo Forestal",
                'modules'     => [
                    'Manejo Silvicultural',
                    'Planificación para el Control del Manejo Forestal',
                    'Aprovechamiento de Recursos Forestales Maderables y No Maderables',
                ],
                'album'       => [
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
                'name'        => $program['name'],
                'slug'        => $program['slug'],
                'description' => $program['description'],
                'details'     => $program['details'],
                'is_active'   => true,
            ]);

            // Crear los módulos de certificación (relación polimórfica)
            foreach ($program['modules'] as $moduleName) {
                ModularCertification::create([
                    'module'      => $moduleName,
                    'model_type'  => StudyProgram::class,
                    'program_id'  => $studyProgram->id,
                    'is_active'   => true,
                ]);
            }

            // Crear el álbum de imágenes (relación polimórfica)
            if (isset($program['album'])) {
                foreach ($program['album'] as $index => $imageUrl) {
                    $studyProgram->images()->create([
                        'path'    => $imageUrl,
                        'is_main' => $index === 0,
                    ]);
                }
            }
        }
    }
}