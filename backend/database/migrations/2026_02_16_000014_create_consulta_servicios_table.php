<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consulta_servicios', function (Blueprint $table) {
            $table->id();
            $table->text('detalles_servicio')->nullable();
            $table->double('precio_servicio');
            $table->double('precio_producto');
            $table->double('total');
            $table->unsignedBigInteger('consulta_id');
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('consulta_id')->references('id')->on('consultas');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            //$table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta_servicios');
    }
};
