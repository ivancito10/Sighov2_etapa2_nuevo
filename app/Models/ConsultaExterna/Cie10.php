<?php

namespace App\Models\ConsultaExterna;
// use App\Models\ConsultaExterna\Cie10.php
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cie10 extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.cie_10';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    public function cie10HistoriasClinicas()
    {
        return $this->belongsToMany(HistoriaClinica::class, 'hc_cie10', 'id_cie_10', 'id_historia_clinica');
    }
}
