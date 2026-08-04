<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramMetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'study_program_id'   => 'required|exists:study_programs,id',
            'icon'               => 'nullable|string|max:100',
            'accent'             => 'nullable|string|max:50',
            'bg_badge'           => 'nullable|string|max:255',
            'tag'                => 'nullable|string|max:255',
            'color_bar'          => 'nullable|string|max:255',
            'glow_class'         => 'nullable|string|max:255',
            'badge_class'        => 'nullable|string|max:255',
            'accent_text'        => 'nullable|string|max:255',
            'bullet_class'       => 'nullable|string|max:255',
            'icon_bg_class'      => 'nullable|string|max:255',
            'border_hover_class' => 'nullable|string|max:255',
            'badge_module_class' => 'nullable|string|max:255',
            'sidebar_icon_class' => 'nullable|string|max:255',
            'cta_bg_class'       => 'nullable|string|max:255',
            'bar_color_class'    => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'study_program_id.required' => 'Debe seleccionar un programa de estudio.',
            'study_program_id.exists'   => 'El programa de estudio seleccionado no es válido.',
        ];
    }
}
