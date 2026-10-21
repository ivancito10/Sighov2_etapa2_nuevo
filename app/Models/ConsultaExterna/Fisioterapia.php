<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fisioterapia extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.sol_fisioterapia';

    protected $fillable = [
        'id',
        'nro_solicitud',
        'nro_sesion',
        'indicaciones',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
        'id_persona',
    ];
}
