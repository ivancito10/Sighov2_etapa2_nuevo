<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\MedicamentosPorVencer;

use Livewire\Component;

class MedicamentosPorVencerMain extends Component
{
    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.medicamentos-por-vencer.medicamentos-por-vencer-main');
    }

    public function abrirListadoMedicamentosPorVencer()
    {
        //dd('asdsa');
        $this->dispatch('modalVerMedicamentoVenciendo');
    }
}
