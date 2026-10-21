<?php

namespace App\Livewire\AtencionMedica\EspecialidadVariable;

use App\Models\ConsultaExterna\EspecialidadVariable;
use App\Services\EspecialidadService;
use Livewire\Attributes\On;
use Livewire\Component;

class EspecialidadVariableMain extends Component
{
    public $id_especialidad;

    public $nombre_especialidad;

    public $verEspVariables = [];

    public function render()
    {
        return view('livewire.atencion-medica.especialidad-variable.especialidad-variable-main');
    }

    #[On('registrarEspVariables')]
    public function registrarEspVariables($data)
    {
        $this->id_especialidad = $data;
        $this->dispatch('especialidadSeleccionada', [
            'id' => $this->id_especialidad,
        ]);

        $this->dispatch('modalRegistrarVariables');
    }

    #[On('verEspVariables')]
    public function verEspVariables($data)
    {
        $this->id_especialidad = $data;
        $service = new EspecialidadService();
        $especialidades = $service->obtenerEspecialidades();
        $this->nombre_especialidad = $especialidades->get($data);

        $this->verEspVariables = EspecialidadVariable::where('id_especialidad', $data)->get();
        //dd($this->verEspVariables);

        $this->dispatch('modalAbrirEspVar');
    }
}
