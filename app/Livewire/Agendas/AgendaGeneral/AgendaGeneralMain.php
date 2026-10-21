<?php

namespace App\Livewire\Agendas\AgendaGeneral;

use Livewire\Attributes\On;
use App\Services\ObtenerToken;
use Livewire\Component;
use Illuminate\Support\Carbon;

class AgendaGeneralMain extends Component
{
    //DATOS PERSOALES
    public $nombre_asegurado, $matricula, $edad, $genero;
    public $especialidad, $especialista, $hora_agenda, $fecha_agenda, $tipo_ficha, $creado_por, $asistencia;
    public $ip, $nombre_equipo, $observacion;
    public $tiene_suspension = false, $ficha_anulada = false, $fecha_busqueda, $ficha_anticipada, $ficha_extra;
    public $fecha_anulacion, $motivo_anulacion;
    public $nro_suspension, $fecha_suspension, $observacion_suspension, $suspension_creador, $motivo_suspension;
    public function render()
    {
        //$this->dispatch('modalVerAgendaGeneral');
        return view('livewire.agendas.agenda-general.agenda-general-main');
    }
    public function mount()
    {
        //$token = new ObtenerToken();
        //$token->obtenerToken();
        //dd($token->obtenerToken());
        $this->fecha_busqueda = now()->format('Y-m-d');
    }

    #[On('verAgendaGeneral')]
    public function verAgendaGeneral($data)
    {
        $this->dispatch('modalVerAgendaGeneral');
        //dd('asdsd', $data);
        $this->mostrarAgenda($data);
    }
    public function mostrarAgenda($data)
    {
        $this->resetearDatos();
        if ($data) {
            $this->nombre_asegurado = $data['nombre_asegurado'];
            $this->edad = $data['edad'];
            $this->asistencia = $data['asistencia'];
            if ($data['genero'] == 'F') {
                $this->genero = 'FEMENINO';
            } elseif ($data['genero'] == 'M') {
                $this->genero = 'MASCULINO';
            } else {
                $this->genero = ' SIN DATO';
            }
            $this->hora_agenda = $data['hora_agenda'];
            $this->fecha_agenda = $data['fecha_agenda'];
            $this->tipo_ficha = $data['tipo_ficha'];
            $this->especialidad =  $data['especialidad'] = (isset($data['asignacion_horario_agenda']['especialista_detalles']['especialidad_especialista']['especialidad'])
                ? $data['asignacion_horario_agenda']['especialista_detalles']['especialidad_especialista']['especialidad']
                : 'SIN DATOS');
            $this->especialista = $data['especialista'] = (isset($data['asignacion_horario_agenda']['especialista_detalles']['persona'])
                ? $data['asignacion_horario_agenda']['especialista_detalles']['persona']['p_apellido'] . ' ' . $data['asignacion_horario_agenda']['especialista_detalles']['persona']['s_apellido'] . ' ' . $data['asignacion_horario_agenda']['especialista_detalles']['persona']['nombres']
                : 'SIN DATOS');
            $this->creado_por = (isset($data['usuario_creador']['name']) ? $data['usuario_creador']['name'] : 'SIN DATO');
            $this->ip = $data['ip'];
            $this->nombre_equipo = $data['nombre_equipo'] ?: 'SIN DATO ';
            $this->observacion = $data['observacion'] ?: 'SIN OBSERVACIONES';
            $this->ficha_anticipada = $data['ficha_anticipada'];
            $this->ficha_extra = $data['ficha_extra'];
            if ($data['anulacion_ficha']) {
                $this->ficha_anulada = true;
                $this->fecha_anulacion =  Carbon::parse($data['fecha_anulacion'])->format('d/m/Y');
                $this->motivo_anulacion = $data['motivo_anulacion'];
            }
            if ($data['suspension_horario_agenda']) {
                $this->tiene_suspension = true;
                $this->fecha_suspension =  Carbon::parse($data['suspension_horario_agenda']['fecha_suspension'])->format('d/m/Y');
                $this->nro_suspension = $data['suspension_horario_agenda']['nro_suspension'];
                $this->observacion_suspension = $data['suspension_horario_agenda']['observacion'] ?: 'SIN DATO';
                $this->motivo_suspension = $data['suspension_horario_agenda']['motivo_suspension_horario']['nombre_motivo'];
                $this->suspension_creador = $data['suspension_horario_agenda']['usuario_creador']['name'];
            }
        }
    }
    public function resetearDatos()
    {
        $this->reset([
            'nombre_asegurado',
            'edad',
            'asistencia',
            'genero',
            'hora_agenda',
            'fecha_agenda',
            'tipo_ficha',
            'especialidad',
            'especialista',
            'creado_por',
            'ip',
            'nombre_equipo',
            'observacion',
            'ficha_anticipada',
            'ficha_extra',
            'ficha_anulada',
            'fecha_anulacion',
            'motivo_anulacion',
            'tiene_suspension',
            'fecha_suspension',
            'nro_suspension',
            'observacion_suspension',
            'motivo_suspension',
            'suspension_creador'
        ]);
    }
    public function busquedaFechaAgendado()
    {
        $this->dispatch('mandandoFechaATable', $this->fecha_busqueda);
    }
}
