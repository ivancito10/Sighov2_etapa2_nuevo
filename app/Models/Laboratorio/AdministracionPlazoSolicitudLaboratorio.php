<?php

namespace App\Models\Laboratorio;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministracionPlazoSolicitudLaboratorio extends Model
{
    use HasFactory;
    protected $table = 'laboratorio.administracion_plazo_solicitud_laboratorio';

    protected $fillable = [
        'id',
        'id_lab_examenes',
        'dias_plazo_habilitacion',
        'estado',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at'
    ];

    public function labExamenesAdministracionPlazoSolicitudLaboratorio()
    {
        return $this->belongsTo(LabExamenes::class, 'id_lab_examenes', 'id');
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
