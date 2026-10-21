<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\Administracion\Unidad;
use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reingreso extends Model
{
    use HasFactory;

    protected $table = 'farmacia.reingreso';

    protected $fillable = [
        'id',
        'cantidad_ingreso',
        'fecha_ingreso',
        'observacion',
        'id_unidad',
        'id_paciente',
        'piso',
        'estado',
        'codigo_reingreso',
        'nro_habitacion',
        'motivo_reingreso',
        'motivo_anulacion',
        'fecha_anulacion',
        'id_user_anula',
        'id_user_created',
        'id_user_updated',
        'tipo_reingreso',
        'farmacia',
    ];

    public function detallesMedicamento()
    {
        return $this->hasMany(ReingresoMedicamento::class, 'id_reingreso', 'id');
    }

    public function paciente()
    {
        return $this->belongsTo(Persona::class, 'id_paciente', 'id');
    }

    //RELACION CON UNIDAD
    // public function unidadReingresoMedicamento(){
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
}
