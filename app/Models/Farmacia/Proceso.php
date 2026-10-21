<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\TipoProceso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    use HasFactory;

    protected $table = 'farmacia.proceso';

    protected $fillable = [
        'id',
        'id_tipo_proceso',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA TIPO PROCESO
    public function tipoProcesoProceso(){
        return $this->belongsTo(TipoProceso::class, 'id_tipo_proceso', 'id');
    }

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
