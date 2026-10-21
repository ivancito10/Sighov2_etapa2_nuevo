<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\Administracion\Unidad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicamentoStock extends Model
{
    use HasFactory;

    protected $table = 'farmacia.medicamento_stock';

    protected $fillable = [
        'id',
        'cantidad_inicial',
        'precio_unitario',
        'stock_actualizado',
        'nro_lote',
        'id_medicamento',
        'id_unidad',
        'cierre_gestion',
        'id_user_created',
        'id_user_updated',
        'fecha_vencimiento',
        'id_ingreso',
    ];

    //RELACION CON MEDICAMENTO
    public function medicamentoMedicamentoStock()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }

    //RELACION CON UNIDAD
    public function unidadMedicamentoStock()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id');
    }

    //RELACION CON INGRESO
    public function ingresoMedicamentoStock()
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
