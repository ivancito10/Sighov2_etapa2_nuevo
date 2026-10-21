<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSolicitud extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.material_solicitud';

    protected $fillable = [
        'id',
        'cantidad_solicitada',
        'cantidad_entregada',
        'cantidad_solicitada_original',
        'id_solicitud',
        'id_material',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA MATERIAL

    public function materialMaterialSolicitud(){
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }

    //RELACION CON LA TABLA SOLICITUD

    public function solicitudMaterialSolicitud(){
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id');
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
