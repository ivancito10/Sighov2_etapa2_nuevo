<?php

namespace App\Models\Laboratorio;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministracionVistaLaboratorio extends Model
{
    use HasFactory;

    protected $table = 'laboratorio.administracion_vista_laboratorio';

    protected $fillable = [
        'id',
        'id_user_especialista',
        'casillas_checks',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at'
    ];

    //RELACION CON LA TABLA HISTORIA CLINICA
    public function userAdministracionVistaLaboratorio(){
        return $this->belongsTo(User::class, 'id_user_especialista', 'id');
    }

    //RELACION CON LA TABLA USUARIO
    public function usuarioCreador(){
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor(){
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
