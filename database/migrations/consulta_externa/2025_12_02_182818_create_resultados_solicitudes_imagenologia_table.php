<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consulta_externa.resultados_solicitudes_imagenologia', function (Blueprint $table) {
            $table->id();
            $table->string('observaciones')->nullable();
            $table->integer('id_solicitud');
            $table->string('nombre_placa');
            $table->integer('id_user_created')->nullable();
            $table->integer('id_user_updated')->nullable();
            $table->timestamps();
            $table->foreign('id_solicitud')
                ->references('id')
                ->on('consulta_externa.sol_imagenologia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_externa.resultados_solicitudes_imagenologia');
    }
};
