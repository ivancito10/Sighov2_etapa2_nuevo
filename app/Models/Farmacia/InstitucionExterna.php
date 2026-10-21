<?php

namespace App\Models\Farmacia;

use App\Models\Administracion\Residencia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionExterna extends Model
{
    use HasFactory;

    protected $table = 'farmacia.institucion_externa';

    protected $fillable = [
        'id',
        'razon_social',
        'nit' ,
        'registro_sanitario',
        'telefono',
        'correo',
        'estado',
        'id_residencia',
        'proveedor_almacen_general',   
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA RESIDENCIA

    public function residenciaInstitucionExterna(){
        return $this->belongsTo(Residencia::class, 'id_residencia', 'id');
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
}
