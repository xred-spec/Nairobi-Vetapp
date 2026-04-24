<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\TrabajadorHorario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CitaPolicy
{

    public function viewAny(User $user): bool
    {
        //throw new AuthorizationException('el usuario no tiene permisos para ver las citas');
        if($user->hasRol('cliente') ){
            return true;
        }
        if($user->hasRol('administrador')|| $user->hasRol('trabajador')){
            return true;
        }
       throw new AuthorizationException('el usuario no tiene permisos para ver esta cita');
    }


    public function view(User $user, Cita $cita): bool
    {
        return false;
    }


    public function cancel(User $user,Cita $cita): bool
    {
        if($user->hasRol('cliente')){
            return true;       
        }   
        if($user->hasRol('administrador')){
                return true;
        }
        if($user->hasRol('trabajador')){
                return true;
        }
        throw new AuthorizationException('el usuario no tiene permisos para ajecutar esta accion');
    }

    public function update(User $user, Cita $cita, Request $request): bool
    {
        $mascota = Mascota::where('id','=',$request->mascota_id)->where('visibilidad','=','visible')->first();
        if(!$mascota){
            throw new AuthorizationException('la mascota seleccionada no existe o no es visible');
        }
        if($user->hasRol('cliente')){
            if(($mascota->cliente_id == $user->cliente->id)){
                return true;
            }
            throw new AuthorizationException('No tienes permiso para ejecutar esta accion');
        }
        if($user->hasRol('administrador')){
            return true;
        }

        if($user->hasRol('trabajador')){
            $hora = TrabajadorHorario::where('id','=',$cita->horario_trabajador_id)->first();
            if($user->trabajador->id == $hora->trabajador_id){
                return true;
            } 
            throw new AuthorizationException('la accion no se pudo ejecutar, revise que el trabajador asignado a la cita sea el mismo que esta intentando realizar la accion');
        }

        throw new AuthorizationException('el usuario no tiene permisos para ajecutar esta accion');
    }

    public function changeStatus(User $user, Cita $cita){
        if($user->hasRol('administrador')){
                return true;
        }
        if($user->hasRol('trabajador')){
            if($user->trabajador->id == $cita->horarioTrabajador->trabajador->id){
                 return true;
            }
            throw new AuthorizationException('el trabajador no puede cambiar estados de cita que no son de el');
        }
        throw new AuthorizationException('el usuario no tiene permisos para cambiar el estado de esta cita');
    }

    public function search(User $user){
        return true;
        if($user->hasRol('cliente')){
            return true;       
        }   
        if($user->hasRol('administrador')){
                return true;
        }
        if($user->hasRol('trabajador')){
                return true;
        }
        throw new AuthorizationException('el usuario no tiene permisos para ajecutar esta accion');
    }

}
