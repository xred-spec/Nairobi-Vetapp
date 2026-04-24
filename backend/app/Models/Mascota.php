<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mascota extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'mascotas';

    protected $fillable = [
        'nombre',
        'sexo',
        'peso',
        'descripcion',
        'fecha_nacimiento',
        'visibilidad',
        'raza_id',
        'cliente_id'
    ];

    public function scopeFilter($query, $request)
    {
        return $query->whereNull('deleted_at')
            ->when($request->nombre, function ($q, $nombre) {
                $q->where('nombre', 'like', '%' . $nombre . '%');
            })
            ->when($request->sexo, function ($q, $sexo) {
                $q->where('sexo', $sexo);
            })
            ->when($request->peso, function ($q, $peso) {
                $q->where('peso', $peso);
            })
            ->when($request->fecha_nacimiento, function ($q, $fecha) {
                $q->whereDate('fecha_nacimiento', $fecha);
            })
            ->when($request->animal_id, function ($q, $animalId) {
                $q->whereHas('raza.animal', function ($sq) use ($animalId) {
                    $sq->where('id', $animalId);
                });
            })
            ->when($request->raza_id, function ($q, $razaId) {
                $q->where('raza_id', $razaId);
            })
            ->when($request->cliente_id, function ($q, $clienteId) {
                $q->where('cliente_id', $clienteId);
            })
            ->when($request->dueno, function ($q, $dueno) {
                $q->whereHas('cliente.user', function ($sq) use ($dueno) {
                    $sq->where('nombre', 'like', '%' . $dueno . '%');
                });
            })
            ->when($request->visibilidad, function ($q, $visibilidad) {
                $q->where('visibilidad', $visibilidad);
            });
    }

    public function raza()
    {
        return $this->belongsTo(Raza::class, 'raza_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'mascota_id', 'id');
    }
}
