<?php

namespace App\Models\AlmacenGeneral;

use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\AlmacenGeneral\MaterialSolicitud;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'almacen_general.solicitud';

    protected $fillable = [
        'id',
        'cod_solicitud',
        'fecha_solicitud',
        'motivo',
        'descripcion',
        'estado',
        'fecha_aprobacion',
        'id_usuario_aprueba',
        'id_unidad_solicita',
        'id_unidad_recepciona',
        'motivo_anulacion',
        'estado_validacion',
        'fecha_anulacion',
        'id_usuario_anula',
        'fecha_entregado',
        'id_usuario_entrega',
        'id_unidad_servicio',
        'id_user_revisado',
        'fecha_revisado',
        'piso',
        'nombre_unidad_servicio',
        'nombre_unidad_solicita',
        'id_user_created',
        'id_user_updated',
        
    ];

    //RELACIONES CON LA TABLA SOLICITUD MEDICAMENTO

    public function materialSolicitudSolicitud()
    {
        return $this->hasMany(MaterialSolicitud::class, 'id_solicitud', 'id');
    }

    //RELACION CON LA TABLA USUARIO

    public function usuarioApruebaSolicitud()
    {
        return $this->belongsTo(User::class, 'id_usuario_aprueba', 'id');
    }

    public function usuarioAnulaSolicitud()
    {
        return $this->belongsTo(User::class, 'id_usuario_anula', 'id');
    }

    public function usuarioEntregaSolicitud()
    {
        return $this->belongsTo(User::class, 'id_usuario_entrega', 'id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
