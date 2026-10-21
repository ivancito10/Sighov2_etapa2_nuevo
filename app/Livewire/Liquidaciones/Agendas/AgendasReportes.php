<?php

namespace App\Livewire\Liquidaciones\Agendas;

use App\Models\Aportes\Institucion;
use App\Models\Vigencia\SeguroInterior;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;


class AgendasReportes extends Component
{
    #[Validate]
    public $tipo_asegurado, $instituciones = [], $segurosInterior = [];
    public $mostrarListado = false, $esEstudiante = false;
    #[Validate]
    public $fecha_inicio, $fecha_fin, $id_dato;

    public function render()
    {
        return view('livewire.liquidaciones.agendas.agendas-reportes');
    }

    public function updatedTipoAsegurado()
    {
        if ($this->tipo_asegurado == 'TITULAR' || $this->tipo_asegurado == 'BENEFICIARIO') {
            $this->mostrarListado =  true;
            $this->instituciones = Institucion::where('estado', true)
                ->where('nombre', '!=', 'UNIVERSIDAD MAYOR DE SAN ANDRES')
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('nombre', 'like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%')
                            ->where('nombre', 'not like', '%-%');
                    })
                        ->orWhere('nombre', 'not like', 'UNIVERSIDAD CATOLICA BOLIVIANA SAN PABLO%');
                })
                // ->with(['codigosInstitucion', 'claseAportanteInstitucion', 'institucionResidencia'])
                ->orderBy('nombre', 'asc')
                ->get();
            //dd($instituciones);
        } elseif ($this->tipo_asegurado == 'CONVENIO') {
            $this->mostrarListado =  false;
            $this->segurosInterior = SeguroInterior::orderBy('nombre_seguro', 'asc')->get();
        } else {
            $this->esEstudiante = true;
            $this->mostrarListado =  false;
        }
    }

    public function resetVariables()
    {
        $this->reset(
            'tipo_asegurado',
            'mostrarListado',
            'id_dato',
            'fecha_inicio',
            'fecha_fin',
            'esEstudiante'
        );
    }

    public function rules()
    {
        return [
            'tipo_asegurado' => 'required',
            'id_dato' => 'required_if:esEstudiante,false',
            'fecha_inicio' => 'required|date| before_or_equal:fecha_fin',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ];
    }


    #[On('descargarExcelAgendasLiquidacion')]
    public function descargarExcelAgendasLiquidacion()
    {
        $this->validate();
        if ($this->tipo_asegurado == 'ESTUDIANTES') {
            $id_instituciones = 0;
        } else {
            $id_instituciones = $this->id_dato;
        }
        //dd($this->fecha_inicio, $this->fecha_fin, $this->tipo_asegurado, $id_instituciones);
        $url = route('pdf.generateReporteAgendas', [
            'tipo_asegurado' => $this->tipo_asegurado,
            'id_institucion' => $id_instituciones,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);
        $this->js("window.open('{$url}', '_blank');");
        $this->dispatch('reporteGeneradoExito');
        $this->dispatch('cerrarmodalLiquidacionAgendasReportes');

        $this->resetVariables();
    }
}
