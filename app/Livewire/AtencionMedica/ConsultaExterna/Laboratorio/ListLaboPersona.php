<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Laboratorio;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\LaboratorioResultados;

class ListLaboPersona extends Component
{
    public $id_persona; 

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.laboratorio.list-labo-persona');
    }
    #[On('enviar-id-persona-laboratorio')]
    public function recibirIdPersona($id)
    {
        $this->id_persona = $id; 
    }

    #[On('ver_laboratorio_pdf')]
    public function ver_laboratorio_pdf($solicitud)
    { 
        $id_solicitud = $solicitud['id']; 
        $this->js('window.open("/laboratorio/pdf/resultados/' . $id_solicitud . '", "_blank", "width=800,height=1000,scrollbars=yes,resizable=yes");');

    } 
    
}
