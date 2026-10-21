<?php

namespace App\Models\Farmacia;

use App\Models\Administracion\LinkServicio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'farmacia.medicamento';
    //protected $connection = 'pgsql';

    protected $fillable = [
        'id',
        'codigo_medicamento',
        'nombre',
        'forma_farmaceutica',
        'restringido',
        'concentracion',
        'estado',
        'insumo',
        'cantidad_maxima',
        'cantidad_minima',
        'id_cuenta_contable',
        'id_user_created',
        'id_user_updated',
        'stock_minimo_almacen',
        'cuenta_contable',
        'habilitado_farmacia'
    ];

    //RELACIONES CON USUARIO CREADOR Y EDITOR
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    public function detalleIngresoMedicamento()
    {
        return $this->hasMany(DetalleIngreso::class, 'id_medicamento', 'id');
    }

    public function stockActual()
    {
        return $this->hasMany(MedicamentoStock::class, 'id_medicamento')
            ->sum('stock_actualizado');
    }

    public function stockActualFarmacia()
    {
        // return $this->hasMany(MedicamentoStock::class, 'id_medicamento')
        //     ->whereHas('unidadMedicamentoStock', function($q){
        //         $q->where('nombre', 'FARMACIA');
        //     });

        $token = session('access_token', '');
        $link = LinkServicio::where('nombre_servicio', 'DATOS FARMACIA Y ALMACEN FARMACIA')->pluck('link_servicio')->first();
        $response = Http::withToken($token)
            ->get($link);
        $datos_farmacia = $response->json('farmacia');
        $id_farmacia = $datos_farmacia[0]['id'] ?? null;

        //dd($id_farmacia);
        return $this->hasMany(MedicamentoStock::class, 'id_medicamento')
            ->where('id_unidad', $id_farmacia);

        // return $this->hasMany(MedicamentoStock::class, 'id_medicamento')
        //         ->where('id_unidad', 76);
    }

    public function getStockActualFarmaciaAttribute()
    {
        return $this->stockActualFarmacia()
            ->sum('stock_actualizado');
    }
}
