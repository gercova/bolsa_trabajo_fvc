<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Enterprise::create([
            'ruc'                       => '20000000000',
            'company_name'              => 'IESTP Francisco Vigo Caballero',
            'trade_name'                => 'IESTP Francisco Vigo Caballero',
            'legal_representative_dni'  => '00000000',
            'legal_representative'      => 'Teodorico Ganoza Medina',
            'address'                   => 'Av. Ricardo Palma N° 1401',
            'city'                      => 'Uchiza',
            'business_sector'           => 'Educación',
            'phrase'                    => 'Somos tu llave para triunfar',
            'description'               => 'Somos el Instituto de Educación Superior Tecnológico Público Francisco Vigo Caballero, pioneros en la educación de la juventud uchicina en la zona del bajo Huallaga',
            'vision'                    => 'Ser el instituto tecnológico público lider en la educación superior técnica te toda la zona del Bajo Huallaga garantizando la educación técnica de calidad de nuestros jóvenes en todos los programas de estudios.',
            'mission'                   => 'Para este 2026 conseguir el licenciamiento institucional por el MINEDU garantizando las condiciones básicas de calidad para nuestra juventud uchicina y toda la zona del Bajo Huallaga.',
            'phone_number_1'            => '942131215',
            'phone_number_2'            => '',
            'email'                     => 'secretariageneral@franciscovigocaballero.edu.pe',
            'facebook_link'             => 'https://web.facebook.com/FranciscoVigoCaballero',
            'linkedin_link'             => '',
            'twitter_link'              => '',
            'instagram_link'            => '',
            'whatsapp_link'             => 'https://wa.me/51942131215',
            'logo_path'                 => 'enterprise/favicons/logo-iestpfvc.png',
            'favicon_path'              => 'enterprise/favicons/logo-iestpfvc.png',
        ]);
    }
}
