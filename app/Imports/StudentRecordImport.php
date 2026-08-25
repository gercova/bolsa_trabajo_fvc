<?php

namespace App\Imports;

use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class StudentRecordImport implements ToCollection, WithStartRow, WithChunkReading
{
    /**
     * Default record type — overridden per-row when cycle column is filled.
     */
    protected string $recordType;

    /**
     * Cache of DocumentType abbreviation → id.
     * Built once at construction to avoid N+1 per row.
     */
    protected array $documentTypeMap = [];

    /**
     * Whether the file is the full MINEDU Excel (cols A-AF = 32 cols)
     * or a partial CSV starting from col H.
     * null = not yet determined.
     */
    protected ?bool $isFullExcel = null;

    /**
     * Counters exposed to the controller for flash messages.
     */
    public int $importedCount = 0;
    public int $skippedCount  = 0;

    /**
     * @param string $recordType  'ADMISION' | 'MATRICULA' | 'AUTO'
     */
    public function __construct(string $recordType = 'AUTO')
    {
        $resolved         = strtoupper(trim($recordType));
        $this->recordType = ($resolved === 'AUTO') ? 'ADMISION' : $resolved;

        // Pre-load all document types once
        DocumentType::all()->each(function ($dt): void {
            // Normalised abbreviation key (e.g. "DNI", "CE", "PAS")
            $key = strtoupper(preg_replace('/[\s.\-]/', '', $dt->abreviation));
            $this->documentTypeMap[$key] = $dt->id;
            // Also index by full name for safety
            $this->documentTypeMap[strtoupper(trim($dt->name))] = $dt->id;
        });
    }

    // ─── Laravel-Excel interface ─────────────────────────────────────────────

    /**
     * Data rows start at row 6 in MINEDU reports
     * (rows 1-5 = title banner, logos, and merged header rows).
     */
    public function startRow(): int
    {
        return 6;
    }

    /**
     * Process rows in chunks of 500 to balance memory and round-trips.
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Receives one chunk at a time and inserts it as a single batch.
     *
     * ── Full MINEDU Excel (≥ 32 columns, A–AF) ──────────────────────────────
     *   [0]  A  → PERIODO             → academic_period  ← read per row
     *   [1]  B  → REGIÓN (IESTP)      → region
     *   [2]  C  → PROVINCIA (IESTP)   → province
     *   [3]  D  → DISTRITO (IESTP)    → district
     *   [4]  E  → CÓDIGO MODULAR      → codigo_modular
     *   [5]  F  → NOMBRE INSTITUCIÓN  → nombre_institucion
     *   [6]  G  → TIPO GESTIÓN        → tipo_gestion
     *   [7]  H  → TIPO DOCUMENTO      → document_type_id
     *   [8]  I  → DOCUMENTO           → document
     *   [9]  J  → APELLIDO PATERNO    → last_name_father
     *   [10] K  → APELLIDO MATERNO    → last_name_mother
     *   [11] L  → NOMBRES             → names
     *   [12] M  → FECHA NACIMIENTO    → birthdate
     *   [13] N  → SEXO                → gender
     *   [14] O  → CORREO              → email
     *   [15] P  → CELULAR             → phone
     *   [16] Q  → LENGUA MATERNA      → mother_tongue
     *   [17] R  → PAIS PROCEDENCIA    → pais_procedencia
     *   [18] S  → UBIGEO IE           → ubigeo_ie
     *   [19] T  → DEPARTAMENTO IE     → region_ie
     *   [20] U  → PROVINCIA IE        → province_ie
     *   [21] V  → DISTRITO IE         → district_ie
     *   [22] W  → TIPO INSTITUCIÓN IE → institution_type_ie
     *   [23] X  → CÓDIGO MODULAR IE   → modular_code_ie
     *   [24] Y  → NOMBRE IE           → institution_name_ie
     *   [25] Z  → TIPO GESTIÓN IE     → management_type_ie
     *   [26] AA → AÑO DE EGRESO       → year_graduation
     *   [27] AB → PROGRAMA ESTUDIOS   → study_program
     *   [28] AC → CICLO               → cycle
     *   [29] AD → ESTADO MATRÍCULA    → enrollment_status
     *   [30] AE → ESTADO PERÍODO      → period_status
     *   [31] AF → FECHA REGISTRO      → registration_date
     *
     * ── Partial CSV (< 32 columns, H–AF only, no A–G prefix) ────────────────
     *   Offset = 0, academic_period left null (fill manually later if needed).
     *   [0]  H  → TIPO DOCUMENTO
     *   [1]  I  → DOCUMENTO
     *   … same relative offsets as Full Excel minus 7.
     */
    public function collection(Collection $rows): void
    {
        $now   = now()->toDateTimeString();
        $batch = [];

        foreach ($rows as $row) {
            // Determine format on first meaningful row
            if ($this->isFullExcel === null) {
                $this->isFullExcel = $row->count() >= 32;
            }

            if ($this->isFullExcel) {
                // ── Full Excel path ──────────────────────────────────────────
                $document = $this->cleanString($row[8] ?? null);
                $names    = $this->cleanString($row[11] ?? null);

                if (empty($document) && empty($names)) {
                    $this->skippedCount++;
                    continue;
                }

                $cycle      = $this->cleanString($row[28] ?? null);
                $recordType = !empty($cycle) ? 'MATRICULA' : $this->recordType;

                $batch[] = [
                    // Institution where the student studies (cols A-G)
                    'academic_period'     => $this->cleanString($row[0] ?? null),
                    'region'              => $this->cleanString($row[1] ?? null),
                    'province'            => $this->cleanString($row[2] ?? null),
                    'district'            => $this->cleanString($row[3] ?? null),
                    'codigo_modular'      => $this->cleanString($row[4] ?? null),
                    'nombre_institucion'  => $this->cleanString($row[5] ?? null),
                    'tipo_gestion'        => $this->cleanString($row[6] ?? null),
                    // Student personal data (cols H-R)
                    'document_type_id'    => $this->resolveDocumentTypeId($this->cleanString($row[7] ?? null)),
                    'document'            => $document,
                    'last_name_father'    => $this->cleanString($row[9]  ?? null),
                    'last_name_mother'    => $this->cleanString($row[10] ?? null),
                    'names'               => $names,
                    'birthdate'           => $this->parseDate($row[12] ?? null),
                    'gender'              => $this->normalizeGender($row[13] ?? null),
                    'email'               => $this->cleanString($row[14] ?? null),
                    'phone'               => $this->cleanString($row[15] ?? null),
                    'mother_tongue'       => $this->cleanString($row[16] ?? null),
                    'pais_procedencia'    => $this->cleanString($row[17] ?? null),
                    // Origin IE (cols S-AA)
                    'ubigeo_ie'           => $this->cleanString($row[18] ?? null),
                    'region_ie'           => $this->cleanString($row[19] ?? null),
                    'province_ie'         => $this->cleanString($row[20] ?? null),
                    'district_ie'         => $this->cleanString($row[21] ?? null),
                    'institution_type_ie' => $this->cleanString($row[22] ?? null),
                    'modular_code_ie'     => $this->cleanString($row[23] ?? null),
                    'institution_name_ie' => $this->cleanString($row[24] ?? null),
                    'management_type_ie'  => $this->cleanString($row[25] ?? null),
                    'year_graduation'     => $this->cleanInt($row[26] ?? null),
                    // Academic process (cols AB-AF)
                    'study_program'       => $this->cleanString($row[27] ?? null),
                    'cycle'               => $cycle,
                    'enrollment_status'   => $this->cleanString($row[29] ?? null),
                    'period_status'       => $this->cleanString($row[30] ?? null),
                    'registration_date'   => $this->parseDate($row[31] ?? null),
                    'record_type'         => $recordType,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            } else {
                // ── Partial CSV path (H–AF only, no A-G columns) ────────────
                // Offset is 0: col H is at index 0
                $document = $this->cleanString($row[1] ?? null);  // I
                $names    = $this->cleanString($row[4] ?? null);  // L

                if (empty($document) && empty($names)) {
                    $this->skippedCount++;
                    continue;
                }

                $cycle      = $this->cleanString($row[21] ?? null);
                $recordType = !empty($cycle) ? 'MATRICULA' : $this->recordType;

                $batch[] = [
                    'academic_period'     => null,
                    'document_type_id'    => $this->resolveDocumentTypeId($this->cleanString($row[0] ?? null)),
                    'document'            => $document,
                    'last_name_father'    => $this->cleanString($row[2]  ?? null),
                    'last_name_mother'    => $this->cleanString($row[3]  ?? null),
                    'names'               => $names,
                    'birthdate'           => $this->parseDate($row[5]    ?? null),
                    'gender'              => $this->normalizeGender($row[6] ?? null),
                    'email'               => $this->cleanString($row[7]  ?? null),
                    'phone'               => $this->cleanString($row[8]  ?? null),
                    'mother_tongue'       => $this->cleanString($row[9]  ?? null),
                    'pais_procedencia'    => $this->cleanString($row[10] ?? null),
                    'ubigeo_ie'           => $this->cleanString($row[11] ?? null),
                    'region_ie'           => $this->cleanString($row[12] ?? null),
                    'province_ie'         => $this->cleanString($row[13] ?? null),
                    'district_ie'         => $this->cleanString($row[14] ?? null),
                    'institution_type_ie' => $this->cleanString($row[15] ?? null),
                    'modular_code_ie'     => $this->cleanString($row[16] ?? null),
                    'institution_name_ie' => $this->cleanString($row[17] ?? null),
                    'management_type_ie'  => $this->cleanString($row[18] ?? null),
                    'year_graduation'     => $this->cleanInt($row[19] ?? null),
                    'study_program'       => $this->cleanString($row[20] ?? null),
                    'cycle'               => $cycle,
                    'enrollment_status'   => $this->cleanString($row[22] ?? null),
                    'period_status'       => $this->cleanString($row[23] ?? null),
                    'registration_date'   => $this->parseDate($row[24]   ?? null),
                    'record_type'         => $recordType,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        // ── Bulk insert the whole chunk in one query ──────────────────────────
        if (!empty($batch)) {
            DB::table('student_records')->insert($batch);
            $this->importedCount += count($batch);
        }
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function cleanString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $str = trim((string) $value);
        return $str !== '' ? $str : null;
    }

    private function cleanInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $int = intval($value);
        return $int > 0 ? $int : null;
    }

    private function normalizeGender(mixed $value): ?string
    {
        $v = strtoupper(trim((string) ($value ?? '')));
        return match (true) {
            in_array($v, ['M', 'H', 'MASCULINO', 'MALE', 'HOMBRE'], true) => 'MASCULINO',
            in_array($v, ['F', 'FEMENINO', 'FEMALE', 'MUJER'],       true) => 'FEMENINO',
            default                                                         => null,
        };
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

    private function resolveDocumentTypeId(?string $raw): ?int
    {
        if (empty($raw)) {
            return null;
        }

        $key = strtoupper(preg_replace('/[\s.\-]/', '', $raw));

        if (isset($this->documentTypeMap[$key])) {
            return $this->documentTypeMap[$key];
        }

        foreach ($this->documentTypeMap as $mapKey => $id) {
            if (str_contains($mapKey, $key) || str_contains($key, $mapKey)) {
                return $id;
            }
        }

        return null;
    }
}
