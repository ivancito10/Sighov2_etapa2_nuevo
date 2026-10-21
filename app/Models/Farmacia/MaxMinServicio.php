<?php

namespace App\Models\Farmacia;

use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\User;
use App\Models\Farmacia\Medicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxMinServicio extends Model
{
    use HasFactory;

    protected $table = 'farmacia.max_min_servicio';

    protected $fillable = [
        'id',
        'cantidad_maxima',
        'cantidad_minima',
        'id_medicamento',
        'id_tipo_consulta',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACIONES CON MEDICAMENTO
    public function maxMinServicioMedicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }

    //RELACIONES CON TIPO CONSULTA
    public function maxMinServicioTipoConsulta(){
        return $this->belongsTo(TipoConsulta::class, 'id_tipo_consulta', 'id');
    }

    //RELACIONES CON USUARIO CREADOR Y EDITOR
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
