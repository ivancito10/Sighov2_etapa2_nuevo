<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Agenda;
use App\Models\User;
use App\Models\Administracion\Persona;



class AsignacionHorario extends Model
{
    use HasFactory;
    protected $table = "plataforma.asignacion_horario";
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'id_dia',
        'id_horario',
        'id_especialista',
        'id_consultorio',
        'permanente',
        'id_user_created',
        'id_user_updated',
    ];


    //Relaciones con la tabla Especilista
    public function especialistaAsignacionHorario()
    {
        return $this->belongsTo(Especialista::class, 'id_especialista', 'id');
    }

    //Relaciones con la Tabla Agenda
    public function agendaAsignacionHorario()
    {
        return $this->hasMany(Agenda::class, 'id_asignacion_horario', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    public function persona()
    {
        return $this->hasOneThrough(Persona::class, Especialista::class);
    }

    public function especialistaDetalles()
    {
        return $this->belongsTo(Especialista::class, 'id_especialista')->with('persona');
    }
}
