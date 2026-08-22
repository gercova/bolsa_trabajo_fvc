<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudyProgramValidate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $program = $this->route('program');
        $programId = is_object($program) ? $program->id : $program;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_programs', 'name')->ignore($programId),
            ],
            'logo_path'               => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
            'training_itinerary_path' => 'nullable|file|mimes:pdf|max:20480',
            'description'             => 'required|string',
            'details'                 => 'required|string',
            'icon'                    => 'nullable|string|max:100',
            'accent'                  => 'nullable|string|max:50',
            'bg_badge'                => 'nullable|string|max:255',
            'tag'                     => 'nullable|string|max:255',
            'color_bar'               => 'nullable|string|max:255',
            'glow_class'              => 'nullable|string|max:255',
            'badge_class'             => 'nullable|string|max:255',
            'accent_text'             => 'nullable|string|max:255',
            'bullet_class'            => 'nullable|string|max:255',
            'icon_bg_class'           => 'nullable|string|max:255',
            'border_hover_class'      => 'nullable|string|max:255',
            'badge_module_class'      => 'nullable|string|max:255',
            'sidebar_icon_class'      => 'nullable|string|max:255',
            'cta_bg_class'            => 'nullable|string|max:255',
            'bar_color_class'         => 'nullable|string|max:255',
            'order'                   => 'nullable|integer|min:0|max:255',
            'is_active'               => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                 => 'El nombre del programa es obligatorio.',
            'name.string'                   => 'El nombre debe ser una cadena de texto.',
            'name.max'                      => 'El nombre no debe tener más de 255 caracteres.',
            'name.unique'                   => 'Ya existe un programa de estudio con este nombre.',
            'logo_path.image'               => 'El archivo seleccionado debe ser una imagen válida.',
            'logo_path.mimes'               => 'El logo debe estar en formato PNG, JPEG, JPG, GIF, WEBP o SVG.',
            'logo_path.max'                 => 'La imagen del logo no debe pesar más de 4MB.',
            'training_itinerary_path.file'  => 'El itinerario formativo debe ser un archivo válido.',
            'training_itinerary_path.mimes' => 'El itinerario formativo debe ser un documento en formato PDF.',
            'training_itinerary_path.max'   => 'El documento del itinerario formativo no debe pesar más de 20MB.',
            'description.required'          => 'La descripción es obligatoria.',
            'description.string'            => 'La descripción debe ser una cadena de texto.',
            'details.required'              => 'Los detalles del programa son obligatorios.',
            'details.string'                => 'Los detalles deben ser una cadena de texto.',
            'order.integer'                 => 'El orden debe ser un número entero.',
            'order.min'                     => 'El orden no puede ser menor a 0.',
            'order.max'                     => 'El orden no puede ser mayor a 255.',
        ];
    }
}
