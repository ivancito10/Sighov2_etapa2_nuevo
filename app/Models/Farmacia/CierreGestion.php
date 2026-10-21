<?php

namespace App\Models\Farmacia;

use App\Models\Farmacia\Medicamento;
use App\Models\Administracion\Unidad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Farmacia/CierreGestion.php
class CierreGestion extends Model
{
    use HasFactory;

    protected $table = 'farmacia.cierre_gestion';

    protected $fillable = [
        'id',
        'cantidad_fisica',
        'cantidad_sistema',
        'fecha_registro',
        'diferencia',
        'fecha_vencimiento',
        'id_unidad',
        'id_medicamento',
        'cantidad_bajas',
        'fecha_vencimiento_baja',
        'id_user_created',
        'id_user_updated',
    ];

    //RELACION CON UNIDAD
    // public function unidadCierreGestion()
    // {
    //     return $this->belongsTo(Unidad::class, 'id_unidad', 'id');
    // }

    //RELACION CON MEDICAMENTO
    public function medicamentoCierreGestion()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id');
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
