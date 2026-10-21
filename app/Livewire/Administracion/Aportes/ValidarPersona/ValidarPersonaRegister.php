<?php

namespace App\Livewire\Administracion\Aportes\ValidarPersona;

use Livewire\Component;
use Livewire\WithFileUploads;
use  App\Imports\Aportes\PersonaImport;
use Maatwebsite\Excel\Facades\Excel;
use BD;
use Illuminate\Support\Facades\DB;

class ValidarPersonaRegister extends Component
{
    use WithFileUploads;
    public $planilla;
    protected $listeners = ['SubirPlanilla'];

    public function SubirPlanilla()
    {
         DB::beginTransaction();
        // try {
        $file = $this->planilla;
        $import = new PersonaImport();
        Excel::import($import, $file);
        $this->dispatch('pg:eventRefresh-ComprobanteAportesInstitucionales');
        // } catch (\Exception $e) {
        // DB::rollBack();
        // if ($e->getMessage() === 'Ya existe una planilla con estas características.') {
        //     $this->dispatch('ErrorYaExistePlanilla');
        // } else {
        //     $this->dispatch('ErrorGeneral');
        // }
        // }
        DB::commit();
    }
    public function render()
    {
        return view('livewire.Administracion.aportes.validar-persona.validar-persona-register');
    }
}
