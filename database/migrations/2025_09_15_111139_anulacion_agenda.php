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
        Schema::create('plataforma.anulacion_agenda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona')->nullable();
            $table->foreign('id_persona')->references('id')->on('administracion.persona')->onDelete('set null');
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('id_asignacion')->nullable();
            $table->foreign('id_asignacion')->references('id')->on('plataforma.asignacion_horario')->onDelete('set null');
            $table->string('nombre_equipo')->nullable();
            $table->string('ip')->nullable();
            $table->unsignedBigInteger('id_agenda')->nullable();
            $table->foreign('id_agenda')->references('id')->on('plataforma.agenda')->onDelete('set null');
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('id_user_created')->nullable();
            $table->foreign('id_user_created')->references('id')->on('administracion.users')->onDelete('set null');
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->foreign('id_user_updated')->references('id')->on('administracion.users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
