<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'stock' => $this->stock,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'cantidad' => $this->cantidad,
            'visibilidad' => $this->visibilidad,
            'medida' => $this->medida,
            'marca' => $this->marca,
        ];
    }
}
