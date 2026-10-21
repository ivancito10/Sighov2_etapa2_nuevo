<?php

namespace App\Models\Enfermeria;

use Illuminate\Database\Eloquent\Model;

class RegistroInyeccionProcedimiento extends Model
{
    protected $table = 'enfermeria.registro_inyeccion_procedimiento';

    protected $fillable = [
        'id_registro_inyeccion',
        'curacion_plana',
        'curacion_infectada',
        'oxigenoterapia',
        'retiro_puntos',
        'prueba_sensibilidad',
        'sangria',
        'signos_vitales',
        'tx_vo',
        'orientacion_salud',
        'vendajes',
        'nebulizacion',
        'otros',
        'observaciones',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'curacion_plana'      => 'boolean',
        'curacion_infectada'  => 'boolean',
        'oxigenoterapia'      => 'boolean',
        'retiro_puntos'       => 'boolean',
        'prueba_sensibilidad' => 'boolean',
        'sangria'             => 'boolean',
        'signos_vitales'      => 'boolean',
        'tx_vo'               => 'boolean',
        'orientacion_salud'   => 'boolean',
        'vendajes'            => 'boolean',
        'nebulizacion'        => 'boolean',
        'estado'              => 'boolean',
    ];

    public function registroInyeccion()
    {
        return $this->belongsTo(RegistroInyeccion::class, 'id_registro_inyeccion');
    }
}