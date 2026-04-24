<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Trabajador extends Model
{
    //use SoftDeletes;
    use HasFactory;
    protected $table = 'trabajadores';

    protected $fillable = [
        'user_id',
    ];

    public function scopeFilter($query, array $filters)
    {
        $valorBusqueda = $filters['search'] ?? $filters['nombre'] ?? null;

        return $query->whereHas('user', function ($q) use ($valorBusqueda) {
            $q->filter(['search' => $valorBusqueda]);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function horarios(): BelongsToMany
    {
        return $this->belongsToMany(Horario::class, 'horario_trabajador', 'trabajador_id', 'horario_id')->withPivot('asignado')->withTimestamps();
    }

    public function horarioTrabajadores(): HasMany
    {
        return $this->hasMany(TrabajadorHorario::class, 'trabajador_id');
    }
}
