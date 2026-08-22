<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::create([
            'company' => 'Universidad Nacional Autónoma de la Selva',
            'link' => 'https://academico.unas.edu.pe/',
        ]);
        Partner::create([
            'company' => 'Palmas del Espino',
            'link' => 'https://www.palmas.com.pe/',
        ]);
        Partner::create([
            'company' => 'Universidad Nacional Hermilio Valdizán',
            'link' => 'https://unheval.edu.pe/portal/',
        ]);
        Partner::create([
            'company' => 'Municipalidad Distrital de Uchiza',
            'link' => 'https://www.gob.pe/muniuchiza',
        ]);
        Partner::create([
            'company' => 'Hospital de Tingo María',
            'link' => 'https://hospitaltingomaria.regionhuanuco.gob.pe/',
        ]);
        Partner::create([
            'company' => 'Cámara de Comercio de Huánuco',
            'link' => 'https://camarahuanuco.org.pe/',
        ]);
        Partner::create([
            'company' => 'Unidad de Gestión Local Tocache (UGEL)',
            'link' => 'https://www.gob.pe/ugeltocache',
        ]);
    }
}
