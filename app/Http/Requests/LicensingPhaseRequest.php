<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LicensingPhaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phase_number'        => ['required', 'integer', 'min:1', 'max:20'],
            'title'               => ['required', 'string', 'max:255'],
            'subtitle'            => ['nullable', 'string', 'max:500'],
            'code'                => ['nullable', 'string', 'max:50'],
            'stage_tag'           => ['nullable', 'string', 'max:20'],
            'status'              => ['required', 'in:pending,in_progress,completed,observed'],
            'is_current'          => ['nullable', 'boolean'],
            'progress_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'description'         => ['nullable', 'string'],
            'resolution_number'   => ['nullable', 'string', 'max:255'],
            'legal_basis'         => ['nullable', 'string', 'max:255'],
            'start_date'          => ['nullable', 'date'],
            'end_date'            => ['nullable', 'date', 'after_or_equal:start_date'],
            'estimated_date'      => ['nullable', 'string', 'max:255'],
            'file_path'           => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:20480'],
            'external_link'       => ['nullable', 'url', 'max:500'],
            'order'               => ['nullable', 'integer', 'min:1'],
            'is_active'           => ['nullable', 'boolean'],
            'milestones_json'     => ['nullable', 'string'],
        ];
    }

    /**
     * Custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'phase_number'        => 'número de fase',
            'title'               => 'título de la fase',
            'subtitle'            => 'subtítulo',
            'code'                => 'código',
            'status'              => 'estado del proceso',
            'is_current'          => 'etapa actual (P)',
            'progress_percentage' => 'porcentaje de avance',
            'description'         => 'descripción detallada',
            'resolution_number'   => 'número de resolución',
            'legal_basis'         => 'base legal',
            'start_date'          => 'fecha de inicio',
            'end_date'            => 'fecha de culminación',
            'estimated_date'      => 'fecha o periodo estimado',
            'file_path'           => 'archivo adjunto',
            'external_link'       => 'enlace externo',
            'order'               => 'orden de visualización',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'phase_number.required' => 'El número de fase es obligatorio.',
            'title.required'        => 'El título de la fase o proceso es obligatorio.',
            'status.required'       => 'Debe seleccionar un estado para la fase.',
            'status.in'             => 'El estado seleccionado no es válido.',
            'file_path.max'         => 'El archivo no debe exceder los 20MB.',
            'file_path.mimes'       => 'El archivo debe ser de formato PDF, DOC, DOCX, ZIP o RAR.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ];
    }
}
