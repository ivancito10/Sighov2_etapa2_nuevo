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
        Schema::dropIfExists('laboratorio.solicitud_examen');

        Schema::create('laboratorio.solicitud_examen', function (Blueprint $table) {
            $table->id();
            $table->integer('id_examen');
            $table->integer('id_solicitudes');
            $table->foreign('id_solicitudes')->references('id')->on('laboratorio.solicitudes');
            $table->string('nombre_examen');
            $table->string('grupo');
            $table->string('tipo_laboratorio');
            $table->boolean('estado')->default('true');
            $table->integer('id_user_created');
            $table->integer('id_user_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio.solicitud_examen');
    }
};
