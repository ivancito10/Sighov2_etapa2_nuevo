<?php

namespace App\Models\ConsultaExterna;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaltaAtraso extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.falta_atraso';

    protected $fillable = [
        'id',
        'detalle_falta',
        'falta',
        'id_agenda',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA AGENDA
    //SE HARA CON SERVICIOS

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
