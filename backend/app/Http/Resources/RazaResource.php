<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RazaResource extends JsonResource
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
            'animal_id' => $this->animal_id,
            'animal' => $this->animal?->nombre,
            'visibilidad' => $this->visibilidad
        ];
    }

    public function with($request): array{
        if($this->resource instanceof LengthAwarePaginator){
            return [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
            ];
        }

        return [];
    }
}
