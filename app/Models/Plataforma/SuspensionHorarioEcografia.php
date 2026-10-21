<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspensionHorarioEcografia extends Model
{
    use HasFactory;

       protected $table = 'plataforma.suspension_horario_ecografia';

    protected $connection = 'pgsql_bd1';

    protected $fillable = [
        'id',
        'fecha_suspension',
        'observacion',
        'estado',
        'id_motivo',
        'id_especialista',
        'nro_suspension',
        'id_user_created',
        'id_user_updated',
    ];
}
