<?php

namespace Database\Seeders;

use App\Models\Tupa;
use Illuminate\Database\Seeder;

class TupaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tupa::create([
            'title' => 'Texto Único de Procedimientos Administrativos - TUPA 2026',
            'description' => 'Reglamento Oficial de Procedimientos Administrativos, Requisitos, Calificación y Cuadro de Tasas y Derechos de Pago del IESTP Francisco Vigo Caballero para el periodo fiscal 2026.',
            'file_path' => 'tupa/tupa-2026-fvc.pdf',
            'effective_start_date' => '2026-01-01',
            'effective_end_date' => '2026-12-31',
            'is_active' => true,
        ]);
    }
}
