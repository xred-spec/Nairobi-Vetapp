<?php
namespace App\Services;

use App\Models\Cita;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\TrabajadorHorario;
use App\Exceptions\CitaException;
use App\Models\HorarioTrabajador;
use App\Models\Trabajador;
use App\Models\User;

class CitaService{
    public function __construct()
    {
        // Constructor vacío
    }

    public function getCita($id)
    {
        // Lógica para obtener una cita por su ID
        return "Detalles de la cita con ID: $id";
    }
    public function CancelCita($cita,$request)
    {
        if($cita->consulta || $cita->estado !='agendada'){
            throw new CitaException('el estado actual de la cita no permite generar cancelaciones');
        }
        $user = $request->user();
        if($user->hasRol('cliente')){
         $fecha = Carbon::parse($cita->fecha->format('Y-m-d').' '.$cita->horarioTrabajador->horario->inicio_hora);
            if($fecha->lessThanOrEqualTo(now()->addDay())){
            throw new CitaException('El plazo para hacer alguna modificacion a la cita a caducado, contacte con el trabajador');
            }
            if($cita->mascota->cliente->id != $user->cliente->id){
                throw new CitaException('No puedes cancelar citas que no te pertenecen');
            }
            $cita->update(['estado'=>'cancelada']);
            return $cita;
        }

        if($user->hasRol('trabajador')){
            if($cita->horarioTrabajador->trabajador->id != $user->trabajador->id){
                throw new CitaException('No puedes cancelar citas que no te pertenecen');
            }
            $cita->update(['estado'=>'cancelada']);
            return $cita;
        }

        if($user->hasRol('administrador')){
            $cita->update(['estado'=>'cancelada']);
            return $cita;
        }
        throw new CitaException('Error al cancelar la cita');
    }

    public function updateCita(Cita $cita,Request $request)
    {
        $disponibilidad = Cita::where('horario_trabajador_id',$request->horario_trabajador_id)->where('fecha',$request->fecha)->where('id','!=',$cita->id)->where(function($query){
        $query->where('estado','agendada')->orWhere('estado','en_proceso');
        })->exists();
        if($disponibilidad){
             throw new CitaException('este trabajador ya tiene una cita agendada a esa hora y a esa fecha');
        }

        if($cita->estado !='agendada'){
            throw new CitaException('Error en la modificacion de la cita');
        }

        $mascota = Mascota::findOrFail($request->mascota_id);
        if($cita->mascota->cliente_id != $mascota->cliente_id){
            throw new CitaException('No se permite añadir otra mascota que no le pertenezca al cliente original de la cita');
        }

        $user = $request->user();
        $hora = TrabajadorHorario::where('id','=',$cita->horario_trabajador_id)->first();
        $fecha = Carbon::parse($cita->fecha->format('Y-m-d').' '.$hora->horario->inicio_hora);
        
        if($user->hasRol('cliente')){
           
            if($fecha->lessThanOrEqualTo(now()->addDay())){
            throw new CitaException('El plazo para hacer alguna modificacion a la cita a caducado, contacte con el trabajador');
            }

            $cita->update([
                 'descripcion'=>$request->descripcion,
            ]);
             return $cita;
        }

        $horario = TrabajadorHorario::where('id','=',$request->horario_trabajador_id)->where('asignado','asignado')->first();
        if(!$horario){
            throw new CitaException('el horario no es valido o esta desasignado, contacte con el administrado');
        }
         if($user->hasRol('trabajador')){
            if($user->trabajador->id != $horario->trabajador->id){
                throw new CitaException('El horario seleccionado no le pertenece a este trabajador');
            }
            $cita->update($request->validated());
            return $cita;
         }   

         if($request->user()->hasRol('administrador')){
            $cita->update($request->validated());
            return $cita;
        }
        
    }
    
    public function changeStatus(Cita $cita,Request $request)
    {
        if($cita->estado != $request->estado){
            $cita->update(['estado'=>$request->estado]);
            return $cita;
        }
        throw new CitaException('no se pudo cambiar el estado de la cita',400);
        
        
    }

    public function index(Request $request){
        $user= $request->user();
        if($user->hasRol('cliente')){
            return Cita::with('mascota','horarioTrabajador.trabajador.user','consulta')->whereHas('mascota',function($query) use ($user){
                $query->where('cliente_id','=',$user->cliente->id);
            })->paginate(15);
        }

        if($user->hasRol('trabajador')){
            return Cita::with('mascota','horarioTrabajador.trabajador','consulta','mascota.cliente.user')->whereHas('horarioTrabajador',function($query) use ($user){
                $query->where('trabajador_id','=',$user->trabajador->id);
            })->paginate(15);
        }
        if($user->hasRol('administrador')){
            return Cita::with('mascota','horarioTrabajador.trabajador.user','consulta','mascota.cliente.user')->paginate(15);
        }
        throw new CitaException('Error al obtener las citas');
    }

    public function search(Request $request,$history)
{
    $user = $request->user();

        $queryBuilder = Cita::query()->with([
                'mascota',
                'mascota.raza',
                'mascota.raza.animal',
                'horarioTrabajador.trabajador.user',
                'mascota.cliente.user'=>function($q){$q->withTrashed();},
                'horarioTrabajador.horario',
                'consulta.consultaServicios.ConsultaServicioProductos.producto'=>function($q){$q->withTrashed();},
                'consulta.consultaServicios.servicio'=>function($q){$q->withTrashed();}
        ]);
    if($request->exclude_deleted){
        $queryBuilder->whereHas('mascota',function($q){
            $q->whereNull('deleted_at');
        });
    }

    
    if ($user->hasRol('cliente')) {
            //mascota
           $queryBuilder->whereHas('mascota', function ($query) use ($user, $request) {
            $query->where('cliente_id', $user->cliente->id)
                    ->when($request->mascota, function ($q) use ($request) {
                        $q->where('nombre', 'like', '%' . $request->mascota . '%');
                    });
            })            
            ->when($request->trabajador, function ($query) use ($request) {
                $query->whereHas('horarioTrabajador.trabajador.user', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->trabajador . '%');
                });
            }); 
          }  
  


    if ($user->hasRol('administrador')) {
   
        $queryBuilder ->when($request->mascota, function ($q) use ($request) {
                    $q->whereHas('mascota',function($m) use($request){
                        $m->where('nombre', 'like', '%' . $request->mascota . '%');
                    });
            })
            ->when($request->trabajador, function ($query) use ($request) {
                $query->whereHas('horarioTrabajador.trabajador.user', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->trabajador . '%');
                });
            })    
            ->when($request->cliente,function($query) use($request){
                $query->whereHas('mascota.cliente.user',function($q) use ($request){
                    $q->where('nombre','like','%'.$request->cliente.'%');
                });
            });
    }
    if($user->hasRol('trabajador')){
            if(!$history){
                
            $queryBuilder->whereHas('horarioTrabajador', function ($query) use ($request,$user) {
                $query->where('trabajador_id','=',$user->trabajador->id);  
            }); 
            //;
            }else{
            $queryBuilder->when($request->trabajador,function($query) use($request){
                $query->whereHas('horarioTrabajador.trabajador.user',function($q) use($request){
                    $q->where('nombre','like','%'.$request->trabajador.'%');
                });
            });
            }
            $queryBuilder->when($request->cliente,function($query) use($request){
                $query->whereHas('mascota.cliente.user',function($q) use ($request){
                    $q->where('nombre','like','%'.$request->cliente.'%');
                });
            })
            ->when($request->mascota, function ($q) use ($request) {
                $q->whereHas('mascota', function($m) use($request){
                    $m->where('nombre', 'like', '%' . $request->mascota . '%');
                });
            });
    }
    $queryBuilder->when($request->raza, function ($query) use ($request) {
                $query->whereHas('mascota.raza', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->raza . '%');
                });        
            })
            ->when($request->animal, function ($query) use ($request) {
                    $query->whereHas('mascota.raza.animal', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->animal . '%');
                });
            }) 
            ->when($request->estado, function ($query) use ($request) {
                $query->where('estado', $request->estado);
            })
            ->when($request->fecha, function ($query) use ($request) {
                if ($request->fecha_final) {
                    $query->whereBetween('fecha', [$request->fecha, $request->fecha_final]);
                } else {
                    $query->whereDate('fecha', $request->fecha);
                }
            })
            ->when($request->tipo, function ($query) use ($request) {
                $query->where('tipo', $request->tipo);
            });
          //  ->paginate(15);
    return $queryBuilder->orderByRaw('(select count(*) from consultas where consultas.cita_id = citas.id)Desc')->orderby('fecha')
       ->orderByRaw('(select inicio_hora from horarios inner join horario_trabajador on horario_trabajador.horario_id = horarios.id where horario_trabajador.id = citas.horario_trabajador_id limit 1) ASC')->paginate(15);
}

    public function CitaFetchingCreate(Request $request){
        $user = $request->user();
        //return $user->with('cliente')->get();
        if($user->hasRol('cliente')){
            $mascotas = Mascota::where('cliente_id','=',$user->cliente->id)
            ->whereHas('raza',function($q){
                $q->where('visibilidad','visible');
            })
            ->where('visibilidad','visible')->get();
            $veterinarios = Trabajador::with('user')->whereHas('horarioTrabajadores',function($q){
                $q->where('asignado','asignado');
            })->get();
            return [
                'mascotas'=>$mascotas,
                'trabajadores'=>$veterinarios
            ];
        }
        //return User::with('rol')->get();
        if($user->hasRol('trabajador')){
            $clientes =User::with('cliente.mascotas')->whereHas('cliente',function($query){
                $query->whereHas('mascotas',function($query){
                    $query->where('visibilidad','visible')->whereHas('raza',function($q){
                        $q->where('visibilidad','visible');
                    });
                });
            })->orderby('nombre')->paginate(50);
            return [
                'clientes'=>$clientes->items(),
                'trabajador'=>Trabajador::where('user_id',$user->id)->first()
            ];
        }
        if($user->hasRol('administrador')){
             $user =User::with('cliente.mascotas')->whereHas('cliente',function($query){
                $query->whereHas('mascotas',function($query){
                    $query->where('visibilidad','visible')->whereHas('raza',function($q){
                        $q->where('visibilidad','visible');
                    });
                });
            })->orderby('nombre')->paginate(50);
            $veterinario = Trabajador::with('user')->whereHas('horarioTrabajadores',function($q){
                $q->where('asignado','asignado');
            })->get();
            return [
                'clientes'=>$user->items(),
                'trabajadores'=>$veterinario
            ];
        }
        throw new CitaException('El usuario no tiene permisos para crear citas');
    }

    public function getMascotasCitas($id){
        return [
            'mascotas'=>Mascota::whereHas('cliente.user',function($query) use ($id){
                $query->where('id',$id);
            })->whereHas('raza',function($q){
                $q->where('visibilidad','visible');
            })->where(function($q){
                $q->where('visibilidad','visible');
            })->get()
        ];
    }

    public function getClientes(string $payload ){
        return [
            'clientes'=>User::WhereHas('rol',function($query){
                $query->where('nombre','cliente');
            })->whereHas('cliente', function($query) {
             $query->whereHas('mascotas',function($q){
                $q->whereHas('raza',function($q){
                    $q->where('visibilidad','visible');
                })->where('visibilidad','visible');
             }); 
            })->where(function($query) use ($payload){
                $query->where('nombre','like','%'.$payload.'%')
                ->orWhere('telefono','like','%'.$payload.'%');
            })->get()
        ];
    }
    
    public function getDisponibilidadHora($id,string $fecha){
       $horario =  HorarioTrabajador::withWhereHas('horario',function($query) use ($id,$fecha){
           $fechaCarbon = Carbon::parse($fecha);
            $fechaFormato = $fechaCarbon->format('Y-m-d');

            if ($fechaFormato <= Carbon::now()->format('Y-m-d')) {
                $query->where('inicio_hora', '>', Carbon::now()->format('H:i:s'));
            }

            if ($fechaCarbon->isSaturday()) {
                $query->where('inicio_hora', '<', '14:00:00');
            }
            $query->orderBy('inicio_hora');
        })->whereNotIn('id',function($query) use ($fecha){
         $query->select('horario_trabajador_id')->from('citas')->where('fecha',$fecha)
         ->where(function($query){
            $query->where('estado','en_proceso')->orWhere('estado','agendada');
         });})
         ->where('trabajador_id',$id)->where('asignado','asignado')->get();
        $horario = $horario->sortBy('horario.inicio_hora')->values();
         return [
            'horas'=>$horario
         ];
    }

    public function prefetchingeditcita($id,$request){
        $cita = Cita::with('horarioTrabajador.trabajador','horarioTrabajador.horario','mascota.cliente')->where('id',$id)->first();
        $user = $request->user();
        if(!$cita){
            throw new CitaException('error al mostrar la cita',404);
        }
        $mascotas = Mascota::where('cliente_id',$cita->mascota->cliente_id)->whereHas('raza',function($q){
                        $q->where('visibilidad','visible');
                    })->get();
        $horario = HorarioTrabajador::withWhereHas('horario',function($query) use ($cita){
           /* $fecha = Carbon::parse($cita->fecha)->format('Y-m-d');
            $newfecha = Carbon::parse($fecha.' '.$cita->horarioTrabajador->horario->inicio_hora);
                if($newfecha<Carbon::now()){
                    $query->where('inicio_hora','>',Carbon::now()->format('H:i:s'));
            }*/
             $fecha = Carbon::parse($cita->fecha)->format('Y-m-d');
                if($fecha<=Carbon::now()){
                    $query->where('inicio_hora','>',Carbon::now()->format('H:i:s'));
                }        
                
            $query->orderBy('inicio_hora');
        })->whereNotIn('id',function($query) use ($cita){
            $query->select('horario_trabajador_id')->from('citas')->where('fecha',$cita->fecha)
                ->where(function($query){
                    $query->where('estado','en_proceso')->orWhere('estado','agendada');
                });
        })
        ->where('trabajador_id',$cita->horarioTrabajador->trabajador->id)->where('asignado','asignado');

        if($user->hasRol('trabajador')){
            return [
                'horarios'=>$horario->get(),
                'mascotas'=>$mascotas
            ];
        }

        if($user->hasRol('administrador')){
            $veterinario = Trabajador::with('user')->whereHas('horarioTrabajadores',function($q){
                $q->where('asignado','asignado');
            })->get();
            return [
                'horarios'=>$horario->get(),
                'trabajadores'=>$veterinario,
                'mascotas'=>$mascotas
            ];
        }
        throw new CitaException('error de permisos');
    }

    
}