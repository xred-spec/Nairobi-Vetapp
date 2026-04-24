<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class MascotaRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'sexo' => 'required|in:macho,hembra',
            'peso' => 'nullable|numeric',
            'descripcion' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'raza_id' => 'required|exists:razas,id',
            'cliente_id' => 'sometimes|exists:clientes,id',
            'visibilidad' => 'nullable|in:visible,invisible'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser una cadena de texto',
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
