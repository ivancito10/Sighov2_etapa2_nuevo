<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.estudio';

    protected $fillable = [
        'id',
        'nombre',
        'id_tipo_img',
        'estado',
        'created_at',
        'updated_at',
    ];

    public function estudioTipo(){
        return $this->belongsTo(TipoImagenologia::class, 'id_tipo_img', 'id');
    }
}
