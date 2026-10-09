<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserDataSheetExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function title(): string
    {
        return 'Plantilla de Usuarios';
    }

    public function headings(): array
    {
        return [
            'N°',
            'DNI / N° Documento',
            'Nombres',
            'Apellido Paterno',
            'Apellido Materno',
            'Cargo / Puesto (job_position)',
            'Correo Electrónico',
            'Rol',
            'Teléfono',
        ];
    }

    public function array(): array
    {
        return [
            [
                1,
                '71234567',
                'Juan Carlos',
                'Pérez',
                'Gómez',
                'student/graduate',
                '', // Vacío: el sistema generará jperezg@iestpfvc.edu.pe
                'Estudiante',
                '987654321',
            ],
            [
                2,
                '45678901',
                'María Elena',
                'Ramos',
                'Castillo',
                'teaching/administrative staff',
                '', // Vacío: el sistema generará mramosc@iestpfvc.edu.pe
                'Docente',
                '976543210',
            ],
            [
                3,
                '78901234',
                'Luis Alberto',
                'Vásquez',
                'Torres',
                'student/graduate',
                'lvasquezt_personal@gmail.com', // Se respeta el correo si se ingresa
                'Estudiante',
                '965432109',
            ],
            [
                4,
                '41239876',
                'Rosa Aurora',
                'Mendoza',
                'Flores',
                'teaching/administrative staff',
                '', // Vacío: el sistema generará rmendozaf@iestpfvc.edu.pe
                'Administrativo',
                '954321098',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getRowDimension(1)->setRowHeight(32);
        for ($i = 2; $i <= 5; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(22);
        }

        // Header styling
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '5B21B6'], // Purple 800
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '7C3AED'],
                ],
            ],
        ]);

        // Accent on job_position column (F)
        $sheet->getStyle('F1')->getFill()->applyFromArray([
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '6D28D9'], // Purple 700
        ]);

        // Accent on email column (G)
        $sheet->getStyle('G1')->getFill()->applyFromArray([
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4C1D95'], // Purple 900
        ]);

        // Data rows styling
        $sheet->getStyle('A2:I5')->applyFromArray([
            'font' => [
                'size' => 9.5,
                'color' => ['rgb' => '1F2937'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB'],
                ],
            ],
        ]);

        // Center alignments
        $sheet->getStyle('A2:B5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F2:F5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H2:I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
