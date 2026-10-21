<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\Afiliacion\TitularInstitucion;

class Titular extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.titular';
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'estado_requisitos',
        'estado_vigencia',
        'telefono_referencia',
        'nombre_referencia',
        'foto_nombre',
        'observacion',
        'fecha_afiliacion',
        'id_persona',
        'telefono',
        'fecha_reafiliacion',
        'correo',
        'estado',
        'tipo_titular',
        'id_residencia',
        'tipo_rentista',
        'fecha_baja',
        'permanente',
        'id_user_created',
        'id_user_updated',
        'ip_afiliado',
        'archivo_grupo',
        'fecha_archivo',
        'id_user_archivo',
    ];

    // -- RELACIONES CON EL ESQUEMA AFILIACION ---
    public function titularConTitularInstitucion()
    {
        return $this->hasMany(TitularInstitucion::class, 'id_titular', 'id');
    }


    //Relacion de titular con Persona 
    public function titularPersona()
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
}
