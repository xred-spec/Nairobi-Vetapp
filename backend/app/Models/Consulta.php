<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultaServicio;
use App\Models\ReporteConsulta;
// la clase de cita
use App\Models\Cita;

class Consulta extends Model
{
    protected $fillable = [
        'pre_diagnostico',
        'descripcion_consulta',
        'indicaciones',
        'cita_id',
        'total_servicios',
        'total_producto',
        'total',
    ];

    public function cita()
    {
        // falta la clase de cita
        return $this->belongsTo(Cita::class);
    }

    public function consultaServicios()
    {
        return $this->hasMany(ConsultaServicio::class);
    }

    public function reporteConsulta()
    {
        return $this->hasOne(ReporteConsulta::class);
    }
}
