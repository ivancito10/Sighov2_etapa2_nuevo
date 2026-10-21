<?php

namespace App\Models\ConsultaExterna;

use App\Models\User;
use App\Models\ConsultaExterna\TipoConsulta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.consulta';

    protected $fillable = [
        'id',
        'motivo',
        'diagnostico_descriptivo',
        'subjetivo',
        'objetivo',
        'plan_descripcion',
        'enfermedad_actual',
        'reconsulta',
        'estado',
        'id_agenda',  //RELACION A TRAVES DE MICROSERVICIOS
        'id_tipo_consulta',
        'id_consulta',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CONSIGO MISMO

    public function consultaConsulta(){
        return $this->belongsTo(Consulta::class, 'id_consulta', 'id');
    }

    //RELACION CON LA TABLA TIPO CONSULTA

    public function consultaTipoConsulta(){
        return $this->belongsTo(TipoConsulta::class, 'id_tipo_consulta', 'id');
    }

    //RELACION CON LA TABLA USUARIO

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
