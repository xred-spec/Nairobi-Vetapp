<?php

namespace App\Http\Controllers;

use App\Events\AppointmentEvent;
use App\Http\Requests\CitaRequest;
use App\Http\Resources\CitaResource;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\TrabajadorHorario;
use App\Services\CitaService;
use App\Services\MascotaService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;

class CitaController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function index(Request $request,CitaService $citaService){
            $this->authorize('viewAny',[Cita::class]);
             return $this->Respuesta($citaService->index($request),'todas las citas',200,null);
            return $this->Respuesta(CitaResource::collection($citaService->index($request)),'todas las citas',200,null);

    }

    public function store(CitaRequest $request){
        // un comentario
        $mascota = Mascota::with('raza')->where('id',$request->mascota_id)->where('visibilidad','visible')->first();
        if(!$mascota){
             return $this->Respuesta(null, 'mascota no encontrada', 404,null);
        }
        $this->authorize('createCita',[$mascota,$request]);
        $mascotaService = new MascotaService();
        $value = $mascotaService->createCita($mascota,$request);
        $user = $request->user();
        if($user->hasRol('cliente')){
            $user->load('cliente.mascotas','rol');
        }else{
            $user->load('rol');
        }
        event(new AppointmentEvent(['mensaje'=>'se ha agendado una nueva cita','uuid'=>Str::random(20)],$value,$user,'creation'));
        return $this->Respuesta(new CitaResource($value), 'Cita creada exitosamente', 201,null);
    }

    public function update(CitaRequest $request, $id){
        $cita = Cita::With('mascota')->where('id',$id)->first();
        $this->authorize('update',[$cita,$request]);
        $citaservice = new CitaService();
        $event = $citaservice->updateCita($cita,$request);
        Event(new AppointmentEvent(['modificacion'=>$cita,'mensaje'=>'se ha actualizado una cita','uuid'=>Str::random(20)],$event,$request->user(),'modificacion'));
        return $this->Respuesta(new CitaResource($event),'cita actualizada',200,null);
    }

    public function cancel(Request $request,$id){
        $cita = Cita::findOrFail($id);
        $this->authorize('cancel',$cita);
        $citaservice = new CitaService();
        $estado = $cita->estado;
        $event = $citaservice->CancelCita($cita,$request);
        Event(new AppointmentEvent(['antiguo' => $estado,'nuevo' => $event->estado,'mensaje'=>'se ha cancelado una cita','uuid'=>Str::random(20)],$event,$request->user(),'estado'));
        return $this->Respuesta(new CitaResource($event),'cita cancelada',200,null);
    }

    public function changeStatus(Request $request, $id){
       // dd('aqui si llego');
        $request->validate([
            'estado' => 'required|in:agendada,en_proceso,realizada,cancelada',
        ],[
            'estado.required' => 'El campo estado es obligatorio.',
            'estado.in' => 'El campo estado debe ser uno de los siguientes valores: agendada, en_proceso, realizada, cancelada.',
        ]);
        $cita = Cita::With('mascota')->where('id',$id)->first();
        $state = $cita->estado;
        $this->authorize('changeStatus',[$cita,$request]);
            $citaservice = new CitaService();
            $value = $citaservice->changeStatus($cita,$request);
            Event(new AppointmentEvent(['antiguo' => $state, 'nuevo' => $request->estado,'mensaje'=>'se ha modificado el estado de una cita','uuid'=>Str::random(20)],$value,$request->user(),'estado'));
            return $this->Respuesta(new CitaResource($value),'cita actualizada',200,null);
        // Lógica para cambiar el estado de una cita a en proceso o a agendada
    }

    public function search(Request $request,CitaService $citaService,$history=false)
    {
        $this->authorize('search',Cita::class);

        return $this->Respuesta($citaService->search($request,$history),'citas encontradas',200,null);

    }
    public function CitaFetchingCreate(Request $request){
        $citaservice = new CitaService();
        $this->authorize('viewAny',Cita::class);
        $value = $citaservice->CitaFetchingCreate($request);
        return $this->Respuesta($value,'datos enviaddos',200,null);
    }

    public function getMascotasCitas($id){
        $citaservice = new CitaService();
        $value = $citaservice->getMascotasCitas($id);
        return $this->Respuesta($value,'datos enviaddos',200,null);
    }

    public function getClientes(Request $request){
        $citaservice = new CitaService();
        $value = $citaservice->getClientes($request->nombre);
        return $this->Respuesta($value,'datos enviaddos',200,null);
    }

    public function getDisponibilidadHora($id,$fecha){
        $citaservice = new CitaService();
        $value = $citaservice->getDisponibilidadHora($id,$fecha);
        return $this->Respuesta($value,'datos enviados',200,null);
    }

    public function prefetchingeditcita(Request $request,$id){
        $citaservice = new CitaService();
        $value = $citaservice->prefetchingeditcita($id,$request);
        return $this->Respuesta($value,'datos enviados',200,null);
    }
}
