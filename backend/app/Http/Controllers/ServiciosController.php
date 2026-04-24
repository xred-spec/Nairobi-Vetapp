<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicios;
use App\Http\Resources\ServicioResource;
use App\Http\Requests\ServicioRequest;
use App\Http\Requests\ServicioFilterRequest;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiciosController extends Controller
{
    use ApiResponse,AuthorizesRequests;

    public function index2(Request $request)
    {
        
     //  $this->authorize('viewAny',Servicios::class);
       $servicios = Servicios::where('visibilidad','visible')->orderBy('nombre')->get();
         if($servicios->isEmpty()){
            return $this->Respuesta(null,'No se encontraron servicios',404,'servicios no encontrados');
        }
        return $this->Respuesta(ServicioResource::collection($servicios),'15 servicios',200,null);
        
    }


    public function index(Request $request)
    {
       $this->authorize('viewAny',Servicios::class);
       $servicios = Servicios::filter($request) ->paginate(15);
         if($servicios->isEmpty()){
            return $this->Respuesta(null,'No se encontraron servicios',404,null);
        }
        $data=ServicioResource::collection($servicios);
        $paginacion = [
                'current_page' => $servicios->currentPage(),
                'last_page' => $servicios->lastPage(),
                'per_page' => $servicios->perPage(),
                'total' => $servicios->total()
            ];
               return $this->respuesta(
                [
                    'data' => $data,
                    'paginacion' => $paginacion
                ],
                'Servicios obtenidas correctamente',
                200
            );
    }

    //Crea un nuevo servicio en la base de datos
    public function store(ServicioRequest $request)
    {
          $this->authorize('create',Servicios::class);
          $servicio = Servicios::create($request->validated());
          return $this->Respuesta(new ServicioResource($servicio),'Servicio creado correctamente',201,null);    
    }
          

    
    public function search(ServicioFilterRequest $request){
        $this->authorize('viewAny',[Servicios::class]);
        $servicios = Servicios::Filter($request)->paginate(15);
        if($servicios->isEmpty()){
            return $this->Respuesta(null,'No se encontraron servicios con los criterios de búsqueda',404,null);
        }
        return $this->Respuesta(ServicioResource::collection($servicios),'servicios encontrados',200,null);
    }

    //Actualiza un servicio existente.
    public function update(ServicioRequest $request, $id)
    {
        $servicio = Servicios::where('deleted_at','=',null) ->find($id);
         if(!$servicio){
            return $this->Respuesta(null,'Servicio no encontrado',404,null);
        }
        $this->authorize('update', $servicio);
        $servicio->update($request->validated());
        return $this->Respuesta(new ServicioResource($servicio),'Servicio actualizado correctamente',200,null);
    }

    //Elimina permanentemente (Hard Delete) un servicio de la base de datos
    public function destroy($id)
    {
        $servicio = Servicios::where('deleted_at','=',null) ->find($id);
      if(!$servicio){
            return $this->Respuesta(null,'Servicio no encontrado',404,null);
        }
        $this->authorize('delete',$servicio);
       $servicio->delete();
        return $this->Respuesta($servicio,'Servicio eliminado correctamente',200,null);
    }

    public function cambiarEstado($id)
    {
            $servicio = Servicios::where('deleted_at','=',null) ->find($id);
            if(!$servicio){
            return $this->Respuesta(null,'Servicio no encontrado',404,null);
        }
         $this->authorize('CambiarEstado',$servicio);
        $nuevoEstado = $servicio->visibilidad == 'visible' ? 'invisible' : 'visible';
        $servicio ->visibilidad=$nuevoEstado;
        $servicio->save();
        return $this->Respuesta($servicio,'Servicio actualizado correctamente',200,null);
    }
}