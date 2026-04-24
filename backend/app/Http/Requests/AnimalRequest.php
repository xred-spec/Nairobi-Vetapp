<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AnimalRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('animales')->where(function ($q) {
                    $q->where('visibilidad', '!=', 'removido');
                })->ignore($this->route('id'))
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.animales' => 'Exte animal ya esta registrado',
            'nombre.unique' => 'Este animal ya esta registrado',
            'nombre.string' => 'El nombre debe ser una cadena de texto',
            'nombre.max' => 'El nombre no debe exceder los 50 caracteres',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($errors->has('nombre') && str_contains($errors->first('nombre'), 'registrado')) {
            throw new HttpResponseException(
                response()->json([
                    'data' => null,
                    'message' => $errors->first(),
                    'error' => null
                ], 409) 
            );
        }

        throw new HttpResponseException(
            response()->json([
                'data' => null,
                'message' => $errors->first(),
                'error' => null
            ], 422)
        );
    }
}
