<?php

namespace App\Models\Etapa3\Planificacion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'planificacion.unidad';
    protected $connection = 'pgsql_bd2';
    protected $fillable = [
        'id',
        'nombre',
        'sigla',
        'id_unidad_dependiente',
        'id_personal_ssu',
        'estado',
        'objetivo',
        'id_user_created',
        'id_user_updated',
        'created_at',
        'updated_at',
        'id_estado',
        'id_user_aprueba',
        'fecha_aprobacion',
        'id_user_valida',
        'fecha_validacion',
        'productos_esperados',
    ];


    /*public function funcionesMOFUnidad()
    {
        return $this->hasMany(FuncionesMOFUnidad::class, 'id_unidad','id');
    }

    public function productosEsperados()
    {
        return $this->hasMany(ProdEsperadoUnid::class, 'id_unidad','id');
    }

    public function personalSSU()
    {
        return $this->hasOne(PersonalSSU::class, 'id_unidad','id');
    }*/

    // Relación con unidad dependiente
    public function unidadDependiente()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad_dependiente','id');
    }
    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
    //Relaciones con la Tabla Usuario
    public function usuarioValida()
    {
        return $this->belongsTo(User::class, 'id_user_valida', 'id');
    }

    public function usuarioAprueba()
    {
        return $this->belongsTo(User::class, 'id_user_aprueba', 'id');
    }
}
