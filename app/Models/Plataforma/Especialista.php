<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\Administracion\Especialidad;

class Especialista extends Model
{
    use HasFactory;
    protected $table = "plataforma.especialista";
    protected $connection = 'pgsql_bd1';
    protected $fillable = [
        'id',
        'fecha_contrato_inicio',
        'fecha_contrato_fin',
        'foto',
        'permanente',
        'estado',
        'id_especialidad',
        'id_persona',
        'grado_academico',
        'afiliados',
        'estudiantes',
        'convenios',
        'externo',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la tabla Tipo Atencion Asegurado
    
    // Relacion con la tabla Persona
    public function personaEspecialista()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
   

    //Relaciones con la tabla Especialidad
    public function especialidadEspecialista()
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id');
    }

    
    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
    //Relaciones con la tabla AsigancionHorario
    public function asignacionHorarioEspecialista()
    {
        return $this->hasMany(AsignacionHorario::class, 'id_especialista', 'id');
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
}
