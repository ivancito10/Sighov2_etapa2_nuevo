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
        // Nota: Asegúrate de que el esquema 'consulta_externa' exista en tu DB
        Schema::create('consulta_externa.receta', function (Blueprint $table) {
            // Identificador principal (bigserial)
            $table->id();

            // Campos obligatorios ([v] en la imagen)
            $table->integer('nro_receta');
            $table->date('fecha_transcripcion');
            $table->boolean('manual');
            $table->integer('id_tipo_consulta');
            $table->integer('id_especialista');
            $table->integer('id_persona');
            $table->boolean('estado')->default(true);
            $table->string('clave_unica', 20);

            // Campos opcionales (vacíos en Not Null)
            $table->date('fecha_entrega')->nullable();
            $table->integer('id_historia_clinica')->nullable();
            $table->bigInteger('id_user_created')->nullable();
            $table->bigInteger('id_user_updated')->nullable();

            // Auditoría y estados
            $table->integer('id_usuario_dispensa')->nullable();
            $table->timestamp('fecha_dispensado')->nullable();
            $table->integer('id_usuario_anula')->nullable();
            $table->timestamp('fecha_anulado')->nullable();
            $table->integer('id_estado_validacion')->nullable();
            $table->integer('id_persona_especialista')->nullable();
            $table->string('motivo_anulacion')->nullable();
            $table->integer('id_persona_transcribe')->nullable();
            $table->string('observacion_quirofano')->nullable();
            $table->integer('nro_receta_manual')->nullable();
            $table->timestamp('fecha_caducidad')->nullable();
            $table->date('fecha_proxima_consulta')->nullable();

            // Timestamps (created_at, updated_at)
            // Se usa timestamp(0) según la imagen
            $table->timestamps(0);

            /* Si 'dias_tratamiento' no está en la imagen pero sí en tu fillable, 
               lo agrego aquí como entero opcional:
            */
            $table->integer('dias_tratamiento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('consulta_externa.receta');
    }
};
