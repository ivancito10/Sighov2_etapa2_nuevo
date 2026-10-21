<?php

namespace App\Models\Farmacia;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlCierreGestion extends Model
{
    use HasFactory;

    protected $table = 'farmacia.control_cierre_gestion';

    protected $fillable = [
        'id',
        'id_cierre_gestion',
        'id_unidad',
        'id_medicamento_stock',
        'id_movimiento',
        'tipo_movimiento',
        'cantidad',
    ];

    //RELACION CON CIERRE GESTION
    public function controlCierreGestionCierreGestion()
    {
        return $this->belongsTo(CierreGestion::class, 'id_cierre_gestion', 'id');
    }

    //RELACION CON LA TABLA MEDICAMENTO STOCK
    public function controlCierreGestionMedicamentoStock()
    {
        return $this->belongsTo(MedicamentoStock::class, 'id_medicamento_stock', 'id');
    }

    //RELACION CON LA TABLA MOVIMIENTO
    public function controlCierreGestionMovimiento()
    {
        return $this->belongsTo(Movimiento::class, 'id_movimiento', 'id');
    }
}
