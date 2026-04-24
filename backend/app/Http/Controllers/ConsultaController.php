<?php

namespace App\Http\Controllers;

use App\Events\AppointmentEvent;
use App\Models\Consulta;
use App\Http\Resources\ConsultaResource;
use App\Http\Requests\ConsultaRequest;
use App\Traits\ApiResponse;
use App\Policies\ConsultaPolicy;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Models\Cita;
use App\Models\Productos;
use Symfony\Contracts\EventDispatcher\Event;

class ConsultaController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function store(ConsultaRequest $request)
    {
        
        $cita = Cita::find($request->cita_id);
        if (!$cita) {
            return $this->respuesta(null, 'Cita no encontrada', 404);
        }

        if (in_array($cita->estado, ['realizada', 'cancelada', 'agendada'])) {
            return $this->Respuesta(
                null,
                'Solo se puede actualizar citas en proceso',
                403,
                null
            );
        }
        
        if($cita->consulta){
            return $this->Respuesta(
                null,
                'esta cita ya tiene una consulta',
                403,
                'la cita ya tiene una consulta activa'
            );
        }

        DB::beginTransaction();
        try {
            $this->authorize('create', Consulta::class);

            $datos = $request->validated();

            $consulta = Consulta::create([
                'pre_diagnostico' => $datos['pre_diagnostico'] ?? null,
                'descripcion_consulta' => $datos['descripcion_consulta'] ?? null,
                'indicaciones' => $datos['indicaciones'] ?? null,
                'cita_id' => $datos['cita_id'],
                'total_servicios' => $datos['total_servicios'] ?? 0,
                'total_producto' => $datos['total_producto'] ?? 0,
                'total' => $datos['total'] ?? 0
            ]);
            /*
            foreach ($datos['servicios'] as $servicio) {

                $consultaServicio = $consulta->consultaServicios()->create([
                    'servicio_id' => $servicio['servicio_id'],
                    'detalles_servicio' => $servicio['detalles_servicio'] ?? '',
                    'precio_servicio' => $servicio['precio_servicio'] ?? 0,
                    'precio_producto' => $servicio['precio_producto'] ?? 0,
                    'total' => $servicio['total'] ?? 0
                ]);

                if (!empty($servicio['productos'])) {
                    foreach ($servicio['productos'] as $producto) {
                        $consultaServicio->productos()->attach(
                            $producto['producto_id'],
                            [
                                'cantidad_usada' => $producto['cantidad'],
                                'tipo_venta' => $producto['tipo_venta'] ?? 'Total',
                                'subtotal' => $producto['subtotal'] ?? 0
                            ]
                        );
                    }
                }
            }
                */
            DB::commit();

            $consulta->load(
                'consultaServicios.productos',
                'consultaServicios.servicio'
            );
            Event(new AppointmentEvent(['antiguo' => $consulta->cita->estado,'nuevo'=>'consulta'], $consulta->cita, $request->user(), 'estado'));
            return $this->Respuesta(
                new ConsultaResource($consulta),
                'Consulta creada correctamente',
                201
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->Respuesta(
                null,
                'Error al crear la consulta',
                403,
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        $consulta = Consulta::find($id);
        if (!$consulta) {
            return $this->Respuesta(
                null,
                'Consulta no encontrada',
                404
            );
        }

        try {
            $this->authorize('view', $consulta);
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                null,
                403,
                $e->getMessage()
            );
        }

        return $this->Respuesta(
            new ConsultaResource($consulta->load(
                'consultaServicios.productos',
                'consultaServicios.servicio'
            )),
            'Consulta obtenida correctamente',
            200,
            null
        );
    }

    public function update(ConsultaRequest $request, $id)
    {
        $consulta = Consulta::with('cita')->where('id',$id)->first();
        if (!$consulta) {
            return $this->Respuesta(
                null,
                'Consulta no encontrada',
                404,
                null
            );
        }

        // validacion de que la consulta no este realizada | cancelada
        if (in_array($consulta->cita->estado, ['realizada', 'cancelada', 'agendada'])) {
            return $this->Respuesta(
                null,
                'Solo se puede actualizar citas en proceso',
                403,
                null
            );
        }
        try {
            // $this->authorize('update', $consulta);
            // aqui empieza la parte fea
            DB::beginTransaction();
            $datos = $request->validated();
            $consulta->cita()->update(['estado'=>'realizada']);
            $consulta->update([
                
                'pre_diagnostico' => $datos['pre_diagnostico'] ?? $consulta->pre_diagnostico,
                'descripcion_consulta' => $datos['descripcion_consulta'] ?? $consulta->descripcion_consulta,
                'indicaciones' => $datos['indicaciones'] ?? $consulta->indicaciones,
                'total_servicios' => $datos['total_servicios'] ?? $consulta->total_servicios,
                'total_producto' => $datos['total_producto'] ?? $consulta->total_producto,
                'total' => $datos['total'] ?? $consulta->total
            ]);

            // actualizar servicios/productos de la consulta
            if (!empty($datos['servicios'])) {
                // sacar los ids de los servicios enviados
                $serviciosId = collect($datos['servicios'])->pluck('id')->filter()->toArray();

                // eliminar servicios que ya no están en la peticion
                $serviciosEliminar = $consulta->consultaServicios()
                    ->whereNotIn('id', $serviciosId)
                    ->get();
                foreach ($serviciosEliminar as $servicio) {
                    // desvincular los servicios porque si no truena mysql
                    $servicio->productos()->detach();
                    $servicio->delete();
                }


                foreach ($datos['servicios'] as $servicio) {
                    if (!empty($servicio['id'])) {
                        $consultaServicio = $consulta->consultaServicios()->find($servicio['id']);
                        if ($consultaServicio) {
                            $consultaServicio->update([
                                'servicio_id' => $servicio['servicio_id'] ?? $consultaServicio->servicio_id,
                                'detalles_servicio' => $servicio['detalles_servicio'] ?? $consultaServicio->detalles_servicio,
                                'precio_servicio' => $servicio['precio_servicio'] ?? $consultaServicio->precio_servicio,
                                'precio_producto' => $servicio['precio_producto'] ?? $consultaServicio->precio_producto,
                                'total' => $servicio['total'] ?? $consultaServicio->total
                            ]);
                        }
                    } else {
                        $consultaServicio = $consulta->consultaServicios()->create([
                            'servicio_id' => $servicio['servicio_id'],
                            'detalles_servicio' => $servicio['detalles_servicio'] ?? '',
                            'precio_servicio' => $servicio['precio_servicio'] ?? 0,
                            'precio_producto' => $servicio['precio_producto'] ?? 0,
                            'total' => $servicio['total'] ?? 0
                        ]);
                    }

                    if (!empty($servicio['productos'])) {
                        $productoid = [];
                        $productosSync = [];
                        foreach($servicio['productos'] as $producto){
                            array_push($productoid,$producto['producto_id']);
                        }
                        $productos = Productos::whereIn('id',$productoid)->get();
                        //$productosStock = [];
                        foreach ($servicio['productos'] as $producto) {
                            $productosSync[$producto['producto_id']] = [
                                'cantidad_usada' => $producto['cantidad'],
                                'tipo_venta' => $producto['tipo_venta'] ?? 'Total',
                                'subtotal' => $producto['subtotal'] ?? 0
                            ];
                            foreach($productos as $p){
                                if($p->id === $producto['producto_id']){
                                    $productodb = $p;
                                    break;
                                }
                            }
                            
                            if($producto['tipo_venta']=='Fraccionado' && $productodb){
                                $stock = (($productodb->cantidad * $productodb->stock) - $producto['cantidad'])/$productodb->cantidad;
                               //array_push($productosStock,['id'=>$productodb->id,'stock'=>$stock]);
                               $productodb->update([
                                'stock'=>$stock
                               ]);
                            }else if($producto['tipo_venta']=='Total' && $productodb){
                                $stock = $productodb->stock  - $producto['cantidad'];
                                $productodb->update([
                                'stock'=>$stock
                               ]);
                               // array_push($productosStock,['id'=>$productodb->id,'stock'=>$stock]);
                            }
                        }
                        //Productos::upsert($productosStock,['id'],['stock']);
                        $consultaServicio->productos()->sync($productosSync);
                    }
                }
            }
            DB::commit();
            $consulta->load(
                'consultaServicios.productos',
                'consultaServicios.servicio'
            );
            Event(new AppointmentEvent(['antiguo' => 'consulta','nuevo'=>'realizada'], $consulta->cita, $request->user(), 'estado'));
            return $this->Respuesta(
                new ConsultaResource($consulta),
                'Consulta actualizada correctamente',
                200,
                null
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Error al actualizar la consulta',
                500,
                $e->getMessage()
            );
        }

    }

    public function obtenerPorCita($citaId)
    {

        try {
            $this->authorize('viewAny', Consulta::class);

            $consulta = Consulta::where('cita_id', $citaId)->first();
            if (!$consulta) {
                return $this->Respuesta(
                    null,
                    'Consulta no encontrada para esta cita',
                    404,
                    null
                );
            }
            return $this->Respuesta(
                new ConsultaResource($consulta),
                'Consulta obtenida correctamente',
                200,
                null
            );
        } catch (Exception $e) {
            return $this->Respuesta(
                null,
                'Error al obtener la consulta',
                403,
                $e->getMessage()
            );
        }

    }


}

