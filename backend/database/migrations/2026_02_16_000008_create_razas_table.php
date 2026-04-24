
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->enum('visibilidad', ['visible', 'invisible'/*, 'removido'*/])->default('visible');
            $table->unsignedBigInteger('animal_id');
            $table->foreign('animal_id')->references('id')->on('animales');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razas');
    }
};

