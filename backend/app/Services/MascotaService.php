<?php

namespace App\Services;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Cita;
use App\Models\TrabajadorHorario;
use App\Exceptions\CitaException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class MascotaService
{
    public function index(Request $request)
    {
        $user = $request->user();

        $user->loadMissing('rol');

        $query = Mascota::with(['raza.animal', 'cliente.user']);

        if ($user->hasRol('cliente')) {
            $query->where('cliente_id', $user->cliente->id);
        }
        elseif (!$user->hasAnyRol(['trabajador', 'administrador'])) {
            throw new HttpException(403, 'No tienes permisos para ver mascotas');
        }

        $query->filter($request);

        return $query->paginate(15);
    }

    public function search(Request $request)
    {
        $user = $request->user();

        $query = Mascota::with(['raza.animal', 'cliente.user']);

        $query->when($request->nombre, function ($q) use ($request) {
            $q->where('nombre', 'like', '%' . $request->nombre . '%');
        });

        $query->when($request->raza, function ($q) use ($request) {
            $q->whereHas('raza', function ($r) use ($request) {
                $r->where('nombre', 'like', '%' . $request->raza . '%');
            });
        });

        $query->when($request->animal, function ($q) use ($request) {
            $q->whereHas('raza.animal', function ($a) use ($request) {
                $a->where('nombre', 'like', '%' . $request->animal . '%');
            });
        });

        if ($request->has('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($user->hasRol('cliente')) {
            $query->where('cliente_id', $user->cliente->id);
        }

        if ($user->hasRol('trabajador')) {
            $query->whereHas('cliente', function ($q) use ($user) {
                $q->whereHas('citas.horarioTrabajador', function ($q2) use ($user) {
                    $q2->where('trabajador_id', $user->trabajador->id);
                });
            });
        }


        return $query->paginate(15);
    }

    public function store(array $data, $user)
    {
        if (!$user->hasRol('administrador') && !$user->hasRol('trabajador') && !$user->hasRol('cliente')) {
            throw new HttpException(403, 'No tienes permisos para crear mascotas');
        }

        if ($user->hasRol('cliente')) {
            if (!$user->cliente) {
                throw new HttpException(422, 'El usuario tiene rol cliente pero no tiene un perfil de cliente creado.');
            }
            $data['cliente_id'] = $user->cliente->id;
        }

        if (!isset($data['cliente_id'])) {
            throw new HttpException(422, 'Debes seleccionar un cliente para registrar la mascota.');
        }

        unset($data['animal_id']);

        return Mascota::create($data);
    }

    public function update(Mascota $mascota, array $data, $user)
    {
        if ($user->hasRol('cliente')) {
            if ($mascota->cliente_id !== $user->cliente->id) {
                throw new HttpException(403, 'No puedes modificar esta mascota');
            }

            $data['cliente_id'] = $user->cliente->id;
        }

        if (
            !$user->hasRol('administrador') &&
            !$user->hasRol('trabajador') &&
            !$user->hasRol('cliente')
        ) {
            throw new HttpException(403, 'No tienes permisos para actualizar mascotas');
        }

        $mascota->update($data);

        return $mascota;
    }
    public function delete(Mascota $mascota)
    {
        $mascota->delete();
        return true;
    }


     public function createCita(Mascota $mascota,Request $request){
        if(!$mascota->raza || $mascota->raza->visibilidad!='visible'){
            throw new CitaException('ya no se reciben mas animales de este tipo, contacte al administrador');
        }
        $disponibilidad = Cita::where('horario_trabajador_id',$request->horario_trabajador_id)->where('fecha',$request->fecha)->whereIn('estado', ['agendada', 'en_proceso'])->exists();
        if($disponibilidad){
             throw new CitaException('este trabajador ya tiene una cita agendada a esa hora y a esa fecha');
        }

        $trabajador = TrabajadorHorario::findOrFail($request->horario_trabajador_id);   
        $user = $request->user();
        $now = now();
        $fecha = Carbon::parse($request->fecha.' '.$trabajador->horario->inicio_hora);

        if($trabajador->asignado =='desasignado' ){
            throw new CitaException('El trabajador no esta disponible a esa hora');
        }

        if($user->hasRol('cliente')){
            $citas = Cita::where('mascota_id',$request->mascota_id)->where('fecha',$request->fecha)->whereNotIn('estado',['cancelada','realizada'])->first();
            if($citas){
                throw new CitaException('solo se puede reservar una cita al dia por mascota, contacte a la veterinaria para mas reservaciones');
            }

            if(!$fecha->greaterThan($now->copy()->addHour())){
            throw new CitaException('Solo se puede reservar con al menos una hora de anticipacion');
            }

            if($mascota->cliente_id !== $user->cliente->id){
            throw new CitaException('solo puedes agendar cita a mascotas que te pertenencen');
            }  //Cita::create($request->validated());

        }

        if ($user->hasRol('trabajador')) {

            if(!$fecha->greaterThan($now)){
                throw new CitaException('la reserva debe ser en una hora valida y dia valido');
            }

            if( $user->trabajador->id !== $trabajador->trabajador_id){
                throw new CitaException('No puedes usar un horario que no te pertenece');
            }
          //   return Cita::create($request->validated());
   
        }

        if($user->hasRol('administrador')){
            
            if(!$fecha->greaterThan($now)){
                throw new CitaException('la reserva debe ser en una hora valida y dia valido');
            }
         //    return Cita::create($request->validated());
        }
        return $this->CitaProcedure($request);   
    }

    public function CitaProcedure(Request $request){
        // throw new \Exception('Not implemented');
        try{
             $cita =  DB::Select('CALL create_cita(?,?,?,?,?)', [
            $request->fecha,
            $request->tipo,
            $request->descripcion,
            $request->mascota_id,
            $request->horario_trabajador_id
        ]);
          $id = $cita[0]->id ?? throw new CitaException('No se pudo crear la cita');
          return Cita::with([
            'mascota.cliente.user',
            'horarioTrabajador.horario',
            'horarioTrabajador.trabajador.user'])->findOrFail($id);
        }catch(\Throwable $e){
            throw new CitaException('Error al crear la cita: '.$e->getMessage());
        }

    }
}