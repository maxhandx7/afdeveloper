<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nombre', 'email' => 'correo', 'phone' => 'teléfono', 'message' => 'mensaje'];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'Escribe un correo válido.',
            'message.min' => 'Cuéntame un poco más (mínimo 10 caracteres).',
            'max' => 'El campo :attribute es demasiado largo.',
        ];
    }
}
