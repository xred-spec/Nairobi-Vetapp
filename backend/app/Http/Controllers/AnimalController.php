<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalFilterRequest;
use App\Http\Requests\AnimalRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animales;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class AnimalController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function fullAndroid()
    {
        try {
            // no lo vuelvo a poner
            $razas = Animales::all();
            // $this->authorize('viewAny', Animales::class);
            return $this->Respuesta(
                AnimalResource::collection($razas),
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

    public function fullList()
    {
        try {
            $animales = Animales::where('visibilidad', 'visible')->get();
            return $this->respuesta(
                AnimalResource::collection($animales),
                'Animales obtenidos correctamente',
                200
            );
        } catch (Exception $e) {
            return $this->respuesta(
                null,
                'Ocurrio un problema obteniendo los animales',
                403,
                $e->getMessage()
            );
        }
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Animales::class);
        $query = Animales::filter($request)->orderBy('id', 'desc');
        $animales = $query->paginate(15)->withQueryString();
        $data = AnimalResource::collection($animales);
        $paginacion = [
            'current_page' => $animales->currentPage(),
            'last_page' => $animales->lastPage(),
            'per_page' => $animales->perPage(),
            'total' => $animales->total()
        ];

        return $this->Respuesta(
            [
                'data' => $data,
                'paginacion' => $paginacion
            ],
            '15 animales',
            200
        );
    }


    public function store(AnimalRequest $request)
    {
        try {

            $animal = Animales::create($request->validated());
            $this->authorize('create', [Animales::class]);
            return $this->Respuesta(
                new AnimalResource($animal),
                'Animal creado correctamente',
                201,
                null
            );
        } catch (QueryException $e) {

            if ($e->getCode() === '23000') {
                return $this->Respuesta(
                    null,
                    'Ya existe un animal con ese nombre',
                    409,
                    null
                );
            }

            return $this->Respuesta(
                null,
                'Ocurrio un problema creando el animal',
                403,
                $e->getMessage()
            );
        }



    }

    public function update(AnimalRequest $request, $id)
    {



        $request->validate([
            'visibilidad' => ['required', 'in:visible,invisible']
        ], [
            'visibilidad.required' => 'La visibilidad es requerida',
            'visibilidad.in' => 'La visibilidad debe ser visible o invisible'
        ]);
        $animal = Animales::where('deleted_at', '=', null)->findOrFail($id);
        $this->authorize('update', $animal);
        if (!$animal) {
            return $this->Respuesta(null, 'Animal no encontrado', 404, 'not found');
        }
        $animal->update([
            'nombre' => $request->nombre,
            'visibilidad' => $request->visibilidad
        ]);
        return $this->Respuesta(new AnimalResource($animal), 'Animal actualizado correctamente', 200, null);
    }

    public function delete($id)
    {

        $animal = Animales::findOrFail($id);
        $this->authorize('delete', $animal);
        $animal->delete();
        return response()->json(['message' => 'Animal eliminado correctamente'], 204);
    }

    public function switchState($id)
    {

        $animal = Animales::findOrFail($id);
        $this->authorize('update', $animal);
        $animal->visibilidad = $animal->visibilidad === 'visible' ? 'invisible' : 'visible';
        $animal->save();
        return response()->json($animal, 200);
    }
}
