<?php

namespace App\Livewire\AlmacenGeneral\Stock;

use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialStock;
use Livewire\Component;
use Livewire\Attributes\On;

class StockAlmacenMain extends Component
{
    public $nombre_material, $codigo_material, $unidad, $descripcion,
        $stockAlmacen;
    public $detallesStock = [];

    public function render()
    {
        return view('livewire.almacen-general.stock.stock-almacen-main');
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
        $material = Material::find($data);
        // dd($medicamento);
        $this->nombre_material = $material->nombre_material;
        $this->codigo_material = $material->codigo;
        $this->unidad = $material->unidad;
        $this->descripcion = $material->descripcion;
        //stock total
        $this->stockAlmacen = MaterialStock::where('id_material', $material->id)
            ->where('id_unidad', $this->obtenerIdAlmacen())
            ->sum('stock_actualizado');
        //  dd($this->stockAlmacen);
        //detalles medicamento Stock
        $this->detallesStock = MaterialStock::where('id_material', $data)
            ->where('id_unidad', $this->obtenerIdAlmacen())
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
    public function obtenerIdAlmacen()
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];*/
        return 19;
    }
}
