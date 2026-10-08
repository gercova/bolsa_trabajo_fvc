<?php

namespace App\Http\Requests;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $userId = (int) $this->input('user_id');
            $courseId = (int) $this->input('course_id');
            $issueDate = $this->input('issue_date');
            $eventName = $this->input('event_name');
            $startDate = $this->input('start_date');

            $certParam = $this->route('certificate');
            $certId = is_object($certParam) ? $certParam->id : ($this->certificate ?? null);

            if ($userId && $courseId && $issueDate) {
                if (empty($eventName)) {
                    $eventName = Course::where('id', $courseId)->value('event_name');
                }

                $duplicate = Certificate::findDuplicate(
                    userId: $userId,
                    courseId: $courseId,
                    eventName: $eventName,
                    issueDate: $issueDate,
                    startDate: $startDate,
                    ignoreId: $certId ? (int) $certId : null
                );

                if ($duplicate) {
                    $courseName = $duplicate->course?->name ?? 'mismo curso';
                    $validator->errors()->add(
                        'user_id',
                        "El estudiante ya cuenta con un certificado registrado para este evento/curso ({$courseName}) en la misma fecha ({$issueDate}). Código registrado: {$duplicate->certificate_code}."
                    );
                }
            }
        });
    }

    public function rules(): array
    {
        $certId = $this->route('certificate')?->id ?? $this->certificate;

        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'course_id' => [
                'required',
                'exists:courses,id',
            ],
            'certificate_type' => [
                'nullable',
                'string',
                Rule::in(['capacitacion', 'modular']),
            ],
            'participation_type' => [
                'nullable',
                'string',
                'max:100',
            ],
            'event_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'study_program_id' => [
                'nullable',
                'exists:study_programs,id',
            ],
            'institution_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'certificate_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('certificates', 'certificate_code')->ignore($certId),
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'duration' => [
                'nullable',
                'string',
                'max:100',
            ],
            'modality' => [
                'required',
                'string',
                Rule::in(['Presencial', 'Semipresencial', 'Virtual']),
            ],
            'issue_date' => [
                'required',
                'date',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Debe seleccionar un estudiante/usuario.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'course_id.required' => 'Debe seleccionar un curso.',
            'course_id.exists' => 'El curso seleccionado no existe.',
            'certificate_code.required' => 'El código del certificado es obligatorio.',
            'certificate_code.unique' => 'Este código de certificado ya está registrado.',
            'issue_date.required' => 'La fecha de emisión es obligatoria.',
            'issue_date.date' => 'La fecha de emisión no es válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'estudiante / usuario',
            'course_id' => 'curso',
            'certificate_code' => 'código de certificado',
            'description' => 'descripción',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de fin',
            'duration' => 'duración / horas académicas',
            'issue_date' => 'fecha de emisión',
            'is_active' => 'estado activo',
        ];
    }
}
