<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Servicios extends Model
{
    use SoftDeletes,HasFactory;
    
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'visibilidad'
    ];

    public function scopeFilter($query,$request):Builder{
      return $query
                 ->when($request->nombre,function($q) use ($request){
                     $q->where('nombre','like','%'.$request->nombre.'%');
                 })
                 ->when($request->visibilidad,function($q) use ($request){
                     $q->where('visibilidad',$request->visibilidad);
                 });
    }
}
