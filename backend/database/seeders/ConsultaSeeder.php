<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Servicios;
use App\Models\Productos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class ConsultaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Consulta::truncate();
        DB::table('consulta_servicios')->truncate();
        DB::table('consulta_servicios_productos')->truncate();
        Schema::enableForeignKeyConstraints();

        $citasRealizadas = Cita::where('estado', 'en_proceso')->get();
        $serviciosDisponibles = Servicios::all();
        $productosDisponibles = Productos::all();
    
        if ($citasRealizadas->isEmpty() || $serviciosDisponibles->isEmpty()) {
            $this->command->error('Faltan citas realizadas o servicios en la base de datos.');
            return;
        }

        foreach ($citasRealizadas as $cita) {
            $consulta = Consulta::create([
                'cita_id' => $cita->id,
                'pre_diagnostico' => 'Revisión de rutina.',
                'descripcion_consulta' => 'Paciente en observación, se aplicaron servicios preventivos.',
                'indicaciones' => 'Reposo por 24 horas.',
                'total_servicios' => 0, 
                'total_producto' => 0,
                'total' => 0,
                'created_at' => $cita->fecha,
                'updated_at' => $cita->fecha
            ]);

            $sumaServicios = 0;
            $sumaProductos = 0;
            $serviciosAzar = $serviciosDisponibles->random(rand(1, 2));

            foreach ($serviciosAzar as $servicio) {
                $precioS = rand(200, 500);
                

                $consultaServicioId = DB::table('consulta_servicios')->insertGetId([
                    'detalles_servicio' => 'Aplicación estándar de ' . $servicio->nombre,
                    'precio_servicio' => $precioS,
                    'precio_producto' => 0, 
                    'total' => $precioS,
                    'consulta_id' => $consulta->id,
                    'servicio_id' => $servicio->id,
                    'created_at' => $cita->fecha,
                    'updated_at' => $cita->fecha
                ]);

                $sumaServicios += $precioS;
                $randProductos = rand(1,5);
                
                if (rand(0, 1) && !$productosDisponibles->isEmpty()) {
                    $subtotalP=0;
                    for($i=0;$i<$randProductos;$i++){
                        $producto = $productosDisponibles->random();
                        $cantidad = rand(1, 3);
                        $subtotalP = $producto->precio_venta * $cantidad;
                        DB::table('consulta_servicios_productos')->insert([
                        'cantidad_usada' => $cantidad,
                        'consulta_servicio_id' => $consultaServicioId,
                        'tipo_venta' => 'Total',
                        'producto_id' => $producto->id,
                        'subtotal' => $subtotalP,
                        'created_at' => $cita->fecha,
                        'updated_at' => $cita->fecha
                    ]);
                    }
                    DB::table('consulta_servicios')->where('id', $consultaServicioId)->update([
                        'precio_producto' => $subtotalP,
                        'total' => $precioS + $subtotalP
                    ]);
                    $sumaProductos += $subtotalP;
                }
            }
            $consulta->update([
                'total_servicios' => $sumaServicios,
                'total_producto' => $sumaProductos,
                'total' => $sumaServicios + $sumaProductos
            ]);
            $cita = Cita::where('id',$cita->id)->update(['estado'=>'realizada']);
        }

        $this->command->info('Consultas y detalles de servicios/productos generados.');
    }
}