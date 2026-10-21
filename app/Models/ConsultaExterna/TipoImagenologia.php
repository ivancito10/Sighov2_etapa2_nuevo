<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoImagenologia extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.tipo_img';

    protected $fillable = [
        'id',
        'nombre',
        'estado',
        'created_at',
        'updated_at',
    ];
}
