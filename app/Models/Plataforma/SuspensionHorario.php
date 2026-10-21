<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SuspensionHorario extends Model
{
    use HasFactory;
    protected $table = "plataforma.suspension_horario";
    protected $connection = 'pgsql_bd1';

    protected $fillable = [
        'id',
        'fecha_suspension',
        'observacion',
        'estado',
        'id_motivo',
        'id_especialista',
        'nro_suspension',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la tabla Asignacion Horario
    public function asignacionHorarioSuspensionHorario()
    {
        return $this->belongsTo(AsignacionHorario::class, 'id_asignacion_horario', 'id');
    }

    //Relaciones con la tabla Motivo
    public function motivoSuspensionHorario()
    {
        return $this->belongsTo(Motivo::class, 'id_motivo', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaSuspensionHorario()
    {
        return $this->belongsTo(Especialista::class, 'id_especialista', 'id');
    }

    //Relaciones con la tabla Persona
    //public function personaSuspensionHorario(){
    //    return $this->belongsTo(Persona::class,'id_persona','id');
    //}

    //Relaciones con la tabla Agenda
    public function agendaSuspensionHorario()
    {
        return $this->hasMany(Agenda::class, 'id_suspension_horario', 'id');
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
}
