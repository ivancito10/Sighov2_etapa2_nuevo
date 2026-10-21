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
        if (Schema::hasTable('almacen_general.solicitud_material')) {
            Schema::dropIfExists('almacen_general.solicitud_material');
        }

        Schema::dropIfExists('almacen_general.solicitud');

        Schema::create('almacen_general.solicitud', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_solicitud');
            $table->integer('id_unidad');
            $table->integer('id_persona_solicitante');
            $table->date('fecha_solicitud');
            $table->integer('id_persona_autoriza');
            $table->date('fecha_entrega');
            $table->date('fecha_anulacion');
            $table->string('estado_solicitud');
            $table->boolean('estado')->default('true');
            $table->integer('id_persona_entrega');
            $table->integer('id_user_created');
            $table->integer('id_user_updated')->nulleable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacen_general.solicitud');
    }
};
