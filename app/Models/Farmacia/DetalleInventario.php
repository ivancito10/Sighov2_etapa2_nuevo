<?php

namespace App\Models\Farmacia;

use App\Models\User;
use App\Models\Farmacia\HistorialInventario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleInventario extends Model
{
    use HasFactory;

    protected $table = 'farmacia.detalle_inventario';

    protected $fillable = [
        'id',
        'id_historial_inventario',
        'id_persona',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA HISTORIAL INVENTARIO

    public function historialInventarioDetalleInventario()
    {
        return $this->belongsTo(HistorialInventario::class, 'id_historial_inventario', 'id');
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
