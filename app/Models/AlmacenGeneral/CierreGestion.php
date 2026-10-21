<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use App\Models\AlmacenGeneral\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreGestion extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.cierre_gestion';

    protected $fillable = [
        'id',
        'cantidad_fisica',
        'cnatidad_sistema',
        'fecha_registro',
        'diferencia',
        'id_unidad',
        'id_material',
        'fecha_vencimiento',
        'cantidad_bajas',
        'fecha_vencimiento_baja',
        'id_user_created',
        'id_user_updated'
    ];

    //RELACION CON LA TABLA MATERIAL

    public function materialCierreGestion()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
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
