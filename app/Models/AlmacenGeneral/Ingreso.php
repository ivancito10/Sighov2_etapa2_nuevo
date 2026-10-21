<?php

namespace App\Models\AlmacenGeneral;

use App\Models\Farmacia\InstitucionExterna;
use App\Models\Farmacia\Proceso;
use App\Models\Farmacia\TipoProceso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;
    protected $table = 'almacen_general.ingreso';

    protected $fillable = [
        'id',
        'nro_lote',
        'id_institucion_externa',
        'responsables',
        'codigo_ingreso',
        'codigo_cuce',
        'fecha_contrato',
        'fecha_recepcion',
        'hora_recepcion',
        'monto_total',
        'estado',
        'codigo_asmi',
        'nro_resolucion_memorandum',
        'nro_orden_compra_servicio',
        'objeto_recepcion',
        'id_unidad_recepciona',
        'estado_validacion',
        'id_tipo_proceso',
        'id_user_contabiliza',
        'fecha_contabiliza',
        'id_user_valida',
        'fecha_valida',
        'fecha_ingreso',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA INSTITUCION EXTERNA

    public function institucionExternaIngreso(){
        return $this->belongsTo(InstitucionExterna::class, 'id_institucion_externa', 'id');
    }

    //RELACION CON LA TABLA PROCESO

    public function procesoIngreso()
    {
        return $this->belongsTo(TipoProceso::class, 'id_tipo_proceso', 'id');
    }

    //RELACION CON LA TABLA FACTURAS

    public function primeraFacturaIngreso()
    {
        return $this->hasOne(Factura::class, 'id_ingreso')
            ->where('estado', true)->orderBy('id', 'asc');
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

    public function proveedorCumple()
    {
        return $this->hasOne(ProveedorCumple::class, 'id_ingreso', 'id');
    }
}
