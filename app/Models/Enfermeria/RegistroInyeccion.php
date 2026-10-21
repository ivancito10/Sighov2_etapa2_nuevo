<?php

namespace App\Models\Enfermeria;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultaExterna\Receta;
use App\Models\Enfermeria\ViaParenteral;

class RegistroInyeccion extends Model
{
    protected $table = 'enfermeria.registro_inyeccion';

    protected $fillable = [
        'id_persona',
        'id_especialista',
        'id_via_parenteral',
        'id_receta',
        'id_receta_manual',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relación Eloquent con la vía parenteral
    public function viaParenteral()
    {
        return $this->belongsTo(ViaParenteral::class, 'id_via_parenteral', 'id');
    }

    // Relación Eloquent con la receta del sistema
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta', 'id');
    }

    public function recetaManual()
    {
        return $this->belongsTo(RecetaManual::class, 'id_receta_manual');
    }

    public function medicamentos()
    {
        return $this->hasMany(RegistroInyeccionMedicamento::class, 'id_registro_inyeccion');
    }

    public function procedimiento()
    {
        return $this->hasOne(RegistroInyeccionProcedimiento::class, 'id_registro_inyeccion');
    }
}