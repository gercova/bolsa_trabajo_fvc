<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use App\Models\ModularCertification;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programsData = [
            [
                'name'        => 'Producción Agropecuaria',
                'description' => 'El Profesional Técnico en Producción Agropecuaria tiene una sólida formación técnica y humanística, que le permite alcanzar las competencias para incorporarse y desarrollarse, en una explotación agropecuaria familiar y/o empresarial, pequeña o mediana según las características socioculturales, ecológicas y regionales del país, realizando las funciones de supervisión de la infraestructura y los procesos productivos agrícolas y pecuarias, además, del procesamiento y aprovechamiento de los productos agropecuarios para su almacenamiento de acuerdo a los procedimientos establecidos, así como, la comercialización de la producción agrícola y/o pecuaria de acuerdo al requerimiento del mercado y las buenas prácticas agropecuarias, considerando las normas de seguridad, higiene y preservación del medio ambiente con criterio rentabilidad y sostenibilidad, así mismo se comunica efectiva y asertivamente, utiliza el idioma inglés y las tecnologías de la información como soporte a sus actividades, soluciona problemas de su entorno, es innovador y emprendedor, y aplica la ética estableciendo relaciones con respeto y justicia, contribuyendo a una convivencia democrática.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Producción Agropecuaria",
                'modules'     => [
                    'Implementación de Infraestructura Agropecuaria',
                    'Supervisión de Procesos Productivos Agropecuarios',
                    'Procesamiento Primario y Aprovechamiento de Productos Agropecuarios',
                    'Almacenamiento y Comercialización de la Producción Agropecuaria',
                ],
            ],
            [
                'name'        => 'Enfermería Técnica',
                'description' => 'El egresado del Instituto de Educación Superior Tecnológico Público “Francisco Vigo Caballero” de Uchiza, del programa de estudio de Enfermería Técnica, es un profesional Técnico que realiza actividades de promoción, prevención, asistencia en las necesidades básicas de la salud; en los cuidados integrales de la persona por etapas de vida y colectiva, aplica la comunicación efectiva, utiliza el inglés, las herramientas informáticas, comprendiendo la cultura ambiental, con ética laboral, en solución de problemas de salud con innovación tecnológica y emprendimiento empresarial de acuerdo a la normativa vigente.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Enfermería Técnica",
                'modules'     => [
                    'Asistencia en Promoción y Prevención de la Salud',
                    'Asistencia en la Atención Básica de la Salud',
                    'Asistencia en la Atención Integral de la Salud',
                ],
            ],
            [
                'name'        => 'Administración de Redes y Comunicaciones',
                'description' => 'El egresado del Instituto de Educación Superior Tecnológico Público "Francisco Vigo Caballero" de Uchiza, del programa de estudio de Administración de Redes y Comunicaciones, brinda asistencia a nivel operativo y funcional en los sistemas o servicios de TI, realizando la puesta en producción, implementación, configuración y administración de la infraestructura de redes y servicios de comunicaciones, teniendo en cuenta las políticas de seguridad de acuerdo a los roles y perfiles de los colaboradores de la organización y a la planificación efectuada. Así mismo se comunica de manera clara e interactúa con otras personas en contextos sociales y laborales, empleando el idioma inglés, operando las herramientas informáticas de las TIC, demostrando principios éticos en la solución de problemas de acuerdo a la necesidad del sector productivo y educativo, aplicando el emprendimiento y la innovación, en el ámbito personal, profesional y laboral.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Administración de Redes y Comunicación",
                'modules'     => [
                    'Soporte Técnico y Monitoreo de Equipos',
                    'Implementación de Redes y Comunicaciones',
                    'Administración y Seguridad de Redes y Comunicaciones',
                ],
            ],
            [
                'name'        => 'Asistencia Administrativa',
                'description' => 'El egresado del Programa de Estudios de Asistencia Administrativa está en la capacidad de programar, controlar y administrar actividades y eventos, además de administrar los recursos y la documentación en función a las políticas y normativas vigentes. Se comunica de manera asertiva y efectiva, emplea el idioma inglés y las tecnologías de la información como apoyo para desarrollar sus actividades técnico profesionales; es ético en el desempeño de sus labores y destaca por sus aptitudes para identificar problemas y brindar alternativas de soluciones innovadoras en los procesos productivos y de servicios. Asimismo, es emprendedor identificando oportunidades de negocios, mejorando así los procesos de servicio con calidad, es responsable en prevención y control progresivo de los impactos ambientales con responsabilidad social y desarrollo sostenible, destaca por su liderazgo y trabajo colaborativo.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Asistencia Administrativa",
                'modules'     => [
                    'Planificación y Organización de Actividades Administrativas',
                    'Gestión de Actividades Organizacionales',
                    'Asistencia en Administración Documentaria',
                ],
            ],
            [
                'name'        => 'Manejo Forestal',
                'description' => 'El egresado de Manejo Forestal, está capacitado para desarrollar procesos en plantaciones forestales como la recolección de la información del bosque natural, teniendo en cuenta el inventario o censo forestal; asimismo, controla la construcción y mantenimiento de caminos forestales, patios, trochas e infraestructura complementaria, para dar sostenimiento a los bosques y sus recursos afines, según el plan general de manejo forestal. De igual modo, controla la ejecución del aprovechamiento/ cosecha, considerando el plan operativo y la normatividad vigente; considerando las normas de seguridad, higiene y preservación del medio ambiente con criterio de rentabilidad y sostenibilidad, así mismo se comunica efectiva y asertivamente, utiliza el idioma inglés y las tecnologías de la información como soporte a sus actividades, soluciona problemas de su entorno, es innovador y emprendedor, y aplica la ética estableciendo relaciones con respeto y justicia, contribuyendo a una convivencia democrática.',
                'details'     => "Duración: 3 años (06 períodos lectivos)\nTítulo: Profesional Técnico en Manejo Forestal",
                'modules'     => [
                    'Manejo Silvicultural',
                    'Planificación para el Control del Manejo Forestal',
                    'Aprovechamiento de Recursos Forestales Maderables y No Maderables',
                ],
            ],
        ];

        foreach ($programsData as $program) {
            // Crear el programa
            $studyProgram = StudyProgram::create([
                'name'        => $program['name'],
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
        }
    }
}