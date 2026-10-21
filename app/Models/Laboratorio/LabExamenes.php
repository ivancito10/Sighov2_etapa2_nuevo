<?php

namespace App\Models\Laboratorio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Laboratorio\AdministracionPlazoSolicitudLaboratorio;

class LabExamenes extends Model
{
    use HasFactory;
    protected $table = 'laboratorio.lab_examenes';

    protected $fillable = [
        'id',
        'id_examen_maestro',
        'nombre_examen',
        'descripcion_examen',
        'estado',
        'tipo',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at'
    ];

    public function examenes_hijo()
    {
        return $this->hasmany(LabExamenes::class, 'id_examen_maestro', 'id')->with('AdministracionPlazoSolicitudLaboratorioLabExamenes')->where('estado', '1')->orderby('nombre_examen');
    }
    public function examen_maestro()
    {
        return $this->hasone(LabExamenes::class, 'id', 'id_examen_maestro')->orderby('nombre_examen');
    }

    public function AdministracionPlazoSolicitudLaboratorioLabExamenes()
    {
        return $this->hasOne(AdministracionPlazoSolicitudLaboratorio::class, 'id_lab_examenes', 'id');
    }

    // public function examen_parametro()
    // {
    //     return $this->hasmany(LabEstudioParametro::class, 'id_examen', 'id_examen')->where('estado', '=', 1)->orderby('orden')->with('unidades');
    // }
}
