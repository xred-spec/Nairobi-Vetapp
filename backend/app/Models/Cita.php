<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cita extends Model
{
    protected $table = 'citas';
    protected $fillable = [
        'fecha',
        'mascota_id',
        'estado',
        'tipo',
        'descripcion',
        'horario_trabajador_id',
        
    ];

    protected $casts = [
        'fecha'=>'date'
    ];
    public function mascota():BelongsTo{
        return $this->belongsTo(Mascota::class,'mascota_id')->withTrashed();
    }

    public function horarioTrabajador():BelongsTo{
        return $this->belongsTo(TrabajadorHorario::class,'horario_trabajador_id');
    }

    public function consulta():HasOne{
        return $this->hasOne(Consulta::class);
    }

    public function scopeFilter($query, $request){
       return $query->
        when($request->estado,function($q) use($request){
            $q->where('estado',$request->estado);
        })->when($request->fecha,function($q) use($request){
            $q->where('fecha',$request->fecha);
        })->when($request->tipo,function($q) use($request){
            $q->where('tipo',$request->tipo);
        })->when($request->mascota_id,function($q) use($request){
            $q->where('mascota_id',$request->mascota_id);
        })->when($request->horario_trabajador_id,function($q) use($request){
            $q->where('horario_trabajador_id',$request->horario_trabajador_id);
        })->orderBy('estado');
    }
}
