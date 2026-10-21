<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\Plataforma\ServicioPlataforma;
use App\Models\Plataforma\SuspensionHorario;
use App\Models\Enfermeria\SignoVital;

class Agenda extends Model
{
    use HasFactory;
    protected $table = "plataforma.agenda";
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'fecha_agenda',
        'hora_agenda',
        'confirmado',
        'id_servicio_plataforma',
        'id_persona',
        'id_asignacion_horario',
        'anulacion_ficha',
        'fecha_anulacion',
        'motivo_anulacion',
        'id_suspension_horario',
        'clave_unica',
        'ficha_extra',
        'ip',
        'observacion',
        'nombre_equipo',
        'ficha_anticipada',
        'id_usuario_anula',
        'replicado',
        'id_user_created',
        'id_user_updated',
    ];


    //Relaciones con la tabla Asignacion Horario
    public function asignacionHorarioAgenda()
    {
        return $this->belongsTo(AsignacionHorario::class, 'id_asignacion_horario', 'id');
    }

    //Relaciones con la tabla Servicio Plataforma
    public function servicioPlataformaAgenda()
    {
        return $this->belongsTo(ServicioPlataforma::class, 'id_servicio_plataforma', 'id');
    }

    //Relaciones con la tabla Persona
    public function personaAgenda()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Suspension Horario
    public function suspensionHorarioAgenda()
    {
        return $this->belongsTo(SuspensionHorario::class, 'id_suspension_horario', 'id');
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

    public function usuarioAnula()
    {
        return $this->belongsTo(User::class, 'id_usuario_anula', 'id');
    }

    public function signosVitales()
    {
        return $this->hasMany(SignoVital::class, 'id_agenda', 'id');
    }
}
