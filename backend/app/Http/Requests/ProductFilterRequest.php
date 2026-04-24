<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
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
            'nombre'=>['nullable','max:50','string'],
            'stock'=>['nullable','numeric'],
            'precio_compra'=>['nullable','numeric'],
            'precio_venta'=>['nullable','numeric'],
            'cantidad'=>['nullable','numeric'],
            'visibilidad'=>['nullable','in:visible,invisible'],
            'medida'=>['nullable','in:gramos,kilos,mililitros,litros,unidad'],
            'marca' => ['nullable','string']

        ];
    }
    public function messages(): array{
        return [
            'nombre.max'=>'El nombre no debe exceder los 50 caracteres',
            'nombre.string'=>'El nombre debe ser una cadena de texto',
            'stock.numeric'=>'El stock debe ser un número',
            'precio_compra.numeric'=>'El precio de compra debe ser un número',
            'precio_venta.numeric'=>'El precio de venta debe ser un número',
            'cantidad.numeric'=>'La cantidad debe ser un número',
            'visibilidad.in'=>'La visibilidad debe ser visible o invisible',
            'medida.in'=>'La medida debe ser gramos, kilos, mililitros, litros o unidad',
            'marca.string' => 'Debe de llenar ese campo'
        ];
    }
}
