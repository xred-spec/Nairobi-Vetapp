<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTrabajadorRequest extends FormRequest
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
        'nombre'   => ['required', 'string'],
        'telefono' => ['required', 'digits:10', 'unique:users'],
        'email'    => ['required', 'email', 'unique:users'],
        // ← sin validación de horarios
    ];
}

public function messages(): array
{
    return [
        'nombre.required'  => 'El nombre es obligatorio',
        'nombre.string'    => 'El nombre debe de ser caracteres',
        'telefono.required'=> 'El telefono es obligatorio',
        'telefono.digits'  => 'El telefono debe de ser uno valido',
        'telefono.unique'  => 'Telefono no valido',
        'email.unique'     => 'email no valido',
        'email.email'      => 'El email debe de ser uno valido',
        'email.required'   => 'El email es obligatorio',
    ];
    }
}