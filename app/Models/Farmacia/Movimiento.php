<?php

namespace App\Models\Farmacia;

use App\Models\Administracion\Unidad;
use App\Models\ConsultaExterna\EstadoValidacion;
use App\Models\ConsultaExterna\Receta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $table = 'farmacia.movimiento';

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
        'cierre_gestion',
        'estado_validacion',
        'id_estado_validacion',
        'precio_unitario_peps',
        'precio_unitario_ueps',
        'precio_unitario_cpp',
        'observacion',
        'id_medicamento',
        'id_unidad',
        'nro_lote',
        'id_user_created',
        'id_user_updated',
        'fecha_vencimiento',
        'stock_real',
    ];

    //RELACION CON MEDICAMENTO
    public function medicamentoMovimiento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
    }

    //RELACION CON ESTADO VALIDACION
    public function estadoValidacionMovimiento()
    {
        return $this->belongsTo(EstadoValidacion::class, 'id_estado_validacion', 'id');
    }

    //RELACION CON UNIDAD
    // public function unidadMovimiento(){
    //     return $this->belongsTo(Unidad::class, 'id_unidad', 'id');
    // }

    //RELACION CON LA TABLA USUARIO

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    // RELACIÓN CON RECETA
    public function receta()
    {
        return $this->belongsTo(
            Receta::class,
            'id_corresponde', // FK en movimiento
            'id'              // PK en receta
        );
    }
}
