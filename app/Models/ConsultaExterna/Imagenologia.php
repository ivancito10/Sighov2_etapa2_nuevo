<?php

namespace App\Models\ConsultaExterna;

use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagenologia extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.sol_imagenologia';

    protected $fillable = [
        'id',
        'nro_solicitud',
        'indicaciones',
        'id_tipo',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
        'id_persona',
        'resultado',
        'informe_estado',
    ];

    public function imagenologiaEstudio()
    {
        return $this->belongsToMany(
            Estudio::class,
            'consulta_externa.imagenologia_estudio',
            'id_sol_imagenologia',
            'id_estudio'
        )
            ->withPivot([
                'id_user_created',
                'id_user_updated',
                'created_at',
                'updated_at',
            ])
            ->withTimestamps();
    }

    public function datosPacientePersona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function tipoImagenologia()
    {
        return $this->belongsTo(TipoImagenologia::class, 'id_tipo', 'id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }
}
