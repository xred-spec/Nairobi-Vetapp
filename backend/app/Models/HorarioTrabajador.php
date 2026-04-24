<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class HorarioTrabajador extends Pivot
{
    use HasFactory;
    protected $table = 'horario_trabajador';

    protected $fillable = ['horario_id', 'trabajador_id', 'asignado'];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'horario_trabajador_id');
    }
    public function horario():BelongsTo{
        return $this->belongsTo(Horario::class);
    }
    public function trabajador():BelongsTo{
        return $this->belongsTo(Trabajador::class);
    }
}