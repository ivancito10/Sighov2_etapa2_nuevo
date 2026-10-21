<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFormulario extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.detalle_formulario';

    protected $fillable = [
        'id',
        'id_especialidad_variable', 
        'valor',
        'id_agenda',
        'id_formulario',
        'id_consulta_externa',
        'created_at',
        'updated_at',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA USUARIO

    public function DetalleFormularioFormulario()
    {
        return $this->belongsTo(Formulario::class, 'id_user_created', 'id');
    }

    public function DetalleFormularioConsultaExterna()
    {
        return $this->belongsTo(HistoriaClinica::class, 'id_consulta_externa', 'id');
    }

    public function DetalleFormularioespecialidadVariable()
    {
        return $this->belongsTo(EspecialidadVariable::class, 'id_especialidad_variable');
    }
}
