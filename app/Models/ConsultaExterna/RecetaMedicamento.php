<?php

namespace App\Models\ConsultaExterna;

use App\Models\Farmacia\Medicamento;
use App\Models\ConsultaExterna\Receta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RecetaMedicamento extends Pivot
{
    use HasFactory;

    protected $table = 'consulta_externa.receta_medicamento';

    protected $fillable = [
        'id',
        'id_receta',
        'id_medicamento',
        'cantidad',
        'indicaciones',
        'forma_adm',
        'recomendacion',
        'dias_validez',
        'fecha_ini',
        'fecha_fin',
        'estado_validez',
        'id_user_habilita',
        'motivo_habilita',
        'id_user_created',
        'id_user_updated',
    ];

    public function medicamentoRecetaMedicamento(){
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }

    public function recetaRecetaMedicamento(){
        return $this->belongsTo(Receta::class, 'id_receta', 'id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
