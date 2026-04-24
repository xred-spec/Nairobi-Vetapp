<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'estado'=>$this->estado?$this->estado : 'agendada',
            'fecha'=>$this->fecha->format('Y-m-d'),
            'tipo'=>$this->tipo,
            'descripcion'=>$this->descripcion,
            'mascota'=>$this->whenLoaded('mascota'),
            'horario_trabajador'=>$this->whenLoaded('horarioTrabajador'),
            'consulta'=>$this->whenLoaded('consulta'),
            'cliente'=>$this->whenLoaded('cliente')
        ];
    }
}
