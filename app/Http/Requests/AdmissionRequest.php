<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period'                 => 'required|string|max:20',
            'total_vacancies'        => 'required|integer|min:0',
            'activity'               => 'required|string|max:255',
            'exam_date'              => 'required|date',
            'inscription_start_date' => 'required|date',
            'inscription_end_date'   => 'required|date|after_or_equal:inscription_start_date',
            'url_pdf'                => 'nullable|file|mimes:pdf|max:10240',
            'results_url_pdf'        => 'nullable|file|mimes:pdf|max:10240',
            'price'                  => 'required|numeric|min:0',
            'monthly_fee'            => 'nullable|numeric|min:0',
            'tuition_fee'            => 'nullable|numeric|min:0',
            'duration'               => 'nullable|string|max:100',
            'indications'            => 'nullable|string',
            'type'                   => 'required|string|in:ordinario,extraordinario',
            'process'                => 'required|string|in:admisión,cepre,matrícula',
            'area_id'                => 'nullable|integer|exists:area,id',
            'is_active'              => 'boolean',
            'programs'               => 'nullable|array',
            'programs.*.program_id'  => 'required_with:programs|exists:study_programs,id',
            'programs.*.vacancies'   => 'required_with:programs|integer|min:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'period'                 => 'período académico',
            'total_vacancies'        => 'total de vacantes',
            'activity'               => 'nombre de la actividad',
            'exam_date'              => 'fecha del examen',
            'inscription_start_date' => 'fecha de inicio de inscripción',
            'inscription_end_date'   => 'fecha de fin de inscripción',
            'url_pdf'                => 'documento de bases (PDF)',
            'results_url_pdf'        => 'documento de publicación de resultados (PDF)',
            'price'                  => 'costo de inscripción',
            'monthly_fee'            => 'costo mensual / cuota',
            'tuition_fee'            => 'costo de matrícula',
            'duration'               => 'duración',
            'indications'            => 'indicaciones para el postulante',
            'type'                   => 'tipo de examen',
            'process'                => 'proceso',
            'area_id'                => 'área académica',
        ];
    }

    public function messages(): array
    {
        return [
            'period.required'                 => 'El campo período es obligatorio.',
            'period.string'                   => 'El período debe ser un texto válido.',
            'period.max'                      => 'El período no puede exceder los 20 caracteres.',
            'total_vacancies.required'        => 'El campo total de vacantes es obligatorio.',
            'total_vacancies.integer'         => 'El total de vacantes debe ser un número entero.',
            'total_vacancies.min'             => 'El total de vacantes no puede ser menor a 0.',
            'activity.required'               => 'El nombre de la actividad es obligatorio.',
            'activity.string'                 => 'La actividad debe ser un texto válido.',
            'activity.max'                    => 'La actividad no puede exceder los 255 caracteres.',
            'exam_date.required'              => 'La fecha del examen es obligatoria.',
            'exam_date.date'                  => 'La fecha del examen debe ser una fecha válida.',
            'inscription_start_date.required' => 'La fecha de inicio de inscripción es obligatoria.',
            'inscription_start_date.date'      => 'La fecha de inicio de inscripción debe ser una fecha válida.',
            'inscription_end_date.required'   => 'La fecha de fin de inscripción es obligatoria.',
            'inscription_end_date.date'        => 'La fecha de fin de inscripción debe ser una fecha válida.',
            'inscription_end_date.after_or_equal' => 'La fecha de fin de inscripción debe ser igual o posterior a la fecha de inicio de inscripción.',
            'url_pdf.file'                    => 'El archivo de bases debe ser un documento válido.',
            'url_pdf.mimes'                   => 'El archivo de bases debe ser un documento en formato PDF.',
            'url_pdf.max'                     => 'El archivo de bases no puede superar los 10 MB.',
            'results_url_pdf.file'            => 'El archivo de resultados debe ser un documento válido.',
            'results_url_pdf.mimes'           => 'El archivo de resultados debe ser un documento en formato PDF.',
            'results_url_pdf.max'             => 'El archivo de resultados no puede superar los 10 MB.',
            'price.required'                  => 'El precio de inscripción es obligatorio.',
            'price.numeric'                   => 'El precio de inscripción debe ser un valor numérico.',
            'price.min'                       => 'El precio de inscripción no puede ser menor a 0.',
            'monthly_fee.numeric'             => 'El costo mensual debe ser un valor numérico.',
            'monthly_fee.min'                 => 'El costo mensual no puede ser menor a 0.',
            'tuition_fee.numeric'             => 'El costo de matrícula debe ser un valor numérico.',
            'tuition_fee.min'                 => 'El costo de matrícula no puede ser menor a 0.',
            'duration.string'                 => 'La duración debe ser un texto válido.',
            'duration.max'                    => 'La duración no puede exceder los 100 caracteres.',
            'indications.string'              => 'Las indicaciones deben ser un texto válido.',
            'type.required'                   => 'El tipo de examen es obligatorio.',
            'type.in'                         => 'El tipo de examen seleccionado no es válido.',
            'process.required'                => 'El proceso es obligatorio.',
            'process.in'                      => 'El proceso seleccionado no es válido.',
            'area_id.integer'                 => 'El área seleccionada debe ser válida.',
            'area_id.exists'                  => 'El área seleccionada no existe en el sistema.',
            'programs.array'                  => 'El formato de los programas seleccionados es inválido.',
            'programs.*.program_id.exists'    => 'El programa seleccionado no existe.',
            'programs.*.vacancies.integer'    => 'Las vacantes por programa deben ser un número entero.',
            'programs.*.vacancies.min'        => 'Las vacantes por programa no pueden ser menores a 0.',
        ];
    }
}
