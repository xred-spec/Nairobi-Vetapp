<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_consultas', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio_servicios', 10, 2)->default(0);
            $table->decimal('precio_productos', 10, 2)->default(0);
            $table->decimal('precio_total', 10, 2)->default(0);
            $table->unsignedBigInteger('consulta_id')->unique();
            $table->foreign('consulta_id')->references('id')->on('consultas')->onDelete('cascade')->onUpdate('cascade');
            //$table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_consultas');
    }
};
