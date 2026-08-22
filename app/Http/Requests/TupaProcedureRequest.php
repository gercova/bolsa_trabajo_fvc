<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TupaProcedureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tupa_id'       => 'required|exists:tupa,id',
            'category_id'   => 'required|exists:tupa_categories,id',
            'code'          => 'required|string|max:50',
            'name'          => 'required|string|max:255',
            'description'   => 'required|string',
            'requirements'  => 'required',
            'cost'          => 'required|string|max:50',
            'uit_percent'   => 'nullable|string|max:50',
            'qualification' => 'required|string|max:100',
            'duration'      => 'required|string|max:100',
            'office'        => 'required|string|max:150',
            'is_active'     => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'tupa_id.required'       => 'Debe seleccionar un documento TUPA.',
            'tupa_id.exists'         => 'El documento TUPA seleccionado no existe en el sistema.',
            'category_id.required'   => 'Debe seleccionar una categoría TUPA.',
            'category_id.exists'     => 'La categoría seleccionada no existe en el sistema.',
            'code.required'          => 'El código del procedimiento es obligatorio (ej. P-01).',
            'code.string'            => 'El código debe ser un texto válido.',
            'code.max'               => 'El código no puede exceder los 50 caracteres.',
            'name.required'          => 'El nombre del procedimiento es obligatorio.',
            'name.string'            => 'El nombre debe ser un texto válido.',
            'name.max'               => 'El nombre no puede exceder los 255 caracteres.',
            'description.required'   => 'La descripción del procedimiento es obligatoria.',
            'requirements.required'  => 'Debe ingresar al menos un requisito para el procedimiento.',
            'cost.required'          => 'El costo del derecho de pago es obligatorio.',
            'cost.max'               => 'El costo no puede exceder los 50 caracteres.',
            'qualification.required' => 'La calificación del procedimiento es obligatoria.',
            'qualification.max'      => 'La calificación no puede exceder los 100 caracteres.',
            'duration.required'      => 'El plazo de atención es obligatorio.',
            'duration.max'          => 'El plazo de atención no puede exceder los 100 caracteres.',
            'office.required'        => 'La oficina o área que atiende es obligatoria.',
            'office.max'             => 'La oficina no puede exceder los 150 caracteres.',
        ];
    }
}
