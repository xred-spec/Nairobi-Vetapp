<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultaServicio;
use App\Models\Productos;

class ConsultaServicioProductos extends Model
{
    protected $table = 'consulta_servicios_productos';
    
    protected $fillable = [
        'consulta_servicio_id',
        'producto_id',
        'cantidad_usada',
        'tipo_venta',
        'subtotal',
    ];

    public function consulta_servicio(){
        return $this->belongsTo(ConsultaServicio::class);
    }

    /**
     * Relación muchos a muchos inversa con ConsultaServicio
     */
    public function consultaServicios()
    {
        return $this->belongsToMany(ConsultaServicio::class, 'consulta_servicios_productos')
                    ->withPivot('cantidad_usada', 'tipo_venta', 'subtotal')
                    ->withTimestamps();
    }

    public function producto(){
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
