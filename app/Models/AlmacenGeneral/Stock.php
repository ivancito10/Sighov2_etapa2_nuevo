<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use App\Models\AlmacenGeneral\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.stock';

    protected $fillable = [
        'id',
        'id_material',
        'nro_lote',
        'cantidad_inicial',
        'stock_actualizado',
        'precio_unitario',
        'ajuste_contable',
        'fecha_vencimiento',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA MATERIAL

    public function materialStock(){
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
