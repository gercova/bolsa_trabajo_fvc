<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Role;

class UserImport implements ToCollection, WithChunkReading, WithStartRow
{
    public int $createdCount = 0;

    public int $updatedCount = 0;

    public int $skippedCount = 0;

    public array $errors = [];

    protected array $generatedEmailsInBatch = [];

    /**
     * Start reading from row 2 (row 1 is assumed to be header).
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
        // Ensure DNI document type (ID 1) exists in database
        if (! DB::table('document_type')->where('id', 1)->exists()) {
            DB::table('document_type')->insertOrIgnore([
                'id' => 1,
                'name' => 'Documento Nacional de Identidad',
                'abreviation' => 'DNI',
                'is_active' => true,
            ]);
        }

        // Preload existing users indexed by DNI and existing emails for quick uniqueness lookups
        $existingUsers = User::all()->keyBy('dni');
        $availableRoles = Role::pluck('name')->map(fn ($r) => mb_strtolower($r))->toArray();

        foreach ($rows as $rowIndex => $row) {
            $rowNum = $rowIndex + 2; // 1-based index (header = 1)

            // Extract row values safely
            $colA = $this->cleanString($row[0] ?? null);
            $colB = $this->cleanString($row[1] ?? null);
            $colC = $this->cleanString($row[2] ?? null);
            $colD = $this->cleanString($row[3] ?? null);
            $colE = $this->cleanString($row[4] ?? null);
            $colF = $this->cleanString($row[5] ?? null);
            $colG = $this->cleanString($row[6] ?? null);
            $colH = $this->cleanString($row[7] ?? null);
            $colI = $this->cleanString($row[8] ?? null);

            // Skip completely empty rows
            if (empty($colA) && empty($colB) && empty($colC) && empty($colD) && empty($colF)) {
                $this->skippedCount++;

                continue;
            }

            // Detect if this is an accidental repeated header row
            if (
                preg_match('/^(n°|dni|documento|apellidos|nombres|tipo)/i', $colA ?? '') ||
                preg_match('/^(dni|documento|n°)/i', $colB ?? '')
            ) {
                $this->skippedCount++;

                continue;
            }

            // Determine column layout:
            // Template layout: Col 0: N°, Col 1: DNI, Col 2: Nombres, Col 3: Apellido Paterno, Col 4: Apellido Materno, Col 5: job_position, Col 6: email, Col 7: role, Col 8: phone
            // Direct layout (without N°): Col 0: DNI, Col 1: Nombres, Col 2: Apellido Paterno, Col 3: Apellido Materno, Col 4: job_position, Col 5: email...
            if (! empty($colB) && (is_numeric($colB) || strlen($colB) >= 7)) {
                // Template format with N° in column 0
                $dni = $colB;
                $firstName = $colC;
                $paternalSurname = $colD;
                $maternalSurname = $colE;
                $rawJobPosition = $colF;
                $rawEmail = $colG;
                $rawRole = $colH;
                $phone = $colI;
            } elseif (! empty($colA) && (is_numeric($colA) || strlen($colA) >= 7)) {
                // Direct format with DNI in column 0
                $dni = $colA;
                $firstName = $colB;
                $paternalSurname = $colC;
                $maternalSurname = $colD;
                $rawJobPosition = $colE;
                $rawEmail = $colF;
                $rawRole = $colG;
                $phone = $colH;
            } else {
                $this->errors[] = "Fila {$rowNum}: No se pudo identificar el número de DNI.";
                $this->skippedCount++;

                continue;
            }

            // 1. Validation: DNI
            $dni = preg_replace('/[^0-9A-Za-z]/', '', $dni);
            if (empty($dni) || strlen($dni) < 5 || strlen($dni) > 20) {
                $this->errors[] = "Fila {$rowNum}: El DNI '{$dni}' no es válido. Debe tener un formato correcto.";
                $this->skippedCount++;

                continue;
            }

            // 2. Validation: Names & Surnames
            if (empty($firstName) && empty($paternalSurname)) {
                $this->errors[] = "Fila {$rowNum} (DNI {$dni}): Los nombres o apellidos son obligatorios.";
                $this->skippedCount++;

                continue;
            }

            // Resolve full name and parts if single name field was provided
            if (empty($paternalSurname) && ! empty($firstName)) {
                [$resolvedFirst, $resolvedPaternal, $resolvedMaternal] = $this->parseFullName($firstName);
                $firstName = $resolvedFirst;
                $paternalSurname = $resolvedPaternal;
                $maternalSurname = $maternalSurname ?: $resolvedMaternal;
            }

            $fullName = trim("{$paternalSurname} {$maternalSurname} {$firstName}");
            if (empty($fullName)) {
                $fullName = trim("{$firstName} {$paternalSurname}");
            }

            // 3. Validation: job_position (MUST be "student/graduate" or "teaching/administrative staff")
            $jobPosition = self::normalizeJobPosition($rawJobPosition);
            if (! $jobPosition) {
                $posDisplay = $rawJobPosition ?: 'vacío';
                $this->errors[] = "Fila {$rowNum} (DNI {$dni}): El campo job_position ('{$posDisplay}') es inválido. Debe ser 'student/graduate' o 'teaching/administrative staff'.";
                $this->skippedCount++;

                continue;
            }

            // 4. Resolve Email:
            // If user has an email, validate and use it; otherwise generate using formula:
            // first letter of first name + full last name + first letter of maternal surname + @iestpfvc.edu.pe
            $email = null;
            if (! empty($rawEmail)) {
                $cleanEmail = mb_strtolower(trim($rawEmail));
                if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                    $email = $cleanEmail;
                } else {
                    $this->errors[] = "Fila {$rowNum} (DNI {$dni}): El correo electrónico '{$rawEmail}' no tiene un formato válido.";
                    $this->skippedCount++;

                    continue;
                }
            } else {
                $email = $this->generateInstitutionalEmail($firstName ?: 'Usuario', $paternalSurname ?: 'Fvc', $maternalSurname);
            }

            // 5. Password: Set to 'P4$$w0rd*'
            $passwordHash = Hash::make('P4$$w0rd*');

            // 6. Role: Resolve role
            $role = $this->resolveRole($rawRole, $jobPosition, $availableRoles);

            // 7. Persist or Update User
            try {
                $existing = $existingUsers->get($dni) ?? User::where('dni', $dni)->first();

                if ($existing) {
                    $existing->update([
                        'document_type_id' => 1, // Always DNI [1]
                        'names' => $fullName ?: $existing->names,
                        'email' => $existing->email ?: $email,
                        'job_position' => $jobPosition,
                        'role' => $role ?: $existing->role,
                        'phone' => $phone ?: $existing->phone,
                        'password' => $passwordHash, // Set to P4$$w0rd*
                        'is_active' => true,
                    ]);

                    if ($role && in_array(mb_strtolower($role), $availableRoles, true)) {
                        $existing->syncRoles([$role]);
                    }

                    $this->updatedCount++;
                } else {
                    // Check if email is already taken by another user
                    if (User::where('email', $email)->exists()) {
                        // Generate a unique fallback email
                        $email = $this->generateUniqueEmailFallback($email);
                    }

                    $newUser = User::create([
                        'document_type_id' => 1, // Always DNI [1]
                        'dni' => $dni,
                        'names' => $fullName,
                        'email' => $email,
                        'job_position' => $jobPosition,
                        'role' => $role,
                        'phone' => $phone ?: null,
                        'password' => $passwordHash, // Set to P4$$w0rd*
                        'is_active' => true,
                    ]);

                    if ($role && in_array(mb_strtolower($role), $availableRoles, true)) {
                        $newUser->assignRole($role);
                    }

                    $existingUsers->put($dni, $newUser);
                    $this->createdCount++;
                }
            } catch (\Throwable $e) {
                $this->errors[] = "Fila {$rowNum} (DNI {$dni}): Error al guardar usuario - ".$e->getMessage();
                $this->skippedCount++;
            }
        }
    }

    /**
     * Normalize job_position strictly to either "student/graduate" or "teaching/administrative staff".
     */
    public static function normalizeJobPosition(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $clean = mb_strtolower(trim($value));

        // Matches student / graduate (English and Spanish)
        if (
            str_contains($clean, 'student') ||
            str_contains($clean, 'graduate') ||
            str_contains($clean, 'estudiante') ||
            str_contains($clean, 'egresad') ||
            str_contains($clean, 'alumno')
        ) {
            return 'student/graduate';
        }

        // Matches teaching / administrative staff (English and Spanish)
        if (
            str_contains($clean, 'teaching') ||
            str_contains($clean, 'administrative') ||
            str_contains($clean, 'staff') ||
            str_contains($clean, 'docente') ||
            str_contains($clean, 'administrativ') ||
            str_contains($clean, 'profesor') ||
            str_contains($clean, 'docencia')
        ) {
            return 'teaching/administrative staff';
        }

        return null;
    }

    /**
     * Generate institutional email using the required formula:
     * first letter of first name + full last name + first letter of maternal surname + @iestpfvc.edu.pe
     *
     * Example: Juan Carlos Pérez Gómez -> jperezg@iestpfvc.edu.pe
     */
    public function generateInstitutionalEmail(string $firstName, string $paternalSurname, ?string $maternalSurname = null): string
    {
        // First name: extract first letter of the first given name
        $firstGivenName = trim(explode(' ', trim($firstName))[0] ?? '');
        $letterName = mb_substr($firstGivenName, 0, 1);
        $letterName = Str::lower(Str::ascii($letterName));
        $letterName = preg_replace('/[^a-z0-9]/', '', $letterName) ?: 'u';

        // Paternal surname: clean full last name (no accents, no spaces)
        $paternalClean = Str::lower(Str::ascii(trim($paternalSurname)));
        $paternalClean = preg_replace('/[^a-z0-9]/', '', $paternalClean);
        if (empty($paternalClean)) {
            $paternalClean = 'usuario';
        }

        // Maternal surname: extract first letter (if available)
        $letterMaternal = '';
        if (! empty($maternalSurname)) {
            $firstMaternalWord = trim(explode(' ', trim($maternalSurname))[0] ?? '');
            $charMaternal = mb_substr($firstMaternalWord, 0, 1);
            $letterMaternal = Str::lower(Str::ascii($charMaternal));
            $letterMaternal = preg_replace('/[^a-z0-9]/', '', $letterMaternal) ?: '';
        }

        $baseUsername = $letterName.$paternalClean.$letterMaternal;
        $domain = '@iestpfvc.edu.pe';
        $email = $baseUsername.$domain;

        // Ensure uniqueness against database and current import batch
        $candidate = $email;
        $counter = 2;
        while (User::where('email', $candidate)->exists() || in_array($candidate, $this->generatedEmailsInBatch, true)) {
            $candidate = $baseUsername.$counter.$domain;
            $counter++;
        }

        $this->generatedEmailsInBatch[] = $candidate;

        return $candidate;
    }

    /**
     * Generate unique email fallback if a collision occurs.
     */
    protected function generateUniqueEmailFallback(string $email): string
    {
        $prefix = Str::before($email, '@');
        $domain = '@'.Str::after($email, '@');
        $counter = 2;

        do {
            $candidate = $prefix.$counter.$domain;
            $counter++;
        } while (User::where('email', $candidate)->exists() || in_array($candidate, $this->generatedEmailsInBatch, true));

        $this->generatedEmailsInBatch[] = $candidate;

        return $candidate;
    }

    /**
     * Split full names if supplied in a single string.
     */
    protected function parseFullName(string $raw): array
    {
        $raw = trim($raw);

        // Check if format is "Paterno Materno, Nombres"
        if (str_contains($raw, ',')) {
            $parts = explode(',', $raw, 2);
            $surnames = trim($parts[0] ?? '');
            $names = trim($parts[1] ?? '');

            $surParts = explode(' ', $surnames);
            $paternal = $surParts[0] ?? '';
            $maternal = isset($surParts[1]) ? implode(' ', array_slice($surParts, 1)) : '';

            return [$names ?: 'Usuario', $paternal ?: 'Apellido', $maternal ?: null];
        }

        // Format without comma: "Juan Carlos Pérez Gómez"
        $words = array_values(array_filter(explode(' ', $raw)));
        $count = count($words);

        if ($count === 1) {
            return [$words[0], $words[0], null];
        } elseif ($count === 2) {
            return [$words[0], $words[1], null];
        } elseif ($count === 3) {
            return [$words[0], $words[1], $words[2]];
        } else {
            // 4 or more words: first 2 are given names, last 2 are surnames
            $names = implode(' ', array_slice($words, 0, $count - 2));
            $paternal = $words[$count - 2];
            $maternal = $words[$count - 1];

            return [$names, $paternal, $maternal];
        }
    }

    /**
     * Resolve user role based on input or job_position.
     */
    protected function resolveRole(?string $rawRole, string $jobPosition, array $availableRoles): string
    {
        if (! empty($rawRole)) {
            $cleanRole = trim($rawRole);
            foreach ($availableRoles as $roleName) {
                if (mb_strtolower($cleanRole) === $roleName) {
                    return Str::title($cleanRole);
                }
            }
            if (in_array(mb_strtolower($cleanRole), ['estudiante', 'solicitante', 'docente', 'administrativo'], true)) {
                return Str::title($cleanRole);
            }
        }

        // Defaults based on job_position
        if ($jobPosition === 'student/graduate') {
            return in_array('estudiante', $availableRoles, true) ? 'Estudiante' : 'Solicitante';
        }

        return in_array('docente', $availableRoles, true) ? 'Docente' : 'Administrativo';
    }

    /**
     * Clean string helper.
     */
    protected function cleanString(mixed $val): ?string
    {
        if ($val === null) {
            return null;
        }

        $str = trim((string) $val);

        return $str === '' ? null : $str;
    }
}
