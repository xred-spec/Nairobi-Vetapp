<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncHorariosRequest extends FormRequest
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
            'horarios'   => ['present','array',],
            'horarios.*' => ['present','integer','exists:horarios,id'],
        ];
    }

    public function messages(): array
    {
        return [
            //los horarios ya no horarean
            //'horarios.required'   => 'Debe enviar al menos un horario',
            'horarios.array'      => 'Los horarios deben ser un arreglo',
            //'horarios.min'        => 'El trabajador debe tener al menos un horario',
            'horarios.*.exists'   => 'Uno o más horarios no existen',
        ];
    }
}


