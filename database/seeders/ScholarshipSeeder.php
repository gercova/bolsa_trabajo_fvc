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
                'name'        => 'PRIMEROS PUESTOS',
                'icon'        => 'bi-trophy',
                'description' => 'Modalidad especial dirigida a los egresados de Educación Secundaria que hayan ocupado el primer o segundo puesto de su promoción escolar en instituciones públicas o privadas. Otorga beneficio de exoneración de examen ordinario y priorización en el proceso de matrícula.',
            ],
            [
                'name'        => 'DEPORTISTAS CALIFICADOS',
                'icon'        => 'bi-person-arms-up',
                'description' => 'Destinada a deportistas destacados o de alto nivel acreditados oficialmente por el Instituto Peruano del Deporte (IPD) o Federaciones Deportivas Nacionales. Incluye reserva de vacantes, apoyo académico flexible y descuentos arancelarios.',
            ],
            [
                'name'        => 'VICTIMAS DE TERRORISMO',
                'icon'        => 'bi-heart',
                'description' => 'Dirigida a beneficiarios del Plan Integral de Reparaciones (PIR - Ley N° 28592) acreditados en el Registro Único de Víctimas (RUV) y sus familiares directos. Otorga ingreso directo por vacante reservada y exoneración total de pagos.',
            ],
            [
                'name'        => 'PRE-INSTITUTO',
                'icon'        => 'bi-book',
                'description' => 'Beneficio otorgado a los estudiantes más destacados del ciclo de preparación CEPRE-FVC que alcancen los primeros lugares en el cuadro de mérito, asegurando su ingreso directo y facilidades de pago.',
            ],
            [
                'name'        => 'DISCAPACITADOS',
                'icon'        => 'bi-person-wheelchair',
                'description' => 'En cumplimiento de la Ley N° 29973 (Ley General de la Persona con Discapacidad), se reserva el 5% de las vacantes del proceso de admisión y se brindan tarifas preferenciales a postulantes registrados en CONADIS.',
            ],
            [
                'name'        => 'TITULADOS',
                'icon'        => 'bi-mortarboard',
                'description' => 'Orientada a graduados y titulados de educación universitaria o técnica superior que buscan convalidar estudios y seguir una segunda carrera profesional tecnológica de forma ágil y con beneficios arancelarios.',
            ],
            [
                'name'        => 'FUERZAS ARMADAS',
                'icon'        => 'bi-shield-fill',
                'description' => 'Dirigida al personal del Servicio Militar Voluntario (SMV) o licenciados de las Fuerzas Armadas (Ejército, Marina, Fuerza Aérea). Otorga bonificación del 10% en el puntaje de admisión y facilidades de pago.',
            ],
        ];

        foreach ($modalidades as $index => $item) {
            Scholarship::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name'        => $item['name'],
                    'description' => $item['description'],
                    'icon'        => $item['icon'],
                    'is_active'   => true,
                    'sort_order'  => $index + 1,
                ]
            );
        }
    }
}