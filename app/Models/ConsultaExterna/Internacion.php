<?php

namespace App\Models\ConsultaExterna;

use App\Models\User;
use App\Models\ConsultaExterna\Consulta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internacion extends Model
{
    use HasFactory;

    protected $table = 'consulta_externa.internacion';

    protected $fillable = [
        'id',
        'nro_internacion',
        'fecha_internacion',
        'indicaciones',
        'estado',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
        'id_persona',
    ];

    //RELACION CON LA TABLA CONSULTA

    public function consultaInternacion(){
        return $this->belongsTo(Consulta::class, 'id_consulta', 'id');
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
