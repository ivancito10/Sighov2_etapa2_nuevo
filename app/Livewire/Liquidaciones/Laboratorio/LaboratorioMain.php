<?php

namespace App\Livewire\Liquidaciones\Laboratorio;

use App\Models\Laboratorio\Solicitudes;
use App\Models\Laboratorio\SolicitudExamen;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Services\LaboratorioResultados;


class LaboratorioMain extends Component
{
    public function render()
    {
        return view('livewire.liquidaciones.laboratorio.laboratorio-main');
    }

    #[On('detalleLaboratorio')]
    public function detalleLaboratorio($data)
    {
        $this->mostrarLaboratorios($data);
        $this->dispatch('abrirLaboratorioLiquidacion');
    }
    public $asegurado, $matricula, $especialista, $fecha_solicitud, $hora_solicitud, $tipo_consulta;
    public $solicitudes_examen;
    public function mostrarLaboratorios($id_solcitud)
    {
        $solicitudes = Solicitudes::with([
            'RelacionHistoriaClinica',
            'tipoConsultaSolicitudes',
            'usuarioCreador',
            'examenesSolicitud',
            'aseguradoSolicitudesLaboratorio',
            'solicitanteSolicitudesLaboratorio'
        ])
            ->where('id', $id_solcitud)
            ->first();
        $this->asegurado = $solicitudes->aseguradoSolicitudesLaboratorio->nombres . ' ' . $solicitudes->aseguradoSolicitudesLaboratorio->p_apellido . ' ' . $solicitudes->aseguradoSolicitudesLaboratorio->s_apellido;
        $this->matricula = $solicitudes->aseguradoSolicitudesLaboratorio->matricula_seguro;
        $this->especialista =   $solicitudes->solicitanteSolicitudesLaboratorio->nombres . ' ' . $solicitudes->solicitanteSolicitudesLaboratorio->p_apellido . ' ' . $solicitudes->solicitanteSolicitudesLaboratorio->s_apellido;
        $this->tipo_consulta = $solicitudes->tipoConsultaSolicitudes->nombre;
        $this->fecha_solicitud = $solicitudes->RelacionHistoriaClinica->fecha_hora;
        $this->hora_solicitud = $solicitudes->RelacionHistoriaClinica->fecha_hora;
        $this->solicitudes_examen = SolicitudExamen::where('id_solicitudes', $solicitudes->id)
            ->get()->map(function ($sol) {
                return [
                    'nombre_examen' => $sol->nombre_examen,
                    'tipo_lab' => $sol->tipo_laboratorio,
                ];
            })
            ->toArray();
        // dd($this->solicitudes_examen);
    }

    #[On('ver_resultado')]
    public function ver_resultado($data)
    {
        $laboratorio = new LaboratorioResultados();
        $datos = $laboratorio->datosLaboratorioResultados($data);
        if ($datos) {
            $this->js('window.open("/laboratorio/pdf/resultados/' . $data . '", "_blank")');
        } else {
            $this->dispatch('noHayResultado');
        }
    }
}
