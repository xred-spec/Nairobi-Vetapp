<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ConsultaServicio;

class Productos extends Model
{ 
    use HasFactory, SoftDeletes;
    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'stock',
        'precio_compra',
        'precio_venta',
        'cantidad',
        'marca',
        'visibilidad',
        'medida'
    ];
    
    /**
 * Scope para filtrar productos dinámicamente
 */
public function scopeFilter($query, $request): Builder 
{
    return $query->whereNull('deleted_at') 
        ->when($request->nombre, function($q, $nombre) {
            $q->where('nombre', 'like', '%' . $nombre . '%');
        })
        ->when($request->marca, function($q, $marca) {
            $q->where('marca', 'like', '%' . $marca . '%');
        })
        ->when($request->visibilidad, function($q, $visibilidad) {
            $q->where('visibilidad', $visibilidad);
        })
        ->when($request->filled('precio_venta'), function($q) use ($request) {
            $q->where('precio_venta', $request->precio_venta);
        })
        ->when($request->filled('stock'), function($q) use ($request) {
            $q->where('stock', $request->stock);
        })
        ->when($request->filled('cantidad'), function($q) use ($request) {
            $q->where('cantidad', $request->cantidad);
        })
        ->when($request->medida, function($q, $medida) {
            $q->where('medida', $medida);
        });
}

    public function consultaServicios()
    {
        return $this->belongsToMany(ConsultaServicio::class, 'consulta_servicios_productos', 'producto_id', 'consulta_servicio_id')
                    ->withPivot('cantidad_usada', 'tipo_venta', 'subtotal')
                    ->withTimestamps();
    }
}

