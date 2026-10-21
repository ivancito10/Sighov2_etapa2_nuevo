<?php

namespace App\Models\AlmacenGeneral;

use App\Models\User;
use App\Models\AlmacenGeneral\Ingreso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.factura';

    protected $fillable = [
        'id',
        'id_ingreso',
        'nro_factura',
        'fecha',
        'monto_total',
        'estado',
        'nota_remision',
        'id_institucion_externa',
        'id_user_created',
        'id_user_updated'
    ];

    //RELACION CON LA TABLA INGRESO

    public function ingresoFactura()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso', 'id');
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
