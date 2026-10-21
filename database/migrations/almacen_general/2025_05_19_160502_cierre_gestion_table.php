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
        Schema::dropIfExists('almacen_general.cierre_gestion');

        Schema::create('almacen_general.cierre_gestion', function (Blueprint $table){
            $table->id();
            $table->integer('id_material');
            $table->foreign('id_material')->references('id')->on('almacen_general.material');
            $table->integer('cantidad_fisica');
            $table->integer('cantidad_sistema');
            $table->integer('diferencia');
            $table->date('fecha_vencimiento');
            $table->integer('cantidad_baja');
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
        Schema::dropIfExists('almacen_general.cierre_gestion');
    }
};
