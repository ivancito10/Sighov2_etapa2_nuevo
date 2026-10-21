<?php

namespace App\Livewire\AlmacenGeneral\CierreGestion;

use Livewire\Component;

class CierreGestionRegister extends Component
{
    public $idMat, $cantidad_fisica, $diferencia, $fecha_vencimiento;
    public $cod_material, $material, $unidad, $descripcion,
        $nombre_completo, $carnet, $unidadSolicitante, $cargoSolicitante,
        $stock_actual;

    public $hoy;

    public function render()
    {
        return view('livewire.almacen-general.cierre-gestion.cierre-gestion-register');
    }

    public function lista_material_almacen_general()
    {
        $this->dispatch('listar_material');
    }
}
