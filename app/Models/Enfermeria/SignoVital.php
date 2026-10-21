<?php

namespace App\Models\Enfermeria;

use App\Models\Plataforma\Agenda;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignoVital extends Model
{
    use HasFactory;
    protected $table = 'enfermeria.signos_vitales';
    protected $fillable = [
        'id',
        'presion_arterial',
        'frecuencia_respiratoria',
        'temperatura',
        'frecuencia_cardiaca',
        'saturacion_oxigeno',
        'peso',
        'talla',
        'id_agenda',
        'id_especialista',
        'id_especialidad',
        'id_user_created',
        'id_user_updated',
    ];

     public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'id_agenda', 'id');
    }
}
