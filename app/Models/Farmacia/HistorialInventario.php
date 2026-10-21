<?php

namespace App\Models\Farmacia;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInventario extends Model
{
    use HasFactory;

    protected $table = 'farmacia.historial_inventario';

    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'nro_precio',
        'id_unidad',
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
