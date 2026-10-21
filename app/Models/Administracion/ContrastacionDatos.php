<?php

namespace App\Models\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContrastacionDatos extends Model
{
    use HasFactory; 

    protected $table = "administracion.contrastacion_datos";
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'id_persona',
        'id_tipo_asegurado',
        'id_corresponde',
        'datos_personales_old',
        'datos_referenciales_old',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA PERSONA
    public function personaContrastacionDatos(){
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //RELACION CON LA TABLA TIPO ASEGURADO
    public function tipoAseguradoContrastacionDatos(){
        return $this->belongsTo(TipoAsegurado::class, 'id_tipo_asegurado', 'id');
    }

    //RELACIONES CON LA TABLA USUARIOS

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
