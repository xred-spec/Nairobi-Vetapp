<?php

namespace App\Jobs;

use App\Mail\ReportsMail;
use App\Services\ReporteGeneralService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\MailjetClass;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public MailjetClass $mailjet)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $reportes = new ReporteGeneralService();
        $request = (object) ['fecha_inicio'=>Carbon::now(),'fecha_fin'=>Carbon::now()];
        $data1 = $reportes->getReporte($request);
        $data2 = $reportes->getReporte2($request);
        $data3 = DB::select('select mas.nombre as Mascota,users.nombre as Dueño, r.nombre as Raza, a.nombre as Animal from
            mascotas mas
            join razas r on mas.raza_id = r.id
            join animales a on r.animal_id = a.id
            join clientes cl on mas.cliente_id = cl.id
            join users on  cl.user_id = users.id
            where Date (mas.created_at) = CURDATE()');

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
        WHERE citas.estado = "realizada" AND citas.fecha =CURDATE()
        GROUP BY productos.nombre
        order by counted,diferencia desc;
');

        $data5 = DB::select('select servicios.nombre, count(servicios.nombre) as cantidad, SUM(coalesce(consultas.total_servicios,0)) AS sutotal from consulta_servicios cs join  
            consultas on cs.consulta_id = consultas.id
              join servicios on cs.servicio_id = servicios.id
         join citas on consultas.cita_id = citas.id
         WHERE citas.estado = "realizada" AND citas.fecha =CURDATE()
            group by servicios.nombre
            order by cantidad,sutotal desc;');
        $data6 = DB::select('select nombre,marca,stock from productos where stock<10;');
   
        
        $pdf = Pdf::loadView('ReportsPDF',['data1'=>$data1,'data2'=>$data2,'mascotas'=>$data3,'productos'=>$data4,'servicios'=>$data5,'productostock'=>$data6]);

        $pdfContent = $pdf->output();

        $administrador = User::whereHas('rol',function($q){
            $q->where('nombre','administrador');
        })->where(function($q){
            $q->where('email_verified_at','!=',null);
        })->get()->map(fn($admin)=>[
            'name'=>$admin->nombre,
            'email'=>$admin->email
        ])->values()->toArray();
       // dd($administrador);
        $this->mailjet->from()->to($administrador)->subject('reporte general')->htmlContent('<p>hola, este es un reporte general<p/>')->attachment(['content'=>base64_encode($pdfContent),'name'=>'archivo_muy_importante.pdf',"type"=> "application/pdf"])->send();
        
    }
}
