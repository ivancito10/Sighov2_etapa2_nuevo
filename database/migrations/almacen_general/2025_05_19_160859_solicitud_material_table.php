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
        Schema::dropIfExists('almacen_general.solicitud_material');

        Schema::create('almacen_general.solicitud_material', function (Blueprint $table){
            $table->id();
            $table->integer('id_material');
            $table->foreign('id_material')->references('id')->on('almacen_general.material');
            $table->integer('id_solicitud');
            $table->foreign('id_solicitud')->references('id')->on('almacen_general.solicitud');
            $table->integer('cantidad');
            $table->integer('cantidad_solicitada');
            $table->integer('cantidad_aprobada');
            $table->integer('cantidad_entregada');
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
        Schema::dropIfExists('almacen_general.solicitud_material');
    }
};
