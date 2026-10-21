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
        //
        Schema::table('consulta_externa.cie_10', function (Blueprint $table) {
            $table->boolean('estado')->default('true');
            $table->integer('id_user_created')->nullable();
            $table->integer('id_user_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consulta_externa.cie_10', function (Blueprint $table) {
            // En el down, eliminamos las columnas agregadas para poder revertir
            $table->dropColumn(['estado', 'id_user_created', 'id_user_updated', 'created_at', 'updated_at']);
        });
    }
};
