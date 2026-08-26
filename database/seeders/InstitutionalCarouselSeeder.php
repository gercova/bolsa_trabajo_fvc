<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\InstitutionalCarousel;
use Illuminate\Database\Seeder;

class InstitutionalCarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar registros previos de carrusel e imágenes asociadas
        $existingCarousels = InstitutionalCarousel::all();
        foreach ($existingCarousels as $c) {
            Image::where('imageable_type', InstitutionalCarousel::class)
                ->where('imageable_id', $c->id)
                ->delete();
            $c->delete();
        }

        $slides = [
            [
                'data' => [
                    'tag'                   => 'Admisión 2026-I • Modalidades Abiertas',
                    'tag_icon'              => 'bi-mortarboard-fill',
                    'tag_color'             => 'amber',
                    'title'                 => 'Tu futuro profesional empieza aquí, en el',
                    'highlight_text'        => 'IESTP Francisco Vigo Caballero',
                    'description'           => 'Estudia una de nuestras 5 carreras técnicas a Nombre de la Nación en Uchiza. Formación con alta demanda laboral, plana docente calificada y modernos ambientes.',
                    'primary_button_text'   => 'Examen de Admisión',
                    'primary_button_url'    => 'examen-de-admision',
                    'primary_button_icon'   => 'bi-pencil-square',
                    'secondary_button_text' => 'Ver 5 Carreras',
                    'secondary_button_url'  => 'programas-de-estudio',
                    'secondary_button_icon' => 'bi-grid-3x3-gap-fill',
                    'indicator_label'       => 'Admisión 2026',
                    'order'                 => 1,
                    'is_active'             => true,
                ],
                'image_path' => 'images/slider_admision.jpg',
            ],
            [
                'data' => [
                    'tag'                   => 'Innovación & Transformación Digital',
                    'tag_icon'              => 'bi-cpu-fill',
                    'tag_color'             => 'sky',
                    'title'                 => 'Laboratorios modernos de computación,',
                    'highlight_text'        => 'redes y telecomunicaciones',
                    'description'           => 'Capacítate en arquitectura de redes, ciberseguridad, ensamblaje de servidores y desarrollo de software con talleres prácticos desde el primer ciclo.',
                    'primary_button_text'   => 'Programa de Redes',
                    'primary_button_url'    => 'programas-de-estudio',
                    'primary_button_icon'   => 'bi-hdd-network-fill',
                    'secondary_button_text' => 'Bolsa de Empleo',
                    'secondary_button_url'  => 'bolsa-de-trabajo',
                    'secondary_button_icon' => 'bi-briefcase-fill',
                    'indicator_label'       => 'Redes & TI',
                    'order'                 => 2,
                    'is_active'             => true,
                ],
                'image_path' => 'images/slider_tecnologia.jpg',
            ],
            [
                'data' => [
                    'tag'                   => 'Vocación de Servicio & Salud Comunitaria',
                    'tag_icon'              => 'bi-heart-pulse-fill',
                    'tag_color'             => 'rose',
                    'title'                 => 'Enfermería Técnica con',
                    'highlight_text'        => 'simulación clínica integral',
                    'description'           => 'Desarrolla competencias asistenciales con docentes médicos y licenciados. Convenios con hospitales y centros de salud para tus prácticas preprofesionales.',
                    'primary_button_text'   => 'Enfermería Técnica',
                    'primary_button_url'    => 'programas-de-estudio',
                    'primary_button_icon'   => 'bi-heart-pulse',
                    'secondary_button_text' => 'Becas PRONABEC',
                    'secondary_button_url'  => 'becas-y-creditos',
                    'secondary_button_icon' => 'bi-award-fill',
                    'indicator_label'       => 'Enfermería',
                    'order'                 => 3,
                    'is_active'             => true,
                ],
                'image_path' => 'images/slider_enfermeria.jpg',
            ],
            [
                'data' => [
                    'tag'                   => 'Desarrollo Agroforestal & Sostenibilidad',
                    'tag_icon'              => 'bi-tree-fill',
                    'tag_color'             => 'emerald',
                    'title'                 => 'Liderazgo en el agro y la',
                    'highlight_text'        => 'conservación de los bosques',
                    'description'           => 'Aprende en parcelas demostrativas, viveros tecnificados y módulos de producción pecuaria en Uchiza. Formando técnicos para la productividad del Alto Huallaga.',
                    'primary_button_text'   => 'Ver Carreras del Agro',
                    'primary_button_url'    => 'programas-de-estudio',
                    'primary_button_icon'   => 'bi-tree',
                    'secondary_button_text' => 'Ingreso Directo CEPRE',
                    'secondary_button_url'  => 'cepre-fvc',
                    'secondary_button_icon' => 'bi-book-fill',
                    'indicator_label'       => 'Agroforestal',
                    'order'                 => 4,
                    'is_active'             => true,
                ],
                'image_path' => 'images/slider_agroforestal.jpg',
            ],
        ];

        foreach ($slides as $item) {
            $carousel = InstitutionalCarousel::create($item['data']);

            // Crear registro en el modelo Image
            Image::create([
                'path'           => $item['image_path'],
                'is_main'        => true,
                'imageable_type' => InstitutionalCarousel::class,
                'imageable_id'   => $carousel->id,
            ]);
        }
    }
}
