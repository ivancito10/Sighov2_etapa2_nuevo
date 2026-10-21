<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoObtencionUsuario extends Model
{
    use HasFactory;

    protected $table = 'administracion.seguimiento_obtencion_usuario';
    protected $connection='pgsql_bd1';
    protected $fillable = [
        'matricula_seguro',
        'numero_ci',
        'complemento_ci',
        'fecha_nacimiento',
        'ip_address',
        'device_name',
        'captcha_token',
        'recuperado'
    ];
}
