<?php

namespace App\Services\ConsultaExterna;

use App\Models\Administracion\LinkServicio;
use App\Models\Farmacia\MedicamentoStock;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerToken;

class MedicamentosService
{
    protected $dato_farmacia = null;
    protected $dato_almacen = null;

    // protected function ObtenerDatosFarmaciaAlmacen()
    // {   //dd('llega');
    //     $token = session('access_token', '');
    //     $link = LinkServicio::where('nombre_servicio','DATOS FARMACIA Y ALMACEN FARMACIA')->pluck('link_servicio')->first();
    //     $response = Http::withToken($token)
    //         ->get($link);
    //     $datos_farmacia = $response->json('farmacia');
    //     $datos_almacen = $response->json('almacen_farmacia');
    //     $id_farmacia = $datos_farmacia[0]['id'] ?? null;
    //     $id_almacen = $datos_almacen[0]['id'] ?? null;
    //     return [
    //         'id_farmacia' => $id_farmacia,
    //         'id_almacen' => $id_almacen
    //     ];
    // }

    public function stockActual($id_medicamento, $dato_farmacia_alamcen)
    {
        // Solo se llama una vez por ciclo de vida del objeto
        // if ($this->dato_farmacia === null) {
        //     $data = $this->ObtenerDatosFarmaciaAlmacen();
        //     $this->dato_farmacia = $data['id_farmacia'];
        // }
        $stock_farmacia = MedicamentoStock::where('id_medicamento', $id_medicamento)
            ->where('id_unidad', $dato_farmacia_alamcen)
            ->sum('stock_actualizado');
       // dd($stock_farmacia);
        return $stock_farmacia;
    }

    // public function stockAlmacen($id_medicamento){
    //     if ($this->dato_almacen === null) {
    //         $data = $this->ObtenerDatosFarmaciaAlmacen();
    //         $this->dato_almacen = $data['id_almacen'];
    //     }
    //     $stock_almacen = MedicamentoStock::where('id_medicamento',$id_medicamento)
    //             ->where('id_unidad', $this->dato_almacen)
    //             ->sum('stock_actualizado');
    //     //dd($stock_almacen);
    //     return $stock_almacen;
    // }
}
