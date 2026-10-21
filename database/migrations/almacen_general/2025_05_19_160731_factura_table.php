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
        Schema::dropIfExists('almacen_general.factura');

        Schema::create('almacen_general.factura', function (Blueprint $table){
            $table->id();
            $table->integer('id_ingreso');
            $table->foreign('id_ingreso')->references('id')->on('almacen_general.ingreso');
            $table->integer('nro_factura');
            $table->date('fecha');
            $table->decimal('monto_total_factura', 10, 2);
            /*$table->integer('id_institucion_externa');
            $table->foreign('id_institucion_externa')->references('id')->on('farmacia.institucion_externa');*/
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
        Schema::dropIfExists('almacen_general.factura');
    }
};
