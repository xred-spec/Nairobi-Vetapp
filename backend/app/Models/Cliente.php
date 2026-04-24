<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Cliente extends Model
{
    use /*SoftDeletes,*/ HasFactory;
    protected $table = 'clientes';

    protected $fillable = [
        'municipio',
        'colonia',
        'codigo_postal',
        'calle',
        'numero_exterior',
        'user_id',
    ];

    public function scopeFilter($query, array $filters)
    {

        $search = $filters['search'] ?? $filters['nombre'] ?? null;

        $query->when($search, function ($query, $value) {
            $query->whereHas('user', function ($q) use ($value) {
                $q->filter(['search' => $value]);
            });
        })->when($filters['municipio'] ?? null, function ($query, $municipio) {
            $query->where('municipio', 'like', '%' . $municipio . '%');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'cliente_id', 'id');
    }
}

