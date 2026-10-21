<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\MedicamentosPorVencer;

use Livewire\Component;

class MedicamentosPorAgotarseMain extends Component
{
    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.medicamentos-por-vencer.medicamentos-por-agotarse-main');
    }


    public function abrirListadoMedicamentosPorAgotarse()
    {
        //dd('asdsa');
        $this->dispatch('pg:eventRefresh-MedicamentosPorAgotarseTablee');
        //MedicamentosPorAgotarseTablee
        $this->dispatch('modalVerMedicsAgotados');
    }
}
