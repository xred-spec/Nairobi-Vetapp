<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultaResource extends JsonResource
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
            'pre_diagnostico' => $this->pre_diagnostico,
            'descripcion_consulta' => $this->descripcion_consulta,
            'indicaciones' => $this->indicaciones,
            'cita' => $this->whenLoaded('cita'),
            'total_servicios' => $this->total_servicios,
            'total_producto' => $this->total_producto,
            'total' => $this->total,

            // la parte fea
            'servicios' => $this->whenLoaded('consultaServicios', function () {
                return $this->consultaServicios->map(function ($cs) {
                    return [
                        'id' => $cs->id,
                        'servicio' => $cs->servicio,
                        'total' => $cs->total,

                        // mapeado de los productos
                        'productos' => $cs->productos->map(function ($p) {
                            return [
                                'id'=> $p->id,
                                'nombre' => $p->nombre,
                                'cantidad_usada' => $p->pivot->cantidad_usada,
                            ];
                        })
                    ];
                });
            })
        ];
    }
}
