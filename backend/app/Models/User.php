<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'password',
        'estado',
        'rol_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('telefono', 'like', '%' . $search . '%');
            });
        })->when($filters['rol'] ?? null, function ($query, $rol) {
            $query->whereHas('rol', function ($q) use ($rol) {
                $q->where('nombre', $rol);
            });
        })->when($filters['rol_id'] ?? null, function ($query, $rolId) {
            $query->where('rol_id', $rolId);
        });
    }

    /**
     * Relación con el rol del usuario
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'id');
    }

    /**
     * Relación con el trabajador (si es trabajador)
     */
    public function trabajador(): HasOne
    {
        return $this->hasOne(Trabajador::class, 'user_id', 'id');
    }

    /**
     * Relación con el cliente (si es cliente)
     */
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'user_id', 'id');
    }


    public function hasAnyRol(array $nombres): bool
    {
        foreach ($nombres as $nombre) {
            if ($this->hasRol($nombre)) {
                return true;
            }
        }
        return false;
    }


    public function hasRol($nombre): bool
    {
        return $this->rol && $this->rol->nombre === $nombre;
    }
}
