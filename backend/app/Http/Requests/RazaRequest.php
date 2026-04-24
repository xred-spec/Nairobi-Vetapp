<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RazaRequest extends FormRequest
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
            'nombre' => 'required|string',
            'animal_id' => 'required|Integer|exists:animales,id',
            'filtro' => 'nullable',
            'visibilidad' => 'required|in:visible,invisible'
        ];
    }

    public function messages(){
        return [
            'El atributo :attribute es obligatorio'
        ];
    }
}
