<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoMaterial extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.ingreso_material';

    protected $fillable = [
        'id',
        'id_ingreso',
        'id_material',
        'precio_unitario',
        'fecha_vencimiento',
        'cantidad',
        'precio_total',
        'nro_lote',
        'estado',
        'estado_adelanto',
        'pais_marca',
        'id_user_created',
        'id_user_updated',
    ];
    
    //RELACION CON LA TABLA INGRESO

    public function ingresoIngresoMaterial(){
        return $this->belongsTo(Ingreso::class, 'id_ingreso', 'id');
    }

    //RELACION CON LA TABLA MATERIAL

    public function materialIngresoMaterial(){
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
