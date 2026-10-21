<?php

namespace App\Models\RayosX;

use App\Models\Administracion\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacasRayosX extends Model
{
    use HasFactory;
    protected $table = 'rayos_x.placas_rayos_x';

    protected $fillable = [
        ' id',
        'estado',
        'id_persona_asegurado',
        'id_persona_solicitante',
        'codigo',
        'nro_placas',
        'observacion',
        'fecha_entrega',
        'fecha_estudio',
        'estudios',
        'estudio_origen',
        'tipo',
        'nombre_solicitante',
        'id_user_created',
        'id_user_updated',
    ];
    public function personaAsegurado()
    {
        return $this->belongsTo(Persona::class, 'id_persona_asegurado', 'id');
    }
    public function personaSolicitante()
    {
        return $this->belongsTo(Persona::class, 'id_persona_solicitante', 'id');
    }
}
