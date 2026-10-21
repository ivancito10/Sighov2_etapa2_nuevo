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
        if (Schema::hasTable('laboratorio.solicitud_examen')) {
            Schema::dropIfExists('laboratorio.solicitud_examen');
        }

        Schema::dropIfExists('laboratorio.solicitudes');

        Schema::create('laboratorio.solicitudes', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_solicitud');
            $table->integer('id_historia_clinica');
            $table->foreign('id_historia_clinica')->references('id')->on('consulta_externa.historia_clinica');
            $table->integer('id_persona_asegurado');
            $table->integer('id_persona_solicitante');
            $table->string('observacion_solicitud');
            $table->integer('id_tipo_atencion');
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
        Schema::dropIfExists('laboratorio.solicitudes');
    }
};
