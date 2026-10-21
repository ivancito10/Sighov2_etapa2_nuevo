<?php

namespace App\Models\ConsultaExterna;

use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ConsultaExterna\Receta;
use App\Models\Enfermeria\SignoVital;

class HistoriaClinica extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.historia_clinica';

    protected $fillable = [
        'id',
        'id_agenda',
        'id_tipo_consulta',
        'id_persona',
        'id_especialidad',
        'reconsulta',
        'id_historia_clinica',
        'asistencia',
        'fecha_hora',
        'id_especialista',
        'motivo_consulta',
        'enfermedad_actual',
        'objetivo',
        'estado_consulta',
        'diagnostico',
        'descriptivo',
        'plan_descriptivo',
        'estado',
        'observacion_planes',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
    ];

    //RELACION CONSIGO MISMO

    public function reconsultas()
    {
        return $this->hasMany(self::class, 'id_historia_clinica');
    }
    public function historiaClinicaPaciente()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //RELACION CON LA TABLA TIPO CONSULTA

    public function historiaClinicaTipoConsulta()
    {
        return $this->belongsTo(TipoConsulta::class, 'id_tipo_consulta', 'id');
    }

    public function HistoriaClinicaCie10()
    {
        return $this->belongsToMany(Cie10::class, 'consulta_externa.hc_cie10', 'id_historia_clinica', 'id_cie_10');
    }
    public function HistoriaClinicaPersona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }


    //RELACION CON LA TABLA DE ESPCIALIDADES

    public function EspecialidadHistoriaClinica()
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id');
    }


    //RELACION CON LA TABLA USUARIO

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    public function signosVitales()
{
    return $this->hasOne(SignoVital::class, 'id_agenda', 'id_agenda');
}
}
