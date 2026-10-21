<?php

namespace App\Models\AlmacenGeneral;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoValidacion extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.estado_validacion';

    protected $fillable = [
        'id',
        'nombre_estado',
        'estado'
    ];
}
