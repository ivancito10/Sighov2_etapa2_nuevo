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
        if (Schema::hasTable('consulta_externa.especialidad_variable')) {
            Schema::dropIfExists('consulta_externa.especialidad_variable');
        }
        Schema::dropIfExists('consulta_externa.especialidad_variable');

        Schema::create('consulta_externa.especialidad_variable', function (Blueprint $table) {
            $table->id();
            $table->integer('id_especialidad');
            $table->integer('id_variable');
            $table->integer('id_user_created');
            $table->integer('id_user_updated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_externa.especialidad_variable');
    }
};
