<?php

namespace Database\Seeders;

use App\Models\AdmissionRequirement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdmissionRequirement::create([
            'requirement' => 'Comprobante de pago por derecho de admisión',
            'is_active'   => true,
        ]);
        AdmissionRequirement::create([
            'requirement' => 'Certificado de Estudios del colegio',
            'is_active'   => true,
        ]);
        AdmissionRequirement::create([
            'requirement' => 'Partida de Nacimiento',
            'is_active'   => true,
        ]);
        AdmissionRequirement::create([
            'requirement' => 'Copia del DNI',
            'is_active'   => true,
        ]);
    }
}
