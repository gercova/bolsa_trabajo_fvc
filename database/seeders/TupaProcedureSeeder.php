<?php

namespace Database\Seeders;

use App\Models\TupaProcedure;
use App\Models\TupaCategory;
use Illuminate\Database\Seeder;

class TupaProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asumiendo que el Tupa con ID 1 existe, ajusta según tu caso
        $tupaId = \App\Models\Tupa::where('is_active', true)->first()?->id ?? 1;

        // Obtener categorías por nombre
        $categories = TupaCategory::pluck('id', 'name')->toArray();

        $procedures = [
            [
                'category' => 'Trámites Académicos y Certificación',
                'items' => [
                    [
                        'code' => 'P-01',
                        'name' => 'Certificado de Estudios (Por Semestre / Módulo)',
                        'description' => 'Documento oficial que acredita las notas obtenidas por semestre académico o módulo profesional.',
                        'requirements' => [
                            'Formulario Único de Trámite (FUT) completado y dirigido al Director General.',
                            'Comprobante de pago por derecho de trámite.',
                            '02 fotos tamaño carné a color en fondo blanco.',
                            'Constancia de no adeudar libros ni materiales a la institución.'
                        ],
                        'cost' => 'S/. 35.00',
                        'uit_percent' => '0.65%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '5 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-02',
                        'name' => 'Certificado Modular Formal de Capacitación',
                        'description' => 'Otorgamiento del certificado al culminar satisfactoriamente un Módulo Técnico Profesional.',
                        'requirements' => [
                            'FUT dirigido al Director General del instituto.',
                            'Comprobante de pago por derecho de certificado modular.',
                            'Constancia de prácticas preprofesionales / módulo acreditado.',
                            '02 fotografías tamaño pasaporte en fondo blanco con vestimenta formal.'
                        ],
                        'cost' => 'S/. 60.00',
                        'uit_percent' => '1.12%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '7 días hábiles',
                        'office' => 'Área de Unidad Académica'
                    ],
                    [
                        'code' => 'P-03',
                        'name' => 'Expedición de Título Profesional Técnico',
                        'description' => 'Trámite institucional para la emisión del Título Profesional a nombre de la Nación.',
                        'requirements' => [
                            'FUT institucional especificando el Programa de Estudios cursado.',
                            'Certificado de Estudios integrales aprobados (Semestres I al VI).',
                            'Constancias aprobadas de Módulos Profesionales y Prácticas Preprofesionales (EFSRT).',
                            'Constancia de Acreditación de Idioma Extranjero y Computación (si corresponde).',
                            'Comprobante de pago por derecho de Titulación y carpeta de egresado.',
                            '04 fotografías tamaño pasaporte en papel mate con traje formal.'
                        ],
                        'cost' => 'S/. 350.00',
                        'uit_percent' => '6.54%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '30 días hábiles',
                        'office' => 'Dirección General / Secretaría Académica'
                    ],
                    [
                        'code' => 'P-04',
                        'name' => 'Duplicado de Título Profesional o Certificados',
                        'description' => 'Expedición de duplicado oficial en caso de pérdida, deterioro o robo del documento original.',
                        'requirements' => [
                            'FUT dirigido al Director General.',
                            'Denuncia policial original por pérdida o robo (si aplica) o devolución del original deteriorado.',
                            'Publicación en diario de mayor circulación o declaración jurada legalizada.',
                            'Comprobante de pago por emisión de duplicado.',
                            '02 fotografías tamaño pasaporte.'
                        ],
                        'cost' => 'S/. 120.00',
                        'uit_percent' => '2.24%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '15 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-05',
                        'name' => 'Constancias Varias (Estudios, Egresado, Orden de Mérito)',
                        'description' => 'Emisión de constancia oficial de matrícula, condición de egresado, conducta o ponderado académico.',
                        'requirements' => [
                            'FUT solicitando la constancia específica.',
                            'Comprobante de pago correspondiente al tipo de constancia.'
                        ],
                        'cost' => 'S/. 20.00',
                        'uit_percent' => '0.37%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '2 días hábiles',
                        'office' => 'Secretaría Académica'
                    ]
                ]
            ],
            [
                'category' => 'Matrícula, Reincorporación y Traslados',
                'items' => [
                    [
                        'code' => 'P-06',
                        'name' => 'Matrícula Regular por Semestre Académico',
                        'description' => 'Inscripción semestral ordinaria para estudiantes regulares de la institución.',
                        'requirements' => [
                            'Ficha de matrícula debidamente completada.',
                            'Comprobante de pago de tasa por concepto de matrícula semestral.',
                            'Estar al día en compromisos administrativos y biblioteca.'
                        ],
                        'cost' => 'S/. 80.00',
                        'uit_percent' => '1.50%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '1 día hábil',
                        'office' => 'Unidad de Admisión y Registro'
                    ],
                    [
                        'code' => 'P-07',
                        'name' => 'Matrícula Extemporánea',
                        'description' => 'Inscripción fuera del cronograma ordinario de matrícula establecido en el calendario académico.',
                        'requirements' => [
                            'FUT justificado indicando motivo extemporáneo.',
                            'Comprobante de pago de recargo por matrícula extemporánea.',
                            'Aprobación previa de la Jefatura de Unidad Académica.'
                        ],
                        'cost' => 'S/. 110.00',
                        'uit_percent' => '2.05%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '2 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-08',
                        'name' => 'Reserva o Licencia de Matrícula',
                        'description' => 'Solicitud de suspensión temporal de estudios hasta por un máximo de 4 semestres académicos consecutivos.',
                        'requirements' => [
                            'FUT solicitando la reserva de matrícula por razones personales, laborales o de salud.',
                            'Documentos sustentatorios del motivo (si aplica).',
                            'Comprobante de pago por derecho de trámite de reserva.'
                        ],
                        'cost' => 'S/. 30.00',
                        'uit_percent' => '0.56%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '3 días hábiles',
                        'office' => 'Secretaría Académica'
                    ],
                    [
                        'code' => 'P-09',
                        'name' => 'Reincorporación a los Estudios',
                        'description' => 'Retorno a la actividad académica tras haber mantenido licencia o reserva de vacante.',
                        'requirements' => [
                            'FUT pidiendo reincorporación al semestre correspondiente.',
                            'Copia del documento que aprobó la reserva o licencia previa.',
                            'Comprobante de pago por derecho de reincorporación.'
                        ],
                        'cost' => 'S/. 40.00',
                        'uit_percent' => '0.75%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '3 días hábiles',
                        'office' => 'Unidad Académica'
                    ],
                    [
                        'code' => 'P-10',
                        'name' => 'Convalidación de Asignaturas / Módulos',
                        'description' => 'Reconocimiento académico de unidades didácticas aprobadas en la misma u otra institución de educación superior.',
                        'requirements' => [
                            'FUT especificando las asignaturas o módulos a convalidar.',
                            'Certificados de Estudios oficiales originales.',
                            'Sílabos oficiales visados y sellados por la institución de origen.',
                            'Comprobante de pago por derecho de convalidación por unidad didáctica o módulo.'
                        ],
                        'cost' => 'S/. 50.00',
                        'uit_percent' => '0.93%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '10 días hábiles',
                        'office' => 'Comisión de Convalidación / Jefatura de Área'
                    ]
                ]
            ],
            [
                'category' => 'Evaluaciones y Exámenes',
                'items' => [
                    [
                        'code' => 'P-11',
                        'name' => 'Inscripción al Examen de Admisión Ordinario',
                        'description' => 'Derecho a participar en el proceso de admisión general del instituto.',
                        'requirements' => [
                            'Certificado de Estudios de Educación Secundaria aprobados.',
                            'Copia simple de DNI o Carné de Extranjería.',
                            'Partida o Acta de Nacimiento.',
                            'Comprobante de pago por derecho de inscripción al examen de admisión.'
                        ],
                        'cost' => 'S/. 150.00',
                        'uit_percent' => '2.80%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => 'Inmediata',
                        'office' => 'Comisión Institucional de Admisión'
                    ],
                    [
                        'code' => 'P-12',
                        'name' => 'Examen de Subsanación / Recuperación',
                        'description' => 'Rendición de examen de evaluación extraordinaria para subsanar unidades didácticas no aprobadas.',
                        'requirements' => [
                            'FUT dirigido a la Jefatura de Área Académica.',
                            'Boleta de notas indicando la asignatura a subsanar.',
                            'Comprobante de pago por examen de subsanación por curso.'
                        ],
                        'cost' => 'S/. 45.00',
                        'uit_percent' => '0.84%',
                        'qualification' => 'Evaluación Previa (Positivo)',
                        'duration' => '3 días hábiles',
                        'office' => 'Jefatura de Unidad Académica'
                    ]
                ]
            ],
            [
                'category' => 'Servicios Complementarios y Carnés',
                'items' => [
                    [
                        'code' => 'P-13',
                        'name' => 'Carné Institucional de Estudiante (Expedición / Duplicado)',
                        'description' => 'Identificación oficial del estudiante para el acceso a instalaciones e infraestructura física y digital.',
                        'requirements' => [
                            'Ficha de matrícula vigente.',
                            '01 foto tamaño carné en digital o físico.',
                            'Comprobante de pago por derecho de expedición o duplicado de carné.'
                        ],
                        'cost' => 'S/. 18.00',
                        'uit_percent' => '0.33%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '5 días hábiles',
                        'office' => 'Unidad de Bienestar e Infraestructura'
                    ],
                    [
                        'code' => 'P-14',
                        'name' => 'Autenticación / Visado de Documentos e Imagen Institucional',
                        'description' => 'Certificación de autenticidad de firmas y fotocopias de documentos expedidos por la institución.',
                        'requirements' => [
                            'FUT solicitando la autenticación o copia fiel del original.',
                            'Presentación de los documentos originales y fotocopias legibles.',
                            'Comprobante de pago por folio o documento.'
                        ],
                        'cost' => 'S/. 10.00',
                        'uit_percent' => '0.18%',
                        'qualification' => 'Aprobación Automática',
                        'duration' => '1 día hábil',
                        'office' => 'Secretaría General / Dirección'
                    ]
                ]
            ]
        ];

        foreach ($procedures as $categoryData) {
            $categoryName = $categoryData['category'];
            $categoryId = $categories[$categoryName] ?? null;

            if (!$categoryId) {
                continue; // Saltar si no se encuentra la categoría
            }

            foreach ($categoryData['items'] as $item) {
                TupaProcedure::create([
                    'tupa_id' => $tupaId,
                    'category_id' => $categoryId,
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'requirements' => $item['requirements'],
                    'cost' => $item['cost'],
                    'uit_percent' => $item['uit_percent'],
                    'qualification' => $item['qualification'],
                    'duration' => $item['duration'],
                    'office' => $item['office'],
                    'is_active' => true,
                ]);
            }
        }
    }
}