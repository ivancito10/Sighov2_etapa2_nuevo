<?php

namespace App\Models\AlmacenGeneral;

use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Movimiento;
use App\Models\AlmacenGeneral\CierreGestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlCierreGestion extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.control_cierre_gestion';

    protected $fillable = [
        'id',
        'id_cierre_gestion',
        'id_unidad',
        'id_material_stock',
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
        return $this->belongsTo(MaterialStock::class, 'id_medicamento_stock', 'id');
    }

    //RELACION CON LA TABLA MOVIMIENTO
    public function controlCierreGestionMovimiento()
    {
        return $this->belongsTo(Movimiento::class, 'id_movimiento', 'id');
    }
}
