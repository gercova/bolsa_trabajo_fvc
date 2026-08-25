<?php

namespace App\Imports;

use App\Models\DocumentType;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StudentRecordImport implements ToCollection, WithStartRow, WithChunkReading
{
    /**
     * Academic period (e.g. "2026-I") to assign to every imported row.
     */
    protected string $academicPeriod;

    /**
     * Default record type. Overridden per-row if cycle is present.
     */
    protected string $recordType;

    /**
     * Cache of DocumentType abbreviation → id for quick lookup.
     */
    protected array $documentTypeMap = [];

    /**
     * Offset from index 0 to column H.
     * Excel full-report: offset = 7 (columns A-G exist before H).
     * CSV exported from just H onwards: offset = 0.
     * Auto-detected on first non-empty row.
     */
    protected ?int $colOffset = null;

    /**
     * Counters for reporting.
     */
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    public function __construct(string $academicPeriod, string $recordType = 'ADMISION')
    {
        $this->academicPeriod = strtoupper(trim($academicPeriod));
        $resolved             = strtoupper(trim($recordType));
        // AUTO means detect per-row; store ADMISION as the default fallback
        $this->recordType = ($resolved === 'AUTO') ? 'ADMISION' : $resolved;

        // Build document type lookup map once
        DocumentType::all()->each(function ($dt) {
            $key = strtoupper(trim(preg_replace('/[\s.\-]/', '', $dt->abreviation)));
            $this->documentTypeMap[$key] = $dt->id;
            $this->documentTypeMap[strtoupper(trim($dt->name))] = $dt->id;
        });
    }

    /**
     * Data starts at row 6 (rows 1-5: title, logo, header rows in MINEDU format).
     * For plain CSV exports without header rows, startRow = 1 should be used externally,
     * but for the MINEDU xlsx the default of 6 applies.
     */
    public function startRow(): int
    {
        return 6;
    }

    /**
     * Process rows in chunks of 200 to avoid memory spikes.
     */
    public function chunkSize(): int
    {
        return 200;
    }

    /**
     * Detect the column offset for the current row.
     *
     * Strategy: column H (TIPO DOCUMENTO) contains values like "D.N.I.", "C.E.", etc.
     * We search the first 15 indexes of the row for a value matching a known doc-type
     * abbreviation keyword. If found, that index becomes offset for H.
     * Fallback: if row has >= 32 columns → offset = 7 (full Excel).
     *           if row has < 32 columns  → offset = 0 (CSV from col H only).
     */
    private function detectOffset(Collection $row): int
    {
        if ($this->colOffset !== null) {
            return $this->colOffset;
        }

        $rowArray = $row->toArray();
        $count    = count($rowArray);

        // Heuristic 1: row width suggests full-sheet vs partial CSV
        if ($count >= 32) {
            $this->colOffset = 7;
            return 7;
        }

        // Heuristic 2: look for a doc-type keyword in first 10 cells
        $docKeywords = ['DNI', 'D.N.I', 'CE', 'C.E', 'PAS', 'RUC'];
        for ($i = 0; $i < min(10, $count); $i++) {
            $val = strtoupper(trim(preg_replace('/[\s.\-]/', '', (string)($rowArray[$i] ?? ''))));
            if (in_array($val, $docKeywords, true) || str_contains($val, 'DNI') || str_contains($val, 'CE')) {
                $this->colOffset = $i;
                return $i;
            }
        }

        // Fallback: no offset
        $this->colOffset = 0;
        return 0;
    }

    /**
     * Process each chunk (collection of rows).
     *
     * Column mapping relative to detected offset O:
     *   O+0  → TIPO DOCUMENTO      → document_type_id
     *   O+1  → DOCUMENTO           → document
     *   O+2  → APELLIDO PATERNO    → last_name_father
     *   O+3  → APELLIDO MATERNO    → last_name_mother
     *   O+4  → NOMBRES             → names
     *   O+5  → FECHA NACIMIENTO    → birthdate
     *   O+6  → SEXO                → gender
     *   O+7  → CORREO              → email
     *   O+8  → CELULAR             → phone
     *   O+9  → LENGUA MATERNA      → mother_tongue
     *   O+10 → PAIS PROCEDENCIA    → pais_procedencia
     *   O+11 → UBIGEO IE           → ubigeo_ie
     *   O+12 → DEPARTAMENTO IE     → region_ie
     *   O+13 → PROVINCIA IE        → province_ie
     *   O+14 → DISTRITO IE         → district_ie
     *   O+15 → TIPO INSTITUCIÓN    → institution_type_ie
     *   O+16 → CODIGO MODULAR IE   → modular_code_ie
     *   O+17 → NOMBRE IE           → institution_name_ie
     *   O+18 → TIPO GESTION IE     → management_type_ie
     *   O+19 → AÑO DE EGRESO       → year_graduation
     *   O+20 → PROGRAMA ESTUDIOS   → study_program
     *   O+21 → CICLO               → cycle
     *   O+22 → ESTADO MATRÍCULA    → enrollment_status
     *   O+23 → ESTADO PERÍODO      → period_status
     *   O+24 → FECHA REGISTRO      → registration_date
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            // Detect offset on first non-empty row
            $offset = $this->detectOffset($row);

            // col I (offset+1) = document, col L (offset+4) = names
            $document = $this->cleanString($row[$offset + 1] ?? null);
            $names    = $this->cleanString($row[$offset + 4] ?? null);

            if (empty($document) && empty($names)) {
                $this->skippedCount++;
                continue;
            }

            // Resolve document type from col H (offset+0)
            $docTypeRaw = $this->cleanString($row[$offset + 0] ?? null);
            $docTypeId  = $this->resolveDocumentTypeId($docTypeRaw);

            // Determine record type per row: if cycle (offset+21) is filled → MATRICULA
            $cycle      = $this->cleanString($row[$offset + 21] ?? null);
            $recordType = (!empty($cycle)) ? 'MATRICULA' : $this->recordType;

            $data = [
                'document_type_id'    => $docTypeId,
                'document'            => $document,
                'last_name_father'    => $this->cleanString($row[$offset + 2] ?? null),
                'last_name_mother'    => $this->cleanString($row[$offset + 3] ?? null),
                'names'               => $names,
                'birthdate'           => $this->parseDate($row[$offset + 5] ?? null),
                'gender'              => $this->normalizeGender($row[$offset + 6] ?? null),
                'email'               => $this->cleanString($row[$offset + 7] ?? null),
                'phone'               => $this->cleanString($row[$offset + 8] ?? null),
                'mother_tongue'       => $this->cleanString($row[$offset + 9] ?? null),
                'pais_procedencia'    => $this->cleanString($row[$offset + 10] ?? null),
                'ubigeo_ie'           => $this->cleanString($row[$offset + 11] ?? null),
                'region_ie'           => $this->cleanString($row[$offset + 12] ?? null),
                'province_ie'         => $this->cleanString($row[$offset + 13] ?? null),
                'district_ie'         => $this->cleanString($row[$offset + 14] ?? null),
                'institution_type_ie' => $this->cleanString($row[$offset + 15] ?? null),
                'modular_code_ie'     => $this->cleanString($row[$offset + 16] ?? null),
                'institution_name_ie' => $this->cleanString($row[$offset + 17] ?? null),
                'management_type_ie'  => $this->cleanString($row[$offset + 18] ?? null),
                'year_graduation'     => $this->cleanInt($row[$offset + 19] ?? null),
                'study_program'       => $this->cleanString($row[$offset + 20] ?? null),
                'cycle'               => $cycle,
                'enrollment_status'   => $this->cleanString($row[$offset + 22] ?? null),
                'period_status'       => $this->cleanString($row[$offset + 23] ?? null),
                'registration_date'   => $this->parseDate($row[$offset + 24] ?? null),
                'academic_period'     => $this->academicPeriod,
                'record_type'         => $recordType,
            ];

            StudentRecord::create($data);
            $this->importedCount++;
        }
    }

    // ─── Private helpers ────────────────────────────────────────────────────

    /**
     * Clean and trim a cell value, returning null if empty.
     */
    private function cleanString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $str = trim((string) $value);
        return $str !== '' ? $str : null;
    }

    /**
     * Clean integer cell value.
     */
    private function cleanInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $int = intval($value);
        return $int > 0 ? $int : null;
    }

    /**
     * Normalize gender value from various Spanish/abbreviated formats.
     */
    private function normalizeGender(mixed $value): ?string
    {
        $v = strtoupper(trim((string) ($value ?? '')));
        if (in_array($v, ['M', 'H', 'MASCULINO', 'MALE', 'HOMBRE'])) {
            return 'MASCULINO';
        }
        if (in_array($v, ['F', 'FEMENINO', 'FEMALE', 'MUJER'])) {
            return 'FEMENINO';
        }
        return null;
    }

    /**
     * Parse date from Peruvian format (d/m/Y), ISO (Y-m-d),
     * or Excel OLE numeric serial.
     */
    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Numeric: likely OLE serial from Excel
        if (is_numeric($value)) {
            try {
                $date = ExcelDate::excelToDateTimeObject((float) $value);
                return $date->format('Y-m-d');
            } catch (\Exception) {
                return null;
            }
        }

        $str = trim((string) $value);

        // Try d/m/Y or d-m-Y (Peruvian standard) and other common formats
        foreach (['d/m/Y', 'd-m-Y', 'd/m/y', 'Y-m-d', 'Y/m/d', 'm/d/Y', 'd/m/Y H:i:s', 'Y-m-d H:i:s'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $str);
                if ($date && $date->year > 1900 && $date->year < 2100) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception) {
                // continue trying next format
            }
        }

        return null;
    }

    /**
     * Resolve DocumentType ID from abbreviation text in the cell.
     * Matches D.N.I., DNI, C.E., CE, etc.
     */
    private function resolveDocumentTypeId(?string $raw): ?int
    {
        if (empty($raw)) {
            return null;
        }

        // Normalize: remove dots, spaces, dashes → uppercase
        $key = strtoupper(preg_replace('/[\s.\-]/', '', $raw));

        // Direct lookup
        if (isset($this->documentTypeMap[$key])) {
            return $this->documentTypeMap[$key];
        }

        // Partial match — find first key that contains the normalized value
        foreach ($this->documentTypeMap as $mapKey => $id) {
            if (str_contains($mapKey, $key) || str_contains($key, $mapKey)) {
                return $id;
            }
        }

        return null;
    }
}
