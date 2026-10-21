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
        if (Schema::hasTable('consulta_externa.formulario')) {
            Schema::dropIfExists('consulta_externa.formulario');
        }
        Schema::dropIfExists('consulta_externa.formulario');

        Schema::create('consulta_externa.formulario', function (Blueprint $table) {
            $table->id();
            $table->integer('id_persona_enfermera')->nullable();
            $table->integer('id_asignacion_horario');
            $table->integer('id_user_created');
            $table->integer('id_user_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_externa.formulario');
    }
};
