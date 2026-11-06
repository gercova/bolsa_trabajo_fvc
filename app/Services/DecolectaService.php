<?php

namespace App\Services;

use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DecolectaService
{
    /**
     * Consulta los datos de un DNI a través de la API de Decolecta (RENIEC)
     * e integra la búsqueda con los registros institucionales de personal y estudiantes.
     *
     * @return array{
     *     success: bool,
     *     message?: string,
     *     data?: array{
     *         dni: string,
     *         first_name: string,
     *         first_last_name: string,
     *         second_last_name: string,
     *         full_name: string,
     *         names: string,
     *         institutional_records: array{
     *             existing_user: ?array,
     *             student_record: ?array,
     *         }
     *     }
     * }
     */
    public function searchByDni(string $dni): array
    {
        $dni = trim($dni);

        if (! preg_match('/^\d{8}$/', $dni)) {
            return [
                'success' => false,
                'message' => 'El DNI debe tener exactamente 8 dígitos numéricos.',
            ];
        }

        $token = config('services.decolecta.token');
        if (empty($token)) {
            return [
                'success' => false,
                'message' => 'El token de la API de Decolecta no está configurado en el servidor.',
            ];
        }

        $baseUrl = rtrim(config('services.decolecta.base_url', 'https://api.decolecta.com/v1'), '/');

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(10)
                ->get("{$baseUrl}/reniec/dni", [
                    'numero' => $dni,
                ]);

            if ($response->status() === 404) {
                return [
                    'success' => false,
                    'message' => 'No se encontraron datos en RENIEC para el DNI ingresado.',
                ];
            }

            if ($response->status() === 401) {
                return [
                    'success' => false,
                    'message' => 'Token de autenticación de Decolecta inválido o expirado.',
                ];
            }

            if (! $response->successful()) {
                $errorMsg = $response->json('message') ?? 'Error al consultar el servicio de Decolecta (HTTP '.$response->status().').';

                return [
                    'success' => false,
                    'message' => $errorMsg,
                ];
            }

            $payload = $response->json();
            $firstName = trim((string) ($payload['first_name'] ?? ''));
            $firstLastName = trim((string) ($payload['first_last_name'] ?? ''));
            $secondLastName = trim((string) ($payload['second_last_name'] ?? ''));
            $fullName = trim((string) ($payload['full_name'] ?? ''));

            $naturalName = trim("{$firstName} {$firstLastName} {$secondLastName}");
            $titleFormattedName = $naturalName !== ''
                ? mb_convert_case($naturalName, MB_CASE_TITLE, 'UTF-8')
                : $fullName;

            // Búsqueda de registros institucionales:
            // 1. Verificar si ya es usuario registrado (administrativo, docente, estudiante)
            $existingUser = User::where('dni', $dni)->first();
            $existingUserData = null;
            if ($existingUser) {
                $existingUserData = [
                    'id' => $existingUser->id,
                    'names' => $existingUser->names,
                    'email' => $existingUser->email,
                    'role' => $existingUser->role,
                    'job_position' => $existingUser->job_position,
                    'is_active' => (bool) $existingUser->is_active,
                ];
            }

            // 2. Verificar si está registrado en el padrón de estudiantes de la institución
            $studentRecord = StudentRecord::where('document', $dni)->first();
            $studentRecordData = null;
            if ($studentRecord) {
                $studentRecordData = [
                    'id' => $studentRecord->id,
                    'names' => trim("{$studentRecord->names} {$studentRecord->last_name_father} {$studentRecord->last_name_mother}"),
                    'study_program' => $studentRecord->study_program,
                    'academic_period' => $studentRecord->academic_period,
                    'email' => $studentRecord->email,
                    'phone' => $studentRecord->phone,
                    'enrollment_status' => $studentRecord->enrollment_status,
                    'cycle' => $studentRecord->cycle,
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'dni' => $dni,
                    'first_name' => $firstName,
                    'first_last_name' => $firstLastName,
                    'second_last_name' => $secondLastName,
                    'full_name' => $fullName,
                    'names' => $titleFormattedName,
                    'institutional_records' => [
                        'existing_user' => $existingUserData,
                        'student_record' => $studentRecordData,
                    ],
                ],
            ];
        } catch (\Throwable $e) {
            Log::error('Error al consultar Decolecta API: '.$e->getMessage(), [
                'dni' => $dni,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => 'Ocurrió un error de comunicación con el servicio de consulta de identidad: '.$e->getMessage(),
            ];
        }
    }
}

