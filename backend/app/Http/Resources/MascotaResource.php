<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MascotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nombre' => $this->nombre,
            'sexo' => $this->sexo,
            'peso' => $this->peso,
            'descripcion' => $this->descripcion,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'visibilidad' => $this->visibilidad,
        ];
    }
}
