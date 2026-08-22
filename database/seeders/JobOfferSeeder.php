<?php

namespace Database\Seeders;

use App\Models\JobOffer;
use Illuminate\Database\Seeder;

class JobOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'title'       => 'Técnico en Soporte de Redes y Comunicaciones',
                'company'     => 'Telecomunicaciones del Oriente S.A.C.',
                'location'    => 'Uchiza, San Martín',
                'description' => 'Buscamos técnico egresado en Administración de Redes o Computación para mantenimiento preventivo y correctivo de infraestructura de red, monitoreo de enlaces de fibra óptica y asistencia a usuarios finales.',
                'url'         => 'https://ejemplo.com/empleos/soporte-redes-uchiza',
                'source'      => 'Bolsa Institucional IESTP FVC',
                'is_active'   => true,
            ],
            [
                'title'       => 'Asistente Técnico Administrativo y Contable',
                'company'     => 'Cooperativa Agraria de Cacao y Café Uchiza',
                'location'    => 'Uchiza, San Martín',
                'description' => 'Se requiere egresado o titulado en Asistencia Administrativa para gestión documentaria, archivo digital, atención al socio y apoyo en liquidaciones contables y facturación electrónica.',
                'url'         => 'https://ejemplo.com/empleos/asistente-administrativo-coop',
                'source'      => 'Convenio Empresarial',
                'is_active'   => true,
            ],
            [
                'title'       => 'Técnico en Enfermería Ocupacional',
                'company'     => 'Clínica Especializada San Martín',
                'location'    => 'Tocache, San Martín',
                'description' => 'Se solicita profesional técnico en Enfermería para la atención de urgencias, triaje, administración de medicamentos, control de historias clínicas y programas de salud ocupacional.',
                'url'         => 'https://ejemplo.com/empleos/enfermeria-tecnica-tocache',
                'source'      => 'Convocatoria Pública',
                'is_active'   => true,
            ],
            [
                'title'       => 'Técnico de Campo en Manejo Forestal Sostenible',
                'company'     => 'Consorcio Forestal Amazonía S.A.',
                'location'    => 'Tingo María, Huánuco',
                'description' => 'Especialista técnico para evaluación e inventario forestal, monitoreo de parcelas de corta, supervisión de buenas prácticas de extracción y planes de manejo ambiental.',
                'url'         => 'https://ejemplo.com/empleos/tecnico-forestal-amazonia',
                'source'      => 'Bolsa Institucional IESTP FVC',
                'is_active'   => true,
            ],
            [
                'title'       => 'Supervisora / Técnico en Producción Agropecuaria',
                'company'     => 'Fundo Agroindustrial Valle del Huallaga',
                'location'    => 'Uchiza, San Martín',
                'description' => 'Se requiere técnico agropecuario para manejo agronómico de cultivos tropicales (palma aceitera, cacao), sanidad vegetal, fertilización y supervisión de personal de campo.',
                'url'         => 'https://ejemplo.com/empleos/tecnico-agropecuario-huallaga',
                'source'      => 'Convenio Empresarial',
                'is_active'   => true,
            ],
            [
                'title'       => 'Administrador de Sistemas e Infraestructura TI',
                'company'     => 'Grupo Comercial del Oriente',
                'location'    => 'Tarapoto, San Martín',
                'description' => 'Encargado de la configuración de servidores Linux/Windows, administración de base de datos MySQL, políticas de seguridad perimetral y respaldo de copias de seguridad.',
                'url'         => 'https://ejemplo.com/empleos/administrador-sistemas-tarapoto',
                'source'      => 'Bolsa Institucional IESTP FVC',
                'is_active'   => true,
            ],
            [
                'title'       => 'Auxiliar de Enfermería y Cuidados Preventivos',
                'company'     => 'Centro de Salud Comunitario Uchiza',
                'location'    => 'Uchiza, San Martín',
                'description' => 'Apoyo en campañas de vacunación, inmunizaciones, toma de funciones vitales y charlas de prevención comunitaria en el ámbito rural de la provincia de Tocache.',
                'url'         => 'https://ejemplo.com/empleos/auxiliar-enfermeria-minsa',
                'source'      => 'Convocatoria Pública',
                'is_active'   => true,
            ],
            [
                'title'       => 'Asistente de Logística y Trámites Administrativos',
                'company'     => 'Distribuidora Logística Selva Central',
                'location'    => 'Tingo María, Huánuco',
                'description' => 'Persona dinámica encargada del control de inventarios de almacén, guías de remisión, coordinación con proveedores y trámites documentarios ante entidades públicas.',
                'url'         => 'https://ejemplo.com/empleos/asistente-logistica-tingomaria',
                'source'      => 'Convenio Empresarial',
                'is_active'   => true,
            ],
            [
                'title'       => 'Técnico Extensionista en Cultivos Cacaoteros',
                'company'     => 'Asociación de Productores Orgánicos del Huallaga',
                'location'    => 'Uchiza, San Martín',
                'description' => 'Brindar asistencia técnica en inocuidad, poscosecha y fermentación de cacao fino de aroma a pequeñas cooperativas de la provincia de Tocache.',
                'url'         => 'https://ejemplo.com/empleos/extensionista-cacao-uchiza',
                'source'      => 'Bolsa Institucional IESTP FVC',
                'is_active'   => true,
            ],
        ];

        foreach ($offers as $offer) {
            JobOffer::updateOrCreate(
                ['url' => $offer['url']],
                $offer
            );
        }
    }
}
