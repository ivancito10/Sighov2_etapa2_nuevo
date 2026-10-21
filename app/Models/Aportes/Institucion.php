<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Administracion\Persona;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;
use App\Models\User;


class Institucion extends Model
{
    use HasFactory;
    protected $table = 'aportes.institucion';
    protected $connection = 'pgsql_bd1';

    protected $fillable = [
        'nit',
        'nro_empleador',
        'actividad_empleador',
        'id_clase_aportante',
        'acronimo',
        'fecha_afiliacion',
        'correo',
        'telefono',
        'id_persona',
        'nombre',
        'estado',
        'tgn',
        'id_user_created',
        'id_user_updated',
        'id_usuario_responsable',
        'estado_validado',
        'id_residencia',
        'publica',
        'codigo_comercio',
        'tipo_empresa_institucion',
        'referencia_direccion',
        'telefono_dos',
        'migrado_tesoreria',
        'id_usuario_aviso_baja',
    ];


    public function claseAportanteInstitucion()
    {
        return $this->belongsTo(ClaseAportante::class, 'id_clase_aportante');
    }

    public function personaRepresentanteInstitucion()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
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

    public function codigosInstitucion()
    {
        return $this->hasOne(CodigoInstitucionSsu::class, 'id_institucion', 'id');
    }

    //-- RELACION CON ESQUEMA AFILIACION
    public function institucionConTitularInstitucion()
    {
        return $this->hasMany(TitularInstitucion::class, 'id_institucion', 'id');
    }

}
