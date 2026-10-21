<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\Vigencia\AutorizacionInterior;


class SeguroInterior extends Model
{
    use HasFactory;
    protected $table = "vvdd.seguro_interior";
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'nombre_seguro',
        'sigla_seguro',
        'nit',
        'nro_empleador',
        'id_residencia',
        'correo',
        'direccion_seguro',
        'telefono',
        'fax',
        'id_usuario_responsable',
        'id_rep_legal',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    
    //Relaciones con la tabla Usuario
    public function usuarioSeguroInterior()
    {
        return $this->belongsTo(User::class, 'id_usuario_responsable', 'id');
    }

    //Relaciones con la tabla Persona
    public function personaSeguroInterior()
    {
        return $this->belongsTo(Persona::class, 'id_rep_legal', 'id');
    }


    //Relaciones con la tabla Autorizacion Interior
    public function autorizacionInteriorSeguroInterior()
    {
        return $this->hasMany(AutorizacionInterior::class, 'id_seguro_interior', 'id');
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
