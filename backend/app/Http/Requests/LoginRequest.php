<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password'=>['required','min:8'],
            'email'=>['required_without:telefono','email'],
            'telefono'=>['required_without:email','digits:10']
        ];
    }

    public function messages(): array{
        return [
            'password.required'=>'La contraseña es obligatoria',
            'password.min'=>'la contraseña debe de tener al menos 8 caracteres',
            'email.required_without'=>'el correo o el telefono son necesario',
            'email.email'=>'el correo debe de ser uno valido',
            'telefono.required_without'=>'el correo o el telefono son necesario',
            'telefono.digits'=>'el numero debe de ser valdio'
        ];
    }
}
