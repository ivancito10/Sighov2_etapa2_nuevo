<?php

namespace App\Livewire\Imagenologia\AgendaEcografia;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Plataforma\AgendaEcografia;

class AgendaEcografiaMain extends Component
{
    public $fecha_hoy;
    public $fechaFiltro;
    public $fue_anulado, $fue_suspendido;
    public $asegurado, $matricula, $institucion, $vigencia, $especialista, $especialidad, $fecha, $hora;
    public $servicio, $usuario_creado, $fecha_creado;
    public function render()
    {
        return view('livewire.imagenologia.agenda-ecografia.agenda-ecografia-main');
    }


    public function filtrarPorFecha()
    {
        $this->dispatch('actualizar_FechaAgendaEcografia', fecha: $this->fechaFiltro);
        $this->fecha_hoy = ($this->fechaFiltro === now()->format('Y-m-d'));
        $this->dispatch('fechaFiltradaEcografia');
    }



    #[On('verAgendaEcografia')]
    public function verAgendaEcografia($data)
    {
        $this->dispatch('abrirModalEcografia');
        $this->detalleAgendaEcografia($data);
    }

    public function detalleAgendaEcografia($dato)
    {
        //dd($dato);
        $agenda = AgendaEcografia::with('personaAgenda', 'servicioPlataformaAgenda', 'suspensionHorarioAgenda', 'usuarioCreador')
            ->find($dato);
        $this->asegurado =  trim("{$agenda->personaAgenda->nombres} {$agenda->personaAgenda->p_apellido} {$agenda->personaAgenda->s_apellido}");
        $this->fecha = $agenda->fecha_agenda;
        $this->hora = $agenda->hora_agenda;
        $this->matricula = $agenda->personaAgenda->matricula_seguro;
        $this->servicio = $agenda->servicioPlataformaAgenda->nombre_servicio;
        $this->usuario_creado = $agenda->usuarioCreador->name;
        $this->fecha_creado = $agenda->created_at;
    }
}
