<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->text('pre_diagnostico')->nullable();
            $table->text('descripcion_consulta')->nullable();
            $table->text('indicaciones')->nullable();
            $table->unsignedBigInteger('cita_id');
            $table->double('total_servicios')->nullable();
            $table->double('total_producto')->nullable();
            $table->double('total')->nullable();
            $table->foreign('cita_id')->references('id')->on('citas');
            //$table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
