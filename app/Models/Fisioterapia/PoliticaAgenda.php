<?php

namespace App\Models\Fisioterapia;

use Illuminate\Database\Eloquent\Model;

class PoliticaAgenda extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'fisioterapia.politicas_agenda';
    protected $fillable = ['codigo_version', 'cupos_normales', 'cupos_especiales', 'max_sesiones_normales'];
    protected $casts = ['estado' => 'boolean'];
}
