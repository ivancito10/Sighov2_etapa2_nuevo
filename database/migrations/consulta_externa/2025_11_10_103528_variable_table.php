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
        if (Schema::hasTable('consulta_externa.variable')) {
            Schema::dropIfExists('consulta_externa.variable');
        }
        Schema::dropIfExists('consulta_externa.variable');

        Schema::create('consulta_externa.variable', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo');
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('consulta_externa.variable');
    }
};
