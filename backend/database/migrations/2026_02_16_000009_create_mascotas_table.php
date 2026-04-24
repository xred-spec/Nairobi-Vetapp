
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->enum('sexo', ['macho', 'hembra']);
            $table->float('peso')->nullable();
            $table->text('descripcion');
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('visibilidad', ['visible', 'invisible'/*, 'removido'*/])->default('visible');
            $table->unsignedBigInteger('raza_id');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('raza_id')->references('id')->on('razas');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};

