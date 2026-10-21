<?php

namespace App\Models\ConsultaExterna;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\Estudio;

class ImagenologiaEstudio extends Model
{
    use HasFactory; 

    protected $table = 'consulta_externa.imagenologia_estudio';

    protected $fillable = [
        'id',
        'id_sol_imagenologia',
        'id_estudio',
        'id_user_created', 
        'id_user_updated',
        'created_at',
        'updated_at',
    ];

    //RELACION CON LA TABLA TEstudios

    public function datosEstudio(){
        return $this->belongsTo(Estudio::class, 'id_estudio', 'id');
    }

    public function datosImagenologia(){
        return $this->belongsTo(Imagenologia::class, 'id_sol_imagenologia', 'id');
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
