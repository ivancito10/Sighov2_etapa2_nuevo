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
        Schema::dropIfExists('almacen_general.ingreso_material');

        Schema::create('almacen_general.ingreso_material', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ingreso');
            $table->foreign('id_ingreso')->references('id')->on('almacen_general.ingreso');
            $table->integer('id_material');
            $table->foreign('id_material')->references('id')->on('almacen_general.material');
            $table->decimal('precio_unitario', 10, 2);
            $table->date('fecha_vencimiento');
            $table->integer('cantidad');
            $table->decimal('precio_total', 10, 2);
            $table->integer('nro_lote');
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
        Schema::dropIfExists('almacen_general.ingreso_material');
    }
};
