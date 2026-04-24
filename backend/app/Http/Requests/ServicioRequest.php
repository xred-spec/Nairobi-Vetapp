<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicioRequest extends FormRequest
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
            'nombre'=>['required','string','max:50'],
            'descripcion'=>['nullable','string','max:1000'],
            'visibilidad'=>['required','in:visible,invisible']
        ];
    }

    public function messages(): array
{
    return [
            'nombre.required'=>'El nombre es requerido',
            'nombre.string'=>'El nombre debe ser una cadena de texto',
            'nombre.max'=>'El nombre no debe exceder los 50 caracteres',
            'nombre.string'=>'La descripcion debe ser una cadena de texto',
            'visibilidad.required'=>'La visibilidad es requerida',
            'visibilidad.in'=>'La visibilidad debe ser visible, invisible o removido'
        ];
}
}
