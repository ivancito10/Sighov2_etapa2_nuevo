<?php

namespace App\Models\ConsultaExterna;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoBajaMedica extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.tipo_baja';

    protected $fillable = [
        'id',
        'nombre',
        'porcentaje_devolucion', 
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA USUARIO

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
