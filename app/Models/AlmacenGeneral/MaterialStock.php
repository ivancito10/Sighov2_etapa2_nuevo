<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialStock extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.material_stock';

    protected $fillable = [
        'id',
        'cantidad_inicial',
        'precio_unitario',
        'stock_actualizado',
        'nro_lote',
        'id_material',
        'id_unidad',
        'fecha_vencimiento',
        'cierre_gestion',
        'id_ingreso',
        'id_user_created',
        'id_user_updated',

    ];

    //RELACION CON INGRESO
    public function ingresoMaterialStock()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso', 'id');
    }

    //RELACION CON MATERIAL
    public function ingresoMaterialMaterial()
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
