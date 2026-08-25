<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modalidades = [
            [
                'name'                => 'PRIMEROS PUESTOS',
                'icon'                => 'bi-trophy',
                'vacancies'           => 10,
                'discount_percentage' => 100.00,
                'discount_details'    => '100% de descuento para el Primer Puesto y 50% de descuento para el Segundo Puesto.',
                'description'         => 'Modalidad especial dirigida a los egresados de Educación Secundaria que hayan ocupado el primer o segundo puesto de su promoción escolar en instituciones públicas o privadas. Otorga beneficio de exoneración de examen ordinario y priorización en el proceso de matrícula.',
                'requirements'        => "Certificado de estudios original con acreditación de 1° o 2° puesto escolar.\nActa de sesión de promoción escolar emitida por la I.E. de origen.\nCopia simple de DNI vigente.",
            ],
            [
                'name'                => 'DEPORTISTAS CALIFICADOS',
                'icon'                => 'bi-person-arms-up',
                'vacancies'           => 5,
                'discount_percentage' => 50.00,
                'discount_details'    => '50% de descuento arancelario y facilidades de horario para deportistas acreditados por el IPD.',
                'description'         => 'Destinada a deportistas destacados o de alto nivel acreditados oficialmente por el Instituto Peruano del Deporte (IPD) o Federaciones Deportivas Nacionales. Incluye reserva de vacantes, apoyo académico flexible y descuentos arancelarios.',
                'requirements'        => "Credencial vigente emitida por el IPD o Federación Deportiva Nacional.\nCarta de compromiso de representación institucional en torneos y competencias.\nCopia simple de DNI vigente.",
            ],
            [
                'name'                => 'VICTIMAS DE TERRORISMO',
                'icon'                => 'bi-heart',
                'vacancies'           => 5,
                'discount_percentage' => 100.00,
                'discount_details'    => '100% de exoneración total de pagos según Ley N° 28592 (Plan Integral de Reparaciones - PIR).',
                'description'         => 'Dirigida a beneficiarios del Plan Integral de Reparaciones (PIR - Ley N° 28592) acreditados en el Registro Único de Víctimas (RUV) y sus familiares directos. Otorga ingreso directo por vacante reservada y exoneración total de pagos.',
                'requirements'        => "Acreditación oficial en el Registro Único de Víctimas (RUV - Ley N° 28592).\nCertificado emitido por el Consejo de Reparaciones.\nCopia simple de DNI vigente.",
            ],
            [
                'name'                => 'PRE-INSTITUTO',
                'icon'                => 'bi-book',
                'vacancies'           => 25,
                'discount_percentage' => 100.00,
                'discount_details'    => 'Ingreso directo y 100% de exoneración de pago en el examen ordinario para los primeros puestos de la CEPRE.',
                'description'         => 'Beneficio otorgado a los estudiantes más destacados del ciclo de preparación CEPRE-FVC que alcancen los primeros lugares en el cuadro de mérito, asegurando su ingreso directo y facilidades de pago.',
                'requirements'        => "Constancia de nota final aprobatoria y orden de mérito de la CEPRE-FVC.\nAsistencia mínima obligatoria del 85% a clases preparatorias.\nCopia simple de DNI vigente.",
            ],
            [
                'name'                => 'DISCAPACITADOS',
                'icon'                => 'bi-person-wheelchair',
                'vacancies'           => 5,
                'discount_percentage' => 50.00,
                'discount_details'    => '50% de descuento en derechos arancelarios y reserva del 5% de vacantes conforme a la Ley N° 29973.',
                'description'         => 'En cumplimiento de la Ley N° 29973 (Ley General de la Persona con Discapacidad), se reserva el 5% de las vacantes del proceso de admisión y se brindan tarifas preferenciales a postulantes registrados en CONADIS.',
                'requirements'        => "Carné oficial de inscripción en CONADIS (Ley N° 29973).\nCertificado médico oficial de discapacidad emitido por MINSA, EsSalud o FF.AA.\nCopia simple de DNI vigente.",
            ],
            [
                'name'                => 'TITULADOS',
                'icon'                => 'bi-mortarboard',
                'vacancies'           => 5,
                'discount_percentage' => 30.00,
                'discount_details'    => '30% de descuento arancelario en matrícula y convalidación ágil para segunda carrera profesional.',
                'description'         => 'Orientada a graduados y titulados de educación universitaria o técnica superior que buscan convalidar estudios y seguir una segunda carrera profesional tecnológica de forma ágil y con beneficios arancelarios.',
                'requirements'        => "Copia autenticada de Título Profesional o Grado Académico de Bachiller.\nCertificado oficial de estudios de nivel superior para convalidación.\nCopia simple de DNI vigente.",
            ],
            [
                'name'                => 'FUERZAS ARMADAS',
                'icon'                => 'bi-shield-fill',
                'vacancies'           => 5,
                'discount_percentage' => 50.00,
                'discount_details'    => '50% de descuento arancelario para personal del Servicio Militar Voluntario (SMV) y licenciados de las FF.AA.',
                'description'         => 'Dirigida al personal del Servicio Militar Voluntario (SMV) o licenciados de las Fuerzas Armadas (Ejército, Marina, Fuerza Aérea). Otorga bonificación del 10% en el puntaje de admisión, reserva de plazas y 50% de descuento.',
                'requirements'        => "Libreta Militar o Constancia de Licenciado del Servicio Militar Voluntario (SMV).\nDocumento oficial de acreditación emitido por las FF.AA.\nCopia simple de DNI vigente.",
            ],
        ];

        foreach ($modalidades as $index => $item) {
            Scholarship::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name'                => $item['name'],
                    'description'         => $item['description'],
                    'vacancies'           => $item['vacancies'],
                    'discount_percentage' => $item['discount_percentage'],
                    'discount_details'    => $item['discount_details'],
                    'requirements'        => $item['requirements'],
                    'icon'                => $item['icon'],
                    'is_active'           => true,
                    'sort_order'          => $index + 1,
                ]
            );
        }
    }
}