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
        if (Schema::hasTable('almacen_general.factura')) {
            Schema::dropIfExists('almacen_general.factura');
        }

        if (Schema::hasTable('almacen_general.ingreso_material')) {
            Schema::dropIfExists('almacen_general.ingreso_material');
        }

        Schema::dropIfExists('almacen_general.ingreso');

        Schema::create('almacen_general.ingreso', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_lote');
            $table->integer('id_tipo_proceso');
            $table->foreign('id_tipo_proceso')->references('id')->on('farmacia.tipo_proceso');
            $table->integer('id_institucion_externa');
            $table->foreign('id_institucion_externa')->references('id')->on('farmacia.institucion_externa');
            $table->text('responsables');
            $table->integer('codigo_ingreso');
            $table->integer('codigo_cuce');
            $table->date('fecha_contrato');
            $table->date('fecha_recepcion');
            $table->time('hora_recepecion');
            $table->decimal('monto_total', 10, 2);
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
        Schema::dropIfExists('almacen_general.ingreso');
    }
};
