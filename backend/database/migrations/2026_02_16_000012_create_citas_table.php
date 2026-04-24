<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['agendada', 'realizada', 'cancelada', 'en_proceso'])->default('agendada');
            $table->date('fecha');
            $table->enum('tipo', ['medico', 'estetico', 'mixto']);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('mascota_id');
            $table->unsignedBigInteger('horario_trabajador_id');
            $table->foreign('mascota_id')->references('id')->on('mascotas');
            $table->foreign('horario_trabajador_id')->references('id')->on('horario_trabajador');
            //$table->softDeletes();
            $table->timestamps();
           // $table->unique(['horario_trabajador_id', 'fecha'], 'unique_cita_trabajador_fecha');
        });

        // Restricción para evitar citas duplicadas en el mismo horario para el mismo trabajador
        // DB::statement('ALTER TABLE citas ADD CONSTRAINT unique_cita_trabajador_fecha UNIQUE (horario_trabajador_id, fecha)');
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
