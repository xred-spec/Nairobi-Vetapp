<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaProductoRequest extends FormRequest
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
            'cantidad_usada' => 'required|integer|min:1',
            'consulta_servicio_id' => 'required|exists:consulta_servicios,id',
            'tipo_venta' => 'required|in:Total,Fraccionado',
            'producto_id' => 'required|exists:productos,id',
            'subtotal' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'El atributo :attribute es obligatorio'
        ];
    }
}

