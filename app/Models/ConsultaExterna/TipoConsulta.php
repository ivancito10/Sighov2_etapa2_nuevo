<?php

namespace App\Models\ConsultaExterna;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoConsulta extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.tipo_consulta';

    protected $fillable = [
        'id',
        'nombre', 
        'estado',
        'max_med_receta',
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
