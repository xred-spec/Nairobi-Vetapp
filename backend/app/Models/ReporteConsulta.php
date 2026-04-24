<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteConsulta extends Model
{
    protected $fillable = [
        'consulta_id',
        'precio_servicios',
        'precio_productos',
        'precio_total'
    ];

    public function consulta(){
        return $this->belongsTo(Consulta::class);
    }
}
