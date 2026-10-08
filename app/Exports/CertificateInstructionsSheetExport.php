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

class CertificateInstructionsSheetExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function title(): string
    {
        return 'Guía e Instrucciones';
    }

    public function array(): array
    {
        return [
            // Row 1: Title
            ['GUÍA DE IMPORTACIÓN MASIVA DE CERTIFICADOS — IESTP FVC', '', '', '', '', ''],
            // Row 2: Subtitle
            ['Formatos compatibles: Microsoft Excel (.xlsx, .xls) y Archivos CSV (.csv) delimitados por comas', '', '', '', '', ''],
            // Row 3: Blank
            ['', '', '', '', '', ''],
            // Row 4: Section 1 Header
            ['1. INSTRUCCIONES GENERALES Y PASOS A SEGUIR', '', '', '', '', ''],
            // Rows 5-11: Step instructions
            ['• Paso 1: Vaya a la hoja "Plantilla de Importación" para ingresar la lista de certificados.', '', '', '', '', ''],
            ['• Paso 2: Las tres primeras filas son ejemplos referenciales. Puede reemplazarlas con sus registros reales.', '', '', '', '', ''],
            ['• Paso 3: Si un estudiante (DNI) no existe en el sistema, se registrará de forma automática con su DNI como contraseña provisional.', '', '', '', '', ''],
            ['• Paso 4: El nombre del curso debe coincidir exactamente con el nombre de un curso registrado en el sistema.', '', '', '', '', ''],
            ['• Paso 5: Las fechas deben tener formato YYYY-MM-DD (ejemplo: 2026-03-21) o formato de fecha nativo de Excel.', '', '', '', '', ''],
            ['• Paso 6: Guarde el archivo terminado como .xlsx, .xls o .csv y súbalo desde el modal "Importar Certificados".', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            // Row 12: Section 2 Header
            ['2. ESPECIFICACIÓN DETALLADA DE COLUMNAS (A–P)', '', '', '', '', ''],
            // Row 13: Table Header
            ['Columna', 'Encabezado en Plantilla', 'Requerido', 'Campo en Sistema', 'Descripción / Reglas de Validación', 'Valores Permitidos / Ejemplo'],
            // Rows 14-29: Column definitions
            ['A', 'N°', 'No', 'Ignorado', 'Número correlativo de fila. Ignorado durante la importación.', '1, 2, 3...'],
            ['B', 'DNI / N° Documento', 'SÍ', 'user.dni', 'DNI o documento de identidad del estudiante (8 dígitos numéricos).', '74123456'],
            ['C', 'Apellidos y Nombres', 'No', 'user.names', 'Nombre referencial del estudiante (se usa el registrado en el sistema).', 'García López, María Elena'],
            ['D', 'Curso', 'SÍ', 'course.name', 'Nombre exacto del curso o evento tal como figura en el sistema.', 'Excel Avanzado'],
            ['E', 'Fecha de Inicio', 'No', 'start_date', 'Fecha de inicio del curso en formato YYYY-MM-DD.', '2026-01-10'],
            ['F', 'Fecha de Término', 'No', 'end_date', 'Fecha de culminación del curso en formato YYYY-MM-DD.', '2026-03-20'],
            ['G', 'Fecha de Emisión', 'SÍ', 'issue_date', 'Fecha oficial de expedición del certificado en formato YYYY-MM-DD.', '2026-03-21'],
            ['H', 'Horas', 'No', 'duration', 'Horas lectivas o duración total del evento académico.', '120 Horas'],
            ['I', 'Calificación I (numérica)', 'No', 'detail.score', 'Nota numérica del Módulo 1 del curso (escala 0 a 20 o Aprobado).', '17'],
            ['J', 'Calificación Letras I', '—', 'Ignorado', 'Calificación en letras del Módulo 1 (referencial — ignorada).', 'DIECISIETE'],
            ['K', 'Calificación II (numérica)', 'No', 'detail.score', 'Nota numérica del Módulo 2 del curso (escala 0 a 20 o Aprobado).', '19'],
            ['L', 'Calificación Letras II', '—', 'Ignorado', 'Calificación en letras del Módulo 2 (referencial — ignorada).', 'DIECINUEVE'],
            ['M', 'Promedio', '—', 'Ignorado', 'Promedio general ponderado (calculado automáticamente por el sistema).', '18'],
            ['N', 'Modalidad', 'SÍ', 'modality', 'Modalidad de dictado del curso (exactamente uno de los tres valores).', 'Presencial / Virtual / Semipresencial'],
            ['O', 'Código', 'No', 'code', 'Código único del certificado. Si está vacío, se autogenera con formato secuencial.', 'CERT-74123456-1 (o dejar vacío)'],
            ['P', 'Condición / Participación', 'No', 'participation_type', 'Calidad de participación del estudiante. Si está vacío, se asigna ASISTENTE.', 'ASISTENTE / PONENTE / ORGANIZADOR'],
            ['', '', '', '', '', ''],
            // Row 30: Section 3 Header
            ['3. REGLAS ESPECIALES Y CARACTERÍSTICAS DEL SISTEMA', '', '', '', '', ''],
            ['• CONDICIÓN / PARTICIPACIÓN (Columna P):', 'Permite personalizar la condición académica en el certificado impreso. Ejemplos comunes: ASISTENTE, PONENTE, PANELISTA, ORGANIZADOR, PARTICIPANTE, MODERADOR. Si no se especifica, el sistema registrará "ASISTENTE" por defecto.', '', '', '', ''],
            ['• AUTOGENERACIÓN DE CÓDIGO (Columna O):', 'Si la columna Código se deja vacía, el sistema genera automáticamente un identificador secuencial con el formato: CERT-{user_DNI}-{secuencia} (por ejemplo: CERT-74123456-1, CERT-74123456-2 si posee más de uno). Si se indica un código manual, el sistema verificará que no exista colisión.', '', '', '', ''],
            ['• VALIDACIÓN DE DUPLICADOS:', 'El sistema protege contra duplicados verificando que un estudiante no tenga registrado otro certificado con el mismo curso/evento en la misma fecha.', '', '', '', ''],
            ['• COMPATIBILIDAD:', 'Puede subir archivos creados en Microsoft Excel (.xlsx, .xls), Google Sheets, LibreOffice Calc o archivos de texto CSV (.csv).', '', '', '', ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Title merge and style
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->getRowDimension(2)->setRowHeight(20);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 13,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E1B4B'], // Dark Indigo 950
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'indent' => 1,
            ],
        ]);

        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => false,
                'color' => ['rgb' => 'C7D2FE'], // Indigo 200
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '312E81'], // Indigo 900
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'indent' => 1,
            ],
        ]);

        // Section Headers
        $sectionRows = [4, 12, 30];
        foreach ($sectionRows as $r) {
            $sheet->mergeCells("A{$r}:F{$r}");
            $sheet->getRowDimension($r)->setRowHeight(24);
            $sheet->getStyle("A{$r}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '312E81'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EEF2FF'], // Indigo 50
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'indent' => 1,
                ],
            ]);
        }

        // Table Header
        $sheet->getRowDimension(13)->setRowHeight(26);
        $sheet->getStyle('A13:F13')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4338CA'], // Indigo 700
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '3730A3'],
                ],
            ],
        ]);

        // Table Data Rows
        $sheet->getStyle('A14:F29')->applyFromArray([
            'font' => [
                'size' => 9.5,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);

        $sheet->getStyle('A14:A29')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C14:C29')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Highlight Row for P (Condición / Participación)
        $sheet->getStyle('A29:F29')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECFDF5'], // Emerald 50
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '065F46'],
            ],
        ]);

        return [];
    }
}
