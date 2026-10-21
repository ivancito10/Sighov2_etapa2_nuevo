<?php

namespace App\Models\Enfermeria;

use Illuminate\Database\Eloquent\Model;
use App\Models\Farmacia\Medicamento;

class RecetaManual extends Model
{
    protected $table = 'enfermeria.receta_manual';

    protected $fillable = [
        'id_persona',
        'id_especialista',
        'id_medicamento',
        'fecha_receta',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'fecha_receta' => 'date',
        'estado'       => 'boolean',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }
}