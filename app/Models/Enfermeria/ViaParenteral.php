<?php

namespace App\Models\Enfermeria;

use Illuminate\Database\Eloquent\Model;

class ViaParenteral extends Model
{
    protected $table = 'enfermeria.via_parenteral';

    protected $fillable = [
        'codigo',
        'nombre',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}