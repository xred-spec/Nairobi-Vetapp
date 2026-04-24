<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
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
            'stock'=>['required','numeric','min:0'],
            'precio_compra'=>['required','numeric','min:0'],
            'precio_venta'=>['required','numeric','min:0'],
            'cantidad'=>['required','numeric','min:0'],
            'visibilidad'=>['required','in:visible,invisible'],
            'marca'=>['required','string'],
            'medida'=>['required','in:gramos,kilos,mililitros,litros,unidad']
        ];
    }

    public function messages(): array
    {
        return [
            'marca.required'=>'El nombre de la marca es requerido',
            'nombre.required'=>'El nombre es requerido',
            'nombre.string'=>'El nombre debe ser una cadena de texto',
            'nombre.max'=>'El nombre no debe exceder los 50 caracteres',
            'stock.required'=>'El stock es requerido',
            'stock.numeric'=>'El stock debe ser un número',
            'stock.min'=>'El stock no puede ser negativo',
            'precio_compra.required'=>'El precio de compra es requerido',
            'precio_compra.numeric'=>'El precio de compra debe ser un número',
            'precio_compra.min'=>'El precio de compra no puede ser negativo',
            'precio_venta.required'=>'El precio de venta es requerido',
            'precio_venta.numeric'=>'El precio de venta debe ser un número',
            'precio_venta.min'=>'El precio de venta no puede ser negativo',
            'cantidad.required'=>'La cantidad es requerida',
            'cantidad.numeric'=>'La cantidad debe ser un número',
            'cantidad.min'=>'La cantidad no puede ser negativa',
            'visibilidad.required'=>'La visibilidad es requerida',
            'visibilidad.in'=>'La visibilidad debe ser visible, invisible o removido',
            'medida.required'=>'La medida es requerida',
            'medida.in'=>'La medida debe ser gramos, kilos, mililitros, litros o unidades'
        ];
    }
}
