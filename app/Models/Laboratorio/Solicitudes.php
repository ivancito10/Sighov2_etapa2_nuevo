<?php

namespace App\Models\Laboratorio;

use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;


class Solicitudes extends Model
{
    use HasFactory;

    protected $table = 'laboratorio.solicitudes';

    protected $fillable = [
        'id',
        'nro_solicitud',
        'id_historia_clinica',
        'id_persona_asegurado',
        'id_persona_solicitante',
        'observacion_solicitud',
        'id_tipo_atencion',
        'id_solicitud_s1',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at'
    ];

    //RELACION CON LA TABLA HISTORIA CLINICA
    public function RelacionHistoriaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'id_historia_clinica', 'id');
    }
    // RELACION CON TIPO CONSULTA
    public function tipoConsultaSolicitudes()
    {
        return $this->belongsTo(TipoConsulta::class, 'id_tipo_atencion', 'id');
    }

    //RELACION CON LA TABLA USUARIO
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function examenesSolicitud()
    {
        return $this->hasMany(SolicitudExamen::class, 'id_solicitudes', 'id');
    }

    //RELACION CON LA TABLA PERSONA

    public function aseguradoSolicitudesLaboratorio()
    {
        return $this->belongsTo(Persona::class, 'id_persona_asegurado', 'id');
    }

    public function solicitanteSolicitudesLaboratorio()
    {
        return $this->belongsTo(Persona::class, 'id_persona_solicitante', 'id');
    }
}
