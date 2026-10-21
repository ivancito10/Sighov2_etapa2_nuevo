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
        Schema::create('administracion.api_replica_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sistema_destino'); // Nombre del sistema de destino
            $table->string('nombre_tabla'); // Nombre de la tabla afectada
            $table->json('datos_enviados'); // Datos enviados en la réplica
            $table->integer('codigo_respuesta'); // Código de respuesta HTTP
            $table->json('respuesta_servidor')->nullable(); // Respuesta de la API
            $table->text('mensaje_error')->nullable(); // Mensaje de error si la réplica falla
            $table->timestamp('fecha_replicacion')->useCurrent(); // Fecha de réplica
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administracion.api_replica_logs');
    }
};
