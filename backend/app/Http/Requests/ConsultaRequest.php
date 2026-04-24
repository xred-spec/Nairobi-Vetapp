<?php
 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pre_diagnostico' => 'nullable|string|max:255',
            'descripcion_consulta' => 'nullable|string',
            'indicaciones' => 'nullable|string',
            'cita_id' => 'required|exists:citas,id',
            'total_servicios' => 'nullable|numeric|min:0',
            'total_producto' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',

            'servicios' => 'nullable|array|min:1',
            'servicios.*.servicio_id' => 'nullable|exists:servicios,id',
            'servicios.*.detalles_servicio' => 'nullable|string',
            'servicios.*.precio_servicio' => 'nullable|numeric|min:0',
            'servicios.*.precio_producto' => 'nullable|numeric|min:0',
            'servicios.*.total' => 'nullable|numeric|min:0',
            'servicios.*.productos' => 'nullable|array',
            'servicios.*.productos.*.producto_id' => 'nullable|exists:productos,id',
            'servicios.*.productos.*.cantidad' => 'nullable|min:1',
            'servicios.*.productos.*.tipo_venta' => 'in:Total,Fraccionado',
            'servicios.*.productos.*.subtotal' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'min' => 'El :attribute debe ser al menos :min.',
            'exists' => 'El :attribute no existe.',
            'integer' => 'Cantidad debe ser entero.',
            'in' => 'Tipo venta inválido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'descripcion_consulta' => 'descripción consulta',
            'cita_id' => 'cita',
            'servicios.*.servicio_id' => 'servicio',
            'servicios.*.detalles_servicio' => 'detalles servicio',
            'servicios.*.precio_servicio' => 'precio servicio',
            'servicios.*.productos.*.producto_id' => 'producto',
            'servicios.*.productos.*.cantidad' => 'cantidad',
            'servicios.*.productos.*.tipo_venta' => 'tipo venta',
            'servicios.*.productos.*.subtotal' => 'subtotal',
        ];
    }
}

