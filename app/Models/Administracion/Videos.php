<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Videos extends Model
{
    use HasFactory;
    protected $table = "administracion.videos";
    protected $connection = "pgsql_bd1";
    protected $fillable = [
        'id',
        'nombre',
        'titulo',
        'descripcion',
        'ruta',
        'permanente',
        'fecha_inicio',
        'fecha_fin',
        'externo',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
