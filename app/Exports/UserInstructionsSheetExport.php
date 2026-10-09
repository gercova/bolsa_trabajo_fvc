<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserInstructionsSheetExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function title(): string
    {
        return 'Guía e Instrucciones';
    }

    public function array(): array
    {
        return [
            ['GUÍA DE IMPORTACIÓN MASIVA DE USUARIOS — IESTP FVC'],
            ['Formatos compatibles: Microsoft Excel (.xlsx, .xls) y CSV (.csv)'],
            [''],
            ['ESPECIFICACIONES TÉCNICAS Y REGLAS DE NEGOCIO'],
            ['1. Tipo de Documento: Siempre se asigna automáticamente DNI (ID: 1).'],
            ['2. Campo job_position: El valor DEBE ser estrictamente uno de los siguientes:'],
            ['   • "student/graduate"           → Para estudiantes, egresados o graduados.'],
            ['   • "teaching/administrative staff" → Para personal docente y/o administrativo.'],
            ['   (Se aceptan variantes en español como "estudiante/egresado" o "docente/personal administrativo" y se normalizan automáticamente).'],
            ['3. Correo Electrónico (email):'],
            ['   • Si el usuario YA cuenta con correo ingresado, se conservará y validará.'],
            ['   • Si la celda está VACÍA, el sistema generará automáticamente su correo institucional bajo la fórmula:'],
            ['     primera letra del nombre + apellido paterno completo + primera letra del apellido materno + @iestpfvc.edu.pe'],
            ['     Ejemplo: Juan Pérez Gómez → jperezg@iestpfvc.edu.pe'],
            ['4. Contraseña por defecto: Todos los usuarios importados tendrán como contraseña inicial: P4$$w0rd*'],
            ['5. Estado: Todos los usuarios se registran con estado Activo (is_active = 1).'],
            [''],
            ['DETALLE DE COLUMNAS DE LA PLANTILLA'],
            ['Columna', 'Nombre del Campo', 'Requerido', 'Descripción / Formato y Ejemplo'],
            ['A', 'N°', 'No', 'Número correlativo de fila. Solo referencia (ignorado al importar).'],
            ['B', 'DNI / N° Documento', 'SÍ', 'DNI de 8 dígitos numéricos. Ej: 71234567. Si ya existe, se actualiza la información.'],
            ['C', 'Nombres', 'SÍ', 'Nombre o nombres de pila de la persona. Ej: Juan Carlos.'],
            ['D', 'Apellido Paterno', 'SÍ', 'Primer apellido completo. Usado para el nombre y el correo. Ej: Pérez.'],
            ['E', 'Apellido Materno', 'No', 'Segundo apellido. Se extrae su inicial para el correo. Ej: Gómez.'],
            ['F', 'Cargo / Puesto (job_position)', 'SÍ', 'Debe ser "student/graduate" o "teaching/administrative staff".'],
            ['G', 'Correo Electrónico', 'No', 'Opcional. Si se deja en blanco, se auto-genera según la fórmula institucional.'],
            ['H', 'Rol', 'No', 'Opcional. Por defecto: "Estudiante" (si es student/graduate) o "Docente" / "Administrativo".'],
            ['I', 'Teléfono', 'No', 'Opcional. Número de celular o teléfono de contacto (ej: 987654321).'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '5B21B6']],
        ]);

        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6B7280']],
        ]);

        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '4C1D95']],
        ]);

        $sheet->getStyle('A17')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '4C1D95']],
        ]);

        // Table header row (row 18)
        $sheet->getStyle('A18:D18')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '5B21B6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Table data borders
        $sheet->getStyle('A18:D27')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        return [];
    }
}
