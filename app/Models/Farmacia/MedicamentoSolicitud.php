<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Solicitud;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicamentoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'farmacia.medicamento_solicitud';

    protected $fillable = [
        'id',
        'cantidad_solicitada',
        'cantidad_entregada',
        'id_solicitud',
        'id_medicamento',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON MEDICAMENTO
    public function medicamentoMedicamentoSolicitud()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }

    //RELACION CON LA TABLA SOLICITUD
    public function solicitudMedicamentoSolicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id');
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
