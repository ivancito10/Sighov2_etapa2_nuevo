<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorCumple extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.proveedor_cumple';

    protected $fillable = [
        'id',
        'tiempo_entrega',
        'cantidad_pedido',
        'calidad_solicitada',
        'terminos_referencia',
        'capacitacion',
        'garantias',
        'tiempo_entrega_obs',
        'cantidad_pedido_obs',
        'calidad_solicitada_obs',
        'terminos_referencia_obs',
        'capacitacion_obs',
        'garantias_obs',
        'otras_observaciones',
        'id_ingreso',
        'id_user_created',
        'id_user_updated'
    ];

    //RELACION CON LA TABLA MATERIAL

    public function materialCierreGestion()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso', 'id');
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
