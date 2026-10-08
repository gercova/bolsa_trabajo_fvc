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

class CertificateDataSheetExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function title(): string
    {
        return 'Plantilla de Importación';
    }

    public function headings(): array
    {
        return [
            'N°',
            'DNI / N° Documento',
            'Apellidos y Nombres',
            'Curso',
            'Fecha de Inicio',
            'Fecha de Término',
            'Fecha de Emisión',
            'Horas',
            'Calificación I (numérica)',
            'Calificación Letras I',
            'Calificación II (numérica)',
            'Calificación Letras II',
            'Promedio',
            'Modalidad',
            'Código',
            'Condición / Participación',
        ];
    }

    public function array(): array
    {
        return [
            [
                1,
                '74123456',
                'García López, María Elena',
                'Excel Avanzado',
                '2026-01-10',
                '2026-03-20',
                '2026-03-21',
                '120 Horas',
                '17',
                'DIECISIETE',
                '19',
                'DIECINUEVE',
                '18',
                'Presencial',
                'CERT-74123456-1',
                'ASISTENTE',
            ],
            [
                2,
                '71234568',
                'Rodríguez Quispe, Carlos Alberto',
                'Desarrollo Web Full Stack',
                '2026-02-01',
                '2026-04-15',
                '2026-04-16',
                '180 Horas',
                '20',
                'VEINTE',
                '18',
                'DIECIOCHO',
                '19',
                'Virtual',
                '', // Vacío para autogenerar CERT-{DNI}-{secuencia}
                'PONENTE',
            ],
            [
                3,
                '70987654',
                'Mamani Flores, Ana Lucía',
                'Inteligencia Artificial Aplicada',
                '2026-03-01',
                '2026-05-10',
                '2026-05-12',
                '90 Horas',
                '18',
                'DIECIOCHO',
                '17',
                'DIECISIETE',
                '17.5',
                'Semipresencial',
                'CERT-IA-2026-003',
                'ORGANIZADOR',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(22);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // Header styling
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '312E81'], // Indigo 900
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '4338CA'],
                ],
            ],
        ]);

        // Specific header accents: Code (O) and Participation (P)
        $sheet->getStyle('O1')->getFill()->applyFromArray([
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4338CA'], // Indigo 700
        ]);
        $sheet->getStyle('P1')->getFill()->applyFromArray([
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '047857'], // Emerald 700
        ]);

        // Data rows border and alignments
        $sheet->getStyle('A2:P4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'size' => 10,
            ],
        ]);

        $sheet->getStyle('A2:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:G4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H2:H4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I2:M4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N2:P4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Highlight participation type column in example rows
        $sheet->getStyle('P2:P4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '065F46'], // Emerald 800
            ],
        ]);

        return [];
    }
}
