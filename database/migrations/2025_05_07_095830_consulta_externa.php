<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {   
        DB::statement('CREATE SCHEMA IF NOT EXISTS consulta_externa');

        // Crear tablas
        Schema::create('consulta_externa.tipo_consulta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado');
            $table->timestamps();
        });

        Schema::create('consulta_externa.historia_clinica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_agenda')->nullable();
            $table->unsignedBigInteger('id_tipo_consulta');
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_especialidad')->nullable();
            $table->boolean('reconsulta')->default(false);
            $table->unsignedBigInteger('id_historia_clinica')->nullable(); // recursivo
            $table->boolean('asistencia');  //agregado recien
            $table->timestamp('fecha_hora');
            $table->unsignedBigInteger('id_especialista')->nullable();
            // Datos subjetivo
            $table->text('motivo_consulta');
            $table->text('enfermedad_actual');
            $table->text('objetivo');
            $table->boolean('estado_consulta'); //agregado recien
            //$table->text('diagnostico');
            $table->text('descriptivo');
            $table->text('plan_descriptivo')->nullable();
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.sol_imagenologia', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_solicitud');
            $table->text('indicaciones');
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.estudio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('id_tipo_img')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('consulta_externa.tipo_img', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('consulta_externa.sol_fisioterapia', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_solicitud');
            $table->integer('nro_sesion');
            $table->text('indicaciones');
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.referencia', function (Blueprint $table) {
            $table->id();
            $table->text('indicaciones');
            $table->unsignedBigInteger('id_especialidad');
            $table->integer('nro_referencia');
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.receta', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_receta');
            $table->date('fecha_transcripcion');
            $table->date('fecha_entrega')->nullable();
            $table->text('manual');
            $table->integer('id_tipo_consulta');
            $table->integer('id_especialista');
            $table->integer('id_persona_especialista');
            $table->integer('id_historia_clinica');
            $table->integer('id_persona');
            $table->integer('id_usuario_dispensa');
            $table->string('motivo_anulacion');
            $table->timestamp('fecha_dispensado');
            $table->integer('id_usuario_anula');
            $table->timestamp('fecha_anulado');
            $table->integer('id_estado_validacion');
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.internacion', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_internacion');
            $table->timestamp('fecha_internacion');
            $table->text('indicaciones');
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.baja_medica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_baja')->nullable();
            $table->integer('nro_baja_medica');
            $table->timestamp('fecha_ini_baja');
            $table->timestamp('fecha_fin_baja');
            $table->integer('dias_baja');
            $table->boolean('estado')->default(true);
            $table->text('descripcion');
            $table->unsignedBigInteger('id_user_created')->nullable(); // El usuario que crea el registro
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.tipo_baja', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('porcentaje_devolucion', 8, 2);
            $table->timestamps();
        });

        Schema::create('consulta_externa.cie_10', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->text('descripcion');
        });

        Schema::create('consulta_externa.hc_cie10', function (Blueprint $table) {
            $table->unsignedBigInteger('id_historia_clinica');
            $table->unsignedBigInteger('id_cie_10');

            $table->foreign('id_historia_clinica')
                ->references('id')
                ->on('consulta_externa.historia_clinica');

            $table->foreign('id_cie_10')
                ->references('id')
                ->on('consulta_externa.cie_10');

            // Agregar índices para mejorar el rendimiento de las consultas
            $table->index(['id_historia_clinica', 'id_cie_10']);
        });

        Schema::create('consulta_externa.receta_medicamento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_receta');
            $table->unsignedBigInteger('id_medicamento');
            $table->integer('cantidad');
            $table->integer('dias_validez');
            $table->string('indicaciones');
            $table->string('forma_adm');
            $table->text('recomendacion')->nullable();
            $table->foreignId('id_user_created')->nullable();
            $table->foreignId('id_user_updated')->nullable();
            $table->timestamps();

            $table->foreign('id_receta')
                ->references('id')
                ->on('consulta_externa.receta')
                ->onDelete('cascade');
            $table->foreign('id_medicamento')
                ->references('id')
                ->on('farmacia.medicamento')
                ->onDelete('restrict');
        });

        Schema::create('consulta_externa.imagenologia_estudio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sol_imagenologia');
            $table->unsignedBigInteger('id_estudio');
            $table->unsignedBigInteger('id_tipo');
            $table->unsignedBigInteger('id_user_created')->nullable(); 
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();

            $table->foreign('id_sol_imagenologia')
                ->references('id')
                ->on('consulta_externa.sol_imagenologia')
                ->onDelete('cascade');
            $table->foreign('id_estudio')
                ->references('id')
                ->on('consulta_externa.estudio')
                ->onDelete('restrict');
        });

        Schema::create('consulta_externa.hc_planes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_historia_clinica')->nullable();
            $table->unsignedBigInteger('id_plan')->nullable();
            $table->integer('id_corresponde')->nullable();
            $table->unsignedBigInteger('id_user_created')->nullable(); 
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        Schema::create('consulta_externa.planes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado');
            $table->unsignedBigInteger('id_user_created')->nullable(); 
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });

        // RELACIONES
        Schema::table('consulta_externa.historia_clinica', function (Blueprint $table) {
            $table->foreign('id_tipo_consulta')->references('id')->on('consulta_externa.tipo_consulta');

        });


        Schema::table('consulta_externa.estudio', function (Blueprint $table) {
            $table->foreign('id_tipo_img')->references('id')->on('consulta_externa.tipo_img');
        });

        // Schema::table('consulta_externa.referencia', function (Blueprint $table) {
        //     $table->foreign('id_especialidad')->references('id')->on('especialidades');
        // });

        Schema::table('consulta_externa.baja_medica', function (Blueprint $table) {
            $table->foreign('id_tipo_baja')->references('id')->on('consulta_externa.tipo_baja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consulta_externa.tipo_baja');
        Schema::dropIfExists('consulta_externa.baja_medica');
        Schema::dropIfExists('consulta_externa.internacion');
        Schema::dropIfExists('consulta_externa.referencia');
        Schema::dropIfExists('consulta_externa.sol_fisioterapia');
        Schema::dropIfExists('consulta_externa.tipo_img');
        Schema::dropIfExists('consulta_externa.estudio');
        Schema::dropIfExists('consulta_externa.sol_imagenologia');
        Schema::dropIfExists('consulta_externa.historia_clinica');
        Schema::dropIfExists('consulta_externa.tipo_consulta');
    }
};
