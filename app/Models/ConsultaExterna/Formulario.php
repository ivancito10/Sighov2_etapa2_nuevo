<?php

namespace App\Models\ConsultaExterna;

use App\Models\Administracion\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.formulario';

    protected $fillable = [
        'id',
        'id_persona_enfermera', 
        'id_asignacion_horario',
        'created_at',
        'updated_at',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA USUARIO

    public function FormularioDetalleFormulario()
    {
        return $this->hasMany(DetalleFormulario::class, 'id_formulario', 'id');
    }

    public function FormularioEnfermera()
    {
        return $this->belongsTo(Persona::class, 'id_enfermera', 'id');
    }
}
