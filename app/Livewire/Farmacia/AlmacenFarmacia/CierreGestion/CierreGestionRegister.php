<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\CierreGestion;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Movimiento;
use App\Services\ObtenerDatosPersonalSsu;
use App\Models\Administracion\Persona;
use App\Services\ListaUnidades;
use App\Models\Farmacia\CierreGestion;

use App\Models\Farmacia\MedicamentoStock;
use Carbon\Carbon;

class CierreGestionRegister extends Component
{
    public $idMed, $cantidad_fisica, $diferencia, $fecha_vencimiento;
    public $cod_medicamento, $medicamento, $forma_farmaceutica, $concentracion,
        $nombre_completo, $carnet, $unidadSolicitante, $cargoSolicitante,
        $stock_actual;
    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-register');
    }
    public $hoy;

    public function lista_medicamento_almacen()
    {
        $this->dispatch('listar_medicamento');
    }
}
