<?php

namespace App\Models\ConsultaExterna;

use App\Models\User;
use App\Models\ConsultaExterna\TipoBajaMedica;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\Plataforma\Especialista;

class BajaMedica extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.baja_medica';

    protected $fillable = [
        'id',
        'id_tipo_baja',
        'nro_baja_medica',
        'fecha_ini_baja',
        'fecha_fin_baja',
        'id_persona',
        'dias_baja',
        'estado',
        'descripcion',
        'id_user_created',
        'id_user_updated',
        'motivo_anulacion',
        'nro_resolucion_retroactivo',
        'fecha_anulacion',
        'id_usuario_anula',
        'id_especialidad',
        'id_especialista',
        'id_institucion',
    ];

    protected $casts = [
        'fecha_fin_baja' => 'datetime',
        'estado'    => 'boolean',
    ];

    //RELACION CON LA TABLA TIPO BAJA MEDICA
    public function tipoBajaMedicaBajaMedica()
    {
        return $this->belongsTo(TipoBajaMedica::class, 'id_tipo_baja', 'id');
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

    public function bajaMedicaPersona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function usuarioAnula()
    {
        return $this->belongsTo(User::class, 'id_usuario_anula', 'id');
    }

    public function especialista()
    {
        return $this->belongsTo(Especialista::class, 'id_especialista', 'id');
    }
}
