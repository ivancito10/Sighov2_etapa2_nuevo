<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\Afiliacion\Titular;


class Beneficiario extends Model
{
    use HasFactory;
    protected $table = 'afiliacion.beneficiario';
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'fecha_afiliacion',
        'fecha_reafiliacion',
        'foto_nombre',
        'observacion',
        'vigencia',
        'preafiliacion',
        'tipo_afiliacion',
        'telefono_referencia',
        'nombre_referencia',
        'telefono',
        'correo',
        'estado_requisitos',
        'codigo_respuesta_asuss',
        'id_titular',
        'id_persona',
        'id_residencia',
        'con_remuneracion',
        'fecha_baja',
        'permanente',
        'id_user_created',
        'id_user_updated',
        'ip_afiliado',
        'archivo_grupo',
        'fecha_archivo',
        'id_user_archivo',
    ];
    // relacion con la tabla titular 
    public function titularBeneficiario()
    {
        return $this->belongsTo(Titular::class, 'id_titular');
    }

    // relacion con la tabla beneficiario institucion 
    public function beneficiarioConInstitucionBeneficiario()
    {
        return $this->hasMany(BeneficiarioInstitucion::class, 'id_beneficiario', 'id');
    }
    //-- RELACIONES CON EL ESQUEMA ADMINISTRACION
    // relacion con la tabla persona
    public function beneficiarioPersona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
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
