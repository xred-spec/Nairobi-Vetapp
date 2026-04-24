<?php


namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
class ReporteGeneralService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getReporte($request)
    {
        if (!$request->fecha_fin || !$request->fecha_inicio) {
            throw ValidationException::withMessages([
                'fecha_inicio' => 'El rango de fechas no puede ser mayor a 6 meses',
            ]);
        }
        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);

        if($inicio>$fin){
            throw ValidationException::withMessages([
                'rango' => 'El rango de inicio no puede ser mayor que la del final',
            ]);
        }

        if ($inicio->diffInMonths($fin) > 6) {
            throw ValidationException::withMessages([
                'fecha_inicio' => 'El rango de fechas no puede ser mayor a 6 meses',
            ]);
        }

        $stmt = DB::getPdo()->prepare('call sp_Reporte_General(?,?)');
        $stmt->execute([
            $inicio->format('Y-m-d'),
            $fin->format('Y-m-d')
        ]);

        $general = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt->nextRowset();

        $trabajadores = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $trabajadores = array_map(function ($item) {
            $item['cantidad_citas'] = (int) $item['cantidad_citas'];
            $item['completadas'] = (int) $item['completadas'];
            return $item;
        }, $trabajadores);

        $stmt->closeCursor();

        return [
            'general' => $general[0] ?? null,
            'trabajadores' => $trabajadores
        ];
    }

    public function getReporte2($request){
        
        if (!$request->fecha_fin || !$request->fecha_inicio) {
            throw ValidationException::withMessages([
                'fecha_inicio' => 'El rango de fechas no puede ser mayor a 6 meses',
            ]);
        }

        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);

        if($inicio>$fin){
            throw ValidationException::withMessages([
                'rango' => 'El rango de inicio no puede ser mayor que la del final',
            ]);
        }

        $fin->format('Y-m-d');
        if ($inicio->diffInMonths($fin) > 6) {
            throw ValidationException::withMessages([
                'fecha_inicio' => 'El rango de fechas no puede ser mayor a 6 meses',
            ]);
        }
        $ingresos_promedios = DB::select('call ingresos_promedios(?,?)',[ $inicio->format('Y-m-d'), $fin->format('Y-m-d')]);
        $finanzas_tipo_cita = DB::select('call finanzas_tipo_cita(?,?)',[$inicio->format('Y-m-d'), $fin->format('Y-m-d')]);
        $finanzas_por_veterinario = DB::select('call finanzas_por_veterinario(?,?)',[$inicio->format('Y-m-d'), $fin->format('Y-m-d')]);
         return [
            'ingresos_promedios' => $ingresos_promedios,
            'finanzas_tipo_cita'=>$finanzas_tipo_cita,
            'finanzas_por_veterinario'=> $finanzas_por_veterinario
         ];
         
    }
}
