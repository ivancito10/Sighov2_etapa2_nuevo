<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\BindCard;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Http\Controllers\Farmacia\PDFBindcardController;
use App\Models\Farmacia\Medicamento;
use App\Services\ListaUnidades;
use App\Models\Farmacia\MedicamentoStock;
use Carbon\Carbon;

class BindCardAlmacen extends Component
{
    #[Validate]
    public $fecha_inicio;
    #[Validate]

    public $fecha_fin;
    #[Validate]

    public $id_medicamento;
    public $medicamentos = [];
    //----- sin footer
    public $nombre_medicamento, $codigo_medicamento, $forma_farmaceutica, $concentracion,
        $stockAlmacen;
    public $detallesStock = [];
    /*   public function mount()
    {
        $this->medicamentos = Medicamento::where('estado', true)->get();
    } */
    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.bind-card.bind-card-almacen');
    }

    //------ modal sin footer:

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
        $this->id_medicamento = $data;
        $this->mostrarSolicitud($data);
        // $this->mostrarSolicitud($data);
        // $this->mostrarSolicitud($data);
        $this->dispatch('abrirBinCardAlmacen');
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
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

    #[On('generarPDF_BincardFarmacia')]
    public function generarPDF_BincardFarmacia()
    {
        $this->validate();

        $unidad = 'ALMACEN DE FARMACIA';
        $url = route('farmacia.almacenfarmacia.bind-card.pdf', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_medicamento' => $this->id_medicamento,
            'unidad' => $unidad
        ]);
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('generarExcel_BincardFarmaciaAlmacen')]
    public function generarExcel_BincardFarmaciaAlmacen()
    {
        $this->validate();

        $unidad = 'ALMACEN DE FARMACIA';
        $url = route('farmacia.almacenfarmacia.bind-card.excel', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_medicamento' => $this->id_medicamento,
            'unidad' => $unidad
        ]);
        $this->js("window.open('{$url}', '_blank');");
    }
}
