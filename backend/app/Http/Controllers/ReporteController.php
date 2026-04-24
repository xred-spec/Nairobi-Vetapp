<?php

namespace App\Http\Controllers;
use App\Services\ReporteGeneralService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
class ReporteController extends Controller
{
    protected $service;
    public function __construct(ReporteGeneralService $service)
    {
        $this->service = $service;
    }
    use ApiResponse;

    public function getReporte(Request $request)
    {
        $data = $this->service->getReporte($request);
        return $this->Respuesta(
            $data,
            'Reporte obtenido correctamente',
            200
        );

    }

    public function getReporte2(Request $request)
    {
        $data = $this->service->getReporte2($request);
        return $this->Respuesta($data, 'Reporte obtenido correctamente', 200);
    }

    public function distribucionTipo(Request $request)
    {
        $fecha = $request->input('fecha', now()->format('Y-m-d'));
        try {
            $stmt = DB::getPdo()->prepare('call sp_Main_Distribucion(?)');
            $stmt->execute([$fecha]);

            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->Respuesta($res, 
            'Reporte obtenido correctamente', 
            200);
        } catch (\Exception $e) {
            return $this->Respuesta(
                null, 
                'Error al obtener reporte: ' . $e->getMessage(), 
                500);
        }

    }
}
