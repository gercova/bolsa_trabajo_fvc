<?php

namespace Database\Seeders;

use App\Models\LicensingPhase;
use Illuminate\Database\Seeder;

class LicensingPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phases = [
            [
                'phase_number'        => 1,
                'title'               => 'Documentos de Gestión de las 7 Condiciones Básicas de Calidad (CBC)',
                'subtitle'            => 'Elaboración, actualización y aprobación de los instrumentos normativos y matrices de evaluación CBC.',
                'code'                => 'CBC-01',
                'stage_tag'           => 'P',
                'status'              => 'in_progress',
                'is_current'          => true, // Active current stage (P)
                'progress_percentage' => 85,
                'description'         => 'En esta primera y crucial fase, el IESTP "Francisco Vigo Caballero" formula, adecúa y aprueba todos los instrumentos de gestión institucional, curricular y de infraestructura conforme a la RVM N° 276-2019-MINEDU y la Ley N° 30512. Contempla el cumplimiento exhaustivo de las 7 Condiciones Básicas de Calidad exigidas para el licenciamiento institucional.',
                'milestones'          => [
                    [
                        'cbc_number'  => 'CBC 1',
                        'name'        => 'Gestión Institucional',
                        'description' => 'Demostrar coherencia y solidez del Proyecto Educativo Institucional (PEI), Reglamento Interno (RI), Plan Anual de Trabajo (PAT) y Manual de Perfiles de Puestos (MPP).',
                        'status'      => 'completed',
                        'progress'    => 100,
                    ],
                    [
                        'cbc_number'  => 'CBC 2',
                        'name'        => 'Gestión Académica y Programas de Estudio',
                        'description' => 'Planes de estudios modulares actualizados y pertinentes para los 5 programas de estudio: Manejo Forestal, Producción Agropecuaria, Enfermería Técnica, Asistencia Administrativa y Computación e Informática.',
                        'status'      => 'completed',
                        'progress'    => 100,
                    ],
                    [
                        'cbc_number'  => 'CBC 3',
                        'name'        => 'Infraestructura, Equipamiento y Recursos para el Aprendizaje',
                        'description' => 'Acondicionamiento de laboratorios, talleres tecnológicos, parcelas demostrativas (palma aceitera), ambientes tipo G, conectividad y biblioteca física y virtual.',
                        'status'      => 'in_progress',
                        'progress'    => 80,
                    ],
                    [
                        'cbc_number'  => 'CBC 4',
                        'name'        => 'Personal Docente Idóneo y Calificado',
                        'description' => 'Cumplimiento del porcentaje normativo de docentes a tiempo completo, convocatorias públicas de méritos y plan de capacitación y perfeccionamiento pedagógico continuo.',
                        'status'      => 'completed',
                        'progress'    => 90,
                    ],
                    [
                        'cbc_number'  => 'CBC 5',
                        'name'        => 'Previsión Económica y Financiera',
                        'description' => 'Presupuesto institucional asignado, plan de mantenimiento recurrente y sostenibilidad financiera garantizada a mediano y largo plazo.',
                        'status'      => 'completed',
                        'progress'    => 85,
                    ],
                    [
                        'cbc_number'  => 'CBC 6',
                        'name'        => 'Servicios Complementarios e Intermediación Laboral',
                        'description' => 'Servicios de bienestar estudiantil, tópico de salud psicopedagógico, actividades deportivas/culturales y bolsa de trabajo activa para egresados.',
                        'status'      => 'completed',
                        'progress'    => 95,
                    ],
                    [
                        'cbc_number'  => 'CBC 7',
                        'name'        => 'Investigación e Innovación Tecnológica',
                        'description' => 'Líneas institucionales de investigación aplicada, proyectos de innovación agropecuaria y forestal, y convenios con el sector productivo.',
                        'status'      => 'in_progress',
                        'progress'    => 75,
                    ],
                ],
                'resolution_number'   => 'RVM N° 276-2019-MINEDU',
                'legal_basis'         => 'Ley N° 30512, Ley de Institutos y Escuelas de Educación Superior y de la Carrera Pública de sus Docentes.',
                'start_date'          => '2026-01-02',
                'end_date'            => null,
                'estimated_date'      => 'En desarrollo (2026)',
                'file_path'           => null,
                'external_link'       => 'https://www.minedu.gob.pe/superiortecnologica',
                'order'               => 1,
                'is_active'           => true,
            ],
            [
                'phase_number'        => 2,
                'title'               => 'Presentación y Registro de Solicitud de Licenciamiento',
                'subtitle'            => 'Ingreso formal del expediente digital y físico ante la Dirección de Formación Inicial Docente / DIGEST del MINEDU.',
                'code'                => 'REG-02',
                'stage_tag'           => 'PTE',
                'status'              => 'pending',
                'is_current'          => false,
                'progress_percentage' => 0,
                'description'         => 'Corresponde a la presentación formal del expediente del IESTP Francisco Vigo Caballero con todos los medios de verificación foliados y matrices CBC a través de la plataforma digital del MINEDU para la asignación de número de expediente oficial.',
                'milestones'          => [
                    [
                        'cbc_number'  => 'Hito 2.1',
                        'name'        => 'Foliación y estructuración del expediente integral',
                        'description' => 'Compilación de los 7 tomos de evidencias y matrices de cumplimiento.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                    [
                        'cbc_number'  => 'Hito 2.2',
                        'name'        => 'Ingreso por Mesa de Partes Virtual / Plataforma MINEDU',
                        'description' => 'Generación de Registro de Trámite Documentario Oficial ante el Ministerio de Educación.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                ],
                'resolution_number'   => null,
                'legal_basis'         => 'Texto Único de Procedimientos Administrativos (TUPA) - MINEDU',
                'start_date'          => null,
                'end_date'            => null,
                'estimated_date'      => 'Próxima Etapa (2026 - II)',
                'file_path'           => null,
                'external_link'       => null,
                'order'               => 2,
                'is_active'           => true,
            ],
            [
                'phase_number'        => 3,
                'title'               => 'Revisión Documentaria y Supervisión Presencial',
                'subtitle'            => 'Evaluación técnica integral por el equipo de especialistas evaluadores del MINEDU.',
                'code'                => 'REV-03',
                'stage_tag'           => 'PTE',
                'status'              => 'pending',
                'is_current'          => false,
                'progress_percentage' => 0,
                'description'         => 'La comisión evaluadora del MINEDU realiza la constatación minuciosa de los medios probatorios y efectúa la visita de supervisión in situ en el campus del IESTP en Uchiza para verificar infraestructura, equipamiento, seguridad y servicios.',
                'milestones'          => [
                    [
                        'cbc_number'  => 'Hito 3.1',
                        'name'        => 'Evaluación Integral del Expediente por Comisión MINEDU',
                        'description' => 'Revisión de legalidad, consistencia académica y sostenibilidad financiera.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                    [
                        'cbc_number'  => 'Hito 3.2',
                        'name'        => 'Visita de Inspección y Verificación Presencial en Uchiza',
                        'description' => 'Constatación en campo de laboratorios, talleres, campos de práctica y aulas.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                ],
                'resolution_number'   => null,
                'legal_basis'         => 'Guía Metodológica de Verificación Presencial de CBC - MINEDU',
                'start_date'          => null,
                'end_date'            => null,
                'estimated_date'      => 'Programado 2026 - 2027',
                'file_path'           => null,
                'external_link'       => null,
                'order'               => 3,
                'is_active'           => true,
            ],
            [
                'phase_number'        => 4,
                'title'               => 'Levantamiento y Subsanación de Observaciones',
                'subtitle'            => 'Absolución oportuna de precisiones o requerimientos emitidos en el informe preliminar.',
                'code'                => 'OBS-04',
                'stage_tag'           => 'PTE',
                'status'              => 'pending',
                'is_current'          => false,
                'progress_percentage' => 0,
                'description'         => 'En caso de emitirse un informe con observaciones técnicas o complementarias, el Instituto dispone de un plazo normativo para presentar las aclaraciones y evidencias de subsanación correspondientes.',
                'milestones'          => [
                    [
                        'cbc_number'  => 'Hito 4.1',
                        'name'        => 'Recepción y análisis del Informe Técnico Preliminar',
                        'description' => 'Evaluación del pliego de observaciones y plan de acción de respuesta.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                    [
                        'cbc_number'  => 'Hito 4.2',
                        'name'        => 'Entrega del Informe Final de Subsanación',
                        'description' => 'Presentación de medios probatorios complementarios ante MINEDU.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                ],
                'resolution_number'   => null,
                'legal_basis'         => 'Artículo 44 del Reglamento de la Ley N° 30512',
                'start_date'          => null,
                'end_date'            => null,
                'estimated_date'      => 'Etapa Sujeta a Informe Técnico',
                'file_path'           => null,
                'external_link'       => null,
                'order'               => 4,
                'is_active'           => true,
            ],
            [
                'phase_number'        => 5,
                'title'               => 'Aprobación y Otorgamiento de Licencia Institucional',
                'subtitle'            => 'Emisión de la Resolución Ministerial que formaliza el Licenciamiento Institucional por 6 años renovables.',
                'code'                => 'APR-05',
                'stage_tag'           => 'PTE',
                'status'              => 'pending',
                'is_current'          => false,
                'progress_percentage' => 0,
                'description'         => 'Culminación exitosa del procedimiento con la emisión de la Resolución Ministerial de Licenciamiento Institucional a favor del IESTP "Francisco Vigo Caballero", garantizando calidad y acreditación oficial para todos sus programas de estudio.',
                'milestones'          => [
                    [
                        'cbc_number'  => 'Hito 5.1',
                        'name'        => 'Informe Técnico Favorable de DIGEST - MINEDU',
                        'description' => 'Dictamen final de cumplimiento del 100% de las CBC.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                    [
                        'cbc_number'  => 'Hito 5.2',
                        'name'        => 'Publicación de la Resolución Ministerial en el Diario Oficial El Peruano',
                        'description' => 'Otorgamiento formal de la Licencia Institucional para el IESTP Francisco Vigo Caballero.',
                        'status'      => 'pending',
                        'progress'    => 0,
                    ],
                ],
                'resolution_number'   => null,
                'legal_basis'         => 'Resolución Ministerial MINEDU - Otorgamiento de Licencia',
                'start_date'          => null,
                'end_date'            => null,
                'estimated_date'      => 'Meta Institucional 2027',
                'file_path'           => null,
                'external_link'       => null,
                'order'               => 5,
                'is_active'           => true,
            ],
        ];

        foreach ($phases as $data) {
            LicensingPhase::updateOrCreate(
                ['phase_number' => $data['phase_number']],
                $data
            );
        }
    }
}
