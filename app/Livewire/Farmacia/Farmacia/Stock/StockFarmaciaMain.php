<?php

namespace App\Livewire\Farmacia\Farmacia\Stock;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ListaUnidades;

class StockFarmaciaMain extends Component
{
    public $nombre_medicamento, $codigo_medicamento, $forma_farmaceutica, $concentracion,
        $stockFarmaciaReserva, $stockFarmaciaReal;
    public $detallesStock = [];
    public $fecha_inicio, $fecha_fin;
    public $id_medicamento;

    public function render()
    {
        return view('livewire.farmacia.farmacia.stock.stock-farmacia-main');
    }

    #[On('verStockFarmacia')]
    public function verStockFarmacia($data)
    {  // dd('dentra',$data);
        $this->id_medicamento = $data;
        $this->mostrarSolicitud($this->id_medicamento);
        $this->dispatch('verModalStockFarmacia');
    }
    public function mostrarSolicitud($data)
    {
        // dd($data, $this->id_medicamento);
        $medicamento = Medicamento::find($data);
        $this->nombre_medicamento = $medicamento->nombre;
        $this->codigo_medicamento = $medicamento->codigo_medicamento;
        $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
        $this->concentracion = $medicamento->concentracion;
        //stock total
        /*   $this->stockFarmaciaReserva = MedicamentoStock::where('id_medicamento', $data)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->sum('stock_actualizado'); */

        //detalles medicamento Stock
        $this->stockFarmaciaReserva = $this->calcularSaldo($medicamento->id, [true, false]);
        $this->stockFarmaciaReal = $this->calcularSaldo($medicamento->id, true);

        /* $this->stockFarmaciaReserva = 0;
        $this->stockFarmaciaReal = 0; */
        //dd($this->stockFarmaciaReserva, $this->stockFarmaciaReal);
        $this->detallesStock = MedicamentoStock::where('id_medicamento', $data)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        //dd($this->detallesStock);
    }
    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    public function calcularSaldo($id_medicamento, $tipo_stock)
    {
        /*  $suma_ingresos = Movimiento::where('id_medicamento', $id_medicamento)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->where('stock_real', $tipo_stock)
            ->sum('cantidad_ingreso');

        $suma_salidas = Movimiento::where('id_medicamento', $id_medicamento)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->where('stock_real', $tipo_stock)
            ->sum('cantidad_salida');
        $resultado_final = $suma_ingresos - $suma_salidas;
        // dd($suma_ingresos, $suma_salidas,  $resultado_final);
        return $resultado_final; */


        $query = Movimiento::where('id_medicamento', $id_medicamento)
            ->where('id_unidad', 76);

        if (is_array($tipo_stock)) {
            $query->whereIn('stock_real', $tipo_stock);
        } else {
            $query->where('stock_real', $tipo_stock);
        }

        $suma_ingresos = (clone $query)->sum('cantidad_ingreso');
        $suma_salidas = (clone $query)->sum('cantidad_salida');

        return $suma_ingresos - $suma_salidas;
    }

    //--------- modal sin footer
    /* #[On('verBinCard')]
    public function verBinCard($data)
    {
        //dd('entra', $data);
        // $this->mostrarSolicitud($data);
        $this->mostrarSolicitud($data);
        $this->dispatch('binCardModal');
    } */
}
