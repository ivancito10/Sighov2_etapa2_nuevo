<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\Administracion\Unidad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReingresoMedicamento extends Model
{
    use HasFactory;

    protected $table = 'farmacia.reingreso_medicamento';

    protected $fillable = [
        'id',
        'id_reingreso',
        'id_medicamento',
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
    public function medicamentoReingresoMedicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }
    //RELACION CON Reingreso
    public function reingresoReingresoMedicamento()
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
