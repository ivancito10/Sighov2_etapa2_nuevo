<?php

namespace App\Livewire\Liquidaciones\Agendas;

use App\Models\Administracion\DetalleAgendas;
use App\Models\Plataforma\Agenda;

use Livewire\Component;
use Livewire\Attributes\On;


class AgendasMain extends Component
{
    public function render()
    {
        return view('livewire.liquidaciones.agendas.agendas-main');
    }

    #[On('detalleAgendas')]
    public function detalleAgendas($data)
    {
        //dd($data);
        $this->mostrarAgenda($data);
        $this->dispatch('abrirAgendaLiquidacion');
    }
    public $asegurado, $especialista, $matricula, $vigencia, $institucion, $especialidad;
    public $fecha, $hora, $servicio, $usuario_creado, $fecha_creado;
    public $fue_anulado, $fecha_anulacion, $motivo_anulacion;
    public $fue_suspendido = false, $fecha_suspension, $detalle_suspension;
    public function mostrarAgenda($id_agenda)
    {
        $agenda = DetalleAgendas::where('id', $id_agenda)
            ->first();
        // dd($agenda);
        $this->asegurado = $agenda->nombres . ' ' . $agenda->p_apellido . ' ' . $agenda->s_apellido;
        $this->matricula = $agenda->matricula_seguro;
        $this->vigencia = $agenda->vigencia_actual;
        $this->institucion = $agenda->nombre_institucion;
        $this->especialidad = $agenda->especialidad;
        $this->especialista = $agenda->medico_nombres . ' ' . $agenda->medico_p_apellido . ' ' . $agenda->medico_s_apellido;
        $this->fecha = $agenda->fecha_agenda;
        $this->hora = $agenda->hora_agenda;
        $this->servicio = $agenda->nombre_servicio;
        $this->usuario_creado = $agenda->usuario_creacion;
        $this->fecha_creado = $agenda->created_at;
        $this->fue_anulado = $agenda->anulacion_ficha;
        //$info_suspension = 

        //dd($this->fue_suspendido, $this->fecha_suspension, $this->detalle_suspension);
        // dd( $agenda->id_suspension_horario,$this->fue_suspendido);
        // dd($this->fue_anulado);
        $this->fecha_anulacion = $agenda->fecha_anulacion;
        $this->motivo_anulacion = $agenda->motivo_anulacion;
        // DESPUES HICE LOS MODELOS para este punto
        $agenda = Agenda::with('suspensionHorarioAgenda')->find($id_agenda);
        // dd($agenda);
        if ($agenda->id_suspension_horario != null) {
            $this->fue_suspendido = true;
            $this->fecha_suspension = $agenda->suspensionHorarioAgenda->fecha_suspension;
            $this->detalle_suspension = $agenda->suspensionHorarioAgenda->detalle_suspension;
        }
        // dd($agenda);


        // dd($info_suspension);

    }

    public function abrirModalReportesAgendas()
    {
        $this->dispatch('modalLiquidacionAgendasReportes');
    }
}
