<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Imagen extends Model
{
    use HasFactory;
    protected $table = "administracion.imagen";
    protected $connection = "pgsql_bd1";
    protected $fillable = [
        'id',
        'nombre_imagen',
        'descripcion',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    public function usuarioCreadorImagen()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditorImagen()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

}
