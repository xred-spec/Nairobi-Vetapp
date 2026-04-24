<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('marca');
            $table->float('stock');
            $table->float('precio_compra');
            $table->float('precio_venta');
            $table->float('cantidad');
            $table->enum('visibilidad', ['visible', 'invisible'/*, 'removido'*/])->default('visible');
            $table->enum('medida', ['gramos', 'kilos', 'mililitros', 'litros', 'unidad']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
