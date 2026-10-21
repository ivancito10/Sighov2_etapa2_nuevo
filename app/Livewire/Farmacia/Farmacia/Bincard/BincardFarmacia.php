<?php

namespace App\Livewire\Farmacia\Farmacia\Bincard;

use App\Exports\BindcardExport;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Http\Controllers\Farmacia\PDFBindcardController;
use App\Models\Farmacia\MedicamentoStock;
use App\Services\ListaUnidades;
use App\Models\Farmacia\Medicamento;
use Maatwebsite\Excel\Facades\Excel;

class BincardFarmacia extends Component
{
    #[Validate]
    public $fecha_inicio;
    #[Validate]

    public $fecha_fin;
    #[Validate]


    public $id_medicamento;
    public $medicamentos = [];
    // ----------------------
    public $nombre_medicamento, $codigo_medicamento, $forma_farmaceutica, $concentracion,
        $stockFarmacia;
    public $detallesStock = [];

    /*   public function mount()
    {
        $this->medicamentos = Medicamento::where('estado', true)->get();
    } */
    public function render()
    {
        return view('livewire.farmacia.farmacia.bincard.bincard-farmacia');
    }

    public function rules()
    {
        return [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ];
    }
    #[On('generarPDF_BincardFarmacia')]
    public function generarPDF_BincardFarmacia()
    {
        $this->validate();
        $unidad = 'FARMACIA';
        $url = route('farmacia.farmacia.bind-card.pdf', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_medicamento' => $this->id_medicamento,
            'unidad' => $unidad
        ]);
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('generarExcel_BincardFarmacia')]
    public function generarExcel_BincardFarmacia()
    {
        $this->validate();
        
        $unidad = 'FARMACIA';
        $url = route('farmacia.farmacia.bind-card.excel', [
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'id_medicamento' => $this->id_medicamento,
            'unidad' => $unidad
        ]);
        $this->js("window.open('{$url}', '_blank');");
    }

    // #[On('verPDF')]
    // public function verPDF($rowId)
    // {
    //     //dd($rowId);
    //     $this->js('window.open("/solicitudinsumos/' . $rowId . '", "_blank")');
    // }

    // #[On('medicamentoSeleccionado')]
    // public function medicamentoSeleccionado()
    // {
    //     if ($this->id_unidad_recepciona != null) {
    //         if ($this->id_medicamento != null || $this->id_medicamento != '') {
    //             $nombreMed = Medicamento::where('id', $this->id_medicamento)->first();
    //             $this->nombreMedicamento = $nombreMed->nombre;
    //             $this->formaFarmaceuticaMostrar = $nombreMed->forma_farmaceutica;
    //             $this->stockMed = MedicamentoStock::where('id_medicamento', $nombreMed->id)
    //                 ->where('id_unidad', $this->id_unidad_recepciona)
    //                 ->sum('stock_actualizado');
    //             if ($this->stockMed == 0) {
    //                 $this->dispatch('refreshSelect2');
    //                 $this->dispatch('cantidadNoDisponible');
    //                 $this->id_medicamento = null;
    //                 $this->nombreMedicamento = null;
    //                 $this->formaFarmaceuticaMostrar = null;
    //                 $this->stockMed = null;
    //             }
    //         } else {
    //             $this->nombreMedicamento = null;
    //             $this->formaFarmaceuticaMostrar = null;
    //             $this->stockMed = null;
    //             $this->id_medicamento = null;
    //             $this->dispatch('refreshSelect2');
    //         }
    //     } else {
    //         $this->id_medicamento = null;
    //         $this->dispatch('refreshSelect2');
    //         $this->dispatch('seleccioneUnidad');
    //     }
    // }

    //------------- modal sin footer
    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    #[On('verBinCard')]
    public function verBinCard($data)
    {
        $this->id_medicamento = $data;
        $this->mostrarSolicitud($this->id_medicamento);
        //$this->mostrarProveedor($this->id_medicamento);
        // AQUI LLAMAR AL MODAL DEL SCRIPT
        $this->dispatch('abrirModalBinCard');
    }

    public function mostrarSolicitud($data)
    {
        //dd($data, $this->id_medicamento);
        $medicamento = Medicamento::find($data);
        $this->nombre_medicamento = $medicamento->nombre;
        $this->codigo_medicamento = $medicamento->codigo_medicamento;
        $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
        $this->concentracion = $medicamento->concentracion;
        //stock total
        $this->stockFarmacia = MedicamentoStock::where('id_medicamento', $medicamento->id)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->sum('stock_actualizado');
        //detalles medicamento Stock
        $this->detallesStock = MedicamentoStock::where('id_medicamento', $data)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        //dd($this->detallesStock);
    }
}
