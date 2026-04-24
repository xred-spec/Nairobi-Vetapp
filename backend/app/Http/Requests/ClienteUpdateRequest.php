<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteUpdateRequest extends FormRequest
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
          'municipio'=>['sometimes','string','max:60'],
          'colonia'=>['sometimes','string','max:60'],
          'codigo_postal'=>['sometimes','string','max:5'],
          'calle'=>['sometimes','string','max:60'],
          'numero_exterior'=>['sometimes','string','max:20']
        ];
    }

     public function messages():array{
       return [
        'municipio.max'=>'El nombre del municipio excede los 60 caracteres',
        'colonia.max'=>'El nombre del municipio excede los 60 caracteres',
        'codigo_postal.max'=>'El nombre del municipio excede los 5 caracteres',
        'calle.max'=>'El nombre del municipio excede los 60 caracteres',
        'numero_exterior.max'=>'El nombre del municipio excede los 20 caracteres'
        ];
    }
}
