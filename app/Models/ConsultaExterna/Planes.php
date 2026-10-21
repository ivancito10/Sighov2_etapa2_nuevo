<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planes extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.planes';

    protected $fillable = [
        'id',
        'nombre',
        'estado',
        'id_user_created',
        'id_user_updated',
        'id_created_at',
        'id_updated_at',
    ];
}
