<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\Stock;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoStock;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ListaUnidades;

class StockAlmacenMain extends Component
{
    public $nombre_medicamento, $codigo_medicamento, $forma_farmaceutica, $concentracion,
        $stockAlmacen;
    public $detallesStock = [];

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.stock.stock-almacen-main');
    }

    #[On('verStockAlmacen')]
    public function verStockAlmacen($data)
    {   //dd('dentra',$data);
        $this->mostrarSolicitud($data);
        $this->dispatch('verModalStockAlmacen');
    }
    public function mostrarSolicitud($data)
    {
        // dd($data);
        $medicamento = Medicamento::find($data);
        // dd($medicamento);
        $this->nombre_medicamento = $medicamento->nombre;
        $this->codigo_medicamento = $medicamento->codigo_medicamento;
        $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
        $this->concentracion = $medicamento->concentracion;
        //stock total
        $this->stockAlmacen = MedicamentoStock::where('id_medicamento', $medicamento->id)
            ->where('id_unidad', $this->obtenerIdAlmacen())
            ->sum('stock_actualizado');
        //  dd($this->stockAlmacen);
        //detalles medicamento Stock
        $this->detallesStock = MedicamentoStock::where('id_medicamento', $data)
            ->where('id_unidad', $this->obtenerIdAlmacen())
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    //----modal sin fttoe
    /* #[On('verBinCardAlmacen')]
    public function verBinCardAlmacen($data)
    {
        dd('entra', $data);
        $this->mostrarSolicitud($data);
        $this->mostrarSolicitud($data);
        $this->dispatch('binCardModal');
    } */
}
