<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.variable';

    protected $fillable = [
        'id',
        'nombre', 
        'tipo',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA USUARIO

    public function variableEspecialidadVariable()
    {
        return $this->belongsTo(EspecialidadVariable::class, 'id', 'id_variable');
    }

    // public function usuarioEditor()
    // {
    //     return $this->belongsTo(User::class, 'id_user_updated', 'id');
    // }
}
