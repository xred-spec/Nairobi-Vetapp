<?php

namespace App\Http\Controllers;

use App\Http\Requests\RazaRequest;
use App\Models\Raza;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\RazaResource;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RazaController extends Controller
{
    use ApiResponse, AuthorizesRequests;


    public function fullList()
    {
        try {
            $razas = Raza::with('animal')->where('visibilidad', 'visible')->get();
            return $this->Respuesta(
                RazaResource::collection($razas),
                'Razas obtenidas correctamente',
                200
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema obteniendo las razas',
                403,
                $e->getMessage()
            );
        }
    }

    public function fullAndroid()
    {
        try {
            // $this->authorize('viewAny', Raza::class);
            $razas = Raza::with('animal')->get();
            return $this->Respuesta(
                RazaResource::collection($razas),
                'Razas obtenidas correctamente',
                200
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema obteniendo las razas',
                403,
                $e->getMessage()
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', Raza::class);

            $query = Raza::with('animal')
            ->filter($request)
            ->orderBy('id', 'desc');

            $razas = $query->paginate(15)->withQueryString();

            return $this->Respuesta(
                [
                    'data' => RazaResource::collection($razas),
                    'paginacion' => [
                        'current_page' => $razas->currentPage(),
                        'last_page' => $razas->lastPage(),
                        'per_page' => $razas->perPage(),
                        'total' => $razas->total(),
                    ]
                ],
                'Razas obtenidas correctamente',
                200
            );

        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema obteniendo las razas',
                403,
                $e->getMessage()
            );
        }
    }

    public function store(RazaRequest $request)
    {
        try {
            $this->authorize('create', Raza::class);

            $raza = Raza::create($request->validated());
            return $this->Respuesta(
                new RazaResource($raza),
                'Raza creada correctamente',
                201
            );
        } catch (QueryException $e) {

            if ($e->getCode() === '23000') {
                return $this->Respuesta(
                    null,
                    'Ya existe una raza con ese nombre para el mismo animal',
                    409,
                    null
                );
            }
            return $this->Respuesta(
                null,
                'Ocurrio un problema creando la raza',
                403,
                $e->getMessage()
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema creando la raza',
                403,
                $e->getMessage()
            );
        }


    }

    public function show($id)
    {
        try {
            $raza = Raza::findOrFail($id);
            $this->authorize('view', $raza);
            return $this->Respuesta(
                new RazaResource($raza),
                'Raza obtenida correctamente',
                200
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema obteniendo la raza',
                403,
                $e->getMessage()
            );
        }

    }

    public function update(RazaRequest $request, $id)
    {
        try {

            $raza = Raza::findOrFail($id);
            // Un cambio para que funcione el backend
            $this->authorize('update', $raza);
            $raza->update($request->validated());

            return $this->Respuesta(
                new RazaResource($raza),
                'Raza actualizada correctamente',
                200
            );
        } catch (QueryException $e) {

            if ($e->getCode() === '23000') {
                return $this->Respuesta(
                    null,
                    'Ya existe una raza con ese nombre para el mismo animal',
                    409,
                    null
                );
            }
            return $this->Respuesta(
                null,
                'Ocurrio un problema actualizando la raza',
                403,
                $e->getMessage()
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema actualizando la raza',
                403,
                $e->getMessage()
            );
        }

    }

    public function destroy($id)
    {
        try {
            $raza = Raza::findOrFail($id);
            $this->authorize('delete', $raza);
            $raza->delete();

            return $this->Respuesta(
                new RazaResource($raza),
                "Raza eliminada correctamente",
                204
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema eliminando la raza',
                403,
                $e->getMessage()
            );
        }

    }

    public function cambiarEstado($id)
    {
        try {
            $raza = Raza::findOrFail($id);
            $this->authorize('update', $raza);
            $raza->visibilidad = $raza->visibilidad === 'visible' ? 'invisible' : 'visible';
            $raza->save();
            return $this->Respuesta(
                new RazaResource($raza),
                'Visibilidad cambiada correctamente',
                200
            );
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $mensaje = $e->errorInfo[1] ?? 'Error desconocido';

            if ($sqlState === '45000') {
                return $this->Respuesta(
                    null,
                    $mensaje,
                    422,
                    null
                );
            }
            return $this->Respuesta(
                null,
                'Error en base de datos al cambiar visibilidad',
                500,
                $mensaje
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Ocurrio un problema cambiando la visibilidad',
                403,
                $e->getMessage()
            );
        }

    }
}