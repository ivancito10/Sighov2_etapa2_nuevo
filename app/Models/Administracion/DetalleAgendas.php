<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAgendas extends Model
{
    use HasFactory;

    protected $table = 'administracion.detalle_agendas';

    protected $connection = 'pgsql_bd1';

    protected $fillable = [
        'id',
        'matricula_seguro',
        'p_apellido',
        's_apellido',
        'nombres',
        'vigencia_actual',
        'nombre_institucion',
        'especialidad',
        'medico_p_apellido',
        'medico_s_apellido',
        'medico_nombres',
        'fecha_agenda',
        'hora_agenda',
        'created_at',
        'nombre_servicio',
        'usuario_creacion',
        'anulacion_ficha',
        'fecha_anulacion',
        'motivo_anulacion'
    ];
}
