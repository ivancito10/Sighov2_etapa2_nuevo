<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Ingreso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    use HasFactory;

    protected $table = 'farmacia.detalle_ingreso';

    protected $fillable = [
        'id',
        'precio_unitario',
        'cantidad',
        'precio_total',
        'id_medicamento',
        'id_ingreso',
        'fecha_vencimiento',
        'estado_adelanto',
        'nro_lote',
        'pais_marca',
        'id_user_created',
        'id_user_updated',
    ];

    // algo
    //RELACION CON LA TABLA MEDICAMENTO
    public function medicamentoDetalleIngreso()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }

    //RELACION CON LA TABLA INGRESO 
    public function ingresoDetalleIngreso()
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
