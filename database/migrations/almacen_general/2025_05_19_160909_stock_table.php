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
        Schema::dropIfExists('almacen_general.stock');

        Schema::create('almacen_general.stock', function (Blueprint $table) {
            $table->id();
            $table->integer('id_material');
            $table->foreign('id_material')->references('id')->on('almacen_general.material');
            $table->integer('nro_lote');
            $table->integer('cantidad_inicial');
            $table->integer('stock_actualizado');
            $table->decimal('precio_unitario', 10, 2);
            $table->boolean('ajuste_contable');
            $table->date('fecha_vencimiento');
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
        Schema::dropIfExists('almacen_general.stock');
    }
};
