<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DegreeRecordImport implements ToCollection, WithStartRow, WithChunkReading
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    /**
     * Data rows begin at row 5.
     * Rows 1-4 in the MINEDU report are:
     *   1 = Title banner ("REPORTE DE GRADOS Y TÍTULOS REGISTRADOS")
     *   2 = Source line
     *   3 = Generation date
     *   4 = Column headers
     */
    public function startRow(): int
    {
        return 5;
    }

    /**
     * Process 500 rows per chunk → single INSERT per chunk.
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Column mapping (0-indexed, columns A–X):
     *
     *  [0]  A  → N° del reporte          → IGNORED
     *  [1]  B  → CÓDIGO MODULAR          → modular_code
     *  [2]  C  → NOMBRE INSTITUCIÓN      → institution_name
     *  [3]  D  → TIPO DE GESTIÓN         → management_type
     *  [4]  E  → DEPARTAMENTO            → department
     *  [5]  F  → PROGRAMA DE ESTUDIOS    → study_program
     *  [6]  G  → MENCIÓN                 → IGNORED
     *  [7]  H  → NIVEL FORMATIVO         → formative_level
     *  [8]  I  → FAMILIA PRODUCTIVA      → productive_family
     *  [9]  J  → TIPO DE DOCUMENTO       → document_type
     *  [10] K  → NÚMERO DE DOCUMENTO     → document_number
     *  [11] L  → NOMBRES COMPLETOS       → full_names
     *  [12] M  → FECHA DE NACIMIENTO     → birth_date
     *  [13] N  → SEXO                    → gender
     *  [14] O  → FECHA DE EGRESO         → graduation_date
     *  [15] P  → NÚMERO DE REGISTRO      → institutional_registration_number
     *  [16] Q  → FECHA DE EMISIÓN        → diploma_issue_date
     *  [17] R  → FECHA DE REGISTRO       → minedu_registration_date
     *  [18] S  → CÓDIGO TÍTULO GENERADO  → generated_title_code
     *  [19] T  → NÚMERO DE EXPEDIENTE    → file_number
     *  [20] U  → TIPO DE REGISTRO        → registration_type
     *  [21] V  → USUARIO ESPECIALISTA    → specialist_user
     *  [22] W  → (blank / not used)      → IGNORED
     *  [23] X  → TIPO DE DIPLOMA         → diploma_type
     */
    public function collection(Collection $rows): void
    {
        $now   = now()->toDateTimeString();
        $batch = [];

        foreach ($rows as $row) {
            // Skip if both the document number (K) and full name (L) are empty
            $docNumber = $this->cleanString($row[10] ?? null);
            $fullNames = $this->cleanString($row[11] ?? null);

            if (empty($docNumber) && empty($fullNames)) {
                $this->skippedCount++;
                continue;
            }

            $batch[] = [
                // A [0] → IGNORED (record_number not mapped per user request)
                'modular_code'                      => $this->cleanString($row[1]  ?? null),
                'institution_name'                  => $this->cleanString($row[2]  ?? null),
                'management_type'                   => $this->cleanString($row[3]  ?? null),
                'department'                        => $this->cleanString($row[4]  ?? null),
                'study_program'                     => $this->cleanString($row[5]  ?? null),
                // G [6] → IGNORED (mention)
                'formative_level'                   => $this->cleanString($row[7]  ?? null),
                'productive_family'                 => $this->cleanString($row[8]  ?? null),
                'document_type'                     => $this->cleanString($row[9]  ?? null),
                'document_number'                   => $docNumber,
                'full_names'                        => $fullNames,
                'birth_date'                        => $this->parseDate($row[12]   ?? null),
                'gender'                            => $this->cleanString($row[13] ?? null),
                'graduation_date'                   => $this->parseDate($row[14]   ?? null),
                'institutional_registration_number' => $this->cleanString($row[15] ?? null),
                'diploma_issue_date'                => $this->parseDate($row[16]   ?? null),
                'minedu_registration_date'          => $this->parseDate($row[17]   ?? null),
                'generated_title_code'              => $this->cleanString($row[18] ?? null),
                'file_number'                       => $this->cleanString($row[19] ?? null),
                'registration_type'                 => $this->cleanString($row[20] ?? null),
                'specialist_user'                   => $this->cleanString($row[21] ?? null),
                // W [22] → IGNORED
                'diploma_type'                      => $this->cleanString($row[23] ?? null),
                'created_at'                        => $now,
                'updated_at'                        => $now,
            ];
        }

        if (!empty($batch)) {
            DB::table('degree_records')->insert($batch);
            $this->importedCount += count($batch);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function cleanString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $str = trim((string) $value);
        return $str !== '' ? $str : null;
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel OLE numeric serial
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Exception) {
                return null;
            }
        }

        $str = trim((string) $value);

        foreach (['d/m/Y', 'd-m-Y', 'd/m/y', 'Y-m-d', 'Y/m/d', 'm/d/Y', 'd/m/Y H:i:s', 'Y-m-d H:i:s'] as $fmt) {
            try {
                $date = Carbon::createFromFormat($fmt, $str);
                if ($date && $date->year > 1900 && $date->year < 2100) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception) {
                // try next format
            }
        }

        return null;
    }
}
