<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array  {
        return [
            'company'   => 'sometimes|required|string|max:255',
            'image_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            // company
            'company.required' => 'El campo company es obligatorio.',
            'company.string'   => 'El campo company debe ser una cadena de texto.',
            'company.max'      => 'El campo company no debe tener más de 255 caracteres.',
            
            // image_url
            'image_url.url'    => 'El campo image_url debe ser una URL válida (ej: https://...).',
            'image_url.max'    => 'El campo image_url no debe tener más de 255 caracteres.',
            
            // is_active
            'is_active.boolean'=> 'El campo is_active debe ser un valor booleano (true/false, 1/0).',
        ];
    }
}
