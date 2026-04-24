
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horario_trabajador', function (Blueprint $table) {
            $table->id();
            $table->enum('asignado', ['asignado', 'desasignado'])->default('asignado');
            $table->unsignedBigInteger('horario_id');
            $table->unsignedBigInteger('trabajador_id');
            $table->foreign('horario_id')->references('id')->on('horarios');
            $table->foreign('trabajador_id')->references('id')->on('trabajadores');
            $table->unique(['horario_id', 'trabajador_id'], 'unique_horario_trabajador');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horario_trabajador');
    }
};

