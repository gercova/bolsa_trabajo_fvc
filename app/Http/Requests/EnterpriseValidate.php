<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'ruc'                       => 'nullable|string|max:11',
            'company_name'              => 'required|string|max:150',
            'trade_name'                => 'nullable|string|max:150',
            'legal_representative_dni'  => 'nullable|string|max:10',
            'legal_representative'      => 'nullable|string|max:100',
            'address'                   => 'nullable|string|max:100',
            'city'                      => 'nullable|string|max:150',
            'business_sector'           => 'nullable|string|max:255',
            'phrase'                    => 'nullable|string|max:255',
            'description'               => 'nullable|string',
            'vision'                    => 'nullable|string',
            'mission'                   => 'nullable|string',
            'phone_number_1'            => 'nullable|string|max:20',
            'phone_number_2'            => 'nullable|string|max:20',
            'email'                     => 'nullable|email|max:255|unique:enterprise,id,'.$this->id,
            'facebook_link'             => 'nullable|url|max:255',
            'linkedin_link'             => 'nullable|url|max:255',
            'twitter_link'              => 'nullable|url|max:255',
            'instagram_link'            => 'nullable|url|max:255',
            'whatsapp_link'             => 'nullable|url|max:255',
            'logo_path'                 => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'favicon_path'              => 'nullable|image|mimes:ico,png,jpg,svg|max:1024',
        ];
    }

    public function messages() {
        return [
            'ruc.string' => 'El RUC debe ser un texto válido.',
            'ruc.max' => 'El RUC no debe exceder los 11 caracteres.',
            'company_name.required' => 'El nombre de la empresa es obligatorio.',
            'company_name.string' => 'El nombre de la empresa debe ser un texto.',
            'company_name.max' => 'El nombre de la empresa no debe exceder los 150 caracteres.',
            'trade_name.string' => 'El nombre comercial debe ser un texto.',
            'trade_name.max' => 'El nombre comercial no debe exceder los 150 caracteres.',
            'legal_representative_dni.string' => 'El DNI del representante legal debe ser un texto.',
            'legal_representative_dni.max' => 'El DNI no debe exceder los 10 caracteres.',
            'legal_representative.string' => 'El nombre del representante legal debe ser un texto.',
            'legal_representative.max' => 'El nombre del representante legal no debe exceder los 100 caracteres.',
            'address.string' => 'La dirección debe ser un texto.',
            'address.max' => 'La dirección no debe exceder los 100 caracteres.',
            'city.string' => 'La ciudad debe ser un texto.',
            'city.max' => 'La ciudad no debe exceder los 150 caracteres.',
            'business_sector.string' => 'El sector empresarial debe ser un texto.',
            'business_sector.max' => 'El sector empresarial no debe exceder los 255 caracteres.',
            'phrase.string' => 'La frase debe ser un texto.',
            'phrase.max' => 'La frase no debe exceder los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto.',
            'vision.string' => 'La visión debe ser un texto.',
            'mission.string' => 'La misión debe ser un texto.',
            'phone_number_1.string' => 'El teléfono principal debe ser un texto.',
            'phone_number_1.max' => 'El teléfono principal no debe exceder los 20 caracteres.',
            'phone_number_2.string' => 'El teléfono secundario debe ser un texto.',
            'phone_number_2.max' => 'El teléfono secundario no debe exceder los 20 caracteres.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no debe exceder los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado en otra empresa.',
            'facebook_link.url' => 'El enlace de Facebook debe ser una URL válida.',
            'facebook_link.max' => 'El enlace de Facebook no debe exceder los 255 caracteres.',
            'linkedin_link.url' => 'El enlace de LinkedIn debe ser una URL válida.',
            'linkedin_link.max' => 'El enlace de LinkedIn no debe exceder los 255 caracteres.',
            'twitter_link.url' => 'El enlace de Twitter/X debe ser una URL válida.',
            'twitter_link.max' => 'El enlace de Twitter/X no debe exceder los 255 caracteres.',
            'instagram_link.url' => 'El enlace de Instagram debe ser una URL válida.',
            'instagram_link.max' => 'El enlace de Instagram no debe exceder los 255 caracteres.',
            'whatsapp_link.url' => 'El enlace de WhatsApp debe ser una URL válida.',
            'whatsapp_link.max' => 'El enlace de WhatsApp no debe exceder los 255 caracteres.',
            'logo_path.image' => 'El logo debe ser una imagen válida.',
            'logo_path.mimes' => 'El logo debe tener un formato: jpeg, png, jpg o svg.',
            'logo_path.max' => 'El logo no debe superar los 2 MB.',
            'favicon_path.image' => 'El favicon debe ser una imagen válida.',
            'favicon_path.mimes' => 'El favicon debe tener un formato: ico, png, jpg o svg.',
            'favicon_path.max' => 'El favicon no debe superar los 1 MB.',
        ];
    }

    protected function prepareForValidation() {
        // Recorremos todos los inputs
        $data = $this->all();

        foreach ($data as $key => $value) {
            // Si el valor es un string y está vacío (o solo espacios), lo convertimos a NULL
            // Nota: Esto no afecta a los archivos (logo, favicon) porque no son strings.
            if (is_string($value) && trim($value) === '') {
                $data[$key] = null;
            }
        }

        // Reemplazamos la data original con la data limpia
        $this->merge($data);
    }
}
