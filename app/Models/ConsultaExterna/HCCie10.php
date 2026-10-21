<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Cie10;

class HCCie10 extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.hc_cie10';

    protected $fillable = [
        'id',
        'id_historia_clinica',
        'id_cie_10', 
    ];

    public function datosHistoriaClinica(){
        return $this->belongsTo(HistoriaClinica::class, 'id_historia_clinica', 'id');
    }
    public function datosCie10(){
        return $this->belongsTo(Cie10::class, 'id_cie_10', 'id');
    }
}
