<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        Enterprise::create([
            'ruc'                       => '20000000000',
            'company_name'              => 'IESTP Francisco Vigo Caballero',
            'trade_name'                => 'IESTP Francisco Vigo Caballero',
            'legal_representative_dni'  => '00000000',
            'legal_representative'      => 'Teodorico Ganoza Medina',
            'address'                   => 'Av. Ricardo Palma N° 1401',
            'google_maps_iframe'        => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.886026543033!2d-76.46860822416807!3d-8.449056191591522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91af63dcf2a7d793%3A0x39afb5dd2aae7783!2sFRANCISCO%20VIGO%20CABALLERO!5e0!3m2!1ses!2spe!4v1740000000000!5m2!1ses!2spe" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'city'                      => 'Uchiza',
            'business_sector'           => 'Educación',
            'phrase'                    => 'Somos tu llave para triunfar',
            'description'               => 'Somos el Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero, pioneros en la educación de la juventud uchicina en la zona del bajo Huallaga',

            // Visión y misión actualizadas según el PDF
            'vision'                    => 'Al 2029 ser un IES Licenciado con excelente servicio educativo, referente en la formación de profesionales técnicos competitivos, basados en conocimientos científicos, tecnológicos, humanísticos y con conciencia ambiental, que aportan al desarrollo local, regional y nacional.',

            'mission'                   => 'Somos un Instituto de Educación Superior Tecnológica de gestión pública, revalidado por el MINEDU, que brinda un servicio educativo de calidad orientado a formar profesionales técnicos en Producción Agropecuaria, Enfermería Técnica, Administración de Redes y Comunicaciones, Asistencia Administrativa y Medio Ambiente y Recursos Naturales, con docentes idóneos aplicando principios éticos y morales.',

            'phone_number_1'            => '942131215',
            'phone_number_2'            => '',
            'email'                     => 'secretariageneral@franciscovigocaballero.edu.pe',
            'facebook_link'             => 'https://web.facebook.com/FranciscoVigoCaballero',
            'linkedin_link'             => '',
            'twitter_link'              => '',
            'instagram_link'            => '',
            'whatsapp_link'             => 'https://wa.me/51942131215',

            // Principios y valores extraídos del PDF
            'principles'                => 'Calidad, Transparencia, Inclusión, Trabajo colaborativo, Interculturalidad, Innovación',
            'values'                    => 'Responsabilidad, Orden, Disciplina, Solidaridad, Honestidad, Respeto',

            'logo_path'                 => 'enterprise/favicons/logo-iestpfvc.png',
            'favicon_path'              => 'enterprise/favicons/logo-iestpfvc.png',
        ]);
    }
}