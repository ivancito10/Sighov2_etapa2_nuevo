<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\Reingreso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReingresoMaterial extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.reingreso_material';

    protected $fillable = [
        'id',
        'id_reingreso',
        'id_material',
        'fecha',
        'estado',
        'cantidad_ingreso',
        'fecha_vencimiento',
        'precio_unitario',
        'created_at',
        'updated_at',
        'id_user_created',
        'id_user_updated',
    ];


    //RELACION CON MEDICAMENTO
    public function materialReingresoMaterial()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }
    //RELACION CON Reingreso
    public function reingresoReingresoMaterial()
    {
        return $this->belongsTo(Reingreso::class, 'id_reingreso', 'id');
    }
    //RELACION CON UNIDAD
    // public function unidadReingresoMedicamento(){
    //     return $this->belongsTo(Unidad::class, 'id_unidad', 'id');
    // }

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
