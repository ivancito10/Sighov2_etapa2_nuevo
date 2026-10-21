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
       // IMPORTANTE: Cambiar a esquema administracion


        Schema::create('administracion.seguimiento_obtencion_usuario', function (Blueprint $table) {
            $table->id();

            $table->string('matricula_seguro', 50);
            $table->string('numero_ci', 20);
            $table->string('complemento_ci', 10)->nullable();
            $table->date('fecha_nacimiento');

            $table->string('ip_address', 45);
            $table->string('device_name')->nullable();
            $table->text('captcha_token');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administracion.seguimiento_obtencion_usuario');
    }
};
