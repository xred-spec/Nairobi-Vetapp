<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TrabajadorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'usuario'    => $this->whenLoaded('user', fn() => [
                'id'      => $this->user->id,
                'nombre'  => $this->user->nombre,
                'telefono'=> $this->user->telefono,
                'email'   => $this->user->email,
                'estado'  => $this->user->estado,
            ]),
            'horarios' => $this->whenLoaded('horarios', fn() =>
                $this->horarios->map(fn($h) => [
                    'id'          => $h->id,
                    'inicio_hora' => Carbon::parse($h->inicio_hora)->format('H:i'),
                    'final_hora'  => Carbon::parse($h->final_hora)->format('H:i'),
                    'asignado'    => $h->pivot->asignado,
                ])
            ),
        ];
    }
}


