<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoValidacion extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.estado_validacion';

    protected $fillable = [
        'id',
        'nombre_estado',
        'estado',
    ];
}
