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
        Schema::dropIfExists('almacen_general.movimiento');

        Schema::create('almacen_general.movimiento', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unidad_solicitante');
            $table->integer('nro_documento');
            $table->integer('id_institucion_externa');
            $table->foreign('id_institucion_externa')->references('id')->on('farmacia.institucion_externa');
            $table->integer('cantidad_ingreso');
            $table->integer('cantidad_salida');
            $table->integer('saldo');
            $table->integer('id_corresponde');
            $table->integer('id_identificador_tabla');
            $table->string('estado_validacion');
            $table->integer('id_material');
            $table->foreign('id_material')->references('id')->on('almacen_general.material');
            $table->boolean('cierre_gestion');
            $table->decimal('precio_unitario', 10, 2);
            $table->string('observacion');
            $table->boolean('estado')->default('true');
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
        Schema::dropIfExists('almacen_general.movimiento');
    }
};
