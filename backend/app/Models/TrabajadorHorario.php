<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TrabajadorHorario extends Model
{
    protected $table = 'horario_trabajador';

    protected $fillable = [
        'asignado',
        'horario_id',
        'trabajador_id',
    ];

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    public function trabajador(){
        return $this->belongsTo(Trabajador::class,'trabajador_id','id');
    }
}
