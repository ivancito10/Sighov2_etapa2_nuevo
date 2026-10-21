<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.material';

    protected $fillable = [
        'id',
        'codigo',
        'nombre_material',
        'descripcion',
        'estado',
        'id_nro_cuenta',
        'nro_cuenta',
        'unidad',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION REVERSA CON LA TABLA INGRESO

    public function materialIngresoMaterial()
    {
        return $this->hasMany(IngresoMaterial::class, 'id_material', 'id');
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
