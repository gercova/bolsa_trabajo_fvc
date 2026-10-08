<?php

namespace App\Imports;

use App\Models\Certificate;
use App\Models\CertificateDetail;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CertificateImport implements ToCollection, WithChunkReading, WithStartRow
{
    public int $importedCount = 0;

    public int $skippedCount = 0;

    public int $detailCount = 0;

    public int $createdUsers = 0;

    public array $errors = [];

    /**
     * Data rows begin at row 2 (row 1 is the header).
     *
     * Real Excel column layout (0-indexed):
     *
     *  [0]  A  → N° (número de fila)           → ignorado
     *  [1]  B  → DNI / N° Documento            → match user by 'dni'
     *                                              (se crea el usuario si no existe)
     *  [2]  C  → Apellidos y Nombres           → User.names si se crea el usuario
     *  [3]  D  → Curso                         → match course by name
     *  [4]  E  → Fecha de Inicio               → start_date
     *  [5]  F  → Fecha de Término              → end_date
     *  [6]  G  → Fecha de Emisión              → issue_date
     *  [7]  H  → Horas                         → duration ("N Horas")
     *  [8]  I  → Calificación Módulo I (núm.)  → CertificateDetail, módulo 1 del curso
     *  [9]  J  → *** IGNORADA ***              (calificación en letras, módulo I)
     *  [10] K  → Calificación Módulo II (núm.) → CertificateDetail, módulo 2 del curso
     *  [11] L  → *** IGNORADA ***              (calificación en letras, módulo II)
     *  [12] M  → Promedio                      → ignorado
     *  [13] N  → Modalidad                     → modality
     *  [14] O  → Código                        → code (si está vacío, auto-genera CERT-{DNI}-{secuencia})
     */
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function collection(Collection $rows): void
    {
        // Pre-load users (keyed by DNI) and courses with modules (keyed by lowercase name)
        $users = User::pluck('id', 'dni');
        $courses = Course::with(['modules' => fn ($q) => $q->orderBy('id')])
            ->get()
            ->keyBy(fn ($c) => strtolower(trim($c->name)));

        foreach ($rows as $rowIndex => $row) {
            $rowNum = $rowIndex + 2; // 1-based Excel row (header = row 1)

            $dni = $this->cleanString($row[1] ?? null);
            $names = $this->cleanString($row[2] ?? null);

            // Skip completely blank rows (no DNI)
            if (empty($dni)) {
                $this->skippedCount++;

                continue;
            }

            // ── 1. Resolve user by DNI — auto-create if not found ─────────────
            $userId = $users->get($dni);

            if (! $userId) {
                try {
                    $newUser = User::create([
                        'document_type_id' => 1,                     // DNI (default)
                        'dni' => $dni,
                        'names' => $names ?? $dni,        // fallback to DNI if no name
                        'password' => Hash::make($dni),      // temp password = DNI
                        'role' => 'Solicitante',
                        'is_active' => true,
                    ]);

                    $userId = $newUser->id;
                    $users->put($dni, $userId);   // cache for subsequent rows
                    $this->createdUsers++;

                } catch (\Exception $e) {
                    $this->errors[] = "Fila {$rowNum}: No se pudo crear el usuario con DNI '{$dni}' — ".$e->getMessage();
                    $this->skippedCount++;

                    continue;
                }
            }

            // ── 2. Resolve course by name (case-insensitive) ──────────────────
            $courseNameRaw = $this->cleanString($row[3] ?? null);
            $course = $courseNameRaw
                ? $courses->get(strtolower(trim($courseNameRaw)))
                : null;

            if (! $course) {
                $this->errors[] = "Fila {$rowNum}: Curso '{$courseNameRaw}' no encontrado — fila omitida.";
                $this->skippedCount++;

                continue;
            }

            $courseId = $course->id;

            // ── 3. Parse fields ──────────────────────────────────────────────
            $startDate = $this->parseDate($row[4] ?? null);
            $endDate = $this->parseDate($row[5] ?? null);
            $issueDate = $this->parseDate($row[6] ?? null) ?? now()->toDateString();
            $horasRaw = $this->cleanString($row[7] ?? null);
            $duration = $horasRaw !== null ? $horasRaw.' Horas' : null;
            $modality = $this->parseModality($this->cleanString($row[13] ?? null));

            // ── 4. Check for duplicate certificate (same user, same event/course, same date) ──
            $duplicate = Certificate::findDuplicate(
                userId: $userId,
                courseId: $courseId,
                eventName: $course->event_name,
                issueDate: $issueDate,
                startDate: $startDate
            );

            if ($duplicate) {
                $existingCode = $duplicate->code ?: $duplicate->certificate_code;
                $this->errors[] = "Fila {$rowNum}: Certificado duplicado omitido. El estudiante con DNI '{$dni}' ya cuenta con un certificado para el curso/evento '{$course->name}' en la fecha {$issueDate} (Código existente: {$existingCode}).";
                $this->skippedCount++;

                continue;
            }

            // ── 5. Resolve certificate code (use provided code or auto-generate CERT-{user_DNI}-{sequence_number}) ──
            $providedCode = $this->cleanString($row[14] ?? null);
            if (! empty($providedCode)) {
                if (Certificate::where('code', $providedCode)->orWhere('certificate_code', $providedCode)->exists()) {
                    $this->errors[] = "Fila {$rowNum}: El código '{$providedCode}' ya está registrado para otro certificado — fila omitida.";
                    $this->skippedCount++;

                    continue;
                }
                $code = $providedCode;
            } else {
                $code = Certificate::generateUniqueCodeForStudent($dni);
            }

            // ── 6. Create certificate ─────────────────────────────────────────
            try {
                $certificate = Certificate::create([
                    'code' => $code,
                    'certificate_code' => $code,
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'certificate_type' => $course->certificate_type ?? 'capacitacion',
                    'participation_type' => 'ASISTENTE',
                    'event_name' => $course->event_name,
                    'study_program_id' => $course->study_program_id,
                    'description' => $course->description,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'duration' => $duration,
                    'modality' => $modality,
                    'issue_date' => $issueDate,
                    'is_active' => true,
                ]);

                $this->importedCount++;

                // ── 6. Assign module scores by position ───────────────────────
                // Col I (index 8)  → módulo 1 (first module of the course, ordered by id)
                // Col K (index 10) → módulo 2
                // Cols J (9) and L (11) are ignored (letter representation of scores)
                $modules = $course->modules->values();

                $scoreMap = [
                    0 => $this->cleanString($row[8] ?? null), // Módulo 1
                    1 => $this->cleanString($row[10] ?? null), // Módulo 2
                ];

                foreach ($scoreMap as $position => $score) {
                    $module = $modules->get($position);

                    if ($module === null || $score === null) {
                        continue;
                    }

                    CertificateDetail::updateOrCreate(
                        [
                            'certificate_id' => $certificate->id,
                            'module_id' => $module->id,
                        ],
                        [
                            'score' => $score,
                            'is_active' => true,
                        ]
                    );

                    $this->detailCount++;
                }

            } catch (\Exception $e) {
                $this->errors[] = "Fila {$rowNum}: Error — ".$e->getMessage();
                $this->skippedCount++;
            }
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Sanitise a raw cell value to a clean UTF-8 string.
     *
     * Handles: iconv(): Detected an incomplete multibyte character in input string
     * Some Excel cells are stored in Windows-1252 / ISO-8859-1 encoding.
     * We normalise everything to clean UTF-8 before any string processing.
     */
    private function cleanString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $str = trim((string) $value);

        if ($str === '') {
            return null;
        }

        // 1. Re-encode if not valid UTF-8 (Windows-1252 is the most common source)
        if (! mb_check_encoding($str, 'UTF-8')) {
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        }

        // 2. Strip any remaining invalid byte sequences (suppress PHP notice)
        $sanitised = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
        if ($sanitised !== false) {
            $str = $sanitised;
        }

        $str = trim($str);

        return $str !== '' ? $str : null;
    }

    /**
     * Parse a date value from Excel.
     * Handles OLE numeric serial dates and common string formats.
     */
    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel OLE numeric serial date
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Exception) {
                return null;
            }
        }

        $str = $this->cleanString($value);
        if ($str === null) {
            return null;
        }

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

    /**
     * Normalise a raw modality string to one of the three accepted values.
     */
    private function parseModality(?string $raw): string
    {
        if (! $raw) {
            return 'Presencial';
        }

        $lower = strtolower(trim($raw));

        if (str_contains($lower, 'virtual')) {
            return 'Virtual';
        }

        if (str_contains($lower, 'semi') || str_contains($lower, 'híbrid') || str_contains($lower, 'hibrid')) {
            return 'Semipresencial';
        }

        return 'Presencial';
    }
}
