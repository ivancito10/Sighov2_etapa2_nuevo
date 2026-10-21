<?php

namespace App\Models\Laboratorio;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudExamen extends Model
{
    use HasFactory;

    protected $table = 'laboratorio.solicitud_examen';

    protected $fillable = [
        'id',
        'id_examen', 
        'id_solicitudes',
        'nombre_examen',
        'grupo',
        'tipo_laboratorio',
        'estado',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at'
    ];

    
    //RELACION CON LA TABLA USUARIO

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }
}
