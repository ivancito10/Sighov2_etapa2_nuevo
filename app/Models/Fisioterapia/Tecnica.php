<?php

namespace App\Models\Fisioterapia;

use Illuminate\Database\Eloquent\Model;

class Tecnica extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'fisioterapia.tecnicas';
    protected $fillable = ['codigo', 'nombre', 'grupo'];
    protected $casts = ['estado' => 'boolean'];
}
