<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_period'  => 'required|string|max:30',
            'enrollment_type'  => 'required|string|in:ordinaria,extraordinaria',
            'enrollment_fee'   => 'required|numeric|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'observations'     => 'nullable|string|max:1000',
            'is_active'        => 'boolean',
            'details'                       => 'nullable|array',
            'details.*.program_id'          => 'required_with:details|exists:study_programs,id',
            'details.*.available_slots'     => 'required_with:details|integer|min:0',
            'details.*.observations'        => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'academic_period.required'      => 'El período académico es obligatorio.',
            'academic_period.max'           => 'El período académico no puede exceder los 30 caracteres.',
            'enrollment_type.required'      => 'El tipo de matrícula es obligatorio.',
            'enrollment_type.in'            => 'El tipo de matrícula no es válido. Seleccione ordinaria o extraordinaria.',
            'enrollment_fee.required'       => 'El costo del derecho de matrícula es obligatorio.',
            'enrollment_fee.numeric'        => 'El costo de matrícula debe ser un valor numérico.',
            'enrollment_fee.min'            => 'El costo de matrícula no puede ser negativo.',
            'start_date.required'           => 'La fecha de inicio es obligatoria.',
            'start_date.date'               => 'La fecha de inicio debe ser una fecha válida.',
            'end_date.required'             => 'La fecha de fin es obligatoria.',
            'end_date.date'                 => 'La fecha de fin debe ser una fecha válida.',
            'end_date.after_or_equal'       => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'observations.max'              => 'Las observaciones no pueden exceder los 1000 caracteres.',
            'details.array'                 => 'El formato de los detalles de programas no es válido.',
            'details.*.program_id.exists'   => 'El programa seleccionado no existe.',
            'details.*.available_slots.integer' => 'Los cupos disponibles deben ser un número entero.',
            'details.*.available_slots.min'     => 'Los cupos disponibles no pueden ser negativos.',
        ];
    }
}
