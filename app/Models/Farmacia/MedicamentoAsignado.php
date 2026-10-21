<?php

namespace App\Models\Farmacia;

use App\Models\User;
use App\Models\Farmacia\DetalleInventario;
use App\Models\Farmacia\Medicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicamentoAsignado extends Model
{
    use HasFactory;

    protected $table = 'farmacia.medicamento_asignado';

    protected $fillable = [
        'id',
        'id_detalle_inventario',
        'id_medicamento',
        'cantidad_registrada',
        'estado_inventario',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA DETALLE INVENTARIO
    public function detalleInventarioMedicamentoAsignado(){
        return $this->belongsTo(DetalleInventario::class, 'id_detalle_inventario', 'id');
    }

    //RELACION CON LA TABLA MEDICAMENTO
    public function medicamentoMedicamentoAsignado(){
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
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
