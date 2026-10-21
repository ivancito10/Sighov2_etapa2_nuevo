<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoSolicitudImagenologia extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.resultados_solicitudes_imagenologia';

    protected $fillable = [
        'observaciones',
        'id_solicitud',
        'nombre_placa',
        'id_user_created',
        'id_user_updated',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Imagenologia::class, 'id_solicitud', 'id');
    }

    public function creador()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user_created');
    }

    public function editor()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user_updated');
    }
}
