<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultaExterna\Variable;

class EspecialidadVariable extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.especialidad_variable';

    protected $fillable = [
        'id',
        'id_especialidad', 
        'id_variable',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA USUARIO

    public function especialidadVariableVariable()
    {
        return $this->belongsTo(Variable::class, 'id_variable');
    }
    public function especialidadVariableDetalles()
    {
        return $this->hasMany(DetalleFormulario::class, 'id_especialidad_variable', 'id');
    }

}
