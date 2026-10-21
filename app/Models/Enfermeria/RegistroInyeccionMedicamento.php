<?php

namespace App\Models\Enfermeria;

use Illuminate\Database\Eloquent\Model;
use App\Models\Farmacia\Medicamento;

class RegistroInyeccionMedicamento extends Model
{
    protected $table = 'enfermeria.registro_inyeccion_medicamento';

    protected $fillable = [
        'id_registro_inyeccion',
        'id_medicamento',
        'observacion',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }
}