<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Horario extends Model
{
    use HasFactory;
    protected $table ='horarios';

    public function trabajadores():BelongsToMany{
        return $this->belongsToMany(Trabajador::class,'horario_trabajador','horario_id','trabajador_id')->withPivot('asignado')->withTimestamps();
    }
    public function horariotrabajador():HasMany{
        return $this->hasMany(HorarioTrabajador::class);
    }
}
