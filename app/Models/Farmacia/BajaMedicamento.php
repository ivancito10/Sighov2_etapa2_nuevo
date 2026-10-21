<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BajaMedicamento extends Model
{
    use HasFactory;

    protected $table = 'farmacia.baja_medicamento';

    protected $fillable = [
        'id',
        'motivo_baja',
        'observacion',
        'fecha_baja',
        'nro_lote',
        'cantidad_medicamentos',
        'precio_unitario',
        'id_medicamento',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON MEDICAMENTO
    public function medicamentoBajaMedicamento()
    {
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
