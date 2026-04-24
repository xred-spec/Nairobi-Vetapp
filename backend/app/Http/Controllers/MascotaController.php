<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mascota;
use App\Traits\ApiResponse;
use App\Http\Requests\MascotaRequest;
use App\Services\MascotaService;

class MascotaController extends Controller
{
    use ApiResponse;

    protected $service;

    public function __construct(MascotaService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Mascota::class);
        
        $mascotas = $this->service->index($request);

        return $this->respuesta($mascotas, 'Mascotas obtenidas correctamente', 200);
    }

    public function search(Request $request)
    {
        $this->authorize('viewAny', Mascota::class);

        $mascotas = $this->service->search($request);

        return $this->respuesta($mascotas, 'Mascotas encontradas', 200);
    }

    public function store(MascotaRequest $request)
    {
        $this->authorize('create', Mascota::class);
        $mascota = $this->service->store(
            $request->validated(),
            $request->user() 
        );

        return $this->respuesta(
            $mascota->load(['raza.animal', 'cliente.user']),
            'Mascota creada correctamente',
            201
        );
    }

    public function show(string $id)
    {
        $mascota = Mascota::findOrFail($id);

        $this->authorize('view', $mascota);

        return $this->respuesta(
            $mascota->load(['raza.animal', 'cliente.user'])
        );
    }

    public function update(MascotaRequest $request, string $id)
    {
        $mascota = Mascota::findOrFail($id);

        $this->authorize('update', $mascota);

        $mascota = $this->service->update(
            $mascota,
            $request->validated(),
            $request->user()
        );

        return $this->respuesta(
            $mascota->load(['raza.animal', 'cliente.user']),
            'Mascota actualizada correctamente',
            200
        );
    }

    public function cambiarEstado(string $id)
    {
        $mascota = Mascota::findOrFail($id);

        $this->authorize('switchState', $mascota);

        $mascota->visibilidad = $mascota->visibilidad === 'visible' ? 'invisible' : 'visible';
        $mascota->save();

        return $this->respuesta(
            $mascota->load(['raza.animal', 'cliente.user']),
            'Estado actualizado'
        );
    }

    public function destroy(string $id)
    {
        $mascota = Mascota::findOrFail($id);

        $this->authorize('delete', $mascota);

        $this->service->delete($mascota);

        return $this->respuesta(null, 'Mascota eliminada correctamente', 204);
    }
}
