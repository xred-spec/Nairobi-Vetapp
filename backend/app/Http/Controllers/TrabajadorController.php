<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTrabajadorRequest;
use App\Http\Requests\UpdateTrabajadorRequest;
use App\Http\Requests\SyncHorariosRequest;
use App\Http\Resources\TrabajadorResource;
use App\Models\Trabajador;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Horario;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    use ApiResponse;

    public function getHorarios()
    {
        $horarios = Horario::all();
        return $this->Respuesta($horarios, 'Horarios obtenidos correctamente', 200, null);
    }

    public function index(Request $request)
    {
        try {
            $trabajadores = Trabajador::with(['user', 'horarios'])
                ->filter($request->all())
                ->paginate(15);

            return $this->Respuesta(
                [
                    'data' => TrabajadorResource::collection($trabajadores),
                    'paginacion' => [
                        'current_page' => $trabajadores->currentPage(),
                        'last_page' => $trabajadores->lastPage(),
                        'per_page' => $trabajadores->perPage(),
                        'total' => $trabajadores->total(),
                    ],
                ],
                'Trabajadores obtenidos correctamente',
                200
            );
        } catch (\Exception $e) {
            return $this->Respuesta(null, 'Error al obtener trabajadores: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $trabajador = Trabajador::with(['user', 'horarios'])->find($id);

        if (!$trabajador) {
            return $this->Respuesta(null, 'Trabajador no encontrado', 404, null);
        }

        return $this->Respuesta(new TrabajadorResource($trabajador), 'Trabajador encontrado', 200, null);
    }

    public function store(CreateTrabajadorRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $user = User::create([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'password' => Hash::make($request->telefono),
                'rol_id' => 2,
                'estado' => 'pendiente',
            ]);

            $trabajador = $user->trabajador()->create();

            $pivotData = collect($request->horarios)
                ->mapWithKeys(fn($id) => [$id => ['asignado' => 'asignado']])
                ->toArray();

            $trabajador->horarios()->attach($pivotData);

            return $trabajador->load(['user', 'horarios']);
        });

        return $this->Respuesta(
            new TrabajadorResource($data),
            'Trabajador creado correctamente',
            201,
            null
        );
    }

    public function update(UpdateTrabajadorRequest $request, $id)
    {
        $trabajador = Trabajador::with(['user', 'horarios'])->find($id);

        if (!$trabajador) {
            return $this->Respuesta(null, 'Trabajador no encontrado', 404, null);
        }

        $trabajador->user->update($request->validated());

        return $this->Respuesta(
            new TrabajadorResource($trabajador->fresh(['user', 'horarios'])),
            'Trabajador actualizado correctamente',
            200,
            null
        );
    }

    public function desactivarTrabajador($id)
    {
        $trabajador = Trabajador::find($id);

        if (!$trabajador) {
            return $this->Respuesta(null, 'Trabajador no encontrado', 404, null);
        }

        DB::transaction(function () use ($trabajador) {
            $trabajador->user->update(['estado' => 'removido']);
        });

        return $this->Respuesta(null, 'Trabajador removido correctamente', 200, null);
    }

    public function activarTrabajador($id)
    {
        $trabajador = Trabajador::find($id);

        if (!$trabajador) {
            return $this->Respuesta(null, 'Trabajador no encontrado', 404, null);
        }

        DB::transaction(function () use ($trabajador) {
            $trabajador->user->update(['estado' => 'registrado']);
        });

        return $this->Respuesta(null, 'Trabajador activado correctamente', 200, null);
    }



    public function syncHorarios(SyncHorariosRequest $request, $id)
    {
        $trabajador = Trabajador::with('horarios')->find($id);

        if (!$trabajador) {
            return $this->Respuesta(null, 'Trabajador no encontrado', 404, null);
        }

        $nuevosIds = collect($request->horarios);
        $actualesIds = $trabajador->horarios->pluck('id');

        DB::transaction(function () use ($trabajador, $nuevosIds, $actualesIds) {

            $desasignar = $actualesIds->diff($nuevosIds);
            if ($desasignar->isNotEmpty()) {
                $trabajador->horarios()
                    ->wherePivotIn('horario_id', $desasignar->toArray())
                    ->each(function ($horario) use ($trabajador) {
                        $trabajador->horarios()->updateExistingPivot($horario->id, ['asignado' => 'desasignado']);
                    });
            }

            foreach ($nuevosIds as $horarioId) {
                $existe = $actualesIds->contains($horarioId);

                if (!$existe) {
                    $trabajador->horarios()->attach($horarioId, ['asignado' => 'asignado']);
                } else {
                    $trabajador->horarios()->updateExistingPivot($horarioId, ['asignado' => 'asignado']);
                }
            }
        });

        return $this->Respuesta(new TrabajadorResource($trabajador->fresh(['user', 'horarios'])), 'Horarios actualizados correctamente correctamente', 200, null);
    }
}