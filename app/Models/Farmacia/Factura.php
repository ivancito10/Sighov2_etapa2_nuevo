<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'farmacia.factura';

    protected $fillable = [
        'id',
        'nro_factura',
        'fecha',
        'id_ingreso',
        'id_institucion_externa',
        'monto_total',
        'nota_remision',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA INGRESO
    public function ingresoFactura()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso', 'id');
    }

    //RELACION CON LA TABLA INSTITICION EXTERNA
    public function institucionExternaFactura()
    {
        return $this->belongsTo(InstitucionExterna::class, 'id_institucion_externa', 'id');
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
