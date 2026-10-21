<?php

namespace App\Models\Farmacia;

use App\Models\Administracion\Unidad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{

    use HasFactory;

    protected $table = 'farmacia.solicitud';

    protected $fillable = [
        'id',
        'cod_solicitud',
        'fecha_solicitud',
        'motivo',
        'descripcion',
        'estado',
        'farmacia',
        'motivo_anulacion',
        'estado_validacion',
        'fecha_aprobacion',
        'fecha_anulacion',
        'fecha_entregado',
        'id_usuario_aprueba',
        'id_usuario_entrega',
        'id_usuario_anula',
        'id_unidad_solicita',
        'id_unidad_recepciona',
        'id_user_created',
        'id_user_updated',
        'cargo',
        'unidad',
        'id_unidad_servicio',
        'piso',
        'nombre_unidad_servicio',
        'nombre_unidad_solicita',
    ];

    protected $casts = [
        'fecha_solicitud'  => 'datetime',
        'fecha_aprobacion' => 'datetime',
        'fecha_anulacion'  => 'datetime',
        'fecha_entregado'  => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    //RELACION CON UNIDAD
    // public function unidadSolicitaSolicitud()
    // {
    //     return $this->belongsTo(Unidad::class, 'id_unidad_solicita', 'id');
    // }

    // public function unidadRecepcionaSolicitud(){
    //     return $this->belongsTo(Unidad::class, 'id_unidad_recepciona', 'id');
    // }   

    //RELACIONES CON LA TABLA SOLICITUD MEDICAMENTO

    public function medicamentoSolicitudSolicitud()
    {
        return $this->hasMany(MedicamentoSolicitud::class, 'id_solicitud', 'id');
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
