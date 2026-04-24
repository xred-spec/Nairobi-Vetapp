<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animales extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'animales';

    protected $fillable = [
        'nombre',
        'visibilidad'
    ];
    public function razas():HasMany{
        return $this->hasMany(Raza::class,'id_animal','id');
    }

    public function scopeFilter($query,$request):Builder{
        return $query
        ->when($request->nombre,function($query,$nombre){
            $query->where('nombre','like',"%$nombre%");
        })
        ->when($request->visibilidad,function($query,$visibilidad){
            $query->where('visibilidad',$visibilidad);
        });
    }
}
