<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interconsulta extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.interconsulta';

    protected $fillable = [
        'id',
        'indicaciones',
        'id_especialidad',
        'nro_interconsulta',
        'estado',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
        'id_persona',
    ];
}
