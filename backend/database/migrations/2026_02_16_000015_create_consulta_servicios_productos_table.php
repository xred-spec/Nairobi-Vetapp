<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consulta_servicios_productos', function (Blueprint $table) {
            $table->id();
            $table->double('cantidad_usada');
            $table->unsignedBigInteger('consulta_servicio_id');
            $table->enum('tipo_venta', ['Total', 'Fraccionado']);
            $table->unsignedBigInteger('producto_id');
            $table->float('subtotal');
            $table->foreign('consulta_servicio_id')->references('id')->on('consulta_servicios');
            $table->foreign('producto_id')->references('id')->on('productos');
            //$table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta_servicios_productos');
    }
};
