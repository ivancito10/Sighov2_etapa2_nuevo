<?php

namespace App\Models\AlmacenGeneral;

use App\Models\Farmacia\InstitucionExterna;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\EstadoValidacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.movimiento';

    protected $fillable = [
        'id',
        'id_corresponde',
        'id_identificador_tabla',
        'dato_solicitante',
        'nro_documento',
        'fecha',
        'dato_interesado',
        'cantidad_ingreso',
        'cantidad_salida',
        'saldo',
        'estado_validacion',
        'id_material',
        'id_unidad',
        'cierre_gestion',
        'observacion',
        'nro_lote',
        'precio_unitario_peps',
        'precio_unitario_ueps',
        'precio_unitario_cpp',
        'id_estado_validacion',
        'fecha_vencimiento',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON LA TABLA INSTITUCION EXTERNA 

    public function movimientoInstitucionExterna()
    {
        return $this->belongsTo(InstitucionExterna::class, 'id_institucion_externa', 'id');
    }

    //RELACION CON LA TABLA MATERIAL
    public function movimientoMaterial()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }

    //RELACION CON LA TABLA ESTADO VALIDACION
    public function movimientoEstadoValidacion()
    {
        return $this->belongsTo(EstadoValidacion::class, 'id_estado_validacion', 'id');
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
