<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            //$table->enum('tipo',['medico','esterico','mixto']);
            $table->text('descripcion')->nullable();
            $table->enum('visibilidad', ['visible', 'invisible'/*, 'removido'*/])->default('visible');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
