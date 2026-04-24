<?php

namespace App\Jobs;

use App\Services\ReporteGeneralService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use App\MailjetClass;
use App\Models\User;

class ReportWeeklyJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public MailjetClass $mailjet)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reportes = new ReporteGeneralService();
    $request = (object) ['fecha_inicio'=>Carbon::now()->startOfWeek()->format('Y-m-d'),'fecha_fin'=>Carbon::now()->endOfWeek()->format('Y-m-d')];
    $data1 = $reportes->getReporte($request);
    $data2 = $reportes->getReporte2($request);
    $data3 = DB::select("
    SELECT 
        mas.nombre AS Mascota,
        users.nombre AS Dueño,
        r.nombre AS Raza,
        a.nombre AS Animal
    FROM mascotas mas
    JOIN razas r ON mas.raza_id = r.id
    JOIN animales a ON r.animal_id = a.id
    JOIN clientes cl ON mas.cliente_id = cl.id
    JOIN users ON cl.user_id = users.id
    WHERE DATE(mas.created_at) BETWEEN ? AND ?
", [$request->fecha_inicio, $request->fecha_fin]);

$data4 = DB::select('select count(productos.nombre) as counted,productos.nombre, SUM(
                CASE 
                    WHEN csp.tipo_venta = "Total" THEN (csp.subtotal - (csp.cantidad_usada * productos.precio_compra))
                    WHEN productos.cantidad > 0 THEN (csp.subtotal - ((csp.cantidad_usada / CAST(productos.cantidad AS DECIMAL(10,2))) * productos.precio_compra))
                    ELSE 0
                END
            ) AS diferencia from consulta_servicios_productos csp 
 JOIN productos ON csp.producto_id = productos.id
        JOIN consulta_servicios cs ON csp.consulta_servicio_id = cs.id
        JOIN consultas ON cs.consulta_id = consultas.id
        JOIN citas ON consultas.cita_id = citas.id
        WHERE citas.estado = "realizada" AND citas.fecha between ? and ?
        GROUP BY productos.nombre
        order by counted,diferencia desc;
',[$request->fecha_inicio,$request->fecha_fin]);
$data5 = DB::select('select servicios.nombre, count(servicios.nombre) as cantidad, SUM(coalesce(consultas.total_servicios,0)) AS sutotal from consulta_servicios cs join  
            consultas on cs.consulta_id = consultas.id
              join servicios on cs.servicio_id = servicios.id
         join citas on consultas.cita_id = citas.id
         WHERE citas.estado = "realizada" AND citas.fecha between ? and ?
            group by servicios.nombre
            order by cantidad,sutotal desc;',[$request->fecha_inicio,$request->fecha_fin]);

    $data6 = DB::select('call finanzas_semana(?, ?)', [
        $request->fecha_inicio,  
        $request->fecha_fin
    ]); 


        $data7 = DB::select('select citas.fecha,count(*) as Total_C from citas where citas.fecha between ? and ?
group by citas.fecha
order by citas.fecha asc;',[$request->fecha_inicio,  
        $request->fecha_fin]);


        $data8 = DB::select('select citas.fecha,count(*) as Total_C,citas.estado from citas where citas.fecha between ? and ? and citas.estado ="cancelada"
            group by citas.fecha
            order by citas.fecha asc;',[$request->fecha_inicio,$request->fecha_fin]);

        $data9 = DB::select('select citas.fecha,count(*) as Total_C,citas.estado from citas where citas.fecha between ? and ? and citas.estado ="en_proceso"
            group by citas.fecha
            order by citas.fecha asc;',[$request->fecha_inicio,$request->fecha_fin]); 
        $data10 = DB::select('select citas.fecha,count(*) as Total_C,citas.estado from citas where citas.fecha between ? and ? and citas.estado ="realizada"
            group by citas.fecha
            order by citas.fecha asc;',[$request->fecha_inicio,$request->fecha_fin]);  

        $data11 = DB::select('select users.nombre as cliente, mascotas.nombre as Mascota, count(citas.id) as Citas from citas
            join mascotas on citas.mascota_id = mascotas.id
            join clientes on mascotas.cliente_id = clientes.id
            join users on clientes.user_id = users.id
            where DATE(mascotas.created_at) between ? and ? and citas.fecha between ? and ? group by mascotas.nombre, users.nombre; ;',[$request->fecha_inicio,$request->fecha_fin,$request->fecha_inicio,$request->fecha_fin]);
    
        $data12 = DB::select('select users.nombre as cliente, mascotas.nombre as Mascota,count(citas.id) as Citas  from citas 
            join mascotas on citas.mascota_id = mascotas.id
            join clientes on mascotas.cliente_id = clientes.id
            join users on clientes.user_id = users.id
            where mascotas.id not in (
            select mascotas.id from citas
            join mascotas on citas.mascota_id = mascotas.id
            where DATE(mascotas.created_at) between ? and ? and citas.fecha between ? and ?)
            and DATE(mascotas.created_at) between ? and ? and 
            Date(citas.created_at ) between ? and ? and citas.fecha > ? group by mascotas.nombre, users.nombre;',[$request->fecha_inicio,$request->fecha_fin,$request->fecha_inicio,$request->fecha_fin,$request->fecha_inicio,$request->fecha_fin,$request->fecha_inicio,$request->fecha_fin,$request->fecha_fin]);
        
        $data13 = DB::select('select users.nombre as cliente, mascotas.nombre as Mascota, DATE(mascotas.created_at) as registro from mascotas
            join clientes on mascotas.cliente_id = clientes.id
            join users on clientes.user_id = users.id
            where mascotas.id not in (
            select mascotas.id from citas 
            join mascotas on citas.mascota_id = mascotas.id
            where DATE(mascotas.created_at) between ? and ? and Date(citas.created_at ) between ? and ?
            ) and DATE(mascotas.created_at) between ? and ? ',[$request->fecha_inicio,$request->fecha_fin,$request->fecha_inicio,$request->fecha_fin,$request->fecha_inicio,$request->fecha_fin]);
    
            $pdf = Pdf::loadView('ReportWeekly',['data1'=>$data1,
    'data2'=>$data2,'mascotas'=>$data3,'productos'=>$data4,
    'servicios'=>$data5,'finanzas'=>$data6,'TD_citas'=>$data7,'cancelada'=>$data8,
    'en_proceso'=>$data9,'realizada'=>$data10,'periodo'=>['inicio'=>$request->fecha_inicio,'fin'=>$request->fecha_fin],
    'mas_cita'=>$data11,'mas_cita_otra'=>$data12,'mas_sin_cita'=>$data13]);
    $pdfContent = $pdf->output();

    $administrador = User::whereHas('rol',function($q){
            $q->where('nombre','administrador');
        })->where(function($q){
            $q->where('email_verified_at','!=',null);
        })->get()->map(fn($admin)=>[
            'name'=>$admin->nombre,
            'email'=>$admin->email
        ])->values()->toArray();
    $this->mailjet->from()->to($administrador)->subject('reporte general')->htmlContent('<p>hola, este es un reporte general<p/>')->attachment(['content'=>base64_encode($pdfContent),'name'=>'archivo_muy_importante.pdf',"type"=> "application/pdf"])->send();
        
    }
    
}
