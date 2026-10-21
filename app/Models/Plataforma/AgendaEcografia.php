<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;
class AgendaEcografia extends Model
{
    use HasFactory;
    protected $table = "plataforma.agenda_ecografia";
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'fecha_agenda',
        'hora_agenda',
        'confirmado',
        'asistencia',
        'anulacion_ficha',
        'fecha_anulacion',
        'motivo_anulacion',
        'observacion',
        'clave_unica',
        'ip',
        'nombre_equipo',
        'id_persona',
        'id_servicio_plataforma',
        'id_asignacion_horario',
        'id_user_created',
        'id_user_updated',
        'id_usuario_anula',
        'id_suspension_horario_ecografia',
        'id_sol_imagenologia',
        'id_user_created',
        'id_user_updated',
    ];

    public function asignacionHorarioAgenda()
    {
        return $this->belongsTo(AsignacionHorario::class, 'id_asignacion_horario', 'id');
    }

    //Relaciones con la tabla Servicio Plataforma
    public function servicioPlataformaAgenda()
    {
        return $this->belongsTo(ServicioPlataforma::class, 'id_servicio_plataforma', 'id');
    }

    //Relaciones con la tabla Suspension Horario
    public function suspensionHorarioAgenda()
    {
        return $this->belongsTo(SuspensionHorarioEcografia::class, 'id_suspension_horario_ecografia', 'id');
    }
    public function personaAgenda()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }


    public function usuarioAnula()
    {
        return $this->belongsTo(User::class, 'id_usuario_anula', 'id');
    }
}
