<?php

namespace Database\Seeders;

use App\Models\AccountBalance;
use Illuminate\Database\Seeder;

class AccountBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            // ── Período 2025 ─────────────────────────────────────────────
            [
                'month'          => 'ENERO',
                'date'           => '2025-01-08',
                'receipt_number' => '567',
                'client'         => 'AILCIA EMELDA ALVARADO HERRERA',
                'description'    => 'CERTIFICACIÓN MODULAR A.A',
                'category'       => 'CERTIFICACIÓN MODULAR',
                'program_code'   => 'A.A',
                'program_name'   => 'Asistencia Administrativa',
                'amount'         => 245.00,
                'reason'         => 'Trámite regular',
            ],
            [
                'month'          => 'ENERO',
                'date'           => '2025-01-15',
                'receipt_number' => '569',
                'client'         => 'GUIDO DENNIS RODRIGUEZ SAENZ',
                'description'    => 'CERTIFICACION MODULAR P.A',
                'category'       => 'CERTIFICACIÓN MODULAR',
                'program_code'   => 'P.A',
                'program_name'   => 'Producción Agropecuaria',
                'amount'         => 524.00,
                'reason'         => 'Trámite regular',
            ],
            [
                'month'          => 'FEBRERO',
                'date'           => '2025-02-03',
                'receipt_number' => '574',
                'client'         => 'HINOSTROZA CERNA AZUCENA',
                'description'    => 'CONSTANCIA DE EGRESADO',
                'category'       => 'CONSTANCIA',
                'program_code'   => 'A.A',
                'program_name'   => 'Asistencia Administrativa',
                'amount'         => 21.00,
                'reason'         => 'Trámite regular',
            ],
            [
                'month'          => 'FEBRERO',
                'date'           => '2025-02-12',
                'receipt_number' => '580',
                'client'         => 'JUAN CARLOS FLORES RAMOS',
                'description'    => 'DIPLOMA DE EGRESADO D.S',
                'category'       => 'DIPLOMA',
                'program_code'   => 'D.S',
                'program_name'   => 'Desarrollo de Sistemas de Información',
                'amount'         => 350.00,
                'reason'         => 'Trámite regular',
            ],
            [
                'month'          => 'MARZO',
                'date'           => '2025-03-05',
                'receipt_number' => '595',
                'client'         => 'ANA LUCIA PAREDES MENDOZA',
                'description'    => 'MATRÍCULA SEMESTRAL 2025-I',
                'category'       => 'MATRÍCULA',
                'program_code'   => 'E.T',
                'program_name'   => 'Enfermería Técnica',
                'amount'         => 180.00,
                'reason'         => 'Matrícula regular',
            ],

            // ── Período 2024 ─────────────────────────────────────────────
            [
                'month'          => 'NOVIEMBRE',
                'date'           => '2024-11-10',
                'receipt_number' => '420',
                'client'         => 'CARLOS MANUEL SOTO RIVERA',
                'description'    => 'CERTIFICADO DE ESTUDIOS COMPLETOS',
                'category'       => 'CERTIFICADOS',
                'program_code'   => 'D.S',
                'program_name'   => 'Desarrollo de Sistemas de Información',
                'amount'         => 120.00,
                'reason'         => 'Trámite regular',
            ],
            [
                'month'          => 'DICIEMBRE',
                'date'           => '2024-12-04',
                'receipt_number' => '445',
                'client'         => 'ROSA MARIA HUAMAN PEÑA',
                'description'    => 'CONSTANCIA DE NO ADEUDO',
                'category'       => 'CONSTANCIA',
                'program_code'   => 'A.A',
                'program_name'   => 'Asistencia Administrativa',
                'amount'         => 25.00,
                'reason'         => 'Trámite de egreso',
            ],
            [
                'month'          => 'DICIEMBRE',
                'date'           => '2024-12-18',
                'receipt_number' => '460',
                'client'         => 'MIGUEL ANGEL CORDOVA LOPEZ',
                'description'    => 'DIPLOMA DE TÍTULO PROFESIONAL',
                'category'       => 'DIPLOMA',
                'program_code'   => 'P.A',
                'program_name'   => 'Producción Agropecuaria',
                'amount'         => 450.00,
                'reason'         => 'Titulación',
            ],
        ];

        foreach ($records as $record) {
            AccountBalance::create($record);
        }
    }
}
