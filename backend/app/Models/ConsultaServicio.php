<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Servicios;
use App\Models\Consulta;
use App\Models\ConsultaServicioProductos;
use App\Models\Productos;

class ConsultaServicio extends Model
{
    protected $fillable = [
        'servicio_id',
        'consulta_id',
        'detalles_servicio',
        'precio_servicio',
        'precio_producto',
        'total'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicios::class);
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }


    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'consulta_servicios_productos', 'consulta_servicio_id', 'producto_id')
                    ->withPivot('cantidad_usada', 'tipo_venta', 'subtotal')
                    ->withTimestamps();
    }

    public function consultaServicioProductos()
    {
        return $this->hasMany(ConsultaServicioProductos::class);
    }
}

