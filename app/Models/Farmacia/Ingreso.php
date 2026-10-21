<?php

namespace App\Models\Farmacia;

use App\Models\Administracion\Residencia;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\Farmacia\Proceso;
use App\Models\Farmacia\DetalleIngreso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'farmacia.ingreso';

    protected $fillable = [
        'id',
        'cod_ingreso',
        'fecha_ingreso',
        'fecha_contrato',
        'fecha_recepcion',
        'hora_recepcion',
        'nro_lote',
        'cod_asmi',
        'monto_total',
        'responsables',
        'cod_cuce',
        'estado_validacion',
        'estado',
        'id_institucion_externa',
        'id_tipo_proceso',
        'id_user_contabiliza',
        'fecha_contabiliza',
        'id_user_created',
        'id_user_updated',
        'id_user_valida',
        'fecha_valida',
    ];


    // ESTE ES INGRESO DE MEDICAMENTOS

    public function detalleIngresoIngreso()
    {
        return $this->hasMany(DetalleIngreso::class, 'id_ingreso', 'id');
    }

    //RELACION CON LA TABLA INSTITUCION EXTERNA}
    public function institucionExternaIngreso()
    {
        return $this->belongsTo(InstitucionExterna::class, 'id_institucion_externa', 'id');
    }

    //RELACION POR EL MOMENTO CON FACTURA
    public function facturaIngreso()
    {
        return $this->hasMany(Factura::class, 'id_ingreso', 'id');
    }

    public function facturasActivas()
    {
        return $this->hasMany(Factura::class, 'id_ingreso')
            ->where('estado', true);
    }

    public function primeraFacturaIngreso()
    {
        return $this->hasOne(Factura::class, 'id_ingreso')
            ->where('estado', true)->orderBy('id', 'asc');
    }

    //RELACION CON LA TABLA PROCESO
    public function procesoIngreso()
    {
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
