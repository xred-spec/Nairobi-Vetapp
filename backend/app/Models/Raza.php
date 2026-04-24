<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Animales;
use App\Models\Mascota;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Raza extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'razas';

    protected $fillable = [
        'nombre',
        'animal_id',
        'visibilidad'
    ];

    public function scopeFilter($query, $request)
    {
        return $query->whereNull('deleted_at')
            ->when($request->nombre, function ($q, $nombre) {
                $q->where('nombre', 'like', "%$nombre%");
            })
            ->when($request->filled('visibilidad'), function ($q) use ($request) {
                $q->where('visibilidad', $request->visibilidad);
            })
            ->when($request->animal_id, function ($q, $animalId) {
                $q->where('animal_id', $animalId);
            });
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animales::class);
    }
}
