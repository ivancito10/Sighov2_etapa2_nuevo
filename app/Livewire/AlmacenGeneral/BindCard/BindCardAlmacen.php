<?php

namespace App\Livewire\AlmacenGeneral\BindCard;

use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Services\ListaUnidades;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class BindCardAlmacen extends Component
{
    #[Validate]
    public $fecha_inicio;
    #[Validate]

    public $fecha_fin;
    #[Validate]

    public $id_material;
    public $materiales = [];
    //----- sin footer
    public $nombre_material, $codigo_material, $unidad, $descripcion,
        $stockAlmacen;
    public $detallesStock = [];

    public function render()
    {
        return view('livewire.almacen-general.bind-card.bind-card-almacen');
    }

    public function rules()
    {
        return [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ];
    }
    #[On('verBinCardAlmacen')]
    public function verBinCardAlmacen($data)
    {
        //dd('entra');
        $this->id_material = $data;
        $this->mostrarSolicitud($data);
        // $this->mostrarSolicitud($data);
        // $this->mostrarSolicitud($data);
        $this->dispatch('abrirBinCardAlmacen');
    }

    public function obtenerIdAlmacen()
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];*/
        return 19;
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

    #[On('generarPDF_BincardAlmacenGeneral')]
    public function generarPDF_BincardAlmacenGeneral()
    {
        $this->validate();

        $unidad = 'ALMACEN GENERAL';
        $url = route('almacen_general.bind-card.pdf', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_material' => $this->id_material,
            'unidad' => $unidad
        ]);
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('generarExcel_BincardAlmacenGeneral')]
    public function generarExcel_BincardAlmacenGeneral()
    {
        $this->validate();

        $unidad = 'ALMACEN GENERAL';
        $url = route('almacen_general.bind-card.excel', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_material' => $this->id_material,
            'unidad' => $unidad
        ]);
        $this->js("window.open('{$url}', '_blank');");
    }
}
